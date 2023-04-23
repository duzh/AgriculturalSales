<?php
namespace Mdg\Wuliu\Controllers;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Mdg\Models as M;
use Mdg\Models\ConsultCategory as Category;
use Lib\Pages as Pages;
use Lib\Func as Func;
/**
 * 货源管理
 */

class CargoController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction($pid = 0)
    {   
     
        
   
        $p = $this->request->get('p', 'int', 1 );
        if($p==0){
            $p=1;
        }
        $cond[] = " 1 ";
        $start_areas = array();
        $end_areasa = array();
        
        $start_pid      = $this->request->get('start_pid', 'string', '');
        $start_cid      = $this->request->get('start_cid', 'string', '');
        $start_did      = $this->request->get('start_did', 'string', '');
        $end_pid        = $this->request->get('end_pid', 'string', '');
        $end_cid        = $this->request->get('end_cid', 'string', '');
        $end_did        = $this->request->get('end_did', 'string', '');

        if($pid){
            $cond[] =" start_pid = '{$pid}'";
            $start_areas[] = M\AreasFull::getAreasNametoid($pid);
        }

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
        $data = M\CargoInfo::getCarInfoList($cond, $p, $this->db);
        $start_areas = join("','" ,$start_areas);
        $this->view->start_areas = $start_areas;
        $this->view->end_areas = join("','" ,$end_areas);
        $this->view->_BOX_TYPE  =M\CarInfo::getBoxType();
        $this->view->_BODY_TYPE  =M\CarInfo::getBoDYType();
        $this->view->_GOODS_TYPE  =M\CargoInfo::getGoodsType();
        $this->view->CarGoNavs = M\CargoInfo::getCarNavsList();
        $this->view->total_count = intval(ceil($data['total'] / 20));
        $this->view->data = $data;
        $this->view->title = ''.$start_areas.'货源信息-丰收汇';
        $this->view->keywords = ''.$start_areas.'货源信息，丰收汇';
        $this->view->descript = '丰收汇-可靠农产品交易网，为你提供'.$start_areas.'货源信息。'; 
        $this->view->sorce = '货源'; 
    }

      public function newAction () {
        $this->view->title = '新增货源';
        $this->view->day =date("d",time());
        $this->view->month=date("m-1",time());
        $this->view->year=date("Y",time());
        $this->view->_BOX_TYPE  =M\CarInfo::getBoxType();
        $this->view->_BODY_TYPE  =M\CarInfo::getBoDYType();
        $this->view->_GOODS_TYPE  =M\CargoInfo::getGoodsType();
    }
    
    /**
     * 新增车源
     * @return [type] [description]
     */
    public function createAction () {


        $this->view->title='车源管理';
        if(!$this->request->getPost()) {
             return $this->dispatcher->forward(array(
                    "controller" => "car",
                    "action" => "index"
                ));
        }
        $CarInfo = new M\CargoInfo();
        $contact_phone = $this->request->getPost('contact_phone','string', '');
        $end_pid       = intval($this->request->getPost('end_pid','int', 0));
        $end_cid       = intval($this->request->getPost('end_cid','int', 0));
        $end_did       = intval($this->request->getPost('end_did','int', 0));
        $start_pid     = intval($this->request->getPost('start_pid','int', 0));
        $start_cid     = intval($this->request->getPost('start_cid','int', 0));
        $start_did     = intval($this->request->getPost('start_did','int', 0));
        $box_type      = intval($this->request->getPost('box_type','int', 0));
        $body_type     = intval($this->request->getPost('body_type','int', 0));
        $is_longtime   = intval($this->request->getPost('is_longtime','int', 0));

        $contact_man   = $this->request->getPost('contact_man','string', '');
        $settle_type   = $this->request->getPost('settle_type','string','');
        $goods_name    = $this->request->getPost('goods_name','string', '');
        $goods_type    = $this->request->getPost('goods_type','string', '');
        $goods_weight  = $this->request->getPost('goods_weight','string', '');
        $goods_size    = $this->request->getPost('goods_size','string', '');
        $except_price  = $this->request->getPost('except_price','string', '');
        $except_length = $this->request->getPost('except_length','string', '');
        $expire_time   = $this->request->getPost('expire_time','string', '');
        $demo          = $this->request->getPost('demo','string','');

        $moblie                    =M\CargoInfo::GetMoblie($contact_phone);
        $expire_time               =strtotime($expire_time);
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
        $CarInfo->phone_number     = substr_replace($contact_phone,'****',3,4);
        $CarInfo->phone_img        =  $moblie;
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
            // print_R($CarInfo->getMessages());exit;
        }
        $cargoinfo=M\CargoInfo::findFirstBygoods_id($CarInfo->goods_id);
        $cargoinfo->goods_no = 100000+$cargoinfo->goods_id;
        if(!$cargoinfo->update()) {
            // print_R($CarInfo->getMessages());exit;
        }
        $this->response->redirect("cargo/index")->sendHeaders();

    }
    /**
     * 查看详细
     * @param  integer $cid 车源id
     * @return [type]       [description]
     */
    public function lookAction ($cid=0) {
        $cargoinfo=M\CargoInfo::getCargoInfo($cid);
        
        $this->view->CarGoNavs = M\CargoInfo::getCarNavsList();
        $this->view->cargoinfo =$cargoinfo;
        $start_name = M\CargoInfo::GetAreaName($cargoinfo['start_cname'],$cargoinfo['start_pname']);
        $end_name   = M\CargoInfo::GetAreaName($cargoinfo['end_cname'],$cargoinfo['end_pname']);
        $this->view->title = ''.$start_name.'到'.$end_name.'的货源信息-丰收汇';
        $this->view->keywords = '';
        $this->view->descript = '丰收汇-可靠农产品交易网，为你提供'.$start_name.'到'.$end_name.'的货源信息。'; 
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
