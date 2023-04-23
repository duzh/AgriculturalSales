<?php
namespace Mdg\Manage\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Mdg\Models as M;
use Mdg\Models\Ad as Ad;

use Lib\File as File;
use Lib\Func as Func;
use Lib\Areas as lAreas;
use Mdg\Models\Purchase as Purchase;
use Mdg\Models\TmpFile as TmpFile;

class AdController extends ControllerMember
{

    /**
     * 首页广告列表
     */
    public function indexAction()
    {
       
        $page = $this->request->getQuery("page", "int", 1);
        $ad = Ad::find('position=1 and type=0 order by id asc');
       
        $paginator = new Paginator(array(
            "data" => $ad,
            "limit"=> 1000,
            "page" => $page
        ));
        $this->view->page = $paginator->getPaginate();
    }

    /**
     * 直营图片广告列表
     */
    public function imageAction()
    {
        $page = $this->request->getQuery("page", "int", 1);
        $ad = Ad::find('type=1 order by position asc');
       
        $paginator = new Paginator(array(
            "data" => $ad,
            "limit"=> 1000,
            "page" => $page
        ));
        $this->view->page = $paginator->getPaginate();       

    }

    /**
     * 直营轮播列表
     */
    public function carouselAction()
    {
        $page = $this->request->getQuery("page", "int", 1);
        $ad = Ad::find('type=2 order by position asc');
       
        $paginator = new Paginator(array(
            "data" => $ad,
            "limit"=> 1000,
            "page" => $page
        ));
        $this->view->page = $paginator->getPaginate();       
      

    }
    /**
     * 直营文字列表
     */
    public function fontAction()
    {
        $page = $this->request->getQuery("page", "int", 1);
        $ad = Ad::find('type=3 order by position asc');
       
        $paginator = new Paginator(array(
            "data" => $ad,
            "limit"=> 1000,
            "page" => $page
        ));
        $this->view->page = $paginator->getPaginate();       
      

    }
    /** 广告修改 **/
    public function editAction($id)
    {

        $ad  = Ad::findFirstByid($id);
        $sid = md5(session_id());  
        $adsrc = str_replace("http://", '', $ad->adsrc);     
        $this->view->sid  = $sid;
        $this->view->adsrc  = $ad->adsrc;
        $this->view->ad   = $ad;
        $this->view->type = $ad->type;
        $this->view->isshwow =$ad->is_show;
    }

    /**
     * 广告修改保存
     *
     */
    public function saveAction()
    {
        
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "ad",
                "action" => "index"
            ));
        }
        $type= $this->request->getPost('type','string','');
        $id  = $this->request->getPost("id",'int',0);
        $sid = $this->request->getPost("sid",'string','');
        $isshwow = $this->request->getPost("isshwow",'int',1);
        $Ad = Ad::findFirstByid($id);
        switch ($type) {
            case '0': $action = 'index';break;
            case '1': $action = 'image';break;           
            case '2': $action = 'carousel';break;
            case '3': $action = 'font';break;            
        }     
        if (!$Ad) {
            parent::msg('信息不存在','/manage/ad/'.$action);
        }

        $src=$this->request->getPost("adsrc", 'string', 0);
        $src=$src ? str_replace("http://",'',$src) : ''; 
        $Ad->adtitle = $this->request->getPost("adtitle", 'string', '');
        $Ad->adsrc   = $src ? "http://".$src : "#";
        $Ad->is_show = $isshwow;
        $Ad->addtime = time();
        if (!$Ad->save()) {

            foreach ($purchase->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "ad",
                "action" => "edit",
                "params" => array($Ad->id)
            ));
        }
        Func::adminlog("修改广告：{$Ad->adtitle}",$this->session->adminuser['id']);
        Ad::copyImages($id,$sid);
        return $this->dispatcher->forward(array(
            "controller" => "ad",
            "action" => $action
        )); 

       



    }

}
