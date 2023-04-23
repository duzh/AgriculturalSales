<?php
namespace Mdg\Models;
use Mdg\Models as M;
use Lib as L;

class Shop extends \Phalcon\Mvc\Model
{
    /**
     *
     * @var integer
     */
    
    public $shop_id;
    /**
     *
     * @var string
     */
    
    public $shop_no;
    /**
     *
     * @var string
     */
    
    public $shop_name;
    /**
     *
     * @var integer
     */
    
    public $user_id;
    /**
     *
     * @var integer
     */
    
    public $business_type;
    /**
     *
     * @var integer
     */
    
    public $user_type;
    /**
     *
     * @var string
     */
    
    public $shop_type;
    /**
     *
     * @var integer
     */
    
    public $shop_status;
    /**
     *
     * @var string
     */
    
    public $main_product;
    /**
     *
     * @var string
     */
    
    public $contact_man;
    /**
     *
     * @var string
     */
    
    public $contact_phone;
    /**
     *
     * @var integer
     */
    
    public $province_id;
    /**
     *
     * @var integer
     */
    
    public $city_id;
    /**
     *
     * @var integer
     */
    
    public $county_id;
    /**
     *
     * @var integer
     */
    
    public $town_id;
    /**
     *
     * @var integer
     */
    
    public $village_id;
    /**
     *
     * @var integer
     */
    
    public $add_time;
    /**
     *
     * @var integer
     */
    
    public $last_update_time;
    /**
     * 用户类型
     * @var array
     */
    // public static $_user_type =  array(
    //             1 => '个人',
    //             2 => '个体户',
    //             3 => '企业'
    //     );
    
    /**
     * 店铺类型
     * @var array
     */
    
    public static $_business_type = array(
        1 => '个人',
        2 => '个体户',
        3 => '企业'
    );
    /**
     * 店铺用户类型
     * @var array
     */
    
    public static $_shop_type = array(
        0 => '种植户',
        1 => '养殖户',
        2 => '批发经销商',
        3 => '农业经纪人',
        4 => '合作社',
        5 => '加工企业',
        6 => '贸易企业',
        // 7 =>'其他',
        
    );
    /**
     * 店铺状态
     * @var array
     */
    
    public static $_shop_state = array(
        0 => '待审核 ',
        1 => '已审核通过 ',
        2 => '审核未通过',
    );
    /**
     * 店铺状态
     * @var array
     */
    
    public static $_service_type = array(
        2 => '个体户',
        3 => '企业'
    );
    CONST UNAUDIT = 0; //待审核
    CONST AUDITVIA = 1; //审核通过
    CONST NOTPASS = 2; //审核未通过
    const SHOP_ERROR = 100001; //店铺错误
    const SHOP_STATE_ERROR = 100002; //店铺状态错误
    
    /**
     * save 修改数据
     * @return [type] [description]
     */
    
    public function beforeSave() 
    {
        $this->last_update_time = time();
    }
    /**
     *
     * @return [type] [description]
     */
    
    public function afterFetch() 
    {
        $this->addtime = $this->add_time ? date('Y-m-d H:i:s', $this->add_time) : 0;
    }
    /**
     * 获取服务站编号
     * @param  integer $shopid 服务站id
     * @return string
     */
    public static function getShopNo ($shopid=0) {
     
        $no = sprintf('HC10365%05u', $shopid);
        $laststr = substr($no, -1);
        $i = 0;
        while(in_array( $laststr, array(3,6,5)) ) {
                $length = 5 - strlen($shopid);
                $random = L\Func::random($length, 1);
                $shopid= $shopid.$random;

                $no = sprintf('HC10365%05u', $shopid);
                $laststr = substr($no, -1);
               if($i > 100 ){
                break;
               }
               $i++;

        }
        return $no;
    }
    /**
     * 初始化数据
     * @return [type] [description]
     */
    
    public function initialize() 
    {
        $this->hasOne('shop_id', 'Mdg\Models\ShopCredit', 'shop_id', array(
            'foreignKey' => true,
            'alias' => 'credit'
        ));
        $this->hasOne('shop_id', 'Mdg\Models\ShopCoods', 'shop_id', array(
            'foreignKey' => true,
            'alias' => 'shopgoods'
        ));
    }
    /**
     * 获取店铺详细信息
     * @param  integer $sid 店铺id
     * @return array
     */
    
    public static function getShopInfo($sid = 0) 
    {
        $data = self::findFirst(" shop_id = '{$sid}'");
        
        if (!$data) return array();
        return $data;
    }
    /**
     * 检测店铺状态信息
     * @param  integer $id    店铺id
     * @param  integer $state 对比状态
     * @param  [type]  $db    db链接
     * @return array
     */
    
