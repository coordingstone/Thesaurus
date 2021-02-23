<?php

use DI\Container;
use DI\ContainerBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Thesaurus\Datamodels\Synonym;
use Thesaurus\Datamodels\Word;
use Thesaurus\Service\SynonymsService;

class SynonymsServiceTest extends TestCase
{

    /**
     * @var MockObject
     */
    private MockObject $dao;

    /**
     * @var SynonymsService
     */
    private SynonymsService $synonymsService;

    /**
     * @var Container
     */
    private DI\Container $container;

    public function setUp(): void
    {
        parent::setUp();
        $builder = new ContainerBuilder();
        $this->container = $builder->build();

        $dbMock = $this->getMockBuilder('Thesaurus\Database\Db')->disableOriginalConstructor()->getMock();
        $this->container->set('Thesaurus\Database\Db', $dbMock);

        $this->dao = $this->getMockBuilder('Thesaurus\Dao\SynonymsDao')
            ->setConstructorArgs(array($this->container->get('Thesaurus\Database\Db')))
            ->getMock();

        $this->synonymsService = new SynonymsService($this->dao);
    }

    /**
     * @covers \Thesaurus\Service\SynonymsService::getSynonyms
     * @throws Exception
     */
    public function testGetSynonymsFoundWordWithSynonyms()
    {

        $word = Word::withDbRow(["id" => 1, "value" => "test"]);
        $this->dao->method('getWordByValue')->willReturn($word);
        $this->dao->expects($this->once())
            ->method('getWordByValue')
            ->with($word->value);

        $synonym = Synonym::withDbRow(["id" => 1, "word_id" => 1, "value" => "försök"]);

        $this->dao->method('getAllSynonymsByWordId')->willReturn(array($synonym, $synonym, $synonym));
        $this->dao->expects($this->once())
            ->method('getAllSynonymsByWordId')
            ->with($word->id);

        $result = $this->synonymsService->getSynonyms($word->value);


        $this->assertIsArray($result);
        $this->assertEquals($result[0], $word->value);
        $this->assertEquals(count($result[1]), 3);

    }

    /**
     * @covers \Thesaurus\Service\SynonymsService::getSynonyms
     * @throws Exception
     */
    public function testGetSynonymsNoWordNoSynonym()
    {

        $word = Word::withDbRow(["id" => 1, "value" => "test"]);
        $this->dao->method('getWordByValue')->willReturn(false);
        $this->dao->expects($this->once())
            ->method('getWordByValue')
            ->with($word->value);

        $this->dao->method('getSynonymByValue')->willReturn(false);
        $this->dao->expects($this->once())
            ->method('getSynonymByValue')
            ->with($word->value);

        $result = $this->synonymsService->getSynonyms($word->value);


        $this->assertIsArray($result);
        $this->assertEquals($result[0], $word->value);
        $this->assertEmpty($result[1]);

    }
}