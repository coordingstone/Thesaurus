<?php
namespace Thesaurus\Controllers;

use Exception;
use Thesaurus\Requests\AddWordRequest;
use Thesaurus\Response\GetSynonymResponse;
use Thesaurus\Response\GetWordResponse;
use Thesaurus\Service\SynonymsService;

class SynonymsController
{

    /** @var SynonymsService */
    private SynonymsService $synonymsService;

    public function __construct(SynonymsService $synonymsService)
    {
        $this->synonymsService = $synonymsService;
    }

    /**
     * Get all words in the thesaurus
     * @return false|string
     */
    public function getWords()
    {
        try {
            $words = $this->synonymsService->getWords();
            $response = GetWordResponse::createModel($words);

            return json_encode($response);
        } catch (Exception $exception) {
            http_response_code(500);
            echo json_encode('Something went wrong');
        }
    }

    /**
     * Get all synonyms for word in request
     *
     * @param string $word
     * @return false|string
     * @throws Exception
     */
    public function getSynonyms(string $word)
    {
        if (empty($word)) {
            http_response_code(400);
            echo json_encode('Bad request');
            die();
        }

        $word = strtolower(filter_var($word, FILTER_SANITIZE_STRING));

        $errorMessage = $this->validateWord($word);
        if (!empty($errorMessage)) {
            http_response_code(400);
            return json_encode($errorMessage);
        }

        list($value, $synonyms) = $this->synonymsService->getSynonyms($word);
        $response = GetSynonymResponse::createModel($value, $synonyms);
        return json_encode($response);
    }

    /**
     * @param string $word
     * @return string
     */
    private function validateWord(string $word)
    {
        $errorMessage = array();

        if ($word == '') {
            $errorMessage[] = 'Word missing';
        }

        preg_match('/[^a-z]/', $word) == true ? $errorMessage[] = 'Word contains non letters or uppercase' : null;

        return implode('. ', $errorMessage);
    }

    /**
     * Add synonyms to thesaurus if it not exists
     *
     * @param false|string $request
     * @return string
     * @throws Exception
     */
    public function addSynonyms($request)
    {
        $addWordRequest = AddWordRequest::withRequest(json_decode($request));
        if (empty($addWordRequest->words)) {
            return "Empty list of words";
        }
        $listOfWords = array();
        foreach ($addWordRequest->words as $word) {
            $candidate = strtolower(filter_var($word, FILTER_SANITIZE_STRING));
            $errorMessage = $this->validateWord($candidate);
            if (!empty($errorMessage)) {
                return $errorMessage;
            }
            $listOfWords[] = $candidate;
        }

        $this->synonymsService->addSynonyms($listOfWords);

    }
}