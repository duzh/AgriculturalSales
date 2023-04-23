<?php
namespace Lib;

class SubsidyPay {
	// 数据库连接对象
	private $db = null;
	// 归属用户ID
	private $sell_id = 0;

	private $connect = '';
	// 初始化
	public function __construct($sell_id=0, $connect='db') {
		$this->sell_id = $sell_id;
		if(!isset($GLOBALS['di'][$connect])) {
			throw new \Exception('connect error');
		}
		$this->connect = $connect;
		$this->db = $GLOBALS['di'][$connect];
	}
	/**
	 * 使用补贴
	 * @param  integer $order_id 订单ID
	 * @param  string  $order_sn 订单编号
	 * @param  integer $pay_way  使用渠道：0 丰收汇 1农资汇
	 * @param  string  $username 用户名
	 * @param  string  $mobile   手机
	 * @param  integer $amount   使用金额
	 * @return boolean           
	 */
	public function subsidyUse($order_id=0, $order_sn='', $pay_way=0, $username='', $mobile='', $amount=0) {
		$data = array();
		$data['order_id'] = $order_id;
		$data['order_no'] = $order_sn;
		$data['pay_way'] = $pay_way;
		$data['pay_amount'] = $amount;
		$data['user_id'] = $this->sell_id;
		$data['user_name'] = $username;
		$data['user_phone'] = $mobile;
		$data['pay_time'] = $data['add_time'] = $data['last_update_time'] = time();

		$use = new UserSubsidy($this->sell_id, $this->connect);

		$flag = false;
		try{
			$this->db->begin();
			$this->insert($data);
			$use->decSubsidy($amount, "订单【{$order_sn}】消费支出", $pay_way);
			$this->db->commit();
			$flag = true;
		} catch(\Exception $e) {
			$this->db->rollback();
		}
		return $flag;
	}

	// 插入用户使用补贴信息
	private function insert($data=array()) {
		$field = implode('`, `', array_keys($data));
		$val = implode("', '", $data);
		$sql = "INSERT INTO subsidy_pay (`{$field}`) values ('{$val}')";
		if(!$this->db->execute($sql)) {
			throw new \Exception('insert user subsidy error');
		}
	}
}