<?php
return [
    'view_manager' => [
        'template_path_stack' => [
            'Debug' => __DIR__ . '/../view',
        ],
    ],
    'service_manager' => [
        'factories' => [
            'timer' => 'Debug\Service\Factory\Timer'
        ],
        'initializers' => [
            'Debug\Service\Initializer\DbProfiler',
        ],
        'aliases' => [
            'Application\Timer' => 'timer',
        ]
    ],
    'timer' => [
        'times_as_float' => true,
    ],
    'debug' => [
        'isDisplayed' => false,
    ],
];
