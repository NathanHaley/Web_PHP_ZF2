<?php
namespace ChangeLog\Factory;

use ChangeLog\Controller\WriteController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class WriteControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $realServiceLocator = $serviceLocator->getServiceLocator();
        $changeLogService   = $realServiceLocator->get('ChangeLog\Service\ChangeLogServiceInterface');
        $changeLogInsertForm= $realServiceLocator->get('FormElementManager')->get('ChangeLog\Form\ChangeLogForm');
        
        return new WriteController (
            $changeLogService,
            $changeLogInsertForm
        );
    }
}