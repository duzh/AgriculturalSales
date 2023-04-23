<?php
namespace Mdg\Models;
use Mdg\Models as M;
class TagPlant extends \Phalcon\Mvc\Model
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
    public $begin_date;

    /**
     *
     * @var integer
     */
    public $end_date;

    /**
     *
     * @var string
     */
    public $weather;

    /**
     *
     * @var string
     */
    public $comment;

    /**
     *
     * @var integer
     */
    public $operate_type;

    /**
     *
     * @var string
     */
    public $path;
    /**
     * 获取种植作业信息
     * @param  integer $tid 标签id
     * @return array
     */
    private static $datedigital = " Y-m-d ";
    private static $formchar = " Y年m月d日";

    public static function getTagPlantList($tid=0, $sell=0) {
        $cond[] = " tag_id = '{$tid}' ";
        $cond['order'] = "  id ASC ";
        $date = self::$datedigital;

        $data = self::find($cond)->toArray();
        if($sell) {
            $date = self::$formchar;
        }
        foreach ($data as $key => $val) {

            $data[$key]['begin_date'] = $val['begin_date'] ? date($date , $val['begin_date']) : 0;
            $data[$key]['end_date'] = $val['end_date'] ? date($date , $val['end_date']): 0;

            $data[$key]['imgList'] = M\TagPicture::getTagPlantImgList($val['id']);
            $data[$key]['ptype'] = isset(M\Tag::$_operate_type[$val['operate_type']]) ?M\Tag::$_operate_type[$val['operate_type']]: '';
        }
        return $data;
    }
}
