<?php
/**
 * 盟商供应信息
 */
namespace Mdg\Manage\Controllers;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Mdg\Models as M;
use Lib\File as File;
use Mdg\Models\Sell as Sell;
use Lib\Func as Func;
use Lib\Areas as lAreas;
use Mdg\Models\Purchase as Purchase;
use Mdg\Models\TmpFile as TmpFile;
use Mdg\Models\SellImages as SellImages;
use Mdg\Models\Users as Users;
use Mdg\Models\UsersExt as UsersExt;
use Mdg\Models\AreasFull as mAreas;
use Lib\Pages as Pages;
use Lib\Category as lCategory;
use Lib\Path as Path;
use Lib\Member as Member;
use Lib\Auth as Auth;
use Lib\SMS as sms;
use Lib\Utils as Utils;
use Lib\Excel as Excel;

class SellshareController extends ControllerMember
{

    /**
     * 盟商供应列表
     */
    public function indexAction(){
        $plat = $this->request->get('plat', 'int', 0);
        $page = $this->request->get('p', 'int', 1);
        $maxcategory = $this->request->get("maxcategory", 'string', '');
        $category = $this->request->get("category", 'string', '');
        $orderattribute =$this->request->get('orderattribute', 'int',0);
        $sell_id = $this->request->get("id",'int','');
        $sell_name = $this->request->get("sell_name",'string','');
        $mobile = $this->request->get("mobile");
        $goods_name = $this->request->get("goods_name");
        $goods_breed = $this->request->get("goods_breed");
        $goods_number = $this->request->get("goods_number");
        $start_time = $this->request->get("start_time");
        $end_time = $this->request->get("end_time");
        $buy_min = $this->request->get("buy_min");
        $farm_areas = $this->request->get("farm_areas");
        $province_id = $this->request->get("province_id");
        $city_id = $this->request->get("city_id");
        $district_id = $this->request->get("district_id");
        $townlet_id = $this->request->get("townlet_id");
        $village_id = $this->request->get("village_id");
        $state = $this->request->get('state');
        $update_name = $this->request->get('update_name');

        $where = '1=1';
        if($sell_id){
            $where .= " AND s.share_id ={$sell_id} ";
        }
        if($sell_name){
            $where .= " AND s.sell_name LIKE '%{$sell_name}%'";
        }
        if($mobile){
            $where .= " AND s.mobile ='{$mobile}' ";
        }
        if($goods_name){
            $where .= " AND s.goods_name LIKE '%{$goods_name}%'";
        }
        if($goods_breed){
            $where .= " AND s.goods_breed LIKE '%{$goods_breed}%'";
        }
        if($goods_number){
            $where .= " AND s.goods_number={$goods_number}";
        }
        if($start_time && $end_time){
            $sellstime = strtotime($start_time);
            $selletime = strtotime($end_time);
            $where .= " AND s.start_time<={$sellstime} AND s.end_time>={$selletime}";
        }
        if($buy_min){
            $where .= " AND s.buy_min={$buy_min}";
        }
        if($farm_areas){
            $where .= " AND s.farm_areas = {$farm_areas}";
        }
        $curAreas = '';
        if($province_id){
            $where .= " AND s.province_id={$province_id}";
            $curAreas = lAreas::ldData($province_id);
        }
        if($city_id){
            $where .= " AND s.city_id={$city_id}";
            $curAreas = lAreas::ldData($city_id);
        }
        if($district_id){
            $where .= " AND s.district_id={$district_id}";
            $curAreas = lAreas::ldData($district_id);
        }
        if($townlet_id) {
            $where .= " and s.townlet_id = '{$townlet_id}'";
            $curAreas = lAreas::ldData($townlet_id);
        }
        if($village_id) {
            $where .= " and s.village_id = '{$village_id}'";
            $curAreas = lAreas::ldData($village_id);
        }
        if($update_name){
            $where .= " and s.update_name = '{$update_name}'";
        }
        if ($maxcategory)
        {
            $max_category = M\Category::getChildcate($maxcategory);
            $where.= " and s.cat_id in ($max_category,$maxcategory) ";
            $pcurCate = M\Category::getFamily($max_category);
        }
        if ($category)
        {
            $where.= " and s.cat_id =  {$category}";
            $curCate = M\Category::getFamily($category);
        }
        $ptexttitle = '';
        if(isset($pcurCate['0']['title']) && isset($pcurCate['1']['title'])){
            $texttitle = $ptexttitle = "'".$pcurCate['0']['title']."'".','." ";
        }else{
            $texttitle = '';
        }

        if(isset($curCate['0']['title']) && isset($curCate['1']['title'])){
            $texttitle = "'".$curCate['0']['title']."'".','."'".$curCate["1"]["title"]."'";
        }else{

            $texttitle = ''.$ptexttitle;
        }

        if($state) { #状态处理
            switch($state){
                case 2:
                    $tim = time();
                    $sql = "UPDATE sell_share SET state = 2 WHERE end_time<{$tim} AND start_time <>0";
                    $this->db->execute($sql);
                    break;
                case 3:
                    $state = 0;
                    break;
            }
            $where .= " and s.state = '{$state}'";
            if($state == 0){
                $state = 3;
            }
        }
        $page_size = 20;
        $sql="SELECT count(*) as count from sell_share as s   where {$where} ";
        $count=$this->db->fetchOne($sql,2);
        $total = $count["count"];
        $offst = intval(($page - 1) * $page_size);
        $sql = "SELECT s.*,c.title AS stitle,cp.title AS ptitle FROM sell_share AS s ".
                " LEFT JOIN category AS c ON c.id =s.cat_id".
                " LEFT JOIN category AS cp ON c.parent_id =cp.id".
                " where {$where} ORDER BY s.add_time DESC limit {$offst} , {$page_size} ";
        $data=$this->db->fetchAll($sql,2);
        foreach($data AS $aK => $share){
            $province_name = M\AreasFull::getAreasNametoid($share['province_id']);
            $city_name = M\AreasFull::getAreasNametoid($share['city_id']);
            $districte_name = M\AreasFull::getAreasNametoid($share['district_id']);
            $townlet_name = M\AreasFull::getAreasNametoid($share['townlet_id']);
            $village_name = M\AreasFull::getAreasNametoid($share['village_id']);
            $data[$aK]['full_address'] = $province_name.$city_name.$districte_name.$townlet_name.$village_name;
            if($share['start_time']){
                $data[$aK]['start_time'] = date('Y-m-d ',$share['start_time']);
                $data[$aK]['end_time'] = date('Y-m-d ',$share['end_time']);
            }
        }

        $pages['total_pages'] = ceil($total / $page_size);
        $pages['current'] = $page;
        $pages['total'] = $total;
        $pages = new Pages($pages);
        $pages = $pages->show(3);
        $this->view->sellstate = Sell::$sellstate;
        $this->view->current = $page;
        $this->view->data = $data;
        $this->view->pages = $pages;
        $this->view->id =$sell_id;
        $this->view->sell_name = $sell_name;
        $this->view->mobile = $mobile;
        $this->view->goods_name = $goods_name;
        $this->view->goods_breed = $goods_breed;
        $this->view->goods_number = $goods_number;
        $this->view->start_time = $start_time;
        $this->view->end_time = $end_time;
        $this->view->buy_min = $buy_min;
        $this->view->farm_areas = $farm_areas;
        $this->view->status = M\SellShare::$status;
        $this->view->sel_state = $state;
        $this->view->update_name = $update_name;
        $this->view->curAreas = $curAreas;
        $this->view->textCate = $texttitle;
    }

