<?php
namespace Mdg\Member\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Mdg\Models\Purchase as Purchase;
use Mdg\Models\PurchaseQuotation as Quotation;
use Mdg\Models\Orders as Orders;
use Mdg\Models\OrdersLog as OrdersLog;
use Mdg\Models\Sell as Sell;
use Mdg\Models\Users as Users;
use Mdg\Models\OrderEngineer as OrderEngineer;
use Lib\Func as Func;
use Lib\Pages as Pages;
use Mdg\Models\MessageUsers as MessageUsers;
use Mdg\Models\Message as Message;
use Mdg\Models\OrdersDelivery as OrdersDelivery;
use Mdg\Models as M;
use Lib as L;
class OrderssellController extends ControllerMember
{

    public function indexAction() { 
        $page = $this->request->get('p', 'int', 1);
        $stime = L\Validator::replace_specialChar($this->request->get('stime', 'string', ''));
        $etime = L\Validator::replace_specialChar($this->request->get('etime', 'string', ''));
        $state = $this->request->get('state', 'int', 0);
        $total = $this->request->get('total', 'int', 0);
        $page = intval($page)>0 ? intval($page) : 1;
        if($total&&$page>$total){
            $page=$total;
        }
        $user_id = $this->getUserID();
        $user_id = intval($user_id) ? intval($user_id) : 11;
        $params = array("suserid = '{$user_id}'");

        if($stime&&$etime){
            $params[] .= "addtime >= '".strtotime($stime."00:00:00")."'";
            $params[] .= "addtime <= '".strtotime($etime."23:59:59")."'";
        }
        if($state) {
            $params[] = "state = '{$state}'";
        }
        $params = array(implode(' and ', $params));
        $params['order'] = ' addtime desc ';
        
        $quotation = Orders::find($params);
        $total = Orders::count($params);
       
        $paginator = new Paginator(array(
            "data" => $quotation,
            "limit"=> 10,
            "page" => $page
        ));

        $data = $paginator->getPaginate();

        $pages = new Pages($data);
        $pages = $pages->show(2);
        $this->view->total_count = ceil($total / 10);
        $this->view->data = $data;
        $this->view->pages = $pages;
        $this->view->p     = $page; 
        $this->view->stime = $stime;
        $this->view->etime = $etime;
        $this->view->state = $state;
        $this->view->orders_state = Orders::$_orders_sell_state;
        $this->view->goods_unit = Purchase::$_goods_unit;
    
        $this->view->title = '我供应的订单-用户中心-丰收汇';
        $this->view->keywords = '我供应的订单-用户中心-丰收汇';
        $this->view->descript = '我供应的订单-用户中心-丰收汇';
    }

     public function infoAction($oid=0) {
        $order = Orders::findFirstByid($oid);
       
        $address='';
        if(!$order) {
            echo "<script>alert('订单不存在');location.href='/member/orderssell/index'</script>";die;
        }
        $user_id = $this->getUserID();
        
        if($user_id != $order->suserid ) {
            echo "<script>alert('订单不存在');location.href='/member/orderssell/index'</script>";die;
            //die('请正确操作取消订单！');
        }
        
        if($order->sellid>0){
            $sell = Sell::findFirstByid($order->sellid);
            $address=$sell->address;
            $spec =$sell->spec;
        }

        if($order->purid>0){
            $purchase=Purchase::findFirstByid($order->purid);
            $qutaion=Quotation::findFirstBypurid($order->purid);
            $address=$purchase  ? $purchase->address : '' ;
            $spec =$qutaion ? $qutaion->spec :'';
        }
        if($order->state==2){
            $order->total=M\Sell::getordertotal($order->sellid,$order->quantity);  
        }
        $message = Message::findFirstByorder_id($oid);
        if($message){
            $messageusers=MessageUsers::findFirstBymsg_id($message->msg_id);
            if($messageusers){
                $messageusers->is_new = 1;
                $messageusers->save();
            }
        }
        $OrderEngineer = OrderEngineer::findFirst("order_id='{$oid}'");

        if ($OrderEngineer){
            $Engineer=$OrderEngineer->toArray();
            $Engineer_phone =$Engineer['engineer_phone'] ;       
        }
//        if($order->commission_party == 1) #如果佣金支付是采购方进行累加值
//            $order->total +=$order->commission;

        $this->view->Engineer_phone = $Engineer_phone;       
        $this->view->spec   = $spec;
        $this->view->address=$address;
        $this->view->order = $order;
        $this->view->order_state = Orders::$_orders_sell_state;
        $this->view->goods_unit = Purchase::$_goods_unit;
        $this->view->goods_desc = $goods_desc;

        $this->view->title = '我供应的订单详情-用户中心-丰收汇';
        $this->view->keywords = '我供应的订单详情-用户中心-丰收汇';
        $this->view->descript = '我供应的订单详情-用户中心-丰收汇';
    }


