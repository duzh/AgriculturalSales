<?php
namespace Lib;

class PhpRedis {

	// private $_host = '203.158.23.104';
	// private $_port = 6379;
	private $_prefix = '';
	static  $_redis = array();

	public function __construct($prefix='redis') {
		if(!isset(self::$_redis[$prefix]) || null == self::$_redis[$prefix]) {
			$this->_prefix = $prefix;
			self::$_redis[$prefix] = $GLOBALS['di']['redis'];
			self::$_redis[$prefix]->setOption(\Redis::OPT_PREFIX, $prefix);
		}
		return self::$_redis[$prefix];
	}

	// public function __construct($prefix='') {
	// 	if(null == self::$_redis) {

	// 		self::$_redis = new \Redis();
	// 		self::$_redis->connect($this->_host, $this->_port);
	// 		self::$_redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_PHP);
	// 		self::$_redis->setOption(\Redis::OPT_PREFIX, $prefix);
	// 	}
	// }


	public function set($key='', $value='', $timeOut=0) {
		return self::$_redis[$this->_prefix]->set($key, $value, $timeOut);
	}


	public function get($key='') {
		return self::$_redis[$this->_prefix]->get($key);
	}

	public function delete($key='') {
		return self::$_redis[$this->_prefix]->delete($key);
	}



}