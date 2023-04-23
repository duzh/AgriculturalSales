<?php
//2015-8-26 17:10 产品沟通去掉上传限制 修改人-祁耀博
namespace Mdg\Frontend\Controllers;

use Lib\Path as Path;
use Lib\File as File;
use Mdg\Models\TmpFile as TmpFile;
use Mdg\Models\Image as Image;
use Mdg\Models\Advisory as Advisory;
use Lib\UpYun as UpYun;
use Lib\Cropavatar as Cropavatar;
use Mdg\Models as M;
use Lib as L;
use Mdg\Member\Controllers\UserfarmController as CUSER_FARM;

class UploadController extends ControllerBase
{
	public function tmpfileAction() {
		
		$rs = array('status'=>false, 'msg'=> '上传成功');
		$sid    = $this->request->getPost('sid', 'string', '');
		$type   = $this->request->getPost('type', 'int', 0);
		$isclos = $this->request->getPost('isclos', 'int', 1);
		$plant  = $this->request->getPost('plant', 'string', '');
		$shopId = $this->request->getPost('shopId', 'int', 0);
		$tag    = $this->request->getPost('tag', 'int', 0);
		$member = $this->request->getPost('member', 'int', 0);
		$count  = $this->request->getPost('count','int',0);
		$memberkey  = $this->request->getPost('key','string','');
		$adtype  = $this->request->getPost('adtype','int',0);
		$position  = $this->request->getPost('position','int',0);
		$user_id = $this->getUserID();
		$data = $_FILES['Filedata'];
		if($data['error']) {
			$rs['msg'] = '上传失败！';
			die(json_encode($rs));
		}
		$count_info=TmpFile::count("sid='{$sid}' and type = 36");
		$count_infotwo=M\CredibleFarmPicture::count("type = 4 and user_id = {$user_id}");
		$count_info = $count_info+$count_infotwo;

		if($type==1){
			$imgsize=getimagesize($data['tmp_name']);

			if($imgsize[0]<400&&$imgsize[1]<400){
				$rs['msg'] = '图片大小不符！';
			    die(json_encode($rs));
			}
		}
        if($type==32&&$count>5){
            $rs['msg'] = '农场图片最多上传5张';
			die(json_encode($rs));
        }
        if($type==42&&$count>5){
            $rs['msg'] = '耕地合同最多上传5张';
			die(json_encode($rs));
        }
		if($type==36&&$count_info==3){
			$rs['msg'] = '已超过上传次数';
			die(json_encode($rs));
		}
		if($data['size']>2048000){
			$rs['msg'] = '图片大小不得大于2M！';
			die(json_encode($rs));
		}
		if($adtype==1 && $position!= 9){
            $imgsize=getimagesize($data['tmp_name']);
			if($imgsize[0]==200&&$imgsize[1]==200){
			}else{
				$rs['msg'] = '图片大小不符！';
			    die(json_encode($rs));
			}
		}
		if($adtype==1 && $position==9){
            $imgsize=getimagesize($data['tmp_name']);
			if($imgsize[0]==400&&$imgsize[1]==200){
			}else{
			    $rs['msg'] = '图片大小不符！';
			    die(json_encode($rs));
			}
		}

		if($adtype==2){
            $imgsize=getimagesize($data['tmp_name']);
			if($imgsize[0]==400&&$imgsize[1]==200){
			}else{
				$rs['msg'] = '图片大小不符！';
			    die(json_encode($rs));
			}   
		}
		if($type==12){
			$imgsize=getimagesize($data['tmp_name']);
			if($imgsize[0]!=760&&$imgsize[1]!=250){
				$rs['msg'] = '图片大小不符！';
			    die(json_encode($rs));
			}
		}
		if($type==35 || $type==36 || $type==37 || $type==38 || $type==40){
			if($data['size']>1024000){
				$rs['msg'] = '图片大小不得大于1M！';
				die(json_encode($rs));
			}
		}
		if(strlen($plant)) {
			$type  = "26_{$plant}";
		}
   
		switch ($type) {
			case '1':
			$path = Path::imagePath();//sell图片
				break;
			case '2':
			$path = Path::categroyPath();//categroy大图片
				break;
			case '3':
			$path = Path::categroyminPath();//categroy小图片
				break;
			case '4':
			$path = Path::adPath();//广告位图片
				break;
			case '6':
			$path = Path::bankPath();//银行正面照
			TmpFile::clearall($sid,$type);
				break;
			case '7':
			$path = Path::identity_card_frontPath();//身份证正面照
			TmpFile::clearall($sid,$type);
				break;
			case '8':
			$path = Path::identity_picture_licPath();//持身份证正面头部照
			TmpFile::clearall($sid,$type);
				break;
			case '9':
			$path = Path::identity_card_backPath();//身份证背面照
			TmpFile::clearall($sid,$type);
				break;
			case '10':
			$path = Path::tax_registrationPath();//企业税务登记证照
			TmpFile::clearall($sid,$type);
			   break;
			case '11':
			$path = Path::organization_codePath();//组织机构代码证
			TmpFile::clearall($sid,$type);
				break;
			case '12':
			$path = Path::mapPath();//店铺宣传图
			TmpFile::clearall($sid,$type);
				break;
			case '13':
			$path = Path::mobliePath();//店铺宣传图
			TmpFile::clearall($sid,$type);
				break;
			case '21':
			$path = Path::personal_logoPath();//个人照或者logo
			TmpFile::clearall($sid,$type);
				break;
			case '22':
			$path = Path::servicePic();//服务站实地照片
				break;
			case '23':
			$path = Path::shoprec();//店铺推荐图片
			TmpFile::clearall($sid,$type);
				break;
			case '24':
			$path = Path::tagcertifying_file();//标签机构文件
			// TmpFile::clearall($sid,$type);
				break;
			case '25':
			$path = Path::originPath();//产地照片
				break;
			case "26_{$plant}":
	
			$path = Path::plantPath();//标签机构文件
			$type = "26_{$plant}";
				break;
			case "27":
			$path = Path::HeadPath();//个人头像
			    break;
			case "28":
			$path = Path::AdvisoryPath();//咨询图片
			    break;
			case 29:  //29
			$path = Path::bankcardPath();//个人中心-申请农场主银行卡
				break;
			case 30: //30
			$path = Path::memberbankcardPath();//个人中心-个人手持身份证照
			TmpFile::clearall($sid,$type);
				break;
			case 31: //31
			$path = Path::membercardPath();//个人中心-身份证
			TmpFile::clearall($sid,$type);
			$isclos=0;
			    break;
			case 32: //32
			$path = Path::memberpicturePath();//个人中心-用户农场
			$isclos = 0 ;
				// TmpFile::clearall($sid,$type);
			    break;
			case 33: //33
			$path = Path::memberidentityPath();//个人中心-营业执照
			TmpFile::clearall($sid,$type);
			$isclos=0;
			    break;
			 case 34: //33
			$path = Path::membercardbackPath();//个人中心-身份证背面
			TmpFile::clearall($sid,$type);
			$isclos=0;
			    break;
			 case 35:
			$path = Path::membercardbackPath();//个人中心-可信农场logo
			TmpFile::clearall($sid,$type);
				break;
			 case 36:
			 $path = Path::membercardbackPath();//个人中心-可信农场-宣传图
			 	break;
			 case 37:
			 $path = Path::membercardbackPath();//个人中心-可信农场-农场介绍
			 TmpFile::clearall($sid,$type);
			 break;
			 case 38:
			 $path = Path::membercardbackPath();//个人中心-可信农场-发展足迹
			 TmpFile::clearall($sid,$type);
			 break;
			 case 39 :
			 $path=Path::picturnwallPath();
			 TmpFile::clearall($sid,$type);
			 break;
			 case 40 :
			 $path=Path::picturnwallPath();//个人中心-可信农场-资质认证
			 TmpFile::clearall($sid,$type);
			 break;
			 case 41 : 
			 $path=Path::picturnwallPath();//个人中心-可信农场-种植过程
			 TmpFile::clearall($sid,$type);
			 break;
             case 42 : 
			     $path=Path::identity_picture_orgtaxPath();//个人中心-组织机构证或税务证
			 $isclos = 0 ;
			     break;
			 case 43 : 
			     $path=Path::crediblefarminfopath();//个人中心-组织机构证或税务证
			 TmpFile::clearall($sid,$type);
			     break;
			default:
				break;
		}

		//上传图片
		$data = File::move_file($data, PUBLIC_PATH.$path, true);
		
		if(!$data["status"]){
             $rs['msg'] =$data["msg"];
			die(json_encode($rs));
		}
		$tmppath = str_replace(PUBLIC_PATH, '', $data['path']);
		
		//上传U盘云
		$this->upyunfile($data['path'],$tmppath);
		
        //上传本地 
        $img_id=$this->moveTmpFile($data['realname'],$tmppath,$type,$sid);

		if(!$img_id) {
			$rs['msg'] = '上传失败！';
			die(json_encode($rs));
		}

        if($type==2||$type==3){
        //$html='<div class="imgs f-fl f-pr"><a class="close-btn" href="javascript:; onclick="closeImg(this, %s);"></a><img src="%s" height="120" width="120" alt=""></div>';
        $html = '<dl><dt><img src="%s" width="190" height="230"><a href="javascript:;" onclick="closeImg(this, %s);">删除</a></dt></dl>';	
        }elseif ($type == 12 ){
        	//保存数据库
        	$te = false;
        	if($shopId) {
        		$te = Image::saveImageMap($shopId , $tmppath);
        	}
        	
        	$html = '<dl><dt><img src="%s" width="760" height="250"><a href="javascript:;" onclick="closeImg(this, %s);"></a></dt></dl>';	

        }else{
        $close = '删除';
        if($isclos)$close = "";
        $html = "<div class='imgBox f-fl'> <div class='imgs f-oh'><img src='%s' width='137' height='103'></div><a href='javascript:;' onclick='closeImg(this, %s);'>{$close}</a></div>";	
        }
        if($tag) {
        	$html = "<div class='imgBox f-fl'> <div class='imgs f-oh' id='dl_{$img_id}'><img src='%s' width='201' height='201'><a href='javascript:closeImg(this, %s)'; >删除</a></div></div>";
        	//$html = "<dl id='dl_{$img_id}' ><dt ><img src='%s' width='201' height='201'><a href='javascript:closeImg(this, %s)'; >删除</a></dt></dl>";
        }
        if($type==1){
            $html='<div class="imgs f-fl f-pr"><a class="close-btn" href="javascript:;"  onclick="closeImg(this, %s)" ></a><img src="%s" height="120" width="120" alt=""></div>';
        }  
        if($member) {
        	$tpl = '';
    		if(!$isclos) $tpl = "<a class='close-btn' href='javascript:;' onclick='closeImg(this, %s,  \"{$memberkey}\");'></a>";
    		$html = "<div class='imgs f-fl f-pr' id='dl_{$img_id}' >{$tpl}<img src='%s' height='145' width='120'></div>";	
        }
        if($type==35||$type==43){
           $html=' <div class="imgs f-fl f-pr"><a class="close-btn" href="javascript:;" onclick="closeImgup(this, %s);" ></a><ul class="gallery"><li><a href="%s"><img src="%s" height="77" width="157" alt=""></a></li><li style="height:1px;"><a href=""><img style="opacity:0; filter:alpha(opacity:0);" src="" alt=""></a></li></ul></div>';
        }
        if($type==36){
        	$html=' <div class="imgs f-fl f-pr"><a class="close-btn" href="javascript:;" onclick="closeImgup(this, %s);" ></a><ul class="gallery"><li><a href="%s"><img src="%s" height="120" width="120" alt=""></a></li><li style="height:1px;"><a href=""><img style="opacity:0; filter:alpha(opacity:0);" src="" alt=""></a></li></ul></div>';
        }
        if($type==1||!$isclos){
	    	$data['html'] = sprintf($html,$img_id,IMG_URL.$tmppath);
	    }else{
		    if($type==36||$type==35||$type==43){
	           $data['html'] = sprintf($html,$img_id,IMG_URL.$tmppath,IMG_URL.$tmppath);
		    }else{
	    	$data['html'] = sprintf($html,IMG_URL.$tmppath,$img_id);
	        }
	    }
	    
		$data['total'] = M\TmpFile::count(" sid = '{$sid}' AND type = '{$type}'");
		$data["path"]=IMG_URL.$tmppath;
		$data['id'] = $img_id;
		die(json_encode($data));

	}

