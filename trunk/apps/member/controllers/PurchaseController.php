<?php
namespace Mdg\Member\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Mdg\Models\AreasFull as mAreas;
use Mdg\Models\Orders as Orders;
use Mdg\Models\Purchase as Purchase;
use Mdg\Models\PurchaseQuotation as Quotation;
use Lib\Func as Func;
use Lib\Pages as Pages;
use Mdg\Models\MessageUsers as MessageUsers;
use Mdg\Models\Message as Message;
use Mdg\Models\Users as Users;
use Mdg\Models\UsersExt as Usersext;
use Mdg\Models\UserYnpInfo as UserYnpInfo;
use Lib as L;

/**
 * 订单采购确认
 */
class PurchaseController extends ControllerMember
{
public function indexAction() {
        $page = $this->request->get('p', 'int', 1);
        $page = intval($page)>0 ? intval($page) : 1;
        $user_id = $this->getUserID();
        $state = $this->request->get('state', 'int', '');

        $psize = $this->request->get('psize','int',5);
        $params = array("uid = '{$user_id}' ");

        $state = L\Validator::replace_specialChar($this->request->get('state', 'string', 'all'));
        if($state != 'all') {
            $params[0] .= $state == '3' ? " AND is_del = 1 ": " AND state = '{$state}' and is_del=0 ";
        }
        $where = implode(' and ', $params);
        $total = Purchase::count($where);
        $where .=' order by updatetime desc ';
        $offst = ($page - 1 ) * $psize ;
        $data = Purchase::find($where." limit $offst,$psize ");
      
        $pages['total_pages'] = ceil($total / $psize);
        $pages['current'] = $page;
        $pages['total'] = $total;
        $pages = new L\Pages($pages);
        $pages = $pages->show(2);
        
        $this->view->total_count = ceil($total / $psize);
        $this->view->data = $data;
        $this->view->pages = $pages;
        $this->view->p = $page;
        $this->view->goods_unit = Purchase::$_goods_unit;
        $this->view->_state = Purchase::$_state;
        $this->view->title = '我的采购-用户中心-丰收汇-高价值农产品交易服务商';
        $this->view->keywords = '丰收汇,农业,农产品,农副产品,农产品批发,农产品电商,农产品现货,农产品交易,农产品加工,农产品价格';
        $this->view->descript = '丰收汇是依托中国农业互联网高科技综合服务商云农场的丰富资源和先进技术而建立的中国最专业的网上农产品服务平台，
        为中国农村专业合作社、中小农商企业等提供农产品信息发布、行情资讯、网上商城、金融服务、定向对接、安全模型、订单种植等专业化服务，
        旨在成为全球高价值农产品交易服务商。';

    }

    
    /**
     * 我的采购报价列表
     */
    public function quolistAction()
    {
        $purid = $this->request->get('purid', 'int', 0);
        $total = $this->request->get('total','int',0);
        $purchase = Purchase::findFirstByid($purid);
        $page = $this->request->get('p', 'int', 1);
        if($page<=0){
        $page=1;
        }
        if($page>$total){
            $page=$total;
        }
        $user_id = $this->getUserID();
        $user_id = intval($user_id) ? intval($user_id) : 11;
        
        if (!$purchase || $purchase->uid != $user_id) {
            $this->flash->error("此求购信息不存在");

            return $this->dispatcher->forward(array(
                "controller" => "purchase",
                "action" => "index"
            ));
        }

        $params = array("purid='{$purid}'");
        $params['order'] = ' addtime desc ';
        $total  =Quotation::count($params);

        $quotation = Quotation::find($params);
        
        $paginator = new Paginator(array(
            "data" => $quotation,
            "limit"=> 5,
            "page" => $page
        ));

        $data = $paginator->getPaginate();

        $pages = new Pages($data);
        $pages = $pages->show(2);    
        foreach ($data->items as & $value) {
            $message=Message::find("offer_id = " .$value->purid)->toArray();
            foreach ($message as $key => $v) {
                $messageUsers=MessageUsers::findFirstBymsg_id($v['msg_id']);
                if($messageUsers && $messageUsers->is_new ==0){
                    $messageUsers->is_new = 1;
                    $messageUsers->last_update_time = time();
                    $messageUsers->save();
                }
            }
            $tree = mAreas::getFamily($value->sareas);
            $value->areas_name = Func::getCols($tree, 'name', ',');
            $areas = $value->ld_areas = "'".Func::getCols($tree, 'name', "','")."'";
        }
        $total_count=ceil($total / 5);
        $this->view->total_count = $total_count;
        $this->view->purid      = $purid;
	    $this->view->count=Quotation::count($params);
        $this->view->areas =$areas;
        $this->view->data = $data;
        $this->view->pages = $pages;
        $this->view->p          = $page; 
        $this->view->purchase = $purchase;
        $this->view->goods_unit = Purchase::$_goods_unit;
        
        $this->view->title = '报价信息-用户中心-丰收汇-高价值农产品交易服务商';
        $this->view->keywords = '丰收汇,农业,农产品,农副产品,农产品批发,农产品电商,农产品现货,农产品交易,农产品加工,农产品价格';
        $this->view->descript = '丰收汇是依托中国农业互联网高科技综合服务商云农场的丰富资源和先进技术而建立的中国最专业的网上农产品服务平台，
        为中国农村专业合作社、中小农商企业等提供农产品信息发布、行情资讯、网上商城、金融服务、定向对接、安全模型、订单种植等专业化服务，
        旨在成为全球高价值农产品交易服务商。';

    }

