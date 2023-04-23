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
use Mdg\Models\UserInfo as UserInfo;
use Lib as L;
use Mdg\Models as M;

class ControllerKx extends Controller
{
	public function initialize(){
		
		$this->view->_nav = 'shou';
	    if(!empty($_COOKIE["ync_auth"])){
             $user=crypt::authcode($_COOKIE["ync_auth"],$operation = 'DECODE');
             $users=explode("|",$user);
             $mobile= isset($users[1]) ? $users[1] : 0 ;
             if($mobile){
             	$user = Users::checkLogin($mobile);
                $userext=UsersExt::getuserext($user->id);
	    		$this->session->user = array('mobile'=>$user->username,'id'=>$user->id,'name'=>$userext->name,'path'=>$userext->picture_path);
             }else{
             	$_COOKIE["ync_auth"]=array();
                $this->session->user=array();
             }
        }else{
        	$_COOKIE["ync_auth"]=array();
            $this->session->user=array();
        }
        $user = $this->session->user;
		if(!$user ||( !isset($_COOKIE["ync_auth"]) || !$_COOKIE["ync_auth"]) ){
			 $this->response->redirect("login/index")->sendHeaders();
		}

		$con = $this->dispatcher->getControllerName();
        //echo $con;die;
		//$con = $con == 'orderssell' ? 'ordersbuy' : $con;

		$navInfo = Nav::findFirstBycontroller($con);
		$this->view->curId = $navInfo ? $navInfo->id : 0;

		$this->view->title = '';
		$navLeft = Nav::find(array('is_show=1', 'order'=>'sortrank desc,id asc'))->toArray();

		$navLeft = Func::toTree($navLeft, 'id', 'pid');
         
        $privilege_tags= UserInfo::getprivilege_tags($this->session->user["id"]);
        $memberuserlwtt=$this->selectBylwtt(M\UserInfo::USER_TYPE_LWTT);
        /* 检测用户是否为采购商家 */
        $uid = $this->getuserId();
        // $pe = UserInfo::selectBycredit_type($uid , M\UserInfo::USER_TYPE_PE);
        if(!UserInfo::selectBycredit_type($uid , M\UserInfo::USER_TYPE_PE) && !UserInfo::selectBycredit_type($uid , M\UserInfo::USER_TYPE_HC)) {
            unset($navLeft[47]);
        }
        // var_dump($privilege_tags);die;
        foreach ($navLeft as $key => $value) {
        	if(!$this->selectByUserFarm(M\UserInfo::USER_TYPE_IF)){
        		unset($navLeft['37']);
                unset($navLeft['38']);
        	}

            if($value["controller"]=="commercia"&&!$privilege_tags["user"]&&!$privilege_tags["order"]&&!$privilege_tags["sell"]&&!$privilege_tags["pur"]){
                unset($navLeft[$key]);
            } 

            foreach ($value["child"] as $k => $v) {
                if($v["controller"]=="conformity"&&$memberuserlwtt!=1){
                            unset($navLeft[$key]["child"][$k]);
                }  
                if($v["controller"]=="commercia"&&!$privilege_tags["user"]){
                    unset($navLeft[$key]["child"][$k]);
                }   
                if($v["controller"]=="commerciaorder"&&!$privilege_tags["order"]){
                    unset($navLeft[$key]["child"][$k]);
                }    
                //print_r($value);die;
                if($v["controller"]=="commerciasell"&&!$privilege_tags["sell"]){
                    unset($navLeft[$key]["child"][$k]);
                }    
                if($v["controller"]=="commerciapurchase"&&!$privilege_tags["pur"]){
                     unset($navLeft[$key]["child"][$k]);
                }
            }
          
        }

		$this->view->navLeft = $navLeft;
        
        //店铺管理左侧
		$shopLeft = ShopNav::find(array('is_show=1', 'order'=>'sortrank desc'))->toArray();
		$shopLeft = Func::toTree($shopLeft, 'id', 'pid');
	
		$this->view->shopLeft = $shopLeft;
		
		//获取店铺信息
		$data = $this->checkShopExist();

		$helper = ArticleCategory::find('pid = 1');
		$this->view->helper = $helper;

		$navTop = Category::find('is_show=1')->toArray();
		$navTop = Func::toTree($navTop, 'id', 'parent_id');
		if(isset($user["id"])){
			//订单个数
	        $ordercount=Orders::getordercount($user['id']);
	        //是否有报价
	        $quotation=Quotation::count("suserid = ".$user['id']."");
	        $messagecount = M\Message::GetMessageUnreadCount($user['id']);
		}else{
            $ordercount=0;
            $quotation=0;
            $messagecount=0;
		}
        $cache_key='cate_home.cache';

        $cateData = $this->cache->get($cache_key);
        
        if($cateData === NULL) {
        
            $data = M\Category::find(array(
                " is_groom = 1 ",
                'columns' => 'id, title,abbreviation,parent_id',
                'order' => 'id ASC '
            ))->toArray();
            
            $sort = array(
                7 => 0,  //粮油
                1 => 1,  //蔬菜
                2 => 2,  //水果
                1377 => 3, //苗木
                15 => 4,  //干果
                22 => 5,  //中药材
                899 => 6, //其他
            );

            $pid = array_unique(array_column($data, 'parent_id'));
            $data = L\Arrays::toTree($data, 'id', 'parent_id');
            
            $k = 0;

            foreach ($data as $row) {
                
                if(isset($sort[$row['id']])) {
                    $k = $sort[$row['id']];
                }else{
                    continue;
                }

                $cateData[$k]['id'] = $row['id'];
                $cateData[$k]['title'] = $row['title'];
                $cateData[$k]['parent_id'] = $row['parent_id'];
                $cateData[$k]['cate'] = array();
                /* 检测是否存在 */
                foreach ($row['children'] as $key => $val) {
                    switch ($val['abbreviation']) {
                        case 'A': case 'B': case 'C': case 'D': case 'E':
                            if(isset($cateData[$k]['cate'][1]) && count($cateData[$k]['cate'][1]) > 10 ) break;
                            $cateData[$k]['cate'][1][$key]  = $val;
                            
                            break;
                        case 'F': case 'G': case 'H': case 'I': case 'J':
                            if(isset($cateData[$k]['cate'][2]) && count($cateData[$k]['cate'][2]) > 10 ) break;
                            $cateData[$k]['cate'][2][$key]  = $val;
                            break;
                        case 'K': case 'L': case 'M': case 'N': case 'O':
                            if(isset($cateData[$k]['cate'][3]) && count($cateData[$k]['cate'][3]) > 10 ) break;
                            $cateData[$k]['cate'][3][$key]  = $val;

                            break; 
                        case 'P': case 'Q': case 'R': case 'S': case 'T':
                            if(isset($cateData[$k]['cate'][4]) && count($cateData[$k]['cate'][4]) > 10 ) break;
                           $cateData[$k]['cate'][4][$key]  = $val;

                            break;
                        case 'U': case 'V': case 'W': case 'X': case 'Y':case 'Z':
                            if(isset($cateData[$k]['cate'][5]) && count($cateData[$k]['cate'][5]) > 10 ) break;
                            $cateData[$k]['cate'][5][$key]  = $val;
                            break;
                    }
                }
                
                if($cateData[$k]['cate']) {
                    ksort($cateData[$k]['cate']);
                }
                if(!in_array($cateData[$k]['id'] , $pid)) unset($cateData[$k]);
            }
           
            $this->cache->save($cache_key, $cateData);
        }
       
      

        $user = $this->session->user;
        if(isset($user['id'])){
            
            $messagecount = M\Message::GetMessageUnreadCount($user['id']);
        }else{
            
            $messagecount=0;
        }
        $hotsell=array();
        $hotsell=M\Sell::getHotlist();
       
        $this->usercount = 100;
        $this->shopcount = 100;
        $this->storecount = 100;
        $this->servicecount = 100;
        $this->ordercount = 100;

        $this->view->haeder_cate = $cateData;
        // $arr             = explode('/',__DIR__);
        // $action              = $arr['1'];
        // $this->view->action  = $action;
        
        
        
        /* 头部数据 */
        $this->view->userInfo = $this->checkUserInfo();
        //检测用户是否完善信息
        $this->view->_nav = $_nav = strtolower($this->dispatcher->getControllerName());
        $this->view->_action = $action = strtolower($this->dispatcher->getActionName());

     
        $this->view->get = $_GET;
        $this->view->is_kx = 1;
        $this->view->messagecount = $messagecount;
        $this->view->title = '';
        $this->view->hotsell=$hotsell;
		/* 新版头部 */

        /*  检测用户身份 */
        $this->view->user_if = $this->selectBylwtt(M\UserInfo::USER_TYPE_IF);
        $this->view->user_hc = $this->selectBylwtt(M\UserInfo::USER_TYPE_HC);
        $this->view->user_lwtt=$memberuserlwtt;
        $this->view->user_pe=$this->selectByUserFarms(M\UserInfo::USER_TYPE_PE);
        $this->view->is_brokeruser =Users::findFirstByid($this->session->user["id"]);
        $this->view->userInfo = $this->checkUserInfo();
        $this->view->messagecount = $messagecount ? $messagecount : 0;
        $this->view->quotation = $quotation ? $quotation : 0 ;
		$this->view->ordercount = $ordercount;

		$this->view->navTop = $navTop;
		$this->view->title = '个人中心-店铺管理-丰收汇';
		$this->view->keywords = '丰收汇,农业,农产品,农副产品,农产品批发,农产品电商,农产品现货,农产品交易,农产品加工,农产品价格';
        $this->view->descript = '丰收汇是依托中国农业互联网高科技综合服务商云农场的丰富资源和先进技术而建立的中国最专业的网上农产品服务平台，
        为中国农村专业合作社、中小农商企业等提供农产品信息发布、行情资讯、网上商城、金融服务、定向对接、安全模型、订单种植等专业化服务，
        旨在成为全球高价值农产品交易服务商。';
        
		$this->view->shopVia  = isset($data['shop_status']) ? $data['shop_status'] : 0; # 检测店铺是否审核通过 1 通过 
	}

