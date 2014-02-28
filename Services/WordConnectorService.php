<?php

/**
 * This file is part of the Integrated package.
 *
 * (c) e-Active B.V. <integrated@e-active.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Integrated\Bundle\WordConnectorBundle\Services;

use Integrated\Bundle\ContentBundle\Document\Content\Article;
use Integrated\Bundle\ContentBundle\Document\Content\File;
use Doctrine\Common\Collections\ArrayCollection;
use Funstaff\Tika\Wrapper;
use Funstaff\Tika\ConfigurationInterface;
use Symfony\Component\DomCrawler\Crawler;
use Funstaff\Tika\Document;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
/**
 * @author Nizar Ellouze <integrated@e-active.nl>
 */
class WordConnectorService extends Wrapper
{
    /**
     * @var Doctrine\Bundle\MongoDBBundle\ManagerRegistry
     */    
    private $doctrineMongodb;
    /**
     * @var Symfony\Component\DomCrawler\Crawler
     */    
    private $crawler;
    
    /**
     * Constructor
     * @param Doctrine\Bundle\MongoDBBundle\ManagerRegistry $doctrineMongodb
     * @param Symfony\Component\DomCrawler\Crawler $crawler
     * @param Funstaff\Tika\ConfigurationInterface $config
     */
    public function __construct(ManagerRegistry $doctrineMongodb, Crawler $crawler, ConfigurationInterface $config)
    {
        $this->doctrineMongodb = $doctrineMongodb;
        $this->crawler = $crawler;
        parent::__construct($config);
    }

    /**
     * Upload and save word content into article
     *
     * @param Array $files
     * @param String $type
     * @return boolean
     */
    public function convert($files, $type)
    {
        foreach ($files as $file) {
            $fileName = $file["fileName"];
            $path = $file["path"];
            $document = new Document($fileName, $path);
            $this->addDocument($document);
            $this->execute();
        }
        foreach ($files as $file) {
            $fileName = $file["fileName"];
            $document = $this->getDocument($fileName);
            $metadata = $document->getMetadata();
            $author = $metadata->get("Author");
            $title = $metadata->get("dc:title");

            if ($title == "") {
                $this->crawler->addContent($document->getContent());
                $title = $this->crawler->filter('body p:first-child b')->text();
                if ($title == "") {
                    $title = $fileName;
                }
            }
            //Create new Article and add images as Files.
            $article = new Article();
            $authors = new ArrayCollection();
            $authors->add($author);
            //$article->setAuthors($authors);
            $article->setContentType($type);
            $article->setTitle($title);
            $article->setContent($document->getContent());
            $references = new ArrayCollection();
            $dm = $this->doctrineMongodb->getManager();
            foreach ($document->images as $image) {
                $uploadedFile =new UploadedFile("upload/" . $document->getName() . "/" . $image, 'original', 'mime/original', 123, UPLOAD_ERR_OK, true);
                $file = new File();
                $file->setFile($uploadedFile);
                $references->add($file);
            }
            //$article->setReferences($references);
            $dm->persist($article);
            $dm->flush();
        }

        return true;
    }

    /**
     * Execute
     *
     * @return Integrated\Bundle\WordConnectorBundle\Services\WordConnectorService
     */
    public function execute()
    {
        parent::execute();
        $base = $this->generateCommand();
        foreach ($this->document as $name => $doc) {
            if ($doc->getPassword()) {
                $command = sprintf('%s --password=%s', $base, $doc->getPassword());
            } else {
                $command = $base;
            }
            if ($this->logger) {
                $this->logger->addInfo(sprintf('Tika command: "%s"', $command));
            }
            ob_start();
            if (!is_dir("upload/" . $doc->getName())) {
                mkdir("upload/" . $doc->getName());
            }
            $cmd = sprintf("$command -z --extract-dir=./upload/" . $doc->getName() . " %s", $doc->getPath());
            passthru($cmd);
            ob_get_clean();
            $images = scandir("upload/" . $doc->getName());
            array_shift($images);
            array_shift($images);
            $doc->images=$images;
        }
        
        return $this;
    }

    /**
     * Generate Command
     *
     * @return string $command
     */
    private function generateCommand()
    {

        $java = $this->config->getJavaBinaryPath() ? : 'java';
        $command = sprintf('%s -jar %s', $java, $this->config->getTikaBinaryPath());

        if (!$this->config->getMetadataOnly()) {
            $command .= ' --' . $this->config->getOutputFormat();
        } else {
            $command .= ' --json';
        }

        $command .= sprintf(' --encoding=%s', $this->config->getOutputEncoding());

        return $command;
    }

}