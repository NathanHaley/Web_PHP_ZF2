<?php
namespace ChangeLog\Factory;

use ChangeLog\Service\ChangeLogService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ChangeLogServiceFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new ChangeLogService($serviceLocator->get('ChangeLog\Mapper\ChangeLogMapperInterface'));
    }
}
