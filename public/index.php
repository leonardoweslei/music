<?php
//require '../vendor/autoload.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

require '../Music/autoloader.php';
try {

    $config = new \Music\Util\Config();
    $app    = new \Music\App($config);
    echo $app->handleRequest();
} catch (\Exception $e) {
    echo $e->getMessage();
}

