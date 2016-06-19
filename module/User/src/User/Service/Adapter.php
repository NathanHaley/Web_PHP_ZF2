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

    /*(non-PHPdoc)
     * @see \Zend\Authentication\Adapter\AdapterInterface::authenticate()
     */
    public function authenticate()
    {
        $entityManager = $this->serviceLocator->get('entity-manager');
        $userEntityClassName = get_class($this->serviceLocator->get('user-entity'));
        $user = $entityManager->getRepository($userEntityClassName)->findOneByEmail($this->identity);

        if ($user && $user->verifyPassword($this->credential)) {
            return new Result(Result::SUCCESS, $user);
        }

        return new Result(Result::FAILURE, $this->identity);
    }
    /*(non-PHPdoc)
     * @see \Zend\ServiceManager\ServiceLocatorAwareInterface::setServiceLocator()
     */
    public function setServiceLocator(ServiceLocatorinterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /*(non-PHPdoc)
     * @see \Zend\ServiceManager\ServiceLocatorAwareInterface::getServiceLocator()
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
}
