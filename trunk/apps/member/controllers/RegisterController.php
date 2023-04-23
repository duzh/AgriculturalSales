<?php
/**
 * @package     Mdg
 * @subpackage  Member
 * @author      Funky <70793999@qq.com>
 * @copyright   2014 YNC365
 * @version     @@PACKAGE_VERSION@@
 */
namespace Mdg\Member\Controllers;
use Lib\Member as Member,
    Lib\Auth as Auth,
    Lib\SMS as sms,
    Lib\Utils as Utils, 
    Lib\Crypt as crypt;
use Mdg\Models\Users as Users;
use Mdg\Models\UsersExt as Ext;
use Mdg\Models as M;
use Lib as L;

class RegisterController extends ControllerBase
{

    public function indexAction()
    {
        $this->view->title = '注册-丰收汇-高价值农产品交易服务商';
        $this->view->keywords = '丰收汇,农业,农产品,农副产品,农产品批发,农产品电商,农产品现货,农产品交易,农产品加工,农产品价格';
        $this->view->descript = '丰收汇是依托中国农业互联网高科技综合服务商云农场的丰富资源和先进技术而建立的中国最专业的网上农产品服务平台，
        为中国农村专业合作社、中小农商企业等提供农产品信息发布、行情资讯、网上商城、金融服务、定向对接、安全模型、订单种植等专业化服务，
        旨在成为全球高价值农产品交易服务商。';
    }

    public function companyAction(){
        $this->view->title = '注册-丰收汇-高价值农产品交易服务商';
        $this->view->keywords = '丰收汇,农业,农产品,农副产品,农产品批发,农产品电商,农产品现货,农产品交易,农产品加工,农产品价格';
        $this->view->descript = '丰收汇是依托中国农业互联网高科技综合服务商云农场的丰富资源和先进技术而建立的中国最专业的网上农产品服务平台，
        为中国农村专业合作社、中小农商企业等提供农产品信息发布、行情资讯、网上商城、金融服务、定向对接、安全模型、订单种植等专业化服务，
        旨在成为全球高价值农产品交易服务商。';
    }

    public function userAction(){
        $this->view->title = '注册-丰收汇-高价值农产品交易服务商';
        $this->view->keywords = '丰收汇,农业,农产品,农副产品,农产品批发,农产品电商,农产品现货,农产品交易,农产品加工,农产品价格';
        $this->view->descript = '丰收汇是依托中国农业互联网高科技综合服务商云农场的丰富资源和先进技术而建立的中国最专业的网上农产品服务平台，
        为中国农村专业合作社、中小农商企业等提供农产品信息发布、行情资讯、网上商城、金融服务、定向对接、安全模型、订单种植等专业化服务，
        旨在成为全球高价值农产品交易服务商。';
    }

    public function checkAction(){
        $mobile = $this->request->getPost('mobile');

        $msg = array();
        $member = new Member();
        if($mobile){
            $member = $member->checkMember($mobile);
            
            if($member){
               $users=Users::findFirst("username=".$mobile);
               if($users){
                $users->delete();
               }
               $msg['ok'] = '';
            }else{
                 $msg['error'] = '手机号码已经存在!';
            }
        }else{
            $msg['error'] = '手机号码错误!';
        }
        echo json_encode($msg);
        exit;
    }


