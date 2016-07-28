<?php
return [
    'view_manager' => [
        'template_path_stack' => [
            'NHUtils' => __DIR__ . '/../view',
        ],
    ],
    'controllers' => [
        'invokables' => [
        ]
    ],
    //@todo what should routing be like if at all for utility module?
    'router' => [
        'routes' => [
            'nh' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/nh',
                    'defaults' => [
                        '__NAMESPACE__' => 'NHUtils\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ]
                ],
                'may_terminate' => true,
                'child_routes' => [
                    // This route is a sane default when developing a module;
                    // as you solidify the routes for your module, however,
                    // you may want to remove it and replace it with more
                    // specific routes.
                    'default' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/[:controller[/:action[/:id]]]',
                            'constraints' => [
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[0-9]*'
                            ],
                            'defaults' => []
                        ]
                    ]
                ]
            ]
        ]
    ],
]
;
