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
            'ChangeLog\Controller\List'     => 'ChangeLog\Factory\ListControllerFactory',
            'ChangeLog\Controller\Write'    => 'ChangeLog\Factory\WriteControllerFactory',
            'ChangeLog\Controller\Delete'   => 'ChangeLog\Factory\DeleteControllerFactory'
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
                    'default' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/[:controller[/:action[/:id]]]',
                            'constraints' => [
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'         => '[0-9]*',
                            ],
                            'defaults' => [
                            ],
                        ],
                    ],
                    'detail' => [
                        'type' => 'segment',
                        'options' => [
                            'route' => '/list/detail[/:id]',
                            'defaults' => [
                                'controller' => 'ChangeLog\Controller\List',
                                'action'     => 'detail'
                            ],
                            'constraints' => [
                                'id' => '[1-9]\d*'
                            ]
                        ]
                    ],
                    'json' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route' => '/json[/orderby/:orderby][/order/:order]',
                            'constraints' => [
                                'orderby' => 'id|add_ts|description',
                                'order' => 'asc|desc'
                            ],
                            'defaults' => [
                                'controller'    => 'ChangeLog\Controller\List',
                                'action'        => 'listJSON',
                                'orderby' => 'id',
                                'order' => 'desc'
                            ],
                        ]
                    ],
                    'list' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route' => '/list[/page/:page][/orderby/:orderby][/order/:order]',
                            'constraints' => [
                                'page'     => '[0-9]*',
                                'orderby' => 'id|add_ts|description',
                                'order' => 'asc|desc'
                            ],
                            'defaults' => [
                                'controller'    => 'ChangeLog\Controller\List',
                                'action'        => 'list',
                                'page'          => '1',
                                'orderby' => 'id',
                                'order' => 'asc'
                            ],
                        ]
                    ],
                    'add' => [
                        'type' => 'literal',
                        'options' => [
                            'route' => '/write/add',
                                'defaults' => [
                                    'controller'    => 'ChangeLog\Controller\Write',
                                    'action'        => 'add'
                                ]
                            ]
                    ],
                    'edit' => [
                        'type' => 'segment',
                        'options' => [
                            'route' => '/write/edit[/:id]',
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
                            'route' => '/delete/delete[/:id]',
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
    ],
    'acl' => [
        // List of modules to apply the ACL. Protect current Module's pages.
        'modules' => [
            'ChangeLog',
        ],
        'resource' => [
            // resource -> single parent
            'write'     => null,
            'delete'    => null,
            'list'      => null,
        ],
        'allow' => [
            // ['role', 'resource', ['permission-1', 'permission-2', ...]],
            ['guest',   'list',     'index'],
            ['member',  'list',     'index'],
            ['admin',   'write',    ['add', 'edit']],
            ['admin',   'delete',   'delete'],
            ['admin',   'list',     ['index', 'detail']],
        ],
        'resource_aliases' => [
            'ChangeLog\Controller\Write'    => 'write',
            'ChangeLog\Controller\Delete'   => 'delete',
            'ChangeLog\Controller\List'     => 'list'
        ],

    ],
    'navigation' => [
        'default' => [
            [
                'label'         => 'Change Log',
                'title'         => 'Change Log',
                'route'         => 'changelog',
                'controller'    => 'list',
                'pages' => [
                    [
                        'label'         => 'List',
                        'route'         => 'changelog/list',
                        'resource'      => 'list',
                        'privilege'     => 'index',
                        'title'         => 'List'
                    ],
                    [
                        'label'         => 'Add',
                        'route'         => 'changelog/add',
                        'resource'      => 'write',
                        'privilege'     => 'add',
                        'title'         => 'Add Change Log'
                    ],

                ]
            ]
        ]
    ]
];
