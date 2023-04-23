<?php

namespace Mdg\Member\Controllers;

class MsgController extends ControllerBase
{
	public function showmsgAction() {
	   $data = base64_decode(str_replace(' ','+',$this->request->get("data")),1);
	   if(isset($data)&&$data!=''){
           parse_str($data,$_GET);
       }
	   if(isset($_GET["msg"])&&$_GET["msg"]){
	   	  $this->view->content = $_GET["msg"];
	   }else{
	     $this->view->content = '' ;
	   }
	   if(isset($_GET["ref"])&&$_GET["ref"]){
	   	  $this->view->url =  $_GET["ref"];
	   }else{
	   	  $this->view->url = '';
	   }
	   $this->view->is_ajax = true;
	}
}