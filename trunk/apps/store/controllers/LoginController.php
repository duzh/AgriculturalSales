<?php
/**
 * @package     Mdg
 * @subpackage  Member
 * @author      Funky <70793999@qq.com>
 * @copyright   2014 YNC365
 * @version     @@PACKAGE_VERSION@@
 */
namespace Mdg\Store\Controllers;
use Lib\Member as member,
    Lib\Auth as Auth,
    Lib\Crypt as crypt,
    Lib\SMS as sms;

use Mdg\Models\Users as Users;
use Mdg\Models\UsersExt as UsersExt;
use Mdg\Models\Orders as Orders;
class LoginController extends ControllerBase
{

    public function indexAction()
    {
     
        $this->view->title    = '登录-丰收汇';
        $this->view->keywords = '登录-丰收汇';
        $this->view->descript = '登录-丰收汇';
    }
   
    public function findpwdAction(){
        
    }
    public function validateloginAction(){
    	$mobile = $this->request->getPost('mobile','string','');
    	$password = $this->request->getPost('password','string','');
    	$user = Users::validateLogin($mobile,$password);
      
    	if($user){
      
            $userext=UsersExt::getuserext($user->id);
            $str="{$user->id}|{$user->username}|{$userext->name}";
            $cookies=crypt::authcode($str,$operation = 'ENCODE', $key = '', $expiry = 3600);
            setcookie("ync_auth",$cookies,time()+3600, '/',".ync365.com"); 
    		$this->session->user = array('mobile'=>$user->username,'id'=>$user->id,'name'=>$userext->name);
              echo "<script>location.href='/member/index/index'</script>";
    	}else{
            $this->flash->error('用户名密码错误！');
            return $this->dispatcher->forward(array(
                        "controller" => "login",
                        "action" => "index",
            ));
    	}
    }

    public function resetpwdAction(){
        $mobile = $this->request->getPost('mobile','string','');
        $vcode = $this->request->getPost('vcode','string','');

        if($vcode != $this->session->vcode){
            return $this->dispatcher->forward(array(
                        "controller" => "message",
                        "action" => "index",
                        "params" => array('text'=>'','msg'=>'手机验证码错误','url'=>''),
                    ));
        }

        $this->view->vcode = $vcode;
    }

    public function changepwdAction(){
		$password   = $this->request->getPost('password','string','');
		$repassword = $this->request->getPost('repassword','string','');
        $vcode = $this->request->getPost('vcode','string','');

        if($this->session->vcode != $vcode){
            return $this->dispatcher->forward(array(
                        "controller" => "message",
                        "action" => "index",
                        "params" => array('text'=>'','msg'=>'手机验证码错误','url'=>''),
                    ));
        }

    	if($password == $repassword){
            $result = Users::changePWD($this->session->mobile,$password);
            if($result){
                return $this->dispatcher->forward(array(
                        "controller" => "message",
                        "action" => "index",
                        "params" => array('text'=>'进入会员中心','msg'=>'密码重置成功','url'=>'/member'),
                    ));
            }
    	}

    	return $this->dispatcher->forward(array(
                    "controller" => "message",
                    "action" => "index",
                    "params" => array('text'=>'','msg'=>'两次密码不一致','url'=>''),
                ));
    }

    public function getcodeAction(){
    	$mobile = $this->request->get('mobile','string','');
        $msg = array();
        if($mobile && $this->validatemobile($mobile)){
            $code = auth::random(6,1);
            $sms = new sms();
            $str = $sms->send($mobile,$code);
            if($str==0){
                $this->session->vcode = $code;
                $this->session->mobile = $mobile;
                $msg['ok']='1';
            }
        }else{
            $msg['ok']='0';
            $msg['error']='手机号码错误';
        }
        echo json_encode($msg);
        exit;
    }

    public function checkcodeAction(){
        $msg = array('code'=>$this->session->vcode);
        $vcode = $this->request->get('vcode','string','');
        if($this->session->vcode == $vcode){
            $msg['ok'] = 1;
        }else{
            $msg['error']='验证码错误';
        }
        die(json_encode($msg));
    }

    public function validateMobile($mobile){
        $regex = '/^13[0-9]{9}|15[012356789][0-9]{8}|18[0256789][0-9]{8}|147[0-9]{8}|170[0-9]{8}$/';
        return preg_match($regex,$mobile);
    }
    public function setloginAction(){

        if($_COOKIE["order_sn"]){
            $order_sn=crypt::authcode($_COOKIE["order_sn"],$operation = 'DECODE');
            $order=Orders::findFirstByorder_sn($order_sn);
            $pur=$order->puserid;
            $user=Users::findFirst("id='{$pur}'");
            if($user){
                 $str="{$user->id}|{$user->username}|{$userext->name}";
                 $cookies=crypt::authcode($str,$operation = 'ENCODE', $key = '', $expiry = 3600);
                 setcookie("ync_auth",$cookies,time()+3600, '/',".ync365.com"); 
                 $this->session->user = array('mobile'=>$user->username,'id'=>$user->id);
                  echo "<script>location.href='/member/ordersbuy/index/'</script>";
            }else{
                echo "<script>location.href='/member/login/index/'</script>";
            }

        }else{
            echo "<script>location.href='/member/login/index/'</script>";
        }


    }
}

