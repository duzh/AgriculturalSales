<?php
namespace Mdg\Frontend\Controllers;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Lib\Member as Member, Lib\Auth as Auth, Lib\SMS as sms, Lib\Utils as Utils;
use Mdg\Models as M;
use Lib as L;
/**
 * 价格行情
 */

class MarketController extends ControllerBase
{
    /**
     * 分类行情
     * @return [type] [description]
     */
    
    public function indexAction() 
    {
        $p = $this->request->get('p', 'int', 1);
        //获取白菜 趋势图
        $cid = $this->request->get('cid', 'int', $cid = M\MarketAvgprice::selectByNewCateId(1));
        $keyword = $this->request->get('keyword', 'string', '');
        $data = array();
        $encode = 'utf-8';

        //查询分类基本信息
        $VirtualData = array(
            19 => '白菜',
            30 => '土豆',
            67 => '西瓜',
            61 => '柚子',
            117 => '绿豆',
        );

        if ($keyword) 
        {
            //查询数据分类
            $info = M\MarketPrice::getMarketPriceInfo(0, " category_id > 0 AND goods_name like '{$keyword}%'");
            if ($info) { $cid = $info->category_id; }
        }
        else
        {
            $cond[] = " category_id = '{$cid}' ";
            $info = M\MarketPrice::findFirst($cond);
        }
       
        $getMovementsAvg = M\MarketAvgprice::getMovementsAvg($cid, 0, M\MarketAvgprice::WEEK);
        
        if ($getMovementsAvg) 
        {
            
            foreach ($getMovementsAvg as $key => $val) 
            {
                $keys[] = date('Y-m-d', strtotime($val['publish_time']));
                $vals[] = L\Func::format_money($val['avg']);
            }
            $data['keys'] = join("','", $keys);
            $data['vals'] = join(',', $vals);
        }
        
        $class = array(1 => 'shucai', 2=> 'shuiguo', 7 => 'liangyou', 8 => 'shiyongjun',899 => 'qita');
        $sort = array(1 =>1, 2=> 2, 7 =>3, 8 => 4, 899 => 5);

        /** 首页 */
        $cache_key = 'market_home.cache';
        $homeData = $this->cache->get($cache_key);
        
        /* 顶级分类 */
        if (!$homeData) 
        {
            //查询分类
            $catecond[] = " parent_id = 0  and id in (1,2,7,8,899)AND is_show = 1  ";
            $homeData = M\Category::find($catecond)->toArray();
            
            foreach ($homeData as $key => &$val) 
            {
                //检测底下是否有数据
                $homeData[$key]['isChid'] = M\MarketPrice::checkChid($val['id']);
                $title = $val['title'];
                $str_num = mb_strlen($title, $encode);

                $val['className'] =  $str_num > 2 ? $title :  mb_substr($title,0,1, $encode) . mb_substr($title,1,2, $encode) ;
                $_cid = isset($val['id']) ?  intval($val['id']) : 0 ;

                $homeData[$key]['class'] = isset($class[$_cid]) ? $class[$_cid] : 'shucai';
                $homeData[$key]['sort'] = isset($sort[$val['id']]) ? $sort[$val['id']] : '';
            }
            $homeData = L\Arrays::sortByCol($homeData , 'sort' , SORT_ASC);
            $this->cache->save($cache_key, $homeData);
        }
        
        $this->view->orders = $orders = $this->getTimeOrder();
        $this->view->goods_unit = M\Purchase::$_goods_unit;
        $this->view->homeData = $homeData;
        $this->view->keyword = $keyword;
        $this->view->VirtualData = $VirtualData;
        $this->view->cid = $cid;
        $this->view->info = $info;
        $this->view->data = $data;
        $this->view->floor = 1 ;
        $this->view->title = '农产品价格行情-丰收汇';
        $this->view->keywords = '农产品价格行情，丰收汇';
        $this->view->descript = '丰收汇-可靠农产品交易网，为你提供蔬菜价格、水果价格、粮油价格、食用菌价格等农产品价格行情信息。';
    }
    /**
     * 详细分类价格行情
     * @return
     */
    
