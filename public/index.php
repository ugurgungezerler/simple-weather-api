<?php

use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Micro;
use Phalcon\Events\Manager;
use Phalcon\Logger\Adapter\File as FileAdapter;



error_reporting(E_ALL);

/**
 *
 */
define('BASE_PATH', dirname(__DIR__));
/**
 *
 */
define('APP_PATH', BASE_PATH . '/app');
$logger = new FileAdapter(APP_PATH . '/logs/errors.log');

try {

    /**
     * The FactoryDefault Dependency Injector automatically registers the services that
     * provide a full stack framework. These default services can be overidden with custom ones.
     */
    $di = new FactoryDefault();

    /**
     * Include Services
     */
    include APP_PATH . '/config/services.php';

    /**
     * Get config service for use in inline setup below
     */
    $config = $di->getConfig();

    /**
     * Include Autoloader
     */
    include APP_PATH . '/config/loader.php';

    /**
     * Starting the application
     * Assign service locator to the application
     */
    $app = new Micro($di);

    $eventsManager = new Manager();
    $eventsManager->attach('micro', new AuthMiddleware());
    $app->before(new AuthMiddleware());
//    $app->after(new AuthMiddleware());
    $app->setEventsManager($eventsManager);


    /**
     * Include Application
     */
    include APP_PATH . '/app.php';


    /**
     * Handle the request
     */
    $app->handle();
} catch (\Exception $e) {
    echo json_encode(['message' => $e->getMessage()]);

    $logger->critical(
      $e->getMessage()
    );
    die();
}
