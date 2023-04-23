<?php
namespace Mdg\Member;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\MetaData\Files as FilesMetaData;
use Phalcon\Cache\Multiple;
use Phalcon\Cache\Backend\File as FileCache;
use Phalcon\Cache\Frontend\Data as DataFrontend;
/* 注册 thrift */
require_once __DIR__ . '/../lib/Thrift/Thrift/ClassLoader/ThriftClassLoader.php';
use Thrift\ClassLoader\ThriftClassLoader;
$loader = new ThriftClassLoader();
$loader->registerNamespace('Thrift', __DIR__ . '/../lib/Thrift/Thrift');
$loader->register();
use Thrift\Transport\TSocket;
use Thrift\Transport\THttpClient;
use Thrift\Transport\TFramedTransport;
use Thrift\Transport\TBufferedTransport;
use Thrift\Exception\TException;
use Thrift\Protocol\TCompactProtocol;
use Thrift\Protocol\TMultiplexedProtocol;
use Thrift\Protocol\TBinaryProtocol;
/* 记录用户日志 */
use Phalcon\Logger\Adapter\File as FileAdapter;

class Module implements ModuleDefinitionInterface
{
    /**
     * Registers the module auto-loader
     */
    
    public function registerAutoloaders(\Phalcon\DiInterface $di = null) 
    {
        $loader = new Loader();
        $loader->registerNamespaces(array(
            'Mdg\Member\Controllers' => __DIR__ . '/controllers/',
            'Mdg\Models' => __DIR__ . '/../models/',
            'Lib' => __DIR__ . "/../lib/",
            'Thrift' => __DIR__ . "/../lib/Thrift/Thrift",
        ));
        $loader->register();
    }
    /**
     * Registers the module-only services
     *
     * @param Phalcon\DI $di
     */
    
    public function registerServices(\Phalcon\DiInterface $di = null) 
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
            // $view->setViewsDir($config->application->viewsDir);

            /** chao begin */
            $view->setViewsDir(__DIR__ . '/views/');
            /** chao end */

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
         * 数据链接
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
        
        
        /**
         * model 缓存
         */
        $di->set('modelsMetadata', function () use ($config) 
        {
            $metaData = new FilesMetaData(array(
                "lifetime" => 86400,
                "prefix" => "my-prefix",
                "metaDataDir" => $config->application->cacheDir . "/metadata/",
            ));
            return $metaData;
        });
        /**
         * 注册刷新服务
         */
        $di->set('flash', function () 
        {
            return new \Phalcon\Flash\Direct();
        });
        /**
         * 注册 云农宝服务
         */
        $di->set('ynp', function () use ($config) 
        {
            /* 实例化 thrift接口 */
            require_once __DIR__ . "/../lib/Thrift/YncInteractionService/Types.php";
            require_once __DIR__ . "/../lib/Thrift/YncInteractionService/YncInteractionService.php";
            $socket = new TSocket($config->ynp->host, $config->ynp->port);
            $socket->setSendTimeout(100000);
            $socket->setRecvTimeout(200000);
            $transport = new TFramedTransport($socket);
            $protocol = new TBinaryProtocol($transport);
            $ync = new TMultiplexedProtocol($protocol, "YncInteractionService");
            $client = new \YncInteractionServiceClient($ync);
            $transport->open();
            return $client;
        });
        $di->set('cache', function () use ($config) 
        {
            $cache = new Multiple(array(
                new FileCache(new DataFrontend(array(
                    'lifetime' => 604800
                )) , array(
                    "prefix" => 'cache_',
                    "cacheDir" => $config->application->cacheDir . '/datacache/',
                ))
            ));
            return $cache;
        });
        $logger = new FileAdapter(PUBLIC_PATH . "/log/test.log", array(
            'mode' => 'w'
        ));


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
    }
}
