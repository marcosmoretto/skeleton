<?php
return array(
    'db' => array(
        'adapters' => array(
            'postgres' => array(
                'database' => 'postgres',
                'driver' => 'PDO_Mysql',
                'hostname' => '127.0.0.1',
                'username' => 'postgres',
                'password' => 'postgres',
                'port' => '5432',
                'dsn' => 'pgsql:dbname=postgres;host=127.0.0.1',
            ),
            'oauth2' => array(
                'database' => 'postgres',
                'driver' => 'PDO_Pgsql',
                'hostname' => '127.0.0.1',
                'username' => 'postgres',
                'password' => 'postgres',
                'port' => '5432',
                'dsn' => 'pgsql:dbname=postgres;host=127.0.0.1',
            ),
        ),
    ),
    'zf-mvc-auth' => array(
        'authentication' => array(
            'adapters' => array(
                'oauth2_pdo' => array(
                    'adapter' => 'ZF\\MvcAuth\\Authentication\\OAuth2Adapter',
                    'storage' => array(
                        'adapter' => 'pdo',
                        'dsn' => 'pgsql:dbname=postgres;host=127.0.0.1',
                        'route' => '/oauth',
                        'username' => 'postgres',
                        'password' => 'postgres',
                    ),
                ),
            ),
        ),
    ),
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\\DBAL\\Driver\\PDOPgSql\\Driver',
                'params' => array(
                    'host' => '127.0.0.1',
                    'port' => '5432',
                    'user' => 'postgres',
                    'password' => 'postgres',
                    'dbname' => 'postgres',
                ),
            ),
            'orm_alternative' => array(
                'driverClass' => 'Doctrine\\DBAL\\Driver\\PDOPgSql\\Driver',
                'params' => array(
                    'host' => '127.0.0.1',
                    'port' => '5432',
                    'user' => 'postgres',
                    'password' => 'postgres',
                    'dbname' => 'postgres',
                ),
            ),
        ),
        'entitymanager' => array(
            'orm_default' => array(
                'connection' => 'orm_default',
                'configuration' => 'orm_default',
            ),
            'orm_alternative' => array(
                'connection' => 'orm_alternative',
                'configuration' => 'orm_default',
            ),
        ),
    ),
);
