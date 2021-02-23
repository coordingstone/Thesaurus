<?php
namespace Thesaurus\Requests;

class AddWordRequest
{

    /**
     * @var array
     */
    public array $words;

    /**
     * @param object $request
     * @return AddWordRequest
     */
    public static function withRequest(object $request) {
        $obj = new self();
        $obj->words = isset($request->words) ? $request->words : array();
        return $obj;
    }
}