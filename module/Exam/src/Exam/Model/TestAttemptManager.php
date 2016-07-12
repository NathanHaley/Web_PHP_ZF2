<?php
namespace Exam\Model;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Form\Factory as FormFactory;

class TestAttemptManager implements ServiceLocatorAwareInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $services;

    /**
     * @var array
     */
    protected $cache;

    /**
     * Creates and fills the entity identified by id
     * @param integer $id
     * @return Entity\TestAttempt
     */
    public function create($id)
    {
        $testAttemptEntity = $this->services->get('Test-Attempt-entity');
        $entityManager = $this->services->get('entity-manager');

        $testAttemptEntity = $entityManager->getRepository(get_class($testAttemptEntity))
                                            ->findOneByEmail($id);

        return $testAttemptEntity;
    }

    public function get($id)
    {
        if (! isset($this->cache[$id])) {
            // The TestAttempt class is a table gateway class
            $model = new TestAttempt();
            $result = $model->select(array('id' => $id));
            $data = $result->current();

            $this->cache[$id] = $data;
        }

        return $this->cache[$id];
    }

    public function store($data)
    {
        $model = new TestAttempt();

        return $model->insert($data);
    }

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
