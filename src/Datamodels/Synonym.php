<?php

namespace Src\Datamodels;

class Synonym {

    public $id;

    public $wordId;

    public $value;

    public static function withDbRow($row) {
        $obj = new self();
        $obj->id = $row['id'];
        $obj->wordId = $row['word_id'];
        $obj->value = $row['value'];

        return $obj;
    }
}