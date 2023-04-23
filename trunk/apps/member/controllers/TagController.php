<?php
namespace Mdg\Member\Controllers;

use Mdg\Models\Users as Users;
use Mdg\Models\AreasFull as mAreas;
use Mdg\Models as M;
use Mdg\Models\Sell as Sell;
use Lib as L;

class TagController extends ControllerMember
{
    public function testAction () {
      $p=27;
      $page_size=20;
      $cond = array();
      $cond[] = " status = 1 ";
      $cond['columns'] = ' sell_id,tag_id ';
      $total = M\Tag::count($cond);
      $offst = intval(($p - 1) * $page_size);
      $data = M\Tag::find("status = 1 order by tag_id desc  limit {$offst}, {$page_size}  ")->toArray();
      
      foreach ($data as $key => $value) {
          $erweitu = $this->getqrcodeAction($value['tag_id'],1, $value['sell_id']);
          var_dump($erweitu);die;
      }
       print_r($data);die;
    }
    
    /**
     * 下载标签
     * @return [type] [description]
     */
    public function downloadAction () {
        ini_set('display_errors', 'no');
        $tid = $this->request->get('id', 'int', 0);
        //标签详细内容
        $tag = $data = M\Tag::getTagInfo($tid);
        $tag_id = $data->tag_id;
        //查询商品是否下架状态
        $sell = M\Sell::getSellInfo($data->sell_id);
       
        if(!$sell) {
            return $this->dispatcher->forward(array(
                "controller" => "tag",
                "action" => "index",
            ));
            exit;
        }



        $areas[] = M\AreasFull::getAreasNametoid($data->province);
        $areas[] = M\AreasFull::getAreasNametoid($data->city);
        $areas[] = M\AreasFull::getAreasNametoid($data->district);
        $areas[] = M\AreasFull::getAreasNametoid($data->townlet);
        $areas[] = M\AreasFull::getAreasNametoid($data->village);
  
        $areas = join('', $areas) . $data->address;

        $data = array(
            array(
                'value' => "品名：{$data->goods_name}",
                'x' => 200,
                'y' => 40,
            ) ,
            array(
                'value' => "溯源码：{$data->source_no}",
                'x' => 200,
                'y' => 65,
            ) ,
            array(
                'value' => "价格：{$data->min_price}~ {$data->max_price}",
                'x' => 200,
                'y' => 90,
            ) ,

            array(
                'value' => "生产日期：{$data->product_date}",
                'x' => 200,
                'y' => 115,
            ) ,

            array(
                'value' => "保质期：{$data->expiration_date}天",
                'x' => 200,
                'y' => 140,
            ) ,
            array(
                'value' => "产地：{$areas}",
                'x' => 200,
                'y' => 160,
            ) 
            

        );

        $img = new L\Img();
        $old_img = ITEM_IMG . $tag->path;
    
        if(!$this->check_remote_file_exists($old_img)) {
            return $this->dispatcher->forward(array(
                "controller" => "tag",
                "action" => "index",
            ));
        }

        $ImgUrl = $img->getFile($tag_id, $data , $old_img );
        
        $filename = $ImgUrl;//图片地址,可以绝对地址也可以相对地址
        
        header("Content-Type: application/force-download");
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        $img = file_get_contents($filename); 
        // //读取地址 上传UpYun
        // $upyun = new L\UpYun();
        // /**
        //  * 读取本地地址
        //  * @var [type]
        //  */
        // $test = @fopen($filename,'r');
        // /**
        //  * 又拍云 新地址
        //  * @var [type]
        //  */
        // $UpYunPath = str_replace('/wwwroot/mdg.ync365.com/public', '', $filename);
        // $arr = $upyun->writeFile($UpYunPath, $test, true);
        // 
        echo $img;
        exit;
    }
    /**
     * 查询标签管理
     * @return [type] [description]
     */
    
    public function indexAction() 
    {

        $uid = $this->getUserID();
            
        $p = $this->request->get('p', 'int', 1);
		$p = intval($p)>0 ? intval($p) : 1;
        $cond = " user_id = '{$uid}'";
        $orderby = ' ORDER  BY tag_id desc ';
        $data = M\Tag::getTagList($cond, $p, $orderby);
        $this->view->_STATUS = M\Tag::$_STATUS;
        $this->view->data = $data;
        $this->view->p = $p;
        $this->view->title = '个人中心-标签管理-丰收汇-高价值农产品交易服务商';
    }
    /**
     * 标签详细查看
     * @return [type] [description]
     */
    
