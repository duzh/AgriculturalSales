<?php
/**
 * 标签管理
 * 
 */
namespace Mdg\Manage\Controllers;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Mvc\Controller;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Mdg\Models as M;
use Mdg\Models\Ad as Ad;
use Lib\File as File;
use Lib\Func as Func;
use Lib as L;
use Lib\Areas as lAreas;
use Mdg\Models\Purchase as Purchase;
use Mdg\Models\TmpFile as TmpFile;
use Mdg\Models\AreasFull as mAreas;



class TagController extends Controller
{
 
    /**
     * 标签列表
     * @return [type] [description]
     */
    public function indexAction () {

        $category_one   = $this->request->get('category_one', 'int', 0);
        $category_two   = $this->request->get('category_two', 'int', 0);
        $category_three = $this->request->get('category_three', 'int', 0);
        $status         = $this->request->get('status', 'string', 'all');
        $user_name      = $this->request->get('user_name', 'string', '');
        $starttime      = $this->request->get('starttime', 'string', '');
        $endtime        = $this->request->get('endtime', 'string', '');
        $uname          = $this->request->get('name', 'string','');
        $p              = $this->request->get('p', 'int',1);
        $cond[] = " 1 ";
        
        $cate = array();
        if($starttime && $endtime) {
            $start_time = strtotime($starttime . ' 00:00:00');
            $end_time = strtotime($endtime . ' 23:59:59');

            $cond[] = " add_time BETWEEN '{$start_time}' AND '{$end_time}'";
        }
        if($status != 'all') {
            $cond[] = " status = '{$status}'";
        }
        if($category_one) {
            $cond[] = " category_one = '{$category_one}'";
            $cate[] = M\Category::selectBytocateName($category_one);

        }
        if($category_two) {
            $cond[] = " category_two = '{$category_two}'";
            $cate[] = M\Category::selectBytocateName($category_two);

        }
        if($category_three) {
            $cond[] = " category_three = '{$category_three}'";
            $cate[] = M\Category::selectBytocateName($category_three);

        }
        if($user_name) {
            //根据手机号获取 sellid
            $sellid = M\Users::selectMobile($user_name);
            if($sellid){
                $cond[] = " user_id in({$sellid})";
            }else{
                $cond[] = " user_id in('')";
            }
        }
        if($uname){
            $sellid = M\Users::selectbyname($uname);
            
            if($sellid){
                $cond[] = " user_id in({$sellid})";
            }else{
                $cond[] = " user_id in('')";
            }
        }
        $cond = implode( ' AND ', $cond);
       
        
        $orderby = " ORDER BY tag_id DESC ";

        $data = M\Tag::getTagList($cond , $p, $orderby);
       
        //分类
        $cate = join( "','", $cate);

        $this->view->cate = $cate ? "'$cate'" : '';
        $this->view->_STATUS = M\Tag::$_STATUS;
        $this->view->data = $data;
    }


    /**
     * 详细查看
     * @param  integer $tid [description]
     * @return [type]       [description]
     */
    public function getAction ($tid=0) {

        $data = M\Tag::getTagInfo($tid);

        #查询生产照片
        $imageProduct = M\TagPicture::getTagProductionImgList($data->tag_id);
        //查询作业信息
        $plant = M\TagPlant::getTagPlantList($data->tag_id);
        //获取分类
        $name = M\Category::selectBytocateName($data->category_one). ">>".M\Category::selectBytocateName($data->category_two). ">>".M\Category::selectBytocateName($data->category_three);
        //产地
        /* 查询农药信息 */
        $TagPesticide = M\TagPesticide::getTagPesticideList($data->tag_id);
        $TagPesticide = $TagPesticide ? $TagPesticide->toArray() : array();
        /* 查询肥料信息 */
        $TagManureList = M\TagManure::getTagManureList($data->tag_id);
        $TagManureList = $TagManureList ? $TagManureList->toArray() : array();

        /* 图片机构文件 */
        $TagCertifying = M\TagCertifying::getTagCertifyingList($data->tag_id);  


        $this->view->TagPesticide = $TagPesticide;
        $this->view->TagManureList = $TagManureList;
        $this->view->TagCertifying = $TagCertifying;
        #作业类型
        $this->view->_operate_type = M\Tag::$_operate_type;
        $this->view->plant = $plant;
        $this->view->cname =$name;
        $areas =array_column(lAreas::getAreasFull($data->village), 'name');
        $_address = join(',', $areas);
        $this->view->address = $_address;
        
        $this->view->imageProduct = $imageProduct;
        $this->view->_STATUS = M\Tag::$_STATUS;
        $this->view->data = $data;
    }
    
