<?php
namespace Mdg\Models;
use Lib as L;

class Subsidy extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $subsidy_id;

    /**
     *
     * @var string
     */
    public $subsidy_no;

    /**
     *
     * @var double
     */
    public $subsidy_amount;

    /**
     *
     * @var integer
     */
    public $subsidy_type;

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
    public $status;

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

    public static $_subsidy_type =  array(0 => '交易补贴',  1 => '审核补贴' ,  2 => '退款审核' ,3=>'追加补贴');
    public static $_status =  array( 0 =>  '待审核',  1 => '审核通过',  2 => '审核不通过' );
    public static function getSubsidyList($where = '' , $p =1, $psize=10) {

            $total = self::count( $where );
            $offst = intval(($p - 1) * $psize);
            $cond[] = $where;
            $cond['order'] = " subsidy_id desc ";
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

}
