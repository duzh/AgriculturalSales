<?php
namespace Mdg\Store\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

use Mdg\Models\TmpFile as TmpFile;
use Mdg\Models\Shop as Shop;
use Mdg\Models\Users as Users;
use Mdg\Models\UsersExt as Usersext;
use Mdg\Models\Areas as mAreas;
use Mdg\Models\Purchase as Purchase;
use Mdg\Models\SellContent as Content;
use Mdg\Models\SellImages as SellImages;
use Mdg\Models\Category as Category;

use Lib\Func as Func;
use Lib\Path as Path;
use Lib\File as File;
use Lib\Pages as Pages;
use Lib\Category as lCategory;
use Lib\Areas as lAreas;

class ShopController extends ControllerMember
{

    /**
     * 我的供应
     */
    public function indexAction()
    {   
        
        $page = $this->request->get('p', 'int', 1);
        $user_id = $this->getUserID();

        $params = array("uid = '{$user_id}' ");
        $params['order'] = ' updatetime desc ';

        $sell = Sell::find($params);

        $paginator = new Paginator(array(
            "data" => $sell,
            "limit"=> 5,
            "page" => $page
        ));
        
        $data = $paginator->getPaginate();

        $pages = new Pages($data);
        $pages = $pages->show(1);

        $this->view->data = $data;
        $this->view->pages = $pages;
        $this->view->goods_unit = Purchase::$_goods_unit;
        $this->view->time_type = Sell::$type;

        $this->view->title = '我的供应-用户中心-丰收汇';
        $this->view->keywords = '我的供应-用户中心-丰收汇';
        $this->view->descript = '我的供应-用户中心-丰收汇';
              
    }

    /**
     * 我要卖货
     */
    public function newAction()
    {   
        $user_id = $this->getUserID();
        $userext=Usersext::findFirstByuid($user_id);
        if(!isset($userext)||$userext->name==''||$userext->areas_name==''){
            echo "<script>alert('请先完善信息');location.href='/member/perfect/index/'</script>";die;
        }
        $sid = md5(session_id());
        $goods_unit = Purchase::$_goods_unit;
        $tfile = TmpFile::find("sid='{$sid}'");
        if($tmpFile){
           $tmpFile->delete();
        }
        $this->view->goods_unit = $goods_unit;
        $this->view->time_type = Sell::$type;
        $this->view->sid = $sid;
        $this->view->cur_unit = array_shift($goods_unit);
        $this->view->title = '我的供应-发布供应-丰收汇';
    }

    /**
     * 修改供应信息
     */
    public function editAction()
    {
        $sellid = $this->request->get('sellid', 'int', 0);
        $user_id = $this->getUserID();

        $sell = Sell::findFirst("id='{$sellid}' and is_del=0");
        if (!$sell) {
            $this->flash->error("此供应信息不存在！");
            return $this->dispatcher->forward(array(
                "controller" => "sell",
                "action" => "index"
            ));
        }

        if($this->getUserID() != $sell->uid) {
            $this->flash->error("你无权修改此供应信息！");
            return $this->dispatcher->forward(array(
                "controller" => "sell",
                "action" => "index"
            ));
        }

        $sid = md5(session_id());
        // 删除旧session图片
        TmpFile::clearOld($sid);
        $this->view->tfile = TmpFile::find("sid='{$sid}'");
        $this->view->simages = SellImages::find("sellid='{$sellid}'");
        $this->view->goods_unit = Purchase::$_goods_unit;
        $this->view->time_type = Sell::$type;
        $this->view->sid = $sid;
        $this->view->sell = $sell;
        $this->view->curCate = lCategory::ldData($sell->category);
        $this->view->curAreas = lAreas::ldData($sell->areas);

        $this->view->title = '修改供应信息-用户中心-丰收汇';
        $this->view->keywords = '修改供应信息-用户中心-丰收汇';
        $this->view->descript = '修改供应信息-用户中心-丰收汇';

    }

