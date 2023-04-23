<?php
namespace Mdg\Frontend\Controllers;
use Lib\Member as Member;
use Lib\Auth as Auth, Lib\Arrays as Arrays;
use Mdg\Models as M;
use Mdg\Models\Category as Category;
use Lib as L;

class IndexController extends ControllerBase
{
    
    private $key = 'riwqe89712hjzxc8970230651mlk';
    
    public function indexAction() 
    {
        if(!isset($_GET['mobile'])){

            $Wap = new L\Wap();
            if($Wap->is_mobile_request()) {
               echo  "<script>location.href='http://m.5fengshou.com/'</script>"; die;
            }
        }
        $type =$this->request->get('type','string','');
        if(!$type){
            $type = 1;
        }
        //var_dump($type);die;
        //广告轮播
        $ad_img  = M\Ad::find('position=1 and is_show=1 and type=0 order by id asc  ');
        //直营图片
        $ds_img  = M\Ad::find("is_show='1' and type='1' order by position asc  ")->toArray();
        //直营轮播
        $ds_car  = M\Ad::find("is_show='1' and type='2' order by position asc  ")->toArray();
        //直营文字
        $chapter = M\Ad::find('is_show=1 and type=3 order by position asc')->toArray();


        $usercount = 0;
        $storecount = 0;
        $servicecount = 0;
        $ordercount = 0;
        /* 可信农场 */
        list($store, $total) = M\Shop::getStoreList(5);
        $stores["data"] = $store;
        $stores["count"] = $total;
        /* 今日行情 */
        $dayAdvisory = M\Article::selectBycid(31);
        // $dayAdvisory = M\Advisory::getdayList(3);
        /* 首页数据写入文件 */
        //$cKey = date('Ymd') . '.cache';
        // $todaydata = $this->cache->get($cKey);
        // if (!$todaydata) 
        // {
        //     /* 检测昨天数据 */
        //     $yest = array();
        //     $fileName = PUBLIC_PATH .  '/../cache/datacache/cache_' . date('Ymd', strtotime('-1day')) . '.cache';

        //     if(file_exists($fileName)) {
        //         $yest = unserialize(file_get_contents($fileName));
        //     }
        //     $client = L\Func::serviceApi();

        //     $minData = array('order' =>27531 , 'sell' =>139430, 'users' => 832572 );
        //     $maxData = array( 'order' => '4114', 'sell' => '140800', 'users' => '556760');

        //     if (!$yest) 
        //     {
        //         $todaydata = array(
        //             'usercount' => $minData['users'] ,
        //             'shopcount' => $minData['sell'],
        //             'storecount' => 3235,
        //             'servicecount' => $client->village_getVillageCount(),
        //             'ordercount' => $minData['order']
        //         );
        //     }
        //     else
        //     {
                
        //         $order = M\Orders::count(" id > '{$maxData['order']}' AND state > '3' ");
        //         $sell = M\Sell::count(" id > '{$maxData['sell']}' ");
        //         $users = M\Users::count(" id > '{$maxData['users']}' ");

        //         /* 实时统计订单用户数量 */
        //         $todaydata = array(
        //             'usercount' => $users ? ($minData['users'] + $order * 3 ) : $minData['users'],
        //             'shopcount' => $sell ? ($minData['sell'] + $order * 3 ) : $minData['sell'] ,
        //             'storecount' => $yest['storecount'] + rand(5, 10) ,
        //             'servicecount' => $client->village_getVillageCount() ,
        //             'ordercount' => $order ? ($minData['order'] + $order * 3 ) : $minData['order']
        //         );
        //     }
       
        //     $this->cache->save($cKey, $todaydata, 72000);
        //     extract($todaydata);
        // }
        // else
        // {
        //     extract($todaydata);
        // }
        /*可信农场*/
        $farmhome = M\CredibleFarmInfo::find( " status=1 and is_home_page=1 ORDER BY last_update_time desc limit 5 ")->toArray();
        foreach ($farmhome as $key => $value) {
                $user_info = M\UserInfo::findFirst("user_id={$value['user_id']} and status=1 and credit_type=8 ");
                if($user_info){
                $user_farm = M\UserFarm::findFirst("credit_id={$user_info->credit_id} ");
                $farmhome[$key]["farm_name"]=$user_farm->farm_name ? $user_farm->farm_name : '';
                }else{
                 $farmhome[$key]["farm_name"]='';
                }
        }
        
        /* 数据统计 */
        $this->view->usercount = $usercount;
        $this->view->shopcount = $shopcount;
        $this->view->storecount = $storecount;
        $this->view->servicecount = $servicecount;
        $this->view->ordercount = $ordercount;
        $this->view->dayAdvisory = $dayAdvisory;
        $this->view->stores = $stores;
        $this->view->getN = L\Func::getN();
        $this->view->timeName = L\Func::getTimeName();
        $this->view->adimg = $ad_img;
        $this->view->dsimg = $ds_img;
        $this->view->dscar = $ds_car;
        $this->view->chapter = $chapter;
        $this->view->type = $type;
        $this->view->farmhome = $farmhome;
		//友情连接显示的位置
		$this->view->homefooter=1;
        // $category = M\Category::find("is_groom=1  order by deeps desc ")->toArray();
        // $cat_list = M\Category::totree($category, 'id', 'parent_id', 'child');
        // $this->view->cat_list = $cat_list;
        $this->view->goods_unit = M\Purchase::$_goods_unit;
        $this->view->title = '丰收汇-可靠农产品交易服务商_现货农产品价格_批发_采购';
        $this->view->keywords = '农产品，农产品电商，农产品批发，农产品交易网，农产品价格，农产品采购，农副产品，丰收汇';
        $this->view->descript = '丰收汇-可靠农产品交易服务商，可靠的农产品电商网，为你提供现货农产品批发采购、农产品价格、可追溯农产品、农副产品等信息，农产品可靠交易，农场主和采购商直接对接，实现农产品“卖的快、卖得好”。';
    }
    /**
     * 首页分类数据
     * @return [type] [description]
     */
    
    public function indexcenterAction() 
    {
        /* 供应产品  蔬菜 */
        $sell = new M\Sell();
        $purchase = new M\Purchase();
        /* 蔬菜 */
        $vegcid = 1;
        $veg['sell'] = $sell->getRecoList($vegcid, 'home', 10);
        $veg['pur'] = $purchase->getRecList($vegcid, 'home', 10);
        $veg['cate'] = M\Category::threecate($vegcid, 14);
        
        foreach ($veg['sell'] as $key => $val) 
        {
            $veg['sell'][$key]['quantity'] = L\Func::conversion($val['quantity']);
            $veg['pur'][$key]['quantity'] = L\Func::conversion($veg['pur'][$key]['quantity']);
        }
        /* 水果 */
        $fruitid = 2;
        $fruit['sell'] = $sell->getRecoList($fruitid, 'home', 10);
        $fruit['pur'] = $purchase->getRecList($fruitid, 'home', 10);
        $fruit['cate'] = M\Category::threecate($fruitid, 14);
        
        foreach ($fruit['sell'] as $key => $val) 
        {
            $fruit['sell'][$key]['quantity'] = L\Func::conversion($val['quantity']);
            $fruit['pur'][$key]['quantity'] = L\Func::conversion($fruit['pur'][$key]['quantity']);
        }
        /* 粮油 */
        $grainid = 7;
        $grain['sell'] = $sell->getRecoList($grainid, 'home', 10);
        $grain['pur'] = $purchase->getRecList($grainid, 'home', 10);
        $grain['cate'] = M\Category::threecate($grainid, 14);
        
        foreach ($grain['sell'] as $key => $val) 
        {
            $grain['sell'][$key]['quantity'] = L\Func::conversion($val['quantity']);
            $grain['pur'][$key]['quantity'] = L\Func::conversion($grain['pur'][$key]['quantity']);
        }
        $this->view->vegcid = $vegcid;
        $this->view->grainid = $grainid;
        $this->view->fruitid = $fruitid;
        $this->view->goods_unit = M\Purchase::$_goods_unit;
        $this->view->is_ajax = 1;
        $this->view->veg = $veg;
        $this->view->fruit = $fruit;
        $this->view->grain = $grain;
        // $this->view->is_ajax = 1 ;
        
    }
    /**
     * 搜索
     * @return [type] [description]
     */
    
    public function searchAction() 
    {
        $keywords = L\Validator::replace_specialChar($this->request->get('keywords', 'string', ''));
        $type = L\Validator::replace_specialChar($this->request->get('search_header_type', 'string', ''));
        
        switch ($type) 
        {
        case 'pur':
            $this->response->redirect("purchase/index?search_header_type={$type}&keyword=" . ( urlencode($keywords) ) ." ")->sendHeaders();
            break;

        case 'shop':
            $this->response->redirect("shop/index")->sendHeaders();
            break;

        case 'tag':
            $this->response->redirect("tag/index?search_header_type={$type}&keyword=" . ( urlencode($keywords) ) .  " ")->sendHeaders();
            break;

        default:
            $this->response->redirect("sell/index?search_header_type={$type}&keyword=" . ( urlencode($keywords) ) ." ")->sendHeaders();
            break;
        }
    }
    
    public function decodeAction() 
    {
        echo Auth::decode('be89Uays5fRrO8r0Gt5gqyT4fT9QEz5eevcyY9UjLGax', $this->key, 10);
        exit;
    }
    
    public function testAction() 
    {
        if(!isset($_GET['mobile'])){
            $Wap = new L\Wap();
            var_dump($Wap->is_mobile_request());
            echo 2;
            if($Wap->is_mobile_request()) {
            //    echo  "<script>location.href='http://m.5fengshou.com/'</script>"; die;
            }
        }
        echo 4;
        exit;
        $dayAdvisory = M\Article::selectBycid(31);
        var_dump($dayAdvisory);
        exit;
        /* 首页数据写入文件 */
        $cKey = date('Ymd') . '.cache';
        $todaydata = $this->cache->get($cKey);
        
        if (!$todaydata) 
        {
            /* 检测昨天数据 */
            $yestkey = date('Ymd', strtotime('-1day')) . '.cache';
            $yest = $this->cache->get($yestkey);
            $client = L\Func::serviceApi();
            
            if (!$yest) 
            {
                $todaydata = array(
                    'usercount' => $client->member_getUserCount() ,
                    'shopcount' => M\Shop::count(" is_recommended = 1 AND shop_status = 1 ") ,
                    'storecount' => 3235,
                    'servicecount' => $client->county_getCountycount() + $client->townlet_getTownletCount() ,
                    'ordercount' => 12972
                );
            }
            else
            {
                $todaydata = array(
                    'usercount' => $client->member_getUserCount() ,
                    'shopcount' => M\Shop::count(" is_recommended = 1 AND shop_status = 1 ") ,
                    'storecount' => $yest['3235'] + rand(5, 10) ,
                    'servicecount' => $client->county_getCountycount() + $client->townlet_getTownletCount() ,
                    'ordercount' => $yest['ordercount'] + rand(100, 500)
                );
            }
            $this->cache->save($cKey, $todaydata, 72000);
            extract($todaydata);
        }
        else
        {
            extract($todaydata);
        }
        print_R($todaydata);
        exit;
    }
    
