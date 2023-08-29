import time
from flask import Flask, jsonify
from flask_cors import CORS
import RPi.GPIO as GPIO
import smbus2 as smbus
import mysql.connector

app = Flask(__name__)
CORS(app)
cors = CORS(app, resources={r"/*": {"origins": "*"}})


@app.route('/WTMS/')
def index():
    return jsonify({"distance": "none"})  

@app.route('/WTMS/start', methods=['GET'])
def start():
    return jsonify(status='Sensor Started')
    
@app.route('/WTMS/restart', methods=['GET'])
def restart():
    time.sleep(1)
    return jsonify(status='200', message='Sensor Restarted')

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
    return jsonify()
 
    
        
if __name__ == '__main__':
    app.run(debug=True, host='0.0.0.0')
