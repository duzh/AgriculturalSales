<?php
namespace Mdg\Models;
use Mdg\Models as M;
use Lib as L;


class MarketAvgprice extends \Phalcon\Mvc\Model
{
    CONST LimitTime = 10;

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var double
     */
    public $today_avgprice;

    /**
     *
     * @var double
     */
    public $yesterday_avgprice;

    /**
     *
     * @var integer
     */
    public $province_id;

    /**
     *
     * @var string
     */
    public $province_name;

    /**
     *
     * @var integer
     */
    public $category_id;

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
     *
     * @var string
     */
    public $unit;

    /**
     * 获取当天全国 均价
     * @param  integer $cid [description]
     * @return [type]       [description]
     */
    public static function getCateAvgPrice($cid=0, $pid=0) {
        /* 取出最后一天平均价  */
        $time = date('H') < self::LimitTime ? date('Ymd', strtotime('-1 day')) : date('Ymd');
        $cond[] = " province_id = '{$pid}' AND category_id = '{$cid}' ";
        $cond['columns'] = 'today_avgprice';
        $cond['group'] = "publish_time, category_id";
        $cond['order'] = "publish_time desc ";
        $cond['limit'] = 1 ;
        $data = self::findFirst($cond);
       
        $return = $data ? $data->today_avgprice : 0 ;  
        return L\Func::format_money($return);
    }
    
    /**
     * 获取全国 昨天平均值
     * @param  integer $cid 分类id
     * @return folat
     */
    public static function getCateYesterAvgPrice($cid=0,$pid=0) {
        $yesterday_avgprice = 0;
        /* 昨天均价 */
        $yesterday =  " category_id = '{$cid}'  AND province_id = '{$pid}' ";
        $condday[] = $yesterday;
        $condday['limit'] = 2;
        $condday['group'] ='publish_time';
        $condday['order'] ='publish_time DESC ';
        $condday['columns'] = " sum(total) as sum  , sum(amount) as amount";
        $rs = self::find($condday)->toArray();
        if(isset($rs[1]) && $rs[1] ) {
            if(isset($rs[1]['sum']) && $rs[1]['sum']) {
                $yesterday_avgprice =  $rs[1]['amount'] /$rs[1]['sum']  ;
            }
        }
        
        return L\Func::format_money($yesterday_avgprice);
    }
    
    /**
     * 获取分类分析价
     * @param  integer $cid 分类id
     * @param  integer $pid 地区id 0 全国
     * @return array( '0'=> 全国, 1 =>'一周', '2' => '昨天' , 3 =>'今天', 4 百分比)
     */
    public static function getAnalysisAvg($cid=0,$pid=0) {
        $where[] = " category_id = '{$cid}'";
        $yesterday_avgprice =0;
        $w_sum              =0;
        $a_sum              =0;
        $day_avgprice       =0;
        $where[] = "  province_id = '{$pid}'";
       
        /* 最新均价 */
        $cond[] =implode(' AND ', $where);
        $rs = self::findFirst( array(   " {$cond[0]}  ", 'columns' => ' today_avgprice ', 'order' => 'id desc ', 'group' => 'publish_time'));
        if($rs){
                $day_avgprice =  $rs->today_avgprice ;
        }

        /* 昨天均价 */
        $yesterday =  " {$cond[0]}  ";
        $condday[] = $yesterday;
        $condday['limit'] = 2;
        $condday['group'] ='publish_time';
        $condday['order'] ='publish_time DESC ';
        $condday['columns'] = " sum(total) as sum  , sum(amount) as amount";
        $rs = self::find($condday)->toArray();
        if(isset($rs[1]) && $rs[1] ) {
            if(isset($rs[1]['sum']) && $rs[1]['sum']) {
                $yesterday_avgprice =  $rs[1]['amount'] /$rs[1]['sum']  ;
            }
        }

        /* 一周均价 */
        $stime = date('Ymd' , (strtotime('-1week')) );
        $yesterweek =  " {$cond[0]} AND publish_time >= '{$stime}' "  ;
        
        $week_total = self::sum(array($yesterweek, 'column' => 'total' ) );
        $week_sum   = self::sum(array($yesterweek, 'column' => 'amount' ) );
        if($week_sum) {
            $w_sum = $week_sum / $week_total;
        }
        
        /* 全部均价  */
        $totime = date('Ymd');
        $allcond =  " category_id = '{$cid}' AND province_id = {$pid}";
        $all_total = self::sum(array($allcond, 'column' => 'total' ) );
        $all_sum   = self::sum(array($allcond, 'column' => 'amount' ) );
        if($all_sum) {
            $a_sum = $all_sum / $all_total;
        }
        
        return array( 
                L\Func::format_money($a_sum), 
                L\Func::format_money($w_sum),
                L\Func::format_money($yesterday_avgprice),
                L\Func::format_money($day_avgprice),
                );
    }
    /** 周 */
    CONST WEEK = 1 ;
    /** 月 */
    CONST MONTH = 2 ;
    /** 三月 */
    CONST MARCH = 3;
    /** 年 */
    CONST HALFYEAR = 4;

    
    /**
     * 走势图数据 
     * @param  integer $cid  分类id
     * @param  integer $pid  地区id 0 全国
     * @param  integer $type 类型 1 7天 ， 2 一月 ，3 三月 4 半年
     * @return array
     */
    public static function getMovementsAvg($cid=0, $pid=0, $type=1) {
        
        switch ($type) {
            case self::WEEK:
                $time = date('Ymd' , strtotime( ' -1week'));
                break;
            case self::MONTH:
                $time =  date('Ymd' , strtotime( ' -1month'));
                break;
            case self::MARCH:
                $time =  date('Ymd' , strtotime( ' -3month'));
                break;
            case self::HALFYEAR:
                $time =  date('Ymd' , strtotime( ' -6month'));
                break;
        }
      
        $where[] = " category_id = '{$cid}' AND publish_time >= '{$time}' AND province_id = '{$pid}' ";
        if($pid) {
            $where[] = " province_id = '{$pid}'";
        }

        $cond[] = implode( ' AND ', $where);
        $cond['columns'] = "  sum(total) as  total , sum(amount) as amount ,publish_time,province_id  ";
        $cond['order'] = 'publish_time ASC';
        $cond['group'] = 'publish_time';
        
        $data = self::find($cond)->toArray();

        foreach ($data as $key => $val) {
            $data[$key]['avg'] = L\Func::format_money($val['amount'] / $val['total']);
        }
        ksort($data);
       
        return $data;
    }

