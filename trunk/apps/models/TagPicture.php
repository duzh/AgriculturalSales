<?php
namespace Mdg\Models;

class TagPicture extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $tag_id;

    /**
     *
     * @var integer
     */
    public $order;

    /**
     *
     * @var string
     */
    public $path;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var integer
     */
    public $add_time;

    /**
     *
     * @var integer
     */
    public $last_update_time;
    public $plant_id;
    public $picture_source;
    /**
     * 获取标签生产图片
     * @param  integer $tid 标签id
     * @return [type]       [description]
     */
    public static function getTagProductionImgList($tid=0) {
        $cond[] = " tag_id = '{$tid}' AND picture_source = 0 ";
        $cond['order'] = "  id DESC ";
        $data = self::find($cond)->toArray();
        foreach ($data as $key => $val) {
            $data[$key]['path'] = IMG_URL.$val['path'];
        }
   
        return $data;
    }
    /**
     * 获取种植作业图片
     * @param  integer $pid    种植id
     * @param  integer $tag_id 标签id
     * @return array
     */
    public static function getTagPlantImgList($pid=0, $tag_id=0) {

        if($tag_id) {
            $cond[] = "     tag_id = '{$tag_id}' AND picture_source = 1 ";
        }
        if($pid) {
            $cond[] = "     plant_id = '{$pid}' AND picture_source = 1 ";
        }
        
        $cond['order'] = "  id DESC ";
        
        $data = self::find($cond)->toArray();
        foreach ($data as $key => $val) {
            $data[$key]['path'] = IMG_URL.$val['path'];
        }
        return $data;
    }

 

}
