<?php
namespace ChangeLog\Factory;

use ChangeLog\Controller\ListController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ListControllerFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $realServiceLocator = $serviceLocator->getServiceLocator();
        $changeLogService   = $realServiceLocator->get('ChangeLog\Service\ChangeLogServiceInterface');

        return new ListController($changeLogService);
    }
}
