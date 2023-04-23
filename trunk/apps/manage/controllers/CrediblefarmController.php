<?php
namespace Mdg\Manage\Controllers;
use Mdg\Models\CredibleFarmInfo as CredibleFarmInfo;
use Mdg\Models as M;
use Lib\Pages as Pages;
use Lib\Func as Func;

class CrediblefarmController extends ControllerBase
{
    /**
     * 可信农场列表
     * @return [type] [description]
     */
    
    public function indexAction() 
    {

        $status = $this->request->get('status', 'int', 0);
        $is_home_page = $this->request->get('is_home_page', 'int', 0);
        $farm_name = $this->request->get('farm_name', 'string', '');
        $province = $this->request->get('province', 'string', '') ? explode(',',$this->request->get('province', 'string', ''))[1] : '';
        $city     = $this->request->get('city', 'string', '') ? explode(',',$this->request->get('city', 'string', ''))[1] : '' ;
        $qu       = $this->request->get('qu', 'string', '') ?  explode(',',$this->request->get('qu', 'string', ''))[1] : '' ;
        $xian     = $this->request->get('xian', 'string', '') ? explode(',',$this->request->get('xian', 'string', ''))[1] : '';
        $areas    = $this->request->get('areas', 'string', '') ? explode(',',$this->request->get('areas', 'string', ''))[1] : '' ;
        $page = $this->request->get('p', 'int', 1);
        $page = $page > 0 ? $page : 1;
        $page_size = 10;
        $where="1=1";

        if($status){
            $status = $status == 11 ? 1 : 0;
            $where.=" and status={$status}";
        }
        if($is_home_page){
            $where.=" and is_home_page=1";
        }

        if($farm_name){
            $where.=self::GetwhereFarmname($farm_name);
        }
        if($province){
            $selectarea = M\UserFarm::selectByArea($province,$city,$qu,$xian,$areas);
            //print_r($selectarea);die;
            $where.=" $selectarea";
        }
        //echo $where;die;
        $total = CredibleFarmInfo::count($where);
        $offst = intval(($page - 1) * $page_size);
        $data = CredibleFarmInfo::find($where . " ORDER BY last_update_time DESC limit {$offst} , {$page_size} ");

        if($data->toArray()){
            $data = $data->toArray();
            foreach($data as $k=>$v){
                $data[$k]['address'] = self::GetFarmAddress($v['user_id']);     //获取农场地区
                $data[$k]['goods_name'] = self::GetFarmGoods($v['user_id']);        //获取主营产品
                $data[$k]['farmer_user_name'] = $v["user_name"]?$v["user_name"]:self::GetFarmUserName($v['user_id']);        //获取农场主
                $time=self::getFarmtime($v['user_id']);        //获取可信农场名称
                if($time){
                 $data[$k]['add_time'] = $time;
                }
                $data[$k]["farmname"]=$v["farm_name"]?$v["farm_name"]:self::GetFarmname($v['user_id']);
            }
        }
// print_R($data);exit;
        $pages['total_pages'] = ceil($total / $page_size);
        $pages['current'] = $page;
        $pages['total'] = $total;
        $pages = new Pages($pages);
        $pages = $pages->show(1);
        $this->view->current = $page;
        $this->view->data = $data;
        $this->view->pages = $pages;
        $this->view->status = $this->request->get('status', 'int', 0);
        $this->view->farm_name = $farm_name;
        $this->view->curAreas = "'{$province}','"."{$city}','"."{$qu}','"."{$xian}','"."{$areas}'";
    }

    /**
     * 推荐
     * @param  [type]  $id   供应Id
     * @param  integer $page [description]
     */
    public function recommandAction(){
        $id = $this->request->get("id", 'int', 0);
        $CredibleFarmInfo = CredibleFarmInfo::findFirstByid($id);

        if($CredibleFarmInfo){
            $CredibleFarmInfo->is_home_page = $CredibleFarmInfo->is_home_page==1?0:1;
            $CredibleFarmInfo->last_update_time = time();           
        }

        if($CredibleFarmInfo->save()){
            $msg = '操作成功';
        }else{
            $msg = '操作失败';
        }
        die(json_encode($msg));
    }

