<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Exam\Controller\Test' => 'Exam\Controller\TestController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'exam' => array(
                'type'    => 'Literal',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/exam',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'Exam\Controller',
                        'controller'    => 'Test',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    // This route is a sane default when developing a module;
                    // as you solidify the routes for your module, however,
                    // you may want to remove it and replace it with more
                    // specific routes.
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action[/:id]]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'         => '[0-9]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                    'list' => array(
                        'type'    => 'Segment',
                        'options' => array (
                            'route' => '/test/list[/page/:page][/orderby/:orderby][/order/:order]',
                            'constraints' => array(
                                'page'     => '[0-9]*',
                                'orderby' => 'name|description|duration|score',
                                'order' => 'asc|desc'
                            ),
                            'defaults' => array(
                                'controller'    => 'Test',
                                'action'        => 'list',
                                'page'          => '1',
                                'orderby' => 'name',
                                'order' => 'desc'
                            ),
                        )
                    )
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'Exam' => __DIR__ . '/../view',
        ),
    ),
    'view_helpers' => array (
        'invokables' => array (
            'formMultipleChoice'  => 'Exam\Form\View\Helper\Question\FormMultipleChoice',
            'formSingleChoice'    => 'Exam\Form\View\Helper\Question\FormSingleChoice',
            'formFreeText'   	  => 'Exam\Form\View\Helper\Question\FormFreeText',
            'formQuestionElement' => 'Exam\Form\View\Helper\Question\FormQuestionElement',
        )
    ),
    'service_manager' => array(
        'factories'  => array(
            'mail-transport'        => 'Exam\Service\Factory\MailTransport',
            'entity-manager'        => 'Exam\Service\Factory\EntityManager',
            'database'              => 'Exam\Service\Factory\Database',
            'test'                  => 'Exam\Service\Factory\Test',
            'test-attempt'          => 'Exam\Service\Factory\TestAttempt',
            'test-attempt-answer'   => 'Exam\Service\Factory\TestAttemptAnswer',
        ),
        'invokables' => array(
            'doctrine-profiler'             => 'Exam\Service\Invokable\DoctrineProfiler',
            'table-gateway'                 => 'Exam\Service\Invokable\TableGateway',
            'test-manager'                  => 'Exam\Model\TestManager',
            'test-entity'                   => 'Exam\Model\Entity\Test',
            'test-attempt-manager'          => 'Exam\Model\TestAttemptManager',
            'test-attempt-answer-manager'   => 'Exam\Model\TestAttemptAnswerManager',
            'test-attempt-entity'           => 'Exam\Model\Entity\TestAttempt',
            'test-attempt-answer-entity'    => 'Exam\Model\Entity\TestAttemptAnswer',
            'pdf'                           => 'Exam\Service\Invokable\Pdf',
            'mail'		                    => 'Exam\Service\Invokable\Mail',
        )
    ),
    'table-gateway' => array(
        'map' => array(
            'tests'                         => 'Exam\Model\Tests',
            'x_users_tests_attempts'        => 'Exam\Model\TestAttempt',
            'x_users_tests_attempts_answers'=> 'Exam\Model\TestAttemptAnswer',
        )
    ),
    'doctrine' => array(
        'entity_path' => array (
                __DIR__ . '/../src/Exam/Model/Entity/',
        ),
    ),
    'acl' => array(
        'resource' => array (
            'test' => null,
        ),
        'allow' => array(
            array('guest', 'test', 'list'),
            array('member', 'test', array('list','take')),
            array('admin', 'test', array('reset','certificate', 'view', 'edit', 'delete', 'take', 'list')),
        ),
        'modules' => array (
            'Exam',
        ),
    ),
    'navigation' => array(
        'default' => array(
            array(
                'label' => 'Exam',
                'route' => 'exam',
                'pages' => array(
                    array(
                        'label' => 'List',
                        'route' => 'exam/list',
                        // acl
                        'resource'   => 'test',
                        'privilege'  => 'list',
                    ),
                    array(
                        'label' => 'Reset',
                        'title' => 'Resets the test to the default set',
                        // uri
                        'route' => 'exam/default',
                        'controller' => 'test',
                        'action'     => 'reset',
                        // acl
                        'resource'   => 'test',
                        'privilege'  => 'reset',
                    ),
                )
            ),
        )
    ),
    'pdf' => array(
        'exam_certificate' => __DIR__.'/../samples/pdf/exam_certificate.pdf',
    ),
);
