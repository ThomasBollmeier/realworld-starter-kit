<?php

namespace tbollmeier\realworld\backend\db;

use tbollmeier\webappfound\db\Connector;
use tbollmeier\webappfound\db\Environment;

class Database
{
    /**
     * Establish database connection
     * 
     * @throws \Exception
     */
    public static function connect() 
    {
        $connector = new Connector();
        $dbConn = $connector->createConfigConnection(__DIR__ . "/../config/db.json");
        if ($dbConn === false) {
            throw new \Exception("Could not establish connection to database");
        }
        
        Environment::getInstance()->dbConn = $dbConn;
    }
}
