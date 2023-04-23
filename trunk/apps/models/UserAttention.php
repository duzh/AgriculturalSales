<?php
namespace Mdg\Models;
use Mdg\Models as M;


class UserAttention extends \Phalcon\Mvc\Model
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
     * @var integer
     */
    public $attention_type;

    /**
     *
     * @var string
     */
    public $category_name;

    /**
     *
     * @var integer
     */
    public $category_id;

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
     * 获取用户关注分类
     * @param  integer $uid 用户id
     * @param  integer $type 类型 1 供应 2采购
     * @return array
     */
    public static function selectByuid($uid=0 , $type=1) {
        $cond[] = " user_id = '{$uid}'";
  
        /* A-E */
        return self::find(array(
            "  attention_type = '{$type}' AND user_id = '{$uid}'",
            'colimns' => 'id,title',
            'limit' => 60
        ))->toArray();

        /* F-J*/
        $rest[2] = self::find(array(
            " abbreviation between 'F' AND 'J' AND attention_type = '{$type}'  AND user_id = '{$uid}' ",
            'colimns' => 'id,title',
            'limit' => 10
        ))->toArray();
        /* K-O*/
        $rest[3] = self::find(array(
            " abbreviation between 'K' AND 'O' AND attention_type = '{$type}' AND user_id = '{$uid}' ",
            'colimns' => 'id,title',
            'limit' => 10
        ))->toArray();
        /* P-T*/
        $rest[4] = self::find(array(
            " abbreviation between 'P' AND 'T' AND attention_type = '{$type}' AND user_id = '{$uid}' ",
            'colimns' => 'id,title',
            'limit' => 10
        ))->toArray();
        /* U-Z*/
        $rest[5] = self::find(array(
            " abbreviation between 'U' AND 'Z' AND attention_type = '{$type}'  AND user_id = '{$uid}' ",
            'colimns' => 'id,title',
            'limit' => 10
        ))->toArray();
        
        return $rest;
    }   

}
