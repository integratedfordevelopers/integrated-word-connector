<?php

/*
 * This file is part of the Integrated package.
 *
 * (c) e-Active B.V. <integrated@e-active.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Integrated\Bundle\WordConnectorBundle\Controller;

//use Integrated\Common\Content\ContentInterface;
use Integrated\Bundle\ContentBundle\Form\Type\DeleteType;
use Integrated\Bundle\ContentBundle\Document\Content\Content;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DomCrawler\Crawler;
use Integrated\Bundle\ContentBundle\Document\Content\Article;
use Integrated\Bundle\ContentBundle\Document\Content\File;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
/**
 * @author Jan Sanne Mulder <jansanne@e-active.nl>
 */
class WordConnectorController extends Controller {

    /**
     *
     *
     * @Template()
     * @return array
     */
    public function indexAction(Request $request) {
        $documentTypes = $this->getReader()->readAll();
        return $this->render('IntegratedWordConnectorBundle:Form:upload.html.twig', array('documentTypes' => $documentTypes));
    }

    protected function getReader() {
        $this->reader = $this->get('integrated_content.reader.document');
        return $this->reader;
    }

    /**
     * Create a new document
     *
     * @Template()
     * @param Request $request
     * @return array | Response
     */
    public function newAction(Request $request) {

        $files = $request->files;
        $directory = "upload";
        foreach ($files as $uploadedFile) {
            $file_full_name = $uploadedFile->getClientOriginalName();
            $extension = $uploadedFile->getClientOriginalExtension();
            $file_name = str_replace("." . $extension, "", $file_full_name);

            $uploadedFile->move($directory, $file_full_name);

            $tika = $this->get('funstaff.tika');
            $tika->setOutputFormat('xml')
                    ->addDocument($file_name, $directory . "/" . $file_full_name);
            $tika->extractMetadata();
            $tika->extractContent();
            $tika->extractImages();
        }


        $documents = $tika->getDocuments();
        foreach ($documents as $doc) {
            $metadata = $doc->getMetadata();
            $author = $metadata->get("Author");
            $title = $metadata->get("title");
            if ($title == "") {
                $crawler = new Crawler();
                $crawler->addContent($content);

                $title = $crawler->filter('body p:first-child b')->text();
                if ($title == "") {
                    $title = $file_name;
                }
            }

            $type= $request->get("type");

            //Create new Article and add images as Files.
            $article = new Article();
            $article->setContentType($type);
            $article->setTitle($title);
            $article->setContent($doc->getContent());

            $references = new ArrayCollection();
            $dm = $this->get('doctrine_mongodb')->getManager();
            foreach ($doc->getImages() as $image) {
                $file = new File();
                $file->setPath("upload/".$doc->getName()."/".$image);
                $references->add($file);
            }

            $article->setReferences($references);
            $dm->persist($article);
            $dm->flush();
        }

        echo "File was imported";  exit;
    }

  

}