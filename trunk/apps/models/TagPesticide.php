<?php
namespace Mdg\Models;

class TagPesticide extends \Phalcon\Mvc\Model
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
     * @var string
     */
    public $use_period;

    /**
     *
     * @var string
     */
    public $pesticide_name;

    /**
     *
     * @var double
     */
    public $pesticide_amount;

    /**
     *
     * @var string
     */
    public $pesticide_brand;

    /**
     *
     * @var string
     */
    public $pesticide_suppliers;
    /**
     * 获取标签肥料信息
     * @param  integer $tid 标签id
     * @return array
     */
    public static function getTagPesticideList($tid=0) {
        $cond[] = " tag_id = '{$tid}'";
        $data = self::find($cond);
        return $data;
    }

}
