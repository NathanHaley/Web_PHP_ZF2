<?php
namespace Exam\Controller;

use Util\Controller\UtilBaseController;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter;

class CertController extends UtilBaseController
{

    public function indexAction()
    {
        return [];
    }

    public function viewAction()
    {
        $user = $this->identity();
        $examId = $this->params()->fromRoute('id', 0);

        //No id send them home
        if ($examId === 0) {
            return $this->redirect()->toRoute('home');
        }

        $em = $this->serviceLocator->get('entity-manager');
        $conn = $em->getConnection();
        $qb = $conn->createQueryBuilder();

        $qb
            ->select('file', 'name', 'logo' )
            ->from('e_exam_attempt_pass_view')
            ->where($qb->expr()->andX(
                $qb->expr()->eq('a_user_id', $user->getId()),
                $qb->expr()->eq('ee_id', $examId)
                ));

        $query = $qb->execute();
        $result = $query->fetchAll();
        //var_dump($result[0]['name']);die();
        //If no record send them home
        if (! $result) {
            return $this->redirect()->toRoute('home');
        }

        $pdfService = $this->serviceLocator->get('pdf');

        $pdf = $pdfService->generateCertificate($user, $result[0]['name'], $result[0]['logo']);

        $response = $this->getResponse();

        $response->getHeaders()->addHeaderLine('Content-Type: application/pdf');

        $response->setContent($pdf->render());

        return $response;
    }

    public function usercertlistAction()
    {
        $user = $this->identity();
        $currentPage = $this->params()->fromRoute('page', 1);

        //orderby, order values whitelisted in list route config
        $orderby = $this->params()->fromRoute('orderby', 'add_ts');
        $order = $this->params()->fromRoute('order', 'desc');

        $em = $this->serviceLocator->get('entity-manager');

        $conn = $em->getConnection();

        $queryBuilder = $conn->createQueryBuilder();

        $queryBuilder
            ->select('*')
            ->from('e_exam_attempt_pass_view')
            ->where('a_user_id = ?')
            ->setParameter(0, $user->getId())
            ->orderby($orderby,$order) ;

        $query = $queryBuilder->execute();

        $result = $query->fetchAll();

        $paginator = new Paginator(new ArrayAdapter($result));

        $paginator->setCurrentPageNumber($currentPage);
        $paginator->setItemCountPerPage(4);

        $acl = $this->serviceLocator->get('acl');

        return array(
            'entities'      => $paginator,
            'acl'           => $acl,
            'page'          => $currentPage,
            'orderby'       => $orderby,
            'order'         => $order,
            'route'         => 'usercertlist',
            'controller'    => 'cert'
        );
    }
}