	/**
     * 检测当前用户是否信息完整
     * @return [type] [description]
     */
    public function checkUserInfo() {
        $user = $this->session->user;
        $uid = isset($user['id']) ? intval($user['id']) : 0 ;
        if(!$uid) return 1;
        $info = M\Users::findFirst($uid);
        if(!$info) return 1;
        if(!isset($info->ext->name) || !isset($info->ext->areas_name) ||  !$info->ext->name || !$info->ext->areas_name ) return 1;
        return false;
    }

	public function getUserID() {
		$user = $this->session->user;
		return $user['id'];
	}

	public function getUserName() {
		$user = $this->session->user;
		return $user['mobile'];
	}
	public function getUname() {
		$user = $this->session->user;
		return $user['name'];
	}

	/**
	 * 检测登陆人是否需要完善信息
	 * @return 
	 */
	public function checkLoginInfo() {
		$uid = $this->getUserID();
		$cond[] = " id = '{$uid}'";
		$info = Users::findFirst($cond)->toArray();
		if(!isset($info->ext->name) || !$info->ext->name || !isset($info->ext->areas_name) || !$info->ext->areas_name) {
			return 1;
		}
		return 0;
	}


	/**
	 * 检测当前用户是否开店
	 * @param  boolean $reutnrObj 是否返回对象数据
	 * @return 
	 */
	public function checkShopExist($reutnrObj = false) {
		$user_id = $this->getUserID();
		$data = Shop::findFirst(" user_id = '{$user_id}' AND is_service = 0 ");
		return $data ? $reutnrObj ? $data : $data->toArray() : array();
	}


	
	/**
	 * 检测当前用户是否绑定云农宝
	 * @param  string $ishref 是否跳转
	 * @return string
	 */
	public function checkIsYnp($ishref=''){	
		$username = $this->getUserName();
        //检测当前用户是否绑定云农宝
        $ThriftInterface = new L\Ynp($this->ynp);
        $data = $ThriftInterface->checkPhoneExist((string)$username);

        if($ishref) {
        	return (!isset($data) || $data != '01');
        }

        if($data  != '01') {
        	//未绑定 
        	echo "<script>location.href='/member/bind/index'</script>";exit;
        }
	}

