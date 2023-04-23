<?php
namespace Mdg\Api\Controllers;
use Lib\Member as Member,
    Lib\Auth as Auth,
    Lib\SMS as sms,
    Lib\Utils as Utils;
use Mdg\Models\Users as Users;
use Mdg\Models\UsersExt as Ext;
use Lib as L;
use Mdg\Models as M;
/**
 * 这个接口是负责用户注册  检测验证码  检测手机号  注册协议
 */
class RegisterController extends ControllerBase
{    

    
    /** @var array 验证码类型 */
    private $_type = array(
        'reg'     => 1,
        'findpwd' => 2
    );
    /**
     * 用户注册接口 
     * @return string  
     * {"errorCode":0,"data":{"token":"jbh6lb1n5avclq7m2k7tk335c0","sortVersion"=>'分类版本号',"addrVersion"=>'地址版本号',"member":{"mobile":"13331057973","uid":"507389"}}}
     * <br />
     * <code>
     * <br />
     * post 传值 
     * url http://www.5fengshou.com/api/register/save <br />
     * string mobile 用户名 <br />
     * string password   密码 <br />
     * int usertype   用户类型  1 供应商 2 采购商 <br />
     * string vcode   验证码 <br />
     * </code>
     */
    public function saveAction(){
        
        $username = $this->request->getPost('mobile','string','');
        $password = $this->request->getPost('password','string','');
        $usertype = $this->request->getPost('usertype','int',1);
        $vcode    = $this->request->getPost('vcode','string','');
        
        //检测各项是否为空
        if(!$username || !$password || !$vcode ||!L\Validator::validate_is_mobile($username) ){
            $this->getMsg(parent::PARAMS_ERROR);
        }    
        //检测验证码
        // $codes=$this->checkcodeAction($username,$vcode);
        
        // if(!$codes){
        //      $this->getMsg(parent::CODE_ERROR);
        // }
        // if($codes->code!=$vcode){
        //      $this->getMsg(parent::CODE_ERROR);
        // }
        // if($codes->endtime<time()){
        //      $this->getMsg(parent::VCODETIME_ERROR);
        // }
        $this->db->begin();
        try
        {       
                $member = new Member();
                $synuser = $member->register($username,$password,100);
               
                 //检测是否注册
                //$synuser=yncuser::register($username,$password);
                // if(!$synuser){
                //     throw new \Exception(parent::MOBILEEXISTENE);
                // }
                //存入主表
                $salt  = Auth::random(6,1);
                $users=Users::findFirstByusername($synuser['user_name']);
                if($users){
                    $users->delete();
                }
                $users = new Users();
                $users->id            = $synuser['id'];
                $users->username      = $synuser['user_name'];
                $users->usertype      = $usertype;
                $users->regtime       = time();
                $users->regip         = Utils::getIP();
                $users->lastlogintime = time();
                $users->encodePwd($password,$salt);
                $users->salt=$salt;
                $users->logincount += 1;
                $users->member_type = 1;
                $users->lastloginip = Utils::getIP();
                $users->areas = 0;
                $users->province_id = 0;
                $users->city_id = 0;
                $users->district_id = 0;
                $users->town_id = 0;
                $users->village_id = 0;
            
                if(!$users->save()){
                     throw new \Exception(parent::REGISTER_ERROR);
                }
                //同步信息
                $ext = new Ext();
                $ext->uid = $synuser['id'];
                $ext->save();
                $flag=0;
                $this->db->commit();

        }
        catch(\Exception $e) 
        {
            $this->db->rollback();
            $flag = $e->getMessage();
        }
 
        if($flag==0){
            //删除验证码      
            //M\Codes::delcode($username,1);
            $user = array('mobile'=>$users->username, 'uid'=>$users->id);
            $this->session->user = $user;
            $this->getMsg(parent::SUCCESS, array('token'=>$this->session->getID(),'sortVersion'=>1,'addrVersion'=>1, 'member'=>$user));
        }else{
            $this->getMsg($flag);
        }
       
    }
     // 检测验证码
    public function checkcodeAction($mobile,$code) {

        if(!$mobile || !L\Validator::validate_is_mobile($mobile)||!$code) return false;
        $cond = array("mobile='{$mobile}' and type=1 ");
        $code = M\Codes::findFirst($cond);
        if(!$code){
            return false;
        }else{
            return $code;
        }
    }
    /**
     * 用户检测手机号是否存在 
     * @return   {"errorCode":0}    SUCCESS 0  error 10110
     * <br />
     * <code>
     * <br />
     * post 传值 
     * url http://www.5fengshou.com/api/register/checkmoblie <br />
     * string mobile 要检测的手机号 <br />
     * </code>
     */
    public function checkmoblieAction(){
        $mobile = $this->request->getPost('mobile','string','');

        if(!$mobile || !L\Validator::validate_is_mobile($mobile) ) $this->getMsg(parent::PARAMS_ERROR); 
        $users=Users::findFirst("username='{mobile}'");
        $member = new Member();
        $yncuser=$member->checkMember($mobile);
        //$yncuser=yncuser::checkmoblie($mobile);
        if(!$yncuser){
           $this->getMsg(parent::MOBILEEXISTENE);
        }else{
           $this->getMsg(parent::SUCCESS);
        }
    }
    
}

