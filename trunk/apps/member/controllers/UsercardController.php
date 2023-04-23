<?php
namespace Mdg\Member\Controllers;

use Mdg\Models\UserBankCard as UserBankCard;
use Mdg\Models as M;
use Lib as L;

/**
 * 银行卡管理
 */

class UsercardController extends ControllerMember
{
	static $sourceType  = [ 0=> '', 1 => '注册', 2 => '县域服务站', 8 => '可信农场', 161 => '采购企业', 160 => '采购经纪人'];

	function sourceType($id, $credit_id) {
		$result = "";
		switch($id){
			case 0: break;
			case 1: 
				$result = "注册"; 
				break;
			case 2: 
				$result = "县域服务"; 
				break;
			case 4: 
				$result = "村站服务"; 
				break;
			case 8: 
				$result = "可信任农场"; 
				break;
			case 16: 
				$type = UserBankCard::getUserInfoTypeById($credit_id);
				if($type == 0){
					$result = "采购经纪人"; 
				} else if( $type == 1 ) {
					$result = "采购企业"; 
				}
				break;
			default:
				break;
		}
		return $result;
	}
	
    public function indexAction () {
        $uid = $this->getUserID();
        $page = $this->request->get('p', 'int', 1);
        $page = intval($page) ? intval($page) : 1;
		$cond[] = "  user_id = '{$uid}'" ;
		$cond = implode( ' AND ', $cond);
		$data = M\UserBankCard::getUserBankList($cond, $page);
		
		// $userbank    = UserBankCard::find("user_id={$uid} and status=0 and source_type<2")->toArray();
		//$ceruserbank = UserBankCard::find("user_id={$uid} and status=0 and source_type>1")->toArray();
		// foreach($data['items'] as $bank){
			
		// 	if(isset($userbank[$bank['bank_cardno']]) ) {
		// 		$source = $userbank[$bank['bank_cardno']]['source_type'];
		// 		$bank['is_default'] = $userbank[$bank['bank_cardno']]['is_default'] ? $userbank[$bank['bank_cardno']]['is_default'] : $bank['is_default'];
		// 		$userbank[$bank['bank_cardno']] = $bank;
		// 		$userbank[$bank['bank_cardno']]['source_type'] = $source;
		// 	} else {
		// 		$userbank[$bank['bank_cardno']] = $bank;
		// 		$userbank[$bank['bank_cardno']]['source_type'] = self::sourceType($bank['source_type'],$bank['credit_id']);
		// 	}
		// }
		
		foreach ($data['items'] as &$val) {
			$val['source_type'] = intval($val['source_type']);
			if($val['source_type'] == 16 ) {
				/* 检测 PE 身份类型 */
				$cid = $val['credit_id'];
				$uInfo = M\UserInfo::findFirst(array(" credit_id = '{$cid}'", 'columns' => 'type'));
				$val['source_type'] = $val['source_type'] . $uInfo->type;
			}
			$val['source_type'] = isset(self::$sourceType[$val['source_type']]) ? self::$sourceType[$val['source_type']] : '';

		}

		$banklist = M\Bank::getBankList();
		$this->view->page = $page;
		$this->view->data = $data;
		$this->view->bankList = $banklist;
        $this->view->userbank = $userbank;
        $this->view->title = '个人中心-我的银行卡';
    }

