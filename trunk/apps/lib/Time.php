<?php
/**
 * @package     Mdg
 * @subpackage  Lib
 * @author      Funky <70793999@qq.com>
 * @copyright   2014 YNC365
 * @version     @@PACKAGE_VERSION@@
 */

namespace Lib;
/**
 * 时间计算
 */
class Time
{
    function __construct($createtime, $gettime)
    {
        $this->createtime = $createtime;
        $this->gettime = $gettime;
    }

    private function checkTime() {
        return $this->createtime < $this->gettime;
    }

    private function getSeconds()
    {
        return $this->createtime - $this->gettime;
    }

    private function getMinutes()
    {
        return ($this->createtime - $this->gettime) / (60);
    }

    private function getHours()
    {
        return ($this->createtime - $this->gettime) / (60 * 60);
    }

    private function getDay()
    {
        return ($this->createtime - $this->gettime) / (60 * 60 * 24);
    }

    private function getMonth()
    {
        return ($this->createtime - $this->gettime) / (60 * 60 * 24 * 30);
    }

    private function getYear()
    {
        return ($this->createtime - $this->gettime) / (60 * 60 * 24 * 30 * 12);
    }

    public function transform()
    {
        if ($this->getYear() > 1)
        {

            if ($this->getYear() > 2)
            {
                return date("Y-m-d", $this->gettime);
            }
            return intval($this->getYear()) . " 年前";
        }

        if ($this->getMonth() > 1)
        {
            return intval($this->getMonth()) . " 月前";
        }

        if ($this->getDay() > 1)
        {
            return intval($this->getDay()) . " 天前";
        }

        if ($this->getHours() > 1)
        {
            return intval($this->getHours()) . " 小时前";
        }

        if ($this->getMinutes() > 1)
        {
            return intval($this->getMinutes()) . " 分钟前";
        }

        if ($this->getSeconds() > 1)
        {
            return intval($this->getSeconds() - 1) . " 秒前";
        }
    }


    static function timestamp($str){

        $flag_time = mb_substr($str, -3, 1, 'utf8');
        
        if($flag_time=='-'){

            $cur = strtotime($str);

        }else{

        $flag = mb_substr($str, -2, 1, 'utf8');
        $time = time();
        $num = intval($str);
        switch ($flag) {
            case '秒':
                $cur = $time - $num;
                break;
            case '钟':
                $cur = $time - $num * 60;
                break;
            case '时':
                $cur = $time - $num * 3600;
                break;
            case '天':
                $cur = $time - $num * 24 * 3600;
                break;
            
            default:
                $cur = $time;
                break;
            }
        }

            return $cur;
    } 

}
