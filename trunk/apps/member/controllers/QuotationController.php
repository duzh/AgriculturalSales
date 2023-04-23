<?php
namespace Mdg\Member\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Mdg\Models\PurchaseQuotation as Quotation;
use Mdg\Models\Purchase as Purchase;
use Mdg\Models\PurchaseContent as PurchaseContent;
use Lib\Pages as Pages;
use Lib\Func as Func;
use Lib\Utils as Utils;


class QuotationController extends ControllerMember
{

    /**
     * 我的报价列表
     */
    public function indexAction()
    {
        $page = $this->request->get('p', 'int', 1);
        $page = intval($page)>0 ? intval($page) : 1;
        $user_id = $this->getUserID();
        $user_id = intval($user_id) ? intval($user_id) : 11;
        
        $params = array("suserid = '{$user_id}'");

        $params['order'] = ' addtime desc ';

        $quotation = Quotation::find($params);
        $total = Quotation::count($params);
        $total_count = ceil($total / 5);
        $paginator = new Paginator(array(
            "data" => $quotation,
            "limit"=> 5,
            "page" => $page
        ));

        $data = $paginator->getPaginate();

        $pages = new Pages($data);
        $pages = $pages->show(2);

        foreach ($data->items as & $val) {
            $val->countQuo = Quotation::find("purid='{$val->purid}'")->count();
        }
        $this->view->total_count = $total_count;
        $this->view->data = $data;
        $this->view->pages = $pages;
        $this->view->goods_unit = Purchase::$_goods_unit;
        $this->view->p      = $page;
        $this->view->title = '我的报价-用户中心-丰收汇-高价值农产品交易服务商';
        $this->view->keywords = '丰收汇,农业,农产品,农副产品,农产品批发,农产品电商,农产品现货,农产品交易,农产品加工,农产品价格';
        $this->view->descript = '丰收汇是依托中国农业互联网高科技综合服务商云农场的丰富资源和先进技术而建立的中国最专业的网上农产品服务平台，
        为中国农村专业合作社、中小农商企业等提供农产品信息发布、行情资讯、网上商城、金融服务、定向对接、安全模型、订单种植等专业化服务，
        旨在成为全球高价值农产品交易服务商。';
    }

    /**
     * Edits a quotation
     *
     * @param string $id
     */
    public function editAction()
    {
        $quoid = $this->request->get('quoid', 'int', 0);
        $price = $this->request->get('price', 'float', 0.00);
        $rs = array('state'=>false, 'msg'=>'报价修改成功！');

        $user_id = $this->getUserID();
        $user_id = intval($user_id) ? intval($user_id) : 11;

        $quotation = Quotation::findFirstByid($quoid);

        if(!$quotation) {
            $rs['msg'] = '此报价不存在！';
            die(json_encode($rs));
        }

        if($user_id != $quotation->suserid) {
            $rs['msg'] = '无权修改此报价！';
            die(json_encode($rs));
        }

        $quotation->price = $price;
        if(!$quotation->save()) {
            $rs['msg'] = '报价修改失败，请联系管理员！';
            die(json_encode($rs));
        }

        $rs['state'] = true;
        die(json_encode($rs));

    }

    /**
     * Saves a quotation edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "quotation",
                "action" => "index"
            ));
        }

        $id = $this->request->getPost("id");

        $quotation = Quotation::findFirstByid($id);
        if (!$quotation) {
            $this->flash->error("此报价不存在！" );

            return $this->dispatcher->forward(array(
                "controller" => "purchasequotation",
                "action" => "index"
            ));
        }

        $user_id = $this->getUserID();
        $user_id = intval($user_id) ? intval($user_id) : 11;

        if($user_id != $quotation->purchase->uid) {
            $this->flash->error("无权修改此报价！");

            return $this->dispatcher->forward(array(
                "controller" => "quotation",
                "action" => "index"
            ));
        }

        $quotation->price = $this->request->getPost("price", 'float', 0.00);
        $quotation->addtime = time();
        

        if (!$quotation->save()) {

            foreach ($quotation->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "quotation",
                "action" => "edit",
                "params" => array($quotation->id)
            ));
        }

        $this->flash->success("报价修改成功！");

        return $this->dispatcher->forward(array(
            "controller" => "quotation",
            "action" => "index"
        ));

    }

    /**
     * Deletes a quotation
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $quotation = Quotation::findFirstByid($id);
        if (!$quotation) {
            $this->flash->error("此报价不存在！");

            return $this->dispatcher->forward(array(
                "controller" => "quotation",
                "action" => "index"
            ));
        }

        if (!$quotation->delete()) {

            foreach ($quotation->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "quotation",
                "action" => "search"
            ));
        }

        $this->flash->success("报价删除成功！");

        return $this->dispatcher->forward(array(
            "controller" => "quotation",
            "action" => "index"
        ));
    }

}
