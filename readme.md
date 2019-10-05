# Kurulum
```sh
git clone git@github.com:ugurgungezerler/simple-weather-api.git
cd simple-weather-api_api_1
docker-compose up -d

docker exec -it simple-weather-api_api_1 bash
composer install
./vendor/phalcon/devtools/phalcon migration run
curl 127.0.0.1/dummy/create
```

# Postman
[Postman](https://documenter.getpostman.com/view/459680/SVtR1A1M?version=latest ) linki için tıklayın.

# Ayarlar (Config.php)
```sh

# App
'notification_time' => '09:00:00',
'life' => 900  // (15dk)

# Cron string  
0 * * * * /usr/bin/php /app/path/NotificationCron.php
```

### TODOS
Unit tests