    /**
     * 禁止访问
     * @param  integer $id [description]
     * @return [type]      [description]
     */
    public function callAction($id=0){
        if(!$id){
            echo "<script>alert('数据异常');location.href='/manage/crediblefarm/index'</script>";
            exit;
        }
        $CredibleFarmInfo = CredibleFarmInfo::findFirstByid($id);
        if(!$CredibleFarmInfo){
            echo "<script>alert('数据异常');location.href='/manage/crediblefarm/index'</script>";     
        }
        $CredibleFarmInfo->status = $CredibleFarmInfo->status==1?0:1;
        $CredibleFarmInfo->last_update_time = time();
        if($CredibleFarmInfo->save()){
            Func::adminlog("可信农场禁止/开启访问：{$id}",$this->session->adminuser['id']);
            echo "<script>alert('操作成功');location.href='/manage/crediblefarm/index'</script>";    
        }else{
            echo "<script>alert('操作失败');location.href='/manage/crediblefarm/index'</script>";    
        }
        
    }



    /**
     * 可信农场基本信息
     * @param  integer $user_id [description]
     * @return [type]           [description]
     */
    public function editAction($user_id=0){
        $sid = $this->session->getId();
        //echo $user_id;die;
        if(!$user_id){
            echo "<script>alert('数据异常');location.href='/manage/crediblefarm/index'</script>";
            exit;
        }
        $CredibleFarmInfo = CredibleFarmInfo::findFirstByuser_id($user_id);
        if(!$CredibleFarmInfo){
            echo "<script>alert('数据异常');location.href='/manage/crediblefarm/index'</script>";   
            exit;  
        }

        $farmname = self::GetFarmname($CredibleFarmInfo->user_id);
        $farmdesc = self::GetFarmdesc($CredibleFarmInfo->user_id);
        //宣传图
        $advertisingmap = M\CredibleFarmPicture::find("user_id={$user_id} and type=4");

        $this->view->crediblefarminfo = $CredibleFarmInfo;
        $this->view->sid = $sid;
        $this->view->advertisingmap = $advertisingmap;
        $this->view->user_id = $user_id;
        $this->view->farmname = $farmname;
        $this->view->farmdesc = $farmdesc;
        $this->view->aspectRatiologo=157/77;
    }

    /**
     * 基本信息编辑保存
     * @return [type] [description]
     */
    public function saveAction(){
        $sid = $this->session->getId();
        $user_id = $this->request->getPost('user_id', 'int', 0);
        $farm_name = $this->request->getPost('farm_name', 'string', '');
        $desc = $this->request->getPost('desc', 'string', '');
        $custom_content = $this->request->getPost('custom_content');
        $crediblefarminfo = CredibleFarmInfo::findFirstByuser_id($user_id);
        if(!$crediblefarminfo){
            echo "<script>alert('修改失败!');location.href='/manage/crediblefarm/edit/{$user_id}'</script>";  
            exit;   
        }

        $user_info = M\UserInfo::findFirst("user_id={$user_id} and status=1");
        if($user_info){
            $user_farm = M\UserFarm::findFirst("user_id={$user_id} and credit_id={$user_info->credit_id}");
            if($user_farm){
                $user_farm->farm_name = $farm_name;
                $user_farm->describe = $desc;
                $user_farm->save();
            }         
        }
        
        $img=M\TmpFile::findFirst("sid = '{$sid}' and type = 35");
        $farmimg=M\TmpFile::findFirst("sid = '{$sid}' and type = 42");

        if(!empty($img->file_path)){
            $crediblefarminfo->logo_pic = $img->file_path;
        }
        if(!empty($farmimg->file_path)){
            $crediblefarminfo->img_pic = $farmimg->file_path;
        }
        $crediblefarminfo->farm_name = $farm_name;
        $crediblefarminfo->desc = $desc;
        $crediblefarminfo->custom_content = $custom_content;
        if(!$crediblefarminfo->save()){
            echo "<script>alert('修改失败!');location.href='/manage/crediblefarm/edit/{$user_id}'</script>";  
            exit; 
        }else{
            Func::adminlog("可信农场农场信息编辑：{$user_id}",$this->session->adminuser['id']);
            echo "<script>alert('修改成功!');location.href='/manage/crediblefarm/edit/{$user_id}'</script>";  
            exit; 
        }
        
    }

