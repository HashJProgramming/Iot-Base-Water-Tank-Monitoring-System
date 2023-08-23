import time
import RPi.GPIO as GPIO
import smbus2 as smbus
import socket
import mysql.connector
try:

    bus = smbus.SMBus(1)
    TRIG_PIN = 4
    ECHO_PIN = 17

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
    cursor = db.cursor()
    sql_settings = "SELECT high_threshold, low_threshold FROM settings WHERE id = 1"
    cursor.execute(sql_settings)
    settings_result = cursor.fetchone()
    
    if settings_result:
        max_distance, min_distance = settings_result
    # print(settings_result)
    # print(f"max{max_distance}")
    # print(f"min{min_distance}")
    
    sql_tank_status = "SELECT liters FROM water_tank WHERE status = 'active'"
    cursor.execute(sql_tank_status)
    tank_status_result = cursor.fetchone()
    # print(tank_status_result)   
    if tank_status_result:
        tank_capacity_liters = tank_status_result[0]
    # print(tank_capacity_liters)
    # HASH'J PROGRAMMING DB GET DATA END
    
    def get_ip_address():
        with socket.socket(socket.AF_INET, socket.SOCK_DGRAM) as sock:
            sock.connect(("192.168.8.1", 80))
            local_ip_address = sock.getsockname()[0]
        return local_ip_address

    def setup():
        GPIO.setmode(GPIO.BCM)
        GPIO.setup(TRIG_PIN, GPIO.OUT)
        GPIO.setup(ECHO_PIN, GPIO.IN)
        GPIO.output(TRIG_PIN, False)
        time.sleep(2)
        print("sensor ready")

    def calculate_distance():
        GPIO.output(TRIG_PIN, True)
        time.sleep(0.00001)
        GPIO.output(TRIG_PIN, False)

        pulse_start = time.time()
        pulse_end = time.time()

        while GPIO.input(ECHO_PIN) == 0:
            pulse_start = time.time()

        while GPIO.input(ECHO_PIN) == 1:
            pulse_end = time.time()

        pulse_duration = pulse_end - pulse_start
        speed_of_sound = 34300 
        distance_cm = (pulse_duration * speed_of_sound) / 2

        return distance_cm

    def calculate_percentage(distance_cm):
        if max_distance == min_distance:
            return 100  # Handle division by zero
        percentage = ((max_distance - distance_cm) / (max_distance - min_distance)) * 100
        if percentage < 0:
            percentage = 0
        return round(percentage)

    def calculate_liters(percentage):
        return round((percentage / 100) * tank_capacity_liters, 2)

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
        
    lcd_init()
    setup()
    while True:
        distance_cm = calculate_distance()
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
            
        lcd_string(f'IP:{get_ip_address()}', 2) 
        lcd_string(f"WATER LEVEL:{water_percentage}%", 1)
        time.sleep(.5)
except KeyboardInterrupt:
    # Clean up GPIO pins when the script is interrupted
    GPIO.cleanup()
    cursor.close()
    db.close()
    