<?php
namespace Mdg\Manage\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Mdg\Models\ArticleCategory as Category;
use Lib\Func as Func;
use Lib\Pages as Pages;

class ArticleCategoryController extends ControllerMember
{

    /**
     *  文章分类列表
     */
    public function indexAction()
    {

        $page = $this->request->get('p', 'int', 1);
        $pid = $this->request->get('pid', 'int', 0);
        $is_show = $this->request->get('is_show', 'int', 0);
        $catname = $this->request->get('catname', 'string', '');
        $where = array(" 1=1 ");

        if($is_show=='1') {
            $where[] = "is_show='0'";
        }
        if($is_show=='2') {
            $where[] = "is_show='1'";
        }

        if($catname) {
            $where[] = "catname like '%{$catname}%'";
        }

        if($pid) {
            $ids = Category::getChild($pid);
            $where[] = empty($ids) ? '' : "id in (".implode(', ', $ids).")";
        }
        
        $where = array(implode(' and ', $where));
       
        $articlecategory = Category::find($where);
        
        $paginator = new Paginator(array(
            "data" => $articlecategory,
            "limit"=> 10,
            "page" => $page
        ));
        $data = $paginator->getPaginate();
        $cat_list = Func::totree(Category::find('pid=0')->toArray(), 'id', 'pid', 'child');
        $pages = new Pages($data);
        $pages = $pages->show(1);
        $this->view->cat_list   = $cat_list;
        $this->view->data       = $data;
        $this->view->pages      = $pages;
        $this->view->curstate   = $is_show;
        //保留数据
        $this->view->catname    = $catname;
        $this->view->pid        = $pid;
        $this->view->isshow     = $is_show;
        $this->view->is_show    = Category::$_is_show;
    }

    /**
     * 新增文章分类
     */
    public function newAction()
    {
        $this->view->is_show = Category::$_is_show;
        $this->tag->setDefault("is_show", 0);
        $this->view->position = Category::$position;
        $this->tag->setDefault("position", 0);

    }

    /**
     * 编辑文章分类
     *
     * @param string $id
     */
    public function editAction($id)
    {

        if (!$this->request->isPost()) {

            $articlecategory = Category::findFirstByid($id);
            if (!$articlecategory) {
                parent::msg("文章分类未找到",'/manage/articlecategory/index');
                // $this->flash->error("文章分类未找到");

                // return $this->dispatcher->forward(array(
                //     "controller" => "articlecategory",
                //     "action" => "index"
                // ));
            }

            $this->tag->setDefault("id", $articlecategory->id);
            $this->tag->setDefault("pid", $articlecategory->pid);
            $this->tag->setDefault("catname", $articlecategory->catname);
            $this->tag->setDefault("sortrank", $articlecategory->sortrank);
            $this->tag->setDefault("is_show", $articlecategory->is_show);
            $this->tag->setDefault("keywords", $articlecategory->keywords);
            $this->tag->setDefault("description", $articlecategory->description);

            $this->view->is_show = Category::$_is_show;
            $this->view->id = $articlecategory->id;
            $this->view->category = $articlecategory;

        }
    }

    /**
     * 新增文章分类
     */
    public function createAction()
    {
        
        if (!$this->request->isPost()) {
            $this->response->redirect("articlecategory/index")->sendHeaders();

        }
        $articlecategory = new Category();

        $articlecategory->pid         = $this->request->getPost("pid", 'int', 0);
        $articlecategory->catname     = $this->request->getPost("catname", 'string', '');
        $articlecategory->sortrank    = $this->request->getPost("sortrank", 'int', 50);
        $articlecategory->is_show     = $this->request->getPost("is_show", 'int', 0);
        $articlecategory->keywords    = $this->request->getPost("keywords", 'string', '');
        $articlecategory->description = $this->request->getPost("description", 'string', '');
        $articlecategory->addtime     = time();

        if (!$articlecategory->save()) {
            parent::msg($message,'/manage/articlecategory/index');
            // foreach ($articlecategory->getMessages() as $message) {
            //     $this->flash->error($message);
            // }

            // $this->response->redirect("articlecategory/index")->sendHeaders();
           
        }

        
        Func::adminlog("添加文章分类：{$articlecategory->catname}",$this->session->adminuser['id']);
        parent::msg('文章分类添加成功','/manage/articlecategory/index');
        // $this->flash->success("文章分类添加成功");
        // $this->response->redirect("articlecategory/index")->sendHeaders();
       

    }