    /**
     * 增加修改Action
     * @param null $share_id
     */
    public function editAction($share_id = null){
        $share = ($share_id != null)?M\SellShare::findFirstByshare_id($share_id)->toArray():null;
        $curCate = M\Category::getFamily($share['cat_id']);
        if(isset($curCate['0']['title']) && isset($curCate['1']['title'])){
            $texttitle = "'".$curCate['0']['title']."'".','."'".$curCate["1"]["title"]."'";
        }else{
            $texttitle = '';
        }
        if($share['start_time']){
            $share['start_time'] = date('Y-m-d',$share['start_time']);
            $share['end_time'] = date('Y-m-d',$share['end_time']);
        }
        $curAreas = lAreas::ldData($share['village_id']);
        $this->view->share_id = $share_id;
        $this->view->share = ($share)?$share:null;
        $this->view->curAreas = $curAreas;
        $this->view->textCate = $texttitle;
        $this->view->goods_unit = M\Purchase::$_goods_unit;

    }
    public function saveAction(){
        $share_id = $this->request->getPost('share_id','int',0);
        $sell_name = $this->request->getPost("sell_name");
        $mobile = $this->request->getPost("mobile");
        $cat_id = $this->request->getPost("cat_id");
        $goods_name = $this->request->getPost("goods_name");
        $goods_breed = $this->request->getPost("goods_breed");
        $goods_unit = $this->request->getPost("goods_unit");
        $buy_min = $this->request->getPost("buy_min");
        $goods_number = $this->request->getPost("goods_number");
        $s_time = $this->request->getPost("s_time",'int',0);
        $start_time = $this->request->getPost("start_time");
        $end_time = $this->request->getPost("end_time");
        $province_id = $this->request->getPost("province_id");
        $city_id = $this->request->getPost("city_id");
        $district_id = $this->request->getPost("district_id");
        $townlet_id = $this->request->getPost("townlet_id");
        $village_id = $this->request->getPost("village_id");
        $farm_areas = $this->request->getPost("farm_areas");
        $state = $this->request->getPost("state");
        $username = $this->getUsername();
        $userid   = $this->getUserID();
        $msg = [];
        if($share_id)
            $sellShare  = M\SellShare::findFirstByshare_id($share_id);
        else{
            $sellShare = new M\SellShare();
            $sellShare->add_time = CURTIME;
        }

        $sellShare->sell_name = $sell_name;
        $sellShare->mobile = $mobile;
        $sellShare->cat_id = $cat_id;
        $sellShare->goods_name = $goods_name;
        $sellShare->goods_breed = $goods_breed;
        $sellShare->goods_unit = M\Purchase::$_goods_unit[$goods_unit];
        $sellShare->buy_min = $buy_min;
        if($s_time == 0){
            $sellShare->start_time = 0;
        }
        else{
            $sellShare->start_time = strtotime($start_time);
            $sellShare->end_time = strtotime($end_time);
        }
        $sellShare->province_id = $province_id;
        $sellShare->city_id = $city_id;
        $sellShare->district_id = $district_id;
        $sellShare->townlet_id = $townlet_id;
        $sellShare->village_id = $village_id;
        $sellShare->farm_areas = $farm_areas;
        $sellShare->state = $state;
        $sellShare->last_update_time = CURTIME;
        $sellShare->update_id     = $userid;
        $sellShare->update_name   = $username;
        $sellShare->goods_number  = $goods_number;
        if (!$sellShare->save())
        {

            foreach ($sellShare->getMessages() as $message)
            {
                $msg[$message->getField() ] = $message->getMessage();
            }
        }

        if ($msg)
        {
            $this->dispatcher->forward(array(
                "controller" => "sellshare",
                "action" => 'edit',
                'params' => [$share_id,$msg,
                    $_POST]
            ));
            return false;
        }

//        $next_name = '操作成功,返回商品列表';
//        $next_url = '/sellshare/index';
//        $this->dispatcher->forward(array(
//            "controller" => "sellshare",
//            "action" => "index"
//        ));
        $this->response->redirect('sellshare/index')->sendHeaders();

    }
    public function set_stopAction(){
        $share_id = $this->request->getPost('share_id');
        $status = $this->request->getPost('s');
        if(isset($share_id) && isset($status)){
            $sellShare = M\SellShare::findFirstByshare_id($share_id);
            $set_state = $status == 1?0:1;
            $sellShare->state= $set_state;
            $sellShare->save();
            echo true;
        }
        else{
            echo false;
        }
        exit;
    }
    /*
     *导出excel表
     */
    public function downExcelAction(){
        $plat = $this->request->get('plat', 'int', 0);
        $page = $this->request->get('p', 'int', 1);
        $maxcategory = $this->request->get("maxcategory", 'string', '');
        $category = $this->request->get("category", 'string', '');
        $orderattribute =$this->request->get('orderattribute', 'int',0);
        $sell_id = $this->request->get("id",'int','');
        $sell_name = $this->request->get("sell_name",'string','');
        $mobile = $this->request->get("mobile");
        $goods_name = $this->request->get("goods_name");
        $goods_breed = $this->request->get("goods_breed");
        $goods_number = $this->request->get("goods_number");
        $start_time = $this->request->get("start_time");
        $end_time = $this->request->get("end_time");
        $buy_min = $this->request->get("buy_min");
        $farm_areas = $this->request->get("farm_areas");
        $province_id = $this->request->get("province_id");
        $city_id = $this->request->get("city_id");
        $district_id = $this->request->get("district_id");
        $townlet_id = $this->request->get("townlet_id");
        $village_id = $this->request->get("village_id");
        $state = $this->request->get('state');
        $update_name = $this->request->get('update_name');

        $where = '1=1';
        if($sell_id){
            $where .= " AND s.share_id ={$sell_id} ";
        }
        if($sell_name){
            $where .= " AND s.sell_name LIKE '%{$sell_name}%'";
        }
        if($mobile){
            $where .= " AND s.mobile ='{$mobile}' ";
        }
        if($goods_name){
            $where .= " AND s.goods_name LIKE '%{$goods_name}%'";
        }
        if($goods_breed){
            $where .= " AND s.goods_breed LIKE '%{$goods_breed}%'";
        }
        if($goods_number){
            $where .= " AND s.goods_number={$goods_number}";
        }
        if($start_time && $end_time){
            $sellstime = strtotime($start_time);
            $selletime = strtotime($end_time);
            $where .= " AND s.start_time<={$sellstime} AND s.end_time>={$selletime}";
        }
        if($buy_min){
            $where .= " AND s.buy_min={$buy_min}";
        }
        if($farm_areas){
            $where .= " AND s.farm_areas = {$farm_areas}";
        }
        if($province_id){
            $where .= " AND s.province_id={$province_id}";
        }
        if($city_id){
            $where .= " AND s.city_id={$city_id}";
        }
        if($district_id){
            $where .= " AND s.district_id={$district_id}";
        }
        if($townlet_id) {
            $where .= " and s.townlet_id = '{$townlet_id}'";
        }
        if($village_id) {
            $where .= " and s.village_id = '{$village_id}'";
        }
        if($update_name){
            $where .= " and s.update_name = '{$update_name}'";
        }
        if ($maxcategory)
        {
            $max_category = M\Category::getChildcate($maxcategory);
            $where.= " and s.cat_id in ($max_category,$maxcategory) ";
        }
        if ($category)
        {
            $where.= " and s.cat_id =  {$category}";
        }

        if($state) { #状态处理
            switch($state){
                case 2:
                    $tim = time();
                    $sql = "UPDATE sell_share SET state = 2 WHERE end_time<{$tim} AND start_time <>0";
                    $this->db->execute($sql);
                    break;
                case 3:
                    $state = 0;
                    break;
            }
            $where .= " and s.state = '{$state}'";
            if($state == 0){
                $state = 3;
            }
        }
        $sql = "SELECT s.*,c.title AS stitle,cp.title AS ptitle FROM sell_share AS s ".
                " LEFT JOIN category AS c ON c.id =s.cat_id".
                " LEFT JOIN category AS cp ON c.parent_id =cp.id".
                " where {$where} ORDER BY s.share_id ";
        $data=$this->db->fetchAll($sql,2);
        foreach($data AS $key => $share){
            $province_name  = M\AreasFull::getAreasNametoid($share['province_id']);
            $city_name      = M\AreasFull::getAreasNametoid($share['city_id']);
            $districte_name = M\AreasFull::getAreasNametoid($share['district_id']);
            $townlet_name   = M\AreasFull::getAreasNametoid($share['townlet_id']);
            $village_name   = M\AreasFull::getAreasNametoid($share['village_id']);
            $sellshare[$key]['id']           = $share['share_id'];
            $sellshare[$key]['sell_name']    = $share['sell_name'];
            $sellshare[$key]['mobile']       = $share['mobile'];                       
            $sellshare[$key]['ptitle']       = $share['ptitle'];
            $sellshare[$key]['stitle']       = $share['stitle'];
            $sellshare[$key]['goods_name']   = $share['goods_name'];
            $sellshare[$key]['goods_breed']  = $share['goods_breed'];
            $sellshare[$key]['numbers']      = $share['goods_number'];
            $sellshare[$key]['goods_unit']   = $share['goods_unit'];
            $sellshare[$key]['buy_min']      = $share['buy_min'];
            if($share['start_time']){
                $sellshare[$key]['time'] = date('Y-m-d ',$share['start_time'])."~".date('Y-m-d ',$share['end_time']);
            }else{
                $sellshare[$key]['time'] = "长期有效";
            }           
            $sellshare[$key]['province']     = $province_name;
            $sellshare[$key]['city']         = $city_name;
            $sellshare[$key]['districte']    = $districte_name;
            $sellshare[$key]['townlet']      = $townlet_name;
            $sellshare[$key]['village']      = $village_name;
            $sellshare[$key]['farm_areas']   = $share['farm_areas'];
            switch ($share['state']) {
              case '0':$sellshare[$key]['state'] = '停用';break;
              case '1':$sellshare[$key]['state'] = '可用';break;
              case '2':$sellshare[$key]['state'] = '过期';break;
            }                     
        }
        $tableheader = array('*ID','*供应商姓名','*供应商电话','*一级分类','*二级分类','*产品名称','*产品品种','*采购数量','*单位名称','*起订数量','*供货期限','*省','*市','*区','*乡/镇','*村','*农场亩数','*状态');    
        $filename = 'supply';
        $excel = Excel::downexcel($tableheader,$sellshare,$filename);
    }