    public function catelistAction() 
    {

        //查询大分类子报价商品
        $p = $this->request->get('p', 'int', 1);
        $cid = $this->request->get('cid', 'int', 1);
        $keyword = $this->request->get('keyword', 'string', '');

        $flag = true;
        $count = 24;
        $step = 196;

        while ($flag) {
            $time = date('Ymd',strtotime("-{$step} day"));
            $data = M\MarketPrice::getShowCateList($cid , $pid=0,$time, 10, $this->db);
            $count--;
            $step++;
            if($data) $flag=false;
            if(!$count) $flag=false;
        }

        $data = L\Func::groupBy($data , 'abbreviation');
        
        //查询子级分类
        $key = 'market_chid.cache';
        $chid = $this->cache->get($key);
        if (!$chid) 
        {
            $chid = M\Category::getTopCaetList();
            $this->cache->save($key, $chid);
        }
        //热门供应产品
        $hot = new M\Sell();
        $need = 'hot';
        $hot = $hot->getRecoList(0,$need,$limit=5);
       
        //最新行业资讯
        $advisory = M\Advisory::getdayList(0,$limit=5);
      
        //print_r($advisory->toArray());die;
        

        //seo
        $catName =  M\Category::findFirst("id = '{$cid}'")->title;

        $this->view->cid = $cid;
        $this->view->chid = $chid;
        $this->view->data = $data;
        $this->view->hot = $hot;
        $this->view->advisory = $advisory;
        $this->view->title = ''.$catName.'价格行情信息大全_丰收汇-中国权威互联网农产品供求交易平台';
        $this->view->keywords = ''.$catName.'价格,'.$catName.'价格信息,'.$catName.'价格查询,'.$catName.'交易网,'.$catName.'价格信息网';
        $this->view->descript = '丰收汇全国'.$catName.'价格行情信息实时更新,'.$catName.'价格走势分析，'.$catName.'价格变化指数。';
    }
    /**
     * 获取分类详细
     * @param  integer $cid 分类id
     * @return [type]       [description]
     */
    
    public function getAction($cid = 0) 
    {
        $p = $this->request->get('p', 'int', 1);
        $pid = $this->request->get('pid', 'int', 0);
        $cid = $this->request->get('cid', 'int', 0);
        $zzs = $this->request->get('zzs', 'int', 7);
        $keyword = $this->request->get('keyword', 'string', '');
        $args = array(
            'cid',
            'pid',
            'keyword',
            'p',
            'zzs'
        );
        $get = $this->request->get('query');
        $get = $get ? $get : $_SERVER['REQUEST_URI'];
        $query = str_replace('/market/get?', '', $get);
        $pname = '全国';
        $cond = '';
        parse_str($query, $query);
        $url = array();
        
        foreach ($args as $v) 
        {
            
            if (isset($query[$v])) 
            {
                $url[$v] = $query[$v];
            }
        }
        
        if ($keyword) 
        {
            $cond = " category_name = '{$keyword}'";
        }
        $data = M\MarketPrice::getMarketPriceInfo($cid, $cond);
      
        if (!$data) 
        {
            echo "<script>alert('暂无数据');location.href='/market/catelist';</script>";
            exit;
        }
        $url['cid'] = $data->id;
        $url = empty($url) ? '' : http_build_query($url) . '&';
        $url = "/market/get?" . $url;
        //查看分类详细
        /* 查询地区 */
        
        if ($pid) 
        {
            $pname = M\AreasFull::getAreasNametoid($pid);
        }
        /**  平均详情 */
        $getAnalysisAvg = M\MarketAvgprice::getAnalysisAvg($data->category_id, $pid);
        
        /**  产品分布地区 来源*/
        $getMarketSellList = M\MarketPrice::getMarketSellList($data->category_id, $pid, $p);
        /** 产品 所有分布地区 */
        $this->view->getMarketProvince = M\MarketPrice::getMarketProvince($data->category_id);
        /**查询柱形数据*/
        $mid = $data->id;
        $info = M\MarketPrice::getMarketPriceInfo($mid);
        //热门供应产品
        $hot = new M\Sell();
        $hot = $hot->getRecoList($cid=0,'hot',$limit=5);
        
        if (!$info) {
            echo "<script>alert('数据来源错误');location.href='/market/index';</script>";
            exit;
        }


        $xianImgConvals = array();
        $xianImgConkeys = array();
        switch ($zzs) 
        {
        case 1:
            $xianImgContype = M\MarketAvgprice::MONTH;
            break;

        case 3:
            $xianImgContype = M\MarketAvgprice::MARCH;
            break;

        case 6:
            $xianImgContype = M\MarketAvgprice::HALFYEAR;
            break;

        default:
            $xianImgContype = M\MarketAvgprice::WEEK;
            break;
        }

        $xianImgCongetMovementsAvg = M\MarketAvgprice::getMovementsAvg($data->category_id, $pid, $xianImgContype);

        foreach ($xianImgCongetMovementsAvg as $key => $val) 
        {
            $xianImgConkeys[] = date('Y-m-d', strtotime($val['publish_time']));
            $xianImgConvals[] = L\Func::format_money($val['avg']);
        }
        
        $xianImgCon['keys'] = join("','", $xianImgConkeys);
        $xianImgCon['vals'] = join(',', $xianImgConvals);
        
        $this->view->xianImgCon = $xianImgCon;

        $getAllProvinceData = M\MarketAvgprice::getAllProvinceData($data->category_id);
        
        $keys = array_column($getAllProvinceData, 'province_name');
        $vals = array_column($getAllProvinceData, 'today_avgprice');

        $this->view->hot = $hot;
        $this->view->info = $info;
        $this->view->keys = join("','", $keys);
        $this->view->vals = join(',', $vals);
        $this->view->zzs = $zzs;
        $this->view->pid = $pid;
        $this->view->pname = $pname;
        $this->view->url = $url;
        $this->view->getAnalysisAvg = $getAnalysisAvg;
        $this->view->getMarketSellList = $getMarketSellList;
        $this->view->data = $data;
        $this->view->title = ''.$data->goods_name.'价格行情信息大全_丰收汇-中国权威互联网农产品供求交易平台';
        $this->view->keywords = ''.$data->goods_name.'价格,'.$data->goods_name.'价格信息,'.$data->goods_name.'价格查询,'.$data->goods_name.'交易网,'.$data->goods_name.'价格信息网';
        $this->view->descript = '丰收汇全国'.$data->goods_name.'价格行情信息实时更新,'.$data->goods_name.'价格走势分析，'.$data->goods_name.'价格变化指数。';
    }
    /**
     * 首页 楼层分类
     * @param  integer $cid 大分类id
     * @return
     */
    
