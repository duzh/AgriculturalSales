<?php
namespace Mdg\Models;
use Lib\regExp as regExp;
use Lib\Func as Func;
class Areas extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $area_id;

    /**
     *
     * @var integer
     */
    public $parent_id;

    /**
     *
     * @var string
     */
    public $area_name;

    /**
     *
     * @var string
     */
    public $code;

    /**
     *
     * @var string
     */
    public $child;

    /**
     *
     * @var string
     */
    public $parent;

    /**
     *
     * @var integer
     */
    public $level;

    /**
     * 获取家庭
     */
    static function getFamily($area_id=0) {
        $rs = array();
        $data = self::findFirstByarea_id($area_id);
        if(!$data) return $rs;

        $rs[] = $data->toArray();
        while ($data && $data->parent_id) {
            $data = self::findFirstByarea_id($data->parent_id);
            if($data) $rs[] = $data->toArray();
        }

        return array_reverse($rs);
    }

    /**
     * 获取子地区
     */
    static function getChild($area_id=0) {
        $rs = array();
        $data = self::find("area_id = '{$area_id}'")->toArray();

        while (!empty($data)) {
            $ids = Func::getCols($data, 'area_id');
            $rs = array_merge($rs, $ids);
            $data = self::find("parent_id in (".implode(', ', $ids).")")->toArray();
        }
        sort($rs);
        return $rs;
    }

    /**
     * 获取父地区
     */
    static function getParent($area_id=0) {

        $rs = array();
        $data = mAreas::findFirstByarea_id($area_id);
        if(!$data) return $rs;

        $rs[] = $data->area_id;
        while ($data && $data->parent_id) {
            $data = mAreas::findFirstByarea_id($data->parent_id);
            if($data) $rs[] = $data->area_id;
        }

        sort($rs);
        return $rs;

    }

    static function parseAreas($sheng, $shi, $qu) {
        $sheng = trim($sheng);
        $shi = trim($shi);
        $qu = trim($qu);

        if(!$sheng || !$shi ||!$qu) return false;

        $info = self::findFirst("area_name like '%{$qu}%' and level = 3");
        if($info) return $info->area_id;

        $shengList = self::find("area_name like '%{$sheng}%' and parent_id = 0")->toArray();

        foreach ($shengList as $sheng) {
            $shiIds = Func::getCols(self::find("parent_id = '{$sheng['area_id']}'")->toArray(), 'area_id');
            $shiList = self::find("area_name like '%{$shi}%'")->toArray();
            foreach ($shiList as $shi) {
                if(!in_array($shi['area_id'], $shiIds)) continue;
                $quIds = Func::getCols(self::find("parent_id = '{$shi['area_id']}'")->toArray(), 'area_id');
                $quList = self::find("area_name like '%{$qu}%'")->toArray();
                foreach ($quList as $qu) {
                    if(in_array($qu['area_id'], $quIds)) return $qu['area_id'];
                }
            }
        }
        return false;

    }

    static function getAreaname($areaname=''){
        $data=self::findFirstbyarea_name($areaname);
        return $data ? $data->area_id : false;
    }
    
}
