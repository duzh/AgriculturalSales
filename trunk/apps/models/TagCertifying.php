<?php
namespace Mdg\Models;

class TagCertifying extends \Phalcon\Mvc\Model
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
    public $tag;

    /**
     *
     * @var string
     */
    public $certifying_file;

    /**
     *
     * @var string
     */
    public $status;

    /**
     * 标签机构文件
     * @param  integer $tid 标签id
     * @return array
     */
    public static function getTagCertifyingList($tid=0) {
        $cond[] = " tag_id = '{$tid}'";
        $data = self::find($cond)->toArray();
        foreach ($data as $key => $val) {
            $data[$key]['path'] = $val['certifying_file']  ?  IMG_URL . $val['certifying_file']  : '';
        }
     
        return $data;
    }

  

}
