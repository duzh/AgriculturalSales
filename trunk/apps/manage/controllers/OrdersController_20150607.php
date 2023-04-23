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

class OrdersController extends ControllerMember
{
    
    public function indexAction() 
    {

        $page = $this->request->get('p', 'int', 1);
        $category = $this->request->get('category', 'int', 0);
        $maxcategory = $this->request->get('maxcategory', 'int', 0);
        $page_size = 10;
        $arr = array();
        if($page&&$page>0){
            $page=$page;
        }else{
            $page=1;
        }
        $_GET["state"] = $this->request->get('state', 'int', 4);
        $where = M\Orders::conditions($_GET);

        if(!$category&&$maxcategory){
            $where.= M\Orders::purmaxcate(intval($maxcategory));
        }
        if($category){
            $where.= M\Orders::purcate(intval($category));
        }
        
        $total = M\Orders::count($where);
        $offst = intval(($page - 1) * $page_size);
      //  print_R($where);exit;
        $data = M\Orders::find($where . " ORDER BY addtime DESC limit {$offst} , {$page_size} ");
        $pages['total_pages'] = ceil($total / $page_size);
        $pages['current'] = $page;
        $pages['total'] = $total;
        $pages = new Pages($pages);
        $pages = $pages->show(1);
        $this->view->orderstate = Orders::$_orders_sell_state;
        $this->view->data = $data;
        $this->view->current = $page;
        $this->view->pages = $pages;
        //默认选中
        $this->view->statue = $this->request->get('state', 'int', 4);
        $this->view->sellname = $this->request->get('sellname', 'string', '');
        $this->view->purname = $this->request->get('purname', 'string', '');
        $this->view->order_sn = $this->request->get('order_sn', 'string', '');
        $this->view->stime = $this->request->get('stime', 'string', '');
        $this->view->etime = $this->request->get('etime', 'string', '');
        $cat[] = lCategory::ldDataName($this->request->get("maxcategory") , 1);
        $cat[] = lCategory::ldDataName($this->request->get("category") , 2);
        $this->view->cat_name = join(',', $cat);
    }
    /**
     * 订单详细
     * @param  [type] $id 订单id
     * @return
     */
    
    public function infoAction($id = 0, $p = 1) 
    {
        $order = Orders::findFirstByid($id);
        $pay_time = '';
        $dev_time = '';
        $dev_name = '';
        
        if (!$order) 
        {
            $this->flash->error("订单没有找到");
            return $this->dispatcher->forward(array(
                "controller" => "Orders",
                "action" => "index"
            ));
        }
        $orderlog = OrdersLog::find("order_id='{$id}'");
        
        if ($orderlog) 
        {
            
            foreach ($orderlog->toArray() as $key => $value) 
            {
                
                if ($value['state'] == '4') 
                {
                    $pay_time = date("Y-m-d H:i:s", $value['addtime']);
                }
                
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
        $this->view->dev_name = $dev_name;
        $this->view->pay_time = $pay_time;
        $this->view->dev_time = $dev_time;
        $this->view->puruser = Users::findFirstByid($order->puserid);
        $this->view->selluser = Users::findFirstByid($order->suserid);
        $this->view->order = $order;
        $this->view->orderlog = $orderlog;
        $this->view->orderstate = Orders::$_orders_sell_state;
        $this->view->goods_unit = Purchase::$_goods_unit;
        $this->view->page = $p;
    }
    
    public function editAction($id) 
    {
        $Orders = M\Orders::findFirstByid($id);
        $orderlog = M\OrdersLog::find();
        
        if (!$Orders) 
        {
            $this->flash->error("订单没有找到");
            return $this->dispatcher->forward(array(
                "controller" => "Orders",
                "action" => "index"
            ));
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
    
    public function updatestateAction() 
    {
        $type = $this->request->getPost('type', 'string', '');
        $order_id = $this->request->getPost('order_id', 'int', 0);
        $content = $this->request->getPost('content', 'string', '');
        $order = Orders::findFirstByid($order_id);
        $order = new Orders();
       
        if (!$order || !$type || !method_exists($order, $type)) 
        {
            $this->flash->error("请下确操作订单状态修改！");
            return $this->dispatcher->forward(array(
                "controller" => "Orders",
                "action" => "index"
            ));
        }
        $order->$type($order_id, $this->getUserID() , $this->getUsername() , $content, 1);
        $this->response->redirect('/orders/info/' . $order_id)->sendHeaders();
    }
    
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
        $this->flash->error("设置价格成功！");
        echo "<script>parent.parent.location.href='/manage/orders/info/{$oid}'</script>";
        die;
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
            $this->flash->error("<span style='text-align:center; display:block; font-size:16px; margin-top:90px;'>发货成功！</span>");
            echo "<script>parent.mainFrame.location.reload();;</script>";die;
        }
    }
}
