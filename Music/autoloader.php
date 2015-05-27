<?php

if (class_exists('Music\App')) {
    return;
}
include('App.php');

\Music\App::registerAutoloader();
