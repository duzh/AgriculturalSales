<?php
namespace Mdg\Member\Controllers;
use Lib\Validatecode as vcode;
use Lib as L;

class VcodeController extends ControllerBase
{

	public function getVcodeAction() {
		$this->session;
		$vcode = new vcode();
		// $code = $vcode->createCode();
		// print_R($code);exit;
		$vcode->doimg();
		exit;
	}

	public function testAction(){
		$this->session;
		$code = new L\Vcode();
		header (  "Content-Type: image/gif"  );
		echo $code->output();
		exit;
	}

	public function checkvcodeAction(){
		$this->session;
		$code = $this->request->get('code','string','');
		if($code == '') die(json_encode(array('result'=>0)));
		if(vcode::validateCode($code)){
			
			die(json_encode(array('result'=>1)));
		}else{
			die(json_encode(array('result'=>0)));
		}
	}

}