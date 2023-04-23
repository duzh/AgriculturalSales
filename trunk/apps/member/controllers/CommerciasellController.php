<?php
/**
 * 个人 供应
 */
namespace Mdg\Member\Controllers;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Mdg\Models\TmpFile as TmpFile;
use Mdg\Models\Sell as Sell;
use Mdg\Models\Users as Users;
use Mdg\Models\UsersExt as Usersext;
use Mdg\Models\AreasFull as mAreas;
use Mdg\Models\Purchase as Purchase;
use Mdg\Models\SellContent as Content;
use Mdg\Models\SellImages as SellImages;
use Mdg\Models\Category as Category;
use Mdg\Models\UserArea as UserArea;
use Mdg\Models\UserInfo as UserInfo;
use Lib\Func as Func;
use Lib\Path as Path;
use Lib\File as File;
use Lib\Arrays as Arrays;
use Lib\Pages as Pages;
use Lib\Category as lCategory;
use Lib\Areas as lAreas;

class  CommerciasellController extends ControllerMember
{
    /**
     * 我的供应
     */
    
    public function indexAction() 
    {
        $page = $this->request->get('p', 'int', 1);
        $page = intval($page)>0 ? intval($page) : 1;
        $page_size = $this->request->get('page_size', 'int', 10);
        $state = $this->request->get('state', 'int', 0);
        $credit_type = $this->request->get('credit_type','int',0);
        $user_id=$this->session->user['id'];
        $userid = $this->request->get('userid','int',0);
        $mobile = $this->request->get('mobile','string','');
        $suppliers_sn = $this->request->get('suppliers_sn','string','');
        $stime = $this->request->get('stime','string','');
        $etime = $this->request->get('etime','string','');
        $sell_price_start = $this->request->get('sell_price_start','int',0);
        $sell_price_end = $this->request->get('sell_price_end','int',0);
        $province = $this->request->get('province','string','');
        $city = $this->request->get('city','string','');
        $district = $this->request->get('district','string','');
        $quantity_start = $this->request->get('quantity_start','string','');
        $quantity_end = $this->request->get('quantity_end','string','');
        $user_id = $this->getUserID();
        $se_mobile=$this->getUserName();
        // $params=UserInfo::getdistrict_id($user_id);
        $params=" ui.se_mobile = '{$se_mobile}' and ui.credit_type!=2 and ui.status=1 and ui.user_id!=$user_id and ui.user_id!='' ";
        
        $curAreas='';
        
        if($userid){
            $params .= " and s.uid='{$userid}' ";
        }
        if($credit_type){
            $params .= " info.credit_type={$credit_type} "; 
        }
        if($mobile){
            $params .= " and s.mobile='{$mobile}' ";
        }
        if($suppliers_sn){
             $params .= " and s.sell_sn='{$suppliers_sn}' ";
        } 
        if($stime&&$etime){
            $params.= " and createtime >= '" . strtotime($stime . "00:00:00") . "'";
            $params.= " and createtime <= '" . strtotime($etime . "23:59:59") . "'";
        }
        
        if($sell_price_start&&$sell_price_end){
             $params .= " and min_price BETWEEN {$sell_price_start} and  {$sell_price_end} ";
        }   
        if($quantity_start&&$quantity_end){
             $params .= " and quantity BETWEEN {$quantity_start} and  {$quantity_end} ";
        }
       
        if ($state) 
        {
            
            switch ($state) 
            {
            case '1':
                $params.= " and state = 0 and is_del=0 ";
                break;
            case '2':
                $params.= " and state = 1 and is_del=0 ";
                break;
            case '3':
                $params.= " and is_del =1 ";
                break;
            case '4':
                $params.= " and state = 2 and is_del=0 ";
                break;
            default:
                break;
            }
        }
        if($province){
             $curAreas=lAreas::ldData($province);
             $province=lAreas::ldareaname($province);
             $params.= " and s.full_address like '{$province}%' ";
        }
        if($city){
             $curAreas=lAreas::ldData($city);
             $city=lAreas::ldareaname($city);
             $params.= " and   s.full_address like '{$city}%' ";
             
        }
        if($district){
             $curAreas=lAreas::ldData($district);
             $district=lAreas::ldareaname($district);
             $params.= " and s.full_address like '{$district}%'";
        }

        $db=$GLOBALS['di']['db'];
        $sql=" select count(DISTINCT s.sell_sn) as count from  user_info as ui left join users as  u on ui.user_id=u.id  join sell as s on u.id=s.uid where {$params} ";
        // print_r($sql);die;
        $total = $db->fetchOne($sql,2)["count"];
        
        $offst = intval(($page - 1) * $page_size);
        $sql=" select u.username,u.id as user_id ,s.* from  user_info as ui left join users as  u on ui.user_id=u.id   join sell as s on u.id=s.uid where  {$params} group by s.sell_sn  limit {$offst} , {$page_size}";
        // print_r($sql);die;
        $data = $db->fetchAll($sql,2);
        
        foreach ($data as $key => $value) {
            $data[$key]["type"]=UserInfo::getuserinfo($value["user_id"]);
        }
       
        $pages['total_pages'] = ceil($total / $page_size);
        $pages['current'] = $page;
        $pages['total'] = $total;
      
        $pages = new Pages($pages);
        $pages = $pages->show(2);
        $this->view->total_count = ceil($total / $page_size);
        $this->view->data = $data;
        $this->view->pages = $pages;
        $this->view->goods_unit = Purchase::$_goods_unit;
        $this->view->_state = Sell::$type1;
        $this->view->time_type = Sell::$type;
        $this->view->sellstate = $state;
        $this->view->state = Sell::$sellstate;
        $this->view->curAreas=$curAreas;
        $this->view->stime=$stime;
        $this->view->p=$page;
        $this->view->etime=$etime;
        $this->view->_credit_type=UserInfo::$user_type;
        $this->view->title = '用户中心-商户供应列表';
        $this->view->keywords = '丰收汇,农业,农产品,农副产品,农产品批发,农产品电商,农产品现货,农产品交易,农产品加工,农产品价格';
        $this->view->descript = '丰收汇是依托中国农业互联网高科技综合服务商云农场的丰富资源和先进技术而建立的中国最专业的网上农产品服务平台，
        为中国农村专业合作社、中小农商企业等提供农产品信息发布、行情资讯、网上商城、金融服务、定向对接、安全模型、订单种植等专业化服务，
        旨在成为全球高价值农产品交易服务商。';
    }
}
