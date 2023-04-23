<?php
namespace Mdg\Frontend\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Mdg\Models\Purchase as Purchase;
use Mdg\Models\Areas as Areas;
use Mdg\Models\Category as Category;
use Mdg\Models\PurchaseQuotation as Quotation;
use Mdg\Models\Orders as Orders;
use Mdg\Models as M;
use Lib\Pages as Pages;
use Lib\Time as Time;
use Lib\Utils as Utils;
use Lib\Func as Func;
use Lib\Validator as Validator;
class PurchaseController extends ControllerBase
{
    
    public function indexAction() {
        // $p          = $this->request->get('p', 'int', 1);
        //var_dump($p);die;
        $supply     = $this->request->get('supply','int',0);
        $page       = $this->dispatcher->getParam('p','int',1);
        $page = intval($page)>0 ? intval($page) : 1;
        $cate       = $this->dispatcher->getParam('c', 'int', 0);
        $area       = $this->dispatcher->getParam('a', 'int', 0);
        $maxcate    = $this->dispatcher->getParam('mc', 'int', 0);
        $f          = $this->dispatcher->getParam('f', 'string', '');
        //var_dump($firstStr);die;
        $keyword    = $this->request->get('keyword', 'string', '');
        $url['mc'] = $maxcate;
        $url['f'] = $f;
        $url['c'] = $cate;
        $url['p'] = $page;
        $url['a'] = $area; 
        $info = M\AreasFull::findFirstByid($area);
        $page_size=10;
        $where = array('state = 1 and is_del = 0');
        $where[] = 'endtime >= '.strtotime(date('Y-m-d'));
        $query_str = array();

        if($maxcate){
            $where[] = " maxcategory ={$maxcate} ";
        }
        if($cate) {
            $where[] = " category ={$cate} ";
        }
       
       

        if($info) {  
            
            if($info->level==1){
                $where[] = " province_id={$area} ";
            }else{
                $where[] = " city_id={$area} ";
            }
        }
         $keywordwhere =implode(' and ', $where);
        if($keyword) {
            
             $keyword =Validator::replace_specialChar($keyword);
             if($keyword){
                 $where[] = " title like '%{$keyword}%' and {$keywordwhere} or username like '%{$keyword}%' and {$keywordwhere} ";
             }else{
                  $where[] = " id=0 ";
             }
            
        }
        $where =implode(' and ', $where);
        
        $total = Purchase::count($where);
        
        $offst = intval(($page - 1) * $page_size);

        $recommand_data = array();

        $recommandpurchase = M\RecommandPurchase::find(" location_category=1 and status=1");
        //print_r($recommandpurchase->toArray());die;

        if($recommandpurchase->toArray()){

            $purchase_id = Func::getCols($recommandpurchase->toArray(), 'purchase_id', ",");
        
            $recommand_where = " and id in ({$purchase_id})";

            $count = Purchase::count($where.$recommand_where);

            $recommand_data = Purchase::find($where.$recommand_where."  ORDER BY updatetime DESC limit {$offst} , {$page_size} ")->toArray();

            if($count<10){                                
                if($page>=2){                   
                    $offst = $offst-$count;                   
                }else{
                    $page_size = $page_size -$count;
                }
                $where = $where." and id not in ({$purchase_id})";
            }

        }
        $purchase_data= M\Purchase::find( $where." ORDER BY updatetime DESC limit {$offst} , {$page_size}")->toArray();

        $data = array_merge($recommand_data,$purchase_data);

    $collect_purchase = array();
    if($user = $this->session->user){
        $collect_purchase = M\CollectPurchase::find("collect_uid = '{$user['id']}'")->toArray();
    }
    foreach($data as $key => $val){
        $data[$key]['quantity']=round($val['quantity'], 2);
        if(!$user){
            $data[$key]['is_collectsel'] = '';
            continue;
        }
        if(!$collect_purchase){
            $data[$key]['is_collectsel'] = '';
            continue;
        }
        foreach($collect_purchase as $k=>$v){
            if ($val['id'] == $v['purchase_id']){
                $data[$key]['is_collectsel'] = 1;
                break;
            }else{
                $data[$key]['is_collectsel'] = '';
            }
        }
    }


        $pages['total_pages'] = ceil($total / $page_size);
        $pages['current'] = $page;
        $pages['total'] = $total;
        $pages = new Pages($pages);
        $newpages = $pages->show(11);
        $newpages = str_replace(array('下一页', '上一页'), '', $newpages);
        $pages = $pages->show(10);


    foreach($data as $key=>$val){
        $time = new Time(time(), $val['updatetime']);
        $data[$key]['pubtime'] = $time->transform();
        $cai=M\UserInfo::checkpe($val["uid"]);
        $data[$key]['cai'] = $cai;
        $data[$key]['content'] = M\PurchaseContent::getPurchaseContent($val['id']);
    }
        $orders = Orders::find('state > 3 order by addtime desc limit 10')->toArray();

        foreach ($orders as & $ord) {
            $time = new Time(time(), $ord['addtime']);
            $ord['pubtime'] = $time->transform();
            $ord['areas_name'] = Utils::getC($ord['areas_name']);
        }
        $this->view->data       = $data;
        $this->view->newpages      = $newpages;
        $this->view->pages      = $pages;
        $this->view->total_count = ceil($total / $page_size);
        $this->view->goods_unit = Purchase::$_goods_unit;
        $this->view->orders = $orders;   
        if($maxcate){
            $this->view->cate = $maxcate;
        }else{
            $this->view->cate = $cate;
        }

        
        //seo
        if($cate){
            $catName = Category::findFirst("id = '{$cate}'")->title;
            if(!$catName){
                $catName = "农产品";
            }
        }else{
            $catName = Category::findFirst("id = '{$maxcate}'");
            if(!$catName){
                $catName = "农产品";
            }else{
            $catName=$catName->title;
            }
        }
        if($catName == "其他"){
            $catName = "农产品";
        }
        //如果只有地区
        if(isset($info->name) && !empty($info->name) && $catName=='农产品'){
            $catName = $info->name;
        }else if(isset($info->name) && !empty($info->name) && $catName!='农产品'){
            $catName = $info->name.$catName;
        }
        $Description = self::seoDescription($maxcate);
        $this->view->total = $total;
        //$this->view->firstStr   = $firstStr;
        $purchaseorderby=M\Purchase::gepurchase();
        $this->view->purchaseorderby = $purchaseorderby;
        $this->view->area       = $area;
        $this->view->controller = 'purchase';       
        $this->view->_nav       = 'purchase';
        $this->view->url        = $url;
        $this->view->title      = ''.$catName.'采购_求购'.$catName.'_'.$catName.'报价/价格/行情-丰收汇';
        $this->view->keywords   = ''.$catName.'采购，求购'.$catName.'，'.$catName.'报价行情， 丰收汇';
        $this->view->descript   = '丰收汇-可靠农产品交易网，为你提供'.$catName.'采购、求购'.$catName.'、'.$catName.'报价行情、'.$catName.'种类产地等采购信息，海量'.$catName.'商品采购信息，安全可靠有保障。';

        // $this->view->title = ''.$catName.'采购中心最新'.$catName.'采购信息大全_丰收汇-中国权威互联网农产品供求交易平台';
        // $this->view->keywords = ''.$catName.'价格,'.$catName.'采购,'.$catName.'批发,'.$catName.'采购信息,'.$catName.'大全';
        // $this->view->descript = ''.$Description.'';
        $this->view->navs_title = '采购中心';
        $this->view->keyword = urlencode($keyword);
        $this->view->maxcate=$maxcate;
    }

