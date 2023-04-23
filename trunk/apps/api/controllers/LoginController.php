<?php
namespace Mdg\Api\Controllers;

use Mdg\Models as M;
use Lib\Func as Func;
use Mdg\Models\AreasFull as Areas;
use Lib\Member as member,
    Lib\Auth as Auth,
    Lib\Crypt as crypt,
    Lib\SMS as sms;
use Lib as L;
use Mdg\Models\YncUsers as yncuser;
/**
 * 这个接口是负责用户登陆 找回密码 完善资料 查看资料  登录后修改密码
 */
class LoginController extends ControllerBase
{
	
	/**
	 * 用户登录接口 
	 * @return string  {"errorCode":0,"data":{"token":"jbh6lb1n5avclq7m2k7tk335c0",
     * "member":{"mobile":"13331057973","uid":"507389","realname":"用户姓名  没有的话为空",
     * "isperfect":"是否完善信息 ture  完善 false 没有完善"}}}
     * <br />
	 * <br />
	 * <code>
	 * post 传值 
	 * url http://www.5fengshou.com/api/login/checklogin
     * <br />
	 * string uname 用户名 <br />
	 * string pwd   密码 <br />
	 * </code>
	 */
    public function checkloginAction()
    {
    	$uname = $this->request->getPOST('uname', 'string', '');
    	$pwd = $this->request->getPOST('pwd', 'string', '');
    
    	if(!$uname || !$pwd) $this->getMsg(parent::PARAMS_ERROR);

    	if(!($info = M\Users::validateLogin($uname, $pwd))) $this->getMsg(parent::LOGIN_ERROR);
        
    	$user = array('mobile'=>$info->username, 'uid'=>$info->id);
        $userext=M\UsersExt::getuserext($info->id);
        
    	$user['realname'] = $userext->name;
        $isperfect=parent::checkuser($info->id);
        if($isperfect){
          $user['isperfect'] =true;
        }else{
          $user['isperfect'] = false;
        }
        $version=M\Ifversion::getversion();
        $user['sortVersion']=$version["cate"];
        $user['addrVersion']=$version["area"];
        $this->session->user = $user;
       
    	$this->getMsg(parent::SUCCESS, array('token'=>$this->session->getID(), 'member'=>$user));
       
    }
    /**
     * 用户登录接口 
     * @return string  {"errorCode":0,"data":{"token":"jbh6lb1n5avclq7m2k7tk335c0",
     * "member":{"mobile":"13331057973","uid":"507389","realname":"用户姓名  没有的话为空",
     * "isperfect":"是否完善信息 ture  完善 false 没有完善"}}}
     * <br />
     * <br />
     * <code>
     * post 传值 
     * url http://www.5fengshou.com/api/login/checklogins
     * <br />
     * string uname 用户名 <br />
     * string pwd   密码 <br />
     * </code>
     */
    public function checkloginsAction()
    {
        $uname = $this->request->getPOST('uname', 'string', '13121349730');
        $pwd = $this->request->getPOST('pwd', 'string', '75dc652a95abd54a454d1accb01cd42d');
        
        if(!$uname || !$pwd) $this->getMsg(parent::PARAMS_ERROR);
        $info = M\Users::findFirst("username='{$uname}'");
        if(!$info){
            $this->getMsg(parent::LOGIN_ERROR);
        }
        $user = array('mobile'=>$info->username, 'uid'=>$info->id);
        $userext=M\UsersExt::getuserext($info->id);
        
        $user['realname'] = $userext->name;
        $isperfect=parent::checkuser($info->id);
        if($isperfect){
          $user['isperfect'] =true;
        }else{
          $user['isperfect'] = false;
        }
        $version=M\Ifversion::getversion();
        $user['sortVersion']=$version["cate"];
        $user['addrVersion']=$version["area"];
        $this->session->user = $user;
       
        $this->getMsg(parent::SUCCESS, array('token'=>$this->session->getID(), 'member'=>$user));
       
    }

