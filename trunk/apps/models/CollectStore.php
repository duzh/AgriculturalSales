<?php
namespace Mdg\Models;
use Lib as L;
use Lib\Time as Time;
use Lib\Pages as Pages;
class CollectStore extends Base
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
    public $store_id;

    /**
     *
     * @var integer
     */
    public $store_name;

    /**
     *
     * @var integer
     */
    public $main_products;

    /**
     *
     * @var string
     */
    public $collect_uid;

    /**
     *
     * @var string
     */
    public $add_time;

    /**
     *
     * @var integer
     */
    public $last_update_time;


    /**
     * 获取收藏的可信农场列表
     * @param  integer $page    
     * @param  integer $psize   
     * @param  integer $offst   
     * @param  integer $userId  
     * @return array
     */
    public static function getColFarmList( $db, $page=1, $psize=10, $offst, $userId ) {
        
        // 瀹氫箟鏁扮粍
        $data   = $sellList = array();
        $total  = self::count("collect_uid = $userId");
        $sql ="SELECT id,store_id,store_name,main_products FROM collect_store WHERE collect_uid = $userId ORDER BY add_time desc limit $offst,$psize";

        $data   = $db->fetchAll($sql,2);
        foreach($data as $k=>&$v){
            $url = $db->fetchOne("SELECT url FROM credible_farm_info WHERE id='{$v['store_id']}'",2);
            $data[$k]['url'] = $url['url'];           
        }
        $pages['total_pages']   = ceil($total / $psize);
        $pages['current']       = $page;
        $pages['total']         = $total;
        $pages  = new L\Pages($pages);
        $sellList['total_count'] = ceil($total / $psize);
        $sellList['total'] = $total;
        $sellList['items'] = $data;
        $sellList['start'] = $offst;
        $sellList['pages'] = $pages->show(2);
        return $sellList;
        // if($sellList){
        //     return $sellList;
        // } else {
        //     return array();
        // }
    }
	
}
