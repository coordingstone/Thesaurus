<?php

namespace Src\Database;

use Src\Database\Db;
use PDOException;
use Exception;
use PDO;
use Src\Datamodels\Word;

class DbStatementHelper
{
    private $db;

    public function __construct(Db $db)
    {
        $this->db = $db;
    }

    /**
     * @param  String $query
     * @return Object
     * @throws Exception
     */
    public function query($query)
    {
        try {
            $statement = $this->db->getConnection()->prepare($query);
            if ($statement->execute()) {

            }
            
            return $statement;
        } catch (PDOException $e) {
            throw new Exception('Query failed: ' . $e->getMessage());
        }
    }

    public function selectAll($query) {
        try {
            return $statement = $this->db->getConnection()->query($query);
        } catch (PDOException $e) {
            throw new Exception('Query failed: ' . $e->getMessage());
        }
    }

    public function selectAllByParams($query, $params) {
        try {
            $statement = $this->db->getConnection()->prepare($query);
            $statement->execute($params);
            return $statement;
        } catch (PDOException $e) {
            throw new Exception('Query failed: ' . $e->getMessage());
        }
    }

    public function insert($query, $params, $returnObjectId = false) {
        try {
            if ($returnObjectId) {
                $statement = $this->db->getConnection()->prepare($query);
                $statement->execute($params);
                return $this->db->getConnection()->lastInsertId();
            } else {
                return $statement = $this->db->getConnection()->prepare($query)->execute($params);
            }
            
        } catch (PDOException $e) {
            throw new Exception('Query failed: ' . $e->getMessage());
        }
    }

    public function selectByValue($query, $value) {
        try {
            $statement = $this->db->getConnection()->prepare($query);
            $statement->bindParam(':value', $value, PDO::PARAM_STR);
            $statement->execute();
            return $statement->fetch();
        } catch (PDOException $e) {
            throw new Exception('Query failed: ' . $e->getMessage());
        }
    }

    public function selectById($query, $id) {
        try {
            $statement = $this->db->getConnection()->prepare($query);
            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            $statement->execute();
            return $statement->fetch();
        } catch (PDOException $e) {
            throw new Exception('Query failed: ' . $e->getMessage());
        }
    }
}
