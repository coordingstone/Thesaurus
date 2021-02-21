<?php

namespace Src\Datamodels;

class WordWithSynonym {

    public $id;

    public $value;

    public $fromTable;

    public static function withDbRow($row) 
    {
        $obj = new self();
        $obj->id            = $row['id'];
        $obj->value         = $row['value'];
        $obj->fromTable     = $row['from_table'];

        return $obj;
    }
}