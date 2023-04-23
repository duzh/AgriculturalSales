<?php
namespace Mdg\Api\Controllers;

use Lib as L,
    Mdg\Models as M;

class UpospController extends ControllerBase
{
    /**
     * 获取U刷订单号
     * @return string
     * /api/uposp/upospsn
     * <code><pre>
     * post 
     * order_sn    string  订单编号
     * return
     * {
     *     "errorCode": 0, // 错误码
     *     "data": {
     *         "uposp_sn": "5128152807610242"  // U刷订单号
     *     }
     * } 
     * </pre></code>
     */
    public function upospsnAction() {
        $postdata = $this->request->getPost('data');
        
        if($postdata){
             $postdata=json_decode($postdata,2);
             if(!empty($postdata)){
                foreach ($postdata as $key => $value) {
                    $_POST[$key]=$value;
                }
             }
        }
        $order_sn = $this->request->getPost('order_sn', 'string', 'PF101554647865501');
        //$order_sn = $this->request->getPost('order_sn', 'string', '052544738860586');
        $ordersn=substr($order_sn,0,3);
        switch ($ordersn) {
            case 'mdg':
                $this->insertmdgAction($order_sn);
                break;
            default:
          		$ordersn=substr($order_sn,0,2);
          		if ($ordersn == 'PF'){
          			$this->insetpfAction($order_sn);
          			break;
          		}
              $this->insertyncAction($order_sn);
              break;
        }
    }
    public function insertmdgAction($order_sn) {
        $order = M\Orders::findFirstByorder_sn($order_sn);
        // var_dump($order);die;
        if(!$order || 3 != $order->state) $this->getJson(self::ORDER_ERROR);
        $curtime = strtotime(date('Y-m-d'));


        $upinfo = M\OrdersUposp::findFirst("order_sn='{$order_sn}'  and type=1  and add_time='{$curtime}'");
        if($upinfo) {
            // $upinfo->count += 1;
            5 >= $upinfo->count ? $this->getJson(self::SUCCESS, array('uposp_sn'=>$upinfo->uposp_sn)) : $this->getJson(self::ORDER_PAY_CANCEL_MAX);
        }
        // 保存U刷订单号
        M\OrdersUposp::find("order_sn='{$order_sn}'")->delete();
        $upinfo = new M\OrdersUposp();
        $upinfo->order_id = $order->id;
        $upinfo->order_sn = $order->order_sn;
        $upinfo->add_time = $curtime;
        $upinfo->type = 1;
        switch($order->commission_party){
            case 1: #供应方支付
                $order->total = $order->total;
                break;
            case 2: #采购方支付
               $order->total = $order->total+$order->commission;
                break;
            default:
                $order->total = $order->total;
                break;
        }
        $uposp_sn = L\Uposp::getSn($order->order_sn,$order->total,$curtime);
        if(!$uposp_sn) $this->getJson(self::GET_UPOSP_SN_ERROR);
        $upinfo->uposp_sn = $uposp_sn;
        $upinfo->save();
        $this->getJson(self::SUCCESS, array('uposp_sn'=>$upinfo->uposp_sn));
    }
    
    public function insertyncAction($order_sn) {
     
        $order = L\DB::getDB('ync365')->fetchOne("SELECT pay_status,order_amount,order_id from order_info where order_sn='{$order_sn}' ", 2);
        if(!$order || 0 != $order['pay_status']) $this->getJson(self::ORDER_ERROR);
        $curtime = strtotime(date('Y-m-d'));
        $upinfo = L\DB::getDB('ync365')->fetchOne("SELECT order_id,order_sn, uposp_sn from orders_uposp where order_sn='{$order_sn}' and add_time='{$curtime}'  and type='1'", 2);
    
        if($upinfo) {
           $this->getJson(self::SUCCESS, array('uposp_sn'=>$upinfo['uposp_sn']));die;
        }
        // 保存U刷订单号
        $deletesql="delete from orders_uposp where order_sn='{$order_sn}' ";
        $delupinfo=L\DB::getDB('ync365')->execute($deletesql);
        $uposp_sn = L\Uposp::getSn($order_sn, $order['order_amount'], $curtime);
        if(!$uposp_sn) $this->getJson(self::GET_UPOSP_SN_ERROR);
        $insertsql = " INSERT INTO orders_uposp (order_id,order_sn,uposp_sn,add_time,type) values ({$order['order_id']},'{$order_sn}','{$uposp_sn}','{$curtime}',1) ";
        $upinfos=L\DB::getDB('ync365')->execute($insertsql);
        if($upinfos){
            $this->getJson(self::SUCCESS, array('uposp_sn'=>$uposp_sn));
        }else{
            $this->getJson(self::GET_UPOSP_SN_ERROR);
        }
    }

