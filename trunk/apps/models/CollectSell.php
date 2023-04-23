<?php
namespace Mdg\Models;
use Lib as L;
class CollectSell extends \Phalcon\Mvc\Model
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
    public $sell⁯_id;

    /**
     *
     * @var integer
     */
    public $collect_uid;

    /**
     *
     * @var integer
     */
    public $sell_uid;

    /**
     *
     * @var string
     */
    public $sell_uname;

    /**
     *
     * @var string
     */
    public $goods_name;

    /**
     *
     * @var string
     */
    public $goods_picture;

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
     * 获取供应列表
     * @param  integer $page  	
     * @param  integer $psize 	
     * @param  integer $offst	
     * @param  integer $userId	
     * @return array
     */
    public static function getColSellList( $db, $page=1, $psize=3, $offst, $userId ) {
		
		// 定义数组
        $data	= $sellList = array();
        $total	= self::count("collect_uid = $userId");
        $data	= $db->fetchAll("SELECT id,sell_id,goods_name,goods_picture FROM collect_sell WHERE collect_uid = $userId ORDER BY id desc limit $offst,$psize",2);
		foreach($data as $k=>&$v){
			$sells = $db->fetchAll("SELECT id,uname FROM sell WHERE id='{$v['sell_id']}'",2);
			foreach($sells as $kk=>$vv) {
				if($v['sell_id'] == $vv['id']){
					$v['uname'] 	= $vv['uname'];
				}
			}				
		}
        $pages['total_pages']	= ceil($total / $psize);
        $pages['current']		= $page;
        $pages['total'] 		= $total;
        $pages	= new L\Pages($pages);
        $sellList['total'] = $total;
        $sellList['items'] = $data;
        $sellList['start'] = $offst;
        $sellList['total_pages'] = ceil($total / $psize);
        $sellList['pages'] = $pages->show(2);
		if($sellList){
			return $sellList;
		} else {
			return array();
		}
    }
}
