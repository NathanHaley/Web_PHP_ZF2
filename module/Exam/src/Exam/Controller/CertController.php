<?php
namespace Exam\Controller;

use Util\Controller\UtilBaseController;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter;

class CertController extends UtilBaseController
{

    public function indexAction()
    {

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
            ->orderby("$orderby");
        
        $query = $queryBuilder->execute();
       
        $result = $query->fetchAll();
           // var_dump($result);die(__LINE__);

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
