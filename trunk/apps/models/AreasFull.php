<?php
namespace Mdg\Models;
use Lib\Func as Func;
class AreasFull extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id=0;

    /**
     *
     * @var integer
     */
    public $pid=0;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $code;

    /**
     *
     * @var integer
     */
    public $level=0;

    /**
     *
     * @var integer
     */
    public $is_show=0;
    

    /**
     * 获取家庭
     */
    static function getFamily($id=0) {
        $rs = array();
        $data = self::findFirstByid($id);
        if(!$data) return $rs;

        $rs[] = $data->toArray();
        while ($data && $data->pid) {
            $data = self::findFirstByid($data->pid);
            if($data) $rs[] = $data->toArray();
        }

        return array_reverse($rs);
    }

    /**
     * 获取子地区
     */
    static function getChild($id=0) {
        $rs = array();
        $data = self::find("id = '{$id}'")->toArray();

        while (!empty($data)) {
            $ids = Func::getCols($data, 'id');
            $rs = array_merge($rs, $ids);
            $data = self::find("pid in (".implode(', ', $ids).")")->toArray();
        }
        sort($rs);
        return $rs;
    }

    /**
     * 获取父地区
     */
    static function getParent($id=0) {

        $rs = array();
        $data = mAreas::findFirstByid($id);
        if(!$data) return $rs;

        $rs[] = $data->id;
        while ($data && $data->pid) {
            $data = mAreas::findFirstByid($data->pid);
            if($data) $rs[] = $data->id;
        }

        sort($rs);
        return $rs;

    }

    static function parseAreas($sheng, $shi, $qu) {
        $sheng = trim($sheng);
        $shi = trim($shi);
        $qu = trim($qu);

        if(!$sheng || !$shi ||!$qu) return false;

        $info = self::findFirst("name like '%{$qu}%' and level = 3");
        if($info) return $info->id;

        $shengList = self::find("name like '%{$sheng}%' and pid = 0")->toArray();

        foreach ($shengList as $sheng) {
            $shiIds = Func::getCols(self::find("pid = '{$sheng['id']}'")->toArray(), 'id');
            $shiList = self::find("name like '%{$shi}%'")->toArray();
            foreach ($shiList as $shi) {
                if(!in_array($shi['id'], $shiIds)) continue;
                $quIds = Func::getCols(self::find("pid = '{$shi['id']}'")->toArray(), 'id');
                $quList = self::find("name like '%{$qu}%'")->toArray();
                foreach ($quList as $qu) {
                    if(in_array($qu['id'], $quIds)) return $qu['id'];
                }
            }
        }
        return false;

    }

    /**
     * 根据地区id获取地区名
     * @param  integer $aid 地区id
     * @return string
     */
    public static function getAreasNametoid ($aid=0) {
        $data = self::findFirst(" id = '{$aid}'");
        return $data ? $data->name : '';
    }

    /**
     * 获取所有子级信息
     * @param  integer $pid 父级id    
     * @return array
     */
    static function getChildData ($pid=0) {
        return self::find(" pid = '{$pid}' AND  is_show = 1")->toArray();
    }
    static function getAreaname($areaname=''){
        $data=self::findFirstbyarea_name($areaname);
        return $data ? $data->id : false;
    }
    static function checkarea($area){
        $address=self::findFirstByid($area);  
        if(!$address){
            return false;
        }
        
        if($address->level==3||$address->level==5){
          return true;
        }
        return false;
    }

}
