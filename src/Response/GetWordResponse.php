<?php

namespace Src\Response;

use Src\Response\WordResponse;

class GetWordResponse {
        
    public $words;

    public static function createModel($words) {
        $obj = new self();
        $wordResponseModels = array();
        foreach ($words as $word) {
            $wordResponseModels[] = WordResponse::createModel($word);
        }
        $obj->words = $wordResponseModels;

        return $obj;
    }
}