<?php
namespace Mdg\Api\Controllers;

use Lib as L;
use Mdg\Models as M;
/**
 * 这个接口是负责获取短信验证码   和校验短信验证码
 * 
 */
class CodesController extends ControllerBase
{
	/** @var array 验证码类型 */
	private $_type = array(
						'reg'     => 1,
						'findpwd' => 2
					);

	/**
	 * 获取验证码接口 
	 * @return string  {"errorCode":0}
	 * <br />
	 * <code>
	 * post 传值 
	 * url http://www.5fengshou.com/api/codes/getcode <br />
	 * string mobile 手机号 <br />
	 * string type   验证码类型 reg 注册 findpwd 找回密码 <br />
	 * </code>
	 */
	public function getcodeAction() {
		$mobile = $this->request->getPost('mobile', 'string', '');
		$type = $this->request->getPost('type', 'string', '');

		$type = isset($this->_type[$type]) ? $this->_type[$type] : '';

		if(!$mobile || !L\Validator::validate_is_mobile($mobile) || !$type) $this->getMsg(parent::PARAMS_ERROR);
		$time = CURTIME;
		$cond = array("mobile='{$mobile}' and  endtime>'{$time}' and type='{$type}'");
		$cond['order'] = 'id desc';
		$code = M\Codes::findFirst($cond);
		if(!$code) {
			$code = new M\Codes();
			$code->mobile = $mobile;
			$code->code = rand(100000, 999999);
			$code->endtime = $time + 3600;
			$code->type = $type;
			$code->save();
		}
		$code->code = rand(100000, 999999);
		$code->endtime = $time + 3600;
        $code->save();
		$sms = new L\SMS();
        
		switch ($type) {
			case $this->_type['reg']:
				$msg = '欢迎来到丰收汇，注册验证码为' . $code->code . '(如非本人操作请忽略！)';
				break;
			case $this->_type['findpwd']:
				$msg = '欢迎来到丰收汇,找回密码验证码为' . $code->code . '(如非本人操作请忽略！)';
				break;
		}

		$sms->send($mobile, $msg) ? $this->getMsg(parent::SENT_CODE_ERROR) : $this->getMsg(parent::SUCCESS);
	}
    /**
	 * 检测验证码接口 
	 * @return string  {"errorCode":0,"data":{"mobile":"13331057973","code":"123456","type":"reg"}}
	 * <br />
	 * <code>
	 * post 传值 
	 * url http://www.5fengshou.com/api/codes/checkcode <br />
	 * string mobile 手机号 <br />
	 * string code   验证码 <br />
	 * string type   验证码类型 reg 验证 findpwd 找回密码 <br />
	 * </code>
	 */
     public function checkcodeAction() {
		$mobile = $this->request->getPOST('mobile', 'string', '');
		$vcode  = $this->request->getPOST('code', 'string', '');
		$types = $this->request->getPOST('type', 'string', '');
		$type = isset($this->_type[$types]) ? $this->_type[$types] : '';
		if(!$mobile || !L\Validator::validate_is_mobile($mobile) || !$type ||!$vcode) $this->getMsg(parent::PARAMS_ERROR);
		$time = CURTIME;
		$cond = array("mobile='{$mobile}' and  endtime>'{$time}' and type='{$type}' and code='{$vcode}'");
		$code = M\Codes::findFirst($cond);
		if(!$code){
		    $this->getMsg(parent::CODE_ERROR);
		}else{
            $this->getMsg(parent::SUCCESS,array("mobile"=>$mobile,"code"=>$vcode,"type"=>$types));
		}
	}
	public function getcodesAction() {
		$mobile = $this->request->get('mobile', 'string', '');
		$types = $this->request->get('type', 'string',1);
   
		$cond = array("mobile='{$mobile}' order by id desc  ");
		$code = M\Codes::findFirst($cond);
		if(!$code){
			echo "没有验证码";die;
		}
        echo $code->code;die;
	   
	}
	public function testAction($mobile){

		if(!$mobile){
			die("请输入手机号");
		}
        try{
           $redis = new \Lib\PhpRedis('fsh_register_');
        }catch(\RedisException $e) {
            die('网络异常');
        }
        $cp = new \Lib\Captcha($redis);
        $char=$cp->can($mobile);
      
        if(!$char){
        	die("验证码失效");
        }else{
        	echo ($char["code"]);die;
        	
        }
        
	}
}