<?php
namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use User\Form\User as UserForm;
use User\Model\User as UserModel;

class AccountController extends AbstractActionController
{
    public function indexAction()
    {
        return array();
    }
    
    public function addAction()
    {
        $form = new UserForm();
        if($this->getRequest()->isPost()) {
            $data = array_merge_recursive(
                $this->getRequest()->getPost()->toArray(),
                $this->getRequest()->getFiles()->toArray()
                );
            
            $form->setData($data);
            if($form->isValid()) {
                $model = new UserModel();
                $id = $model->insert($form->getData());
                
                //@todo: redirect user to tehe view user action
            }
        }
        return array('form1' => $form);
    }
    
    /*
     * Anonymous users can use this action to register new accounts
     */
    
    public function registerAction()
    {
        return array();
    }
    
    public function viewAction()
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
}