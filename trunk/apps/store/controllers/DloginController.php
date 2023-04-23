<?php



namespace Mdg\Store\Controllers;



use Lib\Member as member,

    Lib\Auth as Auth,

    Lib\SMS as sms;



use Mdg\Models\Users as Users;

use Lib\Crypt as crypt;

class DloginController extends ControllerBase

{

	public function indexAction() {
        
		$go_back = $this->request->get('ref', 'string', '');

		$this->view->go_back = $go_back;

	}



	public function loginAction() {


        $mobile = $this->request->getPost('mobile','string','');

        $password = $this->request->getPost('password','string','');

        $go_back = $this->request->getPost('go_back','string','');



        $user = Users::validateLogin($mobile, $password);

        if(!$user){

            $this->flash->error("用户名密码错误！");

            return $this->dispatcher->forward(array(

                "controller" => "dlogin",

                "action" => "index"

            ));

        }

        if(!$user->areas || !$user->ext->name || !$user->ext->address || !$go_back) {

            echo "<script>alert('请完善个人信息！');window.parent.location.href='/member/perfect/index';</script>";exit;

        }

        $this->session->user = array('mobile'=>$user->username,'id'=>$user->id,'name'=>$userext->name);

        $str="{$mobile},{$password}";

        $cookies=crypt::authcode($str,$operation = 'ENCODE', $key = '', $expiry = 3600);

        setcookie("user",$cookies,time()+3600, '/',".ync365.com"); 

   

         echo "<script src='http://www.ync365.com/user.php?act=setlogin'></script>";

         $go_back = str_replace('/store', '', $go_back);

         $this->response->redirect($go_back)->sendHeaders();



	}

}