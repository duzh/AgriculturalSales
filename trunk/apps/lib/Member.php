<?php
namespace Lib;
include_once "Hprose/HproseHttpClient.php";

class Member{
	private $_url = HPROSE_UWEBSERVICE.'/member.php';
	private $client = null;

	public function __construct(){
		$this->client = new \HproseHttpClient($this->_url);
	}

	/**
	 * 用户注册
	 * @param  string $mobile 手机号
	 * @param  string $pwd    密码
	 * @return array
	 */
	public function register($mobile='',$pwd='',$type=10){
		$user = $this->client->Member_Register($mobile,$pwd,$type);
		return $user;
	}

	/**
	 * 验证登录
	 * @param  string $mobile 手机号
	 * @param  string $pwd    密码
	 * @return Array
	 */
	public function validateLogin($mobile='',$pwd=''){
		$user = $this->client->Member_ValidateLogin($mobile,$pwd);
		return $user;
	}
    
	/**
	 * 修改密码
	 * @param  string $mobile 手机号
	 * @param  string $pwd    密码
	 * @return boolean
	 */
	public function changePWD($mobile='',$newpwd='',$oldpwd='',$need=true){
		return $this->client->Member_changePWD($mobile,$newpwd,$oldpwd,$need);
	}

	public function checkMember($mobile=''){
		return $this->client->Member_checkMobile($mobile);
	}

	public function getMember($mobile){
		return $this->client->Member_getMember($mobile);
	}
	public function perfect($areas_info='',$user_id){
		 
		 return $this->client->Member_perfect($areas_info,$user_id);
	}
}