<?php
namespace Lib;

class Auth
{
    static
    private $_key = 'riwqe89712hjzxc8970230651mlk';
    /**
     * 解密
     * @param  string  $string 密文
     * @param  string  $key    密钥
     * @param  integer $expiry 有效期
     * @return string
     */

    public static function decode($string = '', $key = '', $expiry = 0)
    {

        if ($key == '') $key = self::$_key;
        return self::authcode($string, 'DECODE', $key, $expiry);
    }
    /**
     * 加密
     * @param  string  $string 明文
     * @param  string  $key    密钥
     * @param  integer $expiry 有效期
     * @return String
     */

    public static function encode($string = '', $key = '', $expiry = 0)
    {

        if ($key == '') $key = self::$_key;
        return self::authcode($string, 'ENCODE', $key, $expiry);
    }
    /**
     * 加解密函数
     * @param  string  $string    明文&&密文
     * @param  string  $operation DECODE表示解密,其它表示加密
     * @param  string  $key       密钥
     * @param  integer $expiry    密文有效期
     * @return string
     */

    public static function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0)
    {
        $ckey_length = 4;
        $key = md5($key ? $key : UC_KEY);
        $keya = md5(substr($key, 0, 16));
        $keyb = md5(substr($key, 16, 16));
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()) , -$ckey_length)) : '';
        $cryptkey = $keya . md5($keya . $keyc);
        $key_length = strlen($cryptkey);
        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb) , 0, 16) . $string;
        $string_length = strlen($string);
        $result = '';
        $box = range(0, 255);
        $rndkey = array();

        for ($i = 0; $i <= 255; $i++)
        {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }

        for ($j = $i = 0; $i < 256; $i++)
        {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }

        for ($a = $j = $i = 0; $i < $string_length; $i++)
        {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result.= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }

        if ($operation == 'DECODE')
        {

            if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb) , 0, 16))
            {
                return substr($result, 26);
            }
            else
            {
                return '';
            }
        }
        else
        {
            return $keyc . str_replace('=', '', base64_encode($result));
        }
    }

    public static function random($length = 6, $numeric = 0)
    {
        $seed = base_convert(md5(microtime() . $_SERVER['DOCUMENT_ROOT']) , 16, $numeric ? 10 : 35);
        $seed = $numeric ? (str_replace('0', '', $seed) . '012340567890') : ($seed . 'zZ' . strtoupper($seed));
        $hash = '';
        $max = strlen($seed) - 1;

        for ($i = 0; $i < $length; $i++)
        {
            $hash.= $seed{mt_rand(0, $max) };
        }
        return $hash;
    }
}
