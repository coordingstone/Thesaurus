<?php
namespace Thesaurus\Datamodels;

class Synonym
{

    /**
     * @var int
     */
    public int $id;

    /**
     * @var int
     */
    public int $wordId;

    /**
     * @var string
     */
    public string $value;

    /**
     * @param array $row
     * @return Synonym
     */
    public static function withDbRow(array $row)
    {
        $obj = new self();
        $obj->id = $row['id'];
        $obj->wordId = $row['word_id'];
        $obj->value = $row['value'];

        return $obj;
    }
}