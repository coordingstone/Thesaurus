<?php

namespace Src\Datamodels;

class Word {

    public $id;

    public $value;

    public static function withDbRow($row) {
        $obj        = new self();
        $obj->id    = $row['id'];
        $obj->value = $row['value'];

        return $obj;
    }
}