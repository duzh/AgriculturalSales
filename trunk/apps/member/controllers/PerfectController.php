<?php
namespace Mdg\Member\Controllers;

use Mdg\Models\Users as Users;
use Mdg\Models\AreasFull as Areas;
use Mdg\Models\UsersExt as UsersExt;
use Mdg\Models\TmpFile as TmpFile;
use Mdg\Models\Category as Category;
use Mdg\Models\UserAttention as UserAttention;
use Lib\Func as Func;
use Lib\Areas as lAreas;
use Lib\Member as Member;
use Lib\Arrays as Arrays;
use Lib\Path as Path;
use Lib\File as File;
use Lib\UpYun as UpYun;
use Lib as L;
class PerfectController extends ControllerMember
{

    /**
     * 个人信息展示
     * @return [type] [description]
     */
    public function indexAction() {

        $user_id = $this->request->get('user_id', 'int', 0);
        if($user_id){
            $user_id = $user_id;
        }else{
            $user_id = $this->getUserID();
        }
        if(!$user_id){
             $this->response->redirect("login/index")->sendHeaders();
        }
        $users = Users::findFirstById($user_id);
          
        $farm_areas = $users->ext ? $users->ext->farm_areas :0;
        //查询所有分类信息
        $category= Category::find(" is_show=1 ")->toArray();
        $cate=Arrays::toTree($category,"id","parent_id","child");
        //print_r($cate);die;
        //查询关注的商品
        $cond[] = "user_id={$user_id} and attention_type=2 ";
        $cond['columns'] = " category_name,category_id";
        $purchasecate=UserAttention::find($cond)->toArray();
        
        //获取采购txt
        $purchasecatecount=UserAttention::count($cond);
        if($purchasecatecount>1){
             $purchasetxt='';
             foreach ($purchasecate as $key => $value) {
                $purchasetxt.="<em id='".$value['category_id']."' >".$value['category_name']."</em>";
             }
        }else{
            $purchasetxt="<em id='".$purchasecate[0]['category_id']."' >".$purchasecate[0]['category_name']."</em>";
        }
        //获取采购分类tid
        if(isset($purchasecate[0]["category_name"])&&$purchasecate[0]["category_name"]!=''){
           $purchasecate =$purchasecate;  
           $purchasecateid=Arrays::getCols($purchasecate,"category_id",",");
        }else{
          $purchasecate=array();
           $purchasecateid='';
        }
      
        //获取分类id
        $cond1[] = "user_id={$user_id} and attention_type=1 ";
        $cond1['columns'] = " category_name,category_id";
        $sellcate=UserAttention::find($cond1)->toArray();
        
        if(isset($sellcate[0]["category_name"])&&$sellcate[0]["category_name"]!=''){
           $sellcate =$sellcate;  
           $sellcateid=Arrays::getCols($sellcate,"category_id",",");
        }else{
           $sellcate=array();
           $sellcateid='';
        }
        //获取分类txt

        $sellcatecount=UserAttention::count($cond1);
        
        if($sellcatecount>1){
             $selltxt='';
             foreach ($sellcate as $key => $value) {
                $selltxt.="<em id='".$value['category_id']."' >".$value['category_name']."</em>";
             }
        }else{
            $selltxt="<em id='".$sellcate[0]['category_id']."' >".$sellcate[0]['category_name']."</em>";
        }
       
        $this->view->curAreas = $users->areas ? lAreas::ldData($users->areas) : '';
        $this->view->users = $users;
        $this->view->user_type = Users::$_user_type;
        $this->view->farm_areas = $farm_areas;
        $sid = md5(session_id());
        $this->view->sid = $sid;
        $this->view->cate = $cate;
        $this->view->sellcate = $sellcate;
        $this->view->purchasecate = $purchasecate;
        $this->view->sellcateid = $sellcateid;
        $this->view->purchasecateid = $purchasecateid;
        $this->view->plat = Users::$_plat;
        $this->view->publish_set = $users->ext->publish_set;
       
        $this->view->selltxt = base64_encode($selltxt);
        $this->view->purchasetxt = base64_encode($purchasetxt);
        $this->view->title = '个人信息完善-用户中心-丰收汇-高价值农产品交易服务商';
        $this->view->keywords = '丰收汇,农业,农产品,农副产品,农产品批发,农产品电商,农产品现货,农产品交易,农产品加工,农产品价格';
        $this->view->descript = '丰收汇是依托中国农业互联网高科技综合服务商云农场的丰富资源和先进技术而建立的中国最专业的网上农产品服务平台，
        为中国农村专业合作社、中小农商企业等提供农产品信息发布、行情资讯、网上商城、金融服务、定向对接、安全模型、订单种植等专业化服务，
        旨在成为全球高价值农产品交易服务商。';
    }

