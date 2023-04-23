<?php
/**
 * 申请服务站
 */
namespace Mdg\Member\Controllers;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Mdg\Models\TmpFile as TmpFile;
use Mdg\Models\Users as Users;
use Mdg\Models\UsersExt as Usersext;
use Mdg\Models\Areas as mAreas;
use Mdg\Models\Purchase as Purchase;
use Mdg\Models\Category as Category;
use Mdg\Models\Shop as Shop;
use Mdg\Models\ShopCredit as ShopCredit;
use Mdg\Models\ShopCoods as ShopCoods;
use Mdg\Models\ShopCheck as ShopCheck;
use Mdg\Models as M;
use Lib as L;
use Lib\Func as Func;
use Lib\Path as Path;
use Lib\File as File;
use Lib\Pages as Pages;
use Lib\Category as lCategory;
use Lib\Areas as lAreas;
use Lib\Arrays as Arrays;

class ServiceController extends ControllerMember
{
    //显示服务站文章
    public function servicearticleAction () {
        $this->view->title = '个人中心-服务站管理-丰收汇-高价值农产品交易服务商';
    }

    /**
     * 保存店铺申请成功状态
     * @return [type] [description]
     */
    public function savesuccessstatusAction() {
        //查询店铺
        $shopid  = $this->request->getPost('shopid', 'int', 0 );
        $contact_man = L\Validator::replace_specialChar($this->request->getPost('contact_man', 'string', ''));
        $contact_phone = L\Validator::replace_specialChar($this->request->getPost('contact_phone', 'string', ''));
        $shop_desc = $this->request->getPost('shop_desc');
        $shopid = $this->request->getPost('shopid', 'int', 0);
        $data = M\Shop::findFirst( " shop_id = '{$shopid}'");
        if(!$data) $this->response->redirect("service/index")->sendHeaders();

        $data->contact_man  = $contact_man;
        $data->contact_phone = $contact_phone;
        $data->save();

        //修改备注
        $ShopCredit = M\ShopCredit::findFirst(" shop_id = '{$shopid}'");
        $ShopCredit->shop_desc = $shop_desc;
        $ShopCredit->save();
        
        $this->response->redirect("service/index")->sendHeaders();
    }
    /**
     * 我的服务站
     */
    
