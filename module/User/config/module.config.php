<?php
return [
    'controllers' => [
        'invokables' => [
           'User\Controller\Account' => 'User\Controller\AccountController',
           'User\Controller\Log'     => 'User\Controller\LogController',
           'User\Controller\Fb'      => 'User\Controller\FbController',
        ],
    ],
    'router' => [
        'routes' => [
            'user' => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/user',
                    'defaults' => [
                        '__NAMESPACE__' => 'User\Controller',
                        'controller'    => 'Account',
                        'action'        => 'me',
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
                            'defaults' => [
                            ],
                        ],
                    ],
                    'list' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route' => '/list[/page/:page][/orderby/:orderby][/order/:order]',
                            'constraints' => [
                                'page'     => '[0-9]*',
                                'orderby' => 'id|email|name|role',
                                'order' => 'asc|desc'
                            ],
                            'defaults' => [
                                'controller'    => 'Account',
                                'action'        => 'list',
                                'page'          => '1',
                                'orderby' => 'id',
                                'order' => 'desc'
                            ],
                        ]
                    ],
                    'autologin' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route' => '/autologin[/username/:username]',
                            'constraints'   => [
                                'username'  => '[a-zA-Z][a-zA-Z]*',
                            ],
                            'defaults' => [
                                'controller'=> 'Log',
                                'action'    => 'autologin',
                                'username'  => 'demouser'
                            ],
                        ]
                    ]

                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'User' => __DIR__ . '/../view',
        ],
    ],
    'service_manager' => [
        'factories' => [
            'log'	            => 'User\Service\Factory\Log',
            'password-adapter'  => 'User\Service\Factory\PasswordAdapter',
            'auth' 	            => 'User\Service\Factory\Authentication',
            'acl'	            => 'User\Service\Factory\Acl',
            'user'	            => 'User\Service\Factory\User',
        ],
        'invokables' => [
            'user-entity'       => 'User\Model\Entity\User',
            'auth-adapter' 	    => 'User\Authentication\Adapter',
        ],
        'shared' => [
            'user-entity' => false,
        ],
        'initializers' => [
            'User\Service\Initializer\Password'
        ],
    ],
    'table-gateway' => [
        'map' => [
            'users' => 'User\Model\User',
        ]
    ],
    'doctrine' => [
        'entity_path' => [
                __DIR__ . '/../src/User/Model/Entity/',
        ],
        'initializers' => [
            // add here the list of initializers for Doctrine 2 entities..
            'User\Service\Initializer\Password'
        ],
    ],

    'acl' => [
        'role' => [
                // role -> multiple parents
                'guest'   => null,
                'member'  => null,
                'admin'   => 'member',
        ],
        'resource' => [
                // resource -> single parent
                'account'   => null,
                'log'       => null,
                'fb'        => null,
        ],
        'allow' => [
                // ['role', 'resource', ['permission-1', 'permission-2', ...]],
                ['guest', 'log', ['in', 'autologin']],
                ['guest', 'account', 'register'],
                ['guest', 'fb', ['fbLogin', 'fbCallback']],
                ['member', 'account', ['me', 'edit']],
                ['member', 'log', ['out', 'autologin']],
                ['member', 'fb', ['index', 'fbLogin', 'fbCallback']],
                ['admin', 'account', ['list', 'view', 'delete', 'edit', 'add']],
        ],
        'deny'  => [
                ['guest', null, 'delete'] // null as second parameter means all resources


        ],
        'defaults' => [
                'guest_role' => 'guest',
                'member_role' => 'member',
                'default_role' => 'member',
                'admin_role' => 'admin'
        ],
        'resource_aliases' => [
                'User\Controller\Account'   => 'account',
                'User\Controller\Fb'        => 'fb'
        ],

        // List of modules to apply the ACL. Protect current Module's pages.
        'modules' => [
                'User',
        ],
    ],
    'navigation' => [
        'default' => [
            [
                'label'         => 'User',
                'title'         => 'User',
                'route'         => 'user/default',
                'controller'    => 'account',
                'pages' => [
                        [
                            'label'         => 'Me',
                            'route'         => 'user/default',
                            'controller'    => 'account',
                            'action'        => 'me',
                            'resource'      => 'account',
                            'privilege'     => 'me',
                            'title'         => 'My Account'
                        ],
                        [
                            'label'         => 'Log in',
                            // uri
                            'route'         => 'user/default',
                            'controller'    => 'log',
                            'action'        => 'in',
                            // acl
                            'resource'      => 'log',
                            'privilege'     => 'in',
                            'title'         => 'Log In'
                        ],
                        [
                            'label'         => 'Register',
                            // uri
                            'route'         => 'user/default',
                            'controller'    => 'account',
                            'action'        => 'register',
                            // acl
                            'resource'      => 'account',
                            'privilege'     => 'register',
                            'title'         => 'Create/Register An Account'
                        ],
                        [
                            'label'         => 'Log out',
                            'route'         => 'user/default',
                            'controller'    => 'log',
                            'action'        => 'out',
                            'resource'      => 'log',
                            'privilege'     => 'out',
                            'title'         => 'Log Out'
                        ],
                        [
                            'label'         => 'List',
                            'route'         => 'user/list',
                            'resource'      => 'account',
                            'privilege'     => 'list',
                            'title'         => 'Users Account List'
                        ],
                        [
                            'label'         => 'Add',
                            'route'         => 'user/default',
                            'controller'    => 'account',
                            'action'        => 'add',
                            'resource'      => 'account',
                            'privilege'     => 'add',
                            'title'         => 'Add/Create User Account'
                        ],
                        [
                            'label'         => 'Edit',
                            'route'         => 'user/default',
                            'controller'    => 'account',
                            'action'        => 'edit',
                            'resource'      => 'account',
                            'privilege'     => 'edit',
                            'title'         => 'Edit User Account'
                        ],
                        /* Accessed from the list page */
                        [
                            'label'         => 'View',
                            'route'         => 'user/default',
                            'controller'    => 'account',
                            'action'        => 'view',
                            'resource'      => 'account',
                            'privilege'     => 'view',
                            'visible'       => false,
                            'title'         => 'View User Account Details'
                        ],
                        [
                            'label'         => 'Delete',
                            'route'         => 'user/default',
                            'controller'    => 'account',
                            'action'        => 'delete',
                            'resource'      => 'account',
                            'privilege'     => 'delete',
                            'visible'       => false,
                            'title'         => 'Delete User Account'

                        ],

                ]
            ]
        ]
    ],

];
