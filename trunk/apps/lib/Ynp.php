<?php
namespace Lib;
/**
* 云农宝服务
*/
class Ynp
{
	public $client = null;
    /*  农业银行支付方式 */
    CONST  PAYTYPE_ABC = 2 ;
    /* 云农宝支付 */
    CONST  PAYTYPE_YNP = 1;

    
    /* 丰收汇支付来源 */
    CONST MDG_SOURCE = 2;
    
	public function __construct($ynp=null) {
		$this->client = $ynp;
	}

    public function noticeOpsTransactionAmount($orderNo, $orderAmount, $sign, $source=2 ) {
        $data = $this->client->noticeOpsTransactionAmount($orderNo, $orderAmount, $source, $sign);
        return $data->value;
    }
	/**
     * 支付成功通知云农宝接口
     * @param  [type] $orderNo 订单号
     * @param  [type] $status  状态
     * @param  [type] $sign    签名串
     * @return boolean
     */
    public function noticeRechargeOpsTransactionStatus($orderNo='', $status='', $amount=0, $sign='', $payment=2, $source=2) {
        
        $data = $this->client->noticeRechargeOpsTransactionStatus($orderNo, $status, $amount, $payment, $source, $sign);
        return $data->value;
    }
    /**
     *  取消订单通知云农宝
     * @param  string  $orderNum 订单号
     * @param  integer $source   来源 2 丰收汇
     * @param  string  $sign     签名穿
     * @return boolean
     */
    public function noticeOpsTransactionClose ($orderNum='', $source=2,$sign='') {
        $data = $this->client->noticeOpsTransactionClose($orderNum, $source , $sign);
        return $data->value;
    }   
    /**
     * 订单确认收货通知云农宝
     * @param  string  $orderNo 订单号
     * @param  integer $status  订单状态
     * @param  integer $source  来源
     * @param  string  $sign    签名穿
     * @return boolean
     */
    public function noticeOpsTransactionStatus($orderNo='', $sign='', $payType=0, $status=3, $source=2 ) {
        $data = $this->client->noticeOpsTransactionStatus($orderNo, $status, $source, $payType, $sign);
        return $data->value;

    }

    /**
     * 根据手机号获取用户余额
     * @param  [type] $userPhone 用户手机号
     * @return 
     */
    public function getAmountByUserPhone($userPhone) 
    {
        $data =  $this->client->getAmountByUserPhone((string)$userPhone);
        return array($data->account , $data->freeezeAccount);
        
    }
    /**
     * 同步用户数据
     * @param  [type] $userId            用户id
     * @param  [type] $userPhone         用户手机号
     * @param  [type] $userEmail         邮箱
     * @param  [type] $userLoginPassword 登录密码
     * @param  [type] $regDate           注册日期
     * @param  [type] $realName          真是姓名
     * @param  [type] $userPayPassword   支付密码
     * @param  [type] $userSafeQuestion  问题
     * @param  [type] $userSafeAnswer    答案
     * @param  [type] $idcardNum         身份证
     * @param  [type] $amount            余额
     * @return boolean
     */
    public function userDataSync($userId = '',$userPhone = '',$userEmail = '',$userLoginPassword = '',$regDate = '',$realName = '',$userPayPassword = '',$userSafeQuestion = '',$userSafeAnswer = '',$idcardNum = '',$amount = '') 
    {   
        $regDate = date('YmdHis', $regDate + 28800);
        
        return $this->client->userDataSync((string)$userId, (string)$userPhone, (string)$userEmail, (string)$userLoginPassword, 
            (string)$regDate, (string)$realName, (string)$userPayPassword, (string)$userSafeQuestion, (string)$userSafeAnswer, (string)$idcardNum, (string)$amount);
    }

    /**
     * 生成token
     * @param  [type] $ip        本地ip
     * @param  [type] $userPhone 用户手机号
     * @param  [type] $sign      签名穿
     * @return string
     */
    public function createBindToken($clientip, $mobile, $sign) 
    {
        $data =  $this->client->createBindToken((string)$clientip, (string)$mobile, (string)$sign );
        return $data->value;
    }
    /**
     * 检测用户是否存在
     * @param  string $userPhone 用户手机号
     * @return boolean
     */
    
    public function checkPhoneExist($userPhone = '') 
    {
        $data =  $this->client->checkPhoneExist((string)$userPhone);
        return $data->value;
    }


}


?>