	/**
	 * 检测当前用户是否开
	 * @param  boolean $reutnrObj 是否返回对象数据
	 * @return 
	 */
	public function checkServiceExist($reutnrObj = false) {
		$user_id = $this->getUserID();

		$data = Shop::findFirst(" user_id = '{$user_id}' and is_service='1' ");
		return $data ? $reutnrObj ? $data : $data->toArray() : array();

	}


	/**
	 * 检测商品是否可以申请标签
	 * @param  integer $sid 商品id
	 * @return boolean
	 */
	public function checkSellBindTag($sid=0) {
		$data = M\Tag::checkSell($sid);
		if($data) {
			echo "<script>location.href='/member/sell/index'</script>";exit;
		}
	}

    /**
     * 获取用户身份
     * @return [type] [description]
     */
    public function selectByUserFarm($type=0) {
        $uid = $this->getUserID();
        $cond[] = " user_id = '{$uid}' AND status = 1 AND credit_type = '{$type}' ";
        $info = M\UserInfo::findFirst($cond);
        return $info;
    }


    public function showMsg($msg = '', $url='',  $module = 'member') {
    	echo "<script>alert('{$msg}');location.href='/{$module}/{$url}'</script>";
    	exit;
    }
     /**
     * 获取用户身份
     * @return [type] [description]
     */
    public function selectBylwtt($type=0) {
        $uid = $this->getUserID();
        $cond[] = " user_id = '{$uid}' AND credit_type = '{$type}' order by credit_id desc  ";
        $info = M\UserInfo::findFirst($cond);
        if(!$info){
            return 5;
        }
        return $info->status;
    }
        /**
     * 获取用户身份
     * @return [type] [description]
     */
    public function selectByUserFarms($type=0) {
        $uid = $this->getUserID();
        $cond[] = " user_id = '{$uid}' AND credit_type = '{$type}' order by credit_id desc ";
        $info = M\UserInfo::findFirst($cond);
        return $info;
    }

}
