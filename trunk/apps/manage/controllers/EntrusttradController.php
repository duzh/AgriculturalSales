<?php
namespace Mdg\Manage\Controllers;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Mdg\Models as M;
use Mdg\Models\EntrustOrder as EntrustOrder;
use Mdg\Models\Bank as Bank;
use Mdg\Models\Category as Category;
use Mdg\Models\EntrustOrderDetail as EntrustOrderDetail;
use Mdg\Models\EntrustOrdersLog as EntrustOrdersLog;
use Lib as L;
use Lib\Func as Func;
use Lib\Pages as Pages;
use Lib\Category as lCategory;

class EntrusttradController extends ControllerMember
{
    /*委托交易首页列表与查询*/
    public function indexAction() 
    {
        $page         		= $this->request->get('p', 'int', 1);
        $order_status 		= $this->request->get('order_status', 'string', '3');
        $maxcategory  		= $this->request->get('maxcategory', 'int', 0);
        $category     		= $this->request->get('category', 'int', 0);
        $order_detail_sn 	= trim($this->request->get('order_detail_sn', 'string', ''));
        $order_parent_sn 	= trim($this->request->get('order_parent_sn', 'string', ''));
        $sell_user_name 	= trim($this->request->get('sell_user_name', 'string', ''));
        $buy_user_name 		= trim($this->request->get('buy_user_name', 'string', ''));
        $bank_sn 			= trim($this->request->get('bank_sn', 'string', ''));
        $pay_type 			= $this->request->get('pay_type', 'string', 'all');
        /*$order_type     	= $this->request->get('order_type', 'int', 0);
         $order_canal  		= $this->request->get('order_canal', 'int', 0); */
        $ynp_sn 			= trim($this->request->get('ynp_sn', 'string', ''));
        $addstime 			= $this->request->get('addstime', 'int', '');
        $addetime			= $this->request->get('addetime', 'int', '');
		$paystime 			= $this->request->get('paystime', 'int', '');
        $payetime			= $this->request->get('payetime', 'int', '');
        $page_size = 10;
        $where = " 1=1 ";
        
        if ($page && $page > 0) 
        {
            $page = $page;
        }
        else
        {
            $page = 1;
        }
        /*子订单表中字段*/
        
        if ($order_status != 'all') 
        {
            $where.= " and a.order_status='{$order_status}'";
        }
        
        if ($maxcategory) 
        {
            $where.= " and a.category_id_one ='{$maxcategory}'";
        }
        
        if ($category) 
        {
            $where.= " and a.category_id_two ='{$category}'";
        }
        
        if ($order_detail_sn) 
        {
            $where.= " and a.order_detail_sn ='{$order_detail_sn}'";
        }
        
        if ($order_parent_sn) 
        {
            $where.= " and a.order_parent_sn ='{$order_parent_sn}'";
        }
        
        if ($sell_user_name) 
        {
            $where.= " and a.sell_user_name ='{$sell_user_name}'";
        }
        /*父订单表中字段*/
        
        if ($buy_user_name) 
        {
            $where.= " and b.buy_user_name ='{$buy_user_name}'";
        }
        
        if ($bank_sn) 
        {
            $where.= " and b.bank_sn ='{$bank_sn}'";
        }
        
        if ($pay_type != 'all') 
        {
            $where.= " and b.pay_type ='{$pay_type}'";
        }
        /* if($order_type){
         $where.=" and order_type ={$order_type}";
        }
        if($order_canal){
         $where.=" and order_canal ={$order_canal}";
        }*/
        
        if ($ynp_sn) 
        {
            $where.= " and b.ynp_sn ='{$ynp_sn}'";
        }
        
        if ($addstime && $addetime) 
        {
            $where.= " and b.add_time between '" . strtotime($addstime) . "' and '" . strtotime($addetime) . "'";
        }
		if ($paystime && $payetime) 
        {
            $where.= " and b.pay_time between '" . strtotime($paystime) . "' and '" . strtotime($payetime) . "'";
        }
		
        $sql = "select a.order_detail_id,a.order_detail_sn,a.order_parent_sn,a.order_status,a.category_id_one,a.category_id_two,a.sell_user_name,a.goods_name,a.goods_amount,a.subsidy_amount,b.order_time,b.buy_user_name,b.bank_sn,b.pay_type,b.ynp_sn ,b.pay_time from entrust_order_detail a left join entrust_order b on (a.order_parent_id = b.order_id) where" . $where;
        $total = count($this->db->fetchall($sql));
        $offst = intval(($page - 1) * $page_size);
        $data = $this->db->fetchall($sql . " ORDER BY order_detail_id DESC limit {$offst} , {$page_size} ");
        $pages['total_pages'] = ceil($total / $page_size);
        $pages['current'] = $page;
        $pages['total'] = $total;
        $pages = new Pages($pages);
        $pages = $pages->show(1);
        $this->view->orderstate 	= EntrustOrder::$_order_status;
        $this->view->paytypearr 	= EntrustOrder::$_pay_type;
        $this->view->data 			= $data;
        $this->view->current 		= $page;
        $this->view->pages 			= $pages;
        //默认选中
        $this->view->statue 			= $this->request->get('order_status', 'int', 3);
        $this->view->order_detail_sn 	= $order_detail_sn;
        $this->view->order_parent_sn 	= $order_parent_sn;
        $this->view->sell_user_name 	= $sell_user_name;
        $this->view->buy_user_name 		= $buy_user_name;
        $this->view->bank_sn 			= $bank_sn;
        $this->view->pay_type 			= $pay_type;
        $this->view->ynp_sn 			= $ynp_sn;
        $this->view->addstime 			= $addstime;
        $this->view->addetime 			= $addetime;
		$this->view->paystime 			= $paystime;
        $this->view->payetime 			= $payetime;
		
        $cat[] = lCategory::ldDataName($this->request->get("maxcategory") , 1);
        $cat[] = lCategory::ldDataName($this->request->get("category") , 2);
        $this->view->cat_name = join(',', $cat);
    }
    /*子订单详情*/
    public function childorderinfoAction($id = 0, $p = 1) 
    {	
        $order = EntrustOrderDetail::findFirst(" order_detail_id= '{$id}'");
        
        if (!$order) 
        {
            parent::msg('订单没有找到','/manage/entrusttrad/index');
            //$this->flash->error("订单没有找到");
            // return $this->dispatcher->forward(array(
            //     "controller" => "entrusttrad",
            //     "action" => "index"
            // ));
        }
        $orderlog 				= EntrustOrdersLog::find(" order_sn= '{$order->order_detail_sn}' ");
		
        $this->view->data 			= $order;
		/*银行名称*/
		$this->view->bankname		=Bank::selectByCode($order->user_bank_name);
		/*分类名称*/
		$this->view->categorynameone=Category::selectBytocateName($order->category_id_one);
		$this->view->categorynametwo=Category::selectBytocateName($order->category_id_two);
		
        $this->view->orderlog 		= $orderlog;
        $this->view->orderstate 	= EntrustOrder::$_order_status;
        $this->view->paytypearr 	= EntrustOrder::$_pay_type;
        $this->view->page 			= $p;
    }
    /*
	待收货状态时执行，点击后子订单状态变为“交易完成”，同时验证此状态是否是父订单的最小状态，如果是则变更父订单状态为交易完成
	*/
    public function updatechildstateAction() 
    {
        $id = $this->request->getPost('order_id', 'int', 0);
        $content = $this->request->getPost('content', 'string', '');
        $EntrustOrderDetail = EntrustOrderDetail::findFirst(" order_detail_id= '{$id}'");
		$this->db->begin();
		try{
			//$status = EntrustOrder::ORDER_STATUS_PAY;|| $EntrustOrderDetail->order_status != $status//订单状态 5 已发货
			if (!$EntrustOrderDetail||$EntrustOrderDetail->order_status !=5) 
			{
				throw new \Exception('the order  is not found or order status is wrong');
			}	
									
			$EntrustOrder= new EntrustOrder();	
			$EntrustOrder->ModyChildState($id,$content);
			$loginfo='操作子订单确认收获';
			Func::adminlog($loginfo,$this->session->adminuser['id']);
			$this->db->commit();
		}catch(\Exception $e){
            $this->db->rollback();
          //var_dump($e->getMessage());
          // exit;
        }
        $this->response->redirect("entrusttrad/index")->sendHeaders();
    }
	