    /**
     * 标签编辑
     * @param  integer $tid [description]
     * @return [type]       [description]
     */
    
    public function editAction($tid = 0) 
    {
        if (!$tid) {
            return $this->dispatcher->forward(array(
                    "controller" => "tag",
                    "action" => "index",
                ));
        }
        $sid = $this->session->getId();
        $data = M\Tag::getTagInfo($tid);
        if(!$data) {
            return $this->dispatcher->forward(array(
                    "controller" => "tag",
                    "action" => "index",
                ));

        } 

        //  查询标签质量评估
        $TagQuality = M\TagQuality::getTagQuality($data->tag_id);
        //查询生产信息
        $TagProduct = M\TagProduct::findFirst(" tag_id = '{$data->tag_id}'");
        $TagProductImgList = M\TagPicture::getTagProductionImgList($data->tag_id);
        //查询种植作业
        $plant = M\TagPlant::getTagPlantList($data->tag_id);
        /* 查询肥料信息 */
        $TagPesticide = M\TagPesticide::getTagPesticideList($data->tag_id);
        /* 查询农药信息 */
        $TagManureList = M\TagManure::getTagManureList($data->tag_id);
        /* 图片机构文件 */
        $TagCertifying = M\TagCertifying::getTagCertifyingList($data->tag_id);  
 
        /* 清楚临时图片 */
        M\TmpFile::clearOld($sid);
        //查询商品地区
        $areas = mAreas::getFamily($data->village);
        $ares = array_column($areas, 'name');
        $this->view->TagPesticide = $TagPesticide;
        $this->view->TagManureList = $TagManureList;
        $this->view->TagCertifying = $TagCertifying;
        $this->view->plantCount = count($plant);
        $this->view->pesticideCount = count($TagPesticide);
        $this->view->manureCount = count($TagManureList);

        $this->view->TagProductImgList = $TagProductImgList;
        $this->view->tagplant = $plant;
        $this->view->TagProduct = $TagProduct;
        $this->view->TagQuality = $TagQuality;
        $this->view->maxcategory = M\Category::selectBytocateName($data->category_one);
        $this->view->twocategory = M\Category::selectBytocateName($data->category_two);
        $this->view->category = M\Category::selectBytocateName($data->category_three);

        $this->view->address = "'" . join("','", $ares) . "'";
        $this->view->sid = $sid;
        $this->view->_operate_type = M\Tag::$_operate_type;
        $this->view->data = $data;
        $this->view->title = '个人中心-标签管理-申请标签-';
    }
    /**
     * 编辑保存信息
     * @return [type] [description]
     */
    