    /*
     *导入excel表
     */
    public function importExcelAction(){
        include_once 'excel/Classes/PHPExcel.php';
        include_once 'excel/Classes/PHPExcel/IOFactory.php';
        require_once 'excel/Classes/PHPExcel/Reader/Excel5.php';
        if (!File::check_excel($_FILES["Filedata"]['name'])) 
        {
            echo "<script>alert('表格格式不对');'</script>";
        }
        $categoryPath = 'excel/' . date('Ymd') . '/';
        $rs = File::move_excel($_FILES["Filedata"], $categoryPath);
        $excel_path = $rs['path'];
        $error=array();
        if ($excel_path) 
        {
            $file_url      = $excel_path;
            $objReader     = \PHPExcel_IOFactory::createReader('Excel5');//use excel2007 for 2007 format
            $objPHPExcel   = $objReader->load($file_url);//$file_url即Excel文件的路径
            $sheet         = $objPHPExcel->getSheet(0);//获取第一个工作表
            $highestRow    = $sheet->getHighestRow();//取得总行数
            $error=array();  
            //循环读取excel文件,读取一条,插入一条
            for($i = 2; $i<=$highestRow;$i++){//从第一行开始读取数据
                for($j='A';$j!='S';$j++){  
                    //数据坐标  
                    $address=$j.$i;
                    //读取到的数据，保存到数组$data中       
                    if($objPHPExcel->getActiveSheet()->getCell($address)->getValue() instanceof PHPExcel_RichText){
                        $data[$i][$j]=$objPHPExcel->getActiveSheet()->getCell($address)->getValue()->__toString();
                    }else{
                        $data[$i][$j]=$objPHPExcel->getActiveSheet()->getCell($address)->getValue(); 
                    }
                }
            }
            // print_r($data);die;
            $errorcount = $successcount = 0;
            $total = count($data)?count($data):0;
            foreach ($data as $key => $value) {
                //筛选导入数据键值全部不为空的数组
                foreach ($value as $k => $val) {
                    $state = 0;
                    if($val){
                        $state = 1;
                    }
                }
                if($state == 1){
                    $str = '';
                    //验证是否为空
                    $str.= Excel::empty_arr($value);
                    //验证省级联动
                    $str.= Excel::Areas($value['L'],$value['M'],$value['N'],$value['O'],$value['P']);
                    //验证产品分类
                    $str.= Excel::category($value['D'],$value['E']);
                    //验证采购单位
                    $str.= Excel::untils($value['I']);
                    //验证状态
                    $str.= Excel::state($value['R']);
                    if($str){
                        $data[$key]['S']=$str;
                        $error[]=$data[$key];                     
                    }
                    // print_r($error);die;
                    if(empty($error)){
                        if($value['A']){
                            $sellshare = M\SellShare::findFirstByshare_id($value['A']);
                        }else{
                            $sellshare = new M\SellShare();
                        }
                        $sellshare->sell_name     = $value['B'];
                        $sellshare->mobile        = $value['C'];
                        $sellshare->pcat_id       = M\Category::selectBytitle($value['D']);
                        $sellshare->cat_id        = M\Category::selectBytitle($value['E']);
                        $sellshare->goods_name    = $value['F'];
                        $sellshare->goods_breed   = $value['G'];
                        $sellshare->goods_number  = $value['H'];
                        $sellshare->goods_unit    = $value['I'];
                        $sellshare->buy_min       = $value['J'];
                        if($value['K']=='长期有效'){
                            $sellshare->start_time = 0;
                        }else{
                            $time = explode("~",$value['K']);
                            $sellshare->start_time = strtotime($time['0']);
                            $sellshare->end_time   = strtotime($time['1']);
                        }
                        $datas = M\AreasFull::get_id($value['L'],$value['M'],$value['N'],$value['O'],$value['P']);
                        if($datas){
                            $sellshare->province_id = $datas['province_id'];
                            $sellshare->city_id     = $datas['city_id'];
                            $sellshare->district_id = $datas['districte_id'];
                            $sellshare->townlet_id  = $datas['townlet_id'];
                            $sellshare->village_id  = $datas['village_id'];

                        }
                        $sellshare->farm_areas    = $value['Q'];
                        switch ($value['R']) {
                          case '停用':$sellshare->state = '0';break;
                          case '可用':$sellshare->state = '1';break;
                          case '过期':$sellshare->state = '2';break;
                        }
                        $sellshare->add_time         = CURTIME;;
                        $sellshare->last_update_time = CURTIME;
                        $sellshare->update_id        = $this->getUserID();
                        $sellshare->update_name      = $this->getUsername();
                        $sellshare->save();                        
                    }
                }                
            }               
            $errorcount   = count($error);
            $successcount = $total-$errorcount; 
            if(!empty($error)){
                $this->downexcal($error);
                $rs = array('status'=>false, 'msg'=> '导入数据不完整！','successcount'=>$successcount,'errorcount'=>$errorcount);
            }else{
                $rs = array('status'=>true, 'msg'=> '导入成功！','successcount'=>$successcount,'errorcount'=>$errorcount);
            }
            
        }else{
            $rs = array('status'=>false, 'msg'=> '上传文件不存在');
        }
        die(json_encode($rs));
    }

