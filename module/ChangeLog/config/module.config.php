<?php

return [
    'service_manager' => [
        'factories' => [
            'ChangeLog\Mapper\ChangeLogMapperInterface'      => 'ChangeLog\Factory\ZendDbSqlMapperFactory',
            'ChangeLog\Service\ChangeLogServiceInterface'    => 'ChangeLog\Factory\ChangeLogServiceFactory',
            'Zend\Db\Adapter\Adapter'                        => 'Zend\Db\Adapter\AdapterServiceFactory'
        ]
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__.'/../view',
        ]
    ],
    'controllers' => [
        'factories' => [
            'ChangeLog\Controller\List' => 'ChangeLog\Factory\ListControllerFactory',
            'ChangeLog\Controller\Write' => 'ChangeLog\Factory\WriteControllerFactory',
            'ChangeLog\Controller\Delete' => 'ChangeLog\Factory\DeleteControllerFactory'
        ],
    ],
    'router' => [
        'routes' => [
            'changelog' => [
                'type' => 'literal',
                'options' => [
                    'route' => '/changelog',
                    'defaults' => [
                        'controller'    => 'ChangeLog\Controller\list',
                        'action'        => 'index'
                    ]
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'detail' => [
                        'type' => 'segment',
                        'options' => [
                            'route' => '/:id',
                            'defaults' => [
                                'action' => 'detail'
                            ],
                            'constraints' => [
                                'id' => '[1-9]\d*'
                            ]
                        ]
                    ],
                    'add' => [
                        'type' => 'literal',
                        'options' => [
                            'route' => '/add',
                                'defaults' => [
                                    'controller'    => 'ChangeLog\Controller\Write',
                                    'action'        => 'add'
                                ]
                            ]
                    ],
                    'edit' => [
                        'type' => 'segment',
                        'options' => [
                            'route' => '/edit[/:id]',
                                'defaults' => [
                                    'controller'    => 'ChangeLog\Controller\Write',
                                    'action'        => 'edit'
                                ],
                                'constraints' => [
                                    'id' => '[1-9]\d*'
                            ]
                        ]
                    ],
                    'delete' => [
                        'type' => 'segment',
                        'options' => [
                            'route' => '/delete[/:id]',
                                'defaults' => [
                                    'controller'    => 'ChangeLog\Controller\Delete',
                                    'action'        => 'delete'
                                ],
                                'constraints' => [
                                    'id' => '[1-9]\d*'
                            ]
                        ]
                    ],
                ]
            ]
        ]
    ] 
];