    public function test2Action() 
    {
        ini_set('max_input_vars', "10000");
        $params = '{"submenuid-0":{"name":"蔬菜","child":{"0":{"url":"http://www.ymt.com/gy_7298","name":"大葱"},"1":{"url":"http://www.ymt.com/gy_7107","name":"白菜"},"2":{"url":"http://www.ymt.com/gy_7710","name":"红薯"},"3":{"url":"http://www.ymt.com/gy_7741","name":"胡萝卜"},"4":{"url":"http://www.ymt.com/gy_8397","name":"土豆"},"5":{"url":"http://www.ymt.com/gy_8217","name":"芹菜"},"6":{"url":"http://www.ymt.com/gy_8275","name":"山药"},"7":{"url":"http://www.ymt.com/gy_8429","name":"西红柿"},"8":{"url":"http://www.ymt.com/gy_7799","name":"黄瓜"},"9":{"url":"http://www.ymt.com/gy_7895","name":"生姜"},"10":{"url":"http://www.ymt.com/gy_8016","name":"辣椒"},"11":{"url":"http://www.ymt.com/gy_7208","name":"菠菜"},"12":{"url":"http://www.ymt.com/gy_7875","name":"茄子"},"13":{"url":"http://www.ymt.com/gy_7991","name":"韭菜"},"14":{"url":"http://www.ymt.com/gy_8544","name":"芋头"},"15":{"url":"http://www.ymt.com/gy_7540","name":"甘蓝"},"16":{"url":"http://www.ymt.com/gy_8449","name":"香菜"},"17":{"url":"http://www.ymt.com/gy_8523","name":"油麦菜"},"18":{"url":"http://www.ymt.com/gy_7447","name":"豆角"},"19":{"url":"http://www.ymt.com/gy_8224","name":"青椒"},"20":{"url":"http://www.ymt.com/gy_7304","name":"洋葱"},"21":{"url":"http://www.ymt.com/gy_7891","name":"尖椒"},"22":{"url":"http://www.ymt.com/gy_8080","name":"青萝卜"},"23":{"url":"http://www.ymt.com/gy_7138","name":"白萝卜"},"24":{"url":"http://www.ymt.com/gy_7382","name":"大蒜"},"25":{"url":"http://www.ymt.com/gy_7437","name":"冬笋"},"26":{"url":"http://www.ymt.com/gy_7222","name":"菜花"},"27":{"url":"http://www.ymt.com/gy_8053","name":"莲藕"},"28":{"url":"http://www.ymt.com/gy_8390","name":"甜玉米"},"29":{"url":"http://www.ymt.com/gy_8402","name":"娃娃菜"},"30":{"url":"http://www.ymt.com/gy_8403","name":"豌豆"},"31":{"url":"http://www.ymt.com/gy_7432","name":"冬瓜"},"32":{"url":"http://www.ymt.com/gy_7742","name":"西葫芦"},"33":{"url":"http://www.ymt.com/gy_8081","name":"萝卜"},"34":{"url":"http://www.ymt.com/gy_8332","name":"蒜苗"},"35":{"url":"http://www.ymt.com/gy_8434","name":"西兰花"},"36":{"url":"http://www.ymt.com/gy_8157","name":"南瓜"},"37":{"url":"http://www.ymt.com/gy_8006","name":"苦瓜"},"38":{"url":"http://www.ymt.com/gy_10417","name":"土豆种子"},"39":{"url":"http://www.ymt.com/gy_55705","name":"毛粉西红柿"},"40":{"url":"http://www.ymt.com/gy_7105","name":"小白菜"},"41":{"url":"http://www.ymt.com/gy_8347","name":"莴笋"},"42":{"url":"http://www.ymt.com/gy_28146","name":"辣椒种子"},"43":{"url":"http://www.ymt.com/gy_8394","name":"茼蒿"},"44":{"url":"http://www.ymt.com/gy_41436","name":"黄秋葵种子"},"45":{"url":"http://www.ymt.com/gy_7294","name":"茨菇"},"46":{"url":"http://www.ymt.com/gy_8327","name":"四季豆"},"47":{"url":"http://www.ymt.com/gy_67426","name":"圆包菜"},"48":{"url":"http://www.ymt.com/gy_408756","name":"甜脆豌豆"},"49":{"url":"http://www.ymt.com/gy_299804","name":"百合菜用"},"50":{"url":"http://www.ymt.com/gy_7826","name":"茴香"},"51":{"url":"http://www.ymt.com/gy_7993","name":"韭黄"},"52":{"url":"http://www.ymt.com/gy_269426","name":"菊芋"},"53":{"url":"http://www.ymt.com/gy_213184","name":"有机菜花"},"54":{"url":"http://www.ymt.com/gy_7657","name":"荷兰豆"},"55":{"url":"http://www.ymt.com/gy_7999","name":"卷心菜"},"56":{"url":"http://www.ymt.com/gy_8325","name":"丝瓜"},"57":{"url":"http://www.ymt.com/gy_8334","name":"蒜薹"},"58":{"url":"http://www.ymt.com/gy_8415","name":"莴苣"},"59":{"url":"http://www.ymt.com/gy_8895","name":"苦菊"},"60":{"url":"http://www.ymt.com/gy_14111","name":"慈姑"},"61":{"url":"http://www.ymt.com/gy_27683","name":"南瓜种子"},"62":{"url":"http://www.ymt.com/gy_434151","name":"玉米笋"},"63":{"url":"http://www.ymt.com/gy_7233","name":"菜心"},"64":{"url":"http://www.ymt.com/gy_8120","name":"毛豆"},"65":{"url":"http://www.ymt.com/gy_8529","name":"鱼腥草"},"66":{"url":"http://www.ymt.com/gy_9049","name":"香椿芽"},"67":{"url":"http://www.ymt.com/gy_14843","name":"四棱豆"},"68":{"url":"http://www.ymt.com/gy_18946","name":"水果萝卜"},"69":{"url":"http://www.ymt.com/gy_27668","name":"西红柿种子"},"70":{"url":"http://www.ymt.com/gy_159524","name":"菠菜种子"},"71":{"url":"http://www.ymt.com/gy_434585","name":"野菜马兰头"},"72":{"url":"http://www.ymt.com/gy_434016","name":"生菜种子"},"73":{"url":"http://www.ymt.com/gy_433944","name":"苦菜食用"},"74":{"url":"http://www.ymt.com/gy_432109","name":"水晶冰菜"},"75":{"url":"http://www.ymt.com/gy_379311","name":"豆薯"},"76":{"url":"http://www.ymt.com/gy_374285","name":"非洲冰草"},"77":{"url":"http://www.ymt.com/gy_7300","name":"小葱"},"78":{"url":"http://www.ymt.com/gy_7503","name":"佛手瓜"},"79":{"url":"http://www.ymt.com/gy_7523","name":"芥菜"},"80":{"url":"http://www.ymt.com/gy_7526","name":"芥蓝"},"81":{"url":"http://www.ymt.com/gy_7744","name":"葫芦"},"82":{"url":"http://www.ymt.com/gy_7874","name":"茄瓜"},"83":{"url":"http://www.ymt.com/gy_7905","name":"沙姜"},"84":{"url":"http://www.ymt.com/gy_7914","name":"豇豆"},"85":{"url":"http://www.ymt.com/gy_8004","name":"空心菜"},"86":{"url":"http://www.ymt.com/gy_8075","name":"芦笋"},"87":{"url":"http://www.ymt.com/gy_8341","name":"蒜黄"},"88":{"url":"http://www.ymt.com/gy_8349","name":"竹笋"},"89":{"url":"http://www.ymt.com/gy_8450","name":"香椿"},"90":{"url":"http://www.ymt.com/gy_8476","name":"心里美"},"91":{"url":"http://www.ymt.com/gy_8723","name":"豆芽"},"92":{"url":"http://www.ymt.com/gy_9483","name":"荠菜"},"93":{"url":"http://www.ymt.com/gy_31819","name":"芹菜种子"},"94":{"url":"http://www.ymt.com/gy_31868","name":"泡泡青"},"95":{"url":"http://www.ymt.com/gy_445028","name":"牛尾笋"},"96":{"url":"http://www.ymt.com/gy_444052","name":"土瓜"},"97":{"url":"http://www.ymt.com/gy_443863","name":"韭菜芯"},"98":{"url":"http://www.ymt.com/gy_443430","name":"雷公菜"},"99":{"url":"http://www.ymt.com/gy_443428","name":"金雀花菜"}}},"submenuid-1":{"name":"水果","child":{"0":{"url":"http://www.ymt.com/gy_8199","name":"苹果"},"1":{"url":"http://www.ymt.com/gy_7277","name":"脐橙"},"2":{"url":"http://www.ymt.com/gy_8031","name":"梨"},"3":{"url":"http://www.ymt.com/gy_7252","name":"草莓"},"4":{"url":"http://www.ymt.com/gy_7924","name":"柑桔"},"5":{"url":"http://www.ymt.com/gy_8519","name":"柚子"},"6":{"url":"http://www.ymt.com/gy_8939","name":"蜜橘"},"7":{"url":"http://www.ymt.com/gy_8265","name":"沙糖桔"},"8":{"url":"http://www.ymt.com/gy_8112","name":"芒果"},"9":{"url":"http://www.ymt.com/gy_8426","name":"西瓜"},"10":{"url":"http://www.ymt.com/gy_9856","name":"大枣"},"11":{"url":"http://www.ymt.com/gy_7278","name":"甜橙"},"12":{"url":"http://www.ymt.com/gy_7734","name":"猕猴桃"},"13":{"url":"http://www.ymt.com/gy_7545","name":"甘蔗"},"14":{"url":"http://www.ymt.com/gy_8302","name":"圣女果"},"15":{"url":"http://www.ymt.com/gy_8381","name":"甜瓜"},"16":{"url":"http://www.ymt.com/gy_8315","name":"柿子"},"17":{"url":"http://www.ymt.com/gy_8453","name":"香蕉"},"18":{"url":"http://www.ymt.com/gy_8556","name":"枣"},"19":{"url":"http://www.ymt.com/gy_8205","name":"葡萄"},"20":{"url":"http://www.ymt.com/gy_301126","name":"石榴"},"21":{"url":"http://www.ymt.com/gy_8361","name":"油桃"},"22":{"url":"http://www.ymt.com/gy_7599","name":"哈密瓜"},"23":{"url":"http://www.ymt.com/gy_8193","name":"枇杷"},"24":{"url":"http://www.ymt.com/gy_8167","name":"柠檬"},"25":{"url":"http://www.ymt.com/gy_7211","name":"菠萝"},"26":{"url":"http://www.ymt.com/gy_7832","name":"火龙果"},"27":{"url":"http://www.ymt.com/gy_8268","name":"山楂"},"28":{"url":"http://www.ymt.com/gy_8483","name":"雪莲果"},"29":{"url":"http://www.ymt.com/gy_8360","name":"樱桃"},"30":{"url":"http://www.ymt.com/gy_7213","name":"菠萝蜜"},"31":{"url":"http://www.ymt.com/gy_7269","name":"车厘子"},"32":{"url":"http://www.ymt.com/gy_8452","name":"香瓜"},"33":{"url":"http://www.ymt.com/gy_8024","name":"蓝莓"},"34":{"url":"http://www.ymt.com/gy_8154","name":"木瓜"},"35":{"url":"http://www.ymt.com/gy_8247","name":"人参果"},"36":{"url":"http://www.ymt.com/gy_8363","name":"桃"},"37":{"url":"http://www.ymt.com/gy_230173","name":"刺角瓜"},"38":{"url":"http://www.ymt.com/gy_8045","name":"李子"},"39":{"url":"http://www.ymt.com/gy_8062","name":"榴莲"},"40":{"url":"http://www.ymt.com/gy_8287","name":"蛇果"},"41":{"url":"http://www.ymt.com/gy_8364","name":"杨桃"},"42":{"url":"http://www.ymt.com/gy_9196","name":"桑葚"},"43":{"url":"http://www.ymt.com/gy_10476","name":"罗汉果"},"44":{"url":"http://www.ymt.com/gy_80248","name":"桔柚"},"45":{"url":"http://www.ymt.com/gy_114484","name":"布福娜"},"46":{"url":"http://www.ymt.com/gy_162187","name":"八月瓜"},"47":{"url":"http://www.ymt.com/gy_445251","name":"多依果"},"48":{"url":"http://www.ymt.com/gy_444058","name":"蛋黄果"},"49":{"url":"http://www.ymt.com/gy_443260","name":"红橙"},"50":{"url":"http://www.ymt.com/gy_434092","name":"酸角"},"51":{"url":"http://www.ymt.com/gy_430014","name":"木通果"},"52":{"url":"http://www.ymt.com/gy_414882","name":"释迦果"},"53":{"url":"http://www.ymt.com/gy_431583","name":"椰子"},"54":{"url":"http://www.ymt.com/gy_301042","name":"无花果"},"55":{"url":"http://www.ymt.com/gy_300908","name":"沙枣"},"56":{"url":"http://www.ymt.com/gy_267533","name":"红参果"},"57":{"url":"http://www.ymt.com/gy_262539","name":"诺丽果"},"58":{"url":"http://www.ymt.com/gy_233858","name":"酸木瓜"},"59":{"url":"http://www.ymt.com/gy_8048","name":"荔枝"},"60":{"url":"http://www.ymt.com/gy_8054","name":"莲雾"},"61":{"url":"http://www.ymt.com/gy_8071","name":"龙眼"},"62":{"url":"http://www.ymt.com/gy_8179","name":"牛油果"},"63":{"url":"http://www.ymt.com/gy_8187","name":"蟠桃"},"64":{"url":"http://www.ymt.com/gy_8278","name":"山竹"},"65":{"url":"http://www.ymt.com/gy_8318","name":"释迦"},"66":{"url":"http://www.ymt.com/gy_8479","name":"杏"},"67":{"url":"http://www.ymt.com/gy_8495","name":"杨梅"},"68":{"url":"http://www.ymt.com/gy_8509","name":"椰子"},"69":{"url":"http://www.ymt.com/gy_9788","name":"西梅"},"70":{"url":"http://www.ymt.com/gy_10782","name":"乌梅"},"71":{"url":"http://www.ymt.com/gy_16233","name":"番石榴"},"72":{"url":"http://www.ymt.com/gy_17060","name":"打瓜"},"73":{"url":"http://www.ymt.com/gy_21275","name":"沙梨"},"74":{"url":"http://www.ymt.com/gy_23811","name":"西番莲"},"75":{"url":"http://www.ymt.com/gy_30153","name":"黄皮果"},"76":{"url":"http://www.ymt.com/gy_34801","name":"海棠果"},"77":{"url":"http://www.ymt.com/gy_42101","name":"百香果"},"78":{"url":"http://www.ymt.com/gy_116631","name":"毛丹"},"79":{"url":"http://www.ymt.com/gy_137843","name":"冰翡翠"},"80":{"url":"http://www.ymt.com/gy_184811","name":"橄榄果"},"81":{"url":"http://www.ymt.com/gy_199877","name":"黑莓"}}},"submenuid-2":{"name":"绿化苗木","child":{"0":{"url":"http://www.ymt.com/gy_11444","name":"银杏树"},"1":{"url":"http://www.ymt.com/gy_11232","name":"栾树"},"2":{"url":"http://www.ymt.com/gy_11215","name":"槐树"},"3":{"url":"http://www.ymt.com/gy_11196","name":"红叶李"},"4":{"url":"http://www.ymt.com/gy_14362","name":"苹果树苗"},"5":{"url":"http://www.ymt.com/gy_11211","name":"柳树"},"6":{"url":"http://www.ymt.com/gy_11197","name":"香樟"},"7":{"url":"http://www.ymt.com/gy_11459","name":"臭椿"},"8":{"url":"http://www.ymt.com/gy_13154","name":"葡萄苗"},"9":{"url":"http://www.ymt.com/gy_10172","name":"桃树苗"},"10":{"url":"http://www.ymt.com/gy_11207","name":"红叶石楠"},"11":{"url":"http://www.ymt.com/gy_11968","name":"海棠"},"12":{"url":"http://www.ymt.com/gy_13465","name":"红豆杉苗"},"13":{"url":"http://www.ymt.com/gy_11299","name":"红豆杉"},"14":{"url":"http://www.ymt.com/gy_11231","name":"紫薇"},"15":{"url":"http://www.ymt.com/gy_112032","name":"钩藤苗"},"16":{"url":"http://www.ymt.com/gy_10146","name":"核桃苗"},"17":{"url":"http://www.ymt.com/gy_12090","name":"桂花树"},"18":{"url":"http://www.ymt.com/gy_12188","name":"白皮松"},"19":{"url":"http://www.ymt.com/gy_12281","name":"国槐种子"},"20":{"url":"http://www.ymt.com/gy_11249","name":"榉树"},"21":{"url":"http://www.ymt.com/gy_11583","name":"茶花"},"22":{"url":"http://www.ymt.com/gy_11345","name":"白蜡树"},"23":{"url":"http://www.ymt.com/gy_11381","name":"榆树"},"24":{"url":"http://www.ymt.com/gy_21141","name":"槭树"},"25":{"url":"http://www.ymt.com/gy_65014","name":"李子树苗"},"26":{"url":"http://www.ymt.com/gy_11666","name":"枫树"},"27":{"url":"http://www.ymt.com/gy_11256","name":"法桐"},"28":{"url":"http://www.ymt.com/gy_11272","name":"广玉兰"},"29":{"url":"http://www.ymt.com/gy_11314","name":"桧柏"},"30":{"url":"http://www.ymt.com/gy_11422","name":"樱花苗"},"31":{"url":"http://www.ymt.com/gy_14030","name":"石榴苗"},"32":{"url":"http://www.ymt.com/gy_25511","name":"西瓜种子"},"33":{"url":"http://www.ymt.com/gy_25523","name":"梨树苗"},"34":{"url":"http://www.ymt.com/gy_11220","name":"雪松"},"35":{"url":"http://www.ymt.com/gy_11318","name":"桂花苗"},"36":{"url":"http://www.ymt.com/gy_120606","name":"西瓜苗"},"37":{"url":"http://www.ymt.com/gy_306623","name":"竹柳"},"38":{"url":"http://www.ymt.com/gy_11343","name":"女贞"},"39":{"url":"http://www.ymt.com/gy_11362","name":"国槐苗"},"40":{"url":"http://www.ymt.com/gy_11408","name":"白蜡苗"},"41":{"url":"http://www.ymt.com/gy_11410","name":"朴树"},"42":{"url":"http://www.ymt.com/gy_11453","name":"红叶石楠苗"},"43":{"url":"http://www.ymt.com/gy_12679","name":"杨树"},"44":{"url":"http://www.ymt.com/gy_13012","name":"红玉兰"},"45":{"url":"http://www.ymt.com/gy_306610","name":"榔榆"},"46":{"url":"http://www.ymt.com/gy_297231","name":"绿香椿苗"},"47":{"url":"http://www.ymt.com/gy_296664","name":"马尾松树苗"},"48":{"url":"http://www.ymt.com/gy_296512","name":"女贞苗"},"49":{"url":"http://www.ymt.com/gy_11214","name":"龙柏"},"50":{"url":"http://www.ymt.com/gy_11274","name":"樱花"},"51":{"url":"http://www.ymt.com/gy_11341","name":"马褂木"},"52":{"url":"http://www.ymt.com/gy_11532","name":"榆树苗"},"53":{"url":"http://www.ymt.com/gy_11853","name":"银杏苗"},"54":{"url":"http://www.ymt.com/gy_12178","name":"美国红枫"},"55":{"url":"http://www.ymt.com/gy_12236","name":"雪松树苗"},"56":{"url":"http://www.ymt.com/gy_27682","name":"甜瓜种子"},"57":{"url":"http://www.ymt.com/gy_37559","name":"牧草"},"58":{"url":"http://www.ymt.com/gy_38540","name":"柑橘苗"},"59":{"url":"http://www.ymt.com/gy_40275","name":"樱桃树苗"},"60":{"url":"http://www.ymt.com/gy_82970","name":"竹柳树苗"},"61":{"url":"http://www.ymt.com/gy_297747","name":"花梅苗"},"62":{"url":"http://www.ymt.com/gy_283525","name":"砂糖桔苗"},"63":{"url":"http://www.ymt.com/gy_8306","name":"石榴树"},"64":{"url":"http://www.ymt.com/gy_11199","name":"玉兰树"},"65":{"url":"http://www.ymt.com/gy_11208","name":"木槿"},"66":{"url":"http://www.ymt.com/gy_11393","name":"毛竹"},"67":{"url":"http://www.ymt.com/gy_11490","name":"油松"},"68":{"url":"http://www.ymt.com/gy_11606","name":"丝棉木"},"69":{"url":"http://www.ymt.com/gy_11727","name":"黄杨"},"70":{"url":"http://www.ymt.com/gy_12687","name":"枣树"},"71":{"url":"http://www.ymt.com/gy_12698","name":"栾树苗"},"72":{"url":"http://www.ymt.com/gy_13132","name":"苹果树"},"73":{"url":"http://www.ymt.com/gy_13363","name":"黄连木"},"74":{"url":"http://www.ymt.com/gy_13485","name":"栾树种子"},"75":{"url":"http://www.ymt.com/gy_19758","name":"松树"},"76":{"url":"http://www.ymt.com/gy_60236","name":"魔芋种子"},"77":{"url":"http://www.ymt.com/gy_159626","name":"枫树苗"},"78":{"url":"http://www.ymt.com/gy_13239","name":"金银花苗"},"79":{"url":"http://www.ymt.com/gy_431388","name":"早酥红梨苗"},"80":{"url":"http://www.ymt.com/gy_416147","name":"蜜橘树苗"},"81":{"url":"http://www.ymt.com/gy_306605","name":"红豆杉"},"82":{"url":"http://www.ymt.com/gy_305448","name":"映霜红桃苗"},"83":{"url":"http://www.ymt.com/gy_299004","name":"栀子花苗"},"84":{"url":"http://www.ymt.com/gy_297888","name":"茶梅苗"},"85":{"url":"http://www.ymt.com/gy_10127","name":"紫穗槐"},"86":{"url":"http://www.ymt.com/gy_11191","name":"杜英"},"87":{"url":"http://www.ymt.com/gy_11206","name":"杜鹃"},"88":{"url":"http://www.ymt.com/gy_11225","name":"丰花月季"},"89":{"url":"http://www.ymt.com/gy_11284","name":"红枫"},"90":{"url":"http://www.ymt.com/gy_11331","name":"泡桐"},"91":{"url":"http://www.ymt.com/gy_11340","name":"法桐小苗"},"92":{"url":"http://www.ymt.com/gy_11421","name":"柿子树"},"93":{"url":"http://www.ymt.com/gy_11473","name":"侧柏"},"94":{"url":"http://www.ymt.com/gy_11653","name":"红叶石楠球"},"95":{"url":"http://www.ymt.com/gy_11919","name":"杏树"},"96":{"url":"http://www.ymt.com/gy_12060","name":"茶梅"},"97":{"url":"http://www.ymt.com/gy_12408","name":"冬青苗"},"98":{"url":"http://www.ymt.com/gy_12697","name":"核桃树"},"99":{"url":"http://www.ymt.com/gy_12704","name":"桉树"}}},"submenuid-3":{"name":"花卉盆景","child":{"0":{"url":"http://www.ymt.com/gy_306146","name":"桂花"},"1":{"url":"http://www.ymt.com/gy_11586","name":"蝴蝶兰"},"2":{"url":"http://www.ymt.com/gy_19410","name":"兰花"},"3":{"url":"http://www.ymt.com/gy_306355","name":"富贵竹"},"4":{"url":"http://www.ymt.com/gy_306252","name":"长寿花"},"5":{"url":"http://www.ymt.com/gy_306160","name":"铁树"},"6":{"url":"http://www.ymt.com/gy_33768","name":"大花蕙兰"},"7":{"url":"http://www.ymt.com/gy_434618","name":"金线莲种子"},"8":{"url":"http://www.ymt.com/gy_434530","name":"乙女心"},"9":{"url":"http://www.ymt.com/gy_306379","name":"金钱树"},"10":{"url":"http://www.ymt.com/gy_306315","name":"鸟巢蕨苗"},"11":{"url":"http://www.ymt.com/gy_306260","name":"滴水观音"},"12":{"url":"http://www.ymt.com/gy_306254","name":"富贵树"},"13":{"url":"http://www.ymt.com/gy_306251","name":"仙客来"},"14":{"url":"http://www.ymt.com/gy_306187","name":"凤梨"},"15":{"url":"http://www.ymt.com/gy_306164","name":"松柏"},"16":{"url":"http://www.ymt.com/gy_306157","name":"澳洲杉"},"17":{"url":"http://www.ymt.com/gy_306153","name":"海棠"},"18":{"url":"http://www.ymt.com/gy_10729","name":"菊花"},"19":{"url":"http://www.ymt.com/gy_13310","name":"马蹄莲"},"20":{"url":"http://www.ymt.com/gy_74789","name":"风信子种球"},"21":{"url":"http://www.ymt.com/gy_445317","name":"碰碰香"},"22":{"url":"http://www.ymt.com/gy_442277","name":"结香花种子"},"23":{"url":"http://www.ymt.com/gy_442243","name":"棠梨花"},"24":{"url":"http://www.ymt.com/gy_441371","name":"鼠尾草种苗"},"25":{"url":"http://www.ymt.com/gy_440573","name":"荆棵根雕"},"26":{"url":"http://www.ymt.com/gy_436231","name":"姬麒麟"},"27":{"url":"http://www.ymt.com/gy_435367","name":"富贵椰子"},"28":{"url":"http://www.ymt.com/gy_434819","name":"红稚莲"},"29":{"url":"http://www.ymt.com/gy_428212","name":"油牡丹苗"},"30":{"url":"http://www.ymt.com/gy_423539","name":"独角莲种子"},"31":{"url":"http://www.ymt.com/gy_417246","name":"菠萝盆景"},"32":{"url":"http://www.ymt.com/gy_417179","name":"华山泉水莲"},"33":{"url":"http://www.ymt.com/gy_411958","name":"鸡蛋花苗"},"34":{"url":"http://www.ymt.com/gy_408879","name":"无刺枸骨"},"35":{"url":"http://www.ymt.com/gy_407249","name":"兰花种子"},"36":{"url":"http://www.ymt.com/gy_380298","name":"醉蝶花种子"},"37":{"url":"http://www.ymt.com/gy_380114","name":"福禄考种子"},"38":{"url":"http://www.ymt.com/gy_375235","name":"佛手菊"},"39":{"url":"http://www.ymt.com/gy_348010","name":"醉蝶花"},"40":{"url":"http://www.ymt.com/gy_307654","name":"剑兰"},"41":{"url":"http://www.ymt.com/gy_307650","name":"鸢尾"},"42":{"url":"http://www.ymt.com/gy_307649","name":"芦笋"},"43":{"url":"http://www.ymt.com/gy_307625","name":"菊花"},"44":{"url":"http://www.ymt.com/gy_307624","name":"剑兰"},"45":{"url":"http://www.ymt.com/gy_307623","name":"棕竹"},"46":{"url":"http://www.ymt.com/gy_307622","name":"君子兰"},"47":{"url":"http://www.ymt.com/gy_307621","name":"罗兰（观叶）"},"48":{"url":"http://www.ymt.com/gy_307620","name":"吊兰（观叶）"},"49":{"url":"http://www.ymt.com/gy_307616","name":"鸢尾"},"50":{"url":"http://www.ymt.com/gy_307615","name":"芦笋"},"51":{"url":"http://www.ymt.com/gy_307613","name":"紫花地丁"},"52":{"url":"http://www.ymt.com/gy_307591","name":"菊花"},"53":{"url":"http://www.ymt.com/gy_307590","name":"剑兰"},"54":{"url":"http://www.ymt.com/gy_307202","name":"鸢尾"},"55":{"url":"http://www.ymt.com/gy_307005","name":"芦笋"},"56":{"url":"http://www.ymt.com/gy_306566","name":"菊花"},"57":{"url":"http://www.ymt.com/gy_306565","name":"大岩桐种球"},"58":{"url":"http://www.ymt.com/gy_306564","name":"仙客来种球"},"59":{"url":"http://www.ymt.com/gy_306563","name":"马蹄莲种球"},"60":{"url":"http://www.ymt.com/gy_306562","name":"香雪兰种球"},"61":{"url":"http://www.ymt.com/gy_306561","name":"唐菖蒲种球"},"62":{"url":"http://www.ymt.com/gy_306560","name":"朱顶红种球"},"63":{"url":"http://www.ymt.com/gy_306559","name":"水仙种球"},"64":{"url":"http://www.ymt.com/gy_306558","name":"根雕象"},"65":{"url":"http://www.ymt.com/gy_306557","name":"根雕蝉"},"66":{"url":"http://www.ymt.com/gy_306556","name":"根雕蛙"},"67":{"url":"http://www.ymt.com/gy_306555","name":"根雕雀"},"68":{"url":"http://www.ymt.com/gy_306554","name":"根雕佛柄"},"69":{"url":"http://www.ymt.com/gy_306553","name":"根雕抓背"},"70":{"url":"http://www.ymt.com/gy_306552","name":"根雕烟斗"},"71":{"url":"http://www.ymt.com/gy_306551","name":"根雕笔筒"},"72":{"url":"http://www.ymt.com/gy_306550","name":"根雕茶台"},"73":{"url":"http://www.ymt.com/gy_306549","name":"根雕茶桌"},"74":{"url":"http://www.ymt.com/gy_306548","name":"根雕茶几"},"75":{"url":"http://www.ymt.com/gy_306547","name":"杂交兰"},"76":{"url":"http://www.ymt.com/gy_306546","name":"罗兰"},"77":{"url":"http://www.ymt.com/gy_306545","name":"剑兰"},"78":{"url":"http://www.ymt.com/gy_306544","name":"火花兰"},"79":{"url":"http://www.ymt.com/gy_306543","name":"金栗兰"},"80":{"url":"http://www.ymt.com/gy_306542","name":"姜兰"},"81":{"url":"http://www.ymt.com/gy_306541","name":"石斛兰"},"82":{"url":"http://www.ymt.com/gy_306540","name":"薰衣草"},"83":{"url":"http://www.ymt.com/gy_306539","name":"文竹"},"84":{"url":"http://www.ymt.com/gy_306538","name":"万年青"},"85":{"url":"http://www.ymt.com/gy_306536","name":"铁线莲"},"86":{"url":"http://www.ymt.com/gy_306535","name":"铁皮石斛"},"87":{"url":"http://www.ymt.com/gy_306534","name":"太阳花"},"88":{"url":"http://www.ymt.com/gy_306533","name":"芍药"},"89":{"url":"http://www.ymt.com/gy_306532","name":"水仙"},"90":{"url":"http://www.ymt.com/gy_306531","name":"炮仗花"},"91":{"url":"http://www.ymt.com/gy_306530","name":"平板金心铁"},"92":{"url":"http://www.ymt.com/gy_306529","name":"南洋杉"},"93":{"url":"http://www.ymt.com/gy_306528","name":"迷迭香"},"94":{"url":"http://www.ymt.com/gy_306527","name":"蔓绿绒"},"95":{"url":"http://www.ymt.com/gy_306526","name":"美女樱"},"96":{"url":"http://www.ymt.com/gy_306525","name":"美国万年青"},"97":{"url":"http://www.ymt.com/gy_306524","name":"美人蕉"},"98":{"url":"http://www.ymt.com/gy_306523","name":"玫瑰"}}},"submenuid-4":{"name":"坚果干果","child":{"0":{"url":"http://www.ymt.com/gy_7645","name":"核桃"},"1":{"url":"http://www.ymt.com/gy_7774","name":"花生米"},"2":{"url":"http://www.ymt.com/gy_8012","name":"葵花籽"},"3":{"url":"http://www.ymt.com/gy_7731","name":"红枣"},"4":{"url":"http://www.ymt.com/gy_16859","name":"核桃仁"},"5":{"url":"http://www.ymt.com/gy_7171","name":"板栗"},"6":{"url":"http://www.ymt.com/gy_16106","name":"栗子"},"7":{"url":"http://www.ymt.com/gy_8555","name":"枣干"},"8":{"url":"http://www.ymt.com/gy_150887","name":"带壳红皮花生"},"9":{"url":"http://www.ymt.com/gy_8206","name":"葡萄干"},"10":{"url":"http://www.ymt.com/gy_8001","name":"开心果"},"11":{"url":"http://www.ymt.com/gy_8137","name":"蜜枣"},"12":{"url":"http://www.ymt.com/gy_10272","name":"榛子"},"13":{"url":"http://www.ymt.com/gy_84069","name":"猕猴桃果干"},"14":{"url":"http://www.ymt.com/gy_378020","name":"风流果"},"15":{"url":"http://www.ymt.com/gy_301188","name":"茶橄榄"},"16":{"url":"http://www.ymt.com/gy_301183","name":"青橄榄檀香"},"17":{"url":"http://www.ymt.com/gy_263450","name":"铁莲子"},"18":{"url":"http://www.ymt.com/gy_8480","name":"杏仁"},"19":{"url":"http://www.ymt.com/gy_8607","name":"白果"},"20":{"url":"http://www.ymt.com/gy_9412","name":"丝瓜子"},"21":{"url":"http://www.ymt.com/gy_10270","name":"莲子"},"22":{"url":"http://www.ymt.com/gy_10271","name":"松子"},"23":{"url":"http://www.ymt.com/gy_10273","name":"夏威夷果"},"24":{"url":"http://www.ymt.com/gy_10307","name":"腰果"},"25":{"url":"http://www.ymt.com/gy_13294","name":"香榧"},"26":{"url":"http://www.ymt.com/gy_14966","name":"杏干"},"27":{"url":"http://www.ymt.com/gy_14997","name":"巴旦木"},"28":{"url":"http://www.ymt.com/gy_18717","name":"白瓜子"},"29":{"url":"http://www.ymt.com/gy_21657","name":"南瓜子"},"30":{"url":"http://www.ymt.com/gy_21704","name":"打瓜子"},"31":{"url":"http://www.ymt.com/gy_27797","name":"榧子"},"32":{"url":"http://www.ymt.com/gy_29342","name":"黄瓜子"},"33":{"url":"http://www.ymt.com/gy_34004","name":"无花果干果"},"34":{"url":"http://www.ymt.com/gy_36552","name":"桂圆干"},"35":{"url":"http://www.ymt.com/gy_38462","name":"油橄榄"},"36":{"url":"http://www.ymt.com/gy_53835","name":"话梅"},"37":{"url":"http://www.ymt.com/gy_54527","name":"西瓜子"},"38":{"url":"http://www.ymt.com/gy_55044","name":"吊瓜子"},"39":{"url":"http://www.ymt.com/gy_121736","name":"西葫芦籽"}}},"submenuid-5":{"name":"禽畜牧蛋肉","child":{"0":{"url":"http://www.ymt.com/gy_9878","name":"肉羊"},"1":{"url":"http://www.ymt.com/gy_9883","name":"肉牛"},"2":{"url":"http://www.ymt.com/gy_9894","name":"仔猪"},"3":{"url":"http://www.ymt.com/gy_10971","name":"牛犊"},"4":{"url":"http://www.ymt.com/gy_7850","name":"鸡蛋"},"5":{"url":"http://www.ymt.com/gy_9664","name":"生猪"},"6":{"url":"http://www.ymt.com/gy_13921","name":"肉狗"},"7":{"url":"http://www.ymt.com/gy_27392","name":"驴苗"},"8":{"url":"http://www.ymt.com/gy_10072","name":"土鸡"},"9":{"url":"http://www.ymt.com/gy_9897","name":"黄牛"},"10":{"url":"http://www.ymt.com/gy_10193","name":"肉驴"},"11":{"url":"http://www.ymt.com/gy_8490","name":"鸭蛋"},"12":{"url":"http://www.ymt.com/gy_15136","name":"鸭苗"},"13":{"url":"http://www.ymt.com/gy_27309","name":"斗鸡"},"14":{"url":"http://www.ymt.com/gy_122170","name":"宠物狗"},"15":{"url":"http://www.ymt.com/gy_11005","name":"鹅苗"},"16":{"url":"http://www.ymt.com/gy_27151","name":"野兔"},"17":{"url":"http://www.ymt.com/gy_11096","name":"鸡"},"18":{"url":"http://www.ymt.com/gy_20979","name":"香猪"},"19":{"url":"http://www.ymt.com/gy_10403","name":"獭兔"},"20":{"url":"http://www.ymt.com/gy_8173","name":"牛肉"},"21":{"url":"http://www.ymt.com/gy_8572","name":"猪肉"},"22":{"url":"http://www.ymt.com/gy_10003","name":"野猪"},"23":{"url":"http://www.ymt.com/gy_16955","name":"种兔"},"24":{"url":"http://www.ymt.com/gy_18579","name":"奶牛"},"25":{"url":"http://www.ymt.com/gy_18615","name":"孔雀"},"26":{"url":"http://www.ymt.com/gy_9790","name":"肉鸡"},"27":{"url":"http://www.ymt.com/gy_19706","name":"鸭"},"28":{"url":"http://www.ymt.com/gy_54092","name":"骆驼"},"29":{"url":"http://www.ymt.com/gy_233306","name":"红骨山羊"},"30":{"url":"http://www.ymt.com/gy_9898","name":"水牛"},"31":{"url":"http://www.ymt.com/gy_10074","name":"麻鸭"},"32":{"url":"http://www.ymt.com/gy_10103","name":"种猪"},"33":{"url":"http://www.ymt.com/gy_10192","name":"肉兔"},"34":{"url":"http://www.ymt.com/gy_10255","name":"鸽蛋"},"35":{"url":"http://www.ymt.com/gy_11117","name":"种羊"},"36":{"url":"http://www.ymt.com/gy_11188","name":"鹅"},"37":{"url":"http://www.ymt.com/gy_15016","name":"鹌鹑蛋"},"38":{"url":"http://www.ymt.com/gy_15121","name":"野鸡"},"39":{"url":"http://www.ymt.com/gy_15748","name":"皮蛋"},"40":{"url":"http://www.ymt.com/gy_15854","name":"鸡苗"},"41":{"url":"http://www.ymt.com/gy_18227","name":"鲜鸭蛋"},"42":{"url":"http://www.ymt.com/gy_18628","name":"马"},"43":{"url":"http://www.ymt.com/gy_19353","name":"鹅蛋"},"44":{"url":"http://www.ymt.com/gy_19698","name":"牦牛"},"45":{"url":"http://www.ymt.com/gy_28354","name":"梅花鹿肉"},"46":{"url":"http://www.ymt.com/gy_29492","name":"肉马"},"47":{"url":"http://www.ymt.com/gy_438352","name":"苍鹰"},"48":{"url":"http://www.ymt.com/gy_437973","name":" 乌骨羊"},"49":{"url":"http://www.ymt.com/gy_423990","name":"羊驼"},"50":{"url":"http://www.ymt.com/gy_385559","name":"宠物鼠"},"51":{"url":"http://www.ymt.com/gy_306627","name":"茶叶蛋"},"52":{"url":"http://www.ymt.com/gy_299532","name":"玩赏鸽"},"53":{"url":"http://www.ymt.com/gy_299519","name":"野鸽"},"54":{"url":"http://www.ymt.com/gy_299498","name":"蛋鹅"},"55":{"url":"http://www.ymt.com/gy_299405","name":"野狗"},"56":{"url":"http://www.ymt.com/gy_8488","name":"咸鸭蛋"},"57":{"url":"http://www.ymt.com/gy_8501","name":"羊肉"},"58":{"url":"http://www.ymt.com/gy_9036","name":"兔肉"},"59":{"url":"http://www.ymt.com/gy_9884","name":"乌鸡"},"60":{"url":"http://www.ymt.com/gy_10004","name":"野猪"},"61":{"url":"http://www.ymt.com/gy_10075","name":"鹧鸪"},"62":{"url":"http://www.ymt.com/gy_11072","name":"母猪"},"63":{"url":"http://www.ymt.com/gy_13860","name":"肉鹅"},"64":{"url":"http://www.ymt.com/gy_13946","name":"肉鸽"},"65":{"url":"http://www.ymt.com/gy_13988","name":"种鸽"},"66":{"url":"http://www.ymt.com/gy_14976","name":"蛋鸡"},"67":{"url":"http://www.ymt.com/gy_15052","name":"羊绒"},"68":{"url":"http://www.ymt.com/gy_15639","name":"咸蛋"},"69":{"url":"http://www.ymt.com/gy_15985","name":"肉鸭"},"70":{"url":"http://www.ymt.com/gy_16005","name":"鹌鹑"},"71":{"url":"http://www.ymt.com/gy_16085","name":"松花蛋"},"72":{"url":"http://www.ymt.com/gy_18435","name":"火鸡"},"73":{"url":"http://www.ymt.com/gy_18835","name":"香鸭"},"74":{"url":"http://www.ymt.com/gy_19713","name":"鹿"},"75":{"url":"http://www.ymt.com/gy_19884","name":"幼兔"},"76":{"url":"http://www.ymt.com/gy_20093","name":"奶山羊"},"77":{"url":"http://www.ymt.com/gy_20980","name":"湖羊"},"78":{"url":"http://www.ymt.com/gy_21128","name":"土公鸡"},"79":{"url":"http://www.ymt.com/gy_21589","name":"羊毛"},"80":{"url":"http://www.ymt.com/gy_21597","name":"大雁"},"81":{"url":"http://www.ymt.com/gy_22239","name":"野鹌鹑蛋"},"82":{"url":"http://www.ymt.com/gy_25509","name":"种鸡"},"83":{"url":"http://www.ymt.com/gy_27556","name":"绒山羊"},"84":{"url":"http://www.ymt.com/gy_27564","name":"长毛兔"},"85":{"url":"http://www.ymt.com/gy_30939","name":"宠物兔"},"86":{"url":"http://www.ymt.com/gy_32284","name":"海鸭蛋"},"87":{"url":"http://www.ymt.com/gy_74600","name":"乌骨绵羊"},"88":{"url":"http://www.ymt.com/gy_84823","name":"骡子"},"89":{"url":"http://www.ymt.com/gy_155454","name":"鸽子"},"90":{"url":"http://www.ymt.com/gy_179491","name":"鸵鸟蛋"},"91":{"url":"http://www.ymt.com/gy_187141","name":"卤蛋"}}},"submenuid-6":{"name":"特种养殖","child":{"0":{"url":"http://www.ymt.com/gy_21433","name":"竹鼠"},"1":{"url":"http://www.ymt.com/gy_19718","name":"土元"},"2":{"url":"http://www.ymt.com/gy_306660","name":"山鸡"},"3":{"url":"http://www.ymt.com/gy_19296","name":"貂皮"},"4":{"url":"http://www.ymt.com/gy_19710","name":"蛇"},"5":{"url":"http://www.ymt.com/gy_58119","name":"黄鼠狼肉"},"6":{"url":"http://www.ymt.com/gy_70407","name":"肉猫"},"7":{"url":"http://www.ymt.com/gy_75986","name":"狐狸"},"8":{"url":"http://www.ymt.com/gy_10407","name":"黄粉虫"},"9":{"url":"http://www.ymt.com/gy_10416","name":"蝎子"},"10":{"url":"http://www.ymt.com/gy_444129","name":"岩松鼠"},"11":{"url":"http://www.ymt.com/gy_443876","name":"仓鼠"},"12":{"url":"http://www.ymt.com/gy_440710","name":"云实蛀虫"},"13":{"url":"http://www.ymt.com/gy_432246","name":"獾子"},"14":{"url":"http://www.ymt.com/gy_306654","name":"黄鳝"},"15":{"url":"http://www.ymt.com/gy_299630","name":"毒蛇"},"16":{"url":"http://www.ymt.com/gy_299622","name":"肉蛇"},"17":{"url":"http://www.ymt.com/gy_223323","name":"斑蝥虫"},"18":{"url":"http://www.ymt.com/gy_221905","name":"狍子"},"19":{"url":"http://www.ymt.com/gy_10941","name":"蜈蚣"},"20":{"url":"http://www.ymt.com/gy_17224","name":"蝗虫"},"21":{"url":"http://www.ymt.com/gy_19702","name":"鸵鸟"},"22":{"url":"http://www.ymt.com/gy_19711","name":"蜜蜂"},"23":{"url":"http://www.ymt.com/gy_19716","name":"水蛭"},"24":{"url":"http://www.ymt.com/gy_20068","name":"蚯蚓"},"25":{"url":"http://www.ymt.com/gy_21367","name":"豪猪"},"26":{"url":"http://www.ymt.com/gy_21821","name":"藏獒"},"27":{"url":"http://www.ymt.com/gy_30327","name":"林蛙"},"28":{"url":"http://www.ymt.com/gy_34588","name":"蜗牛"},"29":{"url":"http://www.ymt.com/gy_36528","name":"大麦虫"},"30":{"url":"http://www.ymt.com/gy_44279","name":"蝉蛹"},"31":{"url":"http://www.ymt.com/gy_46615","name":"鳄龟"},"32":{"url":"http://www.ymt.com/gy_78096","name":"竹鼠苗"},"33":{"url":"http://www.ymt.com/gy_79188","name":"蚕"},"34":{"url":"http://www.ymt.com/gy_80489","name":"貂"},"35":{"url":"http://www.ymt.com/gy_122622","name":"天鹅"},"36":{"url":"http://www.ymt.com/gy_133862","name":"鸬鹚"},"37":{"url":"http://www.ymt.com/gy_200952","name":"貉"}}},"submenuid-7":{"name":"食用菌","child":{"0":{"url":"http://www.ymt.com/gy_8451","name":"香菇"},"1":{"url":"http://www.ymt.com/gy_8153","name":"木耳"},"2":{"url":"http://www.ymt.com/gy_7947","name":"金针菇"},"3":{"url":"http://www.ymt.com/gy_8198","name":"平菇"},"4":{"url":"http://www.ymt.com/gy_7616","name":"海鲜菇"},"5":{"url":"http://www.ymt.com/gy_8481","name":"秀珍菇"},"6":{"url":"http://www.ymt.com/gy_35094","name":"松茸"},"7":{"url":"http://www.ymt.com/gy_203841","name":"白桦茸"},"8":{"url":"http://www.ymt.com/gy_434122","name":"榛磨"},"9":{"url":"http://www.ymt.com/gy_220749","name":"桦褐孔菌"},"10":{"url":"http://www.ymt.com/gy_7739","name":"猴头菇"},"11":{"url":"http://www.ymt.com/gy_8512","name":"银耳"},"12":{"url":"http://www.ymt.com/gy_14949","name":"虫草"},"13":{"url":"http://www.ymt.com/gy_22409","name":"裙带菜"},"14":{"url":"http://www.ymt.com/gy_431904","name":"大红菇"},"15":{"url":"http://www.ymt.com/gy_374151","name":"榆黄菇"},"16":{"url":"http://www.ymt.com/gy_374146","name":"巴西蘑菇"},"17":{"url":"http://www.ymt.com/gy_368967","name":"云耳"},"18":{"url":"http://www.ymt.com/gy_368960","name":"黄落伞"},"19":{"url":"http://www.ymt.com/gy_368955","name":"大杯菇"},"20":{"url":"http://www.ymt.com/gy_367039","name":"白葱菌"},"21":{"url":"http://www.ymt.com/gy_366732","name":"小红菌"},"22":{"url":"http://www.ymt.com/gy_366728","name":"竹荪蛋"},"23":{"url":"http://www.ymt.com/gy_366726","name":"谷熟菌"},"24":{"url":"http://www.ymt.com/gy_366724","name":"荞面菌"},"25":{"url":"http://www.ymt.com/gy_366720","name":"奶浆菌"},"26":{"url":"http://www.ymt.com/gy_366713","name":"青杆菌"},"27":{"url":"http://www.ymt.com/gy_366703","name":"紫丁香蘑"},"28":{"url":"http://www.ymt.com/gy_366701","name":"猪苓菌"},"29":{"url":"http://www.ymt.com/gy_366696","name":"珍珠菇"},"30":{"url":"http://www.ymt.com/gy_366695","name":"元蘑"},"31":{"url":"http://www.ymt.com/gy_366690","name":"银盘蘑菇"},"32":{"url":"http://www.ymt.com/gy_366686","name":"银白离褶伞"},"33":{"url":"http://www.ymt.com/gy_366610","name":"杨树菇"},"34":{"url":"http://www.ymt.com/gy_366607","name":"绣球菌"},"35":{"url":"http://www.ymt.com/gy_366587","name":"香乳菇"},"36":{"url":"http://www.ymt.com/gy_366476","name":"桑黄菌"},"37":{"url":"http://www.ymt.com/gy_366465","name":"牛舌菌"},"38":{"url":"http://www.ymt.com/gy_366462","name":"老人头"},"39":{"url":"http://www.ymt.com/gy_366441","name":"鸡冠菌"},"40":{"url":"http://www.ymt.com/gy_366171","name":"桦剥管菌"},"41":{"url":"http://www.ymt.com/gy_366165","name":"黑虎掌"},"42":{"url":"http://www.ymt.com/gy_366161","name":"荷叶离褶伞"},"43":{"url":"http://www.ymt.com/gy_366159","name":"干巴菌"},"44":{"url":"http://www.ymt.com/gy_366140","name":"大斗菇"},"45":{"url":"http://www.ymt.com/gy_366129","name":"鹅蛋菌"},"46":{"url":"http://www.ymt.com/gy_366125","name":"白落伞"},"47":{"url":"http://www.ymt.com/gy_366114","name":"鸡枞菌"},"48":{"url":"http://www.ymt.com/gy_366038","name":"鸡油菇"},"49":{"url":"http://www.ymt.com/gy_361993","name":"茯苓"},"50":{"url":"http://www.ymt.com/gy_361992","name":"天麻"},"51":{"url":"http://www.ymt.com/gy_361991","name":"马勃"},"52":{"url":"http://www.ymt.com/gy_355992","name":"羊肚菌"},"53":{"url":"http://www.ymt.com/gy_355988","name":"鸡枞"},"54":{"url":"http://www.ymt.com/gy_308337","name":"硫磺菌"},"55":{"url":"http://www.ymt.com/gy_248807","name":"虾米菇"},"56":{"url":"http://www.ymt.com/gy_245116","name":"黄金菇"},"57":{"url":"http://www.ymt.com/gy_237293","name":"石耳"},"58":{"url":"http://www.ymt.com/gy_221300","name":"榆黄蘑"},"59":{"url":"http://www.ymt.com/gy_7136","name":"白灵菇"},"60":{"url":"http://www.ymt.com/gy_7157","name":"白玉菇"},"61":{"url":"http://www.ymt.com/gy_7248","name":"草菇"},"62":{"url":"http://www.ymt.com/gy_7255","name":"茶树菇"},"63":{"url":"http://www.ymt.com/gy_7502","name":"凤尾菇"},"64":{"url":"http://www.ymt.com/gy_7787","name":"滑子菇"},"65":{"url":"http://www.ymt.com/gy_7824","name":"灰树花"},"66":{"url":"http://www.ymt.com/gy_7863","name":"鸡腿菇"},"67":{"url":"http://www.ymt.com/gy_8005","name":"口蘑"},"68":{"url":"http://www.ymt.com/gy_8146","name":"蘑菇"},"69":{"url":"http://www.ymt.com/gy_8319","name":"双孢菇"},"70":{"url":"http://www.ymt.com/gy_8475","name":"蟹味菇"},"71":{"url":"http://www.ymt.com/gy_8567","name":"猪肚菇"},"72":{"url":"http://www.ymt.com/gy_8620","name":"百灵菇"},"73":{"url":"http://www.ymt.com/gy_8815","name":"滑菇"},"74":{"url":"http://www.ymt.com/gy_8840","name":"姬菇"},"75":{"url":"http://www.ymt.com/gy_8842","name":"姬松茸"},"76":{"url":"http://www.ymt.com/gy_9095","name":"竹荪"},"77":{"url":"http://www.ymt.com/gy_9832","name":"鲍鱼菇"},"78":{"url":"http://www.ymt.com/gy_9833","name":"真姬菇"},"79":{"url":"http://www.ymt.com/gy_9835","name":"牛肝菌"},"80":{"url":"http://www.ymt.com/gy_9849","name":"灵芝"},"81":{"url":"http://www.ymt.com/gy_10558","name":"白参"},"82":{"url":"http://www.ymt.com/gy_14983","name":"阿魏菇"},"83":{"url":"http://www.ymt.com/gy_16931","name":"红菇"}}},"submenuid-8":{"name":"中药材","child":{"0":{"url":"http://www.ymt.com/gy_306960","name":"柿饼"},"1":{"url":"http://www.ymt.com/gy_306690","name":"天麻"},"2":{"url":"http://www.ymt.com/gy_9775","name":"金银花"},"3":{"url":"http://www.ymt.com/gy_10651","name":"黄连"},"4":{"url":"http://www.ymt.com/gy_19709","name":"葛根"},"5":{"url":"http://www.ymt.com/gy_307262","name":"紫苏叶"},"6":{"url":"http://www.ymt.com/gy_432154","name":"枣皮"},"7":{"url":"http://www.ymt.com/gy_306956","name":"金橘"},"8":{"url":"http://www.ymt.com/gy_306847","name":"胡椒"},"9":{"url":"http://www.ymt.com/gy_306689","name":"地黄"},"10":{"url":"http://www.ymt.com/gy_252738","name":"玛咖"},"11":{"url":"http://www.ymt.com/gy_7496","name":"粉葛"},"12":{"url":"http://www.ymt.com/gy_10521","name":"白术"},"13":{"url":"http://www.ymt.com/gy_10531","name":"板蓝根"},"14":{"url":"http://www.ymt.com/gy_10617","name":"三七"},"15":{"url":"http://www.ymt.com/gy_14537","name":"茶叶"},"16":{"url":"http://www.ymt.com/gy_17080","name":"贝母"},"17":{"url":"http://www.ymt.com/gy_21232","name":"刺五加"},"18":{"url":"http://www.ymt.com/gy_434631","name":"土荆芥种子"},"19":{"url":"http://www.ymt.com/gy_434160","name":"霍香种苗"},"20":{"url":"http://www.ymt.com/gy_307571","name":"酒"},"21":{"url":"http://www.ymt.com/gy_307426","name":"鹿茸"},"22":{"url":"http://www.ymt.com/gy_307411","name":"石花"},"23":{"url":"http://www.ymt.com/gy_307231","name":"金莲花"},"24":{"url":"http://www.ymt.com/gy_307193","name":"细叶铁线莲"},"25":{"url":"http://www.ymt.com/gy_307009","name":"鹿茸草"},"26":{"url":"http://www.ymt.com/gy_306908","name":"刺玫果"},"27":{"url":"http://www.ymt.com/gy_306890","name":"石榴皮"},"28":{"url":"http://www.ymt.com/gy_306861","name":"枸杞子"},"29":{"url":"http://www.ymt.com/gy_306843","name":"八角茴香"},"30":{"url":"http://www.ymt.com/gy_280896","name":"北五味子"},"31":{"url":"http://www.ymt.com/gy_214225","name":"地参"},"32":{"url":"http://www.ymt.com/gy_7161","name":"白芷"},"33":{"url":"http://www.ymt.com/gy_9260","name":"草果"},"34":{"url":"http://www.ymt.com/gy_9411","name":"孜然"},"35":{"url":"http://www.ymt.com/gy_10479","name":"党参"},"36":{"url":"http://www.ymt.com/gy_10503","name":"黄芩"},"37":{"url":"http://www.ymt.com/gy_10632","name":"猪苓"},"38":{"url":"http://www.ymt.com/gy_10727","name":"淫羊藿"},"39":{"url":"http://www.ymt.com/gy_10743","name":"菟丝子"},"40":{"url":"http://www.ymt.com/gy_10754","name":"何首乌"},"41":{"url":"http://www.ymt.com/gy_10795","name":"玄参"},"42":{"url":"http://www.ymt.com/gy_10850","name":"生地"},"43":{"url":"http://www.ymt.com/gy_20040","name":"林下参"},"44":{"url":"http://www.ymt.com/gy_25596","name":"五味子"},"45":{"url":"http://www.ymt.com/gy_25858","name":"天花粉"},"46":{"url":"http://www.ymt.com/gy_26079","name":"石斛"},"47":{"url":"http://www.ymt.com/gy_26191","name":"千层塔"},"48":{"url":"http://www.ymt.com/gy_34709","name":"杏核"},"49":{"url":"http://www.ymt.com/gy_152148","name":"党参苗"},"50":{"url":"http://www.ymt.com/gy_194686","name":"牛大力种苗"},"51":{"url":"http://www.ymt.com/gy_7196","name":"荸荠"},"52":{"url":"http://www.ymt.com/gy_434165","name":"五味子籽"},"53":{"url":"http://www.ymt.com/gy_11417","name":"无患子"},"54":{"url":"http://www.ymt.com/gy_12862","name":"白芨"},"55":{"url":"http://www.ymt.com/gy_7567","name":"枸杞"},"56":{"url":"http://www.ymt.com/gy_444565","name":"罗汉参"},"57":{"url":"http://www.ymt.com/gy_444553","name":"甘露子"},"58":{"url":"http://www.ymt.com/gy_444045","name":"鸦胆子种子"},"59":{"url":"http://www.ymt.com/gy_442236","name":"芭蕉花"},"60":{"url":"http://www.ymt.com/gy_442110","name":"三叶木通"},"61":{"url":"http://www.ymt.com/gy_435144","name":"升麻种苗"},"62":{"url":"http://www.ymt.com/gy_435123","name":"臭参"},"63":{"url":"http://www.ymt.com/gy_434444","name":"金花葵"},"64":{"url":"http://www.ymt.com/gy_433553","name":"藿香种苗"},"65":{"url":"http://www.ymt.com/gy_432167","name":"茴香菖蒲"},"66":{"url":"http://www.ymt.com/gy_430978","name":"草乌籽"},"67":{"url":"http://www.ymt.com/gy_430359","name":"雪里见"},"68":{"url":"http://www.ymt.com/gy_429510","name":"小麦草"},"69":{"url":"http://www.ymt.com/gy_426672","name":"红花草"},"70":{"url":"http://www.ymt.com/gy_426526","name":"元参苗"},"71":{"url":"http://www.ymt.com/gy_424525","name":"桑瓢消"},"72":{"url":"http://www.ymt.com/gy_423318","name":"五加果"},"73":{"url":"http://www.ymt.com/gy_422147","name":"川穹"},"74":{"url":"http://www.ymt.com/gy_417489","name":"苍术种子"},"75":{"url":"http://www.ymt.com/gy_416540","name":"皇菊"},"76":{"url":"http://www.ymt.com/gy_414694","name":"三叉苦"},"77":{"url":"http://www.ymt.com/gy_414129","name":"花斑竹"},"78":{"url":"http://www.ymt.com/gy_411816","name":"娑罗果"},"79":{"url":"http://www.ymt.com/gy_431353","name":"破石珠"},"80":{"url":"http://www.ymt.com/gy_393785","name":"伍倍子"},"81":{"url":"http://www.ymt.com/gy_379399","name":"仙人草"},"82":{"url":"http://www.ymt.com/gy_378891","name":"茶树花"},"83":{"url":"http://www.ymt.com/gy_366580","name":"珊瑚菌"},"84":{"url":"http://www.ymt.com/gy_307679","name":"芦笋"},"85":{"url":"http://www.ymt.com/gy_307678","name":"蒲公英"}}},"submenuid-9":{"name":"水产","child":{"0":{"url":"http://www.ymt.com/gy_7253","name":"草鱼"},"1":{"url":"http://www.ymt.com/gy_8160","name":"泥鳅"},"2":{"url":"http://www.ymt.com/gy_8351","name":"梭子蟹"},"3":{"url":"http://www.ymt.com/gy_40784","name":"鳖"},"4":{"url":"http://www.ymt.com/gy_8176","name":"牛蛙"},"5":{"url":"http://www.ymt.com/gy_7576","name":"鲑鱼"},"6":{"url":"http://www.ymt.com/gy_8076","name":"鲈鱼"},"7":{"url":"http://www.ymt.com/gy_8242","name":"青鱼"},"8":{"url":"http://www.ymt.com/gy_8516","name":"鳙鱼"},"9":{"url":"http://www.ymt.com/gy_8046","name":"鲤鱼"},"10":{"url":"http://www.ymt.com/gy_7392","name":"带鱼"},"11":{"url":"http://www.ymt.com/gy_8487","name":"鲟鱼"},"12":{"url":"http://www.ymt.com/gy_8162","name":"鲶鱼"},"13":{"url":"http://www.ymt.com/gy_7188","name":"鲍鱼"},"14":{"url":"http://www.ymt.com/gy_7197","name":"鳊鱼"},"15":{"url":"http://www.ymt.com/gy_7259","name":"鲳鱼"},"16":{"url":"http://www.ymt.com/gy_7430","name":"东风螺"},"17":{"url":"http://www.ymt.com/gy_7471","name":"对虾"},"18":{"url":"http://www.ymt.com/gy_7814","name":"黄鳝"},"19":{"url":"http://www.ymt.com/gy_8391","name":"田螺"},"20":{"url":"http://www.ymt.com/gy_8416","name":"乌龟"},"21":{"url":"http://www.ymt.com/gy_8763","name":"鳜鱼"},"22":{"url":"http://www.ymt.com/gy_8833","name":"黄鱼"},"23":{"url":"http://www.ymt.com/gy_9335","name":"小龙虾"},"24":{"url":"http://www.ymt.com/gy_443109","name":"黄金蟹"},"25":{"url":"http://www.ymt.com/gy_442586","name":"银鲳鱼"},"26":{"url":"http://www.ymt.com/gy_442158","name":"花链苗"},"27":{"url":"http://www.ymt.com/gy_442111","name":"火焙鱼"},"28":{"url":"http://www.ymt.com/gy_435293","name":"花仔鱼"},"29":{"url":"http://www.ymt.com/gy_423582","name":"黄螺"},"30":{"url":"http://www.ymt.com/gy_306649","name":"海参"},"31":{"url":"http://www.ymt.com/gy_306648","name":"黄鳝"},"32":{"url":"http://www.ymt.com/gy_306642","name":"罗汉鱼"},"33":{"url":"http://www.ymt.com/gy_300311","name":"青鱼苗"},"34":{"url":"http://www.ymt.com/gy_300269","name":"比目鱼"},"35":{"url":"http://www.ymt.com/gy_262058","name":"鳜鱼鱼苗"},"36":{"url":"http://www.ymt.com/gy_236371","name":"塘鲺"},"37":{"url":"http://www.ymt.com/gy_224348","name":"帝王蟹"},"38":{"url":"http://www.ymt.com/gy_7101","name":"鲅鱼"},"39":{"url":"http://www.ymt.com/gy_7173","name":"包公鱼"},"40":{"url":"http://www.ymt.com/gy_7557","name":"蛤蜊"},"41":{"url":"http://www.ymt.com/gy_7577","name":"桂花鱼"},"42":{"url":"http://www.ymt.com/gy_7601","name":"海参"},"43":{"url":"http://www.ymt.com/gy_7654","name":"河虾"},"44":{"url":"http://www.ymt.com/gy_7670","name":"黑鱼"},"45":{"url":"http://www.ymt.com/gy_7836","name":"基围虾"},"46":{"url":"http://www.ymt.com/gy_7870","name":"鲫鱼"},"47":{"url":"http://www.ymt.com/gy_7929","name":"金鼓鱼"},"48":{"url":"http://www.ymt.com/gy_8055","name":"鲢鱼"},"49":{"url":"http://www.ymt.com/gy_8068","name":"龙虾"},"50":{"url":"http://www.ymt.com/gy_8078","name":"罗非鱼"},"51":{"url":"http://www.ymt.com/gy_8239","name":"青虾"},"52":{"url":"http://www.ymt.com/gy_8240","name":"青蟹"},"53":{"url":"http://www.ymt.com/gy_8280","name":"扇贝"},"54":{"url":"http://www.ymt.com/gy_8305","name":"石斑鱼"},"55":{"url":"http://www.ymt.com/gy_8456","name":"香螺"},"56":{"url":"http://www.ymt.com/gy_8514","name":"银鱼"},"57":{"url":"http://www.ymt.com/gy_8528","name":"鱿鱼"},"58":{"url":"http://www.ymt.com/gy_8769","name":"海螺"},"59":{"url":"http://www.ymt.com/gy_8771","name":"河蚌"},"60":{"url":"http://www.ymt.com/gy_8923","name":"马鲛鱼"},"61":{"url":"http://www.ymt.com/gy_8958","name":"螃蟹"},"62":{"url":"http://www.ymt.com/gy_9008","name":"生蚝"},"63":{"url":"http://www.ymt.com/gy_9201","name":"梭鱼"},"64":{"url":"http://www.ymt.com/gy_9369","name":"牡蛎"},"65":{"url":"http://www.ymt.com/gy_9510","name":"河蟹"}}},"submenuid-10":{"name":"粮油","child":{"0":{"url":"http://www.ymt.com/gy_8534","name":"玉米"},"1":{"url":"http://www.ymt.com/gy_7361","name":"大米"},"2":{"url":"http://www.ymt.com/gy_8470","name":"小米"},"3":{"url":"http://www.ymt.com/gy_7325","name":"大豆"},"4":{"url":"http://www.ymt.com/gy_7679","name":"红豆"},"5":{"url":"http://www.ymt.com/gy_9774","name":"棉花"},"6":{"url":"http://www.ymt.com/gy_7773","name":"花生"},"7":{"url":"http://www.ymt.com/gy_10332","name":"高粱"},"8":{"url":"http://www.ymt.com/gy_7795","name":"黄豆"},"9":{"url":"http://www.ymt.com/gy_8468","name":"小麦"},"10":{"url":"http://www.ymt.com/gy_10160","name":"稻谷"},"11":{"url":"http://www.ymt.com/gy_7411","name":"淀粉"},"12":{"url":"http://www.ymt.com/gy_7778","name":"花生油"},"13":{"url":"http://www.ymt.com/gy_13761","name":"油菜籽"},"14":{"url":"http://www.ymt.com/gy_14874","name":"谷子"},"15":{"url":"http://www.ymt.com/gy_7958","name":"粳米"},"16":{"url":"http://www.ymt.com/gy_20150","name":"葵花"},"17":{"url":"http://www.ymt.com/gy_7662","name":"黑豆"},"18":{"url":"http://www.ymt.com/gy_7764","name":"花椒"},"19":{"url":"http://www.ymt.com/gy_29738","name":"山茶油"},"20":{"url":"http://www.ymt.com/gy_27702","name":"芝麻种子"},"21":{"url":"http://www.ymt.com/gy_8180","name":"糯米"},"22":{"url":"http://www.ymt.com/gy_7242","name":"菜籽油"},"23":{"url":"http://www.ymt.com/gy_7673","name":"黑芝麻"},"24":{"url":"http://www.ymt.com/gy_15303","name":"糯玉米"},"25":{"url":"http://www.ymt.com/gy_433737","name":"黑玉米渣子"},"26":{"url":"http://www.ymt.com/gy_433603","name":"火麻油"},"27":{"url":"http://www.ymt.com/gy_418713","name":"大豆油"},"28":{"url":"http://www.ymt.com/gy_296483","name":"亚麻籽油"},"29":{"url":"http://www.ymt.com/gy_8139","name":"面粉"},"30":{"url":"http://www.ymt.com/gy_15494","name":"芝麻油"},"31":{"url":"http://www.ymt.com/gy_16001","name":"葵花籽油"},"32":{"url":"http://www.ymt.com/gy_21769","name":"黄米"},"33":{"url":"http://www.ymt.com/gy_30620","name":"花椒籽"},"34":{"url":"http://www.ymt.com/gy_7243","name":"蚕豆"},"35":{"url":"http://www.ymt.com/gy_9962","name":"糜子"},"36":{"url":"http://www.ymt.com/gy_444573","name":"谷子种子"},"37":{"url":"http://www.ymt.com/gy_441940","name":"黔高7号"},"38":{"url":"http://www.ymt.com/gy_440459","name":"苋菜籽面粉"},"39":{"url":"http://www.ymt.com/gy_435289","name":"富硒红谷小香米"},"40":{"url":"http://www.ymt.com/gy_426657","name":"山茶油果"},"41":{"url":"http://www.ymt.com/gy_425206","name":"山胡椒油"},"42":{"url":"http://www.ymt.com/gy_423433","name":"大杂米"},"43":{"url":"http://www.ymt.com/gy_296496","name":"米糠油"},"44":{"url":"http://www.ymt.com/gy_296486","name":"葵花子油"},"45":{"url":"http://www.ymt.com/gy_296373","name":"槿麻"},"46":{"url":"http://www.ymt.com/gy_296371","name":"罗布麻"},"47":{"url":"http://www.ymt.com/gy_296354","name":"天竺桑"},"48":{"url":"http://www.ymt.com/gy_296353","name":"黔鄂桑"},"49":{"url":"http://www.ymt.com/gy_296346","name":"鸡桑"},"50":{"url":"http://www.ymt.com/gy_296344","name":"鬼桑"},"51":{"url":"http://www.ymt.com/gy_296336","name":"叶用甜菜"},"52":{"url":"http://www.ymt.com/gy_296332","name":"饲料甜菜"},"53":{"url":"http://www.ymt.com/gy_296319","name":"埃塞俄比亚芥菜菜籽"},"54":{"url":"http://www.ymt.com/gy_296317","name":"甘蓝型油菜籽"},"55":{"url":"http://www.ymt.com/gy_296312","name":"芥菜型油菜籽"},"56":{"url":"http://www.ymt.com/gy_296308","name":"白菜型油菜籽"},"57":{"url":"http://www.ymt.com/gy_296306","name":"彩棉"},"58":{"url":"http://www.ymt.com/gy_296304","name":"黄棉"},"59":{"url":"http://www.ymt.com/gy_296303","name":"白棉"},"60":{"url":"http://www.ymt.com/gy_296261","name":"常规稻"},"61":{"url":"http://www.ymt.com/gy_296260","name":"糯稻谷"},"62":{"url":"http://www.ymt.com/gy_296257","name":"籼稻谷"},"63":{"url":"http://www.ymt.com/gy_287219","name":"长果桑"},"64":{"url":"http://www.ymt.com/gy_272063","name":"马坝油粘米"},"65":{"url":"http://www.ymt.com/gy_253650","name":"青麻"},"66":{"url":"http://www.ymt.com/gy_229189","name":"油瓜"},"67":{"url":"http://www.ymt.com/gy_7159","name":"白芝麻"},"68":{"url":"http://www.ymt.com/gy_7240","name":"菜油"},"69":{"url":"http://www.ymt.com/gy_7355","name":"大麦"},"70":{"url":"http://www.ymt.com/gy_7459","name":"豆粕"},"71":{"url":"http://www.ymt.com/gy_7468","name":"豆油"},"72":{"url":"http://www.ymt.com/gy_7919","name":"荞麦"},"73":{"url":"http://www.ymt.com/gy_8096","name":"绿豆"},"74":{"url":"http://www.ymt.com/gy_8258","name":"色拉油"},"75":{"url":"http://www.ymt.com/gy_8444","name":"籼米"},"76":{"url":"http://www.ymt.com/gy_8460","name":"香油"},"77":{"url":"http://www.ymt.com/gy_8494","name":"燕麦"},"78":{"url":"http://www.ymt.com/gy_8561","name":"芝麻"},"79":{"url":"http://www.ymt.com/gy_8584","name":"棕榈油"},"80":{"url":"http://www.ymt.com/gy_9074","name":"薏米"},"81":{"url":"http://www.ymt.com/gy_9245","name":"油豆"},"82":{"url":"http://www.ymt.com/gy_9945","name":"籽棉"},"83":{"url":"http://www.ymt.com/gy_10306","name":"皮棉"},"84":{"url":"http://www.ymt.com/gy_10358","name":"米糠"},"85":{"url":"http://www.ymt.com/gy_11874","name":"剑麻"},"86":{"url":"http://www.ymt.com/gy_13458","name":"长绒棉"},"87":{"url":"http://www.ymt.com/gy_14814","name":"麦麸"},"88":{"url":"http://www.ymt.com/gy_14955","name":"胡麻油"},"89":{"url":"http://www.ymt.com/gy_15126","name":"粳稻谷"},"90":{"url":"http://www.ymt.com/gy_15725","name":"八宝米"},"91":{"url":"http://www.ymt.com/gy_15968","name":"杂交稻"},"92":{"url":"http://www.ymt.com/gy_16649","name":"棉籽"},"93":{"url":"http://www.ymt.com/gy_17597","name":"花椒油"},"94":{"url":"http://www.ymt.com/gy_21638","name":"胡麻籽"},"95":{"url":"http://www.ymt.com/gy_21655","name":"黄麻"},"96":{"url":"http://www.ymt.com/gy_22234","name":"亚麻籽"},"97":{"url":"http://www.ymt.com/gy_22347","name":"黑麦"},"98":{"url":"http://www.ymt.com/gy_22878","name":"细绒棉"}}},"submenuid-11":{"name":"其他","child":{"0":{"url":"http://www.ymt.com/gy_10707","name":"蜂蜜"},"1":{"url":"http://www.ymt.com/gy_8738","name":"粉条"},"2":{"url":"http://www.ymt.com/gy_58158","name":"红薯干"},"3":{"url":"http://www.ymt.com/gy_30130","name":"绿茶"},"4":{"url":"http://www.ymt.com/gy_434108","name":"五倍子花粉"},"5":{"url":"http://www.ymt.com/gy_7509","name":"腐竹"},"6":{"url":"http://www.ymt.com/gy_16846","name":"牛奶"},"7":{"url":"http://www.ymt.com/gy_25664","name":"红茶"},"8":{"url":"http://www.ymt.com/gy_73349","name":"山毛桃核"},"9":{"url":"http://www.ymt.com/gy_74964","name":"黄茶"},"10":{"url":"http://www.ymt.com/gy_18816","name":"棉粕"},"11":{"url":"http://www.ymt.com/gy_443492","name":"青贮草"},"12":{"url":"http://www.ymt.com/gy_443121","name":"皮渣"},"13":{"url":"http://www.ymt.com/gy_442164","name":"干山楂"},"14":{"url":"http://www.ymt.com/gy_441703","name":"番石榴茶"},"15":{"url":"http://www.ymt.com/gy_439622","name":"芋头干"},"16":{"url":"http://www.ymt.com/gy_439313","name":"板皮"},"17":{"url":"http://www.ymt.com/gy_433138","name":"柠檬籽"},"18":{"url":"http://www.ymt.com/gy_430629","name":"桃叶子"},"19":{"url":"http://www.ymt.com/gy_429656","name":"檀香木"},"20":{"url":"http://www.ymt.com/gy_423785","name":"椰壳"},"21":{"url":"http://www.ymt.com/gy_423227","name":"黄姑娘酸浆"},"22":{"url":"http://www.ymt.com/gy_422939","name":"工艺品葫芦"},"23":{"url":"http://www.ymt.com/gy_422812","name":"野生藤茶"},"24":{"url":"http://www.ymt.com/gy_415227","name":"葵花籽饼"},"25":{"url":"http://www.ymt.com/gy_414240","name":"茶树油"},"26":{"url":"http://www.ymt.com/gy_407299","name":"高粱秆"},"27":{"url":"http://www.ymt.com/gy_396502","name":"标本"},"28":{"url":"http://www.ymt.com/gy_382388","name":"红豆杉茶"},"29":{"url":"http://www.ymt.com/gy_296403","name":"黑茶"},"30":{"url":"http://www.ymt.com/gy_7354","name":"大料"},"31":{"url":"http://www.ymt.com/gy_7497","name":"粉丝"},"32":{"url":"http://www.ymt.com/gy_7578","name":"桂皮"},"33":{"url":"http://www.ymt.com/gy_8459","name":"香叶"},"34":{"url":"http://www.ymt.com/gy_9098","name":"粽叶"},"35":{"url":"http://www.ymt.com/gy_9266","name":"陈皮"},"36":{"url":"http://www.ymt.com/gy_10197","name":"豆饼"},"37":{"url":"http://www.ymt.com/gy_10243","name":"干果"},"38":{"url":"http://www.ymt.com/gy_14115","name":"沮草"},"39":{"url":"http://www.ymt.com/gy_17191","name":"花茶"},"40":{"url":"http://www.ymt.com/gy_19319","name":"菜粕"},"41":{"url":"http://www.ymt.com/gy_20955","name":"可可豆"},"42":{"url":"http://www.ymt.com/gy_26732","name":"咖啡豆"},"43":{"url":"http://www.ymt.com/gy_28887","name":"柠檬片"},"44":{"url":"http://www.ymt.com/gy_32423","name":"竹竿"},"45":{"url":"http://www.ymt.com/gy_32742","name":"白酒"},"46":{"url":"http://www.ymt.com/gy_33064","name":"萝卜条"},"47":{"url":"http://www.ymt.com/gy_33070","name":"苜蓿"},"48":{"url":"http://www.ymt.com/gy_33190","name":"稻草"},"49":{"url":"http://www.ymt.com/gy_38646","name":"干木瓜"},"50":{"url":"http://www.ymt.com/gy_45763","name":"原木"},"51":{"url":"http://www.ymt.com/gy_46977","name":"菌棒"},"52":{"url":"http://www.ymt.com/gy_48073","name":"青储饲料"},"53":{"url":"http://www.ymt.com/gy_50817","name":"羊草"},"54":{"url":"http://www.ymt.com/gy_65885","name":"咖啡"},"55":{"url":"http://www.ymt.com/gy_67934","name":"酒糟"},"56":{"url":"http://www.ymt.com/gy_113652","name":"玫瑰花茶"},"57":{"url":"http://www.ymt.com/gy_166300","name":"菊花茶"},"58":{"url":"http://www.ymt.com/gy_168875","name":"白茶"},"59":{"url":"http://www.ymt.com/gy_177226","name":"罗布麻茶"},"60":{"url":"http://www.ymt.com/gy_184019","name":"云雾茶"}}}}';
        $cat = json_decode($params, 1);
        
        foreach ($cat as $k => $c) 
        {
            $c['name'] = trim($c['name']);
            echo $c['name'], '<br />';
            
            foreach ($c['child'] as $keys => $sub) 
            {
                $sub['name'] = trim($sub['name']);
                echo '----', $sub['name'], $sub['url'], '<br />';
            }
        }
        exit;
    }
    
