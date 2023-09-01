import time
from flask import Flask, jsonify
from flask_cors import CORS
import subprocess
import json
import psutil
import shlex

app = Flask(__name__)
CORS(app)
cors = CORS(app, resources={r"/*": {"origins": "*"}})
data_path = '/home/hash/Desktop/WTMS/WTM-System/sensor.json'
logs_path = '/home/hash/Desktop/WTMS/WTM-System/sensor.log'
@app.route('/wtms/api/')
def index():
    return jsonify({"distance": "none"})  

@app.route('/wtms/api/start', methods=['GET'])
def start():
    return jsonify(status='Sensor Started')
    
@app.route('/wtms/api/restart', methods=['GET'])
def restart():
    try:
        command = 'sudo systemctl restart wtms_sensor_app.service'
        subprocess.run(shlex.split(command), check=True)
        time.sleep(1)
        return jsonify(status='200', message='Sensor Restarted')
    except subprocess.CalledProcessError as e:
        return jsonify(status='500', message=f'Failed to restart sensor: {e}')

@app.route('/wtms/api/check_sensor', methods=['GET'])
def check_sensor():
    return jsonify(status='Running')
  
@app.route('/wtms/api/get_system_stats')
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

@app.route('/wtms/api/logs', methods=['GET'])
def logs():
    with open(logs_path, 'r') as log_file:
            log_contents = log_file.read()
    return jsonify(status='200', message=log_contents)
  
  
@app.route('/wtms/api/stats', methods=['GET'])
def stats():
    with open(data_path, 'r') as data_file:
        sensor_data = json.load(data_file)
        return jsonify(sensor_data)
    
@app.route('/wtms/api/clear', methods=['GET'])
def clear_log():
    with open('/var/www/html/wtms/api/WTM-System/sensor.log', 'w') as log_file:
        pass    
    return jsonify(status='200', message='Log Cleared')

if __name__ == '__main__':
    app.run(debug=True, host='0.0.0.0')
