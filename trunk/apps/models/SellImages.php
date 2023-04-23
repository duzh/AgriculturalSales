<?php

namespace Mdg\Models;

use Lib\Path as Path;
use Lib\File as File;

class SellImages extends \Phalcon\Mvc\Model
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
    public $sellid;

    /**
     *
     * @var string
     */
    public $path;

    /**
     *
     * @var integer
     */
    public $addtime;


    public function initialize()
    {
        $this->belongsTo('sellid', 'Mdg\Models\Sell', 'id', array(
            'alias' => 'sell'
        ));
    }

    /**
     * 从临时表复制信息
     * @param  int $sellid  供应ID
     * @param  string $sid  session_id md5值
     */
    static function copyImages($sellid, $sid='',$path='') {

        $image = new self();
        $image->sellid = $sellid;
        $image->path = $path;
        $image->addtime = time();
        
        $image->save();
        
        $sell=Sell::findFirstByid($sellid);
        $sellimage=SellImages::findFirstBysellid($sellid);
        if($sell && $sellimage){
            $sell->thumb=$sellimage->path;
            $sell->save();    
        }
    }
    /**
     * 删除图片信息
     * @param  array $where 条件
     */
    static function delImages($where) {
        $data = self::find($where);
        
        foreach ($data as $img) {
            @unlink(PUBLIC_PATH.$img->path);
        }
        $data->delete();
    }
}
