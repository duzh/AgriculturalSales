<?php
namespace Lib;
include_once ('pagesV4.0/config.php');
require_once ('pagesV4.0/mer2Plat.php');

class UmpayClass
{
    
    private $service = 'pay_req_split_direct';
    
    private $charset = 'UTF-8';
    
    private $mer_id = 6290;
    
    private $sign_type = 'RSA';
    
    private $res_format = 'HTML';
    
    private $version = '4.0';
    
    private $goods_id = 666666; //自定义商品货号
    
    private $goods_inf = '云农场商品'; //商品描述信息
    
    private $media_id = '13241754050'; //媒介标示
    
    private $media_type = 'MOBILE'; //媒介类型
    
    private $mer_date = '';
    
    private $amt_type = 'RMB';
    
    private $pay_type = 'B2CBANK';
    /**
     * 返回url地址
     * @param  [type] $ret_url    通知地址
     * @param  [type] $notify_url 返回
     * @param  [type] $order_id   订单号
     * @param  [type] $amount     金额
     * @param  [type] $gate_id    银行类型
     * @return [type]
     */
    function get_url($ret_url, $notify_url, $order_id, $amount, $gate_id) 
    {
        $map = new \HashMap();
        $this->mer_date = date("Ymd");
        $map->put("service", $this->service);
        $map->put("charset", $this->charset);
        $map->put("mer_id", $this->mer_id);
        $map->put("sign_type", $this->sign_type);
        $map->put("ret_url", $ret_url);
        $map->put("notify_url", $notify_url);
        $map->put("goods_id", $this->goods_id);
        $map->put("goods_inf", $this->goods_inf);
        $map->put("media_id", $this->media_id);
        $map->put("media_type", $this->media_type);
        $map->put("order_id", $order_id);
        $map->put("mer_date", $this->mer_date);
        $map->put("res_format", $this->res_format);
        $map->put("amount", $amount);
        $map->put("amt_type", $this->amt_type);
        $map->put("pay_type", $this->pay_type);
        $map->put("gate_id", $gate_id);
        $map->put("version", $this->version);
        $reqData = \MerToPlat::requestTransactionsByGet($map); //这个是重要的
        $sign = $reqData->getSign(); //这个是为了在本DEMO中显示签名结果。
        $plain = $reqData->getPlain(); //这个是为了在本DEMO中显示签名原串
        $url = $reqData->getUrl();
        return $url;
    }
    //## 丰收汇与云农宝对接
    
    private $getUrl = YNP_URL."pay/orderRequest.htm?"; //跳转地址
    
    private $return_url =CALLBACK_URL. "callback/"; //回调通知地址

    protected $data = array();
    
    protected $key = "sfVC9u6256GM";
    
    public $source = 2;
    /**
     * 创建云农宝链接请求
     * @param  array  $data array('order_num'=>, 'order_date'=>'','order_amount'=>,)
     * @return [type]       [description]
     */
    
    public function createData($data = array()) 
    {
        if(!isset($data['orderNum']) || !isset($data['orderName'])  || !isset($data['orderAmount']) || !isset($data['orderDate']) || !isset($data['payer'])  || !isset($data['receipt']) ) {
            return false;
        }

        $this->data = $data;
        $sign = $this->getYnpSign();
        $string = '';
        $this->data['sign'] = $this->getYnpSign();
        $string = http_build_query($this->data);
        return $this->getUrl . $string;
    }
    /**
     * 获取规定加密key
     * @return string
     */
    
    public function getYncMd5Key() 
    {
        return $this->key;
    }
    /**
     * 获取签名
     * @return string
     */
    
    public function getYnpSign() 
    {
        return md5(md5($this->data['orderNum'] . $this->data['orderName'] . $this->data['orderAmount'] . $this->data['orderDate'] . $this->data['source'] . $this->data['payer'] . $this->data['receipt']) . $this->key);
    }
    /**
     * 地址验证回调
     * @return boolean
     */
    
    public function callback($data = array()) 
    {
        if(!isset($data['orderNum']) || !isset($data['orderName'])  || !isset($data['orderAmount']) || !isset($data['orderDate']) || !isset($data['payer'])  || !isset($data['receipt']) ) {
            return false;
        }
        
        $this->data = $data;
        $sign = $this->getYnpSign();
        
        if ($sign == $data['sign']) 
        {
            return 1;
        }
        return 0;
    }
    /**
     * 取消订单回调验证sign
     * @param  array  $data
     * @return boolean
     */
    
    public function ordercelCallback($data = array()) 
    {
        $orderNum = isset($data['orderNum']) ? $data['orderNum'] : '';
        $orderAmount = isset($data['orderAmount']) ? $data['orderAmount'] : 0;
        $orderDate = isset($data['orderDate']) ? $data['orderDate'] : '';
        $orderName = isset($data['orderName']) ? $data['orderName'] : '';
        
        if (!isset($data['sign']) || !$data['sign']) return 0;
        $sign = md5(md5($orderNum . $orderAmount . $orderDate . $orderName) . $this->getYncMd5Key());
        
        if ($data['sign'] == $sign) return 1;
        return 0;
    }
}
?>