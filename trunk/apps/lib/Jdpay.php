<?php
namespace Lib;
use Lib\CashCoupon as CashCoupon;
use Lib as L;

class Jdpay extends \Phalcon\Mvc\Model
{
	// 商户信息
	public static $merchantInfo = array(
			"version"=>'2.0',
			"merchantNum"=>'110081398002',
			"desKey"=>'+Bpwhoxb1T0VGX9tMnVSZz1KO2iuYrmA',
			"currency"=>'CNY',
			"md5Key"=>'LgTdekNzclaLaxtuReLoNqWCMkrfFsta'
	);

	// 京东配置信息	
	public static $JdpayInfo = array(
			"serverQueryUrl"=>'https://m.jdpay.com/wepay/query',
	);

	public static $unSignKeyList = array (
			"merchantSign",
			"token",
			"version" 
	);

	public static function sign($params) {
		ksort($params);
  		$sourceSignString = self::signString ( $params, self::$unSignKeyList );
  		$sha256SourceSignString = hash ( "sha256", $sourceSignString );	
		return self::encryptByPrivateKey ($sha256SourceSignString);
	}

	public static function signString($params, $unSignKeyList) {
		$str = "";
		foreach ( $params as $k => $arc ) {
			for($i = 0; $i < count ( $unSignKeyList ); $i ++) {
				
				if ($k == $unSignKeyList [$i]) {
					unset ( $params [$k] );
				}
			}
		}
		foreach ( $params as $k => $arc ) {
			
			$str = $str . $k . "=" . ($arc == null ? "" : $arc) . "&";
		}
		$str = substr ( $str, 0, - 1 );
		return $str;
	}

	public static function encryptByPrivateKey($data) {
		$pi_key =  openssl_pkey_get_private(file_get_contents('../config/my_rsa_private_pkcs8_key.pem'));//这个函数可用来判断私钥是否是可用的，可用返回资源id Resource id
		$encrypted="";
		openssl_private_encrypt($data,$encrypted,$pi_key,OPENSSL_PKCS1_PADDING);//私钥加密
		$encrypted = base64_encode($encrypted);//加密后的内容通常含有特殊字符，需要编码转换下，在网络间通过url传输时要注意base64编码是否是url安全的
		return $encrypted;
	}
	
	public static function decryptByPublicKey($data) {
		$pu_key =  openssl_pkey_get_public(file_get_contents('../config/wy_rsa_public_key.pem'));//这个函数可用来判断公钥是否是可用的，可用返回资源id Resource id
		$decrypted = "";
		$data = base64_decode($data);
		openssl_public_decrypt($data,$decrypted,$pu_key);//公钥解密
		return $decrypted;
	}

	public static function encrypt($input,$key) {
		$key = base64_decode ($key);
		$key = self::pad2Length ($key, 8 );
		$size = mcrypt_get_block_size ( 'des', 'ecb' );
		$input = self::pkcs5_pad ( $input, $size );
		$td = mcrypt_module_open ( 'des', '', 'ecb', '' );
		$iv = @mcrypt_create_iv ( mcrypt_enc_get_iv_size ( $td ), MCRYPT_RAND );
		@mcrypt_generic_init ( $td, $key, $iv );
		$data = mcrypt_generic ( $td, $input );
		mcrypt_generic_deinit ( $td );
		mcrypt_module_close ( $td );
		$data = base64_encode ( $data );
		return $data;
	}
	// 加密算法
	public static function encrypts($input, $key) {
		$size = mcrypt_get_block_size ( 'des', 'ecb' );
		$td = mcrypt_module_open ( MCRYPT_3DES, '', 'ecb', '' );
		$iv = @mcrypt_create_iv ( mcrypt_enc_get_iv_size ( $td ), MCRYPT_RAND );
		// 使用MCRYPT_3DES算法,cbc模式
		@mcrypt_generic_init ( $td, $key, $iv );
		// 初始处理
		$data = mcrypt_generic ( $td, $input );
		// 加密
		mcrypt_generic_deinit ( $td );
		// 结束
		mcrypt_module_close ( $td );
		
		return $data;
	}

