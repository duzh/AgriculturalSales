<?php
/**
 * 订单支付成功回调
 */
namespace Mdg\Frontend\Controllers;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Lib\Member as Member, Lib\Auth as Auth, Lib\SMS as sms, Lib\Utils as Utils;
use Mdg\Models\Users as Users;
use Mdg\Models\UsersExt as Ext;
use Mdg\Models as M;
use Lib as L;


class CallbackController extends ControllerBase
{
    /**
     * 订单支付成功回调
     * @return [type] [description]
     */
    
    public function indexAction() 
    {
  
        $data = $_GET;
        $time = date('Ymd') ; 
        $content = '';
        $logname = PUBLIC_PATH . "/log/{$time}_PAY_WT111.log";
        foreach ($data as $key => $val) {
            $content .= " {$key} => {$val},";    
        }
        file_put_contents($logname , $content, FILE_APPEND);

        $success = false;
        if (!$data) throw new \Exception(1);
        $UmpayClass      = new L\UmpayClass();
        $serialNum       = isset($data['serialNum']) ? $data['serialNum'] : '';
        $pay_type        = isset($data['thirdPay'])&&$data['thirdPay'] ? $data['thirdPay'] : '99' ;
        $orderName       = isset($data['orderName']) ? $data['orderName'] : '';
        $order_date      = isset($data['order_date']) ? $data['order_date'] : time();
        $pay_serial_num  = isset($data['pay_serial_num']) ? $data['pay_serial_num'] : '';
        $bank_serial_num = isset($data['bank_serial_num']) ? $data['bank_serial_num'] : '';
        
        $this->db->begin();
        if (!$UmpayClass->callback($data)) throw new \Exception(M\Orders::SIGN_ERROR);
        $order_sn = $data['orderNum'] ? $data['orderNum'] : 0;
        // $iRspRef = $pay_serial_num ;

        try {
            $prefix = substr($order_sn , 0,2 );

            switch ($prefix) {
                case 'WT':
                    $flag = $this->payWtOrders($order_sn,  $pay_serial_num, $bank_serial_num , L\Order::PAY_TYPE_YNP, '云农宝支付订单' );
                    break;
                default:
                    $this->payOrders($order_sn, $pay_serial_num, $bank_serial_num, 0,'云农宝支付订单');
                    break;
            }
            $success = $this->db->commit();
            $flag = 1 ;
            $pay = '成功';
        } catch (\Exception $e) {
            $success = false;
            $flag = 0;
            $this->db->rollback();
            $code['code'] = $e->getMessage();
            $code['line'] = $e->getLine();
            $code['file'] = $e->getFile();
            $code = implode('=>', $code);
            $pay = "失败，{$code}";

            
        }
        if($success){
            switch ($prefix) {
                case 'WT':
                 //同步用友
                    break;
                default:
                    $yongyou=L\Func::yongyouApi();
                    $yongyou->insertOrder_getinsert($order_sn, true, 'mdg');
                    break;
            }
        }
        /**
         * 记录订单支付log
         * @var [type]
         */
        $time = date('Ymd') ; 
        $logname = PUBLIC_PATH . "/log/{$time}_PAY_WT.log";
        $content = " {$order_sn}_云农宝支付 :{$pay}  " . date("Ymd H:i:s")."\n";
        file_put_contents($logname , $content, FILE_APPEND);

        unset($data['_url']);
        $sign = md5(md5($success.$data['orderNum']).$UmpayClass->getYncMd5Key());
        $data = join(":", array( intval($success),$data['orderNum'], $sign));
        
        if($success&&$prefix!='WT') {
            $orders = M\Orders::findFirstByorder_sn($order_sn);
            if($orders){
                    $mobile= $orders->purphone;
                    if($orders->activity_id>0){
                        $endm=date("m",$orders->except_shipping_time);
                        $endd=date("d",$orders->except_shipping_time);
                        
                        if($orders->except_shipping_type==1){
                            $y=date("Y",$orders->addtime);
                            $m=date("m",$orders->addtime);
                            $d=date("d",$orders->addtime);
                            
                            $address = $orders->areas_name ? str_replace("山东省,", '', $orders->areas_name) : "济宁市";
                            $address =str_replace(",", '', $address);
                            $msgs = "恭喜您已于{$y}年{$m}月{$d}号预订{$orders->quantity}箱丰收汇长沟葡萄，{$endm}月{$endd}号将配送至{$address}，如有疑问请拨打4008811365";
                            
                            $sms=new sms();
                            $str=$sms->send($mobile,$msgs);
                        }else{
                             $msgs = "恭喜您获得2015年{$endm}月{$endd}号参与丰收汇长沟葡萄采摘活动的机会，届时可前往长沟葡萄示范园进行采摘，活动时间8：00——12：00，如有疑问请拨打4008811365";
                             $sms=new sms();
                             $str=$sms->send($mobile,$msgs);
                        }
                       
                    }else{
                            $mobile= $orders->sphone;
                            $sms = new sms();
                            $msgs = $sms->getOrderSendContent($orders->order_sn , 1);
                            $str = $sms->send($mobile, $msgs);
                    }
            }

        }

        exit($data);        

    }