    /**
     * 保存银行卡信息
     */
    public function saveAction(){

    	$bank_name    = $this->request->getPost("name","string","");
		$province_id  = $this->request->getPost("province_id","int",0);
		$city_id      = $this->request->getPost("city_id","int",0);
		$district_id  = $this->request->getPost("district_id","int",0);
		$bank_account = $this->request->getPost("account","string","");
		$bank_cardno  = $this->request->getPost("cardno","string","");
		$refer_btn_cardno = $this->request->getPost('refer_btn_cardno', 'int', 1);
		
		if(!$refer_btn_cardno) {
			$this->response->redirect("usercard/index")->sendHeaders();
			exit;
		}

        $uid = $this->getUserID();

        if(!$uid || !$bank_name || !$province_id || !$city_id || !$district_id || !$bank_account || !$bank_cardno ) {
			$this->flash->error("参数错误");
			echo $this->response->redirect("usercard/index")->sendHeaders();
		}
		
		$address[] =  M\AreasFull::getAreasNametoid($province_id);
		$address[] =  M\AreasFull::getAreasNametoid($city_id);
		$address[] =  M\AreasFull::getAreasNametoid($district_id);
		
		//验证重复：根据卡号->不存在新增; 存在->认证卡：提示存在，存在->非认证：提示更新？更新则更新，否则不更新
		$bankcard = UserbankCard::findFirst("user_id={$uid} and bank_cardno={$bank_cardno} ");

		// if ($bankcard && $bankcard->source_type>1) {
		// 	echo "<script>alert('该银行卡已添加，不能重复添加!');'</script>";
		// 	echo $this->response->redirect("usercard/index")->sendHeaders();  
		// 	exit;
		// }
		$count = M\UserBankCard::count("user_id={$uid} and status=0 and is_default=1");
		/*if ($bankcard && $bankcard->source_type <= 1 && $type) {}*/
		if(!$bankcard) $bankcard = new UserBankCard();
		
		$bankcard->source_type = ($bankcard && $bankcard->source_type) ? $bankcard->source_type : 0;
		
		$bankcard->is_default = ($bankcard && $bankcard->is_default) ? 1 : ($count > 0 ? 0 : 1);
		$bankcard->status = 0;
        $bankcard->user_id = $uid;
        $bankcard->bank_name = $bank_name;
        $bankcard->bank_address = join(",", $address);
        $bankcard->bank_account = $bank_account;
        $bankcard->bank_cardno = $bank_cardno;
        $bankcard->credit_id = 0;
        $bankcard->add_time = CURTIME;
        $bankcard->last_update_time = CURTIME;
		
		$msg = "信息保存成功！";
        if(!$bankcard->save()){
			$msg = "保存失败，请重试";
        }
		$this->flash->error($msg);
		$this->response->redirect("usercard/index")->sendHeaders();  
    }
    

    public function setAction(){
		$uid = $this->getUserID();
		$id	= isset($_REQUEST['id']) ? intval( $_REQUEST['id'] ) : 0;
		$type	= isset($_REQUEST['type']) ? intval( $_REQUEST['type'] ) : 0;
		if (!$uid || !$id || !$type) {
            echo json_encode(array('code'=> 5,'result'=> '参数错误'));
            exit;
        }
		
		$userbank = UserBankCard::findFirst("user_id={$uid} and id={$id}");
		if(!$userbank) {
			echo json_encode(array('code'=>5,'result'=>'数据错误'));
			exit;
		}
		
		//更新默认
		if($type==1){
			try{
				$this->db->begin();
				
				$old_default = UserBankCard::findFirst("user_id={$uid} and is_default=1");
				if($old_default) {
					$old_default->last_update_time = CURTIME;
					$old_default->is_default = 0;
					$old_default->save();
				}
				
				$userbank->last_update_time = CURTIME;
				$userbank->is_default = 1;
				$userbank->save();
				
				$this->db->commit();
				echo json_encode(array('code'=>4,'result'=>'设置成功'));
				exit;
			}catch(\Exception $e){
				$this->db->rollback();
				echo json_encode(array('code'=>3,'result'=>'操作失败'));
				exit;
			}
		}
		//删除记录
		if($type==2){
			if($userbank->is_default==0){
				/*$userbank = UserBankCard::findFirst("user_id={$uid} and id={$id} and source_type=0");
				$userbank->last_update_time = CURTIME;
				$userbank->status = 1;
				if($userbank->save()) {
					echo json_encode(array('code'=>4,'result'=>'删除成功'));
					exit;
				}*/
				if ($userbank->delete()) exit(json_encode(array('code'=>4,'result'=>'删除成功')));
			}
			exit(json_encode(array('code'=> 3,'result'=>'操作失败')));
		}
		
    }

	//验证重复：根据卡号->不存在新增; 存在->认证卡：提示存在，存在->非认证：提示更新？更新则更新，否则不更新
	public function isexistAction(){
		$uid = $this->getUserID();
		$cardno	= isset($_REQUEST['id']) ? intval( $_REQUEST['id'] ) : 0;
		if (!$uid || !$cardno) {
            echo json_encode(array('code'=> 2,'result'=> '参数错误'));
            exit;
        }		
		$bankcard = UserbankCard::findFirst("user_id={$uid} and bank_cardno={$cardno} and status=0");
		if ($bankcard && $bankcard->source_type>1) {
			echo json_encode(array('code'=> 3, 'result'=>'该银行卡已添加，不能重复添加!'));
            exit;
		}
		if ($bankcard && $bankcard->source_type <= 1) {
			echo json_encode(array('code'=> 4, 'result'=>'已经存在，是否要覆盖原有信息?'));
			exit;
		}
	}

    
}