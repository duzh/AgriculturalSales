<?php
namespace Mdg\Manage\Controllers;
/**
 * @package     Mdg
 * @subpackage  Member
 * @author      Funky <70793999@qq.com>
 * @copyright   2014 YNC365
 * @version     @@PACKAGE_VERSION@@
 */
namespace Mdg\Manage\Controllers;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Lib\Member as member, Lib\Auth as Auth, Lib\SMS as sms;
use Mdg\Models\Admin as Admin;
use Mdg\Models\ShopComments as ShopComments;
use Mdg\Models\ShopGrade as ShopGrade;
use Lib\Pages as Pages;
use Lib\Func as Func;

class CommentsController extends ControllerBase
{  
    /**
     *  评论列表
     * @return [type] [description]
     */
    public function indexAction() 
    {
        $where = array(
            '1=1'
        );
        $arr        = array();
        $page_size = 10;

        $start_time = trim($this->request->get('start_time', 'string', ''));
        $end_time   = trim($this->request->get('end_time', 'string', ''));
        $name       = $this->request->get('name', 'string', '');
        $shopname   = $this->request->get('shop_name', 'string', '');
        $type       = $this->request->get('type', 'string', 'all');
        $page = $this->request->getQuery("p", "int", 1);
    
        if ($name) {
            $where[] = "  user_name like '%{$name}%'";
        }
        
        if ($shopname) {
            $where[] = "  shop_name like '%{$shopname}%'";
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
   
       
        $total = ShopComments::count($where);
        $offst = intval(($page - 1) * $page_size);
        $cond[] = $where . " ORDER BY add_time DESC limit {$offst} , {$page_size} " ;
        $cond['columns'] = " shop_id , user_name, comment , add_time, is_check, shop_name, service, accompany, description, supply, id ";
        
        $data = ShopComments::find($cond);
        
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
        $this->tag->setDefault("shop_name", $shopname);

        $this->view->type = $type;
        $this->view->_is_check = ShopComments::$_is_check;
        $this->view->current = $page;
        $this->view->pages = $pages;
        $this->view->page = $paginator->getPaginate();
    }
    /**
     *  评论审核
     * @param  integer $id   [description]
     * @param  integer $type [description]
     * @return boolean       [description]
     */
    public function is_checksaveAction($id = 0, $type = 0) 
    {
        if(!$id){
            echo '<script>alert("审核失败");location.href="/manage/comments/index";</script>';exit;
        }
         
        $shopcomments = ShopComments::findFirstByid($id);
        if(!$shopcomments){
            echo '<script>alert("审核失败");location.href="/manage/comments/index";</script>';exit;
        }
        //审核不通过
        if($type==2){
            $shopcomments->is_check=2;
            $shopcomments->save();
            Func::adminlog("审核未通过：{$shopcomments->comment}",$this->session->adminuser['id']);
            echo '<script>alert("操作成功");location.href="/manage/comments/index";</script>';exit;
        }

        $shopgrade=ShopGrade::findFirstByshop_id($shopcomments->shop_id);

        //第一次评论
        
        if(!$shopgrade) {
            $ShopGrade = new ShopGrade();
            $ShopGrade->shop_id = $shopcomments->shop_id;
            $ShopGrade->service = $shopcomments->service;
            $ShopGrade->accompany = $shopcomments->accompany;
            $ShopGrade->supply = $shopcomments->supply;
            $ShopGrade->description = $shopcomments->description;
            $ShopGrade->add_time = time();
            $ShopGrade->comments_count = 1;
            if(!$ShopGrade->save()) {
            }

            //修改评论状态
            $shopcomments->is_check = 1;
            $shopcomments->save();
            $this->response->redirect('/comments/index')->sendHeaders();exit;
            // echo '<script>alert("操作成功");location.href="/manage/comments/index";</script>';exit;
        }

        
        /*
        //第一次评分除1  
        if($shopgrade->service==0.0) $service_type =1;
        else $service_type =2;
        if($shopgrade->accompany==0.0) $accompany_type =1;
        else $accompany_type =2;
        if($shopgrade->supply==0.0) $supply_type =1;
        else $supply_type =2;
        if($shopgrade->description==0.0) $description_type =1;
        else $description_type =2;
        */
  
        /*
        //格式化分数
        $shopgrade->service = number_format(($shopgrade->service+$shopcomments->service) / $service_type, 1, '.', '');

        $shopgrade->accompany = number_format(($shopgrade->accompany+$shopcomments->accompany) /$accompany_type, 1, '.', '');
        $shopgrade->supply = number_format(($shopgrade->supply+$shopcomments->supply)/$supply_type,1, '.', '');
        $shopgrade->description = number_format(($shopgrade->description+$shopcomments->description)/$description_type, 1, '.', '');
        $shopgrade->comments_count = $shopgrade->comments_count+1;
    */
        $count =  $shopgrade->comments_count + 1 ;
        $shopgrade->service = number_format(($shopgrade->service+$shopcomments->service) / $count, 1, '.', '');

        $shopgrade->accompany = number_format(($shopgrade->accompany+$shopcomments->accompany) /$count, 1, '.', '');
        $shopgrade->supply = number_format(($shopgrade->supply+$shopcomments->supply)/$count,1, '.', '');
        $shopgrade->description = number_format(($shopgrade->description+$shopcomments->description)/$count, 1, '.', '');
        $shopgrade->comments_count = $count;
   

        //更改状态
        $shopcomments->is_check = 1;
        $shopgrade->save();
        $shopcomments->save();
        Func::adminlog("审核通过：{$shopcomments->comment}",$this->session->adminuser['id']);
        $this->response->redirect('/comments/index')->sendHeaders();
    }
}
?>