	private function encodePwd($pwd='') {
		return md5($pwd);
	}
    /**
     * 完善资料 
     * @return string  {"errorCode":0,"data":{"member":{"realName":"张三","moblie":"手机号",
     * "addr":"所在地区","detailAddr":"详细地址","personType":"身份类型",
     * "farmArea":"农场面积","mGoods":"商品"}}}
     * <br />
     * <code>
     * post 传值 
     * url http://www.5fengshou.com/api/login/updateusersinfo <br />
     * int areas 地址 (最后一级地址的id)<br />
     * int usertype   用户类型 1 供应商 2 采购商 <br />     
     * string name 用户姓名 <br />
     * string goods   产品 <br />
     * float farm_areas   农场面积 <br />
     * </code>
     */
    public function updateusersinfoAction(){
        
  
        $user_id = $this->getUid();
        if(!$user_id) $this->getMsg(parent::NOT_LOGGED_IN);
        $areas = $this->request->getPOST('areas', 'int', 0);
        $usertype = $this->request->getPOST('usertype', 'int', 0);
        $name = $this->request->getPOST('name', 'string', '');
        $goods = $this->request->getPOST('goods', 'string', '');
        $farm_areas = abs($this->request->getPOST('farm_areas', 'float', 0.00));
          
        if( !$areas&&!$usertype&&!$name&&!$goods && !$farm_areas ) $this->getMsg(parent::PARAMS_ERROR);
       
        if(!($users = M\Users::findFirstByid($user_id))) $this->getMsg(parent::USER_ERROR);
        
        //判断地址是否完善
        // $isareas=M\AreasFull::checkarea($areas);
        // if($areas&&!$isareas){
        //     $this->getMsg(parent::PUR_ADDRESS_ERROR);
        // }
        $users->areas = $areas ? $areas : $users->areas;
        $users->usertype = $usertype ? $usertype : $users->usertype;
       
        if(!$users->save()) $this->getMsg(parent::USERS_INFO_ERROR);

        $uext = M\UsersExt::findFirstByuid($user_id);
        
        if(!$uext) {
            $uext = new M\UsersExt();
            $uext->uid = $user_id;
        }
        if($areas){
            $areas_name=Func::getCols(Areas::getFamily($areas), 'name', ',');
            $areas_info=Areas::getFamily($areas);
            $member = new Member();
            $yncuser=$member->perfect($areas_info,$user_id);
        }
     
        $uext->name = $name ? $name : $uext->name ;
        $uext->areas_name =$areas ? $areas_name :  $uext->address ;
        $uext->address = $areas ? $areas_name : $uext->address;
        $uext->goods = $goods ? $goods : $uext->goods;
        $uext->farm_areas = $farm_areas ? $farm_areas : $uext->farm_areas ;
        $uext->save();
        $this->lookusersinfoAction();

    }

    /**
     * 查看资料 
     * @return string  {"errorCode":0,"data":{"member":{"realName":"张三","moblie":"手机号",
     * "addr":"所在地区",'area(地址id)','1',"personType":"身份类型","farmArea":"农场面积","mGoods":"商品"}}}
     * <br />
     * <code>
     * 不用传值 
     * url http://www.5fengshou.com/api/login/lookusersinfo <br />
     * </code>
     */
    public function lookusersinfoAction(){
        $user_id = $this->getUid();
        if(!$user_id) $this->getMsg(parent::NOT_LOGGED_IN);
        $users = M\Users::findFirstByid($user_id);
        if(!$users){
            $this->getMsg(parent::DATA_EMPTY);
        }
        if($users) $users = $users->toArray();
        $uext = M\UsersExt::findFirstByuid($user_id);
        if(!$uext){
            $newext=new UsersExt();
            $newext->uid=$user_id;
            $newext->save();
        }
        $uext = $uext->toArray();
        
        $member["realName"]=$uext["name"] ? $uext["name"] : '';
        $member["mobile"]=$users['username'] ? $users['username']: '';
        $member["addr"]=$uext["address"] ? $uext["address"] :'';
        $member["area"]=$users["areas"] ? $users["areas"] :'';
        $member["personType"]=$users["usertype"] ? $users["usertype"] : '';
        $member["farmArea"]=$uext["farm_areas"] ? $uext["farm_areas"] :'';
        $member["mGoods"]=$uext["goods"] ? $uext["goods"] :'';
        $this->getMsg(parent::SUCCESS, array('member'=>$member));
    }
    /**
     * 检测原密码 
     * @return string  {"errorCode":0}  10108  原密码错误
     * <br /> 
     * <code>
     * post 传值 
     * url http://www.5fengshou.com/api/login/detectionoldpassword <br />
     * string oldpwd 原密码   <br />
     * </code>
     */
    public function detectionoldpasswordAction(){
        $oldpwd = $this->request->get('oldpwd', 'string', '');
    
        $user_id = parent::getUid();
        if(!$user_id) $this->getMsg(parent::NOT_LOGGED_IN);
        $users = M\Users::findFirstByid($user_id);
        if(!$users){
            $this->getMsg(parent::DATA_EMPTY);
        }
        if($users->password == md5(md5($oldpwd).$users->salt)){
            $this->getMsg(parent::SUCCESS);
        }else{
            $this->getMsg(parent::OLDPWD_ERROR);
        }
    }
    /**
     * 修改密码 
     * @return string  {"errorCode":0}
     * <br />
     * <code>
     * post 传值 
     * url http://www.5fengshou.com/api/login/updatenewpassword <br />
     * string oldpwd 原密码 <br />
     * string pwd 新密码 <br />
     * string repwd 重复密码 <br />
     * </code>
     */
    public function updatenewpasswordAction(){
        $user_id = parent::getUid();

        if(!$user_id) $this->getMsg(parent::NOT_LOGGED_IN);
        $oldpwd = $this->request->getPost('oldpwd', 'string', '');
        $pwd = $this->request->getPost('pwd', 'string', '');
        $repwd = $this->request->getPost('repwd', 'string', '');
        if($pwd != $repwd) $this->getMsg(parent::TWOPWDNOTMATCH);

        $mobile = $this->getMobile();
        $member = new Member();
        $yncuser=$member->checkMember($mobile);
        if($yncuser){
            $this->getMsg(parent::DATA_EMPTY);
        }

        $member->changePWD($mobile,$pwd,'',false);
        $users = M\Users::findFirstByid($user_id);
        if($users) {
            $users->password = $this->encodePwd($pwd, $users->salt);
            $users->save();
        }
        $this->session->user=array();
        $this->getMsg(parent::SUCCESS);
    }

