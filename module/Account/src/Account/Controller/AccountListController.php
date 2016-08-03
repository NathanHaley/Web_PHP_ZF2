<?php
namespace Account\Controller;

use Account\Model\User as UserModel;
use Util\Controller\UtilBaseController;
use Zend\Paginator\Adapter\DbSelect as PaginatorDbAdapter;
use Zend\Paginator\Paginator;
use Application\Model\Application;

class AccountListController extends UtilBaseController
{

    public function indexAction()
    {
        return array();
    }

    public function listAction()
    {
        $currentPage = $this->params()->fromRoute('page', 1);

        //orderby,order whitelisted in list route config
        $orderby = $this->params()->fromRoute('orderby', 'id');
        $order = $this->params()->fromRoute('order', 'desc');

        $orderby_tmp = strtoupper($orderby);

        $userModel = new UserModel();
        $result = $userModel->getSql()->select()->order("$orderby_tmp $order");

        $adapter = new PaginatorDbAdapter($result, $userModel->getAdapter());
        $paginator = new Paginator($adapter);

        $paginator->setCurrentPageNumber($currentPage);
        $paginator->setItemCountPerPage(4);

        $acl = $this->serviceLocator->get('acl');
        $columns = [
                'id'            =>['th_text'=>'id',    'th_attributes'  =>['nowrap'=>'true', 'width'=>'6%'],'td_formats'=>['tcDefaultCellFormat'=>'%s']],
                'email'         =>['th_text'=>'email', 'th_attributes'  =>['nowrap'=>'true'], 'td_formats'=>['tcDefaultCellFormat'=>'%s']],
                'display_name'  =>['th_text'=>'name', 'th_attributes'   =>['nowrap'=>'true'], 'td_formats'=>['tcDefaultCellFormat'=>'%s']],
                'role'          =>['th_text'=>'role', 'th_attributes'   =>['nowrap'=>'true'], 'td_formats'=>['tcDefaultCellFormat'=>'%s']],

        ];

        $listActions = [
            'view'       =>['text'=>'view', 'styleClass'=>Application::BTN_TAKE_DEFAULT],
            'edit'       =>['text'=>'edit', 'styleClass'=>Application::BTN_EDIT_DEFAULT],
            'delete'     =>['text'=>'delete', 'styleClass'=>Application::BTN_DELETE_DEFAULT],
        ];

        return array(
            'entities'      => $paginator,
            'acl'           => $acl,
            'page'          => $currentPage,
            'orderby'       => $orderby,
            'order'         => $order,
            'columns'       => $columns,
            'listActions'   => $listActions,
            'pageTitle'     => 'Admin User List',
            'route'         => 'user',
            'controller'    => 'account'
        );
    }

}
