<?php
namespace Mdg\Frontend\Controllers;
use Mdg\Models as M;
use Mdg\Models\Orders as Orders;
use Mdg\Models\Purchase as Purchase;
use Lib\Pages as Pages;
class OrdersController extends ControllerBase
{

	public function orderslistAction() 
	{
		$p			= $this->request->get('p', 'int', 1);
		$p = intval($p) > 0 ? intval($p) : 1;
		$type		= $this->request->get('type', 'int' ,0);
		$sell_id		= $this->request->get('sell_id', 'int' , '');
		$is_page		= $this->request->get('is_page', 'int' ,1);
		$page_size	= $this->request->get('page_size', 'int' ,5);

		if ( !$sell_id ){
			$data['item'] = '';
			$data['page'] = '';
			$data['type'] = 'orders';
			die(json_encode($data)); 
		}
		$where = " sellid = '{$sell_id}' AND state > 3 ";
		$total = Orders::count($where);
		$total_page=ceil($total / $page_size);
		if($p>$total_page&&$total!=0){
			$p=$total_page;
		}
		$offst = intval(($p - 1) * $page_size);
		$order_data = Orders::find($where . " ORDER BY addtime DESC limit {$offst} , {$page_size}")->toArray();
		$total_score = '';
		foreach($order_data as $k=>$v){
			$tr = '<tr height="40">';
			$tr .='<td><em>'.$v['purname'].'</em></td>';
			$tr .='<td align="center">'.$v['quantity'].Purchase::$_goods_unit[$v['goods_unit']].'</td>';
			$tr .='<td align="center">'.date('Y/m/d H:i',$v['addtime']).'</td></tr>';
			$order_data[$k]['tr_td'] = $tr;
		}
		$pages['total_pages'] = ceil($total / $page_size);
		$pages['current'] = $p;
		$pages['total'] = $total;
		$pages['method'] = 'ajax';
		$pages['ajax_type'] = 'orders';
		if ($is_page>0){
			$ajax_func_name = 'getDatas';
			$pages['type'] = 2;
		}else{
			$ajax_func_name = 'getData';
			$pages['type'] = 1;
		}
		$pages['ajax_func_name'] = $ajax_func_name;
		$pages = new Pages($pages);
		$pages = $pages->show(6);
		$data['item'] = $order_data;
		$data['page'] = $pages;
		$data['type'] = 'orders';
		die(json_encode($data)); 
	}
}
?>