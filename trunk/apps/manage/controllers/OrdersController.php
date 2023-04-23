<?php
/**
 * 订单管理
 */
namespace Mdg\Manage\Controllers;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Mdg\Models\Purchase as Purchase;
use Mdg\Models\PurchaseQuotation as Quotation;
use Mdg\Models\Orders as Orders;
use Mdg\Models\OrdersLog as OrdersLog;
use Mdg\Models\Users as Users;
use Lib\Func as Func;
use Lib\Pages as Pages;
use Mdg\Models as M;
use Lib\Category as lCategory;
use Mdg\Models\OrdersDelivery as OrdersDelivery;
use Mdg\Models\OrderEngineer as OrderEngineer;
use Lib as L;

class OrdersController extends ControllerMember
{
    
    private $PAY_TYPE = array(
        0 => '云农宝',
        1 => '农行直联',
        2 => '农行直联',
        3 => '微信支付',
        4 => '京东支付',
        5 => 'u刷',
        ''=>''
    );
    /**
     * 订单列表
     * @return [type] [description]
     */
    public function indexAction() 
    {
        $page        = $this->request->get('p', 'int', 1);
        $category    = $this->request->get('category', 'int', 0);
        $maxcategory = $this->request->get('maxcategory', 'int', 0);
        $ynp_no      = $this->request->get('ynp_no', 'string', '');
        $bank_no     = $this->request->get('bank_no', 'string', '');
        $paytype     = $this->request->get('paytype', 'string', 'all');
        
        $addtype     = $this->request->get('addtype', 'int', 0);
        $source      = $this->request->get('source', 'int', 0);
        
        $paystime    = $this->request->get('paystime', 'string', '');
        $payetime    = $this->request->get('payetime', 'string', '');
	    $orderattribute =$this->request->get('orderattribute', 'int',0);

        $page_size = 10;
        $arr = array();
        
        if ($page && $page > 0) 
        {
            $page = $page;
        }
        else
        {
            $page = 1;
        }
        $_GET["state"] = $this->request->get('state', 'int', 4);
        $where = M\Orders::conditions($_GET);
        

        
        if ($ynp_no) 
        {
            $where.= " AND  o.ynp_sn = '{$ynp_no}'";
        }
        
        if ($bank_no) 
        {
            $where.= " AND o.bank_sn = '{$bank_no}'";
        }
        if($paytype != 'all') {
            $where .= " AND o.pay_type = '{$paytype}'";
        }
        if($source){
            switch ($source) {
                case '1':
                $where .= " AND o.source in(1,2)";  
                    break;
                case '2':
                $where .= " AND o.source in(3,4) ";  
                    break;
                 case '3':
                $where .= " AND o.source in(5,6) ";  
                    break;
                default:
                    break;
            }
          
        }
        if($addtype){
             switch ($addtype) {
                case '1':
                $where .= " AND o.source in (1,3,5) ";  
                    break;
                case '2':
                $where .= " AND o.source in (2,4,6)";  
                    break;
                default:
                    break;
            }
            
        }
        
        if(!$category && $maxcategory){
             $where .= " AND s.maxcategory = {$maxcategory} or p.maxcategory={$maxcategory} and {$where} ";
        }
        if ($category) 
        {  
             $where .= " AND s.category = {$category} or p.category={$category} and {$where} ";
        }
        
        if($orderattribute){
             switch ($orderattribute) {
                 case 1:
                 $where.=" AND u.is_broker=1 ";
                     break;
                 default:
                  $where.=" AND u.is_broker=0 ";
                     break;
             }
        }
       
        $sql="select count(*) as count from orders as o left join sell as s on o.sellid=s.id  left join purchase as p on o.purid=p.id left join users as u on o.puserid=u.id  where {$where} ";
        
        $count=$this->db->fetchOne($sql,2);
        $total = $count["count"];
        $offst = intval(($page - 1) * $page_size);
        $sql="select o.*,u.is_broker from orders as o left join sell as s on o.sellid=s.id left join purchase as p on o.purid=p.id left join users as u on o.puserid=u.id where {$where} ORDER BY o.addtime DESC limit {$offst} , {$page_size} ";
        $data=$this->db->fetchAll($sql,2);
     
        foreach ($data as $key => $value) {
            $data[$key]["purname"]=M\UsersExt::getusername($value['puserid']);
            $data[$key]["sname"]=M\UsersExt::getusername($value['suserid']);
        }
        
        $pages['total_pages'] = ceil($total / $page_size);
        $pages['current'] = $page;
        $pages['total'] = $total;
        $pages = new Pages($pages);
        $pages = $pages->show(3);
        $this->view->orderstate = Orders::$_orders_sell_state;
        $this->view->data = $data;
        $this->view->current = $page;
        $this->view->pages = $pages;
        
        //默认选中
        $this->view->paytype = $paytype;
        $this->view->pay_type = $this->PAY_TYPE;
        $this->view->statue = $this->request->get('state', 'int', 4);
        $this->view->sellname = $this->request->get('sellname', 'string', '');
        $this->view->purname = $this->request->get('purname', 'string', '');
        $this->view->order_sn = $this->request->get('order_sn', 'string', '');
        $this->view->stime = $this->request->get('stime', 'string', '');
        $this->view->etime = $this->request->get('etime', 'string', '');
        $cat[] = lCategory::ldDataName($this->request->get("maxcategory") , 1);
        $cat[] = lCategory::ldDataName($this->request->get("category") , 2);
        $this->view->bank_no = $bank_no;
        $this->view->ynp_no = $ynp_no;

        $this->view->addtype = $addtype;
        $this->view->sourcetype = $source;
        $this->view->paystime = $this->request->get('paystime', 'string', '');
        $this->view->payetime = $this->request->get('payetime', 'string', '');
        $this->view->orderattribute=$orderattribute;
        $this->view->add_type = M\Orders::$add_type;
        $this->view->source = M\Orders::$source;
        $this->view->cat_name = join(',', $cat);
    }
    /**
     * 订单详细
     * @param  [type] $id 订单id
     * @return
     */
    
