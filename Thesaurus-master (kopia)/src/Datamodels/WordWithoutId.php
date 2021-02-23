<?php
namespace Thesaurus\Datamodels;

class WordWithoutId
{

    /**
     * @var string
     */
    public string $value;

    /**
     * @param array $row
     * @return WordWithoutId
     */
    public static function withDbRow(array $row) {
        $obj = new self();
        $obj->value = $row['value'];

        return $obj;
    }
}