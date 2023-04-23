<?php
namespace Mdg\Manage\Controllers;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Mdg\Models\Advisory as Article;
use Mdg\Models\AdvisoryCategory as Category;
use Lib\Pages as Pages;
use Lib\Func as Func;
use Lib\Validator as Validator;
use Mdg\Models\TmpFile as TmpFile;
/**
 * 资讯管理
 */

class AdvisoryController extends ControllerMember
{
    /**
     * 咨询列表
     */
    
    public function indexAction() 
    {
        
        $page = $this->request->get('p', 'int', 1);
        $cid = $this->request->get('pid', 'int', 0);
        $title = $this->request->get('title', 'string', '');
        $where = array();
        
        if ($cid) 
        {
            $ids = Category::getChild($cid);
            $where[] = empty($ids) ? '' : "cid in (" . implode(', ', $ids) . ")";
        }
        
        if ($title) 
        {
            $where[] = "title like '%{$title}%'";
        }
        $where = array(
            implode(' and ', $where)
        );
        $where['order'] = 'addtime desc';
        $articlecategory = Article::find($where);
        $paginator = new Paginator(array(
            "data" => $articlecategory,
            "limit" => 10,
            "page" => $page
        ));
        $this->view->cid = $cid;
        $data = $paginator->getPaginate();
        $pages = new Pages($data);
        $pages = $pages->show(1);
        $cat_list = Func::totree(Category::find()->toArray() , 'id', 'pid', 'child');
        $this->view->cat_list = $cat_list;
        $this->view->isshow = Article::$_is_show;
        $this->view->pid = $cid;
        $this->view->title = $title;
        $this->view->data = $data;
        $this->view->pages = $pages;
    }
    /**
     * 新增资讯
     */
    
    public function newAction() 
    {
		$sid = md5(session_id());
        TmpFile::clearOld($sid);
        $this->view->sid = $sid;
        $this->view->is_show	= Category::$_is_show;
        //$this->view->is_recom	= Article::$_is_recom;
        $this->tag->setDefault("is_show", 1);
    }
    /**
     * 编辑资讯
     *
     * @param string $id
     */
    
