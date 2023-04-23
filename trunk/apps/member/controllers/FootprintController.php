<?php
namespace Mdg\Member\Controllers;
use Mdg\Models as M;
use Lib\Pages as Pages;
use Lib as L;
	/**
	 * 可信农场管理
	 *
	 */
class FootprintController extends ControllerKx{

	/**
	 * 发展足迹
	 * @return  [html]
	 */
	public function indexAction(){
		$page        = $this->request->get('p', 'int', 1);
		$page = intval($page)>0 ? intval($page) : 1;
		$page_size = 5;
		$user_id=$this->getUserID();
		$sid = $this->session->getId();
		$credible_farm_picture = M\CredibleFarmPicture::find("user_id = {$user_id} and type = 1")->toArray();
		$total=count($credible_farm_picture);
		$offst = intval(($page - 1) * $page_size);
		$total_count=ceil($total / $page_size);
		$sql="select * from credible_farm_picture where user_id = {$user_id} and type = 1 ORDER BY id DESC limit {$offst} , {$page_size} ";
		$credible_farm_picture=$this->db->fetchAll($sql,2);
		$crediblefarminfo=M\CredibleFarmInfo::findFirstByuser_id($user_id);
		

		$pages['total_pages'] = ceil($total / $page_size);
		$pages['current'] = $page;
		$pages['total'] = $total;
		$pages = new Pages($pages);
        $pages = $pages->show(2);
		$this->view->url = !empty($crediblefarminfo->url) ? $crediblefarminfo->url : 'www';		
		$this->view->credible_farm_picture = $credible_farm_picture;
		$this->view->sid = $sid;
		$this->view->title = '可信农场管理-发展足迹';
		$this->view->total_count = $total_count;
		$this->view->pages = $pages;
	}
	/**
	 * 发展足迹新增保存
	 * @return [type] [description]
	 */
	public function saveAction(){
		
		$sid = $this->session->getId();
		$user_id = $this->getUserID();
		$user_name=$this->session->user['name'];
		$title = L\Validator::replace_specialChar($this->request->getPost('title','string',''));
		$desc = L\Validator::replace_specialChar($this->request->getPost('content','string',''));
		$year1 = L\Validator::replace_specialChar($this->request->getPost('year1','string',''));
		$month1 =L\Validator::replace_specialChar($this->request->getPost('month1','string',''));
		$day1  = L\Validator::replace_specialChar($this->request->getPost('day1','string',''));
		$time = strtotime($year1.$month1.$day1);

		$tempfile=M\TmpFile::findFirst("type = 38 and sid = '{$sid}'");
		$credible_farm_picture=new M\CredibleFarmPicture();
		$credible_farm_picture->user_id =$user_id;
		if($tempfile){
			$credible_farm_picture->picture_path = $tempfile->file_path;
		}
		$credible_farm_picture->title = $title;
		$credible_farm_picture->desc = $desc;
		$credible_farm_picture->status = 1;
		$credible_farm_picture->type = 1;
		$credible_farm_picture->add_time = time();
		$credible_farm_picture->last_update_time = time();
		$credible_farm_picture->picture_time =$time;
		$credible_farm_picture->save();
	    /*  清除 */
	    M\TmpFile::clearOld($sid);
		echo '<script>alert("提交成功");parent.location.href="/member/footprint/index"</script>';exit;
	}

	public function upsaveAction(){
		$sid = $this->session->getId();
		$user_id = $this->getUserID();
		$medesc = htmlspecialchars($this->request->getPost('News'));
		$crediblefarminfo=M\CredibleFarmInfo::findFirstByuser_id($user_id);
		if(!$crediblefarminfo){
			$crediblefarminfo=new M\CredibleFarmInfo();
		}
			$crediblefarminfo->custom_content = $medesc;
			$crediblefarminfo->last_update_time =time();
			$crediblefarminfo->save();
		echo '<script>alert("提交成功");location.href="/member/farm/index"</script>';exit;
	}
	public function add_developFootAction(){
		$sid = $this->session->getId();
		$this->view->sid = $sid;
	}
}