    public function indexAction() 
    {
        $shop = $this->checkServiceExist();
        $goods = array();
       
        $p = $this->request->get('p', 'int', 1);
        if (!$shop) {
            $this->response->redirect("service/new")->sendHeaders();
        }

        if (!$shop['is_service']) 
        {
            $this->response->redirect("service/new")->sendHeaders();
        }
        // 查看服务站状态
     
        if ($shop['shop_status'] != 1 && $shop['is_service']) 
        {
            $this->response->redirect("service/look")->sendHeaders();
        }
        
        $name = array();

        $area = lAreas::ldarea($shop['village_id']);
        $shop_id = $shop['shop_id'];
        $shopgoods = ShopCoods::find("shop_id={$shop_id}")->toarray();
        //负责区域
        $areas = M\ServiceArea::find(" service_id = '{$shop_id}'")->toArray(); 
        if ($shopgoods) 
        {
            $goods = Arrays::getCols($shopgoods, 'goods_name');
        }

        foreach ($areas as $key => $val) {
            $province = $val['province'];
            $city     = $val['city'];
            $distinct = $val['distinct'];
            $town[]   = $val['town'];

            $province = M\AreasFull::getAreasNametoid($val['province']);
            $city = M\AreasFull::getAreasNametoid($val['city']);
            $distinct = M\AreasFull::getAreasNametoid($val['distinct']);

            $Viewareas[] = $name['town'] = M\AreasFull::getAreasNametoid($val['town']);
           
        }
        //查询审核未通过的原因
        $shopcheck = ShopCheck::findFirst("shop_id={$shop_id} order by id desc ");

        //获取地址
        $addressArea[] = M\AreasFull::getAreasNametoid($shop['province_id']);
        $addressArea[] = M\AreasFull::getAreasNametoid($shop['city_id']);
        $addressArea[] = M\AreasFull::getAreasNametoid($shop['county_id']);
        $addressArea[] = M\AreasFull::getAreasNametoid($shop['town_id']);
        $addressArea[] = M\AreasFull::getAreasNametoid($shop['village_id']);
        //查询 服务站头像 或者logo
        $cond[] = " shop_id = '{$shop_id}' ";
        $cond['columns'] = " personal_logo_picture ";
        $credit = ShopCredit::findFirst($cond);

        $sellcond[] = " is_hot = 1 AND is_del = 0 AND state = 1 ";
        $sellcond['columns'] = " id, uid, title, thumb, createtime, sell_sn, uname ";
        $sellData = M\Sell::getSellList($sellcond , $p );
       
        $this->view->province = $province;
        $this->view->city = $city;
        $this->view->distinct = $distinct;
        $this->view->sellData = $sellData;
        $this->view->credit = $credit;
        $this->view->addressArea = join('', $addressArea);
        $this->view->Viewareas = join(',', $Viewareas );
        
        //店铺信息
        $this->view->shopcheck = $shopcheck;
        $this->view->area = $area;
        $this->view->shopgoods = join("、", $goods);
        $this->view->usertype = Users::$_user_type1;
        $this->view->business_type = shop::$_business_type;
        $this->view->shopstate = shop::$_shop_state;
        $this->view->shop = $shop;
        $this->view->title = '店铺管理-用户中心-丰收汇-高价值农产品交易服务商';
        $this->view->keywords = '丰收汇,农业,农产品,农副产品,农产品批发,农产品电商,农产品现货,农产品交易,农产品加工,农产品价格';
        $this->view->descript = '丰收汇是依托中国农业互联网高科技综合服务商云农场的丰富资源和先进技术而建立的中国最专业的网上农产品服务平台，
        为中国农村专业合作社、中小农商企业等提供农产品信息发布、行情资讯、网上商城、金融服务、定向对接、安全模型、订单种植等专业化服务，
        旨在成为全球高价值农产品交易服务商。';
    }
    
    public function listAction() 
    {
        $shop = $this->checkShopExist();
        
        if (!$shop) 
        {
            $this->response->redirect("service/new")->sendHeaders();
        }
        else
        {
            $this->response->redirect("service/index")->sendHeaders();
        }
    }
    /**
     * 我要开店
     */
    
    public function newAction() 
    {

        $user_id = $this->getUserID();
        $sid = md5(session_id());
        TmpFile::clearOld($sid);
        $shop = $this->checkServiceExist($user_id);

        
        if ($shop) 
        {
            $this->response->redirect("service/index")->sendHeaders();
        }

        $users = Users::findFirstByid($user_id);
       
        $curAreas = $users->areas ? lAreas::ldData($users->areas) : '';
        $this->view->users = $users;
        $this->view->curAreas = $curAreas;
        $this->view->servicetype = shop::$_service_type;
        $this->view->sid = $sid;
        $this->view->title = '个人中心-服务站管理-申请服务站-丰收汇-高价值农产品交易服务商';
        
    }
    /**
     * 修改店铺信息
     */
    
