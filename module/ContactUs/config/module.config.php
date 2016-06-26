<?php
return array(
    'view_manager' => array(
        'template_path_stack' => array(
            'ContactUs' => __DIR__ . '/../view'
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'ContactUs\Controller\Index' => 'ContactUs\Controller\IndexController'
        )
    ),
    'router' => array(
        'routes' => array(
            'contactus' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/contactus',
                    'defaults' => array(
                        '__NAMESPACE__' => 'ContactUs\Controller',
                        'controller' => 'Index',
                        'action' => 'index'
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
                    ),

                    'list' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/contactus/list[/:page]',
                            'constraints' => array(
                                'page' => '[0-9]*'
                            ),
                            'defaults' => array(
                                'controller' => 'Index',
                                'action' => 'list',
                                'page' => '1'
                            )
                        )
                    )
                )
            )
        )
    ),
    'service_manager' => array(
        'factories' => array(
            'database' => 'ContactUs\Service\Factory\Database',
            'entity-manager' => 'ContactUs\Service\Factory\EntityManager'
        ),
        'invokables' => array(
            'table-gateway' => 'ContactUs\Service\Invokable\TableGateway',
            'contactus-entity' => 'ContactUs\Model\Entity\ContactUs',
            'doctrine-profiler' => 'ContactUs\Service\Invokable\DoctrineProfiler'
        ),
        'shared' => array(
            'contactUs-entity' => false
        )
    ),
    'table-gateway' => array(
        'map' => array(
            'contactus' => 'ContactUs\Model\ContactUs'
        )
    ),
    'doctrine' => array(
        __DIR__ . '/../src/ContactUs/Model/Entity/'
    ),
    'initializers' => array()
    // add here the list of initializers for Doctrine 2 entities..
    ,
    'acl' => array(
        'resource' => array(
            'index' => null
        ),
        'allow' => array(
            array('guest','index','index'),
            array('member','index','index'),
            array('admin',null,null)
        ),
        'modules' => array(
            'ContactUs'
        )
    ),
    'navigation' => array(
        'default' => array(
            array(
                'label' => 'Contact',
                'route' => 'contactus',
                'pages' => array(
                    array(
                        'label' => 'List',
                        'route' => 'contactus/list',
                        // acl
                        'resource' => 'index',
                        'privilege' => 'list'
                    ),
                    array(
                        'label' => 'Contact Us',
                        'route' => 'contactus',
                        // acl
                        'resource' => 'index',
                        'privilege' => 'index'
                    )
                )
            )
        )
    )
);
