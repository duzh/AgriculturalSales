<?php

namespace Mdg\Frontend\Controllers;
use Mdg\Models as M;
use Lib\Pages as Pages;
use Lib\Arrays as Arrays;
class CrediblefarmController extends ControllerKx
{
	/**
	 * 可信农场--所有商品列表
	 * @param  integer $user_id [description]
	 * @return [type]           [description]
	 */
	public function goodslistAction() {
		
		$user_id = $this->session->farm_user_id;
		if(!$user_id){
			echo "<script>alert('数据异常');location.href='/index'</script>";
            exit;
		}
		$page = $this->request->get('p', 'int', 1);
		if(!$page){
			$page=1;
		}
        $page_size = 10;
        $where = "uid={$user_id} and is_del=0 and state=1";
        $total = M\Sell::count($where);
        $offst = intval(($page - 1) * $page_size);
		$data = M\Sell::find($where . " ORDER BY updatetime DESC limit {$offst} , {$page_size} ")->toArray();
		foreach($data as $k=>$v){
			$data[$k]['goods_unit'] = self::getGoodsUnit($v['goods_unit']);		//获取产品价格单位
		}
		$pages['total_pages'] = ceil($total / $page_size);
        $pages['current'] = $page;
        $pages['total'] = $total;
        $pages = new Pages($pages);
        $newpages = str_replace(array('下一页', '上一页'), '', $newpages);     
        $pages = $pages->show(4);
        $this->view->data = $data;
        $this->view->pages = $pages;
        $this->view->user_id = $user_id;
        $this->view->newpages = $newpages;
        $this->view->title = '可信农场-所有商品';
	
	}

	/**
	 * 可信农场--种植过程
	 * @param  integer $user_id [description]
	 * @return [type]           [description]
	 */
	public function farmplantAction(){

		$user_id = $this->session->farm_user_id;

		if(!$user_id){
			echo "<script>alert('数据异常');location.href='/index'</script>";
            exit;
		}
		$count = M\CredibleFarmPlant::find("user_id={$user_id} and is_delete=0 group by goods_id")->toArray();
		
		if($count){
			end($count);
			$key = key($count)+1;
		}else{
			$key=0;
		}
		
		$data = M\CredibleFarmPlant::find("user_id={$user_id} and is_delete=0 order by goods_id desc,picture_time asc")->toArray();

		foreach($data as $k=>$v){
			$goods_name=self::getGoodsName($v['goods_id']);		//获取种植商品名称
			if($goods_name){
				$data[$k]['goods_name'] =$goods_name;
			}
		}
		foreach ($data as $key => $value) {
			$goodsname[$value["goods_id"]]=$value["goods_name"];
		}
	
		$data_info = Arrays::groupBy($data,'goods_id');
		$this->view->data_info = $data_info;
		$this->view->goodsname=$goodsname;
		$this->view->num = $key;
		$this->view->count = $count;
		$this->view->title = '可信农场-种植过程';
	}

	/**
	 * 获取商品单位
	 * @param  integer $goods_unit [description]
	 * @return [type]              [description]
	 */
	public function getGoodsUnit($goods_unit=0){
		if(!$goods_unit){
			return '不限';
		}
		$goods_unit_name = M\Purchase::$_goods_unit[$goods_unit];
		return $goods_unit_name;
	}

	public function getGoodsName($goods_id=0){
		if(!$goods_id){
			return false;
		}
		$crediblefarmgoodsplant = M\CredibleFarmGoodsplant::findFirst("goods_id={$goods_id} and is_delete=0");
		if($crediblefarmgoodsplant){
			return $crediblefarmgoodsplant->goods_name;
		}else{
			return false;
		}
	}

}
