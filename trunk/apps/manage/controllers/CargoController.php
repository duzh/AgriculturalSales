<?php
namespace Mdg\Manage\Controllers;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Mdg\Models as M;
use Mdg\Models\ConsultCategory as Category;
use Lib\Pages as Pages;
use Lib\Func as Func;
/**
 * 车源管理
 */

class CargoController extends ControllerBase
{
    /**
     * 车源列表
     */
    public function indexAction()
    {   
        $p = $this->request->get('p', 'int', 1 );
        $cond[] = " 1 ";
        $start_areas = array();
        $end_areasa = array();
        
        $goods_type  = $this->request->get('goods_type', 'string', '');

        $start_pid      = $this->request->get('start_pid', 'string', '');
        $start_cid      = $this->request->get('start_cid', 'string', '');
        $start_did      = $this->request->get('start_did', 'string', '');
        $end_pid        = $this->request->get('end_pid', 'string', '');
        $end_cid        = $this->request->get('end_cid', 'string', '');
        $end_did        = $this->request->get('end_did', 'string', '');
        if($start_pid) {
            $cond[] = " start_pid = '{$start_pid}'";
            $start_areas[] = M\AreasFull::getAreasNametoid($start_pid);
        }
        if($start_cid) {
            $cond[] = " start_cid = '{$start_cid}'";
            $start_areas[] = M\AreasFull::getAreasNametoid($start_cid);
        }
        if($start_did) {
            $cond[] = " start_did = '{$start_did}'";
            $start_areas[] = M\AreasFull::getAreasNametoid($start_did);
        }
        if($end_pid) {
            $cond[] = " end_pid = '{$end_pid}'";
            $end_areas[] = M\AreasFull::getAreasNametoid($end_pid);
        }
        if($end_cid) {
            $cond[] = " end_cid = '{$end_cid}'";
            $end_areas[] = M\AreasFull::getAreasNametoid($end_cid);
        }
        if($end_did) {
            $cond[] = " end_did = '{$end_did}'";
            $end_areas[] = M\AreasFull::getAreasNametoid($end_did);
        }
        if($goods_type != 'all' && $goods_type!='') {
            $cond[] = " goods_type = '{$goods_type}'";
        }

        $cond= implode( ' AND ', $cond);
        $data = M\CargoInfo::getCarInfoList($cond, $p, $this->db);

        $this->view->start_areas = join("','" ,$start_areas);
        $this->view->end_areas = join("','" ,$end_areas);
        $this->view->_BOX_TYPE  =M\CargoInfo::getGoodsType();
        $this->view->data = $data;
    }
    /**
     *  新增车源
     * @return [type] [description]
     */
    public function newAction () {
        $this->view->_BOX_TYPE  =M\CarInfo::getBoxType();
        $this->view->_BODY_TYPE  =M\CarInfo::getBoDYType();
        $this->view->_GOODS_TYPE  =M\CargoInfo::getGoodsType();
    }
    
