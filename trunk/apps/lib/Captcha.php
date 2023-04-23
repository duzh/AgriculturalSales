<?php
namespace Lib;

class Captcha
{
    static $_redis = null;
    
    private $_timeOut = 600;
    
    private $_resend = 58;
    
    public function __construct(&$redis = '') 
    {
        self::$_redis = $redis;
    }
    /**
     * 获取验证码
     * @param  string $mobile 手机号
     * @return mix
     */
    
    public function getCode($mobile = '') 
    {
        // 检测是否已发送
        $vcode = self::$_redis->get($mobile);
        $curtime = time();
        
        if ($vcode && $vcode['resend_time'] > $curtime) 
        {
            return false;
        }
        $rand = rand(100000, 999999);
        $data = array(
            'mobile' => $mobile,
            'code' => $rand,
            'resend_time' => $curtime + $this->_resend
        );
        
        if (self::$_redis->set($mobile, $data, $this->_timeOut)) 
        {
            return $rand;
        }
        return false;
    }
    /**
     * 检测验证码
     * @param  string $mobile 手机号
     * @param  string $code   待检测验证码
     * @return mix
     */
    
    public function verifyCode($mobile = '', $code = '', $is_del = false) 
    {
        $vcode = self::$_redis->get($mobile);
//echo $code;
 //       var_dump($vcode);exit;
        if ($vcode && $code == $vcode['code']) 
        {
            
            if ($is_del) $this->delete($mobile);
            return true;
        }
        return false;
    }
    /**
     * 清空
     * @param  integer $mobile 手机号
     * @return mix
     */
    
    public function delete($mobile = 0) 
    {
        return self::$_redis->delete($mobile);
    }
    /**
     * 查询验证码
     * @param  integer $mobile 手机号
     * @return mix
     */
    
    public function can($mobile = 0) 
    {
        $vcode = self::$_redis->get($mobile);
        return $vcode;
    }
}