    /**
     * 宣传图
     * @param  integer $user_id [description]
     * @return [type]           [description]
     */
    public function advertAction($user_id=0){
        
        if(!$user_id){
            echo "<script>alert('数据异常');location.href='/manage/crediblefarm/index'</script>";
            exit;
        }
        $sid = $this->session->getId();
        M\TmpFile::clearOld($sid);
        $crediblefarmpicture = M\CredibleFarmPicture::find("user_id = {$user_id} and type = 4")->toArray();
        $count = M\CredibleFarmPicture::count("user_id = {$user_id} and type = 4");
        $this->view->count = $count;
        $this->view->crediblefarmpicture = $crediblefarmpicture;
        $this->view->sid = $sid;
        $this->view->user_id = $user_id;
        $this->view->title = '可信农场管理-宣传图';
        $this->view->aspectRatio=1187/426;
    }

    /**
     * 宣传图修改保存
     * @param  integer $user_id [description]
     * @return [type]           [description]
     */
    public function advertsaveAction(){
        $sid = $this->session->getId();
        $user_id = $this->request->getPost('user_id', 'int', 0);
        //$user_name=$this->session->user['name'];
        $tmpfile=M\TmpFile::find("type = 36 and sid = '{$sid}'")->toArray();
        foreach ($tmpfile as $key => $value) {
            $crediblefarmpicture = new M\CredibleFarmPicture();
            $crediblefarmpicture->user_id = $user_id;
            $crediblefarmpicture->picture_path = $value['file_path'];
            $crediblefarmpicture->title  = '';
            $crediblefarmpicture->desc = '';
            $crediblefarmpicture->type = 4;
            $crediblefarmpicture->status = 1;
            $crediblefarmpicture->add_time = time();
            $crediblefarmpicture->last_update_time = time();
            $crediblefarmpicture->picture_time = time();
            if(!$crediblefarmpicture->save()){
                print_r($crediblefarmpicture->getMessages());die;
            }
        }
            /*  清除 */
        M\TmpFile::clearOld($sid);
        Func::adminlog("可信农场宣传上传：{$user_id}",$this->session->adminuser['id']);
        echo "<script>alert('修改成功!');location.href='/manage/crediblefarm/advert/{$user_id}'</script>";  
    }

    /**
     * 农场介绍
     * @param  integer $user_id [description]
     * @return [type]           [description]
     */
    public function presentAction($user_id=0){
        if(!$user_id){
            echo "<script>alert('数据异常');location.href='/manage/crediblefarm/index'</script>";
            exit;
        }
        //图文介绍
        $sid = $this->session->getId();
        $graphic = M\CredibleFarmPicture::find("user_id={$user_id} and type=0");
        $crediblefarminfo = M\CredibleFarmInfo::findFirstByuser_id($user_id);
        $this->view->graphic = $graphic;
        $this->view->user_id = $user_id;
        $this->view->sid = $sid;
        $this->view->crediblefarminfo = $crediblefarminfo;
        $this->view->aspectRatio=844/250;
    }

    /**
     * 农场介绍编辑保存
     * @param  integer $user_id [description]
     * @return [type]           [description]
     */
    public function presentsaveAction(){
        $user_id = $this->request->getPost('user_id', 'int', 0);
        $sid = $this->session->getId();
        $title = $this->request->getPost('title','string','');
        $desc = $this->request->getPost('desc','string','');

        $tempfile=M\TmpFile::findFirst("type = 37 and sid = '{$sid}'");
        $credible_farm_picture=new M\CredibleFarmPicture();
        $credible_farm_picture->user_id =$user_id;
        $credible_farm_picture->picture_path = $tempfile->file_path;
        $credible_farm_picture->title = $title;
        $credible_farm_picture->desc = $desc;
        $credible_farm_picture->status = 1;
        $credible_farm_picture->type = 0;
        $credible_farm_picture->add_time = time();
        $credible_farm_picture->last_update_time = time();
        $credible_farm_picture->picture_time =time();
        $credible_farm_picture->save();
        /*  清除 */
        M\TmpFile::clearOld($sid);
        Func::adminlog("可信农场添加图文介绍：{$user_id}",$this->session->adminuser['id']);
        echo "<script>alert('修改成功!');location.href='/manage/crediblefarm/present/{$user_id}'</script>";  
    }

