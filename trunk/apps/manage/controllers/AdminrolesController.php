<?php
namespace Mdg\Manage\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Mdg\Models as M;
use Lib\Func as F;
use Lib\Pages as Pages;
class AdminrolesController extends ControllerMember
{

    /**
     * 角色列表
     */
    public function indexAction()
    {
        $page = $this->request->get('page', 'int', 1);
        $rolename = $this->request->get('rolename', 'string', '');
        $description = $this->request->get('description', 'string', '');
        $where = array(" 1=1 ");
        if($rolename) {
            $where[] = "rolename like '%{$rolename}%'";
        }

        if($description) {
            $where[] = "description like '%{$description}%'";
        }

        $where = array(implode(' and ', $where));
       
        $AdminRoles = M\AdminRoles::find($where);
        
        $paginator = new Paginator(array(
            "data" => $AdminRoles,
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
     * 新增角色
     */
    public function newAction()
    {
        $action = array();
        $role=M\AdminPermission::find(" display=1 ")->toArray();
        $admin_role= F::groupBy($role,"controller");
        
        // foreach($role as $v){
        //     $action[$v['action']] = '';
        // }
        //print_r(array_keys($action));
        $this->view->admin_role=$admin_role;
        $this->view->controllername=M\AdminRoles::$controllername;
        $this->view->actionername=M\AdminRoles::$actionername;
    }

    /**
     * 编辑角色
     *
     * @param string $id
     */
    public function editAction($id)
    {
         
        if (!$this->request->isPost()) {

            $admin_role = M\AdminRoles::findFirstByid($id);

            if (!$admin_role) {
                parent::msg('dmin_role was not found','/manage/admin_roles/index');
                // $this->flash->error("admin_role was not found");

                // return $this->dispatcher->forward(array(
                //     "controller" => "admin_roles",
                //     "action" => "index"
                // ));
            }
            $role_id=M\AdminRolesPermission::findByrole_id($id)->toArray();
            $permission_id=array();
            $peractions=array();
            if(!empty($role_id)){
                foreach ($role_id as $key => $value) {
                    $permission_id[$value["permission_id"]]=$value["permission_id"];
                }

                $per=implode(",",$permission_id);
                $peraction=M\AdminPermission::find(" permission_id in(".$per.")")->toArray();
                foreach($peraction as $v){
                     $peractions[$v['controller']] = $v['controller'];
                } 
            }
            $role=M\AdminPermission::find("display=1")->toArray();
            $adminrole= F::groupBy($role,"controller");
            $this->view->id             = $admin_role->id;
            $this->view->admin_role     = $adminrole;
            $this->view->peractions     = $peractions;
            $this->view->permission_id  = $permission_id;
            $this->view->controllername = M\AdminRoles::$controllername;
            $this->view->actionername   = M\AdminRoles::$actionername;
            $this->tag->setDefault("id", $admin_role->id);
            $this->tag->setDefault("parent_id", $admin_role->parent_id);
            $this->tag->setDefault("rolename", $admin_role->rolename);
            $this->tag->setDefault("description", $admin_role->description);
            
        }
    }

    /**
     * 新增角色
     */
    public function createAction()
    {
        
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "adminroles",
                "action" => "index"
            ));
        }
        $controller=$this->request->getPost("controller",'string',''); 
        if(empty($controller)){
            parent::msg('请选择权限','/manage/adminroles/new/');
            // echo "<script>alert('请选择权限');location.href='/manage/adminroles/new/'</script>";die;
        } 
        
        $admin_role = new M\AdminRoles();
        $admin_role->rolename    = $this->request->getPost("rolename",'string','');
        $admin_role->description = $this->request->getPost("description",'string','');
        if (!$admin_role->save()) {
            parent::msg($message,'/manage/adminroles/new/');
            // foreach ($admin_role->getMessages() as $message) {
            //     $this->flash->error($message);
            // }

            // return $this->dispatcher->forward(array(
            //     "controller" => "adminroles",
            //     "action" => "new"
            // ));
        }
        
       
        foreach ($_POST["controller"] as $key => $value) {

           $adminrole= new M\AdminRolesPermission();
           $adminrole->role_id       =$admin_role->id;
           $adminrole->permission_id =$value;
           $adminrole->save();
        }
        F::adminlog("添加角色：{$adminrole->role_id}");
        parent::msg('角色添加成功','/manage/adminroles/new/');
        // $this->flash->success("角色添加成功");

        // return $this->dispatcher->forward(array(
        //     "controller" => "adminroles",
        //     "action" => "index"
        // ));

    }

    /**
     * 编辑保存角色
     *
     */
    public function saveAction()
    {
       
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "adminroles",
                "action" => "index"
            ));
        }
        $id = $this->request->getPost("id");
        $controller=$this->request->getPost("controller",'string',''); 
        if(empty($controller)){
            parent::msg('请选择权限',"/manage/adminroles/edit/{$id}");
            // echo "<script>alert('请选择权限');location.href='/manage/adminroles/edit/{$id}'</script>";die;
        } 
       

        $admin_role = M\AdminRoles::findFirstByid($id);
        if (!$admin_role) {
            parent::msg('adminrole does not exist','/manage/adminroles/index');
            // $this->flash->error("adminrole does not exist " . $id);

            // return $this->dispatcher->forward(array(
            //     "controller" => "adminroles",
            //     "action" => "index"
            // ));
        }

        $admin_role->parent_id = $this->request->getPost("parent_id","string",'0');
        $admin_role->rolename = $this->request->getPost("rolename",'string','');
        $admin_role->description = $this->request->getPost("description",'string','');
        

        if (!$admin_role->save()) {
            parent::msg($message,'/manage/admin_roles/edit/'.$id);
            // foreach ($admin_role->getMessages() as $message) {
            //     $this->flash->error($message);
            // }

            // return $this->dispatcher->forward(array(
            //     "controller" => "admin_roles",
            //     "action" => "edit",
            //     "params" => array($admin_role->id)
            // ));
        }
        $adminrole=M\AdminRolesPermission::findByrole_id($id);
        $adminrole->delete();
        foreach ($_POST["controller"] as $key => $value) {

           $adminrole= new M\AdminRolesPermission();
           $adminrole->role_id       =$admin_role->id;
           $adminrole->permission_id =$value;
           $adminrole->save();
        }
        F::adminlog("修改角色：{$adminrole->role_id}");
        parent::msg('修改成功','/manage/adminroles/index');
        // $this->flash->success("修改成功");

        // return $this->dispatcher->forward(array(
        //     "controller" => "adminroles",
        //     "action" => "index"
        // ));

    }

    /**
     * 删除角色
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $admin_role = M\AdminRoles::findFirstByid($id);
        if (!$admin_role) {
            parent::msg('管理员未找到','/manage/adminroles/index');
            // $this->flash->error("管理员未找到");

            // return $this->dispatcher->forward(array(
            //     "controller" => "adminroles",
            //     "action" => "index"
            // ));
        }

        if (!$admin_role->delete()) {
            parent::msg($message,'/manage/adminroles/search');
            // foreach ($admin_role->getMessages() as $message) {
            //     $this->flash->error($message);
            // }

            // return $this->dispatcher->forward(array(
            //     "controller" => "adminroles",
            //     "action" => "search"
            // ));
        }
        F::adminlog("修改角色：{$adminrole->rolename}");
        parent::msg('删除成功','/manage/adminroles/index');
        // $this->flash->success("删除成功");

        // return $this->dispatcher->forward(array(
        //     "controller" => "adminroles",
        //     "action" => "index"
        // ));
    }

}
