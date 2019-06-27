<?php
$host = '127.0.0.1';

return [
    'zf-oauth2' => [
    	'storage' => 'user.auth.pdo.adapter',
        'db' => [
            'dsn' => "pgsql:dbname=postgres;host={$host}",
           	'route' => '/oauth',
           	'username' => 'postgres',
           	'password' => 'postgres',
       	],
       	'options' => [
            'always_issue_new_refresh_token' => true,
            'unset_refresh_token_after_use' => true,
        ],
        'allow_implicit' => false, // default (set to true when you need to support browser-based or mobile apps)
        'access_lifetime' => 3600, // default (set a value in seconds for access tokens lifetime)
        'enforce_state'  => true,  // default
    ],
];