    public function saveAction(){
        
        $username = $this->request->get('mobile');
        $password = $this->request->get('password');
        $usertype = $this->request->get('usertype','int',0);
        $vcode = $this->request->get('vcode','int',0);

        $this->session->moblie=$username;

        try{
            $redis = new \Lib\PhpRedis('fsh_register_');
        }catch(\RedisException $e) {
            die('网络异常');
            // file_put_contents("log/yncregister.log", $e->getMessage() . date("Y-m-d H:i:s") . "\r\n", FILE_APPEND);
        }
        $cp = new \Lib\Captcha($redis);

        // var_dump($cp->verifyCode($username, $vcode));exit;
        if(!$cp->verifyCode($username, $vcode, 1)){
        // if(!M\Codes::checkCode($username, $vcode )){
            $this->flash->error('验证码错误');
            switch ($usertype) {
                case '1':
                    $action = 'user';
                    break;
                default:
                    $action = 'company';
                    break;
            }
            
            return $this->dispatcher->forward(array(
                "controller" => "register",
                "action" => $action
            ));
        }
        $this->session->vcode='';
        M\Codes::delcode($username,3);
        $member = new Member();
        $synuser = $member->register($username,$password,10);
        
        if($synuser){
            $salt  = Auth::random(6,1);
            $users = new Users();
            $users->id            = $synuser['id'];
            $users->username      = $synuser['user_name'];
            $users->usertype      = $usertype;
            $users->regtime       = time();
            $users->regip         = Utils::getIP();
            $users->lastlogintime = time();
            $users->member_type = 1;
            $users->encodePwd($password,$salt);
            $users->save();
            $ext = new Ext();
            $ext->uid = $synuser['id'];
            $ext->save();
            
            $this->session->vcode = null;

            $ThriftInterface = new L\Ynp($this->ynp);
            //检测用户是否绑定云农宝
            $data = $ThriftInterface->checkPhoneExist($username);
            if($data != '01') {
                //绑定用户信息
                $info = $member->getMember($username);
                
                $ynpinfo = $ThriftInterface->userDataSync(
                    $info['user_id'],
                    $info['user_name'],
                    $info['email'],
                    $info['password'],
                    $info['reg_time'], 
                    $info['msn'], 
                    '','','',$info['qq'],0
                    );
                }
                
            $userext=Ext::getuserext($users->id);
            $str="{$users->id}|{$users->username}|{$userext->name}";
            $cookies=crypt::authcode($str,$operation = 'ENCODE', $key = '', $expiry = 3600);
            setcookie("ync_auth",$cookies,time()+3600, '/',".ync365.com");
            setcookie("ync_auth",$cookies,time()+3600, '/',".5fengshou.com");  
            $this->session->user = array('mobile'=>$users->username,'id'=>$users->id,'name'=>$userext->name);
            echo "<script>location.href='/index'</script>";exit;            
        }
   

        $this->flash->error('手机号码已经存在');
        switch ($usertype) {
            case '1':
                $action = 'user';
                break;
            default:
                $action = 'company';
                break;
        }

        return $this->dispatcher->forward(array(
            "controller" => "register",
            "action" => $action
        ));

    }

    public function successAction(){
        $sms = new sms();
        $str = $sms->send($this->session->moblie,'恭喜您已注册成功，成为丰收汇会员。丰收汇，最真实的农产品交易服务商，寻找您信赖的农产品。');
        $this->session->moblie='';
        $this->view->title='注册成功!';
    }

    public function getCodeAction(){
        $mobile = L\Validator::replace_specialChar($this->request->get('mobile','string',''));
        $code = $this->request->get('code', 'string', '');
        if(!$code || strtolower($code) != strtolower($this->session->image_code)) {
            $msg['ok']='0';
            $msg['error']='验证码错误';
            die(json_encode($msg));
        }
        $msg = array();
        if($mobile && $this->validatemobile($mobile)){

            $options = array();
            $options['accountsid']='0aaf2a6bbde511445e9da9498815e3c9';
            $options['token']='9875222300eb509df58aadc906ba1191';
            
            $ucpass = new \Lib\Ucpaas($options);
            try{
                $redis = new \Lib\PhpRedis('fsh_register_');
            }catch(\RedisException $e) {
                $msg['ok']='0';
                $msg['error']='网络异常';
                die(json_encode($msg));
                // file_put_contents("log/yncregister.log", $e->getMessage() . date("Y-m-d H:i:s") . "\r\n", FILE_APPEND);
            }
            $cp = new \Lib\Captcha($redis);
            // 获取验证码
            $codes = $cp->getCode($mobile);
            if($codes){
                $appId = "94ea71b824e449b09b64b0cd079961db";
                $to = $mobile;
                $templateId = "10191";
                // $param=rand(100000,999999);
                $ucpass->templateSMS($appId,$to,$templateId,$codes);
            }

            // require_once(PUBLIC_PATH.'/../apps/lib/Ucpaas.class.php');

            //初始化必填
            // $options['accountsid']='0aaf2a6bbde511445e9da9498815e3c9';
            // $options['token']='9875222300eb509df58aadc906ba1191';
            // $ucpass = new \Ucpaas($options);
            // $appId = "eefc9db32aa744fa9093bb8529790399";
            // $to = $mobile;
            // $templateId = "10191";
            // $param=rand(100000,999999);

            // $ucpass->templateSMS($appId,$to,$templateId,$param);
            // $this->session->vcode = $code;
            // $this->savecode($mobile,$code);
            $msg['ok']='1';
            // $code = auth::random(6,1);
            // $sms = new sms();
            // $time=date("Y-m-d H:i:s",time());
            // $msgs = '欢迎来到丰收汇，注册验证码为' . $code . ' 注册时间: '.$time.'(如非本人操作请忽略！)';
            // $str = $sms->send($mobile,$msgs);
            // if($str==0){
            //     $this->session->vcode = $code;
            //     $this->savecode($mobile,$code);
            //     $msg['ok']='1';
            // }
        }else{
            $msg['ok']='0';
            $msg['error']='手机号码错误';
        }
        echo json_encode($msg);
        exit;
    }

