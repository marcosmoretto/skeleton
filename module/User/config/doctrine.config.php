<?php
return [
    'doctrine' => [
        'driver' => [
            'user_entity' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\XmlDriver',
                'cache' => 'array',
                'paths' => [__DIR__ . '/orm']
            ],
            'orm_default' => [
                'drivers' => [
                    'Aqilix\OAuth2\Entity' => 'aqilix_oauth2_entity',
                    'User\Entity' => 'user_entity',
                    'Core\Entity\Acl' => 'core_acl',
                    'Core\Entity\Gearman' => 'core_gearman',
                    'Core\Entity\Logs' => 'core_logs',
                    'Core\Entity\Mail' => 'core_mail',
                    'Core\Entity\Oauth' => 'core_oauth',
                    'Core\Entity\Endereco' => 'core_endereco',
                    'Core\Entity\Projeto' => 'core_projeto',
                ]
            ]
        ],
    ],
    'data-fixture' => [
        'fixtures' => __DIR__ . '/../src/V1/Fixture'
    ],
];
