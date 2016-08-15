<?php
namespace ChangeLog\Form;

use Zend\Form\Form;

class ChangeLogForm extends Form
{
    public function __construct($name = null, $options = [])
    {
        parent::__construct($name, $options);
        
        $this->add([
            'name' => 'changeLog-fieldset',
            'type' => 'ChangeLog\Form\ChangeLogFieldset',
            'options' => [
                'use_as_base_fieldset' => true
            ]
        ]);
        
        $this->add([
            'name' => 'csrf',
            'type' => 'Zend\Form\Element\Csrf'
        ]);
        
        $this->add([
            'type' => 'submit',
            'name' => 'submit',
            'attributes' => [
                'value' => 'Save Log',
                'class' => 'btn btn-primary btn-sm'
            ]
        ]);
    }
}