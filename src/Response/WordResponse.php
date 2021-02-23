<?php
namespace Thesaurus\Response;

use Thesaurus\Datamodels\WordWithoutId;

class WordResponse
{

    /**
     * @var string
     */
    public string $word;

    /**
     * @param WordWithoutId $word
     * @return WordResponse
     */
    public static function createModel(WordWithoutId $word)
    {
        $obj = new self();
        $obj->word = $word->value;

        return $obj;
    }
}