    /**
     * 保存个人信息
     * @return [type] [description]
     */
    public function saveAction() {
        
         
        $user_id = $this->getUserID();

        if(!$user_id){
         echo "<script>alert(登录超时);location.href='/member/login/index/';</script>";die;
        }
        $areas = $this->request->getPOST('areas', 'int', 0);
        $mgoodscate = $this->request->getPOST('mgoodscate', 'int', 0);
        $name = $this->request->getPOST('name', 'string', '');
        $purchasecate = $this->request->getPOST('purcateid', 'string', '');
        $sellcate =$this->request->getPOST('sellcateid', 'string','');
        $sex = $this->request->getPOST('sex', 'int',0);
        $province_id = $this->request->getPOST('province_id', 'int',0);
        $city_id = $this->request->getPOST('city_id', 'int',0);
        $district_id = $this->request->getPOST('district_id', 'int',0);
        $town_id = $this->request->getPOST('town_id', 'int',0);
        
        if( !$areas&&!$mgoodscate&&!$name&&!$purchasecate && !$sellcate ) {
            echo "<script>alert('参数错误');location.href='/member/login/index/';</script>";die;
        }
        if(!($users = Users::findFirstByid($user_id))){
           echo "<script>alert('没有此用户');location.href='/member/perfect/index/';</script>";die;
        }
        $users->areas = $areas ? $areas : $users->areas;
        $users->province_id=$province_id;
        $users->city_id=$city_id;
        $users->district_id=$district_id;
        $users->town_id=$town_id;
        $users->village_id=$areas;
        

        if(!$users->save()){

             echo "<script>alert('没有此用户');location.href='/member/perfect/index/';</script>";die;
        }
         
        $uext = UsersExt::findFirstByuid($user_id);
         
        if(!$uext) {
            $uext = new UsersExt();
            $uext->uid = $user_id;
        }
        if($areas){
            $areas_name=Func::getCols(Areas::getFamily($areas), 'name', ',');
            $areas_info=Areas::getFamily($areas);
            $member = new Member();
            $yncuser=$member->perfect($areas_info,$user_id);
        }
        // var_dump($purchasecate);exit;
        $purchasecate=array_unique(explode(',' ,$purchasecate));
        $sellcate=array_unique(explode(",",$sellcate));
        UserAttention::find("user_id={$user_id} and attention_type in (1, 2)")->delete();
        foreach ($purchasecate as $v) {
            if(!$v) {
                continue;
            }
            $newuseratten=new UserAttention();
            $newuseratten->user_id=$user_id;
            $newuseratten->attention_type=2;
            $newuseratten->category_name=Category::selectBytocateName($v);
            $newuseratten->category_id=intval($v);
            $newuseratten->add_time=time();
            $newuseratten->last_update_time=time();
            $newuseratten->abbreviation=Category::selectBytocateabbrev($v);
            $newuseratten->parent_id=Category::selectBytocateparent_id($v);
            $newuseratten->save();
        }

        foreach ($sellcate as $v) {
            if(!$v) {
                continue;
            }
            $selluseratten=new UserAttention();
            $selluseratten->user_id=$user_id;
            $selluseratten->attention_type=1;
            $selluseratten->category_name=Category::selectBytocateName($v);
            $selluseratten->category_id=intval($v);
            $selluseratten->add_time=time();
            $selluseratten->last_update_time=time();
            $selluseratten->abbreviation=Category::selectBytocateabbrev($v);
            $selluseratten->parent_id=Category::selectBytocateparent_id($v);
            $selluseratten->save();
            
        }

        // if(empty($purchasecate[0])){
        //     $useratten=UserAttention::find("user_id={$user_id} and attention_type=2 ");
        //     if($useratten){
        //        $useratten->delete();
        //     }
        // }
        // if(empty($sellcate[0])){
        //     $useratten=UserAttention::find("user_id={$user_id} and attention_type=1 ");
        //     if($useratten){
        //        $useratten->delete();
        //     }
        // }
        // if(!empty($purchasecate[0])){
        //     foreach ($purchasecate as $key => $value) {
                
        //         $newuseratten=new UserAttention();
        //         $newuseratten->user_id=$user_id;
        //         $newuseratten->attention_type=2;
        //         $newuseratten->category_name=Category::selectBytocateName($value);
        //         $newuseratten->category_id=$value;
        //         $newuseratten->add_time=time();
        //         $newuseratten->last_update_time=time();
        //         $newuseratten->abbreviation=Category::selectBytocateabbrev($value);
        //         $newuseratten->parent_id=Category::selectBytocateparent_id($value);
        //         $newuseratten->save();
        //     }
        // }
        // if(!empty($sellcate[0])){
        //     foreach ($sellcate as $key => $value) {

        //         $selluseratten=new UserAttention();
        //         $selluseratten->user_id=$user_id;
        //         $selluseratten->attention_type=1;
        //         $selluseratten->category_name=Category::selectBytocateName($value);
        //         $selluseratten->category_id=$value;
        //         $selluseratten->add_time=time();
        //         $selluseratten->last_update_time=time();
        //         $selluseratten->abbreviation=Category::selectBytocateabbrev($value);
        //         $selluseratten->parent_id=Category::selectBytocateparent_id($value);

        //         $selluseratten->save();
        //     }
        // }
        $uext->main_category=$mgoodscate;
        $uext->sex=$sex;
        $uext->name = $name ? $name : $uext->name ;
        $uext->areas_name =$areas ? $areas_name :  $uext->address ;
        $uext->address = $areas ? $areas_name : $uext->address;
        $uext->publish_set = $this->request->getPOST('plat', 'int', 0);
        
        if(!$uext->save()){
           echo "<script>alert('修改失败');location.href='/member/perfect/index/';</script>";die;
        }else{
           echo "<script>alert('修改成功');location.href='/member/perfect/index/';</script>";die;
        }
    }
   public function uploadfileAction(){
      
       if(!$this->my_get_browser()){
         die("很抱歉！您的浏览器版本过低，建议您使用IE9以上或chrome、firefox");
       }
       $sid = md5(session_id());
       $this->view->sid = $sid;
       $this->view->messagecount=0;
    }
    public function my_get_browser(){
    
        if(empty($_SERVER['HTTP_USER_AGENT'])){
            return true;
        }
        // if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'rv:11.0')){
        //     return true;
        // }
        if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 9.0')){
           return false;
        }
        if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 8.0')){
        
