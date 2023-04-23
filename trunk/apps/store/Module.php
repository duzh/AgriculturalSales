<?php
namespace Mdg\Store;
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
    public function registerAutoloaders(\Phalcon\DiInterface $di=null)
    {


        $loader = new Loader();
        
        $loader->registerNamespaces(array(
            'Mdg\Store\Controllers' => __DIR__ . '/controllers/',
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
    public function registerServices(\Phalcon\DiInterface $di=null)
    {
    
        /**
         * Read configuration
         */
        // $config = include __DIR__ . "/config/config.php";
                
        /** chao start */
        $config = include __DIR__ . "/../../config/config_".DEV_MODE.".php";
        /** chao end */
   
        /**
         * Setting up the view component
         */
        $di->set('view', function () use ($config) {

            $view = new View();

            /** chao begin */
            $view->setViewsDir(__DIR__ . '/views/');
            /** chao end */
         
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
        
        // $di->set('ync365', function () use ($config) {
        //     return new DbAdapter(array(
        //         'host' => $config->ync->host,
        //         'username' => $config->ync->username,
        //         'password' => $config->ync->password,
        //         'dbname' => $config->ync->dbname,
        //         'charset' => $config->database->charset,
        //     ));
        // });
        
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

        /** chao begin */
        $di->set('session', function () use ($config)
        {
            $session = new \Phalcon\Session\Adapter\Memcache(array(
                'uniqueId' => '',
                'host' => $config->memcache->host,
                'port' => $config->memcache->port,
                'persistent' => TRUE,
                'lifetime' => $config->memcache->lifetime,
                'prefix' => 'mdg_'
             ));
            if (!$session->isStarted()) {
                $session->start();
            }
            return $session;
        });
        /** chao end */

        /** chao begin */
        $di->set('redis', function () use ($config)
        {
            $redis = new \Redis();
            $redis->connect($config->redis->host, $config->redis->port);
            $redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_PHP);
            return $redis;
        });
        /** chao end */

        // $di->set('session', function () use ($config) 
        // {
        //     $session = new \Phalcon\Session\Adapter\Memcache(array(
        //         'uniqueId' => 'ync365',
        //         'host' => '10.0.0.34',
        //         'port' => 11213,
        //         'persistent' => TRUE,
        //         'lifetime' => 3600,
        //         'prefix' => 'mdg_'
        //      ));

        //     if (!$session->isStarted()) {
        //         $session->start();
        //     }
        //     return $session;
        // });
    }



}
