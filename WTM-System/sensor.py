import time
import RPi.GPIO as GPIO
import smbus2 as smbus
import socket
import mysql.connector
import logging
import json

logging.basicConfig(filename='sensor.log', level=logging.DEBUG, format='%(asctime)s - %(levelname)s - %(message)s')

bus = smbus.SMBus(1)
TRIG = 4
ECHO = 17

max_distance = 0
min_distance = 0
tank_capacity_liters = 0

LCD_ADDR = 0x27
LCD_CHR = 1
LCD_CMD = 0
LCD_WIDTH = 16
LCD_LINE_1 = 0x80
LCD_LINE_2 = 0xC0
LCD_BACKLIGHT_ON = 0x08
LCD_BACKLIGHT_OFF = 0x00

# HASH'J PROGRAMMING DB GET DATA START
db = mysql.connector.connect(
host="localhost",
user="root",
password="hash",
database="wtms"
)

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
    try:
        message = message.ljust(LCD_WIDTH, " ")
        if line == 1:
            lcd_byte(LCD_LINE_1, LCD_CMD)
        elif line == 2:
            lcd_byte(LCD_LINE_2, LCD_CMD)
        for i in range(LCD_WIDTH):
            lcd_byte(ord(message[i]), LCD_CHR)
    except Exception as e:
        logging.error(f"Error displaying on LCD: {e}")
    

def lcd_init():
    lcd_byte(0x33, LCD_CMD)
    lcd_byte(0x32, LCD_CMD)
    lcd_byte(0x06, LCD_CMD)
    lcd_byte(0x0C, LCD_CMD)
    lcd_byte(0x28, LCD_CMD)
    lcd_byte(0x01, LCD_CMD)
    time.sleep(0.0005)

def get_ip_address():
    try:
       with socket.socket(socket.AF_INET, socket.SOCK_DGRAM) as sock:
            sock.connect(("192.168.8.1", 80))
            local_ip_address = sock.getsockname()[0]
            return local_ip_address
    except Exception as e:
        logging.error(f"Error getting ip address: {e}")
    
    
def distance():
    try:
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
        return round(during * 340 / 2 * 100, 1)
    except Exception as e:
        logging.error(f"Error getting distance: {e}")
        return None
    

def calculate_percentage(distance_cm):
    try:
        percentage = ((max_distance - distance_cm) / (max_distance - min_distance)) * 100
        return round(percentage, 1)
    except Exception as e:
        logging.error(f"Error calculating percentage: {e}")
        return None
        

def calculate_liters(percentage):
    try:
        # return round((percentage / 100) * tank_capacity_liters, 2)
        return round((percentage / 100) * tank_capacity_liters, 1)
    except Exception as e:
        logging.error(f"Error calculating liters: {e}")
        return None
    

def set_settings():
    try:
        global max_distance, min_distance, tank_capacity_liters
        cursor = db.cursor()
        sql_settings = "SELECT high_threshold, low_threshold FROM settings WHERE id = 1"
        cursor.execute(sql_settings)
        settings_result = cursor.fetchone()
        if settings_result:
            max_distance, min_distance = settings_result
        sql_tank_status = "SELECT liters FROM water_tank WHERE status = 'Activated'"
        cursor.execute(sql_tank_status)
        tank_status_result = cursor.fetchone()
        if tank_status_result:
            tank_capacity_liters = tank_status_result[0]
        cursor.close()
    except Exception as e:
        logging.error(f"Error set settings: {e}")

def save_data(distance, percentage, liters):
    try:
        sql = "INSERT INTO water_data (distance, level, liters) VALUES (%s, %s, %s)"
        values = (distance, percentage, liters)
        cursor = db.cursor()
        cursor.execute(sql, values)
        cursor.close()
    except Exception as e:
        logging.error(f"Error saving data: {e}")

def update_data(distance, percentage, liters):
    try:
        # Read existing data from the JSON file
        with open('sensor.json', 'r') as data_file:
            existing_data = json.load(data_file)
        
        # Update the specific fields
        existing_data['distance'] = distance
        existing_data['level'] = percentage
        existing_data['liters'] = liters
        
        # Write the updated data back to the JSON file
        with open('sensor.json', 'w') as data_file:
            json.dump(existing_data, data_file)
    except Exception as e:
        logging.error(f"Error saving data: {e}")


def monitor():
    while True:
        set_settings()
        distance_cm = distance()
        water_percentage = calculate_percentage(distance_cm)
        water_liters = calculate_liters(water_percentage)
        lcd_string(f"WATER LEVEL:{round(water_percentage)}%", 1)
        lcd_string(f'IP:{get_ip_address()}', 2) 
        update_data(distance_cm, water_percentage, water_liters)
        if time.localtime().tm_min % 10 == 0 and time.localtime().tm_sec == 0:
            save_data(distance_cm, water_percentage, water_liters)
        time.sleep(1)
try:
    setup()
    lcd_init()
    monitor()
    print("LCD initialized")
    print("Sensor started")
except Exception as e:
    logging.error(f"Error running the sensor app: {e}")
 
    