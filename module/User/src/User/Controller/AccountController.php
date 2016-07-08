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
        $currentUser = $this->serviceLocator->get('user');
        $entity = $this->serviceLocator->get('user-entity');

        $form = $this->setupUserDetailForm($entity);

        if ($this->getRequest()->isPost()) {

            $data = array_merge_recursive($this->getRequest()
                ->getPost()
                ->toArray(),
                // Notice: make certain to merge the Files also to the post data
                $this->getRequest()
                    ->getFiles()
                    ->toArray());

            $form->setData($data);

            if ($form->isValid()) {
                // We use now the Doctrine 2 entity manager to save user data to the database
                $entityManager = $this->serviceLocator->get('entity-manager');
                $entityManager->persist($entity);
                $entityManager->flush();

                $this->flashmessenger()->addSuccessMessage("Account created successfully.");

                $event = new EventManager('user');
                $event->trigger('register', $this, array(
                    'user' => $entity
                ));


                if ($currentUser->getRole() === 'guest') {
                    //Log in new account
                    $this->forward()->dispatch('User/Controller/Log', array('action' => 'in'));
                } else {
                    //Admin adding acount, route to detail view
                    return $this->redirect()->toRoute('user/default', array(
                        'controller' => 'account',
                        'action' => 'view',
                        'id' => $entity->getId()
                    ));
                }
            }
        }

        // pass the data to the view for visualization
        return array('form1' => $form);
    }

    protected function setupUserDetailForm($entity)
    {
        $userEntity = $entity;
        $builder = new AnnotationBuilder();

        $form = $builder->createForm($userEntity);

        $form = $this->formAddFields($form);

        // We bind the entity to the user. If the form tries to read/write data from/to the entity
        // it will use the hydrator specified in the entity to achieve this. In our case we use ClassMethods
        // hydrator which means that reading will happen calling the getter methods and writing will happen by
        // calling the setter methods.
        $form->bind($userEntity);

        return $form;
    }

    /*
     * Anonymous users can use this action to register new accounts
     */
    public function registerAction()
    {
        $result = $this->forward()->dispatch('User\Controller\Account', array(
            'action' => 'add'
        ));

        return $result;
    }

    public function viewAction()
    {
        $id = $this->params('id');

        // If no id then send to Me page to see themselves
        if (! $id) {
            return $this->redirect()->toRoute('user/default', array(
                'controller' => 'account',
                'action' => 'me'
            ));
        }

        $userToView = $this->findUserEntity($id);

        // var_dump($userToView);

        return array(
            'userToView' => $userToView
        );
    }

    public function editAction()
    {
        $id = $this->params('id');

        if (! $id) {
            return $this->redirect()->toRoute('user/default', array(
                'controller' => 'account',
                'action' => 'view'
            ));
        }

        $entity = $this->findUserEntity($id);

        $form = $this->setupUserDetailForm($entity);

        if ($this->getRequest()->isPost()) {
            $data = array_merge_recursive($this->getRequest()
                ->getPost()
                ->toArray(),
                // Notice: make certain to merge the Files also to the post data
                $this->getRequest()
                    ->getFiles()
                    ->toArray());
            $form->setData($data);
            if ($form->isValid()) {
                // We use now the Doctrine 2 entity manager to save user data to the database
                $entityManager = $this->serviceLocator->get('entity-manager');

                $entity = $entityManager->merge($entity);
                $entityManager->flush();

                $this->flashmessenger()->addSuccessMessage("User: {$entity->getEmail()} was updated successfully.");

                // redirect the user to the view user action
                return $this->redirect()->toRoute('user/default', array(
                    'controller' => 'account',
                    'action' => 'list',
                    'id' => $entity->getId()
                ));
            }
        }

        // pass the data to the view for visualization
        return array(
            'form1' => $form
        );
    }

    public function deleteAction()
    {
        $id = $this->params('id');
        if (! $id) {
            return $this->redirect()->toRoute('user/default', array(
                'controller' => 'account',
                'action' => 'view'
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
        return $this->redirect()->toRoute('user/default', array(
            'controller' => 'account',
            'action' => 'list'
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
        $currentPage = $this->params('page', 1);
        $orderby = $this->params('orderby', 'ID');
        $order = $this->params('order', 'DESC');

        $orderby_tmp = strtoupper($orderby);

        $userModel = new UserModel();
        $result = $userModel->getSql()->select()->order("$orderby_tmp $order");

        $adapter = new PaginatorDbAdapter($result, $userModel->getAdapter());
        $paginator = new Paginator($adapter);

        $paginator->setCurrentPageNumber($currentPage);
        $paginator->setItemCountPerPage(4);

        $acl = $this->serviceLocator->get('acl');
        $columns = [
                'id'    =>['text'=>'id',    'attributes'=>['nowrap'=>'true', 'width'=>'6%']],
                'email' =>['text'=>'email', 'attributes'=>['nowrap'=>'true']],
                'name'  =>['text'=>'name',  'attributes'=>['nowrap'=>'true']],
                'role'  =>['text'=>'role',  'attributes'=>['nowrap'=>'true']]

        ];
        return array(
            'entities'  => $paginator,
            'acl'       => $acl,
            'page'      => $currentPage,
            'orderby'   => $orderby,
            'order'     => $order,
            'columns'   => $columns,
            'pageTitle' => 'Admin User List',
            'route'     => 'user',
            'controller'=> 'account'
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
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Verify Password'
            )
        ), array(
            'priority' => $form->get('password')
                ->getOption('priority')
        ));

        // This is the special code that protects our form being submitted from automated scripts
        $form->add(array(
            'name' => 'csrf',
            'type' => 'Zend\Form\Element\Csrf'
        ));

        // This is the submit button
        $form->add(array(
            'name' => 'submit',
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'value' => 'Submit',
                'required' => 'false'
            )
        ));

        return $form;
    }
}
