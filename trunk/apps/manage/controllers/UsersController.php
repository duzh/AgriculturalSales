<?php
/**
 * 用户管理
 */
namespace Mdg\Manage\Controllers;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Mdg\Models\Users as Users;
use Mdg\Models\UsersExt as UsersExt;
use Mdg\Models\Category as Category;
use Mdg\Models as M;
use Lib\Member as Member;
use Lib\Auth as Auth;
use Lib\SMS as sms;
use Lib\Func as Func;
use Lib\Utils as Utils;
use Lib\Areas as lAreas;
use Lib\Pages as Pages;
use Lib\Arrays as Arrays;
use Lib\Path as Path;
use Lib\File as File;
use Lib\UpYun as UpYun;

class UsersController extends ControllerMember
{
    /**
     * 用户管理列表
     */
    
    public function indexAction() 
    {

        $page = $this->request->get('p', 'int', 1);
        $page_size = 10;

        $province = $this->request->get('province', 'string', '') ? explode(',',$this->request->get('province', 'string', ''))[1] : '';
        $city     = $this->request->get('city', 'string', '') ? explode(',',$this->request->get('city', 'string', ''))[1] : '' ;
        $qu       = $this->request->get('qu', 'string', '') ?  explode(',',$this->request->get('qu', 'string', ''))[1] : '' ;
        $xian     = $this->request->get('xian', 'string', '') ? explode(',',$this->request->get('xian', 'string', ''))[1] : '';
        $areas    = $this->request->get('areas', 'string', '') ? explode(',',$this->request->get('areas', 'string', ''))[1] : '' ;
        $sex      = $this->request->get('sex', 'int', 0);
        $main_category    = $this->request->get('main_category', 'int', 0);
        $id       = $this->request->get('id', 'int', 0);
        $plat     = $this->request->get('plat', 'string', 'sel');//发布平台
        //echo $sex;die;

        $where    = M\Users::conditions($this->request->get());
       
        if($province){
            $where.=" and areas_name like '%".$province."%'";
        }
        if($city){
            $where.=" and areas_name like '%".$city."%'" ;
        }
        if($qu){
            $where.= " and areas_name like '%".$qu."%'" ;
        }
        if($xian){
            $where.= " and areas_name like '%".$xian."%'" ;
        }
        if($areas){
            $where.=" and areas=".explode(',',$this->request->get('areas', 'string', ''))[0];
        } 

        if($sex){
            $sexwhere=$sex;
            $sex = $sex == 11 ? 0 : 1;
            $where.=" and sex={$sex}";
        }else{
            $sexwhere=0;
        }
        if($main_category){
            $where.=" and main_category={$main_category}";
        }
        if($id){
            $where.=" and id={$id}";
        }
        //发布平台
        if($plat && $plat!='sel'){
            if($plat == 'all') {
                $plat = 0;
            }
            $where.=" and publish_set = {$plat} ";
        }
       

        $offst =  intval(($page - 1) * $page_size);
       
        $total =$this->db->query("select count(id) as count from users join  users_ext  on id=uid  where ".$where." ORDER BY id DESC ")->fetchArray()['count'];
        
        $sql   = "select * from users join  users_ext  on id=uid  where ".$where." ORDER BY regtime DESC limit {$offst} , {$page_size}";
        
        $data  = $this->db->query($sql)->FetchAll();
        /*foreach($data as $k=>$v){
            $data[$k]['user_attention'] = self::getuserattention($v['id']);
        }*/
                  
        $pages['total_pages'] = ceil($total / $page_size);
        $pages['current']     = $page;
        $pages['total']       = $total;
        $pages                = new Pages($pages);
        $pages                = $pages->show(3);
        $this->view->data     = $data;
        $this->view->pages    = $pages;
        $this->view->current  = $page;
        $this->view->usertype = Users::$_user_type1;
        $category= M\Category::find(" is_show=1 ")->toArray();
        $cate=Arrays::toTree($category,"id","parent_id","child");
        //搜索选中数据
        $this->view->username  = $this->request->get('username', 'string', '');
        $this->view->stime     = $this->request->get('stime', 'string', '');
        $this->view->etime     = $this->request->get('etime', 'string', '');
        $this->view->user_type = $this->request->get('usertype', 'string', '');
        $this->view->sex = $sexwhere;
        $this->view->cate = $cate;
        $this->view->main_category = $main_category;
        $this->view->id = $id;
        // 发布平台
        $this->view->plat       = M\Users::$_plat;
        $this->view->plat_param = $plat;
        $this->view->curAreas = "'{$province}','"."{$city}','"."{$qu}','"."{$xian}','"."{$areas}'";
    }
    /**
     * 用户详细信息
     * @return [type] [description]
     */
    public function infoAction() 
    {
        $this->view->username = $this->getUsername();
    }
    /**
     * 新增用户
     */
    
