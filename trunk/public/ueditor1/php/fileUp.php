<?php
/**
 * Created by JetBrains PhpStorm.
 * User: taoqili
 * Date: 12-7-26
 * Time: 上午10:32
 */
header("Content-Type: text/html; charset=utf-8");
error_reporting(E_ERROR | E_WARNING);
date_default_timezone_set("Asia/chongqing");
include "Uploader.class.php";
include "../upyun.class.php";
include "../upyun.config.sample.php";
//上传图片框中的描述表单名称，
$title = htmlspecialchars($_POST['pictitle'], ENT_QUOTES);
$path = htmlspecialchars($_POST['dir'], ENT_QUOTES);
$globalConfig = include ("config.php");
$imgSavePathConfig = $globalConfig['imageSavePath'];
//获取存储目录

if (isset($_GET['fetch'])) 
{
    header('Content-Type: text/javascript');
    echo 'updateSavePath(' . json_encode($imgSavePathConfig) . ');';
    return;
}
//上传配置
$config = array(
    "savePath" => $imgSavePathConfig,
    "allowFiles" => array(
        ".jpeg",
        ".jpg",
        ".rar",
        ".doc",
        ".docx",
        ".zip",
        ".pdf",
        ".txt",
        ".swf",
        ".mkv",
        ".avi",
        ".rm",
        ".rmvb",
        ".mpeg",
        ".mpg",
        ".ogg",
        ".mov",
        ".wmv",
        ".mp4",
        ".webm",
        '.flv'
    ) , //文件允许格式
    "maxSize" => 1024000, //文件大小限制，单位KB
    "fileNameFormat" => $_POST['fileNameFormat']
);


if (empty($path)) 
{
    $path = $config['savePath'][1];
}
//上传目录验证

if (!in_array($path, $config['savePath'])) 
{
    //非法上传目录
    echo '{"state":"\u975e\u6cd5\u4e0a\u4f20\u76ee\u5f55"}';
    return;
}
$config['savePath'] = $path . '/';
//上传配置
// $config = array(
//     "savePath" => "upload/" , //保存路径
//     "allowFiles" => array( ".jpeg", ".rar" , ".doc" , ".docx" , ".zip" , ".pdf" , ".txt" , ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg", ".ogg", ".mov", ".wmv", ".mp4", ".webm") , //文件允许格式
//     "maxSize" => 102400, //文件大小限制，单位KB
//     "fileNameFormat" => $_POST['fileNameFormat']
// );
//
//生成上传实例对象并完成上传

$up = new Uploader("upfile", $config);



/**
 * 得到上传文件所对应的各个参数,数组结构
 * array(
 *     "originalName" => "",   //原始文件名
 *     "name" => "",           //新文件名
 *     "url" => "",            //返回的地址
 *     "size" => "",           //文件大小
 *     "type" => "" ,          //文件类型
 *     "state" => ""           //上传状态，上传成功时必须返回"SUCCESS"
 * )
 */
$info = $up->getFileInfo();

$upyun = new UpYun($file_bucket, $file_username, $file_passwd);

try
{
    $con = $info["url"];

    $opts = array(
        UpYun::CONTENT_MD5 => md5(file_get_contents($con))
    );

    $fh = fopen($con, "r");
    // $rsp = $upyun->writeFile("/" . $con, $fh, True, $opts); //上传文件，自动创建目录
     fclose($fh);
}
catch(Exception $e) 
{
    $log_file = "./error_log.txt";
    $err = "file " . date("Y-m-d") . " " . $e->getCode() . " " . $e->getMessage() . "\r\n";
    // var_dump($e->getMessage());
    $handle = fopen($log_file, "a");
    fwrite($handle, $err);
    fclose($handle);
 
}


/**
 * 向浏览器返回数据json数据
 * {
 *   'url'      :'a.rar',        //保存后的文件路径
 *   'fileType' :'.rar',         //文件描述，对图片来说在前端会添加到title属性上
 *   'original' :'编辑器.jpg',   //原始文件名
 *   'state'    :'SUCCESS'       //上传状态，成功时返回SUCCESS,其他任何值将原样返回至图片上传框中
 * }
 */
$info["state"] = "SUCCESS";

echo '{"url":"' . $info["url"] . '","fileType":"' . $info["type"] . '","original":"' . $info["originalName"] . '","state":"' . $info["state"] . '"}';
