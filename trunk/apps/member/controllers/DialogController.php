<?php

namespace Mdg\Member\Controllers;

use Mdg\Models\Purchase as Purchase;
use Mdg\Models\PurchaseContent as Content;
use Mdg\Models\PurchaseQuotation as Quotation;
use Mdg\Models\Users as Users;
use Mdg\Models\Sell as Sell;
use Mdg\Models\Orders as Orders;
use Mdg\Models\OrdersLog as OrdersLog;
use Mdg\Models\Category as Category;
use Mdg\Models\Areas as Areas;
use Mdg\Models\Shop as Shop;
use Mdg\Models\ShopComments as ShopComments;
use Mdg\Models as M;
use Lib\Areas as lAreas;
use Lib\Func as Func;
use Lib\SMS as sms;
use Mdg\Models\OrdersDelivery as OrdersDelivery;
use Mdg\Models\AreasFull as mAreas;
use Lib as L;
use Mdg\Models\OrderEngineer as OrderEngineer;


class DialogController extends ControllerDialog
{
    

    public function newpurAction() {
      
        $this->view->goods_unit = Purchase::$_goods_unit;

    }

    public function savepurAction() {
        
        
        $purchase = new Purchase();
        $users = Users::findFirstById($this->getUserID());
        if(!$users) {
            $this->flash->error("采购用户不存在");
            return $this->dispatcher->forward(array(
                "controller" => "msg",
                "action" => "showmsg",
            ));
        }
        $areas=M\AreasFull::getFamily($users->areas);
       
        $purchase->uid = $users->id;
        $purchase->title = $title = L\Validator::replace_specialChar($this->request->getPost('title', 'string', ''));
        $purchase->maxcategory = $this->request->getPost('maxcategory', 'int', 0);
        $purchase->category = $this->request->getPost('category', 'int', 0);
        $purchase->quantity = $this->request->getPost('quantity', 'float', 0);
        $purchase->goods_unit = $this->request->getPost('goods_unit', 'int', 0);
        $purchase->state = 0;
        $purchase->areas = $users->areas;
        $purchase->areas_name = $users->ext->areas_name;
        $purchase->address = $users->ext->address;
        $purchase->username = $users->ext->name;
        $purchase->mobile = $users->username;
        $purchase->province_id=isset($areas[0]['id']) ? $areas[0]['id'] : 0;
        $purchase->city_id=isset($areas[1]['id']) ? $areas[1]['id'] : 0;
        $purchase->endtime = L\Validator::replace_specialChar($this->request->getPost("endtime", 'string', ''));
        $purchase->endtime = strtotime($purchase->endtime."23:59:59");
        $purchase->createtime = $purchase->updatetime = time();
        $content = L\Validator::replace_specialChar($this->request->getPost("content", 'string', ''));
        
        if(9999999 < $purchase->quantity) {
            $this->flash->error("采购数量最多为9999999！");
            return $this->dispatcher->forward(array(
                "controller" => "msg",
                "action" => "showmsg",
            ));
        }

        if(!$title || !$content) {
            $this->flash->error("请正确填写采购信息！");
            return $this->dispatcher->forward(array(
                "controller" => "msg",
                "action" => "showmsg",
            ));
        }

        if(!$purchase->save()) {
            $this->flash->error("发布失败，请联系客服！");
            return $this->dispatcher->forward(array(
                "controller" => "msg",
                "action" => "showmsg",
            ));
        }

        $purchase->pur_sn = sprintf('pur%010u', $purchase->id);

        $purchase->save(); 

        $pContent = new Content();
        $pContent->purid = $purchase->id;
        $pContent->content = $content;
        
        if (!$pContent->save()) {
            Purchase::findFirstByid($purchase->id)->delete();
            $this->flash->error("发布失败，请联系客服！");
            return $this->dispatcher->forward(array(
                "controller" => "msg",
                "action" => "showmsg",
            ));
        }

        Category::numAdd($purchase->category, 'pur_num');
        $member_info =$this->request->getPost('member_info', 'int', 0);
        if($member_info=='1'){
            echo "<script>alert('发布成功');location.href='/member/purchase/'</script>"; 
            exit;  
        }
        $this->flash->error("发布成功！");
        return $this->dispatcher->forward(array(
            "controller" => "msg",
            "action" => "showmsg",
        ));
    }

