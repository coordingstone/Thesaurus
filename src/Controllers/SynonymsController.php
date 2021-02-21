<?php

namespace Src\Controllers;

use Exception;
use Src\Dao\SynonymsDao;
use Src\Interfaces\Thesaurus;
use Src\Requests\AddWordRequest;
use Src\Requests\GetWordRequest;
use Src\Response\GetSynonymResponse;
use Src\Response\GetWordResponse;

class SynonymsController implements Thesaurus {

    private $synonymsDao;

    public function __construct() 
    {
        $this->synonymsDao = new SynonymsDao();
    }

    public function getWords() 
    {
        try {
            $words = $this->synonymsDao->getWords();
            $response = GetWordResponse::createModel($words);

            return json_encode($response);
        } catch (Exception $exception) {
            http_response_code(500);
            echo json_encode('Something went wrong');
        }
               
    }

    public function getSynonyms($request) 
    {
        $getWordRequest = GetWordRequest::withRequest(json_decode($request));

        $errorMessage = $this->validateWordRequest($getWordRequest);
        if (!empty($errorMessage)) {
            return $errorMessage;
        }

        $found = $this->synonymsDao->getWordByValue($getWordRequest->word);
        if (!$found) {
            $foundSynonym = $this->synonymsDao->getSynonymByValue($getWordRequest->word);
            if (!$foundSynonym) {
                return "No synonyms found";
            }

            $allSynonyms = $this->synonymsDao->getAllSynonymsByWordId($foundSynonym->wordId);
            if (!empty($allSynonyms)) {
                $word = $this->synonymsDao->getWordById($allSynonyms[0]->wordId);
                $allSynonyms[] = $word;
                $synonyms = array();
                foreach ($allSynonyms as $synonym) {
                    if ($synonym->value !== $foundSynonym->value) {
                        $synonyms[] = $synonym;
                    }
                }
                $response = GetSynonymResponse::createModel($foundSynonym->value, $synonyms);
                return json_encode($response);
            }
            
            $response = GetSynonymResponse::createModel($foundSynonym->value, $allSynonyms);
            return json_encode($response);
            
        }

        $allSynonyms = $this->synonymsDao->getAllSynonymsByWordId($found->id);
        $response = GetSynonymResponse::createModel($found->value, $allSynonyms);
        return json_encode($response);
    }

    private function validateWordRequest($request) {
        $errorMessage = array();

        if ($request->word == '') {
            $errorMessage[] = 'Word missing';
        }

        preg_match('/[^a-zA-Z]/', $request->word) == true ? $errorMessage[] = 'Word contains non letters' : null;

        return implode('. ', $errorMessage);
    }

    public function addSynonyms($request) 
    {
        $addWordRequest = AddWordRequest::withRequest(json_decode($request));

        $errorMessage = $this->validateRequest($addWordRequest);
        if (!empty($errorMessage)) {
            return $errorMessage;
        }

        $wordWithSynonymArray = $this->synonymsDao->wordHasSynonym($addWordRequest->firstWord, $addWordRequest->secondWord);

        if (count($wordWithSynonymArray) == 2) {
            return "First word is already added as a synonym to second word";
        } else if (count($wordWithSynonymArray) == 1) {
            if ($wordWithSynonymArray[0]->fromTable == 'word') {
                if ($wordWithSynonymArray[0]->value === $addWordRequest->firstWord) {
                    // insert second word to synonyms
                    if (!$this->synonymsDao->insertSynonym($addWordRequest->secondWord, $wordWithSynonymArray[0]->id)) {
                        return 'Failed to insert synonym for ' . $addWordRequest->secondWord;
                    }
                    return 'Inserted synonym for ' . $addWordRequest->secondWord;
                } else {
                    // insert first word to synonyms
                    if (!$this->synonymsDao->insertSynonym($addWordRequest->firstWord, $wordWithSynonymArray[0]->id)) {
                        return 'Failed to insert synonym for ' . $addWordRequest->firstWord;
                    }
                    return 'Inserted synonym for ' . $addWordRequest->firstWord;
                }
            } else {
                if ($wordWithSynonymArray[0]->value === $addWordRequest->firstWord) {
                    // insert second word to synonym
                    if (!$this->synonymsDao->insertSynonym($addWordRequest->secondWord, $wordWithSynonymArray[0]->id)) {
                        return 'Failed to insert synonym for ' . $addWordRequest->secondWord;
                    }
                    return 'Inserted synonym for ' . $addWordRequest->secondWord;
                } else {
                    // insert first word to synonym
                    if (!$this->synonymsDao->insertSynonym($addWordRequest->firstWord, $wordWithSynonymArray[0]->id)) {
                        return 'Failed to insert synonym for ' . $addWordRequest->firstWord;
                    }
                    return 'Inserted synonym for ' . $addWordRequest->firstWord;
                }
            }
        } else if (empty($wordWithSynonymArray)) {
            $newWordId = $this->synonymsDao->insertWord($addWordRequest->firstWord);
            if ($newWordId) {
                if (!$this->synonymsDao->insertSynonym($addWordRequest->secondWord, $newWordId)) {
                    return 'Failed to insert synonym for ' . $addWordRequest->secondWord;
                }
                return "Added two words";
            }
            return 'Failed to insert word for ' . $addWordRequest->firstWord;
        }

        return 'Something went terrible wrong';   
    }

    private function validateRequest($request) 
    {
        $errorMessage = array();

        if ($request->firstWord === $request->secondWord) {
            $errorMessage[] = 'First word same as second word';
        }

        if ($request->firstWord == '') {
            $errorMessage[] = 'First word missing';
        }

        if ($request->secondWord == '') {
            $errorMessage[] = 'Second word missing';
        }

        preg_match('/[^a-zA-Z]/', $request->firstWord) == true ? $errorMessage[] = 'First word contains non letters' : null;
        preg_match('/[^a-zA-Z]/', $request->secondWord) == true ? $errorMessage[] = 'Second word contains non letters' : null;

        return implode('. ', $errorMessage);
    }
}