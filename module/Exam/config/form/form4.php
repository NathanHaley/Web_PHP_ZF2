<?php
$i = 0;
return array(
    'info' => array(
        'name' => 'PHP 5 Scalar Types',
        'locale' => 'en_US',
        'description' => 'Test your PHP 5 scalar knowledge.',
        'creator' => 1,
        'active' => 1,
        'duration' => 10
    ),
    'type' => 'form',
    'elements' => array(
        array(
            'spec' => array(
                'type' => 'Exam\Form\Element\Question\MultipleChoice',
                'name' => 'q' . ++$i,
                'options' => array(
                    'question' => 'Which scalar types does PHP have?',
                    'value_options' => array(
                        '1' => ' - long',
                        '2' => ' - boolean',
                        '3' => ' - integer',
                        '4' => ' - float (aka double)',
                        '5' => ' - string',
                        '6' => ' - char'
                    ),
                    'answers' => array(
                        '2',
                        '3',
                        '4',
                        '5'
                    )
                )
            )
        ),
        array(
            'spec' => array(
                'type' => 'Exam\Form\Element\Question\YesNo',
                'name' => 'q' . ++$i,
                'options' => array(
                    'question' => 'When a float is converted to an int it is equal to floor($someInt)?',
                    'answers' => array(
                        '0'
                    )
                )
            )
        ),
        array(
            'spec' => array(
                'type' => 'Exam\Form\Element\Question\FreeText',
                'name' => 'q' . ++$i,
                'options' => array(
                    'question' => 'What is the result from the following code?
<?php
echo (int) ( (0.1+0.7) * 10 );
?>',
                    'answers' => array(
                        '7'
                    )
                )
            )
        ),
        array(
            'spec' => array(
                'type' => 'Exam\Form\Element\Question\MultipleChoice',
                'name' => 'q' . ++$i,
                'options' => array(
                    'question' => 'Which code snippets generate output: bool(false)',
                    'value_options' => array(
                        '1' => ' - var_dump((bool) "");',
                        '2' => ' - var_dump((bool) 1);',
                        '3' => ' - var_dump((bool) -2);',
                        '4' => ' - var_dump((bool) "foo");',
                        '5' => ' - var_dump((bool) 2.3e5);',
                        '6' => ' - var_dump((bool) array(12));',
                        '7' => ' - var_dump((bool) array());',
                        '8' => ' - var_dump((bool) "false")'
                    ),
                    'answers' => array(
                        '1',
                        '7'
                    )
                )
            )
        ),
        array(
            'spec' => array(
                'type' => 'Exam\Form\Element\Question\SingleChoice',
                'name' => 'q' . ++$i,
                'options' => array(
                    'question' => 'Which of the below are valid syntax for floating point numbers:
<?php
$a = 1.234;
$b = 1.2e3;
$c = 7E-10;
?>

                    ',
                    'value_options' => array(
                        '1' => ' - $a',
                        '2' => ' - $a and $b',
                        '3' => ' - $b and $a',
                        '4' => ' - $a, $b, and $c',
                        '5' => ' - none'
                    ),
                    'answers' => array(
                        '4'
                    )
                )
            )
        ),
        array(
            'spec' => array(
                'type' => 'Exam\Form\Element\Question\FreeText',
                'name' => 'q' . ++$i,
                'options' => array(
                    'question' => 'What is the result from the code below:
<?php
$blue = \'bl\'."ue";
$a = \'When the Sun in shining the sky is usually $blue!\';
echo $a;
?>',
                    'answers' => array(
                        'When the Sun in shining the sky is usually $blue!'
                    )
                )
            )
        )
    )

);