    public function cancelAction() {
        $rs = array('state'=>false, 'msg'=>'取消成功！');
        $oid = $this->request->get('oid', 'int', 1114);
        $order = Orders::findFirstByid($oid);
        if(!$order) {
            $rs['msg'] = '此订单不存在！';
            die(json_encode($rs));
        }

        $user_id = $this->getUserID();
        
        if($user_id != $order->suserid || $order->state >4) {
            $rs['msg'] = '请正确操作取消订单！';
            die(json_encode($rs));
        }

        OrdersLog::insertLog($oid, 1, $user_id, $this->getUserName(), 0, $demo='供应商取消订单');
        $order->state = 1;
        $order->updatetime=time();
        if(!$order->save()) {
            $rs['msg'] = '取消订单失败，请联系客服！';
            die(json_encode($rs));
        }
        // $subsidy=M\Subsidy::findFirst("order_id={$order->id}");
        // if(!$subsidy){
        //     $subsidytotal=M\SubsidyPay::findFirst("order_id={$order->id}");
        //     if($subsidytotal){
        //          $SubsidySend=new l\SubsidySend($order->suserid);
        //          $subsidysend=$SubsidySend->sendByCancel($order->id,$order->order_sn,$order->sname,$order->sphone,$subsidytotal->pay_amount);
        //          if(!$subsidysend){
        //               $str=$order->id.','.$order->id.','.$subsidytotal.','.date("Y-m-d",time());
        //               file_put_contents(PUBLIC_PATH.'log/sendBycancel.txt',$str."\n", FILE_APPEND);
        //          }
        //     }
        // }
        $rs['state'] = true;
        die(json_encode($rs));
    }

    public function confirmAction() {
        $rs = array('state'=>false, 'msg'=>'确认成功！');
        $oid = $this->request->get('oid', 'int', 0);
        $order = Orders::findFirstByid($oid);
        if(!$order) {
            $rs['msg'] = '此订单不存在！';
            die(json_encode($rs));
        }

        $user_id = $this->getUserID();
        if($user_id != $order->suserid || $order->state != 2) {
            $rs['msg'] = '请正确操作确认订单！';
            die(json_encode($rs));
        }
        OrdersLog::insertLog($oid, 3, $user_id, $this->getUserName(), 0, $demo='供应商确认订单');
        $order->state = 3;
        $order->updatetime=time();
        if(!$order->save()) {
            $rs['msg'] = '确认订单失败，请联系客服！';
            die(json_encode($rs));
        }

        $rs['state'] = true;
        die(json_encode($rs));
        
    }
     public function sendAction(){
        
        $oid =  $this->request->get('oid','int',0);
        $order = Orders::findFirstByid($oid);
     
        $fahuo = L\Validator::replace_specialChar($this->request->get('fahuo', 'string', ''));
        $wuliu_sn = L\Validator::replace_specialChar($this->request->get('wuliu_sn', 'string', ''));
        $wuliu_gongsi = L\Validator::replace_specialChar($this->request->get('wuliu_gongsi', 'string', ''));
        $driver_name = L\Validator::replace_specialChar($this->request->get('driver_name', 'string', ''));
        $driver_phone = L\Validator::replace_specialChar($this->request->get('driver_phone', 'string', ''));
        $name = L\Validator::replace_specialChar($this->request->get('name', 'string', ''));
        $mobile = L\Validator::replace_specialChar($this->request->get('mobile', 'string', ''));
        
        if(!$order) {
            die('此订单不存在！');
        }
        $user_id = $this->getUserID();
        if($user_id != $order->suserid || $order->state != 4) {
            die("请正确操作未发货订单！");
        }
        OrdersLog::insertLog($oid, 5, $user_id, $this->getUserName(), 0, $demo='订单发货');
        $order->state = 5;
        $order->updatetime=time();
        if(!$order->save()) {
            die("确认订单失败，请联系客服！");
        }
        $OrdersDelivery = new OrdersDelivery();
        $OrdersDelivery->deliverytype=$fahuo;
        if($fahuo==1){
            $OrdersDelivery->deliveryname=$wuliu_gongsi;
        }else{
            $OrdersDelivery->deliveryname=OrdersDelivery::$dev_name[$fahuo];
        }
        $OrdersDelivery->drivername=$driver_name;
        $OrdersDelivery->driverphone=$driver_phone;
        $OrdersDelivery->name=$name;
        $OrdersDelivery->mobile=$mobile;
        $OrdersDelivery->orderid=$oid;
        $OrdersDelivery->delivery_sn=$wuliu_sn;
        
        if($OrdersDelivery->save()){
           $this->flash->error("<span style='text-align:center; display:block; font-size:16px; margin-top:90px;'>发货成功！</span>");
           die("<script>parent.location.href='/member/orderssell/index/'</script>");
        }
  
    }
    /**
     * 我卖出的订单
     * @return [type] [description]
     */
        /**
     * 我卖出的订单
     * @return [type] [description]
     */
    public function sellListAction () {
        $stime       = $this->request->get('stime', 'string','');
        $etime       = $this->request->get('etime', 'string','');
        $state       = $this->request->get('state', 'int', 0);
        $page        = $this->request->get('p' , 'int',  1);
        $total       = $this->request->get('total' , 'int',  0);
        $page = $page > 0 ? $page : 1 ;
        if($total&&$page>$total){
          $page=$total;
        }
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
        $this->view->p = $page;
        $this->view->p = $page;
        $this->view->etime = $etime;
        $this->view->title = '个人中心-委托交易-我卖出的订单';
    }
    

}
