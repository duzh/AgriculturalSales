<?php
namespace Mdg\Manage\Controllers;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Mdg\Models as M;
use Mdg\Models\ConsultCategory as Category;
use Lib\Pages as Pages;
use Lib\Func as Func;
use Mdg\Models\Codes as Codes;
/**
 * 车源管理
 */

class CarController extends ControllerBase
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
        
        $truck_type_id  = $this->request->get('truck_type_id', 'string', 'all');
        $body_type      = $this->request->get('body_type', 'string', 'all');
        $is_longtime    = $this->request->get('is_longtime', 'string', 'all');
        $transport_type = $this->request->get('transport_type', 'string', 'all');

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
        if($truck_type_id != 'all') {
            $cond[] = " ext.box_type = '{$truck_type_id}'";
        }   
        if($body_type != 'all') {
            $cond[] = " ext.body_type = '{$body_type}'";
        }
        if($is_longtime != 'all') {
            $cond[] = " ext.is_longtime = '{$is_longtime}'";
        }
        if($transport_type != 'all') {
            $cond[] = " ext.transport_type = '{$transport_type}'";
        }

        $cond= implode( ' AND ', $cond);

        $data = M\CarInfo::getCarInfoList($cond, $p, $this->db);

        $this->view->start_areas = join("','" ,$start_areas);
        $this->view->end_areas = join("','" ,$end_areas);
        $this->view->_BOX_TYPE  =M\CarInfo::getBoxType();
        $this->view->_BODY_TYPE  =M\CarInfo::getBoDYType();
        $this->view->_TRANSPORT_TYPE = M\CarInfo::getTransportType();
        $this->view->data = $data;
    }
    /**
     *  新增车源
     * @return [type] [description]
     */
    public function newAction () {
        
        $this->view->_BOX_TYPE  =M\CarInfo::getBoxType();
        $this->view->_BODY_TYPE  =M\CarInfo::getBoDYType();
        $this->view->_TRANSPORT_TYPE = M\CarInfo::getTransportType();
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
        $CarInfo = new M\CarInfo();
        $CarExt = new M\CarExt();

        $end_pid        = intval($this->request->getPost('end_pid','int', 0));
        $end_cid        = intval($this->request->getPost('end_cid','int', 0));
        $end_did        = intval($this->request->getPost('end_did','int', 0));
        
        $truck_type_id  = $this->request->getPost('truck_type_id','int', 0);
        $body_type      = $this->request->getPost('body_type','int', 0);
        $start_pid      = intval($this->request->getPost('start_pid','int', 0));
        $start_cid      = intval($this->request->getPost('start_cid','int', 0));

        $contact_man    = $this->request->getPost('contact_man','string', '');
        $contact_phone  = $this->request->getPost('contact_phone','string', '');
        $car_no         = $this->request->getPost('car_no','string', '');
        $length         = $this->request->getPost('length','string', '');
        $carry_weight   = $this->request->getPost('carry_weight','string', '');
        $use_time       = $this->request->getPost('use_time','string', '');
        $endtime        = $this->request->getPost('depart_time','string', '');
        $transport_type = $this->request->getPost('transport_type','string', '');
        $start_did      = intval($this->request->getPost('start_did','int', 0));
        $light_goods    = $this->request->getPost('light_goods','string', '');
        $heavy_goods    = $this->request->getPost('heavy_goods','string', '');
        $content        = $this->request->getPost('content','string', '');
        $is_longtime        = $this->request->getPost('is_longtime','string', 0);

        //M\AreasFull::getAreasNametoid($start_pid);
        
        
        $CarInfo->contact_man =$contact_man;
        $CarInfo->contact_phone =$contact_phone;
        $CarInfo->car_licence =$car_no;
        $CarInfo->light_goods = $light_goods;
        $CarInfo->heavy_goods = $heavy_goods;
        $CarInfo->start_pid = $start_pid;
        $CarInfo->start_pname = M\AreasFull::getAreasNametoid($CarInfo->start_pid);
        $CarInfo->start_cid = $start_cid;
        $CarInfo->start_cname =M\AreasFull::getAreasNametoid($CarInfo->start_cid);
        $CarInfo->start_did = $start_did;
        $CarInfo->start_dname = M\AreasFull::getAreasNametoid($CarInfo->start_did);
        $CarInfo->status  = 1 ;
        $CarInfo->add_time  = CURTIME;
        $CarInfo->end_pid = $end_pid;
        $CarInfo->end_pname = M\AreasFull::getAreasNametoid($CarInfo->end_pid);
        $CarInfo->end_cid = $end_cid;
        $CarInfo->end_cname = M\AreasFull::getAreasNametoid($CarInfo->end_cid);
        $CarInfo->end_did = $end_did;
        $CarInfo->end_dname = M\AreasFull::getAreasNametoid($CarInfo->end_did);
        $CarInfo->phone_number = substr($CarInfo->contact_phone,0,3)."****".substr($CarInfo->contact_phone,7,4);
        $CarInfo->phone_img = Codes::getmoblie($CarInfo->contact_phone);
        
        if(!$CarInfo->create()) {
            print_R($CarInfo->getMessages());
        }
        $CarInfo->car_no =  sprintf('C%08u',  $CarInfo->car_id);
        $CarInfo->save();
        
        $car_id = $CarInfo->car_id;
        $CarExt->car_id           = $car_id;
        $CarExt->box_type         = $truck_type_id;
        $CarExt->body_type        = $body_type;
        $CarExt->length           = $length;
        $CarExt->carry_weight     = $carry_weight;
        $CarExt->use_time         = $use_time;
        $CarExt->depart_time      = strtotime($endtime);
        $CarExt->is_longtime      = $is_longtime;
        $CarExt->transport_type   = $transport_type;
        $CarExt->demo             = $content;
        $CarExt->add_time         = CURTIME;
        if(!$CarExt->create()) {
            print_R($CarExt->getMessages());
        }
        Func::adminlog("新增车源：{$CarInfo->car_id}",$this->session->adminuser['id']);
        $this->response->redirect("car/index")->sendHeaders();

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
        $cond[] = " car_id = '{$cid}'";
        $data = M\CarInfo::findFirst($cond);
        $carExt = M\CarExt::selectByCar_id($data->car_id);

        $start_areas = "'{$data->start_pname}','{$data->start_cname}','{$data->start_dname}'";  
        $end_areas = "'{$data->end_pname}','{$data->end_cname}','{$data->end_dname}'"; 
        $this->view->start_areas = $start_areas;
        $this->view->end_areas = $end_areas;
        $this->view->_BOX_TYPE  =M\CarInfo::getBoxType();
        $this->view->_BODY_TYPE  =M\CarInfo::getBoDYType();
        $this->view->_TRANSPORT_TYPE = M\CarInfo::getTransportType();

        $this->view->data = $data;
        $this->view->ext = $carExt ;
    }
    /**
     *  修改车源保存
     * @return [type] [description]
     */
    public function saveAction () {

        if(!$this->request->getPost()) {
             return $this->dispatcher->forward(array(
                    "controller" => "car",
                    "action" => "index"
                ));
        }
        $contact_man    = $this->request->getPost('contact_man', 'string', '');
        $contact_phone  = $this->request->getPost('contact_phone', 'string', '');
        $car_licence         = $this->request->getPost('car_licence', 'string', '');
        
        $length         = $this->request->getPost('length', 'string', '');
        $carry_weight   = $this->request->getPost('carry_weight', 'string', '');
        $use_time       = $this->request->getPost('use_time', 'string', '');
        $depart_time    = $this->request->getPost('depart_time', 'string', '');
        $transport_type = $this->request->getPost('transport_type', 'string', '');
        $is_longtime    = $this->request->getPost('is_longtime', 'string', '');
        $light_goods    = $this->request->getPost('light_goods', 'string', '');
        $heavy_goods    = $this->request->getPost('heavy_goods', 'string', '');
        $demo           = $this->request->getPost('demo', 'string', '');

        $start_pid      = $this->request->getPost('start_pid', 'int', 0);
        $start_cid      = $this->request->getPost('start_cid', 'int', 0);
        $start_did      = $this->request->getPost('start_did', 'int', 0);
        $truck_type_id  = $this->request->getPost('truck_type_id', 'int', 0);
        $body_type      = $this->request->getPost('body_type', 'int', 0);
        $end_pid        = $this->request->getPost('end_pid', 'int', 0);
        $end_cid        = $this->request->getPost('end_cid', 'int', 0);
        $end_did        = $this->request->getPost('end_did', 'int', 0);
        $car_id         = $this->request->getPost('car_id', 'int', 0);
        if(!$car_id) {
            return $this->dispatcher->forward(array(
                    "controller" => "car",
                    "action" => "index"
            ));
        }

        $cond[] = " car_id = '{$car_id}'";
        $data = M\CarInfo::findFirst($cond);
        $ext = M\CarExt::findFirst($cond);

        if(!$data) {
            return $this->dispatcher->forward(array(
                    "controller" => "car",
                    "action" => "index"
            ));
        }
        $data->car_licence =  $car_licence;
        $data->contact_man =$contact_man;
        $data->contact_phone =$contact_phone;
       
        $data->light_goods = $light_goods;
        $data->heavy_goods = $heavy_goods;
        $data->start_pid = $start_pid;
        $data->start_pname = M\AreasFull::getAreasNametoid($data->start_pid);
        $data->start_cid = $start_cid;
        $data->start_cname =M\AreasFull::getAreasNametoid($data->start_cid);
        $data->start_did = $start_did;
        $data->start_dname = M\AreasFull::getAreasNametoid($data->start_did);
        $data->status  = 1 ;
        $data->add_time  = CURTIME;
        $data->end_pid = $end_pid;
        $data->end_pname = M\AreasFull::getAreasNametoid($data->end_pid);
        $data->end_cid = $end_cid;
        $data->end_cname = M\AreasFull::getAreasNametoid($data->end_cid);
        $data->end_did = $end_did;
        $data->end_dname = M\AreasFull::getAreasNametoid($data->end_did);
        $data->phone_img = Codes::getmoblie($data->contact_phone);

        if(!$data->save())
        {

        }

        $ext->car_id           = $car_id;
        $ext->box_type         = $truck_type_id;
        $ext->body_type        = $body_type;
        $ext->length           = $length;
        $ext->carry_weight     = $carry_weight;
        $ext->use_time         = $use_time;
        $ext->depart_time      = strtotime($depart_time);
        $ext->is_longtime      = $is_longtime;
        $ext->transport_type   = $transport_type;
        $ext->demo             = $demo;
        $ext->add_time         = CURTIME;
        if(!$ext->save()) {

        }
        Func::adminlog("修改车源：{$car_id}",$this->session->adminuser['id']);
        $this->response->redirect("car/index")->sendHeaders();
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
        ;
        $this->db->begin();

        try {
            $cond[] = " car_id = '{$cid}'";
            $data = M\CarInfo::findFirst($cond);
            $ext = M\CarExt::findFirst($cond);

            if(!$data) {
               throw new \Exception( 1);
            }

            if($ext) {
                if(!$ext->delete()) {
                    throw new \Exception(2);
                }
            }

            if(!$data->delete()) {
                throw new \Exception(3);
            }
            $this->db->commit();
        } catch (\Exception $e) {
            $this->db->rollback();
        }
       
        Func::adminlog("删除车源：{$data->contact_man}",$this->session->adminuser['id']);
        return $this->dispatcher->forward(array(
            "controller" => "car",
            "action" => "index"
        ));

    }
    /**
     * 查看详细
     * @param  integer $cid 车源id
     * @return [type]       [description]
     */
    public function getAction ($cid=0) {
        
        if(!$cid) {
            $this->response->redirect("car/index")->sendHeaders();
        }
        //查询车源基本信息
        $data = M\CarInfo::getCarInfo($cid);
        
        $this->view->_BOX_TYPE  =M\CarInfo::getBoxType();
        $this->view->_BODY_TYPE  =M\CarInfo::getBoDYType();
        $this->view->_TRANSPORT_TYPE = M\CarInfo::getTransportType();
        $this->view->data = $data;
    }
    /**
     * 检测图形验证码
     * @return [type] [description]
     */
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
                    "controller" => "car",
                    "action" => "index"
                ));
        }
        $id = $this->request->getPost('remove');
        if(!$id) {
                $this->response->redirect("car/index")->sendHeaders();
        }
        $_id = join(',', $id);

        $sql = " UPDATE car_info SET status = 0 where car_id in ({$_id}) ";
         
        $this->db->execute($sql);
       
        $this->response->redirect("car/index")->sendHeaders();
    }

}
