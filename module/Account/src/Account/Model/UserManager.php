<?php
namespace Account\Model;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


class UserManager implements ServiceLocatorAwareInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $services;

    /**
     * Creates and fills the user entity identified by user identity/email
     * @param string $identity
     * @return Entity\User
     */
    public function create($email)
    {
        $user = $this->services->get('user-entity');
        $entityManager = $this->services->get('entity-manager');

        $user = $entityManager->getRepository(get_class($user))
                              ->findOneByEmail($email);

        return $user;
    }

    /* (non-PHPdoc)
     * @see \Zend\ServiceManager\ServiceLocatorAwareInterface::setServiceLocator()
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->services = $serviceLocator;
    }

    /* (non-PHPdoc)
     * @see \Zend\ServiceManager\ServiceLocatorAwareInterface::getServiceLocator()
     */
    public function getServiceLocator()
    {
        return $this->services;
    }

}
