<?php
namespace Mdg\Member\Controllers;

use Mdg\Models\Users as Users;
//use Mdg\Models\YncUsers as YncUsers;

use Lib\Member as member;
use Lib as L;


class RepwdController extends ControllerMember{

	public function indexAction() {

        $this->view->title = '修改密码-用户中心-丰收汇-高价值农产品交易服务商';
        $this->view->keywords = '丰收汇,农业,农产品,农副产品,农产品批发,农产品电商,农产品现货,农产品交易,农产品加工,农产品价格';
        $this->view->descript = '丰收汇是依托中国农业互联网高科技综合服务商云农场的丰富资源和先进技术而建立的中国最专业的网上农产品服务平台，
        为中国农村专业合作社、中小农商企业等提供农产品信息发布、行情资讯、网上商城、金融服务、定向对接、安全模型、订单种植等专业化服务，
        旨在成为全球高价值农产品交易服务商。';

	}

	public function saveAction() {
		$oldpwd = L\Validator::replace_specialChar($this->request->getPost('oldpwd', 'string', ''));
        $pwd = L\Validator::replace_specialChar($this->request->getPost('pwd', 'string', ''));
        $repwd = L\Validator::replace_specialChar($this->request->getPost('repwd', 'string', ''));

        if($pwd != $repwd) {
            $this->flash->error("两次输入的密码不一致！");
            return $this->dispatcher->forward(array(
                "controller" => "repwd",
                "action" => "index"
            ));
        }

        $user_id = $this->getUserID();
        $username = $this->getUserName();

        $member = new member();
      
        if(!$member->changePWD($username, $pwd, $oldpwd)) {

            $this->flash->error("原密码错误！");
            return $this->dispatcher->forward(array(
                "controller" => "repwd",
                "action" => "index"
            ));
        }

        $users = Users::findFirstByid($user_id);

        if($users) {

            $users->password = md5(md5($pwd).$users->salt);
            $users->save();
        }
        $this->session->destroy();

        $this->response->redirect("login/index")->sendHeaders();
    }

    // public function checkpwdAction(){
    //     $old_pwd = $this->request->getPost('oldpwd','string','');
       
    //     $user_id = $this->getUserID();
    //     $users = Users::findFirstByid($user_id);
    //     $password = md5(md5($old_pwd).$users->salt);
       
    //     if($users->password!=$password)
    //     {
    //         $msg['error'] = "原密码错误";  
    //     }else{
    //         $msg['ok'] = ''; 
    //     }
    //     echo json_encode($msg);
    //     exit;
    // }

    public function checkpwdAction(){
        $old_pwd = $this->request->getPost('oldpwd','string','');
        $user_id = $this->getUserID();
        $username = $this->getUserName();
        $member = new member();
        $user=$member->getMember($username);
        if($user&&$user["password"]==md5($old_pwd)){
           $msg['ok'] = ""; 
        }else{
            $msg['error'] = "原密码错误";  
        }
        echo json_encode($msg);
        exit;
    }

}