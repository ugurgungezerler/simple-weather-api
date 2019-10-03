<?php

/**
 * Registering an autoloader
 */
$loader = new \Phalcon\Loader();
$loader->registerFiles([$config->application->vendorDir . '/autoload.php']);

$loader->registerDirs(
    [
        $config->application->modelsDir,
        $config->application->controllersDir,
        $config->application->middlewaresDir,
    ]

)->register();
