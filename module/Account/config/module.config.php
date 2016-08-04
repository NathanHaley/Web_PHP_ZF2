<?php
return [
    'controllers' => [
        'invokables' => [
           'Account\Controller\Account'        => 'Account\Controller\AccountController',
           'Account\Controller\AccountList'    => 'Account\Controller\AccountListController',
           'Account\Controller\Log'            => 'Account\Controller\LogController',
           'Account\Controller\Fb'             => 'Account\Controller\FbController',
        ],
    ],
    'router' => [
        'routes' => [
            'user' => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/user',
                    'defaults' => [
                        '__NAMESPACE__' => 'Account\Controller',
                        'controller'    => 'Account',
                        'action'        => 'index',
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
                                'controller'    => 'AccountList',
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
                    ],
                    'logout' => [
                        'type'    => 'Literal',
                        'options' => [
                            'route' => '/logout',
                            'defaults' => [
                                'controller'=> 'Log',
                                'action'    => 'out',
                             ],
                        ]
                    ]

                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'Account' => __DIR__ . '/../view',
        ],
    ],
    'service_manager' => [
        'factories' => [
            'log'	            => 'Account\Service\Factory\Log',
            'password-adapter'  => 'Account\Service\Factory\PasswordAdapter',
            'auth' 	            => 'Account\Service\Factory\Authentication',
            'acl'	            => 'Account\Service\Factory\Acl',
            'user'	            => 'Account\Service\Factory\User',
        ],
        'invokables' => [
            'user-entity'       => 'Account\Model\Entity\User',
            'auth-adapter' 	    => 'Account\Authentication\Adapter',
        ],
        'shared' => [
            'user-entity' => false,
        ],
        'initializers' => [
            'Account\Service\Initializer\Password'
        ],
    ],
    'table-gateway' => [
        'map' => [
            'user' => 'Account\Model\User',
        ]
    ],
    'doctrine' => [
        'entity_path' => [
                __DIR__ . '/../src/Account/Model/Entity/',
        ],
        'initializers' => [
            // add here the list of initializers for Doctrine 2 entities..
            'Account\Service\Initializer\Password'
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
                'account'       => null,
                'accountlist'   => null,
                'log'           => null,
                'fb'            => null,
        ],
        'allow' => [
                // ['role', 'resource', ['permission-1', 'permission-2', ...]],
                ['guest', 'log', ['in', 'autologin']],
                ['guest', 'account', 'register'],
                ['guest', 'fb', ['fbLogin', 'fbCallback']],
                ['member', 'account', ['me', 'edit', 'index']],
                ['member', 'log', ['out', 'autologin']],
                ['member', 'fb', ['index', 'fbLogin', 'fbCallback']],
                ['admin', 'account', ['view', 'delete', 'edit', 'add', 'admin']],
                ['admin', 'accountlist', 'list'],
        ],
        'deny'  => [
                ['guest', null, 'delete'] // null as second parameter means all resources
        ],
        'defaults' => [
                'guest_role'    => 'guest',
                'member_role'   => 'member',
                'default_role'  => 'member',
                'admin_role'    => 'admin'
        ],
        'resource_aliases' => [
                'Account\Controller\Account'       => 'account',
                'Account\Controller\AccountList'   => 'accountlist',
                'Account\Controller\Fb'            => 'fb'
        ],
        // List of modules to apply the ACL. Protect current Module's pages.
        'modules' => [
                'Account',
        ],
    ],
    'navigation' => [
        'default' => [
            [
                'label'         => 'Account',
                'title'         => 'Account',
                'route'         => 'user/default',
                'controller'    => 'account',
                'pages' => [
                        [
                            'label'         => 'My Home',
                            'route'         => 'user/default',
                            'controller'    => 'account',
                            'action'        => 'index',
                            'resource'      => 'account',
                            'privilege'     => 'index',
                            'title'         => 'My Home'
                        ],
                        [
                            'label'         => 'Log in',
                            // uri
                            'route'         => 'user/default',
                            // acl
                            'controller'    => 'log',
                            'action'        => 'in',
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
                            'label'         => 'My Profile',
                            'route'         => 'user/default',
                            'controller'    => 'account',
                            'action'        => 'me',
                            'resource'      => 'account',
                            'privilege'     => 'me',
                            'title'         => 'My Profile'
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
                        [
                            'label'         => 'Log out',
                            'route'         => 'user/logout',
                            'resource'      => 'log',
                            'privilege'     => 'out',
                            'title'         => 'Log Out'
                        ],

                        [
                            'label'         => 'Admin Tools',
                            'route'         => 'user/default',
                            'controller'    => 'account',
                            'action'        => 'admin',
                            'resource'      => 'account',
                            'privilege'     => 'admin',
                            'title'         => 'Admin Tools'
                        ],
                        [
                            'label'         => 'List',
                            'route'         => 'user/list',
                            'resource'      => 'accountlist',
                            'privilege'     => 'list',
                            'title'         => 'Users Accounts List'
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