    public function getAction() 
    {
        $tid = $this->request->get('itd', 'int', 0);
        
        if (!$tid) 
        {
            $this->response->redirect("tag/index")->sendHeaders();
        }

        //检测商品是否上架状态
        //标签详细内容
        $cond[] = " tag_id = '$tid' AND status = 1 ";
        $data = M\Tag::findFirst($cond);

        if(!$data) {

            $this->flash->error("信息来源错误");
            return $this->dispatcher->forward(array(
                "controller" => "tag",
                "action" => "index",
            ));
            exit;
        }

        $sell = M\Sell::findFirst(" id = '{$data->sell_id}'   ");

        if(!$sell) {
                $this->flash->error("信息来源错误");
                return $this->dispatcher->forward(array(
                    "controller" => "tag",
                    "action" => "index",
                ));
        }


        $isdownload = 0 ;
        /**
         * 检测商品是否可以下载
         * @var [type]
         */
        if($sell->state == 1 && $sell->is_del == 0  ) {
            $isdownload = 1;
        }


        $this->view->isdownload = $isdownload;
        $this->view->_STATUS = M\Tag::$_STATUS;
        $areas[] = M\AreasFull::getAreasNametoid($data->province);
        $areas[] = M\AreasFull::getAreasNametoid($data->city);
        $areas[] = M\AreasFull::getAreasNametoid($data->district);
        //生成二维码路径
        $erweitu = ITEM_IMG . $data->path;
  
        $filedata = $this->check_remote_file_exists($erweitu);
        $filedata=false;
        if(!$data->path ||  !$filedata ) {
            
            $erweitu = $this->getqrcodeAction($data->tag_id,1, $data->sell_id);
        }

        $this->view->erweitu = IMG_URL.$erweitu;
        $this->view->areas = join(',', $areas) . $data->address;
        $this->view->data = $data;
        $this->view->title = '个人中心-标签管理-丰收汇';
        $this->view->Keywords = '个人中心-标签管理-丰收汇';

    }
    /**
     * 生成二维码图片
     * @param  array  $data 数据
     * @return [type]       [description]
     */
    
    public function getqrcodeAction($tid = 0, $isaction = 0,$sellid=0 ) 
    {
        $tid = $tid ? $tid : $this->request->get('tid', 'int', 0);
        $sellid = $sellid ? $sellid : $this->request->get('sellid', 'int', 0);

        include_once __DIR__ . '/../../lib/phpqrcode.php';

        $value = TAG_URL."/sell/index?id={$sellid}"; //二维码内容

        $errorCorrectionLevel = 'M'; //容错级别
        $matrixPointSize = 5; //生成图片大小
        //生成二维码图片
        \QRcode::png($value, PUBLIC_PATH . '/qrcode/qrcode.png', $errorCorrectionLevel, $matrixPointSize, 2);
        $logo = PUBLIC_PATH . '/qrcode/logo.png'; //准备好的logo图片
        $QR = PUBLIC_PATH . '/qrcode/qrcode.png'; //已经生成的原始二维码图

        if ($logo !== FALSE) { 
         $QR = imagecreatefromstring(file_get_contents($QR)); 
         $logo = imagecreatefromstring(file_get_contents($logo)); 
         $QR_width = imagesx($QR);//二维码图片宽度 
         $QR_height = imagesy($QR);//二维码图片高度 
         $logo_width = imagesx($logo);//logo图片宽度 
         $logo_height = imagesy($logo);//logo图片高度 
         $logo_qr_width = $QR_width / 5; 
         $scale = $logo_width/$logo_qr_width; 
         $logo_qr_height = $logo_height/$scale; 
         $from_width = ($QR_width - $logo_qr_width) / 2; 
         //重新组合图片并调整大小 
         imagecopyresampled($QR, $logo, 70, 60, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height); 

        } 

        /* 修改数据存储路径 */
        $UpYunPath = $path = "/qrcode/www_5fenghsou.com_{$tid}_{$sellid}.png";
        $sql = " UPDATE tag SET path = '{$path}' WHERE tag_id = '{$tid}'";
        $this->db->execute($sql);
        /**/

        $newPath = PUBLIC_PATH . $path;
        
        //newPath
        imagepng($QR, $newPath);
       
        $upyun = new L\UpYun();
        /**
         * 读取本地地址
         * @var [type]
         */
        $resources = @fopen($newPath,'r');
        
        /**
         * 又拍云 新地址
         * @var [type]
         */
        $arr = $upyun->writeFile($UpYunPath, $resources, true);
        
         
        return $path;
        
    }
    /**
     * 标签编辑
     * @param  integer $tid [description]
     * @return [type]       [description]
     */
    
