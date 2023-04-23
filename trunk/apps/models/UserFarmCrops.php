<?php
namespace Mdg\Models;

class UserFarmCrops extends \Phalcon\Mvc\Model
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
    public $category_name;

    /**
     *
     * @var integer
     */
    public $add_time;

    /**
     *
     * @var integer
     */
    public $category_id;

    /**
     * 获取用户作物
     * @param  integer $uid 用户id
     * @param  integer $cid 用户身份id
     * @return arary
     */
    public static function selectByuserFarm($uid=0, $cid=0) {

        $cond[] = " user_id = '{$uid}' AND credit_id = '{$cid}'";
        $data = self::find($cond)->toArray();
        return $data;
    }

    

}
