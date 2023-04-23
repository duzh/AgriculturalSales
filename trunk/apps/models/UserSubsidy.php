<?php
namespace Mdg\Models;
use Lib\Func as Func;
class UserSubsidy extends \Phalcon\Mvc\Model
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
     * @var double
     */
    public $subsidy_total_amount;

    /**
     *
     * @var double
     */
    public $subsidy_left_subsidy;

    /**
     *
     * @var double
     */
    public $subsidy_lock_amount;

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
     * 获取订单可用鼻贴
     * @param  [type] $user_id 用户id
     * @param  [type] $total   订单金额
     * @return array  
     * usersubsidy 可用金额
     * ordersubsidy  订单可使用
     * oddorders 本次剩余
     */
    public static function getusermoney($user_id,$total){
        //echo $total;die;366663.00
        $usersubsidy=self::findFirstByuser_id($user_id);
        if($usersubsidy){
            $usersubsidys=$usersubsidy->subsidy_left_subsidy;            
        }else{
            $usersubsidys=0;
        }
        //1234321.00
        $ordersubsidy=$total*0.1;
       
        if($ordersubsidy>$usersubsidys){
            $arr["usersubsidy"]=Func::format_money($usersubsidys);
            $arr["ordersubsidy"]=Func::format_money($usersubsidys);
            $arr["oddorders"]=Func::format_money($total-$arr["ordersubsidy"]);
        }else{
            $arr["usersubsidy"]=Func::format_money($usersubsidys);
            $arr["ordersubsidy"]=Func::format_money($total*0.1);
            $arr["oddorders"]=Func::format_money($total-$arr["ordersubsidy"]);
        }      
        return $arr;

    }
    public static function getsubsidy_total($user_id){
       
        $usersubsidy=self::findFirstByuser_id($user_id);
        $amount=$usersubsidy ? $usersubsidy->subsidy_total_amount : 0.00;
        return Func::format_money($amount);

    }
}
