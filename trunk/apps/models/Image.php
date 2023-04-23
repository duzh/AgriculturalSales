<?php
namespace Mdg\Models;
use Mdg\Models as M;

class Image extends \Phalcon\Mvc\Model
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
    public $gid;

    /**
     *
     * @var string
     */
    public $agreement_image;

    /**
     *
     * @var integer
     */
    public $createtime;

    /**
     *
     * @var integer
     */
    public $state;
     
    /**
     *
     * @var integer
     */
    public $type;
    CONST TAGORIGIN = 99; //标签产地照片
    CONST TAGHOMEWORK = 98; // 标签作业照片
    static 
    public function imgsrc($id,$size = '142') 
    {
        $imgsrc = Image::findFirst("gid=" . $id . " and type=0");
        return $imgsrc ? IMG_URL . $imgsrc->agreement_image."!".$size : STATIC_URL . "/mdg/version2.4/images/detial_b_img.jpg";
    }
    static 
    public function imgmaxsrc($id, $size = '300') 
    {
        $imgsrc = Image::findFirst("gid=" . $id . " and type=1");
        if($size==0){
            return $imgsrc ? IMG_URL . $imgsrc->agreement_image : STATIC_URL . "/mdg/version2.4//images/detial_b_img.jpg";
        }else{
            return $imgsrc ? IMG_URL . $imgsrc->agreement_image . "!" . $size : STATIC_URL . "/mdg/version2.4//images/detial_b_img.jpg";
        }   
    }
    static 
    public function publicize($id) 
    {
        $imgsrc = Image::findFirst("gid=" . $id . " and type=12");
        return $imgsrc ? IMG_URL . $imgsrc->agreement_image : "http://yncstatic.b0.upaiyun.com/mdg/images/shop_banner.jpg";
    }
    static 
    public function imgmaxedit($id) 
    {
        $imgsrc = Image::findFirst("gid=" . $id . " and type=1");
        return $imgsrc ? $imgsrc->toArray() : array();
    }
    //产地照片
    
    public function getListImage($tid = 0) 
    {
    }

    /**
     * 根据类型获取图片集
     * @param  integer $id   id
     * @param  integer $type 类型
     * @return array
     */
    
    public function getImagesList($id = 0, $type = 0) 
    {
        $cond = array();
        
        if ($id) 
        {
            $cond[] = " id = '{$id}'";
        }
        
        if (isset($type) && $type) 
        {
            $cond[] = " type = '{$type}'";
        }
        $data = self::find($cond)->toArray();
        
        foreach ($data as $key => $val) 
        {
            $data[$key]['src'] = ITEM_IMG . $val['agreement_image'];
        }
        return $data;
    }

    

    
            /**
     * 生成店铺 宣传图
     * @param  integer $pid  店铺id
     * @param  string  $path 宣传图id
     * @return [type]        [description]
     */
    
    public static function saveImageMap($pid = 0, $path = '') 
    {
        $image = self::findFirst(" gid = '{$pid}' AND type = 12 ");
        
        if (!$image) 
        {
            $images = new M\Image();
            $images->gid = $pid;
            $images->agreement_image = $path;
            $images->createtime = time();
            $images->state = 1;
            $images->type = 12;
            $flag = $images->save();
        }
        else
        {
            $image->agreement_image = $path;
            $image->createtime =  time();
            $image->state =  1;
            $image->type = 12;
            $flag = $image->save();
        }
        return $flag;
    }
     /**
     *  检测图片是否存在
     * @param  [type] $url [description]
     * @return [type]      [description]
     */
    public static function img_exists($url) 
    {
        if( @fopen( $url, 'r' ) ) 
        { 
           return $url;
        } 
        else 
        {
            return STATIC_URL."/mdg/version2.4//images/detial_b_img.jpg";
        }
    }
}
