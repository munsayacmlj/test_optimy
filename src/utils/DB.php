<?php

namespace App\Utils;

class DB
{
    private $pdo;

    private static $instance = null;

    private function __construct()
    {
        $dsn = 'mysql:dbname=phptest;host=127.0.0.1';
        $user = 'root';
        $password = '';

        $this->pdo = new \PDO($dsn, $user, $password);
    }

    public static function getInstance()
    {
        if (null === self::$instance) {
            $c = __CLASS__;
            self::$instance = new $c();
        }
        return self::$instance;
    }

    public function select($sql)
    {
        $sth = $this->pdo->query($sql);
        return $sth->fetchAll();
    }

    public function prepare($sql)
    {
        return $this->pdo->prepare($sql);
    }

    /**
     * use this method to execute the prepared statement
     */
    public function execute()
    {
        return $this->pdo->execute();
    }

    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
}