    public function editAction() 
    {
        $province=  0;
        $city=  0;
        $distinct=  0;
        $town = array();
        $Viewareas = array();
        


        $shop = $this->checkServiceExist(1);
        
        if (!$shop) 
        {
            $this->response->redirect("service/index")->sendHeaders();
            die;
        }
        
        if ($shop->shop_status == 0) 
        {
            $this->response->redirect("service/index")->sendHeaders();
        }
        //负责区域
        $areas = M\ServiceArea::find(" service_id = '{$shop->shop_id}'")->toArray(); 
        $name = array();

        foreach ($areas as $key => $val) {
            $province = $val['province'];
            $city     = $val['city'];
            $distinct = $val['distinct'];
            $town[]   = $val['town'];

            $name['province'] = M\AreasFull::getAreasNametoid($val['province']);
            $name['city'] = M\AreasFull::getAreasNametoid($val['city']);
            $name['distinct'] = M\AreasFull::getAreasNametoid($val['distinct']);
            $name['town'] = M\AreasFull::getAreasNametoid($val['town']);
            $Viewareas[] = implode( '', $name);
        }

        $shop_type = array_flip(shop::$_shop_type);
        $shop_type = isset($shop_type[$shop->shop_type]) ? $shop_type[$shop->shop_type] : 7;
        $shopgoods = ShopCoods::find("shop_id=$shop->shop_id")->toarray();
        $sid = md5(session_id());
        TmpFile::clearOld($sid);

        $this->view->province = $province;
        $this->view->city = $city;
        $this->view->distinct = $distinct;
        $this->view->town = implode(',', $town);
        $this->view->usertype = Users::$_user_type1;
        $this->view->business_type = shop::$_business_type;
        $this->view->servicetype = shop::$_service_type;
        $this->view->sid = $sid;
        $this->view->shop = $shop;
        $this->view->Viewareas = $Viewareas;
        $this->view->shopgoods = $shopgoods;
        $this->view->shop_type = $shop_type;
        $this->view->area = lAreas::ldData($shop->village_id);
        $this->view->title = '个人中心-服务站管理-丰收汇-高价值农产品交易服务商';
        
    }
    
