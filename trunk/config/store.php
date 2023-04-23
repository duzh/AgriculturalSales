<?php

/**
 * Services are globally registered in this file
 */

use Phalcon\Mvc\Router;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\DI\FactoryDefault;
use Phalcon\Session\Adapter\Files as SessionAdapter;

/**
 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
 */

$di = new FactoryDefault();

/**
 * Registering a router
 */
$di['router'] = function () {

    $router = new Router();
    
    $router->setDefaultModule("store");
    $router->setDefaultNamespace("Mdg\Store\Controllers");
    $router->add('/store', array(
        'module' => "store",
        'action' => "index",
        'params' => "index"
    ));

    $router->add('/store/:controller', array(
        'module' => "store",
        'controller' => 1,
        'action' => "index"
    ));

    $router->add('/store/:controller/:action/', array(
        'module' => "store",
        'controller' => 1,
        'action' => 2
    ));

    $router->add('/store/:controller/:action/:params', array(
        'module' => "store",
        'controller' => 1,
        'action' => 2,
        'params' => 3
    ));


    return $router;
};

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di['url'] = function () {
    $url = new UrlResolver();
    $url->setBaseUri('/store/');

    return $url;
};

/**
 * Start the session the first time some component request the session service
 */
$di['session'] = function () {
    $session = new SessionAdapter();
    $session->start();

    return $session;
};
