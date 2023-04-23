<?php
namespace Mdg\Frontend\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Mdg\Models as M;
use Lib\File as File;
use \Sell as Sell;
use Lib\Func as Func;
use Lib\Pages as Pages;
class SellController extends ControllerBase
{

    /**
     * Index action
     */
    public function indexAction()
    {   
       
        $page = $this->request->get('p', 'int', 1);
        $arr=array();
       
        if ($_GET){
            if(!empty($_GET['category'])){   
            $arr["category"]=$_GET['category'];
            $this->session->category=$_GET['category']; 
            }
            if(!empty($_GET['address'])){  
            $arr["area_id"]=$_GET['address'];
            $this->session->areas=$_GET['address'];  
            }
            if(!empty($_GET['sellname'])){  
            $arr["sellname"]=$_GET['sellname'];
            }
            if(!empty($_GET['all'])){
            if($_GET["all"]=="-1"){
            $this->session->category="";
            }
            if($_GET["all"]=="-2"){
            $this->session->areas="";
            }   
            }
            if($this->session->category){
            $arr["category"]=$this->session->category;
            }
            if($this->session->areas){
            $arr["area_id"]=$this->session->areas;
            }
            
            $where = M\Sell::conditions($arr);
            
            $query = Criteria::fromInput($this->di, "Mdg\Models\Sell", $_REQUEST)->where($where);
            $this->persistent->parameters = $query->getParams();
        }else{    
                $numberPage = $this->request->getQuery("page", "int");
        }
        
        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = " updatetime desc ";

        $sell = M\Sell::find($parameters);

        $paginator = new Paginator(array(
            "data" => $sell,
            "limit"=> 10,
            "page" => $page
        ));

     
        $data = $paginator->getPaginate();

        $pages = new Pages($data);
        $pages = $pages->show(1);
        $cat_list   = M\Category::find("parent_id=0 ");
        $areas_list = M\Areas::find("level=1 and is_show=1 ");

        $this->view->data       = $data;
        $this->view->pages      = $pages;
        $this->view->cat_list   = $cat_list;
        $this->view->areas_list = $areas_list;
        $this->view->_nav       = 'sell';
        $this->view->sellname = $_GET['sellname'];

        $this->view->title = '供应大厅-丰收汇';
        $this->view->keywords = '供应大厅-丰收汇';
        $this->view->descript = '供应大厅-丰收汇';
    }
   
    /**
     * Displayes the creation form
     */
    public function newAction()
    {
        $category = M\Category::find()->toArray();
        $cat_list = M\Category::totree($category, 'id', 'parent_id', 'child');
        $this->view->cat_list=$cat_list;     
    }

    /**
     * Edits a sell
     *
     * @param string $id
     */
    public function editAction($id)
    {
        
        if (!$this->request->isPost()) {

            $sell     = M\Sell::findFirstByid($id);
            if (!$sell) {
            $this->flash->error("sell was not found");
            
            return $this->dispatcher->forward(array(
            "controller" => "sell",
            "action" => "index"
            ));
            }

            $p=empty($_GET["p"])?"0":$_GET["p"]>0 ?$_GET["p"]:"0";
            $category = M\Category::tocategroy($sell->category);
            $content  = M\SellContent::findFirstBysid($id);
            $sup      = M\Sell::find("uid=".$sell->uid." limit 0,10 ");
            $UsersExt = M\UsersExt::findFirstByuid($sell->uid);
            $img      = M\SellImages::findBysellid($id);
            $imgs     =$img->toArray();
            $this->view->img      = $img;
            $this->view->imgs     = $imgs[$p];
            $this->view->p        = $p;
            $this->view->sellid   = $id; 
            $this->view->sell     = $sell;
            $this->view->UsersExt = $UsersExt;
            $this->view->sup      = $sup;
            $this->view->content  = $content;
            $this->view->category = $category;
            $this->view->_nav     = 'sell';

            $this->view->title = $sell->title.'-供应详情-丰收汇';
            $this->view->keywords = $sell->title.'-供应详情-丰收汇';
            $this->view->descript = $sell->title.'-供应详情-丰收汇';
            
        }
    }

