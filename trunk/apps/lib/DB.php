<?php
namespace Lib;

class DB {
	static function getDB($type='db') {
		return $GLOBALS['di'][$type];
	}
}