    public function editAction($tid = 0) 
    {
        
        $uid = $this->getUserID();
        if (!$tid || !$uid) {
            return $this->dispatcher->forward(array(
                    "controller" => "tag",
                    "action" => "index",
                ));
        }
        $sid = $this->session->getId();
        $data = M\Tag::getTagInfo($tid);
        $info = M\UserInfo::findFirst(" user_id = '{$uid}' AND credit_type ='8' AND status = '1'");


        if(!$data || !$info ) {
            return $this->dispatcher->forward(array(
                    "controller" => "tag",
                    "action" => "index",
                ));
        } 

     
       
        //查询种植作业
        $plant = M\TagPlant::getTagPlantList($data->tag_id);
        /* 查询肥料信息 */
        $TagPesticide = M\TagPesticide::getTagPesticideList($data->tag_id);
        /* 查询农药信息 */
        $TagManureList = M\TagManure::getTagManureList($data->tag_id);
        /* 查询种子信息 */
        $tagseed = M\TagSeed::findFirst(" tag_id = '{$data->tag_id}'");

        $this->view->farmArea = M\UserFarm::selectByFarmArea($info->credit_id);
        $this->view->info = $info;

        /* 清楚临时图片 */
        M\TmpFile::clearOld($sid);
        //查询商品地区
        $areas = mAreas::getFamily($data->village);
        $ares = array_column($areas, 'name');
        $this->view->TagPesticide = $TagPesticide;
        $this->view->TagManureList = $TagManureList;
        
        $this->view->plantCount = count($plant);
        $this->view->pesticideCount = count($TagPesticide);
        $this->view->manureCount = count($TagManureList);

        $this->view->rainwater = $rainwater = M\TagSeed::$rainwater;
       
        $this->view->tagseed = $tagseed;

        $this->view->TagProductImgList = $TagProductImgList;
        $this->view->tagplant = $plant;
        $this->view->maxcategory = M\Category::selectBytocateName($data->category_one);
        $this->view->twocategory = M\Category::selectBytocateName($data->category_two);
        $this->view->category = M\Category::selectBytocateName($data->category_three);

        $this->view->address = "'" . join("','", $ares) . "'";
        $this->view->sid = $sid;
        $this->view->_operate_type = M\Tag::$_operate_type;
        $this->view->data = $data;
        $this->view->title = '个人中心-标签管理-修改标签-丰收汇-高价值农产品交易服务商';
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

 
        $province        = $this->request->getPost('province', 'int', 0);
        $city            = $this->request->getPost('city', 'int', 0);
        $county          = $this->request->getPost('county', 'int', 0);
        $townlet         = $this->request->getPost('townlet', 'int', 0);
        $village         = $this->request->getPost('village', 'int', 0);
        $address         = L\Validator::replace_specialChar($this->request->getPost('address', 'string', ''));

        $resultlat       = $this->request->getPost('resultlat', 'string', '');
        // print_r($resultlat);die;
        $rainwater       = L\Validator::replace_specialChar($this->request->getPost('rainwater', 'string', ''));
        $product_date    = L\Validator::replace_specialChar($this->request->getPost('product_date', 'string', ''));
        $expiration_date = L\Validator::replace_specialChar($this->request->getPost('expiration_date', 'string', ''));
        $breed           = L\Validator::replace_specialChar($this->request->getPost('breed', 'string', ''));
        $neatness        = L\Validator::replace_specialChar($this->request->getPost('neatness', 'string', ''));
        $fineness        = L\Validator::replace_specialChar($this->request->getPost('fineness', 'string', ''));
        $sprout          = L\Validator::replace_specialChar($this->request->getPost('sprout', 'string', ''));
        $water           = L\Validator::replace_specialChar($this->request->getPost('water', 'string', ''));
        $use_period = $this->request->getPost('use_period');
        $manure_name = $this->request->getPost('manure_name');
        $manure_type = $this->request->getPost('manure_type');
        $manure_amount = $this->request->getPost('manure_amount');
        $tag_id = $this->request->getPost('sid', 'int', 0 );

        $plantoperate_type = $this->request->getPost('plantoperate_type');
        $plantbegin_date = $this->request->getPost('plantbegin_date');
        $plantend_date = $this->request->getPost('plantend_date');
        $plant_area = $this->request->getPost('plant_area', 'float', 0.00);

       
        $time = time();
        $sid = $this->session->getId();
        $tag = M\Tag::findFirst(" tag_id = '{$tag_id}'");
      
        if (!$tag) 
        {
            return $this->dispatcher->forward(array(
                    "controller" => "tag",
                    "action" => "index",
                ));
        }
        // print_R($_POST);exit;

        $this->db->begin();

        try {
                /* 清除数据 */
                $sql = "DELETE FROM tag_plant  where tag_id = '{$tag_id}';
                        DELETE FROM tag_pesticide  where tag_id = '{$tag_id}';
                        DELETE FROM tag_manure  where tag_id = '{$tag_id}';
                        DELETE FROM tag_seed  where tag_id = '{$tag_id}';";
                $this->db->execute($sql);

                $lnt = explode(',', $resultlat);
                
                $time = CURTIME;
                $tag->plant_area = $plant_area;
                $tag->province         = intval($province);
                $tag->city             = intval($city);
                $tag->district         = intval($county);
                $tag->townlet          = intval($townlet);
                $tag->village          = intval($village);
                
                $tag->address          = $address;
                $tag->product_date     = $product_date;
                $tag->expiration_date  = $expiration_date;
                $tag->status           = 0;
                $areas                 = mAreas::getFamily($tag->village);
                $ares                  = array_column($areas, 'name');
                $addAreas              = join("", $ares) . $tag->address;
                $tag->full_address     = $addAreas;
                $tag->source_no        = $tag->source_no;
                $tag->latitude = isset($lnt[0]) ? $lnt[0] : 0;
                $tag->longitude = isset($lnt[1]) ? $lnt[1] : 0; 
                $tag->rainwater = $rainwater;
                if (!$tag->create()) 
                {
                }
                $tag_id = $tag->tag_id;
                $tag->path = $this->getqrcodeAction($tag_id, 1,$tag->sell_id);
                $tag->save();
               /** 肥料 */
                if(isset($use_period['manure'])) {
                    foreach ($use_period['manure'] as $key => $val) {
                        $TagManure    = new M\TagManure();
                        $TagManure->tag_id =  $tag_id;
                        $TagManure->use_period = isset($use_period['manure'][$key]) ? $use_period['manure'][$key] : '';
                        $TagManure->manure_name = isset($manure_name['manure'][$key]) ? htmlentities($manure_name['manure'][$key]) : '';
                        $TagManure->manure_type = 0;
                        $TagManure->manure_amount = isset($manure_amount['manure'][$key]) ? htmlentities($manure_amount['manure'][$key]) : '';
                        $TagManure->manure_brand = '';
                        $TagManure->manure_suppliers = '';
                        $TagManure->save();
                    }
                }

                /* 农药*/
                if(isset($use_period['pesticide'])) {
                    foreach ($use_period['pesticide'] as $key => $val) {
                        $TagPesticide = new M\TagPesticide();
                        $TagPesticide->tag_id =  $tag_id;
                        $TagPesticide->use_period = isset($use_period['pesticide'][$key]) ? $use_period['pesticide'][$key] : '';
                        $TagPesticide->pesticide_name = isset($manure_name['pesticide'][$key]) ? htmlentities($manure_name['pesticide'][$key]) : '';
                        $TagPesticide->pesticide_amount = isset($manure_amount['pesticide'][$key]) ? htmlentities($manure_amount['pesticide'][$key]) : '';;
                        $TagPesticide->pesticide_brand = '';
                        $TagPesticide->pesticide_suppliers = '';
                        // var_dump($TagPesticide->toArray());
                        // exit;
                        if(!$TagPesticide->create()) {
                            throw new \Exception("TagPesticide_errors");
                        }
                    }
                }
                
                $TagSeed      = new M\TagSeed();
                $TagSeed->tag_id = $tag_id;
                $TagSeed->crops = M\Category::getparent($tag->category_three);
                $TagSeed->breed = $breed;
                $TagSeed->neatness = $neatness;
                $TagSeed->fineness = $fineness;
                $TagSeed->sprout = $sprout;
                $TagSeed->water = $water;
                $TagSeed->add_time = $time;
                $TagSeed->last_update_time = $time;
                $TagSeed->save();
                foreach ($plantoperate_type as $key => $val) {
                    $TagPlant = new M\TagPlant();
                    $TagPlant->tag_id = $tag_id;
                    $TagPlant->begin_date = isset($plantbegin_date[$key]) ? strtotime(($plantbegin_date[$key])) : 0;
                    $TagPlant->end_date = isset($plantend_date[$key]) ? strtotime($plantend_date[$key]) : 0 ;
                    $TagPlant->weather = '';
                    $TagPlant->comment = '';
                    $TagPlant->operate_type = isset($plantoperate_type[$key]) ? intval($plantoperate_type[$key]) : 0 ;
                    $TagPlant->save();
                }
            $this->db->commit();
        } catch (\Exception $e) {
            $this->db->rollback();
        }

        
        $this->response->redirect("tag/index")->sendHeaders();
    }
    /**
     * 申请标签
     * @return [type] [description]
     */
    
