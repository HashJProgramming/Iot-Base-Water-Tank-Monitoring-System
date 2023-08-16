
from flask import Flask, render_template, session, jsonify
from flask_socketio import SocketIO, emit
from flask_cors import CORS
import subprocess
import os, time, random
import signal
import RPi.GPIO as GPIO

app = Flask(__name__)
socketio = SocketIO(app)
CORS(app)
app.config['SECRET_KEY'] = 'TEAMLEARNINGSESSION'
process = None

tank_capacity_liters = 18.9
TRIG_PIN = 4
ECHO_PIN = 17

def setup():
    GPIO.setmode(GPIO.BCM)
    GPIO.setup(TRIG_PIN, GPIO.OUT)
    GPIO.setup(ECHO_PIN, GPIO.IN)
    GPIO.output(TRIG_PIN, False)
    print("Waiting for the sensor to settle")
    time.sleep(2)
    print("Ready")

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
    max_distance = 40
    min_distance = 4
    percentage = ((max_distance - distance_cm) / (max_distance - min_distance) ) * 100
    if percentage < 0:
        percentage = 0
    return percentage

def calculate_liters(percentage):
    return round((percentage / 100) * tank_capacity_liters, 2)

@socketio.on('connect')
def handle_connect():
    print('Client connected')

@socketio.on('disconnect')
def handle_disconnect():
    print('Client disconnected')

@app.route("/")
def home():
    return render_template("index.html")

@app.route("/dashboard")
def dashboard():
    return render_template("dashboard.html")

@app.route("/start")
def start_script():
    global process
    if process is None:
        process = subprocess.Popen(["venv/bin/python", "sensor.py"])
        return "Sensor Online"
    else:
        return "Sensor is already running."

@app.route("/stop")
def stop_script():
    global process
    if process is None:
        return "Sensor is not running."
    else:
        os.kill(process.pid, signal.SIGINT)
        process = None
        return "Sensor Offline"

@app.route("/status")
def status():
   if process is None:
        return "Offline"
   else:
        return "Online"

@app.route('/water-level')
def water_level():
    distance_cm = calculate_distance()
    water_percentage = calculate_percentage(distance_cm)
    water_liters = calculate_liters(water_percentage)
    
    if water_percentage < 0:
        water_percentage = 0
        water_liters = 0
    elif water_percentage >= 100:
        water_percentage = 100
        
    data = {
    'distance': distance_cm,
    'percentage': water_percentage,
    'liters': water_liters
    }
    
    socketio.emit('sensor-data', data)
    return jsonify({
        'water_level_percent': round(water_percentage, 2),
        'remaining_volume': round(water_liters, 2)
    })



if __name__ == "__main__":
    setup()
    socketio.run(app, host='localhost', port=3000)
