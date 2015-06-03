<?php
namespace Music;

use Music\Config\Config;
use Music\Util\Url;
use ActiveRecord;

class App
{
    public static function  run()
    {
        ActiveRecord\Config::initialize(
            function ($cfg) {

                $cfg->set_model_directory(Config::getModelPath());
                $dsn = Config::getPHPActiveRecordDSN();
                $cfg->set_connections(array('development' => $dsn));
            }
        );

        $param = Url::identifyParameters();

        if (empty($param)) {
            $controller = "Base";
            $action     = "index";
            $args       = array();
        } else {
            $controller = $param[0];
            $action     = $param[1];

            $args = array_slice($param, 2);
        }

        $controller = __NAMESPACE__ . '\\Controller\\' . $controller;

        try {

            $objController = new $controller();

            $action = lcfirst($action) . "Action";
            echo $objController->$action($args);
        } catch (\Exception $e) {
            echo '<pre>';
            print_r($e);
            echo '</pre>';
        }
    }
}
