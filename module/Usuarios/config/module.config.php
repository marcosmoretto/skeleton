<?php
return array(
    'service_manager' => array(
        'factories' => array(
            'Usuarios\\V1\\Rest\\Usuario\\UsuarioResource' => 'Usuarios\\V1\\Rest\\Usuario\\UsuarioResourceFactory',
        ),
    ),
    'router' => array(
        'routes' => array(
            'usuarios.rest.usuario' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/usuario[/:usuario_id]',
                    'defaults' => array(
                        'controller' => 'Usuarios\\V1\\Rest\\Usuario\\Controller',
                    ),
                ),
            ),
        ),
    ),
    'zf-versioning' => array(
        'uri' => array(
            0 => 'usuarios.rest.usuario',
        ),
    ),
    'zf-rest' => array(
        'Usuarios\\V1\\Rest\\Usuario\\Controller' => array(
            'listener' => 'Usuarios\\V1\\Rest\\Usuario\\UsuarioResource',
            'route_name' => 'usuarios.rest.usuario',
            'route_identifier_name' => 'usuario_id',
            'collection_name' => 'usuario',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
                4 => 'POST',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
                1 => 'POST',
            ),
            'collection_query_whitelist' => array(),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'Usuarios\\V1\\Rest\\Usuario\\UsuarioEntity',
            'collection_class' => 'Usuarios\\V1\\Rest\\Usuario\\UsuarioCollection',
            'service_name' => 'usuario',
        ),
    ),
    'zf-content-negotiation' => array(
        'controllers' => array(
            'Usuarios\\V1\\Rest\\Usuario\\Controller' => 'HalJson',
        ),
        'accept_whitelist' => array(
            'Usuarios\\V1\\Rest\\Usuario\\Controller' => array(
                0 => 'application/vnd.usuarios.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
        ),
        'content_type_whitelist' => array(
            'Usuarios\\V1\\Rest\\Usuario\\Controller' => array(
                0 => 'application/vnd.usuarios.v1+json',
                1 => 'application/json',
            ),
        ),
    ),
    'zf-hal' => array(
        'metadata_map' => array(
            'Usuarios\\V1\\Rest\\Usuario\\UsuarioEntity' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'usuarios.rest.usuario',
                'route_identifier_name' => 'usuario_id',
                'hydrator' => 'Zend\\Hydrator\\ArraySerializable',
            ),
            'Usuarios\\V1\\Rest\\Usuario\\UsuarioCollection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'usuarios.rest.usuario',
                'route_identifier_name' => 'usuario_id',
                'is_collection' => true,
            ),
        ),
    ),
    'zf-mvc-auth' => array(
        'authorization' => array(
            'Usuarios\\V1\\Rest\\Usuario\\Controller' => array(
                'collection' => array(
                    'GET' => true,
                    'POST' => true,
                    'PUT' => false,
                    'PATCH' => false,
                    'DELETE' => false,
                ),
                'entity' => array(
                    'GET' => true,
                    'POST' => true,
                    'PUT' => false,
                    'PATCH' => false,
                    'DELETE' => false,
                ),
            ),
        ),
    ),
);
