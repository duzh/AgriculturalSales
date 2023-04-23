<?php
namespace Mdg\Manage\Controllers;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Mdg\Models\WebsiteLink as WebsiteLink;
use Lib\Pages as Pages;
use Lib\Func as Func;
class FriendlinkController extends ControllerMember
{
	
	 /**
     * 友情连接列表
     */
    public function linklistAction()
    {   
        $page = $this->request->get('p', 'int', 1);
		$website_name	= trim($this->request->get('website_name', 'string', ''));
		$website_link	= trim($this->request->get('website_link', 'string', ''));
        $status 		= $this->request->get('status', 'int', '3');
        $location		= $this->request->get('location', 'int', '3');
        $islogo 		= $this->request->get('islogo', 'int', '3');		
		$where			= array();
		if($website_name) {
           
            $where[] = "website_name ='{$website_name}'";
        }
        if($website_link) {
           
            $where[] = "website_link ='{$website_link}'";
        }

        if($status&&$status!=3) {
           $where[] = "status ='{$status}'";
        }
        if($location&&$location!=3) {
           $where[] = "location ='{$location}'";
        }
		if($islogo&&$islogo!=3) {
			 $where[] = "islogo ='{$islogo}'";
          
        }
        $where = array(implode(' and ', $where));

        $where['order'] = 'add_time desc';
        $Linklist = WebsiteLink::find($where);
		$paginator = new Paginator(array(
            "data" => $Linklist,
            "limit"=> 10,
            "page" => $page
        ));
        $data = $paginator->getPaginate();		
        $pages = new Pages($data);
        $pages = $pages->show(1);
		$this->view->Linkstatus= WebsiteLink::$Linkstatus;
		$this->view->Linklocation= WebsiteLink::$Linklocation;
		$this->view->Linklogo= WebsiteLink::$Linklogo;
		$this->view->status=$status;
		$this->view->location=$location;
		$this->view->islogo=$islogo;
		$this->view->website_name=$website_name;
		$this->view->website_link=$website_link;
		$this->view->pages      = $pages;
		$this->view->page	    = $page;
		$this->view->data       = $data;
    }
	/*添加友情连接*/
	public function addlinkAction(){
		$sid = $this->session->getId();
        $this->view->sid = $sid;
		$this->view->logo = '';		 
		$this->view->id = '';
       /* $this->view->advertisingmap = $advertisingmap;
        $this->view->user_id = $user_id;
        $this->view->farmname = $farmname;
        $this->view->farmdesc = $farmdesc;
        $this->view->aspectRatiologo=157/77;*/
		
	}
	/*保存新增连接*/
	public function savelinkAction(){
		//$sid = $this->session->getId();
        $website_name	= $this->request->getPost('website_name', 'string', '');
		$website_link	= $this->request->getPost('website_link', 'string', '');
		$logopath		= $this->request->getPost('logopath', 'string', '');
		$order_item 	= $this->request->getPost('order_item', 'int', '');
        $status 		= $this->request->getPost('status', 'int', '');
        $location 		= $this->request->getPost('location', 'int', '');
        $demo 			= $this->request->getPost('demo', 'string', '');	
		if($logopath){
			$islogo=2;
		}else{
			$islogo=1;
		}
		$WebsiteLink= new WebsiteLink();
		$WebsiteLink->website_name	= $website_name;
		$WebsiteLink->website_link	= $website_link;
		$WebsiteLink->logo			= $logopath;	
		$WebsiteLink->islogo		= $islogo;	
		$WebsiteLink->order_item	= $order_item;
		$WebsiteLink->status		= $status;
		$WebsiteLink->location		= $location;			
		$WebsiteLink->demo			= $demo;
		$WebsiteLink->add_time		= time();			
		
        if(!$WebsiteLink->save()){
        	parent::msg('保存失败','');
            // echo "<script>alert('保存失败!');</script>";  
            // exit; 
        }else{
        	parent::msg('保存成功!','/manage/friendlink/linklist');
            // echo "<script>alert('保存成功!');location.href='/manage/friendlink/linklist'</script>";  
            // exit; 
        }
	}
	/*删除连接*/
	public function dellinkAction(){
	 $id	= $this->request->getPost('id', 'int');
		 if($id){
			 $link=WebsiteLink::findFirst("id={$id}");
				if($link){
				 $rs=$link->delete();
					if($rs){
						exit(json_encode(array('code'=> 3, 'result' => '删除成功')));
					}else{
						exit(json_encode(array('code'=> 2, 'result' => '删除失败')));
					}
				}
		 
		}
	
	}
	public function modylinkAction(){	
		$sid = $this->session->getId();	
		$id	= $this->request->get('id', 'int');
		$link=WebsiteLink::findFirst("id={$id}");
		$this->view->link = $link;
		$this->view->sid = $sid;
	}
	/*修改连接*/
	public function domodylinkAction(){
		$id				= $this->request->getPost('id', 'int');
		$website_name	= $this->request->getPost('website_name', 'string', '');
		$website_link	= $this->request->getPost('website_link', 'string', '');
		$logopath		= $this->request->getPost('logopath', 'string', '');
		$order_item 	= $this->request->getPost('order_item', 'int', '');
        $status 		= $this->request->getPost('status', 'int', '');
        $location 		= $this->request->getPost('location', 'int', '');
        $demo 			= $this->request->getPost('demo', 'string', '');	
		if($logopath){
			$islogo=2;
		}else{
			$islogo=1;
		}
		$link=WebsiteLink::findFirst("id={$id}");		
		$WebsiteLink= new WebsiteLink();
		$WebsiteLink->id			= $id;
		$WebsiteLink->website_name	= $website_name;
		$WebsiteLink->website_link	= $website_link;
		$WebsiteLink->logo			= $logopath;	
		$WebsiteLink->islogo		= $islogo;	
		$WebsiteLink->order_item	= $order_item;
		$WebsiteLink->status		= $status;
		$WebsiteLink->location		= $location;			
		$WebsiteLink->demo			= $demo;
		$WebsiteLink->add_time		= $link->add_time;
		$WebsiteLink->last_update_time = time();			
		
        if(!$WebsiteLink->save()){
        	parent::msg('修改失败!','');
            // echo "<script>alert('修改失败!');</script>";  
            // exit; 
        }else{
			parent::msg('修改成功!','/manage/friendlink/linklist');
            // echo "<script>alert('修改成功!');location.href='/manage/friendlink/linklist'</script>";  
            // exit; 
        }
	}
	
	
}