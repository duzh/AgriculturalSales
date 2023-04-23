<?php
namespace Mdg\Member\Controllers;
use Mdg\Models as M;
use Lib\Func as Func;
use Lib\Validator as Validator;
	/**
	 * 可信农场管理
	 *
	 */
class CredibleController extends ControllerKx{

	/**
	 * 农场基本信息
	 * @return  [html]
	 */
	public function farminfoAction(){
		$user_id=$this->getUserID();
		$sid = $this->session->getId();

		$userinfo=M\UserInfo::findFirst("user_id = {$user_id} and credit_type = 8 and status = 1");
		if(!$userinfo){
			echo "<script>alert('来源错误');location.href='/member/perfect/index'</script>";
		}

		$userfarm=M\UserFarm::findFirstBycredit_id($userinfo->credit_id);
		$crediblefarminfo=M\CredibleFarmInfo::findFirstByuser_id($user_id);

		if(!empty($crediblefarminfo)){
			$farm_name = $userfarm->farm_name;
			$describe  = $crediblefarminfo->desc;
			$logo_pic  = $crediblefarminfo->logo_pic;
			$farm_id   = $crediblefarminfo->id;
			if(!$describe){
				$describe  = $userfarm->describe;
			}
		}else{
			if(!empty($userfarm)){
				$farm_name = $userfarm->farm_name;
				$describe  = $userfarm->describe;
				$logo_pic  = '';
				$farm_id   = 0;
			}else{
				$farm_name = '';
				$describe  = '';
				$logo_pic  = '';
				$farm_id   = 0;
			}
		}
		$this->view->farm_id =$farm_id;
		$this->view->logo_pic = $logo_pic;
		$this->view->img_pic=$crediblefarminfo->img_pic;
		$this->tag->setDefault("farm_name", $farm_name);
		$this->tag->setDefault("desc", $describe);
		$this->view->crediblefarminfo = $crediblefarminfo;
		$this->view->url = !empty($crediblefarminfo->url) ? $crediblefarminfo->url : 'www';
		$this->view->sid = $sid;
        $this->view->type=32;
        $this->view->aspectRatio=0;
		$this->view->title = '可信农场管理-农场基本信息';
		
	}
	/**
	 * 农场基本信息保存
	 * @return [type] [description]
	 */
	public function farminfosaveAction(){
		$sid = $this->session->getId();

		$user_id = $this->getUserID();
		$user_name=$this->session->user['name'];
		$desc=Validator::replace_specialChar($this->request->getPost('desc','string',''));
		$img=M\TmpFile::findFirst("sid = '{$sid}' and type = 35");
		$image=M\TmpFile::findFirst("sid = '{$sid}' and type = 43");
		$crediblefarminfo=M\CredibleFarmInfo::findFirstByuser_id($user_id);

		if(!$crediblefarminfo){
			$crediblefarminfo = new M\CredibleFarmInfo();
		}
			$crediblefarminfo->user_id = $user_id;
			$crediblefarminfo->user_name = $user_name;
			$crediblefarminfo->desc = $desc;
			if(!empty($img->file_path)){
				$crediblefarminfo->logo_pic = $img->file_path;
			}
			if(!empty($image->file_path)){
				$crediblefarminfo->img_pic = $image->file_path;
			}
			$crediblefarminfo->add_time = time();
			$crediblefarminfo->last_update_time = time();
			$crediblefarminfo->status = 0;
			$crediblefarminfo->is_home_page = 0;
			$crediblefarminfo->home_page_order =1;
			$crediblefarminfo->save();
	        /*  清除 */
	        M\TmpFile::clearOld($sid);
			echo '<script>alert("提交成功");location.href="/member/credible/farminfo"</script>';exit;
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
        
        $image = M\CredibleFarmInfo::findFirst("id={$id}");
            if ($image) 
            {
            	$image->logo_pic = '';
            	$image->last_update_time = time();
            	$image->save();
            }
        die(json_encode($rs));
    }
    public function testAction(){
    	
    }
    public function addfarmAction($type=32,$width=157,$height=77){
         
         $sid = md5(session_id());
         $this->view->sid = $sid;
         $this->view->width=$width;
         $this->view->height=$height;
         $this->view->type=$type;
    }
    public function cropperAction($type=32){
 
    	 $sid = md5(session_id());
         $this->view->sid = $sid;
         $this->view->type=$type;
         $this->view->aspectRatio=0;
    }

}