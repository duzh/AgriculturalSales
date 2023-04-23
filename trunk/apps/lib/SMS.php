<?php
namespace Lib;
use Lib\Curl as CURL;
class SMS{
	private $sn='SDK-BBX-010-19680';
	private $pwd;
	private $code = '19680';
	private $url = 'http://sdk2.entinfo.cn:8060/webservice.asmx/SendSMS';
	private $http;
	//private $ext = '【云农场】';
    private $ext = '【丰收汇】';
	public function __construct(){
		$this->http = new CURL();
		$this->pwd = '476f1-Ea';
	}

	public function send($mobile,$content){
		// $ex = "欢迎您注册云农场-丰收汇，您的短信验证码为： %s 如非本人操作，请直接忽略";
		$ex = "%s";
		$content = iconv('utf-8', 'gbk', sprintf($ex , $content ).$this->ext);
		// echo $content;
		// exit;
		$data = array(
			'sn'=>$this->sn,
			'pwd' => $this->pwd,
			'mobile' => $mobile,
			'content' => $content,
		);
		$data = http_build_query($data);
		$content = $this->http->post($this->url,$data);
		$xml = simplexml_load_string($content);
		return intval($xml[0]);
	}
	/**
	 *  订单相关信息内容发送
	 * @param  string  $order_sn 订单号
	 * @param  integer $type     0 确认订单 1 订单支付 
	 * @return string
	 */
	public function getOrderSendContent($order_sn='', $type=0) {
		if(!$order_sn) return '';
		
		switch ($type) {
			case 0:
				$con = '您购买的商品[订单号：%s]已被卖家确认';
				break;
			case 1:
				$con = '您发布的商品[订单号：%s]买家已付款';
				break;
		}
		return sprintf($con, $order_sn);
	}
}