    /**
     * 获取redis 验证码
     * @return [type] [description]
     */
    public function getCodesAction () {
        $redis = new \Lib\PhpRedis('fsh_register_');
        #$mobile = '18632835661';
        $mobile = L\Validator::replace_specialChar($this->request->get('mobile','string',''));
        $cd = $redis->get($mobile);
        
        var_dump($cd);
        exit;

    }
    public function getsubsidycodeAction(){

        $mobile = $this->session->user["mobile"];
        
        //$mobile=13521962900;
        $msg = array();
        if($mobile && $this->validatemobile($mobile)){
            $code = auth::random(6,1);
            $sms = new sms();
            $time=date("Y-m-d H:i:s",time());
            $msgs = '欢迎来到丰收汇，使用补贴验证码为' . $code . ' 使用时间: '.$time.'(如非本人操作请忽略！)';
            $str = $sms->send($mobile,$msgs);
            if($str==0){
                $this->session->vcode = $code;
                $this->savesubsidycode($mobile,$code);
                $msg['ok']='1';
            }
        }else{
            $msg['ok']='0';
            $msg['error']='手机号码错误';
        }
        echo json_encode($msg);
        exit;
    }
     public function  savecode($mobile,$code){
        $time = time();
        $cond = array("mobile='{$mobile}' and  endtime>'{$time}' and type=3 ");
        $cond['order'] = 'id desc';
        $codes = M\Codes::findFirst($cond);
        if(!$codes) {
            $codes = new M\Codes();
            $codes->mobile = $mobile;
            $codes->code =$code;
            $codes->endtime = $time + 3600;
            $codes->type = 3;
            $codes->save();
        }

        $codes->code = $code;
        $codes->endtime = $time + 3600;
        $codes->save();
    }
    public function  savesubsidycode($mobile,$code){
        $time = time();
        $cond = array("mobile='{$mobile}' and  endtime>'{$time}' and type=4 ");
        $cond['order'] = 'id desc';
        $codes = M\Codes::findFirst($cond);
        if(!$codes) {
            $codes = new M\Codes();
            $codes->mobile = $mobile;
            $codes->code =$code;
            $codes->endtime = $time + 3600;
            $codes->type = 4;
            $codes->save();
        }

        $codes->code = $code;
        $codes->endtime = $time + 3600;
        $codes->save();
    }
    public function checkcodeAction(){

        // $msg = array('code'=>$this->session->vcode);
        
        $vcode = L\Validator::replace_specialChar($this->request->getPost('vcode','string',''));
        $mobile = L\Validator::replace_specialChar($this->request->getPost('mobile','string',''));
        if(!$mobile || !$vcode) {
            exit(json_encode(array('error' => '验证码错误')));
        }

        try{
            $redis = new \Lib\PhpRedis('fsh_register_');
        }catch(\RedisException $e) {
            die(json_encode(array('error'=>'网络异常')));
            // file_put_contents("log/yncregister.log", $e->getMessage() . date("Y-m-d H:i:s") . "\r\n", FILE_APPEND);
        }
        $cp = new \Lib\Captcha($redis);
        if($cp->verifyCode($mobile, $vcode)){
        // if(M\Codes::checkCode($mobile, $vcode )){
            $msg['ok'] = 1;
        }else{
            $msg['error']='验证码错误';
        }
        die(json_encode($msg));
    }
     public function checksubsidycodeAction(){
       
        $vcode = L\Validator::replace_specialChar($this->request->getPOST('vcode','string',0));
        $mobile = $this->session->user["mobile"];
      
    
        //$mobile=13521962900;
        $cond = array("mobile='{$mobile}' and type=4 and code='{$vcode}' ");
        $cond['order'] = 'id desc';
        $codes = M\Codes::findFirst($cond);

        $time=time();
        if(!$codes){
            $msg['error']='验证码错误';
            die(json_encode($msg));
        }
        if($codes->endtime<$time){
            $msg['error']='验证码已过期';
            die(json_encode($msg));
        }
        $msg['ok'] = 1;
        $codes->delete();
        die(json_encode($msg));
    }
    public function validateMobile($mobile){
        $regex = '/^1[3-9]\d{9}$/';
        return preg_match($regex,$mobile);
    }
}

