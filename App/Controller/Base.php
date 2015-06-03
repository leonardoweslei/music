<?php
namespace Music\Controller;

use Music\Core\Controller;

class Base extends Controller
{
    public function indexAction()
    {
        $this->getViewManager()->setVar('p1', 'world');
        $this->getViewManager()->setVar('p2', 'world');
        $this->getViewManager()->setType('JSON');
        echo $this->getViewManager();
    }
}