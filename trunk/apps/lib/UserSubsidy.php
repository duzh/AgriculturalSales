<?php
namespace Lib;
use Mdg\Models as M;
use Lib as L;
class UserSubsidy {
	// 数据库连接对象
	private $db = null;
	// 归属用户ID
	private $sell_id = 0;
	// 初始化
	public function __construct($sell_id=0, $connect='db') {
		$this->sell_id = $sell_id;
		if(!isset($GLOBALS['di'][$connect])) {
			throw new \Exception('connect error');
		}
		$this->db = $GLOBALS['di'][$connect];
	}
	/**
	 * 添加补贴
	 * @param integer $amount 补贴金额
	 */
	public function addSubsidy($amount=0, $demo='', $source=0) {
		$data = array();
		$data['user_id'] = $this->sell_id;
		$sql = "SELECT * from user_subsidy where user_id='{$data['user_id']}'";
		if($row = $this->db->fetchOne($sql, 2)) {
			$data['subsidy_total_amount'] = $row['subsidy_total_amount']+$amount;
			$data['subsidy_left_subsidy'] = $row['subsidy_left_subsidy'] + $amount;
			$data['last_update_time'] = time();
			$this->update($data, $demo);
		} else {
			$data['subsidy_total_amount'] = $data['subsidy_left_subsidy'] = $amount;
			$data['add_time'] = $data['last_update_time'] = time();
			$this->insert($data, $demo);
		}
		$amount = $amount;
		$this->insertLog($amount, $demo, $source);
	}
	/**
	 * 使用补贴
	 * @param  integer $use 使用金额
	 */
	public function decSubsidy($use=0, $demo='', $source=0) {
		$uid = $this->sell_id;
		$sql = "SELECT * from user_subsidy where user_id = '{$uid}'";
		if((!$row=$this->db->fetchOne($sql, 2)) || $row['subsidy_left_subsidy'] < $use) {
			throw new \Exception('subsidy error');
		}
		$sql = "update user_subsidy set subsidy_left_subsidy = subsidy_left_subsidy-{$use} where user_id = '{$uid}'";
		if(!$this->db->execute($sql)) {
			throw new \Exception('update subsidy error');
		}
		$use = 0 - $use;
		$this->insertLog($use, $demo, $source);
	}

	// 插入用户补贴信息
	private function insert($data=array()) {
		$field = implode('`, `', array_keys($data));
		$val = implode("', '", $data);
		$sql = "INSERT INTO user_subsidy (`{$field}`) values ('{$val}')";
		if(!$this->db->execute($sql)) {
			throw new \Exception('insert user subsidy error');
		}
	}
	// 更新用户补贴信息
	private function update($data=array()) {
		$str = array();
		foreach ($data as $k => $v) {
			$str[] = "{$k}='{$v}'";
		}
		$str = implode(', ', $str);
		$sql = "UPDATE user_subsidy set {$str} where user_id='{$data['user_id']}'";
		if(!$this->db->execute($sql)) {
			throw new \Exception('update user subsidy error');
		}
	}
	// 插入补贴日志
	private function insertLog($amount=0, $demo='', $source=0) {
		$user_id = $this->sell_id;
		$time = time();
		$sql = "INSERT INTO subsidy_log (`user_id`, `amount`, `add_time`, `demo`, `source`) values ('{$user_id}', '{$amount}', '{$time}', '{$demo}', '{$source}')";
		if(!$this->db->execute($sql)) {
			throw new \Exception('insert log error');
		}
	}
	//插入操作日志
	public function insertAdminLog($subsidy_id=0,$admin_name='',$content='',$demo='',$status=1){
		
		$info = M\Subsidy::findFirstBysubsidy_id($subsidy_id); 	
		$SubsidyOperateLog = new M\SubsidyOperateLog();
		$SubsidyOperateLog->subsidy_id=$info->subsidy_id;
        $SubsidyOperateLog->sunsidy_no=$info->subsidy_no;
        $SubsidyOperateLog->operate_user_no=L\Func::GetIps();
        $SubsidyOperateLog->operate_user_name=$admin_name;
        $SubsidyOperateLog->operate_time=time();
        $SubsidyOperateLog->status=$status;
        $SubsidyOperateLog->operate_content=$content;
        $SubsidyOperateLog->demo=$demo;
        $SubsidyOperateLog->add_time=time();
        if(!$SubsidyOperateLog->save()){
        	throw new \Exception('insert SubsidyOperateLog error');
        }
	}
}