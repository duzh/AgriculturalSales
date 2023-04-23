<?php
namespace Mdg\Models;

use Lib\Member as Member,
    Lib\Auth as Auth,
    Lib\Utils as Utils;
use Lib\Pages as Pages;
use Mdg\Models\UsersExt as Ext;
use Mdg\Models as M;
class UserArea extends Base
{

public $id;

public $user_id;
		
public $province_id;
	
public $city_id;
	
public $district_id;

public $town_id;

public $village_id;

public $town_name;

public $village_name;

public $district_name;

public $city_name;

public $province_name;

public $add_time;

public $last_update_time;

 /**
     * 获取用户服务区域
     * @param  integer $uid 用户id
     * @return
     */
    
    public static function selectByArea($uid = 0) 
    {
        /* 获取用户身份 */
        
        if (!$uid) return array();
        $county = M\UserInfo::findFirst(" user_id ='{$uid}' AND credit_type = '2' AND status = '1'");
        
        if (!$county) $village = M\UserInfo::findFirst(" user_id ='{$uid}' AND  credit_type = '4' AND status = '1'");
        
        if (!$county && !$village) return array();
        $data = $county ? $county : $village;
        $cond[] = " credit_id  ='{$data->credit_id}' AND user_id = '{$data->user_id}'";
        $area = self::findFirst($cond);
      
        return $county ? array(
            'county' => $area->district_id
        ) : array(
            'village' => $area->town_id
        );
    }

}