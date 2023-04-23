<?php
/**
 * @file MengsController.php
 * @brief  盟商会员列表 
 * @author huangb
 * @version 1.0
 * @date 2015-10-19
 */
namespace Mdg\Manage\Controllers;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Mdg\Models as M;
use Lib as L;
use Lib\Func as Func;
use Lib\PhpRedis as PhpRedis;
class MengsController extends ControllerMember
{
    /**
     * MS列表
     * @return [type] [description]
     */

    public function msAction()
    {
        $where = array();
        $credit_type = " credit_type = 32";
        $arr = array();
        $page_size = 10;
        $arr['user_id'] = $this->request->get('user_id', 'int', 0);
        $arr['ext_name'] = $this->request->get('ext_name', 'string', '');
        $arr['status'] = $this->request->get('status', 'int', 0);
        $arr['type'] = $this->request->get('type', 'int', 0);
        $arr['expire_time'] = $this->request->get('expire_time', 'string', '');
        $arr['expire_etime'] = $this->request->get('expire_etime', 'string', '');
        $arr['credit_id'] = $this->request->get('credit_id', 'int', 0);
        $arr['username'] = $this->request->get('username', 'string', '');
        $p = $this->request->get('p', 'int', '1');
        $page = $this->request->get('page', 'int', '1');
        $where = M\Users::getinfowhere($arr, $where, 32);     
        $users = M\Users::getCorporateUsers($where, $p, $page_size, $credit_type);
        // print_r($users);die;
        $this->view->users = $users;

    }
   /**
     *盟商信息修改
     * @return [type] [description]
     */

    public function mseditAction($id = 0, $type, $type_name, $credit_id = 0)
    {
        $p = $this->request->get('p','int','1');
        if (!$id)
        {
            parent::msg('访问方式不正确，请重新访问',"/manage/mengs/{$type_name}");

        }
        $userinfo = M\UserInfo::findFirst("user_id = {$id} and credit_type = {$type} and credit_id = {$credit_id}");
        if($userinfo){
            //工程师信息
            $se_id  = $userinfo->se_id;
            $se_no  = $userinfo->se_no;
            $seinfo = M\EngineerManager::findFirst("engineer_id = {$se_id} and engineer_no = {$se_no}");
            //身份证照片信息
            $idcard_info = M\UserBank::findFirstBycredit_id($userinfo->credit_id);
            //已整合的可信农场
            $user_id        = $userinfo->user_id;
            $engineerinfo = M\Users::findFirstByid($user_id);
            if($engineerinfo){
                $lwtt_phone = $engineerinfo->username;
                $se_id      = M\UserInfo::getlwttinfo($engineerinfo->id);
            }
            $where        = "credit_type = '8' and se_mobile = {$lwtt_phone} and mobile_type = '2' and se_id = {$se_id} and status = '1' ";
            $credit_farm  = M\Users::getCorporateUsers($where,$p,10,8);
  
        }
        $crops  =M\UserFarmCrops::selectByuserFarm($id,$credit_id);
        $this->view->crops = $crops;
        $this->view->cateList = $cateList = M\Category::getTopCaetList();
        $this->view->seinfo      = $seinfo;
        $this->view->idcard_info = $idcard_info;
        $this->view->userinfo    = $userinfo;
        $this->view->sid=$this->session->getId();
        //已整合的可信农场
        $this->view->credit_farm = $credit_farm;


    }
    /**
      *盟商信息保存
      *@return boolean
      */
    public function mssaveAction()
    {
        
        $user_id   = $this->request->getPost('user_id','int',0);
        $info_id   = $this->request->getPost('info_id', 'int', 0);
        $info_type = $this->request->getPost('info_type', 'string', '');
        $phone     = $this->request->getPost('lwtt_phone','string','');
        $category_name =$this->request->getPost('category_name', 'int', 1);
        $category_name_text= trim($this->request->getPost('category_name_text_0', 'string', '') , ',');
       
        $url       = $user_id.'/32/ms/'.$info_id;
        if(!$info_id)
        {
            parent::msg('数据不完整保存失败',"/manage/mengs/msedit/{$url}");
        }
        $info = M\UserInfo::findFirst("credit_id = {$info_id}");
        if($info){
            $info->phone = $phone;
        }

        if($category_name_text!=''&&$category_name_text!='1') 
        {
            $credit_id=$info_id;
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
                $UserFarmCrops->user_id = $user_id;
                $UserFarmCrops->category_name = $cate->title;
                $UserFarmCrops->add_time = CURTIME;
                $UserFarmCrops->category_id = intval($cate->id);
                $UserFarmCrops->credit_id = $credit_id;
                $UserFarmCrops->save();
            }
        }

