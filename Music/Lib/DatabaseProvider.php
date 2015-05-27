<?php

namespace Music\Lib;

class DatabaseProvider
{
    private $config;
    private $host = "";
    private $user = "";
    private $password = "";
    private $database = "";

    public function __construct(\Music\Util\Config $config)
    {
        $this->config = $config;

        $this->host     = $this->config->getConfig('database', 'host');
        $this->user     = $this->config->getConfig('database', 'user');
        $this->password = $this->config->getConfig('database', 'password');
        $this->database = $this->config->getConfig('database', 'database');
    }

    public function getInstance()
    {
        $pdo = new \PDO('mysql:dbname=' . $this->database . ';host=' . $this->host, $this->user, $this->password);

        return $pdo;
    }
}