    public function newAction() 
    {   
        $category= M\Category::find(" is_show=1 ")->toArray();
        $cate=Arrays::toTree($category,"id","parent_id","child");
        $this->tag->setDefault("usertype", "1");
        $this->view->cate = $cate;
        //发布平台
        $this->view->plat = M\Users::$_plat;

    }

    public function lookAction($id=0){

        if(!$id){
            parent::msg('数据错误','/manage/users/index');
            // $this->flash->error("数据错误");
            // return $this->dispatcher->forward(array(
            //     "controller" => "users",
            //     "action" => "index"
            // ));
        }
        $user = Users::findFirstByid($id);

        # 保留分页数据
        $this->session->USERS_REFERER = $_SERVER['HTTP_REFERER'];

        if (!$user) 
        {
            parent::msg('此用户不存在！','/manage/users/index');
            // $this->flash->error("此用户不存在！");
            // return $this->dispatcher->forward(array(
            //     "controller" => "users",
            //     "action" => "index"
            // ));
        }

        $category= M\Category::find(" is_show=1 ")->toArray();
        $cate=Arrays::toTree($category,"id","parent_id","child");
        
        $user_attention_sell = M\UserAttention::find(" user_id={$id} and attention_type=1");
        if($user_attention_sell){
            $category_sell_name = Arrays::getCols($user_attention_sell->toArray(),"category_name",",");
        }

        $user_attention_pu = M\UserAttention::find(" user_id={$id} and attention_type=2");
        if($user_attention_pu){
            $category_pu_name = Arrays::getCols($user_attention_pu->toArray(),"category_name",",");
        }

        $this->view->sex = $user->ext->sex;
        $this->view->picture_path = $user->ext->picture_path;
        $this->view->main_category = isset($user->ext->main_category) ? $user->ext->main_category : 0 ;
        $this->view->id = $user->id;
        $this->view->user = $user;
        $this->view->name = $user->ext->name;
        $this->view->area_name = $user->ext->areas_name;
        $this->view->cate = $cate;
        $this->view->category_sell_name = $category_sell_name;
        $this->view->category_pu_name = $category_pu_name;
        // 发布平台
        $this->view->plat       = M\Users::$_plat;
        $this->view->publish_set= $user->ext->publish_set;
        //print_r($user->toArray());die;
        $this->view->curAreas = lAreas::ldData($user->areas);
    }
    /**
     * 编辑用户
     *
     * @param string $id
     */
    
