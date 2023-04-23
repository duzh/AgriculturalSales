<?php
namespace Mdg\Models;
use Lib\Pages as Pages;
use Mdg\Models as M;
use Lib as L;

class UserInfo extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $credit_id;

    /**
     *
     * @var integer
     */
    public $user_id;

    /**
     *
     * @var integer
     */
    public $type;

    /**
     *
     * @var integer
     */
    public $apply_time;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var string
     */
    public $certificate_no;

    /**
     *
     * @var string
     */
    public $phone;

    /**
     *
     * @var integer
     */
    public $province_id;

    /**
     *
     * @var string
     */
    public $province_name;

    /**
     *
     * @var integer
     */
    public $city_id;

    /**
     *
     * @var string
     */
    public $city_name;

    /**
     *
     * @var integer
     */
    public $district_id;

    /**
     *
     * @var string
     */
    public $district_name;
    
    /**
     * 
     * @var integer
     */
    public $town_id;

    /**
     * 
     * @var string
     */
    public $town_name;
    /**
     *
     * @var string
     */
    public $address;

    /**
     *
     * @var integer
     */
    public $se_id;

    /**
     *
     * @var integer
     */
    public $credit_type;

    /**
     *
     * @var string
     */
    public $credit_no;

    /**
     *
     * @var integer
     */
    public $privilege_tag;
    
    /**
     * @var string
    */
    public $se_mobile;

    /**
     * @var string
    */
    public $se_no;

    /**
     *
     * @var integer
     */
    public $verify_time;

    /**
     *
     * @var integer
     */
    public $mobile_type;

    public static $_status = array(
        0=>'未审核',
        1=>'审核通过',
        2=>'审核未通过',
        3=>'认证取消',
        4=>'已删除',
        );
    public static $_type = array(
        0=>'个人',
        1=>'企业',
        2=>'家庭农场',
        3=>'农场合作社');

    public static $user_type = array(
        1 =>'普通会员',
        2=>'县域服务中心',
        4=>'村级服务站',
        8=>'可信农场',
        16=>'采购商');

    /**
     * 县域
     */
    CONST USER_TYPE_HC = 2;
    /* 村站 */
    CONST USER_TYPE_VS = 4;
    /* 可信农场 */
    CONST USER_TYPE_IF = 8;
    /* 采购商*/
    CONST USER_TYPE_PE = 16;
     /* 盟商*/
    CONST USER_TYPE_LWTT = 32;
    
    public static function getdistrict_id($user_id){
        $where='';

        if(!$user_id)  return " u.district_id={$district_id} ";
        $userinfo=self::findFirst(" user_id = '{$user_id}' and credit_type in (2,4) and status=1 ");
        if($userinfo){
                $userarea = UserArea::findFirst("credit_id = {$userinfo->credit_id}");
                if($userarea){
                   if($userinfo->credit_type==2){
                      $district_id=$userarea->district_id;
                      $where.=" u.district_id={$district_id} ";
                   }else{
                      $district_id=$userarea->town_id;
                      $where.=" u.town_id={$district_id} ";
                   }

                }else{
                    $district_id=0.01;
                    $where.=" u.district_id={$district_id} ";
                }
        }else{
            $district_id=0.01;
            $where.="  u.district_id={$district_id} ";
        }
        return $where;

    }
    public static function getlist($where='1=1',$p = 1,$page_size = 10) 
    {           
        $db=$GLOBALS['di']['db'];
        $count=$db->fetchOne(" SELECT count(distinct ui.user_id) as count from user_info as ui left join users as u on ui.user_id=u.id left join users_ext as uext on u.id=uext.uid where {$where} ",2);
        $total=$count["count"];
        
        $offst = intval(($p - 1) * $page_size);
        
        $sql="SELECT distinct ui.user_id,u.username,u.id,u.regtime,uext.name,ui.credit_type from user_info as ui left join users as u on ui.user_id=u.id left join users_ext as uext on u.id=uext.uid where {$where} group by u.username limit {$offst} , {$page_size} ";

        $data=$db->fetchAll($sql,2);
        
        foreach ($data as $key => $value) 
        {   
            $data[$key]["type"]=self::getuserinfo($value['id']);
        }
    
        $pages['total_pages'] = ceil($total / $page_size);
        $pages['current'] = $p;
        $pages['total'] = $total;
        $pages = new Pages($pages);
        $datas['total_count'] = ceil($total / $page_size);
        $datas['total'] = $total;
        $datas['items'] = $data;
        $datas['start'] = $offst;
        $datas['pages'] = $pages->show(2);
        return $datas;
    }
    public static function getuserinfo($user_id){
       
   
        $cond[] = " user_id = '{$user_id}' AND status in (1) ";

        $info = self::find($cond)->toArray();
        if(!$info){
            $arr[]='普通会员';
        }
        foreach ($info as $key => $value) {
           
            if($value['credit_type']){
                $arr[]=self::$user_type[$value['credit_type']];
            }
        }
        if($arr){
            $arr=implode(",",$arr);
            return $arr;
        }
        
        return $arr;
    }

    /**
     * 检测用户身份
     * @param  integer $uid         用户id
     * @param  string  $credit_type 类型
     * @return boolean
     */
    public static function selectBycredit_type($uid=0 , $credit_type = 0) {

        $cond[] = " user_id = '{$uid}' AND credit_type = '{$credit_type}' AND status = '1'";
        return self::count($cond);
    }
    public static function checkpe($uid=0){
        $pe=self::findFirst("user_id={$uid} and credit_type=16 ORDER BY credit_id DESC ");
        if(!$pe){
            return false;
        }
        if($pe->type==0){
            return "采购经纪人";
        }
        if($pe->type==1){
            return "采购企业";
        }
        return false;
    }
    public static function getprivilege_taginfo($id = 0,$type_id = 0){
        $arr['one']=0;
        $arr['two']=0;
        $arr['three']=0;
        $arr['four']=0;
        if(!$id||$type_id){
            return $arr;
        }
        $arr['one']=self::count("user_id = {$id} and credit_id = {$type_id} and privilege_tag&1=1");
        $arr['two']=self::count("user_id = {$id} and credit_id = {$type_id} and privilege_tag&2=2");
        $arr['three']=self::count("user_id = {$id} and credit_id = {$type_id} and privilege_tag&4=4");
        $arr['four']=self::count("user_id = {$id} and credit_id = {$type_id} and privilege_tag&8=8");
        return $arr;
    }
    public static function getmember_typeinfo($id){
        $arr['one']=M\Users::count("id = {$id} and member_type&1=1");
        $arr['two']=M\Users::count("id = {$id} and member_type&2=2");
        $arr['three']=M\Users::count("id = {$id} and member_type&4=4");
        $arr['four']=M\Users::count("id = {$id} and member_type&8=8");
        $arr['five']=M\Users::count("id = {$id} and member_type&16=16");
        return $arr; 
    }

    public static function getprivilege_tags($id=0){
        $arr['user']=0;
        $arr['order']=0;
        $arr['sell']=0;
        $arr['pur']=0;
        if(!$id){
            return $arr;
        }
        $userinfo=self::find("user_id = '{$id}' and credit_type in (2,4) and status=1");
        if(!$userinfo){
           return $arr;
        }else{
            $arr['user']=self::count("user_id = {$id} and privilege_tag&1=1");
            $arr['pur']=self::count("user_id = {$id} and privilege_tag&2=2");
            $arr['sell']=self::count("user_id = {$id} and privilege_tag&4=4");
            $arr['order']=self::count("user_id = {$id} and privilege_tag&8=8");
        }
        return $arr; 
    }

    public static function getUserInfoList($where = ''  , $p = 1  , $psize =10 ) {

        $total = self::count( $where );
        $offst = intval(($p - 1) * $psize);
        $cond[] = $where;
        $cond['order'] = " credit_id desc ";
        $cond['limit'] = array(  $psize, $offst);

        $data = self::find($cond);
  
        $pages['total_pages'] = ceil($total / $psize);
        $pages['current'] = $p;
        $pages['total'] = $total;
        
        $pages = new L\Pages($pages);
        $datas['total'] = $total;
        $datas['items'] = $data;
        $datas['start'] = $offst;
        $datas['pages'] = $pages->show(1);
        return $datas;

    }

    /**
     * 获取用户身份呢
     * @param  integer $uid 用户id
     * @return [type]       [description]
     */
    public static function selectIdentity($uid=0) {
        $cond[] = " user_id = '{$uid}' AND status = 1 ";
        $data = self::find($cond)->toArray();
        $credit_type = array_column($data, 'credit_type');
        $ident = array();
        if(!$credit_type) return '普通会员';
        foreach ($credit_type as $key => $val) {
            $ident[] = isset(self::$user_type[$val]) ? self::$user_type[$val] : '';
        }
        return $ident ? join( ',', $ident) : '普通会员';

    }
    /**
     * 商品详情页
     */
    public static function checkuser_info($uid=0){

        $cond[] = " user_id = '{$uid}' AND status = 1 and credit_type=8 ";
        $data = self::findFirst($cond);

        if($data){
            $user_farm=UserFarm::findFirst("credit_id={$data->credit_id} and user_id={$data->user_id} order by id desc ");
            if(!$user_farm){
              return false;   
            }else{
                $CredibleFarmInfo = M\CredibleFarmInfo::findFirst("user_id={$uid}");
                
                if(!$CredibleFarmInfo){
                    return false;
                }
                $arr["farm_name"]=$user_farm->farm_name;
                $arr["farm_area"]=$user_farm->farm_area;
                //$farmGodosIds   = M\CredibleFarmGoods::find(" user_id={$uid} AND is_recommend = 1 ORDER BY id desc")->toArray();
                $farmGodosIds   = M\UserFarmCrops::find(" user_id={$uid} ")->toArray();

                if(!empty($farmGodosIds)){
                $arr['main_name']=L\Arrays::getCols($farmGodosIds,'category_name',' ');
                }else{
                $arr['main_name']='';
                }
                $arr['address'] =$user_farm->province_name.$user_farm->city_name.$user_farm->district_name.$user_farm->town_name.$user_farm->village_name;
                $arr['farm_id'] = $CredibleFarmInfo->id;
                $arr['logo_pic'] =$CredibleFarmInfo->logo_pic ? IMG_URL.$CredibleFarmInfo->logo_pic : '';
                $arr['url']=$CredibleFarmInfo->url;
                $arr['status']=$CredibleFarmInfo->status;
                return $arr;
            }
        }else{
            return false;
        }

    }
    /**
     *  获取已经整合的可信农场
     * @return [type] [description]
     */
    public static function getlwttlist($user_id,$se_mobile,$catename=0,$issell=0){
        $user_info=self::findFirst(" user_id={$user_id} and credit_type=32  order by credit_id desc ");

        $arr["user_id"]=0;
        $arr["credit_id"]=0;
        $arr["maxcategory_id"]=0;
        $arr["cate_id"]=0;
        $arr["lwtt"]=array();
        $arr["cate_name"]='';
        $arr["farm_area"]=0;
        $arr["lwttcount"]=0;
        if($user_info&&$user_info->status==1){
            $lwtt=self::find(" se_id='{$user_info->credit_id}'and credit_type=8 and status=1 and mobile_type=2 order by credit_id  desc ")->toArray();
           
            if(!empty($lwtt)){
                $arr["user_id"]=L\Func::getCols($lwtt,"user_id",",");
                $arr["credit_id"]=L\Func::getCols($lwtt,"credit_id",",");
                if($lwtt){
                 $user_id=L\Func::getCols($lwtt,"user_id",",");
                 $sql="select maxcategory,category from sell where uid in ($user_id) and state=1 and is_del=0 ";
                }else{
                 $credit_id=L\Func::getCols($lwtt,"credit_id",",");   
                 $sql="select maxcategory,category from user_lwtt as u  join sell as s on u.sell_id=s.id and s.state=1 and s.is_del=0 where u.farm_id in ({$credit_id})";
                }
                $sell=$GLOBALS['di']['db']->fetchAll($sql,2);
                // print_R($lwtt);exit;
                foreach ($lwtt as $key => $value) {
                    $user_farm = M\UserFarm::findFirst("credit_id={$value['credit_id']} ");
                    if($user_farm){
                       $arrs[$key]["farm_name"]=$user_farm->farm_name;
                       $farm_area[]=$user_farm->farm_area;
                    }else{
                       $arrs[$key]["farm_name"]='';
                       $farm_area[]=0;
                    }
                    $arrs[$key]["credit_id"]=$value['credit_id'];
                }
                if(!empty($sell)){
                   $maxcategory_id=L\Func::getCols($sell,"maxcategory",",");
                   $category_id=L\Func::getCols($sell,"category",",");
                }else{
                   $maxcategory_id=0;
                   $category_id=0;
                }
                $arr["maxcategory_id"]=$maxcategory_id;
                $arr["cate_id"]=$category_id;
                $arr["lwtt"]=$arrs;
                $arr["lwttcount"]=count($lwtt);
                $arr["lwttstate"]=1;
                if($catename&&$category_id){
                $sql="select distinct title from category where id in ({$category_id})";
                    $category_name=$GLOBALS['di']['db']->fetchAll($sql,2); 
                    $category_name =$category_name ? $category_id=L\Func::getCols($category_name,"title",",") : '';
                }else{
                    $category_name='';
                }
                $arr["cate_name"]=$category_name;
                $arr["farm_area"]=L\Arrays::SUM($farm_area);
                
                return $arr;
            }
        }
        if($user_info&&$user_info->status==0){
            $arr["lwttstate"]=5;
        }elseif($user_info&&$user_info->status==1){
            $arr["lwttstate"]=1;
        }else{
            $arr["lwttstate"]=0;
        }
        return $arr;
      

    }
    public static function getlwttinfo($user_id){
        $arr=self::findFirst(" user_id={$user_id} and credit_type=32 and status=1 order by credit_id desc ");
        return $arr ? $arr->credit_id : 0;
    }

    static function sellFarm($user_id=0,$cate=0) {
          $user_info=self::getlwttlist($user_id,'',0,1);
          if($cate&&$user_info["lwtt"]){
            $user_id=$user_info['user_id'];
            $credit_id=$user_info['credit_id'];
            $sell=Sell::findFirst(" uid in ({$user_id}) and state=1 and is_del=0 and category={$cate} ");
            if($sell){
               $userinfo=M\UserInfo::find("user_id={$sell->uid} and credit_type=8  and credit_id in($credit_id) ")->toArray();
            }else{
                $userinfo=array();
            }
            if($userinfo){
                foreach ($user_info["lwtt"] as $k => $v) {
                    foreach ($userinfo as $key => $value) {
                         if($value['credit_id']==$v["credit_id"]){
                            $arr[]=$v;
                         }
                     }
                }
            }else{
                $arr=$user_info["lwtt"];
            }
          }else{
            $arr=$user_info["lwtt"];
          }
          return $arr;

    }
    
    /**
     *个人中心已整合的可信农场
     * @return [type] [description]
     */
    public static function getConformitUsers($where = ' 1=1', $p = 1, $page_size = 10,$credit_type = 1) 
    {   

        $total = M\UserInfo::count($where);
       
        $offst = intval(($p - 1) * $page_size);
        $data = M\UserInfo::find($where . "  ORDER BY add_time DESC limit {$offst} , {$page_size} ")->toArray();     
 
        if($data){
            foreach ($data as $key => $value) 
            {

                $data[$key]['userfarm'] = array();
                $data[$key]['category_name'] = array();
                $data[$key]['sell_total'] = array();
                $data[$key]['picture_path'] = array();
                //可信农场信息
                $userfarm=M\UserFarm::findFirstBycredit_id($value['credit_id']);
                //$data[$key]['desc']=($userfarm&&$userfarm->describe) ? L\Utils::c_strcut($userfarm->describe,32) : ''; 
                if($userfarm) $data[$key]['userfarm'] = $userfarm->toArray();

                //可信农场主要农作物
                $userfarm=M\UserFarmCrops::findFirst("credit_id = {$value['credit_id']}");
                if($userfarm) $data[$key]['category_name'] = $userfarm->category_name;                
                //可信农场的照片信息
                $credit_farm_picture = M\UserFarmPicture::findFirst("credit_id = {$value['credit_id']} AND type=0");
                if($credit_farm_picture) $data[$key]['picture_path'] = $credit_farm_picture->picture_path;
               
                //已整合的可信农场供应信息
                $sell_total = Sell::count(" uid in ({$value['user_id']}) and is_del = '0' and state = '1'");                   
                $data[$key]['sell_total'] = $sell_total ;
                             
            
            
            }
        }else{
            $data = array();
        }
        
        $pages['total_pages'] = ceil($total / $page_size);
        $pages['current'] = $p;
        $pages['total'] = $total;
        $total = $pages['total_pages'];
        $pages = new Pages($pages);
        $datas['total'] = $total;
        $datas['items'] = $data;
        $datas['start'] = $offst;
        $datas['pages'] = $pages->show(2);
        return $datas;
    }
    /**
     *已整合的可信农场发布的供应信息
     *@return [type] [description]
     */
    public static  function supplyInfo($credit_id = '', $p = 1, $page_size = 10)
    {
        $offst     = intval(($p - 1) * $page_size);
        $data['sell']   = array();
        //查找可信农场的供应信息
        $user_lwtt = M\UserInfo::findFirst("credit_id = {$credit_id} ");
        if($user_lwtt){
            $user_id=$user_lwtt->user_id;
        }else{
            $user_id=0;
        }
        $total     =  M\Sell::count(" uid = ({$user_id}) and is_del = '0' and state ='1'");     
        $sell_info = M\Sell::find(" uid = ({$user_id}) and is_del = '0' and state ='1' limit {$offst},{$page_size} ");
        if($sell_info){$data['sell'] = $sell_info->toArray();}
        //查找供应订单信息
        foreach ($data['sell'] as $k => $v) {
  
            $data['sell'][$k]['orders'] =  M\Orders::count("sellid = {$v['id']} and state >= 4 ");
        }

        $pages['total_pages'] = $toatl_page = ceil($total / $page_size);       
        $pages['current'] = $p;
        $pages['total'] = $total;
        $pages = new Pages($pages);
        $pages = $pages->show(2); 
        $data['pages'] = $pages; 
        $data['total'] = $toatl_page = ceil($total / $page_size);  
        return $data;       
    }
}
