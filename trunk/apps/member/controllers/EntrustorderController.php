<?php
namespace Mdg\Member\Controllers;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Mdg\Models as M;
use Lib as L;

/**
 *  委托交易订单
 */


class EntrustorderController extends ControllerMember
{

    public function indexAction () {
        return $this->dispatcher->forward(array("action" => "buyList"));
    }
    /**
     * 我买入的订单
     * @return [type] [description]
     */
    public function buyListAction () {
        $stime       = $this->request->get('stime', 'string','');
        $etime       = $this->request->get('etime', 'string','');
        $state       = $this->request->get('state', 'int', 0);
        $minprice    = $this->request->get('minprice', 'float', '');
        $maxprice    = $this->request->get('maxprice', 'float', '');
        $maxcategory = $this->request->get('maxcategory', 'int', 0);
        $category    = $this->request->get('category', 'int', 0);
        $goods_name  = $this->request->get('goods_name', 'string', '');
        $page        = intval($this->request->get('p' , 'int',  1));
        $page = $page > 0 ? $page : 1 ;

        $psize       = $this->request->get('psize', 'int', 10);
        $uid = $this->getuserID();
        $cate = array(0 => '请选择', 1 => '请选择');
        if(!$uid) {
            
        }
      
        $cond[] = " buy_user_id = '{$uid}' ";
        if($goods_name) {
            $cond[] = " goods_name = '{$goods_name}'";
        }
        if($stime && $etime) {
            $starttime = strtotime($stime);
            $endtime = strtotime($etime . ' 23:59:59');
            $cond[] = " add_time BETWEEN '{$starttime}' AND  '{$endtime}'";
        }
        if($maxcategory) {
            $cond[] = " category_id_one = '{$maxcategory}'";
            $cate[0] = M\Category::selectBytocateName($maxcategory);
        }

        if($category) {
            $cond[] = " category_id_two = '{$category}'";
            $cate[1] = M\Category::selectBytocateName($category);
        }
        if($minprice) {
            $cond[] = " goods_price >= '{$minprice}' ";
        }
        if($maxprice) {
            $cond[] = " goods_price <= '{$maxprice}' ";
        }
        if($state) {
            $cond[] = " order_status = '{$state}'";
        }

        $cond = implode( ' AND ', $cond);

        $data = M\EntrustOrder::getEntrustOrderList($cond , $page , $psize);

        $this->view->cate = $cate = join('","', $cate);;
        $this->view->goods_name = $goods_name;
        $this->view->maxcategory = $maxcategory;
        $this->view->category = $category;
        $this->view->minprice = $minprice;
        $this->view->maxprice = $maxprice;
        $this->view->etime = $etime;
        $this->view->stime = $stime;
        $this->view->state = $state;
        $this->view->p    = $page;
        $this->view->data = $data;
        $this->view->_goods_unit = $_goods_unit = M\Purchase::$_goods_unit;
        $this->view->_order_status = $_order_status = M\EntrustOrder::$_order_status;
        $this->view->title = '个人中心-委托交易-我买入的订单';
    }

    
    /**
     * 新建委托交易
     * @return [type] [description]
     */
    public function buyNewAction () {

        $uid = $this->getUserID();
        $page = $this->request->get('p', 'int', 1 );
        
       
        $this->view->buy_mobile = $this->getUserName();
        
        $this->view->UserPartner = $UserPartner;
        $_goods_unit = M\Purchase::$_goods_unit;
        unset($_goods_unit[0]);
        $this->view->_goods_unit = $_goods_unit;
        $_pay_type = M\EntrustOrder::$_pay_type;
        $_pay_type[0] = '请选择';
        $this->view->_pay_type = $_pay_type;
        $this->view->title = '个人中心-新建委托交易';
    }
    
