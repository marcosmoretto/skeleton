<?php
return [
    'service_manager' => [
        'factories' => [
            \Sistema\V1\Rest\Versao\VersaoResource::class => \Sistema\V1\Rest\Versao\VersaoResourceFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'sistema.rest.versao' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/versao[/:versao_id]',
                    'defaults' => [
                        'controller' => 'Sistema\\V1\\Rest\\Versao\\Controller',
                    ],
                ],
            ],
        ],
    ],
    'zf-versioning' => [
        'uri' => [
            0 => 'sistema.rest.versao',
        ],
    ],
    'zf-rest' => [
        'Sistema\\V1\\Rest\\Versao\\Controller' => [
            'listener' => \Sistema\V1\Rest\Versao\VersaoResource::class,
            'route_name' => 'sistema.rest.versao',
            'route_identifier_name' => 'versao_id',
            'collection_name' => 'versao',
            'entity_http_methods' => [
                0 => 'GET',
            ],
            'collection_http_methods' => [
                0 => 'GET',
            ],
            'collection_query_whitelist' => [],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \Sistema\V1\Rest\Versao\VersaoEntity::class,
            'collection_class' => \Sistema\V1\Rest\Versao\VersaoCollection::class,
            'service_name' => 'Versao',
        ],
    ],
    'zf-content-negotiation' => [
        'controllers' => [
            'Sistema\\V1\\Rest\\Versao\\Controller' => 'HalJson',
        ],
        'accept_whitelist' => [
            'Sistema\\V1\\Rest\\Versao\\Controller' => [
                0 => 'application/vnd.sistema.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
        ],
        'content_type_whitelist' => [
            'Sistema\\V1\\Rest\\Versao\\Controller' => [
                0 => 'application/vnd.sistema.v1+json',
                1 => 'application/json',
            ],
        ],
    ],
    'zf-hal' => [
        'metadata_map' => [
            \Sistema\V1\Rest\Versao\VersaoEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'sistema.rest.versao',
                'route_identifier_name' => 'versao_id',
                'hydrator' => \Zend\Hydrator\ArraySerializable::class,
            ],
            \Sistema\V1\Rest\Versao\VersaoCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'sistema.rest.versao',
                'route_identifier_name' => 'versao_id',
                'is_collection' => true,
            ],
        ],
    ],
];
