<?php
namespace ContactUs\Controller;

use Util\Controller\UtilBaseController;
use Zend\Paginator\Adapter\DbSelect as PaginatorDbAdapter;
use Zend\Paginator\Paginator;
use ContactUs\Model\ContactUs as ContactUsModel;
use Application\Model\Application;



class ContactUsListController extends UtilBaseController
{
    //Lists contact us messages for admins
    public function listAction()
    {
        $currentPage = $this->params()->fromRoute('page', 1);

        //orderby,order whitelisted in list route config
        $orderby = $this->params()->fromRoute('orderby', 'cdate');
        $order = $this->params()->fromRoute('order', 'desc');


        $orderby_tmp = strtolower($orderby);

        $contactUsModel = new ContactUsModel;

        $result = $contactUsModel->getSql()
                    ->select()
                        ->columns([
                            'id'        => 'id',
                            'name'      => 'fullname',
                            'email'     => 'email',
                            'comments'  => 'comments',
                            'time'      => 'add_ts'
                        ])
                    ->order("$orderby_tmp $order");

        $adapter = new PaginatorDbAdapter($result, $contactUsModel->getAdapter());
        $paginator = new Paginator($adapter);

        $paginator->setCurrentPageNumber($currentPage);
        $paginator->setItemCountPerPage(4);

        $acl = $this->serviceLocator->get('acl');

        //top keys match db columns
        $columns = [
            'email'     =>['th_text'=>'email', 'th_attributes'=>['nowrap'=>'true'], 'td_formats'=>['tcDefaultCellFormat'=>'%s']],
            'name'      =>['th_text'=>'name', 'th_attributes'=>['nowrap'=>'true'], 'td_formats'=>['tcDefaultCellFormat'=>'%s']],
            'comments'  =>['th_text'=>'comments', 'th_attributes'=>['nowrap'=>'true'],'td_formats'=>['tcDefaultCellFormat'=>'%s']],
            'time'      =>['th_text'=>'time', 'th_attributes'=>['nowrap'=>'true'], 'td_attributes'=>[],'td_formats'=>['tcDefaultCellFormat'=>'%s']],

        ];

        $listActions = [
            'view'       =>['text'=>'view', 'styleClass'=>Application::BTN_TAKE_DEFAULT],
            'edit'       =>['text'=>'edit', 'styleClass'=>Application::BTN_EDIT_DEFAULT],
            'delete'     =>['text'=>'delete', 'styleClass'=>Application::BTN_DELETE_DEFAULT],
        ];

        return array(
            'entities'  => $paginator,
            'acl'       => $acl,
            'page'      => $currentPage,
            'orderby'   => $orderby,
            'order'     => $order,
            'columns'   => $columns,
            'listActions'   => $listActions,
            'pageTitle' => 'Admin List Of Contact Us Messages',
            'route'     => 'contactus',
            'controller'=> 'index'
        );
    }
}
