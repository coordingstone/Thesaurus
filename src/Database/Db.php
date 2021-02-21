<?php

namespace Src\Database;

use Exception;
use mysqli;
use PDO;
use PDOException;

class Db
{
    /** @var mysqli */
    private $connection;

    /**
     * Try to establish connection to db
     * @throws Exception
     */
    public function __construct($host, $dbName, $user, $password, $port)
    {
        try {
            $pdo = new PDO("mysql:host=" . $host . ";dbname=" . $dbName . ";port=" . $port, $user, $password);

            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            $this->connection = $pdo;
        } catch (PDOException $e) {
            throw new Exception('Connection failed: ' . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->connection;
    }
}