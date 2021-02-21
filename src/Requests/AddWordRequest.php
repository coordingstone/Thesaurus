<?php

namespace Src\Requests;

class AddWordRequest {

    public $firstWord;

    public $secondWord;

    public static function withRequest($request) {
        $obj = new self();
        $obj->firstWord     = isset($request->first_word) ? filter_var($request->first_word, FILTER_SANITIZE_STRING) : '';
        $obj->secondWord    = isset($request->second_word) ? filter_var($request->second_word, FILTER_SANITIZE_STRING) : '';
        return $obj;
    }
}