    /**
     * 获取 全国 分类全国价格
     * @param  integer $cid 分类id
     * @return array
     */
    public static function getAllProvinceData($cid=0) {
        /** 拼接时间条件 */

        $cond[] = " category_id = '{$cid}' AND province_id > 0 ";
        $cond['columns'] = " id, category_id, province_id , sum(total) as total , sum(amount) as amount ";
        $cond['group'] = 'province_id';
        $cond['order'] = ' id desc ';

        $data = self::find($cond)->toArray();
        
        foreach ($data as $key => $val) {
            $data[$key]['province_name'] = M\AreasFull::getAreasNametoid($val['province_id']);
            $data[$key]['today_avgprice'] = L\Func::format_money($val['amount'] / $val['total']);
        }
        return $data;
    }

    /**
     * 获取产品百分比
     * @param  integer $cid 分类id
     * @return 
     */
    public static function getCateAvg($cid=0 , $pid=0) {

        list(,,$yesterday_avgprice,$today_avgprice) = self::getAnalysisAvg($cid, $pid);
        // if($today_avgprice <= 0 ){ return "-".$yesterday_avgprice;}
        // if($yesterday_avgprice <=0) { return '100';}
        $yest = $today_avgprice  - $yesterday_avgprice;
        if($yest == 0 ) return 0;
        if($yesterday_avgprice <=0) return '100'; 
        $data = $yest / $yesterday_avgprice * 100;
   
        return intval($data);
    }

    /**
     * 获取最新分类id
     * @return [type] [description]
     */
    public static function selectByNewCateId($limit=1) {
        $cond[] = " yesterday_avgprice != today_avgprice  ";
        $cond['columns'] = 'category_id ';
        $cond['limit'] = $limit;
        $data = self::find($cond)->toArray();

        return $data ? $data[0]['category_id'] : 30;
    }


    public static function selectHome($cid =0 , $pid = 0 , $time='',$limit = 10 , $db=null) {
       
        $sql = " SELECT c.abbreviation , a.publish_time,c.`title`,c.`parent_id`,category_id,today_avgprice,yesterday_avgprice,
        ((today_avgprice-yesterday_avgprice)/yesterday_avgprice*100) AS ppp FROM `market_avgprice` AS a 
        LEFT JOIN category AS c ON c.`id` = a.`category_id` WHERE publish_time = '{$time}'  
        AND c.`abbreviation` != '' AND ((today_avgprice-yesterday_avgprice)/yesterday_avgprice*100)  != 0 AND c.`parent_id` = '{$cid}' AND  a.province_id = '{$pid}'  ORDER BY ppp DESC   limit {$limit}";
        
        $data = $db->fetchAll($sql,2);
        return $data;

    }
   


    
   

}
