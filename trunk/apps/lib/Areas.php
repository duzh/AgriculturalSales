<?php
namespace Lib;
use Mdg\Models\AreasFull as mAreas;
use Lib\Func as Func;

class Areas
{
	static function getAreaTree($area_id = 0) {
		$rs = array();
		$data = mAreas::findFirstByarea_id($area_id);
		if(!$data) return $rs;

		$rs[] = $data->toArray();
		while ($data && $data->parent_id) {
			$data = mAreas::findFirstByarea_id($data->parent_id);
			if($data) $rs[] = $data->toArray();
		}

		return array_reverse($rs);

	}

	static function getAreasFull($area_id = 0) {
		$rs = array();
		$data = mAreas::findFirstByid($area_id);
		if(!$data) return $rs;

		$rs[] = $data->toArray();
		while ($data && $data->pid) {
			$data = mAreas::findFirstByid($data->pid);
			if($data) $rs[] = $data->toArray();
		}

		return array_reverse($rs);

	}


	static function ldData($area_id=0, $num = 5) {

		$data = mAreas::getFamily($area_id);
       
		if(empty($data)){
			return '';
		}
		$rs = array();
		$num=count($data);
	
		for ($i=0; $i < $num; $i++) { 
			if(!isset($data[$i])) {
				$data[$i] = $data[$i-1];
			}
			$rs[] = $data[$i]['name'];
		}

		$rs = "'".implode("', '", $rs)."'";
		
		return $rs;
	}
	static function ldarea($area_id=0, $num = 5) {
		$data = mAreas::getFamily($area_id);

		if(empty($data)){
			return '';
		}
		$rs = array();
		$num=count($data);
	
		for ($i=0; $i < $num; $i++) { 
			if(!isset($data[$i])) {
				$data[$i] = $data[$i-1];
			}
			$rs[] = $data[$i]['name'];
		}
    
		$rs = implode("-", $rs);
		return $rs;	
	}
	static function ldareaname($area_id=0, $num = 5) {
		$data = mAreas::getFamily($area_id);

		if(empty($data)){
			return '';
		}
		$rs = array();
		$num=count($data);
	
		for ($i=0; $i < $num; $i++) { 
			if(!isset($data[$i])) {
				$data[$i] = $data[$i-1];
			}
			$rs[] = $data[$i]['name'];
		}
    
		$rs = implode("", $rs);
		return $rs;
	}
	static function ldareasname($area_id=0, $num = 5) {
		$data = mAreas::getFamily($area_id);

		if(empty($data)){
			return '';
		}
		$rs = array();
		$num=count($data);
	
		for ($i=0; $i < $num; $i++) { 
			if(!isset($data[$i])) {
				$data[$i] = $data[$i-1];
			}
			$rs[] = $data[$i]['name'];
		}
    
		$rs = implode(",", $rs);
		return $rs;
	}
}