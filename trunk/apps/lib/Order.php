<?php
namespace Lib;
use Lib AS L;
use Mdg\Models as M;

/**
 * 订单操作
 */
class Order
{

    /*  订单关闭 */
    CONST ORDER_STATUS_SHUT = 1 ;
    /* 订单支付 */
    CONST ORDER_STATUS_PAY = 4 ;
    /* 订单完成*/
    CONST ORDER_STATUS_COMP = 6 ;

    private $callback_abc = ORDER_CALLBACK_URL. 'callback/orderAbcCallback';
    private $callback_ynp = '';


    private $_order_table = 'entrust_order';
    private $_order_table_detail = 'entrust_order_detail';

    /**
     * 委托支付订单
     */
    CONST ORDER_TYPE_NORMAL = 0 ;
    /**
     * 正常订单
     */
    CONST ORDER_TYPE_ENTRUST = 1 ;
    /* 订单支付方式 */
    CONST PAY_TYPE_YNP = 0;
    CONST PAY_TYPE_ABC = 1;


    private $db = null;
    /**
     * DB 
     * @param string $connect [description]
     */
    public function __construct($connect = 'db') {
        if(!isset($GLOBALS['di'][$connect])) {
            throw new \Exception('connect error');
        }
        $this->connect = $connect;
        $this->db = $GLOBALS['di'][$connect];

    }
    /**
     * 获取订单号
     * @param  [type] $oid 订单id
     * @return string
     */
    public static function getOrderSn($oid) {
         return 'WT' . date('Ymd') . $oid . L\Func::random(4,1);
    }

    /**
     * 农业银行支付方式
     * @param  array  $order 订单信息
     * order_sn         订单号
     * addtime          下单时间
     * order_amount     订单金额
     * goods_id         产品id
     * goods_name       产品名称
     * goods_price      产品单价
     * quantity         产品数量
     * source           订单来源 1 正常订单 0 委托订单
     * @return [type]        [description]
     */
    public function postAbcPayment($order = array()) {

        if(!isset($order['order_sn']) || !isset($order['order_amount'])  || !$order['order_amount'] || !isset($order['source'])) {
            return false;
        }

        $_POST = array(
            'PayTypeID' => 'ImmediatePay',
            'OrderDate' => date('Y/m/d', $order['addtime']) ,
            'OrderTime' => date("H:i:s", $order['addtime']) ,
            'orderTimeoutDate' => '20241019104901',
            'OrderNo' => $order['order_sn'],
            'CurrencyCode' => '156',
            'PaymentRequestAmount' => $order['order_amount'],
            'Fee' => '',
            'OrderDesc' => '丰收汇',
            'OrderURL' => ORDER_CALLBACK_URL.'msg/merchantqueryorder?ON=' . $order['order_sn'] . '&DetailQuery=1',
            'ReceiverAddress' => '1',
            'InstallmentMark' => '0',
            'InstallmentCode' => '',
            'InstallmentNum' => '',
            'CommodityType' => '0101',
            'BuyIP' => '',
            'ExpiredDate' => '30',
            'PaymentType' => 'A',
            'PaymentLinkType' => '1',
            'UnionPayLinkType' => '0',
            'ReceiveAccount' => '',
            'ReceiveAccName' => '',
            'NotifyType' => '1',
            'ResultNotifyURL' => $this->callback_abc,
            'MerchantRemarks' => 'Hi',
            'IsBreakAccount' => '0',
            'SplitAccTemplate' => '',
        );
   
        require_once (__DIR__ . '/abc/demo/MerchantPayment.php');

    }   
    
    /**
     * 云农宝支付
     * @param  array  $order 订单信息
     * @return [type]        [description]
     */
    public function postYnpPayment($order = array()){
        
        if(!$order || !isset($order['order_sn']) || !isset($order['username']) || !isset($order['sellname']) || !isset($order['order_amount'])) {
            return false;
        }

        $UmpayClass = new L\UmpayClass();
        $ThriftInterface = new L\Ynp($this->db);
        $mobile = $order['username'];

        $clientip = str_replace('.', '', L\Func::getIP());
        $sign = md5(md5($clientip . $mobile) . $UmpayClass->getYncMd5Key());
        $token = $ThriftInterface->createBindToken($clientip, $mobile, $sign);
        
        $UmpayClass = new L\UmpayClass();
        $data = array(
            'orderNum' => $order['order_sn'],
            'orderAmount' => $order['order_amount'],
            'orderDate' => date("Ymd", $order['addtime']) ,
            'orderName' => (trim($order['goods_name']) ),
            'payer' => trim($mobile),
            'receipt' => $order['sellname'],
            'order_id' => $order['order_id'],
            'source' => $UmpayClass->source,
            'clientip' => $clientip,
            'token' => $token,
        );

        $url = $UmpayClass->createData($data);
        // print_R($url);
        // exit;
        echo "<script>location.href='{$url}'</script>";
        exit;
    } 

