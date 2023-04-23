<?php

namespace Mdg\Manage\Controllers;
use Phalcon\Mvc\Controller;
use Mdg\Models as M;
use Lib as L;
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
use Lib\Func as Func;
use Lib\Path as Path;
use Lib\File as File;
use Lib\Pages as Pages;
use Lib\Category as lCategory;
use Lib\Areas as lAreas;
use Lib\Arrays as Arrays;

/**
 *  店铺管理
 */

class ShopController extends ControllerBase
{
    /**
     * 首页店铺管理
     * @return [type] [description]
     */
    
    public function indexAction() 
    {
        $page = $this->request->get('p', 'int', 1);
        $page_size = 20;
        $shop_state = $this->request->get('shop_state', 'string', 'all');
        $user_type = $this->request->get('user_type', 'string', '');
        $business_type = $this->request->get('business_type', 'int', 0);
        $shop_type = $this->request->get('shop_type', 'string', 'all');
        $stime = $this->request->get('stime', 'string', '');
        $etime = $this->request->get('etime', 'string', '');
        $shop_name = $this->request->get('shop_name', 'string', '');
        $conds[] = " is_service = 0  ";
        
        if ($shop_state != 'all') 
        {
            $conds[] = " shop_status = '{$shop_state}'";
        }
        // 职位类型
        
        if ($user_type) 
        {
            $conds[] = " business_type = '{$user_type}' ";
        }
        //用户 类型
        
        if ($business_type) 
        {
            $conds[] = " user_type = '{$business_type}'";
        }
        
        if ($shop_type != 'all') 
        {
            $conds[] = " shop_type = '{$shop_type}'";
        }
        
        if ($stime && $etime) 
        {
            $starttime = strtotime($stime . ' 00:00:00');
            $endtime = strtotime($etime . ' 23:59:59');
            $conds[] = " add_time BETWEEN '{$starttime}' AND '{$endtime}' ";
        }
        
        if ($shop_name) 
        {
            $conds[] = " shop_name = '{$shop_name}'";
        }
        $conds = implode(' AND ', $conds);
        $total = M\Shop::count($conds);
        $offst = intval(($page - 1) * $page_size);
        $conds.= " ORDER BY shop_id DESC  LIMIT {$offst}, {$page_size}";
        $cond[] = $conds;
        $cond['columns'] = ' is_recommended, shop_id, add_time, business_type, user_type, shop_type, shop_status, user_id, shop_name, shop_no ';
        $data = M\Shop::find($cond);
        $pages['total_pages'] = ceil($total / $page_size);
        $pages['current'] = $page;
        $pages['total'] = $total;
        $pages = new Pages($pages);
        $pages = $pages->show(1);
        $this->view->_business_type = M\Shop::$_business_type; // 类型
        $this->view->_shop_state = M\Shop::$_shop_state; //店铺状态
        $this->view->_shop_type = M\Shop::$_shop_type; // 类型
        $this->view->_user_type = M\Users::$_user_type1; // 用户类型
        $this->view->start = $page;
        $this->view->current = $page;
        $this->view->data = $data;
        $this->view->pages = $pages;
        $this->view->title = '店铺列表';
    }
    /**
     * 首页推荐
     * @param  integer $sid 店铺id
     * @return [type]       [description]
     */
    
    public function recAction($sid = 0) 
    {
        $data = M\Shop::getShopInfo($sid);
        $sid = $this->session->getId();
        M\TmpFile::clearall($sid, 23);
        $this->view->sid = $sid;
        $this->view->data = $data;
    }
    
