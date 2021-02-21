<?php

namespace Src\Response;

class GetSynonymResponse {
    public $searchWord;
    public $synonyms;

    public static function createModel($searchWord, $synonyms) {
        $obj = new self();
        $obj->searchWord = $searchWord;
        
        $synonymsResponseArray = array();

        foreach ($synonyms as $synonym) {
            $synonymsResponseArray[] = SynonymResponse::createModel($synonym);
        }

        $obj->synonyms = $synonymsResponseArray;

        return $obj;
    }
}