    public function infoAction($id = 0, $p = 1) 
    {

        $order_sn = $this->request->get('order_sn', 'string', '');
        if($order_sn){
            $order = Orders::findFirstByorder_sn($order_sn);
        }else{
            $order = Orders::findFirstByid($id);
        }

        $pay_time = '';
        $dev_time = '';
        $dev_name = '';
        $Engineer_phone='';
        
        if (!$order) 
        {
            parent::msg('订单没有找到','/manage/Orders/index');
        }

        $pay_time = $order->pay_time ? date("Y-m-d H:i:s", $order->pay_time) : '' ;
        $orderlog = OrdersLog::find("order_id='{$order->id}'");
        
        if ($orderlog) 
        {
            
            foreach ($orderlog->toArray() as $key => $value) 
            {
                
                
                if ($value['state'] == '5') 
                {
                    $dev_time = date("Y-m-d H:i:s", $value['addtime']);
                }
            }
        }
        $dev = OrdersDelivery::findFirst("orderid='{$id}'");
       
        if ($dev) 
        {
            $dev_name = OrdersDelivery::$dev_name[$dev->deliverytype];
        }

        $OrderEngineer = OrderEngineer::findFirst("order_id='{$id}'");
        
        if ($OrderEngineer) {
            $Engineer=$OrderEngineer->toArray();
            $Engineer_phone =$Engineer['engineer_phone'] ;
        }       
        $this->view->Engineer_phone = $Engineer_phone;
          
        $this->view->dev_name = $dev_name;
        $this->view->pay_time = $pay_time;
        $this->view->dev_time = $dev_time;
        $this->view->puruser = Users::findFirstByid($order->puserid);
        $this->view->selluser = Users::findFirstByid($order->suserid);
        $this->view->order = $order;
        $this->view->pay_type = $this->PAY_TYPE;
        $this->view->orderlog = $orderlog;
        $this->view->orderstate = Orders::$_orders_sell_state;
        $this->view->goods_unit = Purchase::$_goods_unit;
        $this->view->page = $p;
    }
    /**
     *  编辑订单
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function editAction($id) 
    {
        $Orders = M\Orders::findFirstByid($id);
        $orderlog = M\OrdersLog::find();
        
        if (!$Orders) 
        {
            parent::msg('订单没有找到','/manage/Orders/index');
            // $this->flash->error("订单没有找到");
            // return $this->dispatcher->forward(array(
            //     "controller" => "Orders",
            //     "action" => "index"
            // ));
        }
        
        if (!empty($orderlog)) 
        {
            $this->view->orderlog = $orderlog;
        }
        else
        {
            $this->view->orderlog = array();
        }
        $this->view->order = $Orders;
        $this->view->orderstate = Orders::$_orders_sell_state;
    }
    /**
     * 改变订单相关状态
     * @return [type] [description]
     */
    public function updatestateAction() 
    { 
       
        $type = $this->request->getPost('type', 'string', '');
        $order_id = $this->request->getPost('order_id', 'int', 0);
        $content = $this->request->getPost('content', 'string', '');
        $order = Orders::findFirstByid($order_id);
        $order = new Orders();
        
        if (!$order || !$type || !method_exists($order, $type)) 
        {
            parent::msg('请下确操作订单状态修改！','/manage/Orders/index');
            // $this->flash->error("请下确操作订单状态修改！");
            // return $this->dispatcher->forward(array(
            //     "controller" => "Orders",
            //     "action" => "index"
            // ));
        }

        $this->db->begin();
        $flag=true;
        $payflag=false;
        try {

            $sql = " SELECT id FROM orders where id = '{$order_id}' for update ";
            $data = $this->db->fetchOne($sql,2);
            $a=$order->$type($order_id, $this->getUserID() , $this->getUsername() , $content, 1);
            $this->db->commit();
        } catch (\Exception $e) {
            $flag=false;
            $codes=$e->getMessage();
            $code['code'] = $e->getMessage();
            $code['line'] = $e->getLine();
            $code['file'] = $e->getFile();
            $code = implode('=>', $code);
            $time=date("Y-m-d",time());
            $logname = PUBLIC_PATH . "/log/{$time}_ADMIN_ORDER_CAOZUO.log";
            file_put_contents($logname , $code, FILE_APPEND);
            $this->db->rollback();
            if($type=="pay"&&$codes&&$codes=="nofound"){
                $payflag="没有在云农宝查到该订单";
            }
            if($type=="pay"&&$codes&&$codes=="nopay"){
                $payflag="该订单在云农宝没有支付记录";
            }
            
        }
        if($flag){
            Func::adminlog("更新订单状态{$type},{$order_id}",$this->session->adminuser['id']);
        }
        if($payflag){
            parent::msg($payflag,"/manage/orders/info/{$order_id}");
        }
        $this->response->redirect('/orders/info/' . $order_id)->sendHeaders();
    }
    /**
     * 设置价格
     * @return [type] [description]
     */
    public function editpriceAction() 
    {
        $order_id = $this->request->get('id', 'int', 0);
        $order = Orders::findFirstByid($order_id);
        
        if (!$order) 
        {
            die("此订单不存在");
        }
        $this->view->goods_unit = Purchase::$_goods_unit;
        $this->view->order = $order;
        $this->view->ajax = 1;
    }
    /**
     * 保存设置的价格
     * @return [type] [description]
     */
    public function savepriceAction() 
    {
        $oid = $this->request->getPost('oid', 'int', 0);
        $price = $this->request->getPost('price', 'float', 0.00);
        
        if (!$price) 
        {
            die("请填写价格！");
        }
        $order = Orders::findFirstByid($oid);
        
        if (!$order) 
        {
            die("此订单不存在！");
        }
        
        if ($order->state != 2) 
        {
            die("请正确操作确认订单！");
        }
        M\OrdersLog::insertLog($oid, 3, $this->getUserID() , $this->getUsername() , 1, $demo = '管理员修改价格确认订单');
        $order->state = 3;
        $order->price = $price;
        $order->total = $order->price * $order->quantity;
        
        if (!$order->save()) 
        {
            die("设置价格失败，请联系客服！");
        }
        Func::adminlog("设置订单{$oid}价格{$order->total}元",$this->session->adminuser["id"]);
        parent::msg('设置价格成功！',"/manage/orders/info/{$oid}",2);
        // $this->flash->error("设置价格成功！");
        // echo "<script>parent.parent.location.href='/manage/orders/info/{$oid}'</script>";
        // die;
    }
    /**
     * 设置佣金
     * @return [type] [description]
     */
    public function set_commissionAction()
    {
        $order_id = $this->request->get('id', 'int', 0);
        $content = $this->request->get('content','string','');
        $order = Orders::findFirstByid($order_id);
        if (!$order)
        {
            die("此订单不存在");
        }
        $is_edit = 0;
        $tpay = new L\TpayInterface(1);
        $query_trade = $tpay->query_trade($order->order_sn); #查询订单
        if($query_trade->is_success == 'T')
            $is_edit = 1;
        $this->view->is_edit = $is_edit;
        $this->view->order = $order;
        $this->view->content = $content;
        $this->view->ajax = 1;
    }
    /**
     * 保存设置佣金
     * @return [type] [description]
     */
    public function savecommissionAction()
    {
        $oid = $this->request->getPost('oid', 'int', 0);
        $content = urldecode($this->request->getPost('content', 'string',0));
        $party = $this->request->getPost('commission_party', 'int',0);
        $commission = $this->request->getPost('commission', 'float', 0.00);


        if (!$commission)
        {
            die("请填写佣金金额！");
        }
        $order = Orders::findFirstByid($oid);
        if ($party == 0 && $order->commission_party == 0)
        {
            die("请选择支付方！");
        }
        if (!$order)
        {
            die("此订单不存在！");
        }

        if ($order->state != 3)
        {
            die("请正确操作订单！");
        }
        if($party == 0 && $order->commission_party == 1 && $commission >= $order->total)
            die("佣金金额必须小于订单金额！");
        if($party == 2){ #采购方支付交易检测 以创建交易终止操作
            $tpay = new L\TpayInterface(1);
            $query_trade = $tpay->query_trade($order->order_sn); #查询订单
            if($query_trade->is_success == 'T')
                die("无法设置采购方支付佣金,支付交易已创建!");
        }

        if($party)$order->commission_party = $party;
        $order->commission = $commission;
        $saveMark = 1;
        if (!$order->save())
        {
            $saveMark = 0;
            echo("设置佣金失败，请联系客服！");
        }
        $strParty = $order->commission_party == 1?'供应方':'采购方';
        M\OrdersLog::insertLog($oid, $order->state, $this->getUserID() , $this->getUsername() , 1, $demo = "修改佣金（{$commission}元，{$strParty}）【{$content}】");
        if(!$saveMark)exit;
        Func::adminlog("修改佣金{$oid},将会支付佣金{$commission}元",$this->session->adminuser["id"]);
        parent::msg('设置佣金成功！',"/manage/orders/info/{$oid}",2);
        // $this->flash->error("设置佣金成功！");
        // echo "<script>parent.parent.location.href='/manage/orders/info/{$oid}'</script>";
        // die;
    }
    /**
     *  发货 选择发货方式
     * @return [type] [description]
     */
    
