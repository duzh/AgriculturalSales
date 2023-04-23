<?php
namespace Lib;
use Lib\SMS as SMS;
use Lib\DB as DB;
use Lib\Curl as Curl;
use Lib\Clscrypt as Clscrypt;
class Gift{
 	public static function gift($user_id=0,$user_name='',$name='',$time='',$city=''){
 		   $id="mdg_".$user_id;
           $postData = json_encode(['id'=>$id,'city'=>$city,'date'=>date('Y-m-d',$time)], JSON_UNESCAPED_UNICODE);
           $send=self::giftRemoteCall($postData);
           if($send){
	   	        $curl = new Curl();
	            $datas = array(
		            'user_id'=>$user_id,
		            'name'=>$name,
		            'user_name'=>$user_name,
		            'time'=>$time
                );
                if($user_id>0){
	            $data = $curl->Post("http://shops.ync365.com/index/insertgit",$datas);
	            }
	            
           }
           return $send;
	}
	/**

	 * 将订单信息发送给合作方

	 * 请求限时 5 秒，失败重试 3 次，每次间隔 1 秒

	 */
	private static function giftRemoteCall($postData){
      
		self::log($postData);
           
		// http://zxdong262.ngrok.io/export/ync

		$ch = curl_init('http://helper.baotianqi.cn/export/ync');
        
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);

		curl_setopt($ch, CURLOPT_POST, true);

		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$i = 0;

		do{

			$response = curl_exec($ch);

			self::log($response);

			$response = json_decode($response, true);
			if($response["exported"]!=''){
                return true;
			}else{
				return false;
			}
			if( !isset($response['code']) ) sleep(1);

			$i++;

		}while($i < 3 && !isset($response['code']));

		curl_close($ch);

	}
	public static function log($data){
		file_put_contents(PUBLIC_PATH.'/log/wearther.log', date('Y-m-d H:i:s').' '.$data."\r\n", FILE_APPEND | LOCK_EX);
	}

	

}