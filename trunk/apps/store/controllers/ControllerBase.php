<?php

namespace Mdg\store\Controllers;

use Phalcon\Mvc\Controller;
use Mdg\Models\Users as Users;
class ControllerBase extends Controller
{
	public function initialize(){
		$user = $this->session->user;
		if(!$user['id']){
			$_url = $_SERVER['REQUEST_URI'];
			$this->response->redirect("dlogin/index?ref={$_url}", false, 301)->sendHeaders();
		}

		/* 检测是否完善资料 */
		$user = Users::findFirstByid($user['id']);
		if(!$user->areas || !$user->ext->name || !$user->ext->address) {
            $this->flash->error("请完善个人信息！");
            return $this->dispatcher->forward(array(
                "controller" => "msg",
                "action" => "showmsg",
                'params' => array('perfect/index'),
            )); 
		}
        $this->view->quotation='';
        $this->view->ordercount = '';
		$this->view->title = '';
		$this->view->keywords = '';
		$this->view->descript = '';
	}

	public function getUserID() {
		$user = $this->session->user;
		return $user['id'];
	}

	public function getUserName() {
		$user = $this->session->user;
		return $user['mobile'];
	}

}