    public function saveAction() 
    {
        if(!$this->request->getPost()) {
                return $this->dispatcher->forward(array(
                    "controller" => "tag",
                    "action" => "index",
                ));
        }
      
        //查询基本信息
        $province = intval($this->request->getPost('province', 'int', 0));
        $city = intval($this->request->getPost('city', 'int', 0));
        $county = intval($this->request->getPost('county', 'int', 0));
        $townlet = intval($this->request->getPost('townlet', 'int', 0));
        $village = intval($this->request->getPost('village', 'int', 0));
        $manure_type       = intval($this->request->getPost('manure_type', 'int', 0));
        $is_gene           = intval($this->request->getPost('is_gene', 'int', 0 ));
        $tag_id            = intval($this->request->getPost('sellid', 'int', 0));


        $address           = $this->request->getPost('address', 'string', '');
        $productor         = $this->request->getPost('productor', 'string', '');
        $expiration_date   = $this->request->getPost('expiration_date', 'string', '');
        $process_place     = $this->request->getPost('process_place', 'string', '');
        $process_merchant  = $this->request->getPost('process_merchant', 'string', '');
        $quality_level     = $this->request->getPost('quality_level', 'string', '');
        $pesticide_residue = $this->request->getPost('pesticide_residue', 'string', '');
        $heavy_metal       = $this->request->getPost('heavy_metal', 'string', '');
        $inspector         = $this->request->getPost('inspector', 'string', '');
        $inspect_time      = $this->request->getPost('inspect_time', 'string', '');
        $certifying_agency = $this->request->getPost('certifying_agency', 'string', '');
        $product_place     = $this->request->getPost('product_place', 'string', '');
        $manure            = $this->request->getPost('manure', 'string', '');
        $pollute           = $this->request->getPost('pollute', 'string', '');
        $breed             = $this->request->getPost('breed', 'string', '');
        $seed_quality      = $this->request->getPost('seed_quality', 'string', '');
        $manure_amount     = $this->request->getPost('manure_amount', 'float', '');
        $pesticides_type   = $this->request->getPost('pesticides_type', 'string', '');
        $pesticides_amount = $this->request->getPost('pesticides_amount', 'float', '');
        $process_type      = $this->request->getPost('process_type', 'string', '');
        $product_date      = $this->request->getPost('product_date', 'string', '');
        $operate_type      = $this->request->getPost('operate_type');
        $begin_date        = $this->request->getPost('begin_date');
        $end_date          = $this->request->getPost('end_date');
        $weather           = $this->request->getPost('weather');
        $comment           = $this->request->getPost('comment');
        $plantid           = $this->request->getPost('plantid');
        
        /*  农药使用 */
        $manure_use_period = $this->request->getPost('manure_use_period');
        $manure_name = $this->request->getPost('manure_name');
        $manure_type = $this->request->getPost('manure_type');
        $manure_amount = $this->request->getPost('manure_amount');
        $manure_brand = $this->request->getPost('manure_brand');
        $manure_suppliers = $this->request->getPost('manure_suppliers');
        /* 肥料信息 */
        $pesticide_use_period = $this->request->getPost('pesticide_use_period');
        $pesticide_name = $this->request->getPost('pesticide_name');
        $pesticide_amount = $this->request->getPost('pesticide_amount');
        $pesticide_brand = $this->request->getPost('pesticide_brand');
        $pesticide_suppliers = $this->request->getPost('pesticide_suppliers');


        $time = time();
        $sid = $this->session->getId();
        $tag = M\Tag::findFirst(" tag_id = '{$tag_id}'");
      
        if (!$tag) 
        {
        }
        /**
         * 更新标签信息
         * @var [type]
         */
        $tag->province         = intval($province);
        $tag->city             = intval($city);
        $tag->district         = intval($county);
        $tag->townlet          = intval($townlet);
        $tag->village          = intval($village);
        
        $tag->address          = $address;
        $tag->productor        = $productor;
        $tag->product_date     = $product_date;
        $tag->expiration_date  = $expiration_date;
        $tag->is_gene          = $is_gene;
        $tag->path             = '';
        $tag->process_place    = $process_place;
        $tag->process_merchant = $process_merchant;
        $tag->status           = 0;
        $areas                 = mAreas::getFamily($tag->village);
        $ares                  = array_column($areas, 'name');
        $addAreas              = join("", $ares) . $tag->address;
        $tag->full_address     = $addAreas;
        $tag->source_no        = $tag->source_no;
        
        if (!$tag->save()) 
        {
            
        }

        /* 质量评估 */
        $TagQuality = M\TagQuality::findFirst(" tag_id = '{$tag_id}'");
        $TagQuality->quality_level = $quality_level;
        $TagQuality->heavy_metal = $heavy_metal;
        $TagQuality->pesticide_residue = $pesticide_residue;
        $TagQuality->is_gene = $is_gene;
        $TagQuality->inspector = $inspector;
        $TagQuality->inspect_time = $inspect_time ? strtotime($inspect_time) : 0 ;
        $TagQuality->certifying_agency = $certifying_agency;
        $TagQuality->certifying_file = '';
        //查询上传文件是否有文件存在
        $certifying_file = M\TmpFile::find(" sid = '{$sid}' AND  type = 24 ");
        
        if ($certifying_file) 
        {
            foreach ($certifying_file as $c) {
                $TagCertifying = new M\TagCertifying();
                $TagCertifying->certifying_file = $c->file_path;
                $TagCertifying->tag_id = $tag_id;
                $TagCertifying->status = 1;
                if(!$TagCertifying->save() ) {
                    $TagCertifying->getMessages();
                }
            }

        }
        
        if (!$TagQuality->save()) 
        {
        }

        /* 农药信息 */
        if( M\TagManure::find("tag_id = '{$tag_id}'")->delete() ) {
            foreach ($manure_use_period as $key => $val) {
                $TagManure = new M\TagManure();
                $TagManure->tag_id = $tag_id;
                $TagManure->use_period = htmlentities($val);
                $TagManure->manure_name = isset($manure_name[$key]) ? htmlentities($manure_name[$key]) : '';
                $TagManure->manure_type = isset($manure_type[$key]) ? intval($manure_type[$key]) : '';
                $TagManure->manure_amount = isset($manure_amount[$key]) ? intval($manure_amount[$key]) : ''; 
                $TagManure->manure_brand = isset($manure_brand[$key]) ? htmlentities($manure_brand[$key]) : ''; 
                $TagManure->manure_suppliers = isset($manure_suppliers[$key]) ? htmlentities($manure_suppliers[$key]) : ''; 
                if(!$TagManure->save() ) {
                     $TagManure->getMessages();
                 }
            }
        }

        /* 肥料信息 */
        if( M\TagPesticide::find("tag_id = '{$tag_id}'")->delete() ) {
            foreach ($pesticide_use_period as $key => $val) {
                $TagPesticide = new M\TagPesticide();
                $TagPesticide->tag_id = $tag_id;
                $TagPesticide->use_period = htmlentities($val);
                $TagPesticide->pesticide_name = isset($pesticide_name[$key]) ? htmlentities($pesticide_name[$key]) : '';
                $TagPesticide->pesticide_amount = isset($pesticide_amount[$key]) ? htmlentities($pesticide_amount[$key]) : '';
                $TagPesticide->pesticide_brand = isset($pesticide_brand[$key]) ? htmlentities($pesticide_brand[$key]) : '';
                $TagPesticide->pesticide_suppliers = isset($pesticide_suppliers[$key]) ? htmlentities($pesticide_suppliers[$key]) : '';
                if(!$TagPesticide->save() ) {
                     $TagPesticide->getMessages();
                 }
            }
        }

        // #生产信息
        $TagProduct = M\TagProduct::findFirst(" tag_id = '{$tag_id}'");
        $TagProduct->product_place = $product_place;
        $TagProduct->manure = $manure;
        $TagProduct->pollute = $pollute;
        $TagProduct->breed = $breed;
        $TagProduct->seed_quality = $seed_quality;
        $TagProduct->manure_type = $manure_type;
        $TagProduct->manure_amount = $manure_amount;
        $TagProduct->pesticides_type = $pesticides_type;
        $TagProduct->pesticides_amount = $pesticides_amount;
        $TagProduct->process_type = $process_type;
        $picture = M\TmpFile::find(" sid = '{$sid}' AND  type = 25 ORDER BY addtime asc ");
        
        if (!$TagProduct->save()) 
        {
            // print_R($TagProduct->getMessages());
        }
        
        foreach ($picture as $key => $val) 
        {
            $tag_picture = new M\TagPicture();
            $tag_picture->tag_id = $tag_id;
            $tag_picture->order = $key;
            $tag_picture->path = $val->file_path;
            $tag_picture->status = 0;
            $tag_picture->add_time = $time;
            $tag_picture->last_update_time = $time;
            $tag_picture->plant_id = 0;
            $tag_picture->picture_source = 0;
            $tag_picture->save();
        }


        
        //标签种植作业
        foreach ($operate_type as $key => $val) 
        {
           
            if(isset($plantid[$key]) && $plantid[$key]) {
                $id = $plantid[$key];
                $TagPlant = M\TagPlant::findFirst(" id = '{$id}'");
                }else{
                $TagPlant = new M\TagPlant();
            }  
          
            $TagPlant->tag_id = $tag->tag_id;
            $TagPlant->begin_date = (isset($begin_date[$key]) && $begin_date[$key]) ? strtotime($begin_date[$key]) : 0;
            $TagPlant->end_date = (isset($end_date[$key]) && $end_date[$key]) ? strtotime($end_date[$key]) : 0;
            $TagPlant->weather = $weather[$key];
            $TagPlant->comment = $comment[$key];
            $TagPlant->operate_type = $val;
            //生成图片
            $TagPlant->save();
            $dataFile = M\TmpFile::find(" sid = '{$sid}' AND  type = '26_{$key}' ORDER BY addtime asc ");
                
                foreach ($dataFile as $order => $row) 
                {
                    $tag_picture = new M\TagPicture();
                    $tag_picture->tag_id = $tag->tag_id;
                    $tag_picture->order = $order;
                    $tag_picture->path = $row->file_path;
                    $tag_picture->status = 0;
                    $tag_picture->add_time = $time;
                    $tag_picture->last_update_time = $time;
                    $tag_picture->plant_id = $TagPlant->id;
                    $tag_picture->picture_source = 1;
                    $tag_picture->save();
                }
        }
        $sid = $this->session->getId();
        M\TmpFile::clearOld($sid);
        
        $this->response->redirect("tag/index")->sendHeaders();

    }

    

