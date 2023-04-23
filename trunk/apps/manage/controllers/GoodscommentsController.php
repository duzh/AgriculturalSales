<?php
namespace Mdg\Manage\Controllers;
use Mdg\Models as M;
use Mdg\Models\GoodsComments as GoodsComments;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Lib\Pages as Pages;
use Lib\Func as Func;
class GoodscommentsController extends ControllerBase
{
    /**
     *   评论列表
     * @return [type] [description]
     */
	public function indexAction(){
        $where = array(
            '1=1'
        );
        $arr        = array();
        $page_size = 10;

        $start_time = trim($this->request->get('start_time', 'string', ''));
        $end_time   = trim($this->request->get('end_time', 'string', ''));
        $name       = $this->request->get('name', 'string', '');
        $goodsname   = $this->request->get('goods_name', 'string', '');
        $type       = $this->request->get('type', 'string', 'all');
        $page = $this->request->getQuery("p", "int", 1);
    
        if ($name) {
            $where[] = "  user_name like '%{$name}%'";
        }
        
        if ($goodsname) {
            $where[] = "  goods_name like '%{$goodsname}%'";
        }
        
        if ($start_time && $end_time) {
            $starttime = strtotime( $start_time );
            $endtime = strtotime($end_time);
            $where[] = "  add_time between  {$starttime} and {$endtime} ";
        }
        
        if ($type != 'all') {
            $where[] = " is_check ={$type}";
        }
        
        $where = implode(' AND ', $where);
	
        $total = GoodsComments::count($where);
        $offst = intval(($page - 1) * $page_size);
        $cond[] = $where . " ORDER BY add_time DESC limit {$offst} , {$page_size} " ;
        $cond['columns'] = " sell_id , user_name, comment , add_time, is_check, goods_name, decribe_score, id ";
        
        $data = GoodsComments::find($cond);
        
        $pages['total_pages'] = ceil($total / $page_size);
        $pages['current'] = $page;
        $pages['total'] = $total;
        $pages = new Pages($pages);
        $pages = $pages->show(1);
        $paginator = new Paginator(array(
            "data" => $data,
            "limit" => 10,
            "page" => $page
        ));

        //模版默认选中
        $this->tag->setDefault("start_time", $start_time);
        $this->tag->setDefault("end_time", $end_time);
        $this->tag->setDefault("name", $name);
        $this->tag->setDefault("goods_name", $goodsname);

        $this->view->type = $type;
        $this->view->_is_check = GoodsComments::$_is_check;
        $this->view->current = $page;
        $this->view->pages = $pages;
        $this->view->page = $paginator->getPaginate();
	}
	
	/**
	* 后台获取评论列表
	*/
	public function GoodsCommentsListAction(){
		$p = $this->request->get('p', 'int', 1);
		$begin_time = $this->request->get();
		$user_id = '1';
		if (!$user_id){
		
		}
		$where = ' 1 ';
		
		$data = M\GoodsComments::getList($this->db, $where, $p);
		$pages['total_pages'] = $data['total_page'];
		$pages['current'] = $p;
		$pages['total'] = $data['total'];
		$pages = new Pages($pages);
		$data['pages'] = $pages->show(3);
		echo "<pre>";
		var_dump($data);exit;
	}

   /**
    *  审核评论
    * @param  integer $id   [description]
    * @param  integer $type [description]
    * @return boolean       [description]
    */
    public function is_checksaveAction($id = 0, $type = 0) 
    {
        if(!$id){
            echo '<script>alert("审核失败");location.href="/manage/goodscomments/index";</script>';exit;
        }
         
        $goodscomments = GoodsComments::findFirstByid($id);
        if(!$goodscomments){
            echo '<script>alert("审核失败");location.href="/manage/goodscomments/index";</script>';exit;
        }
        //审核不通过
        if($type==2){
            $goodscomments->is_check=2;
            $goodscomments->save();
            Func::adminlog("审核未通过：{$goodscomments->comment}",$this->session->adminuser['id']);
            echo '<script>alert("操作成功");location.href="/manage/goodscomments/index";</script>';exit;
        }

        //更改状态
        $goodscomments->is_check = 1;
        $goodscomments->save();
        Func::adminlog("审核通过：{$goodscomments->comment}",$this->session->adminuser['id']);
        $this->response->redirect('/goodscomments/index')->sendHeaders();
    }



}
?>