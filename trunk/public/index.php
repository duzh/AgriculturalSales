<?php
//die("系统维护中");
use Phalcon\Mvc\Application;
use Phalcon\Events\Manager as EventsManager;
error_reporting(E_ALL);
ini_set("display_errors", "on"); 

/** chao begin */
define('DEV_MODE', 'dev'); // dev 开发  protect 预生产 master 正式
/** chao end */
//define('UPY_URL', 'http://yncstatic.b0.upaiyun.com/');
define('STATIC_URL', 'http://yncstatic.b0.upaiyun.com/');  //样式 
define('JS_URL', 'http://yncstatic.b0.upaiyun.com/js/');  //js
define('IMG_URL', 'http://yncmdg.b0.upaiyun.com/');       //卖的贵u盘云
define('YNC_URL', 'http://images.ync365.com/');
define('ITEM_IMG', 'http://yncmdg.b0.upaiyun.com/');
DEFINE("ARTICLE_URL", 'http://nj.ync365.com');            //农技文章

// define('THRIFT', dirname(__FILE__).'/../apps/lib/Thrift/');
define('CURTIME', time());                                //时间
$path = __DIR__;
define('PUBLIC_PATH', $path);                               //公共路劲
define('CALLBACK_URL',"http://mdgdev.ync365.com/");         //回调通知地址
define('WEIN_XIN', 'https://api.mch.weixin.qq.com/');       //微信api接口
define('CESHI_YNP', 'https://pay.ync365.com/');              //测试ynp地址
define('FORMAL_YNP', 'https://pay.ync365.com/');              //正式ynp地址
define('TEST', 0);              //正式ynp地址

define('BROKER_MEMBERID','2000000FSH01'); #直营经纪人MEMBER_ID
define('ROYALTY_MEMBERID','2000000FSH02'); #佣金收款MEMBER_ID

define("CUR_DEMAIN", strstr(strtolower($_SERVER['HTTP_HOST']), '.'));

// var_dump(strtolower($_SERVER['SERVER_NAME']));exit; 

switch (DEV_MODE) {
    case 'dev':
        // 用户注册
        define('HPROSE_UWEBSERVICE', 'http://uwebservice.ync365.com');
        // 后台操作记录
        define('HPROSE_API', "http://apidev.ync365.com");
        // 同步用友
        define('HPROSE_WEBSERVICE', "http://webservice.ync365.com");
        // C网工程师
        define('HPROSE_CSE', "http://service.ync365.com/api");
        //农行配置文件
        define('ABC_CONFIG', "TrustMerchantdev.ini");
         //订单回调地址
        define('ORDER_CALLBACK_URL',"http://www.ynb.com/");  
        //云农宝支付连接
        DEFINE("YNP_URL", 'https://paydev.ync365.com/'); 
        //同步消息的地址
        DEFINE("MSG_URL","http://shops.ync365.com");         
        //生成标签的地址
        DEFINE("TAG_URL","http://wx.5fengshou.com:81/");
        //收单网关回调地址
        DEFINE('TPAY_RETURN', 'http://mdgdev.ync365.com:81/');
        // 订单成功通知
        DEFINE('SHOPS_URL', 'http://shopsdev.ync365.com:81/');
        DEFINE('ACTIVE_SELL_ID', '989');
        DEFINE('ACTIVE_USER_ID', '581829');
        DEFINE('NYNP_URL', 'https://192.168.88.28/');              //测试ynp地址
        DEFINE('PAY_URL', 'http://tpay.ync365.com/gop/gateway/receiveOrder.do');              //测试收单网关地址
        break;

    case 'protect':
        define('HPROSE_UWEBSERVICE', 'http://uwebservice.ync365.com');
        // 后台操作记录
        define('HPROSE_API', "http://apidev.ync365.com");
        // 同步用友
        define('HPROSE_WEBSERVICE', "http://webservice.ync365.com");
        // C网工程师
        define('HPROSE_CSE', "http://service.ync365.com/api");
        //农行配置文件
        define('ABC_CONFIG', "TrustMerchant.ini");
         //订单回调地址
        define('ORDER_CALLBACK_URL',"http://wwwdev.5fengshou.com/");  
        //云农宝支付连接
        DEFINE("YNP_URL", 'https://paydev.ync365.com/'); 
        //同步消息的地址
        DEFINE("MSG_URL","http://shops.ync365.com");         
        //生成标签的地址
        DEFINE("TAG_URL","http://wx.5fengshou.com:81/");
        //收单网关回调地址
        DEFINE('TPAY_RETURN', 'http://58.132.173.120:8888/');
        // 订单成功通知
        DEFINE('SHOPS_URL', 'http://shopspro.ync365.com/');
        DEFINE('ACTIVE_SELL_ID', '989');
        DEFINE('ACTIVE_USER_ID', '581829');
        DEFINE('NYNP_URL', 'https://mpay.ync365.com/');              //预生产ynp地址
        DEFINE('PAY_URL', 'http://mpay.ync365.com/gop/gateway/receiveOrder.do');              //预生成收单网关地址
        break;
    
    default:
        // 用户注册
        define('HPROSE_UWEBSERVICE', 'http://uwebservice.ync365.com');
        // 后台操作记录
        define('HPROSE_API', "http://api.ync365.com");
        // 同步用友
        define('HPROSE_WEBSERVICE', "http://webservice.ync365.com");
        // C网工程师
        define('HPROSE_CSE', "http://service.ync365.com/api");
        //农行配置文件
        define('ABC_CONFIG', "TrustMerchantmaster.ini");
         //订单回调地址
        define('ORDER_CALLBACK_URL',"http://www.5fengshou.com/");  
        //云农宝支付连接
        DEFINE("YNP_URL", 'https://pay.ync365.com/'); 
        //同步消息的地址
        DEFINE("MSG_URL","http://shops.ync365.com");         
        //生成标签的地址
        DEFINE("TAG_URL","http://m.5fengshou.com/");
     //收单网关回调地址
        DEFINE('TPAY_RETURN', 'http://www.5fengshou.com/');  
        // 订单成功通知
        DEFINE('SHOPS_URL', 'http://shops.ync365.com/');
         DEFINE('ACTIVE_SELL_ID', '140499');
        DEFINE('ACTIVE_USER_ID', '537143');
        DEFINE('NYNP_URL', 'https://pay.ync365.com/');              //正式ynp地址
        DEFINE('PAY_URL', 'https://pay.ync365.com/gop/gateway/receiveOrder.do');              //正式收单网关地址
        break;
}

