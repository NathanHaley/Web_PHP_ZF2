<?php
namespace User\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature;

class User extends AbstractTableGateway
{
    public function __construct()
    {
        $this->table = 'users';
        $this->featureSet = new Feature\FeatureSet();
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        $this->initialize();
    }

    public function insert($set)
    {
        $set['photo'] = $set['photo']['tmp_name'];
        unset($set['password_verify']);
        $set['password'] = md5($set['password']);

        return parent::insert($set);
    }

    public function deleteAction()
    {
        $id = $this->getRequest()->getQuery()->get('id');
        if($id) {
            $userModel = new UserModel();
            $userModel->delete(array('id' => $id));
        }

        return array();
    }
}
