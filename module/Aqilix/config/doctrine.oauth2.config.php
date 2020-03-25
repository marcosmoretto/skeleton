<?php
return [
    'doctrine' => [
        'eventmanager' => [
            'orm_default' => [
                'subscribers' => [
                    // pick any listeners you need
                    'Gedmo\Timestampable\TimestampableListener',
                    'Gedmo\SoftDeleteable\SoftDeleteableListener'
                ],
            ],
        ],
        'driver' => [
            'aqilix_oauth2_entity' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\XmlDriver',
                'cache' => 'array',
                'paths' => [__DIR__ . '/orm/oauth2']
            ],
            'core_acl' => [
                'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [
                    __DIR__.'/Core/Entity/Acl'
                ],
            ],
            'core_gearman' => [
                'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [
                    __DIR__.'/Core/Entity/Gearman'
                ],
            ],
            'core_logs' => [
                'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [
                    __DIR__.'/Core/Entity/Logs'
                ],
            ],
            'core_mail' => [
                'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [
                    __DIR__.'/Core/Entity/Mail'
                ],
            ],
            'core_oauth' => [
                'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [
                    __DIR__.'/Core/Entity/Oauth'
                ],
            ],
            'core_endereco' => [
                'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [
                    __DIR__.'/Core/Entity/Endereco'
                ],
            ],
            'core_projeto' => [
                'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [
                    __DIR__.'/Core/Entity/Projeto'
                ],
            ],
            'orm_default' => [
                'drivers' => [
                    'Aqilix\OAuth2\Entity' => 'aqilix_oauth2_entity'
                ]
            ]
        ],
        'configuration' => [
            'orm_default' => [
                'filters' => [
                    'soft-deleteable' => 'Gedmo\SoftDeleteable\Filter\SoftDeleteableFilter'
                ]
            ]
        ]
    ]
];
