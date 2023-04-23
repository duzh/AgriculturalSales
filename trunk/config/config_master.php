<?php

return new \Phalcon\Config(array(

   'database' => array(
        'adapter'  => 'Mysql',
        'host'     => '10.0.0.21',
        'username' => '5fengshou',
        'password' => 'nihao123',
        'dbname'   => '5fengshou',
        'charset'  => 'utf8',
    ),
    'slave' => array(
        'adapter'  => 'Mysql',
        'host'     => '10.0.0.22',
        'username' => '5fengshou',
        'password' => 'nihao123',
        'dbname'   => '5fengshou',
        'charset'  => 'utf8',
    ),
    'ync365' => array(
        'adapter'  => 'Mysql',
        'host'     => '10.0.0.21',
        'username' => 'root',
        'password' => 'nihao123',
        'dbname'     => 'ync365',
    ),
    'permission' => array(
        'adapter'  => 'Mysql',
        'host'     => '10.0.0.21',
        'username' => 'roles',
        'password' => 'nihao!@#',
        'dbname'   => 'permission',
        'charset'  => 'utf8',
    ),

    'sso' => array(
        'host' => '10.0.1.11',
        'port' => 9999,
    ),

    'ynp' => array(
        'host' => '10.0.1.11',
        'port' => 9999,
    ),

    'memcache' => array(
        'host' => '10.0.0.34',
        'port' => 11213,
        'lifetime' => 36000
    ),

    'redis' => array(
        'host' => '203.158.23.104',
        'port' => 6379
    ),

    'application' => array(
        'modelsDir'      => __DIR__ . '/../apps/models/',
        'cacheDir'       => __DIR__ . '/../cache/',
        'logsDir'       => __DIR__ . '/../logs/',
        'blockcacheDir'  => __DIR__ . '/../cache/block/',
        'baseUri'        => '/',
    )
));