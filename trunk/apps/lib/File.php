<?php
namespace Lib;

class File
{


    /**
     * 生成随机字符串
     * @param  int  $length  生成字串长度
     * @param  integer $numeric 是否为数字
     * @return string           
     */
    static function random($length, $numeric = 0) {
        $seed = base_convert(md5(microtime().$_SERVER['DOCUMENT_ROOT']), 16, $numeric ? 10 : 35);
        $seed = $numeric ? (str_replace('0', '', $seed).'012340567890') : ($seed.'zZ'.strtoupper($seed));
        if($numeric) {
            $hash = '';
        } else { 
            $hash = chr(rand(1, 26) + rand(0, 1) * 32 + 64);
            $length--;
        }
        $max = strlen($seed) - 1;
        for($i = 0; $i < $length; $i++) {
            $hash .= $seed{mt_rand(0, $max)};
        }
        return $hash;
    }

    /**
     * 文件改名
     * @param  string $src_path 原始文件路径
     * @param  string $dst_path 目标路径不含文件名
     * @return
     */
    static function renameFile($src_path='', $dst_path='', $filename) {
        File::make_dir($dst_path);

        @rename($src_path, $dst_path.$filename);

    }

    /**
     * 创建文件夹
     * @param  [type] $folder 文件夹路径
     * @return boolean             
     */
    static public function make_dir($folder)
    {
        $reval = false;

        if (!file_exists($folder))
        {
            /* 如果目录不存在则尝试创建该目录 */
            @umask(0);

            /* 将目录路径拆分成数组 */
            preg_match_all('/([^\/]*)\/?/i', $folder, $atmp);

            /* 如果第一个字符为/则当作物理路径处理 */
            $base = ($atmp[0][0] == '/') ? '/' : '';

            /* 遍历包含路径信息的数组 */
            foreach ($atmp[1] AS $val)
            {
                if ('' != $val)
                {
                    $base .= $val;

                    if ('..' == $val || '.' == $val)
                    {
                        /* 如果目录为.或者..则直接补/继续下一个循环 */
                        $base .= '/';

                        continue;
                    }
                }
                else
                {
                    continue;
                }

                $base .= '/';

                if (@!file_exists($base))
                {
                    /* 尝试创建目录，如果创建失败则继续循环 */
                    if (@mkdir(rtrim($base, '/'), 0777))
                    {
                        @chmod($base, 0777);
                        $reval = true;
                    }
                }
            }
        }
        else
        {
            /* 路径已经存在。返回该路径是不是一个目录 */
            $reval = is_dir($folder);
        }

        clearstatcache();

        return $reval;
    }

    /**
     * 检查上传文件
     * @param  string $filename 文件名
     * @param  array  $ext_arr  允许上传文件后缀
     * @return [type]           [description]
     */
    static public function check_files( $filename =  ''  , $ext_arr = array('jpg','BMP','PNG','JPG','JPEG','GIF','png', 'bmp','BMP','jpeg') )
    {
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (!in_array($ext, $ext_arr)) {
          return false;
        }
        return true;
    }

    /**
     * 上传文件
     * @param  array   $_files   文件数组
     * @param  string  $path     上传路径
     * @param  boolean $realname 是否需要真实名字
     * @param  array   $ext_arr  允许文件
     * @return array            
     */
    static public function move_file($_files = array()  , $path = ''   , $realname = false , $ext_arr = array('jpg','png', 'jpeg','mp4')){
        
        $rs = array('status' => false, 'msg' =>'' , 'path' =>'' );
        if(!is_array($_files)) return array('stauts' => false, 'msg' =>'请上传文件数据' , 'path' =>'' );
        if(!is_array($ext_arr)) $ext_arr = array($ext_arr);
        if (!file_exists($path)) File::make_dir($path);

        $ext = pathinfo($_files['name'], PATHINFO_EXTENSION);
        $ext =strtolower($ext);
        if (!in_array($ext, $ext_arr)) {
            $rs['msg'] = '图片格式不正确';
            return $rs;
        }
        if($realname) $rs['realname'] = $_files['name'];
         $ext=strtoupper($ext);
        $taget = $path.File::random(12 , 1).'.'.$ext;
        @move_uploaded_file($_files['tmp_name'], $taget);

        $rs['path'] = $taget;
        $rs['msg'] = '上传成功';
        $rs['status'] = true;
        return $rs;
    }
    /**
     * 检查上传excel文件
     * @param  string $filename 文件名
     * @param  array  $ext_arr  允许上传文件后缀
     * @return [type]           [description]
     */
    public static  function check_excel($filename='',$ext_arr = array('xls','xlsx') )
    {
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (!in_array($ext, $ext_arr)) {
          return false;
        }
        return true;
    }
    /**
     * 上传excel文件
     * @param  array   $_files   文件数组
     * @param  string  $path     上传路径
     * @param  boolean $realname 是否需要真实名字
     * @param  array   $ext_arr  允许文件
     * @return array            
     */
    public static  function move_excel($_files = array()  , $path = ''   , $realname = false , $ext_arr = array('xls','xlsx')){

        $rs = array('stauts' => false, 'msg' =>'' , 'path' =>'' );
        if(!is_array($_files)) return array('stauts' => false, 'msg' =>'请上传文件数据' , 'path' =>'' );
        if(is_array($ext_arr)) $ext_arr = $ext_arr;
        if (!file_exists($path)) self::make_dir($path);

        $ext = pathinfo($_files['name'], PATHINFO_EXTENSION);

        if (!in_array($ext, $ext_arr)) {
            $rs['msg'] = '格式不正确';
        }
        if($realname) $rs['realname'] = $_files['name'];

        $taget = $path.self::random(12 , 1).'.'.$ext;

        @move_uploaded_file($_files['tmp_name'], $taget);

        $rs['path'] = $taget;
        $rs['msg'] = '上传成功';
        $rs['status'] = true;
        return $rs;
    }
}
