<?php
namespace Lib;
use Lib\CashCoupon as CashCoupon;
class Pop
{
	static function wxPayID($order_sn='', $type='APP') {
		
		if(!$info = self::orderInfo($order_sn)) {
			return 11123;
		}
        // var_dump($info);die;
		if($info['order_status'] || $info['shipping_status'] || $info['pay_status']) {
			return 19995;
		}

		if($info['order_amount']>10000) {
			return 19994;
		}
		$wx = new Wxpay();
		// if($pay = self::payInfo($order_sn, 2)) {
		// 	return array(
		// 		'status'=>0,
		// 		'data'=>$wx->payInfo($pay['uposp_sn'])
		// 		);
		// }
        
		$data = array();
    	$data['order_sn'] = $info['order_sn'];
    	$data['body'] = "云农场:{$order_sn}订单支付";
    	$data['order_amount'] = $info['order_amount'];
    	$data['ip'] = Func::getIP();
    	
		$rs = $wx->payID($data, 'APP');
 
		if(!$rs['status']) {
		     self::syncOrderSn($info['order_id'], $order_sn, $rs['data']['prepayid']);
		}

		return $rs;
	}
    //微信支付订单
	static function payOrder($order_sn='', $wx_sn='', $total_fee=0) {
		$db = DB::getDB('ync365');
		try {
			$db->begin();
			$info = self::orderInfo($order_sn);
			if($info['order_amount']*100 != $total_fee) {
				throw new \Exception('订单金额有误!');	
			}

			if($info['order_status'] || $info['shipping_status'] || $info['pay_status'] || !self::editState($info['order_id'])) {
				throw new \Exception('订单状态异常!');	
			}
			$data = array();
			// $data['order_id'] = $info['order_id'];
			$data['state'] = 3;
			$data['desc'] = $data['demo'] = '微信支付订单';
			$data['admin_id'] = $info['user_id'];			
			$data['admin_name'] = $info['consignee'];
			$oids = self::getOrders($info['order_id']);
			foreach ($oids as $val) {
				$data['order_id'] = $val['order_id'];
				if(!self::insertLog($data)) {
					throw new \Exception('记录支付日志失败!');
				}
			}

			if(!self::syncWxSN($order_sn, $wx_sn)) {
				throw new \Exception('记录微信流水号失败!');
			}
			//更改商品库存
			self::savesalesnum($info['order_id']);
			//$coupon = CashCoupon::sendCoupon($info);
			$db->commit();
			$falg=true;
			//return true;
		} catch (\Exception $e) {
			$db->rollback();
			$falg=false;
			//return false;
		}
		if($falg){
			self::tongbu($info['order_id']);
		}
		return $falg;

	}

	static function getOrders($order_id=0) {
		return DB::getDB('ync365')->fetchAll("SELECT order_id from order_info where order_id='{$order_id}' or main_id='{$order_id}'", 2);
	}

	/**
	 * 记录微信流水号
	 * @param  string $order_sn 订单编号
	 * @param  string $wx_sn    微信流水号
	 * @return boolean
	 */
	static function syncWxSN($order_sn='', $wx_sn='') {
		return DB::getDB('ync365')->execute("UPDATE orders_uposp set transation_sn='{$wx_sn}' where order_sn='{$order_sn}' and type='2'");
	}

	/**
	 * 记录日志
	 * @param  array  $data 数据
	 * @return boolean
	 */
	static function insertLog($data=array()) {
		$data['createtime'] = CURTIME;
		// print_R($data);exit;
		$keys = implode("`,`", array_keys($data));
		$vals = implode("','", $data);
		return DB::getDB('ync365')->execute("INSERT INTO orderlog (`{$keys}`) values ('{$vals}')");
	}

	/**
	 * 修改订单状态
	 * @param  string  $order_sn 订单ID
	 * @param  integer $state    状态
	 * @return boolean
	 */
	static function editState($order_id=0) {
		$paytime = CURTIME;
		
		if(!DB::getDB('ync365')->execute("UPDATE order_info set pay_name='微信支付', order_status='1', shipping_status='0', pay_status='2', is_search='1', pay_time='{$paytime}',confirm_time='{$paytime}',pay_id='12' where order_id='{$order_id}' or main_id='{$order_id}'")) {
			return false;
		}
		if(DB::getDB('ync365')->fetchOne("SELECT order_id from order_info where main_id='{$order_id}'")) {
			return DB::getDB('ync365')->execute("UPDATE order_info set is_search='0' where order_id='{$order_id}'");
		}
		return true;
	}
	/**
	 *  更改商品销售量
	 * @param  [type] $order_id [description]
	 * @return [type]           [description]
	 */
    static function savesalesnum($order_id=0){
    	$order_goods=DB::getDB('ync365')->fetchAll("select goods_number,goods_id from order_goods where order_id={$order_id} ",2);
      
        if(!empty($order_goods)) {
        	foreach ($order_goods as $key => $value){
        		 $salesnum=$value["goods_number"];
        		 $goods_id=$value["goods_id"];
        		 if(!DB::getDB('ync365')->execute("UPDATE goods set  salesnum=salesnum+{$salesnum}  where goods_id='{$goods_id}'")){
        		 	return false;
        		 }
        	}
		}    
    }
	/**
	 * 获取支付信息
	 * @param  string  $order_sn 订单编号
	 * @param  integer $type     支付类型
	 * @return array            
	 */
	static function payInfo($order_sn='', $type=0) {
		return DB::getDB('ync365')->fetchOne("SELECT order_id, order_sn, uposp_sn from orders_uposp where order_sn='{$order_sn}' and type='2'", 2);
	}