    public function test1Action() 
    {
        $pp = Category::find(array(
            "parent_id='0' and is_show='1'"
        ));
        
        foreach ($pp as $p) 
        {
            echo "<option value='{$p->id}'>{$p->title}</option>";
            $ss = Category::find("parent_id='{$p->id}' and is_show='1'");
            
            foreach ($ss as $s) 
            {
                echo "<option value='{$s->id}'>----{$s->title}</option>";
            }
        }
        exit;
    }
    
    public function cnfruitAction() 
    {
        $cat = array(
            0 => array(
                'id' => '69',
                'title' => '哈密瓜',
                'url' => 'http://www.cnfruit.com/sell/hamigua/'
            ) ,
            1 => array(
                'id' => '54',
                'title' => '梨',
                'url' => 'http://www.cnfruit.com/sell/li/'
            ) ,
            2 => array(
                'id' => '1630',
                'title' => '木瓜',
                'url' => 'http://www.cnfruit.com/sell/mugua/'
            ) ,
            3 => array(
                'id' => '16',
                'title' => '苹果',
                'url' => 'http://www.cnfruit.com/sell/pingguo/'
            ) ,
            4 => array(
                'id' => '1306',
                'title' => '枇杷',
                'url' => 'http://www.cnfruit.com/sell/pipa/'
            ) ,
            5 => array(
                'id' => '857',
                'title' => '山楂',
                'url' => 'http://www.cnfruit.com/sell/shanzha/'
            ) ,
            6 => array(
                'id' => '75',
                'title' => '甜瓜',
                'url' => 'http://www.cnfruit.com/sell/tiangua/'
            ) ,
            7 => array(
                'id' => '67',
                'title' => '西瓜',
                'url' => 'http://www.cnfruit.com/sell/xigua/'
            ) ,
            8 => array(
                'id' => '64',
                'title' => '葡萄',
                'url' => 'http://www.cnfruit.com/sell/268/'
            ) ,
            9 => array(
                'id' => '71',
                'title' => '草莓',
                'url' => 'http://www.cnfruit.com/sell/269/'
            ) ,
            10 => array(
                'id' => '58',
                'title' => '猕猴桃',
                'url' => 'http://www.cnfruit.com/sell/271/'
            ) ,
            11 => array(
                'id' => '1634',
                'title' => '杨桃',
                'url' => 'http://www.cnfruit.com/sell/273/'
            ) ,
            12 => array(
                'id' => '1661',
                'title' => '西番莲',
                'url' => 'http://www.cnfruit.com/sell/277/'
            ) ,
            13 => array(
                'id' => '74',
                'title' => '石榴',
                'url' => 'http://www.cnfruit.com/sell/282/'
            ) ,
            14 => array(
                'id' => '88',
                'title' => '无花果',
                'url' => 'http://www.cnfruit.com/sell/283/'
            ) ,
            15 => array(
                'id' => '1046',
                'title' => '火龙果',
                'url' => 'http://www.cnfruit.com/sell/284/'
            ) ,
            16 => array(
                'id' => '1635',
                'title' => '桑葚',
                'url' => 'http://www.cnfruit.com/sell/286/'
            ) ,
            17 => array(
                'id' => '734',
                'title' => '枸杞',
                'url' => 'http://www.cnfruit.com/sell/287/'
            ) ,
            18 => array(
                'id' => '1639',
                'title' => '八月瓜',
                'url' => 'http://www.cnfruit.com/sell/288/'
            ) ,
            19 => array(
                'id' => '1047',
                'title' => '樱桃',
                'url' => 'http://www.cnfruit.com/sell/290/'
            ) ,
            20 => array(
                'id' => '1631',
                'title' => '桃',
                'url' => 'http://www.cnfruit.com/sell/291/'
            ) ,
            21 => array(
                'id' => '57',
                'title' => '枣',
                'url' => 'http://www.cnfruit.com/sell/292/'
            ) ,
            22 => array(
                'id' => '73',
                'title' => '杏',
                'url' => 'http://www.cnfruit.com/sell/293/'
            ) ,
            23 => array(
                'id' => '1041',
                'title' => '芒果',
                'url' => 'http://www.cnfruit.com/sell/295/'
            ) ,
            24 => array(
                'id' => '1101',
                'title' => '杨梅',
                'url' => 'http://www.cnfruit.com/sell/296/'
            ) ,
            25 => array(
                'id' => '1304',
                'title' => '橄榄',
                'url' => 'http://www.cnfruit.com/sell/298/'
            ) ,
            26 => array(
                'id' => '1687',
                'title' => '油橄榄',
                'url' => 'http://www.cnfruit.com/sell/299/'
            ) ,
            27 => array(
                'id' => '735',
                'title' => '荔枝',
                'url' => 'http://www.cnfruit.com/sell/301/'
            ) ,
            28 => array(
                'id' => '77',
                'title' => '核桃',
                'url' => 'http://www.cnfruit.com/sell/302/'
            ) ,
            29 => array(
                'id' => '83',
                'title' => '板栗',
                'url' => 'http://www.cnfruit.com/sell/303/'
            ) ,
            30 => array(
                'id' => '95',
                'title' => '开心果',
                'url' => 'http://www.cnfruit.com/sell/305/'
            ) ,
            31 => array(
                'id' => '78',
                'title' => '腰果',
                'url' => 'http://www.cnfruit.com/sell/306/'
            ) ,
            32 => array(
                'id' => '1873',
                'title' => '荸荠',
                'url' => 'http://www.cnfruit.com/sell/308/'
            ) ,
            33 => array(
                'id' => '740',
                'title' => '龙眼',
                'url' => 'http://www.cnfruit.com/sell/309/'
            ) ,
            34 => array(
                'id' => '94',
                'title' => '夏威夷果',
                'url' => 'http://www.cnfruit.com/sell/310/'
            ) ,
            35 => array(
                'id' => '79',
                'title' => '榛子',
                'url' => 'http://www.cnfruit.com/sell/311/'
            ) ,
            36 => array(
                'id' => '1054',
                'title' => '香榧',
                'url' => 'http://www.cnfruit.com/sell/313/'
            ) ,
            37 => array(
                'id' => '81',
                'title' => '杏仁',
                'url' => 'http://www.cnfruit.com/sell/314/'
            ) ,
            38 => array(
                'id' => '93',
                'title' => '松子',
                'url' => 'http://www.cnfruit.com/sell/315/'
            ) ,
            39 => array(
                'id' => '97',
                'title' => '莲子',
                'url' => 'http://www.cnfruit.com/sell/316/'
            ) ,
            40 => array(
                'id' => '61',
                'title' => '柚子',
                'url' => 'http://www.cnfruit.com/sell/319/'
            ) ,
            41 => array(
                'id' => '70',
                'title' => '柠檬',
                'url' => 'http://www.cnfruit.com/sell/321/'
            ) ,
            42 => array(
                'id' => '1662',
                'title' => '黄皮果',
                'url' => 'http://www.cnfruit.com/sell/324/'
            ) ,
            43 => array(
                'id' => '17',
                'title' => '香蕉',
                'url' => 'http://www.cnfruit.com/sell/327/'
            ) ,
            44 => array(
                'id' => '1624',
                'title' => '菠萝',
                'url' => 'http://www.cnfruit.com/sell/328/'
            ) ,
            45 => array(
                'id' => '65',
                'title' => '甘蔗',
                'url' => 'http://www.cnfruit.com/sell/329/'
            ) ,
            46 => array(
                'id' => '1626',
                'title' => '菠萝蜜',
                'url' => 'http://www.cnfruit.com/sell/330/'
            ) ,
            47 => array(
                'id' => '1040',
                'title' => '人参果',
                'url' => 'http://www.cnfruit.com/sell/331/'
            ) ,
            48 => array(
                'id' => '1664',
                'title' => '百香果',
                'url' => 'http://www.cnfruit.com/sell/332/'
            ) ,
            49 => array(
                'id' => '1636',
                'title' => '罗汉果',
                'url' => 'http://www.cnfruit.com/sell/334/'
            ) ,
            50 => array(
                'id' => '1625',
                'title' => '雪莲果',
                'url' => 'http://www.cnfruit.com/sell/335/'
            ) ,
            51 => array(
                'id' => '1645',
                'title' => '椰子',
                'url' => 'http://www.cnfruit.com/sell/336/'
            ) ,
            52 => array(
                'id' => '794',
                'title' => '榴莲',
                'url' => 'http://www.cnfruit.com/sell/340/'
            )
        );
        
        foreach ($cat as $val) 
        {
            echo "<option value='{$val['id']}'>{$val['title']}{$val['url']}</option><br />";
        }
        exit;
    }
    
