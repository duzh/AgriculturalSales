<?php
namespace Lib;
use Mdg\Models\AreasFull as Areasf;

class AreasFull{
	static public function get($name='',$area=0){
		if(!$name) return array();
		$cond[] = "pid = {$area}";
		$cond[] = "name like '%{$name}%'";
		$where = implode(' and ', $cond);
		$test = Areasf::findfirst($where);
		if($test){
			return $test->toArray();
		}else{
			return array();
		}
	}

	/**
	 * 递归获取上级信息
	 * @param  integer $area_id 地区id
	 * @return [type]           [description]
	 */
	static function getAreasFull($area_id = 0) {
		$rs = array();
		$data = Areasf::findFirstByid($area_id);
		if(!$data) return $rs;

		$rs[] = $data->toArray();
		while ($data && $data->pid) {
			$data = Areasf::findFirstByid($data->pid);
			if($data) $rs[] = $data->toArray();
		}

		return array_reverse($rs);

	}

}