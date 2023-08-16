import os
import subprocess
from flask import Flask, request, jsonify

app = Flask(__name__)
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

@app.route('/restart_process', methods=['POST'])
def restart_process():
    kill_sensor()
    start_sensor()
    return jsonify(message='Process restarted')

@app.route('/check_sensor', methods=['GET'])
def check_process_sensor():
    global process_sensor
    if process_sensor and process_sensor.poll() is None:
        return jsonify(status='running')
    else:
        return jsonify(status='not running')

@app.route('/')
def home():
    return 'Hello World!'

if __name__ == '__main__':
    app.run(debug=True)
