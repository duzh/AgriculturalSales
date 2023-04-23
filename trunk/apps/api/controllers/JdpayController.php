<?php
namespace Mdg\Api\Controllers;
use Phalcon\Mvc\Controller;
use Lib as L;
use Mdg\Models as M;

class JdpayController extends ControllerBase
{
	/**
	 * 京东支付获取商品信息
	 * /api/Jdpay/GetOrders
	 * <code><pre>
	 * post 
	 * order_sn	integer   订单编号
	 * return 
	 *{
	 *    "errorCode": 0,	错误号
	 *    "data": {
	 *        "currency": "iFM0wGXSNtk=",																// 货币种类（加密）
	 *        "tradeName":"lCv893ihObrvfrlbP/lfqZ9rz19fYIXmRDpcejlJVxy2LqInGfnRlzgfxMKMmeKQHVI",				// 货币种类（加密）
	 *        "tradeTime": "gswD77FCVVwWXVny/G44Rd+qiVxEefB1",										// 交易时间（加密）
	 *        "version": "2.0",																		// 版本号（加密）
	 *        "failCallbackUrl": "7ccAclTJ4zM7l4K3dw4ysL13EYpcAs0m8YNLdDKFxAKf/1+8940/uPB0oJlciYsq",			// 支付失败页面跳转路径（加密）
	 *        "merchantNum": 22294531,																// 商户号（加密）
	 *        "merchantRemark": "/bxsVjgCeHA=",														// 商户号（加密）
	 *        "notifyUrl": "7ccAclTJ4zM7l4K3dw4ysL13EYpcAs0m8YNLdDKFxAIQ7b0lJi9b0v28bFY4Anhw",				// 异步通知地址（加密）
	 *        "successCallbackUrl": "7ccAclTJ4zM7l4K3dw4ysL13EYpcAs0m8YNLdDKFxAKnlxshaf8A+JMjyU30UKG5wZ/jJhmgsZA=",		// 支付成功页面跳转路径（加密）
	 *        "token": "",																			// 用户交易令牌
	 *        "tradeAmount": "7sbSBetqWLo=",															// 交易金额（加密）
	 *        "tradeDescription": "/bxsVjgCeHA=",														// 交易描述（加密）
	 *        "tradeNum": "VpxK4lSGMz1cfMzhUJ6rZXc10CjNAKpt",											// 交易流水号（加密）
	 *        "merchantSign":"LDaHVY4zwEHwuLuHLEBmpfDoMD3jTYIAM/A5OP+1A76IQQz4yHMBxTvAIjFGW"			// 交易信息签名（加密）
	 *    }
	 *}
	 * </pre></code>
	*/
	
	public function GetOrdersAction(){
		$postdata = $this->request->getPost('data');
        
         if($postdata){
             $postdata=json_decode($postdata,2);
             if(!empty($postdata)){
                foreach ($postdata as $key => $value) {
                    $_POST[$key]=$value;
                }
             }
        }
		$order_sn = $this->request->getPost('order_sn', 'string', "");
		//检测参数
		if(!$order_sn){
			$this->getMsg(parent::INFO_ERROR);
		}
		//$order_sn = $this->request->getPost('order_sn', 'string', 'mdg00000083511');
		$type = preg_replace('/\d+/', '', $order_sn);
		switch ($type) {
			case 'mdg':
				$orders = L\Jdpay::mdg_order_info($order_sn);
				break;
			default:
				$orders = L\Jdpay::order_info($order_sn);	
				break;
		}
		if(!is_array($orders)){ 
			$this->getMsg($orders);
		}
		$tradeinfo = array();
		$tradeinfo['currency']		= L\Jdpay::$merchantInfo['currency'];
		$tradeinfo['tradeName']		= '用户订单编号'.$orders['order_sn'];						
		$tradeinfo['tradeTime']		= date('Y-m-d H:i:s', CURTIME);
		$tradeinfo['version']			= L\Jdpay::$merchantInfo['version'];
		$tradeinfo['failCallbackUrl']	= 'http://www.5fengshou.com/api/Jdpay/failCallback';				
		$tradeinfo['merchantNum']	= strval(L\Jdpay::$merchantInfo['merchantNum']);
		$tradeinfo['merchantRemark']	= '';						
		$tradeinfo['notifyUrl']		= 'http://www.5fengshou.com/api/Jdpay/notify'	;
		$tradeinfo['successCallbackUrl'] = 'http://www.5fengshou.com/api/Jdpay/successCallback';
		$tradeinfo['token']			= '';
		$tradeinfo['tradeAmount']		= isset($orders['total']) ? intval($orders['total']*100) : intval($orders['order_amount']*100);
		$tradeinfo['tradeDescription']	= '';
		$tradeinfo['tradeNum']		= $orders['order_sn'];	
		$encrypted				= L\Jdpay::sign($tradeinfo);
		$tradeinfo['merchantSign']	= $encrypted;

		$key						= L\Jdpay::$merchantInfo['desKey'];	

		$tradeinfo["merchantRemark"]	=  L\Jdpay::encrypt($tradeinfo["merchantRemark"],$key);
		$tradeinfo["tradeNum"]		= L\Jdpay::encrypt($tradeinfo["tradeNum"],$key);
		$tradeinfo["tradeName"]		=  L\Jdpay::encrypt($tradeinfo["tradeName"],$key);
		$tradeinfo["tradeDescription"]	=  L\Jdpay::encrypt($tradeinfo["tradeDescription"],$key);
		$tradeinfo["tradeTime"]		= L\Jdpay::encrypt($tradeinfo["tradeTime"],$key);
		$tradeinfo["tradeAmount"]	=  L\Jdpay::encrypt($tradeinfo["tradeAmount"],$key);
		$tradeinfo["currency"]		=  L\Jdpay::encrypt($tradeinfo["currency"],$key);
		$tradeinfo["notifyUrl"]		=  L\Jdpay::encrypt($tradeinfo["notifyUrl"],$key);
		$tradeinfo["successCallbackUrl"] =  L\Jdpay::encrypt($tradeinfo["successCallbackUrl"],$key);
		$tradeinfo["failCallbackUrl"]	= L\Jdpay::encrypt($tradeinfo["failCallbackUrl"],$key);
		$result['errorCode'] = 0;
		$result['data'] = $tradeinfo;
		die(json_encode($result)); 
	}

