<?php
namespace Mdg\Member\Controllers;
use Mdg\Models as M;
use Lib as L;

class PicturewallController extends ControllerKx
{
    
    public function indexAction() 
    {
        $user_id=$this->getUserID();
        $sid = $this->session->getId();
        $page = $this->request->get('p', 'int', 1);
        $total = $this->request->get('total', 'int', 0);
        $page = intval($page)>0 ? intval($page) : 1;
        if($page>$total&&$total!=0){
            $page=$total;
        }
        $where=" user_id = {$user_id} and type = 3 ";
        $page_size=10;
        $is_show_message = false;
        $credible_farm_picture = M\CredibleFarmPicture::getlist($page,$page_size,$where);
       
        $crediblefarminfo=M\CredibleFarmInfo::findFirstByuser_id($user_id);
        $this->view->url = !empty($crediblefarminfo->url) ? $crediblefarminfo->url : 'www';

        $this->view->credible_farm_picture = $credible_farm_picture;
        $this->view->show_message = $is_show_message;
        $this->view->sid = $sid;
        $this->view->total_page = $sid;
        $this->view->title = '可信农场管理-图片墙';
       
    }
    public function upfileAction(){
      
      $sid =$this->session->getId();
      $this->view->sid = $sid;
    }
    public function deleteAction($id){
        $tempfile=M\CredibleFarmPicture::findFirst(" id={$id} ");
        if($tempfile){
            $tempfile->delete();
        }
        echo '<script>alert("删除成功");location.href="/member/picturewall/index"</script>';exit;
    }
    public function createAction(){
      
        $title= L\Validator::replace_specialChar($this->request->getPost("title", 'string', ''));
        $contents= L\Validator::replace_specialChar($this->request->getPost("contents", 'string',''));
        $path= $this->request->getPost("path", 'string','');

        if(!$title||!$contents||!$path){
            die("各项不能为空");
        }
        $sid = $this->session->getId();
        
        $user_id = $this->getUserID();
        $user_name=$this->session->user['name'];
        $title = L\Validator::replace_specialChar($this->request->getPost('title','string',''));
        $desc = L\Validator::replace_specialChar($this->request->getPost('desc','string',''));
       
        $tempfile=M\TmpFile::findFirst("type = 39 and sid = '{$sid}'");
        if(!$tempfile){
             echo '<script>alert("图片未上传");location.href="/member/picturewall/index"</script>';exit;
        }
        $credible_farm_picture=new M\CredibleFarmPicture();
        $credible_farm_picture->user_id =$user_id;
        $credible_farm_picture->picture_path = $tempfile->file_path;
        $credible_farm_picture->title = $title;
        $credible_farm_picture->desc = $contents;
        $credible_farm_picture->status = 1;
        $credible_farm_picture->type =  3;
        $credible_farm_picture->add_time = time();
        $credible_farm_picture->last_update_time = time();
        $credible_farm_picture->picture_time =time();
        $credible_farm_picture->save();
        /*  清除 */
        M\TmpFile::clearOld($sid);
        echo '<script>alert("新增成功");parent.location.href="/member/picturewall/index"</script>';exit;
    }

    public function memberindexAction(){
        $sid = $this->session->getId();
        $user_id = $this->getUserID();
        $credible_farm_picture = M\CredibleFarmPicture::find("user_id = {$user_id} and type = 3 order by picture_time")->toArray();
        $count = M\CredibleFarmPicture::count("user_id = {$user_id} and type = 3 order by picture_time");
        $this->view->count =$count;
        $this->view->credible_farm_picture = json_encode($credible_farm_picture);
    }
}


