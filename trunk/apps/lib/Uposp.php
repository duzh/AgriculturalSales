<?php
namespace Lib;

class Uposp{
	// 商户号
	static $merid = 9601;
	//static $merid = 9996;
	// static $merid = 8398;
	// 请求地址
	static	$url = 'http://221.179.195.166:9888/GetPayResult';
    //static  $url = 'http://106.120.215.234:9555/GetPayResult';

	//拼写请求前置报文
	static $m_send_buf = "<?xml version=\"1.0\" encoding=\"GB2312\" ?> <I8583> \n" 
						. "<F0  v='9000'/>  \n"  
						. "<F3  v='900000'/>  \n" 
						. "<F4  v='%s'/> \n"
						. "<F18 v='2807'/>  \n"  
						. "<F42 v='%s'/> \n" 
						. "<F45 v='%s,%s'/> \n" 
						. "<MerSign v='%s'/> \n" 
						. "</I8583>";  
    
    //验证订单状态的请求报文
    static $order_send_buf = "<?xml version=\"1.0\" encoding=\"GB2312\" ?> <I8583> \n" 
						. "<F0  v='9000'/>  \n"  
						. "<F3  v='700002'/>  \n" 
						. "<F42 v='%s'/> \n" 
						. "<F45 v='%s,%s'/> \n" 
						. "<MerSign v='%s'/> \n" 
						. "</I8583>";  
	/**
	 * 获取U刷订单号
	 * @param  string  $order_sn 订单编号
	 * @param  integer $amt      订单金额
	 * @param  integer $date     获取U刷订单号时间
	 * @return             
	 */
	static function getSn($order_sn='', $amt=0, $date=0) {
		
		$amt = floatval($amt)*100;
		//$amt = 1;
		$date = date('Ymd');
		// $date = date('Ymd', $date);
		$sign = self::sign($amt.self::$merid.$order_sn.','.$date);
		// var_dump(self::verify($amt.self::$merid.$order_sn.','.$date, $sign));exit;
		// 生成签名
		$send = sprintf(self::$m_send_buf, $amt, self::$merid, $order_sn, $date, $sign);
		$curl = new Curl();
		// print_R($send);exit;
		$xml = $curl->post(self::$url, $send);
		// print_r($xml);exit;
		if(!$xml || !($xml = simplexml_load_string($xml))) return false;
		$msg = iconv("utf-8","gb2312//IGNORE", $xml->F44->attributes()->v);
		list($uposp_sn, $order_sn) = explode(',', $xml->F45->attributes()->v);
		// if($xml->F39->attributes()->v != '0000') $order_sn = $uposp_sn;
		if($xml->F39->attributes()->v != '0000') return false; 
		$rec = $xml->F4->attributes()->v.self::$merid.$uposp_sn;
		// echo $rec;exit;
		// var_dump(self::verify($rec, $xml->MerSign->attributes()->v));exit;
		if(!self::verify($rec, $xml->MerSign->attributes()->v)) return false;
		return $uposp_sn;
	}

	/**RSA签名
	 * $data待签名数据
	 * 签名用商户私钥，必须是没有经过pkcs8转换的私钥
	 * 最后的签名，需要用base64编码
	 * return Sign签名
	 */
	static function sign($data) 
	{
		//读取私钥文件
		// $priKey = file_get_contents(PUBLIC_PATH.'/pem/test_prv.pem');
		$priKey = file_get_contents(PUBLIC_PATH.'/pem/rsa_prv_key.pem');
	
		//转换为openssl密钥，必须是没有经过pkcs8转换的私钥
		$res = openssl_get_privatekey($priKey); 
		// var_dump($res);exit;
		//调用openssl内置签名方法，生成签名$sign
		openssl_sign($data, $sign, $res);
		//释放资源
		openssl_free_key($res);
		//hex编码 
		$sign = bin2hex($sign);
		return $sign;
	}


	/**RSA验签
	 * $data待签名数据
	 * $sign需要验签的签名
	 * 验签用支付宝公钥
	 * return 验签是否通过 bool值
	 */
	static function verify($data, $sign)  
	{
		//读取支付宝公钥文件
		$pubKey = file_get_contents(PUBLIC_PATH.'/pem/uts.posp.public.key.pem');
		// $pubKey = file_get_contents(PUBLIC_PATH.'/pem/rsa_public_key.pem');
		//转换为openssl格式密钥
		$res = openssl_get_publickey($pubKey);
		//调用openssl内置方法验签，返回bool值
		// var_dump($res);exit;
		// var_dump(openssl_verify($data, hex2bin($sign), $res));exit;
		if( openssl_verify($data, hex2bin($sign), $res) == 1 )
		{
			//释放资源
			openssl_free_key($res);
			//返回资源是否成功
			return true;
		}
		else
		{
			//释放资源
			openssl_free_key($res);
			//返回资源是否成功
			return false;
		 }
	}
    static function checkorder($order_sn,$addtime){
         
		$date = date('Ymd',$addtime);
		// $date = date('Ymd', $date);
		$sign = self::sign(self::$merid.$order_sn.','.$date);
		// var_dump(self::verify($amt.self::$merid.$order_sn.','.$date, $sign));exit;
		// 生成签名
		$send = sprintf(self::$order_send_buf,self::$merid, $order_sn, $date, $sign);

		$curl = new Curl();
		// print_R($send);exit;
		$xml = $curl->post(self::$url, $send);

		if(!$xml || !($xml = simplexml_load_string($xml))) return false;
		$msg = iconv("utf-8","gb2312//IGNORE", $xml->F44->attributes()->v);

		switch ($xml->F39->attributes()->v) {
			case '00':
				return "00";
				break;
			case '01':
				return "01";
				break;
			case '02':
				return "02";
				break;
			case '03':
				return "03";
				break;
			case '96':
				return "96";
				break;
			case '94':
				return "94";
				break;
			case '64':
				return "64";
				break;
			case '00110001':
		        return "01";
		        break;
			default:
			    return "02";
				break;
		}
    }


}