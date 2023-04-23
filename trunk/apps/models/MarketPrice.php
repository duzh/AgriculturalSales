<?php
namespace Mdg\Models;
use Mdg\Models AS M;
use Lib as L;
class MarketPrice extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $contact_name;

    /**
     *
     * @var string
     */
    public $contact_phone;

    /**
     *
     * @var integer
     */
    public $category_id;

    /**
     *
     * @var string
     */
    public $category_name;

    /**
     *
     * @var string
     */
    public $goods_name;

    /**
     *
     * @var integer
     */
    public $province_id;

    /**
     *
     * @var integer
     */
    public $city_id;

    /**
     *
     * @var integer
     */
    public $district_id;

    /**
     *
     * @var string
     */
    public $market_name;

    /**
     *
     * @var string
     */
    public $analyze;

    /**
     *
     * @var integer
     */
    public $publish_time;

    /**
     *
     * @var integer
     */
    public $collect_type;

    /**
     *
     * @var double
     */
    public $price;

    /**
     *
     * @var integer
     */
    public $source;

    /**
     *
     * @var integer
     */
    public $sell_id;

    /**
     *
     * @var integer
     */
    public $add_time;


    public function afterFetch() {

        $time = new L\Time( time() , $this->publish_time);
        $this->addtime = $time->transform();
    }
    /**
     * 获取首页分类数据
     * @param  integer $cid 大 分类id
     * @return [type]       [description]
     */
    public static function getHomeList($cid, $cond = array()) {
        $data = self::find($cond)->toArray();
        return $data;
    }
    /**
     * 获取首字母
     * @return [type] [description]
     */ 
    private static function getMarketLetter() {
        $cond[] = " firstname !='' ";
        $cond['columns'] = 'firstname';
        $cond['group'] = 'firstname';
        $cond['order'] = 'firstname ASC';
        // $cond['limit'] =  array(11,1);
        $data = self::find($cond);
        return $data;
    }
    /**
     * 获取分类页数
     * @return [type] [description]
     */
    public static function getShowCateList($cid =0 , $pid = 0 , $time='', $limit=10, $db=null) {
        $sql = " SELECT mp.`id` AS mpid , a.unit,c.id, c.abbreviation , a.publish_time,c.`title`,c.`parent_id`,a.category_id,a.today_avgprice,a.yesterday_avgprice,
        ((today_avgprice-yesterday_avgprice)/yesterday_avgprice*100) AS ppp FROM `market_avgprice` AS a 
        LEFT JOIN category AS c ON c.`id` = a.`category_id` JOIN market_price AS mp ON mp.`category_id` = a.`category_id` 
        WHERE a.publish_time = '{$time}'  
        AND ((today_avgprice-yesterday_avgprice)/yesterday_avgprice*100)  != 0 AND 
        c.`parent_id` = '{$cid}' AND  a.province_id = '{$pid}'   AND c.`abbreviation` != ''
        GROUP BY c.abbreviation,c.id ORDER BY c.abbreviation,c.id ASC ";
        
        $data = $db->fetchAll($sql,2);

        foreach ($data as &$val) {
            $val['ppp'] = L\Func::format_money($val['ppp']);
            $val['diff'] = L\Func::format_money($val['today_avgprice'] -  $val['yesterday_avgprice']);
            $val['sort'] = str_replace('-', '', $val['ppp']);
        }
        L\Arrays::sortByCol($data , 'abbreviation', SORT_ASC);
        return $data;
        
    }

    /**
     * 获取分类全国平均价格
     * @param  integer $cid 分类id
     * @return 
     */
    public static function getAllPriceAvg($cid=0) {
        $total = self::count(array("category_id = '{$cid}'"));
        $sum = self::sum(array("category_id = '{$cid}'", 'column' => 'price'));
        if($sum<=0 || $total <=0) return 0;
        return L\Func::format_money($sum / $total);

    }
    /**
     * 获取发 产品分布地区
     * @param  integer $cid [description]
     * @return [type]       [description]
     */
    public static function getMarketProvince ($cid=0) {
        $cond[] = " category_id = '{$cid}'";
        $cond['group'] = 'province_id';
        $cond['order'] = ' id ASC, add_time ASC ';
        $cond['columns'] = " province_id";

        $data = self::find($cond)->toArray();
        foreach ($data as $key => $val) {

            $data[$key]['province_name'] = M\AreasFull::getAreasNametoid($val['province_id']);
        }
        return $data;

    }
    /**
     * 获取分类来源 
     * @param  integer $cid       分类id
     * @param  integer $pid       地区  0 全国 
     * @param  integer $p         分页
     * @param  integer $page_size 条数
     * @return array
     */
    public static function getMarketSellList($cid=0, $pid=0, $p=1, $page_size=10) {

        $cond[] = " category_id = '{$cid}'";
        if($pid) {
            $cond[0] .=  " AND province_id = '{$pid}'";
        }
        $total = self::count($cond);
       
        $cond['order'] = ' publish_time DESC  ';
        $offst = ($p-1) * $page_size;
        $cond['limit'] = array(  $page_size,$offst);
        $data = self::find($cond);
         
        $pages['total_pages'] = ceil($total / $page_size);
        $pages['current'] = $p;
        $pages['total'] = $total;
        
        $pages = new L\Pages($pages);
        $datas['total'] = $total;
        $datas['items'] = $data;
        $datas['start'] = $offst;
        $datas['pages'] = $pages->show(1);
        return $datas;
    }

    /**
     * 查询分类当前价格趋势
     * @param  integer $cid 分类id
     * @return arary
     */
    public function getDayCateGroupPrice($cid=0) {
        $cond[] = " category_id = '{$cid}'";
        $cond['group'] = 'province_id';
        $data = self::find($cond);
        return $data;
    }
    /**
     * 获取分类详细信息
     * @param  integer $cid  分类id
     * @param  string  $cond 条件 
     * @return array
     */
    public static function getMarketPriceInfo($cid=0,$conditions='') {
        if($conditions) {
            $cond[] = $conditions;
        }else{
            $cond[] = " id = '{$cid}'";
        }
        
        $data = self::findFirst($cond);
        return $data;
    }

    /**
     * 获取价格行情基本信息
     * @param  array   $where     条件
     * @param  integer $p         分页
     * @param  [type]  $orderby   排序
     * @param  integer $page_size 条数
     * @return array
     */
    
    public static function getMarketPriceList($where = array() ,  $p = 1, $db=null , $page_size = 20 ) 
    {

        $total=self::count($where);
        $offst = ( $p - 1 ) * $page_size;
        $data = self::find($where. " order by add_time desc limit {$offst} , {$page_size} ")->toArray();
        foreach ($data as $key => $value) {
            $data[$key]['province_name']=M\AreasFull::getAreasNametoid($value['province_id']);
            if($value['city_id']){
                 $data[$key]['city_name'] = M\AreasFull::getAreasNametoid($value['city_id']);
            }else{
                $data[$key]['city_name'] = '';
            }
            if($value['district_id']){
                 $data[$key]['district_name'] = M\AreasFull::getAreasNametoid($value['district_name']);
            }else{
                $data[$key]['district_name'] = '';
            }
        }
        $pages['total_pages'] = ceil($total / $page_size);
        $pages['current'] = $p;
        $pages['total'] = $total;
        $pages = new L\Pages($pages);
        $datas['total'] = $total;
        $datas['items'] = $data;
        $datas['start'] = $offst;
        $datas['pages'] = $pages->show(1);
        return $datas;
    }

    /**
     * 获取top 数据
     * @param  integer $cid   分类id 
     * @param  [type]  $db    数据db
     * @param  boolean $isapi 是否手机端   1 是 0 否
     * @return array
     */
    public static function getTopCateList ($cid=0,$db=null, $isapi=false) {
        $time = strtotime(date("Y-m-d" , time()));
        $yesttime = date('Ymd' , strtotime('-1day'));
        
      
        $cond[] = " sell_id = '{$cid}'  ";
        $limit = 10;
        $province_id = 0 ;
        $sql = " SELECT m.`category_id`,m.`today_avgprice`,m.`yesterday_avgprice`, c.title  AS goods_name FROM `market_avgprice` AS m JOIN category AS c 
                WHERE c.`id` = m.`category_id`  AND c.parent_id = '{$cid}' AND province_id = '{$province_id}' GROUP BY category_id  ORDER BY today_avgprice  DESC ,publish_time DESC LIMIT {$limit} ";
        $data = $db->fetchAll($sql,2);

        foreach ($data as $key => &$val) {

            $val['per']     = M\MarketAvgprice::getCateAvg($val['category_id']);
            $val['sortper']     = str_replace('-', '', M\MarketAvgprice::getCateAvg($val['category_id']));
            $Avgprice       = M\MarketAvgprice::getCateAvgPrice($val['category_id']);
            $YesterAvgPrice = M\MarketAvgprice::getCateYesterAvgPrice($val['category_id']);
            $val['todayprice']     = $Avgprice;
            $val['YesterAvgPrice'] = $YesterAvgPrice;
            list($difference , $rest ) = self::format_difference($YesterAvgPrice - $Avgprice );
            $val['difference'] = $difference;
            $val['pname'] = M\Category::getparent($val['category_id']);
           
        }
        
        $data = L\Arrays::sortByCol($data , 'sortper', SORT_DESC );
 
        // $sql = " SELECT  m.price,m.goods_name , m.id , m.`category_id` , m.`category_name`, ma.`province_name` , ma.`province_id` , ma.`today_avgprice` 
        //         FROM 
        //         `market_price` AS m , `market_avgprice` AS ma WHERE 
        //         ma.`category_id` = m.`category_id` AND ma.`publish_time` = '{$time}'  AND m.`sell_id` = {$cid}  GROUP BY  m.category_id ORDER BY ma.`today_avgprice`  LIMIT 10;
        //         ";
        // $data = $db->fetchAll($sql,2);
        return $data;
    }
    /**
     * 获取分类hot10
     * @param  integer $cid 分类id
     * @return array
     */
    public static function getHotCateList ($cid=0 , $limit=33) {
        $cond[] = " sell_id = '{$cid}'";
        $cond['limit'] = $limit;
        $cond['group'] = 'category_id';
        $cond['order'] = 'price ASC ';
        $data = self::find($cond)->toArray();
        foreach ($data as $key => &$val) {

            $val['per']     = M\MarketAvgprice::getCateAvg($val['category_id']);

            $Avgprice       = M\MarketAvgprice::getCateAvgPrice($val['category_id']);
            $YesterAvgPrice = M\MarketAvgprice::getCateYesterAvgPrice($val['category_id']);
            $val['difference'] = L\Func::format_money($YesterAvgPrice - $Avgprice );
          
        }
        return $data;
    }
    
    /**
     * 检测分类 地下是否用友产品
     * @param  integer $sid 分类id
     * @return boolean
     */
    public static function checkChid ($sid=0) {
        $time = strtotime(date('Y-m-d'));
        $cond[] = " sell_id = '{$sid}'";
        if($sid == '1377') return 0 ;
        return self::count($cond);
    }


    /**
     * 格式化均价差
     * @param  integer $price 价格
     * @return 
     */
    public static  function format_difference($price=0) {

        if($price < 0 ) {
            $rees = 0;
            $difference = str_replace('-', '', $price);
        }else if(intval($price)  == intval(0) ){
            $rees = 1;
            $difference = $price;
        }else{
            $rees = 2;
            $difference = $price;
        }

        return array(L\Func::format_money($difference) , $rees);
    }

}
