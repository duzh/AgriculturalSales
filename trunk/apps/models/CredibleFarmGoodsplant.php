<?php
namespace Mdg\Models;
use Lib\Pages as Pages;
use Mdg\Models as M;
class CredibleFarmGoodsplant extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $goods_id;

    /**
     *
     * @var integer
     */
    public $goods_name;

    /**
     *
     * @var string
     */
    public $user_id;

    /**
     *
     * @var integer
     */
    public $add_time;

    /**
     *
     * @var string
     */
    public $last_update_time;

    /**
     *
     * @var string
     */
    public $is_delete;
    /**
     * 获取产品种植过程列表
     * @param  array   $where     di组合条件
     * @param  integer $p         [description]
     * @param  [type]  $orderby   [description]
     * @param  integer $page_size [description]
     * @return [type]             [description]
     */
    public static function getprocessList($where = array(), $p =1 ,$orderby='', $page_size = 10) {
        
        $total = self::count( $where );
        $offst = intval(($p - 1) * $page_size);
        $cond[] = $where;
        $cond['order'] = " goods_id desc ";
        $cond['limit'] = array(  $page_size, $offst);

        $data = self::find($cond);
  
        $pages['total_pages'] = ceil($total / $page_size);
        $pages['current'] = $p;
        $pages['total'] = $total;
        
        $pages = new Pages($pages);
        $datas['total'] = $total;
        $datas['items'] = $data;
        $datas['start'] = $offst;
        $datas['pages'] = $pages->show(2);
        return $datas;
    }	

    
}
