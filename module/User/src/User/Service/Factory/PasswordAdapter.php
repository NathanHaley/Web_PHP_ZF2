<?php 
namespace User\Service\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Crypt\Password\Bcrypt;

class PasswordAdapter implements FactoryInterface
{
    public function createService (ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('config');
        $adapter = new Bcrypt($config['bcrypt']);
        
        return $adapter;
    }
}