    /**
     * 审核通过
     * @param  integer $tid [description]
     * @return [type]       [description]
     */
    public function tagauditAction ($tid=0) {
      
      $admin_id = $this->session->adminuser['id'];

      $this->db->begin();
       try {
           $tag_id = $this->request->getPost('id', 'int', 0 );
          
           // if(!$tag_id) throw new \Exception(1);
           //检测是否可以审核状态
            $data = M\Tag::checkTagStatus($tag_id, M\Tag::NOTAUDIT, $this->db);
            M\Tag::updateTagStatus($tag_id, M\Tag::APPROVED, $this->db);         
            /* 修改供应产品信息 */
            $info = M\Sell::findFirst($data['sell_id']);
            if(!$info) throw new \Exception('sell_errors');
            $info->is_source = 1 ;
            if(!$info->save()) throw new Exception('sell_source_errors');
            
            Func::adminlog("标签审核通过：{$data['title']}" , $admin_id );
            $pay = '';

           $this->db->commit();
       } catch (\Exception $e) {
            $this->db->rollback();
            $code['code'] = $e->getMessage();
            $code['line'] = $e->getLine();
            $code['file'] = $e->getFile();
            $code = implode('=>', $code);
            $pay = "失败，{$code}";
            // var_dump($code);exit;
            // var_dump($code);
            // exit;
       }

       file_put_contents(__DIR__ . '/logstest.txt', $pay, FILE_APPEND);

       $this->response->redirect("tag/index")->sendHeaders();
        
    }

