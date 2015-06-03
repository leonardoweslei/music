<?php

if (class_exists('Music\Loader')) {
    return;
}
include('Loader.php');

\Music\Loader::registerAutoloader();