    public function newAction() 
    {
        $tid = $this->request->get('tid', 'int', 0);
        $this->checkSellBindTag($tid);
        $uid = $this->getUserID();
        $result = array();
        $info = M\UserInfo::findFirst(" user_id = '{$uid}' AND credit_type ='8' AND status = '1'");

        /* 检测产品是否存在 */
        $data = Sell::findFirst(" id ='{$tid}' AND uid = '{$uid}'");

        if(!$data || !$info) { 

             return $this->dispatcher->forward(array(
                "controller" => "tag",
                "action" => "index",
            ));
            exit;
        }
        /* 检测产品是否申请标签 */
        $lng = array();

        $address = $this->addresstestAction($data->areas_name);
        if($address) {
            $areas  = json_decode($address,2);
            if(isset($areas['status']) && $areas['status'] == 0 ) {
                $result = $areas['result']['location'];
            }
        }
        $lng[] = isset($result['lng']) ?  substr($result['lng'], 0, (strpos($result['lng'],'.')+5))  : 0; 
        $lng[] = isset($result['lat']) ? substr($result['lat'], 0, (strpos($result['lat'],'.')+5)) : 0; 

        //查询供应分类
        //清除
        M\TmpFile::clearOld($sid);
        //查询商品地区
        $areas = mAreas::getFamily($data->areas);
        $ares = array_column($areas, 'name');

        $this->view->farmArea = M\UserFarm::selectByFarmArea($info->credit_id);

        $this->view->info = $info;
        $this->view->category = M\Category::selectBytocateName($data->category);
        $this->view->maxcategory = M\Category::selectBytocateName($data->maxcategory);
        /* 获取可信农场面积 */

        $this->view->result = join(',', $lng);
        $this->view->lng = $lng;



        $this->view->address = "'" . join("','", $ares) . "'";
        $this->view->_operate_type = M\Tag::$_operate_type;
        $this->view->data = $data;
        $this->view->title = '个人中心-标签管理-申请标签-丰收汇-高价值农产品交易服务商';
    }

    public  function addresstestAction($areas) 
    {   $data=array();
        $url="http://api.map.baidu.com/geocoder/v2/?ak=ocQy04ecp2pLe6SlT4cpyiu7&output=json&address=".$areas;
        $Curl = new  L\Curl();
        $data = $Curl->get($url,$data);
        return $data;
    }


