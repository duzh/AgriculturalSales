<?php
namespace Mdg\Wuliu;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Cache\Frontend\Output as Fout;
use Phalcon\Cache\Backend\File as FileBackend;

use Phalcon\Mvc\Model\MetaData\Files as FilesMetaData;

use Phalcon\Cache\Multiple;
use Phalcon\Cache\Backend\File as FileCache;
use Phalcon\Cache\Frontend\Data as DataFrontend;


class Module implements ModuleDefinitionInterface
{
    /**
     * Registers the module auto-loader
     */
    
    public function registerAutoloaders(\Phalcon\DiInterface $di=null) 
    {
        $loader = new Loader();
        $loader->registerNamespaces(array(
            'Mdg\Wuliu\Controllers' => __DIR__ . '/controllers/',
            'Mdg\Models' => __DIR__ . '/../models/',
            'Lib' => __DIR__ . "/../lib/",
        ));

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
        $di->set('view', function () use ($config) 
        {
            $view = new View();

            /** chao begin */
            $view->setViewsDir(__DIR__ . '/views/');
            /** chao end */

            // $view->setViewsDir($config->application->viewsDir);
            $view->registerEngines(array(
                '.volt' => function ($view, $di) use ($config) 
                {
                    $volt = new VoltEngine($view, $di);
                    $volt->setOptions(array(
                        'compiledPath' => $config->application->cacheDir,
                        'compiledSeparator' => '_'
                    ));
                    return $volt;
                }
                ,
                '.phtml' => 'Phalcon\Mvc\View\Engine\Php'
            ));
            return $view;
        }
        , true);
        /**
         * Database connection is created based in the parameters defined in the configuration file
         */
        $di->set('db', function () use ($config) 
        {
            return new DbAdapter(array(
                'host' => $config->database->host,
                'username' => $config->database->username,
                'password' => $config->database->password,
                'dbname' => $config->database->dbname,
                'charset' => $config->database->charset,
            ));
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
         *  5丰收从节点
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

        
        $di->set('modelsMetadata', function () use ($config) 
        {
            $metaData = new FilesMetaData(array(
                "lifetime" => 86400,
                "prefix" => "my-prefix",
                "metaDataDir" => $config->application->cacheDir . "/metadata/",
            ));
            return $metaData;
        });

        $di->set('viewCache', function () use ($config) 
        {
            $frontCache = new \Phalcon\Cache\Frontend\Output(array(
                "lifetime" => 60
            ));
            $cache = new FileBackend($frontCache, array(
                'cacheDir' => $config->application->blockcacheDir
            ));
            return $cache;
        });
        /* 数据缓存 */
        $di->set('cache' , function () use ($config) {
            $cache = new Multiple(array(
            
                new FileCache(new DataFrontend(array('lifetime' => 7200)), array(
                    "prefix"   => 'cache_',
                    "cacheDir" => $config->application->cacheDir . '/datacache/' ,
                ))
            ));
            return $cache;
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
        //         'uniqueId' => '',
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
