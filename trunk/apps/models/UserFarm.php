<?php
namespace Mdg\Models;
use Lib\Func as Func;
use Mdg\Models as M;
class UserFarm extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $user_id;

    /**
     *
     * @var string
     */
    public $farm_name;

    /**
     *
     * @var integer
     */
    public $province_id;

    /**
     *
     * @var integer
     */
    public $city_id;

    /**
     *
     * @var integer
     */
    public $district_id;

    /**
     *
     * @var integer
     */
    public $town_id;

    /**
     *
     * @var string
     */
    public $province_name;

    /**
     *
     * @var string
     */
    public $city_name;

    /**
     *
     * @var string
     */
    public $district_name;

    /**
     *
     * @var string
     */
    public $town_name;

    /**
     *
     * @var string
     */
    public $village_name;

    /**
     *
     * @var integer
     */
    public $village_id;

    /**
     *
     * @var double
     */
    public $farm_area;

    /**
     *
     * @var integer
     */
    public $source;

    /**
     *
     * @var double
     */
    public $year;
    /**
     *
     * @var double
     */
    public $month;

    /**
     *
     * @var double
     */
    public $start_year;
    /**
     *
     * @var double
     */
    public $start_month;

    /**
     *
     * @var string
     */
    public $describe;

    /**
     *
     * @var integer
     */
    public $add_time;

    /**
     *
     * @var integer
     */
    public $last_update_time;

    /**
     *
     * @var string
     */
    public $address;

    /**
     * 获取用户农场信息 
     * @param  integer $uid 用户id
     * @param  integer $cid 身份标识id
     * @return array
     */
    public static function selectByUser_id($uid=0, $cid=0) {
         $cond[] = " user_id = '{$uid}' AND credit_id  = '{$cid}'";
         $data = self::findFirst($cond);
         return $data ? $data->toArray() : array();
    }   

     /**
     * 可信农场列表地区筛选
     * @param  string $province [description]
     * @param  string $city     [description]
     * @param  string $qu       [description]
     * @param  string $xian     [description]
     * @param  string $areas    [description]
     * @return [type]           [description]
     */
    public static function selectByArea($province ='',$city='',$qu='',$xian='',$areas=''){

        $user_info = M\UserInfo::find("status=1")->toArray();
        $credit_ids = '';
        if($user_info){
            $credit_id = Func::getCols($user_info,'credit_id',',');
            $credit_ids = " and credit_id in ({$credit_id})";

        }
        //echo $count;die;
        if($province && !$city){
            $cond[]="province_name='{$province}' {$credit_ids}";
        }elseif($city && !$qu){
            $cond[]="province_name='{$province}' and city_name='{$city}' {$credit_ids}";
        }elseif($qu && !$xian){
            $cond[]="province_name='{$province}' and city_name='{$city}' and district_name='{$qu}' {$credit_ids}";
        }elseif($xian && !$areas){
            $cond[]="province_name='{$province}' and city_name='{$city}' and district_name='{$qu}' and town_name='{$xian}' {$credit_ids}";
        }elseif($xian && $areas){
            $cond[]="province_name='{$province}' and city_name='{$city}' and district_name='{$qu}' and town_name='{$xian}' and village_name='{$areas}' {$credit_ids}";
        }
        
        $cond['columns'] = "user_id";

        //print_r($cond);die;

        $id_arr = self::find($cond)->toArray();
       if($id_arr){
            $user_ids = Func::getCols($id_arr,'user_id',',');
            return "and user_id in ({$user_ids})";
       }else{
        return " and user_id in ('')";
       }
    }


    /**
     * 获取用户农场面积
     * @param  integer $cid 申请信息id
     * @return 
     */
    public static function selectByFarmArea($cid=0) {
        $cond[] = " credit_id = '{$cid}'";
        $cond['columns'] = 'farm_area';
        $data = self::findFirst($cond);
        return $data ? $data->farm_area : 0;

    }

}