	public static function decrypt($encrypted,$key) {
		$encrypted = base64_decode ($encrypted);
		$key = base64_decode ($key);
		$key = self::pad2Length ( $key, 8 );
		$td = mcrypt_module_open ( 'des', '', 'ecb', '' );
		// 使用MCRYPT_DES算法,cbc模式
		$iv = @mcrypt_create_iv ( mcrypt_enc_get_iv_size ( $td ), MCRYPT_RAND );
		$ks = mcrypt_enc_get_key_size ( $td );
		@mcrypt_generic_init ( $td, $key, $iv );
		// 初始处理
		$decrypted = mdecrypt_generic ( $td, $encrypted );
		// 解密
		mcrypt_generic_deinit ( $td );
		// 结束
		mcrypt_module_close ( $td );
		$y = self::pkcs5_unpad ( $decrypted );
		return $y;
	}
	public static function decrypts($encrypted, $key) {
		//$encrypted = base64_decode($encrypted);
		$td = mcrypt_module_open ( MCRYPT_3DES, '', 'ecb', '' ); // 使用MCRYPT_DES算法,cbc模式
		$iv = @mcrypt_create_iv ( mcrypt_enc_get_iv_size ( $td ), MCRYPT_RAND );
		$ks = mcrypt_enc_get_key_size ( $td );
		@mcrypt_generic_init ( $td, $key, $iv ); // 初始处理
		$decrypted = mdecrypt_generic ( $td, $encrypted ); // 解密
		mcrypt_generic_deinit ( $td ); // 结束
		mcrypt_module_close ( $td );
		//$y = TDESUtil::pkcs5Unpad ( $decrypted );
		return $decrypted;
	}
	
	public static function pad2Length($text, $padlen) {
		$len = strlen ( $text ) % $padlen;
		$res = $text;
		$span = $padlen - $len;
		for($i = 0; $i < $span; $i ++) {
			$res .= chr ( $span );
		}
		return $res;
	}
	public static function pkcs5_pad($text, $blocksize) {
		$pad = $blocksize - (strlen ( $text ) % $blocksize);
		return $text . str_repeat ( chr ( $pad ), $pad );
	}
	public static function pkcs5_unpad($text) {
		$pad = ord ( $text {strlen ( $text ) - 1} );
		if ($pad > strlen ( $text ))
			return false;
		if (strspn ( $text, chr ( $pad ), strlen ( $text ) - $pad ) != $pad)
			return false;
		return substr ( $text, 0, - 1 * $pad );
	}


	public static function prepareParms($version, $merchantNum, $tradeNum) {
		$tradeJsonData = "{\"tradeNum\": \"". $tradeNum."\"}";
		// 1.对交易信息进行3DES加密
		$tradeData = self::encrypt2HexStr(base64_decode(self::$merchantInfo['desKey']),$tradeJsonData);
		// 2.对3DES加密的数据进行签名
		$sha256SourceSignString = hash ( "sha256", $tradeData );
		$sign = self::encryptByPrivateKey ( $sha256SourceSignString);
		$params = array ();
		$params ["version"] = $version;
		$params ["merchantNum"] =  $merchantNum;
		$params ["merchantSign"] = $sign;
		$params ["data"] = $tradeData;
		return $params;
	}


