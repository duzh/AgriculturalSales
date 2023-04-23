<?php
namespace Lib;

/**
 * 收单网关接口类
 */
class TpayInterface
{
    #测试环境地址
    public $test_url = 'http://tpay.ync365.com/gop/gateway/receiveOrder.do';
    #正式环境地址
    public $formal_url = 'https://pay.ync365.com/gop/gateway/receiveOrder.do';
    #url
    public $url = '';
    #服务
    public $service = '';
    #请求方式 1 post,2 get
    public $request_type = 1;
    #版本
    public $version = '1.0';
    #合作者身份id
    public $partner_id = '200000100496';
    #字符集
    public $_input_charset = 'UTF-8';
    #签名
    public $sign = '';
    #签名方式 RSA MD5
    public $sign_type='RSA';
    #MD5盐值
    public $md5salt = 'lewistest';
    #页面跳转路径 钱包处理完请求后，当前页面自动跳转到商户网站里指定页面的http路径。批量，多商品的接口，无此字段
    public $return_url = '';
    #异步回调地址
    public $sync_url = '';
    #备注
    public $memo = '';
    #错误码数组
    public $code = array();
    #请求类型 1直接请求 2返回地址
    public $re_type = 1;
    #RSA
    public $rsa = null;

    public function __construct($re_type = null,$return_url = null,$publicKeyPath=null,$privateKeyPath=null) {
        #初始化环境url;
//        $this->url = DEV_MODE=='master'?$this->formal_url:$this->test_url; #根据环境判断使用URL
        $this->url = PAY_URL;
        if($return_url) $this->return_url = $return_url;
        if($re_type)$this->re_type = $re_type;
        $publicKeyPath = PUBLIC_PATH.'/pem/tpay_publicKey.pem';
        $privateKeyPath = PUBLIC_PATH.'/pem/tpay_privateKey.pem';
        #RSA 验签
        $publicKey = file_get_contents($publicKeyPath);
        $privateKey = file_get_contents($privateKeyPath);
        $this->rsa = new RSA($publicKey,$privateKey);
    }

    /**
     * 请求方法
     * @param $params 请求参数
     * @return mixed 请求结果
     */
    private function request($params){
        $params['service'] = $this->service;
        $params['version'] = $this->version;
        $params['partner_id'] = $this->partner_id;
        $params['_input_charset'] = $this->_input_charset;
        $params['return_url'] = $this->return_url;
        $params['memo'] = $this->memo;
        ksort($params);
        $str_parsms =  '';
        foreach($params AS $pk => $pv){
            if(!$pv) unset($params[$pk]);
            else $str_parsms .= $pk.'='.$pv.'&';
        }
        $str_parsms = rtrim($str_parsms,'&');
        # md5验证方式 OR rsa验证方式
        $this->sign = ($this->sign_type == 'MD5')?md5($str_parsms.$this->md5salt):$this->rsa->rsaSign($str_parsms);
        if(isset($params['trade_info']))$params['trade_info'] = urlencode($params['trade_info']); #将参数字段进行url编码
        $data = $str_parsms.'&sign='.$this->sign.'&sign_type='.$this->sign_type;
//        echo $this->url.'<br>';
//        print_r($data.'<br>');exit;
        switch ($this->re_type)
        {
            case 1:
                $curl = new Curl();
                $result = ($this->request_type == 1)?$curl->post($this->url,$data):$curl->get($this->url.'?'.$data);
//              echo $this->url.'?'.$data.'<br>';print_r($result);#exit;
                #print_r($params);
//                print_r($result);exit;
                return json_decode($result);
                break;
            case 2:
                return $this->url.'?'.$data;
                break;
            default:
                return false;
        }
    }

    /**
     * 即时到账交易网关接口
     * @param $request_no 商户网站请求号
     * @param $trade_info 交易信息
     * @param null $operator_id 操作员Id
     * @param null $buyer_id 买家ID
     * @param null $buyer_id_type 买家ID类型
     * @param null $buyer_mobile 买家手机号
     * @param null $buyer_ip 用户在商户平台下单时候的ip地址
     */
    public function create_instant_trade($request_no,$trade_info,$operator_id=null,$buyer_id=null,$buyer_id_type=null,$buyer_mobile=null,$buyer_ip=null){
        $this->service='create_instant_trade';
        $params = array('request_no'=>$request_no,
            'trade_info'=>$this->trading_params($trade_info),
            'operator_id'=>$operator_id,
            'buyer_id'=>$buyer_id,
            'buyer_id_type'=>$buyer_id_type,
            'buyer_mobile'=>$buyer_mobile,
            'buyer_ip'=>$buyer_ip);
        return $this->request($params);
    }

