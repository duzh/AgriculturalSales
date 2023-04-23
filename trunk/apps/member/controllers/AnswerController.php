<?php
namespace Mdg\Member\Controllers;
use Mdg\Models as M;
use Mdg\Models\GoodsComments as Goodscomments;
use Mdg\Models\Sell as Sell;
use Lib\Pages as Pages;
class AnswerController extends ControllerMember
{
	public function listAction(){
		$user_id = $this->getUserID();
		//$user_id = '492492';
		$page = $this->request->get('p', 'int', 1);
		$page = intval($page)>0 ? intval($page) : 1;
		$page_size = 10;
		if( !$user_id ){
			echo '<script>alert("登录超时");location.href="/member/login/index/";</script>';exit;
		}
		$total = Goodscomments::count("user_id='{$user_id}'");
		$offst = intval(($page - 1) * $page_size);
		$goodscomments = Goodscomments::find(" user_id='{$user_id}' ORDER BY add_time DESC limit {$offst} , {$page_size}")->toArray();
		$pages['total_pages'] = ceil($total / $page_size);
		$pages['current'] = $page;
		$pages['total'] = $total;
		$pages = new Pages($pages);
		$pages = $pages->show(2);
		$data = array();
		foreach($goodscomments as $key=>$val){
			$tr = '';
			$goodscomments[$key]['is_status'] = Goodscomments::$_is_check[$val['is_check']];
			$goodscomments[$key]['addtime'] = date('Y/m/d',$val['add_time']);
			for($i=1;$i<=5;$i++){
				if ($val['decribe_score'] >= $i ){
					$tr .='<a href="javascript:;" class="star active"></a>';
				}else{
					$tr .='<a href="javascript:;" class="star"></a>';
				}
			}
			$goodscomments[$key]['decribe_scores'] = $tr;
			$goodscomments[$key]['scores'] = Goodscomments::$_decribe_score[$val['decribe_score']];
			if($val['sell_id']){
				$result = Sell::findFirst('id='.$val['sell_id']);
			}else{
				$result = M\Purchase::findFirst("id = '{$val['purchase_id']}' and state = 1 and is_del = 0 ");
			}
			if (isset($result->thumb)){
				$goodscomments[$key]['thumb'] = $result->thumb ? IMG_URL . $result->thumb : "http://static.ync365.com/mdg/images/detial_b_img.jpg";
			}else{
				$goodscomments[$key]['thumb'] = "http://static.ync365.com/mdg/images/detial_b_img.jpg";
			}
		}
		//echo "<pre>";
		//var_dump($goodscomments);exit;
		$this->view->title = '个人中心-我评价过的商品-丰收汇';
		$this->view->p=$page;
		$this->view->total_count = ceil($total / $page_size);
		$this->view->data = $goodscomments;
		$this->view->pages = $pages;
	}

	public function delAction(){
	    $id = $this->request->get('id', 'int', 0);
		if(!$id){
			echo '<script>alert("错误参数");location.href="/member/answer/list";</script>';exit;
		}
		$user_id = $this->getUserID();		
		if( !$user_id ){
		   echo '<script>alert("登录超时");location.href="/member/login/index/";</script>';exit;		
		}
		$goodscomments = Goodscomments::findFirst(" id ='{$id}' and  user_id='{$user_id}'");
		if (!$goodscomments){
			echo '<script>alert("此评论已经删除");location.href="/member/answer/list";</script>';exit;
		}
		$goodscomments->is_check=3;
		if(!$goodscomments->save()){
			echo '<script>alert("评论删除失败");location.href="/member/answer/list";</script>';exit;
		}
		$rs = array(
			'state' => true,
			'msg' => '删除成功！'
		);
	        die(json_encode($rs));
	}
}
?>