    /**
     * 保存委托交易  
     * @return 
     * 1  生成主订单
     * 2  关联子订单
     * 
     */
    public function buyCreateAction () {
       
        $goods['goods_name']      = $goods_name = $this->request->getPost('goods_name', 'string', '');
        $goods['goods_price']     = $goods_price =$this->request->getPost('goods_price', 'float', 0.00);
        $goods['goods_unit']      = $goods_unit = $this->request->getPost('goods_unit', 'int', 0);
        $goods['category_id_one'] = $category_id_one = $this->request->getPost('category_id_one', 'int', 1);
        $goods['category_id_two'] = $category_id_two = $this->request->getPost('category_id_two', 'int', 2);
        $goods['demo']            = $demo =$this->request->getPost('demo', 'string', '');
        $engphone = $this->request->getPost('engphone', 'string', '');
      
        $mobile       = $this->request->getPost('mobile');
        $realname     = $this->request->getPost('realname');
        $goods_number = $this->request->getPost('goods_number');
        $goods_amount = $this->request->getPost('goods_amount');
        $bank_name    = $this->request->getPost('bank_name');
        $bank_account = $this->request->getPost('bank_account');
        $bank_card    = $this->request->getPost('bank_card');
        $bank_address = $this->request->getPost('bank_address');
        $user_partner = $this->request->getPost('user_partner');
        $user['user_id']  = $this->getUserID();
        $user['username'] = $this->getUserName();
        $user['uname']    = $this->getUname();
        
        
        if($engphone && !L\Validator::validate_is_mobile($engphone)) {
            $this->showMsg('工程师手机号错误', 'entrustorder/index');
        }

        if(!$mobile || !$realname || !$goods_number || !$goods_amount || !$bank_name || !$bank_account || !$bank_card || !$category_id_two || !$user['user_id']) {
            $this->showMsg('参数错误', 'entrustorder/index');
        }


        $goodsAmount = 0 ;
        $goodsnumber= 0;
        foreach ($mobile as $key => $val) {
            if(!L\Validator::validate_is_mobile($val) || !$user_id = M\Users::selectByusername($val) ) {
                $this->showMsg('手机号错误', 'entrustorder/index');
            }
          
            /* 根据手机查询用户ID */
            $sell[$key]['partner_user_id'] = $user_id;
            $sell[$key]['goods_number']    =  isset($goods_number[$key]) ? intval($goods_number[$key]) : 0 ;
            $sell[$key]['user_bank_card']  = isset($bank_card[$key]) ? htmlentities($bank_card[$key]) : '';
            $sell[$key]['bank_address']    = isset($bank_address[$key]) ? htmlentities($bank_address[$key]) : '';
            $sell[$key]['bank_code']       = isset($bank_name[$key]) ? htmlentities($bank_name[$key]) : '';
            $sell[$key]['pay_type']        = 1;
            $sell[$key]['partner']         = isset($user_partner[$key])&&$user_partner[$key]== 'true' ? 1: 0;
            $sell[$key]['username']        = $val;
            $sell[$key]['uname']           = isset($realname[$key]) ? htmlentities($realname[$key]) : '';
            $sell[$key]['bank_account']    = isset($bank_account[$key]) ? htmlentities($bank_account[$key]) : '';
            $sell[$key]['user_id']         = $user['user_id'];
            
            $goodsAmount                   += $sell[$key]['goods_number'] * $goods['goods_price'];
            $goodsnumber                   += $sell[$key]['goods_number'];

        }

        $goods['goods_amount'] = $goodsAmount;
        $goods['goodsnumber']  = $goodsnumber;
        $order = array('order_sn' => '', 'order_id' => 0 );

        $this->db->begin();
        try {

            list( $order['order_id'] , $order['order_sn']) = M\EntrustOrder::MainOrderCreate($user, $goods, $sell);
            if(!$order['order_sn'] || !$order['order_id']) throw new \Exception("ORDER_SN_ERRORS");
            $order_id=$order['order_id'];
            $sellUserCount = count($sell);
            $goodsAmount = M\EntrustOrderDetail::DetailOrderCreate($order , $sell, $goods);
            
            //保存服务工程师信息
            if($engphone){
                $client = L\Func::serviceApi();
                $data = $client->county_selectByEngineerMobile($engphone); 
                if(!$data){ throw new \Exception("ORDER_ENGINEER_ERRORS");}
                $OrderEngineer                 = new M\OrderEngineer();
                $OrderEngineer->order_id       =$order['order_id'];
                $OrderEngineer->order_sn       =$order['order_sn'];
                $OrderEngineer->engineer_name  =$data['engineer_name']; 
                $OrderEngineer->engineer_phone =$data['engineer_phone']; 
                $OrderEngineer->add_time       =time(); 
                $OrderEngineer->engineer_id    =$data['engineer_id'];
                $OrderEngineer->type           = M\OrderEngineer::ORDER_TYPE_ORDERS_WT;
                
                if(!$OrderEngineer->save()){
                    throw new \Exception("ORDER_ENGINEER_ERRORS");
                }
            }

            $order = M\EntrustOrder::findFirst(" order_id = '{$order['order_id']}'");
            $order->goods_amount = $goodsAmount;
            $order->sell_user_count = $sellUserCount;
            if(!$order->save()) {
                throw new \Exception('ORDER_AMOUNT_ERRORS');
            }
            $this->db->commit();
        } catch (\Exception $e) {
            $this->db->rollback();
            $code['code'] = $e->getMessage();
            $code['line'] = $e->getLine();
            $code['file'] = $e->getFile();
            // var_dump($code);
          
        }

      
        /* 去付款 */
        $this->response->redirect("entrustorder/postPay/{$order_id}")->sendHeaders();
    }

