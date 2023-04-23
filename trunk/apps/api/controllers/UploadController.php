<?php
namespace Mdg\Api\Controllers;
use Lib\Path as Path;
use Lib\File as File;
use Mdg\Models\TmpFile as TmpFile;
use Mdg\Models\SellImages as SellImages;
use Mdg\Models\Image as Image;
use Lib\UpYun as UpYun;
class UploadController extends ControllerBase
{
     /**
     * 上传图片
     * @return string  {"errorCode":0,"data":{"img":{"path":"图片路劲","id":"图片id"}}}
     * <br/>
     * <code>
     * post 传值 
     * filedata 上传图片的name值
     * url http://www.5fengshou.com/api/upload/upfile <br />
     * </code>
     */
    public function upfileAction() {
   
        $str=implode("-----------",$_POST);
        file_put_contents(PUBLIC_PATH.'/cate2.txt',$str."\n", FILE_APPEND);
        
        $str = $this->request->getPost('filedata', 'string', '');
        if(!$str) $this->getMsg(self::DATA_EMPTY);

        $sid =$this->session->getID();
        file_put_contents(PUBLIC_PATH.'/cate1.txt',$sid."\n", FILE_APPEND);
        if(!$sid){
            $this->getMsg(parent::NOT_LOGGED_IN);
        }
        $jpg = base64_decode($str);//得到post过来的二进制原始数
        $tmppath= PUBLIC_PATH.Path::imagePath();
        $newpath=Path::imagePath();
        $filename=File::random(8).'.jpg';
        if (!file_exists($tmppath)) File::make_dir($tmppath);
        @file_put_contents($tmppath.$filename,$jpg);
        //上传U盘
        $this->upyunfile($tmppath.$filename,$newpath.$filename);
        //上传本地 
        $img_id=$this->moveTmpFile($filename,$newpath.$filename,1,$sid);
        if(!$img_id) {
            $this->getMsg(parent::UPLOADIMG_ERROR);
        }
        $datas["path"]=IMG_URL.$newpath.$filename;
        $datas['id'] = $img_id;
        file_put_contents(PUBLIC_PATH.'/cate3.txt',$datas["path"].$datas['id']."\n", FILE_APPEND);
        $this->getMsg(parent::SUCCESS,array('img'=>$datas));

    }

    public function wxupfileAction() {
  
        $str = $this->request->getPost('imgsrc');
        if(!$str) $this->getMsg(self::DATA_EMPTY);
        
        $sid =$this->session->getID();
        
        if(!$sid){
            $this->getMsg(parent::NOT_LOGGED_IN);
        }
        $strs=json_decode($str,2);
        $ext = pathinfo($strs['name'], PATHINFO_EXTENSION);
        $path=Path::imagePath();
        $filename=$path.File::random(12 , 1).'.'.$ext;  
       
        //上传U盘云
        $this->upyunfile($strs['tmp_name'],$filename);
        //上传本地 
        $img_id=$this->moveTmpFile($strs['tmp_name'],$filename,1,$sid);
        if(!$img_id) {
            $this->getMsg(parent::UPLOADIMG_ERROR);
        }
        $datas["path"]=IMG_URL.$filename;
        $datas['id'] = $img_id;
        $this->getMsg(parent::SUCCESS,$datas);

    }

            


     /**
     * 未保存之前的删除图片  删除临时表的图片 
     * @return string  {"errorCode":0}
     * <br/>
     * <code>
     * post 传值 
     * int id 图片id 
     * url http://www.5fengshou.com/api/upload/deltmpfile <br />
     * </code>
     */
    public function deltmpfileAction() {
        
        $id = $this->request->get('id', 'int', 0);
        if(!$id){
            $this->getMsg(parent::PARAMS_ERROR);
        }   
        $tmpFile = TmpFile::findFirstByid($id);
        if($tmpFile) {
            @unlink(PUBLIC_PATH.$tmpFile->file_path);
            $tmpFile->delete();
        }
        $this->getMsg(parent::SUCCESS);
    }
    /**
     * 重新编辑的删除图片  假如重新编辑后  把图片删除了  重新上传的话   就需要调用deltmpfileAction删除图片
     * @return string  {"errorCode":0}
     * <br/>
     * <code>
     * post 传值 
     * int id 图片id 
     * url http://www.5fengshou.com/api/upload/delimg <br />
     * </code>
     */
    public function delimgAction() 
    {
       
        $id = $this->request->get('id', 'int', 0);
        $img = SellImages::findFirstByid($id);
        if (!$img) 
        {
            $this->getMsg(parent::DATA_EMPTY);
        }
        if ($this->getUid() != $img->sell->uid) 
        {
            $this->getMsg(parent::NOT_DELETE_ERROR);
        }
        $sellid = $img->sellid;
        @unlink(PUBLIC_PATH . $img->path);
        $img->delete();
        $data = M\SellImages::findFirstBysellid($sellid);
        if (!$data) 
        {
            $sell = Sell::findFirstByid($sellid);
            
            if ($sell) 
            {
                $sell->thumb = '';
                if(!$sell->save()){
                    $this->getMsg(parent::DELETE_IMG_ERROR);
                }
            }
        }
        $this->getMsg(parent::SUCCESS);
    }
    public function  upyunfile($path,$publicpath){
    
        //上传upy
        $upyun = new UpYun();
           
        try{

          $test = fopen($path,'r');
          $pathaa =$publicpath;
          $arr = $upyun->writeFile($pathaa, $test, true);

        }
        catch(Exception $e) {
            return $e->getCode().$e->getMessage();
        }
    }
    public function moveTmpFile($name,$publicpath,$type,$sid){
        $tmpFile = new TmpFile();
        $tmpFile->src_name = $name;
        $tmpFile->file_path =$publicpath;
        $tmpFile->sid = $sid;
        $tmpFile->addtime = time();
        $tmpFile->type=$type;
        if(!$tmpFile->save()) {
            return false;
        }else{
            return $tmpFile->id;
        }       
    }

}