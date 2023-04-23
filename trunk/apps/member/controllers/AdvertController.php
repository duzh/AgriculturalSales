<?php
namespace Mdg\Member\Controllers;
use Mdg\Models as M;
	/**
	 * 可信农场管理
	 *
	 */
class AdvertController extends ControllerKx{

	/**
	 * 宣传图
	 * @return  [html]
	 */
	public function indexAction(){
		$user_id=$this->getUserID();
		$sid = $this->session->getId();
		M\TmpFile::clearOld($sid);
		$crediblefarmpicture = M\CredibleFarmPicture::find("user_id = {$user_id} and type = 4")->toArray();
		$count = M\CredibleFarmPicture::count("user_id = {$user_id} and type = 4");
		$crediblefarminfo = M\CredibleFarmInfo::findFirst("user_id = {$user_id}");
		$this->view->url = !empty($crediblefarminfo->url) ? $crediblefarminfo->url : 'www';
		$this->view->count = $count;
		$this->view->crediblefarmpicture = $crediblefarmpicture;
		$this->view->sid = $sid;
		$this->view->title = '可信农场管理-宣传图';
	}
	/**
	 * 宣传图保存
	 * @return [type] [description]
	 */
	public function saveAction(){
		$sid = $this->session->getId();
		$user_id = $this->getUserID();
		$user_name=$this->session->user['name'];
		$tmpfile=M\TmpFile::find("type = 36 and sid = '{$sid}'")->toArray();
		foreach ($tmpfile as $key => $value) {
			$crediblefarmpicture = new M\CredibleFarmPicture();
			$crediblefarmpicture->user_id = $user_id;
			$crediblefarmpicture->picture_path = $value['file_path'];
			$crediblefarmpicture->title  = '';
			$crediblefarmpicture->desc = '';
			$crediblefarmpicture->type = 4;
			$crediblefarmpicture->status = 1;
			$crediblefarmpicture->add_time = time();
			$crediblefarmpicture->last_update_time = time();
			$crediblefarmpicture->picture_time = time();
			if(!$crediblefarmpicture->save()){
				print_r($crediblefarmpicture->getMessages());die;
			}
		}
	        /*  清除 */
	    M\TmpFile::clearOld($sid);
		echo '<script>alert("提交成功");location.href="/member/advert/index"</script>';exit;
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
}