    public function newbuyAction($id) {
        $user_id = $this->getUserID();
        #检测是否绑定了云农宝 start
        $userd = M\Users::findFirstByid($user_id);
        if(!$userd->is_broker){ #不属于直销经纪人进行验证
//            $UserYnpInfo = M\UserYnpInfo::findFirst(" user_id={$user_id}");
//            if (!$UserYnpInfo)
//            {
//                die("<script>alert('您好，发布供应信息，必须先绑定云农宝帐号！');location.href='/member/ynbbinding/'</script>");
//            }
        }
        else{
            $new_url=base64_encode("ref=/sell/index/&msg=直销经纪人不能采购!");
            die("<script>location.href='/member/msg/showmsg?data={$new_url}'</script>");
        }

        #end

        $sell = Sell::findFirstByid($id);
        
        if(!$sell) {
            $this->flash->error("此供应信息不存在！");
            return $this->dispatcher->forward(array(
                "controller" => "msg",
                "action" => "showmsg",
            ));
        }
    
        if($this->getUserID() == $sell->uid) {
            $this->flash->error("不能采购自己发布的信息");
            return $this->dispatcher->forward(array(
                "controller" => "msg",
                "action" => "showmsg",
            ));
        }
        //print_r($sell->toArray());die;
        //检测商品状态
        if(!$sell->state || $sell->is_del || $sell->state==2) {
            $this->flash->error("该商品已下架！");
            return $this->dispatcher->forward(array(
                "controller" => "msg",
                "action" => "showmsg",
            ));
        }
     
        // 添加点击量
        Sell::clickAdd($id);



        $users = Users::findFirstByid($user_id);

        $this->view->users = $users;
        $this->view->sell = $sell;
        
        $this->view->minnumber = $sell->min_number;
        if($sell->quantity>0){
            $this->view->quantity = intval($sell->quantity) ? $sell->quantity : 0;
            
        }else{
            
              $this->view->quantity = intval(10000000000);
        }
     
        $this->view->goods_unit = Purchase::$_goods_unit;
        $this->view->curAreas = lAreas::ldData($users->areas);

    }
   public function savebuyAction() {
       
        $sellid = $this->request->getPost('sellid', 'int', 0);
        $areas = $this->request->getPost('areas', 'int', 0);
        $purname =$this->request->getPost('purname', 'string', '');
        $quantity = $this->request->getPost('quantity', 'float', 0.00);
        $purphone = L\Validator::replace_specialChar($this->request->getPost('purphone', 'string', ''));
        $address = L\Validator::replace_specialChar($this->request->getPost('address', 'string', ''));
        $engphone = L\Validator::replace_specialChar($this->request->getPost('engphone', 'string', ''));//工程师手机号      
      
        if(!$sellid || !$areas || !$purname || !$quantity || !$purphone ) {
            
            $this->flash->error("信息不完整！");
            return $this->dispatcher->forward(array(
                "controller" => "msg",
                "action" => "showmsg",
            ));
        }
     
        $sell = Sell::findFirstByid($sellid);
        $user_id = $this->getUserID();
        if($sell->quantity>0){
            if($sell->quantity < $quantity&&$sell->uid!=0) {
                $this->flash->error("采购量不能大于供应量！");
                return $this->dispatcher->forward(array(
                    "controller" => "msg",
                    "action" => "showmsg",
                ));
            }
        }
        if(10000000 < intval($quantity)) {
            $this->flash->error("最大采购量不超过999999！");
            return $this->dispatcher->forward(array(
                "controller" => "msg",
                "action" => "showmsg",
            ));
        }
        if(!$sell) {
            $this->flash->error("此供应信息不存在！");
            return $this->dispatcher->forward(array(
                "controller" => "msg",
                "action" => "showmsg",
            ));
        }

        if($user_id == $sell->uid) {
            $this->flash->error("不能采购自己发布的商品！");
            return $this->dispatcher->forward(array(
                "controller" => "msg",
                "action" => "showmsg",
            ));
        }

        //检测买方 卖方是否绑定云农宝信息
        // $sellermobile = Users::getUsersName($sell->uid);
        // $ThriftInterface = new L\Ynp($this->ynp);
        // //检测卖家信息
        // $data = $ThriftInterface->checkPhoneExist($sellermobile);
        // if($data != '01') {
        //     //绑定用户信息
        //     $member = new L\Member();
        //     $info = $member->getMember($sellermobile);

        //     $ynpinfo = $ThriftInterface->userDataSync(
        //         $info['user_id'],
        //         $info['user_name'],
        //         $info['email'],
        //         $info['password'],
        //         $info['reg_time'], 
        //         $info['msn'], 
        //         '','','',$info['qq'],0
        //     );
        // }
    
        $order = new Orders();
        $order->sellid = $sellid;
        $order->puserid = $user_id;
        $order->purname = $purname;
        $order->purphone = $purphone;
        $order->suserid = $sell->uid;
        $order->sname = $sell->uname;
        $order->sphone = $sell->mobile;
        $order->areas = $areas;
        $order->address = Func::getCols(mAreas::getFamily($areas), 'name', ',');
        $order->goods_name = $sell->title;

        if($sell->price_type==1){
            $price=M\SellStepPrice::getorderprice($sellid,$quantity);
            $order->price=$price;
            $order->state = 3;
        }else{
            $order->price = $sell->min_price;
            $order->state = 2;
        }
        $order->quantity = $quantity;
        $order->goods_unit = $sell->goods_unit;
        $order->total = $order->price * $order->quantity;
        $order->addtime = $order->updatetime = time();
        $order->areas_name = Func::getCols(mAreas::getFamily($areas), 'name', ',');
        $order->source = 1;
        $order->pay_time = 0 ;
        if(!$order->save()) {
            $this->flash->error("采购失败，请联系客服！");
            return $this->dispatcher->forward(array(
                "controller" => "msg",
                "action" => "showmsg",
            ));
        }

        #订单号 ， 拼接随机数
        $random = date('Ym',time()). Func::random(4,1);

        $order->order_sn = sprintf('mdg%09u', $order->id . $random );
        $order->save();
        OrdersLog::insertLog($order->id, 2, $user_id, $this->getUserName(), 0, $demo='采购下单');
        $info = array();
        $info['order_id'] = $order->id;
        $info['comment'] = "您发布的{$order->goods_name}商品，有人采购啦";
        $info['user_id'] = $sell->uid;
        M\Message::GetInsertMessage($info);

        $sms=new sms();
        $msgs = "您发布的{$order->goods_name}商品，有人采购啦";
        $mobile=$sell->mobile;
        $str = $sms->send($mobile,$msgs);

        // //插入app消息
        // $Curl = new L\Curl();  
        // $info["type"]=1;
        // $data=array();
        // $data["data"] =json_encode($info);
       
        // $url="http://shops.ync365.com/shop/msg/savemsg";
        // $data = $Curl->POST($url,$data);

        //保存服务工程师信息
        // if($engphone){
        //     $client = L\Func::serviceApi();
        //     $data = $client->county_selectByEngineerMobile($engphone); 
        //     if($data){
        //         $OrderEngineer = new OrderEngineer();
        //         $OrderEngineer->order_id =$order->id;
        //         $OrderEngineer->order_sn =$order->order_sn;
        //         $OrderEngineer->engineer_name =$data['engineer_name']; 
        //         $OrderEngineer->engineer_phone =$data['engineer_phone']; 
        //         $OrderEngineer->add_time =time(); 
        //         $OrderEngineer->engineer_id =$data['engineer_id'];
        //         $OrderEngineer->type = OrderEngineer::ORDER_TYPE_ORDERS;
        //         $OrderEngineer->save();         
        //     }
        // }
       if($sell->price_type==1){
           $new_url = base64_encode("ref=/member/ordersbuy/info/{$order->id}&msg=已生成采购单点确定去支付!");
           die("<script>location.href='/member/msg/showmsg?data={$new_url}'</script>");
       }
       else{
           $this->flash->error("采购成功！");
           return $this->dispatcher->forward(array(
               "controller" => "msg",
               "action" => "showmsg",
           ));
       }
    }

