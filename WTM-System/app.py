import time
from flask import Flask, jsonify
from flask_cors import CORS
import RPi.GPIO as GPIO
import smbus2 as smbus
import mysql.connector
import subprocess
import json
import psutil
import shlex

app = Flask(__name__)
CORS(app)
cors = CORS(app, resources={r"/*": {"origins": "*"}})
data_path = '/var/www/html/WTMS/WTM-System/sensor.json'

@app.route('/WTMS/')
def index():
    return jsonify({"distance": "none"})  

@app.route('/WTMS/start', methods=['GET'])
def start():
    return jsonify(status='Sensor Started')
    
@app.route('/WTMS/restart', methods=['GET'])
def restart():
    try:
        command = 'sudo systemctl restart wtms_sensor_app.service'
        subprocess.run(shlex.split(command), check=True)
        time.sleep(1)
        return jsonify(status='200', message='Sensor Restarted')
    except subprocess.CalledProcessError as e:
        return jsonify(status='500', message=f'Failed to restart sensor: {e}')

@app.route('/WTMS/check_sensor', methods=['GET'])
def check_sensor():
    return jsonify(status='Running')
  
@app.route('/WTMS/get_system_stats')
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

@app.route('/WTMS/logs', methods=['GET'])
def logs():
    with open('sensor.log', 'r') as log_file:
            log_contents = log_file.read()
    return jsonify(status='200', message=log_contents)
  
  
@app.route('/WTMS/stats', methods=['GET'])
def stats():
    with open(data_path, 'r') as data_file:
        sensor_data = json.load(data_file)
        return jsonify(sensor_data)
    
@app.route('/WTMS/clear', methods=['GET'])
def clear_log():
    with open('sensor.log', 'w') as log_file:
        pass    
    return jsonify(status='200', message='Log Cleared')

if __name__ == '__main__':
    app.run(debug=True, host='0.0.0.0')
