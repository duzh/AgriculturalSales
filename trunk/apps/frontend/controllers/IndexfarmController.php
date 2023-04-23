<?php
namespace Mdg\Frontend\Controllers;
use Lib\Member as Member;
use Lib\Auth as Auth, Lib\Arrays as Arrays;
use Mdg\Models as M;
use Mdg\Models\Category as Category;
use Lib as L;

class IndexfarmController extends ControllerKx
{
    /**
	 *	农场首页
	 */
    public function indexAction() {
		
		// 根据域名获取用户ID
		$user_id	= $this->session->farm_user_id;
		if(!$user_id){
			echo "<script>alert('数据异常');location.href='/index'</script>";
            exit;
		}
		// 获取轮播
		$recomLogon	= M\CredibleFarmPicture::find(" status = 1 AND type = 4 AND user_id=$user_id ORDER BY id desc ")->toArray();

		// 农场介绍
		$farmDetail	= M\CredibleFarmPicture::find(" status = 1 AND type = 0 AND user_id=$user_id ORDER BY id desc limit 3")->toArray();

		// 基地位置
		$areadetail	= M\CredibleFarmInfo::findFirst(" status = 1 AND user_id=$user_id AND home_page_order!=0 ");
		if($areadetail){
			$areadetail	= $areadetail->toArray();
		} else {
			$areadetail	= array();
		}
		
		// 发展足迹
		$footprint	= M\CredibleFarmPicture::find(" status = 1 AND type = 1 AND user_id=$user_id ORDER BY picture_time asc ")->toArray();
		$count		= M\CredibleFarmPicture::count(" status = 1 AND type = 1 AND user_id=$user_id ");
		
		// 主营产品
		$farmGodosIds	= M\CredibleFarmGoods::find(" user_id=$user_id AND is_recommend = 1 ORDER BY id desc")->toArray();
		$sellIds		= implode(',',array_column($farmGodosIds,'sell_id'));
		$sellGoods = array();
		if($sellIds){
			$sellGoods		= M\Sell::find(" is_del=0 AND state=1 AND id in($sellIds) ")->toArray();
		}
		
		if( $sellGoods ){
			// 处理图片
			foreach($sellGoods as $key=>$val) {
				if($val['thumb']) {
					$sellGoods[$key]['thumb']	= IMG_URL . $val['thumb'];
				} elseif($val['category'] && $image	= M\Image::imgsrc($val['category'])) {
					$sellGoods[$key]['thumb']	= M\Image::imgsrc($val['category']);
				}else{
					$sellGoods[$key]['thumb']	= 'http://static.ync365.com/mdg/images/detial_b_img.jpg';
				}
			}
		}
       
		// 传值
		$this->view->logo		= $recomLogon;
		$this->view->farm		= $farmDetail;
		$this->view->area		= $areadetail;
		$this->view->goods		= $sellGoods;
		$this->view->goods_unit = M\Purchase::$_goods_unit;
		$this->view->footprint	= $footprint;
		$this->view->cout		= $count;
		$this->view->title		= '可信农场首页';
    }

    /**
     *  供应收藏
     *  @param  [type]  userId  用户ID
     *  @param  [type]  sellId  卖货ID
     *  @return json
     */
    
    public function collectFarmAction() 
    {
        // 获取参数
        $userId = isset($this->session->user['id']) ? $this->session->user['id'] : 0;
        $farmid = isset($_REQUEST['farmId']) ? intval($_REQUEST['farmId']) : 0;
        // 校验参数
        if (!$userId) 
        {
            echo json_encode(array(
                'code' => 0,
                'result' => '请登录'
            ));
            exit;
        }
        
        if (!$farmid) 
        {
            echo json_encode(array(
                'code' => 1,
                'result' => '参数有误'
            ));
            exit;
        }

        $credible_farm_info = M\CredibleFarmInfo::findFirst("id={$farmid}");
        
        if ($credible_farm_info->user_id==$userId) 
        {
            echo json_encode(array(
                'code' => 2,
                'result' => '不能收藏自己的农场哦'
            ));
            exit;
        }
        // 判断用户是否已收藏
        $isCollect = M\CollectStore::findFirst("store_id='{$farmid}' and collect_uid='{$userId}'");
        
        if (!$isCollect) 
        {
            // 根据农场ID获取农场信息
            $farmInfo = M\CredibleFarmInfo::findFirst("id={$farmid}");
            $user_farm_crops = M\UserFarmCrops::find("user_id = {$credible_farm_info->user_id}")->toArray();
            $main_products = L\Arrays::getCols($user_farm_crops, 'category_name', ',');
            // 入库收藏表
            $CollectStore = new M\CollectStore();
            $CollectStore->store_id = $farmid;
            $CollectStore->store_name = $farmInfo->farm_name;
            $CollectStore->main_products = $main_products;
            $CollectStore->collect_uid = $userId;
            $CollectStore->add_time = time();
            $CollectStore->last_update_time = time();
            
            if (!$CollectStore->save()) 
            {
                echo json_encode(array(
                    'code' => 3,
                    'result' => '收藏失败'
                ));
                exit;
            }
            else
            {
                echo json_encode(array(
                    'code' => 4,
                    'result' => '收藏成功'
                ));
                exit;
            }
        }
        else
        {
			$delFarm = $isCollect->delete();
            if (!$delFarm) 
            {
                echo json_encode(array(
                    'code' => 5,
                    'result' => '取消失败'
                ));
                exit;
            }
            else
            {
                echo json_encode(array(
                    'code' => 6,
                    'result' => '取消成功'
                ));
                exit;
            }	
        }
    }
}
