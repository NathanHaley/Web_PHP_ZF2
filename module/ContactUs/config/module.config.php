<?php
return [
    'view_manager' => [
        'template_path_stack' => [
            'ContactUs' => __DIR__ . '/../view'
        ]
    ],
    'controllers' => [
        'invokables' => [
            'ContactUs\Controller\Index' => 'ContactUs\Controller\IndexController'
        ]
    ],
    'router' => [
        'routes' => [
            'contactus' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/contactus',
                    'defaults' => [
                        '__NAMESPACE__' => 'ContactUs\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index'
                    ]
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'default' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/[:controller[/:action[/:id]]]',
                            'constraints' => [
                                'controller'    => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'        => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'            => '[0-9]*'
                            ],
                            'defaults' => []
                        ]
                    ],

                    'list' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/list[/page/:page][/orderby/:orderby][/order/:order]',
                            'constraints' => [
                                'page'      => '[0-9]*',
                                'orderby'   => 'email|name|comments|time',
                                'order'     => 'asc|desc'
                            ],
                            'defaults' => [
                                'controller'    => 'Index',
                                'action'        => 'list',
                                'page'          => '1',
                                'orderby'       => 'time',
                                'order'         => 'desc'
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ],
    'service_manager' => [
        'invokables' => [
            'contactus-entity'  => 'ContactUs\Model\Entity\ContactUs',
        ],
        'shared' => [
            'contactUs-entity' => false
        ]
    ],
    'table-gateway' => [
        'map' => [
            'contactus' => 'ContactUs\Model\ContactUs'
        ]
    ],
    'doctrine' => [
        __DIR__ . '/../src/ContactUs/Model/Entity/'
    ],
    'acl' => [
        'resource' => [
            'index' => null
        ],
        'allow' => [
            ['guest','index','index'],
            ['member','index','index'],
            ['admin','index',['index', 'list', 'view', 'edit', 'delete']]
        ],
        'modules' => [
            'ContactUs'
        ]
    ],
    'navigation' => [
        'default' => [
            [
                'label'         => 'Contact Us',
                'title'         => 'Contact Us',
                'route'         => 'contactus/default',
                'controller'    => 'index',
                'pages'         => [
                    [
                        'label'         => 'List',
                        'route'         => 'contactus/list',
                        'resource'      => 'index',
                        'privilege'     => 'list',
                        'title'         => 'Contact Us Submissions'
                    ],
                    [
                        'label'         => 'Contact Us',
                        'route'         => 'contactus/default',
                        // acl
                        'resource'      => 'index',
                        'privilege'     => 'index',
                        'title'         => 'Contact Us'
                    ]
                ]
            ]
        ]
    ]
];