	public static function jdpayStatic($params) {
		$data = json_encode ($params);
		list ( $return_code, $return_content ) = self::http_post_data(self::$JdpayInfo['serverQueryUrl'], $data);
		$return_content = str_replace("\n", '', $return_content);
		$return_data = json_decode ($return_content,true);
		// 执行状态 成功
		if ($return_data['resultCode'] == 0) {
			$mapResult = $return_data['resultData'];
			// 有返回数据
			if (null != $mapResult) {
				$data = $mapResult["data"];
				$sign = $mapResult["sign"];
				// 解密data
				$decrypData = self::decrypt4HexStr(base64_decode(self::$merchantInfo['desKey']),$data);
				// 注意 结果为List集合
				$decrypData = json_decode ( $decrypData, true );

				if (count ( $decrypData ) < 1) {
					$decrypData['errorMsg'] = '无校验信息';
				} else {
					 $decrypData = $decrypData['0'];
				}
			}
		} else {
			$decrypData['errorMsg'] = $return_data ['resultMsg'];
			$decrypData['queryDatas'] =null;
		}
		return $decrypData;
	}

	/**
	 * 将元数据进行补位后进行3DES加密
	 * <p/>
	 * 补位后 byte[] = 描述有效数据长度(int)的byte[]+原始数据byte[]+补位byte[]
	 *
	 * @param
	 *        	sourceData 元数据字符串
	 * @return 返回3DES加密后的16进制表示的字符串
	 */
	public static function encrypt2HexStr($keys, $sourceData) {
		$source = array ();
		$source = self::getBytes ( $sourceData );
		$merchantData = count($source);
		$x = ($merchantData + 4) % 8;
		$y = ($x == 0) ? 0 : (8 - $x);
		$sizeByte = self::integerToBytes ( $merchantData );
		$resultByte = array ();
		for($i = 0; $i < 4; $i ++) {
			$resultByte [$i] = $sizeByte [$i];
		}
		for($j = 0; $j < $merchantData; $j ++) {
			$resultByte [4 + $j] = $source [$j];
		}
		for($k = 0; $k < $y; $k ++) {
			$resultByte [$merchantData + 4 + $k] = 0x00;
		}
		$desdata = self::encrypts ( self::toStr ( $resultByte ), $keys );
		return self::strToHex ( $desdata );
	}

	/**
	 * 转换一个String字符串为byte数组
	 * @param $str 需要转换的字符串        	
	 * @param $bytes 目标byte数组        	
	 */
	public static function getBytes($string) {
		$bytes = array ();
		for($i = 0; $i < strlen ( $string ); $i ++) {
			$bytes [] = ord ( $string [$i] );
		}
		return $bytes;
	}


	/**
	 * 转换一个int为byte数组
	 * @param $byt 目标byte数组        	
	 * @param $val 需要转换的字符串        	
	 */
	public static function integerToBytes($val) {
		$byt = array ();
		$byt [0] = ($val >> 24 & 0xff);
		$byt [1] = ($val >> 16 & 0xff);
		$byt [2] = ($val >> 8 & 0xff);
		$byt [3] = ($val & 0xff);
		return $byt;
	}

	/**
	 * 将十进制字符串转换为十六进制字符串
	 * @param $string 需要转换字符串        	
	 * @return 一个十六进制字符串
	 */
	public static function strToHex($string) {
		$hex = "";
		for($i = 0; $i < strlen ( $string ); $i ++) {
			$tmp = dechex ( ord ( $string [$i] ) );
			if (strlen ( $tmp ) == 1) {
				$hex .= "0";
			}
			$hex .= $tmp;
		}
		$hex = strtolower ( $hex );
		return $hex;
	}
	public static function strToBytes($string) {
		$bytes = array ();
		for($i = 0; $i < strlen ( $string ); $i ++) {
			$bytes [] = ord ( $string [$i] );
		}
		return $bytes;
	}	

	/**
	 * 将字节数组转化为String类型的数据
	 * @param $bytes 字节数组        	
	 * @param $str 目标字符串        	
	 * @return 一个String类型的数据
	 */
	public static function toStr($bytes) {
		$str = '';
		foreach ( $bytes as $ch ) {
			$str .= chr ( $ch );
		}
		return $str;
	}

