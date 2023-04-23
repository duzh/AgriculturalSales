<?php
namespace Mdg\Models;

class TagProduct extends \Phalcon\Mvc\Model
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
    public $product_place;

    /**
     *
     * @var string
     */
    public $manure;

    /**
     *
     * @var string
     */
    public $pollute;

    /**
     *
     * @var string
     */
    public $breed;

    /**
     *
     * @var string
     */
    public $seed_quality;

    /**
     *
     * @var string
     */
    public $manure_type;

    /**
     *
     * @var string
     */
    public $manure_amount;

    /**
     *
     * @var string
     */
    public $pesticides_type;

    /**
     *
     * @var string
     */
    public $pesticides_amount;

    /**
     *
     * @var string
     */
    public $process_type;

    /**
     *  根据标签id 获取生产信息
     * @param  integer $tid 标签id
     * @return [type]       [description]
     */
    public static function getProductInfo($tid=0) {
        $cond[] = " tag_id = '{$tid}'";

        $data = self::findFirst($cond);
        if(!$data) return array();
        $data = $data->toArray();
        // $data['certifying_file'] = IMG_URL . $data['certifying_file'];
        return $data;
    }

    


}
