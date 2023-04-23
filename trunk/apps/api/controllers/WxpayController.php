<?php
namespace Mdg\Api\Controllers;

use Lib as L;

class WxpayController extends ControllerBase
{
	/**
	 * http://mdgdev.ync365.com/api/wxpay/index
	 * 获取微信支付信息
	 * @return string 
	 * <code><pre>
	 * post
	 * order_sn   string   订单编号
	 * return
	 * {
     * 	   "status": 0,
	 *     "data": {
	 *         "appId": "wx831c570fcfd84ee7",
	 *         "partnerId": "1241375802",
	 *         "prepayId": "wx2015052217175579b3845e390748060198",
	 *         "package": "Sign=WXPay",
	 *         "noncestr": "i8l2x1qhlpldsses3y24yo3zox3vc31d",
	 *         "timeStamp": "1432286227",
	 *         "sign": "125BCD7DC9ABCB3E667DECDE4F841690"
	 *     }
	 * }
	 * </pre></code>
	 */
	public function indexAction() {
		$postdata = $this->request->getPost('data');
        
        if($postdata){
             $postdata=json_decode($postdata,2);
             if(!empty($postdata)){
                foreach ($postdata as $key => $value) {
                    $_POST[$key]=$value;
                }
             }
        }
		$order_sn = $this->request->getPost('order_sn', 'string', '');
		$type = preg_replace('/\d+/', '', $order_sn);

		switch ($type) {
			case 'mdg':
				$rs = L\Mdg::wxPayID($order_sn);
				break;
			default:
				$rs = L\Pop::wxPayID($order_sn);
				break;
		}
		if(is_array($rs)){
			$rs['msg'] = $rs['status'] ? '' : '';
			die(json_encode($rs));
		}else{
			$this->getJson($rs);
		}

		
	}

	/** 微信支付回调 */
	public function callbackAction() {
	
		$res = '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
		//$xml = $this->getXml();
     
		$xml = file_get_contents('php://input', 'r');
		file_put_contents(PUBLIC_PATH.'/xmlxml.txt',$xml,FILE_APPEND); 
		try {
			$wx = new L\Wxpay();
			$xml = $wx->checkSign($xml);

		} catch (\Exception $e) {
			die();
		}
	
		// var_dump($xml);exit;
		$type = preg_replace('/\d+/', '', $xml['out_trade_no']);
		switch ($type) {
			case 'mdg':
				$rs = L\Mdg::payOrder($xml['out_trade_no'], $xml['transaction_id'], $xml['total_fee']);
				break;
				
			case 'PF':
					$rs = false;
					$data['order_sn'] = $order_sn;
					$data['pay_type'] = 'WX';
					$c = L\Curl::getCurl();

					$url = SHOPS_URL . 'station/pay/callback';
					$json = $c->post($url, $data);
					$json  = json_encode($json,2);
					if(isset($json['status']) && !$json['status']) {
						$rs = true;
					}
				break;
			default:
				$rs = L\Pop::payOrder($xml['out_trade_no'], $xml['transaction_id'], $xml['total_fee']);
				break;
		}

		$rs ? die($res) : die();
	}

	public function getXml() {
		return '<xml>
				   <appid><![CDATA[wx2421b1c4370ec43b]]></appid>
				   <attach><![CDATA[支付测试]]></attach>
				   <bank_type><![CDATA[CFT]]></bank_type>
				   <fee_type><![CDATA[CNY]]></fee_type>
				   <is_subscribe><![CDATA[Y]]></is_subscribe>
				   <mch_id><![CDATA[10000100]]></mch_id>
				   <nonce_str><![CDATA[5d2b6c2a8db53831f7eda20af46e531c]]></nonce_str>
				   <openid><![CDATA[oUpF8uMEb4qRXf22hE3X68TekukE]]></openid>
				   <out_trade_no><![CDATA[051668957532220]]></out_trade_no>
				   <result_code><![CDATA[SUCCESS]]></result_code>
				   <return_code><![CDATA[SUCCESS]]></return_code>
				   <sign><![CDATA[AE9908F42A08D327132C0DB2B534A2D7]]></sign>
				   <sub_mch_id><![CDATA[10000100]]></sub_mch_id>
				   <time_end><![CDATA[20140903131540]]></time_end>
				   <total_fee>999900</total_fee>
				   <trade_type><![CDATA[JSAPI]]></trade_type>
				   <transaction_id><![CDATA[1004400740201409030005092168]]></transaction_id>
				</xml>';
	}
	/**
	 * http://www.5fengshou.com/api/wxpay/orderquery
	 * 查询订单是否支付
	 * @return string 
	 * <code><pre>
	 * post
	 * order_sn   string   订单编号
	 * </pre></code>
	 * return 
     * {
     *     "code": 20005,
     *     "msg": "订单支付失败"
     * }
	 */
    public function orderqueryAction(){
        
        $postdata = $this->request->getPost('data');
        
        if($postdata){
             $postdata=json_decode($postdata,2);
             if(!empty($postdata)){
                foreach ($postdata as $key => $value) {
                    $_POST[$key]=$value;
                }
             }
        }
    	$order_sn = $this->request->getPost('order_sn', 'string', '');
    	if(!$order_sn){
    		    $msg["status"]=parent::WXNOTPAY;
        		$msg["msg"]="订单异常";
        		die(json_encode($msg));
    	}
		$type = preg_replace('/\d+/', '', $order_sn);
		$rs='';
		$msg=array();
		switch($type) {
			case 'mdg':
				$rs = L\Mdg::orderQuery($order_sn);
				break;
			default:
				$rs = L\Pop::orderQuery($order_sn);
				break;
		}

        switch($rs) {
        	case 'SUCCESS':
        		$msg["status"]=parent::WXSUCCESS;
        		$msg["msg"]="支付成功";
        		break;
        	case 'REFUND':
        		$msg["status"]=parent::WXREFUND;
        		$msg["msg"]="转入退款";
        		break;
            case 'NOTPAY':
        		$msg["status"]=parent::WXNOTPAY;
        		$msg["msg"]="支付尚未支付";
        		break;
        	case 'CLOSED':
        		$msg["status"]=parent::WXCLOSED;
        		$msg["msg"]="支付关闭";
        		break;
        	case 'REVOKED':
        		$msg["status"]=parent::WXREVOKED;
        		$msg["msg"]="支付已撤销";
        		break;
        	case 'USERPAYING':
        		$msg["status"]=parent::WXUSERPAYING;
        		$msg["msg"]="用户支付中";
        		break;
            case 'PAYERROR':
        		$msg["status"]=parent::WXPAYERROR;
        		$msg["msg"]="订单支付失败";
        		break;
        	default:
        		$msg["status"]=parent::WXPAYERROR;
        		$msg["msg"]="订单支付失败";
        		break;
        }
    
        die(json_encode($msg));
    }
	public function testAction(){
		//070742328179914
		echo L\Pop::payOrder("07152517797782","wx20150707192934a9a8a48a580851917946","999900");die;
	}

}