<?php
return array(
    'service_manager' => array(
        'factories' => array(
            'Projeto\\V1\\Rest\\Projeto\\ProjetoResource' => 'Projeto\\V1\\Rest\\Projeto\\ProjetoResourceFactory',
        ),
    ),
    'router' => array(
        'routes' => array(
            'projeto.rest.projeto' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/projeto[/:projeto_id]',
                    'defaults' => array(
                        'controller' => 'Projeto\\V1\\Rest\\Projeto\\Controller',
                    ),
                ),
            ),
        ),
    ),
    'zf-versioning' => array(
        'uri' => array(
            0 => 'projeto.rest.projeto',
        ),
    ),
    'zf-rest' => array(
        'Projeto\\V1\\Rest\\Projeto\\Controller' => array(
            'listener' => 'Projeto\\V1\\Rest\\Projeto\\ProjetoResource',
            'route_name' => 'projeto.rest.projeto',
            'route_identifier_name' => 'projeto_id',
            'collection_name' => 'projeto',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
                1 => 'POST',
            ),
            'collection_query_whitelist' => array(),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'Projeto\\V1\\Rest\\Projeto\\ProjetoEntity',
            'collection_class' => 'Projeto\\V1\\Rest\\Projeto\\ProjetoCollection',
            'service_name' => 'projeto',
        ),
    ),
    'zf-content-negotiation' => array(
        'controllers' => array(
            'Projeto\\V1\\Rest\\Projeto\\Controller' => 'Json',
        ),
        'accept_whitelist' => array(
            'Projeto\\V1\\Rest\\Projeto\\Controller' => array(
                0 => 'application/vnd.projeto.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
        ),
        'content_type_whitelist' => array(
            'Projeto\\V1\\Rest\\Projeto\\Controller' => array(
                0 => 'application/vnd.projeto.v1+json',
                1 => 'application/json',
            ),
        ),
    ),
    'zf-hal' => array(
        'metadata_map' => array(
            'Projeto\\V1\\Rest\\Projeto\\ProjetoEntity' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'projeto.rest.projeto',
                'route_identifier_name' => 'projeto_id',
                'hydrator' => 'Zend\\Hydrator\\ArraySerializable',
            ),
            'Projeto\\V1\\Rest\\Projeto\\ProjetoCollection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'projeto.rest.projeto',
                'route_identifier_name' => 'projeto_id',
                'is_collection' => true,
            ),
        ),
    ),
    'zf-content-validation' => array(
        'Projeto\\V1\\Rest\\Projeto\\Controller' => array(
            'input_filter' => 'Projeto\\V1\\Rest\\Projeto\\Validator',
        ),
    ),
    'input_filter_specs' => array(
        'Projeto\\V1\\Rest\\Projeto\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => 'Zend\\Filter\\StripNewlines',
                        'options' => array(),
                    ),
                    2 => array(
                        'name' => 'Zend\\Filter\\StringTrim',
                        'options' => array(),
                    ),
                    3 => array(
                        'name' => 'Zend\\Filter\\StringToUpper',
                        'options' => array(),
                    ),
                ),
                'name' => 'nome',
                'description' => 'Nome do projeto',
                'error_message' => 'Verifique o nome do projeto',
            ),
            1 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Date',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'data_inicial',
                'description' => 'Data inicial do projeto',
                'error_message' => 'Verifique o campo data inicial',
            ),
        ),
    ),
    'zf-mvc-auth' => array(
        'authorization' => array(
            'Projeto\\V1\\Rest\\Projeto\\Controller' => array(
                'collection' => array(
                    'GET' => false,
                    'POST' => false,
                    'PUT' => false,
                    'PATCH' => false,
                    'DELETE' => false,
                ),
                'entity' => array(
                    'GET' => false,
                    'POST' => false,
                    'PUT' => false,
                    'PATCH' => false,
                    'DELETE' => false,
                ),
            ),
        ),
    ),
);
