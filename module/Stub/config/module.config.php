<?php
return array(
    'view_manager' => array(
        'template_path_stack' => array(
            'Stub' => __DIR__ . '/../view',
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Stub\Controller\Index' => 'Stub\Controller\IndexController'
        )
    ),
    'router' => array(
        'routes' => array(
            'stub' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/stub',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Stub\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    // This route is a sane default when developing a module;
                    // as you solidify the routes for your module, however,
                    // you may want to remove it and replace it with more
                    // specific routes.
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:controller[/:action[/:id]]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[0-9]*'
                            ),
                            'defaults' => array()
                        )
                    )
                )
            )
        )
    )
)
;