    /**
     * 发布
     */
    public function createAction()
    { 
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "sell",
                "action" => "index"
            ));
        }

        $user_id = $this->getUserID();
        $users = Users::findFirstByid($user_id);
        $cur_time = time();
           
        $sell = new Sell();

        $sell->title = $this->request->getPost("title", 'string', '');
        $sell->category = $this->request->getPost("category", 'int', 0);
        $sell->min_price = $this->request->getPost("min_price", 'float', 0.00);
        $sell->max_price = $this->request->getPost("max_price", 'float', 0.00);
        $sell->quantity = $this->request->getPost("quantity", 'float', 0.00);
        $sell->goods_unit = $this->request->getPost("goods_unit", 'int', 0);
        $sell->stime = $this->request->getPost("stime", 'int', 0);
        $sell->etime = $this->request->getPost("etime", 'int', 0);
        $sell->areas = $this->request->getPost("areas", 'int', 0);
        $sell->areas_name = Func::getCols(mAreas::getFamily($sell->areas), 'area_name', ',');
        $sell->address = $this->request->getPost("address", 'string', '');
        $sell->breed = $this->request->getPost("breed", 'string', '');
        $sell->spec = $this->request->getPost("spec", 'string', '');
        $sell->state = 0;
        $sell->uid = $user_id;
        $sell->uname = $users->ext->name;
        $sell->mobile = $users->username;
        $sell->createtime = $sell->updatetime = $cur_time;
        $content = $this->request->getPost('content', 'string', '');

        if (!$sell->save()) {
            foreach ($sell->getMessages() as $message) {
                $this->flash->error('发布出售失败！');
            }
            return $this->dispatcher->forward(array(
                "controller" => "sell",
                "action" => "new"
            ));
        } 

        $sell->sell_sn = sprintf('SELL%010u', $sell->id);
        $sell->save(); 

        // 处理图片
        $sid = md5(session_id());
        
        SellImages::copyImages($sell->id, $sid);

        $scontent = new Content();
        $scontent->sid=$sell->id;
        $scontent->content=$content;
        $scontent->save();
        Category::numAdd($sell->category, 'sell_num');
        $this->response->redirect("sell/index")->sendHeaders();
    }

    /**
     * 保存修改
     */
    public function saveAction()
    {
        if (!$this->request->isPost()) {

            return $this->dispatcher->forward(array(
                "controller" => "sell",
                "action" => "index"
            ));
        }

        $sellid = $this->request->get('sellid', 'int', 0);

        $sell = Sell::findFirstByid($sellid);

        if(!$sell) {
            $this->flash->error("此供应信息不存在！");
            return $this->dispatcher->forward(array(
                "controller" => "sell",
                "action" => "index"
            ));
        }

        $user_id = $this->getUserID();

        if($user_id != $sell->uid) {
            $this->flash->error("你无权修改此供应信息！");
            return $this->dispatcher->forward(array(
                "controller" => "sell",
                "action" => "index"
            ));
        }

        $users = Users::findFirstByid($user_id);

        $sell->title = $this->request->getPost("title", 'string', '');
        $sell->category = $this->request->getPost("category", 'int', 0);
        $sell->min_price = $this->request->getPost("min_price", 'float', 0.00);
        $sell->max_price = $this->request->getPost("max_price", 'float', 0.00);
        $sell->quantity = $this->request->getPost("quantity", 'float', 0.00);
        $sell->goods_unit = $this->request->getPost("goods_unit", 'int', 0);
        $sell->stime = $this->request->getPost("stime", 'int', 0);
        $sell->etime = $this->request->getPost("etime", 'int', 0);
        $sell->areas = $this->request->getPost("areas", 'int', 0);
        $sell->areas_name = Func::getCols(mAreas::getFamily($sell->areas), 'area_name', ',');
        $sell->address = $this->request->getPost("address", 'string', '');
        $sell->breed = $this->request->getPost("breed", 'string', '');
        $sell->spec = $this->request->getPost("spec", 'string', '');
        $sell->state = 0;
        $sell->uid = $user_id;
        $sell->mobile = $users->username;
        $sell->uname = $users->ext->name;
        $sell->updatetime = time();
        $content = $this->request->getPost('content', 'string', '');

        if (!$sell->save()) {
            foreach ($sell->getMessages() as $message) {
                $this->flash->error('发布出售失败！');
            }
            return $this->dispatcher->forward(array(
                "controller" => "sell",
                "action" => "new"
            ));
        }
        // 处理图片
        $sid = md5(session_id());

        SellImages::copyImages($sell->id, $sid);

        $scontent = new Content();
        $scontent->sid=$sell->id;
        $scontent->content=$content;
        $scontent->save();
        return $this->dispatcher->forward(array(
            "controller" => "sell",
            "action" => "index"
        ));
    }

    /**
     * 删除供应信息
     */
    public function deleteAction()
    {
        $id = $this->request->get('sellid', 'int', 0);

        $sell = Sell::findFirstByid($id);

        if (!$sell) {
            $this->flash->error("此供应信息不存在！");
            return $this->dispatcher->forward(array(
                "controller" => "sell",
                "action" => "index"
            ));
        }


        if($sell->uid != $this->getUserID()) {
            $this->flash->error("你无权删除此信息！");
            return $this->dispatcher->forward(array(
                "controller" => "sell",
                "action" => "index"
            ));
        }

        $sell->is_del = 1;
       
        if (!$sell->save()) {
            echo '<script>alert("取消发布失败，请联系客服！")</script>';
            foreach ($sell->getMessages() as $message) {
                $this->flash->error('取消发布失败，请联系客服！');
            }
            return $this->dispatcher->forward(array(
                "controller" => "sell",
                "action" => "index"
            ));
        }

        Category::numDec($sell->category, 'sell_num');
        echo '<script>alert("取消发布成功！")</script>';

        return $this->dispatcher->forward(array(
            "controller" => "sell",
            "action" => "index"
        ));
    }

    public function delimgAction() {
        $rs = array('state'=>false, 'msg'=>'删除图片成功！' );
        $id = $this->request->get('id', 'int', 0);

        $img = SellImages::findFirstByid($id);

        if(!$img) {
            $rs['msg'] = '图片不存在！';
            die(json_encode($rs));
        }

        if($this->getUserID() != $img->sell->uid) {
            $rs['msg'] = '你无权删除此图片！';
            die(json_encode($rs));
        }


        $sellid = $img->sellid;
        @unlink(PUBLIC_PATH.$img->path);
        $img->delete();
        $data = SellImages::findFirstBysellid($sellid);
        if(!$data) {
            $sell = Sell::findFirstByid($sellid);
            if($sell) {
                $sell->thumb = '';
                $sell->save();
            }
        }

        $rs['state'] = true;
        die(json_encode($rs));
    }

}