    /**
     * Index action
     */
    public function index1Action()
    {       
        $page = $this->request->get('p', 'int', 1);
        $arr=array();
        if ($_GET){

            if(!empty($_GET['category'])){   
                $arr["category"]=$_GET['category'];
                $this->session->category1=$_GET['category']; 
            }
            if(!empty($_GET['address'])){  
                $arr["area_id"]=$_GET['address'];
                $this->session->areas1=$_GET['address'];    
            }
            if(!empty($_GET['sellname'])){  
                $arr["sellname"]=$_GET['sellname'];

                //$this->session->sellname=$_REQUEST['sellname'];    
            }
            if(!empty($_GET['all'])){
                if($_GET["all"]=="-1"){
                    $this->session->category1="";
                }else{
                    $this->session->areas1="";
                }
                
            }
            if($this->session->category){
                $arr["category"]=$this->session->category;
            }
            if($this->session->areas){
                $arr["area_id"]=$this->session->areas;
            }
            
            $where = Quotation::conditions($arr);
            
            $query = Criteria::fromInput($this->di, "Mdg\Models\Purchase", $_REQUEST)->where($where);
            $this->persistent->parameters = $query->getParams();
        } else {    
            $numberPage = $this->request->getQuery("page", "int");
        }
        
        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = " updatetime desc ";

        $sell = M\Purchase::find($parameters);
        
        $paginator = new Paginator(array(
            "data" => $sell,
            "limit"=> 10,
            "page" => $page
        ));
    
       
        $data = $paginator->getPaginate();

        $pages = new Pages($data);
      
        $pages = $pages->show(1);

        $this->view->data = $data;
        $this->view->pages = $pages;
        $cat_list=M\Category::find("parent_id=0 ");
        $this->view->cat_list=$cat_list;
        $areas_list=M\Areas::find("level=1 and is_show=1 ");
        $this->view->areas_list=$areas_list;
        $order=M\Orders::find("purid!='0' order by  updatetime desc limit 0,10");
        $this->view->order=$order;
        $this->view->_nav = 'purchase';
        $this->view->title = '采购大厅-丰收汇-可靠农产品交易服务商';
        $this->view->keywords = '';
        $this->view->descript = '';
       
    }
    /**
     *  采购收藏
     *  @param  [type]  userId  用户ID
     *  @param  [type]  userName用户名称
     *  @param  [type]  purId   采购ID
     *  @return json
     */
    public function collectPurchaseAction() {
        
        // 获取参数
        $userId     = isset($this->session->user['id']) ? $this->session->user['id'] : 0;
        $userName   = isset($this->session->user['name']) ? $this->session->user['name'] : 0;
        $purId      = isset($_REQUEST['purId']) ? intval( $_REQUEST['purId'] ) : 0;
        
        // 校验参数
        if( !$userId ) {
            echo json_encode(array('code'=>0,'result'=>'请登录'));
            exit;
        }
        if( !$purId ) {
            echo json_encode(array('code'=>1,'result'=>'参数有误'));
            exit;
        }
        if( M\Purchase::findFirst("uid='{$userId}' and id='{$purId}'") ) {
            echo json_encode(array('code'=>2,'result'=>'不能收藏自己的商品哦'));
            exit;
        }
        // 判断用户是否已收藏
        $isCollect  = M\CollectPurchase::findFirst("purchase_id='{$purId}' and collect_uid='{$userId}'");
        if( !$isCollect ) { 
            
            // 根据卖货ID获取卖货信息
            $purInfo    = M\Purchase::findFirst("id='{$purId}'");
            // 入库收藏表
            $CollectPurchase = new M\CollectPurchase();
            $CollectPurchase->purchase_id       = $purId;
            $CollectPurchase->purchase_uid      = $purInfo->uid;
            $CollectPurchase->collect_uid       = $userId;
            $CollectPurchase->purchase_uname    = $purInfo->username;
            $CollectPurchase->areas_name        = $purInfo->areas_name;
            $CollectPurchase->publish_time      = $purInfo->createtime;
            $CollectPurchase->add_time          = CURTIME;
            $CollectPurchase->last_update_time  = CURTIME;
            if (!$CollectPurchase->save()){
                echo json_encode(array('code'=>3,'result'=>'收藏失败'));
                exit;
            } else {
                echo json_encode(array('code'=>4,'result'=>'收藏成功'));
                exit;
            }
        } else {
        
            $delPur = $this->db->query("delete from collect_purchase where `purchase_id`={$purId} and `collect_uid`={$userId}");
            if( !$delPur ) {
                echo json_encode(array('code'=>5,'result'=>'取消失败'));
                exit;
            } else {
                echo json_encode(array('code'=>6,'result'=>'取消成功'));
                exit;
            }
        }
    }


