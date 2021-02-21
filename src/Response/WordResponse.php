<?php

namespace Src\Response;

use Src\Datamodels\Word;

class WordResponse {

    public $word;

    public static function createModel(Word $word) {
        $obj = new self();
        $obj->word = $word->value;

        return $obj;
    }
}