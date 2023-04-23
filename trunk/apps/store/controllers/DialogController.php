<?php

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
class DialogController extends ControllerShop
{
    public function evaluationAction($shop_id){
           
            $this->view->shop_id =$shop_id;
    }
    public function evaluationsaveAction(){
  
          $shop_id=$this->request->getPost('shop_id','int','0');
          $shop=M\Shop::findFirstByshop_id($shop_id);
          $user_name=$this->session->user['name'];
          $user_id = $this->session->user['id'];
          $shopcomments=new M\ShopComments();
          $shopcomments->shop_id = $this->request->getPost('shop_id','int','0');
          $shopcomments->user_name = $user_name;
          $shopcomments->user_id = $user_id;
          $shopcomments->comment = $this->request->getPost('pl_area','string','');
          $shopcomments->add_time = time();
          $shopcomments->last_update_time = time();
          $shopcomments->is_check = 0;
          $shopcomments->shop_name = $shop->shop_name;
          $shopcomments->service = $this->request->getPost('fwtdVal','int','0');
          $shopcomments->accompany = $this->request->getPost('ptcdVal','int','0');
          $shopcomments->description = $this->request->getPost('msxfVal','int','0');
          $shopcomments->supply = $this->request->getPost('ghnlVal','int','0');

        if(!$shopcomments->save()) {
            $this->flash->error("评价失败！");
                        return $this->dispatcher->forward(array(
                        "controller" => "msg",
                        "action" => "showmsg",
            ));
        }else{
            $this->flash->error("评价成功！");
             return $this->dispatcher->forward(array(
                        "controller" => "msg",
                        "action" => "showmsg",
            ));
        }
    }
}