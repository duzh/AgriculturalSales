<?php

namespace Mdg\Store\Controllers;


use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Mdg\Models\Sell as Sell;
use Mdg\Models\Areas as Areas;
use Mdg\Models\Category as Category;
use Mdg\Models\Purchase as Purchase;
use Mdg\Models\Users as Users;
use Mdg\Models\SellImages as SellImages;
use Mdg\Models as M;
use Lib\File as File,
    Lib\Func as Func;
use Lib\Pages as Pages;
use Lib\Time as Time;
use Lib\Utils as Utils;


class GoodsController extends ControllerShop
{

	/** 店铺 供应详细页 **/
    public function indexAction($id=0)
    {
        
       

            $sell     = M\Sell::findFirstByid($id);

            if (!$sell || !$sell->state) {
                $this->flash->error("此供应信息不存在！");
                return $this->dispatcher->forward(array(
                "controller" => "sell",
                "action" => "index"
                ));
            }
            
            $contents ='';
            $scontent = M\SellContent::findFirstBysid($id);
              
            if($scontent->attr!=''){
                if($id<22247){
                    $arr=str_replace("u",'\u', $scontent->attr);
                    $code = json_decode($arr,true);
                }else{
                    $code = json_decode($scontent->attr,true);

                }
                foreach ($code as $key => $value) {
                    $contents.=$value['title'].":".$value['val'].';';
                }
            }

            
            //增加产品 点击量
            Sell::clickAdd($id);

            
            $category = Category::findFirstByid($sell->category);
            if($sell->uid){
                $otherSell = Sell::find("uid='{$sell->uid}' and state = 1 and is_del=0  and id <> '{$id}' limit 0,10")->toArray();
            }else{
                $otherSell =array($sell->toArray());
            }
            
            $user = Users::findFirstByid($sell->uid);
            $imgs = SellImages::find("sellid='{$id}'")->toArray();
            if($sell->thumb){
                 $this->view->curImg =IMG_URL.$sell->thumb;
            }else{
                $this->view->curImg=array();
            }
            
            $this->view->imgs = $imgs;
            $this->view->otherSell = $otherSell;
            $this->view->user = $user;
            $this->view->family = Category::getFamily($sell->category);
            $this->view->category = $category;
            $this->view->_nav     = 'sell';
            $this->view->time_type = Sell::$type;
            $this->view->sell = $sell;
            $this->view->goods_unit = Purchase::$_goods_unit;
            $this->view->total = count($otherSell);
            $this->view->cateid=$sell->category;
            $this->view->contents=$contents;
            $this->view->title = $sell->title.'-供应详情-丰收汇';
            $this->view->keywords = $sell->title.'-供应详情-丰收汇';
            $this->view->descript = $sell->title.'-供应详情-丰收汇';
            
    }

	
}