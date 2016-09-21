<?php

namespace ChangeLog\Factory;

use ChangeLog\Mapper\ZendDbSqlMapper;
use ChangeLog\Model\ChangeLog;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class ZendDbSqlMapperFactory implements FactoryInterface
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
        return new ZendDbSqlMapper(
            $serviceLocator->get('Zend\Db\Adapter\Adapter'),
            new ClassMethods(true),
            new ChangeLog()
            );
    }
}
