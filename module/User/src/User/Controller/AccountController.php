<?php
namespace User\Controller;

use User\Model\User as UserModel;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\EventManager\EventManager;
use Zend\Paginator\Adapter\DbSelect as PaginatorDbAdapter;
use Zend\Paginator\Paginator;

class AccountController extends AbstractActionController
{
    public function indexAction()
    {
        return array();
    }

    public function addAction()
    {

        $entity = $this->serviceLocator->get('user-entity');

        $builder = new AnnotationBuilder();
        $entity = $this->serviceLocator->get('user-entity');
        $form = $builder->createForm($entity);
        $form = $this->formAddFields($form);
        $form->bind($entity);

        if($this->getRequest()->isPost()) {
            $data = array_merge_recursive(
                $this->getRequest()->getPost()->toArray(),
                // Notice: make certain to merge the Files also to the post data
                $this->getRequest()->getFiles()->toArray()
                );
            $form->setData($data);
            if($form->isValid()) {
                // We use now the Doctrine 2 entity manager to save user data to the database
                $entityManager = $this->serviceLocator->get('entity-manager');
                $entityManager->persist($entity);
                $entityManager->flush();

                $this->flashmessenger()->addSuccessMessage('User was added successfully.');

                $event = new EventManager('user');
                $event->trigger('register', $this, array(
                    'user'=> $entity,
                ));

                // redirect the user to the view user action
                return $this->redirect()->toRoute('user/default', array (
                    'controller' => 'account',
                    'action'     => 'view',
                    'id'		 => $entity->getId()
                ));
            }
        }

        // pass the data to the view for visualization
        return array('form1'=> $form);
    }

    /**
     * Expects an array containing user entity, controller action, success message
     * @param unknown $details
     *
     * @todo: work in progress
     */
    protected function processUserDetailForm($details, $caller)
    {
        $user = $details['userEntity'];

        // The annotation builder help us create a form from the annotations in the user entity.
        $builder = new AnnotationBuilder();

        $form = $builder->createForm($user);

        $form = $this->formAddFields($form);

        $builder = new AnnotationBuilder();
        $entity = $this->serviceLocator->get('user-entity');
        $form = $builder->createForm($entity);
        $form = $this->formAddFields($form);
        $form->bind($entity);

        // We bind the entity to the user. If the form tries to read/write data from/to the entity
        // it will use the hydrator specified in the entity to achieve this. In our case we use ClassMethods
        // hydrator which means that reading will happen calling the getter methods and writing will happen by
        // calling the setter methods.
        $form->bind($user);

        if($this->getRequest()->isPost()) {
            $data = array_merge_recursive(
                $this->getRequest()->getPost()->toArray(),
                // Notice: make certain to merge the Files also to the post data
                $this->getRequest()->getFiles()->toArray()
                );
            $form->setData($data);
            if($form->isValid()) {

                $config = $this->serviceLocator->get('config');

                // We use now the Doctrine 2 entity manager to save user data to the database
                $entityManager = $this->serviceLocator->get('entity-manager');

                if ($user->getId()) {
                    //Edit
                    die('editing');
                } else {
                    //Add/Register
                    //Set the default role
                    $user->setRole($config['acl']['defaults']['default_role']);
                    $entityManager->persist($user);
                    $entityManager->flush();

                    $event = new EventManager('user');
                    $event->trigger('register', $this, array(
                        'user'=> $entity,
                    ));

                    $this->flashmessenger()->addSuccessMessage($details['message']);
                    // redirect the user to the view user action
                    return $caller->redirect()->toRoute('user/default', array (
                        'controller' => 'account',
                        'action'     => 'view',
                        'id'		 => $entity->getId()
                    ));

                }

                $this->flashmessenger()->addSuccessMessage($details['message']);



            }
        }

        return $form;

    }

    /*
     * Anonymous users can use this action to register new accounts
     */
    public function registerAction()
    {
        $result = $this->forward()->dispatch('User\Controller\Account', array(
            'action' => 'add',
        ));

        return $result;
    }

