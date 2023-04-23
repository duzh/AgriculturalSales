<?php
namespace Mdg\Models;
use Lib as L;
use Lib\Time as Time;
use Lib\Pages as Pages;
class CollectPurchase extends Base
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
    public $purchase_id;

    /**
     *
     * @var integer
     */
    public $purchase_uid;

    /**
     *
     * @var integer
     */
    public $collect_uid;

    /**
     *
     * @var string
     */
    public $areas_name;

    /**
     *
     * @var string
     */
    public $purchase_uname;

    /**
     *
     * @var integer
     */
    public $publish_time;

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
     * 获取采购列表
     * @param  integer $page  	
     * @param  integer $psize 	
     * @param  integer $offst	
     * @param  integer $userId	
     * @return array
     */
    public static function getPurchaseList( $db, $page=1, $psize=3, $offst, $userId ) {
		
		// 定义数组
        $data	= $purList = array();
		// 计算总条数
        $total	=self::count("collect_uid = $userId");
		// 获取数据
		$data	= $db->fetchAll("SELECT id,purchase_id,areas_name,publish_time,add_time FROM collect_purchase WHERE collect_uid = $userId order by id desc limit $offst,$psize",2);
		foreach($data as $k=>&$v){
			// 发布时间
			$time = new Time(CURTIME, $v['publish_time']);
			$v['publish_time'] = $time->transform();
			
			$purs = $db->fetchAll("SELECT id,is_del,state,title,endtime,username FROM purchase WHERE id='{$v['purchase_id']}'",2);
            if($purs){
				foreach($purs as $kk=>$vv) {
					if($v['purchase_id'] == $vv['id']){
						$v['title'] 	= $vv['title'];
						$v['endtime']	= $vv['endtime'];
						$v['username']	= $vv['username'];
						$v['is_del']	= $vv['is_del'];
						$v['state']		= $vv['state'];
					}
				}
				if( CURTIME > $v['endtime'] || $v['is_del'] == 1 || $v['state'] == 0 || $v['state'] == 2 ){
					$v['flag']	= 0;//不提供报价
				} else {
					$v['flag']	= 1;//提供报价
				}
			} else {
				$v['title']	= '';
				$v['flag']	= 0;//不提供报价
			}
		}
       
		$pages['total_pages']	= ceil($total / $psize);
        $pages['current']		= $page;
        $pages['total'] 		= $total;
        $pages	= new Pages($pages);
        $purList['total'] = $total;
        $purList['items'] = $data;
        $purList['start'] = $offst;
        $purList['total_pages'] = ceil($total / $psize);
        $purList['pages'] = $pages->show(2);
		if($purList){
			return $purList;
		} else {
			return array();
		}
    }
}
