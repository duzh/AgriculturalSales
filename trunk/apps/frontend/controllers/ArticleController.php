<?php
namespace Mdg\Frontend\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Mdg\Models\Article as Article;
use Mdg\Models\ArticleCategory as Category;
use Lib\Func as Func;
class ArticleController extends ControllerBase
{

    /**
     * Index action
     */
    public function indexAction()
    {
        $p = $this->request->get('p', 'int', 1);
        $category=Category::find("is_show=1")->toArray();
        $category = Func::toTree($category, 'id', 'pid');

        $article=Article::findFirst("id=".$p." and is_show=1 and type=0 ");
        if(!$article) {
            die('此文章不存在！');
        }

        Article::clickAdd($p);
        $first = Article::findFirst(" is_show=1 and type=0 ");
        $catfirst = Article::findFirst("cid='{$article->cid}' and is_show=1 and type=0 ");
        $prev = Article::findFirst(array("id<'{$p}' and is_show=1 and type=0", 'order'=>'id desc'));
        $next = Article::findFirst(array("id>'{$p}' and is_show=1 and type=0 ", 'order'=>'id asc'));


        $this->view->article = $article;
        $this->view->category = $category;
        $this->view->prev = $prev;
        $this->view->next = $next;
        $this->view->first = $first;
        $this->view->catfirst = $catfirst;
        $this->view->p=$p;
        $this->view->title = $article->title.'-丰收汇';
        $this->view->keywords = $article->keywords;
        $this->view->descript = $article->description;
    }

}
