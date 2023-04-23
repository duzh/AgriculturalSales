<?php
namespace Mdg\Models;
use Lib\UpYun as UpYun;
use Lib\File as File;
use Lib\Mobilecode as mcode;
class Codes extends Base
{
    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $mobile;

    /**
     *
     * @var string
     */
    public $code;

    /**
     *
     * @var integer
     */
    public $endtime;

    /**
     *
     * @var integer
     */
    public $type;
    
    public function initialize(){
        $this->setConnectionService('db');
        $this->useDynamicUpdate(true);
    }
    static public function  delcode($mobile,$type){
        $code = self::findFirst("mobile='{$mobile}' and type ='{$type}'");
        if($code) {
          $code->delete();
        }
    }
    static public function getmoblie($mobile){
        if($mobile){
           $_vc = new mcode();
           $path = PUBLIC_PATH . "/qrcode/mobile/mobile_".File::random(12,1).".png";
           $tmppath = str_replace(PUBLIC_PATH, '',$path);
           $_vc->doimg($mobile,$path);
           self::upyunfile($path,$tmppath);
           unlink($path);
           return $tmppath;
        }else{
            return false;
        }
    }
 
   static public function  upyunfile($path,$publicpath){
        $upyun = new UpYun();
        $test = @fopen($path,'r');
        $pathaa =$publicpath;
        $arr = $upyun->writeFile($pathaa, $test, true);
    }

    /**
     * 检测手机号是否和验证码一直
     * @param  string $mobile 手机号
     * @param  string $code   验证码
     * @return boolean
     */
    public static function checkCode($mobile='', $code='') {
        
        $cond[] = " mobile = '{$mobile}' AND code = '{$code}'";
        $cond['order'] = ' id desc ';
        $data = self::findFirst($cond);
        return $data ? $data : array();
    }


    /**
     * 保存手机验证码
     * @param  [type] $mobile 手机号
     * @param  [type] $code   验证码
     * @return boolean
     */
    public static function  savecode($mobile,$code){

        $time = time();
        $cond = array("mobile='{$mobile}' and  endtime>'{$time}' and type=3 ");
        $cond['order'] = 'id desc';
        $codes = self::findFirst($cond);
        if(!$codes) {
            $codes = new self();
            $codes->mobile = $mobile;
            $codes->code =$code;
            $codes->endtime = $time + 900;
            $codes->type = 3;
            $codes->save();
        }

        $codes->code = $code;
        $codes->endtime = $time + 900;
        $codes->save();
    }
  
}
