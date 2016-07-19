<?php 

return [
     'controllers' => [
         'invokables' => [
             'Company\Controller\Company' => 'Company\Controller\CompanyController',
         ],
     ],

     // The following section is new and should be added to your file
     'router' => [
         'routes' => [
             'company' => [
                 'type'    => 'segment',
                 'options' => [
                     'route'    => '/company[/:action][/:id]',
                     'constraints' => [
                         'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                         'id'     => '[0-9]+',
                     ],
                     'defaults' => [
                         'controller' => 'Company\Controller\Company',
                         'action'     => 'index',
                     ],
                 ],
             ],
           
            
         ],
     ],

     'view_manager' => [
         'template_map' => [
             'company/projects' => __DIR__.'/../view/company/company/projects.phtml'
         ],
         'template_path_stack' => [
             'company' => __DIR__ . '/../view',
         ],
     ],
 ];