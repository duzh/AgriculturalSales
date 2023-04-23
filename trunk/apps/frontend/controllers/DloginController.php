<?php

namespace Mdg\Frontend\Controllers;

use Lib\Member as member,
    Lib\Auth as Auth,
    Lib\SMS as sms;

use Mdg\Models\Users as Users;
use Lib as L;

class DloginController extends ControllerBase
{
    public function indexAction() {
        $go_back = L\Validator::replace_specialChar($this->request->get('ref', 'string', ''));

        $go_back = $go_back ? $go_back : '/';
        $this->view->go_back = $go_back;
       
    }

    public function loginAction() {

        $mobile = L\Validator::replace_specialChar($this->request->getPost('mobile','string',''));
        $password = L\Validator::replace_specialChar($this->request->getPost('password','string',''));
        $rs = array('state'=>false, 'msg'=>'登录成功！');

        $member = new member();
        $user = $member->validateLogin($mobile,$password);
        if($user){
            $this->session->user = array('mobile'=>$user['user_name'],'id'=>$user['user_id']);
            $rs['state'] = true;
        }else{
            $rs['msg'] = '用户名密码错误！';
        }

        die(json_encode($rs));
    }
}