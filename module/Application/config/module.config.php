<?php
/**
 * Zend Framework (http://framework.zend.com/]
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c] 2005-2012 Zend Technologies USA Inc. (http://www.zend.com]
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return [
    'application' => [
        'name' 	        => 'Training Center',
        'version'       => '0.0.2',
        'admin-email'   => 'demoadmin@nathanhaley.com',
    ],
    'router' => [
        'routes' => [
            'home' => [
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ],
                ],
            ],
            'application' => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/application',
                    'defaults' => [
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'default' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => [
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ],
                            'defaults' => [],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            'translator'        => 'Zend\I18n\Translator\TranslatorServiceFactory',
            'cipher'            => 'Application\Service\Factory\SymmetricCipher',
            'navigation'        => 'Zend\Navigation\Service\DefaultNavigationFactory',
            'entity-manager'    => 'Application\Service\Factory\EntityManager',
        'database'              => 'Application\Service\Factory\Database',
        ],
        'aliases' => [
            'Zend\Authentication\AuthenticationService' => 'my_auth_service',
        ],
        'invokables' => [
            'doctrine-profiler'             => 'Application\Service\Invokable\DoctrineProfiler',
            'table-gateway'                 => 'Application\Service\Invokable\TableGateway',
            'my_auth_service' => 'Zend\Authentication\AuthenticationService',
        ],
        'initializers' => [
            'Application\Service\Initializer\DbProfiler',
        ],
    ],
    'translator' => [
        'locale' => 'en_US',
        'translation_file_patterns' => [
            [
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ],
        ],
    ],
    'controllers' => [
        'invokables' => [
            'Application\Controller\Index' => 'Application\Controller\IndexController'
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'                             => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index'                   => __DIR__ . '/../view/application/index/index.phtml',
            //Layout Partials
            'application/index/partials/head'           => __DIR__ . '/../view/application/index/partials/head.phtml',
            'application/index/partials/navTop'         => __DIR__ . '/../view/application/index/partials/navTop.phtml',
            'application/index/partials/breadcrumb'     => __DIR__ . '/../view/application/index/partials/breadcrumb.phtml',
            'application/index/partials/flashMessages'  => __DIR__ . '/../view/application/index/partials/flashMessages.phtml',
            'application/index/partials/footer'         => __DIR__ . '/../view/application/index/partials/footer.phtml',
            // Tiny Partials
            'partials/pageHeader'                       => __DIR__ . '/../view/share/partials/pageHeader.phtml',
            //Errors
            'error/404'                                 => __DIR__ . '/../view/error/404.phtml',
            'error/index'                               => __DIR__ . '/../view/error/index.phtml',
            // paginator views
            'paginator/sliding'                         => __DIR__ . '/../view/paginator/sliding.phtml',
            //data/entity lists
            'forms/entity_list'                         => __DIR__ . '/../view/share/forms/entity_list.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'navigation' => [
        'default' => [
            [
                'label'         => 'Home',
                'title'         => 'Home',
                'route'         => 'home',
                'controller'    => 'index',
                'pages' => [
                    [
                        'label'         => 'About',
                        'route'         => 'application/default',
                        'controller'    => 'index',
                        'action'        => 'about',
                        'title'         => 'About Us'
                    ],
                    [
                        'label'     => 'Book',
                        'uri'       => 'http://learnzf2.com',
                        'title'     => 'Book Learnzf2'
                    ],
                    [
                        'label'         => 'Privacy',
                        'route'         => 'application/default',
                        'controller'    => 'index',
                        'action'        => 'privacy',
                        'title'         => 'Privacy Statement'
                    ]
                ]
            ],
        ]
    ],
];
