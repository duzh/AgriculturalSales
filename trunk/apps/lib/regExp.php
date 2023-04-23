<?php


class regExp
{
    //去除字符串空格
    static function strTrim($str)
    {
        return preg_replace("/\s/","",$str);
    }

    //验证用户名
    static function title($str)
    {
        $str=self::strTrim($str);
        if($str=="")
        {
            return false;
        }else{
            return true;
        }
    }

    //验证密码长度
    static function passWord($min,$max,$str)
    {
        $str=self::strTrim($str);
        if(strlen($str)>=$min && strlen($str)<=$max)
        {
            return true;
        }else{
            return false;
        }
    }

    //验证Email
    static function Email($str)
    {
        $str=self::strTrim($str);
        
        if(preg_match("/^([a-z0-9_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.){1,2}[a-z]{2,4}$/i",$str))
        {
            return true;
        }else{
            return false;
        }
        
    }

    //验证身份证(中国)
    static function idCard($str)
    {
        $str=self::strTrim($str);
        if(preg_match("/^([0-9]{15}|[0-9]{17}[0-9a-z])$/i",$str))
        {
            return true;
        }else{
            return false;
        }
    }

    //验证座机电话
    static function Phone($type,$str)
    {
        $str=self::strTrim($str);
        switch($type)
        {
            case "CHN":
                if(preg_match("/^([0-9]{3}|0[0-9]{3})-[0-9]{7,8}$/",$str))
                {
                    return true;
                }else{
                    return false;
                }
                break;
            case "INT":
                if(preg_match("/^[0-9]{4}-([0-9]{3}|0[0-9]{3})-[0-9]{7,8}$/",$str))
                {
                    return true;
                }else{
                    return false;
                }
                break;
        }
    }
}

$str="008-010-2711204";
if(regExp::Phone("INT",$str))
{
    echo "ok";
}else{
    echo "no";
}
?> 