<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Exam for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Exam\Controller;

use Exam\Form\Element\Question\QuestionInterface;
use Exam\Model\Test;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Adapter\DbSelect as PaginatorDbAdapter;
use Zend\Paginator\Paginator;
use Zend\EventManager\EventManager;
use Application\Model\Application;
use Zend\Db\Sql\Select;

class ExamController extends AbstractActionController
{

    public function indexAction()
    {
        return array();
    }

    public function editAction()
    {
        return array();
    }

    public function deleteAction()
    {
        return array();
    }

    public function takeAction()
    {
        $startTime = time();
        //if(! isset($user->examStartTime)){
        //   $user->examStartTime = time();
        //}

        $id = $this->params('id');
        if (! $id) {
            return $this->redirect()->toRoute('exam/list');
        }

        $testManager = $this->serviceLocator->get('test-manager');
        $form = $testManager->createForm($id);

        $form->setAttribute('method', 'POST');

        $form->add(array(
            'type' => 'Zend\Form\Element\Csrf',
            'name' => 'security'
        ));

        $form->add(array(
            'type' => 'submit',
            'name' => 'submit',
            'attributes' => array(
                'value' => 'Ready'
            )
        ));

        if ($this->getRequest()->isPost()) {
            // If we have post request -> check how many correct answers are correct
            $user = $this->serviceLocator->get('user');
            $data = $this->getRequest()->getPost();
            $form->setData($data);
            $correct = 0;
            $total = 0;

            //get the duration
            $duration = time() - $startTime;

            $attemptEntity = $this->serviceLocator->get('test-attempt-entity');

            $attemptEntity->setUser_id($user->getId());
            $attemptEntity->setTest_id($id);
            $attemptEntity->setStime($startTime);
            $attemptEntity->setDuration($duration);

            $entityManager = $this->serviceLocator->get('entity-manager');

            $entityManager->persist($attemptEntity);
            $entityManager->flush();

            $testAttemptAnswerManager = $this->serviceLocator->get('test-attempt-answer-manager');

            foreach ($form as $k => $element) {

                if ($element instanceof QuestionInterface) {
                    $total ++;
                    $form->setValidationGroup($element->getName());

                    $isValid = $form->isValid();

                    if ($isValid) {
                        $correct ++;
                    }

                    $answerEntity = $this->serviceLocator->get('test-attempt-answer-entity');

                    $answerEntity->setXuta_id($attemptEntity->getId());
                    $answerEntity->setXeq_id(str_replace('q','',$element->getName()));
                    $answerEntity->setAnswer($element->getValue());
                    $answerEntity->setValid(intval($isValid));


                    $testAttemptAnswerManager->store($answerEntity->toArray());
                }
            }


            $score = intval(round($correct/$total*100));

            if ($score === 0) {

                $this->flashmessenger()->addInfoMessage('No correct answers. That is sad... but you can try again.');

            } elseif ($score === 100) {

                // All answers were answered correctly.
                $this->flashmessenger()->addSuccessMessage('Great! You have 100% correct answers. You should recieve your certificate in email soon.');

                $newEvent = new EventManager('exam');
                $newEvent->trigger('taken-excellent', $this, array (
                    'user' => $user,
                    'examId' => $id
                ));

            } else {

                $this->flashmessenger()->addInfoMessage(sprintf('Score = %d%%, %d out of total %d correct.100%% correct needed for certificate.', $score, $correct, $total));

            }

            $attemptEntity->setPass(intval($score === 100));
            $attemptEntity->setScore_pct($score);

            $entityManager->persist($attemptEntity);
            $entityManager->flush();

            return $this->redirect()->toRoute('exam/list');
        }

        return array(
            'form' => $form
        );
    }

    /**
     * Deletes all tests in the database and adds the default ones.
     */
    public function resetAction()
    {
        $model = new Test();
        $model->delete(array());

        // fill the default tests
        $manager = $this->serviceLocator->get('test-manager');
        $tests = $manager->getDefaultTests();
        foreach ($tests as $test) {
            $data = $test['info'];
            $data['definition'] = json_encode($test);
            $manager->store($data);
        }

        $this->flashmessenger()->addSuccessMessage('Tests reset to default tests.');
        return $this->redirect()->toRoute('exam/list');
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

    public function certificateAction()
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

        // if we want to shortcut the execution we just return the
        // response object and then the view and the layout are not
        // rendered at all
        return $response;
    }
}
