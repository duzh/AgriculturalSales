<?php
namespace Lib;

class Caiji {
	static $table = 'sell';
	static $c_table = 'sell_content';

	static function cnfruit($data, $db) {
		$info['title'] = addslashes($data['title']);
		$price = $data['price'] ? explode('：', $data['price']) : array();
		if(!isset($price[1])) $info['min_price'] = $info['max_price'] = 0;
		$price = explode('元', $price[1]);
		$info['min_price'] = $info['max_price'] = floatval($price[0]);
		$info['unit'] = isset($price[1]) ? addslashes(trim($price[1], '/')) : '';
		$info['stime'] = 11;
		$info['etime'] = 123;
		$info['breed'] = addslashes($data['breed']);
		$content = addslashes($data['desc']);
		$info['areas_name'] = $info['address'] = addslashes($data['area']);
		if(!$info['breed'] && !$info['address']) return;
		$info['state'] = 1;
		$time = strtotime($data['addtime']);
		$info['createtime'] = $info['updatetime'] = $time ? $time : time(); 
		$info['uname'] = addslashes($data['uname']);
		preg_match('/src="(.+?)"/i', $data['mobile'], $m);
		$info['mobileurl'] = isset($m[1]) ? '/mobile/cnfruit/'.$m[1] : '';
		$info['category'] = intval($data['cid']);
		$sql = 'INSERT INTO %s (title, category, unit, min_price, max_price, areas, areas_name, address, stime, etime, breed, state, createtime, updatetime, uname, mobileurl) values ("%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s")';
		$sql = sprintf($sql, self::$table, $info['title'], $info['category'], $info['unit'], $info['min_price'], $info['max_price'], 0, $info['areas_name'], $info['address'], $info['stime'], $info['etime'], $info['breed'], $info['state'], $info['createtime'], $info['updatetime'], $info['uname'], $info['mobileurl']);
		$db->db->execute($sql);
		$id = $db->db->lastInsertId();
		$sql = 'UPDATE %s set sell_sn = "%s", source ="2" where id = "%s"';
		$sql = sprintf($sql, self::$table, sprintf('SELL%010u', $id), $id);
		$db->db->execute($sql);
		$sql = "INSERT INTO %s (sid, content) values ('%s', '%s')";
		$sql = sprintf($sql, self::$c_table, $id, $content);
		$db->db->execute($sql);

	}

	static function cnfruit_buy($data, $db) {
		$info['title'] = addslashes($data['title']);
		$price = $data['price'] ? explode('：', $data['price']) : array();
		if(!isset($price[1])) $info['min_price'] = $info['max_price'] = 0;
		$price = explode('元', $price[1]);
		$info['min_price'] = $info['max_price'] = floatval($price[0]);
		$info['unit'] = isset($price[1]) ? addslashes(trim($price[1], '/')) : '';
		$info['breed'] = addslashes($data['breed']);
		$content = addslashes($data['desc']);
		$info['areas_name'] = $info['address'] = addslashes($data['area']);
		$info['state'] = 1;
		$time = strtotime($data['addtime']);
		$info['createtime'] = $info['updatetime'] = $time ? $time : time(); 
		$info['username'] = addslashes($data['uname']);
		preg_match('/src="(.+?)"/i', $data['mobile'], $m);
		$info['mobileurl'] = isset($m[1]) ? '/mobile/cnfruit/'.$m[1] : '';
		$info['category'] = intval($data['cid']);
		$sql = 'INSERT INTO purchase (title, category, unit, areas, areas_name, address, state, createtime, updatetime, username, mobileurl) values ("%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s")';
		$sql = sprintf($sql, $info['title'], $info['category'], $info['unit'], 0, $info['areas_name'], $info['address'], $info['state'], $info['createtime'], $info['updatetime'], $info['username'], $info['mobileurl']);
		$db->db->execute($sql);
		$id = $db->db->lastInsertId();
		$sql = 'UPDATE purchase set pur_sn = "%s", source ="2" where id = "%s"';
		$sql = sprintf($sql, sprintf('Pur%010u', $id), $id);
		$db->db->execute($sql);
		$sql = "INSERT INTO purchase_content (purid, content) values ('%s', '%s')";
		$sql = sprintf($sql, $id, $content);
		$db->db->execute($sql);

	}
}