    public function checkShopState($id = 0, $state = 0, $db = null) 
    {
        
        if (!$id) throw new \Exception(self::SHOP_ERROR);
        $data = array();
        $sql = " SELECT * FROM %s where %s for update ";
        $table = 'shop';
        $qsql = sprintf($sql, $table, " shop_id = '{$id}'");
        $data = $db->fetchOne($qsql, 2);
        
        if (!$data || $data['shop_status'] != $state) throw new \Exception(self::SHOP_STATE_ERROR);
        return $data;
    }
    
    public static function getList($user_id = 0) 
    {
        $data = self::find("user_id={$user_id} ")->toArray();
        
        foreach ($data as $k => $v) 
        {
            $data[$k]['shop_id'] = $v['shop_id'];
        }
        return $v;
    }
    /**
     *  获取店铺列表
     * @param  array  $cond 店铺条件
     * @return array
     */
    public static function getRecShopList($cond = array()) {
        $data = self::find($cond)->toArray();
        /**
         * 获取店铺推荐图片
         * @var [type]
         */
        $image = new M\Image();
        foreach ($data as $key => $val) {
            $data[$key]['imagesList'] = $image->getImagesList($val['shop_id'], 23) ;
        }
        return $data;       
    }
    /**
     * 首页服务站数据
     * @return [type] [description]
     */
    
    public static function getServiceList() 
    {
        $cond[] = " shop_status = 1 AND is_service = 1  ";
        $cond['columns'] = " shop_id, user_id, village_id ";
        $cond['order'] = ' shop_id DESC';
        $data['total'] = self::count($cond); //总量
        // $cond['limit'] = array( 10, 1 );
        $data['serviceList'] = self::find($cond)->toArray();
        //获取服务站姓名与地址
        
        foreach ($data['serviceList'] as & $u) 
        {
            $user = M\Users::getFshUsers($u['user_id']);
            $u['realname'] = isset($user->ext->name) ? $user->ext->name : '';
            $u['username'] = isset($user->username) ? $user->username : '';
            $areas = L\AreasFull::getAreasFull($u['village_id']);
            $areas = join('', array_column($areas, 'name'));
            $u['areas'] = $areas;
        }
        return $data;
    }

    /**
     * 根据 用户id 获取服务站
     * @param  integer $sid 商品id
     * @return 
     */
    public static function getServiceStation($uid=0) {

        $data = array();

        if(!$uid) return array();
        $cond[] = " user_id = '{$uid}' AND shop_status = 1 AND is_service = 1  ";
        // $cond['columns'] = "shop_id, shop_no, shop_name, contact_man, contact_phone, village_id, shop_link";
        $service = self::findFirst($cond);
        if(!$service) return $data;
        $data = $service->toArray();
        //获取服务商介绍 以及 实地图片
        $data['desc'] = $service->credit->service_desc;
        $data['shop_desc'] = $service->credit->shop_desc;
        $data['personal_logo_picture'] = IMG_URL.$service->credit->personal_logo_picture;
        $data['picList'] = M\ServicePic::getServiceStaionImg($service->shop_id);
        
        return $data;        
    }

    public function saveSeenGoods() {
        
    }
    /**
     * 根据用户id 判断用户是否开店
     * @param  integer $uid 用户id
     * @return boolean
     */
    public static function selectByUser_idtoIsshop ($uid=0) {
        $cond[] = " user_id = '{$uid}' AND shop_status = 1 AND is_service = 0 " ;
        $cond['columns'] = " shop_link ";
        $data = self::findFirst($cond);
        return $data ? $data->shop_link : '';
    }

    /**
     * 获取可信店铺
     * @param  integer $limit 条数 0 不限
     * @return array
     */
    public static function getStoreList($limit = 0) {
        $data = array();
        $cond[] = " is_recommended = '1' AND shop_status = 1";
        $total = self::count($cond);
        if($limit) $cond['limit'] = $limit;
        $data = self::find($cond)->toArray();

        foreach ($data as $key => $val) {
            $data[$key]['url'] = "http://{$val['shop_link']}.5fengshou.com"; 
            $img23 = M\Image::findFirst(" gid = '" . $val['shop_id'] . "' AND type = 23 ");
            if ($img23 && $img23->agreement_image) 
            {
                $data[$key]["img"] = IMG_URL . $img23->agreement_image . '!180';
                continue;
            }
            $img12 = M\Image::findFirst(" gid = '" . $val['shop_id'] . "' AND type = 12 ");
            if ($img12 && $img12->agreement_image) 
            {
                $data[$key]["img"] = IMG_URL . $img12->agreement_image . '!180';
                continue;
            }
            $data[$key]["img"] = STATIC_URL . '/mdg/images/shop_banner.jpg';
        }

        return array($data, $total);
    }   


}
