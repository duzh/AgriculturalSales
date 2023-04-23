<?php
namespace Lib;

class Func
{   
    public static function sub_monery($num=0) {
         return substr(sprintf("%.3f", $num),0,-1); // 0.021 
    }
    
    /**
     * 获取星期
     * @return [type] [description]
     */
    public static function getN() {
        $n = date('N');
        switch ($n) {
            case 1:
                $n = '一';
                break;
            case 2:
                $n = '二';
                break;
            case 3:
                $n = '三';
                break;
            case 4:
                $n = '四';
                break;
            case 5:
                $n = '五';
                break;
            case 6:
                $n = '六';
                break;
            case 7:
                $n = '日';
                break;
        }
        return '星期'.$n;
    }
    /**
     * 获取时间欢迎语
     * @return [type] [description]
     */
    public static function getTimeName() {
        $h=date('G');
        if ($h<11) $time= '早上好';
        else if ($h<13) $time= '中午好';
        else if ($h<17) $time= '下午好';
        else $time= '晚上好';
        return $time;
    }
    /*** 求最大公约数 ***/
    public static function getgcd($a, $b) {
      if($a % $b)
        return gcd($b, $a % $b);
      else
        return $b;
    }
    /**
     * 数字转换
     * @param  integer $num 值  
     * @return string
     */
    static function conversion($num = 0) {
        
        $a = $num >= 10000 ? str_replace('.00', '', self::format_money($num  / 10000)) . '万'  : $num ;
        return $a;
    }
        /**
     * 获取客户端IP  
     * @return String
     */
    static function getIP()
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

    /**
     * 生成随机字符串
     * @param  int  $length  生成字串长度
     * @param  integer $numeric 是否为数字
     * @return string
     */   
    static function random($length, $numeric = 0) 
    {
        $seed = base_convert(md5(microtime() . $_SERVER['DOCUMENT_ROOT']) , 16, $numeric ? 10 : 35);
        $seed = $numeric ? (str_replace('0', '', $seed) . '012340567890') : ($seed . 'zZ' . strtoupper($seed));
        
        if ($numeric) 
        {
            $hash = '';
        }
        else
        {
            $hash = chr(rand(1, 26) + rand(0, 1) * 32 + 64);
            $length--;
        }
        $max = strlen($seed) - 1;
        
        for ($i = 0; $i < $length; $i++) 
        {
            $hash.= $seed{mt_rand(0, $max) };
        }
        return $hash;
    }
    /**
     * 从一个二维数组中返回指定键的所有值
     * @param array $arr 数据源
     * @param string $col 要查询的键
     *
     * @return array 包含指定键所有值的数组
     */
    static function getCols($arr, $col, $delimiter = '') 
    {
        $ret = array();
        
        foreach ($arr as $row) 
        {
            
            if (isset($row[$col])) 
            {
                $ret[] = $row[$col];
            }
        }
        
        if ($delimiter) 
        {
            return implode($delimiter, $ret);
        }
        return $ret;
    }
    /** ld-select 数据处理 **/
    static function parseLdData($data, $key = 'id', $val = 'title') 
    {
        $rs = array();
        $i = 0;
        
        foreach ($data as $value) 
        {
            $rs[$i]['region_id'] = $value[$key];
            $rs[$i]['region_name'] = $value[$val];
            $i++;
        }
        return $rs;
    }
    /** ld-select 数据处理 **/
    static function parseLect($data, $key = 'id', $val = 'title') 
    {
        $rs = array();
        $i = 0;
        
        foreach ($data as $value) 
        {
            $rs[$i]['region_id'] = $value[$key] . ',' . $value[$val];
            $rs[$i]['region_name'] = $value[$val];
            $i++;
        }
        return $rs;
    }
    static function getObjCols($arr, $col, $delimiter = '') 
    {
        $ret = array();
        
        foreach ($arr as $row) 
        {
            
            if ($row->$col) 
            {
                $ret[] = $row->$col;
            }
        }
        
        if ($delimiter) 
        {
            return implode($delimiter, $ret);
        }
        return $ret;
    }
    /**
     * 将一个二维数组按照指定字段的值分组
     * @param array $arr 数据源
     * @param string $key_field 作为分组依据的键名
     *
     * @return array 分组后的结果
     */
    static function groupBy($arr, $key_field, $pk = false) 
    {
        $ret = array();
        
        foreach ($arr as $row) 
        {
            $key = $row[$key_field];
            
            if ($pk) 
            {
                $k = $row[$pk];
                $ret[$key][$k] = $row;
            }
            else
            {
                $ret[$key][] = $row;
            }
        }
        return $ret;
    }
    /**
     * 格式化金额
     * @param  integer $t 金额
     * @param  integer $g 是否返回金钱标识
     * @return [type]     [description]
     */
    
