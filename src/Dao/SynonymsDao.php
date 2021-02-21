<?php

namespace Src\Dao;

use Src\Database\Db;
use Src\Database\DbStatementHelper;
use Src\Datamodels\Synonym;
use Src\Datamodels\Word;
use Src\Datamodels\WordWithSynonym;

class SynonymsDao {

    private $db;

    public function __construct()
    {
        $this->db = new Db('db', 'thesaurus', 'root', 'root', 3306);
    }

    public function getWords() 
    {
        $helper = new DbStatementHelper($this->db);
        $query = "SELECT word.value " .
                 "FROM word UNION SELECT " .
                 "synonyms.value " .
                 "FROM synonyms;";
        $rows = $helper->selectAll($query);

        $words = array();
        foreach ($rows as $row) {
            $words[] = Word::withDbRow($row);
        }

        return $words;
    }

    public function wordHasSynonym($firstWord, $secondWord) {
        $helper = new DbStatementHelper($this->db);
        $query = "SELECT word.id, word.value, 'word' AS from_table " .
                 "FROM word " . 
                 "WHERE word.value = ? OR word.value = ? " .
                 "UNION SELECT " . 
                 "synonyms.word_id, synonyms.value, " .
                 "'synonyms' AS from_table " . 
                 "FROM synonyms WHERE " .
                 "synonyms.value = ? OR synonyms.value = ?";
        $rows = $helper->selectAllByParams($query, [$firstWord, $secondWord, $firstWord, $secondWord]);

        $wordWithSynonym = array();
        foreach ($rows as $row) {
            $wordWithSynonym[] = WordWithSynonym::withDbRow($row);
        }

        return $wordWithSynonym;
    }

    public function insertSynonym($word, $id) {
        $helper = new DbStatementHelper($this->db);
        $query = "INSERT INTO synonyms (word_id, value) " . 
                 "VALUES (?, ?)";
        return $helper->insert($query, array($id, $word));
    }

    public function insertWord($word) {
        $helper = new DbStatementHelper($this->db);
        $query = "INSERT INTO word (value) " . 
                 "VALUES (?)";
        $id = $helper->insert($query, array($word), true);

        return $id;
    }

    public function getWordByValue($value) {
        $helper = new DbStatementHelper($this->db);
        $query = "SELECT * FROM word WHERE value = :value";
        $row = $helper->selectByValue($query, $value);

        if ($row) {
            
            return Word::withDbRow($row);
        }

        return false;
    }

    public function getSynonymByValue($value) {
        $helper = new DbStatementHelper($this->db);
        $query = "SELECT * FROM synonyms WHERE value = :value";
        $row = $helper->selectByValue($query, $value);

        if ($row) {
            return Synonym::withDbRow($row);
        }

        return false;
    }

    public function getAllSynonymsByWordId($id) {
        $helper = new DbStatementHelper($this->db);
        $query = "SELECT * FROM synonyms WHERE word_id = ?";
        $rows = $helper->selectAllByParams($query, array($id));

        $synonyms = array();
        foreach ($rows as $row) {
            $synonyms[] = Synonym::withDbRow($row);
        }

        return $synonyms;
    }

    public function getWordById($id) {
        $helper = new DbStatementHelper($this->db);
        $query = "SELECT * FROM word WHERE id = :id";
        $row = $helper->selectById($query, $id);

        if ($row) {
            return Word::withDbRow($row);
        }

        return false;
    }

    public function getAllSynonymsById($id) {
        $helper = new DbStatementHelper($this->db);
        $query = "SELECT * FROM synonyms WHERE id = ?";
        $rows = $helper->selectAllByParams($query, array($id));

        $synonyms = array();
        foreach ($rows as $row) {
            $synonyms[] = Synonym::withDbRow($row);
        }

        return $synonyms;
    }
}