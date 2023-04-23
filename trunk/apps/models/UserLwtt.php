<?php
namespace Mdg\Models;
class UserLwtt extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id=0;

    /**
     *
     * @var string
     */
    public $sell_id=0;

    /**
     *
     * @var string
     */
    public $farm_id=0;

    public static function getuserlwtt($sell_id){
        $sell=self::find("sell_id={$sell_id}")->toArray();
        if($sell){
            foreach ($sell as $key => $value) {
                $user_farm = UserFarm::findFirst("credit_id={$value['farm_id']} ");
                if($user_farm){
                   $sell[$key]["farm_name"]=$user_farm->farm_name;
                }else{
                   $sell[$key]["farm_name"]='';
                }
            }
        }
        return $sell ? $sell :array() ;
    }
    public static function adduserlwtt($sell_id,$farm_id){
        $adduserlwtt=new self();
        $adduserlwtt->sell_id=$sell_id;
        $adduserlwtt->farm_id=$farm_id;
        $adduserlwtt->save();
    }
}
