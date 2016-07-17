<?php
return [
    'controllers' => [
        'invokables' => [
           // below is key              and below is the fully qualified class name
           'User\Controller\Account' => 'User\Controller\AccountController',
           'User\Controller\Log'     => 'User\Controller\LogController',
        ],
    ],
    'router' => [
        'routes' => [
            'user' => [
                'type'    => 'Literal',
                'options' => [
                    // Change this to something specific to your module
                    'route'    => '/user',
                    'defaults' => [
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'User\Controller',
                        'controller'    => 'Account',
                        'action'        => 'me',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    // This route is a sane default when developing a module;
                    // as you solidify the routes for your module, however,
                    // you may want to remove it and replace it with more
                    // specific routes.
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
            'database' 	        => 'User\Service\Factory\Database',
            'entity-manager'    => 'User\Service\Factory\EntityManager',
            'log'	            => 'User\Service\Factory\Log',
            'password-adapter'  => 'User\Service\Factory\PasswordAdapter',
            'auth' 	            => 'User\Service\Factory\Authentication',
            'acl'	            => 'User\Service\Factory\Acl',
            'user'	            => 'User\Service\Factory\User',
        ],
        'invokables' => [
            'table-gateway'     => 'User\Service\Invokable\TableGateway',
            'user-entity'       => 'User\Model\Entity\User',
            'doctrine-profiler' => 'User\Service\Invokable\DoctrineProfiler',
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
        ],
        'allow' => [
                // ['role', 'resource', ['permission-1', 'permission-2', ...]],
                ['guest', 'log', ['in', 'autologin']],
                ['guest', 'account', 'register'],
                ['member', 'account', ['me', 'edit']],
                ['member', 'log', ['out', 'autologin']],
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
                'User\Controller\Account' => 'account',
        ],

        // List of modules to apply the ACL. This is how we can specify if we have to protect the pages in our current module.
        'modules' => [
                'User',
        ],
    ],
    // Below is the menu navigation for this module
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