	/**
	 * 3DES 解密 进行了补位的16进制表示的字符串数据
	 * @return
	 */
	public static function decrypt4HexStr($keys, $data) {
		$hexSourceData = array ();
		$hexSourceData = self::hexStrToBytes ($data);
		// 解密
		$unDesResult = self::decrypts (self::toStr($hexSourceData),$keys);
		$unDesResultByte = self::getBytes($unDesResult);
		$dataSizeByte = array ();
		for($i = 0; $i < 4; $i ++) {
			$dataSizeByte [$i] = $unDesResultByte [$i];
		}
		// 有效数据长度
		$dsb = self::byteArrayToInt( $dataSizeByte, 0 );
		$tempData = array ();
 		for($j = 0; $j < $dsb; $j++) {
 			$tempData [$j] = $unDesResultByte [4 + $j];
 		}
		return self::hexTobin (self::bytesToHex ( $tempData ));

	}

	/**
	 * 转换一个16进制hexString字符串为十进制byte数组
	 * @param $hexString 需要转换的十六进制字符串        	
	 * @return 一个byte数组
	 */
	public static function hexStrToBytes($hexString) {
		$bytes = array ();
		for($i = 0; $i < strlen ( $hexString ) - 1; $i += 2) {
			$bytes [$i / 2] = hexdec ( $hexString [$i] . $hexString [$i + 1] ) & 0xff;
		}
		
		return $bytes;
	}

	/**
	 * 将byte数组 转换为int
	 * @param
	 *        	b
	 * @param
	 *        	offset 位游方式
	 * @return
	 */
	public static function byteArrayToInt($b, $offset) {
		$value = 0;
		for($i = 0; $i < 4; $i ++) {
			$shift = (4 - 1 - $i) * 8;
			$value = $value + ($b [$i + $offset] & 0x000000FF) << $shift; // 往高位游
		}
		return $value;
	}

	/**
	 * @param unknown $hexstr
	 * @return Ambigous <string, unknown>
	 */
	public static function hexTobin($hexstr)
	{
		$n = strlen($hexstr);
		$sbin="";
		$i=0;
		while($i<$n)
		{
			$a =substr($hexstr,$i,2);
			$c = pack("H*",$a);
			if ($i==0){$sbin=$c;}
			else {$sbin.=$c;}
			$i+=2;
		}
		return $sbin;
	}

	// 字符串转16进制
	public static function bytesToHex($bytes) {
		$str = self::toStr ( $bytes );
		return self::strToHex ( $str );
	}



