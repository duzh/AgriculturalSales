<?php
namespace Mdg\Member\Controllers;
use Lib\Validatecode as vcode;
use Lib\Mobilecode as mcode;
use Lib as L;
use Mdg\Models\Codes as Codes;

class CodeController extends ControllerBase
{

    public function indexAction()
    { 
       $_vc = new vcode();

       $code= $_vc->doimg();//实例化一个对象     
     
       $this->session->villagecode = $_vc->code;//验证码赋值于session
       exit;
       
    }
    public function checkvcoedAction(){

        $code =strtolower($this->request->get('img_yz'));
        $vcode= strtolower($this->session->villagecode);
        if($code==$vcode){
           $msg['ok'] = '';       
        }else{
           $msg['error'] = '验证码错误!';           
        }
        
        echo json_encode($msg);
        exit;
    }
    public function testAction(){
      echo Codes::getmoblie("13521962900");
    }
    

}


