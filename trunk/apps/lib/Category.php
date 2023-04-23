<?php
namespace Lib;

use Mdg\Models\Category as mCategory;

class Category{
	/**
	 * [getCatTree description]
	 * @param  integer $cat_id [description]
	 * @return [type]          [description]
	 */
	static function getCatTree($cat_id = 0) {
		$rs = array();
		$data = mCategory::findFirstByid($cat_id);
		if(!$data) return $rs;

		$rs[] = $data->toArray();
		while ($data && $data->parent_id) {
			$data = mCategory::findFirstByid($data->parent_id);
			if($data) $rs[] = $data->toArray();
		}

		return array_reverse($rs);

	}
	/**
	 * [ldData description]
	 * @param  [type]  $cid [description]
	 * @param  integer $num [description]
	 * @return [type]       [description]
	 */
	static function ldData($cid, $num=2) {
		$data = mCategory::getFamily($cid);

		if(empty($data)) return '';

		$rs = array();
		for ($i=0; $i < $num; $i++) { 
			if(!isset($data[$i])) {
				$data[$i] = $data[$i-1];
			}
			$rs[] = $data[$i]['title'];
		}

		$rs = "'".implode("', '", $rs)."'";
		return $rs;

	}
	/**
	 * 根据分类获取分类姓名
	 * @param  integer $cid 分类id
	 * @return string
	 */
	static function ldDataName($cid=0) {

		$data = mCategory::findFirst("id = '{$cid}'");
		if(empty($data)) return '';
		return "'{$data->title}'";
		

	}

}