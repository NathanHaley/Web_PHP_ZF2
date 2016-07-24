<?php
namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\EventManager\EventManager;

class LogController extends AbstractActionController
{

    public function outAction()
    {
        $auth = $this->serviceLocator->get('auth');
        $auth->clearIdentity();
        return $this->redirect()->toRoute('home');
    }

    public function autoLoginAction()
    {
        $username = $this->params()->fromRoute('username');
        $autoLoginUsernames = ['demouser', 'demoadmin'];

        if (array_search($username, $autoLoginUsernames) === false ) {
            $this->flashmessenger()->addErrorMessage("Auto Login only works for the demo accounts at this time.");

            // just show the login form
            return $this->forward()->dispatch('User/Controller/Log', array('action' => 'in'));

        }

        $username = $username.'@nathanhaley.com';
        $password = 'pass123';

        $user = $this->logUserIn($username, $password);

        if($user !== false) {

            $this->flashMessenger()->addSuccessMessage(sprintf('Welcome %s. You are now logged in.',$user->getName()));

            return $this->redirect()->toRoute('user/default', array (
                'controller' => 'account',
                'action'     => 'me',
            ));
        } else {
            //Really shouldn't get here.

            $event = new EventManager('user');
            $event->trigger('log-fail', $this, array('username'=> $username));

            $this->flashMessenger()->addErrorMessage(sprintf('Please enter a valid Username and Password combination.'));

            return $this->forward()->dispatch('User/Controller/Log', array('action' => 'in'));
        }
    }

    public function inAction()
    {
        if (! $this->getRequest()->isPost()) {
            // just show the login form
            return array();
        }

        $username = $this->params()->fromPost('username');

        //adjust for forward from register action
        If($this->params()->fromPost('email')) {
            $username = $this->params()->fromPost('email');
        }

        $password = $this->params()->fromPost('password');

        $user = $this->logUserIn($username, $password);

        if($user !== false) {

            $this->flashMessenger()->addSuccessMessage(sprintf('Welcome %s. You are now logged in.',$user->getName()));

            return $this->redirect()->toRoute('user/default', array (
                'controller' => 'account',
                'action'     => 'me',
            ));
        } else {
            $this->flashMessenger()->addErrorMessage(sprintf('Please enter a valid Username and Password combination.'));
            $event = new EventManager('user');
            $event->trigger('log-fail', $this, array('username'=> $username));

            //show login form
            return [];//array('errors' => $result->getMessages());
        }

    }

    protected function logUserIn($username, $password)
    {
        $auth = $this->serviceLocator->get('auth');
        $authAdapter = $auth->getAdapter();

        // below we pass the username and the password to the authentication adapter for verification
        $authAdapter->setIdentity($username);
        $authAdapter->setCredential($password);

        // here we do the actual verification
        $result = $auth->authenticate();
        $isValid = $result->isValid();

        if($isValid) {
            // upon successful validation the getIdentity method returns
            // the user entity for the provided credentials
            $user = $result->getIdentity();


            // @todo: upon successful validation store additional information in the auth storage

            return $user;
        } else {

            return false;
        }
    }
}