    public function editAction($id=0,$p=1) 
    {

        $user = Users::findFirstByid($id);

        # 保留分页数据
        $this->session->USERS_REFERER = $_SERVER['HTTP_REFERER'];

        if (!$user) 
        {
            parent::msg('此用户不存在！','/manage/users/index');
            // $this->flash->error("此用户不存在！");
            // return $this->dispatcher->forward(array(
            //     "controller" => "users",
            //     "action" => "index"
            // ));
        }

        $category= M\Category::find(" is_show=1 ")->toArray();
        $cate=Arrays::toTree($category,"id","parent_id","child");
        //查询关注的商品
        $cond[] = "user_id={$id} and attention_type=2 ";
        $cond['columns'] = " category_name,category_id";
        $purchasecate=M\UserAttention::find($cond)->toArray();
      
        if(isset($purchasecate[0]["category_name"])&&$purchasecate[0]["category_name"]!=''){
           $purchasecate =$purchasecate;  
           $purchasecateid=Arrays::getCols($purchasecate,"category_id",",");
        }else{
          $purchasecate=array();
           $purchasecateid='';
        }

        $cond1[] = "user_id={$id} and attention_type=1 ";
        $cond1['columns'] = " category_name,category_id";
        $sellcate=M\UserAttention::find($cond1)->toArray();
        
        if(isset($sellcate[0]["category_name"])&&$sellcate[0]["category_name"]!=''){
           $sellcate =$sellcate;  
           $sellcateid=Arrays::getCols($sellcate,"category_id",",");
        }else{
           $sellcate=array();
           $sellcateid='';
        }
        $this->tag->setDefault("name", $user->ext->name);
        $this->tag->setDefault("id", $user->id);
        $this->tag->setDefault("mobile", $user->username);
        $this->tag->setDefault("address", $user->ext->address);
        $this->tag->setDefault("usertype", $user->usertype);
        $this->tag->setDefault("goods", $user->ext->goods);
        $this->view->sex = $user->ext->sex;
        $this->view->picture_path = $user->ext->picture_path;
        $this->view->main_category = $user->ext->main_category;
        $this->view->id = $user->id;
        $this->view->user = $user;
        $this->view->cate = $cate;
        $this->view->sellcate = $sellcate;
        $this->view->purchasecate = $purchasecate;
        $this->view->sellcateid = $sellcateid;
        $this->view->purchasecateid = $purchasecateid;
        // 发布平台
        $this->view->plat       = M\Users::$_plat;
        $this->view->publish_set= M\UsersExt::findFirst("uid='{$id}'")->toArray()['publish_set'];
        //print_r($user->toArray());die;
        $this->view->curAreas = lAreas::ldData($user->areas);
    }
    /**
     * 新增用户保存
     */
    
