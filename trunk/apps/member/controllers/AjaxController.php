<?php
namespace Mdg\Member\Controllers;
use Mdg\Models\SellStepPrice;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Mdg\Models\Shop as Shop;
use Mdg\Models\Sell as Sell;
use Mdg\Models\Category as Category;
use Mdg\Models\ShopCredit as ShopCredit;
use Mdg\Models\Orders as Orders;
use Mdg\Models\PurchaseQuotation as Quotation;
use Mdg\Models\AreasFull as AreasFull;
use Mdg\Models\Message as Message;
use Lib\Func as Func;
use Lib\Arrays as Arrays;
use Lib as L;
class AjaxController extends ControllerMember
{
    public function checkAction(){
        $shop_name = L\Validator::replace_specialChar($this->request->getPost('shop_name','string',''));

        $shopcount= shop::count("shop_name ='{$shop_name}'");
        if($shopcount>0){
            $msg['error'] = '店铺名称已经存在!';
             
        }else{
            $msg['ok'] = '';     
        }
        die(json_encode($msg));
    }    
    /**
     * 检测店铺名称是否存在
     */
    public function checkeditAction(){
        $shop_name = L\Validator::replace_specialChar($this->request->getPost('shop_name','string',''));
       
        $shop=$this->checkShopExist(1);
        $shopcount= shop::count("shop_name ='{$shop_name}' and shop_link !='$shop->shop_link' ");
        if($shopcount>0){
            $msg['error'] = '店铺名称已经存在!';
             
        }else{
            $msg['ok'] = '';     
        }
        die(json_encode($msg));
    }
     /**
     * 检测店铺名称是否存在
     */
    public function checkserviceAction(){
        $shop_name = L\Validator::replace_specialChar($this->request->getPost('shop_name','string',''));
       
        $shop=$this->checkServiceExist(1);
        $shopcount= shop::count("shop_name ='{$shop_name}' and shop_link !='$shop->shop_link' and is_service =1 ");
        if($shopcount>0){
            $msg['error'] = '店铺名称已经存在!';
             
        }else{
            $msg['ok'] = '';     
        }
        die(json_encode($msg));
    }
    public function delimgAction(){
        $id = $this->request->get('id','int',0);
        $type = $this->request->get('type','int',0);
        $rs = array('state'=>true, 'msg'=>'删除成功！');
        $shopcredit=ShopCredit::findFirst("shop_id={$id}");
        if(!$shopcredit){
         $rs = array('state'=>true, 'msg'=>'删除失败！');   
          die(json_encode($rs));
        }
        if($type==6){
        $shopcredit->bank_card_picture=''; 
        }
        if($type==7){
        $shopcredit->identity_card_front=''; 
        }
        if($type==8){
        $shopcredit->identity_card_back='';
        }
        if($type==9){
        $shopcredit->identity_picture_lic='';
        }
        if($type==10){  
        $shopcredit->tax_registration='';   
        }
        if($type==11){
        $shopcredit->organization_code='';   
        }
        if(!$shopcredit->save()){
        $rs = array('state'=>true, 'msg'=>'删除失败！');
        }
        die(json_encode($rs));
    }

    /**
     * 获取分类
     * @return [type] [description]
     */
    public function getcateAction(){
        
     
        $pid = $this->request->get('pid','int',0);
        $autocomplete = L\Validator::replace_specialChar($this->request->get('autocomplete','string',''));
        if(!$pid){
             $rs = array('state'=>true);
             die(json_encode($rs));
        }

        $cate=Category::find("parent_id={$pid} and title like '%{$autocomplete}%' ")->toArray();
         
        if(empty($cate)){
            $data[] = array('label'=>"其他",'value'=>"其他",'id'=>'-1');
        }
        foreach ($cate as $key => $value) {
           if($value["title"]!=''){
              $data[] = array('label'=>$value['title'],'value'=>$value['title'],'id'=>$value['id']);
           }
        }
        die(json_encode($data));

    }
    public function getorderAction(){

        $user_id = $this->getUserID();
    
        //订单个数
        $ordercount=Orders::find("suserid={$user_id} and state=2 and is_out= 0")->toArray();
        if($ordercount){
                $order_id=Arrays::getCols($ordercount,'id',',');
                $rs = array('state'=>true, 'order_id'=>$order_id);
        }else{
               $rs = array('state'=>false, 'order_id'=>'');
        }
        die(json_encode($rs));          
    }
    public function delorderAction(){
        $user_id = $this->getUserID();

        $order_id = L\Validator::replace_specialChar($this->request->get('order_id','string',''));
        if($order_id){
            $ordercount=Orders::find("suserid={$user_id} and id in ({$order_id}) and is_out=0 ")->toArray();
            if($ordercount){
                foreach ($ordercount as $key => $value) {
                     $neworder=Orders::findFirst("suserid={$user_id} and id=".$value['id']." and is_out=0");
                     $neworder->is_out=1;
                     $neworder->save();
                }
            }
        }
        $rs = array('state'=>true);
        die(json_encode($rs));
        
    }
    public function GetCountMessageAction(){
        $user_id = $this->getUserID();
      
        $county=Message::GetMessageUnreadCount($user_id);

        if(!isset($this->session->time) || $this->session->time==''){
            $this->session->time = time();
        }

        if($county>0){
                $rs = array('state'=>true,'time'=>time().$this->session->time);
        }else{
               $rs = array('state'=>false,'error'=>'无消息');
        }

        if(time() < $this->session->time+10){
            $rs = array('state'=>false,'error'=>'消息等待','time'=>time().$this->session->time);
        }else{
            $rs = array('state'=>true,'time'=>time().$this->session->time.'5');
            $this->session->time = '';
        }
        die(json_encode($rs));   
    }
    
