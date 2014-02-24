<?php

/**
 * This file is part of the Integrated package.
 *
 * (c) e-Active B.V. <integrated@e-active.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Integrated\Bundle\WordConnectorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Nizar Ellouze <integrated@e-active.nl>
 */
class WordConnectorController extends Controller {

    /**
     * Render the upload form
     *
     * @return Response A Response instance
     */
    public function indexAction() {
        $documentTypes = $this->getReader()->readAll();

        return $this->render('IntegratedWordConnectorBundle:Form:upload.html.twig', array('documentTypes' => $documentTypes));
    }

    /**
     * Upload and save word content into article
     *
     * @param Request $request
     * 
     * @return Response A Response instance
     */
    public function saveAction(Request $request) {

        $files = $request->files;
        $type = $request->get("type");
        $directory = "upload";
        foreach ($files as $uploadedFile) {
            $fileFullName = $uploadedFile->getClientOriginalName();
            $extension = $uploadedFile->getClientOriginalExtension();
            $fileName = str_replace("." . $extension, "", $fileFullName);
            $uploadedFile->move($directory, $fileFullName);
            $documents[]=array("fileName"=>$fileName ,"path"=> $directory . "/" . $fileFullName);
        }
        $wordConnectorService = $this->get('integrated_word_connector.word_connector_service');
        if ($wordConnectorService->convert($documents, $type)) {

            return new Response('File Imported.');
        } else {

            return new Response('An error occured while importing the file.');
        }
    }

    /**
     * Get reader document form service container
     *
     * @return \Integrated\Common\Content\Reader\Document
     */
    protected function getReader() {
        $this->reader = $this->get('integrated_content.reader.document');

        return $this->reader;
    }

}