    public function checkCat($title) 
    {
        $cat = Category::findFirstBytitle($title);
        return $cat ? $cat->id : 0;
    }
    
    public function cnfruit() 
    {
        // $cat = '{"0":{"title":"哈密瓜","url":"http://www.cnfruit.com/sell/hamigua/"},"1":{"title":"梨","url":"http://www.cnfruit.com/sell/li/"},"2":{"title":"木瓜","url":"http://www.cnfruit.com/sell/mugua/"},"3":{"title":"苹果","url":"http://www.cnfruit.com/sell/pingguo/"},"4":{"title":"枇杷","url":"http://www.cnfruit.com/sell/pipa/"},"5":{"title":"山楂","url":"http://www.cnfruit.com/sell/shanzha/"},"6":{"title":"甜瓜","url":"http://www.cnfruit.com/sell/tiangua/"},"7":{"title":"西瓜","url":"http://www.cnfruit.com/sell/xigua/"},"8":{"title":"羊奶果","url":"http://www.cnfruit.com/sell/yangnaiguo/"},"9":{"title":"葡萄","url":"http://www.cnfruit.com/sell/268/"},"10":{"title":"草莓","url":"http://www.cnfruit.com/sell/269/"},"11":{"title":"醋栗","url":"http://www.cnfruit.com/sell/270/"},"12":{"title":"猕猴桃","url":"http://www.cnfruit.com/sell/271/"},"13":{"title":"树莓","url":"http://www.cnfruit.com/sell/272/"},"14":{"title":"杨桃","url":"http://www.cnfruit.com/sell/273/"},"15":{"title":"连雾","url":"http://www.cnfruit.com/sell/275/"},"16":{"title":"人心果","url":"http://www.cnfruit.com/sell/276/"},"17":{"title":"西番莲","url":"http://www.cnfruit.com/sell/277/"},"18":{"title":"番木瓜","url":"http://www.cnfruit.com/sell/278/"},"19":{"title":"费约果（番石榴）","url":"http://www.cnfruit.com/sell/279/"},"20":{"title":"沙棘果","url":"http://www.cnfruit.com/sell/280/"},"21":{"title":"越橘","url":"http://www.cnfruit.com/sell/281/"},"22":{"title":"石榴","url":"http://www.cnfruit.com/sell/282/"},"23":{"title":"无花果","url":"http://www.cnfruit.com/sell/283/"},"24":{"title":"火龙果","url":"http://www.cnfruit.com/sell/284/"},"25":{"title":"柿","url":"http://www.cnfruit.com/sell/285/"},"26":{"title":"桑葚","url":"http://www.cnfruit.com/sell/286/"},"27":{"title":"枸杞","url":"http://www.cnfruit.com/sell/287/"},"28":{"title":"八月瓜","url":"http://www.cnfruit.com/sell/288/"},"29":{"title":"灯笼果","url":"http://www.cnfruit.com/sell/289/"},"30":{"title":"樱桃","url":"http://www.cnfruit.com/sell/290/"},"31":{"title":"桃","url":"http://www.cnfruit.com/sell/291/"},"32":{"title":"枣","url":"http://www.cnfruit.com/sell/292/"},"33":{"title":"杏","url":"http://www.cnfruit.com/sell/293/"},"34":{"title":"李","url":"http://www.cnfruit.com/sell/294/"},"35":{"title":"芒果","url":"http://www.cnfruit.com/sell/295/"},"36":{"title":"杨梅","url":"http://www.cnfruit.com/sell/296/"},"37":{"title":"油甘果","url":"http://www.cnfruit.com/sell/297/"},"38":{"title":"橄榄","url":"http://www.cnfruit.com/sell/298/"},"39":{"title":"油橄榄","url":"http://www.cnfruit.com/sell/299/"},"40":{"title":"鳄梨","url":"http://www.cnfruit.com/sell/300/"},"41":{"title":"荔枝","url":"http://www.cnfruit.com/sell/301/"},"42":{"title":"核桃","url":"http://www.cnfruit.com/sell/302/"},"43":{"title":"板栗","url":"http://www.cnfruit.com/sell/303/"},"44":{"title":"银杏","url":"http://www.cnfruit.com/sell/304/"},"45":{"title":"开心果","url":"http://www.cnfruit.com/sell/305/"},"46":{"title":"腰果","url":"http://www.cnfruit.com/sell/306/"},"47":{"title":"巴西坚果","url":"http://www.cnfruit.com/sell/307/"},"48":{"title":"荸荠","url":"http://www.cnfruit.com/sell/308/"},"49":{"title":"龙眼","url":"http://www.cnfruit.com/sell/309/"},"50":{"title":"夏威夷果","url":"http://www.cnfruit.com/sell/310/"},"51":{"title":"榛子","url":"http://www.cnfruit.com/sell/311/"},"52":{"title":"鲍鱼果","url":"http://www.cnfruit.com/sell/312/"},"53":{"title":"香榧","url":"http://www.cnfruit.com/sell/313/"},"54":{"title":"杏仁","url":"http://www.cnfruit.com/sell/314/"},"55":{"title":"松子","url":"http://www.cnfruit.com/sell/315/"},"56":{"title":"莲子","url":"http://www.cnfruit.com/sell/316/"},"57":{"title":"橘","url":"http://www.cnfruit.com/sell/317/"},"58":{"title":"柑","url":"http://www.cnfruit.com/sell/318/"},"59":{"title":"柚子","url":"http://www.cnfruit.com/sell/319/"},"60":{"title":"橙","url":"http://www.cnfruit.com/sell/320/"},"61":{"title":"柠檬","url":"http://www.cnfruit.com/sell/321/"},"62":{"title":"枳","url":"http://www.cnfruit.com/sell/322/"},"63":{"title":"柳丁","url":"http://www.cnfruit.com/sell/323/"},"64":{"title":"黄皮果","url":"http://www.cnfruit.com/sell/324/"},"65":{"title":"葡萄柚","url":"http://www.cnfruit.com/sell/325/"},"66":{"title":"佛手","url":"http://www.cnfruit.com/sell/326/"},"67":{"title":"香蕉","url":"http://www.cnfruit.com/sell/327/"},"68":{"title":"菠萝","url":"http://www.cnfruit.com/sell/328/"},"69":{"title":"甘蔗","url":"http://www.cnfruit.com/sell/329/"},"70":{"title":"菠萝蜜","url":"http://www.cnfruit.com/sell/330/"},"71":{"title":"人参果","url":"http://www.cnfruit.com/sell/331/"},"72":{"title":"百香果","url":"http://www.cnfruit.com/sell/332/"},"73":{"title":"红毛丹","url":"http://www.cnfruit.com/sell/333/"},"74":{"title":"罗汉果","url":"http://www.cnfruit.com/sell/334/"},"75":{"title":"雪莲果","url":"http://www.cnfruit.com/sell/335/"},"76":{"title":"椰子","url":"http://www.cnfruit.com/sell/336/"},"77":{"title":"刺梨","url":"http://www.cnfruit.com/sell/337/"},"78":{"title":"台湾青枣","url":"http://www.cnfruit.com/sell/338/"},"79":{"title":"山竹子(莽吉柿)","url":"http://www.cnfruit.com/sell/339/"},"80":{"title":"榴莲","url":"http://www.cnfruit.com/sell/340/"},"81":{"title":"槟榔","url":"http://www.cnfruit.com/sell/341/"}}';
        $cat = '{"0":{"url":"http://www.cnfruit.com/sell/561/","title":"板栗树苗"},"1":{"url":"http://www.cnfruit.com/sell/562/","title":"草莓苗"},"2":{"url":"http://www.cnfruit.com/sell/563/","title":"柑橘苗"},"3":{"url":"http://www.cnfruit.com/sell/564/","title":"枸杞苗"},"4":{"url":"http://www.cnfruit.com/sell/565/","title":"核桃树苗"},"5":{"url":"http://www.cnfruit.com/sell/566/","title":"火龙果苗"},"6":{"url":"http://www.cnfruit.com/sell/567/","title":"蓝莓苗"},"7":{"url":"http://www.cnfruit.com/sell/568/","title":"梨树苗"},"8":{"url":"http://www.cnfruit.com/sell/569/","title":"李子树苗"},"9":{"url":"http://www.cnfruit.com/sell/570/","title":"荔枝苗"},"10":{"url":"http://www.cnfruit.com/sell/571/","title":"莲雾苗"},"11":{"url":"http://www.cnfruit.com/sell/572/","title":"龙眼苗"},"12":{"url":"http://www.cnfruit.com/sell/573/","title":"芒果苗"},"13":{"url":"http://www.cnfruit.com/sell/574/","title":"猕猴桃苗"},"14":{"url":"http://www.cnfruit.com/sell/575/","title":"苹果树苗"},"15":{"url":"http://www.cnfruit.com/sell/576/","title":"葡萄苗"},"16":{"url":"http://www.cnfruit.com/sell/577/","title":"桑葚苗"},"17":{"url":"http://www.cnfruit.com/sell/578/","title":"山楂树苗"},"18":{"url":"http://www.cnfruit.com/sell/579/","title":"石榴苗"},"19":{"url":"http://www.cnfruit.com/sell/580/","title":"桃树苗"},"20":{"url":"http://www.cnfruit.com/sell/581/","title":"甜瓜苗"},"21":{"url":"http://www.cnfruit.com/sell/582/","title":"无花果苗"},"22":{"url":"http://www.cnfruit.com/sell/583/","title":"西瓜苗"},"23":{"url":"http://www.cnfruit.com/sell/584/","title":"杏树苗"},"24":{"url":"http://www.cnfruit.com/sell/585/","title":"杨梅苗"},"25":{"url":"http://www.cnfruit.com/sell/586/","title":"银杏苗"},"26":{"url":"http://www.cnfruit.com/sell/587/","title":"樱桃苗"}}';
        $cat = json_decode($cat, 1);
        $rs = array();
        
        foreach ($cat as $val) 
        {
            
            if ($cid = $this->checkCat($val['title'])) 
            {
                $info = array();
                $info['id'] = $cid;
                $info['title'] = $val['title'];
                $info['url'] = $val['url'];
                $rs[] = $info;
            }
        }
        $rs = var_export($rs, 1);
        print_R($rs);
        exit;
    }
    
