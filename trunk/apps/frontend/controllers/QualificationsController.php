<?php
namespace Mdg\Frontend\Controllers;
use Mdg\Models as M;
use Lib as L;
	/**
	 * 可信农场管理
	 *
	 */
class QualificationsController extends ControllerKx{

	public function memberindexAction(){
		$sid = $this->session->getId();
		$user_id = $this->session->farm_user_id;
		$credible_farm_picture = M\CredibleFarmPicture::find("user_id = {$user_id} and type = 2 order by picture_time")->toArray();

		if($credible_farm_picture){
			$credible_farm_picture=L\Arrays::groupBy($credible_farm_picture,'picture_time');
		}
		$this->view->title = '资质认证';
		$this->view->credible_farm_picture = $credible_farm_picture;

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