    /**
     * 审核失败
     * @param  integer $tid 标签id
     * @return [type]       [description]
     */
    public function tagunauditunAction ($tid=0) {

        if (!$this->request->getPost());
        $flag = false;
        $time = time();

        $reject = $this->request->getPost('reject', 'string', '');
        $id = $this->request->getPost('id', 'int', 0);
        $admin_id = $this->session->adminuser['id'];

        $this->db->begin();
        try
        {
            
            if (!$reject) throw new \Exception('rejectError');
            //检测装填
            $data = M\Tag::findFirst(" tag_id = '{$id}' AND status = 0 ");
            if(!$data) throw new \Exception("tagError");
            
            //修改审核操作以及备注
            $TagCheck = new M\TagCheck();
            $TagCheck->tag_id = $id;
            $TagCheck->last_failure = 1;
            $TagCheck->failure_desc = $reject;
            $TagCheck->add_time = $time;
            $TagCheck->last_update_time = $time;
            
            if (!$TagCheck->save()) throw new \Exception("tag_checkError");
            //修改审核状态
            $data->status = 2;
            $data->save();

            Func::adminlog("标签审核未通过：{$data->goods_name}" , $admin_id );
            $flag = $this->db->commit();
        }
        catch(\Exception $e) 
        {
            $flag = false;
            $this->db->rollback();
            print_R($e->getMessage());
        }
        
        if ($flag) 
        {
        }
        
        $this->response->redirect("tag/index")->sendHeaders();
    }

