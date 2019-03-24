<?php

namespace tbollmeier\realworld\backend\db;

use tbollmeier\webappfound\db\Connector;
use tbollmeier\webappfound\db\ActiveRecord;


class Database
{
    private static $single = null;

    private $conn; // PDO connection to database

    /**
     * @throws \Exception
     */
    public static function get()
    {
        if (self::$single === null) {
            self::$single = new Database();
        }
    }

    public function getConnection()
    {
        return $this->conn;
    }

    /**
     * Database constructor.
     * @throws \Exception
     */
    private function __construct()
    {
        $connector = new Connector();
        $this->conn = $connector->createConfigConnection(__DIR__ . "/../config/db.json");
        if ($this->conn === false) {
            throw new \Exception("Could not establish connection to database");
        }

        ActiveRecord::setDbConnection($this->conn);

    }
}
