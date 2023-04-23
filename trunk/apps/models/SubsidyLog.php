<?php
namespace Mdg\Models;
use Lib as L;

class SubsidyLog extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $log_id;

    /**
     *
     * @var integer
     */
    public $user_id;

    /**
     *
     * @var double
     */
    public $amount;

    /**
     *
     * @var integer
     */
    public $add_time;

    /**
     *
     * @var string
     */
    public $demo;

   
    public static function getSubsidyLogList($where = '' , $p =1, $psize=10) {

            $total = self::count( $where );
            $offst = intval(($p - 1) * $psize);
            $cond[] = $where;
            $cond['order'] = " log_id desc ";
            $cond['limit'] = array(  $psize, $offst);

            $data = self::find($cond);
      
            $pages['total_pages'] = ceil($total / $psize);
            $pages['current'] = $p;
            $pages['total'] = $total;
            
            $pages = new L\Pages($pages);
            $datas['total_count'] =ceil($total / $psize);
            $datas['total'] = $total;
            $datas['items'] = $data;
            $datas['start'] = $offst;
            $datas['pages'] = $pages->show(2);
            return $datas;
    }

}
