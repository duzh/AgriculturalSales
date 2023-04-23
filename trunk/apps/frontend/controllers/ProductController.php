<?php
namespace Mdg\Frontend\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Mdg\Models\Product as Article;
use Mdg\Models\ProductCategory as Category;
use Lib\Func as Func;
use Lib\Pages as Pages;
class ProductController extends ControllerBase
{

    /**
     *   显示文章列表
     */
    public function infoAction()
    {
        
        $p = $this->request->get('p', 'int', 1);

        $article=Article::findFirst("id=".$p."");
    
        if(!$article) {
            die('此文章不存在！');
        }
        $first = Article::findFirst();
        $catfirst = Article::findFirst("cid='{$article->cid}' and is_show=1 ");
        $prev = Article::findFirst(array("id<'{$p}' and cid='{$article->cid}' and is_show=1 ", 'order'=>'id desc'));
        $next = Article::findFirst(array("id>'{$p}' and cid='{$article->cid}' and is_show=1 ", 'order'=>'id asc'));
        
        $category=Category::findFirst("is_show=1 and id={$article->cid} "); 
        if(!$category){
            die('此分类不存在或未显示！');
        }

        Article::clickAdd($p);
        $category=$category->toArray();
        $this->view->article = $article;
        $this->view->prev = $prev;
        $this->view->next = $next;
        $this->view->first = $first;
        $this->view->catfirst = $catfirst;
        $this->view->p=$p;
        $this->view->title = $article->title.'-丰收汇';
        $this->view->keywords = $article->keywords;
        $this->view->catname = $category['catname'];
        $this->view->catid = $category['id'];
        $this->view->_nav       = 'product';
        $this->view->descript = $article->description;
    }
    /**
     *  分类列表
     * @return [type] [description]
     */
    public function  categoryAction(){
        $category_id=$this->request->get('c', 'int', 0);
        $page_size=$this->request->get('page_size', 'int', 10);
        $page = $this->request->get('p', 'int', 1);
        $category=Category::findFirst(" id={$category_id} ");
        $where=' is_show=1 ';
        if($category){
            $category=$category->toArray();
            $where.=" and cid={$category_id} ";
        }else{
            $category["catname"]="安全种植体系";
        } 
        $articlecategory = Article::find($where."order by  addtime desc  ");
      
        $paginator = new Paginator(array(
            "data" => $articlecategory,
            "limit"=> 10,
            "page" => $page
        ));
        $data = $paginator->getPaginate();
        $pages = new Pages($data);

        $pages = $pages->show(1);
        $this->view->isshow    = Article::$_is_show;
        $this->view->data       = $data;
        $this->view->pages      = $pages;
        $this->view->_nav       = 'product';
        $this->view->catname    = $category['catname'];
        $this->view->title ='丰收汇-'.$category['catname'];

    }
    public function  faoAction(){
        $this->view->_nav       = 'product';
        $this->view->title ='丰收汇-农药检测';
    }
    public function checknumAction(){
        $c=$this->request->get('c', 'int', '');
        $n=$this->request->get('n', 'int', '');
        $x=$this->request->get('x', 'int', '');
        if(!empty($c)&&!empty($n)&&!empty($x)) {
           $num = Article::jisuan($c, $n, $x);
           echo $num;die;
        }else{
           echo "检测参数不符合";die;
        }
    }

}
