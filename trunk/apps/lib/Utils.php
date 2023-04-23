<?php
namespace Lib;

class Utils
{
    /**
     * 获取客户端IP
     * @return String
     */
    static public function getIP()
    {

        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
        {
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        }
        elseif (isset($_SERVER["HTTP_CLIENT_IP"]))
        {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        }
        elseif (isset($_SERVER["REMOTE_ADDR"]))
        {
            $ip = $_SERVER["REMOTE_ADDR"];
        }
        elseif (getenv("HTTP_X_FORWARDED_FOR"))
        {
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        }
        elseif (getenv("HTTP_CLIENT_IP"))
        {
            $ip = getenv("HTTP_CLIENT_IP");
        }
        elseif (getenv("REMOTE_ADDR"))
        {
            $ip = getenv("REMOTE_ADDR"); 
        }
        else
        {
            $ip = "Unknown";
        }
        return $ip;
    }

    static function replaceareas($areas){
        if(!is_array($areas)) $area = explode(',', $areas);
        $areas = array_unique($areas);
        $area = array('市辖区','县','省直辖县级行政区划','自治区直辖县级行政区');
        $areas = array_replace($area, $areas);
        return implode('-',$areas);
    }


    static function getC($area){
        list($c,) = explode(',', $area);
        return $c; 
    }

    static public function c_strcut($string='', $length=16, $ext="...", $chart='utf8') {
        if(mb_strlen($string, $chart) <= $length) return $string;

        return mb_substr($string, 0, $length, $chart).$ext;
    }
}