	/**
	 * 同步支付信息
	 * @param  integer $oid      订单ID
	 * @param  string  $order_sn 订单编号
	 * @param  string  $pay_sn   支付编号
	 * @return array            
	 */
	static function syncOrderSn($oid=0, $order_sn='', $pay_sn='') {

		if(DB::getDB()->fetchOne("SELECT order_id from orders_uposp where order_id='{$oid}' and type='2'")) {
			$sql = "UPDATE orders_uposp SET uposp_sn='{$pay_sn}', add_time='".CURTIME."' where order_id='{$oid}' and type='2'";
		} else {
			$sql = 'INSERT INTO orders_uposp (`order_id`, `order_sn`, `uposp_sn`, `add_time`, `type`) values ("%s", "%s", "%s", "%s", "2")';
			$sql = sprintf($sql, $oid, $order_sn, $pay_sn, CURTIME);
		}
		return DB::getDB()->execute($sql);
	}

	/**
	 * 订单详情
	 * @param  string $order_sn 订单编号
	 * @return array           
	 */
	static function orderInfo($order_sn='') {
		$info = DB::getDB('ync365')->fetchOne("SELECT order_id, user_id, consignee, order_sn, order_status, shipping_status, pay_status, order_amount from order_info where order_sn='{$order_sn}'", 2);
		return $info;
	}

	/**
	 * 同步订单和佣金
	 * @return [type] [description]
	 */
	static function tongbu($order_id){


		    include_once "Hprose/HproseHttpClient.php";
            $ordersClient = new \HproseHttpClient(HPROSE_WEBSERVICE."/index.php");
           
            $apiClient = new \HproseHttpClient(HPROSE_API."/ynccomm");

            $apiClient->Ynccomm_save($order_id);
            $sql = 'SELECT * from %s where order_id="%s" or main_id="%s"';
            $sql = sprintf($sql, 'order_info', $order_id, $order_id);
            $orders = DB::getDB('ync365')->fetchAll($sql, 2);

            if (count($orders) == 1) 
            {
                
                foreach ($orders as $val) 
                {
                  
                    if (!$val['suppliers_id']) 
                    {
                        $ordersClient->insertOrder_getinsert($val['order_sn'], true, '自营');
                    }
                    else
                    {
                        $a = $ordersClient->insertOrder_getinsert($val['order_sn'], true, 'pop');
                    }
                }
            }
            else
            {
                
                foreach ($orders as $val) 
                {
                    
                    if ($val['main_id'] && !$val['suppliers_id']) 
                    {   
                        $ordersClient->insertOrder_getinsert($val['order_sn'], true, '自营');
                    }
                    else
                    {
                        $ordersClient->insertOrder_getinsert($val['order_sn'], true, 'pop');
                    }
                }
            }      
	}
	static function orderQuery($order_sn){
		$wx = new Wxpay();
        $rs = $wx->orderQuery($order_sn);
        if($rs=="SUCCESS"){
            $info = self::orderInfo($order_sn);
			if($info['pay_status']!=2) {
				self::editState($info['order_id']);
			}
        }
        return $rs;
	}
     //u刷支付订单
	static function payUpospOrder($order_sn='', $wx_sn='') {
        
      
		$db = DB::getDB('ync365');
		try {
			$db->begin();
			$info = self::orderInfo($order_sn);
            
			if($info['order_status'] || $info['shipping_status'] || $info['pay_status'] || !self::editState($info['order_id'])) {
				throw new \Exception('订单状态异常!');	
			}

			$data = array();
			// $data['order_id'] = $info['order_id'];
			$data['state'] = 3;
			$data['desc'] = $data['demo'] = 'u刷支付订单';
			$data['admin_id'] = $info['user_id'];			
			$data['admin_name'] = $info['consignee'];
            
			$oids = self::getOrders($info['order_id']);
            
			foreach ($oids as $val) {
				$data['order_id'] = $val['order_id'];
				if(!self::insertLog($data)) {
					throw new \Exception('记录支付日志失败!');
				}
			}
			if(!self::syncupospSN($order_sn, $wx_sn)) {
				throw new \Exception('记录微信流水号失败!');
			}
			//更改商品库存
			self::savesalesnum($info['order_id']);
			//$coupon = CashCoupon::sendCoupon($info);
			$db->commit();
			$falg=true;
			//return true;
		} catch (\Exception $e) {
			$db->rollback();
			$falg=false;
			//return false;
		}
		if($falg){
			self::tongbu($info['order_id']);
		}
		return $falg;

	}
	/**
	 * 记录u刷流水号
	 * @param  string $order_sn 订单编号
	 * @param  string $wx_sn    微信流水号
	 * @return boolean
	 */
	static function syncupospSN($order_sn='', $up_sn='') {
		return DB::getDB('ync365')->execute("UPDATE orders_uposp set transation_sn='{$up_sn}' where order_sn='{$order_sn}' and type='1'");
	}
}