    /**
     * 担保交易网关接口
     * @param $request_no  商户网站请求号 钱包合作商户请求号
     * @param $trade_info 交易信息 参见“2.4.2交易参数”,参数拼接规则参见2.3.1
     * @param $operator_id 操作员Id 运营平台操作者登录Id
     * @param $buyer_id 买家ID 平台ID（UID），钱包ID（MEMBER_ID），手机号（MOBILE）,平台登录名（LOGIN_NAME）
     * @param $buyer_id_type  买家ID类型 表明ID的类型，有这几种：UID/MEMBER_ID /MOBILE/LOGIN_NAME/COMPANY_ID
     * @param $buyer_ip 用户在商户平台下单时候的ip地址 用户在商户平台下单的时候的ip地址，公网IP，不是内网IP用于风控校验
     * @param $pay_method 支付方式 取值范围：pos（POS支付）online_bank(网银支付)格式参见2.3.1
     * @param $discount_pay_method 优惠支付方式 该字段为jsonList格式，参见附录4.6
     * @param $go_cashier 是否转收银台标识  取值有以下情况：Y使用(默认值) ,跳转收银台 N不使用 说明：只有需要跳转收银台时，此参数才有效，可默认为Y
     * @param $is_web_access 是否是WEB访问 取值有以下情况：Y使用(默认值) ,WAP端访问 N不使用
     * @return mixed
     */
    public function create_ensure_trade($request_no,$trade_info,$operator_id=null,$buyer_id=null,$buyer_id_type=null,$buyer_ip=null,$pay_method=null,$discount_pay_method=null,$go_cashier=null,$is_web_access=null){
        $this->service = 'create_ensure_trade';
        $this->return_url = $this->return_url.$request_no;
        $params = array('request_no'=>$request_no,
            'trade_info'=>$this->trading_params($trade_info),
            'operator_id'=>$operator_id,
            'buyer_id'=>$buyer_id,
            'buyer_id_type'=>$buyer_id_type,
            'buyer_ip'=>$buyer_ip,
            'pay_method'=> (is_array($pay_method))?$this->pay_method($pay_method):$pay_method,
            'discount_pay_method'=>$discount_pay_method,
            'go_cashier'=>$go_cashier,
            'is_web_access'=>$is_web_access,
        );
        // print_r($params);die;
        return $this->request($params);
    }

    /** 交易查询网关接口
     * @param $outer_trade_no 商户网站唯一订单号 商户提交的订单号，是交易接口中的交易信息的“参数1”
     * @param $trade_type 交易类型 交易的类型类型包括即时到账(INSTANT)，担保交易(ENSURE)，退款(REFUND),提现（WITHDRAWAL）,定金下定（PREPAY）
     * @param $start_time 开始时间 数字串，一共14位,格式为：年[4位]月[2位]日[2位]时[2位]分[2位]秒[2位],不可空<br>20071117020101
     * @param $end_time 结束时间  数字串，一共14位,格式为：年[4位]月[2位]日[2位]时[2位]分[2位]秒[2位],不可空<br>20071117020101
     * @param $inner_trade_no 钱包交易号 字符串 只允许使用字母、数字、- 、_,并以字母或数字开头
     * @return mixed
     */
    public function query_trade($outer_trade_no,$trade_type="ENSURE",$start_time=null,$end_time=null,$inner_trade_no=null){
        $this->service = 'query_trade';
        $params = array(
            'outer_trade_no'=>$outer_trade_no,
            'trade_type'=>$trade_type,
            'start_time'=>$start_time,
            'end_time'=>$end_time,
            'inner_trade_no'=>$inner_trade_no,
        );
        return $this->request($params);
    }

