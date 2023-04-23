<?php
/**
 * 资讯管理 前台
 */
namespace Mdg\Frontend\Controllers;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Mdg\Models\Advisory as Article;
use Mdg\Models\AdvisoryCategory as Category;
use Lib\Func as Func;
use Lib\Pages as Pages;
class AdvisoryController extends ControllerBase
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
   
        $category=Category::findFirst(" id={$article->cid} "); 
        if(!$category){
            die('此文章无分类！');
        }
        $category = $category->toArray();
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
    public function  indexAction(){

        $category_id=$this->request->get('c', 'int', 0);
        $page_size=$this->request->get('page_size', 'int', 10);
        $page = $this->request->get('p', 'int', 1);        
        $where='is_show=1';

        if($category_id){
        	$category=Category::findFirst(" id={$category_id} ");
        }else{
        	$where.=" AND is_hot=1";
        }

        if($category){
            $category=$category->toArray();
            $where.=" AND cid = {$category_id}";
        }else{
            $category["catname"]="热门推荐";
            $where.=" AND is_hot=1";
        } 
        $articlecategory = Article::find($where." order by  addtime desc  ");
    
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
	
	/**
	 *	汇资讯首页
	 **/
	public function adindexAction() {
		
		$catId		= array( 3, 6, 7, 8 );
		
		// 新闻
		$newsList	= Article::getNewsList( $catId[0] );
		// 动态
		$dynamicList= Article::getNewsList( $catId[2] );
		// 活动
		$activeList	= Article::getNewsList( $catId[1] );
		// 公告
		$noticeList	= Article::getNewsList( $catId[3] );

		// 搜索		
		$keywords	= $this->request->get('keys','string','');
		if($keywords) {
			$this->view->keys	= $keywords;
		} else {
			$this->view->keys	= '';
		}
		$this->view->newsList 	= $newsList;
		$this->view->dynamicList= $dynamicList;
		$this->view->activeList = $activeList;
		$this->view->noticeList = $noticeList;
		$this->view->catId 		= $catId;
		$this->view->title 		= '汇资讯-丰收汇';
	}
	
	/**
	 *	汇资讯列表
	 **/
	public function newslistAction() {
		
		// 获取参数
		$keywords	= $this->request->get('keys','string','');
		$catId		= $this->request->get('cid','int',0);
		$page		= $this->request->get('p','int',1);
		$page		= isset($page) && $page>0 ? intval($page) : 1 ;
		$page_size	= 10;
		$offst 		= ($page-1)*$page_size;
		
		
		$order['count']		= $this->request->get('count',	'string','');
		$order['default']	= $this->request->get('default','string','');
		
		foreach($order as $key => $o){
			if($o) break;
		}
		switch ($key) {
			case 'count':
				$_order = 'count ' . $o;
				break;
			default:
				$_order = isset($order['default']) && !empty($order['default']) ? 'id ' . $o : 'id desc';
				break;
		}
		/*$count	= $this->request->get('count', 'string', '');

		$_order = '';
		if ($count){
			$_order = ' count DESC';
		}

		*/
		$newsList	= Article::getcatList( $keywords , $page, $offst, $page_size, $catId, $_order );
        
		$orderName	= Article::getOrder($key,$o);

		$params = $_GET;
		unset($params['_url'],$params['p']);
		$url = http_build_query($params);
		// 获取分类名称
		$catname = '';
		switch($catId){
			case 3:
				$catname = '新闻';
				break;
			case 6:
				$catname = '活动';
				break;
			case 7:
				$catname = '动态';
				break;
			case 8:
				$catname = '公告';
				break;
			default:
				$catname = '商品搜索';
				break;
		}
		// 搜索
		if($keywords) {
			$this->view->keys	= $keywords;
		} else {
			$this->view->keys	= '';
		}
		//echo "<pre>";
		//var_dump($orderName);exit;
		$this->view->p          = $page;
		$this->view->url		= $url;		
		$this->view->newsList	= $newsList;
		$this->view->orderName	= $orderName;
		$this->view->catName	= $catname;
		$this->view->catId		= $catId;
		$this->view->title 		= $catname.'-汇资讯-丰收汇';
		
	}
	
	/**
	 *	汇资讯详情页
	 **/
	public function adinfoAction() {
		
		// 接收参数
		$id		= $this->request->get('id','int',0);
		$keywords	= $this->request->get('keys','string','');
		// 校验参数
		$isdata	     = Article::findFirstByid($id);
		$data        = $isdata->toArray();
		$ad_descript = $data['keywords'];
		$tags        = $data['tags'];
		$ad_descript = str_replace(';;' , ';' ,$ad_descript);
		$ad_descript = str_replace(';' , ',' ,$ad_descript);	
		$tags        = explode(';', $tags);
		// echo 'aa';exit;
		if( !$isdata ) {
			echo "<script type='text/javascript'>window.location.href='/'</script>";exit;
		}
		// 获取数据
		$advisoryInfo		= Article::getAdvisoryInfo( $id );
		$key = "advisory_{$id}";
		if(!isset($this->session->$key)) {
			Article::advisoryClickAdd($id);
			$this->session->$key = 1;
		}
		
		// 搜索
		if($keywords) {
			$this->view->keys	= $keywords;
		} else {
			$this->view->keys	= '';
		}
		// 获取分类名称
		$catname = '';
		switch($isdata->cid){
			case 3:
				$catname = '新闻';
				break;
			case 6:
				$catname = '活动';
				break;
			case 7:
				$catname = '动态';
				break;
			case 8:
				$catname = '公告';
				break;
			default:
				$catname = '商品搜索';
				break;
		}

		if($advisoryInfo['infoMess']['keywords']) {
			$this->view->keywords = $advisoryInfo['infoMess']['keywords'];
		}

		$this->view->ad_descript = $ad_descript;
		$this->view->tags 		 = $tags;
		$this->view->info		 = $advisoryInfo;
		$this->view->catId		 = $isdata->cid;
		$this->view->catName     = $catname;
		$this->view->title 		 = $advisoryInfo['infoMess']['title'].'-汇资讯-丰收汇';

	}
}
