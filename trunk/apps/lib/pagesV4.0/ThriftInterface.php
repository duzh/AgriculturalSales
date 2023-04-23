<?php
namespace Lib;
define('LIB', dirname(__FILE__));
ini_set('display_errors', 1);
require_once LIB . '/Thrift/Thrift/ClassLoader/ThriftClassLoader.php';
use Thrift\ClassLoader\ThriftClassLoader;
$loader = new ThriftClassLoader();
$loader->registerNamespace('Thrift', LIB . '/Thrift');
$loader->register();
//YNC 云农场接口
require_once LIB . "/Thrift/YncInteractionService/Types.php";
require_once LIB . "/Thrift/YncInteractionService/YncInteractionService.php";
// 充值接口
// require_once LIB . "/Thrift/OpsDepositService/OpsDepositService.php";
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
    
    public $thriftHost = 'pay.ync365.com'; //Thrift接口服务器IP
    public $thriftPort = 9999; //Thrift端口
    public $client = null;
    private $call = '';
    private $transport = null;

    public function __construct() {

        try {
            
                $service = isset($agrs[0]) ? trim($agrs[0]) : '';
                require_once LIB . "/Thrift/YncInteractionService/Types.php";
                require_once LIB . "/Thrift/YncInteractionService/YncInteractionService.php";
                $socket = new TSocket($this->thriftHost, $this->thriftPort);
                $socket->setSendTimeout(100000);
                $socket->setRecvTimeout(200000);
                //      $transport = new TFramedTransport($socket);
                //      $protocol = new TCompactProtocol($transport);
                //      $ync = new TMultiplexedProtocol($protocol,'YncInteractionService');
                //      $client = new YncInteractionServiceClient($ync);
                //      $transport->open();
                $this->transport = new TFramedTransport($socket);
                $protocol = new TBinaryProtocol($this->transport);
                $ync = new TMultiplexedProtocol($protocol, "YncInteractionService");
                $client = new \YncInteractionServiceClient($ync);
                $this->transport->open();
                $this->client = $client;
            }
            catch(\Exception $e) 
            {
                $e->getMessage();
                exit;
            }
    }
    

    /**
     * 支付成功通知云农宝接口
     * @param  [type] $orderNo 订单号
     * @param  [type] $status  状态
     * @param  [type] $sign    签名串
     * @return boolean
     */
    public function noticeRechargeOpsTransactionStatus($orderNo='', $status='', $sign='') {
        $data = $this->client->noticeRechargeOpsTransactionStatus($orderNo, $status, $sign);
        return $data->value;
    }
    /**
     *  取消订单通知云农宝
     * @param  string  $orderNum 订单号
     * @param  integer $source   来源 2 丰收汇
     * @param  string  $sign     签名穿
     * @return boolean
     */
    public function noticeOpsTransactionClose ($orderNum='', $source=2,$sign='') {
        $data = $this->client->noticeOpsTransactionClose($orderNum, $source , $sign);
        return $data->value;
    }   
    /**
     * 订单确认收货通知云农宝
     * @param  string  $orderNo 订单号
     * @param  integer $status  订单状态
     * @param  integer $source  来源
     * @param  string  $sign    签名穿
     * @return boolean
     */
    public function noticeOpsTransactionStatus($orderNo='', $sign='', $status=3, $source=2 ) {
        $data = $this->client->noticeOpsTransactionStatus($orderNo, $status, $source, $sign);
        return $data->value;

    }

    /**
     * 根据手机号获取用户余额
     * @param  [type] $userPhone 用户手机号
     * @return 
     */
    public function getAmountByUserPhone($userPhone) 
    {
        $data =  $this->client->getAmountByUserPhone((string)$userPhone);
        return array($data->account , $data->freeezeAccount);
        
    }
    /**
     * 同步用户数据
     * @param  [type] $userId            用户id
     * @param  [type] $userPhone         用户手机号
     * @param  [type] $userEmail         邮箱
     * @param  [type] $userLoginPassword 登录密码
     * @param  [type] $regDate           注册日期
     * @param  [type] $realName          真是姓名
     * @param  [type] $userPayPassword   支付密码
     * @param  [type] $userSafeQuestion  问题
     * @param  [type] $userSafeAnswer    答案
     * @param  [type] $idcardNum         身份证
     * @param  [type] $amount            余额
     * @return boolean
     */
    public function userDataSync($userId = '',$userPhone = '',$userEmail = '',$userLoginPassword = '',$regDate = '',$realName = '',$userPayPassword = '',$userSafeQuestion = '',$userSafeAnswer = '',$idcardNum = '',$amount = '') 
    {   
        $regDate = date('YmdHis', $regDate + 28800);
        
        return $this->client->userDataSync((string)$userId, (string)$userPhone, (string)$userEmail, (string)$userLoginPassword, 
            (string)$regDate, (string)$realName, (string)$userPayPassword, (string)$userSafeQuestion, (string)$userSafeAnswer, (string)$idcardNum, (string)$amount);
    }

    /**
     * 生成token
     * @param  [type] $ip        本地ip
     * @param  [type] $userPhone 用户手机号
     * @param  [type] $sign      签名穿
     * @return string
     */
    public function createBindToken($clientip, $mobile, $sign) 
    {
        $data =  $this->client->createBindToken((string)$clientip, (string)$mobile, (string)$sign );
        return $data->value;
    }
    /**
     * 检测用户是否存在
     * @param  string $userPhone 用户手机号
     * @return boolean
     */
    
    public function checkPhoneExist($userPhone = '') 
    {
        $data =  $this->client->checkPhoneExist((string)$userPhone);
        return $data->value;

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