    public function createAction() 
    {   

        
        $sid =$this->session->getID();
        $plat = $this->request->getPost('plat', 'int', 0);
        $username = $this->request->get('mobile', 'string', '');
        $password = $this->request->get('password', 'string', '');
        $usertype = $this->request->get('usertype', 'int', 0);
        $areas = $this->request->getPost('areas_name', 'int', 0);
        $purchasecate = $this->request->getPOST('purcateid', 'string', '');
        $sellcate = $this->request->getPOST('sellcateid', 'string','');

        $province_id = $this->request->getPOST('province', 'int',0);
        $city_id = $this->request->getPOST('city', 'int',0);
        $town = $this->request->getPOST('town', 'int',0);
        $zhen = $this->request->getPOST('zhen', 'int',0);

        $member = new Member();
        $synuser = $member->register($username, $password);
        
        if ($synuser) 
        {
            $salt = Auth::random(6, 1);
            $user = new Users();
            $user->id = $synuser['id'];
            $user->username = $username;
            $user->usertype = $usertype;
            $user->regtime = time();
            $user->lastlogintime = time();
            $user->encodePwd($password, $salt);
            $user->member_type = 1;
            
            
            $user->province_id=$province_id;
            $user->city_id=$city_id;
            $user->district_id=$town;
            $user->town_id=$zhen;
            $user->village_id=$areas;
            
            if (!$user->create()) 
            {
                return $this->dispatcher->forward(array(
                    "controller" => "users",
                    "action" => "index"
                ));
            }
            $user->regip = Utils::getIP();
            $user->lastloginip = Utils::getIP();
            $user->areas = $areas;
            
            if ($user->save()) 
            {
                //添加到附属表
                $tmpfile = M\TmpFile::findFirstBysid($sid);
                $usersext = new M\UsersExt();
                $usersext->uid = $user->id;
                $usersext->name = $this->request->getPost('name', 'string', '');
                $usersext->areas_name = Func::getCols(M\AreasFull::getFamily($areas) , 'name', ',');
                $usersext->address = Func::getCols(M\AreasFull::getFamily($areas) , 'name', ',');
                //$usersext->goods = $this->request->getPost("goods", 'string', '');
                $usersext->sex = intval($this->request->getPost('sex', 'int', 0));
                $usersext->main_category = intval($this->request->getPost('main_category', 'int', 0));
                $usersext->publish_set = $plat;
                if($tmpfile){
                   $usersext->picture_path = $tmpfile->file_path;  
                }
                $usersext->save();

                $purchasecate=array_unique(explode(",",$purchasecate));
                $sellcate=array_unique(explode(",",$sellcate));
                if(!empty($purchasecate[0])){
                    $useratten=M\UserAttention::find("user_id={$user->id} and attention_type=2 ");
                    if($useratten){
                       $useratten->delete();
                    }
                    }
                    if(!empty($sellcate[0])){
                        $useratten=M\UserAttention::find("user_id={$user->id} and attention_type=1 ");
                        if($useratten){
                           $useratten->delete();
                        }
                    }
                if(!empty($purchasecate[0])){
                    foreach ($purchasecate as $key => $value) {
                        $newuseratten=new M\UserAttention();
                        $newuseratten->user_id=$user->id;
                        $newuseratten->attention_type=2;
                        $newuseratten->category_name=Category::selectBytocateName($value);
                        $newuseratten->category_id=$value;
                        $newuseratten->add_time=time();
                        $newuseratten->last_update_time=time();
                        $newuseratten->save();
                    }
                }
                if(!empty($sellcate[0])){
                    foreach ($sellcate as $key => $value) {
                        $selluseratten=new M\UserAttention();
                        $selluseratten->user_id=$user->id;
                        $selluseratten->attention_type=1;
                        $selluseratten->category_name=Category::selectBytocateName($value);
                        $selluseratten->category_id=$value;
                        $selluseratten->add_time=time();
                        $selluseratten->last_update_time=time();
                        $selluseratten->save();
                    }
                }
            }


            Func::adminlog("丰收汇-添加会员：{$usersext->name}",$this->session->adminuser['id']);
            parent::msg('添加成功','/manage/users/index');
            // echo "<script>alert('添加成功')</script>";
            // $this->response->redirect("users/index")->sendHeaders();
        }
        else
        {
            parent::msg('用户已经存在','/manage/users/index');
            // echo "<script>alert('用户已经存在')</script>";
            // $this->response->redirect("users/index")->sendHeaders();
        }
        exit;
    }
    /**
     * 保存用户信息
     *
     */
    