    /**
     * 委托订单更新订单状态
     * @param  string  $order_sn 订单号 
     * @param  string  $iRspRef 流水号
     * @param  integer $state    更新状态
     * @param  integer $ismain   是否为主订单
     * @return boolean
     */
    public function updateState($order_sn = '' ,$pay_serial_num ='', $bank_serial_num, $state = 0, $pay_type=0 ,$ismain=0 ) {

        $time = CURTIME;
        if(!$order_sn || !$state)  {
            throw new \Exception('ORDER_STATE_ERRORS');
        }

        switch ($state) {
            case self::ORDER_STATUS_SHUT:
                    $this->orderWtShut($order_sn);
                break;
            case self::ORDER_STATUS_PAY:
                   $this->orderWtPay($order_sn , $pay_type,$pay_serial_num, $bank_serial_num);
                break;
            case self::ORDER_STATUS_COMP:
                    $this->orderWtCom($order_sn);
                break;
            default:
                throw new \Exception('ORDER_STATUS_ERRORS');
                break;
        }
    }

    /**
     * 委托订单支付
     * @param  string  $order_sn        订单号
     * @param  integer $pay_type        支付方式
     * @param  string  $pay_serial_num  云农宝流水号
     * @param  string  $bank_serial_num 银行刘说好
     * @return 
     */
    private function orderWtPay($order_sn ='',$pay_type=0,$pay_serial_num ='', $bank_serial_num='') {
        

        $sql = " SELECT order_id, order_sn  FROM entrust_order WHERE order_sn = '{$order_sn}' for update ";
        $data = $this->db->fetchOne($sql,2);
        if(!$data) {
            throw new \Exception("ORDER_ID_ERRORS");
        }
        $orderid = $data['order_id'];

        $time = CURTIME ;
        $state = self::ORDER_STATUS_PAY;
        $sql = " UPDATE  %s set %s, last_update_time  = '{$time}' where %s ";
        $phql = sprintf($sql, $this->_order_table, " ynp_sn= '{$pay_serial_num}', bank_sn ='{$bank_serial_num}', pay_time = '{$time}', pay_type = '{$pay_type}', order_status = '{$state}' ",  " order_sn = '{$order_sn}' AND order_status = '3' ");
        
        $this->db->execute($phql);
        if(!$this->db->affectedRows()) {
            throw new \Exception("ORDER_STATE_ERRORS");
        }

        $phql = sprintf($sql, $this->_order_table_detail, " pay_time = '{$time}', order_status = '{$state}'", " order_parent_id = '{$orderid}' AND order_status = '3' ");
        $this->db->execute($phql);
        if(!$this->db->affectedRows()) {
            throw new \Exception("ORDER_STATE_ERRORS");
        }

        
        
        return true;
    }
    /**
     * 委托订单取消
     * @param  string $order_sn 订单号
     * @return 
     */
    private function orderWtShut($order_sn ='') {
        $sql = " SELECT * FROM entrust_order WHERE order_sn = '{$order_sn}' for update ";

        $data = $this->db->fetchOne($sql,2);
        if(!$data) {
            throw new \Exception("ORDER_ID_ERRORS");
        }
        $orderid = $data['order_id'];
        $time = CURTIME ;
        $state = self::ORDER_STATUS_SHUT;
        $sql = " UPDATE  %s set %s, last_update_time  = '{$time}' where %s ";
        $phql = sprintf($sql, $this->_order_table, " order_status = '{$state}'", " order_sn = '{$order_sn}' AND order_status = '3' ");
      
        $this->db->execute($phql);
        if(!$this->db->affectedRows()) {
            throw new \Exception("ORDER_STATE_ERRORS");
        }

        $phql = sprintf($sql, $this->_order_table_detail, " order_status = '{$state}'", " order_parent_id = '{$orderid}' AND order_status = '3' ");
        $this->db->execute($phql);
        if(!$this->db->affectedRows()) {
            throw new \Exception("ORDER_STATE_ERRORS");
        }
        return true;
    }

    /**
     * 订单收货
     * @param  string $order_sn 订单号
     * @return 
     */
    private function orderWtCom($order_sn ='') {
        $sql = " SELECT * FROM entrust_order WHERE order_sn = '{$order_sn}' for update ";

        $data = $this->db->fetchOne($sql,2);
        if(!$data) {
            throw new \Exception("ORDER_ID_ERRORS");
        }
        $orderid = $data['order_id'];

        $time = CURTIME ;
        $state = self::ORDER_STATUS_COMP;
        $sql = " UPDATE  %s set %s, last_update_time  = '{$time}' where %s ";
        $phql = sprintf($sql, $this->_order_table, " order_status = '{$state}' ", " order_sn = '{$order_sn}' AND order_status > '3' ");
        $this->db->execute($phql);

        if(!$this->db->affectedRows()) {
            throw new \Exception("ORDER_STATE_ERRORS");
        }

        $phql = sprintf($sql, $this->_order_table_detail, " order_status = '{$state}'", " order_parent_id = '{$orderid}' AND order_status > '3' ");

        $this->db->execute($phql);
        if(!$this->db->affectedRows()) {
            throw new \Exception("ORDER_STATE_ERRORS");
        }
        return true;
    }

    
}