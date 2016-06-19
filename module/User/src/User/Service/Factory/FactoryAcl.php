<?php
namespace User\Service\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Permissions\Acl\Acl as AccessControlList;

class FactoryAcl implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('config');
        $aclConfig = $config['acl'];

        $acl = new AccessControlList();

        foreach($aclConfig['resource'] as $resource=>$parent) {
            $acl->addResource($resource, $parent);
        }

        foreach($aclConfig['role'] as $role=>$parents) {
            $acl->addRole($role, $parents);
        }

        foreach (array('allow', 'deny') as $action) {
            foreach($aclConfig[$action] as $definition) {
                call_user_func_array(array($acl, $action), $definition);
            }
        }

        return $acl;
    }
}