    public function saveAction() 
    {
        $p = $this->session->USERS_REFERER;
        
        if (!$this->request->isPost()) 
        {
            return $this->dispatcher->forward(array(
                "controller" => "users",
                "action" => "index"
            ));
        }

        $province_id = $this->request->getPOST('province', 'int',0);
        $city_id = $this->request->getPOST('city', 'int',0);
        $town = $this->request->getPOST('town', 'int',0);
        $zhen = $this->request->getPOST('zhen', 'int',0);
        $id = $this->request->getPost("id");
        $user = Users::findFirstByid($id);
       
        if (!$user) 
        {
            parent::msg('此用户不存在','/manage/users/index');
            // $this->flash->error("此用户不存在" . $id);
            // return $this->dispatcher->forward(array(
            //     "controller" => "users",
            //     "action" => "index"
            // ));
        }
        // $user->mobile = $this->request->getPost("mobile", 'string', '');
        $user->usertype = $this->request->getPost("usertype", 'int', 0);
        $areas = $this->request->getPost('areas_name', 'int', 0);
        $user->areas = $areas;
        $purchasecate = $this->request->getPOST('purcateid', 'string', '');
        $sellcate = $this->request->getPOST('sellcateid', 'string','');
        
        $user->province_id=$province_id;
        $user->city_id=$city_id;
        $user->district_id=$town;
        $user->town_id=$zhen;
        $user->village_id=$areas;
        $user->is_broker=$user->is_broker;
       
        if (!$user->save()) 
        {
            parent::msg('修改会员信息失败！',"/manage/users/edit/{$id}");
            // $this->flash->error('修改会员信息失败！');
            // return $this->dispatcher->forward(array(
            //     "controller" => "users",
            //     "action" => "edit",
            //     "params" => array(
            //         $user->id
            //     )
            // ));
        }
        
        $userext = M\UsersExt::findFirstByuid($id);
        $userext->name = $this->request->getPost("name", 'string', '');
        $userext->goods = $this->request->getPost("goods", 'string', '');
        $userext->areas_name = Func::getCols(M\AreasFull::getFamily($areas) , 'name', ',');
        $userext->address = Func::getCols(M\AreasFull::getFamily($areas) , 'name', ',');
        $userext->sex = $this->request->getPOST('sex', 'int',0);
        $userext->main_category = intval($this->request->getPOST('main_category', 'int',0));
        //发布平台
        $userext->publish_set = $this->request->getPost("plat", 'int', 0);
        $userext->save();

        $purchasecate=array_unique(explode(",",$purchasecate));
        $sellcate=array_unique(explode(",",$sellcate));

       
        if(!empty($purchasecate[0])){
            $useratten=M\UserAttention::find("user_id={$id} and attention_type=2 ");
            if($useratten){
               $useratten->delete();
            }
        }
        if(!empty($sellcate[0])){
            $useratten=M\UserAttention::find("user_id={$id} and attention_type=1 ");
            if($useratten){
               $useratten->delete();
            }
        }
        if(!empty($purchasecate[0])){
            foreach ($purchasecate as $key => $value) {
                $newuseratten=new M\UserAttention();
                $newuseratten->user_id=$id;
                $newuseratten->attention_type=2;
                $newuseratten->category_name=Category::selectBytocateName($value);
                $newuseratten->category_id=$value;
                $newuseratten->add_time=time();
                $newuseratten->last_update_time=time();
                $newuseratten->save();
            }
        }
        if(!empty($sellcate[0])){
            foreach ($sellcate as $key => $value) {
                $selluseratten=new M\UserAttention();
                $selluseratten->user_id=$id;
                $selluseratten->attention_type=1;
                $selluseratten->category_name=Category::selectBytocateName($value);
                $selluseratten->category_id=$value;
                $selluseratten->add_time=time();
                $selluseratten->last_update_time=time();
                $selluseratten->save();
            }
        }
        if (!empty($this->request->getPost("confirmpassword"))) 
        {
            $password = $this->request->getPost('password', 'string', '');
            $repassword = $this->request->getPost('confirmpassword', 'string', '');
            
            if ($password == $repassword) 
            {
                $result = Users::changePWD($user->username, $password);
                
                if ($result) 
                {
                    parent::msg('密码修改成功',$p);
                    // echo "<script>alert('密码修改成功')</script>";
                    // echo "<script>location.href='{$p}'</script>";exit;
                    
                    // $this->response->redirect("users/index")->sendHeaders();
                }
            }
        }
        
        Func::adminlog("修改会员：{$userext->name}",$this->session->adminuser['id']);
        $p = $this->session->USERS_REFERER;
        parent::msg('修改成功',$p);
        // $this->flash->success("修改成功");
        // echo "<script>location.href='{$p}'</script>";exit;
        // $this->response->redirect("users/index?p={$p}")->sendHeaders();

    }
    /**
     * 删除用户
     *
     * @param string $id
     */
    
    public function deleteAction($id) 
    {
        $user = Users::findFirstByid($id);
        
        if (!$user) 
        {
            parent::msg('用户未找到','/manage/users/index');
            // $this->flash->error("用户未找到");
            // return $this->dispatcher->forward(array(
            //     "controller" => "users",
            //     "action" => "index"
            // ));
        }
        
        if (!$user->delete()) 
        {
            parent::msg($message,'/manage/users/search');
            // foreach ($user->getMessages() as $message) 
            // {
            //     $this->flash->error($message);
            // }
            // return $this->dispatcher->forward(array(
            //     "controller" => "users",
            //     "action" => "search"
            // ));
        }
        $ext = UsersExt::findFirstByuid($id);
        $ext->delete();
        parent::msg('用户删除成功','/manage/users/index');
        // $this->flash->success("用户删除成功");
        // return $this->dispatcher->forward(array(
        //     "controller" => "users",
        //     "action" => "index"
        // ));
    }