    /**
     * 生成服务站信息
     * @return [type] [description]
     */
    public function createAction() 
    {
        
        //创建随机数
        if (!$this->request->isPost() || $this->request->getPost('shop_desc') == '') 
        {
            echo "<script>alert('各项不能为空');location.href='/member/shop/new'</script>";
            die;
        }
        $time = time();
        $service_area = trim($this->request->getPost('service_area', 'string',''),',');

        $user_id = $this->getUserID();
        $user_name = $this->getUserName();
        $users = Users::findFirstByid($user_id);
        $cur_time = $time;
        $shop_desc = $this->request->getPost('shop_desc');
        $qita = L\Validator::replace_specialChar($this->request->getPost('qita', 'string', ''));
        $shop_type = L\Validator::replace_specialChar($this->request->getPost('shoptype', 'string', ''));
        $user_type = $this->request->getPost('business_type', 'int', 0);
        $main_product[] = L\Validator::replace_specialChar($this->request->getPost("main_product1", 'string', ''));
        $main_product[] = L\Validator::replace_specialChar($this->request->getPost("main_product2", 'string', ''));
        $main_product[] = L\Validator::replace_specialChar($this->request->getPost("main_product3", 'string', ''));
        $main_product[] = L\Validator::replace_specialChar($this->request->getPost("main_product4", 'string', ''));
        $main_product[] = L\Validator::replace_specialChar($this->request->getPost("main_product4", 'string', ''));
        $shop = new Shop();
        $shop->shop_name = L\Validator::replace_specialChar($this->request->getPost("shop_name", 'string', ''));
        $shop->user_id = $user_id;
        $shop->business_type = $this->request->getPost("business_type", 'int', '');
        $shop->user_type = $this->request->getPost("business_type", 'int', 0);

        
        if ($shop_type == 7) 
        {
            $shop->shop_type = $qita;
        }
        else
        {
            $shop->shop_type = isset(shop::$_shop_type[$shop_type]) ? shop::$_shop_type[$shop_type] : 0;
        }
        $shop->shop_status = 0;
        $shop->contact_man = L\Validator::replace_specialChar($this->request->getPost("contact_man", 'string', ''));
        $shop->contact_phone = L\Validator::replace_specialChar($this->request->getPost("contact_phone", 'string', ''));
        $shop->province_id = $this->request->getPost("province", 'int', 0);
        $shop->city_id = $this->request->getPost("city", 'int', 0);
        $shop->county_id = $this->request->getPost("county", 'int', 0);
        $shop->town_id = $this->request->getPost("zhen", 'int', 0);
        $shop->village_id = $this->request->getPost("district", 'int', 0);
        $shop->add_time = $shop->last_update_time = $cur_time;
        $shop->is_service = 1;
        $shop->is_recommended = 0;

        
        if (!$shop->save()) 
        {
            // print_R($shop->getMessages());
        }
        //处理编号以及服务id
        $shop->shop_no =Shop::getShopNo($shop->shop_id);
        //sprintf('HC10365%05u', $shop->shop_id);
        $shop->service_id = $shop->shop_id;
        $shop->save();
        
        //处理负责区域
        if($service_area) {
            $data = explode(',', $service_area);
            $thrid = isset($data[0]) ? intval($data[0]) : 0;
            //查询地区上级
            $thrreg = L\AreasFull::getAreasFull($thrid);
      
            //生成地区
            foreach ($data as $key => $val) {
                $ServiceArea = new M\ServiceArea();
                $ServiceArea->service_id = $shop->shop_id;
                $ServiceArea->province = $thrreg[0]['id'];
                $ServiceArea->city = $thrreg[1]['id'];
                $ServiceArea->distinct = $thrreg[2]['id'];
                $ServiceArea->town = $val;
                $ServiceArea->village = 0;
                $ServiceArea->add_time = $time;
                $ServiceArea->last_update_time = $time;
                $ServiceArea->save();
            }
        }

        // 处理图片
        $sid = md5(session_id());
        //处理店铺认证信息
        $shopcontent = new ShopCredit();
        $shopcontent->shop_id = $shop->shop_id;
        $shopcontent->identity_no = L\Validator::replace_specialChar($this->request->getPost("identity_no", 'string', ''));
        $shopcontent->bank_name = L\Validator::replace_specialChar($this->request->getPost("bank_name", 'string', ''));
        $shopcontent->account_name = L\Validator::replace_specialChar($this->request->getPost("account_name", 'string', ''));
        $shopcontent->card_no = L\Validator::replace_specialChar($this->request->getPost("card_no", 'string', ''));
        $shopcontent->shop_desc = $shop_desc;
        $shopcontent->add_time = $shopcontent->last_update_time = $cur_time;
        $tmpFile = TmpFile::find("sid='{$sid}' and type in (6,7,8,9,10,11,21) ");
        /**
         * 移动 上传图片
         * @var [type]
         */
        
        foreach ($tmpFile as $key => $value) 
        {
            
            if ($value->type == 6) 
            {
                $shopcontent->bank_card_picture = $value->file_path;
            }
            
            if ($value->type == 7) 
            {
                $shopcontent->identity_card_front = $value->file_path;
            }
            
            if ($value->type == 8) 
            {
                $shopcontent->identity_picture_lic = $value->file_path;
            }
            
            if ($value->type == 9) 
            {
                $shopcontent->identity_card_back = $value->file_path;
            }
            
            if ($value->type == 10) 
            {
                $shopcontent->tax_registration = $value->file_path;
            }
            
            if ($value->type == 11) 
            {
                $shopcontent->organization_code = $value->file_path;
            }
            //个人照 ||企业logo
            
            if ($value->type == 21) 
            {
                $shopcontent->personal_logo_picture = $value->file_path;
            }
        }
        
        if (!$shopcontent->save()) 
        {
            // print_R($shopcontent->getMessages());
        }
        
        if ($tmpFile) 
        {
            $tmpFile->delete();
        }
        //写入主营商品
        
        if ($main_product) 
        {
            // $main_products=explode(",",$main_product);
            
            foreach ($main_product as $key => $value) 
            {
                
                if ($value) 
                {
                    //店铺主营产品处理
                    $shopgoods = new ShopCoods();
                    $shopgoods->shop_id = $shop->shop_id;
                    $shopgoods->goods_name = $value;
                    $shopgoods->shop_name = $shop->shop_name;
                    $shopgoods->user_id = $user_id;
                    $shopgoods->user_name = $user_name;
                    $shopgoods->goods_status = 0;
                    $shopgoods->add_time = $shopgoods->last_update_time = $cur_time;
                    $shopgoods->save();
                }
            }
        }
        $this->response->redirect("service/index")->sendHeaders();
    }
    /**
     * 保存修改
     */
    