    /**
     * 找回密码第一步 
     * @return string  {"errorCode":0,array('mobile'=>'完成第一步验证的手机号')}
     * {"errorCode":0,"data":{"mobile":"18600413520"}}
     * <br />
     * <code>
     * post 传值 
     * url http://www.5fengshou.com/api/login/resetpwd <br />
     * string mobile 手机号 <br />
     * string vcode 验证码 <br />
     * </code>
     */
    public function resetpwdAction(){
        $mobile = $this->request->getPost('mobile','string','');
        $vcode = $this->request->getPost('vcode','string','');

        if(!$vcode||!$mobile) $this->getMsg(parent::PARAMS_ERROR);
        $moblie=$this->checkmoblieAction($mobile);
        if(!$mobile){
             $this->getMsg(parent::MOBILE_NOT_EXISTENE);
        }
        $codes=$this->checkcodeAction($mobile,$vcode);
        if(!$codes){
            $this->getMsg(parent::CODE_ERROR);
        }
        if($codes->endtime<time()){
             $this->getMsg(parent::VCODETIME_ERROR);
        }
        if($codes->code!=$vcode){
             $this->getMsg(parent::CODE_ERROR);
        }
        $this->getMsg(parent::SUCCESS,array('mobile'=>$mobile));
    }
   
     // 检测验证码
    public function checkcodeAction($mobile,$code) {

        if(!$mobile || !L\Validator::validate_is_mobile($mobile)||!$code) return false;
        $cond = array("mobile='{$mobile}' and type=2 ");
        $code = M\Codes::findFirst($cond);
        if(!$code){
            return false;
        }else{
            return $code;
        }
    }
    public function checkmoblieAction($mobile){
        
        if(!$mobile || !L\Validator::validate_is_mobile($mobile) ){
            return false;
        } 
        $users=M\Users::findFirst("username='{mobile}'");
        $member = new Member();
        $yncuser=$member->checkMember($mobile);
        if(!$yncuser){
           return true;
        }else{
            return false;
        }
    }

    /**
     * 找回密码第二步 
     * @return string  {"errorCode":0}
     * <br />
     * <code>
     * post 传值 
     * url http://www.5fengshou.com/api/login/changepwd <br />
     * string password 新密码 <br />
     * string repassword 重复密码 <br />
     * string vcode 验证码 <br />
     * string mobile 手机号<br/>
     * </code>
     */
     public function changepwdAction(){
        $password   = $this->request->getPOST('password','string','');
        $repassword = $this->request->getPOST('repassword','string','');
        $vcode = $this->request->getPOST('vcode','string','');
        $mobile = $this->request->getPOST('mobile','string','');
        
        if(!$password||!$repassword||!$vcode||!$mobile) $this->getMsg(parent::PARAMS_ERROR);
        $codes=$this->checkcodeAction($mobile,$vcode);
        if(!$codes){
            $this->getMsg(parent::CODE_ERROR);
        }
        if($codes->endtime<time()){
             $this->getMsg(parent::VCODETIME_ERROR);
        }
        if($codes->code!=$vcode){
             $this->getMsg(parent::CODE_ERROR);
        }
        if($password != $repassword){
             $this->getMsg(parent::TWOPWDNOTMATCH);
        }

        $result = M\Users::changePWD($mobile,$password);
        if($result){
             //删除验证码      
            M\Codes::delcode($mobile,2);
            $this->getMsg(parent::SUCCESS);
        }else{
            $this->getMsg(parent::VCODSAVE_ERROR);
        }
      
    }
    /**
     * 退出 
     * @return string  {"errorCode":0}
     * 
     * url http://www.5fengshou.com/api/login/logout <br />
     */
     public function logoutAction(){
        $this->session->user = '';
        $this->getMsg(parent::SUCCESS);
     }
}