	/**
	 * 删除图片
	 * @return [type] [description]
	 */
	public function deltmpfileAction() {
		$rs = array('state'=>true, 'msg'=>'删除成功！');
		$id = $this->request->get('id', 'int', 0);

		$tmpFile = TmpFile::findFirstByid($id);

		if($tmpFile) {
			@unlink(PUBLIC_PATH.$tmpFile->file_path);
			$tmpFile->delete();
		}

		die(json_encode($rs));
	}

	/**
	 * 删除临时图片
	 * @return [type] [description]
	 */
	public function deleteImgAction(){
	   $rs = array('state'=>true, 'msg'=>'删除成功！');
       $id = $this->request->get('id', 'int', 0);

       $type = $this->request->get('type', 'int', 0);   
       if($type==0){
          $image=Image::findFirst("gid={$id} and type=0 ");
          if($image){
          	@unlink(PUBLIC_PATH.$image->agreement_image);
          	$image->delete();
          }  
       }
       if($type==1){
          $image=Image::findFirst("gid={$id} and type=1 ");
          if($image){
          	@unlink(PUBLIC_PATH.$image->agreement_image);
          	$image->delete();
          }  
       }
	   if($type==28){
          $image = Advisory::findFirstByid($id);
          if($image){
          	$image->delete();
          }  
       }
       /**
        * 可信农场
        * @var [type]
        */
       if(intval($type) == 30) {
       		if($info  = M\UserFarmPicture::findFirstByid($id)) {
       			@unlink(PUBLIC_PATH.$info->picture_path);
       			$info->delete();
       		}
       }
       die(json_encode($rs));

	}
	/**
	 *  分类选择图片 传入临时表 显示
	 * @return [type] [description]
	 */
	public function tmpcatefileAction() {
		$rs = array('status'=>true, 'msg'=> '上传成功');
		$sid = L\Validator::replace_specialChar($this->request->get('sid', 'string', ''));
		$type = $this->request->get('type', 'int', 5);
		$selectval = $this->request->get('selectval', 'int', 0);
        $image=Image::imgmaxedit($selectval);
        
		
		$info = TmpFile::find("sid='{$sid}' and type=5 ");

		foreach ($info as $val) {
			@unlink(PUBLIC_PATH.$val->file_path);
			
		}
		$info->delete();
	
		if(empty($image)){
			echo false;die;
		}

		$path = Path::imagePath();
        
		$path=substr($path,1,strlen($path));
       
		if (!file_exists($path)) File::make_dir($path);
        $imgsrc=IMG_URL.$image['agreement_image'];

        $ext = pathinfo($imgsrc, PATHINFO_EXTENSION);
        $taget = $path.File::random(12 , 1).'.'.$ext;
   
        $jpg=base64_encode($imgsrc);
        
        if(!@copy("$imgsrc","$taget"))
		{
		    echo false;die;
		} 
		$tagets='/'.$taget;
	    //插入临时表
		$tmpFile = new TmpFile();
		$tmpFile->src_name ="categroyimg";
		$tmpFile->file_path =$tagets;
		$tmpFile->sid = $sid;
		$tmpFile->addtime = time();
        $tmpFile->type='5';
		if(!$tmpFile->save()) {
			echo false;die;
		}
		$this->upyunfile(PUBLIC_PATH.$tmpFile->file_path,$tmpFile->file_path);
		$html = " <div id='categroyimg' class='imgBox f-fl'> <div class='imgs f-oh'><img class='f-fl' src='%s' width='200' height='200'></div><a class='close-btn' href='javascript:;' onclick='closeImg(this, %s);'>删除</a></div>";	
		//$html = '<dl id="categroyimg" ><dt><img src="%s"  width="200" height="200"><a href="javascript:;" onclick="closeImg(this, %s);">关闭</a></dt></dl>';
		//$html = '<div id="categroyimg" class="imgBox f-fl"><div class="imgs f-oh"><img class="f-fl" src="%s"  width="200" height="200"></div><a class="close-btn" href="javascript:;" onclick="closeImg(this, %s);">关闭</a></div>';
		$htmls = sprintf($html,IMG_URL.$tagets,$tmpFile->id);
	    echo $htmls;die;

	}
	/**
	 *   上传u盘云
	 * @param  [type] $path       [原路径]
	 * @param  [type] $publicpath [新路进]
	 * @return [type]             [description]
	 */
	public function  upyunfile($path,$publicpath){
    
        //上传upy
        $upyun = new UpYun();
     
		try{
          $test = @fopen($path,'r');
          $pathaa =$publicpath;
          $arr = $upyun->writeFile($pathaa, $test, true);
        }
        catch(Exception $e) {
            return $e->getCode().$e->getMessage();
        }
	}
	/**
	 * 上传本地
	 */
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
    public function thumbthreeAction(){
    	  
          $sessionid = $this->session->getId();
          $sid = $this->request->getPOST('sid', 'string', $sessionid);
	      $type= $this->request->getPOST('imgtype', 'int',27);
	      switch ($type) {
	          case 27: //32
	          $path = Path::memberpicturePath();//个人中心-用户农场
	          TmpFile::clearall($sid,$type);
	            break;
	      }
          $path = Path::memberpicturePath();//个人中心-用户农场
		  $crop = new Cropavatar(
		    isset($_POST['avatar_src']) ? $_POST['avatar_src'] : null,
		    isset($_POST['avatar_data']) ? $_POST['avatar_data'] : null,
		    isset($_FILES['avatar_file']) ? $_FILES['avatar_file'] : null,
		    $path
		  );
          

		  $response = array(
		    'state'  => 200,
		    'message' => $crop -> getMsg(),
		    'result' => $crop -> getResult()
		  );
		  if($crop -> getMsg()==''){
			 $tmppath = $crop -> getResult();
	         $upyun=$this->upyunfile(PUBLIC_PATH.$tmppath,$tmppath);         
	         $imgid = $this->moveTmpFile("裁剪图片", $tmppath, $type, $sid, 0);
	         $user_id = $this->getUserID();
	         $userext=M\UsersExt::findFirstByuid($user_id);
	         if($userext){
	         	$userext->picture_path=$tmppath;
	         	$userext->save();
	         }
		  }
		  echo json_encode($response);die;
    }
	
}