    public function uploadfileAction($user_id = 0){
       $operating=$this->request->get('operating','string','');
       $sid = md5(session_id());
       $this->view->sid = $sid;
       $this->view->messagecount=0;
       $this->view->user_id=$user_id;
       $this->view->operating=$operating;
    }
    public function savefileAction($user_id=0,$operating=''){

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
            if($operating!='new'){
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

                $rs = array('state'=>4, 'msg'=>'头像保存成功', 'path'=>constant('IMG_URL').$newpath.$filename);   
                die(json_encode($rs));
            }
            $this->getMsg(parent::SUCCESS,$datas);
            }else{
            $tmpfile = new M\TmpFile();
            $tmpfile->sid = $sid;
            $tmpfile->file_path = $newpath.$filename;
            $tmpfile->save();
            $rs = array('state'=>4, 'msg'=>'头像保存成功', 'path'=>constant('IMG_URL').$newpath.$filename);   
            die(json_encode($rs));

        }
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

    /**
     * 经纪人列表
     */
    public function brokerAction(){
        $page = $this->request->get('p', 'int', 1);
        $page_size = 10;

        $province = $this->request->get('province', 'string', '') ? explode(',',$this->request->get('province', 'string', ''))[1] : '';
        $city     = $this->request->get('city', 'string', '') ? explode(',',$this->request->get('city', 'string', ''))[1] : '' ;
        $qu       = $this->request->get('qu', 'string', '') ?  explode(',',$this->request->get('qu', 'string', ''))[1] : '' ;
        $xian     = $this->request->get('xian', 'string', '') ? explode(',',$this->request->get('xian', 'string', ''))[1] : '';
        $areas    = $this->request->get('areas', 'string', '') ? explode(',',$this->request->get('areas', 'string', ''))[1] : '' ;
        $sex      = $this->request->get('sex', 'int', 0);
        $main_category    = $this->request->get('main_category', 'int', 0);
        $plat = $this->request->get('plat', 'string', 'sel');
        $id    = $this->request->get('id', 'int', 0);
        //echo $sex;die;

        $where    = M\Users::conditions($this->request->get());

        if($province){
            $where.=" and areas_name like '%".$province."%'";
        }
        if($city){
            $where.=" and areas_name like '%".$city."%'" ;
        }
        if($qu){
            $where.= " and areas_name like '%".$qu."%'" ;
        }
        if($xian){
            $where.= " and areas_name like '%".$xian."%'" ;
        }
        if($areas){
            $where.=" and areas=".explode(',',$this->request->get('areas', 'string', ''))[0];
        }
        #筛选经纪人
        $where.= " and is_broker=1" ;
        if($sex){
            $sexwhere=$sex;
            $sex = $sex == 11 ? 0 : 1;
            $where.=" and sex={$sex}";
        }else{
            $sexwhere=0;
        }
        if($main_category){
            $where.=" and main_category={$main_category}";
        }
        if($id){
            $where.=" and id={$id}";
        }
        //发布平台
        if($plat && $plat!='sel'){
            if($plat == 'all') {
                $plat = 0;
            }
            $where.=" and publish_set = {$plat} ";
        }
        //echo $where;die;
        $offst =  intval(($page - 1) * $page_size);

        $total =$this->db->query("select count(id) as count from users join  users_ext  on id=uid  where ".$where." ORDER BY id DESC ")->fetchArray()['count'];

        $sql   = "select * from users join  users_ext  on id=uid  where ".$where." ORDER BY id DESC limit {$offst} , {$page_size}";

        $data  = $this->db->query($sql)->FetchAll();
        /*foreach($data as $k=>$v){
            $data[$k]['user_attention'] = self::getuserattention($v['id']);
        }*/
        $pages['total_pages'] = ceil($total / $page_size);
        $pages['current']     = $page;
        $pages['total']       = $total;
        $pages                = new Pages($pages);
        $pages                = $pages->show(1);
        $this->view->data     = $data;
        $this->view->pages    = $pages;
        $this->view->current  = $page;
        $this->view->usertype = Users::$_user_type1;
        $category= M\Category::find(" is_show=1 ")->toArray();
        $cate=Arrays::toTree($category,"id","parent_id","child");
        //搜索选中数据
        $this->view->username  = $this->request->get('username', 'string', '');
        $this->view->stime     = $this->request->get('stime', 'string', '');
        $this->view->etime     = $this->request->get('etime', 'string', '');
        $this->view->user_type = $this->request->get('usertype', 'string', '');
        $this->view->sex = $sexwhere;
        $this->view->cate = $cate;
        $this->view->main_category = $main_category;
        $this->view->id = $id;
        // 发布平台
        $this->view->plat       = M\Users::$_plat;
        $this->view->plat_param = $plat;

        $this->view->curAreas = "'{$province}','"."{$city}','"."{$qu}','"."{$xian}','"."{$areas}'";
    }