    public static function format_money($t = 0, $g = false) 
    {
        return $g ? sprintf("￥%s", number_format($t, 2, '.', '')) : number_format($t, 2, '.', '');
    }

    /**
     * 截取UTF-8编码下字符串的函数
     *
     * @param   string      $str        被截取的字符串
     * @param   int         $length     截取的长度
     * @param   bool        $append     是否附加省略号
     *
     * @return  string
     */
    public static function sub_str($str, $length = 0, $append = true) 
    {
        $str = trim($str);
        $strlength = strlen($str);
        
        if ($length == 0 || $length >= $strlength) 
        {
            return $str;
        }
        elseif ($length < 0) 
        {
            $length = $strlength + $length;
            
            if ($length < 0) 
            {
                $length = $strlength;
            }
        }
        
        if (function_exists('mb_substr')) 
        {
            $newstr = mb_substr($str, 0, $length, 'utf-8');
        }
        elseif (function_exists('iconv_substr')) 
        {
            $newstr = iconv_substr($str, 0, $length, 'utf-8');
        }
        else
        {
            //$newstr = trim_right(substr($str, 0, $length));
            $newstr = substr($str, 0, $length);
        }
        
        if ($append && $str != $newstr) 
        {
            $newstr.= '...';
        }
        return $newstr;
    }
    /**
     * 将一个平面的二维数组按照指定的字段转换为树状结构
     *
     * @param array $arr 数据源
     * @param string $key_node_id 节点ID字段名
     * @param string $key_parent_id 节点父ID字段名
     * @param string $key_child 保存子节点的字段名
     * @param boolean $refs 是否在返回结果中包含节点引用
     *
     * return array 树形结构的数组
     */
    static function toTree($arr, $key_node_id, $key_parent_id = 'parent_id', $key_children = 'child', &$refs = null) 
    {
        $refs = array();
        
        foreach ($arr as $offset => $row) 
        {
            $arr[$offset][$key_children] = array();
            $refs[$row[$key_node_id]] = & $arr[$offset];
        }
        $tree = array();
        
        foreach ($arr as $offset => $row) 
        {
            $parent_id = $row[$key_parent_id];
            
            if ($parent_id) 
            {
                
                if (!isset($refs[$parent_id])) 
                {
                    $tree[$row[$key_node_id]] = & $arr[$offset];
                    continue;
                }
                $parent = & $refs[$parent_id];
                $parent[$key_children][$row[$key_node_id]] = & $arr[$offset];
            }
            else
            {
                $tree[$row[$key_node_id]] = & $arr[$offset];
            }
        }
        return $tree;
    }
    /**导入csv文件*/
    static function input_csv($handle) 
    {
        $out = array();
        $n = 0;
        
        while ($data = fgetcsv($handle, 10000)) 
        {
            $num = count($data);
            
            for ($i = 0; $i < $num; $i++) 
            {
                $out[$n][$i] = $data[$i];
            }
            $n++;
        }
        return $out;
    }
    /**
     * 操作日志
     * @param  string $loginfo [操作详细]
     */
    
   public static function adminlog($loginfo = "", $user_id=0) 
    {
        
        require 'Hprose/HproseHttpClient.php';
        $client = new \HproseHttpClient(HPROSE_API.'/privilege');
        if(!$user_id){
            $user_id=isset($_SESSION["adminuser"]['id']) ? $_SESSION['adminuser']['id'] : 0;
        }
        $info["user_id"] = $user_id;
        $info["log_info"] = stripslashes($loginfo);
        $info["ip_address"] = self::getAddressIp();
        $order = $client->Privilege_admin_log($info);
    }
    /**
     * 操作日志
     * @param  string $loginfo [操作详细]
     */
    