    /**
     * 付款操作
     * @return [type] [description]
     */
    public function infoAction ($order_id=0) {
        $uid = $this->getUserID();
        $subsidy = 0;
        
        /* 获取产品子订单信息 */
        $detailOrder = M\EntrustOrder::findFirst(" order_id = '{$order_id}'");
         //自动查询订单
        $orderquery=new L\Query();
        $orderresult=$orderquery->payqueryment($detailOrder->order_sn);
        
        
        if(!$order_id || !$uid) {
            /* 去付款 */
            $this->response->redirect("entrustorder/index")->sendHeaders();
        }

        if(!$order = M\EntrustOrder::findFirst(" order_id = '{$order_id}'")) {
            /* 去付款 */
            $this->response->redirect("entrustorder/index")->sendHeaders();   
        }

        if($order->buy_user_id != $uid) {
            $this->response->redirect("entrustorder/index")->sendHeaders();      
        }

        /* 获取产品子订单信息 */
        $detailOrder = M\EntrustOrderDetail::find(" order_parent_id = '{$order_id}'");
 
        $detailOrderMaxState = M\EntrustOrderDetail::maximum(array("order_parent_id = '{$order_id}'", 'column' => 'order_status'));

        $this->view->detailOrderMaxState = $detailOrderMaxState;
        $this->view->detailOrder = $detailOrder;
        $this->view->_goods_unit = $_goods_unit = M\Purchase::$_goods_unit;
        $this->view->_order_status = $_order_status = M\EntrustOrder::$_order_status;
        $this->view->order = $order;
        $this->view->title = '个人中心-委托交易订单支付';
    }
    /**
     * 订单发货
     * @return [type] [description]
     */
    public static function orderShippingAction () {
    }  
    /**
     * 子订单收获
     * @param string $order_sn [description]
     */
    public function orderDetailCompleteAction ($oid='') {
        $uid = $this->getUserID();
        $uname = $this->getUserName();
        if(!$oid || !$uid) {
            $this->showMsg('参数错误', 'entrustorder/index');
        }

        /* 验证订单信息 */
        if(!$order = M\EntrustOrderDetail::findFirstByorder_detail_id($oid)) {
            $this->showMsg('订单信息错误', 'entrustorder/index');   
        }
        /* 验证主订单是否属于当前登陆 用户 */
        if(!$MainOrder = M\EntrustOrder::findFirst(" order_id = '{$order->order_parent_id}'")) {
            /* 去付款 */
            $this->response->redirect("entrustorder/index")->sendHeaders();   
        }

        if($MainOrder->buy_user_id != $uid) {
            $this->showMsg('无权操作此订单', 'entrustorder/index');
        }
        
        $this->db->begin();
        try {
            $EntrustOrder = new M\EntrustOrder();
            $demo = '订单收货';
            $operationtype=M\EntrustOrder::ADMIN_NOT_USER;
            if(!$EntrustOrder->ModyChildState($order->order_detail_id, $demo, $uid, $uname, $operationtype)) {
                throw new \Exception("UPDATE_STATE_ERRORS");
            }
            $this->db->commit();

        } catch (\Exception $e) {
            $this->db->rollback();
        }

        $this->response->redirect("entrustorder/index")->sendHeaders();
    }

