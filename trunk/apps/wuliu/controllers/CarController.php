<?php
namespace Mdg\Wuliu\Controllers;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Mdg\Models as M;
use Mdg\Models\ConsultCategory as Category;
use Lib\Pages as Pages;
use Lib\Func as Func;
use Lib as L;
use Mdg\Models\Codes as Codes;
/**
 * 车源管理
 */

class CarController extends ControllerBase
{
    public function testAction () {
        var_dump($this->slave);
        exit;
    }
    /**
     * Index action
     */
    public function indexAction()
    {   
        

        $p = $this->request->get('p', 'int', 1 );
        if($p==0){
            $p=1;
        }
        $cond[] = " 1 ";
        $start_areas = array();
        $end_areas = array();

        $truck_type_id  = $this->request->get('truck_type_id', 'int', 0);
        $body_type      = $this->request->get('body_type', 'string', '');
        $is_longtime    = $this->request->get('is_longtime', 'string', '');
        $transport_type = $this->request->get('transport_type', 'string', '');

        $start_pid = $this->request->get('start_pid', 'int', 0);
        $start_cid = $this->request->get('start_cid', 'int', 0);
        $start_did = $this->request->get('start_did', 'int', 0);
        $end_pid   = $this->request->get('end_pid', 'int', 0);
        $end_cid   = $this->request->get('end_cid', 'int', 0);
        $end_did   = $this->request->get('end_did', 'int', 0);

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
        

        $cond= implode( ' AND ', $cond);

        $data = M\CarInfo::getCarInfoList($cond, $p, $this->db);      
        $start_areas = join("','" ,$start_areas);
        $this->view->start_areas = $start_areas;
        $this->view->end_areas = join("','" ,$end_areas);
        $this->view->_BOX_TYPE  =M\CarInfo::getBoxType();
        $this->view->_BODY_TYPE  =M\CarInfo::getBoDYType();
        $this->view->_TRANSPORT_TYPE = M\CarInfo::getTransportType();
        $this->view->carNavs  = M\CarInfo::getCarNavsList();
        $this->view->data = $data;
        $this->view->total_count = intval(ceil($data['total'] / 20));
        $this->view->title = ''.$start_areas.'车源信息-丰收汇';
        $this->view->keywords = ''.$start_areas.'车源信息，丰收汇';
        $this->view->descript = '丰收汇-可靠农产品交易网，为你提供'.$start_areas.'厢式、高栏、平板车源信息。';  
        $this->view->sorce = '厢式、高栏、平板车源'; 

    }
    /**
     * 生成手机号图片
     * @return [type] [description]
     */
    public function getimgpathAction () {
        print_R($_GET);
        $carid = $this->request->get('carid', 'int',  0 );
        $cond[] = " car_id = '{$carid}'";
        $data = M\CarInfo::findFirst($cond);
        if(!$data) {
            //上传UpYun
        }
        echo $data->phone_img;
        
    }

    public function newAction () {
      
        $this->view->day =date("d",time());
        $this->view->month=date("m-1",time());
        $this->view->year=date("Y",time());
        $this->view->title= '丰收汇-发布车源';
        $this->view->_BOX_TYPE  =M\CarInfo::getBoxType();
        $this->view->_BODY_TYPE  =M\CarInfo::getBoDYType();
        $this->view->_TRANSPORT_TYPE = M\CarInfo::getTransportType();
    }
    
    /**
     * 新增车源
     * @return [type] [description]
     */
    public function createAction () {

 

        //生成手机图片
        
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
        $truck_type_id  = intval($this->request->getPost('truck_type_id','int', 0));
        $body_type      = intval($this->request->getPost('body_type','int', 0));
        $start_pid      = intval($this->request->getPost('start_pid','int', 0));
        $start_cid      = intval($this->request->getPost('start_cid','int', 0));
        $is_longtime        = intval($this->request->getPost('is_longtime','int', 0));

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
        $demo           = $this->request->getPost('demo','string', '');

        $CarInfo->car_licence =  $car_no;
        $CarInfo->car_no =  '';
        $CarInfo->contact_man =$contact_man;
        $CarInfo->contact_phone =$contact_phone;

       
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
        $CarExt->demo             = $demo;
        
        $CarExt->add_time         = CURTIME;
        if(!$CarExt->create()) {
            print_R($CarExt->getMessages());
        }

        $this->response->redirect("car/index")->sendHeaders();

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
        if(!$data) {
            $this->response->redirect("car/index")->sendHeaders();   
        }

        $this->view->carNavs  = M\CarInfo::getCarNavsList();

        $this->view->_BOX_TYPE  =M\CarInfo::getBoxType();
        $this->view->_BODY_TYPE  =M\CarInfo::getBoDYType();
        $this->view->_TRANSPORT_TYPE = M\CarInfo::getTransportType();
        $this->view->data = $data;
        $start_name = $data['start_pname'].$data['start_cname'].$data['start_dname'];
        $end_name   = $data['end_pname'].$data['end_cname'].$data['end_dname'];
        $this->view->title = ''.$start_name.'到'.$end_name.'的车辆信息-丰收汇';
        $this->view->keywords = '';
        $this->view->descript = '丰收汇-可靠农产品交易网，为你提供'.$start_name.'到'.$end_name.'的车辆信息。'; 
        $this->view->start_areas = '1';
        $this->view->sorce = ''; 
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

}
