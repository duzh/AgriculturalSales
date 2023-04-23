<?php
namespace Lib;
define('LIB', dirname(__FILE__));
ini_set('display_errors', 1);
require_once LIB . '/Thrift/Thrift/ClassLoader/ThriftClassLoader.php';
use Thrift\ClassLoader\ThriftClassLoader;
$loader = new ThriftClassLoader();
$loader->registerNamespace('Thrift', LIB . '/Thrift');
$loader->register();

use Thrift\Transport\TSocket;
use Thrift\Transport\THttpClient;
use Thrift\Transport\TFramedTransport;
use Thrift\Transport\TBufferedTransport;
use Thrift\Exception\TException;
use Thrift\Protocol\TCompactProtocol;
use Thrift\Protocol\TMultiplexedProtocol;
use Thrift\Protocol\TBinaryProtocol;

/**
 * Thrift 接口实例化
 */

class ThriftInterface
{
    
    public $host = ''; //Thrift接口服务器IP
    
    public $port = 0; //Thrift端口
    
    public $client = null;
    
    private $call = '';
    
    private $transport = null;

    public function __construct($host='', $port=0) {
        $this->host = $host;
        $this->port = $port;
    }

    public function __call($class, $agrs) 
    {
        try
        {
            $service = isset($agrs[0]) ? trim($agrs[0]) : '';
            require_once LIB . "/Thrift/{$service}/Types.php";
            require_once LIB . "/Thrift/{$service}/{$service}.php";
            $socket = new TSocket($this->thriftHost, $this->thriftPort);
            $socket->setSendTimeout(100000);
            $socket->setRecvTimeout(200000);
        
            $this->transport = new TFramedTransport($socket);
            $protocol        = new TBinaryProtocol($this->transport);
            $ync             = new TMultiplexedProtocol($protocol, "{$service}");
            $client          = new $class($ync);

            $this->transport->open();
            $this->client = $client;
            return $client;
        }
        catch(\Exception $e) 
        {
            print_R($e->getMessage());
            exit;
        }
    }

    /**
     * 关闭进程
     */
    public function __destruct() 
    {
        $this->transport->close();
    }

    /**
     * 关闭进程
     */
    public function close() 
    {
        $this->transport->close();
    }
}
?>