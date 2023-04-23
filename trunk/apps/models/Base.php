<?php
namespace Mdg\Models;
use Phalcon\Mvc\Model\Criteria;

class Base extends \Phalcon\Mvc\Model
{
	/**
	 * 0,成功
	 */
	const SUCCESS = 0;

	/**
	 * 10000,暂无数据
	 */
	const DATA_EMPTY   = 10000;

	/**
	 * 15001,用户未登录
	 */
	const UN_LOGIN = 15001;

	/** 15002,token 失效 */
	const TOKEN_ERROR = 15002;

	/** 16001,图片上传失败 */
	const UPLOAD_ERROR = 16001;

	/** 16002,上传图片超过最大数量 */
	const UPLOAD_MAX_ERROR = 16002;

	/** 16101,参数不完整 */
	const PARAMS_ERROR = 16101;

	/** 16102,开通赚钱账号失败 */
	const MONEY_ACCOUNT_OPEN_ERROR = 16102;

	/** 16103,此用户未开通账户 */
	const UN_OPEN_ACCOUNT = 16103;

	/** 16500,验证码发送失败 */
	const SENDCODE_ERROR = 16500;

	/** 16501,无效验证码 */
	const CODE_ERROR = 16501;

	/** 16600,设置支付密码错误 */
	const EDITPWD_ERROR = 16600;

	/** 16601,支付密码错误 */
	const ZFPWD_ERROR = 16601;

	/** 16602,设置余额变动通知失败 */
	const EDIT_BALNOTICE_ERROR = 16602;


	/** 16701,订单编号错误 */
	const ORDER_SN_ERROR = 16701;


	/** 16801,获取U刷订单号失败 */
	const UPOSPID_ERROR = 16801;

	public function getMsg($errorCode=0,$data=array()){
		return $data ? array('errorCode'=>$errorCode,'data'=>$data) : array('errorCode'=>$errorCode);
	}

	public function getUid() {
		$user = $this->_dependencyInjector['session']->user;
		return 496744;
		return $user ? $user['uid'] : 0;
	}

	public function getVid() {
		$user = $this->_dependencyInjector['session']->user;
		// return 66;
		return $user ? $user['vid'] : 0;
	}

	public function getUname() {
		$user = $this->_dependencyInjector['session']->user;
		// return '呢称';
		return $user ? $user['callname'] : '';
	}

	public function getMobile() {
		$user = $this->_dependencyInjector['session']->user;
		// return '呢称';
		// return 13331057973;
		return $user ? $user['mobile'] : '';
	}

	public function getUserImg($uid=0) {
		return 'userimg';
	}

	public function getSid() {
		return $this->_dependencyInjector['session']->getID();
	}

	public function formatTime($time=0) {
		$t=time()-$time;
		$f=array(
			'31536000'=>'年',
			'2592000'=>'个月',
			'604800'=>'星期',
			'86400'=>'天',
			'3600'=>'小时',
			'60'=>'分钟',
			'1'=>'秒'
		);
		foreach ($f as $k=>$v)    {
		    if (0 != $c=floor($t/(int)$k)) {
		        return $c.$v.'前';
		    }
		}
	}
}