	/**
	 * 支付成功，京东直接使用回调函数
	 * /api/Jdpay/successCallback
	 * <code><pre>
	 * get 
	 * tradeNum	string   流水号
	 * return 
	 *{
	 *    "data": {
	 *	"order_status": "2"					// 交易成功   （1：失败    ，   2： 成功）
	 *    }
	 *}
	 * </pre></code>
	*/
	public function successCallbackAction(){
		$order_sn		= $this->request->get('tradeNum' , 'string' , '');
		$version		= L\Jdpay::$merchantInfo['version'];
		$merchantNum = L\Jdpay::$merchantInfo['merchantNum'];
		$params		= L\Jdpay::prepareParms($version, $merchantNum, $order_sn);
		// 获取支付状态
		$decrypData	= L\Jdpay::jdpayStatic($params);
		$pay_time		= strtotime($decrypData['tradeDate'].$decrypData['tradeTime']);
		if ($decrypData['tradeStatus'] != 0){ exit; }

		if(!L\Jdpay::success($order_sn, $pay_time)){
			echo "<input type='hidden' name='ync_jdpay_order_status' value='1'>";exit;
		}
		echo "<input type='hidden' name='ync_jdpay_order_status' value='2'>";
	}

	/**
	 * 支付失败，京东直接使用回调函数
	 * /api/Jdpay/failCallback
	 * <code><pre>
	 * get 
	 * tradeNum	string   流水号
	 * return 
	 * {
	 *     "data": {
	 *	"order_status": "1"					// 支付失败	（1：失败    ，   2： 成功）
	 *	}
	 * }
	 * </pre></code>
	*/
	public function failCallbackAction(){
		$order_sn		= $this->request->get('tradeNum' , 'string' , '');
		$version		= L\Jdpay::$merchantInfo['version'];
		$merchantNum = L\Jdpay::$merchantInfo['merchantNum'];
		$params		= L\Jdpay::prepareParms($version, $merchantNum, $order_sn);
		// 获取支付状态
		$decrypData	= L\Jdpay::jdpayStatic($params);
		$pay_time		= strtotime($decrypData['tradeDate'].$decrypData['tradeTime']);
		if ($decrypData['tradeStatus'] !=0 ){
			echo "<input type='hidden' name='ync_jdpay_order_status' value='1'>";
		}else{
			echo "<input type='hidden' name='ync_jdpay_order_status' value='2'>";
		}
	}

	/**
	 * 京东异步回调，京东直接使用回调函数
	 * /api/Jdpay/failCallback
	 * <code><pre>
	 * get 
	 * resp	string   京东加密字符串
	 * return 
	 * {
	 *     "data": {}
	 * }
	 * </pre></code>
	*/
	public function notifyAction(){
		$resp = $_REQUEST ? $_REQUEST['resp'] : '';
		//$sql = "insert into jd_result set code=1000,msg='".$resp."',addtime='".date('Y-m-d H:i:s')."'";
		//$this->db->execute($sql);
		$result = L\Jdpay::execute($resp);
		$obj = simplexml_load_string($result,'SimpleXMLElement', LIBXML_NOCDATA);
		if (is_object($obj)){
			$_data = get_object_vars($obj);
			$data['trade_data']   = get_object_vars($_data['TRADE']);
			$data['return_data']  = get_object_vars($_data['RETURN']);
		}
		$pay_time = strtotime($data['trade_data']['DATE'].$data['trade_data']['TIME']);
		if ($data['return_data']['CODE']==='0000'){
			if ($data['trade_data']['STATUS']==='0'){
				$order_sn = $data['trade_data']['ID'];
				if(L\Jdpay::success($order_sn, $pay_time)){
					die('success');
				}
			}
		}
	}
	public function testAction(){
		
		var_dump(L\Jdpay::success("PF081094568370733",CURTIME));die;
	}
}

?>