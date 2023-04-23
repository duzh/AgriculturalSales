<?php
namespace Mdg\Models;
use Lib\Member as Member;
use Lib\Auth as Auth;
use Lib\SMS as sms;
use Lib\Func as Func;
use Lib\Utils as Utils;
use Lib\Areas as lAreas;
use Lib\Pages as Pages;
use Mdg\Models\AreasFull as mAreas;

class Reg extends \Phalcon\Mvc\Model
{
    
    public $uid='0';                  
    public $title='';           //产品标题        
    public $max_category='';    //大分类           
    public $min_category='';    //小分类           
    public $min_price='';       //最低价格        
    public $max_price='';      // 最高价格        
    public $quantity='' ;      // 数量              
    public $goods_unit='' ;    // 数量单位        
    public $province='';       // 省                 
    public $city='';           // 市                 
    public $town='';           //区                 
    public $stime='';          //开始时间        
    public $etime='';           //结束时间        
    public $breed='';          // 种类              
    public $spec='';           // 规格              
    public $endtime='';         //报价截至时间  
    public $purmoblie='';      //最后更新时间  
    public $purname='';        // 用户姓名        
    public $sellmoblie='';      //供应编号        
    public $sellname='';     //联系方式        
    public $address='';      //详细地址        
    public $content='';      //秒诉
    public $type='';         //类型