    /**
     * 自定义内容
     * @return [type] [description]
     */
    public function upsaveAction(){
        $sid = $this->session->getId();
        $user_id = $this->request->getPost('user_id', 'int', 0);
        $medesc = htmlspecialchars($this->request->getPost('News'));
        $crediblefarminfo=M\CredibleFarmInfo::findFirstByuser_id($user_id);
        if(!$crediblefarminfo){
            $crediblefarminfo=new M\CredibleFarmInfo();
        }
            $crediblefarminfo->custom_content = $medesc;
            $crediblefarminfo->last_update_time =time();
            $crediblefarminfo->save();

        Func::adminlog("可信农场自定义内容：{$user_id}",$this->session->adminuser['id']);
        echo "<script>alert('修改成功!');location.href='/manage/crediblefarm/present/{$user_id}'</script>"; 
    }

    /**
     * 发展足迹
     * @param  integer $user_id [description]
     * @return [type]           [description]
     */
    public function footprintAction($user_id=0){
        //足迹
        $sid = $this->session->getId();
        $footprints = M\CredibleFarmPicture::find("user_id={$user_id} and type=1");
        $this->view->footprints = $footprints;
        $this->view->user_id = $user_id;
        $this->view->sid = $sid;
        $this->view->aspectRatio=844/250;
    }

    /**
     * 发展足迹编辑保存
     * @param  integer $user_id [description]
     * @return [type]           [description]
     */
    public function footprintsaveAction(){
        $sid = $this->session->getId();
        $user_id = $this->request->getPost('user_id', 'int', 0);
        //$user_name=$this->session->user['name'];
        $title = $this->request->getPost('title','string','');
        $desc = $this->request->getPost('desc','string','');
        $year1 = $this->request->getPost('year1','string','');
        $month1 =$this->request->getPost('month1','string','');
        $day1  = $this->request->getPost('day1','string','');
        $time = strtotime($year1.$month1.$day1);

        $tempfile=M\TmpFile::findFirst("type = 38 and sid = '{$sid}'");
        $credible_farm_picture=new M\CredibleFarmPicture();
        $credible_farm_picture->user_id =$user_id;
        if($tempfile){
            $credible_farm_picture->picture_path = $tempfile->file_path;
        }
        $credible_farm_picture->title = $title;
        $credible_farm_picture->desc = $desc;
        $credible_farm_picture->status = 1;
        $credible_farm_picture->type = 1;
        $credible_farm_picture->add_time = time();
        $credible_farm_picture->last_update_time = time();
        $credible_farm_picture->picture_time =$time;
        $credible_farm_picture->save();
        /*  清除 */
        M\TmpFile::clearOld($sid);
        Func::adminlog("可信农场新增发展足迹：{$user_id}",$this->session->adminuser['id']);
        echo "<script>alert('修改成功!');location.href='/manage/crediblefarm/footprint/{$user_id}'</script>";  
    }

    /**
     * 产品推荐列表
     * @param  integer $user_id [description]
     * @return [type]           [description]
     */
    public function mainproductAction($user_id=0){
        if(!$user_id){
            echo "<script>alert('数据异常');location.href='/manage/crediblefarm/index'</script>";
            exit;
        }
        $userId = $user_id;

        $page = $this->request->get('p', 'int', 1);
        $page_size = 6;
        $where = "user_id={$user_id} AND is_recommend = 1";
        $total = M\CredibleFarmGoods::count($where);
        $offst = intval(($page - 1) * $page_size);
        
        $data = M\CredibleFarmGoods::find($where . " ORDER BY last_update_time DESC limit {$offst} , {$page_size} ")->toArray();
        $pages['total_pages'] = ceil($total / $page_size);
        $pages['current'] = $page;
        $pages['total'] = $total;
        $pages = new Pages($pages);
        $pages = $pages->show(1);
        $this->view->current = $page;
        $this->view->data = $data;
        $this->view->pages = $pages;
        $this->view->user_id = $user_id;
        $this->view->userId = $userId;
    }

