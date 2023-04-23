<?php
namespace Lib;
//验证码类
class Mobilecode {
 private $charset = 'abcdefghkmnprstuvwxyzABCDEFGHKMNPRSTUVWXYZ23456789';//随机因子
 public $code;//验证码
 private $codelen = 4;//验证码长度
 private $width = 135;//宽度
 private $height = 12;//高度
 private $img;//图形资源句柄
 private $font;//指定的字体
 private $fontsize = 12;//指定字体大小
 private $fontcolor;//指定字体颜色
 //构造方法初始化
 public function __construct() {
  //$this->font = '/font/elephant.ttf';//注意字体路径要写对，否则显示不了图片
  $this->font='/font/UniTortred.ttf';
 }
 //生成随机码
 private function createCode() {
  $_len = strlen($this->charset)-1;

  for ($i=0;$i<$this->codelen;$i++) {
   $this->code .= $this->charset[mt_rand(0,$_len)];
  }

 }
 //生成背景
 private function createBg($color = array()) {
  $this->img = imagecreatetruecolor($this->width, $this->height);
  $color = imagecolorallocate($this->img, mt_rand(254,255), mt_rand(254,255), mt_rand(254,255));
  imagefilledrectangle($this->img,0,$this->height,$this->width,0,$color);
 }
 //生成文字
 private function createFont() {
  $_x = $this->width / $this->codelen;
  for ($i=0;$i<$this->codelen;$i++) {
   $this->fontcolor = imagecolorallocate($this->img,255,78,77);
   imagettftext($this->img,$this->fontsize,1,$_x*$i+1,$this->height / 1.4,$this->fontcolor,$this->font,$this->code[$i]);
  }
 }
 //生成线条、雪花
 private function createLine() {
    //线条
    for ($i=0;$i<6;$i++) {
     $color = imagecolorallocate($this->img,mt_rand(0,156),mt_rand(0,156),mt_rand(0,156));
     imageline($this->img,mt_rand(0,$this->width),mt_rand(0,$this->height),mt_rand(0,$this->width),mt_rand(0,$this->height),$color);
    }
    //雪花
    for ($i=0;$i<100;$i++) {
     $color = imagecolorallocate($this->img,mt_rand(200,255),mt_rand(200,255),mt_rand(200,255));
     imagestring($this->img,mt_rand(1,5),mt_rand(0,$this->width),mt_rand(0,$this->height),'*',$color);
    }
 }
 public function createMobile ($mobile='') {


  $this->code=$mobile;
  $this->width    = 170;//宽度
  $this->height   = 35;//高度
  $this->fontsize = 12;//指定字体大小
  $this->codelen = 11;

 } 
 //输出
 public function outPut($path) {
    imagepng($this->img,$path);
    imagedestroy($this->img);
 }
 //对外生成
 public function doimg($ismobile=0,$path) {

    $this->createMobile($ismobile);
    $this->createBg();
    $this->createLine();
    $this->createFont();
    $this->outPut($path);
 }
}

?>