<?php
namespace Thesaurus\Datamodels;

class Word
{

    /**
     * @var int
     */
    public int $id;

    /**
     * @var string
     */
    public string $value;

    /**
     * @param array $row
     * @return Word
     */
    public static function withDbRow(array $row)
    {
        $obj        = new self();
        $obj->id    = $row['id'];
        $obj->value = $row['value'];

        return $obj;
    }
}