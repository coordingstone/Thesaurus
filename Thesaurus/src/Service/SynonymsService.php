<?php
namespace Thesaurus\Service;

use Exception;
use Thesaurus\Dao\SynonymsDao;
use Thesaurus\Datamodels\WordWithoutId;
use Thesaurus\Interfaces\Thesaurus;

class SynonymsService implements Thesaurus
{

    /** @var SynonymsDao $synonymsDao
     */
    private SynonymsDao $synonymsDao;

    public function __construct(SynonymsDao $synonymsDao)
    {
        $this->synonymsDao = $synonymsDao;
    }

    /**
     * @param array $synonyms
     * @throws Exception
     */
    public function addSynonyms($synonyms): void
    {
        $foundWord = false;
        $foundSynonym = false;

        foreach ($synonyms as $word) {
            $found = $this->synonymsDao->getWordByValue($word);
            $synonymFound = $this->synonymsDao->getSynonymByValue($word);
            if ($found) {
                $foundWord = $found;
            }
            if ($synonymFound) {
                $foundSynonym = $synonymFound;
            }
        }

        if ($foundWord) {
            foreach ($synonyms as $word) {
                if (!$this->synonymsDao->getSynonymByValue($word)) {
                    if ($word !== $foundWord->value) {
                        $this->synonymsDao->insertSynonym($word, $foundWord->id);
                    }
                }
            }
        } elseif ($foundSynonym) {
            foreach ($synonyms as $word) {
                if (!$this->synonymsDao->getSynonymByValue($word)) {
                    if ($word !== $foundSynonym->value) {
                        $this->synonymsDao->insertSynonym($word, $foundSynonym->wordId);
                    }
                }
            }
        } else {
            if (count($synonyms) >= 2) {
                $firstWord = array_shift($synonyms);
                $id = $this->synonymsDao->insertWord($firstWord);
                if ($id) {
                    foreach ($synonyms as $word) {
                        $this->synonymsDao->insertSynonym($word, $id);
                    }
                }
            } else {
                $this->synonymsDao->insertWord($synonyms[0]);
            }

        }
    }

    /**
     * @param string $word
     * @return array
     * @throws Exception
     */
    public function getSynonyms($word): array
    {
        $found = $this->synonymsDao->getWordByValue($word);
        if (!$found) {
            $foundSynonym = $this->synonymsDao->getSynonymByValue($word);
            if (!$foundSynonym) {
                return array($word, array());
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
                return array($foundSynonym->value, $synonyms);
            }

            return array($foundSynonym->value, $allSynonyms);
        }

        $allSynonyms = $this->synonymsDao->getAllSynonymsByWordId($found->id);

        return array($found->value, $allSynonyms);
    }

    /**
     * @return WordWithoutId[]
     * @throws Exception
     */
    public function getWords(): array
    {
        return $words = $this->synonymsDao->getWords();
    }
}