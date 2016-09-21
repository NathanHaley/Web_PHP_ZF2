<?php

namespace ChangeLog\Controller;

use Util\Controller\UtilBaseController;
use ChangeLog\Service\ChangeLogServiceInterface;
use Zend\Paginator\Adapter\DbSelect as PaginatorDbAdapter;
use Zend\Paginator\Paginator;
use Application\Model\Application;
use Zend\Paginator\Adapter\ArrayAdapter;

class ListController extends UtilBaseController
{
    /**
     * @var \ChangeLog\Service\ChangeLogServiceInterface
     */
    protected $changeLogService;

    public function __construct(ChangeLogServiceInterface $changeLogService)
    {
        $this->changeLogService = $changeLogService;
    }

    public function indexAction()
    {
        return $this->redirect()->toRoute('changelog/list');
    }

    public function listJSONAction()
    {
        //orderby,order whitelisted in list route config
        $orderby = $this->params()->fromRoute('orderby', 'id');
        $order = $this->params()->fromRoute('order', 'desc');
        $arr = $this->changeLogService->fetchAllChangeLogsBase3($orderby, $order)->toArray();

        $str =  '{"logs": [';

        foreach ($arr as $v1) {
            $str .= '{';

            foreach($v1 as $k2 =>$v2) {

                if ($v2) {
                    $str .= '"'.$k2.'": "'.$v2.'",';
                }

            }
            $str .= '},';
        }

        $str .= ']}';

        $str = str_replace('",}','"}',$str);
        $str = str_replace('add_ts','date',$str);

        echo str_replace('},]}','}]}',$str);

        die();
    }

    public function listAction()
    {
        $currentPage = $this->params()->fromRoute('page', 1);

        //orderby,order whitelisted in list route config
        $orderby = $this->params()->fromRoute('orderby', 'id');
        $order = $this->params()->fromRoute('order', 'asc');

        $result = $this->changeLogService->fetchAllChangeLogs($orderby, $order)->toArray();

        $paginator = new Paginator(new ArrayAdapter($result));

        $paginator->setCurrentPageNumber($currentPage);
        $paginator->setItemCountPerPage(8);

        $acl = $this->serviceLocator->get('acl');
        $columns = [
            'id'           =>['th_text'=>'id', 'th_attributes'          =>['nowrap'=>'true', 'width'=>'6%'],'td_formats'=>['tcDefaultCellFormat'=>'%s']],
            'add_ts'       =>['th_text'=>'Time Stamp', 'th_attributes'  =>['nowrap'=>'true'], 'td_formats'=>['tcDefaultCellFormat'=>'%s']],
            'description'  =>['th_text'=>'Description', 'th_attributes' =>['nowrap'=>'true'], 'td_formats'=>['tcDefaultCellFormat'=>'%s']],
         ];

        $listActions = [
            'detail'      =>['text'=>'view', 'controller' => 'list', 'styleClass'    =>Application::BTN_VIEW_DEFAULT],
            'edit'       =>['text'=>'edit', 'controller' => 'write', 'styleClass'    =>Application::BTN_EDIT_DEFAULT],
            'delete'     =>['text'=>'delete', 'controller' => 'delete','styleClass'  =>Application::BTN_DELETE_DEFAULT],
        ];

        return array(
            'entities'      => $paginator,
            'acl'           => $acl,
            'page'          => $currentPage,
            'orderby'       => $orderby,
            'order'         => $order,
            'columns'       => $columns,
            'listActions'   => $listActions,
            'pageTitle'     => 'Change Log List',
            'route'         => 'changelog',
            'controller'    => 'list'
        );
    }

    public function detailAction()
    {
        $id = $this->params()->fromRoute('id');

        try {
            $changeLog = $this->changeLogService->fetchChangeLog($id);
        } catch (\InvalidArgumentException $ex) {
            $this->flashMessenger()->addErrorMessage('The identifier for the Change Log was invalid.');
            return $this->redirect()->toRoute('changelog/list');
        }

        return [
            'changeLog' => $changeLog
        ];
    }

}