    /**
     * Creates a new sell
     */
    public function createAction()
    {
        
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
            "controller" => "sell",
            "action" => "index"
            ));
        }
      
        $sell = new M\Sell();
        $family=M\Areas::getFamily($this->request->getPost("town"));

        $sell->title                        = $this->request->getPost("title");
        $sell->category                     = $this->request->getPost("categorys");
        $sell->thumb                        = $this->request->getPost("thumb");
        $sell->min_price                    = $this->request->getPost("min_price");
        $sell->max_price                    = $this->request->getPost("max_price");
        $sell->price_unit                   = $this->request->getPost("price_unit");
        $sell->quantity                     = $this->request->getPost("quantity");
        $sell->goods_unit                   = $this->request->getPost("goods_unit");
        $sell->areas                        = $this->request->getPost("town");
        $sell->areas_name                   = $family;
        if($this->request->getPost("areas") !="请输入详细的供货地，具体到镇/乡、村、街道、门牌号"){
        $sell->address                      = $this->request->getPost("areas");  
        }
        $sell->stime                        = $this->request->getPost("stime");
        $sell->etime                        = $this->request->getPost("etime");
        $sell->breed                        = $this->request->getPost("breed");
        $sell->spec                         = $this->request->getPost("spec");
        $sell->state                        = "0";
        $sell->uid                          = "1";
        $sell->username                     = "当前登录用户的姓名";
        $sell->uname                        = "当前登录用户的姓名";
        $sell->createtime                   = time();
        $sell->updatetime                   = time();
        
        if (!$sell->save()) {
            foreach ($sell->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "sell",
                "action"     => "new"
            ));
        }   

        $sell->sell_sn = sprintf('SELL%010u', $sell->id);
        $sell->save();

        $sellcontent = new M\SellContent();
        $sellcontent->sid     =$sell->id;
        $sellcontent->content =$this->request->getPost("content");
        $sellcontent->save();

        return $this->dispatcher->forward(array(
            "controller" => "sell",
            "action" => "index"
        ));

    }

    /**
     * Saves a sell edited
     *
     */
    public function saveAction()
    {
       
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "sell",
                "action" => "index"
            ));
        }
       
        $order=new M\Orders();
        $sell=M\Sell::findFirstByid($this->request->getPost("sell_id"));    
        
        $order->purid      = "0";
        $order->sellid     = $this->request->getPost("sellid");
        $order->puserid    = "1";
        $order->purname    = $this->request->getPost("username",'string','');
        $order->purphone   = $this->request->getPost("moblie",'string', '');
        $order->suserid    = $sell->uid;
        $order->sname      = $sell->uname;
        $order->sphone     = $sell->username;
        $order->areas      = $this->request->getPost("town");
        $order->areas_name = Func::getCols(M\Areas::getFamily($this->request->getPost("town")), 'name', ',');
        $order->address    = $this->request->getPost("areas",'string', '');
        $order->goods_name = $sell->title;
        $order->price      = $this->request->getPost("price");
        $order->quantity   = $this->request->getPost("number",'float', 0.00);
        $order->goods_unit = $sell->goods_unit;
        $order->total      = $this->request->getPost("number")*$this->request->getPost("price");
      
        $order->addtime    =time();
        $order->state      ="0";
        $order->updatetime =time();
        if (!$order->save()) {
          $order =M\Orders::findFirstByid($order->id);
          $order->delete();
          echo "no";die;
        }else{
         $order->order_sn     = sprintf('mdg%09u', $order->id);
         $order->save();
         $this->view->ajax    = 1;
         $this->view->orderid = $order->id;
        }
        
        

    }

    /**
     * Deletes a sell
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $sell = M\Sell::findFirstByid($id);
        if (!$sell) {
        $this->flash->error("sell was not found");
        
        return $this->dispatcher->forward(array(
        "controller" => "sell",
        "action" => "index"
        ));
        }
        
        if (!$sell->delete()) {
        
        foreach ($sell->getMessages() as $message) {
        $this->flash->error($message);
        }
        
        return $this->dispatcher->forward(array(
        "controller" => "sell",
        "action" => "search"
        ));
        }
        
        $this->flash->success("sell was deleted successfully");
        
        return $this->dispatcher->forward(array(
        "controller" => "sell",
        "action" => "index"
        ));
    }
    public function AjaxAction(){
    
        $pid  =$_REQUEST["pid"];
        $type =$_REQUEST["type"];
        $list =M\Areas::find("parent_id='$pid' and level='$type' ")->toArray();
        echo json_encode($list);die;
    
    }
    public function showuserAction(){

        $this->view->ajax = 1;
        $user=M\Users::find(" username like '%".$_REQUEST["str"]."%' ");
        $this->view->user=$user->toArray();
    }
  
    public function showaddressAction(){
          $this->view->ajax = 1;
          $address=M\Areas::findByparent_id($_POST["id"]);
          $this->view->address=$address ? $arrress :'null';
    }
    public function checknameAction(){
         $name=$_GET["title"];
         $checkname=count(M\Sell::findBytitle($name));

         if($checkname>0){
            echo "no";die;
         }else{
            echo "yes";die;
         }
    }
    public function showcategorysAction(){
        $this->view->ajax = 1;
        $category=M\Category::findByparent_id($_POST["id"]);
        $this->view->category=$category ? $category :'null' ;  
    }
 
}