    public function changeAreasAction () {
        $village_id = $this->request->getPost('village', 'int', 297600 );
        /*   组装地址 */
        $data = L\Areas::getAreasFull($village_id);
        $areaName = array_column($data , 'name');
        $areas = join(''  , $areaName);
        $data = $this->addresstestAction($areas);
        if($data) {
            $data = json_decode($data,2);
            if(isset($data['status']) && $data['status'] == 0 ) {
                $result = $data['result']['location'];
            }
        }
    $result['lng'] = isset($result['lng']) ?  substr($result['lng'], 0, (strpos($result['lng'],'.')+5))  : 0; 
    $result['lat'] = isset($result['lat']) ? substr($result['lat'], 0, (strpos($result['lat'],'.')+5)) : 0; 
    exit(json_encode($result));
    }


    /**
     * 标签信息保存
     * @return [type] [description]
     */
    public function createAction () {
       
        
        if(!$this->request->getPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "tag",
                "action" => "index",
            ));
        }
        $province        = intval($this->request->getPost('province', 'int', 0));
        $city            = intval($this->request->getPost('city', 'int', 0));
        $county          = intval($this->request->getPost('county', 'int', 0));
        $townlet         = intval($this->request->getPost('townlet', 'int', 0));
        $village         = intval($this->request->getPost('village', 'int', 0));
        $address         = $this->request->getPost('address', 'string', '');
        $rainwater       = $this->request->getPost('rainwater', 'int', 0);

        $resultlat       = $this->request->getPost('resultlat', 'string', '');

        $product_date    = $this->request->getPost('product_date', 'string', '');
        $expiration_date = $this->request->getPost('expiration_date', 'string', '');
        $breed           = $this->request->getPost('breed', 'string', '');
        $neatness        = $this->request->getPost('neatness', 'string', '');
        $fineness        = $this->request->getPost('fineness', 'string', '');
        $sprout          = $this->request->getPost('sprout', 'string', '');
        $water           = $this->request->getPost('water', 'string', '');
        $use_period = $this->request->getPost('use_period');
        $manure_name = $this->request->getPost('manure_name');
        $manure_type = $this->request->getPost('manure_type');
        $manure_amount = $this->request->getPost('manure_amount');

        $plantoperate_type = $this->request->getPost('plantoperate_type');
        $plantbegin_date = $this->request->getPost('plantbegin_date');
        $plantend_date = $this->request->getPost('plantend_date');
        $plant_area = $this->request->getPost('plant_area', 'float','0.00');
        $sid = $this->request->getPost('sid', 'int', 0 );
        if(!$sid) {
            return $this->dispatcher->forward(array(
                "controller" => "tag",
                "action" => "index",
            ));
        }
    
    
        /* 检测产品是否存在 */
        $sellInfo = M\Sell::findFirst(" id = '{$sid}' AND state = 1 AND is_del = 0 ");

        if(!$sellInfo) {
            return $this->dispatcher->forward(array(
                "controller" => "tag",
                "action" => "index",
            ));
        }
        
        $this->db->begin();
        try {

                $lnt = explode(',', $resultlat);
                $time = CURTIME;
                $tag= new M\Tag();
                $tag->plant_area = $plant_area;
                $tag->category_one = intval($sellInfo->maxcategory);
                $tag->category_two = intval($sellInfo->twocategroy);
                $tag->category_three = intval($sellInfo->category);
                $tag->user_id = $sellInfo->uid;
                $tag->goods_name = $sellInfo->title;
                $tag->province = $province;
                $tag->city = $city;
                $tag->district = $county;
                $tag->townlet = $townlet;
                $tag->village = $village;
                $tag->address = $address;
                $tag->product_date = $product_date;
                $tag->expiration_date = $expiration_date;
                $tag->min_price = $sellInfo->min_price;
                $tag->max_price = $sellInfo->max_price;
                $tag->sell_id = $sellInfo->id;
                $tag->is_gene = 0 ;
                $tag->productor = '';
                $tag->path = '';
                $tag->add_time = $time;
                $tag->status = 0;
                $areas = mAreas::getFamily($tag->village);
                $ares = array_column($areas, 'name');
                $addAreas = join("", $ares) . $tag->address;
                $tag->full_address = $addAreas;
                $tag->source_no = L\Func::random(20, 1 );
                $tag->latitude = isset($lnt[0]) ? $lnt[0] : 0;
                $tag->longitude = isset($lnt[1]) ? $lnt[1] : 0; 
                $tag->rainwater = $rainwater;
                if (!$tag->save()) 
                {
                    throw new \Exception("tag_errors");
                }
                
                $tag_id = $tag->tag_id;
                $tag->path = $this->getqrcodeAction($tag_id,1,$tag->sell_id);
                $tag->save();
               /** 肥料 */
                if(isset($use_period['manure'])) {
                    foreach ($use_period['manure'] as $key => $val) {
                        $TagManure    = new M\TagManure();
                        $TagManure->tag_id =  $tag_id;
                        $TagManure->use_period = isset($use_period['manure'][$key]) ? $use_period['manure'][$key] : '';
                        $TagManure->manure_name = isset($manure_name['manure'][$key]) ? htmlentities($manure_name['manure'][$key]) : '';
                        $TagManure->manure_type = 0;
                        $TagManure->manure_amount = isset($manure_amount['manure'][$key]) ? htmlentities($manure_amount['manure'][$key]) : '';
                        $TagManure->manure_brand = '';
                        $TagManure->manure_suppliers = '';
                        if(!$TagManure->save()) {
                            throw new \Exception("TagManure_errors");
                        }
                    }
                }
                
                /* 农药*/
                if(isset($use_period['pesticide'])) {
                    foreach ($use_period['pesticide'] as $key => $val) {
                        $TagPesticide = new M\TagPesticide();
                        $TagPesticide->tag_id =  $tag_id;
                        $TagPesticide->use_period = isset($use_period['pesticide'][$key]) ? $use_period['pesticide'][$key] : '';
                        $TagPesticide->pesticide_name = isset($manure_name['pesticide'][$key]) ? htmlentities($manure_name['pesticide'][$key]) : '';
                        $TagPesticide->pesticide_amount = isset($manure_amount['pesticide'][$key]) ? htmlentities($manure_amount['pesticide'][$key]) : '';;
                        $TagPesticide->pesticide_brand = '';
                        $TagPesticide->pesticide_suppliers = '';
                        if(!$TagPesticide->create()) {
                            throw new \Exception("TagPesticide_errors");
                        }
                    }
                }
                $TagSeed      = new M\TagSeed();
                $TagSeed->tag_id = $tag_id;
                $TagSeed->crops = M\Category::getparent($sellInfo->category);
                $TagSeed->breed = $breed;
                $TagSeed->neatness = $neatness;
                $TagSeed->fineness = $fineness;
                $TagSeed->sprout = $sprout;
                $TagSeed->water = $water;
                $TagSeed->add_time = $time;
                $TagSeed->last_update_time = $time;
                if(!$TagSeed->save()) {
                    throw new \Exception("TagSeed_errors");
                }
                foreach ($plantoperate_type as $key => $val) {
                    $TagPlant = new M\TagPlant();
                    $TagPlant->tag_id = $tag_id;
                    $TagPlant->begin_date = isset($plantbegin_date[$key]) ? strtotime(($plantbegin_date[$key])) : 0;
                    $TagPlant->end_date = isset($plantend_date[$key]) ? strtotime($plantend_date[$key]) : 0 ;
                    $TagPlant->weather = '';
                    $TagPlant->comment = '';
                    $TagPlant->operate_type = isset($plantoperate_type[$key]) ? intval($plantoperate_type[$key]) : 0 ;
                   
                    if(!$TagPlant->save()) {
                            throw new \Exception("TagPlant_errors");
                    }
                }
            
            $this->db->commit();
        } catch (\Exception $e) {
            $this->db->rollback();
        }
        $this->response->redirect("sell/index")->sendHeaders();
    }

    // /**
    //  * 标签申请保存
    //  * @return [type] [description]
    //  */
    // public function createAction() 
    // {

        
    //     if(!$this->request->getPost()) {
    //          return $this->dispatcher->forward(array(
    //             "controller" => "tag",
    //             "action" => "index",
    //         ));
    //         exit;
    //     }
    //     //查询基本信息
    //     $province          = intval($this->request->getPost('province', 'int', 0));
    //     $city              = intval($this->request->getPost('city', 'int', 0));
    //     $county            = intval($this->request->getPost('county', 'int', 0));
    //     $townlet           = intval($this->request->getPost('townlet', 'int', 0));
    //     $village           = intval($this->request->getPost('village', 'int', 0));
    //     /**
    //      * 产品id
    //      * @var [type]
    //      */
    //     $sellid            = $this->request->getPost('sellid', 'int', 0);

    //     $address           = $this->request->getPost('address', 'string', '');
    //     $productor         = $this->request->getPost('productor', 'string', '');
    //     $expiration_date   = $this->request->getPost('expiration_date', 'string', '');
    //     $process_place     = $this->request->getPost('process_place', 'string', '');
    //     $process_merchant  = $this->request->getPost('process_merchant', 'string', '');
    //     $quality_level     = $this->request->getPost('quality_level', 'string', '');
    //     $pesticide_residue = $this->request->getPost('pesticide_residue', 'string', '');
    //     $heavy_metal       = $this->request->getPost('heavy_metal', 'string', '');
    //     $inspector         = $this->request->getPost('inspector', 'string', '');
    //     $inspect_time      = $this->request->getPost('inspect_time', 'string', '');
    //     $certifying_agency = $this->request->getPost('certifying_agency', 'string', '');
    //     $product_place     = $this->request->getPost('product_place', 'string', '');
    //     $manure            = $this->request->getPost('manure', 'string', '');
    //     $pollute           = $this->request->getPost('pollute', 'string', '');
    //     $breed             = $this->request->getPost('breed', 'string', '');
    //     $seed_quality      = $this->request->getPost('seed_quality', 'string', '');
    //     $manure_type       = $this->request->getPost('manure_type', 'string', '');
    //     $manure_amount     = $this->request->getPost('manure_amount', 'string', '');
    //     $pesticides_type   = $this->request->getPost('pesticides_type', 'string', '');
    //     $pesticides_amount = $this->request->getPost('pesticides_amount', 'string', '');
    //     $process_type      = $this->request->getPost('process_type', 'string', '');
    //     /* 种植信息 */
    //     $product_date      = $this->request->getPost('product_date', 'string', '');
    //     $operate_type      = $this->request->getPost('operate_type');
    //     $begin_date        = $this->request->getPost('begin_date');
    //     $end_date          = $this->request->getPost('end_date');
    //     $weather           = $this->request->getPost('weather');
    //     $comment           = $this->request->getPost('comment');
    //     $is_gene = $this->request->getPost('is_gene');
    //     /*  农药使用 */
    //     $manure_use_period = $this->request->getPost('manure_use_period');
    //     $manure_name = $this->request->getPost('manure_name');
    //     $manure_type = $this->request->getPost('manure_type');
    //     $manure_amount = $this->request->getPost('manure_amount');
    //     $manure_brand = $this->request->getPost('manure_brand');
    //     $manure_suppliers = $this->request->getPost('manure_suppliers');
    //     /* 肥料信息 */
    //     $pesticide_use_period = $this->request->getPost('pesticide_use_period');
    //     $pesticide_name = $this->request->getPost('pesticide_name');
    //     $pesticide_amount = $this->request->getPost('pesticide_amount');
    //     $pesticide_brand = $this->request->getPost('pesticide_brand');
    //     $pesticide_suppliers = $this->request->getPost('pesticide_suppliers');
    //     $time = time();
  
    //     $sid = $this->session->getId();
    //     $data = M\Sell::findFirst(" id = '{$sellid}'");
     
    //     if (!$data)          
    //     {
    //         exit('商品信息不存在');
    //     }  
       
    //     //标签基本信息
    //     $tag = new M\Tag();
    //     $tag->category_one = intval($data->maxcategory);
    //     $tag->category_two = intval($data->twocategroy);
    //     $tag->category_three = intval($data->category);
    //     $tag->user_id = $data->uid;
    //     $tag->goods_name = $data->title;
    //     $tag->province = $province;
    //     $tag->city = $city;
    //     $tag->district = $county;
    //     $tag->townlet = $townlet;
    //     $tag->village = $village;
    //     $tag->address = $address;
    //     $tag->productor = $productor;
    //     $tag->product_date = $product_date;
    //     $tag->expiration_date = $expiration_date;
    //     $tag->is_gene = $is_gene;
    //     $tag->min_price = $data->min_price;
    //     $tag->max_price = $data->max_price;
    //     $tag->sell_id = $sellid;
    //     $tag->path = '';
    //     $tag->add_time = $time;
    //     $tag->process_place = $process_place;
    //     $tag->process_merchant = $process_merchant;
    //     $tag->status = 0;
    //     $areas = mAreas::getFamily($tag->village);
    //     $ares = array_column($areas, 'name');
    //     $addAreas = join("", $ares) . $tag->address;
    //     $tag->full_address = $addAreas;
    //     $tag->source_no = L\Func::random(20, 1 );
        
    //     if (!$tag->create()) 
    //     {
           
    //     }
    //     $tag_id = $tag->tag_id;
    //     $tag->path = $this->getqrcodeAction($tag_id, 1);
    //     $tag->save();

    //     /* 农药信息 */
    //     foreach ($manure_use_period as $key => $val) {
    //         $TagManure = new M\TagManure();
    //         $TagManure->tag_id = $tag_id;
    //         $TagManure->use_period = htmlentities($val);
    //         $TagManure->manure_name = isset($manure_name[$key]) ? htmlentities($manure_name[$key]) : '';
    //         $TagManure->manure_type = isset($manure_type[$key]) ? intval($manure_type[$key]) : '';
    //         $TagManure->manure_amount = isset($manure_amount[$key]) ? intval($manure_amount[$key]) : ''; 
    //         $TagManure->manure_brand = isset($manure_brand[$key]) ? htmlentities($manure_brand[$key]) : ''; 
    //         $TagManure->manure_suppliers = isset($manure_suppliers[$key]) ? htmlentities($manure_suppliers[$key]) : ''; 
    //         if(!$TagManure->save() ) {
    //              $TagManure->getMessages();
    //          }
    //     }

    //     /* 肥料信息 */
    //     foreach ($pesticide_use_period as $key => $val) {
    //         $TagPesticide = new M\TagPesticide();
    //         $TagPesticide->tag_id = $tag_id;
    //         $TagPesticide->use_period = htmlentities($val);
    //         $TagPesticide->pesticide_name = isset($pesticide_name[$key]) ? htmlentities($pesticide_name[$key]) : '';
    //         $TagPesticide->pesticide_amount = isset($pesticide_amount[$key]) ? htmlentities($pesticide_amount[$key]) : '';
    //         $TagPesticide->pesticide_brand = isset($pesticide_brand[$key]) ? htmlentities($pesticide_brand[$key]) : '';
    //         $TagPesticide->pesticide_suppliers = isset($pesticide_suppliers[$key]) ? htmlentities($pesticide_suppliers[$key]) : '';
    //         if(!$TagPesticide->save() ) {
    //              $TagPesticide->getMessages();
    //          }
    //     }

    //     //质量评估
    //     $TagQuality                    = new M\TagQuality();
    //     $TagQuality->tag_id            = $tag_id; //$tag->id;
    //     $TagQuality->quality_level     = $quality_level;
    //     $TagQuality->heavy_metal       = $heavy_metal;
    //     $TagQuality->pesticide_residue = $pesticide_residue;
    //     $TagQuality->is_gene           = $is_gene;
    //     $TagQuality->inspector         = $inspector;
    //     $TagQuality->inspect_time      = $inspect_time ? strtotime($inspect_time) : 0 ;
    //     $TagQuality->certifying_agency = $certifying_agency;
    //     $TagQuality->certifying_file   = '';
    //     /* 检测上传机构文件 */
    //     $certifying_file = M\TmpFile::find(" sid = '{$sid}' AND  type = 24 ");
    //     /* 保存机构文件 */
    //     if ($certifying_file) 
    //     {
    //         foreach ($certifying_file as $c) {
    //             $TagCertifying = new M\TagCertifying();
    //             $TagCertifying->certifying_file = $c->file_path;
    //             $TagCertifying->tag_id = $tag_id;
    //             $TagCertifying->status = 1;
    //             if(!$TagCertifying->save() ) {
    //                 $TagCertifying->getMessages();
    //             }
    //         }
    //     }

    //     if (!$TagQuality->create()) 
    //     {
    //         $tag->getMessages();
    //     }

    //     /* 生产信息  */
    //     $TagProduct                    = new M\TagProduct();
    //     $TagProduct->tag_id            = $tag_id; //$tag_id;
    //     $TagProduct->product_place     = $product_place;
    //     $TagProduct->manure            = $manure;
    //     $TagProduct->pollute           = $pollute;
    //     $TagProduct->breed             = $breed;
    //     $TagProduct->seed_quality      = $seed_quality;
    //     $TagProduct->manure_type       = $manure_type = 0;
    //     $TagProduct->manure_amount     = $manure_amount;
    //     $TagProduct->pesticides_type   = $pesticides_type;
    //     $TagProduct->pesticides_amount = $pesticides_amount;
    //     $TagProduct->process_type      = $process_type;
    //     $picture                       = M\TmpFile::find(" sid = '{$sid}' AND  type = 25 ORDER BY addtime asc ");
        
    //     if (!$TagProduct->create()) 
    //     {
    //     }
        
    //     foreach ($picture as $key => $val) 
    //     {
    //         $tag_picture = new M\TagPicture();
    //         $tag_picture->tag_id = $tag->tag_id;
    //         $tag_picture->order = $key;
    //         $tag_picture->path = $val->file_path;
    //         $tag_picture->status = 0;
    //         $tag_picture->add_time = $time;
    //         $tag_picture->last_update_time = $time;
    //         $tag_picture->plant_id = 0;
    //         $tag_picture->picture_source = 0;
    //         if(!$tag_picture->save() ) {
    //             $tag_picture->getMessages();
    //         }
    //     }
    //     //标签种植作业
        
    //     foreach ($operate_type as $key => $val) 
    //     {
    //         $TagPlant               = new M\TagPlant();
    //         $TagPlant->tag_id       = $tag->tag_id;
    //         $TagPlant->begin_date   = (isset($begin_date[$key]) && $begin_date[$key]) ? strtotime($begin_date[$key]) : 0;
    //         $TagPlant->end_date     = (isset($end_date[$key]) && $end_date[$key]) ? strtotime($end_date[$key]) : 0;
    //         $TagPlant->weather      = $weather[$key];
    //         $TagPlant->comment      = $comment[$key];
    //         $TagPlant->operate_type = intval($val);
    //         //生成图片
    //         $TagPlant->save();
    //         // print_R($TagPlant->getMessages());
    //         $dataFile = M\TmpFile::find(" sid = '{$sid}' AND  type = '26_{$key}' ORDER BY addtime asc ");
            
    //         foreach ($dataFile as $order => $row) 
    //         {
    //             $tag_picture                   = new M\TagPicture();
    //             $tag_picture->tag_id           = $tag->tag_id;
    //             $tag_picture->order            = $order;
    //             $tag_picture->path             = $row->file_path;
    //             $tag_picture->status           = 0;
    //             $tag_picture->add_time         = $time;
    //             $tag_picture->last_update_time = $time;
    //             $tag_picture->plant_id         = $TagPlant->id;
    //             $tag_picture->picture_source   = 1;
    //             $tag_picture->save();
    //             // print_R($tag_picture->getMessages());
    //         }
    //     }
        
    //     $sid = $this->session->getId();
    //     M\TmpFile::clearOld($sid);
    //     // echo $msg="添加成功";die;
    //     //添加成功
    //     $this->response->redirect("sell/index")->sendHeaders();
    // }

    /**
     * 删除真实照片
     * @return [type] [description]
     */
    public function removeAction() 
    {
        $id = $this->request->get('id', 'int', 0);
        $type = $this->request->get('type', 'int', 0);
        $tab = L\Validator::replace_specialChar($this->request->get('tab', 'string', ''));
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



    function check_remote_file_exists($url) {
        $curl = curl_init($url);
        //不取回数据
        curl_setopt($curl, CURLOPT_NOBODY, true);
        //发送请求
        $result = curl_exec($curl);
        $found = false;
        if ($result !== false) {
            //检查http响应码是否为200
            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);  
            if ($statusCode == 200) {
                $found = true;   
            }
        }
        curl_close($curl);
        return $found;
    }

    
}