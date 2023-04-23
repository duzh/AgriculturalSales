<?php
namespace Lib;
/**
 * 密钥文件的路径
 */
class RSA {
    #公有密匙 路径
    public $public_key_path = 'ynb_publicKey.pem';
    #私有密匙 路径
    public $prevate_key_path = 'ynb_privateKey.pem';
    #公有密匙
    public $pu_key;
    #私有密匙
    public $pi_key;

    public function __construct($public_key_path = null,$prevate_key_path = null) {
        if($public_key_path)
            $this->pu_key = openssl_pkey_get_public($public_key_path);//这个函数可用来判断公钥是否是可用的111
        if($prevate_key_path)
            $this->pi_key =  openssl_pkey_get_private($prevate_key_path);//这个函数可用来判断私钥是否是可用的，可用返回资源id Resource id

    }

    /**
     * RSA加密方法
     * @param $data
     * @return string
     */
    public function encrypt($data){
        openssl_public_encrypt($data,$encrypted,$this->pu_key);
        return $encrypted;
    }
    /**
     * RSA解密方法
     * @param $data
     * @return string
     */
    public function decrypt($data){
        openssl_private_decrypt($data,$decrypted,$this->pi_key);
        return $decrypted;
    }

    /**
     * 分段加密方法
     * @param $hexMacDataSource
     * @param int $num
     * @return array
     */
    public function splitData($hexMacDataSource, $num = 16)
    {
//        $len = 0;
//        $len = strlen($hexMacDataSource) / $num;
//        $ds = array();
//
//        for ($i = 0; $i < $len; $i++) {
//            $ds[] = substr($hexMacDataSource, 0, $num);
//            $hexMacDataSource = substr($hexMacDataSource, $num);
//        }
        $ds = str_split($hexMacDataSource,$num);
        return $ds;
    }
    /**
     * 签名(维金)
     */
    public function rsaSign($data){
        openssl_sign($data, $sign, $this->pi_key, OPENSSL_ALGO_SHA1);
        $sign = base64_encode($sign);
//        print_r($data);
//        print_r($this->rsaVerify($data, $sign));exit;
//        echo $this->rsaVerify($data, $sign) ? 'T' : 'F','<br/>';
        return $sign;
    }

    /**
     * 验签(维金)
     */
    public function rsaVerify($data, $sign){
        $sign = base64_decode($sign);
        $result = openssl_verify($data, $sign, $this->pu_key, OPENSSL_ALGO_SHA1) === 1;
        return $result;
    }


}