    /**
     * 保存编辑文章分类
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "articlecategory",
                "action" => "index"
            ));
        }

        $id = $this->request->getPost("id");

        $articlecategory = Category::findFirstByid($id);
        if (!$articlecategory) {
            parent::msg('文章分类未找到'. $id,'/manage/articlecategory/index');
            // $this->flash->error("文章分类未找到 " . $id);

            // return $this->dispatcher->forward(array(
            //     "controller" => "articlecategory",
            //     "action" => "index"
            // ));
        }

        $articlecategory->pid = $this->request->getPost("pid", 'int', 0);
        $articlecategory->catname = $this->request->getPost("catname", 'string', '');
        $articlecategory->sortrank = $this->request->getPost("sortrank", 'int', 50);
        $articlecategory->is_show = $this->request->getPost("is_show", 'int', 0);
        $articlecategory->keywords = $this->request->getPost("keywords", 'string', '');
        $articlecategory->description = $this->request->getPost("description", 'string', '');
     

        if (!$articlecategory->save()) {
            parent::msg($message,"/manage/articlecategory/edit/{$id}");
            // foreach ($articlecategory->getMessages() as $message) {
            //     $this->flash->error($message);
            // }

            

            // return $this->dispatcher->forward(array(
            //     "controller" => "articlecategory",
            //     "action" => "edit",
            //     "params" => array($articlecategory->id)
            // ));
        }

        
        Func::adminlog("修改文章分类：{$articlecategory->catname}",$this->session->adminuser['id']);
        parent::msg('文章分类修改成功','/manage/articlecategory/index');
        // $this->flash->success("文章分类修改成功");
        // return $this->dispatcher->forward(array(
        //     "controller" => "articlecategory",
        //     "action" => "index"
        // ));

    }

    /**
     * 删除文章分类
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $articlecategory = Category::findFirstByid($id);
        if (!$articlecategory) {
            parent::msg('文章分类不存在','/manage/articlecategory/index');
            // $this->flash->error("文章分类不存在");

            // return $this->dispatcher->forward(array(
            //     "controller" => "articlecategory",
            //     "action" => "index"
            // ));
        }

        

        if (!$articlecategory->delete()) {
            parent::msg($message,'/manage/articlecategory/index');
            // foreach ($articlecategory->getMessages() as $message) {
            //     $this->flash->error($message);
            // }
            
            // return $this->dispatcher->forward(array(
            //     "controller" => "articlecategory",
            //     "action" => "index"
            // ));
        }

        Func::adminlog("删除文章分类：{$articlecategory->catname}",$this->session->adminuser['id']);
        parent::msg('删除成功','/manage/articlecategory/index');
        // $this->flash->success("删除成功");
        // return $this->dispatcher->forward(array(
        //     "controller" => "articlecategory",
        //     "action" => "index"
        // ));
    }
    /**
     *  检测文章分类
     * @return [type] [description]
     */
    public function checkarticleAction(){
        $pid = $this->request->get('id', 'int', 0);
        if(!$pid){
            parent::msg('此文章分类不存在！','/manage/articlecategory/index');
            // echo "<script>alert('此文章分类不存在！');location.href='/manage/articlecategory/index'</script>";
        }
        $child = Category::findFirstBypid($pid);
        if($child) {
           echo "no";die;
        }else{
           echo "yes";die;
        }
    }
    /**
     *  文章分类获取
     * @return [type] [description]
     */
    public function ajaxAction(){
        $pid = $this->request->get('pid', 'int', 0);
        $data[0] = array('id'=>0,'pId'=>0,'name'=>'顶级分类');
        if(!$pid) {
            $data[0]['checked'] = true;
        }
        $catelist = Category::find(array('order'=>'sortrank desc'));
        $i = 1;
        foreach ($catelist as $key => $value) {
            $data[$i] = array('id'=>intval($value->id),'pId'=>intval($value->pid),'name'=>$value->catname);
            if($pid == $value->id) $data[$i]['checked'] = true;
            $i++;
        }
        echo json_encode($data);
        exit;
    }

}