    public function orderAction() {
        $quoid = $this->request->get('quoid', 'int', 0);
        $areas = $this->request->get('areas', 'int', 0);
        $quantity = $this->request->get('quantity', 'float', 0.00);
        $sname = L\Validator::replace_specialChar($this->request->get('sname', 'string', ''));
        $sphone = L\Validator::replace_specialChar($this->request->get('sphone', 'string', ''));
        $address = L\Validator::replace_specialChar($this->request->get('address', 'string', ''));
        $rs = array('state'=>false, 'msg'=>'采购成功！');

        if(!$quoid || !$areas || !$quantity || !$sname || !$sphone || !$address) {
            $rs['msg'] = '信息不完整！';
            die(json_encode($rs));
        }

        $quotation = Quotation::findFirstByid($quoid);

        if(!$quotation) {
            $rs['msg'] = '此报价不存在！';
            die(json_encode($rs));
        }

        $user_id = $this->getUserID();
        $user_id = intval($user_id) ? intval($user_id) : 11;

        $order = new Orders();
        $order->purid = $quotation->purid;
        $order->puserid = $user_id;
        $order->purname = $quotation->purchase->username;
        $order->purphone = $quotation->purchase->mobile;
        $order->suserid = $quotation->suserid;
        $order->sname = $sname;
        $order->sphone = $sphone;
        $order->areas = $areas;
        $order->areas_name = Func::getCols(mAreas::getFamily($areas), 'name', ',');
        $order->address = Func::getCols(mAreas::getFamily($areas), 'name', ',');
        $order->goods_name = $quotation->purchase->title;
        $order->price = $quotation->price;
        $order->quantity = $quantity;
        $order->goods_unit = $quotation->purchase->goods_unit;
        $order->total =Func::format_money($order->price * $order->quantity);
        $order->addtime = $order->updatetime = time();
        $order->state = 2;

        if(!$order->save()) {
            $rs['msg'] = '采购失败，请联系管理员！';
            die(json_encode($rs));
        }

        $order->order_sn = sprintf('mdg%09u', $order->id);
        $order->save();

        $rs['state'] = true;
        die(json_encode($rs));
    }

    public function removeAction($id) {
        $purchase = Purchase::findFirstByid($id);
        $user_id = $this->getUserID();

        if (!$purchase || $purchase->uid != $user_id) {
            $this->flash->error("此求购信息不存在");

            return $this->dispatcher->forward(array(
                "controller" => "purchase",
                "action" => "index"
            ));
        }

        $purchase->is_del = 1;
        $purchase->save();

        return $this->dispatcher->forward(array(
            "controller" => "purchase",
            "action" => "index"
        ));

    }


    public function newAction(){
        
        $user_id = $this->getUserID();
        #检测是否绑定了云农宝 start
        $UserYnpInfo = UserYnpInfo::findFirst("user_id={$user_id}");
        if (!$UserYnpInfo)
        {
            die("<script>alert('您好，发布采购信息，必须先绑定云农宝帐号！');location.href='/member/ynbbinding/'</script>");
        }
        #end
        $userext = UsersExt::findFirstByuid($user_id);
        $curAreas = '';
        $user=Users::findFirstByid($user_id);
        $address=mAreas::checkarea($user->areas);
        if (!isset($userext) || $userext->name == '' || $userext->areas_name == '') 
        {
           die("<script>alert('请先完善信息');location.href='/member/perfect/index/'</script>");
        }
        $this->view->goods_unit = Purchase::$_goods_unit;
        $this->view->title = '用户中心-发布采购-丰收汇-高价值农产品交易服务商';
    }

}