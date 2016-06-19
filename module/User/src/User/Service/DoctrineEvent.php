<?php
class DoctrineEvent
{
    protected $initializer;

    public function __constructr($initializer, $serviceLocator)
    {
        $this->initializer = $initializer;
        $this->serviceLocator = $serviceLocator;
    }

    public function postLoad(EvetArgs $event)
    {
        $entity = $event->getEntity();
        $this->initializer->initialize($entity, $this->serviceLocator);
    }
}
