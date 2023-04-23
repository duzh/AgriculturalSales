<?php
namespace Mdg\Manage\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Mdg\Models\Product as Article;
use Mdg\Models\ProductCategory as Category;
use Lib\Pages as Pages;
use Lib\Func as Func;
class ProductController extends ControllerMember
{

    /**
     * 产品案例列表
     */
    public function indexAction()
    {   
       
        $page = $this->request->get('p', 'int', 1);
        
        $cid = $this->request->get('pid', 'int', 0);
        $title = $this->request->get('title', 'string', '');
        
        $where = array();
        if($cid) {
            $ids = Category::getChild($cid);
            $where[] = empty($ids) ? '' : "cid in (".implode(', ', $ids).")";
        }

        if($title) {
            $where[] = "title like '%{$title}%'";
        }
          
        $where = array(implode(' and ', $where));

        $where['order'] = 'addtime desc';

        $articlecategory = Article::find($where);
        
        $paginator = new Paginator(array(
            "data" => $articlecategory,
            "limit"=> 10,
            "page" => $page
        ));

        $this->view->cid = $cid;
        $data = $paginator->getPaginate();
        $pages = new Pages($data);
        $pages = $pages->show(1);
        $cat_list = Func::totree(Category::find()->toArray(), 'id', 'pid', 'child');
        $this->view->cat_list   = $cat_list;
        $this->view->isshow    = Article::$_is_show;
        $this->view->pid        = $cid;
        $this->view->title      = $title;
        $this->view->data       = $data;
        $this->view->pages      = $pages;

    }

    /**
     * 新增产品案例
     */
    public function newAction()
    {
        $this->view->is_show = Category::$_is_show;
        $this->tag->setDefault("is_show", 1);

    }

    /**
     * 编辑产品案例
     *
     * @param string $id
     */
    public function editAction($id)
    {
        
         if (!$this->request->isPost()) {

            $article = Article::findFirstByid($id);
            if (!$article) {
                parent::msg('该分类不存在','/manage/product/index');
                // $this->flash->error("该分类不存在");

                // return $this->dispatcher->forward(array(
                //     "controller" => "product",
                //     "action" => "index"
                // ));
            }
            

            $this->tag->setDefault("id", $article->id);
            $this->tag->setDefault("cid", $article->cid);
            $this->tag->setDefault("title", $article->title);
            $this->tag->setDefault("keywords", $article->keywords);
            $this->tag->setDefault("tags", $article->tags);
            $this->tag->setDefault("is_show", $article->is_show);
            $this->tag->setDefault("description", $article->description);
            
            $this->view->is_show = Category::$_is_show;
            $this->view->id = $article->id;
            $this->view->content=$article->content;
            $this->view->art = $article;
        }
    }
     /**
     * 查看产品案例
     *
     * @param string $id
     */
    public function lookAction($id)
    {
            if(!$id){
                parent::msg('信息不存在','/manage/product/index');
                // $this->flash->error("信息不存在");
                // return $this->dispatcher->forward(array(
                //     "controller" => "product",
                //     "action" => "index"
                // ));
            }
            $article = Article::findFirstByid($id);
            if (!$article) {
                parent::msg('文章未找到','/manage/product/index');
                // $this->flash->error("文章未找到");
                // return $this->dispatcher->forward(array(
                //     "controller" => "product",
                //     "action" => "index"
                // ));
            }
            $this->view->article = $article;
            $this->view->isshow    = Article::$_is_show;
       
    }

    /**
     * 新增产品案例保存
     */
    public function createAction()
    {
       
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "product",
                "action" => "index"
            ));
        }
        
        $article = new Article();

        $article->cid = $this->request->getPost("cid", 'int', 0);
        $article->title = $this->request->getPost("title", 'string', '');
        $article->keywords = $this->request->getPost("keywords", 'string', '');
        $article->tags = $this->request->getPost("tags", 'string', '');
        $article->description = $this->request->getPost("description", 'string', '');
        $article->content = $this->request->getPost("content");
        $article->is_show = $this->request->getPost("is_show",'string','');
        $article->count =0;
        $article->addtime = $article->updatetime = time();
      
        if (!$article->save()) {
            parent::msg($message,'/manage/product/new');
            // foreach ($article->getMessages() as $message) {
            //     $this->flash->error($message);
            // }

            // return $this->dispatcher->forward(array(
            //     "controller" => "product",
            //     "action" => "new"
            // ));
        }

        Func::adminlog("案例添加：{$article->title}",$this->session->adminuser['id']);
        parent::msg('文章添加成功','/manage/product/index');
        // $this->flash->success("文章添加成功");

        // return $this->dispatcher->forward(array(
        //     "controller" => "product",
        //     "action" => "index"
        // ));

    }

    /**
     * 编辑产品案例保存
     *
     */
    public function saveAction()
    {
       
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "product",
                "action" => "index"
            ));
        }

        $id = $this->request->getPost("id");

        $article = Article::findFirstByid($id);
        if (!$article) {
            parent::msg("article does not exist " . $id,'/manage/product/index');
            // $this->flash->error("article does not exist " . $id);

            // return $this->dispatcher->forward(array(
            //     "controller" => "product",
            //     "action" => "index"
            // ));
        }
    
        $article->cid = $this->request->getPost("cid");
        $article->title = $this->request->getPost("title");
        $article->keywords = $this->request->getPost("keywords");
        $article->tags = $this->request->getPost("tags");
        $article->description = $this->request->getPost("description");
        $article->content = $this->request->getPost("content");
        $article->is_show = $this->request->getPost("is_show",'string','');
        $article->updatetime = time();
        

        if (!$article->save()) {
            parent::msg($message,"/manage/product/edit/{$id}");
            // foreach ($article->getMessages() as $message) {
            //     $this->flash->error($message);
            // }

            // return $this->dispatcher->forward(array(
            //     "controller" => "product",
            //     "action" => "edit",
            //     "params" => array($article->id)
            // ));
        }
        Func::adminlog("案例修改：{$article->title}",$this->session->adminuser['id']);
        parent::msg('修改成功','/manage/product/index');
        // $this->flash->success("修改成功");

        // return $this->dispatcher->forward(array(
        //     "controller" => "product",
        //     "action" => "index"
        // ));

    }

    /**
     *删除产品案例
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
      
        $article = Article::findFirstByid($id);
        if (!$article) {
            parent::msg('文章未找到','/manage/product/index');
            // $this->flash->error("文章未找到");

            // return $this->dispatcher->forward(array(
            //     "controller" => "product",
            //     "action" => "index"
            // ));
        }

        if (!$article->delete()) {
            parent::msg($message,'/manage/product/search');
            // foreach ($article->getMessages() as $message) {
            //     $this->flash->error($message);
            // }

            // return $this->dispatcher->forward(array(
            //     "controller" => "product",
            //     "action" => "search"
            // ));
        }


        Func::adminlog("案例删除：{$article->title}",$this->session->adminuser['id']);
        parent::msg('删除成功','/manage/product/index');
        // $this->flash->success("删除成功");

        // return $this->dispatcher->forward(array(
        //     "controller" => "product",
        //     "action" => "index"
        // ));
    }

}
