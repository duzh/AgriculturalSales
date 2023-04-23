<?php
/**
 * 宣传图
 */
namespace Mdg\Member\Controllers;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Mdg\Models\Shop as Shop;
use Mdg\Models\ShopColumns as ShopColumns;
use Mdg\Models\ShopCredit as ShopCredit;
use Mdg\Models\ShopCoods as ShopCoods;
use Mdg\Models as M;
use Lib\Func as Func;
use Lib\Path as Path;
use Lib\File as File;
use Lib\Pages as Pages;
use Lib\Category as lCategory;
use Lib\Areas as lAreas;

class MapController extends ControllerMember
{
    /**
     * 宣传图 首页
     */
    
    public function indexAction() 
    {
        $image = array();
        $data = $this->checkShopExist();
        if(!$data) exit('店铺不存在');
        $sid = $data['shop_id'];
        
        $sessionid = $this->session->getId();
        $image = M\Image::findFirst(" gid = '{$sid}' AND type = 12 ");

        $this->view->sid = $sessionid;
        $this->view->data = $data; #店铺信息
        $this->view->image = $image;
    }
    
    

}
