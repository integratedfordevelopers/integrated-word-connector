<?php

/*
 * This file is part of the Integrated package.
 *
 * (c) e-Active B.V. <integrated@e-active.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Integrated\Tests\Common\ContentType\Mapping\Driver;

use Doctrine\Common\Annotations\Reader;
use Integrated\Common\ContentType\Mapping\Annotations\Document;
use Integrated\Common\ContentType\Mapping\Annotations\Field;
use Integrated\Common\ContentType\Mapping\Driver\AnnotationsDriver;
use Integrated\Bundle\WordConnectorBundle\Services\WordConnectorService;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
/**
 * @author Jeroen van Leeuwen <jeroen@e-active.nl>
 */
class WordConnectorServiceTest extends WebTestCase
{
    private $wordConnectorService;

    /**
     * Setup the test
     */
    protected function setUp()
    {
        $client = static::createClient();
        $container = $client->getContainer();
        
         $this->wordConnectorService = $container->get('integrated_word_connector.word_connector_service');
        // Mocks
   /*    $tika = $this->getMockBuilder('Funstaff\TikaBundle\Wrapper\Tika')
    ->disableOriginalConstructor()
    ->getMock();
        $crawler = $this->getMock('Symfony\Component\DomCrawler\Crawler');
        $doctrineMongodb = $this->getMockBuilder('Doctrine\Bundle\MongoDBBundle\ManagerRegistry')
    ->disableOriginalConstructor()
    ->getMock();

        // Create WordConnectorService
        $this->wordConnectorService = new WordConnectorService($tika,$doctrineMongodb,$crawler);*/
    }

    /**
     * Test the WordConnectorServiceTest convert function
     */
    public function testConvert()
    {
         
         $documents[]=array("fileName"=>"Example_document" ,"path"=>  "web/upload/Example_document.doc");
         $type="Integrated\Bundle\ContentBundle\Document\Content\Relation\Company";

        // Assert
        $this->assertTrue( $this->wordConnectorService->convert($documents,$type));
    }

}


