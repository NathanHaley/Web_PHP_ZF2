<?php
namespace Account\Controller;

use Util\Controller\UtilBaseController;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\EventManager\EventManager;
use Zend\View\Model\ViewModel;

class AccountController extends UtilBaseController
{
    //Member homepage
    public function indexAction()
    {
        return [];
        
    }
    
    //Admin Tools page
    public function adminAction()
    {
        return [];
    
    }

    public function addAction()
    {
        $currentUser    = $this->serviceLocator->get('user');
        $entity         = $this->serviceLocator->get('user-entity');
        $aclDefaults    = $this->serviceLocator->get('config')['acl']['defaults'];

        $form = $this->setupUserDetailForm($entity);

        if ($currentUser->getRole() !== $aclDefaults['admin_role']) {
            //Remove the role field, only for admins to edit
            $form->remove('role');

            $form->setValidationGroup([
                'email',
                'displayName',
                'firstName',
                'middleName',
                'lastName',
                'password',
                'passwordVerify',
                'phone',
                'csrf']);
        }

        //Add password verify field
        $form = $this->formAddFields($form, ['passwordVerify']);

        if ($this->getRequest()->isPost()) {

            $data = array_merge_recursive($this->getRequest()
                ->getPost()
                ->toArray(),
                $this->getRequest()
                    ->getFiles()
                    ->toArray());

            $form->setData($data);

            if ($form->isValid()) {

                $entity = $form->getData();

                //@todo see about moving to entity
                //Default registering user to member role
                if ($currentUser->getRole() === $aclDefaults['guest_role']) {
                    $entity->setRole($aclDefaults['member_role']);
                }
                // We use now the Doctrine 2 entity manager to save user data to the database

                $entityManager = $this->serviceLocator->get('entity-manager');


                $entityManager->persist($entity);
                $entityManager->flush();

                if ($newId = $entity->getId()) {


                    $this->flashmessenger()->addSuccessMessage("Account created successfully.");

                    $event = new EventManager('user');
                    $event->trigger('register', $this, array(
                        'user' => $entity
                    ));


                    if ($currentUser->getRole() === $aclDefaults['guest_role']) {
                        //Log in new account
                        $this->forward()->dispatch('Account/Controller/Log', array('action' => 'in'));
                    } else {
                        //Admin adding acount, route to detail view
                        return $this->redirect()->toRoute('user/default', array(
                            'controller' => 'account',
                            'action' => 'view',
                            'id' => $entity->getId()
                        ));
                    }
                } else {
                    return $this->redirect()->toRoute('home');
                    $this->flashmessenger()->addErrorMessage("Account could not be created, please try again or contanct demoadmin@nathanhaley.com");

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
        $result = $this->forward()->dispatch('Account\Controller\Account', array(
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
        $aclDefaults = $this->serviceLocator->get('config')['acl']['defaults'];
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

        //Everyone can edit thier own password
        if (($currentUser->getRole() !== $admin_role) || (($currentUser->getRole() === $admin_role) && ($currentUser->getId() === $id))) {

            //Add password verify field
            $form = $this->formAddFields($form, ['passwordVerify']);

            //If admin include the role field, otherwise remove it
            if ($currentUser->getRole() === $admin_role) {
                //@todo check if this should be here or in post process
                $form->setValidationGroup([
                    'email',
                    'displayName',
                    'firstName',
                    'middleName',
                    'lastName',
                    'role',
                    'password',
                    'passwordVerify',
                    'phone',
                    'csrf']);
            } else {
                //Remove role field, only mutable by admins
                $form->remove('role');

                //@todo check if this should be here or in post process
                $form->setValidationGroup([
                    'email',
                    'displayName',
                    'firstName',
                    'middleName',
                    'lastName',
                    'password',
                    'passwordVerify',
                    'phone',
                    'csrf']);
            }
        } else {

            //Remove password fields, admins do not set others's passwords for now
                $form->remove('password');
                $form->setValidationGroup([
                    'email',
                    'displayName',
                    'role',
                    'firstName',
                    'middleName',
                    'lastName',
                    'phone',
                    'csrf']);
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

                $demoAccounts = [1 => 'demoadmin', 2 => 'demouser'];

                if (array_key_exists($entity->getId(), $demoAccounts) === true) {
                    $this->flashmessenger()->addWarningMessage("NOTE: demo accounts' username/email, display name, and passwords are ignored for edits.");

                    $entity->setEmail($demoAccounts[$entity->getId()].'@nathanhaley.com');
                    $entity->setDisplayName($demoAccounts[$entity->getId()]);
                    $entity->setPassword('pass123');
                }

                $entityManager->merge($entity);
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
            'passwordVerify' => [
                'name' => 'passwordVerify',
                'type' => 'Zend\Form\Element\Password',
                'attributes' => [
                    'placeholder' => 'Verify Password Here...',
                    'required' => 'required'
                ],
                'options' => [
                    'label' => 'Verify Password:'
                ]
            ],
            'passwordVerify_flag' => [
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
