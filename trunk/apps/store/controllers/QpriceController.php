<?php
/**
 * 采购商店铺查看
 */

namespace Mdg\Store\Controllers;

use Mdg\Models\Purchase as Purchase;

use Mdg\Models\PurchaseContent as Content;

use Mdg\Models\PurchaseQuotation as Quotation;

use Mdg\Models\Users as Users;

use Mdg\Models\Sell as Sell;

use Mdg\Models\Orders as Orders;

use Mdg\Models\OrdersLog as OrdersLog;

use Mdg\Models\Category as Category;

use Mdg\Models\Areas as Areas;

use Mdg\Models as M;

use Lib\Areas as lAreas;

use Lib\Func as Func;

use Mdg\Models\OrdersDelivery as OrdersDelivery;


class QpriceController extends ControllerBase{


public function newquoAction($id=0) {


        $purchase = Purchase::findFirstByid($id);

        $user= $this->session->user;
    //print_r($this->session->mdgshop);die;

        if(!$purchase) {

            $this->flash->error("此采购信息不存在！");

            return $this->dispatcher->forward(array(

                "controller" => "msg",

                "action" => "showmsg",

            ));

        }

        $user_id = $this->getUserID();



        if($user_id == $purchase->uid) {

            $this->flash->error("不能对自己的采购信息报价！");

            return $this->dispatcher->forward(array(

                "controller" => "msg",

                "action" => "showmsg",

            ));           

        }

        $quo = Quotation::findFirst("purid='{$id}' and suserid='{$user_id}'");

        if($quo) {

            $this->flash->error("不能重复报价！");

            return $this->dispatcher->forward(array(

                "controller" => "msg",

                "action" => "showmsg",

            )); 

        }

        // 添加点击量

        Purchase::clickAdd($id);

        $users = Users::findFirstByid($user_id);

        $this->view->users = $users;

        $this->view->curAreas = lAreas::ldData($users->areas);

        $this->view->purchase = $purchase;

        $this->view->quototal = Quotation::countQuo($id);

        $this->view->goods_unit = Purchase::$_goods_unit;

    }



    public function savequoAction() {



        $purid = $this->request->getPost('purid', 'int', 0);

        $price = $this->request->getPost('price', 'float', 0.00);

        $spec = $this->request->getPost('spec', 'string', '');

        $sellname = $this->request->getPost('sellname', 'string', '');

        $sareas = $this->request->getPost('sareas', 'int', 0);

        $saddress = $this->request->getPost('saddress', 'string', '');

        $sphone = $this->request->getPost('sphone', 'string', '');



        if(!$price || !$spec || !$sellname || !$sareas || !$saddress || !$sphone) {

            $this->flash->error("信息不完整！");

            return $this->dispatcher->forward(array(

                "controller" => "msg",

                "action" => "showmsg",

            ));

        }



        $user_id = $this->getUserID();

        $purchase = Purchase::findFirstByid($purid);



        if(!$purchase) {

            $this->flash->error("此采购信息已失效！");

            return $this->dispatcher->forward(array(

                "controller" => "msg",

                "action" => "showmsg",

            ));

        }



        if($user_id == $purchase->uid) {

            $this->flash->error("不能对自己的采购信息报价！");

            return $this->dispatcher->forward(array(

                "controller" => "msg",

                "action" => "showmsg",

            ));           

        }



        $quotation = Quotation::findFirst("purid='{$purid}' and suserid='{$user_id}'");

        if(!$quotation) {

            $quotation = new Quotation();

        }

        

        $quotation->purid = $purid;

        $quotation->price = $price;

        $quotation->spec = $spec;

        $quotation->puserid = $purchase->uid;

        $quotation->purname = $purchase->username;

        $quotation->suserid = $user_id;

        $quotation->sellname = $sellname;

        $quotation->sareas = $sareas;

        $quotation->saddress = $saddress;

        $quotation->sphone = $sphone;

        $quotation->addtime = time();





        if(!$quotation->save()) {

            $this->flash->error("报价失败，请联系客服！");

            return $this->dispatcher->forward(array(

                "controller" => "msg",

                "action" => "showmsg",

            ));  

        }



        $this->flash->error("报价成功！");

        return $this->dispatcher->forward(array(

            "controller" => "msg",

            "action" => "showmsg",

        ));           

    }


}



