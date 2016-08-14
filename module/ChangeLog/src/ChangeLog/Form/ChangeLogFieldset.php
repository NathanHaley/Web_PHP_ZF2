<?php
namespace ChangeLog\Form;

use Zend\Form\Fieldset;
use ChangeLog\Model\ChangeLog;
use Zend\Stdlib\Hydrator\ClassMethods;

class ChangeLogFieldset extends Fieldset
{
    public function __construct($name = null, $options = [])
    {
        
        parent::__construct($name = null, $options = []);
        
        $this->setHydrator(new ClassMethods(false));
        $this->setObject(new ChangeLog());
        
        $this->add([
            'type' => 'hidden',
            'name' => 'id'
        ]);
        
        $this->add([
            'type' => 'text',
            'name' => 'description',
            'options' => [
                'label' => 'Description'
            ]
        ]);
       
    }
}