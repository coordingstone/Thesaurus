<?php
namespace Thesaurus\Dao;

use Exception;
use Thesaurus\Database\Db;
use Thesaurus\Database\DbStatementHelper;
use Thesaurus\Datamodels\Synonym;
use Thesaurus\Datamodels\Word;
use Thesaurus\Datamodels\WordWithoutId;

class SynonymsDao
{

    /** @var Db */
    private Db $db;

    public function __construct(Db $db)
    {
        $this->db = $db;
    }

    /**
     * @return WordWithoutId[]
     * @throws Exception
     */
    public function getWords() 
    {
        $helper = new DbStatementHelper($this->db);
        $query = "SELECT word.value " .
                 "FROM word UNION SELECT " .
                 "synonym.value " .
                 "FROM synonym;";
        $rows = $helper->selectAll($query);

        $words = array();
        foreach ($rows as $row) {
            $words[] = WordWithoutId::withDbRow($row);
        }

        return $words;
    }

    /**
     * @param string $word
     * @param int $id
     * @return bool|int
     * @throws Exception
     */
    public function insertSynonym(string $word, int $id)
    {
        $helper = new DbStatementHelper($this->db);
        $query = "INSERT INTO synonym (word_id, value) " .
                 "VALUES (?, ?)";
        return $helper->insert($query, array($id, $word));
    }

    /**
     * @param string $word
     * @return bool|int
     * @throws Exception
     */
    public function insertWord(string $word)
    {
        $helper = new DbStatementHelper($this->db);
        $query = "INSERT INTO word (value) " . 
                 "VALUES (?)";
        $id = $helper->insert($query, array($word), true);

        return $id;
    }

    /**
     * @param string $value
     * @return bool|Word
     * @throws Exception
     */
    public function getWordByValue(string $value)
    {
        $helper = new DbStatementHelper($this->db);
        $query = "SELECT * FROM word WHERE value = :value";
        $row = $helper->selectByValue($query, $value);

        if ($row) {
            return Word::withDbRow($row);
        }

        return false;
    }

    /**
     * @param string $value
     * @return bool|Synonym
     * @throws Exception
     */
    public function getSynonymByValue(string $value)
    {
        $helper = new DbStatementHelper($this->db);
        $query = "SELECT * FROM synonym WHERE value = :value";
        $row = $helper->selectByValue($query, $value);

        if ($row) {
            return Synonym::withDbRow($row);
        }

        return false;
    }

    /**
     * @param int $id
     * @return Synonym[]
     * @throws Exception
     */
    public function getAllSynonymsByWordId(int $id)
    {
        $helper = new DbStatementHelper($this->db);
        $query = "SELECT * FROM synonym WHERE word_id = ?";
        $rows = $helper->selectAllByParams($query, array($id));

        $synonyms = array();
        foreach ($rows as $row) {
            $synonyms[] = Synonym::withDbRow($row);
        }

        return $synonyms;
    }

    /**
     * @param int $id
     * @return bool|Word
     * @throws Exception
     */
    public function getWordById(int $id)
    {
        $helper = new DbStatementHelper($this->db);
        $query = "SELECT * FROM word WHERE id = :id";
        $row = $helper->selectById($query, $id);

        if ($row) {
            return Word::withDbRow($row);
        }

        return false;
    }
}