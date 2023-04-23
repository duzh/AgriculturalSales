<?php
/**
 * 用户中心-主营产品推荐
 */
namespace	Mdg\Member\Controllers;
use 		Phalcon\Mvc\Model\Criteria;
use 		Phalcon\Paginator\Adapter\Model as Paginator;
use 		Mdg\Models as M;
use 		Lib as L;
class MainproductController extends ControllerKx
{
    /**
     * 主营产品推荐
     */
    public function indexAction() {
		
		// 获取参数
		$userId		= $this->getUserID();
		$page		= $this->request->get( 'p' , 'int' , 1 );
		$page		= $page ? intval($page) : 1 ;
		$pageSize	= 10;
		$offst		= ($page-1) * $pageSize;
		
		// 参数校验
		if(!$userId) {
			echo "<script type='text/javascript'>window.location.href='/member/login/index/'</script>";exit;
		}
		
		// 获取主营产品列表
		$farmgoods	= M\CredibleFarmGoods::getCfarmList($page, $pageSize, $offst, $userId);
		$crediblefarminfo=M\CredibleFarmInfo::findFirstByuser_id($userId);
		
		// 传值
		$this->view->farmgoods	= $farmgoods;
		$this->view->url = !empty($crediblefarminfo->url) ? $crediblefarminfo->url : 'www';
		$this->view->userId		= $userId;
		$this->view->p			= $page;
		$this->view->current    = $offst;
		$this->view->title		= '个人中心-主营产品';
    }
	
	/**
	 *	取消推荐
	 */
	public function recommendCanselAction() {
		
		// 接收参数
		$userId	= $this->getUserID();
		$id		= $this->request->get('id', 'int', 0);
        $farm	= M\CredibleFarmGoods::findFirstByid($id);
		
		// 校验参数
		if( !$userId ) {
			echo json_encode(array(
                'code' => 0,
                'result' => '请登录'
            ));
            exit;
		}
		if( !$farm ) {
			echo json_encode(array('code'=>1,'result'=>'参数有误'));
			exit;
		} elseif ( $farm->is_recommend == 0 ) {
			echo json_encode(array('code'=>2,'result'=>'已取消推荐'));
			exit;
		}
		
		// 取消相关推荐
		$farm->is_recommend = 0;
		if($farm->save()) {
			echo json_encode(array('code'=>3,'result'=>'取消成功'));
			exit;
		} else {
			echo json_encode(array('code'=>4,'result'=>'取消失败'));
			exit;
		}
	}
	
	/**
	 *	推荐产品
	 */
	public function recommframAction() {
		
		// 接收参数
		$userId	= $this->getUserID();
		$cidone	= $this->request->get('cidone', 'int', 0);
		$cidtwo	= $this->request->get('cidtwo', 'int', 0);
		$sellid	= $this->request->get('sell_id', 'int', 0);
        $sell	= M\Sell::findFirstByid($sellid);

		// 参数校验
		if(!$userId) {
			echo json_encode(array('code'=>0,'result'=>'请登录'));
			exit;
		}
		if( !$sell ) {
			echo json_encode(array('code'=>1,'result'=>'此供应产品不存在'));
			exit;
		}
		if( M\CredibleFarmGoods::findFirst("sell_id = $sellid and user_id=$userId and is_recommend = 1")) {
			echo json_encode(array('code'=>2,'result'=>'此供应产品已推荐'));
			exit;
		}
		if( M\CredibleFarmGoods::count("user_id=$userId AND is_recommend = 1") == 6 ) {
			echo json_encode(array('code'=>5,'result'=>'最多可推荐6个哦'));
			exit;
		}
		
		// 获取供应商品信息
		$sell	= M\Sell::getSellInfo($sellid);
		
		// 入库主营产品表
		$farm	= new M\CredibleFarmGoods();
		$farm->sell_id			= $sell->id;
		$farm->user_id			= $userId;
		$farm->category_one		= $cidone;
		$farm->category_two		= $cidtwo;
		$farm->goods_name		= $sell->title;
		$farm->add_time			= time();
		$farm->last_update_time	= time();
		$farm->picture_path		= $sell->thumb;
		$farm->is_recommend		= 1;
		
		if($farm->save()) {
			echo json_encode(array('code'=>3,'result'=>'推荐成功'));
			exit;
		} else {
			echo json_encode(array('code'=>4,'result'=>'推荐失败'));
			exit;
		}
		
	}
}
