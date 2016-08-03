<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Exam for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Exam\Controller;

use Util\Controller\UtilBaseController;
use Exam\Form\Element\Question\QuestionInterface;
use Exam\Model\Test;
use Zend\EventManager\EventManager;


class ExamController extends UtilBaseController
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
        //@todo how best to handle timer/duration
        $startTime = time();
        //if(! isset($user->examStartTime)){
        //   $user->examStartTime = time();
        //}

        $examId = $this->params('id');
        if (! $examId) {
            return $this->redirect()->toRoute('exam/list');
        }

        $testManager = $this->serviceLocator->get('test-manager');

        $formDetails = $testManager->get($examId);
        $form = $testManager->createForm($examId);

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
            // If we have post request -> check how many answers are correct
            $user = $this->serviceLocator->get('user');
            $data = $this->getRequest()->getPost();
            $form->setData($data);
            $correct = 0;
            $total = 0;

            //get the duration
            $duration = intval(time() - $startTime);

            $attemptEntity = $this->serviceLocator->get('test-attempt-entity');
            //$attemptAnswerEntity = $this->serviceLocator->get('test-attempt-answer-entity');

            $attemptEntity->setaUserId($user->getId());
            $attemptEntity->setEeId($examId);
            //$attemptEntity->setStartTs($startTime);
            //$attemptEntity->setDuration($duration);

            $entityManager = $this->serviceLocator->get('entity-manager');
            //$attemptAnswerManager = $this->serviceLocator->get('test-attempt-answer-manager');

            $attemptEntity->setAddStamp($user->getId());

            $entityManager->persist($attemptEntity);
            $entityManager->flush();


            foreach ($form as $k => $element) {

                if ($element instanceof QuestionInterface) {
                    $total ++;
                    $form->setValidationGroup($element->getName());

                    $isValid = $form->isValid();

                    if ($isValid) {
                        $correct ++;
                    }

                    $answerEntity = $this->serviceLocator->get('test-attempt-answer-entity');

                    $answerEntity->setEeaId($attemptEntity->getId());
                    $answerEntity->setEeqId(str_replace('q','',$element->getName()));
                    $answerEntity->setAnswer($element->getValue());
                    $answerEntity->setIsValid(intval($isValid));

                    $answerEntity->setAddStamp($user->getId());
                    //$testAttemptAnswerManager->store($answerEntity->toArray());

                    $entityManager->persist($answerEntity);
                    $entityManager->flush();
                    $entityManager->clear();
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
                    'examId' => $examId
                ));

            } else {

                $this->flashmessenger()->addInfoMessage(sprintf('Score = %d%%, %d out of total %d correct.', $score, $correct, $total));
                $this->flashmessenger()->addInfoMessage('100% correct needed for certificate.');
            }

            $attemptEntity->setPass(intval($score === 100));
            $attemptEntity->setScorePct($score);

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

}
