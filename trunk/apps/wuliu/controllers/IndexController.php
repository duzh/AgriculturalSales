<?php
namespace Mdg\Wuliu\Controllers;
use Lib\Member as Member;
use Phalcon\Mvc\Controller;
use Lib\Auth as Auth;
use Lib\Arrays as Arrays;
use Mdg\Models as M;
use Mdg\Models\Category as Category;
use Lib as L;

class IndexController extends ControllerBase
{
    
        
        private $key = 'riwqe89712hjzxc8970230651mlk';
        
        public function indexAction() 
        {
            $tNavs = $this->request->get('w', 'int', 0);
            //最新车源
            $newCarList = M\CarInfo::getCarInfoList("status= 1", 1, $this->db, 10);
            //车源导航
            $CarNavs = M\CarInfo::getCarNavsList();
            //货源导航
            $CarGoNavs = M\CargoInfo::getCarNavsList();
            $CarGoCount = M\CargoInfo::getCargoCount();
            $CarGoInfolimit = M\CargoInfo::getCargoInfolimit();
            //专线信息
            $ScinfoCount = M\ScInfo::count("status = 1");
            $ScInfolimit = M\ScInfo::getScInfolimit();
            $CargoList = M\CargoInfo::getCarInfoList(1, 1, $this->db, 10);
            /* 最新物流信息 */
            $scList = M\ScInfo::getScInfoList(1, 1, $this->db, 10);
            $this->view->scList = $scList;
            $this->view->CargoList = $CargoList;
            $this->view->ScinfoCount = $ScinfoCount;
            $this->view->ScInfolimit = $ScInfolimit;
            $this->view->CarGoInfolimit = $CarGoInfolimit;
            $this->view->CarGoCount = $CarGoCount;
            $this->view->_BOX_TYPE = M\CarInfo::getBoxType();
            $this->view->_BODY_TYPE = M\CarInfo::getBoDYType();
            $this->view->newCarList = $newCarList;
            $this->view->CarNavs = $CarNavs;
            $this->view->CarGoNavs = $CarGoNavs;
            $this->view->tNavs = $tNavs;
            $this->view->title = '农产品物流信息-丰收汇 ';
            $this->view->keywords = '农产品物流信息，丰收汇';
            $this->view->descript = '丰收汇-可靠农产品交易网，为你提供车源信息、货源信息、专线信息等农产品物流信息'; 
            $this->view->start_areas = '1';
            $this->view->sorce = '厢式、高栏、平板';          
        }
        
        public function getCarInfo() 
        {
        }
    }
