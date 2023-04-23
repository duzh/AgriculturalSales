<?php
/**
 * 商户订单管理
 */
namespace Mdg\Member\Controllers;
use Phalcon\Mvc\Model\Criteria;
use Mdg\Models as M;
use Lib\Func as Func;
use Lib as L;
use Lib\Pages as Pages;
class CommerciaorderController extends ControllerMember
{

    /**
     * 我买入的订单
     */
    public function indexAction()
    {    
        $page_size = 10;
        $page=$this->request->get('p','int',1);
        $total=$this->request->get('total','int',0);
        $user_id=$this->getUserID();
        $se_mobile=$this->getUserName();
        $p = intval($page)>0 ? intval($page) : 1;
        if($p>$total&&$total!=0){
            $p=$total;
        }
        
        $where=" ui.se_mobile = '{$se_mobile}' and ui.credit_type!=2 and ui.status=1 and ui.user_id!=$user_id and ui.user_id!='' ";
        
        $puserid     =$this->request->get('puserid','int',0);
        $purphone    =$this->request->get('purphone','string','');
        $credit_type =$this->request->get('credit_type','int',0);
        $order_sn    =$this->request->get('order_sn','string','');
        $state       =$this->request->get('state','int',0);
        $stime       =$this->request->get('stime','string','');
        $etime       =$this->request->get('etime','string','');
        
        if($puserid){
            $where.= " and o.puserid = {$puserid}";
        }
        if($purphone){
            $where.= " and o.purphone = '{$purphone}'";
        }
        if($order_sn){
            $where.= " and o.order_sn = '{$order_sn}'";
        }
        if($state){
            $where.= " and o.state = {$state}";
        }
        if ($stime&&$etime) 
        {
            $where.= " and o.addtime >= '".strtotime($stime."00:00:00") . "'";
            $where.= " and o.addtime <= '".strtotime($etime."23:59:59") . "'";
        }  
        $sql = " select count(*) as total from  user_info as ui  join orders  as o on ui.user_id=o.puserid where {$where} ";

        $total = $this->db->fetchOne($sql,2)['total'];
        
        $offst = intval(($p - 1) * $page_size);

        $sqlall=" select o.* from  user_info as ui  join orders  as o on ui.user_id=o.puserid where {$where} ORDER BY o.addtime DESC limit {$offst} , {$page_size} ";
        
        $data = $this->db->fetchAll($sqlall,2);
         
       
        foreach ($data as $key => $value) 
        {   
            $data[$key]["ident"]=M\UserInfo::getuserinfo($value['puserid']);
        }
        $pages['total_pages'] = ceil($total / $page_size);
        $pages['current'] = $p;
        $pages['total'] = $total;
        
        $page = new Pages($pages);

        $order['items'] = $data;
        $order['start'] = $offst;
        $order['pages'] = $page->show(2);
        
        $this->view->orders_state = M\Orders::$_orders_buy_state;
        $this->view->data = $order;
        $this->view->stime = $stime;
        $this->view->etime = $etime;
        $this->view->goods_unit = M\Purchase::$_goods_unit;
        $this->view->http = $_SERVER['SERVER_NAME'];
        $this->view->state = $state;
        $this->view->p=$p;
        $this->view->total_count = ceil($total / $page_size);
        $this->view->title = '我发展的商户订单-我买入的列表';
        $this->view->keywords = '丰收汇,农业,农产品,农副产品,农产品批发,农产品电商,农产品现货,农产品交易,农产品加工,农产品价格';
        $this->view->descript = '丰收汇是依托中国农业互联网高科技综合服务商云农场的丰富资源和先进技术而建立的中国最专业的网上农产品服务平台，
        为中国农村专业合作社、中小农商企业等提供农产品信息发布、行情资讯、网上商城、金融服务、定向对接、安全模型、订单种植等专业化服务，
        旨在成为全球高价值农产品交易服务商。';
    }

    public function sellindexAction(){

        $page_size = 10;
        $p=$this->request->get('p','int',1);
        $total=$this->request->get('total','int',0);
        $user_id=$this->getUserID();
        $se_mobile=$this->getUserName();
        $p = intval($p)>0 ? intval($p) : 1;
        if($p>$total&&$total!=0){
            $p=$total;
        }

        $where=" ui.se_mobile = '{$se_mobile}' and ui.credit_type!=2 and ui.status=1 and ui.user_id!=$user_id and ui.user_id!='' ";
        
        $puserid     =$this->request->get('puserid','int',0);
        $purphone    =$this->request->get('purphone','string','');
        $credit_type =$this->request->get('credit_type','int',0);
        $order_sn    =$this->request->get('order_sn','string','');
        $state       =$this->request->get('state','int',0);
        $stime       =$this->request->get('stime','string','');
        $etime       =$this->request->get('etime','string','');
        
        if($puserid){
            $where.= " and o.suserid = {$puserid}";
        }
        if($purphone){
            $where.= " and o.sphone = '{$purphone}'";
        }
        if($order_sn){
            $where.= " and o.order_sn = '{$order_sn}'";
        }
        if($state){
            $where.= " and o.state = {$state}";
        }
        if ($stime&&$etime) 
        {
            $where.= " and o.addtime >= '".strtotime($stime."00:00:00") . "'";
            $where.= " and o.addtime <= '".strtotime($etime."23:59:59") . "'";
        }  
        
        $sql = " select count( ui.user_id) as total from  user_info as ui  join orders  as o on ui.user_id=o.suserid where {$where} ";
        $total = $this->db->fetchOne($sql,2)['total'];
       
        $offst = intval(($p - 1) * $page_size);
        $sqlall=" select  o.* from  user_info as ui  join orders  as o on ui.user_id=o.suserid where {$where} ORDER BY o.addtime DESC limit {$offst} , {$page_size} ";
        
        $data = $this->db->fetchAll($sqlall,2);
        
        foreach ($data as $key => $value) 
        {   
            $data[$key]["ident"]=M\UserInfo::getuserinfo($value['suserid']);
        }
        if($data[0]["id"]==''){
            $data=array();
        }
      
        $page['total_pages'] = ceil($total / $page_size);
        $page['current'] = $p;
        $page['total'] = $total;
        $page = new Pages($page);
        $order['items'] = $data;
        $order['start'] = $offst;
        $order['pages'] = $page->show(2);
        
        $this->view->orders_state = M\Orders::$_orders_buy_state;
        $this->view->data = $order;
        $this->view->stime = $stime;
        $this->view->etime = $etime;
        $this->view->goods_unit = M\Purchase::$_goods_unit;
        $this->view->http = $_SERVER['SERVER_NAME'];
        $this->view->state = $state;
        $this->view->p = $p;
        $this->view->total_count = ceil($total / $page_size);
        $this->view->title = '我发展的商户订单-我卖出的列表';
        $this->view->keywords = '丰收汇,农业,农产品,农副产品,农产品批发,农产品电商,农产品现货,农产品交易,农产品加工,农产品价格';
        $this->view->descript = '丰收汇是依托中国农业互联网高科技综合服务商云农场的丰富资源和先进技术而建立的中国最专业的网上农产品服务平台，
        为中国农村专业合作社、中小农商企业等提供农产品信息发布、行情资讯、网上商城、金融服务、定向对接、安全模型、订单种植等专业化服务，
        旨在成为全球高价值农产品交易服务商。';
    }

}   
