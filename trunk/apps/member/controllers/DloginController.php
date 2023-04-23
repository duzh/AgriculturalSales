<?php

namespace Mdg\Member\Controllers;

use Lib\Member as member,
    Lib\Auth as Auth,
    Lib\SMS as sms;

use Mdg\Models\Users as Users;
use Mdg\Models\UsersExt as UsersExt;
use Lib\Crypt as crypt;
use Lib as L;

class DloginController extends ControllerBase
{
	public function indexAction() {
		$go_back = $this->request->get('ref', 'string', '');
        $islogin = $this->request->get('islogin', 'int', 0);

        $this->view->islogin = $islogin;
		$this->view->go_back = $go_back;
	}

	public function loginAction() {

        $mobile = $this->request->getPost('mobile','string','');
        $password = $this->request->getPost('password','string','');
        $go_back = $this->request->getPost('go_back','string','');
        $islogin = $this->request->getPost('islogin','string','');
    
        $user = Users::validateLogin($mobile, $password);
        if(!$user){
            $this->flash->error("用户名密码错误！");
            return $this->dispatcher->forward(array(
                "controller" => "dlogin",
                "action" => "index"
            ));
        }
        
        $userext=UsersExt::getuserext($user->id);
        $this->session->user = array('mobile'=>$user->username,'id'=>$user->id,'name'=>$userext->name);
        $str="{$user->id}|{$user->username}|{$userext->name}";
        $cookies=crypt::authcode($str,$operation = 'ENCODE', $key = '', $expiry = 3600);
        setcookie("ync_auth",$cookies,time()+3600, '/',".ync365.com");
        setcookie("ync_auth",$cookies,time()+3600, '/',".5fengshou.com"); 
        setcookie("ync_auth", $cookies, time() + 3600, '/', ".abc.com");
        setcookie("ync_auth", $cookies, time() + 3600, '/', ".ynb.com");
        if(!$user->areas || !$user->ext->name || !$user->ext->address) {
            echo "<script>alert('请完善个人信息！');window.parent.location.href='/member/perfect/index';</script>";exit;
        }

        //检测用户是否绑定云农宝
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

        if($islogin) {
            echo "<script>window.parent.location.href='{$go_back}';</script>";exit;   
        }
        
        $go_back = str_replace('/member', '', $go_back);
        $this->response->redirect($go_back)->sendHeaders();

	}
}