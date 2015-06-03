<?php
namespace Music\Core;

Use Music\Config\Config;
use Music\Util\Url;

class Controller
{
    private $viewManager = null;

    public function __construct()
    {
        $this->viewManager = new ViewManager();
    }

    /**
     * @return ViewManager|null
     */
    public function getViewManager()
    {
        return $this->viewManager;
    }

    /**
     * @param ViewManager|null $viewManager
     */
    public function setViewManager($viewManager)
    {
        $this->viewManager = $viewManager;
    }
}