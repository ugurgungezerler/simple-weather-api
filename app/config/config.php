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
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
    'dbname' => 'weather-db',
    'charset' => 'utf8',
  ],

  'application' => [
    'modelsDir' => APP_PATH . '/models/',
    'migrationsDir' => APP_PATH . '/migrations/',
    'viewsDir' => APP_PATH . '/views/',
    'controllersDir' => APP_PATH . '/controllers/',
    'middlewaresDir' => APP_PATH . '/middlewares/',
    'baseUri' => '/weather-app-simple/',
    'vendorDir' => BASE_PATH . '/vendor/'
  ],
  'app' => [
    'url' => 'localhost',
  ],
  'jwt' => [
    'identifier' => '5f1g24a12bb',
    'secret' => 'RmhwYWxUWDl2bXlkTHg4TOOAEeS5F5av.c6BSLAmd1DLv7QISdabK',
    'life' => 9000,
  ],
]);