    /**
     * 农业银行订单回调支付
     * @return [type] [description]
     */
    public function orderAbcCallbackAction () {
       
        if(!isset($_POST['MSG'])){
            echo "<script>location.href='/member/ordersbuy/index'</script>";die;  
        }
        //$_POST['MSG'] = 'PE1TRz48TWVzc2FnZT48VHJ4UmVzcG9uc2U+PFJldHVybkNvZGU+MDAwMDwvUmV0dXJuQ29kZT48RXJyb3JNZXNzYWdlPr270tezybmmPC9FcnJvck1lc3NhZ2U+PEVDTWVyY2hhbnRUeXBlPkVCVVM8L0VDTWVyY2hhbnRUeXBlPjxNZXJjaGFudElEPjEwMzg4MTU1MDAwMDAwMzwvTWVyY2hhbnRJRD48VHJ4VHlwZT5BQkNDYXJkUGF5PC9UcnhUeXBlPjxPcmRlck5vPm1kZzE0NzEyMDE1MDcyNDQ3PC9PcmRlck5vPjxBbW91bnQ+MC4wMTwvQW1vdW50PjxCYXRjaE5vPjAwMDEwMjwvQmF0Y2hObz48Vm91Y2hlck5vPjAyODY5MjwvVm91Y2hlck5vPjxIb3N0RGF0ZT4yMDE1LzA3LzI2PC9Ib3N0RGF0ZT48SG9zdFRpbWU+MTE6Mjc6MTI8L0hvc3RUaW1lPjxNZXJjaGFudFJlbWFya3M+SGk8L01lcmNoYW50UmVtYXJrcz48UGF5VHlwZT5FUDAwNDwvUGF5VHlwZT48Tm90aWZ5VHlwZT4xPC9Ob3RpZnlUeXBlPjxpUnNwUmVmPjkwMTUwNzI2MTEyNzEyMjg3MTc8L2lSc3BSZWY+PC9UcnhSZXNwb25zZT48L01lc3NhZ2U+PFNpZ25hdHVyZS1BbGdvcml0aG0+U0hBMXdpdGhSU0E8L1NpZ25hdHVyZS1BbGdvcml0aG0+PFNpZ25hdHVyZT5KZ0s4V1gxN1RmVGdsZEdQUks0dkV0WjVyTlRJY204MElxTDNxVFlSVzhvZUQ0T2I3bzZNeGpNT3I3RkNJWXN5QWZOUnBNZTFGRVg0RW5nK1U0eURLektrRDM4cFVVQlJPSVhjNnpLM3NqZ1orWHlBMGR0OVpNcFFTVG9ZWWszWG9BdHpRdTk1dDZqanpwUmlFcDQ3Q0Q4WkhtTmdVMWdMRlczRmhGVFc5d2c9PC9TaWduYXR1cmU+PC9NU0c+';
        require_once (__DIR__ . '/../../lib/abc/ebusclient/Result.php');
        //1、取得MSG参数，并利用此参数值生成验证结果对象
        $tResult = new \Result();
        $tResponse = $tResult->init($_POST['MSG']);

        $this->db->begin();
         
        try {

            if (!$tResponse->isSuccess()) {
                throw new \Exception("ORDER_MSG_ERRORS");
            }   
            /* 获取订单号 以及支付流水 */
            $orderNum = $tResponse->getValue("OrderNo");
            $time = date('Ymd');
            $logname = PUBLIC_PATH . "/log/{$time}_nongPay.log";
            $content=$orderNum;
            foreach ($_POST as $key => $val) {
                $content .= " {$key} => {$val},";    
            }
            file_put_contents($logname , $content, FILE_APPEND);

            $iRspRef = $tResponse->getValue("iRspRef");
            $prefix = substr($orderNum , 0,2 );
            $pay_serial_num = '';
            switch ($prefix) {
                case 'WT':
                    $this->payWtOrders($orderNum, $pay_serial_num, $iRspRef, L\Order::PAY_TYPE_ABC, '农业银行支付订单' );
                    break;
                default:
                    $this->payOrders($orderNum, $pay_serial_num, $iRspRef , 1 , '农业银行支付订单');
                    break;
            }
            
            $this->db->commit();
            $success = true;

        } catch (\Exception $e) {
            $success = false;
            $this->db->rollback();
            $code = $e->getMessage();

            $line = $e->getLine();
            $file = $e->getFile();

            
        }
        if($success){
            switch ($prefix) {
                case 'WT':
                 //同步用友
                    break;
                default:
                    $yongyou=L\Func::yongyouApi();
                    $yongyou->insertOrder_getinsert($orderNum, true, 'mdg');
                    break;
            }
        }

        if($success&&$prefix!='WT') {
            $orders = M\Orders::findFirstByorder_sn($orderNum);
            if($orders){
                    $mobile= $orders->purphone;
                    if($orders->activity_id>0){
                        $endm=date("m",$orders->except_shipping_time);
                        $endd=date("d",$orders->except_shipping_time);
                        
                        if($orders->except_shipping_type==1){
                            $y=date("Y",$orders->addtime);
                            $m=date("m",$orders->addtime);
                            $d=date("d",$orders->addtime);
                            
                            $address = $orders->areas_name ? str_replace("山东省,", '', $orders->areas_name) : "济宁市";
                            $address =str_replace(",", '', $address);
                            $msgs = "恭喜您已于{$y}年{$m}月{$d}号预订{$orders->quantity}箱丰收汇长沟葡萄，{$endm}月{$endd}号将配送至{$address}，如有疑问请拨打4008811365";
                            
                            $sms=new sms();
                            $str=$sms->send($mobile,$msgs);
                        }else{
                             $msgs = "恭喜您获得2015年{$endm}月{$endd}号参与丰收汇长沟葡萄采摘活动的机会，届时可前往长沟葡萄示范园进行采摘，活动时间8：00——12：00，如有疑问请拨打4008811365";
                             $sms=new sms();
                             $str=$sms->send($mobile,$msgs);
                        }
                       
                    }else{
                            $mobile= $orders->sphone;
                            $sms = new sms();
                            $msgs = $sms->getOrderSendContent($orders->order_sn , 1);
                            $str = $sms->send($mobile, $msgs);
                    }
            }

        }
        
        /*  订单支付成功 短信发送 */
        // $sms = new sms();
        // $msgs = $sms->getOrderSendContent($orderNum , 1);
        // $mobile=$orders['sphone'];
        // $str = $sms->send($mobile, $msgs);
        if($prefix == 'WT') {
            echo "<script>location.href='/member/entrustorder/index'</script>";        
        }else{
            echo "<script>location.href='/member/ordersbuy/index'</script>";        
        }
        exit;
        

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

    /**
     * 订单支付
     * @param  string  $order_sn        订单号
     * @param  string  $pay_serial_num  云农宝流水号
     * @param  string  $bank_serial_num 银行流水号
     * @param  integer $pay_type        支付方式
     * @param  string  $demo            备注
     * @return 
     */
    public function payOrders($order_sn='', $pay_serial_num='', $bank_serial_num='', $pay_type=0, $demo='') {
        
        $time = CURTIME;
        if (!$order_sn || !$demo ) throw new \Exception("ORDERNUM_ERRORS");
        $order = new M\Orders();

        //检测订单状态
        $orders = $order->checkPay($order_sn, $this->db, 1);
        //插入订单日志
        //订单日志 测试
        $sql = " INSERT INTO orders_log (state, operationid, operationname, type, addtime, demo,order_id) values('%s','%s','%s','%s','%s','%s','%s')";
        
        $phql = sprintf($sql, M\Orders::PAY_STATE, $orders['puserid'], $orders['purname'], 0, $time, $demo , $orders['id']);
        $this->db->execute($phql);
        
        if (!$this->db->affectedRows()) throw new \Exception(M\Orders::STATE_ERROR); //状态修改失败
        //更新订单状态
        
        $order->updateState($order_sn, M\Orders::PAY_STATE, $pay_serial_num, $bank_serial_num, $pay_type, $this->db);
        $flag = 1;
        /* 通知云农宝状态 */
        // $ThriftInterface = new L\Ynp($this->ynp);
        // $UmpayClass = new L\UmpayClass();
        // $status = 1 ;
        // $source = L\Ynp::MDG_SOURCE;
        // $payment = 2; //支付方式 
        // $total = L\Func::format_money($orders['total']);
        // $sign = md5( md5($order_sn . $status . $total . $payment . $source)  . $UmpayClass->getYncMd5Key() );
        // $ynpInfo = $ThriftInterface->noticeRechargeOpsTransactionStatus($order_sn,  $status ,$total, $sign);
        /* 通知云农宝 end */
       
        return $flag;
    }
    
    /**
     * 云农宝订单支付
     * @return [type] [description]
     */
    public function payOrdersYnp($order_sn='') {

            $order = new M\Orders();
            //检测订单状态
            $orders = $order->checkPay($order_sn, $this->db, 1);
            //插入订单日志
            $sql = " INSERT INTO orders_log (state, operationid, operationname, type, addtime, demo,order_id) values('%s','%s','%s','%s','%s','%s','%s')";
            $phql = sprintf($sql, M\Orders::PAY_STATE, $orders['puserid'], $orders['purname'] ,0,time(),'云农宝支付订单', $orders['id'] );
            $this->db->execute($phql);
            if (!$this->db->affectedRows()) throw new \Exception(M\Orders::STATE_ERROR); //状态修改失败

            //更新订单状态
            $order->updateState($order_sn, M\Orders::PAY_STATE,$pay_serial_num , $bank_serial_num, M\Orders::PAY_YNP, $this->db);
            $flag = 1 ;
            $success = 1 ;

            return $success;
    }
    /**
     * 收单网关系统订单回调
     * @param null $order_sn
     * @return bool
     */
    public function tpayAction($type = null,$order_sn=null){
        try{
            if(!$type)throw new \Exception('type Error');
            switch($type){#1同步回调 2异步回调
                case 1:
                    if(!$order_sn)throw new \Exception('非法请求');
                    $order = M\Orders::findFirstByorder_sn($order_sn);
                    if(!$order) {
                        echo "<script>alert('订单错误');location.href='/member/ordersbuy'</script>";exit;
                    }
                    if($order->state == 4){ #验证是否已经异步回调执行过.跳转详情
                        echo "<script>alert('订单已支付');location.href='/member/ordersbuy/info/{$order->id}'</script>";exit;
                    }
                    $tpay = new L\TpayInterface();
                    $tpay_trade = $tpay->query_trade($order_sn,'ENSURE',null,null,null);#ENSURE 担保交易
                    #print_r($tpay_trade);exit;
                    if(!$tpay_trade && $tpay_trade->is_success == 'F')throw new \Exception('非法请求');
                    if($tpay_trade->is_success == 'T'){

                        $trade = $tpay_trade->trade_list[0];
                        if($trade->tradeStatus == 'PAY_SUCCESS' || $trade->tradeStatus == 'PAY_FINISHED') {
                            $success = false;
                            $UmpayClass      = new L\UmpayClass();
                            $this->db->begin();
                            try {
                                $prefix = substr($order_sn , 0,2 );
                                switch ($prefix) {
                                    case 'WT':
                                        $flag = $this->payWtOrders($order_sn,  $trade->innerTradeNo, $trade->innerTradeNo , L\Order::PAY_TYPE_YNP, '云农宝支付订单' );
                                        break;
                                    default:
                                        $this->payOrders($order_sn, $trade->innerTradeNo, $trade->innerTradeNo, 0,'云农宝支付订单');
                                        break;
                                }
                                $this->db->commit();
                                $success = true;
                                $flag = 1 ;
                                $pay = '成功';
                            } catch (\Exception $e) {
                                $success = false;
                                $flag = 0;
                                $this->db->rollback();  
                                $code['code'] = $e->getMessage();
                                $code['line'] = $e->getLine();
                                $code['file'] = $e->getFile();
                                $code = implode('=>', $code);
                                $pay = "失败，{$code}";
                            }
                            if($flag){

                            }
                            /**
                             * 记录订单支付log
                             * @var [type]
                             */
                            $time = date('Ymd') ;
                            $logname = PUBLIC_PATH . "/log/{$time}_PAY_WT.log";
                            $content = " {$order_sn}_云农宝支付 :{$pay}  " . date("Ymd H:i:s")."\n";
                            file_put_contents($logname , $content, FILE_APPEND);

                            //unset($data['_url']);
                            $sign = md5(md5($success.$order_sn).$UmpayClass->getYncMd5Key());
                            $data = join(":", array( intval($success),$order_sn, $sign));

                            if($success&&$prefix!='WT') {
                                $orders = M\Orders::findFirstByorder_sn($order_sn);
                                if($orders){
                                    $mobile= $orders->purphone;
                                    if($orders->activity_id>0){
                                        $endm=date("m",$orders->except_shipping_time);
                                        $endd=date("d",$orders->except_shipping_time);

                                        if($orders->except_shipping_type==1){
                                            $y=date("Y",$orders->addtime);
                                            $m=date("m",$orders->addtime);
                                            $d=date("d",$orders->addtime);

                                            $address = $orders->areas_name ? str_replace("山东省,", '', $orders->areas_name) : "济宁市";
                                            $address =str_replace(",", '', $address);
                                            $msgs = "恭喜您已于{$y}年{$m}月{$d}号预订{$orders->quantity}箱丰收汇长沟葡萄，{$endm}月{$endd}号将配送至{$address}，如有疑问请拨打4008811365";

                                            $sms=new sms();
                                            $str=$sms->send($mobile,$msgs);
                                        }else{
                                            $msgs = "恭喜您获得2015年{$endm}月{$endd}号参与丰收汇长沟葡萄采摘活动的机会，届时可前往长沟葡萄示范园进行采摘，活动时间8：00——12：00，如有疑问请拨打4008811365";
                                            $sms=new sms();
                                            $str=$sms->send($mobile,$msgs);
                                        }

                                    }else{
                                        $mobile= $orders->sphone;
                                        $sms = new sms();
                                        $msgs = $sms->getOrderSendContent($order_sn , 1);
                                        $str = $sms->send($mobile, $msgs);
                                    }
                                }

                            }
                            echo "<script>alert('订单已支付');location.href='/member/ordersbuy/info/{$order->id}'</script>";exit;
                            #exit($data);
                        }
                        else{
                            echo "<script>location.href='/member/ordersbuy/info/{$order->id}'</script>";exit;
                        }
                    }
                    break;
                case 2:
                    try{

                        $params = (object)$_POST;
                        $checkP = $_POST;
                        if(!$_POST){ #未接到post参数
                            throw new \Exception('No post parameter');
                        }
                        //记录日志
                        $this->log($_GET,$_POST,'PAY_FINISHED','订单回调请求');     
                        $tpay = new L\TpayInterface();
                        #验证 RSA
                        $sign = false;
                        unset($checkP['sign']);
                        unset($checkP['sign_type']);
                        ksort($checkP);
                        $str_parsms = '';
                        foreach($checkP AS $pk => $pv){
                            $str_parsms .= $pk.'='.$pv.'&';
                        }
                        $str_parsms = rtrim($str_parsms,'&');
                        switch($params->sign_type){
                            case 'RSA':
                                $sign = $tpay->rsa->rsaVerify($str_parsms,$params->sign);
                                break;
                            case 'MD5':
                                $md5 = md5($str_parsms,$tpay->md5salt);
                                $sign = ($md5)?true:false;
                                break;
                        }

                        #判断验签
                        if(!$sign){ #验签未通过
                             throw new \Exception('check is not passed');
                            // $this->reParams(2,'check is not passed');
                        }
                        $order_sn = $params->outer_trade_no;
                        $order = M\Orders::findFirstByorder_sn($order_sn);

                        $this->db->begin();
                        switch($params->trade_status){
                            case 'PAY_FINISHED'; #买家已付款
                                if($order->state == 4){
                                    throw new \Exception('Orders PAY_FINISHED');
                                }
                                $prefix = substr($order_sn , 0,2 );
                                switch ($prefix) {
                                    case 'WT':
                                        $flag = $this->payWtOrders($order_sn,  $params->inner_trade_no, $params->inner_trade_no , L\Order::PAY_TYPE_YNP, '云农宝支付订单' );
                                        break;
                                    default:
                                        $this->payOrders($order_sn, $params->inner_trade_no, $params->inner_trade_no, 0,'云农宝支付订单');
                                        break;
                                }
                                break;
                            case 'TRADE_SUCCESS';#交易成功
                                $order->state = 4;
                                break;
                            case 'TRADE_FINISHED';#交易结束
                                if($order->state == 6 ){
                                    throw new \Exception('expired TRADE_FINISHED');
                                }
                                $order->state = 6;
                                //插入订单日志
                                $sql = " INSERT INTO orders_log (state, operationid, operationname, type, addtime, demo,order_id) values('%s','%s','%s','%s','%s','%s','%s')";
                                $demo = '交易结束收单网关回调';
                                $phql = sprintf($sql, $order->state, $order->puserid, $order->purname, 0, time(), $demo , $order->id);
                                $this->db->execute($phql);
                                $order->save();
                                break;
                            case 'TRADE_CLOSED';#交易关闭
                                break;
                        }
                        $this->db->commit();
                        $flag=true;
                        echo "success";
                    } catch (\Exception $e) {
                        echo "error";
                        $this->db->rollback();                       
                        $this->reParams(2,$e->getMessage());
                        $flag=false;
                    }
                    if($flag){
                        $yongyou=L\Func::yongyouApi();
                        $yongyou->insertOrder_getinsert($order_sn, true, 'mdg');
                    }
                    exit;
                    break;
            }
        }
        catch(\Exception $e)
        {
            echo "<script>alert('{$e->getMessage()}');location.href='/index'</script>";exit;
            return false;
        }
    }

    /**
     * 日志方法
     * @param $get get参数
     * @param $post post参数
     * @param $res 返回打印参数
     * @param $msg 消息
     */
    public function log($get,$post,$res,$msg){
        $data = date("Y-m-d H:i:s")." GET:".json_encode($get).";POST:".json_encode($post).";RETURN:{$res};MSG:{$msg}\n";
        file_put_contents(PUBLIC_PATH.'/log/fsh_tpay.log',$data,FILE_APPEND);
    }

    public function reParams($status = 0,$msg=''){
        switch($status){
            case 1:
                $res='success';
                break;
            case 2:
                $res = "error";
                break;
            default:
                $res = 'status error';
                break;
        }
        echo $res;
        $this->log($_GET,$_POST,$res,$msg);
        exit;
    }

}
