<?php
/**
 * 葡萄活动
 */
namespace Mdg\Frontend\Controllers;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Mdg\Models\Sell as Sell;
use Mdg\Models\Orders as Orders;
use Mdg\Models\Purchase as Purchase;
use Lib\Func as Func;
use Lib\Pages as Pages;
use Lib\Time as Time;
use Lib\Utils as Utils;
use Lib\Areas as Areast;
use Mdg\Models as M;
use Lib as L;
class ActiveController extends ControllerBase
{

    /**
     *   显示活动的商品
     */
    public function indexAction() 
    {
        $sell_data = Sell::findFirst("activity_id>0");
        $this->view->sell=$sell_data;
        $this->view->issession=$this->session->user;
        $day= date('d' ,strtotime('+2 day'));
        $month= date('m' ,strtotime('+1 day'))-1;
        $this->view->day=$day;
        $this->view->month=$month;

        $this->view->min_number=intval($sell_data->min_number);
        // var_dump($sell_data->toArray());die;
        $this->view->title = '葡萄活动-丰收汇';
        $this->view->keywords = '';
        $this->view->descript = '';
    }
    public function createAction(){
       
    
        $sellid = $this->request->getPost('sellid', 'int', ACTIVE_SELL_ID);
       
        $areas = $this->request->getPost('areas', 'int', 1571);
        if($areas==''){
            $areas=1571;
        }
        $distribution = $this->request->getPost('distribution', 'int',1);
        $distributiontime = L\Validator::replace_specialChar($this->request->getPost('distributiontime', 'string', ''));
        $username = L\Validator::replace_specialChar($this->request->getPost('username', 'string', ''));   
        $mobile = L\Validator::replace_specialChar($this->request->getPost('mobile', 'string', '')); 
        $address = L\Validator::replace_specialChar($this->request->getPost('address', 'string', ''));
        if($address=='请输入街道地址'){
            $address='';
        } 
        $quantity = L\Validator::replace_specialChar($this->request->getPost('number', 'string', '')); 
       
        //自主采购
        $zxusername = L\Validator::replace_specialChar($this->request->getPost('zxusername', 'string', '')); 
        $zxmobile = L\Validator::replace_specialChar($this->request->getPost('zxmobile', 'string', '')); 
        $active_desc= L\Validator::replace_specialChar($this->request->getPost('active_desc', 'string', '')); 
        $stime=L\Validator::replace_specialChar($this->request->getPost('stime', 'string', '')); 
        
        $user_id=$this->getUserID();
        $UserYnpInfo = M\UserYnpInfo::findFirst(" user_id={$user_id}");
        if (!$UserYnpInfo)
        {
            die("<script>alert('您好,请先绑定云农宝帐号！');location.href='/member/ynbbinding/'</script>");
        }
        if($user_id==ACTIVE_USER_ID){
            echo "<script>alert('不能购买自己的商品');location.href='/active/index/'</script>";die;
        }
        if(!$user_id){
            echo "<script>alert('请登录购买');location.href='/member/login/index/'</script>";die;
        }
        //检测采购量不能大于供应量 不能小于起购量
        $sell = M\Sell::findFirstByid($sellid);
        $flag = false;
        $this->db->begin();

        try
        {

            $order = new M\Orders();
            $order->sellid = $sellid;
            $order->puserid = $user_id;
            $order->purname = $username;
            $order->purphone = $mobile;
            $order->suserid = ACTIVE_USER_ID;
            $order->sname = $sell->uname;
            $order->sphone = "15106707083";
            $order->areas = $areas;
            $order->areas_name = Func::getCols(M\AreasFull::getFamily($areas), 'name', ',');
            $order->goods_name = $sell->title;
            $order->price = $sell->min_price;
            $order->quantity = $quantity;
            $order->goods_unit = 22;
            $order->total = $order->price * $order->quantity;
            $order->addtime = $order->updatetime = time();
            $order->except_shipping_type=$distribution;
            $order->state = 3;
            $order->source=7;
            $order->activity_id=1;
            if($distribution==1){
                  $order->address =$order->areas_name.$address;
                  $order->purname = $username;
                  $order->purphone = $mobile;
                  $order->except_shipping_time=strtotime($distributiontime);
                  $order->demo="送货上门";
            }else{
                  $order->address =$order->areas_name;
                  $order->purname = $zxusername;
                  $order->purphone = $zxmobile;
                  $order->except_shipping_time=strtotime($stime); 
                  $order->demo=$active_desc;  
            }
            
            if(!M\OrdersLog::insertLog($order->id, 2, $user_id, $this->session->user["mobile"], 0, $demo='采购下单')){
                throw new \Exception(parent::NEWBUY_ERROR);
            }   
            if(!$order->save()){
                 throw new \Exception(parent::NEWBUY_ERROR);
            }
            $random = date('Ym',time()). Func::random(4,1);
            $order->order_sn = sprintf('mdg%09u', $order->id . $random );
           
            $order->save();

            $flag = $this->db->commit();
        }
        catch(\Exception $e) 
        {
            $flag = false;
            $this->db->rollback();
        }
        if($flag){
            echo "<script>location.href='/member/ordersbuy/payorderpro?gate_id=1&order_id={$order->id}'</script>";die;
        }else{
            echo "<script>alert('购买失败');location.href='/member/ordersbuy/index/'</script>";die;
        }
    }
    public function cornAction(){
     
         $this->view->title = '玉米专题-丰收汇';
    }
    public function firstrankAction($type=0){
        $type = intval($type)>0 ? intval($type) : 0;
        $type = intval($type)<8 ? intval($type) : 0;
        switch ($type) {
            case '1':
            $title="府谷";
                break;
            case '2':
            $title="菏泽";
                break;
            case '3':
            $title="嘉祥";
                break;
            case '4':
            $title="金乡";
                break;
            case '5':
            $title="南丰";
                break;
            case '6':
            $title="五常";
                break;
            case '7':
            $title="子洲";
                break;
            default:
              $title='';
                break;
        }
        if($title!=''){
            $this->view->title="一县一品-{$title}-丰收汇";
        }else{
            $this->view->title="一县一品-丰收汇";
        }
        $this->view->type=$type;

    }
}
