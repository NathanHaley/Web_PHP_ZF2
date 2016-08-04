<?php
namespace ContactUs\Controller;

use Util\Controller\UtilBaseController;
use Zend\Form\Annotation\AnnotationBuilder;
use Application\Model\Application;

class IndexController extends UtilBaseController
{

    public function addAction()
    {

    }

    //Handles admin edits to existing messages
    public function editAction()
    {
        $id = $this->params('id');
        $entityManager = $this->serviceLocator->get('entity-manager');

        if(!$id) {
            return $this->redirect()->toRoute('contactus/list');
        }

        $entity = $this->findCommentEntity($id, $entityManager);

        $form = $this->setupCommentDetailForm($entity);

        if($this->getRequest()->isPost()) {
            $data = array_merge_recursive(
                $this->getRequest()->getPost()->toArray(),
                // Notice: make certain to merge the Files also to the post data
                $this->getRequest()->getFiles()->toArray()
                );
            $form->setData($data);
            if($form->isValid()) {

                if ($this->identity()) {
                    $userId = $this->identity()->getId();
                    $entity->setModStamp($userId);

                    //@todo add field to entity
                    //$entity->setAUserId($userId);
                } else {
                    $entity->setModStamp(0);
                }

                $entity = $entityManager->merge($entity);
                $entityManager->flush();

                $this->flashmessenger()->addSuccessMessage("Contact Us Comment id: {$entity->getId()} was updated successfully.");

                // redirect the user to the view user action
                return $this->redirect()->toRoute('contactus/list');
            }
        }

        // pass the data to the view for visualization
        return array('form1'=> $form);

    }

    //Handles admin deleting message records
    public function deleteAction()
    {
        $id = $this->params('id');
        if(!$id) {
            return $this->redirect()->toRoute('contactus/list');
        }

        $entityManager = $this->serviceLocator->get('entity-manager');

        $commentEntity = $this->findCommentEntity($id, $entityManager);

        $commentEntity = $entityManager->merge($commentEntity);

        $this->flashmessenger()->addSuccessMessage("Contact Us Comment id: $id removed successfully.");

        $entityManager->remove($commentEntity);
        $entityManager->flush();

        // For now redirect to only the list page
        return $this->redirect()->toRoute('contactus/list');

    }

    //Handles Contact us form submissions
    public function indexAction()
    {
        $entity = $this->serviceLocator->get('contactus-entity');

        $config = $this->serviceLocator->get('config');
        $adminEmail = $config['application']['admin-email'];

        $builder = new AnnotationBuilder();

        $form = $builder->createForm($entity);

        $form->add(array(
            'name' => 'csrf',
            'type' => 'Zend\Form\Element\Csrf',
        ));

        // This is the submit button
        $form->add(array(
            'name' => 'submit',
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'value' => 'Submit',
                'required' => 'false',
            ),
        ));

        $form->bind($entity);

        if($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost()->toArray();
            $form->setData($data);
            if($form->isValid()) {

                if ($this->identity()) {
                    $userId = $this->identity()->getId();
                    $entity->setAddStamp($userId);

                    //@todo add field to entity
                    //$entity->setAUserId($userId);
                } else {
                    $entity->setAddStamp(0);
                }

                $entityManager = $this->serviceLocator->get('entity-manager');
                $entityManager->persist($entity);
                $entityManager->flush();

                $name = $data['fullname'];
                $email = $data['email'];
                $comments = $data['comments'];

                $headers   = array();
                $headers[] = "MIME-Version: 1.0";
                $headers[] = "Content-type: text/plain; charset=iso-8859-1";
                $headers[] = "From: $email";;
                $headers[] = "Reply-To: $email";

                mail($adminEmail, "Comment from $name", $comments, implode("\r\n", $headers));

                $this->flashmessenger()->addSuccessMessage('Message sent successfully.');

                return $this->redirect()->toRoute('contactus');

            }
        }

        return array('form1' => $form);
    }

    public function viewAction()
    {
        $id = $this->params('id');


        if(!$id) {
            return $this->redirect()->toRoute('contactus/list');
        }

        $commentToView = $this->findCommentEntity($id);


        return array('commentToView' => $commentToView);
    }

    /****HELPER FUNCTIONS****/

    protected function findCommentEntity($id, $entityManager = null)
    {
        if($entityManager === null) {
            $entityManager = $this->serviceLocator->get('entity-manager');
        }
        $config = $this->serviceLocator->get('config');
        $entity = $entityManager->find($config['service_manager']['invokables']['contactus-entity'], $id);

        return $entity;
    }

    protected function formAddFields($form)
    {
        // Protect our form
        $form->add(array(
            'name' => 'csrf',
            'type' => 'Zend\Form\Element\Csrf',
        ));

        // This is the submit button
        $form->add(array(
            'name' => 'submit',
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'value' => 'Submit',
                'required' => 'false',
            ),
        ));

        return $form;
    }

    protected function setupCommentDetailForm($entity)
    {
        $commentEntity = $entity;

        $builder = new AnnotationBuilder();

        $form = $builder->createForm($commentEntity);

        $form = $this->formAddFields($form);

        $form->bind($commentEntity);

        return $form;

    }




}
