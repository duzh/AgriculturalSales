<?php
namespace Mdg\Frontend\Controllers;
use Mdg\Models as M;
use Lib as L;

class PicturewallController extends ControllerKx
{
    public function memberindexAction(){
        $sid = $this->session->getId();
        $user_id = $this->session->farm_user_id;
        $credible_farm_picture = M\CredibleFarmPicture::find("user_id = {$user_id} and type = 3 order by picture_time")->toArray();
        
        $count = M\CredibleFarmPicture::count("user_id = {$user_id} and type = 3 order by picture_time");
        $this->view->count =$count;
        $this->view->title = '图片墙';
        $this->view->credible_farm_picture = json_encode($credible_farm_picture);
        $this->view->keywords = '农产品价格,农产品价格信息,农产品价格查询,农产品交易网,农产品价格信息网';
        $this->view->descript = '丰收汇全国农产品价格行情信息实时更新,农产品价格走势分析，农产品价格变化指数。';
    }
}


