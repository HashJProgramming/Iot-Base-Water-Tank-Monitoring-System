import time
from flask import Flask, jsonify
from flask_cors import CORS
import RPi.GPIO as GPIO
import smbus2 as smbus
import socket
import psutil
import threading
import mysql.connector
import os

app = Flask(__name__)
CORS(app)
cors = CORS(app, resources={r"/*": {"origins": "*"}})

bus = smbus.SMBus(1)
TRIG = 4
ECHO = 17

max_distance = 100
min_distance = 10
tank_capacity_liters = 0

LCD_ADDR = 0x27
LCD_CHR = 1
LCD_CMD = 0
LCD_WIDTH = 16
LCD_LINE_1 = 0x80
LCD_LINE_2 = 0xC0
LCD_BACKLIGHT_ON = 0x08
LCD_BACKLIGHT_OFF = 0x00

monitor_thread = None
running = False

# HASH'J PROGRAMMING DB GET DATA START
db = mysql.connector.connect(
host="localhost",
user="root",
password="hash",
database="wtms"
)
cursor = db.cursor()

def setup():
    GPIO.setmode(GPIO.BCM)
    GPIO.setup(TRIG, GPIO.OUT)
    GPIO.setup(ECHO, GPIO.IN)
    GPIO.output(TRIG, False)

def lcd_byte(bits, mode):
    bits_high = mode | (bits & 0xF0) | LCD_BACKLIGHT_ON
    bits_low = mode | ((bits << 4) & 0xF0) | LCD_BACKLIGHT_ON

    bus.write_byte(LCD_ADDR, bits_high)
    lcd_toggle_enable(bits_high)

    bus.write_byte(LCD_ADDR, bits_low)
    lcd_toggle_enable(bits_low)

def lcd_toggle_enable(bits):
    time.sleep(0.0005)
    bus.write_byte(LCD_ADDR, (bits | 0x04))
    time.sleep(0.0005)
    bus.write_byte(LCD_ADDR, (bits & ~0x04))
    time.sleep(0.0005)

def lcd_string(message, line):
    message = message.ljust(LCD_WIDTH, " ")

    if line == 1:
        lcd_byte(LCD_LINE_1, LCD_CMD)
    elif line == 2:
        lcd_byte(LCD_LINE_2, LCD_CMD)

    for i in range(LCD_WIDTH):
        lcd_byte(ord(message[i]), LCD_CHR)

def lcd_init():
    lcd_byte(0x33, LCD_CMD)
    lcd_byte(0x32, LCD_CMD)
    lcd_byte(0x06, LCD_CMD)
    lcd_byte(0x0C, LCD_CMD)
    lcd_byte(0x28, LCD_CMD)
    lcd_byte(0x01, LCD_CMD)
    time.sleep(0.0005)

def get_ip_address():
    with socket.socket(socket.AF_INET, socket.SOCK_DGRAM) as sock:
        sock.connect(("192.168.8.1", 80))
        local_ip_address = sock.getsockname()[0]
    return local_ip_address
    
def distance():
    GPIO.output(TRIG, 0)
    time.sleep(0.000002)
    GPIO.output(TRIG, 1)
    time.sleep(0.00001)
    GPIO.output(TRIG, 0)
    while GPIO.input(ECHO) == 0:
        pass
    time1 = time.time()
    while GPIO.input(ECHO) == 1:
        pass
    time2 = time.time()
    during = time2 - time1
    return during * 340 / 2 * 100

def calculate_percentage(distance_cm):
        if max_distance == min_distance:
            return 100  # Handle division by zero
        percentage = ((max_distance - distance_cm) / (max_distance - min_distance)) * 100
        if percentage < 0:
            percentage = 0
        return round(percentage)

def calculate_liters(percentage):
    return round((percentage / 100) * tank_capacity_liters, 2)

def set_settings():
    global max_distance, min_distance, tank_capacity_liters
    sql_settings = "SELECT high_threshold, low_threshold FROM settings WHERE id = 1"
    cursor.execute(sql_settings)
    settings_result = cursor.fetchone()

    if settings_result:
        max_distance, min_distance = settings_result

    sql_tank_status = "SELECT liters FROM water_tank WHERE status = 'active'"
    cursor.execute(sql_tank_status)
    tank_status_result = cursor.fetchone()
    # print(tank_status_result)   
    if tank_status_result:
        tank_capacity_liters = tank_status_result[0]
    # print(tank_capacity_liters)

def save_data(distance, percentage, liters):
    sql = "INSERT INTO water_data (distance, level, liters) VALUES (%s, %s, %s)"
    values = (distance, percentage, liters)
    cursor.execute(sql, values)
    db.commit()

def monitor():
    set_settings()
    while running:
        distance_cm = distance()
        water_percentage = calculate_percentage(distance_cm)
        water_liters = calculate_liters(water_percentage)
        sql = "INSERT INTO water_data (distance, level, liters) VALUES (%s, %s, %s)"
        values = (distance_cm, water_percentage, water_liters)
        cursor.execute(sql, values)
        db.commit()
        
        if water_percentage < 0:
            water_percentage = 0
            water_liters = 0
        elif water_percentage >= 100:
            water_percentage = 100
        save_data(distance_cm, water_percentage, water_liters)
        lcd_string(f'IP:{get_ip_address()}', 2) 
        lcd_string(f"WATER LEVEL:{water_percentage}%", 1)
        time.sleep(0.5)

@app.route('/')
def index():
    dis = distance()
    percent = calculate_percentage(dis)
    lcd_string(f'IP:{get_ip_address()}', 2) 
    lcd_string(f"WATER LEVEL:{percent}%", 1)
    return jsonify({"distance": dis, "percent": percent})  # Return distance as JSON

@app.route('/start', methods=['POST', 'GET'])
def start_monitor():
    global monitor_thread, running
    if monitor_thread is None or not monitor_thread.is_alive():
        running = True
        monitor_thread = threading.Thread(target=monitor)
        monitor_thread.start()
        return jsonify(message='Monitoring started')
    else:
        return jsonify(message='Monitoring is already running')

@app.route('/stop', methods=['POST', 'GET'])
def stop_monitor():
    global running
    if monitor_thread and monitor_thread.is_alive():
        running = False
        monitor_thread.join()
        return jsonify(message='Monitoring stopped')
    else:
        return jsonify(message='Monitoring is not running')

@app.route('/restart', methods=['POST', 'GET'])
def restart_monitor():
    stop_monitor()
    start_monitor()
    return jsonify(message='Monitoring restarted')

@app.route('/check_sensor', methods=['GET'])
def check_sensor():
    global running
    if running:
        return jsonify(status='Running')
    else:
        return jsonify(status='Not running')

@app.route('/get_system_stats')
def get_system_stats():
    cpu_percent = psutil.cpu_percent()
    memory_info = psutil.virtual_memory()
    disk_usage = psutil.disk_usage('/')

    return {
        'cpu_percent': cpu_percent,
        'memory_percent': memory_info.percent,
        'memory_total': memory_info.total,
        'memory_used': memory_info.used,
        'disk_total': disk_usage.total,
        'disk_used': disk_usage.used,
        'disk_percent': disk_usage.percent,
    }


if __name__ == '__main__':
    setup()
    lcd_init()
    app.run(debug=True, host='0.0.0.0')
