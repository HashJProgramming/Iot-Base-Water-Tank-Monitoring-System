import os
import subprocess
import psutil
from flask_cors import CORS
from flask import Flask, request, jsonify

app = Flask(__name__)
CORS(app)
process_sensor = None
process_sensor_data = None

current_path = os.path.dirname(os.path.abspath(__file__))

@app.route('/start_sensor', methods=['POST', 'GET'])
def start_sensor():
    global process_sensor
    if process_sensor is None or process_sensor.poll() is not None:
        process_sensor = subprocess.Popen(['python', f'{current_path}/sensor.py'])
        return jsonify(message='Sensor process started')
    else:
        return jsonify(message='Sensor process is already running')

@app.route('/kill_sensor', methods=['POST', 'GET'])
def kill_sensor():
    global process_sensor
    if process_sensor and process_sensor.poll() is None:
        process_sensor.terminate()
        return jsonify(message='Sensor process terminated')
    else:
        return jsonify(message='Sensor process is not running')

@app.route('/restart_sensor', methods=['POST','GET'])
def restart_process():
    kill_sensor()
    start_sensor()
    return jsonify(message='Process restarted')

@app.route('/check_sensor', methods=['GET'])
def check_process_sensor():
    global process_sensor
    if process_sensor and process_sensor.poll() is None:
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
    
@app.route('/')
def home():
    return 'Hello World!'

if __name__ == '__main__':
    app.run(debug=True, host='0.0.0.0')
