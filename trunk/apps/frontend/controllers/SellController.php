<?php
/**
 * 供应中心
 */
namespace Mdg\Frontend\Controllers;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Mdg\Models\Sell as Sell;
use Mdg\Models\Areas as Areas;
use Mdg\Models\Category as Category;
use Mdg\Models\Purchase as Purchase;
use Mdg\Models\Users as Users;
use Mdg\Models\SellImages as SellImages;
use Mdg\Models\ShopCoods as ShopCoods;
use Mdg\Models\ShopGrade as ShopGrade;
use Mdg\Models\ShopComments as ShopComments;
use Mdg\Models\GoodsComments as GoodsComments;
use Mdg\Models\Shop as Shop;
use Mdg\Models\Orders as Orders;
use Mdg\Models as M;
use Lib\File as File, Lib\Func as Func;
use Lib\Pages as Pages;
use Lib\Time as Time;
use Lib\Utils as Utils;
use Lib as L;
use Lib\Areas as Areast;
use Lib\Validator as Validator;
class SellController extends ControllerBase
{
    

    /**
     * 列表页
     */
    public function indexAction() 
    {

        $imgtrue = $this->request->get('img', 'int', 0);
        $isbroke = $this->request->get('ib','int',0);
        $p = $this->request->get('p', 'int', 1);
        $p = intval($p)>0 ? intval($p) : 1;
        $supply = $this->request->get('supply','int',0);
        $page    = $this->dispatcher->getParam('p','int',1);
        $page = intval($page)>0 ? intval($page) : 1;
        $cate    = $this->dispatcher->getParam('c', 'int', 0);
        $area    = $this->dispatcher->getParam('a', 'int', 0);
        $sellid  = $this->dispatcher->getParam('u', 'int', 0);
        $maxcate = $this->dispatcher->getParam('mc', 'int', 0);
        $f = $this->dispatcher->getParam('f', 'string', '');
        $url['mc'] = $maxcate;
        $url['f'] = $f;
        $url['c'] = $cate;
        $url['p'] = $page;
        $url['a'] = $area;
        $page_size = 10;
        $keyword = $this->request->get('keyword', 'string', '');
        if($keyword=='nx'){
            $keyword="宁夏中宁红枸杞";
        } 
        $info = M\AreasFull::findFirstByid($area);
        $where = array(
            'state = 1 and is_del = 0 and publish_place!=2'
        );
        $cond = array();
        
        if ($maxcate) 
        {
            $where[] = " maxcategory ={$maxcate} ";
        }
        
        if ($cate) 
        {
            $where[] = " category ={$cate} ";
        }
        if ($info) 
        {
            if($info->level==1){
                $where[] = " province_id ={$area} ";
            }else{
                $where[] = " city_id ={$area} ";
            }
        }
        if ($sellid) 
        {
            $where[] = " uid = '{$sellid}'";
        }
        if ($imgtrue=='1') 
        {
            $userdatas = M\Users::find("is_broker=1")->toArray();
            $userid =L\Arrays::getCols($userdatas,'id',',');
            if($userdatas){
                $where []= "  uid  not in ({$userid}) ";
            }else{
                $where [] = "  id=0 ";
            }
            $where[] = " is_source = 1";
            $imgis_true = 1;
        }
        else
        {
            $imgis_true = 0;
        }
        $where = implode(' and ', $where);
       
        
       
        //判断是否经纪人
        if($isbroke){
            $userdatas = M\Users::find("is_broker=1")->toArray();
            $userid =L\Arrays::getCols($userdatas,'id',',');
            if($userdatas){
                $where .= " and uid in ({$userid})  and  is_source!=1 ";
            }else{
                $where = " id=0 and {$where}";
            }
        }
       
        if ($keyword) 
        {   
            $keywords =Validator::replace_specialChar($keyword);
            if($keywords){
                $sellcount=Sell::count(" sell_sn like '%{$keyword}%' ");
                if($sellcount>0){
                    $where= " sell_sn like '%{$keyword}%' and {$where} ";
                }else{
                   $where= "    uname like '%{$keyword}%' and {$where}  or title like '%{$keyword}%' and {$where}";
                }
            }else{
                   $where = " id=0 and {$where}";
            }
           
        }
        $total = Sell::count($where);
        $offst = intval(($page - 1) * $page_size);
        if(!empty($recommand_data)){
             $sell_id = Func::getCols($recommand_data, 'id', ",");
             $exwhere=explode(" or ",$where);
           
             if(!empty($exwhere[1])){
                $where =" id not in ({$sell_id}) and {$exwhere[0]} or id not in ({$sell_id}) and  {$exwhere[1]}";
             }
             
        }
        //echo $where;die;
        $page_size = intval($page_size)>0 ? intval($page_size) : 0;
        
        $data = Sell::find($where . "   ORDER BY is_hot DESC,updatetime DESC limit {$offst} , {$page_size}")->toArray();
      
        // $data = array_merge($recommand_data, $sell_data);
    
        $collect_sell = array();
        if ($user = $this->session->user) 
        {
            $collect_sell = M\CollectSell::find("collect_uid = '{$user['id']}'")->toArray();
        }

        //print_r($data);die;
        foreach ($data as $key => $val) 
        {
            $data[$key]['quantity']=round($val['quantity'], 2);
            //$data[$key]['max_price']=round($val['max_price'], 2);
            if($val['thumb']) {
                $data[$key]['thumb'] = IMG_URL . $val['thumb'].'!142';
            }elseif($val['category'] && $image = M\Image::imgsrc($val['category'])) {
                    $data[$key]['thumb'] = M\Image::imgsrc($val['category']);
            }else{
                $data[$key]['thumb'] = 'http://static.ync365.com/mdg/images/detial_b_img.jpg';
            }
             $data[$key]['is_shopgoods'] = '';
             $shopgoods = M\UserInfo::checkuser_info($val['uid']);
             if ($shopgoods){
                $data[$key]['is_shopgoods'] = 1;
             }
            if (!$user) 
            {
                $data[$key]['is_collectsel'] = '';
                continue;
            }
            
            if (!$collect_sell) 
            {
                $data[$key]['is_collectsel'] = '';
                continue;
            }
            foreach ($collect_sell as $k => $v) 
            {
                
                if ($val['id'] == $v['sell_id']) 
                {
                    $data[$key]['is_collectsel'] = 1;
                    break;
                }
                else
                {
                    $data[$key]['is_collectsel'] = '';
                }
            }
        }
        if($page==1){
              $page_size=10;
        }

        $pages['total_pages'] = ceil($total / $page_size);
        
        $pages['current'] = $page;
        $pages['total'] = $total;
        $pages = new Pages($pages);
        $newpages = $pages->show(11);
        $newpages = str_replace(array('下一页', '上一页'), '', $newpages);
        //判断是否是产地直销
        foreach ($data as $key => $value) {
            $is_broker = M\Users::findFirstByid($value['uid']);
            $data[$key]["is_broker"]=$is_broker ? $is_broker->is_broker : 0;
        }
        $pages = $pages->show(10);
        
        if (empty($data)) 
        {
            $data = array();
        }
        $orders = Orders::find('sellid <> 0 and state>3 order by addtime desc limit 10')->toArray();

        foreach ($orders as & $ord) 
        {
            $time = new Time(time() , $ord['addtime']);
            $ord['pubtime'] = $time->transform();
            $ord['areas_name'] = Utils::getC($ord['areas_name']);
        }


        $special=Sell::getsellspecial($this->session->user ? $this->session->user['id'] : 0);
        
        foreach ($special as $key => $val) {
            if($val['thumb']) {
                $special[$key]['thumb'] = IMG_URL . $val['thumb'].'!136';
            }elseif($val['category'] && $image = M\Image::imgsrc($val['category'])) {
                $special[$key]['thumb'] = M\Image::imgsrc($val['category'],136);
            }else{
                $special[$key]['thumb'] = STATIC_URL . "/mdg/version2.4/images/detial_b_img.jpg";
            }
        }
        
        if ($maxcate) 
        {
            $this->view->cate = $maxcate;

        }
        else
        {
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
        $this->view->is_broker   = $isbroke;
        $this->view->time_type   = Sell::$type;
        $this->view->url         =$url;
        
        $this->view->total_count = ceil($total / $page_size);
        $this->view->special     = $special;
        $this->view->imgis_true  = $imgis_true;
        $this->view->data        = $data;
        $this->view->pages       = $pages;
        $this->view->newpages    = $newpages;
        $this->view->_nav        = 'sell';
        $this->view->goods_unit  = Purchase::$_goods_unit;
        $this->view->controller  = 'sell';
        $this->view->area        = $area;
        $this->view->orders      = $orders;
        // $this->view->title = ''.$catName.'供应大厅优质'.$catName.'供应信息大全_丰收汇-中国权威互联网农产品供求交易平台';
        $this->view->title = ''.$catName.'供应_'.$catName.'价格行情_'.$catName.'批发采购_'.$catName.'种类产地-丰收汇';
        // $this->view->keywords = ''.$catName.'价格,'.$catName.'供应,'.$catName.'批发,'.$catName.'供应信息,'.$catName.'大全';
        $this->view->keywords = ''.$catName.'供应，'.$catName.'价格行情，'.$catName.'批发采购， '.$catName.'种类产地，丰收汇';
        $this->view->descript = '丰收汇-可靠农产品交易网，为你提供'.$catName.'供应、'.$catName.'价格行情、'.$catName.'批发采购、'.$catName.'种类产地等供应信息，快速了解'.$catName.'商品供应信息，就来丰收汇。';
        // $this->view->descript = ''.$Description.'';
        $this->view->keyword = urlencode($keyword);
        $this->view->navs_title = '供应大厅';
        $this->view->maxcate=$maxcate;
    }
    /**
     * 供应详细页
     * @param  [type] $id 供应id
     * @return
     */
    
    public function infoAction($id = 0) 
    {
        $issource   = $this->request->get('source', 'int', 0);
        if (!$this->request->isPost()) {
            $cond[] = " id = '{$id}'  ";
            $sell   = M\Sell::findFirst($cond);
          
            if (!$sell) {
                $this->flash->error("此供应信息不存在！");
                return $this->dispatcher->forward(array(
                    "controller"=> "sell",
                    "action"    => "index"
                ));
            }

            $istag= 0;
            $plantList  = array();
            $tagsell    = array();
            $tagquality = array();
            $user_id    = $sell->uid;
            $contents   = '';
            $data = array();
            $shopgoods      = array();
            $Product        =array();
            $TagPesticide   = array();
            $TagManureList  = array();
            $TagCertifying  = array();
            $TagSeed = array();
            $userinfo=array();
            /**
             * 检测供应人是否是可信农场
             */
            $shopgoods = M\UserInfo::checkuser_info($user_id);
            
            
            //查询供应商是否有标签
            $tagSell = M\Tag::selectBySellid($sell->id);
            
        
            // 查询供应商信息
            $userExt            = M\UsersExt::findFirst('uid='.$sell->uid);
            if (empty($userExt->main_category) || $userExt->main_category< 1){
                $userExt->main_category = 1;
            }
            $goods = Category::findFirst(" id={$userExt->main_category} and is_show=1 ");

            $userinfo["goods"]        = $goods ? $goods->title : '';
            $userinfo["address"]   = isset($userExt->address) ? $userExt->address : '' ;
            $userinfo["userinfo"]     = M\Userinfo::selectIdentity($sell->uid);
            $userinfo["name"]         = $userExt->name;
            $userinfo["mobile"]       =M\Users::getUsersName($sell->uid) ? M\Users::getUsersName($sell->uid) : $sell->mobile;
            $userinfo["picture_path"] =isset($userExt->picture_path)&&$userExt->picture_path!=' ' ? $userExt->picture_path : IMG_URL."/upload/userpic.png" ;
            // 获取推荐商品
            
            $hot_sell = sell::getSellSameLimit($sell->category, $sell->id,5);
            if ($tagSell) {
                // 查询种植作业
                $plantList      = M\TagPlant::getTagPlantList($tagSell['tag_id'], 1);
                // 查询生产信息
                $Product        = M\TagProduct::getProductInfo($tagSell['tag_id']);
                // 质量评估
                $tagquality     = M\TagQuality::getTagQuality($tagSell['tag_id'], 1);
                // 查询农药信息
                $TagPesticide   = M\TagPesticide::getTagPesticideList($tagSell['tag_id']);
                $TagPesticide   = $TagPesticide ? $TagPesticide->toArray() : array();
                
                // 查询肥料信息
                $TagManureList  = M\TagManure::getTagManureList($tagSell['tag_id']);
                $TagManureList  = $TagManureList ? $TagManureList->toArray() : array();

                // 图片机构文件
                $TagCertifying  = M\TagCertifying::getTagCertifyingList($tagSell['tag_id']);
                /* 查询种子信息 */
                $TagSeed = M\TagSeed::findFirst(" tag_id = '{$tagSell['tag_id']}'");
               
            }
            $scontent = M\SellContent::findFirstBysid($id);
            if ($scontent && $scontent->attr != '') { 
                if ($id < 22247) {
                    $arr = str_replace("u", '\u', $scontent->attr);
                    $code = json_decode($arr, true);
                } else {
                    $code = json_decode($scontent->attr, true);
                }
                foreach ($code as $key => $value) {
                    $contents.= $value['title'] . ":" . $value['val'] . ';';
                }
            }
            Sell::clickAdd($id);
            //供应商还供应的商品
            $category = Category::findFirstByid($sell->category);
            
            if ($sell->uid) {

                $otherSell = Sell::find("uid='{$sell->uid}' and state = 1 and is_del=0  and publish_place!=2 and id <> '{$id}' limit 0,5")->toArray();
                foreach ($otherSell as $key=>$v) {
                    $otherSell[$key]['step_min'] = $v['price_type'] ? $this->db->fetchColumn("SELECT price from sell_step_price where sell_id = '{$v['id']}' order by price asc limit 1") : 0;
                }
            } else {
                $otherSell = array(
                    $sell->toArray()
                );
            }
           
            $user = Users::findFirstByid($sell->uid);
            //该供应下的图片
            $imgs = SellImages::find("sellid='{$id}'")->toArray();
            if ($sell->thumb) {
                $this->view->curImg     = IMG_URL . $sell->thumb;
                $this->view->maxcurImg  = IMG_URL . $sell->thumb;
            } else {
                $this->view->curImg     = array();
            }
            $goodscomments  = GoodsComments::find('sell_id='.$id.' AND is_check = 1')->toArray();
            $total_score    = '';
            $average_score  = '';
            $tr = '';
            $quantitys = 0;
            if($goodscomments){
                foreach($goodscomments as $v){
                    $total_score += $v['decribe_score'];
                }
                $average_score = intval($total_score/count($goodscomments));
                for($i=1;$i<=5;$i++){
                    if ($average_score >= $i ){
                        $tr .='<span class="star active"></span>';
                    }else{
                        $tr .='<span class="star"></span>';
                    }
                }
                $quantitys = count($goodscomments);
            }
            $sell->total_score  = $total_score;
            $sell->average_score= $average_score;
            $sell->tr           = $tr;
            $sell->quantity     = $sell->quantity;
          
            //店铺成交数  显示该账号的所有已完成的订单
            $ordercount = M\Orders::count("sellid ='$sell->id' and state>'3' ");

            // 判断是否被收藏
            $flag   = 0;
            $flagfarm = 0;
            $userId = isset($this->session->user['id']) ? $this->session->user['id'] : 0;
            $farmid = $shopgoods['farm_id'];
            if( $userId ) {
                if( M\CollectSell::findFirst("sell_id=$id AND collect_uid=$userId") ) {
                    $flag = 1;
                }

                if($farmid &&  M\CollectStore::findFirst("store_id=$farmid AND collect_uid=$userId") ) {
                    $flagfarm = 1;
                }
            }
      
            //seo
            $areasName  =  M\AreasFull::getFamily($sell->areas);
            if($areasName){
                $proName = $areasName[0]['name'];
                if($areasName[0]['id'] == 1 || $areasName[0]['id'] == 20  || $areasName[0]['id'] == 861  || $areasName[0]['id'] == 2465 ){
                    $areasName = $areasName[0]['name'];
                }else{
                    $areasName = $areasName[0]['name'].$areasName[1]['name'];
                }
                
            }else{
                $areasName = "";
                $proName = "";
            }
            $host = trim($_SERVER['HTTP_HOST'],'www.');
            $catename=M\Category::getFamily($sell->category);
            //盟商
            $lwtt_info=M\UserInfo::getlwttlist($sell->uid,$sell->mobile,1,0);
            
            $ueserlwtt=M\UserLwtt::getuserlwtt($sell->id);
            
            $this->view->lwtt_info=$lwtt_info;
            $this->view->ueserlwtt=$ueserlwtt;
            $this->view->maxcatename=$catename ? $catename[0]["title"] : '';
            $this->view->catename=$catename ? $catename[1]["title"] : '';
            $this->view->catabbr = $catename ? $catename[1]['abbreviation'] : '';
            $this->view->maxcateid=$catename ? $catename[0]["id"] : '';
            $this->view->cateid=$catename ? $catename[1]["id"] : '';

            $this->view->areasName =$areasName;
            $this->view->TagSeed =$TagSeed;
            $this->view->rainwater = M\TagSeed::$rainwater;
            $this->view->flag           = $flag;
            $this->view->hot_sell       = $hot_sell;
            $this->view->issource       = $issource;
            $this->view->TagPesticide   = $TagPesticide;
            $this->view->TagManureList  = $TagManureList;
            $this->view->TagCertifying  = $TagCertifying;
            $sellSame               = sell::getSellSameLimit($sell->category, $sell->id);
            $this->view->tagquality = $tagquality;
            $this->view->product    = $Product;

            $this->view->plantList  = $plantList;
            $this->view->tagsell    = $tagSell;
            $this->view->ordercount = $ordercount;
         
            $this->view->sellSame   = $sellSame ? $sellSame : array();
            $this->view->shopgrade  = $data;
            $this->view->shopgoods  = $shopgoods;
            $this->view->imgs       = $imgs;
            $this->view->otherSell  = $otherSell;


            $this->view->user       = $user;            
            $this->view->family     = Category::getFamily($sell->category);
            $this->view->quantitys  = $quantitys;
            $this->view->category   = $category;
            $this->view->_nav       = 'sell';
            $this->view->time_type  = Sell::$type;
            $this->view->sell       = $sell;
            $this->view->goods_unit = Purchase::$_goods_unit;
            $this->view->total      = count($otherSell);
            $this->view->cateid     = $sell->category;
            $this->view->userinfo   = $userinfo;
            $this->view->contents   = $contents;
            $this->view->flagfarm   = $flagfarm;
            //echo $_SERVER['HTTP_HOST'];die;
            $this->view->host       =$host;
            $this->view->title      = ''.$sell->areas_name.'-'.$sell->title.'价格';
            $this->view->keywords   = ''.$proName.''.$sell->title.'供应,'.$proName.''.$sell->title.'价格,'.$proName.''.$sell->title.'批发,'.$proName.''.$sell->title.'采购';
            $this->view->descript = ''.$sell->title.'供应时间'.Sell::$type[$sell->stime].'~'.Sell::$type[$sell->etime].',供货地址'.$sell->areas_name.'，更多'.$sell->title.'供应信息关注丰收汇。';
        }
    }
  
    /**
     * 查询标签作业图片
     * @return [type] [description]
     */
    
    public function tagplantAction() 
    {
        $pid = $this->request->get('sid', 'int', 0);
        $data = M\TagPicture::getTagPlantImgList($pid);
        $html = '';
        $tpl = '<li %s> <img src="%s" width="350" height="350" /></li>';
        
        foreach ($data as $key => $val) 
        {
            $html.= sprintf($tpl, !$key ? 'class="active"' : '', $val['path']);
        }
        exit($html);
    }
    /**
     * 查询种植相册
     * @return [type] [description]
     */
    /*
    public function tagplantAction () {
        $pid = $this->request->get('pid', 'int', 0);
        $type = $this->request->get('t', 'string', '');
        $sid = $this->request->get('sid', 'string', '');
        // $sname = $this->request->get('sname', 'string', '');
        $te = base64_decode($type);
        $sid = base64_decode($sid);
    
        //查询产品信息是否存在
        if(!$sid) {
            exit('信息错误');
        }
        
        //查询商品
        $sell = M\Sell::findFirst(" id ='{$sid}'");
    
    
        //查询作业是否存在
        $_operate_type = M\Tag::$_operate_type;
        //查询种植图片
        $data = M\TagPicture::getTagPlantImgList($pid);
    
        $this->view->sell = $sell;
        $this->view->sid = $sid;
        $this->view->sname = $sname;
        $this->view->te = $te;
        $this->view->_operate_type = $_operate_type;
        $this->view->data = $data;
        $this->view->title = "供应详情-作业相册-{$sname}-丰收汇-高价值农产品交易服务商";
        $this->view->keywords = '丰收汇,农业,农产品,农副产品,农产品批发,农产品电商,农产品现货,农产品交易,农产品加工,农产品价格';
        $this->view->descript = '丰收汇是依托中国农业互联网高科技综合服务商云农场的丰富资源和先进技术而建立的中国最专业的网上农产品服务平台，
            为中国农村专业合作社、中小农商企业等提供农产品信息发布、行情资讯、网上商城、金融服务、定向对接、安全模型、订单种植等专业化服务，
            旨在成为全球高价值农产品交易服务商。';
    
    }
    */
    /**
     *  供应收藏
     *  @param  [type]  userId  用户ID
     *  @param  [type]  userName用户名称
     *  @param  [type]  sellId  卖货ID
     *  @return json
     */
    
    public function collectSellAction() 
    {
        // 获取参数
        $userId = isset($this->session->user['id']) ? $this->session->user['id'] : 0;
        $userName = isset($this->session->user['name']) ? $this->session->user['name'] : 0;
        $sellId = isset($_REQUEST['sellId']) ? intval($_REQUEST['sellId']) : 0;
        // 校验参数
        if (!$userId) 
        {
            echo json_encode(array(
                'code' => 0,
                'result' => '请登录'
            ));
            exit;
        }
        
        if (!$sellId) 
        {
            echo json_encode(array(
                'code' => 1,
                'result' => '参数有误'
            ));
            exit;
        }
        
        if (M\Sell::findFirst("uid='{$userId}' and id='{$sellId}'")) 
        {
            echo json_encode(array(
                'code' => 2,
                'result' => '不能收藏自己的商品哦'
            ));
            exit;
        }
        // 判断用户是否已收藏
        $isCollect = $this->db->fetchOne("select id from collect_sell where sell_id='{$sellId}' and collect_uid='{$userId}'");
        
        if (!$isCollect) 
        {
            // 根据卖货ID获取卖货信息
            $sellInfo = M\Sell::findFirst("id='{$sellId}'");
            // 入库收藏表
            $collectSell = new M\CollectSell();
            $collectSell->sell_id = $sellId;
            $collectSell->collect_uid = $userId;
            $collectSell->sell_uid = $sellInfo->uid;
            $collectSell->sell_uname = $sellInfo->uname;
            $collectSell->goods_name = $sellInfo->title;
            $collectSell->goods_picture = $sellInfo->thumb;
            $collectSell->add_time = CURTIME;
            $collectSell->last_update_time = CURTIME;
            
            if (!$collectSell->save()) 
            {
                echo json_encode(array(
                    'code' => 3,
                    'result' => '收藏失败'
                ));
                exit;
            }
            else
            {
                echo json_encode(array(
                    'code' => 4,
                    'result' => '收藏成功'
                ));
                exit;
            }
        }
        else
        {
            // 取消收藏
            $delSell = $this->db->query("delete from collect_sell where sell_id={$sellId} and `collect_uid`={$userId}");
            if (!$delSell) 
            {
                echo json_encode(array(
                    'code' => 5,
                    'result' => '取消失败'
                ));
                exit;
            }
            else
            {
                echo json_encode(array(
                    'code' => 6,
                    'result' => '取消成功'
                ));
                exit;
            }   
        }
    }


    public function goodscommentsAction(){


        $sell_id = $this->request->get('sell_id', 'int', 0);
        $page = $this->request->get('p', 'int', 1);
        $page_size = 10;

        if ($page && $page > 0) 
        {
            $page = $page;
        }
        else
        {
            $page = 1;
        }
        $where = " sell_id = '{$sell_id}' and is_check = 1 ";
        $total = GoodsComments::count($where);
        $offst = intval(($page - 1) * $page_size);
        $data = GoodsComments::find($where."  ORDER BY add_time DESC limit {$offst} , {$page_size} ");
        $pages['total_pages'] = ceil($total / $page_size);
        $pages['current'] = $page;
        $pages['total'] = $total; 
        $pages = new Pages($pages);

        $pages = $pages->show(6);
        
        return $pages;
    }


    public function seoDescription($maxcate){
        if($maxcate == 7){
            $Description = "丰收汇粮油供应包含玉米、小麦、大豆、谷子、花生、面粉、芝麻、绿豆、红豆、高粱、荞麦、大米、蚕豆、黑豆、小米等农产品供应信息，是农产品批发商、供应商的电子商务交易平台。";
        }else if($maxcate == 1){
            $Description = "丰收汇蔬菜供应包含白菜、菠菜、土豆、辣椒、大葱、生姜、菜豆、山药、西红柿、甘蓝、菜花、胡萝卜、大蒜、芹菜等农产品供应信息，是农产品批发商、供应商的电子商务交易平台。";
        }else if($maxcate == 2){
            $Description = "丰收汇水果供应包含苹果、香蕉、梨、脐橙、蜜橘、猕猴桃、柿子、柚子、葡萄、甘蔗、西瓜、哈密瓜、柠檬、草莓、圣女果、杏等农产品供应信息，是农产品批发商、供应商的电子商务交易平台。";
        }else if($maxcate == 1377){
            $Description = "丰收汇绿化苗木供应包含甘薯叶种苗、银杏树、栾树、槐树、红叶李、苹果树苗、柳树、香樟、臭椿、葡萄苗、桃树苗、红叶石楠、海棠等农产品供应信息，是农产品批发商、供应商的电子商务交易平台。";
        }else if($maxcate == 15){
            $Description = "丰收汇干果供应包含枸杞、核桃、花椒、南瓜籽、花生、葵花籽、板栗、玛卡、红枣等农产品供应信息，是农产品批发商、供应商的电子商务交易平台。";
        }else if($maxcate == 22){
            $Description = "丰收汇中药材供应包含人参、党参、白芷、防己、桂枝、水半夏、莲子、重楼、黄连、柠檬、当归、丹参等农产品供应信息，是农产品批发商、供应商的电子商务交易平台。";
        }else{
            $Description = "丰收汇农产品供应大厅,发布全国各地粮油、蔬菜、水果、绿化苗木、干果、中药材等农产品供求信息，是农产品批发商、供应商的电子商务交易平台。";            
        }

        return $Description;
    }



    /**
     *  可信任农场收藏
     *  @param  [type]  userId  用户ID
     *  @param  [type]  sellId  卖货ID
     *  @return json
     */
    
    public function collectFarmAction() 
    {
        // 获取参数
        $userId = isset($this->session->user['id']) ? $this->session->user['id'] : 0;
        $farmid = isset($_REQUEST['farmId']) ? intval($_REQUEST['farmId']) : 0;
        // 校验参数
        if (!$userId) 
        {
            echo json_encode(array(
                'code' => 0,
                'result' => '请登录'
            ));
            exit;
        }
        
        if (!$farmid) 
        {
            echo json_encode(array(
                'code' => 1,
                'result' => '参数有误'
            ));
            exit;
        }

        $credible_farm_info = M\CredibleFarmInfo::findFirst("id={$farmid}");
        
        if ($credible_farm_info->user_id==$userId) 
        {
            echo json_encode(array(
                'code' => 2,
                'result' => '不能收藏自己的农场哦'
            ));
            exit;
        }
        // 判断用户是否已收藏
        $isCollect = M\CollectStore::findFirst("store_id='{$farmid}' and collect_uid='{$userId}'");
        
        if (!$isCollect) 
        {
            // 根据农场ID获取农场信息
            $userinfo = M\UserInfo::findFirst(array("user_id={$credible_farm_info->user_id} and status!=4 and credit_type=8", 'columns'=>'credit_id'));
            $user_farm_crops = M\UserFarmCrops::find("user_id = {$credible_farm_info->user_id} and credit_id={$userinfo->credit_id}")->toArray();
            
           // $user_farm_crops = M\UserFarmCrops::find("user_id = {$credible_farm_info->user_id}")->toArray();
            $main_products = Func::getCols($user_farm_crops, 'category_name', ',');
            #查询农场名称
            $user_farm = M\UserFarm::findFirst("credit_id={$userinfo->credit_id} ");
            if($user_farm){
                $farmname=$user_farm->farm_name;
            }else{
                $farmname='';
            }
            // 入库收藏表
            $CollectStore = new M\CollectStore();
            $CollectStore->store_id = $farmid;
            $CollectStore->store_name = $farmname;
            $CollectStore->main_products = $main_products;
            $CollectStore->collect_uid = $userId;
            $CollectStore->add_time = time();
            $CollectStore->last_update_time = time();
            
            if (!$CollectStore->save()) 
            {
                echo json_encode(array(
                    'code' => 3,
                    'result' => '收藏失败'
                ));
                exit;
            }
            else
            {
                echo json_encode(array(
                    'code' => 4,
                    'result' => '收藏成功'
                ));
                exit;
            }
        }
        else
        {
            $delFarm = $isCollect->delete();
            if (!$delFarm) 
            {
                echo json_encode(array(
                    'code' => 5,
                    'result' => '取消失败'
                ));
                exit;
            }
            else
            {
                echo json_encode(array(
                    'code' => 6,
                    'result' => '取消成功'
                ));
                exit;
            }   
        }
    }



}
