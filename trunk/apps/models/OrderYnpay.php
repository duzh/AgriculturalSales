<?php

namespace Mdg\Models;
use Lib\Pages as Pages;
use Mdg\Models as M;

class OrderYnpay extends \Phalcon\Mvc\Model
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
     * @var string
     */
    public $pay_type;

    /**
     *
     * @var string
     */
    public $serial_num;

    /**
     *
     * @var integer
     */
    public $order_date;

    /**
     *
     * @var integer
     */
    public $pay_date;

    /**
     *
     * @var double
     */
    public $order_amount;

    /**
     *
     * @var integer
     */
    public $order_status;

    /**
     *
     * @var integer
     */
    public $add_time;

    /**
     *
     * @var integer
     */
    public $order_name;

    /**
     *
     * @var integer
     */
    public $last_update_time;

    /**
     * 获取标签列表
     * @param  array   $where     di组合条件
     * @param  integer $p         [description]
     * @param  [type]  $orderby   [description]
     * @param  integer $page_size [description]
     * @return [type]             [description]
     */
    public static function getOrderYnpList($where = array(), $p =1 ,$orderby='', $page_size = 10) {
        
        $total = self::count( $where );
        $offst = intval(($p - 1) * $page_size);
        $cond[] = $where;
        $cond['order'] = " id desc ";
        $cond['limit'] = array(  $page_size, $offst);

        $data = self::find($cond);
  
        $pages['total_pages'] = ceil($total / $page_size);
        $pages['current'] = $p;
        $pages['total'] = $total;
        
        $pages = new Pages($pages);
        $datas['total'] = $total;
        $datas['items'] = $data;
        $datas['start'] = $offst;
        $datas['pages'] = $pages->show(1);
        return $datas;
    }
}
