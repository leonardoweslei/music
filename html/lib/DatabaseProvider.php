<?php

require_once '/var/www/html/music/local_settings.php';

class DatabaseProvider
{
    static function getInstance()
    {
        $pdo = new PDO('mysql:dbname=musicas;host=localhost', DB_USER, DB_PASSWORD);
        return $pdo;
    }
}