    /**
     * 使用手机号查询user信息
     */
    public function getusersAction($mobile){
        $status = false;
        $data = null;
        try{
            if(!preg_match("/1[34578]{1}\d{9}$/",$mobile))throw new \Exception('手机号错误');
            $userdata = M\Users::findFirst("username={$mobile}"); #获取指定供应商为1的
            if(!is_object($userdata)) throw new \Exception('帐号不存在');
          
            if($userdata->is_broker) throw new \Exception('此用户已经是经纪人了');
            $user_info = M\UserInfo::findFirstByuser_id($userdata->id);
            if(is_object($user_info)) throw new \Exception('此用户已经认证过其他身份不能添加为经纪人');
            $userYnpInfo = M\UserYnpInfo::findFirstByuser_id($userdata->id);
            if(is_object($userYnpInfo)) throw new \Exception('已绑定云农宝');
            $status = true;
            $data['phone'] = $userdata->username;
            $data['user_id'] = $userdata->id;
            $data['username'] = urlencode(M\UsersExt::getusername($userdata->id));
            echo json_encode(array('status'=>$status ,'data'=>$data ));
        }catch (\Exception $e){
            echo json_encode(array('status'=>$status,'msg'=>$e->getMessage(),'data'=>$data ));
        }
        exit;
    }

    /**
     * 设置帐号为直营经纪人
     */
    public function setbrokerAction(){
        try{
            $uid = $this->request->get('uid', 'string', '');
            if (!$uid)throw new \Exception('error');
            $user = M\Users::findFirstByid($uid);
            if(is_object($user)){
                $user->is_broker = 1;#设置是否是经纪人为是
                $res = $user->save();
                echo $res;
            }
            else{
                throw new \Exception('error');
            }
        }catch (\Exception $e){
            echo false;
        }
        exit;
    }

    /**
     * 取消直营经纪人
     */
    public function cancelbrokerAction($uid){
        try{
            if (!$uid)throw new \Exception('error');
            $user = M\Users::findFirstByid($uid);
            $order=M\Orders::count(" state not in (1,6) and suserid={$uid} ");
            
            if($order>0){
                $this->msg("有未完成的订单,不能取消","/manage/users/broker");
            }
            
            if(is_object($user)){
                $user->is_broker = 0;#设置是否是经纪人为否
                $res = $user->save();
                if($res){
                    //取消成功以后  把所有商品下架
                    $sql=" update sell set state=0 where uid={$uid} ";
                    $db = $GLOBALS['di']['db'];
                    $db->execute($sql);
                    parent::msg('用户取消成功','/manage/users/broker');
                    // $this->flash->success("用户取消成功");
                    // return $this->dispatcher->forward(array(
                    //     "controller" => "users",
                    //     "action" => "broker"
                    // ));
                }
            }
            else{
                throw new \Exception('error');
            }
        }catch (\Exception $e){
            parent::msg('用户取消失败','/manage/users/broker');
            // $this->flash->success("用户取消失败");
            // return $this->dispatcher->forward(array(
            //     "controller" => "users",
            //     "action" => "broker"
            // ));
        }

    }


}
