<?php
namespace Music\Util;

class Url
{
    public static function getBasePath()
    {
        $file = $_SERVER['SCRIPT_FILENAME'];

        return substr($file, 0, strlen($file) - strlen(strrchr($file, "/")) + 1);
    }

    public static function identifyParameters()
    {
        $root = Url::getBasePath();

        if (isset($_SERVER['REDIRECT_URL'])) {
            $part = $_SERVER['REDIRECT_URL'];
        } else {
            $part = $_SERVER['REQUEST_URI'];
        }

        $request = $_SERVER['DOCUMENT_ROOT'] . $part;

        $param = trim(str_replace($root, '', $request), '/');

        if ($param != "") {
            //$param = explode("/", $param);
            $param = preg_split("/[\/\?&]/", $param);
        } else {
            $param = array();
        }

        return $param;
    }
}
