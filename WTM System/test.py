import socketio

# Create a Socket.IO client instance
sio = socketio.Client()

# Define a handler for the 'connect' event
@sio.event
def connect():
    print('Connected to server')

# Define a handler for a custom event 'data_event'
@sio.on('data')
def handle_data(data):
    print('Received data:', data)

# Connect to the Socket.IO server
sio.connect('http://127.0.0.1:5000')

# Wait for events
sio.wait()