try
{
    /**
     * Include services
     */
    list(, $modules) = explode('/', $_SERVER['REQUEST_URI']);
    $_REQUEST['_url'] = rtrim(isset($_REQUEST['_url']) ? $_REQUEST['_url'] : '', '/');
    $_GET['_url'] = rtrim(isset($_GET['_url']) ? $_GET['_url'] : '', '/');
    
    if (file_exists($path . '/../config/' . $modules . '.php')) 
    {
        require __DIR__ . '/../config/' . $modules . '.php';
    }
    else
    {
        require __DIR__ . '/../config/frontend.php';
    }
    /**
     * Handle the request
     */
    $application = new Application();
    $eventsManager = new EventsManager();
    $application->setEventsManager($eventsManager);
    $eventsManager->attach("application", function ($event, $application) 
    {
        // ...
        // print_r($event);
        
    });
    /**
     * Assign the DI
     */
    $application->setDI($di);
    /**
     * Include modules
     */
    require __DIR__ . '/../config/modules.php';
// echo 'aa';exit;
    echo $application->handle()->getContent();
}
catch(Phalcon\Exception $e) 
{
   $code['code'] = $e->getMessage();
   $code['file'] = $e->getFile();
   $code['line'] = $e->getLine();
   // var_dump($code);die;
    $str = date("Y-m-d H:i:s").':';
    foreach ($code as $key => $val) {
        $str .= "{$key} => {$val}";
    }
    file_put_contents(PUBLIC_PATH.'/log/404error.txt',$str."\n", FILE_APPEND);
   echo "<script>location.href='/notfound/index'</script>";die;
}
catch(PDOException $e) 
{
    $code['code'] = $e->getMessage();
   $code['file'] = $e->getFile();
   $code['line'] = $e->getLine();
     // var_dump($code);die;
   $str = date("Y-m-d H:i:s").':';
    foreach ($code as $key => $val) {
        $str .= "{$key} => {$val}";
    }
    file_put_contents(PUBLIC_PATH.'/log/404error.txt',$str."\n", FILE_APPEND);
   echo "<script>location.href='/notfound/index'</script>";die;
    var_dump($code);
    exit;
    // var
}
catch(Exception $e) 
{
    $code['code'] = $e->getMessage();
   $code['file'] = $e->getFile();
   $code['line'] = $e->getLine();
     // var_dump($code);die;
    $str = date("Y-m-d H:i:s").':';
    foreach ($code as $key => $val) {
        $str .= "{$key} => {$val}";
    }
    file_put_contents(PUBLIC_PATH.'/log/404error.txt',$str."\n", FILE_APPEND);
  echo "<script>location.href='/notfound/index'</script>";die;
    var_dump($code);
    exit;
}