    public function adAction() 
    {
        $this->view->title = '丰收汇-丰收汇app下载';
        $this->view->keywords = '';
        $this->view->descript = '';
    }
    
    public function topicAction() 
    {
        $this->view->title = '丰收汇-丰收汇溯源码';
        $this->view->keywords = '';
        $this->view->descript = '';
    }
    
    public function workAction() 
    {
        $this->view->title = '丰收汇-寻找合作伙伴';
        $this->view->keywords = '';
        $this->view->descript = '';
    }
    public function propagandaAction(){
        $this->view->title = '丰收汇-可靠农场溯源系统';
        $this->view->keywords = '';
        $this->view->descript = '';
    }
    public function nofoundAction(){
        $this->view->title = '丰收汇-寻找合作伙伴';
        $this->view->keywords = '';
        $this->view->descript = '';
    }
    public function weartherAction(){
        $this->view->title = '丰收汇-高温补贴';
        $this->view->keywords = '';
        $this->view->descript = '';
    }
    public function propagandainfoAction($str){

        $this->view->str=$str;
        $this->view->title = '丰收汇-可靠农场溯源系统';
        $this->view->keywords = '';
        $this->view->descript = '';
    }
    public function themeAction(){
        $this->view->title = '丰收汇-专题人物';
        $this->view->keywords = '';
        $this->view->descript = '';
    }
    public function jujubeAction(){
        $this->view->title = '丰收汇-枣-专题';
        $this->view->keywords = '';
        $this->view->descript = '';
    }
    public function testynbAction(){
         $yongyou=L\Func::yongyouApi();
        
        $a=$yongyou->insertOrder_getinsert("mdg27872015072461", true, 'mdg');
        var_dump($a);die;
    }
    public function   tongbuweatherAction(){
        // $arr[]="53619";
        // $arr[]="566781";
        // $arr[]="471186";
        // $arr[]="312002";
        // $arr[]="57324";
        // $arr[]="54598";
        // $arr[]="608542";
        // $arr[]="610366";
        // $arr[]="615571";
        
        // $time[]="1437127870";
        // $time[]="1437350834";
        // $time[]="1437380775";
        // $time[]="1437492505";
        // $time[]="1437532392";
        // $time[]="1437621148";
        // $time[]="1439019181";
        // $time[]="1439087308";
        // $time[]="1439340146";

        // $address[]="潍坊";
        // $address[]="青岛";
        // $address[]="济南";
        // $address[]="潍坊";
        // $address[]="潍坊";
        // $address[]="菏泽";
        // $address[]="枣庄";
        // $address[]="枣庄";
        // $address[]="烟台";


        // foreach ($arr as $key => $value) {
        //     $user=M\Users::findFirstByid($value);
        //     $usersext=M\UsersExt::findFirstByuid($value);

        //     $a=L\Gift::gift($value,$user->username,$usersext->name,$time[$key],$address[$key]);
        //     var_dump($a);
        // }
        // print_r($arr);die;

    }
}
