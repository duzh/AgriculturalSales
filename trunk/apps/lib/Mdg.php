<?php
namespace Lib;
class Mdg
{
	static function wxPayID($order_sn='', $type='APP') {
		if(!$info = self::orderInfo($order_sn)) {
			return  11123;
		}
		if(3 != $info['state']) {
			return 19995;
		}
		if($info['total']>10000) {
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
    	$data['body'] = "丰收汇:{$order_sn}订单支付";
    	$data['order_amount'] = $info['total'];
    	$data['ip'] = Func::getIP();
    	// $data['ip'] = '58.132.175.127';
		$rs = $wx->payID($data, 'APP');
		if(!$rs['status']) {
			self::syncOrderSn($info['id'], $order_sn, $rs['data']['prepayid']);
		}
		return $rs;
	}
	/**
	 * 支付接口
	 * @param  string  $order_sn  订单编号
	 * @param  string  $wx_sn     微信流水号
	 * @param  integer $total_fee 金额
	 * @return boolean             
	 */
	static function payOrder($order_sn='', $wx_sn='', $total_fee=0) {
		$db = DB::getDB();
		try {
			$db->begin();
			$info = self::orderInfo($order_sn);
			if($info['total']*100 != $total_fee) {
				throw new \Exception('订单金额有误!');	
			}
			if(!self::editState($order_sn, 4)) {
				throw new \Exception('订单状态异常!');	
			}
			$data = array();
			$data['state'] = 4;
			$data['operationid'] = $info['puserid'];			
			$data['operationname'] = $info['purname'];			
			$data['type'] = 0;			
			$data['demo'] = '微信支付订单';			
			$data['order_id'] = $info['id'];
			if(!self::insertLog($data)) {
				throw new \Exception('记录支付日志失败!');
			}

			if(!self::syncWxSN($order_sn, $wx_sn)) {
				throw new \Exception('记录微信流水号失败!');
			}
// echo 'aaaa';exit;
			$db->commit();
			return true;
		} catch (\Exception $e) {
			$db->rollback();
			return false;
		}

	}

	/**
	 * 记录微信流水号
	 * @param  string $order_sn 订单编号
	 * @param  string $wx_sn    微信流水号
	 * @return boolean
	 */
	static function syncWxSN($order_sn='', $wx_sn='') {
		return DB::getDB()->execute("UPDATE orders_uposp set transation_sn='{$wx_sn}' where order_sn='{$order_sn}' and type='2'");
	}

	/**
	 * 记录日志
	 * @param  array  $data 数据
	 * @return boolean
	 */
	static function insertLog($data=array()) {
		$data['addtime'] = CURTIME;
		$keys = implode("`,`", array_keys($data));
		$vals = implode("','", $data);
		return DB::getDB()->execute("INSERT INTO orders_log (`{$keys}`) values ('{$vals}')");
	}

	/**
	 * 修改订单状态
	 * @param  string  $order_sn 订单编号
	 * @param  integer $state    状态
	 * @return boolean
	 */
	static function editState($order_sn='', $state=0) {
		$time=CURTIME;
		return DB::getDB()->execute("UPDATE orders set state='{$state}',pay_time='{$time}',last_update_time='{$time}',pay_type='3' where order_sn='{$order_sn}'");
	}

	/**
	 * 获取支付信息
	 * @param  string  $order_sn 订单编号
	 * @param  integer $type     支付类型
	 * @return array            
	 */
	static function payInfo($order_sn='', $type=0) {
		return DB::getDB()->fetchOne("SELECT order_id, order_sn, uposp_sn from orders_uposp where order_sn='{$order_sn}' and type='2'", 2);
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
		// echo $sql;exit;
		return DB::getDB()->execute($sql);
	}

	/**
	 * 订单详情
	 * @param  string $order_sn 订单编号
	 * @return array           
	 */
	static function orderInfo($order_sn='') {
		$info = DB::getDB()->fetchOne("SELECT id, order_sn, puserid, purname, state, total from orders where order_sn='{$order_sn}'", 2);
		return $info;
	}
	static function orderQuery($order_sn){
		$wx = new Wxpay();
        $rs = $wx->orderQuery($order_sn);
        if($rs=="SUCCESS"){
            $info = self::orderInfo($order_sn);
            self::editState($order_sn,4);
        }
        return $rs;
	}
}