    public function saveAction() 
    {
        if (!$this->request->isPost()) 
        {
            $this->flash->error('信息不能为空');
            return $this->dispatcher->forward(array(
                "controller" => "sell",
                "action" => "list"
            ));
        }
        $service_area = trim($this->request->getPost('service_area', 'string',''),',');
        $time = time();
        $user_id = $this->getUserID();
        $user_name = $this->getUserName();
        $users = Users::findFirstByid($user_id);
        $cur_time = $time;
        $sid = L\Validator::replace_specialChar($this->request->getPost('sid', 'string', 0));
        $shopid = $this->request->getPost('shop_id', 'int', 0);
        $shop_desc = $this->request->getPost('shop_desc');
        $qita = L\Validator::replace_specialChar($this->request->getPost('qita', 'string', ''));
        $shop_type = L\Validator::replace_specialChar($this->request->getPost('shoptype', 'string', ''));

        //编辑店铺基本信息
        $shop = Shop::findFirst("shop_id={$shopid}  AND is_service = 1 AND user_id={$user_id} ");
 
        if (!$shop) 
        {
            die("服务站信息不存在");
        }

        $main_product[] = L\Validator::replace_specialChar($this->request->getPost("main_product1", 'string', ''));
        $main_product[] = L\Validator::replace_specialChar($this->request->getPost("main_product2", 'string', ''));
        $main_product[] = L\Validator::replace_specialChar($this->request->getPost("main_product3", 'string', ''));
        $main_product[] = L\Validator::replace_specialChar($this->request->getPost("main_product4", 'string', ''));
        $main_product[] = L\Validator::replace_specialChar($this->request->getPost("main_product4", 'string', ''));

        $shop_name = L\Validator::replace_specialChar($this->request->getPost("enterprise_name", 'string', ''));
        $shop->shop_name = $shop_name;
        $shop->user_id = $user_id;
        $shop->business_type = $this->request->getPost("business_type", 'int', 0);
        $shop->user_type = $this->request->getPost("business_type", 'int', 0);
        
        if ($shop_type == 7) 
        {
            $shop->shop_type = $qita;
        }
        else
        {
            $shop->shop_type = isset(shop::$_shop_type[$shop_type]) ? shop::$_shop_type[$shop_type] : 0;
        }

        $shop->contact_man = L\Validator::replace_specialChar($this->request->getPost("contact_man", 'string', ''));
        $shop->contact_phone = L\Validator::replace_specialChar($this->request->getPost("contact_phone", 'string', ''));
        $shop->province_id = $this->request->getPost("province", 'int', 0);
        $shop->city_id = $this->request->getPost("city", 'int', 0);
        $shop->county_id = $this->request->getPost("county", 'int', 0);
        $shop->town_id = $this->request->getPost("zhen", 'int', 0);
        $shop->village_id = $this->request->getPost("district", 'int', 0);
        $shop->add_time = $shop->last_update_time = $cur_time;
        $shop->service_id = $shop->service_id;
        $shop->is_service = $shop->is_service;
        $shop->is_recommended = $shop->is_recommended;

        // if ($shop->shop_status != 1) 
        // {
            $shop->shop_status = 0;
        // }

        if (!$shop->save()) 
        {
            return $this->dispatcher->forward(array(
                "controller" => "service",
                "action" => "index"
            ));
        }
        /**
         * 修改服务站负责区域
         */
        if($service_area) {
            $serviceArea = M\ServiceArea::find(" service_id = '{$shopid}'");
            if($serviceArea) {
                $serviceArea->delete();
            }

            $data = explode(',', $service_area);
            $thrid = isset($data[0]) ? intval($data[0]) : 0;
            //查询地区上级
            $thrreg = L\AreasFull::getAreasFull($thrid);
      
            //生成地区
            foreach ($data as $key => $val) {
                $ServiceArea = new M\ServiceArea();
                $ServiceArea->service_id = $shop->shop_id;
                $ServiceArea->province = $thrreg[0]['id'];
                $ServiceArea->city = $thrreg[1]['id'];
                $ServiceArea->distinct = $thrreg[2]['id'];
                $ServiceArea->town = $val;
                $ServiceArea->village = 0;
                $ServiceArea->add_time = $time;
                $ServiceArea->last_update_time = $time;
                $ServiceArea->save();
            }
        }

        ////编辑店铺认证信息
        $shopcontent = ShopCredit::findFirst("shop_id={$shopid}");
        $shopcontent->shop_id = $shop->shop_id;
        $shopcontent->identity_no = L\Validator::replace_specialChar($this->request->getPost("identity_no", 'string', ''));
        $shopcontent->bank_name = L\Validator::replace_specialChar($this->request->getPost("bank_name", 'string', ''));
        $shopcontent->account_name = L\Validator::replace_specialChar($this->request->getPost("account_name", 'string', ''));
        $shopcontent->card_no = L\Validator::replace_specialChar($this->request->getPost("card_no", 'string', ''));
        $shopcontent->shop_desc = $shop_desc;
        $shopcontent->last_update_time = $cur_time;
        //如果有图片上传 直接覆盖
        $tmpFile = TmpFile::find("sid='{$sid}' and type in (6,7,8,9,10,11,21) ");
        
        if ($tmpFile) 
        {
            
            foreach ($tmpFile as $key => $value) 
            {
                
                if ($value->type == 6) 
                {
                    $shopcontent->bank_card_picture = $value->file_path;
                }
                
                if ($value->type == 7) 
                {
                    $shopcontent->identity_card_front = $value->file_path;
                }
                
                if ($value->type == 8) 
                {
                    $shopcontent->identity_picture_lic = $value->file_path;
                }
                
                if ($value->type == 9) 
                {
                    $shopcontent->identity_card_back = $value->file_path;
                }
                
                if ($value->type == 10) 
                {
                    $shopcontent->tax_registration = $value->file_path;
                }
                
                if ($value->type == 11) 
                {
                    $shopcontent->organization_code = $value->file_path;
                }
                #个人照片 企业logo
                if ($value->type == 21) 
                {
                    $shopcontent->personal_logo_picture = $value->file_path;
                }
            }
        }
        
        if (!$shopcontent->save()) 
        {
            
            foreach ($sell->getMessages() as $message) 
            {
                $this->flash->error('服务站认证信息修改失败');
            }
            return $this->dispatcher->forward(array(
                "controller" => "service",
                "action" => "index"
            ));
        }
        
        if ($tmpFile) 
        {
            $tmpFile->delete();
        }
        
        if ($main_product) 
        {
            $shopgoods = ShopCoods::find("shop_id={$shopid}");
            
            if ($shopgoods) 
            {
                $shopgoods->delete();
            }
            
            foreach ($main_product as $key => $value) 
            {
                
                if ($value) 
                {
                    //店铺主营产品处理
                    $shopgoods = new ShopCoods();
                    $shopgoods->shop_id = $shop->shop_id;
                    $shopgoods->goods_name = $value;
                    $shopgoods->shop_name = $shop->shop_name;
                    $shopgoods->user_id = $user_id;
                    $shopgoods->user_name = $user_name;
                    $shopgoods->goods_status = 0;
                    $shopgoods->last_update_time = $cur_time;
                    $shopgoods->save();
                }
            }
        }
        $this->response->redirect("service/index")->sendHeaders();
    }
    /**
     *  查看当前登录服务站详细
     * @return [type] [description]
     */
    
