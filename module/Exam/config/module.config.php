<?php
return [
    'controllers' => [
        'invokables' => [
            'Exam\Controller\Exam' => 'Exam\Controller\ExamController',
        ],
    ],
    'router' => [
        'routes' => [
            'exam' => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/exam',
                    'defaults' => [
                        '__NAMESPACE__' => 'Exam\Controller',
                        'controller'    => 'Exam',
                        'action'        => 'list',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'default' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/[:controller[/:action[/:id]]]',
                            'constraints' => [
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'         => '[0-9]*',
                            ],
                            'defaults' => [],
                        ],
                    ],
                    'list' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route' => '/list[/page/:page][/orderby/:orderby][/order/:order]',
                            'constraints' => [
                                'page'     => '[0-9]*',
                                'orderby' => 'name|description|duration|score',
                                'order' => 'asc|desc'
                            ],
                            'defaults' => [
                                'controller'    => 'Exam',
                                'action'        => 'list',
                                'page'          => '1',
                                'orderby'       => 'name',
                                'order'         => 'desc'
                            ],
                        ]
                    ]
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'Exam' => __DIR__ . '/../view',
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'formMultipleChoice'  => 'Exam\Form\View\Helper\Question\FormMultipleChoice',
            'formSingleChoice'    => 'Exam\Form\View\Helper\Question\FormSingleChoice',
            'formFreeText'   	  => 'Exam\Form\View\Helper\Question\FormFreeText',
            'formQuestionElement' => 'Exam\Form\View\Helper\Question\FormQuestionElement',
        ]
    ],
    'service_manager' => [
        'factories'  => [
            'mail-transport'        => 'Exam\Service\Factory\MailTransport',
            'test'                  => 'Exam\Service\Factory\Test',
            'test-attempt'          => 'Exam\Service\Factory\TestAttempt',
            'test-attempt-answer'   => 'Exam\Service\Factory\TestAttemptAnswer',
        ],
        'invokables' => [
            'test-manager'                  => 'Exam\Model\TestManager',
            'test-entity'                   => 'Exam\Model\Entity\Test',
            'test-attempt-manager'          => 'Exam\Model\TestAttemptManager',
            'test-attempt-answer-manager'   => 'Exam\Model\TestAttemptAnswerManager',
            'test-attempt-entity'           => 'Exam\Model\Entity\TestAttempt',
            'test-attempt-answer-entity'    => 'Exam\Model\Entity\TestAttemptAnswer',
            'pdf'                           => 'Exam\Service\Invokable\Pdf',
            'mail'		                    => 'Exam\Service\Invokable\Mail',
        ]
    ],
    'table-gateway' => [
        'map' => [
            'tests'                         => 'Exam\Model\Tests',
            'x_users_tests_attempts'        => 'Exam\Model\TestAttempt',
            'x_users_tests_attempts_answers'=> 'Exam\Model\TestAttemptAnswer',
        ]
    ],
    'doctrine' => [
        'entity_path' => [
                __DIR__ . '/../src/Exam/Model/Entity/',
        ],
    ],
    'acl' => [
        'resource' => [
            'exam' => null,
        ],
        'allow' => [
            ['guest', 'exam', 'list'],
            ['member', 'exam', ['list','take']],
            ['admin', 'exam', ['reset','certificate', 'view', 'edit', 'delete', 'take', 'list']],
        ],
        'modules' => [
            'Exam',
        ],
    ],
    'navigation' => [
        'default' => [
            [
                'label'         => 'Exam',
                'title'         => 'Exam',
                'route'         => 'exam/default',
                'controller'    => 'exam',
                'pages' => [
                    [
                        'label'         => 'List',
                        'route'         => 'exam/list',
                        // acl
                        'resource'      => 'exam',
                        'privilege'     => 'list',
                        'title'         => 'Exam List'
                    ],
                    /*
                    [
                        'label' => 'Reset',
                        'title' => 'Resets the tests to the default set',
                        // uri
                        'route' => 'exam/default',
                        'controller' => 'exam',
                        'action'     => 'reset',
                        // acl
                        'resource'   => 'exam',
                        'privilege'  => 'reset',
                    ],
                    */
                ]
            ],
        ]
    ],
    'pdf' => [
        'exam_certificate' => __DIR__.'/../samples/pdf/exam_certificate.pdf',
    ],
];
