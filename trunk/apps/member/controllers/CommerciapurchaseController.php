<?php
namespace Mdg\Member\Controllers;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Mdg\Models\AreasFull as mAreas;
use Mdg\Models\Orders as Orders;
use Mdg\Models\Purchase as Purchase;
use Mdg\Models\PurchaseQuotation as Quotation;
use Mdg\Models\Users as Users;
use Mdg\Models\UserArea as UserArea;
use Mdg\Models\UserInfo as UserInfo;
use Lib\Func as Func;
use Lib\Areas as lAreas;
use Lib\Pages as Pages;
use Mdg\Models as M;
use Mdg\Models\MessageUsers as MessageUsers;
use Mdg\Models\Message as Message;
class CommerciapurchaseController extends ControllerMember
{
    public function indexAction() {

        $page = $this->request->get('p', 'int', 1);
        $page = intval($page)>0 ? intval($page) : 1;
        $page_size=$this->request->get('pagesize', 'int', 10);
        $user_id = $this->getUserID();
        $state = $this->request->get('state', 'string', 'all');

        $userid = $this->request->get('userid','int',0);
        $mobile = $this->request->get('mobile','string','');
        $credit_type = $this->request->get('credit_type','int',0);
        $purchase_sn = $this->request->get('purchase_sn','string','');
        $etime = $this->request->get('etime','string','');
        $min_quantity = $this->request->get('min_quantity','int',0);
        $max_quantity = $this->request->get('max_quantity','int',0);

        $province = $this->request->get('province','string','');
        $city = $this->request->get('city','string','');
        $district = $this->request->get('district','string','');

        $fabu_stime = $this->request->get('fabu_stime','string','');
        $fabu_etime = $this->request->get('fabu_etime','string','');
        $baojia_stime = $this->request->get('baojia_stime','string','');
        $baojia_etime = $this->request->get('baojia_etime','string','');

        $curAreas='';
        $areaparams='';
        $se_mobile=$this->getUserName();
        //$params=UserInfo::getdistrict_id($user_id);
        $params=" ui.se_mobile = '{$se_mobile}' and ui.credit_type!=2 and ui.status=1 and ui.user_id!=$user_id and ui.user_id!='' ";
        $data=array();
     
        if($province){
             $curAreas=lAreas::ldData($province);
             $province=lAreas::ldareasname($province);
             $areaparams = "  and p.address like '%{$province}%' ";
        }
        if($city){
             $curAreas=lAreas::ldData($city);
             $city=lAreas::ldareasname($city);
             $areaparams = " and   p.address like '{$city}%' ";
             
        }
        if($district){
             $curAreas=lAreas::ldData($district);
             $district=lAreas::ldareasname($district);
             $areaparams = " and  p.address like '{$district}%'";
        }
        if($userid){
            $params .= " and uid='{$userid}' ";
        }
        if($credit_type){
            $params .= " and member_type&{$credit_type}={$credit_type}";
        }
        if($mobile){
            $params .= " and mobile='{$mobile}' ";
        }
        if($purchase_sn){
             $params .= " and pur_sn='{$purchase_sn}' ";
        } 
        if($fabu_stime&&$fabu_etime){

            $params.= " and p.createtime >= '" . strtotime($fabu_stime . "00:00:00") . "'";
            $params.= " and p.createtime <= '" . strtotime($fabu_etime . "23:59:59") . "'";
        }
        if($baojia_stime&&$baojia_etime){

            $params.= " and p.endtime >= '" . strtotime($baojia_stime . "00:00:00") . "'";
            $params.= " and p.endtime <= '" . strtotime($baojia_etime . "23:59:59") . "'";
        }
        if($min_quantity&&$max_quantity){
             $params .= " and p.quantity BETWEEN {$min_quantity} and  {$max_quantity} ";
        }  
        if($state != 'all') {
            $params.= $state == '3' ? " AND is_del = 1 ": " AND state = '{$state}' and is_del=0 ";
        }

        $params.=$areaparams;
       
        $db=$GLOBALS['di']['db'];
        $sql=" select count(distinct ui.user_id) as count from   user_info as ui  join users as  u on ui.user_id=u.id left  join purchase as p on u.id=p.uid where {$params} ";
      
        $total = $db->fetchOne($sql,2)["count"];
        
        $offst = intval(($page - 1) * $page_size);
        $sql=" select distinct ui.user_id,u.username as user_name ,u.id as user_id ,p.* from  user_info as ui  join users as  u on ui.user_id=u.id   join purchase as p on u.id=p.uid where  {$params}  limit {$offst} , {$page_size}";
        $data = $db->fetchAll($sql,2);
        

        $pages['total_pages'] = ceil($total / $page_size);
        $pages['current'] = $page;
        $pages['total'] = $total;

        $pages = new Pages($pages);
        $pages = $pages->show(2);

        foreach ($data as $key=>$value) {
            $data[$key]['countquo'] = Quotation::countQuo($value['id']);
            $data[$key]["type"]=UserInfo::getuserinfo($value["user_id"]);
        }
    
        $this->view->curAreas = $curAreas;
        $this->view->_credit_type=Users::$_credit_type;
        $this->view->data = $data;
        $this->view->total_count = ceil($total / $page_size);
        $this->view->pages = $pages;
        $this->view->goods_unit = Purchase::$_goods_unit;
        $this->view->_state = Purchase::$_state;
        $this->view->purstate=$state;
        $this->view->p=$page;
        $this->view->title = '用户中心-商户采购列表';
        $this->view->keywords = '丰收汇,农业,农产品,农副产品,农产品批发,农产品电商,农产品现货,农产品交易,农产品加工,农产品价格';
        $this->view->descript = '丰收汇是依托中国农业互联网高科技综合服务商云农场的丰富资源和先进技术而建立的中国最专业的网上农产品服务平台，
        为中国农村专业合作社、中小农商企业等提供农产品信息发布、行情资讯、网上商城、金融服务、定向对接、安全模型、订单种植等专业化服务，
        旨在成为全球高价值农产品交易服务商。';

    }
}