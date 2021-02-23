<?php
namespace Thesaurus\Response;

class SynonymResponse
{

    /**
     * @var string
     */
    public string $word;

    /**
     * @param object $object
     * @return SynonymResponse
     */
    public static function createModel($object)
    {
        $obj = new self();
        $obj->word = $object->value;

        return $obj;
    }
}