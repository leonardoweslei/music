<?php
namespace Music\Controller;

use Music\Core\Controller;

class Base extends Controller
{
    public function indexAction()
    {
        $this->getViewManager()->setViewFile('main.php');

        return $this->getViewManager();
    }
}