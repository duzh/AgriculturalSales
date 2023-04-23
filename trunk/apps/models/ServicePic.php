<?php
namespace Mdg\Models;

class ServicePic extends \Phalcon\Mvc\Model
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
    public $service_id;

    /**
     *
     * @var integer
     */
    public $pic_path;

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

    /**
     * 删除图片信息
     * @param  array $where 条件
     */
    static function delImages($where) {
        $data = self::find($where);
   
        foreach ($data as $img) {
            @unlink(PUBLIC_PATH.$img->path);
        }
        return $data->delete();
    }


    /**
     * 获取服务站图片
     * @param  integer $sid 服务站id
     * @return array
     */
    public static function getServiceStaionImg ($sid=0) {
        
        if(!$sid) return array();
        $cond[] = " service_id = '{$sid}'";

        $cond['columns'] = " service_id, concat('".IMG_URL."',pic_path) AS pic_path , add_time";
        return self::find($cond)->toArray();

        
    }

}
