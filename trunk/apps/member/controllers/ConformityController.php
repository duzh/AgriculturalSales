<?php
/**
 * @file ConformityController.php
 * @brief  已整合的可信农场 
 * @author huangb
 * @version 1.0
 * @date 2015-10-22
 */
namespace Mdg\Member\Controllers;
use Mdg\Models as M;
use Lib\Pages as Pages;
use Mdg\Models\Purchase as Purchase;
class ConformityController extends ControllerMember
{
	/**
     * 已整合的可信农场
     * @return [type] [description]
     */
	public function indexAction() {
        $page    = $this->request->get('p', 'int', 1);
        $page    = intval($page)>0 ? intval($page) : 1;
        $total     = $this->request->get('total', 'int', 0);
        if($total&&$page>$total){
        	$page=$total;
        }
        $page_size = '5';
        $user_id = $this->getUserID();
        if(!$user_id){
             $this->response->redirect("login/index")->sendHeaders();
        }
        $username = $this->getUserName();
        if($username){
        	$userinfo    = M\Users::findFirstByusername($username);
        	$se_id       = isset($userinfo)? M\UserInfo::getlwttinfo($userinfo->id):'';
        	$where       = "credit_type = '8' and se_id = {$se_id} and se_mobile = {$username} and mobile_type = '2' and status = '1' ";
        	$credit_farm = M\UserInfo::getConformitUsers($where, $page,$page_size, 8);      
        }
        // print_r($credit_farm);die;
        $this->view->p = $page;
        $this->view->credit_farm = $credit_farm;
        $this->view->title = "个人中心-已整合的可信农场-丰收汇";	
	}

	/**
     * 已整合的可信农场的供应信息
     * @return [type] [description]
     */	
	public function sell_listAction(){
		$page      = $this->request->get('p', 'int', 1);
		$total     = $this->request->get('total', 'int', 0);
        $page      = intval($page)>0 ? intval($page) : 1;
        if($total&&$page>$total){
        	$page=$total;
        }
        $page_size = '10';
		$credit_id = $this->request->get('credit_id','int','0');
        $farm_name = M\UserFarm::findFirstBycredit_id($credit_id)->farm_name;
        $data = M\UserInfo::supplyInfo($credit_id,$page,$page_size);
        $this->view->p = $page;
        $this->view->farm_name = $farm_name; 
		$this->view->sell_info = $data;
		$this->view->goods_unit = Purchase::$_goods_unit;
        $this->view->title = "个人中心-已整合的可信农场-丰收汇";
	}

}