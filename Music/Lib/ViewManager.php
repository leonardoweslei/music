<?php
namespace Music\Lib;

class ViewManager
{
    private $viewPath = "";
    private $vars = array();
    private $type = 'HTML';
    private $file = null;
    private $config = null;

    public function __construct(&$config)
    {
        $this->setConfig($config);

        $this->viewPath = $this->config->getParentPath(__FILE__) . 'view' . DIRECTORY_SEPARATOR;
    }

    /**
     * @return string
     */
    public function getViewPath()
    {
        return $this->viewPath;
    }

    /**
     * @param string $viewPath
     */
    public function setViewPath($viewPath)
    {
        $this->viewPath = $viewPath;
    }

    /**
     * @return mixed
     */
    public function getVar($var)
    {
        return $this->var[$var];
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
     * @param array $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return array
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param array $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return null
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param null $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    private function output()
    {
        $out = '';
        if ($this->getType() == 'JSON') {
            header('Pragma: no-cache');
            header('Cache-Control: private, no-cache');
            header('X-Content-Type-Options: nosniff');
            header('Content-type: application/json');
            $out = $this->vars;

            if (count($out) == 1) {
                $out = array_pop($out);
            }

            $out = json_encode($out);
        } elseif ($this->getType() == 'HTML') {
            $file = $this->getViewPath() . $this->getFile();

            extract($this->vars);

            ob_start();

            include $file;

            $out = ob_get_clean();
        }

        return $out;
    }

    public function __toString()
    {
        return $this->output();
    }
}