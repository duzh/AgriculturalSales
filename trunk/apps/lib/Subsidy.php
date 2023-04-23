<?php
namespace Lib;

class Subsidy {
	/** 可信农场补贴比例 */
	const FARM_RT = 0.003;

	/** 可信农场补偿基数 */
	const FARM_BASE = 1200;

	/** 发放可信农场补贴的最小农场面积 */
	const fARM_MIN_SEND = 50;

	/** 出售货物补贴比例 */
	const ORDER_RT = 0.005;

	/**
	 * 发放可信农场出售货物补贴
	 * @param  integer $amount 订单金额
	 * @return boolean        
	 */
	static function subByOrder($amount=0) {
		return $amount * Subsidy::ORDER_RT;
	}

	/**
	 * 发放可信农场补贴
	 * @param  integer $farm    农场面积
	 * @return boolean          
	 */
	static function subByFarm($farm=0) {
		return ($farm > Subsidy::fARM_MIN_SEND) ? $farm * Subsidy::FARM_BASE * Subsidy::FARM_RT : 0;
	}

}