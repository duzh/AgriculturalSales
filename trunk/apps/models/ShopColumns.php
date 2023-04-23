<?php
namespace Mdg\Models;
use Lib\Pages as Pages;
class ShopColumns extends \Phalcon\Mvc\Model
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
    public $shop_id;

    /**
     *
     * @var integer
     */
    public $col_id;

    /**
     *
     * @var integer
     */
    public $col_pid;

    /**
     *
     * @var string
     */
    public $col_link;

    /**
     *
     * @var string
     */
    public $col_name;

    /**
     *
     * @var integer
     */
    public $add_time=0;
    public $col_comment='';

    /**
     *
     * @var integer
     */
    public $last_update_time=0;
    public $is_delete = 0;

    const MAXCOUNT = 5; #店铺栏目上限 

    public function beforeSave() {
        $this->last_update_time = time();
    }
     /**
     * 获取栏目列表
     * @param  array   $where     di组合条件
     * @param  integer $p         [description]
     * @param  [type]  $orderby   [description]
     * @param  integer $page_size [description]
     * @return [type]             [description]
     */
    public static function getList($where = array(), $p =1 ,$orderby, $page_size = 10) {

        $total = self::count($where);
        $offst = intval(($p - 1) * $page_size);
        $data = self::find($where." $orderby  limit {$offst} , {$page_size} ")->toArray();

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
    
    /**
     * 获取 店铺所有栏目
     * @param  integer $sid [description]
     * @return [type]       [description]
     */
    public static function getAllColumns($sid=0 , $where  = ' 1  ') {
        $data = self::find(" {$where} AND is_delete = 0 and col_pid = 0 AND shop_id = '{$sid}' ");
        return $data;
    }
    /**
     * 获取栏目名称
     * @param  integer $cid 栏目id
     * @return string
     */
    public static function getColumnName($cid=0) {
        $data = self::findFirst(" id = '{$cid}'");
        return $data ? $data->col_name : '-';
    }

    /**
     * 获取栏目的子栏目
     * @param  integer $sid [description]
     * @return [type]       [description]
     */
    public static function getchildrencolumns($id=0,$shop_id=0) {
        $data = self::find(" id = '{$id}' AND shop_id = '{$shop_id}'")->toArray();
        foreach($data as $k=>$v){
            $data['$k']['col_pid'] = $v['col_pid'];
        }
        if($v['col_pid']){
            return true;
        }else{
            return false;
        }
    }




}
