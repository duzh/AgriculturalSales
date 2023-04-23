<?php
namespace Mdg\Member\Controllers;
use Mdg\Models as M;
use Lib\Pages as Pages;
use Lib as L;
	/**
	 * 可信农场管理
	 *
	 */
class ProductprocessController extends ControllerKx{

	/**
	 * 产品种植过程列表
	 * @return  [html]
	 */
	public function indexAction(){
		$page        = $this->request->get('p', 'int', 1);
		$page_size = 10;
		$page = intval($page)>0 ? intval($page) : 1;
		$user_id=$this->getUserID();
		$sid = $this->session->getId();
		$crediblefarmgoodsplant=M\CredibleFarmGoodsplant::find("is_delete = 0 and user_id = {$user_id}")->toArray();
		$total=count($crediblefarmgoodsplant);
		$offst = intval(($page - 1) * $page_size);
		$total_count = ceil($total / $page_size);
		$sql="select * from credible_farm_goodsplant where user_id = {$user_id} and is_delete = 0 ORDER BY goods_id DESC limit {$offst} , {$page_size} ";
		$crediblefarmgoodsplant=$this->db->fetchAll($sql,2);
		$crediblefarminfo=M\CredibleFarmInfo::findFirstByuser_id($user_id);
		$pages['total_pages'] = ceil($total / $page_size);
		$pages['current'] = $page;
		$pages['total'] = $total;
		$pages = new Pages($pages);
        $pages = $pages->show(2);
		$this->view->url = !empty($crediblefarminfo->url) ? $crediblefarminfo->url : 'www';
		$this->view->crediblefarmgoodsplant = $crediblefarmgoodsplant;
		$this->view->current = 1;
		$this->view->sid = $sid;
		$this->view->title = '可信农场管理-产品种植过程';
		$this->view->pages = $pages;
		$this->view->total_count = $total_count;
        $this->view->p			= $page;
	}
	/**
	 * 产品种植过程新增列表保存
	 * @return [type] [description]
	 */
	public function saveAction(){
		$sid = $this->session->getId();
		$user_id = $this->getUserID();
		$user_name=$this->session->user['name'];
		$goods_name = L\Validator::replace_specialChar($this->request->getPost('goods_name','string',''));
		$crediblefarmgoodsplant=new M\CredibleFarmGoodsplant();
		$crediblefarmgoodsplant->user_id =$user_id;
		$crediblefarmgoodsplant->goods_name = $goods_name;
		$crediblefarmgoodsplant->add_time = time();
		$crediblefarmgoodsplant->is_delete = 0;
		$crediblefarmgoodsplant->save();
		echo '<script>alert("提交成功");location.href="/member/productprocess/index"</script>';exit;
	}

	/**
	 * 新增种植过程
	 * @return [type] [description]
	 */
	public function listAction($goods_id = 0,$goods_name = ''){	
        $page        = $this->request->get('p', 'int', 1);
		$total       = $this->request->get('total', 'int', 0);
		$page_size = 10;
		if ($page<=0) 
        {
            $page = 1;
        }
        if($page>$total&&$total!=0){
            $page=$total;
        }
		$sid 		= $this->session->getId();
		$user_id	= $this->getUserID();
		$where=" goods_id = {$goods_id} and is_delete = 0 and user_id = {$user_id}";
		//$goods_id	= $this->request->get('goods_id','int');
		$crediblefarmgoodsplant=M\CredibleFarmGoodsplant::findFirst("goods_id = {$goods_id} and goods_name = '{$goods_name}' and user_id = {$user_id}");
		
		if(!$crediblefarmgoodsplant){
			echo '<script>alert("来源错误");location.href="/member/productprocess/index"</script>';exit;
		}

        $total=M\CredibleFarmPlant::count($where);
		
		$offst = intval(($page - 1) * $page_size);
             
		$crediblefarmplant=M\CredibleFarmPlant::find(" {$where}  ORDER BY add_time DESC limit {$offst} , {$page_size} ")->toArray();
		
		$pages['total_pages'] = ceil($total / $page_size);
		$pages['current'] = $page;
		$pages['total'] = $total;
		
		$pages = new Pages($pages);

        $pages = $pages->show(2);

		$this->view->crediblefarmplant  =$crediblefarmplant;
		$this->view->sid = $sid;
		$this->view->total_count=ceil($total / $page_size);
		$this->view->pages = $pages;
		$this->view->goods_name = $goods_name;
		$this->view->goods_id = $goods_id;
		$this->view->title = '可信农场管理-产品种植过程';
	}