    /**
     * 产品推荐
     * @return [type] [description]
     */
    public function recommframAction() {
        // 接收参数
        $userId = $this->request->getPost('userId', 'int', 0);
        $cidone = $this->request->getPost('cidone', 'int', 0);
        $cidtwo = $this->request->getPost('cidtwo', 'int', 0);
        $sellid = $this->request->getPost('sell_id', 'int', 0);
        $sell   = M\Sell::findFirstByid($sellid);

        // 参数校验
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
        $sell   = M\Sell::getSellInfo($sellid);
        
        // 入库主营产品表
        $farm   = new M\CredibleFarmGoods();
        $farm->sell_id          = $sell->id;
        $farm->user_id          = $userId;
        $farm->category_one     = $cidone;
        $farm->category_two     = $cidtwo;
        $farm->goods_name       = $sell->title;
        $farm->add_time         = time();
        $farm->last_update_time = time();
        $farm->picture_path     = $sell->thumb;
        $farm->is_recommend     = 1;
        
        if($farm->save()) {
            Func::adminlog("可信农场推荐产品：{$userId}",$this->session->adminuser['id']);
            echo json_encode(array('code'=>3,'result'=>'推荐成功'));
            exit;
        } else {
            echo json_encode(array('code'=>4,'result'=>'推荐失败'));
            exit;
        }
        
    }

