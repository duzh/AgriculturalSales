<?php
namespace Mdg\Member\Controllers;
use Mdg\Models as M;
use Lib as L;
	/**
	 * 可信农场管理
	 *
	 */
class QualificationsController extends ControllerKx{

	/**
	 * 资质认证
	 * @return  [html]
	 */
	public function indexAction(){
		$page = $this->request->get('p', 'int', 1);
		$user_id=$this->getUserID();
		$sid = $this->session->getId();
		$page = intval($page)>0 ? intval($page) : 1;
		$psize = 10;
        $offst = ($page - 1 ) * $psize ;
		$crediblefarminfo=M\CredibleFarmInfo::findFirstByuser_id($user_id);
		$credible_farm_picture_count = M\CredibleFarmPicture::find("user_id = {$user_id} and type = 2")->toArray();
		$total =count($credible_farm_picture_count);
		$total_count = ceil($total / $psize);
		$pages['total_pages'] = ceil($total / $psize);
        $pages['current'] = $page;
        $pages['total'] = $total;
        $credible_farm_picture = M\CredibleFarmPicture::find("user_id = {$user_id} and type = 2 limit {$offst},{$psize}")->toArray();
        $pages = new L\Pages($pages);
        $pages = $pages->show(2);
		$this->view->url = !empty($crediblefarminfo->url) ? $crediblefarminfo->url : 'www';
		$this->view->crediblefarminfo = $crediblefarminfo;
		$this->view->credible_farm_picture = $credible_farm_picture;
		$this->view->sid   = $sid;
		$this->view->total = $total;
		$this->view->total_count = $total_count;
		$this->view->pages = $pages;
		$this->view->page  = $page;
		$this->view->title = '可信农场管理-资质认证';
	}
	/**
	 * 资质认证新增保存
	 * @return [type] [description]
	 */
	public function saveAction(){
        
		$sid = $this->session->getId();
		$user_id = $this->getUserID();
		$user_name=$this->session->user['name'];
		$title = L\Validator::replace_specialChar($this->request->getPost('title','string',''));
		$year1 = L\Validator::replace_specialChar($this->request->getPost('year1','string',''));
		$month1 =L\Validator::replace_specialChar($this->request->getPost('month1','string',''));
		$day1  = L\Validator::replace_specialChar($this->request->getPost('day1','string',''));
		$time = strtotime($year1.$month1.$day1);

		$tempfile=M\TmpFile::findFirst("type = 40 and sid = '{$sid}'");
		$credible_farm_picture=new M\CredibleFarmPicture();
		$credible_farm_picture->user_id =$user_id;
		if($tempfile){
			$credible_farm_picture->picture_path = $tempfile->file_path;
		}
		$credible_farm_picture->title = $title;
		$credible_farm_picture->desc = '';
		$credible_farm_picture->status = 1;
		$credible_farm_picture->type = 2;
		$credible_farm_picture->add_time = time();
		$credible_farm_picture->last_update_time = time();
		$credible_farm_picture->picture_time =$time;
		$credible_farm_picture->save();
	    /*  清除 */
	    M\TmpFile::clearOld($sid);
		echo '<script>alert("提交成功");parent.location.href="/member/qualifications/index"</script>';exit;
	}

	/**
	 * 删除图片
	 * @return [type] [description]
	 */
    public function deleteImgAction() 
    {
        $rs = array(
            'state' => true,
            'msg' => '删除成功！'
        );

        $id = $this->request->get('id', 'int', 0);
        
        $image = M\CredibleFarmPicture::findFirst("id={$id}");
            if ($image) 
            {
            	$image->delete();
            }
        die(json_encode($rs));
    }
    public function addquaAction(){
    	
       $sid = $this->session->getId();
        $this->view->sid = $sid;
    }
}