<?php
namespace ContactUs\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\Paginator\Adapter\DbSelect as PaginatorDbAdapter;
use Zend\Paginator\Paginator;
use ContactUs\Model\ContactUs as ContactUsModel;
use Application\Model\Application;



class IndexController extends AbstractActionController
{
    //Handles Contact us form submissions
    public function addAction()
    {

    }

    //Stub for possible future admin action
    public function editAction()
    {
        $id = $this->params('id');
        $entityManager = $this->serviceLocator->get('entity-manager');

        if(!$id) {
            return $this->redirect()->toRoute('contactus/default', array(
                'controller' => 'index',
                'action' => 'list',
            ));
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

                $entity = $entityManager->merge($entity);
                $entityManager->flush();

                $this->flashmessenger()->addSuccessMessage("Contact Us Comment id: {$entity->getId()} was updated successfully.");

                // redirect the user to the view user action
                return $this->redirect()->toRoute('contactus/default', array (
                    'controller' => 'index',
                    'action'     => 'list',
                    'id'		 => $entity->getId()
                ));
            }
        }

        // pass the data to the view for visualization
        return array('form1'=> $form);

    }

    //Handles deleting message records
    public function deleteAction()
    {
        $id = $this->params('id');
        if(!$id) {
            return $this->redirect()->toRoute('contactus/default', array(
                'controller' => 'contactus',
                'action' => 'list',
            ));
        }

        $entityManager = $this->serviceLocator->get('entity-manager');

        $commentEntity = $this->findCommentEntity($id, $entityManager);

        $commentEntity = $entityManager->merge($commentEntity);

        $this->flashmessenger()->addSuccessMessage("Contact Us Comment id: $id removed successfully.");

        $entityManager->remove($commentEntity);
        $entityManager->flush();

        // For now redirect to only the list page
        return $this->redirect()->toRoute('contactus/default', array (
            'controller' => 'contactus',
            'action'     => 'list'
        ));

    }

    //Lists messages for admins
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
                            'time'      => 'cdate'
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

                // redirect the user to the view user action
                return $this->redirect()->toRoute('contactus', array (
                    'controller' => 'Index',
                    'action'     => 'index',
                ));

            }
        }

        return array('form1' => $form);
    }

    public function viewAction()
    {
        $id = $this->params('id');

        //If no id then send to Me page to see themselves
        if(!$id) {
            return $this->redirect()->toRoute('contactus/default', array(
                'controller' => 'contactus',
                'action' => 'list',
            ));
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