	 /*父订单详情*/
	public function parentorderinfoAction($sn = 0, $p = 1){
		
		$EntrustOrder = EntrustOrder::findFirst(" order_sn = '{$sn}'");
        if (!$EntrustOrder) 
        {
            parent::msg('订单没有找到','/manage/entrusttrad/index');
 
        }
		$EntrustOrderDetail = EntrustOrderDetail::find(" order_parent_sn = '{$EntrustOrder->order_sn}'")->toArray();
		
		if($EntrustOrderDetail)
		{	
			$numbernum=0;$amountnum=0;
			for($i=0;$i<count($EntrustOrderDetail);$i++){				
			$EntrustOrderDetail[$i]['bankname']	=Bank::selectByCode($EntrustOrderDetail[$i]['user_bank_name']);
			$numbernum 							=$numbernum+$EntrustOrderDetail[$i]['goods_number'];
			$amountnum 							=$amountnum+$EntrustOrderDetail[$i]['goods_amount'];
			}
		}	
		
        $orderlog 						= EntrustOrdersLog::find(" order_sn= '{$EntrustOrder->order_sn}' ");	
        $this->view->data 				= $EntrustOrder;
		$this->view->categorynameone	=Category::selectBytocateName($EntrustOrder->category_id_one);
		$this->view->categorynametwo	=Category::selectBytocateName($EntrustOrder->category_id_two);
		$this->view->dataDetail 		= $EntrustOrderDetail;
		$this->view->numbernum			= $numbernum;
		$this->view->amountnum 			= $amountnum;
        $this->view->orderlog 			= $orderlog;
        $this->view->orderstate 		= EntrustOrder::$_order_status;
        $this->view->paytypearr 		= EntrustOrder::$_pay_type;
        $this->view->page 				= $p;
		
	}
	/*修改父订单详情中的状态*/
	 public function updatestateAction() 
    {
		$type = $this->request->getPost('type', 'string', '');
        $sn = $this->request->getPost('order_sn', 'string', '');
        $content = $this->request->getPost('content', 'string', '');
		
        $EntrustOrder = EntrustOrder::findFirst(" order_sn = '{$sn}'");		
		 $this->db->begin();
		try{
			if (!$EntrustOrder ||!$type) 
			{
				throw new \Exception('the order status is wrong');				
			}	
		
			$EntrustOrder= new EntrustOrder();	
			//判断操作状态执行相关操作
			$EntrustOrder->ModyOrderState($sn,$content,$type);	
			switch ($type)
			{
			case 'closeorder':
					$loginfo='关闭父订单';
					break;
			case 'payorder':
					$loginfo='支付父订单';
					break;
			case 'delivery':
					$loginfo='确认收货';
					break;					
			default:
					break;
			}
			Func::adminlog($loginfo,$this->session->adminuser['id']);
			$this->db->commit();
		}catch(\Exception $e){
            $this->db->rollback();
          //var_dump($e->getMessage());
          // exit;
        }
        $this->response->redirect('/entrusttrad/index')->sendHeaders();
    }
}
?>