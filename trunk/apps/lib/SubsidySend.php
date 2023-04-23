<?php
namespace Lib;

class SubsidySend {
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
	 * 审核补贴信息
	 * @param  integer $subsidy_id 补贴ID
	 * @param  integer $state      审核状态 1 通过 2 不通过
	 * @return boolean             
	 */
	public function checkSend($subsidy_id=0, $state=0) {
		$flag = false;
		try{
			$this->db->begin();
			$this->editState($subsidy_id, $state);
			$this->db->commit();
			$flag = true;
		} catch(\Exception $e) {
			$this->db->rollback();
		
		}
		return $flag;
	}


	/**
	 * 审核可信农场发放补贴
	 * @param  string  $username 用户名
	 * @param  string  $mobile   手机
	 * @param  integer $amount   补贴
	 * @return boolean
	 */
	public function sendByFarm($username='', $mobile='', $amount=0) {
		$data = array();
		$data['subsidy_amount'] = $amount;
		$data['subsidy_type'] = 1;
		$data['user_id'] = $this->sell_id;
		$data['user_name'] = $username;
		$data['user_phone'] = $mobile;
		$data['status'] = 0;
		$data['add_time'] = $data['last_update_time'] = time();
		try{
			$this->db->begin();
			$this->insert($data);
			$this->db->commit();
			$flag = true;
		} catch(\Exception $e) {
			$this->db->rollback();
			$flag = false;
		}
		return $flag;
	}
	/**
	 * 订单支付发放补贴
	 * @param  integer $order_id 订单号
	 * @param  string  $order_sn 订单编号
	 * @param  string  $username 用户名
	 * @param  string  $mobile   手机
	 * @param  integer $amount   补贴
	 * @return boolean           
	 */
	public function sendByOrder($order_id=0, $order_sn='', $username='', $mobile='', $amount=0) {
		$data = array();
		$data['order_id'] = $order_id;
		$data['order_no'] = $order_sn;
		$data['subsidy_amount'] = $amount;
		$data['subsidy_type'] = 0;
		$data['user_id'] = $this->sell_id;
		$data['user_name'] = $username;
		$data['user_phone'] = $mobile;
		$data['status'] = 0;
		$data['add_time'] = $data['last_update_time'] = time();
		
		try{
			$this->db->begin();
			$this->insert($data);

			$this->db->commit();
			$flag = true;
		} catch(\Exception $e) {
			$this->db->rollback();
			
			$flag = false;
		}
		return $flag;
	}
	/**
	 * 订单取消返回使用的补贴
	 * @param  integer $order_id 订单号
	 * @param  string  $order_sn 订单编号
	 * @param  string  $username 用户名
	 * @param  string  $mobile   手机
	 * @param  integer $amount   补贴
	 * @return boolean           
	 */
	public function sendByCancel($order_id=0, $order_sn='', $username='', $mobile='', $amount=0) {

		// $data = array();
		// $data['order_id'] = $order_id;
		// $data['order_no'] = $order_sn;
		// $data['subsidy_amount'] = $amount;
		// $data['subsidy_type'] = 2;
		// $data['user_id'] = $this->sell_id;
		// $data['user_name'] = $username;
		// $data['user_phone'] = $mobile;
		// $data['status'] = 0;
		// $data['add_time'] = $data['last_update_time'] = time();
		
		try{
			$this->db->begin();
			$UserSubsidy = new UserSubsidy($this->sell_id, $this->connect);
			$demo = "订单【{$order_sn}】交易关闭, 退款所得";
			$UserSubsidy->addSubsidy($amount, $demo, 0);
			$this->db->commit();
			$flag = true;
		} catch(\Exception $e) {
			$this->db->rollback();
			$flag = false;
		}
		return $flag;

	}
	/**
	 * 后台添加审核
	 * @param  string  $username 用户名
	 * @param  string  $mobile   手机
	 * @param  integer $amount   补贴
	 * @return boolean
	 */
	public function sendByAdmin($username='',$user_id=0,$mobile='', $amount=0,$order_sn=0) {

		$data = array();
		$data['subsidy_amount'] = $amount;
		$data['subsidy_type'] = 3;
		$data['user_id'] = $user_id;
		$data['user_name'] = $username;
		$data['user_phone'] = $mobile;
		$data['order_no'] = $order_sn;
		$data['status'] = 0;
		$data['add_time'] = $data['last_update_time'] = time();
		try{
			$this->db->begin();
			$this->insert($data);
			$this->db->commit();
			$flag = true;
		} catch(\Exception $e) {
			$this->db->rollback();
			$flag = false;
		}
		return $flag;
	}
	// 修改审核状态
	private function editState($subsidy_id=0, $state=0) {

		/* 锁定用户数据 */
		$sql = " SELECT subsidy_id FROM subsidy where subsidy_id = '{$subsidy_id}' for update ";
		$this->db->fetchOne($sql);

		$sql = "UPDATE subsidy SET status = '{$state}' where subsidy_id='{$subsidy_id}' and status='0'";
		if(!$this->db->execute($sql)) {
			throw new \Exception('edit status error');
		}
		if(1 != $this->db->affectedRows()) {
			throw new \Exception('operate error');
		}
		switch ($state) {
			case 1:
				$sql = "SELECT * from subsidy where subsidy_id='{$subsidy_id}'";
				$row = $this->db->fetchOne($sql, 2);
				$UserSubsidy = new UserSubsidy($this->sell_id, $this->connect);
				switch ($row['subsidy_type']) {
					case 0:
						$demo = "订单【{$row['order_no']}】交易所得";
						break;
					case 1:
						$demo = '"可信农场"身份补贴所得';
						break;
					case 3:
                        $demo = '系统追加补贴所得';
                        break;
					default:
						$demo = "订单【{$row['order_no']}】交易关闭, 退款所得";
						break;
				}
				$UserSubsidy->addSubsidy($row['subsidy_amount'], $demo, 0);
				break;
		}
	}
	// 插入补贴信息
	private function insert($data=array()) {
		//if(!empty($data)&&isset($data["subsidy_amount"])&&$data["subsidy_amount"]>0){
	        $field = implode('`, `', array_keys($data));
			$val = implode("', '", $data);
			$sql = "INSERT INTO subsidy (`{$field}`) values ('{$val}')";
			if(!$this->db->execute($sql)) {
				throw new \Exception('insert send error');
			}
			$subsidy_id = $this->db->lastInsertId();
			$this->updateSn($subsidy_id);
	    //}
	}
	// 生成补贴编号
	private function updateSn($subsidy_id=0) {
		$sub_sn = 'SY'.sprintf('%012d', $subsidy_id);
		$sql = "UPDATE subsidy set subsidy_no='{$sub_sn}' where subsidy_id='{$subsidy_id}'";
		if(!$this->db->execute($sql)) {
			throw new \Exception('update sn error');
		}
	}

}