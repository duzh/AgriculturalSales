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

    $router->setDefaultModule("frontend");
    $router->setDefaultNamespace("Mdg\Frontend\Controllers");
    $router->add('/abcd', array(
        "namespace" => "Mdg\Api\Controllers",
        'module'     => 'api',
        'controller' => "test",
        'action' => "t"
    ));

    $router->add('/sell/mc{mc:(\d+)}_a{a:(\d+)}_c{c:(\d+)}_f{f:[A-Z]?}_p{p:(\d+)}', array(
        "namespace" => "Mdg\Frontend\Controllers",
        "controller" => 'sell',
        "action" => 'index'
    ));

    $router->add('/sell/mc{mc:(\d+)}_a{a:(\d+)}_c{c:(\d+)}_f{f:[A-Z]?}_p', array(
        "namespace" => "Mdg\Frontend\Controllers",
        "controller" => 'sell',
        "action" => 'index'
    ));

    $router->add('/purchase/mc{mc:(\d+)}_a{a:(\d+)}_c{c:(\d+)}_f{f:[A-Z]?}_p{p:(\d+)}', array(
        "namespace" => "Mdg\Frontend\Controllers",
        "controller" => 'purchase',
        "action" => 'index'
    ));
    $router->add('/purchase/mc{mc:(\d+)}_a{a:(\d+)}_c{c:(\d+)}_f{f:[A-Z]?}_p', array(
        "namespace" => "Mdg\Frontend\Controllers",
        "controller" => 'purchase',
        "action" => 'index'
    ));
    $router->add('/farmlist/mc{mc:(\d+)}_a{a:(\d+)}_c{c:(\d+)}_f{f:[A-Z]?}_p{p:(\d+)}', array(
        "namespace" => "Mdg\Frontend\Controllers",
        "controller" => 'farmlist',
        "action" => 'index'
    ));
    $router->add('/ajax/:action/mc{mc:(\d+)}_a{a:(\d+)}_c{c:(\d+)}_f{f:[A-Z]?}_p{p:(\d+)}', array(
        "namespace" => "Mdg\Frontend\Controllers",
        "controller" => 'ajax',
        "action" => 1
    ));


    $router->add('/abcd/mc{mc:(\d+)}_a{a:(\d+)}_c{c:(\d+)}_f{f:[A-Z]?}_p{p:(\d+)}', array(
        "namespace" => "Mdg\Frontend\Controllers",
        "controller" => 'test',
        "action" => 'abc'
    ));

    return $router;
};

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di['url'] = function () {
    $url = new UrlResolver();
    $url->setBaseUri('/');

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
