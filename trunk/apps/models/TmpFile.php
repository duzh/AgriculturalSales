<?php
namespace Mdg\Models;
use Lib\Path as Path;

class TmpFile extends \Phalcon\Mvc\Model
{
    /**
     *
     * @var integer
     */
    
    public $id;
    /**
     *
     * @var string
     */
    
    public $sid;
    /**
     *
     * @var string
     */
    
    public $file_path;
    /**
     *
     * @var string
     */
    
    public $src_name;
    /**
     *
     * @var integer
     */
    
    public $addtime;
    /**
     *
     * @var integer
     */
    
    public $type;
    CONST SERVICEPIC = 22; #服务站实地照片
    static function clearOld($sid = '') 
    {
        $data = self::find("sid='{$sid}'");
        
        foreach ($data as $val) 
        {
            @unlink(PUBLIC_PATH . $val->path);
        }
        $data->delete();
    }
    static function clearall($sid = '', $type = '') 
    {
        $data = self::find("sid='{$sid}' and type='{$type}' ");
        
        foreach ($data as $val) 
        {
            @unlink(PUBLIC_PATH . $val->path);
        }
        $data->delete();
    }
    /**
     * 从临时表复制信息
     * @param  int $sellid  分类ID
     * @param  string $sid  session_id md5值
     */
    static public function copyImages($imgid = 0, $sid = '') 
    {
        
        $tmpFile = TmpFile::find("sid='{$sid}'");
        foreach ($tmpFile as $val) 
        {
            $image = new Image();
            $image->gid = $imgid;
            $image->createtime = time();
            $image->state = 1;
            
            if ($val->type == 2) 
            {
                $image->type = 1;
                $image->agreement_image = $val->file_path;
            }
            
            if ($val->type == 3) 
            {
                $image->type = 0;
                $image->agreement_image = $val->file_path;
            }
            
            if ($val->type == 13) 
            {
                $image->type = 13;
                $image->agreement_image = $val->file_path;
            }
            if($val->type==27){
                 $userext =UsersExt::findFirstByuid($imgid);
                 $userext->picture_path=$val->file_path;
                 $userext->save();
            }
            $image->save();
            
            if ($val->type == 1 || $val->type == 5) 
            {   
                SellImages::copyImages($imgid, $sid, $val->file_path);
            }
			if ($val->type == 28) 
			{	
				Advisory::insertImages($imgid, $sid, $val->file_path);
            }
        }
        $tmpFile->delete();
    }
}
