<?php

namespace Src\Interfaces;

interface Thesaurus {
    
    public function addSynonyms($synonyms);

    public function getSynonyms($word);

    public function getWords();
}