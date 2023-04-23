<?php
namespace Mdg\Frontend\Controllers;
use Mdg\Models as M;
use Lib as L;
use Mdg\Models\Sell as Sell;
class MakeqrcodeController extends ControllerBase
{
	//检测可追溯产品对应的标签二维码是否存在如果不存在重新生成
	public function makeAction(){
		$tagcon = ' 1 ';
		$sid = M\Tag::getSellid($tagcon);
      	$cond[] = " id in ({$sid}) AND is_del = '0' AND  state = '1' ";
      	$cond = implode( ' AND ', $cond);
      	$data1 = M\Sell::getSellList($cond, 1, 5, 1);

		foreach($data1['items'] as $key=>$val){
		  $sql = "select tag_id,sell_id,path from tag where sell_id ='{$val['id']}'";
		  $path = $this->db->fetchOne($sql,2);
		 
		  if($path['tag_id']){
		  	
			//生成二维码路径
	        $erweitu = ITEM_IMG . $path['path'];
	        
	        $filedata = $this->check_remote_file_exists($erweitu);
	        
	        if(!$path['path'] ||  !$filedata ) {
	            $erweitu = $this->getqrcodeAction($path['tag_id'],1, $path['sell_id']);
	        }
	      }
	      echo $path['path'] . '<br/>';
		  
		}
	}
	/**
     * 生成二维码图片
     * @param  array  $data 数据
     * @return [type]       [description]
     */
    
    public function getqrcodeAction($tid = 0, $isaction = 0,$sellid=0 ) 
    {
        $tid = $tid ? $tid : $this->request->get('tid', 'int', 0);
        $sellid = $sellid ? $sellid : $this->request->get('sellid', 'int', 0);

        include_once __DIR__ . '/../../lib/phpqrcode.php';

        $value = "http://m.5fengshou.com/sell/index?id={$sellid}"; //二维码内容

        $errorCorrectionLevel = 'M'; //容错级别
        $matrixPointSize = 5; //生成图片大小
        //生成二维码图片
        \QRcode::png($value, PUBLIC_PATH . '/qrcode/qrcode.png', $errorCorrectionLevel, $matrixPointSize, 2);
        $logo = PUBLIC_PATH . '/qrcode/logo.png'; //准备好的logo图片
        $QR = PUBLIC_PATH . '/qrcode/qrcode.png'; //已经生成的原始二维码图

        if ($logo !== FALSE) { 
         $QR = imagecreatefromstring(file_get_contents($QR)); 
         $logo = imagecreatefromstring(file_get_contents($logo)); 
         $QR_width = imagesx($QR);//二维码图片宽度 
         $QR_height = imagesy($QR);//二维码图片高度 
         $logo_width = imagesx($logo);//logo图片宽度 
         $logo_height = imagesy($logo);//logo图片高度 
         $logo_qr_width = $QR_width / 5; 
         $scale = $logo_width/$logo_qr_width; 
         $logo_qr_height = $logo_height/$scale; 
         $from_width = ($QR_width - $logo_qr_width) / 2; 
         //重新组合图片并调整大小 
         imagecopyresampled($QR, $logo, 70, 60, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height); 

        } 

        /* 修改数据存储路径 */
        $UpYunPath = $path = "/qrcode/www_5fenghsou.com_{$tid}_{$sellid}.png";
        $sql = " UPDATE tag SET path = '{$path}' WHERE tag_id = '{$tid}'";
        $this->db->execute($sql);
        /**/

        $newPath = PUBLIC_PATH . $path;
        
        //newPath
        imagepng($QR, $newPath);
       
        $upyun = new L\UpYun();
        /**
         * 读取本地地址
         * @var [type]
         */
        $resources = @fopen($newPath,'r');
        
        /**
         * 又拍云 新地址
         * @var [type]
         */
        $arr = $upyun->writeFile($UpYunPath, $resources, true);
        
         
        return $path;
        
    }
    //检测文件是否存在
    function check_remote_file_exists($url) {
        $curl = curl_init($url);
        //不取回数据
        curl_setopt($curl, CURLOPT_NOBODY, true);
        //发送请求
        $result = curl_exec($curl);
        $found = false;
        if ($result !== false) {
            //检查http响应码是否为200
            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);  
            if ($statusCode == 200) {
                $found = true;   
            }
        }
        curl_close($curl);
        return $found;
    }
}
?>