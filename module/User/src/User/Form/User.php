<?php
namespace User\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\Factory as InputFactory;

class User extends Form
{

    public function __construct($name = 'user')
    {
        parent::__construct($name);
        
        $this->setAttribute('method', 'post');
        
        $this->add(array(
            'name' => 'email',
            'type' => 'Zend\Form\Element\Email',
            'options' => array(
                'label' => 'Email'
            ),
            'attributes' => array(
                'type' => 'email',
                'required' => true,
                'placeholder' => 'Email Address...'
            )
        ));
        
        $this->add(array(
            'name' => 'phone',
            'options' => array(
                'label' => 'Phone number'
            ),
            'attributes' => array(
                'type' => 'tel',
                'required' => 'required',
                'pattern' => '^[\d-/]+$'
            )
        ));
        
        $this->add(array(
            'name' => 'password',
            'type' => 'Zend\Form\Element\Password',
            'attributes' => array(
                'placeholder' => 'Password Here...',
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Password'
            )
        ));
        
        $this->add(array(
            'name' => 'password_verify',
            'type' => 'Zend\Form\Element\Password',
            'attributes' => array(
                'placeholder' => 'Verify Password Here...',
                'Required' => 'required'
            ),
            'options' => array(
                'label' => 'Verify Password'
            )
        ));
        
        $this->add(array(
            'name' => 'name',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Type name...',
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Name'
            )
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\File',
            'name' => 'photo',
            'options' => array(
                'label' => 'Your photo'
            ),
            'attributes' => array(
                'required' => 'required',
                'id' => 'photo'
            )
        ));
        
        $this->add(array(
            'name' => 'csrf',
            'type' => 'Zend\Form\Element\Csrf'
        ));
        
        $this->add(array(
            'name' => 'submit',
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'value' => 'Submit',
                'required' => 'false'
            )
        ));
    }

    public function getInputFilter()
    {
        if (! $this->filter) {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();
            
            $inputFilter->add($factory->createInput(array(
                'name' => 'name',
                'filters' => array(
                    array(
                        'name' => 'StripTags'
                    ),
                    array(
                        'name' => 'StringTrim'
                    )
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                'isEmpty' => 'Name is required'
                            )
                        )
                    )
                )
            )));
            
            $inputFilter->add($factory->createInput(array(
                'name' => 'phone',
                'filters' => array(
                    array(
                        'name' => 'digits'
                    ),
                    array(
                        'name' => 'StringTrim'
                    )
                ),
                'validators' => array(
                    array(
                        'name' => 'regex',
                        'options' => array(
                            'pattern' => '/^[\d-\/]+$/'
                        )
                    )
                )
            )));
            
            $inputFilter->add($factory->createInput(array(
                'name' => 'email',
                'filters' => array(
                    array(
                        'name' => 'StripTags'
                    ),
                    array(
                        'name' => 'StringTrim'
                    )
                ),
                'validators' => array(
                    array(
                        'name' => 'EmailAddress',
                        'options' => array(
                            'messages' => array(
                                'emailAddressInvalidFormat' => 'Email address format is not valid'
                            )
                        )
                    ),
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                'isEmpty' => 'Email address is required'
                            )
                        )
                    )
                )
            )));
            
            $inputFilter->add($factory->createInput(array(
                'name' => 'password_verify',
                'filters' => array(
                    array(
                        'name' => 'StripTags'
                    ),
                    array(
                        'name' => 'StringTrim'
                    )
                ),
                'validators' => array(
                    array(
                        'name' => 'identical',
                        'options' => array(
                            'token' => 'password'
                        )
                    )
                )
            )));
            

            $inputFilter->add($factory->createInput(array(
                'name' => 'password',
                'filters' => array(
                    array(
                        'name' => 'StripTags'
                    ),
                    array(
                        'name' => 'StringTrim'
                    )
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                'isEmpty' => 'Password is required'
                            )
                        )
                    )
                )
            )));
            
            $inputFilter->add($factory->createInput(array(
                'name' => 'photo',
                'validators' => array(
                    array(
                        'name' => 'filesize',
                        'options' => array(
                            'max' => 2097152
                        ) // 2MB

                    ),
                    array(
                        'name' => 'filemimetype',
                        'options' => array(
                            'mimeType' => 'image/png,image/x-png,image/jpg,image/jpeg,image/gif'
                        )
                    ),
                    array(
                        'name' => 'fileimagesize',
                        'options' => array(
                            'maxWidth' => 200,
                            'maxHeight' => 200
                            )
                        )
                    ),
                    'filters' => array(
                        array(
                            'name' => 'filerenameupload',
                            'options' => array(
                                'target' => 'data/image/photos/',
                                'randomize' => true
                            ),
                        ),
                    ),
                
            )));
            
            $this->filter = $inputFilter;
        }
        return $this->filter;
    }
}
