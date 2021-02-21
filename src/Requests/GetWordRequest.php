<?php

namespace Src\Requests;

class GetWordRequest {

    public $word;

    public static function withRequest($request) {
        $obj = new self();
        $obj->word = isset($request->word) ? filter_var($request->word, FILTER_SANITIZE_STRING) : '';

        return $obj;
    }
}