<?php
/**
 * @package     Mdg
 * @subpackage  Member
 * @author      Funky <70793999@qq.com>
 * @copyright   2014 YNC365
 * @version     @@PACKAGE_VERSION@@
 */
namespace Mdg\Manage\Controllers;

use Lib\Member as member,
    Lib\Auth as Auth,
    Lib\SMS as sms;

use Mdg\Models\YncAdmin as Admin;
use Lib\Crypt as crypt;
class LoginController extends ControllerBase
{
    /**
     * 登录首页
     * @return [type] [description]
     */
    public function indexAction()
    {
     
    }
    /**
     * 验证登录
     * @return [type] [description]
     */
    public function validateloginAction(){
        
        $cookies = base64_decode($this->request->get('cookie','string',''));
       
        if($cookies==''){
                $mobile = $this->request->getPost('mobile','string','');
                $password = $this->request->getPost('password','string','');
                $user = Admin::validateLogin($mobile,$password);

                if(!$user){
                    $this->flash->error("账号密码错误");
                    return $this->dispatcher->forward(
                        array(
                        "controller" => "login",
                        "action" => "index"
                    ));
                }
                if($user->state==0){
                   $this->flash->error("帐号被冻结");
                    return $this->dispatcher->forward(
                        array(
                        "controller" => "login",
                        "action" => "index"
                    ));
                 }

                $passwords=md5($password);
                $str="{$mobile},{$passwords}";
                $cookies=crypt::authcode($str,$operation = 'ENCODE', $key = '', $expiry = 3600);

        }
        
        include "Hprose/HproseHttpClient.php";
        $client = new \HproseHttpClient(HPROSE_API."/privilege/"); 
       
        $info=$client->Privilege_authcode($cookies,'mdg');
      
         if($info){
                foreach ($info['permission'] as $key => $value) {

                       if($value["pid"]==0){
                            $index[]=$value;
                       }
                       if($value["deep"]==2){
                            $left[]=$value;
                       }
                 }
                 $this->session->index=$index;
                 $this->session->left=$left;
                 $this->session->adminuser = array(
                  'id' =>$info["id"],
                  'username' =>$info["username"],
                  'name' => $info["name"],
                  'lastlogintime' => $info["lastlogintime"],
                  'permission'=>$info['permission'],
                 );
              
                $this->response->redirect("/index")->sendHeaders();
        }else{
                  $this->flash->error("账号密码错误");
                  return $this->dispatcher->forward(
                      array(
                      "controller" => "login",
                      "action" => "index"
                  ));
        }  
      
      
    }
    /**
     *  修改密码
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function changepwdAction($id){
        $password =$id;

        $userid=$this->session->adminuser;
        $user=Admin::findFirstByid($userid['id']);
        $salt = Auth::random(6,1);
        $newpassword=Admin::encodePwd($password,$salt);
        $user->salt=$salt;
        $user->password=$newpassword;
        if($user->save()){
             echo $password;die;
        }
    }
    /**
     *  检测手机号
     * @return [type] [description]
     */
    public function checkAction(){
        $mobile = $this->request->getPost('mobile','string','');
        if($mobile){
            $title=Admin::findFirstByusername($mobile);
            $msg = array();
            if(!empty($title)){
                 $msg['ok'] = ''; 
            }else{
                $msg['error'] = '用户名不存在!';    
            }
            
        }else{
            $msg['error'] = '用户名不存在!'; 
        }
        echo json_encode($msg);die; 
    }
    /**
     *  退出
     * @return [type] [description]
     */
    public function logoutAction() {
         $this->session->adminuser=array();
         setcookie("admin",'',time()-3600, '/',".ync365.com");
         $this->response->redirect("login/index")->sendHeaders();
        //echo "<script>parent.location.href='http://roles.ync365.com/manage/login/index'</script>";die;
    }


}