    public function lookAction() 
    {
        $service = $this->checkServiceExist(1);
        $Viewareas = array();

        if (!$service) 
        {
            $this->response->redirect("service/new")->sendHeaders();
        }
        $shop = $service;
        $area = lAreas::ldarea($shop->village_id);
        $shop_id = $shop->shop_id;
        $shopgoods = ShopCoods::find("shop_id={$shop_id}")->toarray();
        
        if ($shopgoods) 
        {
            $goods = Arrays::getCols($shopgoods, 'goods_name');
        }
        //负责区域
        $areas = M\ServiceArea::find(" service_id = '{$shop_id}'")->toArray(); 
        $name = array();
        foreach ($areas as $key => $val) {
            
            $province = M\AreasFull::getAreasNametoid($val['province']);
            $city = M\AreasFull::getAreasNametoid($val['city']);
            $distinct = M\AreasFull::getAreasNametoid($val['distinct']);

            $Viewareas[] = $name['town'] = M\AreasFull::getAreasNametoid($val['town']);
        }

        //查询审核未通过的原因
        $shopcheck = ShopCheck::findFirst("shop_id={$shop_id} order by id desc ");

        $this->view->province = $province;
        $this->view->city = $city;
        $this->view->distinct = $distinct;
        //店铺信息
        $this->view->Viewareas = join(',', $Viewareas );
        $this->view->shopcheck = $shopcheck;
        $this->view->area = $area;
        $this->view->shopgoods = join("、", $goods);
        $this->view->business_type = shop::$_service_type;
        $this->view->shopstate = shop::$_shop_state;
        $this->view->shop = $shop;

        $this->view->title = '个人中心-服务站管理-丰收汇-高价值农产品交易服务商';

    }