    public function viewAction()
    {
        $id = $this->params('id');

        //If no id then send to Me page to see themselves
        if(!$id) {
            return $this->redirect()->toRoute('user/default', array(
                'controller' => 'account',
                'action' => 'me',
            ));
        }

        $userToView = $this->findUserEntity($id);

        //var_dump($userToView);

        return array('userToView' => $userToView);
    }

    public function editAction()
    {

        $id = $this->params('id');
        if(!$id) {
            return $this->redirect()->toRoute('user/default', array(
                'controller' => 'account',
                'action' => 'view',
            ));
        }

        $config = $this->serviceLocator->get('config');

        // The annotation builder help us create a form from the annotations in the user entity.
        $builder = new AnnotationBuilder();

        $userToEdit = $this->findUserEntity($id);

        $form = $builder->createForm($userToEdit);

        $form = $this->formAddFields($form);

        // We bind the entity to the user. If the form tries to read/write data from/to the entity
        // it will use the hydrator specified in the entity to achieve this. In our case we use ClassMethods
        // hydrator which means that reading will happen calling the getter methods and writing will happen by
        // calling the setter methods.
        $form->bind($userToEdit);

        if($this->getRequest()->isPost()) {
            $data = array_merge_recursive(
                $this->getRequest()->getPost()->toArray(),
                // Notice: make certain to merge the Files also to the post data
                $this->getRequest()->getFiles()->toArray()
            );
            $form->setData($data);
            if($form->isValid()) {
                // We use now the Doctrine 2 entity manager to save user data to the database
                $entityManager = $this->serviceLocator->get('entity-manager');

                $entity = $entityManager->merge($userToEdit);
                $entityManager->flush();

                $this->flashmessenger()->addSuccessMessage("User: {$userToEdit->getEmail()} was updated successfully.");

                // redirect the user to the view user action
                return $this->redirect()->toRoute('user/default', array (
                        'controller' => 'account',
                        'action'     => 'list',
                        'id'		 => $entity->getId()
                ));
            }
        }

        // pass the data to the view for visualization
        return array('form1'=> $form);
    }

    public function deleteAction()
    {
        $id = $this->params('id');
        if(!$id) {
            return $this->redirect()->toRoute('user/default', array(
                'controller' => 'account',
                'action' => 'view',
            ));
        }

        $entityManager = $this->serviceLocator->get('entity-manager');
        $userEntity = $this->serviceLocator->get('user-entity');
        $userEntity->setId($id);
        $userEntity = $entityManager->merge($userEntity);

        $this->flashmessenger()->addSuccessMessage("User id: $id removed successfully.");

        $entityManager->remove($userEntity);
        $entityManager->flush();

        // For now redirect to only the list page
        return $this->redirect()->toRoute('user/default', array (
            'controller' => 'account',
            'action'     => 'list'
        ));
    }

    public function meAction()
    {

        return array();
    }

    public function deniedAction()
    {
        return array();
    }

    public function listAction()
    {
        $userModel = new UserModel();
        $result = $userModel->getSql()->select();

        $adapter = new PaginatorDbAdapter($result, $userModel->getAdapter());
        $paginator = new Paginator($adapter);
        $currentPage = $this->params('page', 1);
        $paginator->setCurrentPageNumber($currentPage);
        $paginator->setItemCountPerPage(10);

        $acl = $this->serviceLocator->get('acl');
        return array('users'=> $paginator,
                'acl' => $acl,
                'page'=> $currentPage
        );
    }

    protected function findUserEntity($id)
    {
        $entityManager = $this->serviceLocator->get('entity-manager');
        $config = $this->serviceLocator->get('config');
        $entity = $entityManager->find($config['service_manager']['invokables']['user-entity'], $id);

        return $entity;
    }

    protected function formAddFields($form)
    {
        $form->add(array(
            'name' => 'password_verify',
            'type' => 'Zend\Form\Element\Password',
            'attributes' => array(
                'placeholder' => 'Verify Password Here...',
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'Verify Password',
            )
        ),
            array (
                'priority' => $form->get('password')->getOption('priority'),
            )
            );

        // This is the special code that protects our form being submitted from automated scripts
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
}
