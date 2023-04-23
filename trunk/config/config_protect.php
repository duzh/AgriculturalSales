<?php

return new \Phalcon\Config(array(

   'database' => array(
        'adapter'  => 'Mysql',
        'host'     => '127.0.0.1',
        'username' => 'root',
        'password' => 'nihao!@#',
        'dbname'   => '5fengshou',
        'charset'  => 'utf8',
    ),
    'slave' => array(
        'adapter'  => 'Mysql',
        'host'     => '127.0.0.1',
        'username' => 'root',
        'password' => 'nihao!@#',
        'dbname'   => '5fengshou',
        'charset'  => 'utf8',
    ),
    'ync365' => array(
        'adapter'  => 'Mysql',
        'host'     => '192.168.98.1',
        'username' => 'root',
        'password' => 'nihao123',
        'dbname'     => 'ync365',
    ),

    'permission' => array(
        'adapter'  => 'Mysql',
        'host'     => '192.168.98.1',
        'username' => 'root',
        'password' => 'nihao123',
        'dbname'   => 'permission',
        'charset'  => 'utf8',
    ),

    'sso' => array(
        'host' => '10.0.1.11',
        'port' => 9999,
    ),

    'ynp' => array(
        'host' => '58.132.173.120',
        'port' => 9999,
    ),
    'memcache' => array(
        'host' => '127.0.0.1',
        'port' => 11211,
        'lifetime' => 36000
    ),

    'redis' => array(
        'host' => '127.0.0.1',
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