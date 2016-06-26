<?php
namespace ContactUs\Model;

// Not sure if this is still used

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


class ContactUsManager implements ServiceLocatorAwareInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $services;

    /**
     * Creates and fills the ContactUs entity
     * @param string $identity
     * @return Entity\User
     */
    public function create($id)
    {
        $contactUs = $this->services->get('contactUs-entity');
        $entityManager = $this->services->get('entity-manager');

        $user = $entityManager->getRepository(get_class($contactUs))
                              ->findOneById($id);

        return $contactUs;
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