    /*
     *导出excel表模板
     */
    public function downModelAction(){
        $tableheader = array('ID(不填)','*供应商姓名','*供应商电话','*一级分类','*二级分类','*产品名称','*产品品种','*采购数量','*单位名称','*起订数量','*供货期限','*省','*市','*区','*乡/镇','*村','*农场亩数','*状态');     
        $filename = 'supply_model';
        $sellshare[0]['id']            = '';
        $sellshare[0]['sell_name']     = '张三';
        $sellshare[0]['mobile']        = '13237100914';
        $sellshare[0]['ptitle']        = '蔬菜';
        $sellshare[0]['stitle']        = '大土豆';
        $sellshare[0]['goods_name']    = '测试大土豆';
        $sellshare[0]['goods_breed']   = '蔬菜';
        $sellshare[0]['numbers']       = '100.00';
        $sellshare[0]['goods_unit']    = '公斤';
        $sellshare[0]['goods_desc']    = '测试规格';
        $sellshare[0]['time']          = '2015-11-03~2015-11-11/长期有效';
        $sellshare[0]['province']      = '北京市';
        $sellshare[0]['city']          = '直辖市';
        $sellshare[0]['districte']     = '房山区';
        $sellshare[0]['townlet']       = '良乡镇';
        $sellshare[0]['village']       = '南关村';
        $sellshare[0]['farm_areas']    = '100';
        $sellshare[0]['state']         = '过期/停用/正常';
        $excel = Excel::downexcel($tableheader,$sellshare,$filename);

    }
    /**
     * 生成错误信息表
     */
    public function downexcal($arrs) 
    {
        unlink('excel/error.xls');
        if (!empty($arrs)) 
        {
            include_once 'excel/Classes/PHPExcel.php';
            include_once 'excel/Classes/PHPExcel/IOFactory.php';
            // 创建一个处理对象实例
            $objExcel = new \PHPExcel();
            $objWriter = new \PHPExcel_Writer_Excel5($objExcel); // 用于其他版本格式
            $objExcel->setActiveSheetIndex(0);
            $objActSheet = $objExcel->getActiveSheet();
            //设置当前活动sheet的名称
            $objActSheet->setTitle('exce导出');
            $objExcel->getActiveSheet()->setCellValue('A1', "*ID"); //设置列的值
            $objExcel->getActiveSheet()->setCellValue('B1', "*供应商姓名"); //设置列的值
            $objExcel->getActiveSheet()->setCellValue('C1', "*供应商电话"); //设置列的值
            $objExcel->getActiveSheet()->setCellValue('D1', "*一级分类"); //设置列的值
            $objExcel->getActiveSheet()->setCellValue('E1', "*二级分类"); //设置列的值
            $objExcel->getActiveSheet()->setCellValue('F1', "*产品名称"); //设置列的值
            $objExcel->getActiveSheet()->setCellValue('G1', "*产品品种"); //设置列的值
            $objExcel->getActiveSheet()->setCellValue('H1', "*采购数量"); //设置列的值
            $objExcel->getActiveSheet()->setCellValue('I1', "*单位名称"); //设置列的值
            $objExcel->getActiveSheet()->setCellValue('J1', "*起订数量"); //设置列的值
            $objExcel->getActiveSheet()->setCellValue('K1', "*供货期限"); //设置列的值
            $objExcel->getActiveSheet()->setCellValue('L1', "*省"); //设置列的值
            $objExcel->getActiveSheet()->setCellValue('M1', "*市"); //设置列的值
            $objExcel->getActiveSheet()->setCellValue('N1', "*区"); //设置列的值
            $objExcel->getActiveSheet()->setCellValue('O1', "*乡/镇"); //设置列的值
            $objExcel->getActiveSheet()->setCellValue('P1', "*村"); //设置列的值
            $objExcel->getActiveSheet()->setCellValue('Q1', "*状态"); //设置列的值
            $objExcel->getActiveSheet()->setCellValue('R1', "*农场亩数"); //设置列的值
            $objExcel->getActiveSheet()->setCellValue('S1', "*失败原因"); //设置列的值
            $objExcel->getActiveSheet()->getColumnDimension("A")->setWidth(15);
            $objExcel->getActiveSheet()->getColumnDimension("B")->setWidth(15);
            $objExcel->getActiveSheet()->getColumnDimension("C")->setWidth(15);
            $objExcel->getActiveSheet()->getColumnDimension("D")->setWidth(15);
            $objExcel->getActiveSheet()->getColumnDimension("E")->setWidth(15);
            $objExcel->getActiveSheet()->getColumnDimension("F")->setWidth(15);
            $objExcel->getActiveSheet()->getColumnDimension("G")->setWidth(15);
            $objExcel->getActiveSheet()->getColumnDimension("H")->setWidth(15);
            $objExcel->getActiveSheet()->getColumnDimension("I")->setWidth(15);
            $objExcel->getActiveSheet()->getColumnDimension("J")->setWidth(15);
            $objExcel->getActiveSheet()->getColumnDimension("K")->setWidth(15);
            $objExcel->getActiveSheet()->getColumnDimension("L")->setWidth(15);
            $objExcel->getActiveSheet()->getColumnDimension("M")->setWidth(15);
            $objExcel->getActiveSheet()->getColumnDimension("N")->setWidth(15);
            $objExcel->getActiveSheet()->getColumnDimension("O")->setWidth(15);
            $objExcel->getActiveSheet()->getColumnDimension("P")->setWidth(15);
            $objExcel->getActiveSheet()->getColumnDimension("Q")->setWidth(15);
            $objExcel->getActiveSheet()->getColumnDimension("R")->setWidth(15);
            $objExcel->getActiveSheet()->getColumnDimension("S")->setWidth(1500);
            $i = 2;
            foreach ($arrs as $key => $val) 
            {    
               
                if($val){
                    $objExcel->getActiveSheet()->setCellValue("A{$i}", $val['A']);
                    $objExcel->getActiveSheet()->setCellValue("B{$i}", $val['B']);
                    $objExcel->getActiveSheet()->setCellValue("C{$i}", $val['C']);
                    $objExcel->getActiveSheet()->setCellValue("D{$i}", $val['D']);
                    $objExcel->getActiveSheet()->setCellValue("E{$i}", $val['E']);
                    $objExcel->getActiveSheet()->setCellValue("F{$i}", $val['F']);
                    $objExcel->getActiveSheet()->setCellValue("G{$i}", $val['G']);
                    $objExcel->getActiveSheet()->setCellValue("H{$i}", $val['H']);
                    $objExcel->getActiveSheet()->setCellValue("I{$i}", $val['I']);
                    $objExcel->getActiveSheet()->setCellValue("J{$i}", $val['J']);
                    $objExcel->getActiveSheet()->setCellValue("K{$i}", $val['K']);
                    $objExcel->getActiveSheet()->setCellValue("L{$i}", $val['L']);
                    $objExcel->getActiveSheet()->setCellValue("M{$i}", $val['M']);
                    $objExcel->getActiveSheet()->setCellValue("N{$i}", $val['N']); //设置列的值
                    $objExcel->getActiveSheet()->setCellValue("O{$i}", $val['O']); //设置列的值
                    $objExcel->getActiveSheet()->setCellValue("P{$i}", $val['P']); //设置列的值
                    $objExcel->getActiveSheet()->setCellValue("Q{$i}", $val['Q']); //设置列的值
                    $objExcel->getActiveSheet()->setCellValue("R{$i}", $val['R']); //设置列的值
                    $objExcel->getActiveSheet()->setCellValue("S{$i}", $val['S']); //设置列的值
                    $i++;
                }
            }
            $objWriter->save('excel/error.xls'); 
        }
    }
    /*
     *导出错误信息
     */
    public function downloaderrorAction() 
    {
        $myfile = "excel/error.xls";
        $len = filesize($myfile);
        ob_end_clean();
        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: public');
        header('Content-Description: File Transfer');
        header('Content-type: application/octet-stream');
        header('Content-Disposition: attachment; filename="error.xls"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . $len);
        readfile($myfile);
    }

}
