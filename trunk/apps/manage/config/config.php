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
     
    'permission' => array(
        'adapter'  => 'Mysql',
        'host'     => '203.158.23.43:63306',
        'username' => 'roles',
        'password' => 'nihao!@#',
        'dbname'   => 'permission',
        'charset'  => 'utf8',
    ),
    'application' => array(
        'controllersDir' => __DIR__ . '/../controllers/',
        'modelsDir'      => __DIR__ . '/../../models/',
        'viewsDir'       => __DIR__ . '/../views/',
        'cacheDir'       => __DIR__ . '/../../../cache/',
        'baseUri'        => '/',
    )
));