    public function newquoAction($id) {
        $purchase = Purchase::findFirstByid($id);
        #检测是否绑定了云农宝 start
        $user_id = $this->getUserID();
        $userd = M\Users::findFirstByid($user_id);
        if(!$userd->is_broker) { #不属于直销经纪人进行验证
            $UserYnpInfo = M\UserYnpInfo::findFirst(" user_id={$user_id}");
            if (!$UserYnpInfo) {
                $new_url = base64_encode("ref=/member/ynbbinding/&msg=您好,绑定云农宝帐号,再报价!");
                die("<script>location.href='/member/msg/showmsg?data={$new_url}'</script>");
            }
        }
        #end
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
        $spec = L\Validator::replace_specialChar($this->request->getPost('spec', 'string', ''));
        $sellname = L\Validator::replace_specialChar($this->request->getPost('sellname', 'string', ''));
        $sareas = $this->request->getPost('sareas', 'int', 0);
        $saddress = L\Validator::replace_specialChar($this->request->getPost('saddress', 'string', ''));
        $sphone = L\Validator::replace_specialChar($this->request->getPost('sphone', 'string', ''));

        if(!$price || !$spec || !$sellname || !$sareas || !$sphone) {
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
        $quotation->saddress = Func::getCols(mAreas::getFamily($sareas), 'name', ',');
        $quotation->sphone = $sphone;
        $quotation->addtime = time();
       

        if(!$quotation->save()) {
            $this->flash->error("报价失败，请联系客服！");
            return $this->dispatcher->forward(array(
                "controller" => "msg",
                "action" => "showmsg",
            ));  
        }
        $info = array();
        $info['comment'] = "您发布的{$purchase->title}采购，有人报价啦";
        $info['offer_id'] = $quotation->purid;
        $info['user_id'] = $quotation->puserid;
        M\Message::GetInsertOffer($info);
        
         //设置价格成功  发送短信
        $sms=new sms();
        $msgs = "您采购的{$purchase->title}，有人报价啦";
        $mobile=$purchase->mobile;
        $str = $sms->send($mobile,$msgs);
        
        $Curl = new L\Curl();  
        $info["type"]=2;
        $info["order_id"]=0;
        $data=array();
        $data["data"] =json_encode($info);
        $url="http://shops.ync365.com/shop/msg/savemsg";
        $data = $Curl->POST($url,$data);

        $this->flash->error("报价成功！");
        return $this->dispatcher->forward(array(
            "controller" => "msg",
            "action" => "showmsg",
        ));           
    }

    public function confirmquoAction($quoid) {
        $quotation = Quotation::findFirstByid($quoid);
        $user_id = $this->getUserID();

        if(!$quotation) {
            $this->flash->error("此报价不存在！");
            return $this->dispatcher->forward(array(
                "controller" => "msg",
                "action" => "showmsg",
            )); 
        }

        if($quotation->puserid != $user_id) {
            $this->flash->error("不能采购此信息");
            return $this->dispatcher->forward(array(
                "controller" => "msg",
                "action" => "showmsg",
            )); 
        }

        $users = Users::findFirstByid($user_id);
        $purchase = Purchase::findFirstByid($quotation->purid);

        $this->view->users = $users;
        $this->view->quoid = $quoid;
        $this->view->purchase = $purchase;
        $this->view->curAreas = lAreas::ldData($users->areas);
        $this->view->goods_unit = Purchase::$_goods_unit;

    }
    /**
     * 确认报价产品 产生订单
     * @return [type] [description]
     */
    public function saveconquoAction() {

        $quoid = $this->request->getPost('quoid', 'int', 0);
        $user_id = $this->getUserID();
        $quotation = Quotation::findFirstByid($quoid);

        if($user_id != $quotation->puserid) {
            $this->flash->error("不能采购此报价！");
            return $this->dispatcher->forward(array(
                "controller" => "msg",
                "action" => "showmsg",
            )); 
        }
        
        $purphone = L\Validator::replace_specialChar($this->request->getPost('purphone', 'string', ''));
        $quantity = $this->request->getPost('quantity', 'float', 0.00);
        $purname = L\Validator::replace_specialChar($this->request->getPost('purname', 'string', ''));
        $areas = $this->request->getPost('areas', 'int', 0);
        $address = L\Validator::replace_specialChar($this->request->getPost('address', 'string', ''));
        $engphone = L\Validator::replace_specialChar($this->request->getPost('engphone','string', ''));

        if(intval($quantity)>100000) {
            $this->flash->error("采购量最大为99999！");
            return $this->dispatcher->forward(array(
                "controller" => "msg",
                "action" => "showmsg",
            )); 

        }   

        if(!$purphone || !$quantity || !$purname || !$areas || !$address) {
            $this->flash->error("信息不完整，请完善信息后提交！");
            return $this->dispatcher->forward(array(
                "controller" => "msg",
                "action" => "showmsg",
            )); 
        }
        $order = new Orders();
        $order->purid = $quotation->purid;
        $order->puserid = $user_id;
        $order->purname = $purname;
        $order->purphone = $purphone;
        $order->suserid = $quotation->suserid;
        $order->sname = $quotation->sellname;
        $order->sphone = $quotation->sphone;
        $order->areas = $areas;
        $order->address = Func::getCols(mAreas::getFamily($areas), 'name', ',');
        $order->goods_name = $quotation->purchase->title;
        $order->price = $quotation->price;
        $order->quantity = $quantity;
        $order->goods_unit = $quotation->purchase->goods_unit;
        $order->total = $order->price * $order->quantity;
        $order->addtime = $order->updatetime = time();
        $order->state = 2;
        $order->source = 2;
        $order->pay_time = 0 ;
        $order->areas_name = Func::getCols(mAreas::getFamily($areas), 'name', ',');

        if(!$order->save()) {
            $this->flash->error("确认采购失败，请联系客服！");
            return $this->dispatcher->forward(array(
                "controller" => "msg",
                "action" => "showmsg",
            )); 
        }

        $quotation->state = 1;
        $quotation->save();
        $random = date('Yh',time()). Func::random(4,1);
        $order->order_sn = sprintf('mdg%09u', $order->id . $random );
        OrdersLog::insertLog($order->id, 3, $user_id, $this->getUserName(), 0, $demo='供应商确认订单');
        $order->state = 3;
        $order->save();

        //保存服务工程师信息
        if($engphone){
            $client = L\Func::serviceApi();
            $data = $client->county_selectByEngineerMobile($engphone); 
            if($data){
            $OrderEngineer = new OrderEngineer();
            $OrderEngineer->order_id =$order->id;
            $OrderEngineer->order_sn =$order->order_sn;
            $OrderEngineer->engineer_name =$data['engineer_name']; 
            $OrderEngineer->engineer_phone =$data['engineer_phone']; 
            $OrderEngineer->add_time =time(); 
            $OrderEngineer->engineer_id =$data['engineer_id'];
            $OrderEngineer->type = OrderEngineer::ORDER_TYPE_ORDERS;
            $OrderEngineer->save();         
            }
        }


        $info = array();
        $info['comment'] = "你提交的报价，采购商确定了";
        $info['order_id'] = $order->id;
        $info['user_id'] = $order->suserid;
        $info=M\Message::GetInsertPurchaseOrder($info);
        
        $Curl = new L\Curl();  
        $data=array();
        $info=array();
        $infos['comment'] = "您发布的{$quotation->purchase->title}采购，有人报价啦";
        $infos['offer_id'] = $quotation->purid;
        $infos['order_id'] = $order->id;
        $infos['user_id'] = $quotation->puserid;
        $infos['type']=2;
        $data["data"] =json_encode($infos);
        $url="http://shops.ync365.com/shop/msg/savemsg";
        $data = $Curl->POST($url,$data);


        $this->flash->error("确认采购成功！");
        return $this->dispatcher->forward(array(
            "controller" => "msg",
            "action" => "showmsg",
        )); 
        
    }

    // 修改报价
    public function editquoAction($quoid) {
        $user_id = $this->getUserID();

        $quotation = Quotation::findFirstByid($quoid);
        ;

        if(!$quotation) {
            $this->flash->error("此报价不存在！");
            return $this->dispatcher->forward(array(
                "controller" => "msg",
                "action" => "showmsg",
            )); 
        }

        if($quotation->suserid != $user_id) {
            $this->flash->error("不能修改此报价");
            return $this->dispatcher->forward(array(
                "controller" => "msg",
                "action" => "showmsg",
            )); 
        }

        $this->view->quotation = $quotation;
        $this->view->goods_unit = Purchase::$_goods_unit;

    }

    // 保存修改后的报价
    public function saveeditquoAction() {
        $quoid = $this->request->getPost('quoid', 'int', 0);
        $price = $this->request->getPost('price', 'float', 0.00);

        $quotation = Quotation::findFirstByid($quoid);
        if(!$quotation) {
            $this->flash->error("此报价不存在！");
            return $this->dispatcher->forward(array(
                "controller" => "msg",
                "action" => "showmsg",
            ));
        }

        if(!$price) {
            $this->flash->error("请输入正确的价格！");
            return $this->dispatcher->forward(array(
                "controller" => "msg",
                "action" => "showmsg",
            ));
        }

        $user_id = $this->getUserID();
        if($user_id != $quotation->suserid) {
            $this->flash->error("无权修改此报价！");
            return $this->dispatcher->forward(array(
                "controller" => "msg",
                "action" => "showmsg",
            ));       
        }

        $quotation->price = $price;
        if(!$quotation->save()) {
            $this->flash->error("修改报价失败，请联系客服！");
            return $this->dispatcher->forward(array(
                "controller" => "msg",
                "action" => "showmsg",
            ));  
        }
        $this->flash->error("修改报价成功！");
        return $this->dispatcher->forward(array(
            "controller" => "msg",
            "action" => "showmsg",
        )); 
    }

    public function editpriceAction($oid) {
        $user_id = $this->getUserID();
        #检测是否绑定了云农宝 start
        $userd = M\Users::findFirstByid($user_id);
        if(!$userd->is_broker) { #不属于直销经纪人进行验证
            $UserYnpInfo = M\UserYnpInfo::findFirst("user_id={$user_id}");
            if (!$UserYnpInfo) {
                $new_url = base64_encode("ref=/member/ynbbinding/&msg=您好,绑定云农宝帐号!");
                die("<script>location.href='/member/msg/showmsg?data={$new_url}'</script>");
            }
        }
        #end
        $order = Orders::findFirstByid($oid);
        if(!$order) {
            $this->flash->error("此订单不存在！");
            return $this->dispatcher->forward(array(
                "controller" => "msg",
                "action" => "showmsg",
            )); 
        }


        if($user_id != $order->suserid) {
            $this->flash->error("无权设置此订单报价！");
            return $this->dispatcher->forward(array(
                "controller" => "msg",
                "action" => "showmsg",
            )); 
        }
        $users = Users::findFirstByid($order->puserid);
        $address = Func::getCols(L\AreasFull::getAreasFull($users->areas), 'name', ','); 
        
        $sell = M\Sell::findFirstByid($order->sellid);
        $this->view->goods_unit = Purchase::$_goods_unit;
        $this->view->order = $order;
        $this->view->min_price = $sell->min_price;
        $this->view->max_price = $sell->max_price;
        $this->view->address=$address;
    }

    /**
 * 验证价格设置
 */
    public function checkpriceAction(){
        $min_price= $this->request->getPost('min_price','float','');
        $max_price=$this->request->getPost('max_price','float','');
        $price=$this->request->getPost('price','string','');
        $msg = array();
        if($price<$min_price){
            $msg['error'] = '价格超出报价范围';   
        }else if($price>$max_price){
            $msg['error'] = '价格超出报价范围';   
        }else{
            $msg['ok'] = ''; 
        }
         echo json_encode($msg);die; 
    }

    /**
     * 订单确认报价
     * @return [type] [description]
     */
    public function savepriceAction() {
        $oid = $this->request->getPost('oid', 'int', 0);
        $price = $this->request->getPost('price', 'float', 0.00);

        if(!$price) {
            $this->flash->error("请填写价格！");
            return $this->dispatcher->forward(array(
                "controller" => "msg",
                "action" => "showmsg",
            )); 
        }


        $order = Orders::findFirstByid($oid);
        if(!$order) {
            $this->flash->error("此订单不存在！");
            return $this->dispatcher->forward(array(
                "controller" => "msg",
                "action" => "showmsg",
            )); 
        }
        
        $user_id = $this->getUserID();
        if($user_id != $order->suserid || $order->state != 2) {
            $this->flash->error("请正确操作确认订单！");
            return $this->dispatcher->forward(array(
                "controller" => "msg",
                "action" => "showmsg",
            )); 
        }
        OrdersLog::insertLog($oid, 3, $user_id, $this->getUserName(), 0, $demo='供应商修改价格确认订单');
        $order->state = 3;
        $order->price = $price;
        $order->total = $order->price * $order->quantity;
        $order->is_out=0;

        if(!$order->save()) {
            $this->flash->error("设置价格失败，请联系客服！");
            return $this->dispatcher->forward(array(
                "controller" => "msg",
                "action" => "showmsg",
            )); 
        }

        $info = array();
        $info['comment'] = "你提交的订单，供应商确定了";
        $info['order_id'] = $order->id;
        $info['user_id'] = $order->puserid;
        $info=M\Message::GetInsertPurchase($info);
        
        //设置价格成功  发送短信
        $sms=new sms();
        $msgs = '您购买的商品[订单号：'.$order->order_sn.']已被卖家确认';
        $mobile=$order->purphone;
        $str = $sms->send($mobile,$msgs);
        

        $this->flash->error("设置价格成功！");
        return $this->dispatcher->forward(array(
            "controller" => "msg",
            "action" => "showmsg",
        )); 
    }
    public function setdevAction(){
       $oid = $this->request->get('id', 'int', 0);
       $this->view->order_id=$oid;
       $this->view->dev_name=OrdersDelivery::$dev_name;
       $this->view->ajax=1;
    }
    
    public function evaluationAction($shop_id){
        $mdgshop=Shop::findFirstByshop_id($shop_id);
        if($mdgshop){
            $mdgshop=$mdgshop->toArray();
        }
        $users = $this->session->user;

        if ($mdgshop) 
        {
            if ($mdgshop['user_id'] == $users['id']) 
            {

                $new_url=base64_encode("&msg=禁止自己评论自己的店铺");
                $this->response->redirect("msg/showmsg?data={$new_url}", false, 301)->sendHeaders();
                die;
            }
            
            $shopcomments = ShopComments::findFirst('shop_id=' . $mdgshop['shop_id'] . ' and user_id =' . $users['id']);
            
            if ($shopcomments) 
            {
                $new_url=base64_encode("&msg=您已评论过该店铺");
                $this->response->redirect("msg/showmsg?data={$new_url}", false, 301)->sendHeaders();
                die;
            }
        }
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
          $shopcomments->comment = L\Validator::replace_specialChar($this->request->getPost('pl_area','string',''));
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
    /**
     * 服务站 区域
     * @return [type] [description]
     */
    public function newaddressAction(){
        //查询所有省级
        //$this->view->ajax=1;
        $pid = $this->request->get('parent_id', 'int', 0);
        $type= $this->request->get('type', 'int', 0);
        $province = $this->request->get('province', 'int', 0 );
        $city = $this->request->get('city', 'int', 0 );
        $distinct = $this->request->get('distinct', 'int', 0 );
        $town = L\Validator::replace_specialChar($this->request->get('town', 'string', 0 ));

        if($pid<'0'){
           $data=array();
        }
        $areasList = M\AreasFull::find(array("is_show=1 and pid = '{$pid}'"))->toArray();        
        if($areasList){
           $data=array(); 
        }

        $this->view->data = $areasList;
        if($province && $city && $distinct && $town){
            $name[] = M\AreasFull::getAreasNametoid($province);
            $name[] = M\AreasFull::getAreasNametoid($city);
            $name[] = M\AreasFull::getAreasNametoid($distinct);
          
            $areas = '"'.implode( '","', $name).'"';
           
            //查询子级数据
            $chidData = M\AreasFull::getChildData($distinct);
            $this->view->town = explode(',',$town);
            $this->view->chidData = $chidData;
            $this->view->areas = $areas;
        }
        
    }  
    /**
     * 申请看货服务
     * @param  integer $sellid 供应id
     * @return [type]          [description]
     */
    public function lookgoodsAction($sellid=0) {
        
        
        $session = $this->session->user;
        //检测查看是否为自己商品
        $uid = $session['id'];

        $data = M\Sell::findFirst(" id = '{$sellid}' AND uid != '{$uid}'");
        
        if(!$data) {
            $this->flash->error("禁止对自己申请看货");
                    return $this->dispatcher->forward(array(
                    "controller" => "msg",
                    "action" => "showmsg",
            ));
            exit;   
        }
        echo "<script>parent.setTitle('申请看货服务');</script>";
        $this->view->unit = M\Purchase::$_goods_unit;
        $this->view->user = $session;
        $this->view->data = $data;
    }

      /**
     * 保存申请看货信息
     * @return [type] [description]
     */
    public function savelookgoodsAction () {

        $message = new M\Message();
        $name       = L\Validator::replace_specialChar($this->request->getPost('name', 'string', ''));
        $mobile     = L\Validator::replace_specialChar($this->request->getPost('mobile', 'string', ''));
        $number     = L\Validator::replace_specialChar($this->request->getPost('number', 'string', ''));
        $goods_name = L\Validator::replace_specialChar($this->request->getPost('goods_name', 'string', ''));
        $sepc       = L\Validator::replace_specialChar($this->request->getPost('sepc', 'string', ''));
        $address    = L\Validator::replace_specialChar($this->request->getPost('address', 'string', ''));
        $sellid     = L\Validator::replace_specialChar($this->request->getPost('sellid', 'string', ''));
        
        $data = M\Sell::findFirst(" id ='{$sellid}'");
      
        if(!$data) {
            $this->flash->error("来源信息错误");
                    return $this->dispatcher->forward(array(
                    "controller" => "msg",
                    "action" => "showmsg",
            ));
            exit; 
        }

        $comment  = M\Message::getAfterComment('seen', $name, $mobile, $goods_name );
        $post['oid'] = 0;
        $post['goods_name'] = $goods_name;
        $post['buyer_name'] = $data->uname;
        $post['contact_phone'] = $mobile;
        $post['contact_man'] = $name;
        $post['require'] = '';
        $post['offer_id'] = $sellid;
        $post['comment'] = $comment;
        $post['buy_qty'] = $number;
        $post['address'] = $address;
        $post['spec']  = $sepc;
        $post['uid'] = $data->uid;
        
        
        $data = M\Message::saveMessages($post);
        echo "<script>parent.location.reload();</script>";
    }
    public function changepriceAction($id){
         $user_id = $this->getUserID();
         $where=" uid = {$user_id} and id={$id}";
         $sell = M\Sell::findFirst($where);
         $this->view->goods_unit = M\Purchase::$_goods_unit;
         $this->view->time_type = M\Sell::$type;
         $this->view->sell = $sell;
         $SellStepPrice=M\SellStepPrice::find("sell_id={$sell->id}");
         $SellStepcount=M\SellStepPrice::count("sell_id={$sell->id}");
         $this->view->SellStepcount = $SellStepcount;
         $this->view->sellstepprice=$SellStepPrice;
         $this->view->quantity = intval($sell->quantity);
    }
    public function changequantityAction($id){
         $user_id = $this->getUserID();
         $where=" uid = {$user_id} and id={$id}";
         $sell = M\Sell::findFirst($where);
         if($sell->price_type==1){
           $this->view->min_number = M\SellStepPrice::getquantity($sell->id);
         }else{
           $this->view->min_number = $sell->min_number;
         }
         $this->view->quantity = $sell->quantity;
         $this->view->goods_unit = M\Purchase::$_goods_unit;
         $this->view->time_type = M\Sell::$type;
         $this->view->sell = $sell;
         
    }
    public function savepriceproAction(){
      

        $min_price = $this->request->getPost("min_price", 'float', 0.00);
        $max_price = $this->request->getPost("max_price", 'float', 0.00);
        $quantity = $this->request->getPost("quantity",'float',0.00);
        $step_quantity=$this->request->getPost("step_quantity", 'string',0);
        $step_price=$this->request->getPost("step_price", 'string',0);
        $price_type=$this->request->getPost("price_type",'int',0);
        $ladder_goods_unit=$this->request->getPost("ladder_goods_unit",'int',0);
        $sellid=$this->request->getPost("sellid", 'int', 0.00);
       
        $sell = M\Sell::findFirstByid($sellid);
        $user_id = $this->getUserID(); 
        if ($user_id != $sell->uid) 
        {
            $this->flash->error("你无权修改此供应信息！");
            return $this->dispatcher->forward(array(
                "controller" => "msg",
                "action" => "showmsg",
            ));
        }
        if($min_price&&$max_price){
             $sell->min_price=$min_price;
             $sell->max_price=$max_price;
        }

        if($quantity>=0){
             $sell->quantity=$quantity;
        }
        if($price_type == 1&&$step_price&&$step_quantity){ #阶梯价格保存
            $sellprice=M\SellStepPrice::find("sell_id={$sellid}");
            if($sellprice){
                $sellprice->delete();
            }
            foreach($step_quantity AS $stepKey => $stepVal){
                $sell_step_price = new M\SellStepPrice();
                $sell_step_price->sell_id = $sellid;
                $sell_step_price->price = $step_price[$stepKey];
                $sell_step_price->quantity = $step_quantity[$stepKey];
                $sell_step_price->save();
            }
        }
        if(!$sell->save()){
            $this->flash->error("修改失败！");
            return $this->dispatcher->forward(array(
                "controller" => "msg",
                "action" => "showmsg",
            ));
        }else{
            $_url = "/member/sell/index/";
            $new_url=base64_encode("ref=/member/sell/index/&msg=修改成功");
            die("<script>location.href='/member/msg/showmsg?data={$new_url}'</script>");
           // $this->response->redirect("msg/showmsg?ref=/member/sell/index/&msg=修改成功！", false, 301)->sendHeaders();
        }
    }
    /**
     * 确认提交标签数据
     * @return [type] [description]
     */
    public function savetagAction(){
        
    }
}