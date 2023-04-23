<?php
namespace Lib;
//验证码类

class Validatecode
{
    
    private $charset = 'abcdefghkmnprstuvwxyzABCDEFGHKMNPRSTUVWXYZ23456789'; //随机因子
    
    public $code; //验证码
    
    private $codelen = 4; //验证码长度
    
    private $width = 90; //宽度
    
    private $height = 35; //高度
    
    private $img; //图形资源句柄
    
    private $font; //指定的字体
    
    private $fontsize = 18; //指定字体大小
    
    private $fontcolor; //指定字体颜色
    //构造方法初始化
    
    public function __construct() 
    {
        //$this->font = '/font/elephant.ttf';//注意字体路径要写对，否则显示不了图片
        $this->font = '/font/UniTortred.ttf';
    }
    //生成随机码
    
    private function createCode() 
    {
        $_len = strlen($this->charset) - 1;
        
        for ($i = 0; $i < $this->codelen; $i++) 
        {
            $this->code.= $this->charset[mt_rand(0, $_len) ];
        }
        $_SESSION['image_code'] = $this->code;
    }
    //生成背景
    
    private function createBg($color = array()) 
    {
        $this->img = imagecreatetruecolor($this->width, $this->height);
        $color = imagecolorallocate($this->img, mt_rand(157, 255) , mt_rand(157, 255) , mt_rand(157, 255));
        imagefilledrectangle($this->img, 0, $this->height, $this->width, 0, $color);
    }
    //生成文字
    
    private function createFont() 
    {
        $_x = $this->width / $this->codelen;
        
        for ($i = 0; $i < $this->codelen; $i++) 
        {
            $this->fontcolor = imagecolorallocate($this->img, mt_rand(0, 1) , mt_rand(0, 2) , mt_rand(0, 3));
            imagettftext($this->img, $this->fontsize, mt_rand(-30, 30) , $_x * $i + mt_rand(1, 3) , $this->height / 1.2, $this->fontcolor, $this->font, $this->code[$i]);
        }
    }
    //生成线条、雪花
    
    private function createLine() 
    {
        //线条
        
        // for ($i = 0; $i < 6; $i++) 
        // {
        //     $color = imagecolorallocate($this->img, mt_rand(0, 156) , mt_rand(0, 156) , mt_rand(0, 156));
        //     imageline($this->img, mt_rand(0, $this->width) , mt_rand(0, $this->height) , mt_rand(0, $this->width) , mt_rand(0, $this->height) , $color);
        // }
        // //雪花
        
        // for ($i = 0; $i < 100; $i++) 
        // {
        //     $color = imagecolorallocate($this->img, mt_rand(200, 255) , mt_rand(200, 255) , mt_rand(200, 255));
        //     imagestring($this->img, mt_rand(1, 5) , mt_rand(0, $this->width) , mt_rand(0, $this->height) , '*', $color);
        // }
    }
    
    public function createMobile($mobile = '') 
    {
        $this->code = $mobile;
        $this->width = 170; //宽度
        $this->height = 35; //高度
        $this->fontsize = 13; //指定字体大小
        $this->codelen = 11;
    }
    //输出
    
    private function outPut() 
    {
        header('Content-type:image/png');
        imagepng($this->img);
        imagedestroy($this->img);
    }
    //对外生成

    public function doimg($ismobile = 0) 
    {
        
        if ($ismobile) 
        {
            $this->createMobile($ismobile);
        }
        else
        {
            $this->createCode();
        }
        $this->createBg();
        $this->createLine();
        $this->createFont();
        $this->outPut();
    }

    public static function validateCode($code){
      return (strtolower($_SESSION['image_code']) == $code);
    }

    public static function remove(){
      $_SESSION['image_code'] = '';
    }
}
?>