    /**
     * 新增车源保存
     * @return [type] [description]
     */
    public function createAction () {

        if(!$this->request->getPost()) {
             return $this->dispatcher->forward(array(
                    "controller" => "car",
                    "action" => "index"
                ));
        }
        $CarInfo = new M\CargoInfo();
        $end_pid       = $this->request->getPost('end_pid','int', 0);
        $end_cid       = $this->request->getPost('end_cid','int', 0);
        $end_did       = $this->request->getPost('end_did','int', 0);
        
        $start_pid     = $this->request->getPost('start_pid','int', 0);
        $start_cid     = $this->request->getPost('start_cid','int', 0);
        $start_did     = $this->request->getPost('start_did','int', 0);
        
        $box_type      = $this->request->getPost('box_type','int', 0);
        $body_type     = $this->request->getPost('body_type','int', 0);
        $contact_man   = $this->request->getPost('contact_man','string', '');
        $contact_phone = $this->request->getPost('contact_phone','string', '');
        $goods_name    = $this->request->getPost('goods_name','string', '');
        $goods_type    = $this->request->getPost('goods_type','string', '');
        $goods_weight  = $this->request->getPost('goods_weight','string', '');
        $goods_size    = $this->request->getPost('goods_size','string', '');
        $except_price  = $this->request->getPost('except_price','string', '');
        $except_length = $this->request->getPost('except_length','string', '');
        $expire_time   = $this->request->getPost('expire_time','string', '');
        $is_longtime   = $this->request->getPost('is_longtime','string', 0);
        $demo          = $this->request->getPost('demo','string','');
        $is_longtime   = $this->request->getPost('is_longtime','int',0);
        $settle_type   = $this->request->getPost('settle_type','string','');

        $moblie                    =M\CargoInfo::GetMoblie($contact_phone);
        $expire_time               =strtotime($expire_time);
        $CarInfo->phone_number     = substr_replace($contact_phone,'****',3,4);
        $CarInfo->phone_img        =  $moblie;
        $CarInfo->box_type         =  $box_type;
        $CarInfo->body_type        =$body_type;
        $CarInfo->contact_phone    =$contact_phone;
        $CarInfo->contact_man      = $contact_man;
        $CarInfo->goods_name       = $goods_name;
        $CarInfo->goods_type       = $goods_type;
        $CarInfo->goods_weight     = $goods_weight;
        $CarInfo->goods_size       = $goods_size;
        $CarInfo->except_price     = $except_price;
        $CarInfo->except_length    = $except_length;
        $CarInfo->expire_time      = $expire_time;
        $CarInfo->is_longtime      = $is_longtime;
        $CarInfo->is_longtime      = $is_longtime;
        $CarInfo->demo             = $demo;
        $CarInfo->is_long          = $is_longtime;
        $CarInfo->add_time         = time();
        $CarInfo->last_update_time = time();
        $CarInfo->start_pid        = $start_pid;
        $CarInfo->start_pname      = M\AreasFull::getAreasNametoid($CarInfo->start_pid);
        $CarInfo->start_cid        = $start_cid;
        $CarInfo->start_cname      =M\AreasFull::getAreasNametoid($CarInfo->start_cid);
        $CarInfo->start_did        = $start_did;
        $CarInfo->start_dname      = M\AreasFull::getAreasNametoid($CarInfo->start_did);
        $CarInfo->status           = 1 ;
        $CarInfo->end_pid          = $end_pid;
        $CarInfo->end_pname        = M\AreasFull::getAreasNametoid($CarInfo->end_pid);
        $CarInfo->end_cid          = $end_cid;
        $CarInfo->end_cname        = M\AreasFull::getAreasNametoid($CarInfo->end_cid);
        $CarInfo->end_did          = $end_did;
        $CarInfo->end_dname        = M\AreasFull::getAreasNametoid($CarInfo->end_did);
        $CarInfo->settle_type      = $settle_type;
        if(!$CarInfo->create()) {
            print_R($CarInfo->getMessages());exit;
        }
        $cargoinfo=M\CargoInfo::findFirstBygoods_id($CarInfo->goods_id);
        $cargoinfo->goods_no = 100000+$cargoinfo->goods_id;
        if(!$cargoinfo->update()) {
            print_R($CarInfo->getMessages());exit;
        }
        Func::adminlog("新增货源：{$CarInfo->goods_id}",$this->session->adminuser['id']);
        $this->response->redirect("cargo/index")->sendHeaders();

    }
    /**
     * 修改车源信息
     * @param  integer $cid [description]
     * @return [type]       [description]
     */
    public function editAction ( $cid=0) {

        if(!$cid)  {
            $this->response->redirect("car/index")->sendHeaders();
        }
        $cond[] = " goods_id = '{$cid}'";
        $data = M\CargoInfo::findFirst($cond);

        $start_areas = "'{$data->start_pname}','{$data->start_cname}','{$data->start_dname}'";  
        $end_areas = "'{$data->end_pname}','{$data->end_cname}','{$data->end_dname}'"; 
        $this->view->start_areas = $start_areas;
        $this->view->end_areas = $end_areas;
        $this->view->_BOX_TYPE  =M\CarInfo::getBoxType();
        $this->view->_BODY_TYPE  =M\CarInfo::getBoDYType();
        $this->view->_GOODS_TYPE  =M\CargoInfo::getGoodsType();
        $this->view->data = $data;
        $this->view->ext = $carExt ;
    }
    /**
     *  保存车源
     * @return [type] [description]
     */
    public function saveAction () {


        if(!$this->request->getPost()) {
             return $this->dispatcher->forward(array(
                    "controller" => "car",
                    "action" => "index"
                ));
        }

        $goods_id      = $this->request->getPost("goods_id",'int',0);
        $end_pid       = $this->request->getPost('end_pid','int', 0);
        $end_cid       = $this->request->getPost('end_cid','int', 0);
        $end_did       = $this->request->getPost('end_did','int', 0);
        $settle_type   = $this->request->getPost('settle_type','string','');
        $start_pid     = $this->request->getPost('start_pid','int', 0);
        $start_cid     = $this->request->getPost('start_cid','int', 0);
        $start_did     = $this->request->getPost('start_did','int', 0);
        
        $box_type      = $this->request->getPost('box_type','int', 0);
        $body_type     = $this->request->getPost('body_type','int', 0);
        $contact_man   = $this->request->getPost('contact_man','string', '');
        $contact_phone = $this->request->getPost('contact_phone','string', '');
        $goods_name    = $this->request->getPost('goods_name','string', '');
        $goods_type    = $this->request->getPost('goods_type','string', '');
        $goods_weight  = $this->request->getPost('goods_weight','string', '');
        $goods_size    = $this->request->getPost('goods_size','string', '');
        $except_price  = $this->request->getPost('except_price','string', '');
        $except_length = $this->request->getPost('except_length','string', '');
        $expire_time   = $this->request->getPost('expire_time','string', '');
        $demo          = $this->request->getPost('demo','string','');
        $is_long   = $this->request->getPost('is_longtime','int',0);
        if(!$goods_id) {
            return $this->dispatcher->forward(array(
                    "controller" => "cargo",
                    "action" => "index"
            ));
        }
        $cond[] = " goods_id = '{$goods_id}'";
        $data = M\CargoInfo::findFirst($cond);

        if(!$data) {
            return $this->dispatcher->forward(array(
                    "controller" => "car",
                    "action" => "cargo"
            ));
        }

        if($data->contact_phone!=$contact_phone){
            $moblie                    =M\CargoInfo::GetMoblie($contact_phone);
            $data->phone_number     = substr_replace($contact_phone,'****',3,4);
            $data->phone_img        =  $moblie;
        }
        $expire_time=strtotime($expire_time);
        $data->phone_number = substr_replace($contact_phone,'****',3,4);
        $data->box_type =  $box_type;
        $data->body_type =$body_type;
        $data->contact_phone =$contact_phone;
        $data->contact_man = $contact_man;
        $data->goods_name = $goods_name;
        $data->goods_type = $goods_type;
        $data->goods_weight = $goods_weight;
        $data->goods_size = $goods_size;
        $data->except_price = $except_price;
        $data->except_length = $except_length;
        $data->expire_time = $expire_time;
        //$data->is_longtime = $is_longtime;
        $data->demo = $demo;
        $data->settle_type = $settle_type;
        $data->add_time = time();
        $data->last_update_time = time();
        $data->is_long = $is_long;
        $data->start_pid = $start_pid;
        $data->start_pname = M\AreasFull::getAreasNametoid($data->start_pid);
        $data->start_cid = $start_cid;
        $data->start_cname =M\AreasFull::getAreasNametoid($data->start_cid);
        $data->start_did = $start_did;
        $data->start_dname = M\AreasFull::getAreasNametoid($data->start_did);
        $data->status  = 1 ;
        $data->end_pid = $end_pid;
        $data->end_pname = M\AreasFull::getAreasNametoid($data->end_pid);
        $data->end_cid = $end_cid;
        $data->end_cname = M\AreasFull::getAreasNametoid($data->end_cid);
        $data->end_did = $end_did;
        $data->end_dname = M\AreasFull::getAreasNametoid($data->end_did);

        if(!$data->save())
        {
            print_R($CarInfo->getMessages());exit;
        }

        Func::adminlog("修改货源：{$data->goods_id}",$this->session->adminuser['id']);
        $this->response->redirect("cargo/index")->sendHeaders();
    }
    /**
     * 删除车源
     * @param  integer $cid 车源id
     * @return 
     */
    public function deleteAction ($cid=0) {
        
        if(!$cid) {
            return $this->dispatcher->forward(array(
                    "controller" => "car",
                    "action" => "index"
            ));
        }
        $this->db->begin();

        try {
            $cond[] = " goods_id = '{$cid}'";
            $data = M\CargoInfo::findFirst($cond);

            if(!$data) {
               throw new \Exception( 1);
            }

            if(!$data->delete()) {
                throw new \Exception(3);
            }
            $this->db->commit();
        } catch (\Exception $e) {
            $this->db->rollback();
        }
       
        Func::adminlog("修改货源：{$data->contact_man}",$this->session->adminuser['id']);
        $this->response->redirect("cargo/index")->sendHeaders();

    }
    /**
     * 查看详细
     * @param  integer $cid 车源id
     * @return [type]       [description]
     */
    public function getAction ($cid=0) {
        if(!$cid) {
            $this->response->redirect("cargo/index")->sendHeaders();
        }
        //查询车源基本信息
        $data = M\CargoInfo::getCargoInfo($cid);
        $this->view->_BOX_TYPE  =M\CarInfo::getBoxType();
        $this->view->_BODY_TYPE  =M\CarInfo::getBoDYType();
        $this->view->_TRANSPORT_TYPE = M\CargoInfo::getGoodsType();

        $this->view->cargoinfo = $data;
    }

    public function checkimgcodeAction() {

        $code = $this->request->getPost('img_yz', 'string', '');
        $villagecode = $this->session->villagecode;
        if (strtolower($villagecode) == strtolower($code) ) {
            $msg['ok'] = '';
        }
        else
        {
            $msg['error'] = '验证码错误!';
        }
        echo json_encode($msg);
        exit;
    }

    /**
     * 批量删除
     * @return [type] [description]
     */
    public function removeAllAction () {
        if(!$this->request->getPost()) {
            return $this->dispatcher->forward(array(
                    "controller" => "cargo",
                    "action" => "index"
                ));
        }
        $id = $this->request->getPost('remove');
        if(!$id) {

        }
        $_id = join(',', $id);
       
        $sql = " UPDATE cargo_info SET status = 0 where goods_id in ({$_id}) ";
        $this->db->execute($sql);
        $this->response->redirect("cargo/index")->sendHeaders();
    }

}
