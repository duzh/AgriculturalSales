<?php

namespace Mdg\Frontend\Controllers;

use Phalcon\Mvc\Controller;

class ControllerAjax extends Controller
{
	public function initialize() 
    {
    	$this->view->keywords = '';
        $this->view->descript = '';
    }
	public function getUserID() {
        $user = $this->session->user;
        return isset($user['id']) ? $user['id'] : 0 ;
    }
    public function getUserName() {
        $user = $this->session->user;
        return isset($user['mobile']) ? $user['mobile'] : 0 ;
    }
}
