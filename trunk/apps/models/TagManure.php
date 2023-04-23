<?php
namespace Mdg\Models;

class TagManure extends \Phalcon\Mvc\Model
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
    public $manure_name;

    /**
     *
     * @var integer
     */
    public $manure_type;

    /**
     *
     * @var double
     */
    public $manure_amount;

    /**
     *
     * @var string
     */
    public $manure_brand;

    /**
     *
     * @var string
     */
    public $manure_suppliers;

    /**
     * 标签农药信息
     * @param  integer $tid 标签id
     * @return array
     */
    public static function getTagManureList($tid=0) {
        $cond[] = " tag_id = '{$tid}'";
        $data = self::find($cond);
        return $data;
    }

}
