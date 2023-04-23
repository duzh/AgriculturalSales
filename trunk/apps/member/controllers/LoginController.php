<?php
/**
 * @package     Mdg
 * @subpackage  Member
 * @author      Funky <70793999@qq.com>
 * @copyright   2014 YNC365
 * @version     @@PACKAGE_VERSION@@
 */
namespace Mdg\Member\Controllers;

use Lib\Member as member,
    Lib\Auth as Auth,
    Lib\Crypt as crypt,
    Lib\SMS as sms;

use Mdg\Models\Users as Users;
use Mdg\Models\UsersExt as UsersExt;
use Mdg\Models\Orders as Orders;
use Lib as L;

class LoginController extends ControllerBase
{

    public function indexAction()
    {
         // echo $this->encodePwd(111111,192383);
        $this->view->url=isset($_GET['url']) ? base64_decode($_GET['url']) : '';
        $this->view->title = '登录-丰收汇';
        $this->view->keywords = '丰收汇,农业,农产品,农副产品,农产品批发,农产品电商,农产品现货,农产品交易,农产品加工,农产品价格';
        $this->view->descript = '丰收汇是依托中国农业互联网高科技综合服务商云农场的丰富资源和先进技术而建立的中国最专业的网上农产品服务平台，
        为中国农村专业合作社、中小农商企业等提供农产品信息发布、行情资讯、网上商城、金融服务、定向对接、安全模型、订单种植等专业化服务，
        旨在成为全球高价值农产品交易服务商。';
    }
    // public function encodePwd($pwd,$salt){
    //     $password = md5(md5($pwd).$salt);
    //    echo $password;die;
    // }
    public function findpwdAction(){
        
    }
    public function validateloginAction() 
    {
        $mobile = $this->request->getPost('mobile', 'string', '');
        $password =$this->request->getPost('password', 'string', '');
        $user = Users::validateLogin($mobile, $password);
        if ($user) 
        {
            $userext = UsersExt::getuserext($user->id);
            $str = "{$user->id}|{$user->username}|{$userext->name}";
            $cookies = crypt::authcode($str, $operation = 'ENCODE', $key = '', $expiry = 3600);
            setcookie("ync_auth", $cookies, time() + 3600, '/', ".ync365.com");
            setcookie("ync_auth", $cookies, time() + 3600, '/', ".5fengshou.com");
            setcookie("ync_auth", $cookies, time() + 3600, '/', ".abc.com");
            setcookie("ync_auth", $cookies, time() + 3600, '/', ".ynb.com");
            setcookie("ync_auth", $cookies, time() + 3600, '/', ".5fengshoudev.com");
            $this->session->user = array(
                'mobile' => $user->username,
                'id' => $user->id,
                'name' => $userext
            );
            
            // $ThriftInterface = new L\Ynp($this->ynp);
            // //检测用户是否绑定云农宝
            // $data = $ThriftInterface->checkPhoneExist($mobile);
            // if($data != '01') {
            //     //绑定用户信息
            //     $member = new L\Member();
            //     $info = $member->getMember($mobile);
                
            //     $ynpinfo = $ThriftInterface->userDataSync(
            //         $info['user_id'],
            //         $info['user_name'],
            //         $info['email'],
            //         $info['password'],
            //         $info['reg_time'], 
            //         $info['msn'], 
            //         '','','',$info['qq'],0
            //         );
            // }
            
            echo "<script>location.href='/member/index/index'</script>";
        }
        else
        {
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
                $this->response->redirect("login/success")->sendHeaders();
            }
        }

        return $this->dispatcher->forward(array(
                    "controller" => "message",
                    "action" => "index",
                    "params" => array('text'=>'','msg'=>'两次密码不一致','url'=>''),
        ));
    }
    public function successAction(){

    }
    public function getcodeAction(){
        $mobile = $this->request->get('mobile','string','');
        $msg = array();
        if($mobile && $this->validatemobile($mobile)){
            $code = auth::random(6,1);
            $sms = new sms();
            $content = "校验码{$code}，您正在找回密码，需要进行校验。请勿泄漏您的校验码。";
            $str = $sms->send($mobile,$content);
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
        
        $value = isset($_GET['val']) ? $_GET['val'] : '';

        if($value) {

             $cookies = base64_decode($value);
             $order_sn=crypt::authcode($cookies,$operation = 'DECODE');
             $order=Orders::findFirstByorder_sn($order_sn);
             if($order){
                $order->updatetime=time();
                $order->save();
             }
             $pur=$order->puserid;
             $user=Users::findFirst("id='{$pur}'");
             if($user){
                $str="{$user->id}|{$user->username}|{$user->ext->name}";
                $usercookies=crypt::authcode($str,$operation = 'ENCODE', $key = '', $expiry = 3600);
                setcookie("ync_auth",$usercookies,time()+3600, '/',".5fengshou.com");  

                echo "<script>location.href='/member/ordersbuy/index/'</script>";
             }else{
                echo "<script>location.href='/member/login/index/'</script>";
             }
            
        }else{
            echo "<script>location.href='/member/orderbuy/index/'</script>";
        }
       
    }

}