    public function saverecAction() 
    {
        $sid = $this->session->getId();
        $shopid = $this->request->getPost('shopid', 'int', 0);
        $data = M\TmpFile::find(" sid = '{$sid}' AND type = '23'");
        //查询店铺 更新店铺状态
        $shop = M\Shop::findFirst("  shop_id = '{$shopid}'");
        
        if (!$shop) 
        {
            $this->response->redirect("shop/index")->sendHeaders();
        }
        
        foreach ($data as $key => $val) 
        {
            $Image = new M\Image();
            $Image->gid = $shopid;
            $Image->agreement_image = $val->file_path;
            $Image->createtime = time();
            $Image->state = 1;
            $Image->type = 23;
            $Image->save();
        }
        $shop->is_recommended = 1;
        
        if ($shop->save()) 
        {
        }
        $this->response->redirect("shop/index")->sendHeaders();
    }
    /**
     * 获取店铺详细信息
     * @param  integer $id 店铺ID
     * @return [type]      [description]
     */
    
    public function getAction($id = 0) 
    {
        
        if (!$id) $this->response->redirect("shop/index")->sendHeaders();
        $data = M\Shop::getShopInfo($id);
        $main_product = '';
        
        if (!$data) $this->response->redirect("shop/index")->sendHeaders();
        /**
         * 获取地区
         * @var [type]
         */
        $shopgoods = M\ShopCoods::find(" shop_id = '{$data->shop_id}'");
        
        if ($shopgoods) 
        {
            $main_product = L\Arrays::getCols($shopgoods->toArray() , 'goods_name');
            $main_product = join(',', $main_product);
        }

        $link = sprintf("F%06u", $data->shop_id);

        /* 检测域名是否被使用过 */
        if(M\Shop::count(" shop_link = '{$link}'") ) {
            $link = sprintf("F%06u", $data->shop_id. L\Func::random(3,1) );
        }

        $Areas = L\AreasFull::getAreasFull($data->village_id);
        $areasName = L\Arrays::getCols($Areas, 'name');
        $address = join('', $areasName);
        $this->view->address = $address;
        $this->view->main_product = $main_product;
        $this->view->_user_type = M\Shop::$_business_type;
        $this->view->_business_type = M\Users::$_user_type1;
        $this->view->_shop_state = M\Shop::$_shop_state;
        $this->view->_shop_type = M\Shop::$_shop_type;
        $this->view->link = !empty($data->link) ? $data->link : $link;
        $this->view->data = $data;
    }
    /**
     * 修改店铺信息
     */
    
    public function editAction($id = 0) 
    {
        //保留编辑前条件
        $this->session->SHOP_REFERER = $_SERVER['HTTP_REFERER'];
        
        if (!$id) 
        {
            $this->response->redirect("shop/index")->sendHeaders();
        }
        $shop = M\Shop::getShopInfo($id);
        
        if (!$shop) 
        {
            $this->flash->error("店铺信息不存在");
            die;
        }
        $shop_type = array_flip(shop::$_shop_type);
        $shop_type = isset($shop_type[$shop->shop_type]) ? $shop_type[$shop->shop_type] : 7;
        $shopgoods = ShopCoods::find("shop_id=$shop->shop_id")->toarray();
        $sid = md5(session_id());
        TmpFile::clearOld($sid);
        $this->view->usertype = Users::$_user_type1;
        $this->view->business_type = shop::$_business_type;
        $this->view->shoptype = shop::$_shop_type;
        $this->view->sid = $sid;
        $this->view->shop = $shop;
        $this->view->shopgoods = $shopgoods;
        $this->view->shop_type = $shop_type;
        $this->view->area = lAreas::ldData($shop->village_id);
        $this->view->title = '编辑店铺';
        $this->view->referer = $_SERVER['HTTP_REFERER'];
    }
    /**
     * 保存修改
     */
    