    /**
     * 删除真实照片
     * @return [type] [description]
     */
    public function removeAction() 
    {
        $id = $this->request->get('id', 'int', 0);
        $type = $this->request->get('type', 'int', 0);
        $tab = $this->request->get('tab', 'string', '');
        $msg = array(
            'status' => 0,
            'msg' => '删除成功'
        );
        switch ($type) {
            case 1: #删除机构文件
                    $data = M\TagCertifying::findFirst( " id ='{$id}'");
                    if(!$data) {
                    }
                    if(!$data->delete() ) {
                        $msg['msg'] = '删除失败'; break;
                    }
                    $msg['msg'] = '删除成功';
                    $msg['status'] = 1;
                    /*
                    $data = M\TagQuality::findFirst( " id ='{$id}'");
                    if(!$data) {  $msg['msg'] = '删除失败'; break;}
                    $data->certifying_file = '';
                    if(!$data->save()) {  }
                    $msg['msg'] = '删除成功';
                    $msg['status'] = 1;
                    */
                break;
            case 2: #删除生产信息
                    $cond[] = " id = '{$id}' AND picture_source = 0 ";
                    $data = M\TagPicture::findFirst($cond);
                    if(!$data) {  $msg['msg'] = '删除失败'; break;}
                    $oldimg = $data->path;
                    if(!$data->delete()) {  $msg['msg'] = '删除失败'; break;}
                    //删除本地图片
                    @unlink(PUBLIC_PATH.$oldimg);
                    $msg['msg'] = '删除成功';
                    $msg['status'] = 1;
                break;
            case 3: #删除种植作业
                    $cond[] = " id = '{$id}' AND picture_source = 1 ";
                    $data = M\TagPicture::findFirst($cond);
                    if(!$data) {  $msg['msg'] = '删除失败'; break;}
                    $oldimg = $data->path;
                    if(!$data->delete()) {  $msg['msg'] = '删除失败'; break;}
                    //删除本地图片
                    @unlink(PUBLIC_PATH.$oldimg);
                    $msg['msg'] = '删除成功';
                    $msg['status'] = 1;
                break;
        }
        $msg['status'] = 1;
        $msg['msg'] = '删除成功';
        exit(json_encode($msg));
    }
    
    /**
     *  删除种植信息
     * @return [type] [description]
     */
    public function removeplantAction () {
        $id = $this->request->get('id', 'int', 0);
        if(!$id) {
            exit(json_encode(array('status' => 1, 'msg' => '删除失败 ')));
        }
        $cond[] = " id = '{$id}'";
        $data = M\TagPlant::findFirst($cond);
        if(!$data) exit(json_encode(array('status' => 1, 'msg' => '删除失败 ')));
        if($data->delete()){
            exit(json_encode(array('status' => 1, 'msg' => '删除成功 ')));
        }
    }
    public function infoAction($id){
        
        $tag_seed=array();
        //主表
        $maintag = M\Tag::getTagInfo($id);
        if($maintag&&$maintag->tag_id){
             $sell=M\Sell::getSellInfo($maintag->sell_id);
             if($sell){
                $sell=$sell->toArray();
             }else{
                $sell=array();
             }
        }else{
             $sell=array();
        }
       
        //标签的种子
        $tag_seed=M\TagSeed::find("tag_id={$id}")->toArray();
        //肥料
        $tagmanure=M\TagManure::find("tag_id={$id}")->toArray();
        //农药.
        $tagpicture=M\TagPesticide::find("tag_id={$id}")->toArray();
      
        $tagplant=M\TagPlant::find("tag_id={$id}")->toArray();
        
        
        $this->view->rainwater_type=M\Tag::$rainwater_type;
        $this->view->operate_type=M\Tag::$_operate_type;
        $this->view->maintag = $maintag;
        $this->view->tag_seed = $tag_seed;
        $this->view->tagmanure = $tagmanure;
        $this->view->tagpicture =$tagpicture;
        $this->view->tagplant =$tagplant;
        $this->view->sell =$sell;
    }
  

}
