<?php
require '../vendor/autoload.php';
require '../App/autoloader.php';

ini_set('display_errors', 1);
ini_set('upload_max_filesize', "512M");
error_reporting(E_ALL);

try {
    $app = new \Music\App();
    $app->run();
} catch (\Exception $e) {
    echo $e->getMessage();
}

