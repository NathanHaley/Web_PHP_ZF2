<?php

namespace Exam\Controller;

use Util\Controller\UtilBaseController;
use Application\Model\Application;


class CertController extends UtilBaseController
{

    public function indexAction()
    {

    }

    public function userCertListAction()
    {
        $em = $this->serviceLocator->get('entity-manager');

        $qb = $em->createQueryBuilder();

        $qb->select(['id', 'title',])

        $userCerts = $entityManager->

    }

    public function viewAction()
    {
        $id = $this->params('id');
        if (! $id) {
            return $this->redirect()->toRoute('exam/list');
        }

        $pdfService = $this->serviceLocator->get('pdf');
        $user = $this->serviceLocator->get('user');


        $pdf = $pdfService->generateCertificate($user, $id);

        $response = $this->getResponse();

        // We need to set a content-type header so that the
        // browser is able to recognize our pdf and display it
        $response->getHeaders()->addHeaderLine('Content-Type: application/pdf');

        $response->setContent($pdf->render());

        // Just return the object by itself to display without layout
        return $response;
    }

}