          return false;
        }
        if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 7.0')){
          return false;
        }
        if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 6.0')){
           return false;
        }
        if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'Firefox')){
            return true;
        }
        if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'Chrome')){
             return true;
        }
        if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'Safari')){
            return true;
        }
        if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'Opera')){
            return true;
        }
        if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'360SE')){
           return true;
        }
       
    }
    public function savefileAction(){
 
        $img=$_POST["img"];

     
        $images=explode(",",$img);
        $sid =$this->session->getID();
        if($img){
            $imagestr=str_replace(' ','',$images[1]);
            
            $jpg =base64_decode($imagestr);

            $tmppath= PUBLIC_PATH.Path::HeadPath();
            $newpath=Path::HeadPath();
            $filename=File::random(8).'.jpg';
            
            if (!file_exists($tmppath)) File::make_dir($tmppath);
            @file_put_contents($tmppath.$filename,$jpg);
            //上传U盘
            $this->upyunfile($tmppath.$filename,$newpath.$filename);
            // $datas["path"]=IMG_URL.$newpath.$filename;
            $user_id = $this->getUserID();
            if(!$user_id){
                $rs = array('state'=>1, 'msg'=>'登录超时');   
                die(json_encode($rs));
            }
            $uext = UsersExt::findFirstByuid($user_id);
            if(!$uext){
                $rs = array('state'=>3, 'msg'=>'用户不存在');   
                die(json_encode($rs));
            }
            $uext->picture_path=$newpath.$filename;
            if(!$uext->save()){
                $rs = array('state'=>4, 'msg'=>'头像保存失败');   
                die(json_encode($rs));
            }else{
                
                $rs = array('state'=>4, 'msg'=>'头像保存成功');   
                die(json_encode($rs));
            }
            $this->getMsg(parent::SUCCESS,$datas);
        }else{
            $rs = array('state'=>2, 'msg'=>'参数错误');   
            die(json_encode($rs));
        }
    }
    /**
     *   上传u盘云
     * @param  [type] $path       [原路径]
     * @param  [type] $publicpath [新路进]
     * @return [type]             [description]
     */
    public function  upyunfile($path,$publicpath){
    
        //上传upy
        $upyun = new UpYun();
     
        try{
          $test = @fopen($path,'r');
          $pathaa =$publicpath;
          $arr = $upyun->writeFile($pathaa, $test, true);
          
        }
        catch(Exception $e) {
            return $e->getCode().$e->getMessage();
        }
    }
    public function infoAction(){

        $user_id = $this->request->get('user_id');
        if(!$user_id){
            die("参数错误");
        }
        $user_id=base64_decode($user_id);

        $users = Users::findFirstById($user_id);

        if(!$users) {
            echo "<script>alert('用户不存在');location.href='/member/index'</script>";
            exit;
        }

        $farm_areas = $users->ext ? $users->ext->farm_areas :0;

        //查询所有分类信息
        if(isset($users->ext->main_category) && !$users->ext->main_category){
            $mgoods="暂无";
        }else{
            $cid = isset($users->ext->main_category);
            $category= Category::findFirst(" id= '{$cid}' ");
            if($category){
                $mgoods=$category->title;
            }else{
                $mgoods="暂无";
            }
       }

        if(isset($category) && $category) {
            $cate=Arrays::toTree($category->toArray(),"id","parent_id","child");
        }

        //print_r($cate);die;
        //查询关注的商品
        $cond[] = "user_id={$user_id} and attention_type=2 ";
        $cond['columns'] = " category_name,category_id";
        $purchasecate=UserAttention::find($cond)->toArray();
       

        //获取采购txt
        $purchasecatecount=UserAttention::count($cond);

        if($purchasecatecount>1){
             $purchasetxt='';
             foreach ($purchasecate as $key => $value) {
                $purchasetxt.="<em id='".$value['category_id']."' >".$value['category_name']."</em>";
             }
        }else{
            $purchasetxt="<em id='".$purchasecate[0]['category_id']."' >".$purchasecate[0]['category_name']."</em>";
        }
        
        //获取采购分类tid
        if($purchasecate&&isset($purchasecate[0]["category_name"])&&$purchasecate[0]["category_name"]!=''){
           $purchasecate =$purchasecate;  
           $purchasecateid=Arrays::getCols($purchasecate,"category_id",",");
        }else{
          $purchasecate=array();
           $purchasecateid='';
        }

        //获取分类id
        $cond1[] = "user_id={$user_id} and attention_type=1 ";
        $cond1['columns'] = " category_name,category_id";
        $sellcate=UserAttention::find($cond1)->toArray();
        
        if(isset($sellcate[0]["category_name"])&&$sellcate[0]["category_name"]!=''){
           $sellcate =$sellcate;  
           $sellcateid=Arrays::getCols($sellcate,"category_id",",");
        }else{
           $sellcate=array();
           $sellcateid='';
        }
        //获取分类txt

        $sellcatecount=UserAttention::count($cond1);
        
        if($sellcatecount>1){
             $selltxt='';
             foreach ($sellcate as $key => $value) {
                $selltxt.="<em id='".$value['category_id']."' >".$value['category_name']."</em>";
             }
        }else{
            $selltxt="<em id='".$sellcate[0]['category_id']."' >".$sellcate[0]['category_name']."</em>";
        }
        
        $this->view->curAreas = $users->areas ? lAreas::ldareaname($users->areas) : '';
        $this->view->users = $users;
        $this->view->user_type = Users::$_user_type;
        $this->view->farm_areas = $farm_areas;
        $sid = md5(session_id());
        $this->view->sid = $sid;
        $this->view->cate = $cate;
        $this->view->mgoods=$mgoods;
        $this->view->sellcate = $sellcate;
        $this->view->purchasecate = $purchasecate;
        $this->view->sellcateid = $sellcateid;
        $this->view->purchasecateid = $purchasecateid;
       
        $this->view->selltxt = base64_encode($selltxt);
        $this->view->purchasetxt = base64_encode($purchasetxt);
        $this->view->title = '个人信息完善-用户中心-丰收汇-高价值农产品交易服务商';
        $this->view->keywords = '丰收汇,农业,农产品,农副产品,农产品批发,农产品电商,农产品现货,农产品交易,农产品加工,农产品价格';
        $this->view->descript = '丰收汇是依托中国农业互联网高科技综合服务商云农场的丰富资源和先进技术而建立的中国最专业的网上农产品服务平台，
        为中国农村专业合作社、中小农商企业等提供农产品信息发布、行情资讯、网上商城、金融服务、定向对接、安全模型、订单种植等专业化服务，
        旨在成为全球高价值农产品交易服务商。';
    }
}