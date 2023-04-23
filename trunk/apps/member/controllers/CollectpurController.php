<?php
namespace Mdg\Member\Controllers;
use Mdg\Models as M;
use Lib as L;
class CollectpurController extends ControllerMember
{
	/**
	 * 收藏采购列表
	 */
	public function indexAction(){
		
		// 获取参数
		$userId		= isset($this->session->user['id']) ? $this->session->user['id'] : 0;
		$page		= $this->request->get( 'p' , 'int' , 1 );
		$total		= $this->request->get( 'total' , 'int' , 1 );
		$page		= ($page>0) ? intval($page) : 1 ;
		if($page>$total) $page=$total;
		$pageSize	= 10;
		$offst		= ($page-1) * $pageSize;
		
		// 参数校验
		if(!$userId) {
			echo "<script type='text/javascript'>window.location.href='/member/login/index/'</script>";exit;
		}
		$purchaseList		= M\CollectPurchase::getPurchaseList($this->db,$page,$pageSize,$offst,$userId);
		$this->view->purList= $purchaseList;
		$this->view->p= $page;
		$this->view->pageSize= $pageSize;
		$this->view->title	= '个人中心-收藏采购';	
	}
	/**
	 *	取消收藏
	 */
	public function collCanselAction(){
		
		//获取参数		
		$userId	= isset($this->session->user['id']) ? $this->session->user['id'] : 0;
		$id		= isset($_REQUEST['id']) ? intval( $_REQUEST['id'] ) : 0;
		
		// 校验参数
		if( !$userId ) {
			echo json_encode(array('code'=>0,'result'=>'请登录'));
			exit;
		}
		if( !M\CollectPurchase::findFirstByid($id) ) {
			echo json_encode(array('code'=>1,'result'=>'参数有误'));
			exit;
		} 
		if($this->db->query("delete from collect_purchase where `collect_uid`={$userId} and `id`={$id}")){
			echo json_encode(array('code'=>2,'result'=>'取消成功'));
			exit;
		} else {
			echo json_encode(array('code'=>3,'result'=>'取消失败'));
			exit;
		}
	}
}   