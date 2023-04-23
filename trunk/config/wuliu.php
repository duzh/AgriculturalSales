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

    $router->setDefaultModule("wuliu");
    $router->setDefaultNamespace("Mdg\Wuliu\Controllers");
    $router->add('/wuliu', array(
        'module' => "wuliu",
        'action' => "index",
        'params' => "index"
    ));

    $router->add('/wuliu/:controller', array(
        'module' => "wuliu",
        'controller' => 1,
        'action' => "index"
    ));

    $router->add('/wuliu/:controller/:action/', array(
        'module' => "wuliu",
        'controller' => 1,
        'action' => 2
    ));

    $router->add('/wuliu/:controller/:action/:params', array(
        'module' => "wuliu",
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
    $url->setBaseUri('/wuliu/');

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