    public static function serviceApi() 
    {
       
        require 'Hprose/HproseHttpClient.php';
        $client = new \HproseHttpClient(HPROSE_CSE);
        return $client;
    }
     /**
     * 同步用友  
     * @return [type] [description]
     */
    public static function yongyouApi() 
    {
       
        require 'Hprose/HproseHttpClient.php';
        $ordersClient = new \HproseHttpClient(HPROSE_WEBSERVICE."/index.php");
        return $ordersClient;
    }
    //获取真实ip地址
    public static function getAddressIp() 
    {
        
        if (@$_SERVER["HTTP_X_FORWARDED_FOR"]) $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        else 
        if (@$_SERVER["HTTP_CLIENT_IP"]) $ip = $_SERVER["HTTP_CLIENT_IP"];
        else 
        if (@$_SERVER["REMOTE_ADDR"]) $ip = $_SERVER["REMOTE_ADDR"];
        else 
        if (@getenv("HTTP_X_FORWARDED_FOR")) $ip = getenv("HTTP_X_FORWARDED_FOR");
        else 
        if (@getenv("HTTP_CLIENT_IP")) $ip = getenv("HTTP_CLIENT_IP");
        else 
        if (@getenv("REMOTE_ADDR")) $ip = getenv("REMOTE_ADDR");
        else $ip = "Unknown";
        return $ip;
    }
    public static function GetIps(){
            $realip = '';
            $unknown = 'unknown';
            if (isset($_SERVER)){
                if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']) && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], $unknown)){
                    $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                    foreach($arr as $ip){
                        $ip = trim($ip);
                        if ($ip != 'unknown'){
                            $realip = $ip;
                            break;
                        }
                    }
                }else if(isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP']) && strcasecmp($_SERVER['HTTP_CLIENT_IP'], $unknown)){
                    $realip = $_SERVER['HTTP_CLIENT_IP'];
                }else if(isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR']) && strcasecmp($_SERVER['REMOTE_ADDR'], $unknown)){
                    $realip = $_SERVER['REMOTE_ADDR'];
                }else{
                    $realip = $unknown;
                }
            }else{
                if(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), $unknown)){
                    $realip = getenv("HTTP_X_FORWARDED_FOR");
                }else if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), $unknown)){
                    $realip = getenv("HTTP_CLIENT_IP");
                }else if(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), $unknown)){
                    $realip = getenv("REMOTE_ADDR");
                }else{
                    $realip = $unknown;
                }
            }
            $realip = preg_match("/[\d\.]{7,15}/", $realip, $matches) ? $matches[0] : $unknown;
            return $realip;
    }
    public static function GetIpLookup(){
            if(empty($ip)){
                $ip = self::GetIp();
            }
            
            $res = @file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=' . $ip);
            if(empty($res)){ return false; }
            $jsonMatches = array();
            preg_match('#\{.+?\}#', $res, $jsonMatches);
            if(!isset($jsonMatches[0])){ return false; }
            $json = json_decode($jsonMatches[0], true);
            if(isset($json['ret']) && $json['ret'] == 1){
                $json['ip'] = $ip;
                unset($json['ret']);
            }else{
                return false;
            }
            return $json;
    }

    
}
/**
 * image
 */

class Img
{
    