    /**
     *订单收货
     * @return [type] [description]
     */
    public function orderCompleteAction () {
        $ordersn = $this->request->get('osn', 'string', '');
        $uid = $this->getUserID();
        $uname = $this->getUserName();

        if(!$ordersn || !$uid) {
            $this->response->redirect("entrustorder/index")->sendHeaders();
        }

        if(!$info = M\EntrustOrder::findFirst(" order_sn = '{$ordersn}' AND order_status >= '4'")) {
            /* 去付款 */
            $this->response->redirect("entrustorder/index")->sendHeaders();   
        }

        if($info->buy_user_id != $uid) {
            $this->showMsg('无权操作此订单', 'entrustorder/index');
        }

        /* 取消所有订单 */
        $order_sn = $info->order_sn;
        $liborder = new L\Order();
        $order = new M\EntrustOrder(); 
        list($data , $order_detail) = $order->checkWtPay($order_sn);   
        $order_detail[] = $data;

        $this->db->begin();
        try {

            /* 更新订单状态  插入订单log */
            foreach ($order_detail as $key => $val) {
                if($val['order_status']  < 6) {
                    $log = array();
                    $log['state']         = 6;
                    $log['operationid']   = $data['buy_user_id'];
                    $log['operationname'] = $data['buy_user_name'];
                    $log['type']          =  M\EntrustOrdersLog::OPTYPE_USER;
                    $log['demo']          = $data['buy_user_name'] . '订单收货';
                    $log['order_id']      = isset($val['mainOrder']) ? $val['order_id'] : $val['order_detail_id']; 
                    $log['order_sn']      = isset($val['mainOrder']) ? $val['order_sn'] : $val['order_detail_sn']; 
                    $log['order_type']    = M\EntrustOrdersLog::ORDER_TYPE_ENTRUST;
                    M\EntrustOrdersLog::saveOrderLog($log);
                }
            }

            $liborder->updateState($order_sn , '', '', L\Order::ORDER_STATUS_COMP);
            $this->db->commit();

        } catch (\Exception $e) {
            $this->db->rollback();
            // var_dump($e->getMessage());
            $code['code'] = $e->getMessage();
            $code['line'] = $e->getLine();
            $code['file'] = $e->getFile();
            // var_dump($code);
            
        }
        $this->response->redirect("entrustorder/info/{$info->order_id}")->sendHeaders();



    }

    /**
     * 订单支付
     * @return [type] [description]
     */
    public function postPayAction ($order_id=0) {

        $uid = $this->getUserID();
        $subsidy = 0;
        if(!$order_id || !$uid) {
            /* 去付款 */
            $this->response->redirect("entrustorder/index")->sendHeaders();
        }

        if(!$order = M\EntrustOrder::findFirst(" order_id = '{$order_id}' AND order_status = '3'")) {
            /* 去付款 */
            $this->response->redirect("entrustorder/index")->sendHeaders();   
        }
        if($order->buy_user_id != $uid) {
            $this->showMsg('来源错误', 'entrustorder/index');
        }

        /* 检测用户是否可以使用补贴 */
        if(M\UserInfo::selectBycredit_type($uid , M\UserInfo::USER_TYPE_IF)){
            $subsidy = M\UserSubsidy::getusermoney($uid , $order->goods_amount);
        }

        $this->view->subsidy = $subsidy;
        $this->view->_goods_unit = $_goods_unit = M\Purchase::$_goods_unit;
        $this->view->_order_status = $_order_status = M\EntrustOrder::$_order_status;
        $this->view->order = $order;
        $this->view->title = '个人中心-委托交易订单支付';

    }

    /**
     * 订单支付
     * @return [type] [description]
     */
    public function payAction ($oid=0) {
        $oid = $this->request->get('id', 'int', 0 );
        $pay = $this->request->get('ptype', 'string', '');
        if(!$oid || !$pay) {
            $this->response->redirect("entrustorder/index")->sendHeaders();
        }

        if(!$info = M\EntrustOrder::findFirst(" order_id = '{$oid}' AND order_status = '3'")) {
            /* 去付款 */
            $this->response->redirect("entrustorder/index")->sendHeaders();   
        }

        switch ($pay) {
            case 'ABC':
                $order = new L\Order();
                $data = $info->toArray();
                $data['source'] = L\Order::ORDER_TYPE_NORMAL;
                $data['order_amount'] = $info->order_amount;
                $data['addtime'] = $data['add_time'];
                $data['goods_id'] = 0 ;
                $data['quantity'] = intval($data['goods_number']);
                // $data['goods_name'] = M\EntrustOrderDetail::selectBygoods_name($data['order_id']);
               
                $posturl = $order->postAbcPayment($data);
                
                break;

            case 'YNP':
                $order = new L\Order('ynp');
                $data = $info->toArray();
                $data['source'] = L\Order::ORDER_TYPE_NORMAL;
                $data['order_amount'] = $info->order_amount;
                $data['addtime'] = $data['add_time'];
                $data['goods_id'] = 0 ;
                $data['quantity'] = intval($data['goods_number']);
                $data['goods_name'] = M\EntrustOrderDetail::selectBygoods_name($data['order_id']);
                $data['username'] = $data['buy_user_phone'];
                $data['sellname'] = '13121349730';
                $order->postYnpPayment($data);
                break;
            default:
                break;
        }
        exit;

    }