	public function saveaddAction(){
		
		$sid = $this->session->getId();
		$user_id = $this->getUserID();
		$user_name=$this->session->user['name'];
		$title = L\Validator::replace_specialChar($this->request->getPost('title','string',''));
		$desc = L\Validator::replace_specialChar($this->request->getPost('content','string',''));
		$year1 = L\Validator::replace_specialChar($this->request->getPost('year1','string',''));
		$month1 =L\Validator::replace_specialChar($this->request->getPost('month1','string',''));
		$day1  = L\Validator::replace_specialChar($this->request->getPost('day1','string',''));
		$goods_id = $this->request->getPost('goods_id','int',0);
		$goods_name = L\Validator::replace_specialChar($this->request->getPost('goods_name','string',''));
		$time = strtotime($year1.$month1.$day1);

		$tempfile=M\TmpFile::findFirst("type = 41 and sid = '{$sid}'");

		$credible_farm_plant=new M\CredibleFarmPlant();
		$credible_farm_plant->user_id =$user_id;
		if($tempfile){
			$credible_farm_plant->picture_path = $tempfile->file_path;
		}
		$type='1';
		$credible_farm_plant->goods_id        	= $goods_id;
		$credible_farm_plant->title          	= $title;
		$credible_farm_plant->desc      		= $desc;
		$credible_farm_plant->add_time 			= time();
		$credible_farm_plant->last_update_time 	= time();
		$credible_farm_plant->is_delete 		= 0;
		$credible_farm_plant->picture_time		= $time;
		$credible_farm_plant->$type				= $type;
		$credible_farm_plant->save();
	    /*  清除 */
	    M\TmpFile::clearOld($sid);
		echo "<script>alert('提交成功');parent.location.href='/member/productprocess/list/{$goods_id}/{$goods_name}'</script>";exit;
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
        $image = M\CredibleFarmPlant::findFirst("id={$id}");
        if ($image) 
        {
        	$image->is_delete='1';
        	if($image->save()){
	         //    $crediblefarmplant=M\CredibleFarmPlant::count("goods_id = '{$image->goods_id}' and is_delete = 0 ");
	        	// if(!$crediblefarmplant){
		        // 	$CredibleFarmGoodsplant=M\CredibleFarmGoodsplant::findFirstBygoods_id($image->goods_id);
		        //     $CredibleFarmGoodsplant->is_delete=1;
		        //     $CredibleFarmGoodsplant->save();
	        	// }
        		die(json_encode($rs));	
        	}
        }else{
        	$rs = array(
        		'state'=>false,
        		'msg'=>'删除失败');
        	die(json_encode($rs));
        }
    }

	/**
	 * 删除图片
	 * @return [type] [description]
	 */
    public function deleteImgupAction() 
    {
        $rs = array(
            'state' => true,
            'msg' => '删除成功！'
        );

        $id = $this->request->get('id', 'int', 0);
        
        $image = M\CredibleFarmGoodsplant::findFirst("goods_id={$id}");
        if ($image) 
        {
        	$image->is_delete='1';
        	if($image->save()){
        		die(json_encode($rs));	
        	}
        }

        
    }
    public function addlistAction($goods_id = 0,$goods_name = ''){
    	$sid = $this->session->getId();
        $this->view->sid = $sid;
        $this->view->goods_name = $goods_name;
		$this->view->goods_id = $goods_id;
    }
}