    public function setdevAction() 
    {
        $oid = $this->request->get('id', 'int', 0);
        $content = $this->request->get('content');
        $this->view->orderid = $oid;
        $this->view->dev_name = OrdersDelivery::$dev_name;
        $this->view->content = $content;
        $this->view->ajax = 1;
    }
    /**
     * 保存发货方式
     * @return [type] [description]
     */
    public function savedevAction() 
    {
        $type = "send";
        $id = $this->request->get('orderid', 'string', '');
        $fahuo = $this->request->get('fahuo', 'string', '');
        $wuliu_sn = $this->request->get('wuliu_sn', 'string', '');
        $driver_name = $this->request->get('driver_name', 'string', '');
        $driver_phone = $this->request->get('driver_phone', 'string', '');
        $name = $this->request->get('name', 'string', '');
        $mobile = $this->request->get('mobile', 'string', '');
        $content = $this->request->get('content', 'string', '');
        $wuliugongsi = $this->request->get('wuliu_gongsi', 'string', '');
        $order = Orders::findFirstByid($id);
        
        if (!$order || !$type || !method_exists($order, $type)) 
        {
            die("请正确操作订单状态修改！");
        }
        $order->$type($id, $this->getUserID() , $this->getUsername() , $content, 1);
        $OrdersDelivery = new OrdersDelivery();
        $OrdersDelivery->deliverytype = $fahuo;
        
        if ($fahuo == 1) 
        {
            $OrdersDelivery->deliveryname = $wuliugongsi;
        }
        else
        {
            $OrdersDelivery->deliveryname = OrdersDelivery::$dev_name[$fahuo];
        }
        $OrdersDelivery->drivername = $driver_name;
        $OrdersDelivery->driverphone = $driver_phone;
        $OrdersDelivery->name = $name;
        $OrdersDelivery->mobile = $mobile;
        $OrdersDelivery->orderid = $id;
        $OrdersDelivery->delivery_sn = $wuliu_sn;
        
        if ($OrdersDelivery->save()) 
        {
            Func::adminlog("订单发货{$id}",$this->session->adminuser["id"]);
           //  echo "<script>alert('订单发货成功');location.href='/manage/orders/info/{$id}';</script>";die;
            parent::msg('订单发货成功！',"/manage/orders/info/{$id}",2);
        }
    }
    /**
     * 显示消息
     * @return [type] [description]
     */
    public function showmsgAction() 
    {
        $data = base64_decode($this->request->get("data") , 1);
        
        if (isset($data) && $data != '') 
        {
            parse_str($data, $_GET);
        }
        
        if (isset($_GET["msg"]) && $_GET["msg"]) 
        {
            $this->view->content = $_GET["msg"];
        }
        else
        {
            $this->view->content = '';
        }
        
        if (isset($_GET["ref"]) && $_GET["ref"]) 
        {
            $this->view->url = $_GET["ref"];
        }
        else
        {
            $this->view->url = '';
        }
        $this->view->is_ajax = true;
    }

