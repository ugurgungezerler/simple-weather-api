# Kurulum
git clone git@github.com:ugurgungezerler/simple-weather-api.git

cd simple-weather-api

composer install

(Bu adimdan sonra mysql ayarlari dogru olmasi gerekir (config.php))

phalcon migration run 

phalcon serve

localhost:8000/dummy/create

# Postman
[https://documenter.getpostman.com/view/459680/SVtR1A1M?version=latest](https://documenter.getpostman.com/view/459680/SVtR1A1M?version=latest)

# Ayarlar (Config.php)
### App
'notification_time' => '09:00'

'life' => 900 (15dk)
### Database
  'adapter' => 'Mysql',  
  'host' => 'localhost',  
  'username' => 'root',  
  'password' => '',  
  'dbname' => 'weather-db',  
  'charset' => 'utf8',  
### Cron string  
0 * * * * /usr/bin/php /app/path/NotificationCron.php

