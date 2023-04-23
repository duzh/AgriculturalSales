<?php

namespace Mdg\Models;
use Lib\Path as Path;
use Lib\File as File;
class Ad extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id=0;

    /**
     *
     * @var string
     */
    public $addesc='';

    /**
     *
     * @var integer
     */
    public $position=0;

    /**
     *
     * @var string
     */
    public $adtitle='';

    /**
     *
     * @var string
     */
    public $adsrc='';

    /**
     *
     * @var string
     */
    public $imgpath='';

    /**
     *
     * @var integer
     */
    public $is_show=1;

    /**
     *
     * @var string
     */
    public $addtime=0;

    /**
     * 从临时表复制信息
     * @param  int $sellid  供应ID
     * @param  string $sid  session_id md5值
     */
    public $type=0;

    /**
     *
     * @var string
     */
    static function copyImages($adid=0, $sid='') {


        $tmpFile =TmpFile::find("sid='$sid' and type=4 ");
       
        foreach ($tmpFile as $val) {
        
            $adimg = self::findFirstByid($adid);
            if($adimg) {
                $adimg->imgpath = $val->file_path;
                $adimg->save();
            }
        }
        $tmpFile->delete();
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
