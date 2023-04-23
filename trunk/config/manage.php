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

    $router->setDefaultModule("manage");
    $router->setDefaultNamespace("Mdg\Manage\Controllers");
    $router->add('/manage', array(
        'module' => "manage",
        'action' => "index",
        'params' => "index"
    ));

    $router->add('/manage/:controller', array(
        'module' => "manage",
        'controller' => 1,
        'action' => "index"
    ));

    $router->add('/manage/:controller/:action/', array(
        'module' => "manage",
        'controller' => 1,
        'action' => 2
    ));

    $router->add('/manage/:controller/:action/:params', array(
        'module' => "manage",
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
    $url->setBaseUri('/manage/');

    return $url;
};
