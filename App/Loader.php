<?php
namespace Music;

class Loader
{
    public static function registerAutoloader()
    {
        spl_autoload_register(__NAMESPACE__ . "\\Loader::load");
    }

    public static function load($className)
    {
        $namespace = __NAMESPACE__;

        if (substr($className, 0, strlen($namespace)) == $namespace) {
            $className = substr($className, strlen($namespace));
        }

        $className = ltrim($className, '\\');
        $fileName  = '';

        if ($lastNsPos = strrpos($className, '\\')) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }

        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

        $fileName = __DIR__ . DIRECTORY_SEPARATOR . $fileName;

        if (is_file($fileName)) {
            require $fileName;
        }
    }
}