    public function changeestimeAction($order_id,$type){
      
      $order = Orders::findFirstByid($order_id);
      if(!$order){
          die("订单不存在");
      }
      $this->view->str="预计送货时间";
      $this->view->orders=$order;
    }
    public function changeeetimeAction($order_id,$type){
      
      $order = Orders::findFirstByid($order_id);
      if(!$order){
          die("订单不存在");
      }
      $this->view->str="预约采摘时间";
      $endtime=date("Y-m-d",$order->except_shipping_time);
      $this->view->orders=$order;
      $this->view->endtime=$endtime;
    }
    public function changestimeproAction(){
         
       
        $order_id = $this->request->getPost("order_id", 'int', '');
        $except_shipping_time = $this->request->getPost("except_shipping_time", 'string','');
        $order = Orders::findFirstByid($order_id);
        if(!$order){
            die("订单不存在");
        }
        $order->except_shipping_time=strtotime($except_shipping_time);
       
        if(!$order->save()){
            die("修改失败");
        }
        Func::adminlog("设置发货时间{$order_id}",$this->session->adminuser["id"]);
        $this->flash->error("<p style='algin:center;margin-top:75px'>设置发货时间成功！</p>");
        echo "<script>parent.mainFrame.location.reload();</script>";die;
        //echo "<script>parent.parent.location.href='/manage/orders/info/{$order_id}'</script>";
    }

}