    /** 继续支付/批量支付操作网关接口
     * @param $request_no 商户网站请求号
     * @param $outer_trade_no_list 商户网站唯一订单号集合
     * @param $operator_id 操作员Id
     * @param $pay_method 支付方式
     * @param $buyer_ip 用户在商户平台支付时候的ip地址
     * @param $buyer_id 买家ID
     * @param $buyer_id_type 买家ID类型
     * @param $is_web_access 是否是WEB访问
     * @return mixed
     */
    public function create_pay($request_no,$outer_trade_no_list,$operator_id=null,$pay_method=null,$buyer_ip=null,$buyer_id,$buyer_id_type,$is_web_access=null){
        $this->service = 'create_pay';
        $params = array(
            'request_no'=>$request_no,
            'outer_trade_no_list'=>$outer_trade_no_list,
            'operator_id'=>$operator_id,
            'pay_method'=>$pay_method,
            'buyer_ip'=>$buyer_ip,
            'buyer_id'=>$buyer_id,
            'buyer_id_type'=>$buyer_id_type,
            'is_web_access'=>$is_web_access,
        );#print_r($params);exit;
        return $this->request($params);
    }
    /**
     * 结算（分账）网关接口
     * @param $request_no 商户网站请求号 钱包合作商户请求号
     * @param $outer_trade_no 原始的商户网站唯一订单号 原始的钱包合作商户网站唯一订单号（确保在合作伙伴系统中唯一）。同交易中的一致。
     * @param $royalty_parameters 交易金额分润账号集  参见“2.3.4 royalty_parameters参数说明”。
     * @param $operator_id 操作员Id 结算操作的操作员
     * @return mixed
     */
    public function create_settle($request_no,$outer_trade_no,$royalty_parameters,$operator_id){
        $this->service='create_settle';
        $params = array(
            'request_no'=>$request_no,
            'outer_trade_no'=>$outer_trade_no,
            'royalty_parameters'=>is_array($royalty_parameters)?$this->royalty_method_params($royalty_parameters):$royalty_parameters,
            'operator_id'=>$operator_id,
        );
        return $this->request($params);
    }

    /**
     * 交易取消网关接口
     * @param $request_no 商户网站请求号
     * @param $outer_trade_no 原始的商户网站唯一订单号
     * @param $reason 交易取消原因
     * @return mixed
     */
    public function cancel_trade($request_no,$outer_trade_no,$reason){
        $this->service='cancel_trade';
        $params = array(
            'request_no'=>$request_no,
            'outer_trade_no'=>$outer_trade_no,
            'reason'=>$reason,
        );
        return $this->request($params);
    }
    /**
     * 分润格式化
     * @param $arr
     * 用户id^id类型^101^分润金额|用户id^id类型^101^分润金额
     * @return bool|string
     */
    private function royalty_method_params($arr){
        if(is_array($arr)){
            $str_params = '';
            $new_roy = array();
            #累加值
            foreach($arr AS $aK => $aV){
                foreach($this->royalty_type AS $rtK =>$rtV){
                    if(isset($aV[$rtV])){
                        if(isset($new_roy[$rtV][$aV[$rtV]['uid']]))
                            $new_roy[$rtV][$aV[$rtV]['uid']] += $aV[$rtV]['amount'];
                        else
                            $new_roy[$rtV][$aV[$rtV]['uid']] = $aV[$rtV]['amount'];
                    }
                }
            }
            #组织结果集
            foreach($new_roy AS $nrK => $nrV){
                foreach($nrV AS $nnK => $nnV){
                    $str_params .= $nnK.'^'.'MEMBER_ID^101^'.$nnV.'|';
                }
            }
            return rtrim($str_params,'|');
        }
        else
            return false;
    }

