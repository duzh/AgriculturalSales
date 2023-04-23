<?php
namespace Mdg\Member\Controllers;
use Phalcon\Mvc\Controller;
use Mdg\Models\Users as Users;
use Mdg\Models\ShopComments as ShopComments;
use Mdg\Models\AreasFull as mAreas;
use Mdg\Models as M;
class ControllerDialog extends Controller
{
    
    public function initialize() 
    {
        $users = $this->session->user;
        
        if (!$users) 
        {
            $_url = $_SERVER['REQUEST_URI'];
            $this->response->redirect("dlogin/index?ref={$_url}", false, 301)->sendHeaders();
            die;
        }

        /* 检测是否完善资料 */
        $purchasecatecount=M\UserAttention::count("user_id={$users['id']}");
        $users = Users::findFirstByid($users['id']);
        $address = mAreas::checkarea($users->areas);
        $member_info =$this->request->getPost('member_info', 'int', 0);
        if(isset($member_info)&&$member_info){
            if (!$users->areas || !$users->ext->name || !$address || !$users->ext->address) 
            {
               echo "<script>alert('请完善个人信息');location.href='/member/perfect/index'</script>";die;
            }
        }else{
            if (!$users->areas || !$users->ext->name  || !$address ) 
            {
                die("<div class='tk-tips' style='text-align:center; margin-top:60px;'>请完善个人信息</div>");
            }
        }
        $this->view->quotation = '';
        $this->view->ordercount = '';
        $this->view->title = '';
        $this->view->is_kx = 0;
        $this->view->keywords = '';
        $this->view->descript = '';
    }
    
    public function getUserID() 
    {
        $user = $this->session->user;
        return $user['id'];
    }
    
    public function getUserName() 
    {
        $user = $this->session->user;
        return $user['mobile'];
    }
}
