<?php
namespace User\Authentication;

use Zend\Authentication\Adapter\AbstractAdapter;
use Zend\Authentication\Result;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Adapter extends AbstractAdapter implements ServiceLocatorAwareInterface
{
    /**
     *
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /* (non-PHPdoc)
     * @see \Zend\Authentication\Adapter\AdapterInterface::authenticate()
     */
    public function authenticate()
    {
        $entityManager = $this->serviceLocator->get('entity-manager');
        $userEntityClassName = get_class($this->serviceLocator->get('user-entity'));

        // authenticate by email
        $user = $entityManager->getRepository($userEntityClassName)->findOneByEmail($this->identity);
        // And if we have such an user we check if his password is matching
        if ($user && $user->verifyPassword($this->credential)) {
            // upon successful validation we can return the user entity object
            return new Result(Result::SUCCESS, $user);
        }

        // authenticate by Facebook access token
        if ($user && $user->verifyFaceBookAccessToken($this->credential)) {
            // upon successful validation we can return the user entity object
            return new Result(Result::SUCCESS, $user);
        }

        //@todo do I need to add refresh token verification?



        return new Result(Result::FAILURE, $this->identity);

    }
    /* (non-PHPdoc)
     * @see \Zend\ServiceManager\ServiceLocatorAwareInterface::setServiceLocator()
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

    }

    /* (non-PHPdoc)
     * @see \Zend\ServiceManager\ServiceLocatorAwareInterface::getServiceLocator()
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

}