    public function editAction($id) 
    {
        
        if (!$this->request->isPost()) 
        {
            $article = Article::findFirstByid($id);
            
            if (!$article) 
            {
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
            $this->tag->setDefault("keywords", str_replace(';', ',', $article->keywords));
            $this->tag->setDefault("tags", str_replace(';', ',', $article->tags));
            $this->tag->setDefault("is_show", $article->is_show);
            $this->tag->setDefault("description", $article->description);
			$sid = md5(session_id());
			TmpFile::clearOld($sid);
			$this->view->sid = $sid;
			$imgfile = Article::find("id=" . $id)->toArray();
            $this->view->imgfile = $imgfile;
            $this->view->is_show	= Category::$_is_show;
            //$this->view->is_recom	= Article::$_is_recom;
            $this->view->is_hot	= $article->is_hot;
            $this->view->id = $article->id;
            $this->view->content = $article->content;
            $this->view->art = $article;
            $this->view->img = $article->thumb;
        }
    }
    /**
     * 查看资讯
     *
     * @param string $id
     */
    
    public function lookAction($id) 
    {
        
        if (!$id) 
        {
            parent::msg('信息不存在','/manage/product/index'); 
            // $this->flash->error("信息不存在");
            // return $this->dispatcher->forward(array(
            //     "controller" => "product",
            //     "action" => "index"
            // ));
        }
        $article = Article::findFirstByid($id);
        
        if (!$article) 
        {
            parent::msg('文章未找到','/manage/product/index'); 
            // $this->flash->error("文章未找到");
            // return $this->dispatcher->forward(array(
            //     "controller" => "product",
            //     "action" => "index"
            // ));
        }
        $this->view->article = $article;
        $this->view->isshow = Article::$_is_show;
    }
    /**
     * 新增资讯
     */
    
    public function createAction() 
    {
        
       
        if (!$this->request->isPost()) 
        {
            $this->response->redirect("advisory/index")->sendHeaders();
        }
        $keywordArr = array();//咨询关键词
        $tagArr = array();//标签
        $keywords = $this->request->getPost("keywords", 'string', ' ');
        $tags = $this->request->getPost("tags", 'string', ' ');

        //关键词中如果含有逗号和空格用逗号或空格分割
        if($keywords){
            $keywords = explode(',', str_replace('，', ',',trim($keywords)));
            if (count($keywords) > 1) {
                foreach ($keywords as $key => $value) {
                    $ifKongge = strstr($value, ' ' ,true);
                    if ($ifKongge) {
                        $value = explode(' ', trim($value));
                        if (count($value) > 1) {
                            foreach ($value as $key => $val) {
                               $keywordArr[] =  $val;
                            }
                        }else{
                            $keywordArr[] =  $value[0];
                        }
                    }else{
                        $keywordArr[] = $valueT;
                    }
                }
            }else{
                $keywords = explode(' ', trim($keywords[0]));
                if (count($keywords) > 1) {
                    foreach ($keywords as $key => $value) {
                       $keywordArr[] = trim($value);
                    }
                }else{
                   $keywordArr =  $keywords;
                }
            }
        }
         //关键词中如果含有逗号和空格用逗号或空格分割
        if($tags){
            $tags = explode(',', str_replace('，', ',',trim($tags)));
            if (count($tags) > 1) {
                foreach ($tags as $key => $valueT) {
                    $ifKongge1 = strstr($valueT, ' ' ,true);
                    if ($ifKongge1) {
                        $valueT = explode(' ', trim($valueT));
                        if (count($valueT) > 1) {
                            foreach ($valueT as $key => $val) {
                               $tagArr[] =  $val;
                            }
                        }else{
                            $tagArr[] =  $valueT[0];
                        }
                    }else{
                        $tagArr[] = $valueT;
                    }
                }
            }else{
                $tags = explode(' ', trim($tags[0]));
                if (count($tags) > 1) {
                    foreach ($tags as $key => $valueT) {
                       $tagArr[] = trim($valueT);
                    }
                }else{
                   $tagArr =  $tags;
                }
            }
        }


        $article = new Article();
        $article->cid = $this->request->getPost("cid", 'int', 0);
        $article->title = $this->request->getPost("title", 'string', ' ');
        $article->keywords = implode(';', $keywordArr);
        $article->tags = implode(';', $tagArr);
        $article->description = $this->request->getPost("description", 'string', ' ');
        $article->content = $this->request->getPost("content");
        $article->is_show = $this->request->getPost("is_show", 'string', ' ');
        $article->count = 0;
        $article->addtime = $article->updatetime =$article->recomtime =time();

		
        
        if (!$article->save()) 
        {
            parent::msg($message,'/manage/advisory/index'); 
            // foreach ($article->getMessages() as $message) 
            // {

            //     $this->flash->error($message);
            // }
       
            // $this->response->redirect("advisory/index")->sendHeaders();
        }
		// 处理图片
		$sid = md5(session_id());
        TmpFile::copyImages($article->id, $sid);
        Func::adminlog("案例添加：{$article->title}");
        $this->response->redirect("advisory/index")->sendHeaders();
       
    
    }
    /**
     * 编辑保存资讯
     *
     */
    
    public function saveAction() 
    {
        
        if (!$this->request->isPost()) 
        {
            return $this->dispatcher->forward(array(
                "controller" => "advisory",
                "action" => "index"
            ));
        }

        $id = $this->request->getPost("id");
        $article = Article::findFirstByid($id);
        
        if (!$article) 
        {
            parent::msg("article does not exist ". $id,'/manage/advisory/index');
            // $this->flash->error("article does not exist " . $id);
            // return $this->dispatcher->forward(array(
            //     "controller" => "advisory",
            //     "action" => "index"
            // ));
        }

        $keywordArr = array();//咨询关键词
        $tagArr = array();//标签
        $keywords = $this->request->getPost("keywords", 'string', ' ');
        $tags = $this->request->getPost("tags", 'string', ' ');

        //关键词中如果含有逗号和空格用逗号或空格分割
        if($keywords){
            $keywords = explode(',', str_replace('，', ',',trim($keywords)));
            if (count($keywords) > 1) {
                foreach ($keywords as $key => $value) {
                    $ifKongge = strstr($value, ' ' ,true);
                    if ($ifKongge) {
                        $value = explode(' ', trim($value));
                        if (count($value) > 1) {
                            foreach ($value as $key => $val) {
                               $keywordArr[] =  $val;
                            }
                        }else{
                            $keywordArr[] =  $value[0];
                        }
                    }else{
                        $keywordArr[] = $value;
                    }
                }
            }else{
                $keywords = explode(' ', trim($keywords[0]));
                if (count($keywords) > 1) {
                    foreach ($keywords as $key => $value) {
                       $keywordArr[] = trim($value);
                    }
                }else{
                   $keywordArr =  $keywords;
                }
            }
        }
         //关键词中如果含有逗号和空格用逗号或空格分割
        if($tags){
            $tags = explode(',', str_replace('，', ',',trim($tags)));
            if (count($tags) > 1) {
                foreach ($tags as $key => $valueT) {
                    $ifKongge1 = strstr($valueT, ' ' ,true);
                    if ($ifKongge1) {
                        $valueT = explode(' ', trim($valueT));
                        if (count($valueT) > 1) {
                            foreach ($valueT as $key => $val) {
                               $tagArr[] =  $val;
                            }
                        }else{
                            $tagArr[] =  $valueT[0];
                        }
                    }else{
                        $tagArr[] = $valueT;
                    }
                }
            }else{
                $tags = explode(' ', trim($tags[0]));
                if (count($tags) > 1) {
                    foreach ($tags as $key => $valueT) {
                       $tagArr[] = trim($valueT);
                    }
                }else{
                   $tagArr =  $tags;
                }
            }
        }


        $article->cid = $this->request->getPost("cid");
        $article->title = $this->request->getPost("title");
        $article->keywords = implode(';', $keywordArr);
        $article->tags = implode(';', $tagArr);
        $article->description = $this->request->getPost("description");
        $article->content = $this->request->getPost("content");
        $article->is_show = $this->request->getPost("is_show", 'string', '');
        //$article->is_hot = $this->request->getPost("is_recom", 'string', '');
        $article->updatetime = $article->recomtime=time();
        if (!$article->save()) 
        {
            parent::msg($message,"/manage/advisory/edit/{$id}");
            // foreach ($article->getMessages() as $message) 
            // {
            //     $this->flash->error($message);
            // }
            // return $this->dispatcher->forward(array(
            //     "controller" => "advisory",
            //     "action" => "edit",
            //     "params" => array(
            //         $article->id
            //     )
            // ));
        }
		// 处理图片
		$sid = md5(session_id());
        TmpFile::copyImages($article->id, $sid);
        Func::adminlog("咨询修改：{$article->title}");
        parent::msg('修改成功','/manage/advisory/index');
        // $this->flash->success("修改成功");
        // return $this->dispatcher->forward(array(
        //     "controller" => "advisory",
        //     "action" => "index"
        // ));
    }
    /**
     * 删除资讯
     *
     * @param string $id
     */
    
    public function deleteAction($id) 
    {
        $article = Article::findFirstByid($id);
        
        if (!$article) 
        {
            parent::msg('文章未找到','/manage/advisory/index');
            // $this->flash->error("文章未找到");
            // return $this->dispatcher->forward(array(
            //     "controller" => "advisory",
            //     "action" => "index"
            // ));
        }
        
        if (!$article->delete())
        {
            parent::msg($message,'/manage/advisory/search');
            // foreach ($article->getMessages() as $message) 
            // {
            //     $this->flash->error($message);
            // }
            // return $this->dispatcher->forward(array(
            //     "controller" => "advisory",
            //     "action" => "search"
            // ));
        }
        Func::adminlog("案例删除：{$article->title}");
        parent::msg('删除成功','/manage/advisory/index');
        // $this->flash->success("删除成功");
        // return $this->dispatcher->forward(array(
        //     "controller" => "advisory",
        //     "action" => "index"
        // ));
    }
	
	/**
	 *	文章推荐列表
	 **/
	public function recomAction() {
		
		// 接收参数
		$page   	= $this->request->get( 'p' , 'int' , 1 );
		$page 		= $page ? intval($page) :1 ;
		$pageSize	= 5;
		$offst		= ($page-1) * $pageSize;
		// 查询条件
		$cond		= isset($_REQUEST['keywords']) && (!empty($_REQUEST['keywords'])) ? "is_hot = '{$_REQUEST['keywords']}'" : "";
		
        $pid	= $this->request->get('pid', 'int', 0);
        $child 	= $this->request->get('is_recom', 'int', 0);
		$cats	= array();
		// 获取查询数据
		if( $pid && $child) {
			$cats	= $this->db->fetchAll("SELECT id,title FROM advisory WHERE cid=$pid AND is_show=1 AND is_hot=0",2);
		}
		// 获取推荐数据
		$recomList		= Article::getRecomList( $this->db , $page , $offst , $pageSize , $cond );
		
		// 获取二级分类
		$childCat	= Category::find('pid=3')->toArray();//默认新闻子类二期
		$catname	= Category::getCatnameArray();
		//传值
		$this->view->recomList	= $recomList;
		
		$this->view->cats		= $cats;
		$this->view->is_recom	= Article::$_is_recom;
		$this->view->cat_recom	= json_encode($catname);
		$this->view->recoms		= $catname;
		$this->view->child		= isset($child) && $child != 0 ? intval($child) : 1 ;
		$this->view->pid		= isset($pid) && $pid != 0 ? intval($pid) : 3 ;//默认新闻
		$this->view->childCat	= $childCat;
	}
	
	/**
	 *	推荐
	 */
	public function getArticleAction() {
		
		// 获取参数
		$pid	= isset($_REQUEST['pid']) ? intval($_REQUEST['pid']) : 0 ;
		$recom	= isset($_REQUEST['is_recom']) ? intval($_REQUEST['is_recom']) : 0 ;
		$ids	= implode(',',$_REQUEST['source_select1']);
		$time	= time();
		// 更新推荐数据
		$up 	= $this->db->query("UPDATE advisory SET is_hot=$recom,recomtime=$time WHERE cid=$pid AND is_show=1 AND id in($ids)");
		if($up) {
			echo json_encode("success");exit;
		} else {
			echo json_encode("fail");exit;
		}
		
	}
	/**
	 *	取消推荐
	 */
	public function delRecomAction(){
		// 获取参数
		$id	= isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0 ;
		if(!$id) {
			echo json_encode("false");exit;
		}
		$up 	= $this->db->query("UPDATE advisory SET is_hot=0 WHERE id=$id");
		if($up) {
			echo json_encode("success");exit;
		} else {
			echo json_encode("fail");exit;
		}		
	}
	/**
	 *	获取子类
	 **/
	public function getCatsAction() {
		
		// 获取参数
		$catid		= isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0 ;
		$ids 		= implode(',',Category::getChild($catid));
		$catsname	= $this->db->fetchAll("SELECT id,catname FROM advisory_category WHERE pid=$catid",2);
		echo json_encode($catsname);exit;
	}
}