    /**
     * 删除图片
     * @return [type] [description]
     */
    public function delimgAction() 
    {
        $rs = array(
            'state' => false,
            'msg' => '删除图片成功！'
        );
        $id = $this->request->get('id', 'int', 0);
        $img = SellImages::findFirstByid($id);
        
        if (!$img) 
        {
            $rs['msg'] = '图片不存在！';
            die(json_encode($rs));
        }
        
        if ($this->getUserID() != $img->sell->uid) 
        {
            $rs['msg'] = '你无权删除此图片！';
            die(json_encode($rs));
        }
        $sellid = $img->sellid;
        @unlink(PUBLIC_PATH . $img->path);
        $img->delete();
        $data = SellImages::findFirstBysellid($sellid);
        
        if (!$data) 
        {
            $sell = Sell::findFirstByid($sellid);
            
            if ($sell) 
            {
                $sell->thumb = '';
                $sell->save();
            }
        }
        $rs['state'] = true;
        die(json_encode($rs));
    }
    /**
     * 店铺编辑
     * @return [type] [description]
     */
    
    public function neweditAction() 
    {
        $shop = $this->checkServiceExist(1);
    
        if (!$shop) 
        {
            $this->response->redirect("service/index")->sendHeaders();
            die;
        }   
        
        if ($shop->shop_status != 2 ) 
        {
            $this->response->redirect("service/index")->sendHeaders();
        }

        //负责区域
        $areas = M\ServiceArea::find(" service_id = '{$shop->shop_id}'")->toArray(); 
        $name = array();

        foreach ($areas as $key => $val) {
            $province = $val['province'];
            $city     = $val['city'];
            $distinct = $val['distinct'];
            $town[]   = $val['town'];

            $name['province'] = M\AreasFull::getAreasNametoid($val['province']);
            $name['city'] = M\AreasFull::getAreasNametoid($val['city']);
            $name['distinct'] = M\AreasFull::getAreasNametoid($val['distinct']);
            $name['town'] = M\AreasFull::getAreasNametoid($val['town']);
            $Viewareas[] = implode( '', $name);
        }

        $this->view->province = $province;
        $this->view->city = $city;
        $this->view->distinct = $distinct;
        $this->view->town = implode(',', $town);
        
        $user_id = $shop->user_id;
        $users = Users::findFirstByid($user_id);
        $curAreas = $users->areas ? lAreas::ldData($users->areas) : '';
        $this->view->users = $users;
        $this->view->Viewareas = $Viewareas;
        $shop_type = array_flip(shop::$_shop_type);
        $shop_type = isset($shop_type[$shop->shop_type]) ? $shop_type[$shop->shop_type] : 7;
        $shopgoods = ShopCoods::find("shop_id=$shop->shop_id")->toarray();
        $sid = md5(session_id());
        TmpFile::clearOld($sid);
        $this->view->usertype = Users::$_user_type1;
        $this->view->business_type = shop::$_business_type;
        $this->view->servicetype = shop::$_service_type;
        
        $this->view->curAreas = $curAreas;
        $this->view->sid = $sid;
        $this->view->shop = $shop;
        $this->view->shopgoods = $shopgoods;
        $this->view->shop_type = $shop_type;
        $this->view->area = lAreas::ldData($shop->village_id);
        $this->view->title = '服务站管理-编辑-丰收汇-高价值农产品交易服务商';
        $this->view->keywords = '丰收汇,农业,农产品,农副产品,农产品批发,农产品电商,农产品现货,农产品交易,农产品加工,农产品价格';
        $this->view->descript = '丰收汇是依托中国农业互联网高科技综合服务商云农场的丰富资源和先进技术而建立的中国最专业的网上农产品服务平台，
        为中国农村专业合作社、中小农商企业等提供农产品信息发布、行情资讯、网上商城、金融服务、定向对接、安全模型、订单种植等专业化服务，
        旨在成为全球高价值农产品交易服务商。';
    }
    
