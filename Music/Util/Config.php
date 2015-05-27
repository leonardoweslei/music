<?php
namespace Music\Util;

/**
 * Class Config
 * @package Music\Util
 */
class Config
{
    /**
     * @var bool|string
     */
    private $configDir = false;
    /**
     * @var array
     */
    private $config = array();

    public function __construct()
    {

        $this->configDir = $this->getParentPath(__FILE__) . "config" . DIRECTORY_SEPARATOR;

        $this->loadConfiguration();
    }

    public function getParentPath($file)
    {
        $path = dirname(realpath($file));

        $ds = DIRECTORY_SEPARATOR;

        return realpath($path . $ds . ".." . $ds) . $ds;
    }

    /**
     * @param $section
     * @param $key
     * @return mixed
     */
    public function getConfig($section, $key)
    {
        return $this->config[$section][$key];
    }

    /**
     * @param $section
     * @param $key
     * @param $value
     */
    public function setConfig($section, $key, $value)
    {
        $this->config[$section][$key] = $value;
    }

    /**
     * @param $file
     */
    private function loadConfiguration()
    {
        if (is_file($this->configDir . 'prod.ini')) {
            $file = 'prod.ini';
        } elseif (is_file($this->configDir . 'dev.ini')) {
            $file = 'dev.ini';
        } else {
            throw new \Exception('Config file not found!');
        }

        $this->config = @parse_ini_file($this->configDir . $file, true);

        $this->setupPHPSettings();
    }

    private function setupPHPSettings()
    {
        $varToSet = isset($this->config['php']) ? $this->config['php'] : false;
        $varToSet = !empty($varToSet) ? $varToSet : array();
        foreach ($varToSet as $name => $value) {
            ini_set($name, $value);
        }
    }
}