<?php
namespace Lib;

class Header{
	static $text = array(
		'login' => array(
			'index'=>'用户登录',
			'findpwd' => '找回密码',
			),
		'register' => array(
			'index'	=> '用户注册',
			'company' => '用户注册',
			'user'	=> '用户注册',
			),
	);
	static function getText($obj){
		// print_r($obj);
		$ctrl = $obj->view->getControllerName();
		$action  = $obj->view->getActionName();

		if(isset(self::$text[$ctrl][$action])){
			return self::$text[$ctrl][$action];
		}else{
			return '';
		}
	}
}