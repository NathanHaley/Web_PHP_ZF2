<?php
namespace Exam\Model;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Form\Factory as FormFactory;

class TestAttemptAnswerManager implements ServiceLocatorAwareInterface
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
     * Creates form element for a test
     * @param string $id
     * @return \Zend\Form\Form
     */
    public function createForm($id)
    {
        //...@TODO
    }

    public function get($id)
    {
        if (! isset($this->cache[$id])) {
            // The TestAttemptAnswer class is a table gateway class
            $model = new TestAttemptAnswer();
            $result = $model->select(array('id' => $id));
            $data = $result->current();

            if ($data['answer'] !== null) {
                $data['answer'] = $this->services->get('cipher')->decrypt($data['answer']);
            }

            $this->cache[$id] = $data;
        }

        return $this->cache[$id];
    }

    public function store($data)
    {
        $model = new TestAttemptAnswer();

        if ($data['answer'] === '') {
            $data['answer'] = null;
        }

        if ($data['answer'] !== null) {
            try {
                $data['answer'] = $this->services->get('cipher')->encrypt(strval($data['answer']));
            } catch (Exception $e) {
                //@todo How best to handle
                var_dump($data);
                echo $e->getMessage();
                            die('<br> Could not store data, try retaking and providing a different answer. Data dump of answer object above');
            }
        }

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