	public static function http_post_data($url, $data_string ) {
	
		$cacert = '';	//CA根证书  (目前暂不提供)
		$CA = false ; 	//HTTPS时是否进行严格认证 
		$TIMEOUT = 30;	//超时时间(秒)
		$SSL = substr($url, 0, 8) == "https://" ? true : false; 

		$ch = curl_init ();
		if ($SSL && $CA) {  
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); 	// 	只信任CA颁布的证书  
			curl_setopt($ch, CURLOPT_CAINFO, $cacert); 			// 	CA根证书（用来验证的网站证书是否是CA颁布）  
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); 		//	检查证书中是否设置域名，并且是否与提供的主机名匹配  
		} else if ($SSL && !$CA) {  
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 		// 	信任任何证书  
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); 		// 	检查证书中是否设置域名  
		}  

		curl_setopt ( $ch, CURLOPT_TIMEOUT, $TIMEOUT);  
		curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, $TIMEOUT-2);  
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data_string );
		curl_setopt ( $ch, CURLOPT_HTTPHEADER, array (
				'Content-Type:application/json;charset=utf-8',
				'Content-Length:' . strlen( $data_string )
				) );

		ob_start();
		curl_exec($ch);
		$return_content = ob_get_contents();
		ob_end_clean();

		$return_code = curl_getinfo ( $ch, CURLINFO_HTTP_CODE );
		return array (
				$return_code,
				$return_content 
		);
	}


	public static function xml_to_array($xml){
		$array = (array)(simplexml_load_string ($xml));
		foreach ($array as $key => $item){
			$array[$key] = self::struct_to_array ((array)$item);
		}
		return $array;
	}

	public static function struct_to_array($item){
		if (!is_string($item)) {
			$item = (array)$item;
			foreach($item as $key => $val){
				$item [$key] = self::struct_to_array ($val);
			}
		}
		return $item;
	}

	/**
	 * 签名
	 */
	public static function generateSign($data, $md5Key) {
		$sb = $data ['VERSION'] [0] . $data ['MERCHANT'] [0] . $data ['TERMINAL'] [0] . $data ['DATA'] [0] . $md5Key;
		
		return md5 ( $sb );
	}

	public static function execute($resp) {
		$md5Key = self::$merchantInfo['md5Key'];
		$desKey = self::$merchantInfo['desKey'];
		if (null == $resp) {
			return;
		}
		$params = self::xml_to_array ( base64_decode ( $resp ) );
		$ownSign = self::generateSign ( $params, $md5Key );
		$params_json = json_encode ( $params );
		if ($params ['SIGN'] [0] != $ownSign) {
			return false;
		}
		$decryptArr = self::decrypt ( $params ['DATA'] [0], $desKey ); // 加密字符串
		return $decryptArr;
	}


	public static function order_info($order_sn){
		$info = DB::getDB('ync365')->fetchOne("SELECT order_id, user_id, consignee, order_sn, order_status, shipping_status, pay_status, order_amount from order_info where order_sn='{$order_sn}' ", 2);
		// var_dump($info);die;
		if(!$info) {
			return 11123;
		}
		if($info['order_status'] || $info['shipping_status'] || $info['pay_status']) {
			return  19995;
		}
		if($info['order_amount']>10000) {
			return  19994;
		}
		return $info;
	}

	public static function mdg_order_info($order_sn){
		$info = DB::getDB()->fetchOne("SELECT id, order_sn, puserid, purname, state, total from orders where order_sn='{$order_sn}' ", 2);
		if(!$info) {
			return  11123;
		}
		if(3 != $info['state']) {
			return 19995;
		}
		if($info['total']>10000) {
			return 19994;
		}
		return $info;
	}
	

	public static function success($order_sn, $pay_time){

		$type = preg_replace('/\d+/', '', $order_sn);
		switch ($type) {
			case 'mdg':
				$info = DB::getDB()->fetchOne("SELECT id, order_sn, puserid, purname, state, total from orders where order_sn='{$order_sn}'", 2);
				$result = self::syncOrderSn($info);
				break;
			case 'PF':
					$result = 0;
					$data['order_sn'] = $order_sn;
					$data['pay_type'] = 'JDPAY';
					$c = L\Curl::getCurl();

					$url = SHOPS_URL . 'station/pay/callback';
					$json = $c->post($url, $data);
					$json  = json_encode($json,2);
					if(isset($json['status']) && !$json['status']) {
						$result = 1 ;
					}
				break;
			default:
				$info = DB::getDB('ync365')->fetchOne("SELECT order_id, user_id, consignee, order_sn, order_status, shipping_status, pay_status, order_amount from order_info where order_sn='{$order_sn}'", 2);
				$result = self::payOrder($info);
				break;
		}
        
		if(!$result){ return false; }

		return true;
	}

	public static function syncOrderSn($info){
		$db = DB::getDB();
		try {
			$db->begin();
			if (!$info){ throw new \Exception('订单异常!'); }
			$order_sn = $info['order_sn'];
			$time=CURTIME;
			if(!$db->execute("UPDATE orders set state='4',pay_type='4',updatetime='{$time}',pay_time='{$time}' where order_sn='{$order_sn}'")) {
				throw new \Exception('订单状态异常!');	
			}
			$data = array();
			$data['state'] = 4;
			$data['operationid'] = $info['puserid'];			
			$data['operationname'] = $info['purname'];			
			$data['type'] = 0;			
			$data['demo'] = '京东支付订单';	

			$data['order_id'] = $info['id'];
			if (!DB::getDB()->fetchAll("SELECT order_id FROM  orders_log WHERE order_id='{$data['order_id']}'  AND `demo` ='京东支付订单'")){
				if(!self::insertLog($data)) {
					throw new \Exception('记录支付日志失败!');
				}
			}

			if(!self::syncWxSN($info['id'], $order_sn)) {
				throw new \Exception('记录京东流水号失败!');
			}
			$db->commit();
			return true;
		} catch (\Exception $e) {
			$db->rollback();
			return false;
		}
	}

	/**
	 * 记录日志
	 * @param  array  $data 数据
	 * @return boolean
	 */
	static function insertLog($data=array()) {
		$data['addtime'] = CURTIME;
		$keys = implode("`,`", array_keys($data));
		$vals = implode("','", $data);
		return DB::getDB()->execute("INSERT INTO orders_log (`{$keys}`) values ('{$vals}')");
	}

	/**
	 * 记录京东流水号
	 * @param  string $order_sn 订单编号
	 * @param  string $wx_sn    微信流水号
	 * @return boolean
	 */
	static function syncWxSN($order_id, $order_sn='') {
		$result = DB::getDB()->fetchOne("SELECT count(*) AS num FROM orders_uposp WHERE order_id = '{$order_id}'", 2);
		if ($result['num']>0){
			return DB::getDB()->execute("UPDATE orders_uposp SET `order_sn`='{$order_sn}', `transation_sn` = '{$order_sn}' WHERE order_id = '{$order_id}'");
		}

		return DB::getDB()->execute("INSERT INTO orders_uposp (`order_id`,`order_sn`, `uposp_sn`, `add_time`, `type`,`transation_sn`) values ('{$order_id}', '{$order_sn}', '', '".CURTIME."', '3','".$order_sn."')");
	}

	static function payOrder($info){
		$db = DB::getDB('ync365');

		try {
			$db->begin();
			if (!$info){ throw new \Exception('订单异常!'); }
			if( !self::editState($info['order_id']) ) {
				throw new \Exception('订单状态异常!');	
			}
			// if($info['pay_status']==2){
			// 	throw new \Exception(1);	
			// }
			$order_sn = $info['order_sn'];
			$data = array();
			$data['order_id'] = $info['order_id'];
			$data['state'] = 3;
			$data['desc'] = $data['demo'] = '京东支付订单';
			$data['admin_id'] = $info['user_id'];			
			$data['admin_name'] = $info['consignee'];

			$oids = self::getOrders($info['order_id']);
			foreach ($oids as $val) {
				$data['order_id'] = $val['order_id'];
				if ( !DB::getDB('ync365')->fetchAll("SELECT order_id FROM orderlog WHERE order_id='{$val['order_id']}' AND `demo` = '京东支付订单'", 2)){
					if(!self::insertLogs($data)) {
						throw new \Exception('记录支付日志失败!');
					}
				}
			}
			if(!self::syncWxSNs($info['order_id'], $order_sn)) {
				throw new \Exception('记录京东流水号失败!');
			}
			//$coupon = CashCoupon::sendCoupon($info);
			self::savesalesnum($info['order_id']);
			$db->commit();
			$flag=true;
		} catch (\Exception $e) {
			$db->rollback();
            $flag = $e->getMessage();
			if($flag==1){
				$flag=true;
			}else{
				$flag=false;
			}
			
		}
		if($flag){
			self::tongbu($info['order_id']);
		}
		return $flag;
	}

	static function getOrders($order_id=0) {
		return DB::getDB('ync365')->fetchAll("SELECT order_id from order_info where order_id='{$order_id}' or main_id='{$order_id}'", 2);
	}
	/**
	 * 记录日志
	 * @param  array  $data 数据
	 * @return boolean
	 */
	static function insertLogs($data=array()) {
		$data['createtime'] = CURTIME;
		$keys = implode("`,`", array_keys($data));
		$vals = implode("','", $data);
		return DB::getDB('ync365')->execute("INSERT INTO orderlog (`{$keys}`) values ('{$vals}')");
	}

	/**
	 * 记录京东流水号
	 * @param  string $order_sn 订单编号
	 * @param  string $wx_sn    微信流水号
	 * @return boolean
	 */
	static function syncWxSNs($order_id, $order_sn='') {
		$result = DB::getDB('ync365')->fetchOne("SELECT count(*) AS num FROM orders_uposp WHERE order_id = '{$order_id}'", 2);
		if ($result['num']>0){
			return DB::getDB('ync365')->execute("UPDATE orders_uposp SET `order_sn`='{$order_sn}', `transation_sn` = '{$order_sn}' WHERE order_id='{$order_id}'");
		}
		return DB::getDB('ync365')->execute("INSERT INTO orders_uposp (`order_id`, `order_sn`, `uposp_sn`, `add_time`, `type`,`transation_sn`) values ('{$order_id}', '{$order_sn}', '', '".CURTIME."', '3','".$order_sn."')");
	}

	/**
	 * 修改订单状态
	 * @param  string  $order_sn 订单ID
	 * @param  integer $state    状态
	 * @return boolean
	 */
	static function editState($order_id=0) {
		$time=time();
		if(!DB::getDB('ync365')->execute("UPDATE order_info set  pay_name='京东支付',order_status='1', shipping_status='0', pay_status='2', is_search='1',pay_time='{$time}',confirm_time='{$time}',pay_id='11' where order_id='{$order_id}' or main_id='{$order_id}'")) {
			return false;
		}
		if(DB::getDB('ync365')->fetchOne("SELECT order_id from order_info where main_id='{$order_id}'")) {
			return DB::getDB('ync365')->execute("UPDATE order_info set is_search='0' where order_id='{$order_id}'");
		}
		return true;
	}
	/**
	 *  更改商品销售量
	 * @param  [type] $order_id [description]
	 * @return [type]           [description]
	 */
    static function savesalesnum($order_id=0){
    	$order_goods=DB::getDB('ync365')->fetchAll("select goods_number,goods_id from order_goods where order_id={$order_id} ",2);
      
        if(!empty($order_goods)) {
        	foreach ($order_goods as $key => $value){
        		 $salesnum=$value["goods_number"];
        		 $goods_id=$value["goods_id"];
        		 if(!DB::getDB('ync365')->execute("UPDATE goods set  salesnum=salesnum+{$salesnum}  where goods_id='{$goods_id}'")){
        		 	return false;
        		 }
        	}
		}    
    }
	/**
	 * 同步订单和佣金
	 * @return [type] [description]
	 */
	static function tongbu($order_id){

            
		    include_once "Hprose/HproseHttpClient.php";
            $ordersClient = new \HproseHttpClient(HPROSE_WEBSERVICE."/index.php");
           
            $apiClient = new \HproseHttpClient(HPROSE_API."/ynccomm");

            $apiClient->Ynccomm_save($order_id);
            $sql = 'SELECT * from %s where order_id="%s" or main_id="%s"';
            $sql = sprintf($sql, 'order_info', $order_id, $order_id);
            $orders = DB::getDB('ync365')->fetchAll($sql, 2);
        
            if($orders&&$orders[0]["pay_status"]!=2){
            if (count($orders) == 1) 
            {
                
                foreach ($orders as $val) 
                {
                  
                    if (!$val['suppliers_id']) 
                    {
                        $ordersClient->insertOrder_getinsert($val['order_sn'], true, '自营');
                    }
                    else
                    {
                        $a = $ordersClient->insertOrder_getinsert($val['order_sn'], true, 'pop');
                    }
                }
            }
            else
            {
                
                foreach ($orders as $val) 
                {
                    
                    if ($val['main_id'] && !$val['suppliers_id']) 
                    {   
                        $ordersClient->insertOrder_getinsert($val['order_sn'], true, '自营');
                    }
                    else
                    {
                        $ordersClient->insertOrder_getinsert($val['order_sn'], true, 'pop');
                    }
                }
            }      
	}
  }


}
?>