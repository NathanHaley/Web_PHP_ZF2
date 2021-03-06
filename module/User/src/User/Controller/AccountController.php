<?php
namespace User\Controller;

use Util\Controller\UtilBaseController;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\EventManager\EventManager;

class AccountController extends UtilBaseController
{

    public function indexAction()
    {

        return array();
    }

    public function addAction()
    {
        $currentUser = $this->serviceLocator->get('user');
        $entity = $this->serviceLocator->get('user-entity');
        $aclDefaults = $config = $this->serviceLocator->get('config')['acl']['defaults'];

        $form = $this->setupUserDetailForm($entity);

        if ($currentUser->getRole() !== $aclDefaults['admin_role']) {
            //Remove the role fields, only for admins
            $form->remove('role');

            $form->setValidationGroup(['email','name','password','password_verify','phone','csrf']);
        }

        //Add password verify field
        $form = $this->formAddFields($form, ['password_verify']);

        if ($this->getRequest()->isPost()) {

            $data = array_merge_recursive($this->getRequest()
                ->getPost()
                ->toArray(),
                $this->getRequest()
                    ->getFiles()
                    ->toArray());

            $form->setData($data);

            if ($form->isValid()) {


                //Default registering user to member role
                if ($currentUser->getRole() === $aclDefaults['guest_role']) {
                    $entity->setRole($aclDefaults['member_role']);
                }
                // We use now the Doctrine 2 entity manager to save user data to the database

                $entity->setCDate(new \DateTime);

                $entityManager = $this->serviceLocator->get('entity-manager');
                $entityManager->persist($entity);
                $entityManager->flush();

                $this->flashmessenger()->addSuccessMessage("Account created successfully.");

                $event = new EventManager('user');
                $event->trigger('register', $this, array(
                    'user' => $entity
                ));


                if ($currentUser->getRole() === $aclDefaults['guest_role']) {
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

        $form = $this->formAddFields($form, ['csrf','submit']);

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

        return array(
            'userToView' => $userToView
        );
    }

    public function editAction()
    {
        $currentUser = $this->serviceLocator->get('user');
        $aclDefaults = $config = $this->serviceLocator->get('config')['acl']['defaults'];
        $selfEdit = false;

        $admin_role = $aclDefaults['admin_role'];

        $id = $this->params('id');

        if ((! $id) || (($id) && ($currentUser->getRole() !== $admin_role))){

            $id = $currentUser->getId();

            if (! $id) {
                return $this->redirect()->toRoute('user/default', array(
                    'controller' => 'account',
                    'action' => 'view'
                ));
            } else {
                $selfEdit = true;
            }
        }

        $entity = $this->findUserEntity($id);

        $form = $this->setupUserDetailForm($entity);

        //Every can edit thier own password
        if (($currentUser->getRole() !== $admin_role) || (($currentUser->getRole() === $admin_role) && ($currentUser->getId() === $id))) {

            //Add password verify field
            $form = $this->formAddFields($form, ['password_verify']);

            //Remove role field, only mutable by admins
            $form->remove('role');
            $form->setValidationGroup(['email','name','password','password_verify','phone','csrf']);

        } else {

            //Remove password fields, admins do not set others's passwords for now
                $form->remove('password');
                $form->setValidationGroup(['email','name','role','phone','csrf']);
        }

        if ($this->getRequest()->isPost()) {
            $data = array_merge_recursive($this->getRequest()
                ->getPost()
                ->toArray(),
                $this->getRequest()
                    ->getFiles()
                    ->toArray());
            $form->setData($data);
            if ($form->isValid()) {
                // Save data
                $entityManager = $this->serviceLocator->get('entity-manager');

                $demoAccounts = [1 => 'demoadmin@nathanhaley.com', 2 => 'demouser@nathanhaley.com'];

                if (array_key_exists($entity->getId(), $demoAccounts) === true) {
                    $this->flashmessenger()->addWarningMessage("NOTE: demo accounts' username/email and passwords are ignored for edits.");

                    $entity->setEmail($demoAccounts[$entity->getId()]);
                    $entity->setPassword('pass123');
                }

                $entity = $entityManager->merge($entity);
                $entityManager->flush();

                $this->flashmessenger()->addSuccessMessage("User: {$entity->getEmail()} was updated successfully.");

                //If member updating their own info OR an admin updating their own info
                if ($selfEdit) {

                    $auth = $this->serviceLocator->get('auth');
                    $auth->getStorage()->write($entity);

                    // redirect the user to the view user action
                    return $this->redirect()->toRoute('user/default', array(
                        'controller' => 'account',
                        'action' => 'me'
                    ));
                } else {
                    //Admins updating members get routed back to list page
                    return $this->redirect()->toRoute('user/list', ['id' => $entity->getId()]);
                }
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

        $demoAccounts = [1 => 'demoadmin@nathanhaley.com', 2 => 'demouser@nathanhaley.com'];

        if (array_key_exists($userEntity->getId(), $demoAccounts) === true) {

            $this->flashmessenger()->addWarningMessage("NOTE: demo accounts are ignored for delete.");

        } else {

            $userEntity = $entityManager->merge($userEntity);
            $entityManager->remove($userEntity);
            $entityManager->flush();
        }

        $this->flashmessenger()->addSuccessMessage("User id: $id removed successfully.");

        // For now redirect to only the list page
        return $this->redirect()->toRoute('user/list');
    }

    public function meAction()
    {
        return array();
    }

    public function deniedAction()
    {
        return array();
    }

    protected function findUserEntity($id)
    {
        $entityManager = $this->serviceLocator->get('entity-manager');
        $config = $this->serviceLocator->get('config');
        $entity = $entityManager->find($config['service_manager']['invokables']['user-entity'], $id);

        return $entity;
    }

    protected function formAddFields($form, $fieldsAdd)
    {

        $fieldsSetUpArray = [
            'csrf' =>
            [
                'name' => 'csrf',
                'type' => 'Zend\Form\Element\Csrf'
            ],
            'submit' => [
                'name' => 'submit',
                'type' => 'Zend\Form\Element\Submit',
                'attributes' =>
                 [
                    'value' => 'Submit',
                    'required' => 'false'
                 ]
            ],
            'password_verify' => [
                'name' => 'password_verify',
                'type' => 'Zend\Form\Element\Password',
                'attributes' => [
                    'placeholder' => 'Verify Password Here...',
                    'required' => 'required'
                ],
                'options' => [
                    'label' => 'Verify Password:'
                ]
            ],
            'password_verify_flag' => [
                'priority' => $form->get('password')->getOption('priority') - 100
            ]
        ];

        foreach ( $fieldsAdd as $field) {

            $flag = [];
            if (isset($fieldsSetUpArray[$field.'_flag'])) {
                $flag = $fieldsSetUpArray[$field.'_flag'];
            }

            $form->add($fieldsSetUpArray[$field],$flag);

        }

        return $form;
    }


}