    /**
     *  手机端支付收银台地址获取
     * @param $order 订单数据
     * @param $pay_type 支付方式 支持多种支付 数组格式array(0=>支付方式1,1=>支付方式2) 数组内格式array('ICBC','支付金额','银行代码')@对公/对私：B/C;借记/贷记：DC=借记，CC=贷记，GC=综合
     * @param $operator_id 操作员id
     * @param $return_url 同步回调地址
     * @param $sync_url 异步回调
     * @return mixed
     *
     * 参数样例:
     *  $trade_info = array(
    'order_sn'=> 123435611234132,
    'goods_name' =>'测试商品',
    'price' =>'10.00',
    'quantity' =>100,
    'total' =>'1000.00',
    'suserid' =>'uid001',
    'total' =>'1000.00',
    );
    $pay_type = array(
    'TESTBANK','1000.00','C,DC'
    );
     *
     */
    public static function mobile_create_trede($order,$pay_type,$buyer_id,$operator_id,$return_url,$sync_url){
        $_this = new self();
        $_this->return_url = $return_url;
        $_this->sync_url = $sync_url;
        $trade_info = array(
            $order['order_sn'],#订单号
            $order['goods_name'], #商品名称
            $order['price'], #商品单价
            $order['quantity'], #商品数量
            $order['total'], #商品总价
            $order['total'], #担保金额
            $order['suserid'], #卖家标识MEMBER_ID UID
            'MEMBER_ID', #卖家标识类型
            $order['order_sn'], #业务号
            $order['goods_name'],#商品描述
            '',#商品展示URL
            '',#使用订金金额
            '',#订金下订的商户网站唯一订单号
            '',#商户订单提交时间
            $_this->sync_url,#服务器异步通知页面路径
            '',#支付过期时间
        );

        $clientip = Func::getAddressIp();
        $res = $_this->create_ensure_trade($order['order_sn'],$trade_info,$operator_id,$buyer_id,'MEMBER_ID',$clientip,$pay_type,null,'N','N');
        #print_r($res);
        return $res;
    }


    /**
     * 获取错误代码
     * @param $code
     * @return mixed
     */
    public function getCode($code){
        return $this->code[$code];
    }

    /**
     * 支付方式格式化
     * @param $method
     * @return bool|string
     */
    private function pay_method($method){
        if(is_array($method)){
            $str_params = '';
            if(is_array($method[0]))
            {
                $str_params = '';
                foreach($method as $pk => $pv){
                    $str_params .= $this->method_params($pv);
                    $str_params .= (isset($params[$pk+1]))?"|":'';
                }
            }
            else{
                $str_params .= $this->method_params($method);
            }

            return $str_params;
        }
        return false;
    }

    /**
     * @param $arr online_bank (网银支付)
    如果不设置，默认识别为余额支付，跳转收银台。
    格式(若有多个支付方式则以“|”分隔开)：支付方式1^金额1^备注1|支付方式2^金额2^备注2
    每个参数的前面，需用“:”拼接上该参数的长度，参数为空时需拼接^0:。
    (银行代码；对公/对私：B/C;借记/贷记：DC=借记，CC=贷记，GC=综合)
    必须注意区分大小写。
     * @return bool|string
     */
    private function method_params($arr){
        if(is_array($arr)){
            $str_params = '11:online_bank^';
            $arr[2] = $arr[0].','.$arr[2];
            unset($arr[0]);
            ksort($arr);
            foreach($arr AS $k =>$v){
                if($v){
                    #$str_params .= strlen($v).':'.$v;
                    $str_params .= mb_strlen($v,'utf8').':'.$v;
                    $str_params .= (isset($arr[$k+1]))?"^":'';
                }
                else{
//                    $str_params .= '0:';
//                    $str_params .= (isset($arr[$k+1]))?"^":'';
                }
            }
            rtrim($str_params,'^');
            return $str_params;
        }
        else
            return false;
    }
    /**
     * 参数处理 2.3.2交易参数
     * @param array $params 单个商品传入一维数组 多个商品传入二维数组 数量不超过5个
     * @return bool|string
     */
    private function trading_params($params=array()){
        if($params){
            $str_params = '';
            if(is_array($params[0]))
            {
                $str_params = '';
                foreach($params as $pk => $pv){
                    $str_params .= $this->params_handle($pv);
                    $str_params .= (isset($params[$pk+1]))?"$":'';
                }
            }
            else{
                $str_params .= $this->params_handle($params);
            }

            return $str_params;
        }
        else{
            return false;
        }
    }

    /**
     * 参数处理函数
     * @param $arr
     * @return bool|string
     */
    private function params_handle($arr){
        if(is_array($arr)){
            $str_params = '';
            foreach($arr AS $k =>$v){
                if($v){
                    #$str_params .= strlen($v).':'.$v;
                    $str_params .= mb_strlen($v,'utf8').':'.$v;
                    $str_params .= (isset($arr[$k+1]))?"~":'';
                }
                else{
                    $str_params .= '0:';
                    $str_params .= (isset($arr[$k+1]))?"~":'';
                }
            }
            rtrim($str_params,'~');
            return $str_params;
        }
        else
            return false;
    }
}


?>