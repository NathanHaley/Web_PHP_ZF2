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
use Exam\Form\Element\Question\QuestionInterface;
use Exam\Model\Test;
use Zend\EventManager\EventManager;
use Application\Model\Application;


class ExamController extends NHUtilsBaseController
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

        $formDetails = $testManager->get($id);
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
                $this->flashmessenger()->addSuccessMessage('Awsome! You have 100% correct answers.');
                $this->flashmessenger()->addSuccessMessage('You should recieve your certificate in email soon.');

                $newEvent = new EventManager('exam');
                $newEvent->trigger('taken-excellent', $this, array (
                    'user' => $user,
                    'examId' => $id
                ));

            } else {

                $this->flashmessenger()->addInfoMessage(sprintf('Score = %d%%, %d out of total %d correct.', $score, $correct, $total));
                $this->flashmessenger()->addInfoMessage('100% correct needed for certificate.');
            }

            $attemptEntity->setPass(intval($score === 100));
            $attemptEntity->setScore_pct($score);

            $entityManager->persist($attemptEntity);
            $entityManager->flush();

            return $this->redirect()->toRoute('exam/list');
        }

        return array(
            'form'          => $form,
            'formDetails'   => $formDetails
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
