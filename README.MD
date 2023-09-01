# WTMS - Water Tank Monitoring System

<h3 align="left">Languages and Tools:</h3>
<p align="left"> <a href="https://git-scm.com/" target="_blank" rel="noreferrer"> <img src="https://www.vectorlogo.zone/logos/git-scm/git-scm-icon.svg" alt="git" width="40" height="40"/> </a> <a href="https://developer.mozilla.org/en-US/docs/Web/JavaScript" target="_blank" rel="noreferrer"> <img src="https://raw.githubusercontent.com/devicons/devicon/master/icons/javascript/javascript-original.svg" alt="javascript" width="40" height="40"/> </a> <a href="https://www.linux.org/" target="_blank" rel="noreferrer"> <img src="https://raw.githubusercontent.com/devicons/devicon/master/icons/linux/linux-original.svg" alt="linux" width="40" height="40"/> </a> <a href="https://www.mysql.com/" target="_blank" rel="noreferrer"> <img src="https://raw.githubusercontent.com/devicons/devicon/master/icons/mysql/mysql-original-wordmark.svg" alt="mysql" width="40" height="40"/> </a> <a href="https://www.nginx.com" target="_blank" rel="noreferrer"> <img src="https://raw.githubusercontent.com/devicons/devicon/master/icons/nginx/nginx-original.svg" alt="nginx" width="40" height="40"/> </a> <a href="https://www.php.net" target="_blank" rel="noreferrer"> <img src="https://raw.githubusercontent.com/devicons/devicon/master/icons/php/php-original.svg" alt="php" width="40" height="40"/> </a> <a href="https://www.python.org" target="_blank" rel="noreferrer"> <img src="https://raw.githubusercontent.com/devicons/devicon/master/icons/python/python-original.svg" alt="python" width="40" height="40"/> </a> </p>

## Installation
```
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
```
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
```
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