    /**
     * 图片加水印（适用于png/jpg/gif格式）
     *
     * @author flynetcn
     *
     * @param $srcImg 原图片
     * @param $waterImg 水印图片
     * @param $savepath 保存路径
     * @param $savename 保存名字
     * @param $positon 水印位置
     * 1:顶部居左, 2:顶部居右, 3:居中, 4:底部局左, 5:底部居右
     * @param $alpha 透明度 -- 0:完全透明, 100:完全不透明
     *
     * @return 成功 -- 加水印后的新图片地址
     *          失败 -- -1:原文件不存在, -2:水印图片不存在, -3:原文件图像对象建立失败
     *          -4:水印文件图像对象建立失败 -5:加水印后的新图片保存失败
     */
    function img_water_mark($srcImg, $waterImg, $savepath = null, $savename = null, $positon = 5, $alpha = 30) 
    {
        $temp = pathinfo($srcImg);
        $name = $temp['basename'];
        $path = $temp['dirname'];
        $exte = $temp['extension'];
        $savename = $savename ? $savename : $name;
        $savepath = $savepath ? $savepath : $path;
        $savefile = $savepath . '/' . $savename;
        $srcinfo = @getimagesize($srcImg);
        
        if (!$srcinfo) 
        {
            return -1; //原文件不存在
            
        }
        $waterinfo = @getimagesize($waterImg);
        
        if (!$waterinfo) 
        {
            return -2; //水印图片不存在
            
        }
        $srcImgObj = $this->image_create_from_ext($srcImg);
        
        if (!$srcImgObj) 
        {
            return -3; //原文件图像对象建立失败
            
        }
        $waterImgObj = $this->image_create_from_ext($waterImg);
        
        if (!$waterImgObj) 
        {
            return -4; //水印文件图像对象建立失败
            
        }
        
        switch ($positon) 
        {
            //1顶部居左
            
        case 1:
            $x = $y = 15;
            break;
            //2顶部居右
            
        case 2:
            $x = $srcinfo[0] - $waterinfo[0];
            $y = 0;
            break;
            //3居中
            
        case 3:
            $x = ($srcinfo[0] - $waterinfo[0]) / 2;
            $y = ($srcinfo[1] - $waterinfo[1]) / 2;
            break;
            //4底部居左
            
        case 4:
            $x = 0;
            $y = $srcinfo[1] - $waterinfo[1];
            break;
            //5底部居右
            
        case 5:
            $x = $srcinfo[0] - $waterinfo[0];
            $y = $srcinfo[1] - $waterinfo[1];
            break;

        default:
            $x = $y = 0;
        }
        imagecopymerge($srcImgObj, $waterImgObj, $x, $y, 0, 0, $waterinfo[0], $waterinfo[1], $alpha);
        
        switch ($srcinfo[2]) 
        {
        case 1:
            imagegif($srcImgObj, $savefile);
            break;

        case 2:
            imagejpeg($srcImgObj, $savefile);
            break;

        case 3:
            imagepng($srcImgObj, $savefile);
            break;

        default:
            return -5; //保存失败
            
        }
        imagedestroy($srcImgObj);
        imagedestroy($waterImgObj);
        return $savefile;
    }
    /**
     * 获取文件
     * @param  [type] $imgfile [description]
     * @return [type]          [description]
     */
    function image_create_from_ext($imgfile) 
    {
        $info = getimagesize($imgfile);
        $im = null;
        
        switch ($info[2]) 
        {
        case 1:
            $im = imagecreatefromgif($imgfile);
            break;

        case 2:
            $im = imagecreatefromjpeg($imgfile);
            break;

        case 3:
            $im = imagecreatefrompng($imgfile);
            break;
        }
        return $im;
    }
    function getFile($id, $data ,$er_wei='') 
    {

        // $er_wei = ITEM_IMG . "/qrcode/qrcode_{$id}.png";
        $height = 400;
        $width = 800;
        $font = PUBLIC_PATH . '/font/simhei.ttf';
        //创建背景图
        $im = ImageCreateTrueColor($width, $height);
        //分配颜色
        $white = ImageColorAllocate($im, 0, 0, 0);
        $blue = ImageColorAllocate($im, 255, 255, 255);
        //绘制颜色至图像中
        ImageFill($im, 0, 0, $blue);
        //绘制字符串：Hello,PHP

        foreach ($data as $key => $val) 
        {
            imagettftext($im, 12, 0, $val['x'], $val['y'], $white, $font, $val['value']);
        }
        $down = PUBLIC_PATH . "/qrcode/down/qrcode_{$id}.png";
        $path = PUBLIC_PATH . "/qrcode/down";
        //输出图像，定义头
        Header('Content-type: image/png');
        //将图像发送至浏览器
        ImagePng($im, $down); //保存图片
        ImageDestroy($im); //释放图片
        return $this->img_water_mark($down, $er_wei, $path, "qrcode_down_{$id}.png", 1, 100);
    }

}
