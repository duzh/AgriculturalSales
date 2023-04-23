<?php

namespace Mdg\Manage\Controllers;

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{
	public function initialize(){
  	$adminuser = $this->session->adminuser;  
  	} 


  	public function showMessage ($url='') {
  		echo "<script>location.href='{$url}'</script>";exit;
  	}
}
