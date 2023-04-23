<?php
namespace Mdg\Models;
use Lib as L;

class SubsidyPay extends \Phalcon\Mvc\Model
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
    public $order_id;

    /**
     *
     * @var string
     */
    public $order_no;

    /**
     *
     * @var integer
     */
    public $pay_way;

    /**
     *
     * @var double
     */
    public $pay_amount;

    /**
     *
     * @var integer
     */
    public $pay_time;

    /**
     *
     * @var integer
     */
    public $user_id;

    /**
     *
     * @var string
     */
    public $user_name;

    /**
     *
     * @var string
     */
    public $user_phone;

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

    public static $_pay_way = array( 0 => '丰收汇',  1 => '农资汇' );

    public static function getSubsidyPayList($where = '' , $p =1, $psize=10) {

            $total = self::count( $where );
            $offst = intval(($p - 1) * $psize);
            $cond[] = $where;
            $cond['order'] = " id desc ";
            $cond['limit'] = array(  $psize, $offst);

            $data = self::find($cond);
            $pages['total_pages'] = ceil($total / $psize);
            $pages['current'] = $p;
            $pages['total'] = $total;
            
            $pages = new L\Pages($pages);
            $datas['total'] = $total;
            $datas['items'] = $data;
            $datas['start'] = $offst;
            $datas['pages'] = $pages->show(1);
            return $datas;
    }
    public static function  checkSubsidy($order_id){
       $order_id=self::findFirstByorder_id($order_id);
       if($order_id){
        return true;
       }else{
        return false;
       }
    }
    public static function checkis_close($order_sn){
         $count=SubsidyLog::count("demo like   '%{$order_sn}%' ");
         if($count>1){
            return true;
         }else{
            return false;
         }
    }

}
