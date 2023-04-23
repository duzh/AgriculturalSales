<?php
namespace Mdg\Manage\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Mdg\Models as M;
use Lib\Func as F;
use Lib\Member as Member,
    Lib\Auth as Auth,
    Lib\Utils as Utils;
use Lib\Pages as Pages;
class AdminController extends ControllerMember
{

    /**
     * 管理员列表
     */
    public function indexAction()
    {  
        $page = $this->request->get('page', 'int', 1);
        $username = $this->request->get('username', 'string', '');
        $stime = strtotime($this->request->get('start_time', 'string', ''));
        $etime = strtotime($this->request->get('end_time', 'string', ''));

        $where = array(" 1=1 ");
        if($username) {
            $where[] = "  username like '%{$username}%'";
        }

        if($stime&&$etime) {
            $where[] = "  UNIX_TIMESTAMP(createtime) between  $stime and $etime ";
        }
             
        $where = array(implode(' and ', $where));
        $where[]= " ORDER BY createtime desc ";
       
        $Admin = M\Admin::find($where);
        
        $paginator = new Paginator(array(
            "data" => $Admin,
            "limit"=> 10,
            "page" => $page
        ));
        $data = $paginator->getPaginate();
        $pages = new Pages($data);
        $pages = $pages->show(1);
        $this->view->data       = $data;
        $this->view->pages      = $pages;
        
    }

    
    /**
     * 新增管理员
     */
    public function newAction()
    {
         $role=M\AdminRoles::find();
         $this->view->role=$role;
    }

    /**
     * 编辑管理员
     *
     * @param string $id
     */
    public function editAction($id)
    {
        
        if (!$this->request->isPost()) {

            $admin = M\Admin::findFirstByid($id);
            if (!$admin) {
                parent::msg('管理员未找到','/manage/admin/index');
            }
            $role=M\AdminRoles::find();
            $this->view->id = $admin->id;
            $this->view->role=$role;
            $this->view->role_id=$admin->role_id;
            $this->view->username=$admin->username;
            $this->view->lastlogintime=$admin->lastlogintime;
            $this->view->lastloginip=$admin->lastloginip;
            $this->view->logincount=$admin->logincount;
            $this->view->createtime=$admin->createtime;
            $this->tag->setDefault("id", $admin->id);
        }
    }

    /**
     * 新增管理员
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "admin",
                "action" => "index"
            ));
        }
        $salt=Auth::random(6,1);
        $password=$this->request->getPost("password",'string','');
        $admin = new M\Admin();

        $admin->username = $this->request->getPost("username",'string','');
        $admin->salt =$salt;
        $admin->password = M\Admin::encodePwd($password,$salt);
        $admin->lastlogintime = date("Y-m-d H:i:s",time());
        $admin->lastloginip =Utils::getIP();
        $admin->logincount = $this->request->getPost("logincount",'int',0);
        $admin->createtime = date("Y-m-d H:i:s",time());
        $admin->role_id = $this->request->getPost("role_id");
        if (!$admin->save()) {
            foreach ($admin->getMessages() as $message) {
                $this->flash->error($message);
            }
            return $this->dispatcher->forward(array(
                "controller" => "admin",
                "action" => "new"
            ));
        }
        F::adminlog("添加管理员：{$admin->username}");
        parent::msg('管理员添加成功','/manage/admin/index');

    }

    /**
     * 管理员编辑保存
     *
     */
    public function saveAction()
    {
        
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "admin",
                "action" => "index"
            ));
        }

        $id = $this->request->getPost("id");
        $admin = M\Admin::findFirstByid($id);
        if (!$admin) {
            parent::msg('管理员不存在','/manage/admin/index');
            // $this->flash->error("管理员不存在" . $id);

            // return $this->dispatcher->forward(array(
            //     "controller" => "admin",
            //     "action" => "index"
            // ));
        }
        // $admin->username = $this->request->getPost("username");
        // $admin->password = $this->request->getPost("password");
        // $admin->salt = $this->request->getPost("salt");
        // $admin->lastlogintime = $this->request->getPost("lastlogintime");
        // $admin->lastloginip = $this->request->getPost("lastloginip");
        // $admin->logincount = $this->request->getPost("logincount");
        // $admin->createtime = $this->request->getPost("createtime");
        $admin->role_id = $this->request->getPost("role_id");
        if (!$admin->save()) {
            parent::msg($message,'/manage/admin/edit/'.$id);
            // foreach ($admin->getMessages() as $message) {
            //     $this->flash->error($message);
            // }

            // return $this->dispatcher->forward(array(
            //     "controller" => "admin",
            //     "action" => "edit",
            //     "params" => array($admin->id)
            // ));
        }
        F::adminlog("修改管理员：{$admin->id}");
        parent::msg('管理员修改成功','/manage/admin/index');
        // $this->flash->success("管理员修改成功");

        // return $this->dispatcher->forward(array(
        //     "controller" => "admin",
        //     "action" => "index"
        // ));

    }

    /**
     * 管理员删除
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
       
        $admin = M\Admin::findFirstByid($id);
        if (!$admin) {
            parent::msg('管理员未找到','/manage/admin/index');
            // $this->flash->error("管理员未找到");
            // return $this->dispatcher->forward(array(
            //     "controller" => "admin",
            //     "action" => "index"
            // ));
        }

        if (!$admin->delete()) {
            parent::msg($message,'/manage/admin/search');
            // foreach ($admin->getMessages() as $message) {
            //     $this->flash->error($message);
            // }

            // return $this->dispatcher->forward(array(
            //     "controller" => "admin",
            //     "action" => "search"
            // ));
        }
        parent::msg('删除成功','/manage/admin/index');
        // $this->flash->success("删除成功");
        // return $this->dispatcher->forward(array(
        //     "controller" => "admin",
        //     "action" => "index"
        // ));
    }
    /**
     * 检测管理员
     * @return [type] [description]
     */
     public function checkAction(){
        $mobile = $this->request->getPost('username','string','');
        if(!empty($mobile)){
            $title=M\Admin::findFirstByusername($mobile);
            $msg = array();
            if(!empty($title)){  
                $msg['error'] = '管理员已存在!';   
            }else{
                  $msg['ok'] = '可以添加'; 
            }
            
        }else{
            $msg['error'] = '用户不存在!'; 
        }
        echo json_encode($msg);die; 
    }

}