    /**
     * 取消订单
     * @param  integer $orderid 订单id
     * @return [type]           [description]
     */
    public function orderShutAction ($orderid=0) {
        /* 订单取消 */
        $uid = $this->getUserID();
        if(!$orderid || !$uid) {
            $this->response->redirect("entrustorder/index")->sendHeaders();   
        }

        if(!$info = M\EntrustOrder::findFirst(" order_id = '{$orderid}' AND order_status = '3'")) {
            /* 去付款 */
            $this->response->redirect("entrustorder/index")->sendHeaders();   
        }
        
        if($info->buy_user_id != $uid ) {
            $this->showMsg('来源错误', 'entrustorder/index');
        }

        /* 取消所有订单 */
        $order_sn = $info->order_sn;
        $liborder = new L\Order();
        $order = new M\EntrustOrder(); 
        list($data , $order_detail) = $order->checkWtPay($order_sn);   
        $order_detail[] = $data;

        $this->db->begin();
        try {

            /* 更新订单状态  插入订单log */
            foreach ($order_detail as $key => $val) {
                $log = array();
                $log['state']         = 1;
                $log['operationid']   = $data['buy_user_id'];
                $log['operationname'] = $data['buy_user_name'];
                $log['type']          =  M\EntrustOrdersLog::OPTYPE_USER;
                $log['demo']          = $data['buy_user_name'] . '关闭交易';
                $log['order_id']      = isset($val['mainOrder']) ? $val['order_id'] : $val['order_detail_id']; 
                $log['order_sn']      = isset($val['mainOrder']) ? $val['order_sn'] : $val['order_detail_sn']; 
                $log['order_type']    = M\EntrustOrdersLog::ORDER_TYPE_ENTRUST;
                
                M\EntrustOrdersLog::saveOrderLog($log);
            }

            $liborder->updateState($order_sn , '','',L\Order::ORDER_STATUS_SHUT);
            $this->db->commit();

        } catch (\Exception $e) {
            $this->db->rollback();
            var_dump($e->getMessage());
        }

    
        $this->response->redirect("entrustorder/info/{$info->order_id}")->sendHeaders();
    }

    /**
     * 我卖出的订单
     * @return [type] [description]
     */
    public function sellListAction () {
     
        $stime       = $this->request->get('stime', 'string','');
        $etime       = $this->request->get('etime', 'string','');
        $state       = $this->request->get('state', 'int', 0);
        $page        = $this->request->get('p' , 'int',  1);
        $page = $page > 0 ? $page : 1 ;
        $psize       = $this->request->get('psize', 'int', 10);
        

        if(!$uid = $this->getUserID()) {
            $this->showMsg('来源错误', 'entrustorder/index');
        }

        $cond[] = " sell_user_id = '{$uid}' ";
        if($stime && $etime) {
            $starttime = strtotime($stime);
            $endtime = strtotime($etime . ' 23:59:59');
            $cond[] = " add_time BETWEEN '{$starttime}' AND  '{$endtime}'";
        }
        if($state) {
             $cond[] = " order_status = '{$state}'";
        }

        $cond = implode( ' AND ', $cond);
        $data = M\EntrustOrderDetail::getEntrustOrderDetailList($cond, $page , $psize);
        
        $this->view->_goods_unit = $_goods_unit = M\Purchase::$_goods_unit;
        $this->view->_order_status = $_order_status = M\EntrustOrder::$_order_status;
        $this->view->state = $state;
        $this->view->data = $data;
        $this->view->stime = $stime;
        $this->view->page = $page;

        $this->view->etime = $etime;
        $this->view->title = '个人中心-委托交易-我卖出的订单';
    }
    
