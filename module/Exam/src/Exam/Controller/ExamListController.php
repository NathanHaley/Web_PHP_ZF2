<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Exam for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Exam\Controller;

use NHUtils\Controller\NHUtilsBaseController;
use Exam\Model\Test;
use Zend\Paginator\Adapter\DbSelect as PaginatorDbAdapter;
use Zend\Paginator\Paginator;
use Application\Model\Application;
use Zend\Db\Sql\Select;

class ExamListController extends NHUtilsBaseController
{

    public function indexAction()
    {
        return array();
    }

    public function listAction()
    {
        $user = $this->serviceLocator->get('user');
        $currentPage = $this->params()->fromRoute('page', 1);

        //orderby, order values whitelisted in list route config
        $orderby = $this->params()->fromRoute('orderby', 'name');
        $order = $this->params()->fromRoute('order', 'desc');

        $sql = new Select();
        $sql->columns([
            'score' => new \Zend\Db\Sql\Expression("MAX(score_pct)"),
            'pass'  => 'pass',
            'test_id' => 'test_id'
        ])
        ->from('x_users_tests_attempts')
        ->where(['user_id' => $user->getId()])
        ->group('test_id');

        $testModel = new Test();
        $result = $testModel->getSql()
        ->select()
        ->columns([
            'id',
            'name',
            'description',
            'duration',
        ])
        ->join(
            ['ta' => $sql
            ],
            'tests.id = ta.test_id',
            ['score','pass'],Select::JOIN_LEFT)
            ->where([
                'active' => 1
            ])->order("$orderby $order");

        $adapter = new PaginatorDbAdapter($result, $testModel->getAdapter());
        $paginator = new Paginator($adapter);

        $paginator->setCurrentPageNumber($currentPage);
        $paginator->setItemCountPerPage(4);

        $acl = $this->serviceLocator->get('acl');

        //top keys match db columns
        $columns = [
            'name'         =>['th_text'=>'name', 'th_attributes'=>['nowrap'=>'true'],'td_formats'=>['tcDefaultCellFormat'=>'%s']],
            'description'  =>['th_text'=>'description', 'th_attributes'=>['nowrap'=>'true'],'td_formats'=>['tcDefaultCellFormat'=>'%s']],
            'duration'     =>['th_text'=>'duration (minutes)', 'th_attributes'=>['nowrap'=>'true'],'td_formats'=>['tcDefaultCellFormat'=>'%s']],
            'score'        =>['th_text'=>'Score %', 'th_attributes'=>['nowrap'=>'true'], 'td_attributes'=>['class'=>'list_exam_score'],'td_formats'=>['tcDefaultCellFormat'=>'%s%%', null=>'%s', '100'=>'<span class="badge badge-exam-score-100"><strong>%s</strong></span>']],
        ];

        $listActions = [
            'take'          =>['text'=>'take', 'styleClass'=>Application::BTN_TAKE_DEFAULT],
            //'edit'       =>['text'=>'edit', 'styleClass'=>Application::BTN_EDIT_DEFAULT],
            //'delete'     =>['text'=>'delete', 'styleClass'=>Application::BTN_DELETE_DEFAULT],
        ];
        return array(
            'entities'      => $paginator,
            'acl'           => $acl,
            'page'          => $currentPage,
            'orderby'       => $orderby,
            'order'         => $order,
            'columns'       => $columns,
            'listActions'   => $listActions,
            'pageTitle'     => 'Exam List',
            'route'         => 'exam',
            'controller'    => 'exam'
        );
    }
}