    public function newsaveAction() 
    {
        
        if (!$this->request->isPost()) 
        {
            $this->flash->error('信息不能为空');
            return $this->dispatcher->forward(array(
                "controller" => "sell",
                "action" => "list"
            ));
        }
        $user_id = $this->getUserID();
        $user_name = $this->getUserName();
        $users = Users::findFirstByid($user_id);
        $cur_time = time();
        $sid = L\Validator::replace_specialChar($this->request->getPost('sid', 'string', 0));
        $shopid = $this->request->getPost('shopid', 'int', 0);
        $shop_desc = $this->request->getPost('shop_desc');
        $main_product[] = L\Validator::replace_specialChar($this->request->getPost("main_product1", 'string', ''));
        $main_product[] = L\Validator::replace_specialChar($this->request->getPost("main_product2", 'string', ''));
        $main_product[] = L\Validator::replace_specialChar($this->request->getPost("main_product3", 'string', ''));
        $main_product[] = L\Validator::replace_specialChar($this->request->getPost("main_product4", 'string', ''));
        $main_product[] = L\Validator::replace_specialChar($this->request->getPost("main_product4", 'string', ''));
        //编辑店铺基本信息
        $shop = Shop::findFirst("shop_id={$shopid} and user_id={$user_id} ");
        
        if (!$shop) 
        {
            die("店铺信息不存在");
        }
        $shop->contact_phone = L\Validator::replace_specialChar($this->request->getPost("contact_phone", 'string', ''));
        $shop->province_id = $this->request->getPost("province", 'int', 0);
        $shop->city_id = $this->request->getPost("city", 'int', 0);
        $shop->county_id = $this->request->getPost("county", 'int', 0);
        $shop->town_id = $this->request->getPost("zhen", 'int', 0);
        $shop->village_id = $this->request->getPost("district", 'int', 0);
        $shop->last_update_time = $cur_time;
        
        if (!$shop->save()) 
        {
            
            foreach ($sell->getMessages() as $message) 
            {
                $this->flash->error('发布出售失败！');
            }
            return $this->dispatcher->forward(array(
                "controller" => "shop",
                "action" => "list"
            ));
        }
        ////编辑店铺认证信息
        $shopcontent = ShopCredit::findFirst("shop_id={$shopid}");
        $shopcontent->shop_desc = $shop_desc;
        $shopcontent->last_update_time = $cur_time;
        //如果有图片上传 直接覆盖
        
        if (!$shopcontent->save()) 
        {
            
            foreach ($sell->getMessages() as $message) 
            {
                $this->flash->error('店铺认证信息修改失败');
            }
            return $this->dispatcher->forward(array(
                "controller" => "shop",
                "action" => "list"
            ));
        }
        
        if ($main_product) 
        {
            $shopgoods = ShopCoods::find("shop_id={$shopid}");
            
            if ($shopgoods) 
            {
                $shopgoods->delete();
            }
            
            foreach ($main_product as $key => $value) 
            {
                
                if ($value) 
                {
                    //店铺主营产品处理
                    $shopgoods = new ShopCoods();
                    $shopgoods->shop_id = $shop->shop_id;
                    $shopgoods->goods_name = $value;
                    $shopgoods->shop_name = $shop->shop_name;
                    $shopgoods->user_id = $user_id;
                    $shopgoods->user_name = $user_name;
                    $shopgoods->goods_status = 0;
                    $shopgoods->last_update_time = $cur_time;
                    $shopgoods->save();
                }
            }
        }
        $this->response->redirect("shop/list")->sendHeaders();
    }
}
