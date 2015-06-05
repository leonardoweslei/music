<?php
namespace Music\Core;

use Music\Config\Config;
use Music\Util\String;
use Music\Util\Url;

class ViewManager
{
    private $vars = array();
    private $type = 'HTML';
    private $viewFile = null;
    private $basePath = null;

    public function __construct()
    {
        $this->basePath = Url::getBaseUrl();
    }

    /**
     * @return mixed
     */
    public function getVar($var)
    {
        return $this->vars[$var];
    }

    /**
     * @param string $var
     * @param mixed  $value
     */
    public function setVar($var, $value)
    {
        $this->vars[$var] = $value;
    }

    /**
     * @return array
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return array
     */
    public function getViewFile()
    {
        return $this->viewFile;
    }

    /**
     * @param array $viewFile
     */
    public function setViewFile($viewFile)
    {
        $this->viewFile = $viewFile;
    }

    private function output()
    {
        $out = '';
        if ($this->getType() == 'JSON') {
            $out = $this->outputJSON();
        } elseif ($this->getType() == 'HTML') {
            $out = $this->outputHTML();
        }

        return $out;
    }

    public function outputJSON()
    {
        header('Pragma: no-cache');
        header('Cache-Control: private, no-cache');
        header('X-Content-Type-Options: nosniff');
        header('Content-type: application/json');
        $out = $this->vars;

        if (count($out) == 1) {
            $out = array_pop($out);
        }

        $out = json_encode($out);

        return $out;
    }

    public function outputHTML()
    {
        $out  = "";
        $file = Config::getViewPath() . $this->getViewFile();

        if (is_file($file)) {
            extract($this->vars);
            ob_start();

            include($file);

            $out = ob_get_clean();
        }

        return $out;
    }

    public function __toString()
    {
        return $this->output();
    }

    public function getBasePath($param)
    {
        $param = is_array($param) ? $param : array($param);

        foreach ($param as $i => $v) {
            $param[$i] = String::slugify($v);
        }

        $basePath = $this->basePath . implode("/", $param);

        return $basePath;
    }
}