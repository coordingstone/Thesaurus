<?php
namespace Thesaurus\Response;

class GetSynonymResponse
{

    /**
     * @var string
     */
    public string $searchWord;

    /**
     * @var array
     */
    public array $synonyms;

    /**
     * @param string $searchWord
     * @param array $synonyms
     * @return GetSynonymResponse
     */
    public static function createModel(string $searchWord, array $synonyms)
    {
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