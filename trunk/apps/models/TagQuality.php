<?php
namespace Mdg\Models;

class TagQuality extends \Phalcon\Mvc\Model
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
    public $quality_level;

    /**
     *
     * @var double
     */
    public $heavy_metal;

    /**
     *
     * @var double
     */
    public $pesticide_residue;

    /**
     *
     * @var integer
     */
    public $is_gene;

    /**
     *
     * @var string
     */
    public $inspector;

    /**
     *
     * @var integer
     */
    public $inspect_time;

    /**
     *
     * @var string
     */
    public $certifying_agency;

    /**
     *
     * @var string
     */
    public $certifying_file;

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

    public function afterFetch() {
        $this->inspecttime = $this->inspect_time ? date('Y-m-d', $this->inspect_time) : '';
        $this->certifying_file =  $this->certifying_file ? IMG_URL . $this->certifying_file : '';
    }

    public function beforeSave() {
        $this->last_update_time = time();
    }
    public function beforeCreate() {
        $this->last_update_time = time();
        $this->add_time = time();
    }
    /**
     * 获取质量详细信息
     * @param  integer $tid 标签id
     * @return [type]       [description]
     */
    public static function getTagQuality($tid=0,$isarr = 0) {
        $data = self::findFirst(" tag_id = '{$tid}'");
        if(!$data) return array();
        
        if($isarr) {
            $data = $data->toArray();
            $data['certifying_file'] = $data['certifying_file'] ? $data['certifying_file'] : '';
        }
        return $data;
    }

}
