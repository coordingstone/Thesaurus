<?php
namespace Thesaurus\Response;

use Thesaurus\Datamodels\WordWithoutId;

class GetWordResponse
{

    /**
     * @var WordWithoutId|array
     */
    public $words;

    /**
     * @param WordWithoutId|array $words
     * @return GetWordResponse
     */
    public static function createModel(array $words)
    {
        $obj = new self();
        $wordResponseModels = array();
        foreach ($words as $word) {
            $wordResponseModels[] = WordResponse::createModel($word);
        }
        $obj->words = $wordResponseModels;

        return $obj;
    }
}