    public function saveAction() 
    {
        
        if (!$this->request->isPost()) 
        {
            $this->flash->error('信息不能为空');
            $this->showMessage($this->session->SHOP_REFERER);
        }
        $referer = $this->request->getPost('referer');
        $cur_time = time();
        $sid = $this->request->getPost('sid', 'string', 0);
        $shopid = $this->request->getPost('shopid', 'int', 0);
        $shop_desc = $this->request->getPost('shop_desc');
        $qita = $this->request->getPost('qita', 'string', '');
        $shop_type = $this->request->getPost('shoptype', 'string', '');
        $main_product[] = $this->request->getPost("main_product1", 'string', '');
        $main_product[] = $this->request->getPost("main_product2", 'string', '');
        $main_product[] = $this->request->getPost("main_product3", 'string', '');
        $main_product[] = $this->request->getPost("main_product4", 'string', '');
        $main_product[] = $this->request->getPost("main_product5", 'string', '');
        //编辑店铺基本信息
        $shop = Shop::findFirst("shop_id={$shopid} ");
        $users = Users::findFirstByid($shop->shop_id);
        
        if (!$shop) 
        {
            die("店铺信息不存在");
        }
        $shop->shop_name = $this->request->getPost("shop_name", 'string', '');
        $shop->user_id = $shop->user_id;
        $shop->business_type = $this->request->getPost("business_type", 'int', '');
        $shop->user_type = $this->request->getPost("user_type", 'int', '');
        
        if ($shop_type == 7) 
        {
            $shop->shop_type = $qita;
        }
        else
        {
            $shop->shop_type = shop::$_shop_type[$shop_type];
        }
        $shop->contact_man = $this->request->getPost("contact_man", 'string', '');
        $shop->contact_phone = $this->request->getPost("contact_phone", 'string', '');
        $shop->province_id = $this->request->getPost("province", 'int', 0);
        $shop->city_id = $this->request->getPost("city", 'int', 0);
        $shop->county_id = $this->request->getPost("county", 'int', 0);
        $shop->town_id = $this->request->getPost("zhen", 'int', 0);
        $shop->village_id = $this->request->getPost("district", 'int', 0);
        $shop->add_time = $shop->last_update_time = $cur_time;
        $shop->shop_status = $shop->shop_status;
        
        if (!$shop->save()) 
        {
            
            foreach ($sell->getMessages() as $message) 
            {
                $this->flash->error('');
            }
            $this->showMessage($referer);
        }
        //编辑店铺认证信息
        $shopcontent = ShopCredit::findFirst("shop_id={$shopid}");
        $shopcontent->shop_id = $shop->shop_id;
        $shopcontent->identity_no = $this->request->getPost("identity_no", 'string', '');
        $shopcontent->bank_name = $this->request->getPost("bank_name", 'string', '');
        $shopcontent->account_name = $this->request->getPost("account_name", 'string', '');
        $shopcontent->card_no = $this->request->getPost("card_no", 'string', '');
        $shopcontent->shop_desc = $shop_desc;
        $shopcontent->last_update_time = $cur_time;
        //如果有图片上传 直接覆盖
        $tmpFile = TmpFile::find("sid='{$sid}' and type in (6,7,8,9,10,11) ");
        
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
            }
        }
        
        if (!$shopcontent->save()) 
        {
            
            foreach ($sell->getMessages() as $message) 
            {
                $this->flash->error('店铺认证信息修改失败');
            }
            $this->showMessage($referer);
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
                
                if ($value != '') 
                {
                    //店铺主营产品处理
                    $shopgoods = new ShopCoods();
                    $shopgoods->shop_id = $shop->shop_id;
                    $shopgoods->goods_name = $value;
                    $shopgoods->shop_name = $shop->shop_name;
                    $shopgoods->user_id = $shop->user_id;
                    $shopgoods->user_name = isset($users->user_name) ? $users->user_name : '';
                    $shopgoods->goods_status = 0;
                    $shopgoods->last_update_time = $cur_time;
                    $shopgoods->save();
                }
            }
        }
        $this->showMessage($referer);
        // $this->response->redirect("shop/index")->sendHeaders();
        
    }
    /**
     * 检测店铺名称是否存在
     */
    
    public function checkeditAction() 
    {
        $shop_name = $this->request->getPost('shop_name', 'string', '');
        $shop_id = $this->request->getPost('shopid', 'int', 0);
        $shopcount = shop::count("shop_name ='{$shop_name}' AND shop_id != '{$shop_id}' ");
        
        if ($shopcount > 0) 
        {
            $msg['error'] = '店铺名称已经存在!';
        }
        else
        {
            $msg['ok'] = '';
        }
        die(json_encode($msg));
    }
    /**
     * 店铺审核通过
     * @return [type] [description]
     */
    
    public function shopauditAction() 
    {
        
        if (!$this->request->getPost()) $this->response->redirect("shop/index")->sendHeaders();
        $flag = false;
        $url_dns = $this->request->getPost('url_dns', 'string', '');
        $id = $this->request->getPost('id', 'int', 0);
        $this->db->begin();
        try
        {
            
            if (!$url_dns) throw new \Exception('dsnError');
            $shop = new M\Shop();
            //检测店铺是否可以审核
            $shop->checkShopState($id, M\Shop::UNAUDIT, $this->db);
            //修改状态 以及域名管理
            $data = M\Shop::findFirstByshop_id($id);
            $data->shop_status = M\Shop::AUDITVIA;
            $data->shop_link = $url_dns;
            
            if (!$data->save()) throw new \Exception(M\Shop::SHOP_ERROR);
            $flag = $this->db->commit();
        }
        catch(\Exception $e) 
        {
            $flag = false;
            $this->db->rollback();
            // print_R($e->getMessage());
            
        }
        
        if ($flag) 
        {
        }
        Func::adminlog("店铺审核通过：{$data->shop_name}");
        $this->response->redirect("shop/index")->sendHeaders();
    }
    /**
     * 店铺审核不通过
     * @return [type] [description]
     */
    
    public function shopunauditunAction() 
    {
        // exit;
        
        if (!$this->request->getPost());
        $flag = false;
        $reject = $this->request->getPost('reject', 'string', '');
        $id = $this->request->getPost('id', 'int', 0);
        $this->db->begin();
        try
        {
            
            if (!$reject) throw new \Exception('rejectError');
            $shop = new M\Shop();
            //检测店铺是否可以 操作
            $shop->checkShopState($id, M\Shop::UNAUDIT, $this->db);
            //修改状态 以及域名管理
            $data = M\Shop::findFirstByshop_id($id);
            $data->shop_status = M\Shop::NOTPASS;
            $data->shop_link = '';
            
            if (!$data->save()) throw new \Exception(M\Shop::SHOP_ERROR);
            //修改审核操作以及备注
            $shop_check = new M\ShopCheck();
            $shop_check->shop_id = $id;
            $shop_check->last_failure = 1;
            $shop_check->failure_desc = $reject;
            $shop_check->add_time = time();
            
            if (!$shop_check->save()) throw new \Exception("shop_checkError");
            $flag = $this->db->commit();
        }
        catch(\Exception $e) 
        {
            $flag = false;
            $this->db->rollback();
            print_R($e->getMessage());
        }
        
        if ($flag) 
        {
        }
        Func::adminlog("店铺审核未通过：{$data->shop_name}");
        $this->response->redirect("shop/index")->sendHeaders();
    }
    /**
     * 检测域名是否重复
     * @return [type] [description]
     */
    
    public function checkdnsAction() 
    {
        $url_dns = trim($this->request->getPost('url_dns', 'string', ''));
        $id = trim($this->request->getPost('id', 'string', ''));
        $where = '';
        
        if ($id) 
        {
            $where = " shop_id != '{$id}' AND ";
        }
        $data = M\Shop::count(" {$where} shop_link = '{$url_dns}'");
        $msg = array(
            'error' => '该域名已被使用'
        );
        
        if (!$data) 
        {
            $msg = array(
                'ok' => ''
            );
        }
        exit(json_encode($msg));
    }
}
