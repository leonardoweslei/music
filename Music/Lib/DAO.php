<?php

namespace Music\Lib;

class DAO
{
    private $databaseInstance;

    public function __construct($databaseInstance = null)
    {
        if ($databaseInstance) {
            $this->setDatabaseInstance($databaseInstance);
        }
    }

    /**
     * @return \PDO
     */
    public function getDatabaseInstance()
    {
        return $this->databaseInstance;
    }

    /**
     * @param mixed $databaseInstance
     */
    public function setDatabaseInstance($databaseInstance)
    {
        $this->databaseInstance = $databaseInstance;
    }
}