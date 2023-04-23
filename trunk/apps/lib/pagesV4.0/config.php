<?php
	
	//商户的私钥路径
	define("privatekey",'/wwwroot/www.5fengshou.com/apps/lib/pagesV4.0/6290_ShengFengZhongYe.key.pem');
	//商户证书配置地址,如果需要按商户配置私钥地址，则在map中添加“商户号=私钥绝对路径”即可
	$mer_pk = array();
	$mer_pk['6290'] = '/wwwroot/www.5fengshou.com/apps/lib/pagesV4.0/6290_ShengFengZhongYe.key.pem';
	// $mer_pk['6290'] = '/wwwroot/www.5fengshou.com/apps/lib/pagesV4.0/6290_ShengFengZhongYe.key.pem';
	//UMPAY的平台证书路径
	define("platcert",'/wwwroot/www.5fengshou.com/apps/lib/pagesV4.0/cert_2d59.cert.pem');
	//日志生成目录
	define("logpath",'/wwwroot/www.5fengshou.com/apps/lib/pagesV4.0/umpay.log');
	//记录日志文件的同时是否在页面输出:要输出为true,否则为false
	define("log_echo",false);
	//UMPAY平台地址,根据实际情况修改
	define("plat_url","http://pay.soopay.net");
	//支付产品名称:标准支付spay 
	define("plat_pay_product_name","spay");
	//微支付产品名称
	define("plat_micropay_product_name","micropay");
?>