    /**
     * 卖出订单详细
     * @return [type] [description]
     */
    public function sellOrderInfoAction () {
        $oid = $this->request->get('oid', 'int', 0 );
        $uid = $this->getUserID();
        if(!$oid || !$uid ) {
            $this->showMsg('来源错误', 'entrustorder/index');
            
        }
        if(!$info  = M\EntrustOrderDetail::findFirstByorder_detail_id($oid)) {
            $this->response->redirect("entrustorder/sellList")->sendHeaders();
        }

        /*  查询父订单信息 */
        if(!$parInfo = M\EntrustOrder::findFirstByorder_id($info->order_parent_id)) {
            $this->response->redirect("Entrustorder/sellList")->sendHeaders();
        }
        
        if($info->sell_user_id != $uid && $parInfo->buy_user_id != $uid  ) {
            $this->showMsg('来源错误', 'entrustorder/index');
        }

        $this->view->user_id = $uid;
        $this->view->_goods_unit = $_goods_unit = M\Purchase::$_goods_unit;
        $this->view->order = $info;
        $this->view->pinfo = $parInfo;
        $this->view->title = '个人中心-委托订单-订单详细';

    }
    

    /**
     * 处理订单使用补贴
     * @return [type] [description]
     */
    public function orderSubsidyAction () {

        $order_sn   = $this->request->get('order_sn', 'string', '');
        $money      = (float)$this->request->get('money');
        $money = (float)L\Func::format_money($money);
        $is_subsigy = $this->request->get('is_subsigy', 'string', '');
        $uid = $this->getuserId();
        $msg = array('ok' => 1);
        /*  查询父订单信息 */
        if(!$order = M\EntrustOrder::findFirst(" order_sn = '{$order_sn}' AND buy_user_id = '{$uid}' AND subsidy_total <=0")) {
            $msg = array('error' => '订单错误');
            exit(json_encode($msg));
        }
       
        /* 处理补贴信息 
            10 个子订单
            补贴总额 / 订单数量
            中间判断
                总额 - 之前订单累计总额 = 最后一个订单使用比例 
        */
       
       if(!$num = M\EntrustOrderDetail::count(" order_parent_id = '{$order->order_id}' AND subsidy_amount <=0 ")) {
               $msg = array('error' => '订单信息错误') ;
               exit(json_encode($msg));
        }
        
        $subsidy_amount = L\Func::format_money($money / $num);
        $amount = (float)0;
        $last_amount = (float)0;
        for ($i=1; $i <=$num; $i++) { 
            if(intval($i) == intval($num)) {
                $last_amount = ($money - $amount);
                $amount+=$last_amount;
            }else{
                $amount+=$subsidy_amount;
            }
        }

        if(!$last_amount || !$amount) {
               $msg = array('error' => '金额错误') ;
               exit(json_encode($msg));
        }

        $this->db->begin();
        try {

            $sql = " SELECT  order_id FROM entrust_order where order_sn = '{$order_sn}' for update ";
            $this->db->fetchOne($sql,2);
            $maxid = M\EntrustOrderDetail::maximum( array(" order_parent_id = '{$order->order_id}' ", 'column' => 'order_detail_id'));

            $sql = "  UPDATE entrust_order_detail SET subsidy_amount = '{$subsidy_amount}' WHERE order_parent_id = '{$order->order_id}'";
            $this->db->execute($sql);
            if(!$this->db->affectedRows()) {
                throw new \Exception('补贴使用错误');
            }

            $sql = " UPDATE entrust_order_detail SET subsidy_amount = '{$last_amount}' WHERE order_parent_id = '{$order->order_id}' AND order_detail_id='{$maxid}' ";
            $this->db->execute($sql);
            // if(!$this->db->affectedRows()) {
            //     throw new \Exception('补贴使用错误');
            // }

            /* 修改主订单使用补贴 */
            $sql = " UPDATE entrust_order SET subsidy_total = '{$amount}'  WHERE order_sn = '{$order_sn}'";
            $this->db->execute($sql);
            if(!$this->db->affectedRows()) {
                throw new \Exception('补贴使用错误');
            }
            $this->db->commit();
            $msg = array('ok' => '1');
        } catch (\Exception $e) {
            $this->db->rollback();
            $flag = $e->getMessage();
            $msg = array('error' =>$flag);
            // var_dump($e->getLine());
        }
        exit(json_encode($msg));
        

    }

    
}
