<?php
namespace Mdg\Member\Controllers;
use Mdg\Models as M;
use Lib as L;
	/**
	 * 可信农场管理
	 *
	 */
class FarmController extends ControllerKx{

	/**
	 * 农场介绍
	 * @return  [html]
	 */
	public function indexAction(){
		$user_id=$this->getUserID();
		$sid = $this->session->getId();
		$crediblefarminfo=M\CredibleFarmInfo::findFirstByuser_id($user_id);
		$credible_farm_picture = M\CredibleFarmPicture::find("user_id = {$user_id} and type = 0")->toArray();
		$this->view->crediblefarminfo = $crediblefarminfo;
		$custom_content=$crediblefarminfo->custom_content;
		$this->tag->setDefault("custom_content", $custom_content);
		$this->view->url = !empty($crediblefarminfo->url) ? $crediblefarminfo->url : 'www';
		$this->view->credible_farm_picture = $credible_farm_picture;
		$this->view->sid = $sid;
		$this->view->title = '可信农场管理-农场介绍';
	}
	/**
	 * 农场介绍新增保存
	 * @return [type] [description]
	 */
	public function saveAction(){
		$sid = $this->session->getId();
		$user_id = $this->getUserID();
		$user_name=$this->session->user['name'];
		$title = L\Validator::replace_specialChar($this->request->getPost('title','string',''));
		$desc = L\Validator::replace_specialChar($this->request->getPost('desc','string',''));

		$tempfile=M\TmpFile::findFirst("type = 37 and sid = '{$sid}'");
		$credible_farm_picture=new M\CredibleFarmPicture();
		$credible_farm_picture->user_id =$user_id;
		$credible_farm_picture->picture_path = $tempfile->file_path;
		$credible_farm_picture->title = $title;
		$credible_farm_picture->desc = $desc;
		$credible_farm_picture->status = 1;
		$credible_farm_picture->type = 0;
		$credible_farm_picture->add_time = time();
		$credible_farm_picture->last_update_time = time();
		$credible_farm_picture->picture_time =time();
		$credible_farm_picture->save();
	    /*  清除 */
	    M\TmpFile::clearOld($sid);
		echo '<script>alert("提交成功");parent.location.href="/member/farm/index";</script>';exit;
	}

	/**
	 * 自定义内容
	 * @return [type] [description]
	 */
	public function upsaveAction(){
		$sid = $this->session->getId();
		$user_id = $this->getUserID();
		$medesc =$this->request->getPost('News');
		$crediblefarminfo=M\CredibleFarmInfo::findFirstByuser_id($user_id);
		if(!$crediblefarminfo){
			$crediblefarminfo=new M\CredibleFarmInfo();
		}
			$crediblefarminfo->custom_content = $medesc;
			$crediblefarminfo->last_update_time =time();
			$crediblefarminfo->save();
		echo '<script>alert("提交成功");location.href="/member/farm/index"</script>';exit;
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
	public function add_descriptionAction(){

		$sid = $this->session->getId();
		$this->view->sid = $sid;
	}
}