    public function gethomecatelistAction($cid = 0) 
    {

        //查询分类
        $cond[] = " parent_id = 0 AND is_show = 1  ";
        $data = M\Category::find($cond)->toArray();
        
        foreach ($data as $key => $val) 
        {
            //检测底下是否有数据
            $data[$key]['isChid'] = M\MarketPrice::checkChid($val['id']);
        }
        $this->view->is_ajax = 1;
        $this->view->data = $data;
    }
    /**
     * 获取热销产品
     * @return [type] [description]
     */
    
    public function gethotcateAction($cid = 0) 
    {
        $key = "market_hot_{$cid}.cache";
        $data = $this->cache->get($key);
        
        if (!$data) 
        {
            $data = M\MarketPrice::getHotCateList($cid, 33);
            $this->cache->save($key, $data);
        }
        $this->view->is_ajax = 1;
        $this->view->data = $data;
    }

    
    /**
     * 分析图
     * @param  integer $cid 分类id
     * @return
     */
    
    public function getanalysisAction($cid = 0, $mid = 0, $pid = 0) 
    {
        //查询分类一周数据
        //查看分类详细
        $info = M\MarketPrice::getMarketPriceInfo($mid);
        
        if (!$info) exit('数据异常');
        $data = M\MarketAvgprice::getAllProvinceData($cid);
        $keys = array_column($data, 'province_name');
        $vals = array_column($data, 'today_avgprice');
        $this->view->info = $info;
        $this->view->keys = join("','", $keys);
        $this->view->vals = join(',', $vals);
    }
    /**
     * 折现分析图
     * @param  integer $cid 分类id
     * @return array
     */
    
    public function foldAction($cid = 0, $mid = 0, $pid = 0, $zzs = 0) 
    {

        //查看分类详细
        $info = M\MarketPrice::getMarketPriceInfo($mid);
        if (!$info) exit('数据异常');
        $vals = array();
        $keys = array();
        
        switch ($zzs) 
        {
        case 1:
            $type = M\MarketAvgprice::MONTH;
            break;

        case 3:
            $type = M\MarketAvgprice::MARCH;
            break;

        case 6:
            $type = M\MarketAvgprice::HALFYEAR;
            break;

        default:
            $type = M\MarketAvgprice::WEEK;
            break;
        }
        $getMovementsAvg = M\MarketAvgprice::getMovementsAvg($cid, $pid, $type);
        foreach ($getMovementsAvg as $key => $val) 
        {
            $keys[] = date('Y-m-d', strtotime($val['publish_time']));
            $vals[] = L\Func::format_money($val['avg']);
        }
        
        $data['keys'] = join("','", $keys);
        $data['vals'] = join(',', $vals);
        $this->view->is_ajax = 1;
        $this->view->data = $data;
        $this->view->info = $info;
    }
    /**
     * 搜索产品信息
     * @return [type] [description]
     */
    
    public function searchAction() 
    {
        $keyword = $this->request->get('q', 'string', '');
        $cond[] = " category_name like '%{$keyword}%' AND category_id > 0 ";
        $cname = '';
        // $cond['limit'] = array(20, 1 );
        $cond['order'] = ' add_time asc ';
        $cond['columns'] = 'category_name,goods_name , id , category_id ,sell_id';
        $cond['group'] = 'category_id';
        $data = M\MarketPrice::find($cond)->toArray();

        $result = array();
        
        foreach ($data as $key => $val) 
        {
            array_push($result, array(
                "name" => $val['goods_name'],
            ));
        }
        exit(json_encode($result));
    }
    /**
     * 实时行情
     * @return [type] [description]
     */
    
    public function getTimeOrder() 
    {
        $cond[] = " state >=4 ";
        $cond['order'] = 'addtime DESC ';
        $cond['limit'] = 4;

        $orders = M\Orders::find($cond)->toArray();
        foreach ($orders as & $ord) 
        {
            $time = new L\Time(time() , $ord['addtime']);
            $ord['pubtime'] = $time->transform();
            $ord['areas_name'] = Utils::getC($ord['areas_name']);
        }
        return $orders;
    }
}
