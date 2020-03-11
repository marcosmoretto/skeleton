<?php
return array(
    'zf-content-negotiation' => array(
        'selectors' => array(),
    ),
    'db' => array(
        'adapters' => array(),
    ),
    'zf-mvc-auth' => array(
        'authentication' => array(
            'adapters' => array(
                'oauth2_pdo' => array(
                    'adapter' => '',
                    'storage' => array(),
                ),
            ),
            'map' => array(
                'Acl\\V1' => 'oauth2_pdo',
                'Application\\V1' => 'oauth2_pdo',
                'Aqlix\\V1' => 'oauth2_pdo',
                'Core\\V1' => 'oauth2_pdo',
                'Gearman\\V1' => 'oauth2_pdo',
                'Profile\\V1' => 'oauth2_pdo',
                'User\\V1' => 'oauth2_pdo',
                'Usuarios\\V1' => 'oauth2_pdo',
                'Projeto\\V1' => 'oauth2_pdo',
            ),
        ),
    ),
    'router' => array(
        'routes' => array(
            'oauth' => array(
                'options' => array(
                    'spec' => '%oauth%',
                    'regex' => '(?P<oauth>(/oauth))',
                ),
                'type' => 'regex',
            ),
        ),
    ),
);
