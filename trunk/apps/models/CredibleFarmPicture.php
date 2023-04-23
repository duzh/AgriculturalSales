<?php
namespace Mdg\Models;
use Lib\Pages as Pages;
class CredibleFarmPicture extends \Phalcon\Mvc\Model
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
    public $farm_id;

    /**
     *
     * @var string
     */
    public $picture_path;

    /**
     *
     * @var string
     */
    public $title;

    /**
     *
     * @var string
     */
    public $desc;

    /**
     *
     * @var integer
     */
    public $type;

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

    /**
     *
     * @var integer
     */
    public $picture_time;

    /**
     *
     * @var integer
     */
    public $user_id;
    /**
     * 显示供应列表
     * @param  [type] $category [description]
     * @return [type]           [description]
     */
    
    public static function getlist($p = 1,$page_size = 20,$where) 
    {
        
       
        $total = self::count($where);
        $offst = intval(($p - 1) * $page_size);
        $data = self::find($where . "  ORDER BY add_time DESC limit {$offst} , {$page_size} ")->toArray();
        $total_count =ceil($total / $page_size);
        $pages['total_pages'] = $total_count;
        $pages['current'] = $p;
        $pages['total'] = $total;
        $pages = new Pages($pages);
        $datas['total_count'] = $total_count;
        $datas['total'] = $total;
        $datas['items'] = $data;
        $datas['start'] = $offst;
        $datas['pages'] = $pages->show(2);
        return $datas;
    }
}