        if($info->save()){
            parent::msg("保存成功！","/manage/mengs/msedit/{$url}");
        }else{
            parent::msg("保存失败！","/manage/mengs/msedit/{$url}");
        }
        
    }
    /**
     * 盟商个人详情
     * @param  integer $id [description]
     * @return [type]      [description]
     */

    public function msinfoAction($id = 0, $type, $type_name, $credit_id = 0)
    {
        // var_dump($credit_id);die;
        $p = $this->request->get('p','int','1');

        if (!$id)
        {
            parent::msg('访问方式不正确，请重新访问',"/manage/mengs/{$type_name}");

        }
        $userinfo = M\UserInfo::findFirst("user_id = {$id} and credit_type = {$type} and credit_id = {$credit_id}");
        // var_dump($userinfo->toArray());die;
        if($userinfo){
            //工程师信息
            $se_id  = $userinfo->se_id;
            $se_no  = $userinfo->se_no;

            $seinfo = M\EngineerManager::findFirst("engineer_id = {$se_id} and engineer_no = {$se_no}");
            
            //身份证照片信息
            $idcard_info = M\UserBank::findFirstBycredit_id($userinfo->credit_id);
            //已整合的可信农场
            $se_id        = $userinfo->credit_id;
            $where        = "credit_type = '8'  and mobile_type = '2' and se_id = {$se_id} and status = '1' ";
            $credit_farm  = M\Users::getCorporateUsers($where,$p,10,8);           

        }
        $crops  =M\UserFarmCrops::selectByuserFarm($id,$credit_id);
        
        $this->view->crops=$crops;
        // print_r($credit_farm);die;
        $this->view->seinfo      = $seinfo;
        $this->view->idcard_info = $idcard_info;
        $this->view->userinfo    = $userinfo;
        //已整合的可信农场
        $this->view->credit_farm = $credit_farm;
    }
    /**
     * MS审核
     * @return [type] [description]
     */

    public function msupdateAction()
    {
        $name        = $this->request->getPost('name','int','0');
        $credit_id   = $this->request->getPost('credit_id', 'int', 0);
        $reject      = $this->request->getPost('reject', 'string');  
        $this->db->begin();
        try{
            $userinfo = M\UserInfo::findFirst("credit_id = {$credit_id}");
            $userext  = M\Users::findFirst(" id = '{$userinfo->user_id}'"); 
            $phone    = $userext->username;
            if(!$userinfo){
                throw new \Exception("用户信息不存在", 1);
            }
            switch ($name) {
                case '1': $text = '审核通过';   $userinfo->status = 1; break;
                case '2': $text = '审核不通过'; $userinfo->status = 2; break;
                case '3': $text = '取消认证';   $userinfo->status = 3; break;
            }
            if($name=="1"){
                   //盟商清0
                   $redis = new PhpRedis('lwttstate_');
                   $a=$redis->set("lwttstate{$credit_id}_".$userinfo->user_id,0,1080000);
            }
            $log= M\Users::log($reject,$userinfo,$text);
            if(!$log){
                throw new \Exception("插入日志失败！"); 
            }
            if(!$userinfo->save()){
                throw new \Exception("取消认证失败！"); 
            }
            if($userinfo->status ==1 && $userinfo->save()){
                $sms     = new L\SMS();
                $content = '恭喜，您的盟商认证已经通过！您现在可以整合可信农场了，整合1个可信农场后，您即可发布盟商的供应信息。';
                $send    = $sms->send($phone,$content);                
            }
            $this->db->commit();
            $flag=true;
        } catch (\Exception $e) {
            $flag=false;
            $this->db->rollback();
        }
        if(!$flag){
            parent::msg('操作失败',"/manage/mengs/ms");
        }else{
            Func::adminlog("认证取消{$text}{$userext->username}",$this->session->adminuser['id']);
            parent::msg('操作成功',"/manage/mengs/ms");
        }
        
    }
    /**
     * 检查服务工程师
     */
    public function checklwttAction() 
    {
        $lwtt_phone = $this->request->getPost('lwtt_phone', 'string', '');
        $userfarm = array(
            'ok' => ''
        );        
        if (!$lwtt_phone || !L\Validator::validate_is_mobile($lwtt_phone))
        {
            $userfarm = array(
                'error' => '手机号格式不正确'
            );
            exit(json_encode($userfarm));
        }
        $user=M\Users::findFirstByusername($lwtt_phone);
        if(!$user){
            $userfarm = array(
                'error' => '该手机号当前还未开通产地服务商账号，禁止使用'
            );
            exit(json_encode($userfarm));
        }
        $userinfo=M\UserInfo::findFirst("user_id={$user->id} and status=1 and credit_type=8 ");
        if($userinfo){
            $userfarm = array(
                'error' => '该手机号当前还未开通产地服务商账号，禁止使用'
            );
            exit(json_encode($userfarm));
        }   
        $userinfo=M\UserInfo::findFirst("user_id={$user->id} and status=1 and credit_type=32 ");
        if(!$userinfo){
            $userfarm = array(
                'error' => '该手机号当前还未开通产地服务商账号，禁止使用'
            );
            exit(json_encode($userfarm));
        }   
        exit(json_encode($userfarm));
    }
 

}
