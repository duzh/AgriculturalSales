<?php
/**
 * @package     Mdg
 * @subpackage  Member
 * @author      Funky <70793999@qq.com>
 * @copyright   2014 YNC365
 * @version     @@PACKAGE_VERSION@@
 */

namespace Mdg\Member\Controllers;

class MessageController extends ControllerBase{

	public function indexAction($text='返回上一页',$msg='',$url=''){

		$url = $url ? : 'javascript:window.history.go(-1);';
		$this->view->title = '消息提示';
		$this->view->text = $text;
		$this->view->msg  = $msg;
		$this->view->url  = $url;
	}

}