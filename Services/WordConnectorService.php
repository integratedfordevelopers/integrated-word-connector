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
use Funstaff\TikaBundle\Wrapper\Tika;
use Symfony\Component\DomCrawler\Crawler;
/**
 * @author Nizar Ellouze <nizarellouze@yahoo.fr>
 */
class WordConnectorService
{

    private $tika;
    private $doctrineMongodb;
    private $crawler;
    public function __construct(Tika $tika, $doctrineMongodb,Crawler $crawler)
    {
        $this->tika = $tika;
        $this->doctrineMongodb=$doctrineMongodb;
        $this->crawler= $crawler;
    }
    /**
     * Upload and save word content into article
     *
     * @param Request $request
     * 
     * @return Response A Response instance
     */
    public function convert($files,$type)
    {
        
        foreach ($files as $file ) {
            $fileName =$file["fileName"];
            $path=$file["path"];
            $this->tika->setOutputFormat('xml');
            $this->tika->addDocument($fileName, $path);
            $this->tika->extractMetadata();
            $this->tika->extractContent();
            $this->tika->extractImages();
        }

        $documents = $this->tika->getDocuments();
        foreach ($documents as $doc) {
            $metadata = $doc->getMetadata();
            $author = $metadata->get("Author");
            $title = $metadata->get("title");
            if ($title == "") {
                $this->crawler->addContent($doc->getContent());
                $title = $this->crawler->filter('body p:first-child b')->text();
                if ($title == "") {
                    $title = $fileName;
                }
            }

            //Create new Article and add images as Files.
            $article = new Article();
            $article->setContentType($type);
            $article->setTitle($title);
            $article->setContent($doc->getContent());

            $references = new ArrayCollection();
            $dm = $this->doctrineMongodb->getManager();
            foreach ($doc->getImages() as $image) {
                $file = new File();
                $file->setPath("upload/" . $doc->getName() . "/" . $image);
                $references->add($file);
            }

            //$article->setReferences($references);
            $dm->persist($article);
            $dm->flush();
        }

        return true;
    }

    
}