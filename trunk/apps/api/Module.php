<?php
namespace Mdg\Api;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\MetaData\Files as FilesMetaData;
class Module implements ModuleDefinitionInterface
{

    /**
     * Registers the module auto-loader
     */
    public function registerAutoloaders(  \Phalcon\DiInterface $di=null )
    {


        $loader = new Loader();
        
        $loader->registerNamespaces(array(
            'Mdg\Api\Controllers' => __DIR__ . '/controllers/',
            'Mdg\Models' => __DIR__ . '/../models/',
            'Lib'   =>  __DIR__."/../lib/",
        ));
        
         // Listen all the loader events
        // $eventsManager = new \Phalcon\Events\Manager();
        // $eventsManager->attach('loader', function($event, $loader) {
        //     if ($event->getType() == 'beforeCheckPath') {
        //         echo $loader->getCheckedPath(),"<br>";
        //     }
        // });
        // $loader->setEventsManager($eventsManager);
        
        $loader->register();
    }

    /**
     * Registers the module-only services
     *
     * @param Phalcon\DI $di
     */
    public function registerServices(\Phalcon\DiInterface $di=null )
    {
    
        /**
         * Read configuration
         */
        // $config = include __DIR__ . "/config/config.php";
         $config = include __DIR__ . "/../../config/config_".DEV_MODE.".php";
        /**
         * Setting up the view component
         */
        $di->set('view', function () use ($config) {

            $view = new View();
            $view->setViewsDir(__DIR__ . '/views/');
            // $view->setViewsDir($config->application->viewsDir);

            $view->registerEngines(array(
                '.volt' => function ($view, $di) use ($config) {

                    $volt = new VoltEngine($view, $di);

                    $volt->setOptions(array(
                        'compiledPath' => $config->application->cacheDir,
                        'compiledSeparator' => '_'
                    ));

                    return $volt;
                },
                '.phtml' => 'Phalcon\Mvc\View\Engine\Php'
            ));

            return $view;
        }, true);

        /**
         * Database connection is created based in the parameters defined in the configuration file
         */
        $di->set('db', function () use ($config) {
            return new DbAdapter(array(
                'host' => $config->database->host,
                'username' => $config->database->username,
                'password' => $config->database->password,
                'dbname' => $config->database->dbname,
                'charset' => $config->database->charset,
            ));
        });

        $di['ync365'] = function () use ($config) {
            return new DbAdapter(array(
                "host" => $config->ync365->host,
                "username" => $config->ync365->username,
                "password" => $config->ync365->password,
                "dbname" => $config->ync365->dbname,
                'charset' => $config->database->charset
            ));
        };
        
        $di->set('flash', function() {
            return new \Phalcon\Flash\Direct();
        });
        $di->set('modelsMetadata', function () use ($config)
        {
            $metaData = new FilesMetaData(array(
                "lifetime" => 86400,
                "prefix"   => "my-prefix",
                "metaDataDir" => $config->application->cacheDir."/metadata/",
            ));

            return $metaData;
        });
        
        /* 写入节点 */
        $di->set('dbWrite', function () use ($config) 
        {
            return new DbAdapter(array(
                'host' => $config->database->host,
                'username' => $config->database->username,
                'password' => $config->database->password,
                'dbname' => $config->database->dbname,
                'charset' => $config->database->charset,
            ));
        });

        /**
         *  读取节点
         */
        $di->set('dbRead', function () use ($config) 
        {
            return new DbAdapter(array(
                'host' => $config->slave->host,
                'username' => $config->slave->username,
                'password' => $config->slave->password,
                'dbname' => $config->slave->dbname,
                'charset' => $config->slave->charset,
            ));
        });

        $di->set('stat', function () use ($config) 
        {
            return new DbAdapter(array(
                'host' => $config->stat->host,
                'username' => $config->stat->username,
                'password' => $config->stat->password,
                'dbname' => $config->stat->dbname,
                'charset' => $config->stat->charset,
            ));
        });

        /** chao begin */
        $di->set('redis', function () use ($config)
        {
            $redis = new \Redis();
            $redis->connect($config->redis->host, $config->redis->port);
            $redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_PHP);
            return $redis;
        });
        /** chao end */

        /** chao begin */
        $di->set('session', function () use ($config)
        {
            $session = new \Phalcon\Session\Adapter\Memcache(array(
                'uniqueId' => '',
                'host' => $config->memcache->host,
                'port' => $config->memcache->port,
                'persistent' => TRUE,
                'lifetime' => $config->memcache->lifetime,
                'prefix' => 'mdgapp_'
             ));
            if (!$session->isStarted()) {
                $session->start();
            }
            return $session;
        });
        /** chao end */
        
    }



}
