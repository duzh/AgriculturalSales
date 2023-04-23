<?php
namespace Lib;
use Lib AS L;
use Mdg\Models as M;

/**
 * 订单操作
 */
class Query
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
    public  function payqueryment($orderNo=''){
              
        $flag = false;
        /* 自动查询订单是否农行支付支付 */
        $PayTypeID =  'ImmediatePay' ;
        $QueryType = 'true';

        class_exists('QueryOrderRequest') or
        require __DIR__ . '/abc/ebusclient/QueryOrderRequest.php';
        $tRequest = new \QueryOrderRequest();
        
        $tRequest->request["PayTypeID"] = $PayTypeID; //设定交易类型
        $tRequest->request["OrderNo"] = $orderNo; //设定订单编号 （必要信息）
        $tRequest->request["QueryDetail"] = $QueryType; //设定查询方式
        

        $tResponse = $tRequest->postRequest();
        $orderInfo = $tResponse->GetValue("Order");
        $orderDetail = base64_decode($orderInfo);
        $orderDetail = iconv("GB2312", "UTF-8", $orderDetail);
        $detail = new \Json($orderDetail);
       
        $bank_sn=$detail->GetValue("iRspRef");
        $status=$detail->GetValue("Status");
        $pay_time=time();

        //3、支付请求提交成功，返回结果信息
        if ($tResponse->isSuccess()) {
           $payCode = (string)$tResponse->getReturnCode();
            
           if($status == '04' ) {
                /* 修改订单状态 */ 
                $GLOBALS['di']['db']->begin(); 
                $prefix = substr($orderNo , 0,2 );
                try {
                    
                    switch ($prefix) {
                        case 'WT':
                            $this->payWtOrders($orderNo, '', $bank_sn, L\Order::PAY_TYPE_ABC, '农业银行支付订单' );
                            break;
                        default:
                            $this->payOrders($orderNo, '', $bank_sn, L\Order::PAY_TYPE_ABC,'农业银行支付订单');
                            break;
                    }
                    $GLOBALS['di']['db']->commit();
                }catch (\Exception $e) {
                    $code = $e->getMessage();
                    
                }
               
           }
        }
        return $flag;
    }
     /**
     * 订单支付
     * @param  string  $order_sn        订单号
     * @param  string  $pay_serial_num  云农宝流水号
     * @param  string  $bank_serial_num 银行流水号
     * @param  integer $pay_type        支付方式
     * @param  string  $demo            备注
     * @return 
     */
    public  function payOrders($order_sn='', $pay_serial_num='', $bank_serial_num='', $pay_type=0, $demo='') {
        
        $time = CURTIME;
        if (!$order_sn || !$demo ) throw new \Exception("ORDERNUM_ERRORS");
        $order = new M\Orders();
        
        //检测订单状态
        $orders = $order->checkPay($order_sn, $GLOBALS['di']['db'], 1);
       
        //插入订单日志
        //订单日志 测试
        $sql = " INSERT INTO orders_log (state, operationid, operationname, type, addtime, demo,order_id) values('%s','%s','%s','%s','%s','%s','%s')";
        
        $phql = sprintf($sql, M\Orders::PAY_STATE, $orders['puserid'], $orders['purname'], 0, $time, $demo , $orders['id']);
        
        $GLOBALS['di']['db']->execute($phql);
        
        if (!$GLOBALS['di']['db']->affectedRows()) throw new \Exception(M\Orders::STATE_ERROR); //状态修改失败
        //更新订单状态
        
        $arr=$order->updateState($order_sn, M\Orders::PAY_STATE, $pay_serial_num, $bank_serial_num, $pay_type, $GLOBALS['di']['db']);
        
        $flag = 1;
        return $flag;
    }   
    /**
     * 委托订单
     * @param  string  $order_sn        订单号
     * @param  string  $pay_serial_num  云农宝流水号
     * @param  string  $bank_serial_num 银行流水号
     * @param  integer $pay_type        支付方式
     * @param  string  $demo            备注
     * @return 
     */
    public function payWtOrders($order_sn='' ,$pay_serial_num ='', $bank_serial_num='', $pay_type=0, $demo='') {
        $time = CURTIME;
        if (!$order_sn || !$demo) throw new \Exception("ORDERNUM_ERRORS");
        $log = array();
        $order = new M\EntrustOrder();
        
        list($data , $order_detail) = $order->checkWtPay($order_sn);   
        $liborder = new L\Order();
        $order_detail[] = $data;
        /* 更新订单状态  插入订单log */
        foreach ($order_detail as $key => $val) {
            $log['state']         = 4;
            $log['operationid']   = $data['buy_user_id'];
            $log['operationname'] = $data['buy_user_name'];
            $log['type']          =  M\EntrustOrdersLog::OPTYPE_USER;
            $log['demo']          = $data['buy_user_name'] . $demo;
            $log['order_id']      = isset($val['mainOrder']) ? $val['order_id'] : $val['order_detail_id']; 
            $log['order_sn']      = isset($val['mainOrder']) ? $val['order_sn'] : $val['order_detail_sn']; 
            $log['order_type']    = M\EntrustOrdersLog::ORDER_TYPE_ENTRUST;
            M\EntrustOrdersLog::saveOrderLog($log);
        }
        return $liborder->updateState($order_sn , $pay_serial_num, $bank_serial_num, M\EntrustOrder::ORDER_STATUS_PAY, $pay_type );
    }
}