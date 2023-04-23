<?php
namespace Lib;

/**
 * 云农宝接口类
 */
class YnbInterface
{
    #测试环境地址
//    public $test_url = CESHI_YNP.'ynp-web/ynbWallet/';
    #public $url = 'http://192.168.88.34;8073/ynp-web/ynbWallet/';
//    #正式环境地址
//    public $formal_url = FORMAL_YNP.'ynp-web/ynbWallet/';
    #url
    #public $url = NYNP_URL.'ynp-web/ynbWallet/';
    public $url = 'http://192.168.88.34;8073/ynp-web/ynbWallet/';
    #来源 C网：1，B网：2，丰收汇：3
    public $source = 3;
    #请求方式 1 post,2 get
    public $request_type = 1;
    #RSA加密解密
    public $rsa;
    #错误码数组
    public $code = array(
        '10001'=>'toke请求成功',
        '10002'=>'token验证错误',
        '10003'=>'用户绑定成功',
        '10004'=>'用户绑定失败',
        '10005'=>'用户登陆成功',
        '10006'=>'用户登陆失败',
        '10007'=>'系统发送的uid、yid与云农宝绑定不匹配',
        '10008'=>'验证码没有过期，不能发送',
        '10009'=>'验证码发送成功',
        '10010'=>'验证码发送失败',
        '10011'=>'该用户已经绑定过不能进行再次绑定',
        '10012'=>'传递的参数值有误',
        '10013'=>'Token生成失败',
        '10014'=>'非法token，云农宝不存在，或过期',
        '10015'=>'Token验证成功',
        '10016'=>'Token验证失败',
        '10017'=>'没有找到相应token信息',
        '10018'=>'短信验证码验证错误',
        '10019'=>'修改绑定成功',
        '10020'=>'修改绑定失败',
        '10021'=>'原绑定账号已经存在',
        '10022'=>'该账号未在云农宝注册',
        '10023'=>'该账号已在云农宝注册',
        '10024'=>'用户名或密码错误',
        '10025'=>'用户名密码正确',
        '10026'=>'该账户已绑定',
        '10027'=>'该系统账号未绑定',
        );
    #类型 默认 1 直接请求 2 获取连接
    public $gtype = 1;

    public function __construct($gtype = null) {

        #初始化
        $this->gtype = ($gtype)?$gtype:$this->gtype;
//        $this->url = DEV_MODE=='master'?$this->formal_url:$this->test_url; #根据环境判断使用URL
        $ynb_publicKey = file_get_contents(PUBLIC_PATH.'/pem/ynb_publicKey.pem');
        $ynb_privateKey = file_get_contents(PUBLIC_PATH.'/pem/ynb_privateKey.pem');
        $this->rsa = new RSA($ynb_publicKey,$ynb_privateKey);
    }
    private function request($get_type,$params,$data){
//        $this->url = DEV_MODE=='master'?$this->formal_url:$this->test_url; #根据环境判断使用URL
        $this->url = NYNP_URL.'ynp-web/ynbWallet/'.$get_type;
        #$this->url = 'http://58.132.173.120:9090/ynp-web/ynbWallet/'.$get_type;
        $data = json_encode($data, JSON_UNESCAPED_SLASHES);
        $data = $this->rsa->splitData($data,117);#加密前按照117截取内容
        foreach($data AS $dk => $dv){
            $den[] = $this->rsa->encrypt($dv);
        }
        $den_str = base64_encode(implode('',$den));

        switch($this->gtype){
            case 1:
                $curl = new Curl();
                $data = $params.'='.urlencode($den_str).'&source='.$this->source;
                $result = ($this->request_type == 1)?$curl->post($this->url,$data):$curl->get($this->url.'?'.$data);#print_r($this->url.'?'.$data);
//                echo $this->url.'?'.$data;
//                print_r($result);exit;
                #if(!$result)throw new \Exception("ynb connect to host");
                $resArr = $this->rsa->splitData(base64_decode($result),128);#解密时根据128进行分割

                foreach($resArr AS $raK => $raV){
                    $re[] = $this->rsa->decrypt($raV);
                }
                return json_decode(implode('',$re));
                break;
            case 2:
                $data = $params.'='.urlencode($den_str).'&source='.$this->source;
                return (object)array('url'=>$this->url,'data'=>$data);
                break;
            default:
                throw new \Exception("gtype error");
                break;
        }

    }
    /**
     * 系统绑定云农宝访问token
     * @param $uid 系统登录UID
     */
    public function getYnbToken($uid){
        $get_type= 'getYnbToken';
        $params = 'reqtoken';
        $data = array('uid'=>$uid);
        return $this->request($get_type,$params,$data);
    }

