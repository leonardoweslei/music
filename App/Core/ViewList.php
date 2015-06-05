<?php

namespace Music\Core;

class ViewList extends ViewManager
{
    public function __construct($dataTable)
    {
        parent::__construct();
        $this->setVar('dataTable', $dataTable);
        $this->setViewFile("list.php");
        $this->setType("HTML");
    }
}