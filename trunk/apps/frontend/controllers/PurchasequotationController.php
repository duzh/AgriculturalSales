<?php
namespace Mdg\Frontend\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Mdg\Models\PurchaseQuotation as PurchaseQuotation;
use Mdg\Models\Purchase as Purchase;
use Mdg\Models\PurchaseContent as PurchaseContent;
use Lib\Areas as lAreas;
use Mdg\Models as M;
use Lib\Pages as Pages;
use Lib as L;
class PurchaseQuotationController extends ControllerBase
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
                $this->session->category1=$_GET['category'];
            }
            if(!empty($_GET['address'])){
                $arr["area_id"]=$_GET['address'];
                $this->session->areas1=$_GET['address'];
            }
            if(!empty($_GET['sellname'])){
                $arr["sellname"]=$_GET['sellname'];

                //$this->session->sellname=$_REQUEST['sellname'];
            }
            if(!empty($_GET['all'])){
                if($_GET["all"]=="-1"){
                    $this->session->category1="";
                }else{
                    $this->session->areas1="";
                }

            }
            if($this->session->category){
                $arr["category"]=$this->session->category;
            }
            if($this->session->areas){
                $arr["area_id"]=$this->session->areas;
            }

            $where = PurchaseQuotation::conditions($arr);

            $query = Criteria::fromInput($this->di, "Mdg\Models\Purchase", $_REQUEST)->where($where);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = " updatetime desc ";

        $sell = M\Purchase::find($parameters);

        $paginator = new Paginator(array(
            "data" => $sell,
            "limit"=> 10,
            "page" => $page
        ));


        $data = $paginator->getPaginate();

        $pages = new Pages($data);

        $pages = $pages->show(1);

        $this->view->data = $data;
        $this->view->pages = $pages;
        $cat_list=M\Category::find("parent_id=0 ");
        $this->view->cat_list=$cat_list;
        $areas_list=M\Areas::find("level=1 and is_show=1 ");
        $this->view->areas_list=$areas_list;
        $order=M\Orders::find("purid!='0' order by  updatetime desc limit 0,10");
        $this->view->order=$order;

    }

    /**
     * Searches for quotation
     */
    public function searchAction()
    {

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "Mdg\Models\PurchaseQuotation", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id";

        $quotation = PurchaseQuotation::find($parameters);
        if (count($quotation) == 0) {
            $this->flash->notice("The search did not find any quotation");

            return $this->dispatcher->forward(array(
                "controller" => "purchasequotation",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $quotation,
            "limit"=> 10,
            "page" => $numberPage
        ));

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displayes the creation form
     */
    public function newAction()
    {
        $purid = $this->request->get('purid', 'int', 0);

        $purchase = Purchase::findFirstByid($purid);
        if (!$purchase) {
            $this->flash->error("此采购信息不存在！");

            return $this->dispatcher->forward(array(
                "controller" => "purchasequotation",
                "action" => "index"
            ));
        }

        $pcontent = PurchaseContent::findFirstBypurid($purid);

        $this->view->quo_num = PurchaseQuotation::find("purid='{$purid}'")->count();
        $this->view->purchase = $purchase;
        $this->view->pcontent = $pcontent;
        $this->view->goods_unit = Purchase::$_goods_unit;

    }

    /**
     * Edits a quotation
     *
     * @param string $id
     */
    public function editAction($id)
    {

        if (!$this->request->isPost()) {

            $quotation = PurchaseQuotation::findFirstByid($id);
            if (!$quotation) {
                $this->flash->error("quotation was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "quotation",
                    "action" => "index"
                ));
            }

            $purchase = Purchase::findFirstByid($quotation->purid);
            $pcontent = PurchaseContent::findFirstBypurid($quotation->purid);


            $this->tag->setDefault("id", $quotation->id);
            $this->tag->setDefault("purid", $quotation->purid);
            $this->tag->setDefault("price", $quotation->price);
            $this->tag->setDefault("spec", $quotation->spec);
            $this->tag->setDefault("puserid", $quotation->puserid);
            $this->tag->setDefault("purname", $quotation->purname);
            $this->tag->setDefault("suserid", $quotation->suserid);
            $this->tag->setDefault("sellname", $quotation->sellname);
            $this->tag->setDefault("sareas", $quotation->sareas);
            $this->tag->setDefault("saddress", $quotation->saddress);
            $this->tag->setDefault("sphone", $quotation->sphone);
            $this->tag->setDefault("addtime", $quotation->addtime);

            $this->view->id = $quotation->id;
            $this->view->quotation = $quotation;
            $this->view->purchase = $purchase;
            $this->view->pcontent = $pcontent;
            $this->view->goods_unit = Purchase::$_goods_unit;
            $this->view->quo_num = PurchaseQuotation::find("purid='{$quotation->purid}'")->count();
            $this->view->curAreas = lAreas::ldData($quotation->sareas);
        }
    }

    /**
     * Creates a new quotation
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "quotation",
                "action" => "index"
            ));
        }

        $purid = $this->request->getPost('selectid', 'int', 0);

        $purchase = Purchase::findFirstByid($purid);

        if(!$purchase) {
            $this->flash->error("此采购信息不存在！");
            return $this->dispatcher->forward(array(
                "controller" => "purchasequotation",
                "action" => "index"
            ));
        }


        $quotation = new PurchaseQuotation();

        $quotation->purid = $purid;
        $quotation->price = $this->request->getPost("price", 'float', 0.00);
        $quotation->spec = L\Validator::replace_specialChar($this->request->getPost("spec", 'string', ''));
        $quotation->puserid = $purchase->uid;
        $quotation->purname = $purchase->username;
        //$quotation->suserid = $this->session->user_id;
        $quotation->suserid = 0;
        $quotation->sellname = L\Validator::replace_specialChar($this->request->getPost("sellname", 'string', ''));
        $quotation->areas = $this->request->getPost("sareas", 'int', 0);
        $quotation->areas_name=M\Areas::getFamily($this->request->getPost("sareas"));
        $quotation->saddress = L\Validator::replace_specialChar($this->request->getPost("saddress", 'string', ''));
        $quotation->sphone = L\Validator::replace_specialChar($this->request->getPost("sphone", 'string', ''));
        $quotation->addtime = time();


        if (!$quotation->save()) {
            foreach ($quotation->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "purchasequotation",
                "action" => "new"
            ));
        }
        //$sell->sell_sn = sprintf('SELL%010u', $sell->id);
        //$sell->save();
        $this->flash->success("添加报价成功");

        return $this->dispatcher->forward(array(
            "controller" => "purchasequotation",
            "action" => "index"
        ));

    }

    /**
     * Saves a quotation edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "purchasequotation",
                "action" => "index"
            ));
        }

        $id = $this->request->getPost("id");

        $quotation = PurchaseQuotation::findFirstByid($id);
        if (!$quotation) {
            $this->flash->error("purchasequotation does not exist " . $id);

            return $this->dispatcher->forward(array(
                "controller" => "purchasequotation",
                "action" => "index"
            ));
        }

        $quotation->price = $this->request->getPost("price", 'float', 0.00);
        $quotation->spec = L\Validator::replace_specialChar($this->request->getPost("spec", 'string', ''));
        $quotation->sellname = L\Validator::replace_specialChar($this->request->getPost("sellname", 'string', ''));
        $quotation->sareas = $this->request->getPost("sareas", 'int', 0);
        $quotation->saddress = L\Validator::replace_specialChar($this->request->getPost("saddress", 'string', ''));
        $quotation->sphone = L\Validator::replace_specialChar($this->request->getPost("sphone", 'string', ''));
        $quotation->addtime = time();


        if (!$quotation->save()) {

            foreach ($quotation->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "purchasequotation",
                "action" => "edit",
                "params" => array($quotation->id)
            ));
        }

        $this->flash->success("purchasequotation was updated successfully");

        return $this->dispatcher->forward(array(
            "controller" => "purchasequotation",
            "action" => "index"
        ));

    }

    /**
     * Deletes a quotation
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $quotation = PurchaseQuotation::findFirstByid($id);
        if (!$quotation) {
            $this->flash->error("purchasequotation was not found");

            return $this->dispatcher->forward(array(
                "controller" => "purchasequotation",
                "action" => "index"
            ));
        }

        if (!$quotation->delete()) {

            foreach ($quotation->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "purchasequotation",
                "action" => "search"
            ));
        }

        $this->flash->success("quotation was deleted successfully");

        return $this->dispatcher->forward(array(
            "controller" => "purchasequotation",
            "action" => "index"
        ));
    }
   
   

}
