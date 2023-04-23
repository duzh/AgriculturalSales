<?php
namespace Mdg\Models;
use Mdg\Models\Users as Users;
use Mdg\Models\UsersExt as UsersExt;
use Mdg\Models\ShopGrade as ShopGrade;

class ShopComments extends \Phalcon\Mvc\Model
{
    static $_is_check = array(
        0 => '待审核',
        1 => '审核通过',
        2 => '审核未通过'
    );
    /**
     *
     * @var integer
     */
    
    public $id;
    /**
     *
     * @var integer
     */
    
    public $shop_id;
    /**
     *
     * @var string
     */
    
    public $user_name;
    /**
     *
     * @var integer
     */
    
    public $user_id;
    /**
     *
     * @var string
     */
    
    public $comment;
    /**
     *
     * @var integer
     */
    
    public $add_time;
    
    public $shop_name;
    
    public $service;
    
    public $accompany;
    
    public $description;
    
    public $supply;
    /**
     *
     * @var integer
     */
    
    public $last_update_time;
    static public function conditions($arr) 
    {
        $options = array(
            'addtime' => '',
            'order_sn' => '',
            'state' => '',
            'stime' => '',
            'etime' => '',
            'purname' => '',
            'sellname' => ''
        );
        $opt = array_merge($options, $arr);
        $where = '1=1';
        
        if (is_array($arr)) 
        {
            
            if (isset($arr['stime']) && !empty($arr['stime']) && isset($arr['etime']) && !empty($arr['etime'])) 
            {
                $where.= " and addtime between " . strtotime($arr['stime']) . " and " . strtotime($arr['etime']) . "";
            }
            empty($arr['category']) ? : $where.= self::purcate($arr['category']);
            empty($arr['state']) ? : $where.= " and state =" . $arr["state"];
            empty($arr['sellname']) ? : $where.= " and sname    like '" . $arr["sellname"] . "%' ";
            empty($arr['purname']) ? : $where.= " and purname  like '" . $arr["purname"] . "%' ";
            empty($arr['order_sn']) ? : $where.= " and order_sn like '" . $arr["order_sn"] . "%' ";
        }
        return $where;
    }  
    /**
     * 获取店铺 评价
     * @param  integer $sid   店铺id
     * @param  boolean $isobg 是否返回对象格式
     * @return 
     */
    public static function getShopComments ($sid = 0, $isobj=false){

        $data = self::find(" shop_id = '{$sid}' AND is_check = 1  limit 10");
        
        return $data ? $isobj ? $data : $data->toArray() : array();
    } 
    /**
     * 获取用户信息
     * @param integer $user_id [description]
     */
    
    public static function GetUsersInfo($user_id = 0) 
    {
        $users = Users::findFirstByid($user_id);
        $usersext = UsersExt::findFirstByuid($users->id);
        return $usersext->name;
    }
    
    /**
     * 获取评分星数
     * @param integer $shop_id [description]
     */
    public static function getShopService($shop_id = 0) 
    {

        $score = array( );
        $arr = array();

        if(!$shop_id) return array((object)$arr , (object)$score);
 

        $cond = array(" shop_id = '{$shop_id}' AND is_check = 1");
        $cond['columns'] = " sum(service) AS service , sum(accompany) AS accompany, sum(description) AS description, sum(supply) AS supply  ";
        $data = self::findFirst($cond);
        $count = self::count( " shop_id = '{$shop_id}' AND is_check = 1 " );

        if(!$data || !$count)  return array($arr , $score);

        $shopGrade = $data->toArray();

        $arr['service'] = '' . number_format($shopGrade['service'] / $count, 1, '.', '');
        $arr['accompany'] = '' . number_format($shopGrade['accompany'] / $count, 1, '.', '');
        $arr['supply'] = '' . number_format($shopGrade['supply'] / $count, 1, '.', '');
        $arr['description'] = '' . number_format($shopGrade['description'] / $count, 1, '.', '');

        $score['service'] =  number_format($shopGrade['service'] / $count, 1, '.', '') ;
        $score['accompany'] =  number_format($shopGrade['accompany'] / $count, 1, '.', '');
        $score['supply'] =  number_format($shopGrade['supply'] / $count, 1, '.', '');
        $score['description'] = number_format($shopGrade['description'] / $count, 1, '.', '');

        return array((object)$arr , (object)$score);
    }

    /**
     * 获取评分星数
     * @param integer $shop_id [description]
     */
    public static function GetService($shop_id = 0) 
    {
        $shopGrade = ShopGrade::find('shop_id = ' . $shop_id)->toArray();
        $count = ShopGrade::count('shop_id=' . $shop_id);
        
        if ($count == 0) 
        {
            $arr['service'] = ':0星';
            $arr['accompany'] = ':0星';
            $arr['supply'] = ':0星';
            $arr['description'] = ':0星';
            return $arr;
        }
        
        foreach ($shopGrade as $key => $value) 
        {
            $arr['service'][$key] = $value['service'];
            $arr['accompany'][$key] = $value['accompany'];
            $arr['supply'][$key] = $value['supply'];
            $arr['description'][$key] = $value['description'];
        }
        $arr['service'] = ':' . number_format(array_sum($arr['service']) / $count, 1, '.', '');
        $arr['accompany'] = ':' . number_format(array_sum($arr['accompany']) / $count, 1, '.', '') . '星';
        $arr['supply'] = ':' . number_format(array_sum($arr['supply']) / $count, 1, '.', '') . '星';
        $arr['description'] = ':' . number_format(array_sum($arr['description']) / $count, 1, '.', '') . '星';
        return $arr;
    }
}
