<?php
namespace Music\Config;

class Config
{
    public static function getParentPath($file)
    {
        $path = dirname(realpath($file));

        $ds = DIRECTORY_SEPARATOR;

        return realpath($path . $ds . ".." . $ds) . $ds;
    }

    public static function getPath($file)
    {
        $path = dirname(realpath($file));

        $ds = DIRECTORY_SEPARATOR;

        return realpath($path . $ds) . $ds;
    }

    public static function getConfig($section, $key)
    {
        $config = self::getData();

        $value = false;

        if (isset($config[$section][$key])) {
            $value = $config[$section][$key];
        }

        return $value;
    }

    public static function setConfig($section, $key, $value)
    {
        $config = self::getData();

        $config[$section][$key] = $value;
    }

    private static function getData()
    {
        $configDir = self::getPath(__FILE__);

        if (is_file($configDir . 'prod.ini')) {
            $file = 'prod.ini';
        } elseif (is_file($configDir . 'dev.ini')) {
            $file = 'dev.ini';
        } else {
            throw new \Exception('Config file not found!');
        }

        $config = @parse_ini_file($configDir . $file, true);

        return $config;
    }

    public static function getDSN()
    {
        $dsn = self::getConfig('database', 'dsn');

        if (empty($dsn)) {
            $sgbd     = self::getConfig('database', 'sgbd');
            $host     = self::getConfig('database', 'host');
            $user     = self::getConfig('database', 'user');
            $password = self::getConfig('database', 'password');
            $database = self::getConfig('database', 'database');

            if (empty($sgbd)) {
                $sgbd = 'mysql';
            }
            $dsn   = array();
            $dsn[] = $sgbd . ":";

            if ($database) {
                $dsn[] = "dbname=" . $database . ";";
            }

            if ($host) {
                $dsn[] = "host=" . $host . ";";
            }

            $dsn = implode("", $dsn);
            $dsn = trim($dsn, ";");

            $dsn = array('dsn' => $dsn, 'user' => $user, 'password' => $password);

            self::setConfig('database', 'dsn', $dsn);
        }

        return $dsn;
    }

    public static function getPHPActiveRecordDSN()
    {
        $dsn = self::getConfig('database', 'phpActiveRecordDSN');

        if (empty($dsn)) {
            $sgbd     = self::getConfig('database', 'sgbd');
            $host     = self::getConfig('database', 'host');
            $user     = self::getConfig('database', 'user');
            $password = self::getConfig('database', 'password');
            $database = self::getConfig('database', 'database');

            if (empty($sgbd)) {
                $sgbd = 'mysql';
            }

            $dsn = $sgbd . "://";

            if ($user) {
                $dsn .= $user . ":";

                if ($password) {
                    $dsn .= $password . "@";
                } else {
                    $dsn = trim($dsn, ":") . "@";
                }
            }

            if ($host) {
                $dsn .= $host . "/";
            } else {
                $dsn = trim($dsn, "@");
            }

            if ($database) {
                $dsn .= $database;
            }

            self::setConfig('database', 'phpActiveRecordDSN', $dsn);
        }

        return $dsn;
    }

    public static function getViewPath()
    {
        $viewPath = self::getConfig('path', 'view');

        if (empty($viewPath)) {
            $viewPath = self::getParentPath(__FILE__) . "View" . DIRECTORY_SEPARATOR;

            self::setConfig('path', 'view', $viewPath);
        }

        return $viewPath;
    }

    public static function getModelPath()
    {
        $modelPath = self::getConfig('path', 'model');

        if (empty($modelPath)) {
            $modelPath = self::getParentPath(__FILE__) . "Model" . DIRECTORY_SEPARATOR;

            self::setConfig('path', 'model', $modelPath);
        }

        return $modelPath;
    }
}