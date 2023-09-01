# WTMS - Water Tank Monitoring System

<h3 align="left">Languages and Tools:</h3>
<p align="left"> 
 <img src="https://www.vectorlogo.zone/logos/git-scm/git-scm-icon.svg" alt="git" width="40" height="40"/> <img src="https://raw.githubusercontent.com/devicons/devicon/master/icons/javascript/javascript-original.svg" alt="javascript" width="40" height="40"/> <img src="https://raw.githubusercontent.com/devicons/devicon/master/icons/linux/linux-original.svg" alt="linux" width="40" height="40"/> <img src="https://raw.githubusercontent.com/devicons/devicon/master/icons/mysql/mysql-original-wordmark.svg" alt="mysql" width="40" height="40"/>  <img src="https://raw.githubusercontent.com/devicons/devicon/master/icons/nginx/nginx-original.svg" alt="nginx" width="40" height="40"/> <img src="https://raw.githubusercontent.com/devicons/devicon/master/icons/php/php-original.svg" alt="php" width="40" height="40"/> <img src="https://raw.githubusercontent.com/devicons/devicon/master/icons/python/python-original.svg" alt="python" width="40" height="40"/> </p>

## Software & Hardware Requirements

| Software                                  | Hardware                                                      |
|-------------------------------------------|---------------------------------------------------------------|
| Raspbery Pi OS 64bit                      | Raspberry Pi 3 B+                                             |
| PHP Version 7.4.33                        | USB CardReader                                                |
| MySQL Version 10.5.19                     | DC to DC MicroUSB Buck-Converter 6-24V to 5V 3A               |
| nginx Version 1.18.0                      | Heatsink                                                      |
| phpMyAdmin Version 5.0.4                  | Ultrasonic Sensor  HC-SR04                                    |
| Python Version 3.9                        | Printed Circuit Boards                                        |
|   - blinker Version 1.6.2                 | Jumper Wires Male to Male                                     |
|   - click Version 8.1.7                   | Jumper Wires Male to female                                   |
|   - flask Version 2.3.3                   | ALITOVE 12V 5A 60W                                            |
|   - Flask-Cors Version 4.0.0              | MicroUSB                                                      |
|   - importlib-metadata Version 6.8.0      | Resistor 470 OHMS                                             |
|   - itsdangerous Version 2.1.2            | Risistor 330 OHMS                                             |
|   - Jinja2 Version 3.1.2                  | SDCard 16GB or Higher                                         |
|   - MarkupSafe Version 2.1.3              | 16x2 LCD Display White on Blue                                |
|   - mysql-connector-python Version 8.1.0  | PowerBank                                                     |
|   - protobuf Version 4.21.12              | YX850 Power FailureAutomatic SwitchingStandby Battery         |
|   - psutil Version 5.9.5                  | IP65 Enclosure 240x160x92mm ABS                               |
|   - RPi.GPIO Version 0.7.1                | Power Switch                                                  |
|   - smbus2 Version 0.4.2                  | Digital voltmeter display                                     |
|   - werkzeug Version 2.3.7                | 24AWG Tinned Copper Stranded Hook up Wire                     |
|   - zipp Version 3.16.2                   | CPU Fan                                                       |

 
 
 
 
## Installation
```bash
Linux:
sudo apt update
sudo apt install nginx
sudo apt install mariadb-server
sudo mysql_secure_installation
sudo apt install php-fpm php-mysql
sudo apt install phpmyadmin

ls -l /etc/nginx/conf.d/phpmyadmin.conf
sudo nano /etc/nginx/sites-available/default


Git Clone:
git clone https://github.com/HashJProgramming/WTMS

Python 3.9:
python -m venv venv
pip install -r <requirements path>

```

## Nginx Configuration - site-available/default
```nginx
server {
    listen 80 default_server;
    listen [::]:80 default_server;
    root /var/www/html;
    index index.php index.html index.htm index.nginx-debian.html;
    server_name _;

    location / {
        try_files $uri $uri/ =404;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php7.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location /phpmyadmin {
        alias /usr/share/phpmyadmin;
        index index.php;
    }

    location ~ ^/phpmyadmin/(.+\.php)$ {
        alias /usr/share/phpmyadmin/$1;
        fastcgi_pass unix:/run/php/php7.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME /usr/share/phpmyadmin/$1;
        include fastcgi_params;
    }

    location /wtms {
        alias /home/<username>/Desktop/WTMS; # Replace with the actual path
        index index.php;
        location ~ \.php$ {
            include snippets/fastcgi-php.conf;
            fastcgi_pass unix:/run/php/php7.4-fpm.sock;
            fastcgi_param SCRIPT_FILENAME $request_filename;
            include fastcgi_params;
        }
    }
     location /wtms/api {
         proxy_pass http://127.0.0.1:5000;
     }
}
```

## WTMS_sensor.service

#### Service
```
[Unit]
Description=My WTMS Sensor App
After=network.target

[Service]
Type=simple
User=hash
WorkingDirectory=/home/hash/Desktop/WTMS
ExecStart=/home/hash/Desktop/WTMS/venv/bin/python3.9 /home/hash/Desktop/WTMS/WTM-System/sensor.py
Restart=always

[Install]
WantedBy=multi-user.target
```
#### Command
```bash
Edit/Create config:
sudo nano /etc/systemd/system/WTMS_sensor.service

sudo systemctl daemon-reload
sudo systemctl enable WTMS_sensor.service
sudo systemctl start WTMS_sensor.service

Other:
sudo systemctl status WTMS_sensor.service
sudo systemctl stop WTMS_sensor.service
sudo systemctl restart WTMS_sensor.service

```


## WTMS_api.service
#### Service
```
[Unit]
Description=My WTMS API App
After=network.target

[Service]
Type=simple
User=hash
WorkingDirectory=/home/hash/Desktop/WTMS
ExecStart=/home/hash/Desktop/WTMS/venv/bin/python3.9 /home/hash/Desktop/WTMS/WTM-System/app.py
Restart=always

[Install]
WantedBy=multi-user.target
```
#### Command
```
Edit/Create config:
sudo nano /etc/systemd/system/WTMS_api.service

sudo systemctl daemon-reload
sudo systemctl enable WTMS_api.service
sudo systemctl start WTMS_sensor.service

Other:
sudo systemctl status WTMS_api.service
sudo systemctl stop WTMS_api.service
sudo systemctl restart WTMS_api.service
```

##  LICENSE
```
                                            MIT License

                                Copyright (c) 2023 Joshua Ambalong

            Permission is hereby granted, free of charge, to any person obtaining a copy
            of this software and associated documentation files (the "Software"), to deal
            in the Software without restriction, including without limitation the rights
            to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
            copies of the Software, and to permit persons to whom the Software is
            furnished to do so, subject to the following conditions:

            The above copyright notice and this permission notice shall be included in all
            copies or substantial portions of the Software.

            THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
            IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
            FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
            AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
            LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
            OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
            SOFTWARE.

```