    static public function checkpur($arr){
          
          if(!empty($arr)){
              $title      = self::title($arr[0]);
              $scate      = Category::parseCategory($arr[1],$arr[2]);
              $quantity   = self::quantity($arr[3]);   //供应量
              $goods_unit = self::goods_unit($arr[4]); //供应单位
              $spec       = self::title($arr[5]);
              $endtime    = self::checktime($arr[6]);   
              $moblie     = self::moblie($arr[8]);
              $name       = self::title($arr[7]);
              $areas      = Areas::parseAreas($arr[9],$arr[10],$arr[11]);
              $address    = self::title($arr[12]);
               if($title&&$scate&&$quantity&&$goods_unit&&$spec&&$endtime&&$moblie&&$name&&$areas&&$address){
                
                $area_name=Func::getCols(mAreas::getFamily($areas), 'name', ',');
                $address=Func::getCols(mAreas::getFamily($areas), 'name', ',');
                $userid=self::reguser($moblie,'123456','2',$areas,$area_name,$address,$name,$title); 
                //数据符合的情况下 插入采购表
                if($userid){
                    //插入采购表
                    $purchase = new Purchase();
                    $purchase->uid        = $userid;
                    $purchase->title      = $title;
                    $purchase->category   = $scate;
                    $purchase->quantity   = $quantity;
                    $purchase->goods_unit = $goods_unit;
                    $purchase->areas      = $areas;
                    $purchase->areas_name = $area_name;
                    $purchase->address    = $address;
                    $purchase->username   = $name;
                    $purchase->mobile     = $moblie;
                    $purchase->endtime    = strtotime($endtime);
                    $purchase->createtime = $purchase->updatetime = time();
                    $purchase->state = 0;
                    $purchase->save();

                    $purchase->pur_sn = sprintf('Pur%010u', $purchase->id);
                    $purchase->save();
                    $PurchaseContent = new PurchaseContent();
                    $PurchaseContent->purid=$purchase->id;
                    $PurchaseContent->content=$spec;
                    $PurchaseContent->save();

                 
                }    
                
              }else{

                   //
                    $reg= new Reg(); 
                    $reg->title         = $arr[0];
                    $reg->max_category  = $arr[1];
                    $reg->min_category  = $arr[2];
                    $reg->quantity   = $arr[3];  
                    $reg->goods_unit = $arr[4];
                    $reg->spec       = $arr[5];
                    $reg->endtime    = $endtime;
                    $reg->purname    = $arr[7];
                    $reg->purmoblie  = $arr[8];
                    $reg->province   = $arr[9];
                    $reg->city       = $arr[10];
                    $reg->town       = $arr[11];
                    $reg->address    = $arr[12];
                    $reg->beizhu     = $arr[13];
                    $reg->type       = '1';
                    $reg->save();
            
                        
              }

           }

    } 
    static public function checksell($arr){
            
            if(!empty($arr)){
              $title      = self::title($arr[0]);
              $scate      = Category::parseCategory($arr[1],$arr[2]);
              $max_price  = self::quantity($arr[4]);
              $min_price  = self::quantity($arr[3]);
              $quantity   = self::quantity($arr[5]);   //供应量
              $goods_unit = self::goods_unit($arr[6]); //供应单位
              $stime      = self::stime($arr[7]);
              $etime      = self::stime($arr[8]);
              $bread      = self::title($arr[9]);     //品种 
              $spec       = self::title($arr[10]);
              $content    = self::title($arr[11]);
              $sellname   = self::title($arr[12]);
              $sellmoblie = self::moblie($arr[13]);
              $areas      = Areas::parseAreas($arr[14],$arr[15],$arr[16]);
              $address    = self::title($arr[17]);
               if($title&&$scate&&$max_price&&$min_price&&$quantity&&$goods_unit&&$stime&&$etime&&$bread&&$spec&&$content&&$sellname&&$sellmoblie&&$areas&&$address){
                
                $area_name=Func::getCols(mAreas::getFamily($areas), 'name', ',');
                $userid=self::reguser($sellmoblie,'123456','1',$areas,$area_name,$address,$sellname,$title); 
                if($userid){
                    //插入采购表
                    $cur_time = time();
                    $sell = new Sell();
                    $sell->title      = $title;
                    $sell->category   = $scate;
                    $sell->max_price  = $max_price;
                    $sell->min_price  = $min_price;
                    $sell->stime      = $stime;
                    $sell->etime      = $etime;
                    $sell->quantity   = $quantity;
                    $sell->goods_unit = $goods_unit;
                    $sell->areas      = $areas;
                    $sell->areas_name = Func::getCols(mAreas::getFamily($sell->areas), 'name', ',');
                    $sell->address    = Func::getCols(mAreas::getFamily($sell->areas), 'name', ',');
                    $sell->bread      = $bread;
                    $sell->spec       = $spec;
                    $sell->uid        = $userid;
                    $sell->state      = 0;
                    $sell->uname      = $sellname;
                    $sell->mobile     = $sellmoblie;
                    $sell->createtime = $sell->updatetime = $cur_time;
                    $sell->save(); 
                    $sell->sell_sn = sprintf('SELL%010u', $sell->id);
                    $sell->save(); 
                    $scontent = new SellContent();
                    $scontent->sid     = $sell->id;
                    $scontent->content = $content;
                    $scontent->save();
            
                }    
                
              }else{
                    
                    $reg= new Reg(); 
                    $reg->title        = $arr[0];
                    $reg->max_category = $arr[1];
                    $reg->min_category = $arr[2];
                    $reg->min_price    = $arr[3];
                    $reg->max_price    = $arr[4];
                    $reg->quantity     = $arr[5];
                    $reg->goods_unit   = $arr[6];
                    $reg->stime        = $arr[7];  
                    $reg->etime        = $arr[8];
                    $reg->breed        = "{$arr[9]}";
                    $reg->spec         = $arr[10];
                    $reg->content      = $arr[11];
                    $reg->sellname     = $arr[12];
                    $reg->sellmoblie   = $arr[13];
                    $reg->province     = $arr[14];
                    $reg->city         = $arr[15];
                    $reg->town         = $arr[16];
                    $reg->address      = $arr[17];
                    $reg->type         = '0';
                    if(!$reg->save()){
                        return false;
                    }
                   
            }  
        }
    }
    //检测时间并转换
    static function checktime($time){
        $t=$time;
        $n = intval(($t - 25569) * 3600 * 24); //转换成1970年以来的秒数
        return gmdate('Y-m-d H:i:s', $n);
    }
    //去除字符串空格
    static function strTrim($str)
    {
        return trim($str);
    }
    //验证商品名称
    static function title($str)
    {
        $str=self::strTrim($str);
        if(!empty($str)){
            return $str;
        }else{
            return false;
        }
    }
    static function stime($str){
        $str=self::strTrim($str);
        $arr=Sell::$type;
        if(!empty($str)&&in_array($str,$arr))
        {  
           $arr1=array_flip($arr);
           return $arr1[$str];
        }else{
            return false;
        }
    }
    //验证采购数量
    static function quantity($str)
    {   
        $str=self::strTrim($str);
        if(!empty($str)&&is_numeric($str))
        {  
            return $str;
        }else{
            return false;
        }
    }
    //验证采购单位
    static function goods_unit($str){
        $str=self::strTrim($str);
        $arr=Purchase::$_goods_unit;
        if(!empty($str)&&in_array($str,$arr))
        {   
           return Purchase::$goods_unitname[$str];
        }else{
            return false;
        }
    }
    //验证手机号
    static function moblie($moblie){
        $regex = '/1[3458]{1}\d{9}$/';
        if(!empty($moblie)&&preg_match($regex,$moblie)){
            return $moblie;
        }else{
            return false;
        }
    }
    //插入会员
    static function reguser($username,$password,$type,$areas,$areas_name,$address,$name='-',$goods){
           $member = new Member();
           $synuser = $member->register(trim($username),$password);

            //如果平台注册成功
           if($synuser){
                //查看卖的贵的信息是否有  如果有 就直接取id  如果没有  注册
                $user=Users::findFirst("username='{$username}'");
                if($user){
                    $userid=$user->id;
                }else{
             
                    $salt  = Auth::random(6,1);
                    $users = new Users();
                    $users->id            = $synuser['id'];
                    $users->username      = trim($username);
                    $users->usertype      = $type;
                    $users->regtime       = time();
                    $users->regip         = Utils::getIP();
                    $users->lastlogintime = time();
                    $users->areas = $areas;
                    $users->encodePwd($password,$salt);
                    $users->save();
                    
                    $ext = new UsersExt();
                    $ext->uid = $synuser['id'];
                    $ext->name       = $name;
                    $ext->areas_name = $areas_name;
                    $ext->address    = $address;
                    $ext->goods      = $goods;
                    $ext->save();
                    if($ext->save()){
                        return $users->id;
                    }
                }
           }else{
                $synuser = $member->getMember(trim($username));
                $salt  = Auth::random(6,1);
                $users = new Users();
                $users->id            = $synuser['user_id'];
                $users->username      = trim($username);
                $users->usertype      = $type;
                $users->regtime       = time();
                $users->regip         = Utils::getIP();
                $users->lastlogintime = time();
                $users->areas = $areas;
                $users->encodePwd($password,$salt);
                $users->save();
                
                $ext = new UsersExt();
                $ext->uid = $synuser['user_id'];
                $ext->name       = $name;
                $ext->areas_name = $areas_name;
                $ext->address    = $address;
                $ext->goods      = $goods;
                $ext->save();
                if($ext->save()){
                    return $users->id;
                }

           }
    }
}