    public function insetpfAction($order_sn){
        //echo $order_sn;die;
		//order_state = 1
		$order = L\DB::getDB('station')->fetchOne("SELECT order_id,order_sn, order_state,order_amount from orders where order_sn='{$order_sn}' ", 2);
  
		if(!$order || 1 != $order['order_state']) $this->getJson(self::ORDER_ERROR);
		
		$curtime = strtotime(date('Y-m-d'));
		$upinfo = L\DB::getDB('ync365')->fetchOne("SELECT order_id,order_sn, uposp_sn from orders_uposp where order_sn='{$order_sn}' and add_time='{$curtime}'  and type='1'", 2);
        
		if($upinfo) {
		   $this->getJson(self::SUCCESS, array('uposp_sn'=>$upinfo['uposp_sn']));die;
		}

		$uposp_sn = L\Uposp::getSn($order['order_sn'],$order['order_amount'],$curtime);
        
		if(!$uposp_sn) $this->getJson(self::GET_UPOSP_SN_ERROR);
        $insertsql = " INSERT INTO orders_uposp (order_id,order_sn,uposp_sn,add_time,type) values ({$order['order_id']},'{$order_sn}','{$uposp_sn}','{$curtime}',1) ";
        $upinfos=L\DB::getDB('ync365')->execute($insertsql);
        if($upinfos){

            $this->getJson(self::SUCCESS, array('uposp_sn'=>$uposp_sn));
        }else{
            $this->getJson(self::GET_UPOSP_SN_ERROR);
        }
	}
    /**
     * U刷回调
     */
    public function callbackAction() {
    
        $xml = file_get_contents('php://input', 'r');
        file_put_contents(__DIR__ .'/xml.txt',$xml."\n", FILE_APPEND);
        // $xml = $this->xml();
        if(!$xml || !($xml = simplexml_load_string($xml)) || '00' != $xml->F39->attributes()->v)  die("<map><F39 v='00'/></map>");
        list($order_sn, $transation_sn) = explode(',', $xml->F45->attributes()->v);
        $rec = $xml->F4->attributes()->v.L\Uposp::$merid.$order_sn;
        if(!L\Uposp::verify($rec, $xml->MerSign->attributes()->v)) die("<map><F39 v='00'/></map>");
        
        $ordersn = preg_replace('/\d+/', '', $order_sn);
        
        switch ($ordersn) {
            case 'mdg':
                $this->insertmdgorder($order_sn,$transation_sn);
                break;
            case 'PF':
                 $result = 0;
                 $data['order_sn'] = $order_sn;
                 $data['pay_type'] = 'U';
                 $c = new L\Curl();
                 $url = MSG_URL.'/station/pay/callback';
                 //$url="http://shopspro.ync365.com/station/pay/callback";
                 $json = $c->post($url, $data);
                 $json  = json_encode($json,2);
                 die("<map><F39 v='0000'/></map>");
                 break;
            default:
                $this->insertyncorder($order_sn,$transation_sn);
                break;
        }
       
    }
     /**
     * U刷回调
     */
    public function callback1Action() {
    
        // $xml = file_get_contents('php://input', 'r');
        // file_put_contents(__DIR__ .'/xml.txt',$xml."\n", FILE_APPEND);
        // // $xml = $this->xml();
        // if(!$xml || !($xml = simplexml_load_string($xml)) || '00' != $xml->F39->attributes()->v)  die("<map><F39 v='00'/></map>");
        // list($order_sn, $transation_sn) = explode(',', $xml->F45->attributes()->v);
        // $rec = $xml->F4->attributes()->v.L\Uposp::$merid.$order_sn;
        // if(!L\Uposp::verify($rec, $xml->MerSign->attributes()->v)) die("<map><F39 v='00'/></map>");
        $order_sn="PF101554647865501";
        $ordersn = preg_replace('/\d+/', '','PF101554647865501');
        
        switch ($ordersn) {
            case 'mdg':
                $this->insertmdgorder($order_sn,$transation_sn);
                break;
            case 'PF':
                 $result = 0;
                 $data['order_sn'] = $order_sn;
                 $data['pay_type'] = 'U';
                 $c = new L\Curl();
                 $url = MSG_URL.'/station/pay/callback';
                 //$url="http://shopspro.ync365.com/station/pay/callback";
                 $json = $c->post($url, $data);
                 $json  = json_encode($json,2);
                 die("<map><F39 v='0000'/></map>");
                 break;
            default:
                $this->insertyncorder($order_sn,$transation_sn);
                break;
        }
       
    }
    /**
     * 核对u刷订单号
     * @return string
     * http://mdgdev.ync365.com/api/uposp/checkorder
     * <code><pre>
     * post 
     * order_sn    string  订单编号
     * return
     * {
     *     "errorCode": 0
     * } 
     * </pre></code>
     */
    public function checkorderAction(){
        
        $postdata = $this->request->getPost('data');
        if($postdata){
             $postdata=json_decode($postdata,2);
             if(!empty($postdata)){
                foreach ($postdata as $key => $value) {
                    $_POST[$key]=$value;
                }
             }
        }
        $order_sn = $this->request->getPost('order_sn', 'string', 'PF101232738701142');
        if(!$order_sn){
           $this->getJson(self::ORDER_NOT_PAY);
        }
        // $ordersn=substr($order_sn,0,3);
        $ordersn = preg_replace('/\d+/', '', $order_sn);
        switch ($ordersn) {
            case 'mdg':
                $this->checkmdgorder($order_sn);
                break;
            case 'PF':
                $this->checkpforder($order_sn);
                break;
            default:
                $this->checkyncorder($order_sn);
                break;
        }
    }
    private function checkpforder($order_sn='0'){
       
        if(!$order_sn){
            $this->getstatus(self::ORDER_PAY_ERROR);
        }

        $order = L\DB::getDB('station')->fetchOne("SELECT order_id,order_sn, order_state,order_amount from orders where order_sn='{$order_sn}' ", 2);
      
        if(!$order) $this->getstatus(self::ORDER_NOT_ERROR);

        if($order&&$order['order_state']==2) $this->getJson(self::ORDER_PAY);
 
        $upinfo = L\DB::getDB('ync365')->fetchOne("SELECT add_time  from orders_uposp where order_sn='{$order_sn}' and type=1 ", 2);
       
        if(!$upinfo) {
            $this->getstatus(self::ORDER_NOT_ERROR);
        }
        
        $uposp_sn = L\Uposp::checkorder($order_sn,$upinfo["add_time"]);
       
        if($uposp_sn=='00'){
          $updateorder = L\DB::getDB('station')->execute(" update orders set order_state=2 where  order_sn='{$order_sn}'");
          if(!$updateorder){
              $this->getstatus(self::ORDER_ERROR);
          }
        }
       
        $this->getstatus($uposp_sn);
    }
    private function checkyncorder($order_sn='0'){
        
        if(!$order_sn){
            $this->getstatus(self::ORDER_PAY_ERROR);
        }

        $order = L\DB::getDB('ync365')->fetchOne("SELECT order_status,pay_status,shipping_status  from order_info where order_sn='{$order_sn}'", 2);
       
        if(!$order) $this->getstatus(self::ORDER_NOT_ERROR);
         
        if($order["order_status"]==1&&$order["pay_status"]==2&&$order["shipping_status"]==0){
            $this->getstatus(self::ORDER_PAY);
        }
        $upinfo = L\DB::getDB('ync365')->fetchOne("SELECT add_time  from orders_uposp where order_sn='{$order_sn}' and type=1 ", 2);
        
        if(!$upinfo) {
            $this->getstatus(self::ORDER_NOT_ERROR);
        }
        $uposp_sn = L\Uposp::checkorder($order_sn,$upinfo["add_time"]);
        
        if($uposp_sn=='00'){
          $updateorder = L\DB::getDB('ync365')->execute(" update order_info set order_status=1,pay_status=2,shipping_status=0 where  order_sn='{$order_sn}'");
          if(!$updateorder){
              $this->getstatus(self::ORDER_ERROR);
          }
        }
       
        $this->getstatus($uposp_sn);
    }
    private function checkmdgorder($order_sn){
        
        $order = M\Orders::findFirstByorder_sn($order_sn);
        if(!$order) $this->getJson(self::ORDER_NOT_ERROR);
        if($order->state==4){
            $this->getJson(self::ORDER_PAY);
        } 
        $upinfo = M\OrdersUposp::findFirst(" order_sn='{$order_sn}'  and type=1 ");
        if(!$upinfo) {
            $this->getJson(self::ORDER_NOT_ERROR);
        }
        $uposp_sn = L\Uposp::checkorder($order_sn,$upinfo->add_time);
        if($uposp_sn=='00'){
          $order->state=4;
          if(!$order->save()){
              $this->getJson(self::ORDER_ERROR);
          }
        }
        $this->getJson($uposp_sn);
    }
    private function insertmdgorder($order_sn,$transation_sn){
          
        $db = $this->db;
        $db->begin();
        try{
            // 取出订单列表
            $order = M\Orders::findFirstByorder_sn($order_sn);
            if(!$order) throw new \Exception('无效订单');
            // 修改订单状态
            $order->state = 4;
            $order->pay_type=5;
            $order->updatetime=CURTIME;
            $order->pay_time=CURTIME;
            if(!$order->save()) throw new \Exception('修改订单状态失败');
            // 记录日志
            $olog = new M\OrdersLog();
            $olog->state = 4;
            $olog->operationid = $order->puserid;
            $olog->operationname = $order->purname;
            $olog->type = 0;
            $olog->addtime = CURTIME;
            $olog->demo = 'U刷支付';
            $olog->order_id = $order->id;
            if(!$olog->save())  throw new \Exception('记录日志失败');
            // 记录U刷流水号
            $cond = array("order_sn='{$order_sn}'");
            $cond['order'] = 'add_time desc';
            $uposp = M\OrdersUposp::findFirst($cond);
            if(!$uposp) throw new \Exception('记录U刷流水号失败');
            $uposp->transation_sn = $transation_sn;
            if(!$uposp->save()) throw new \Exception('记录U刷流水号失败');
            $db->commit();
        }catch(\Exception $e) {
            $db->rollback();
        }
        die("<map><F39 v='0000'/></map>");
    }
    /**
     *  改变主商城订单状态
     * @param  [type] $order_sn      [订单号]
     * @param  [type] $transation_sn [交易流水号]
     * @return [type]                [description]
     */
    public function insertyncorder($order_sn=0,$transation_sn=0){
        $order_info = L\DB::getDB('ync365')->fetchOne("SELECT count(*) from order_info where order_sn='{$order_sn}' order by add_time desc ", 2);
        if(!$order_info){
            $this->getJson(self::SAVE_UPOSP_SN_ERROR);
        }  
        $data=L\Pop::payUpospOrder($order_sn,$transation_sn);
        if(!$data){
            $this->getJson(self::ORDER_STATE_ERROR);  
        }
        die("<map><F39 v='0000'/></map>");
    }
    public function xml() {
        return "<?xml version='1.0' encoding='GB2312' ?> <map>
                <F0 v='0200'/>
                <F2 v='7139'/>
                <F3 v='000000'/>
                <F4 v='1'/>
                <F8 v='0,0'/>
                <F12 v='141456'/>
                <F13 v='0507'/>
                <F39 v='00'/>
                <F41 v='122010000001'/>
                <F42 v='9996'/>
                <F45 v='015127141456787941,5127141456787941'/>
                <F46 v='M20150108155652'/>
                <VoidAmt v='1'/>
                <MerSign v='606fecb1ab1d47655db4bbb035f503cba13ee2debf1c0bd73b973f4d354c78e976965a737be1c590fe161eec77cf79dfebcddc4dc91f8bf925459bfe043a2bb231b47405144fb866c77a5b1cca1e8aa1595bfc7bc59482fab4e365d2c2a436c0c74b0a7b61f236f290a0abd0a7159bb8584505f734851ddbdca8c516567541f4'/>
                </map>";
    }
}