    public function seoDescription($maxcate){
        if($maxcate == 7){
            $Description = "丰收汇粮油采购包含玉米、小麦、大豆、谷子、花生、面粉、芝麻、绿豆、红豆、高粱、荞麦、大米、蚕豆、黑豆、小米等农产品采购信息，是农产品批发商、采购商的电子商务交易平台。";
        }else if($maxcate == 1){
            $Description = "丰收汇蔬菜采购包含白菜、菠菜、土豆、辣椒、大葱、生姜、菜豆、山药、西红柿、甘蓝、菜花、胡萝卜、大蒜、芹菜等农产品采购信息，是农产品批发商、采购商的电子商务交易平台。";
        }else if($maxcate == 2){
            $Description = "丰收汇水果采购包含苹果、香蕉、梨、脐橙、蜜橘、猕猴桃、柿子、柚子、葡萄、甘蔗、西瓜、哈密瓜、柠檬、草莓、圣女果、杏等农产品采购信息，是农产品批发商、采购商的电子商务交易平台。";
        }else if($maxcate == 1377){
            $Description = "丰收汇绿化苗木采购包含甘薯叶种苗、银杏树、栾树、槐树、红叶李、苹果树苗、柳树、香樟、臭椿、葡萄苗、桃树苗、红叶石楠、海棠等农产品采购信息，是农产品批发商、采购商的电子商务交易平台。";
        }else if($maxcate == 15){
            $Description = "丰收汇干果采购包含枸杞、核桃、花椒、南瓜籽、花生、葵花籽、板栗、玛卡、红枣等农产品采购信息，是农产品批发商、采购商的电子商务交易平台。";
        }else if($maxcate == 22){
            $Description = "丰收汇中药材采购包含人参、党参、白芷、防己、桂枝、水半夏、莲子、重楼、黄连、柠檬、当归、丹参等农产品采购信息，是农产品批发商、采购商的电子商务交易平台。";
        }else{
            $Description = "丰收汇农产品采购中心,发布全国各地粮油、蔬菜、水果、绿化苗木、干果、中药材等农产品采购信息，是农产品批发商、采购商的电子商务交易平台。";            
        }

        return $Description;
    }
    public function testAction(){
        $pur=Purchase::find()->toArray();
        $sql='';
        foreach ($pur as $key => $value) {
             $areas=M\AreasFull::getFamily($value['areas']);
             $province_id=$areas[0]['id'];
             $city_id=$areas[1]['id'];
             $id=$value["id"];
             if($province_id&&$city_id){
              $sql="update purchase set province_id={$province_id},city_id={$city_id} where id={$id}";
              file_put_contents(PUBLIC_PATH.'/purchase.txt',$sql."\n", FILE_APPEND);
             }
             echo $key;
        }
        echo "aaaaaaaaaa";die;
        print_r($pur);die;
        echo "2";die;
    }
}
