<?php
namespace ChangeLog\Form;

use Zend\Form\Fieldset;
use ChangeLog\Model\ChangeLog;
use Zend\Hydrator\ClassMethods;
use Zend\InputFilter\InputFilterProviderInterface;

class ChangeLogFieldset extends Fieldset implements InputFilterProviderInterface
{
    public function getInputFilterSpecification()
    {
        return [
            'description' => [
                'required'  => true,
                'filters'   => [
                    ['name' => 'Zend\Filter\StringTrim']
                ],
                'validators' => [
                    [
                    'name' => 'Zend\Validator\StringLength',
                    'options' => [
                        'min' => '1',
                        'max' => '255'
                        ]
                    ]
                ]

            ]
        ];

    }

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
            'type' => 'textarea',
            'name' => 'description',
            'options' => [
                'label' => 'Description:',
                'label_attributes' => ['class' => 'control-label']
            ],
            'attributes' => [
                'required' => 'true',
                'class' => 'form-control'
            ]
        ]);

    }
}
