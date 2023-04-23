<?php
namespace Mdg\Frontend\Controllers;
use Mdg\Models as M;
use Mdg\Models\GoodsComments as Goodscomments;
use Lib\Pages as Pages;
use Lib as L;
class GoodscommentsController extends ControllerBase
{
	public function indexAction(){
	
	}

	/**
	* 前台商品获取评论
	*/
	public function GoodsCommentsAction(){
		$p			= $this->request->get('p', 'int', 1);
		$type		= $this->request->get('type', 'int' ,0);
		$sell_id		= $this->request->get('sell_id', 'int' , '');
		$is_page		= $this->request->get('is_page', 'int' ,1);
		$page_size	= $this->request->get('page_size', 'int' ,5);
		$p = intval($p) > 0 ? intval($p) : 1;

		if ( !$sell_id ){
		    $data['item'] = '';
			$data['page'] = '';
			$data['type'] = 'comments';
	     	die(json_encode($data)); 
		}
		$where = " sell_id = '{$sell_id}' AND is_check = 1 ";
		$total = Goodscomments::count($where);
		$total_page=ceil($total / $page_size);
        if($p>$total_page&&$total!=0){
			$p=$total_page;
		}
		$offst = intval(($p - 1) * $page_size);
		$sell_data = Goodscomments::find($where . " ORDER BY add_time DESC limit {$offst} , {$page_size}")->toArray();
		$total_score = '';
		foreach($sell_data as $k=>$v){
			$tr = '<tr height="66">';
			$tr .='<td><em>'.$v['comment'].'</em></td>';
			$tr .='<td align="center"><div class="stars f-oh">';
			for($i=1;$i<=5;$i++){
				if ($v['decribe_score'] >= $i ){
					$tr .='<span class="star active"></span>';
				}else{
					$tr .='<span class="star"></span>';
				}
			}
			$tr .='</div></td>';
			$tr .='<td align="center">'.date('Y/m/d H:i',$v['add_time']).'</td>';
			if ($v['anonym_flag'] == 1){
				$v['user_name'] = '匿名';
			}
			$tr .='<td align="center">'.$v['user_name'].'</td></tr>';
			$sell_data[$k]['tr_td'] = $tr;
			$total_score += $v['decribe_score'];
		}
		$pages['total_pages'] = ceil($total / $page_size);
		$pages['current'] = $p;
		$pages['total'] = $total;
		$pages['method'] = 'ajax';
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
		$data['item'] = $sell_data;
		$data['page'] = $pages;
		$data['type'] = 'comments';

		die(json_encode($data)); 

	}
	
	/**
	* 用户提交评论
	*/
	public function createAction(){
		 // $this->session->user 获取用户登录信息
		// category_id 分类ID
		// 
	
		$sell_id		= $this->request->getPost('sellid', 'int', 0);
		$purchase_id	= $this->request->getPost('purid', 'int', 0);
		$comment	= $this->request->getPost('comment', 'string', '');
		$decribe_score = $this->request->getPost('decribe_score', 'int', 0);
		$anonym_flag	= $this->request->getPost('anonym_flag', 'int', 0);
        $orderid	= $this->request->getPost('orderid', 'int', 0);
		$user_id		= isset($this->session->user['id']) ? $this->session->user['id'] : 0;

		if( !$user_id ){
			die(json_encode(array('status'=>'1001','msg'=>'用户超时，请重新登录！')));
		}

		if (!$user_info = M\Users::getFshUsers($user_id)){
			die(json_encode(array('status'=>'1003','msg'=>'非法请求，请重新登录评分')));
		}
		if (!$sell_id && !$purchase_id){
			die(json_encode(array('status'=>'1004','msg'=>'非法请求，请重新登录评分')));
		}

		if(!$comment){
			die(json_encode(array('status'=>'1006','msg'=>'请填写内容')));
		}
		$sell_info = M\Sell::getSellInfo($sell_id);
		$purchase = M\Purchase::findFirst("id='{$purchase_id}'");

		$title = isset($sell_info->title) ? $sell_info->title : (isset($purchase->title) ? $purchase->title : '');
		$category_id = isset($sell_info->category) ? $sell_info->category : ( isset($purchase->category) ? $purchase->category : 0 );
		$info = M\UsersExt::getuserext($user_id);
		$user_info = $user_info->toArray();
		$user_info['realname'] = $info->name;

		$Goodscomments = new M\GoodsComments();
		$Goodscomments->sell_id		= $sell_id;
		$Goodscomments->user_name	= $user_info['realname'];
		$Goodscomments->user_id		= $user_id;
		$Goodscomments->comment		= $comment;
		$Goodscomments->add_time		= CURTIME;
		$Goodscomments->last_update_time = CURTIME;
		$Goodscomments->is_check		= 0;
		$Goodscomments->goods_name	= $title;
		$Goodscomments->decribe_score	= $decribe_score;
		$Goodscomments->anonym_flag	= $anonym_flag;
		$Goodscomments->category_id	= $category_id;
		$Goodscomments->purchase_id	= $purchase_id;
        $Goodscomments->order_id	= $orderid;
		// print_R($Goodscomments->toArray());exit;
		if(!$Goodscomments->save()){
			die(json_encode(array('status'=>'1007','msg'=>'评价失败，请重新评价')));
		}
		die( json_encode( array('status'=>'1000','msg'=>'评价成功') ) );
	
	}

	public function getGoodsInfoAction(){
		$sellid = $this->request->getPost('sellid', 'int', '0');
		$purid = $this->request->getPost('purid', 'int', '0');
		$user_id	= isset($this->session->user['id']) ? $this->session->user['id'] : 0;
		if( !$user_id ){
			die(json_encode(array('status'=>'1001','msg'=>'用户超时，请重新登录！')));
		}
		if (!$user_info = M\Users::getFshUsers($user_id)){
			die(json_encode(array('status'=>'1002','msg'=>'用户超时，请重新登录！')));
		}

		if (!$sellid && !$purid ){
			die(json_encode(array('status'=>'1003','msg'=>'非法请求，请重新登录评分')));
		}
		$data = array();
		if($sellid){
			$result = M\Sell::findFirst(" id = '{$sellid}' ");
			if (!$result){
				die(json_encode(array('status'=>'1004','msg'=>'错误的商品')));
			}
		}else{
			$result = M\Purchase::findFirst("id = '{$purid}' and state = 1 and is_del = 0 ");
			if (!$result){
				die(json_encode(array('status'=>'1004','msg'=>'错误的商品')));
			}
			$result->uname = $result->username;
		}
		$data['title']	= $result->title;
		$data['uname'] = $result->uname;
		$data['sellid']	= $sellid;
		$data['purid']	= $purid;
		if (isset($result->thumb)){
			$data['thumb'] = $result->thumb ? IMG_URL . $result->thumb : "http://static.ync365.com/mdg/images/detial_b_img.jpg";
		}else{
			$data['thumb'] = "http://static.ync365.com/mdg/images/detial_b_img.jpg";
		}
		die(json_encode(array('status'=>'1000','data'=>$data)));
	}

}
?>