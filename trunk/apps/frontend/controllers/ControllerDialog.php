<?php

namespace Mdg\Frontend\Controllers;

use Phalcon\Mvc\Controller;

class ControllerDialog extends Controller
{
	public function initialize(){

		$user = $this->session->user;

		if(!$user['id']){
			$_url = $_SERVER['REQUEST_URI'];
			$this->response->redirect("dlogin/index?ref={$_url}", false, 301)->sendHeaders();
		}
	}

	public function getUserID() {
		$user = $this->session->user;
		return $user['id'];
	}

}
