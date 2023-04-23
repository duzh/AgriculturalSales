<?php
namespace Mdg\Frontend\Controllers;
use Mdg\Models as M;
class CollectController extends ControllerBase
{
	/**
     *	收藏采购
	 *	@param
	 *	@return array
     */
	public function collectAction() {
		
		// 获取参数
		$collectUid	= $this->session->user['id'];
		$collectName= $this->session->user['name'];
		$purchaseId	= intval( $_REQUEST['purchase_id'] );
		
		// 参数校验
		if ( !$collectUid ) {
			echo json_encode(array('code'=>0,'result'=>'请登录'));
			exit;
		}
		// 判断用户是否已收藏
		$collect	= M\CollectPurchase::collect( $purchaseId , $collectUid );
		if ( $collect ) {
			return json_encode(array('code'=>1,'result'=>'已收藏'));
			exit;
		}
		// 添加收藏
		$purchase	= M\CollectPurchase::savePurchase( $purchaseId );
		if ( $purchase ) {
			return json_encode(array('code'=>2,'result'=>'收藏成功'));
			exit;
		} else {
			return json_encode(array('code'=>3,'result'=>'收藏失败'));
			exit;
		}
		
	}
	/**
     *	供应收藏
	 *	@param
	 *	@return array
     */
	public function sellAction() {
		
		// 获取参数
		$userId		= $this->session->user['id'];
		$userName	= $this->session->user['name'];
		$purchaseId	= intval( $_REQUEST['sell⁯_id'] );
		
		
	}
	/**
     *	农村采购
	 *	@param
	 *	@return array
     */
	public function storeAction() {
		
		// 获取参数
		$collectUid	= $this->session->user['id'];
		$collectName= $this->session->user['name'];
		$purchaseId	= intval( $_REQUEST['store_id'] );
		
	}
}
