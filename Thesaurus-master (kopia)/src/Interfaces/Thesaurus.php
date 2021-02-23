<?php
namespace Thesaurus\Interfaces;

interface Thesaurus
{

    public function addSynonyms($synonyms): void;

    public function getSynonyms($word): array;

    public function getWords(): array;
}