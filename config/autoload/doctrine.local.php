<?php
$host = '127.0.0.1';
return [
    'doctrine' => [
        'connection' => [
            // default connection name
            'orm_default' => [
                'driverClass' => 'Doctrine\\DBAL\\Driver\\PDOPgSql\\Driver',
                'params' => [
                    'host' => $host,
                    'port' => '5432',
                    'user' => 'postgres',
                    'password' => 'postgres',
                    'dbname' => 'postgres',
                ],
            ],
        ],
    ],
];