     public function getquotationAction(){

        $user_id = $this->getUserID();
        //订单个数
        $quotation=Quotation::find(" puserid={$user_id} and is_out=0 ")->toArray();
        if($quotation){
                $order_id=Arrays::getCols($quotation,'id',',');
                $rs = array('state'=>true, 'order_id'=>$order_id);
        }else{
               $rs = array('state'=>false, 'order_id'=>'');
        }
        die(json_encode($rs));          
    }
    public function delquotationAction(){
        $user_id = $this->getUserID();
        $order_id = L\Validator::replace_specialChar($this->request->get('order_id','string',''));
        if($order_id){
            $quotation=Quotation::find("puserid={$user_id} and id in ({$order_id}) and is_out=0 ")->toArray();
            if($quotation){
                foreach ($quotation as $key => $value) {
                     $neworder=Quotation::findFirst("puserid={$user_id} and id=".$value['id']." and is_out=0");
                     $neworder->is_out=1;
                     $neworder->save();
                }
            }
        }
        $rs = array('state'=>true);
        die(json_encode($rs));
        
    }

    /**
     * 检测供应数量
     * @return [type] [description]
     */

    public function checkquantityAction(){
        $user_id = $this->getUserID();
        $quantity = $this->request->get('quantity','float',0);
        $sellid = $this->request->get('sellid','float',0);
        if($sellid){
            $sell=Sell::findFirstByid($sellid);
            switch($sell->price_type){
                case 0:
                    if($quantity<$sell->min_number&&$sell->min_number!=0){
                        $msg['error'] = '采购量不能小于起购量';
                    }else if($quantity>intval($sell->quantity)&&$sell->quantity!=0){
                        $msg['error'] = '采购量不能大于供应量';
                    }else{
                        $msg['ok'] ='';
                    }
                    break;
                case 1:
                    if($quantity>intval($sell->quantity)&&$sell->quantity!=0){
                        $msg['error'] = '采购量不能大于供应量';
                    }
                    else{
                        $SellStepPrice=SellStepPrice::find("sell_id={$sellid}")->toArray();
                        if($quantity<$SellStepPrice[0]['quantity']) {
                            $msg['error'] = '采购量不能小于起购量';
                        }else{
                            $msg['ok'] ='';
                        }
                    }
                    break;
            }
            die(json_encode($msg));
        }
        exit;
    }
    /**
     *  负责区域
     * @return [type] [description]
     */
    
    public function getAddressAction() 
    {
        $pid = $this->request->get('parent_id', 'int', 0);
        if($pid<'0'){
            die(json_encode(''));
        }
        $areasList = AreasFull::find(array("pid = '{$pid}'"))->toArray();

        if(empty($areasList)) {
            $areasList = AreasFull::find("id = '{$pid}'")->toArray();
        }
        
        $rs = Func::parseLect($areasList, 'id', 'name');

        die(json_encode($rs));
    }
    public function getcatenameAction(){

        $pid = $this->request->get('pid','int',1);

        $cate=Category::find("parent_id={$pid} ")->toArray();

        if(!$cate){
            die(json_encode(array()));
        }
        $str='';
        foreach ($cate as $key => $value) {
            $id=$value["id"];
            $str.="<a href='javascript:;' id='{$id}'>{$value['title']}</a>";
        }
        $str=base64_encode($str);
        die(json_encode($str));
    }
}