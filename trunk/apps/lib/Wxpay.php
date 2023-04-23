<?php
namespace Lib;

class Wxpay
{
    
    private $unifiedorder_url = WEIN_XIN. 'pay/unifiedorder';
    
    private $appid = 'wxd96c679b5f97b8b1';

    // private $key = '6a6b280f215a7738d5049ad47ff7558d';
    private $key = '73d9b2a1b669415cbf64fdc81fc08ae4';

    private $appsecret = '584555a5e1287097f8bd3aa1d90d15c0';

    private $mch_id = '1244306602';

    private $notify_url = ORDER_CALLBACK_URL.'api/wxpay/callback';
   // private $notify_url = 'http://mdgdev.ync365.com/api/wxpay/callback';
    
     /** @var string 订单状态查询接口地址 */
    private $queryUrl = WEIN_XIN."pay/orderquery";    
    /**
     * 
     * 查询订单
     * @param string $order_sn
     * @throws Exception
     * @return SUCCESS—支付成功
     *         REFUND—转入退款
     *         NOTPAY—未支付
     *         CLOSED—已关闭
     *         REVOKED—已撤销
     *         USERPAYING--用户支付中
     *         PAYERROR--支付失败(其他原因，如银行返回失败)
     */
    public function orderQuery($order_sn)
    {
        //检测必填参数
        if(!$order_sn) {
            throw new \Exception("PAYERROR");
        }
        $info = array();
        $info['out_trade_no'] = $order_sn;
        $info['appid'] = $this->appid;
        $info['mch_id'] = $this->mch_id;
        $info['nonce_str'] = $this->getNonceStr();

        try {
            //签名
            $info['sign'] = $this->MakeSign($info);
            $xml = $this->ToXml($info);
            $curl = new Curl();
            $xml = $curl->https($this->queryUrl, $xml);
            $tmp = $this->checkSign($xml);
          
            $rs = 'PAYERROR';
            if(isset($tmp['return_code']) && $tmp['return_code'] == 'SUCCESS') {
                $rs = isset($tmp['trade_state']) ? $tmp['trade_state'] : 'PAYERROR';
            }
        } catch (\Exception $e) {
            $rs = 'PAYERROR';
        }
        return $rs;
    }
    public function payID($data=array(), $type='APP') 
    {

        $rs = array('status'=>0);
        $info = array();
        $info['out_trade_no'] = $data['order_sn'];
        $info['body'] = $data['body'];
        $info['total_fee'] = $data['order_amount']*100;
        $info['trade_type'] = $type;
        $info['body'] = $data['body'];
        $info['spbill_create_ip'] = $data['ip'];

        switch (strtoupper($type)) {
            case 'JSAPI':
                $inf['openid'] = $data['openid'];
                break;
            case 'NATIVE':
                $inf['product_id'] = $data['product_id'];
                break;
        }

        $info['notify_url'] = $this->notify_url;
        $info['appid'] = $this->appid;
        $info['mch_id'] = $this->mch_id;
        // $info['nonce_str'] = 'wx831c570fcfd84ee7';
        $info['nonce_str'] = $this->getNonceStr();

        try {
            //签名
            $info['sign'] = $this->MakeSign($info);
            $xml = $this->ToXml($info);
            $curl = new Curl();
            $xml = $curl->https($this->unifiedorder_url, $xml);
            $tmp = $this->checkSign($xml);
            $rs['data'] = $this->payInfo($tmp['prepay_id']);
        } catch (\Exception $e) {
            $rs['status'] = $e->getmessage();
        }
        return $rs;
    }

    public function payInfo($prepayId='') {
        $time = CURTIME;
        $data = array(
                    'appid' => $this->appid,
                    'partnerid' => $this->mch_id,
                    'prepayid' => $prepayId,
                    'package' => 'Sign=WXPay',
                    'noncestr' => $this->getNonceStr(),
                    'timestamp' =>"{$time}",
                );
        $data['sign'] = $this->MakeSign($data);
        return $data;
    }

    /**
     * 检测签名
     * @param  string $xml xml数据
     * @return array
     */
    public function checkSign($xml) {
        $data = $this->xmlToArray($xml);
        if(!isset($data['return_code']) || strtoupper($data['return_code']) != 'SUCCESS' || strtoupper($data['result_code']) != 'SUCCESS') {
            throw new \Exception(19998);
        }
        $sign = $this->MakeSign($data);
        // echo $sign;exit;
        if(isset($data['sign']) && $data['sign'] == $sign){
            return $data;
        }
        throw new \Exception(19997);
        return $data;
    }

    /**
     * 将xml转为array
     * @param string $xml
     * @throws Exception
     */
    public function xmlToArray($xml)
    {   
        if(!$xml){
            throw new \Exception(19998);
        }
        //将XML转为array 
        $data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);        
        return $data;
    }


    /**
     * 输出xml字符
     * @throws Exception
    **/
    public function ToXml($data=array())
    {
        if(!is_array($data) || count($data) <= 0)
        {
            throw new \Exception(19999);
        }
        
        $xml = "<xml>";
        foreach ($data as $key=>$val)
        {
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml; 
    }

    /**
     * 生成签名
     * @return 签名，本函数不覆盖sign成员变量，如要设置签名需要调用SetSign方法赋值
     */
    public function MakeSign($data=array())
    {
        //签名步骤一：按字典序排序参数
        ksort($data);
        $string = $this->ToUrlParams($data);
        //签名步骤二：在string后加入KEY
        $string = $string . "&key=".$this->key;
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }

    /**
     * 格式化参数格式化成url参数
     */
    public function ToUrlParams($data=array())
    {
        $buff = "";
        foreach ($data as $k => $v)
        {
            if($k != "sign" && $v != "" && !is_array($v)){
                $buff .= $k . "=" . $v . "&";
            }
        }
        
        $buff = trim($buff, "&");
        return $buff;
    }
        
    /**
     * 
     * 产生随机字符串，不长于32位
     * @param int $length
     * @return 产生的随机字符串
     */
    public function getNonceStr($length = 32) 
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";  
        $str ="";
        for ( $i = 0; $i < $length; $i++ )  {  
            $str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);  
        } 
        return $str;
    }

}
