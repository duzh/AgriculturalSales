<?php
namespace Mdg\Member\Controllers;
use Mdg\Models as M;
use Lib as L;
/**
 * 蒙商
 */
class LwttController extends ControllerMember{

	/**
	 * 商盟申请
	 * @return  [html]
	 */
	public function newAction(){

		
        $uid = $this->getUserID();
        $userinfocount =M\UserInfo::count("user_id={$uid} and status!=4 and status in (1,2,3) and credit_type=32 ");
        if($userinfocount>0){
            echo "<script>alert('您已申请过此身份,不能重复申请');location.href='/member/userfarm/index';</script>";
            exit;
        }
        $userinfo =M\UserInfo::findFirst("user_id={$uid} and credit_type=32 order by credit_id desc ");
		$sid = $this->session->getId();
        M\TmpFile::clearOld($sid);
        $this->view->sid = $sid;
        $this->view->userinfo=$userinfo;
        $this->view->cateList = $cateList = M\Category::getTopCaetList();
        $this->view->url=$_SERVER['HTTP_REFERER'];
		$this->view->title = '个人中心-身份认证-产地服务站申请';
	}
	public function createAction(){

	    if(!$this->request->getPost()) 
        {
            $this->flash->error("数据错误!");
            return $this->dispatcher->forward(array(
                "controller" => "userfarm",
                "action" => "index",
            ));
        }
        $user_name = $this->request->getPost('user_name', 'string','');
        $member_type = $this->request->getPost('member_type', 'int', 0);
        $user_mobile = $this->request->getPost('user_mobile', 'int', 0);
        $user_credit_no = $this->request->getPost('user_credit_no', 'int', 0);
        $semobile = $this->request->getPost('semobile', 'int', 0);
        $category_name =$this->request->getPost('category_name', 'int', 1);
        $category_name_text_0= trim($this->request->getPost('category_name_text_0', 'string', '') , ',');
        $category_name_text_1= trim($this->request->getPost('category_name_text_1', 'string', '') , ',');
        if($category_name_text_0){
            $category_name_text=$category_name_text_0;
        }
        if($category_name_text_1){
            $category_name_text=$category_name_text_1;
        }

        $sid=$this->session->getId();
        $uid = $this->getUserID();

        $se_id=0;
        $se_no='';
        if (!$uid) 
        {
            $this->flash->error("用户信息不存在!");
            return $this->dispatcher->forward(array(
                "controller" => "userfarm",
                "action" => "index",
            ));
        }
        $info = M\Users::findFirst(" id ='{$uid}'");
        if (!$info) 
        {
            $this->flash->error("用户信息不存在!");
            return $this->dispatcher->forward(array(
                "controller" => "userfarm",
                "action" => "index",
            ));
        }
        if($semobile){
            $engineerinfo = M\EngineerManager::getEngineerInfo($semobile, false);
            if ($engineerinfo)
            {
                $se_id = $engineerinfo->engineer_id;
                $se_no = $engineerinfo->engineer_no;
            }
        }
        $this->db->begin();
        try
        {
            $UserInfo = new M\UserInfo();
            $UserInfo->user_id = $uid;
            $UserInfo->type = $member_type;
            $UserInfo->se_id = $se_id;
            $UserInfo->se_no = $se_no;
            $UserInfo->se_mobile=$semobile;
            $UserInfo->mobile_type=0;
            $UserInfo->apply_time = CURTIME;
            $UserInfo->status = 0;
            $UserInfo->certificate_no = $user_credit_no;
            $UserInfo->phone = $user_mobile;
            $UserInfo->credit_type = M\UserInfo::USER_TYPE_LWTT;
            $UserInfo->credit_no = $no = 'LWTT' . $uid . L\Func::random(3, 1); 
            $UserInfo->add_time = CURTIME;
            $UserInfo->last_update_time = CURTIME;
            $UserInfo->name = $user_name;
            //服务工程师
            if(!$UserInfo->save()){
               throw new \Exception("添加主信息失败");
            }
            $credit_id = $UserInfo->credit_id;
            //插入图片
            $UserBank = new M\UserBank();
            $UserBank->user_id = $uid;
            $idcard_picture = M\TmpFile::findFirst(" sid = '{$sid}' AND type = '31' ");
            if($idcard_picture) {
                $UserBank->idcard_picture = $idcard_picture ? $idcard_picture->file_path : $UserBank->idcard_picture;
            }
             /* 身份证背面照 */
            $idcard_picture_back = M\TmpFile::findFirst(" sid = '{$sid}' AND type = '34' ");
            if($idcard_picture_back) {
                $UserBank->idcard_picture_back = $idcard_picture_back ? $idcard_picture_back->file_path : $UserBank->idcard_picture_back;
            }
            $UserBank->add_time = CURTIME;
            $UserBank->last_update_time = CURTIME;
            $UserBank->credit_id = $credit_id;
            if(!$UserBank->save()){
                 throw new \Exception("添加图片失败");
            }
             //插入日志
            $userlog = new M\UserLog();
            $userlog->user_id = $uid;
            $userlog->credit_id = $credit_id;
            $userlog->operate_user_no =$UserInfo->credit_no;
            $userlog->operate_user_name = $UserInfo->name;
            $userlog->operate_time = CURTIME;
            $userlog->status = 0;
            $userlog->demo = "已提交申请";
            $userlog->add_time = time();
            if(!$userlog->save()){
                throw new \Exception("添加日志失败");
            }
            if($category_name_text!=''&&$category_name_text!='1') 
            {

                $category_name_text = array_unique(explode(',', $category_name_text));
                $DelUserFarmCrops = M\UserFarmCrops::find("credit_id ={$credit_id}");
                if($DelUserFarmCrops){
                    $DelUserFarmCrops->delete();
                }

                foreach ($category_name_text as $key => $val) 
                {
                    /* 检测分类是否存在 */
                    $cid = intval($val);
                    if (!$cate = M\Category::findFirst(" id = '{$cid}' AND parent_id > 0 ")) continue;
                    $UserFarmCrops = new M\UserFarmCrops();
                    $UserFarmCrops->user_id = $uid;
                    $UserFarmCrops->category_name = $cate->title;
                    $UserFarmCrops->add_time = CURTIME;
                    $UserFarmCrops->category_id = intval($cate->id);
                    $UserFarmCrops->credit_id = $credit_id;
                    if(!$UserFarmCrops->save()){
                        throw new \Exception("添加经营类别失败");
                    }
                }
            }
            $this->db->commit();
            $flag=true;
        }
        catch(\Exception $e) 
        {
            $this->db->rollback();
            $flag=false;
            // print_R($e->getMessage());die;
            
        }
        $this->response->redirect("userfarm/index")->sendHeaders();
	}
    public function infoAction($id=0){

        $id=intval($id);
        $uid        = $this->getUserID();
        $users        = M\UserInfo::findFirst("credit_id={$id} and credit_type!=4 ");
        if (!$id) {
            $this->flash->error("用户信息不存在!");
            return $this->dispatcher->forward(array(
                "controller"=> "userfarm",
                "action"    => "index",
            ));
        }
        if(!$users) {
            $this->flash->error("请求错误!");
            return $this->dispatcher->forward(array(
                "controller"=> "userfarm",
                "action"    => "index",
            ));
        }

        if ($users->user_id != $uid) {
            $this->flash->error("你无权查看详情!");
            return $this->dispatcher->forward(array(
                "controller"=> "userfarm",
                "action"    => "index",
            ));
        }  
        // 用户类型
        if($users->type== 0) {
            $users->type= '个体户';
        }elseif($users->type== 1) {
            $users->type= '企业';
        }
        $users->credit_type="产地服务站";
        // 申请日志
        $userLog    =array();
        $userLog    = M\UserLog::find("user_id=$uid and credit_id=$id order by add_time asc")->toArray();
        //身份证照
        $UserBank = M\UserBank::findFirst("credit_id={$id}");
        $engineerinfo=M\EngineerManager::findFirstByengineer_id($users->se_id);
        $crops  =M\UserFarmCrops::selectByuserFarm($uid,$id);
        $this->view->title = '个人中心-身份认证-详细查看';
        $this->view->userlog=$userLog;
        $this->view->crops=$crops;
        $this->view->userbank=$UserBank;
        $this->view->url=$_SERVER['HTTP_REFERER'];
        $this->view->engineerinfo=$engineerinfo;
        $this->view->users=$users;
    }
    public function editAction($id=0){
        $id=intval($id);
        $uid        = $this->getUserID();
        $users        = M\UserInfo::findFirst("credit_id={$id} and credit_type!=4 ");
        if (!$id) {
            $this->flash->error("用户信息不存在!");
            return $this->dispatcher->forward(array(
                "controller"=> "userfarm",
                "action"    => "index",
            ));
        }
        if(!$users) {
            $this->flash->error("请求错误!");
            return $this->dispatcher->forward(array(
                "controller"=> "userfarm",
                "action"    => "index",
            ));
        }

        if ($users->user_id != $uid) {
            $this->flash->error("你无权查看详情!");
            return $this->dispatcher->forward(array(
                "controller"=> "userfarm",
                "action"    => "index",
            ));
        }  
        $crops  =M\UserFarmCrops::selectByuserFarm($uid,$id);
        // 用户类型
        $users->credit_type="产地服务站";
        // 申请日志
        $userLog    =array();
        $userLog    = M\UserLog::find("user_id=$uid and credit_id=$id order by add_time asc")->toArray();
        //身份证照
        $UserBank = M\UserBank::findFirst("credit_id={$id}");
        $engineerinfo=M\EngineerManager::findFirstByengineer_id($users->se_id);
        $this->view->title = '个人中心-身份认证-重新认证';
        $sid = $this->session->getId();
        $this->view->sid = $sid;
        $this->view->crops = $crops;
        $this->view->userlog=$userLog;
        $this->view->userbank=$UserBank;
        $this->view->url=$_SERVER['HTTP_REFERER'];
        $this->view->cateList = $cateList = M\Category::getTopCaetList();
        $this->view->engineerinfo=$engineerinfo;
        $this->view->credit_id=$id;
        $this->view->users=$users;
    }
    public function saveAction(){
         
        
        if(!$this->request->getPost()) 
        {
            $this->flash->error("数据错误!");
            return $this->dispatcher->forward(array(
                "controller" => "userfarm",
                "action" => "index",
            ));
        }
        $user_name = $this->request->getPost('user_name', 'string','');
        $member_type = $this->request->getPost('member_type', 'int', 0);
        $user_mobile = $this->request->getPost('user_mobile', 'int', 0);
        $user_credit_no = $this->request->getPost('user_credit_no', 'int', 0);
        $semobile = $this->request->getPost('semobile', 'int', 0);
        $sid=$this->session->getId();
        $uid = $this->getUserID();
        $cid=$this->request->getPost('cid', 'int', 0);
        $isedit=$this->request->getPost('isedit', 'int', 0);
        $category_name =$this->request->getPost('category_name', 'int', 1);
        $category_name_text_0= trim($this->request->getPost('category_name_text_0', 'string', '') , ',');
        $category_name_text_1= trim($this->request->getPost('category_name_text_1', 'string', '') , ',');
        
        if($category_name_text_0){
            $category_name_text=$category_name_text_0;
        }
        if($category_name_text_1){
            $category_name_text=$category_name_text_1;
        }
        $se_id=0;
        $se_no='';
        if (!$uid) 
        {
            $this->flash->error("用户信息不存在!");
            return $this->dispatcher->forward(array(
                "controller" => "userfarm",
                "action" => "index",
            ));
        }
        $info = M\Users::findFirst(" id ='{$uid}'");
        if (!$info) 
        {
            $this->flash->error("用户信息不存在!");
            return $this->dispatcher->forward(array(
                "controller" => "userfarm",
                "action" => "index",
            ));
        }
        if($semobile){
            $engineerinfo = M\EngineerManager::getEngineerInfo($semobile, false);
            if ($engineerinfo)
            {
                $se_id = $engineerinfo->engineer_id;
                $se_no = $engineerinfo->engineer_no;
            }
        }
        $this->db->begin();
        try
        {
            $UserInfo =M\UserInfo::findFirstBycredit_id($cid);

            if(!$UserInfo){
                throw new \Exception("信息不存在");
            }
            $UserInfo->user_id = $uid;
            $UserInfo->type = $member_type;
            $UserInfo->se_id = $se_id;
            $UserInfo->se_no = $se_no;
            $UserInfo->apply_time = CURTIME;
            $UserInfo->status = 0;
            $UserInfo->certificate_no = $user_credit_no;
            $UserInfo->phone = $user_mobile;
            $UserInfo->credit_type = M\UserInfo::USER_TYPE_LWTT;
            $UserInfo->credit_no = $no = 'LWTT' . $uid . L\Func::random(3, 1); 
            $UserInfo->add_time = CURTIME;
            $UserInfo->last_update_time = CURTIME;
            $UserInfo->name = $user_name;
            //服务工程师
            if(!$UserInfo->save()){
               throw new \Exception("添加主信息失败");
            }
            $credit_id = $UserInfo->credit_id;
            $UserBank = new M\UserBank();
            $UserBank->user_id = $uid;
            $idcard_picture = M\TmpFile::findFirst(" sid = '{$sid}' AND type = '31' ");
            if($idcard_picture) {
                //插入图片
                $UserBank->idcard_picture = $idcard_picture ? $idcard_picture->file_path : $UserBank->idcard_picture;
            }
             /* 身份证背面照 */
            $idcard_picture_back = M\TmpFile::findFirst(" sid = '{$sid}' AND type = '34' ");
            if($idcard_picture_back) {
                $UserBank->idcard_picture_back = $idcard_picture_back ? $idcard_picture_back->file_path : $UserBank->idcard_picture_back;
            }
            $UserBank->add_time = CURTIME;
            $UserBank->last_update_time = CURTIME;
            $UserBank->credit_id = $credit_id;
            if(!$UserBank->save()){
                 throw new \Exception("添加图片失败");
            }

            if($isedit){
                $userlogs=M\UserLog::find(" credit_id=$cid");
                if($userlogs){
                    $userlogs->delete();
                }
            }
             //插入日志
            $userlog = new M\UserLog();
            $userlog->user_id = $uid;
            $userlog->credit_id = $credit_id;
            $userlog->operate_user_no =$UserInfo->credit_no;
            $userlog->operate_user_name = $UserInfo->name;
            $userlog->operate_time = CURTIME;
            $userlog->status = 0;
            if($isedit){
            $userlog->demo = "已提交申请";
            }else{
            $userlog->demo = "重新提交申请";    
            }
            $userlog->add_time = time();
            if(!$userlog->save()){
                throw new \Exception("添加日志失败");
            }
            
            if($category_name_text!=''&&$category_name_text!='1') 
            {

                $category_name_text = array_unique(explode(',', $category_name_text));
                $DelUserFarmCrops = M\UserFarmCrops::find("credit_id ={$credit_id}");
                if($DelUserFarmCrops){
                    $DelUserFarmCrops->delete();
                }

                foreach ($category_name_text as $key => $val) 
                {
                    /* 检测分类是否存在 */
                    $cid = intval($val);
                    if (!$cate = M\Category::findFirst(" id = '{$cid}' AND parent_id > 0 ")) continue;
                    $UserFarmCrops = new M\UserFarmCrops();
                    $UserFarmCrops->user_id = $uid;
                    $UserFarmCrops->category_name = $cate->title;
                    $UserFarmCrops->add_time = CURTIME;
                    $UserFarmCrops->category_id = intval($cate->id);
                    $UserFarmCrops->credit_id = $credit_id;
                    if(!$UserFarmCrops->save()){
                        throw new \Exception("添加经营类别失败");
                    }
                }
            }


            $this->db->commit();
            $flag=true;
        }
        catch(\Exception $e) 
        {
            $this->db->rollback();
            $flag=false;
            print_R($e->getMessage());die;
            
        }

        $this->response->redirect("userfarm/index")->sendHeaders();
    }
    /**
     *  删除采购申请
     */
    public function delapplyAction() {
        
        // 接收参数
        $uid        = $this->getUserID();
        
        $creditId   = isset($_REQUEST['credit_id']) ? intval( $_REQUEST['credit_id'] ) : 0;
        $users      = M\UserInfo::findFirstBycredit_id($creditId);
        
        // 参数校验
        if (!$uid) {
            echo json_encode(array(
                'code'      => 5,
                'result'    => '用户信息不存在'
            ));
            exit;
        }
        if ($users->user_id != $uid) {
            echo json_encode(array(
                'code'      => 0,
                'result'    => '你无权删除此信息！'
            ));
            exit;
        }
        if(!$creditId) {
            echo json_encode(array(
                'code'      => 1,
                'result'    => '参数错误'
            ));
            exit;
        }
        if(!$users) {
            echo json_encode(array(
                'code'      => 2,
                'result'    => '请求错误'
            ));
            exit;
        }
        
        // 处理数据
        $users->status  = 4;
        if(!$users->save()) {
            echo json_encode(array(
                'code'      => 3,
                'result'    => '删除失败'
            ));
            exit;
        }
        echo json_encode(array('code'=>4,'result'=>'删除成功'));
        exit;
    }
    /**
     * 检查服务工程师
     */
    public function checkEngineerAction() 
    {

       
        $phone = $this->request->getPost('semobile', 'string', '');        
        $userfarm = array(
            'ok' => ''
        );        
        if (!$phone || !L\Validator::validate_is_mobile($phone))
        {
            $userfarm = array(
                'error' => '手机号格式不正确'
            );
            exit(json_encode($userfarm));
        }
            
        $result = M\EngineerManager::getEngineerInfo($phone,0);
            
        if (!$result) {
            $userfarm = array(
                'error' => '该手机号当前还未成为工程师，禁止使用'
            );
        }
        
        exit(json_encode($userfarm));
    }

}