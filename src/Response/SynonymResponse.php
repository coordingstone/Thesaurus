<?php

namespace Src\Response;

class SynonymResponse {

    public $word;

    public static function createModel($object) {
        $obj = new self();
        $obj->word = $object->value;

        return $obj;
    }
}