<?php

namespace Mdg\Member\Controllers;

use Phalcon\Mvc\Controller;
use Mdg\Models\UsersNav as Nav;
use Mdg\Models\ShopNav as ShopNav;
use Mdg\Models\Article as Article;
use Mdg\Models\ArticleCategory as ArticleCategory;
use Mdg\Models\Category as Category;
use Mdg\Models\Orders as Orders;
use Mdg\Models\Shop as Shop;
use Mdg\Models\PurchaseQuotation as Quotation;
use Lib\Func as Func;
use Lib\Crypt as crypt;
use Mdg\Models\Users as Users;
use Mdg\Models\UsersExt as UsersExt;
class ControllerStore extends Controller
{
	public function initialize(){
		$this->view->_nav = 'shou';
		
        
        if(!empty($_COOKIE["ync_auth"])){
             $user=crypt::authcode($_COOKIE["ync_auth"],$operation = 'DECODE');
             $users=explode("|",$user);
   
             $mobile=$users[1];
             $user = Users::checkLogin($mobile,$password='123456');
            
             if($user){
                $userext=UsersExt::getuserext($user->id);
	    		$this->session->user = array('mobile'=>$user->username,'id'=>$user->id,'name'=>$userext->name);
             }
        }else{
            $this->session->user=array();
        }
        $user = $this->session->user;
		if(!$user['id']||empty($_COOKIE["ync_auth"])){
			 $this->response->redirect("login/index")->sendHeaders();
		}
       
		$con = $this->dispatcher->getControllerName();
		$con = $con == 'orderssell' ? 'ordersbuy' : $con;
		$navInfo = Nav::findFirstBycontroller($con);
		$this->view->curId = $navInfo ? $navInfo->id : 0;

		$this->view->title = '';
		$navLeft = Nav::find(array('is_show=1', 'order'=>'sortrank desc'))->toArray();
		$navLeft = Func::toTree($navLeft, 'id', 'pid');
		$this->view->navLeft = $navLeft;
        
        //店铺管理左侧
		$shopLeft = ShopNav::find(array('is_show=1', 'order'=>'sortrank desc'))->toArray();
		$shopLeft = Func::toTree($shopLeft, 'id', 'pid');
		$this->view->shopLeft = $shopLeft;
		
		$helper = ArticleCategory::find('pid = 1');
		$this->view->helper = $helper;

		$navTop = Category::find('is_show=1')->toArray();
		$navTop = Func::toTree($navTop, 'id', 'parent_id');
		//订单个数
        $ordercount=Orders::getordercount($user['id']);
        //是否有报价
        $quotation=Quotation::count("suserid = ".$user['id']."");
        
        $this->view->quotation = $quotation ? $quotation : 0 ;
		$this->view->ordercount = $ordercount;
		$this->view->navTop = $navTop;
		$this->view->title = '';
		$this->view->keywords = '';
		$this->view->descript = '';
	}

	
	/**
	 * 检测当前登录用户是否开店
	 * @param  integer $uid 用户id
	 * @return array
	 */
	public function checkShopExist($uid=0) {
		$user_id = $this->getUserID();
		$data = Shop::findFirst(" user_id = '{$user_id}'");
		return $data ? $data->toArray() : array();
	}


}
