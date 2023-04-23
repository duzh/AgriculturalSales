<?php
namespace Mdg\Frontend\Controllers;
use Phalcon\Mvc\Controller;
use Lib as L;


class TestController extends Controller
{
	public function indexAction() {
		var_dump( strstr(strtolower($_SERVER['SERVER_NAME']), '.'));exit;
		define("CUR_DEMAIN", strstr('.', strtolower($_SERVER['SERVER_NAME'])));
		print_R($_SERVER);exit;
		$redis = new L\PhpRedis();
		// $redis->set('aaa', 123456789);
		$redis->delete('aaa');
		var_dump($redis->get('aaa'));exit;
	}
}