    /**
     * 系统绑定云农宝接口
     * @param $uid
     * @param $yid
     * @param $pwd
     * @param $token
     * @param $type
     */
    public function bindTogetherYnb($uid,$uname,$yid,$pwd,$token,$smscode,$type){
        $get_type= 'togetherYnb';
        $params = 'togetoken';
        $data = array('uid'=>$uid,'uname'=>$uname,'yid'=>$yid,'pwd'=>$pwd,'token'=>$token,'smscode'=>$smscode,'type'=>$type);

        return $this->request($get_type,$params,$data);
    }

    /**
     * 系统登陆云农宝请求token
     * @param $uid
     * @param $yid
     * @param $memberId
     */
    public function getYnbLoginToken($uid,$yid,$memberId){
        $get_type= 'getYnbLoginToken';
        $params = 'logintoken';
        $data = array('uid'=>$uid,'yid'=>$yid,'memberId'=>$memberId);
        return $this->request($get_type,$params,$data);
    }

    /**
     * 系统登陆云农宝接口
     * @param $uid
     * @param $yid
     * @param $memberId
     * @param $token
     * @return mixed
     */
    public function loginYnbWallet($uid,$yid,$memberId,$token){
        $get_type= 'loginYnbWallet';
        $params = 'loginform';
        $data = array('uid'=>$uid,'yid'=>$yid,'memberId'=>$memberId,'token'=>$token);#print_r($data);exit;
        return $this->request($get_type,$params,$data);
    }

    /**
     * 系统请求云农宝发送短信
     * @param $tel
     * @param $token
     * @return mixed
     */
    public function getSmsInform($uid,$tel,$opstype,$token){
        $get_type= 'getSmsInform';
        $params = 'smsform';
        $data = array('uid'=>$uid,'tel'=>$tel,'opstype'=>$opstype,'token'=>$token);
        return $this->request($get_type,$params,$data);
    }
    /**
     * 系统修改绑定云农宝Tokin
     * @param $uid
     * @param $yid
     * @param $pwd
     * @param $token
     * @param $type
     */
    public function getModifyYnbToken($uid){
        $get_type= 'getModifyYnbToken';
        $params = 'reqmodfiytoken';
        $data = array('uid'=>$uid);
        return $this->request($get_type,$params,$data);
    }
    /**
     * 系统修改绑定云农宝接口
     * @param $uid
     * @param $yid
     * @param $newyid
     * @param $newpwd
     * @param $oldmemberId
     * @param $token
     * @param $smscode
     * @param $type
     */
    public function modifyYnb($uid,$yid,$newyid,$newpwd,$oldmemberId,$token,$smscode,$type){
        $get_type= 'modifyYnb';
        $params = 'modifyaccount';
        $data = array('uid'=>$uid,'yid'=>$yid,'newyid'=>$newyid,'newpwd'=>$newpwd,'oldmemberId'=>$oldmemberId,'token'=>$token,'smscode'=>$smscode,'type'=>$type);#print_r($data);exit;
        return $this->request($get_type,$params,$data);
    }

    /**
     * 注册跳转 无参数
     * @return mixed|object
     * @throws \Exception
     */
    public function registInfo(){
        $get_type= 'registInfo';
        return $this->url.$get_type;
    }

    /**
     * 判断账号是否在云农宝注册
     * @param $yid
     * @param $token
     * @return mixed|object
     * @throws \Exception
     */
    public function checkregister($yid,$uid,$token){
        $get_type= 'checkRegister';
        $params = 'chereg';
        $data = array('yid'=>$yid,'uid'=>$uid,'token'=>$token);
        return $this->request($get_type,$params,$data);
    }

    /**
     * 验证云农宝账号密码是否正确
     * @param $yid
     * @param $memberId
     * @param $pwd
     * @param $uid
     * @param $token
     * @return mixed|object
     * @throws \Exception
     */
    public function checkYidPwd($yid,$memberId,$pwd,$uid,$token){
        $get_type= 'checkYidPwd';
        $params = 'reqyid';
        $data = array('yid'=>$yid,'memberId'=>$memberId,'pwd'=>$pwd,'uid'=>$uid,'token'=>$token);
        return $this->request($get_type,$params,$data);
    }
    /**
     * 获取错误代码
     * @param $code
     * @return mixed
     */
    public function getCode($code){
      return $this->code[$code];
    }
}


?>