<?php
/*
 * Modified: prepend directory path of current file, because of this file own different ENV under between Apache and command line.
 * NOTE: please remove this comment.
 */
defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

return new \Phalcon\Config([
  'database' => [
    'adapter' => 'Mysql',
    'host' => 'db',
    'port' => '3306',
    'username' => 'root',
    'password' => '1234',
    'dbname' => 'weather-db',
    'charset' => 'utf8',
  ],

  'application' => [
    'modelsDir' => APP_PATH . '/models/',
    'migrationsDir' => APP_PATH . '/migrations/',
    'viewsDir' => APP_PATH . '/views/',
    'controllersDir' => APP_PATH . '/controllers/',
    'middlewaresDir' => APP_PATH . '/middlewares/',
    'helpersDir' => APP_PATH . '/helpers/',
    'baseUri' => '/weather-app-simple/',
    'vendorDir' => BASE_PATH . '/vendor/'
  ],
  'app' => [
    'url' => 'localhost',
    'authorizeExceptions' => [
      '/',
      'login',
      'register',
      'create_dummy_data'
    ],
    'notification_time' => '09:00:00'
  ],
  'jwt' => [
    'identifier' => '5f1g24a12bb',
    'secret' => 'RmhwYWxUWDl2bXlkTHg4TOOAEeS5F5av.c6BSLAmd1DLv7QISdabK',
    'life' => 900,
  ],
]);