    /**
     *  取消推荐
     */
    public function recommendCanselAction() {
        
        // 接收参数
        $userId     = $this->request->getPost('userId', 'int', 0);
        $id     = $this->request->getPost('id', 'int', 0);
        $farm   = M\CredibleFarmGoods::findFirstByid($id);
        
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
            Func::adminlog("可信农场取消推荐产品：{$userId}",$this->session->adminuser['id']);
            echo json_encode(array('code'=>3,'result'=>'取消成功'));
            exit;
        } else {
            echo json_encode(array('code'=>4,'result'=>'取消失败'));
            exit;
        }
    }

    /**
     * 种植过程--产品列表
     * @param  integer $user_id [description]
     * @return [type]           [description]
     */
    public function productprocessAction($user_id=0){
        //种植产品
        $planting = M\CredibleFarmGoodsplant::find("user_id={$user_id} and is_delete=0");
        $this->view->planting = $planting;
        $this->view->user_id = $user_id;
    }

    /**
     * 种植过程查看
     * @param  integer $goods_id [description]
     * @return [type]            [description]
     */
    public function farmplantAction($goods_id=0,$user_id=0){
        if(!$goods_id || !$user_id){
            echo "<script>alert('数据异常');location.href='/manage/crediblefarm/index'</script>";
            exit;
        }
        $sid = $this->session->getId();
        $page = $this->request->get('p', 'int', 1);
        $page_size = 5;
        $where="goods_id={$goods_id} and is_delete=0";
        $total = M\CredibleFarmPlant::count($where);
        $offst = intval(($page - 1) * $page_size);
        $data = M\CredibleFarmPlant::find($where . " ORDER BY last_update_time DESC limit {$offst} , {$page_size} ");
        $pages['total_pages'] = ceil($total / $page_size);
        $pages['current'] = $page;
        $pages['total'] = $total;
        $pages = new Pages($pages);
        $pages = $pages->show(1);
        $this->view->current = $page;
        $this->view->data = $data;
        $this->view->pages = $pages;
        $this->view->user_id = $user_id;   
        $this->view->sid = $sid;  
        $this->view->goods_id = $goods_id; 

    }

    /**
     * 新增种植过程
     * @param  integer $goods_id [description]
     * @return [type]            [description]
     */
    public function farmplantsaveAction(){
        $sid = $this->session->getId();
        $user_id = $this->request->getPost('user_id','string','');
        //$user_name=$this->session->user['name'];
        $title = $this->request->getPost('title','string','');
        $desc = $this->request->getPost('desc','string','');
        $year1 = $this->request->getPost('year1','string','');
        $month1 =$this->request->getPost('month1','string','');
        $day1  = $this->request->getPost('day1','string','');
        $goods_id = $this->request->getPost('goods_id','int',0);
        $time = strtotime($year1.$month1.$day1);

        $tempfile=M\TmpFile::findFirst("type = 41 and sid = '{$sid}'");

        $credible_farm_plant=new M\CredibleFarmPlant();
        $credible_farm_plant->user_id =$user_id;
        if($tempfile){
            $credible_farm_plant->picture_path = $tempfile->file_path;
        }
        $credible_farm_plant->goods_id = $goods_id;
        $credible_farm_plant->title = $title;
        $credible_farm_plant->desc = $desc;
        $credible_farm_plant->add_time = time();
        $credible_farm_plant->last_update_time = time();
        $credible_farm_plant->is_delete = 0;
        $credible_farm_plant->picture_time = $time;
        $credible_farm_plant->save();
        /*  清除 */
        M\TmpFile::clearOld($sid);
        Func::adminlog("可信农场新增种植过程：{$title}",$this->session->adminuser['id']);
        echo "<script>alert('修改成功!');location.href='/manage/crediblefarm/farmplant/{$goods_id}/{$user_id}'</script>";  
    }

    /**
     * 新增种植产品
     * @return [type] [description]
     */
    public function plantsaveAction(){
        $user_id = $this->request->getPost('user_id', 'int', 0);
        $goods_name = $this->request->getPost('goods_name','string','');
        $crediblefarmgoodsplant=new M\CredibleFarmGoodsplant();
        $crediblefarmgoodsplant->user_id =$user_id;
        $crediblefarmgoodsplant->goods_name = $goods_name;
        $crediblefarmgoodsplant->add_time = time();
        $crediblefarmgoodsplant->is_delete = 0;
        $crediblefarmgoodsplant->save();
        Func::adminlog("可信农场新增种植产品：{$user_id}",$this->session->adminuser['id']);
        echo "<script>alert('修改成功!');location.href='/manage/crediblefarm/productprocess/{$user_id}'</script>";  
    }


    /**
     * 资质认证
     * @param  integer $user_id [description]
     * @return [type]           [description]
     */
    public function qualificationsAction($user_id=0){
        //资质图片
        $sid = $this->session->getId();
        $qualification = M\CredibleFarmPicture::find("user_id={$user_id} and type=2");
        $this->view->qualification = $qualification;
        $this->view->user_id = $user_id;
        $this->view->sid = $sid;
    }

    /**
     * 资质认证编辑保存
     * @param  integer $user_id [description]
     * @return [type]           [description]
     */
    public function qualificationssaveAction(){
        $sid = $this->session->getId();
        $user_id = $this->request->getPost('user_id','int',0);
        $title = $this->request->getPost('title','string','');
        $year1 = $this->request->getPost('year1','string','');
        $month1 =$this->request->getPost('month1','string','');
        $day1  = $this->request->getPost('day1','string','');
        $time = strtotime($year1.$month1.$day1);

        $tempfile=M\TmpFile::findFirst("type = 40 and sid = '{$sid}'");
        $credible_farm_picture=new M\CredibleFarmPicture();
        $credible_farm_picture->user_id =$user_id;
        if($tempfile){
            $credible_farm_picture->picture_path = $tempfile->file_path;
        }
        $credible_farm_picture->title = $title;
        $credible_farm_picture->desc = '';
        $credible_farm_picture->status = 1;
        $credible_farm_picture->type = 2;
        $credible_farm_picture->add_time = time();
        $credible_farm_picture->last_update_time = time();
        $credible_farm_picture->picture_time =$time;
        $credible_farm_picture->save();
        /*  清除 */
        M\TmpFile::clearOld($sid);
        Func::adminlog("可信农场新增资质认证：{$title}",$this->session->adminuser['id']);
        echo "<script>alert('修改成功!');location.href='/manage/crediblefarm/qualifications/{$user_id}'</script>";  
    }

    /**
     * 图片墙
     * @return [type] [description]
     */
    public function picturewallAction($user_id=0){
        if(!$user_id){
            echo "<script>alert('数据异常!');location.href='/manage/crediblefarm/index'</script>";  
            exit;  
        }
        $sid = $this->session->getId();
        $page = $this->request->get('p', 'int', 1);
        $page_size = 10;
        $where = "user_id={$user_id} and type=3";
        $total = M\CredibleFarmPicture::count($where);
        $offst = intval(($page - 1) * $page_size);
        
        $data = M\CredibleFarmPicture::find($where . " ORDER BY last_update_time DESC limit {$offst} , {$page_size} ");
        $pages['total_pages'] = ceil($total / $page_size);
        $pages['current'] = $page;
        $pages['total'] = $total;
        $pages = new Pages($pages);
        $pages = $pages->show(1);
        $this->view->current = $page;
        $this->view->data = $data;
        $this->view->pages = $pages;
        $this->view->sid = $sid;
        $this->view->user_id = $user_id;
    }

    public function picturewallsaveAction(){
        $title= $this->request->getPost("title", 'string', '');
        $contents= $this->request->getPost("contents", 'string','');
        if(!$title||!$contents){
            die("各项不能为空");
        }
        $sid = $this->session->getId();
        
        $user_id = $this->request->getPost('user_id','int',0);
        $title = $this->request->getPost('title','string','');
        $desc = $this->request->getPost('desc','string','');
       
        $tempfile=M\TmpFile::findFirst("type = 39 and sid = '{$sid}'");
        if(!$tempfile){
             echo '<script>alert("图片未上传");location.href="/manage/crediblefarm/index"</script>';exit;
        }
        $credible_farm_picture=new M\CredibleFarmPicture();
        $credible_farm_picture->user_id =$user_id;
        $credible_farm_picture->picture_path = $tempfile->file_path;
        $credible_farm_picture->title = $title;
        $credible_farm_picture->desc = $contents;
        $credible_farm_picture->status = 1;
        $credible_farm_picture->type =  3;
        $credible_farm_picture->add_time = time();
        $credible_farm_picture->last_update_time = time();
        $credible_farm_picture->picture_time =time();
        $credible_farm_picture->save();
        /*  清除 */
        M\TmpFile::clearOld($sid);
        Func::adminlog("可信农场新增图片墙：{$title}",$this->session->adminuser['id']);
        echo "<script>alert('修改成功!');location.href='/manage/crediblefarm/picturewall/{$user_id}'</script>"; 
    }

    /**
     * 删除图文介绍、足迹、资质、种植产品、农场LOGO、图片墙、种植过程
     * @return [type] [description]
     */
    public function delgraphicAction(){
        $rs = array(
            'state' => false,
            'msg' => '数据删除成功！'
        );
        $id = $this->request->get('id', 'int', 0);
        $type = $this->request->get('type', 'int', 0);
        if($type==1){
            $crediblefarmpicture = M\CredibleFarmPicture::findFirstByid($id);        
            if (!$crediblefarmpicture) 
            {
                $rs['msg'] = '数据不存在！';
                die(json_encode($rs));
            }
            $crediblefarmpicture->delete();
            Func::adminlog("可信农场删除足迹、资质图片、宣传图、图文信息等信息：{$id}",$this->session->adminuser['id']);
            $rs['state'] = true;
            die(json_encode($rs));
        }elseif($type==2){
            $planting = M\CredibleFarmGoodsplant::findFirstBygoods_id($id);
            if (!$planting) 
            {
                $rs['msg'] = '数据不存在！';
                die(json_encode($rs));
            }
            $planting->is_delete=1;
            $planting->save();
            Func::adminlog("可信农场删除种植产品信息：{$id}",$this->session->adminuser['id']);
            $rs['state'] = true;
            die(json_encode($rs));
        }elseif($type==3){
            $farmlogo = CredibleFarmInfo::findFirstByid($id);
            if (!$farmlogo) 
            {
                $rs['msg'] = '数据不存在！';
                die(json_encode($rs));
            }
            $farmlogo->logo_pic='';
            $farmlogo->save();
            Func::adminlog("可信农场删除农场LOGO：{$id}",$this->session->adminuser['id']);
            $rs['state'] = true;
            die(json_encode($rs));

        }elseif($type==4){
            $crediblefarmpicture = M\CredibleFarmPicture::findFirstByid($id);        
            if (!$crediblefarmpicture) 
            {
                $rs['msg'] = '数据不存在！';
                die(json_encode($rs));
            }
            $crediblefarmpicture->delete();
            Func::adminlog("可信农场删除宣传图：{$id}",$this->session->adminuser['id']);
            $rs['state'] = true;
            die(json_encode($rs));
        }elseif($type==5){
            $farmplant = M\CredibleFarmPlant::findFirstByid($id);
            if (!$farmplant) 
            {
                $rs['msg'] = '数据不存在！';
                die(json_encode($rs));
            }
            $farmplant->is_delete=1;
            $farmplant->save();
            Func::adminlog("可信农场删除种植过程信息：{$id}",$this->session->adminuser['id']);
            $rs['state'] = true;
            die(json_encode($rs));
        }elseif($type==6){
            $farmlogo = CredibleFarmInfo::findFirstByid($id);
            if (!$farmlogo) 
            {
                $rs['msg'] = '数据不存在！';
                die(json_encode($rs));
            }
            $farmlogo->img_pic='';
            $farmlogo->save();
            Func::adminlog("可信农场删除农场图片：{$id}",$this->session->adminuser['id']);
            $rs['state'] = true;
            die(json_encode($rs));
        }
        
    }

    /**
     * 获取农场主地址
     * @param integer $id [description]
     */
    public function GetFarmAddress($id=0){
        $cond[] = "id={$id}";
        $cond['columns'] = "credit_id";
        $users = M\Users::findFirst($cond);
        if($users && $users->credit_id){
            $user_farm = M\UserFarm::findFirst(" user_id={$id} and credit_id={$users->credit_id}");
            if($user_farm->province_name){
                return $user_farm->province_name.$user_farm->city_name.$user_farm->district_name.$user_farm->town_name.$user_farm->village_name;
            }else{
                return '-';
            }
        }else{
            return '-';
        }
    }

    /**
     * 获取主营产品
     * @param integer $id [description]
     */
    public function GetFarmGoods($id=0){

        $user_info = M\UserInfo::findFirst("user_id={$id} and status=1 and credit_type='8'");
        $credit_id=0;
        if($user_info){
            $credit_id = $user_info->credit_id;
        }

        $cond[] = "user_id = {$id} and credit_id={$credit_id}";
        $cond['columns'] = "category_name";
        $farmgoods = M\UserFarmCrops::find($cond)->toArray();
        if($farmgoods){
            $goods_name = Func::getCols($farmgoods,'category_name',',');
            if($goods_name){
                return $goods_name;
            }else{
                return '-';
            }
        }else{
            return '-';
        }
    }

    /**
     * 获取农场名称
     * @param integer $id [description]
     */
    public function GetFarmname($id=0){
        $user_info = M\UserInfo::findFirst("user_id={$id} and status=1 and credit_type = '8'");
        if(!$user_info){
            return '-';die;
        }
        $user_farm = M\UserFarm::findFirst("credit_id={$user_info->credit_id} ");
        if($user_farm){
            return $user_farm->farm_name;
        }else{
            return '-';
        }
    }
    /**
     * 获取农场主名称
     * @param integer $id [description]
     */
    public function GetFarmUserName($id=0){
        $user_info = M\UsersExt::findFirst("uid={$id}");
        if(!$user_info){
            return '-';die;
        }else{
            return $user_info->name;
        }
    }
    public function getFarmtime($id=0){
        $user_info = M\UserInfo::findFirst("user_id={$id} and status=1 and credit_type=8  ");
        if(!$user_info){
            return false;
        }
        return $user_info->add_time;
    }

    /**
     * 获取农场简介
     * @param integer $id [description]
     */
    public function GetFarmdesc($id=0){

        $crediblefarminfo=M\CredibleFarmInfo::findFirst("user_id={$id} and status=1");
        if(!$crediblefarminfo){
            $user_info = M\UserInfo::findFirst("user_id={$id} and status=1 and credit_type = '8'");
            if(!$user_info){
                return '-';die;
            }
            $user_farm = M\UserFarm::findFirst("credit_id={$user_info->credit_id} ");
            if($user_farm){
                return $user_farm->describe;
            }else{
                return '-';
            }
        }else{
            return $crediblefarminfo->desc;
        }
        
    }

    /**
     * 根据农场户名进行搜索
     * @param string $farm_name [description]
     */
    public function GetwhereFarmname($farm_name=''){
        $user_farm = M\UserFarm::find("farm_name='{$farm_name}'")->toArray();
        $credit_id = Func::getCols($user_farm,'credit_id',',');
        if(!$credit_id){
            return ' and user_id=0';die;
        }
        $user_info = M\UserInfo::findFirst("credit_id in ({$credit_id}) and status=1");
        if(!$user_info){
            return ' and user_id=0';die;
        }
        return " and user_id=$user_info->user_id";die;
    }

}