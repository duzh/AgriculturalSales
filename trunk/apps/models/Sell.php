<?php
namespace Mdg\Models;
use Mdg\Models\Category as Category;
use Lib\Func as Func;
use Lib\Time as Time;
use Lib\Utils as Utils;
use Lib\Pages as Pages;
use Mdg\Models as M;

class Sell extends Base
{
    /**
     *
     * @var integer
     */
    
    public $id;
    /**
     *
     * @var integer
     */
    
    public $uid = 0;
    /**
     *
     * @var string
     */
    
    public $title = '';
    /**
     *
     * @var integer
     */
    
    public $category = 0;
    /**
     *
     * @var string
     */
    
    public $thumb = '';
    /**
     *
     * @var double
     */
    
    public $min_price = 0.00;
    /**
     *
     * @var double
     */
    
    public $max_price = 0.00;
    /**
     *
     * @var double
     */
    
    public $quantity = 0.00;
    /**
     *
     * @var integer
     */
    
    public $goods_unit = 0;
    /**
     *
     * @var integer
     */
    
    public $areas = 0;
    /**
     *
     * @var string
     */
    
    public $areas_name = '';
    /**
     *
     * @var string
     */
    
    public $address = '';
    /**
     *
     * @var string
     */
    
    public $mobile = '';
    /**
     *
     * @var integer
     */
    
    public $stime = 0;
    /**
     *
     * @var integer
     */
    
    public $etime = 0;
    /**
     *
     * @var string
     */
    
    public $breed = '';
    /**
     *
     * @var string
     */
    
    public $spec = '';
    /**
     *
     * @var integer
     */
    
    public $state = 0;
    /**
     *
     * @var integer
     */
    
    public $createtime = 0;
    /**
     *
     * @var integer
     */
    
    public $updatetime = 0;
    /**
     *
     * @var string
     */
    
    public $uname = '';
    /**
     *
     * @var string
     */
    
    public $sell_sn = '';
    /**
     *
     * @var string
     */
    
    public $is_del = 0;
    /**
     *
     * @var string
     */
    
    public $count = 0;
    /**
     *
     * @var string
     */
    public $is_source = 0;

    public $is_hot = 0;
    
    public $unit = '';
    
    public $mobileurl = '';
    
    public $source = 0;
    
    public $min_number = 0;
    
    public $maxcategory = 0;

    public $twocategory =0;

    public $url='';

    public $activity_id=0;
    public $publish_place=0;
    public $province_id=0;
    public $city_id=0;
   
    
    static $type = array(
        11 => '1月上旬',
        12 => '1月中旬',
        13 => '1月下旬',
        21 => '2月上旬',
        22 => '2月中旬',
        23 => '2月下旬',
        31 => '3月上旬',
        32 => '3月中旬',
        33 => '3月下旬',
        41 => '4月上旬',
        42 => '4月中旬',
        43 => '4月下旬',
        51 => '5月上旬',
        52 => '5月中旬',
        53 => '5月下旬',
        61 => '6月上旬',
        62 => '6月中旬',
        63 => '6月下旬',
        71 => '7月上旬',
        72 => '7月中旬',
        73 => '7月下旬',
        81 => '8月上旬',
        82 => '8月中旬',
        83 => '8月下旬',
        91 => '9月上旬',
        92 => '9月中旬',
        93 => '9月下旬',
        101 => '10月上旬',
        102 => '10月中旬',
        103 => '10月下旬',
        111 => '11月上旬',
        112 => '11月中旬',
        113 => '11月下旬',
        121 => '12月上旬',
        122 => '12月中旬',
        123 => '12月下旬',
    );
    static $type1 = array(
        0 => '等待审核',
        1 => '已审核',
        2 => '审核失败'
    );
    static $type2 = array(
        0 => '审核',
        1 => '取消',
        2 => '',
    );
    static $appstate = array(
        0 => '等待审核',
        1 => '审核通过',
        2 => '审核失败'
    );
    static $type3 = array(
        1 => '公斤',
        2 => '吨',
        3 => '头',
    );
    static $_ishot = array(
        0 => '否',
        1 => '是',
    );
    static $sellstate = array(
        0 => '全部状态',
        1 => '待审核',
        2 => '已发布',
        3 => '已删除',
        4 => '审核失败',
    );

    
    public function initialize() 
    {
        $this->belongsTo('id', 'Mdg\Models\SellContent', 'sid', array(
            'alias' => 'scontent'
        ));
    }
    
    public function afterFetch() 
    {
        
        $time = new Time(time() , $this->updatetime);
        $this->pubtime = $time->transform();
        $this->areasname = Utils::c_strcut($this->address, 12);
        $this->c_desc = Utils::c_strcut($this->spec, 27);
    }
    static function numCount($cond = array()) 
    {
        return self::find($cond)->count();
    }
    /**
     * 获取用户的信息
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    
    public static function uname($id) 
    {
        $Users = UsersExt::findFirstByuid($id);
        return $Users ? $Users->name : "-";
    }
    /**
     * 获取用户的地址
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    
    public static function uaddress($id) 
    {
        $Users = UsersExt::findFirstByuid($id);
        return $Users ? $Users->address : "-";
    }
    /**
     * 点击量
     * @param  integer $sellid [description]
     * @return [type]          [description]
     */
    
    public static function clickAdd($sellid = 0) 
    {
        $sell = self::findFirstByid($sellid);
        
        if ($sell) 
        {
            $sell->count+= 1;
            $sell->save();
        }
    }
    /**
     * 根据条件搜索
     * @param  array  $arr  搜索条件
     * @param  string  $type  类型 1为良品库查询 2为次品库查询
     * @return array
     */
    
    public static function conditions($arr) 
    {
        $options = array(
            'sellname' => '',
            'category' => '',
            'area_id' => '',
            'is_img' => '',
            'username' => '',
            'addstime' => '',
            'addetime' => '',
            'sellsn' => '',
            'state' => '',
            'location_home' => '',
            'location_category' => '',
            'location_hot' => '',
        );
        $opt = array_merge($options, $arr);
        //return $arr;die;
        $recommand_where = array();
        
        if (isset($arr['location_home'])) 
        {
            $recommand_where[] = " location_home = {$arr['location_home']} and status = 1";
        }
        
        if (isset($arr['location_category'])) 
        {
            $recommand_where[] = " location_category = {$arr['location_category']} and status = 1";
        }
        
        if (isset($arr['location_hot'])) 
        {
            $recommand_where[] = " location_hot = {$arr['location_hot']} and status = 1";
        }
        $recommand_where = implode(' AND ', $recommand_where);
        //return  $recommand_where;die;
        $where = '  1  ';
        
        if (is_array($arr)) 
        {
            empty($arr['category']) ? : $where.= " and s.category= " . $arr['category'] . "";
            empty($arr['area_id']) ? : $where.= " and s.areas =" . $arr["area_id"];
            empty($arr['sellname']) ? : $where.= " and s.title like '%" . $arr["sellname"] . "%'";
            empty($arr['username']) ? : $where.= " and s.uname like '%" . $arr["username"] . "%'";
            empty($arr['sellsn']) ? : $where.= " and s.sell_sn like '%" . $arr["sellsn"] . "%'";
            empty($arr['addstime']) ? : $where.= " and s.createtime between " . strtotime($arr['addstime']) . " and " . strtotime($arr['addetime']) . "";
            
            if (isset($arr["is_img"]) && $arr["is_img"] == 1) 
            {
                $where.= " and s.thumb!=''";
            }
            
            if (isset($arr["is_img"]) && $arr["is_img"] == 2) 
            {
                $where.= " and s.thumb=''";
            }
            
            if (!empty($arr['state'])) 
            {
                
                switch ($arr['state']) 
                {
                case '1':
                    $where.= " and s.state = 0 and is_del=0 ";
                    break;

                case '2':
                    $where.= " and s.state = 1 and is_del=0 ";
                    break;

                case '3':
                    $where = "  s.is_del =1 ";
                    break;

                case '4':
                    $where.= " and s.state = 2 and is_del=0 ";
                    break;

                default:
                    break;
                }
            }
            
            if ($recommand_where) 
            {
                $where.= M\RecommandSell::getsell_id($recommand_where);
            }
        }
        return $where;
    }
    /**
     * 显示供应列表
     * @param  [type] $category [description]
     * @return [type]           [description]
     */
    
    public static function showlist($category) 
    {
        $sell = null;
        //$sell = Sell::find(" twocategory in (" . $category . ") and state = 1 and is_del = 0  order by updatetime desc limit 5 ");
        $c = self::getCategory($category);
        
        if ($c) 
        {
            $cate = implode(",", $c);
            
            if ($cate) 
            {
                $sell = Sell::find(" category in (" . $cate . ") and state = 1 and is_del = 0  order by updatetime desc limit 5 ");
            }
        }
        return $sell ? $sell->toArray() : "";
    }
    /**
     * 显示采购列表
     * @param  [type] $category [description]
     * @return [type]           [description]
     */
    
    public static function showpur($category) 
    {
        $sell = null;
        //$c = self::getCategory($category);
        $sell = Purchase::find(" maxcategory in (" . $category . ") and endtime > " . time() . " and  state = 1 and is_del = 0  order by updatetime desc limit 5 ");
        // if ($c)
        // {
        //     $cate = implode(",", $c);
        //     if ($cate)
        //     {
        //         $sell = Purchase::find(" maxcategory in (" . $category . ") and endtime > " . time() . " and  state = 1 and is_del = 0  order by updatetime desc limit 5 ");
        //     }
        // }
        return $sell ? $sell->toArray() : "";
    }
    
    public static function getCategory($cid) 
    {
        $c = Category::findByparent_id($cid);
        return array_column($c->toArray() , 'id');
    }
    /**
     * 转移商品
     * @param  [type] $id         [description]
     * @param  [type] $categoryid [description]
     * @return [type]             [description]
     */
    
    public static function changeCategory($id = 0, $dst_id = 0) 
    {
        $ids = is_array($id) ? $id : array(
            $id
        );
        $where = 'category in (' . implode(',', $ids) . ')';
        $sellList = self::find($where);
        
        foreach ($sellList as $sell) 
        {
            $sell->category = $dst_id;
            $sell->save();
        }
        $dst_ids = Func::getCols(Category::getFamily($dst_id) , 'id');
        $ids = array_merge($ids, $dst_ids);
        
        foreach ($ids as $v) 
        {
            $ids = array_merge($ids, Func::getCols(Category::getFamily($v) , 'id'));
        }
        $ids = array_unique($ids);
        
        foreach ($ids as $id) 
        {
            Category::numUpdate($id, 'sell');
        }
    }
    /**
     * 监测数据
     */
    function get_int($number) 
    {
        return intval($number);
    }
    //字符串型过滤函数
    function get_str($string) 
    {
        
        if (!get_magic_quotes_gpc()) 
        {
            return addslashes($string);
        }
        return $string;
    }
    /**
     * 显示供应列表
     * @param  [type] $category [description]
     * @return [type]           [description]
     */
    
    public static function shoplookgoodslist($c, $uid, $p = 1, $id, $page_size = 20) 
    {
        
        if ($id) 
        {
            $where = " uid={$uid} and state=1 and is_del=0 and category=" . $id;
        }
        else
        {
            $where = " uid={$uid} and state=1 and is_del=0";
        }
        $total = self::count($where);
        $offst = intval(($p - 1) * $page_size);
        
        if ($c == 'shoplook') 
        {
            $data = self::find($where . " order by updatetime desc limit 8 ")->toArray();
        }
        else
        {
            $data = self::find($where . "  ORDER BY updatetime DESC limit {$offst} , {$page_size} ")->toArray();
        }
        $pages['total_pages'] = ceil($total / $page_size);
        $pages['current'] = $p;
        $pages['total'] = $total;
        $pages = new Pages($pages);
        $datas['total'] = $total;
        $datas['items'] = $data;
        $datas['start'] = $offst;
        $datas['pages'] = $pages->show(3);
        return $datas;
    }
    /**
     * 根据分类id查询商品
     * @param  [type] $category [description]
     * @return [type]           [description]
     */
    
    public static function categorygoods($user_id = 0, $category = 0) 
    {
        //$where=" uid={$uid} and state=1 and is_del=0 and category=".$id;
        $where = ' state=1 and is_del=0 and uid= ' . $user_id . ' and category=' . $category;
        $total = self::count($where);
        return $total;
    }
    /**
     * 分类数据
     * @param  [type] $category [description]
     * @return [type]           [description]
     */
    
    public static function lookgoodslist($uid) 
    {
        $where = " uid={$uid} and state=1 and is_del=0";
        $total = self::count($where);
        $data = self::find($where . " order by updatetime desc")->toArray();
        $datas['items'] = $data;
        return $datas;
    }
    /**
     * 查询同类产品
     * @param  string  $title 产品名称
     * @param  integer $limit 查询条数
     * @return array
     */
    
    public static function getSellSameLimit($category = '', $sellid = 0, $limit = 5) 
    {
        $cond[] = " category = '{$category}' AND id != '{$sellid}' AND  state = 1 AND is_del =  0 and publish_place!=2 and thumb !='' ";
        $cond['columns'] = " title, thumb,spec, goods_unit,stime,etime,breed, address,category, min_price, max_price,createtime, id ";
        $cond['limit'] = array(
            $limit,
            0
        );
        $data = self::find($cond)->toArray();
        return $data;
    }
    /**
     *  获取订单价格区间
     */
    
    public static function getprice($sellid) 
    {
        $sell = self::findFirstByid($sellid);
        return $sell ? $sell->min_price . '-' . $sell->max_price : '';
    }
    
    public static function getordertotal($sellid, $good_number) 
    {
        $sell = self::findFirstByid($sellid);
        
        if (!$sell) 
        {
            return false;
        }
        $min_total = $sell->min_price * $good_number;
        $max_total = $sell->max_price * $good_number;
        return $min_total . '-' . $max_total;
    }
    /**
     * 根据供应id 获取联系人姓名
     * @param  integer $sid [description]
     * @return string
     */
    
    public static function selectBytouname($sid = 0) 
    {
        $cond[] = " id = '{$sid}'";
        $cond['columns'] = " uname";
        $data = self::findFirst($cond);
        return $data ? $data->uname : '';
    }
    /**
     * 根据供应id 获取手机号
     * @param  integer $sid 供应id
     * @return string
     */
    
    public static function selectBytomobile($sid = 0) 
    {
        $cond[] = " id = '{$sid}'";
        $cond['columns'] = " mobile";
        $data = self::findFirst($cond);
        return $data ? $data->mobile : '';
    }
    /**
     * 获取供应商品编号
     * @param  integer $sid 供应id
     * @return string
     */
    
    public static function getSellTosn($sid = 0) 
    {
        $conds[] = " id ='{$sid}'";
        $conds['columns'] = " sell_sn ";
        $data = self::findFirst($conds);
        return $data ? $data->sell_sn : '';
    }
    /**
     * 获取供应列表
     * @param  array   $cond      条件
     * @param  integer $p         分页
     * @param  integer $page_size 条数
     * @param  string  $limit     是否需要分页
     * @return array
     */
    
    public static function getSellList($cond = array() , $p = 1, $page_size = 10, $limit = '') 
    {
        $total = self::count($cond);
        $offst = intval(($p - 1) * $page_size);
        
        if ($limit) 
        {
            $cond.= " LIMIT {$offst}, {$page_size} ";
        }
        $data = self::find($cond)->toArray();
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
    
    public static function getshop() 
    {
        $sell = self::find(" 1 order by count desc limit 4 ");
        return $sell->toArray();
    }
    /**
     * 动态更新 采购 分类
     * @param  integer $cid 分类id
     * @return [type]       [description]
     */
    
    public function SelldynamicCategory($cid = 0, $level = 0) 
    {
        //追溯上级ID
        $category = Category::getFamily($cid);
        $maxPid = isset($category[0]['id']) ? $category[0]['id'] : 0;
        $twoPid = isset($category[1]['id']) ? $category[1]['id'] : 0;
        
        if (!$maxPid || !$twoPid) return array();
        $phql = "UPDATE Mdg\Models\Sell SET category = {$cid}, twocategory = '{$twoPid}', maxcategory = '{$maxPid}' WHERE category = '{$cid}'";
        return $this->_dependencyInjector['modelsManager']->executeQuery($phql);
    }
    /**
     * 动态更新转移商品分类
     * @param integer $cid   分类id
     * @param integer $tocid 指定分类id
     * 分类转移商品 更新供应 采购商品分类， 以及重新计算分类 采购，供应总数
     */
    
    public function SellDynamicSynCategory($cid = 0, $tocid = 0) 
    {
        $category = Category::getFamily($tocid);
        $maxid = isset($category[0]['id']) ? $category[0]['id'] : 0;
        $twoid = isset($category[1]['id']) ? $category[1]['id'] : 0;
        
        if ($twoid != 0) 
        {
            $sql = "UPDATE Mdg\Models\Sell set maxcategory=$maxid,twocategory=$twoid where category =$cid ";
            $this->_dependencyInjector['modelsManager']->executeQuery($sql);
            $sql2 = "UPDATE Mdg\Models\Purchase set maxcategory=$maxid,twocategory=$twoid where category =$cid ";
            $this->_dependencyInjector['modelsManager']->executeQuery($sql2);
        }
    }
    /**
     * 根据手机号查询联系方式
     * @param  string $mobile 手机号
     * @return string
     */
    
    public static function selectMobile($mobile = '') 
    {
        $cond[] = " mobile = '{$mobile}' ";
        $cond['columns'] = " id , mobile";
        $data = self::find($cond)->toArray();
        $_sellid = array_column($data, 'id');
        return $_sellid ? join(',', $_sellid) : '';
    }
    /**
     * 获取供应商品详细信息
     * @param  integer $sid 供应id
     * @return array
     */
    
    public static function getSellInfo($sid = 0) 
    {
        $data = self::findFirst(" id ='{$sid}' AND state = 1 AND is_del = 0 ");
        return $data;
    }
    /**
     * 检测当前用户是否开
     * @param  boolean $reutnrObj 是否返回对象数据
     * @return
     */
    
    public function checkShopExist($uid) 
    {
        $data = Shop::findFirst(" user_id = '{$uid}' and is_service='0' and state=1 ");
        return $data ? $data->toArray() : array();
    }
    /**
     * 获取供应列表
     * @param
     * @return
     */
    // public function getSellList() {
    // }
    //
    
    /**
     * 获取热门供应列表
     */
    /*    public static function hotsell(){
        $recommandsell = M\RecommandSell::find(" location_hot=1 and status=1");
    
        if($recommandsell){
            $purchase_id = Func::getCols($recommandsell->toArray(), 'sell_id', ",");
        }
    
        $where = " id in ('{$purchase_id}')";
    
        return $where;
    }*/
    /**
     * 获取首页推荐，热门供应
     * @param  string $need home 首页推荐  hot 热门推荐
     * @param  integer $limit 显示条数 0 不限
     * @return array
     */
    public function getRecoList($cid=0 , $need = 'home', $limit = 50) 
    {
        $sid = array();
        $where[] = " s.is_del = 0 AND state=1 AND publish_place!=2 ";
        if($cid!=0){
            
            $where[] = " s.maxcategory = '{$cid}'";
        }
        switch ($need) 
        {
        case 'home':
            $where[] = " rs.location_home = '1'  AND status = 1";
            $orderby = " createtime desc";
            break;

        case 'hot':
            $where[] = " rs.location_hot = '1' AND rs.status = 1  ";
            $orderby = " count DESC";
            break;

        default:
            return array();
            break;
        }
        $where = implode(' AND ', $where);

        $sql = " SELECT s.id,title, thumb, min_price, max_price, quantity, goods_unit, areas_name,createtime  FROM sell as s LEFT join recommand_sell as rs ON rs.sell_id = s.id where {$where} LIMIT {$limit}";
        $data = $this->_dependencyInjector['db']->fetchAll($sql, 2);
        foreach ($data as $key => $value) {
          $area_name=explode(",",$value["areas_name"]);
          $province=isset($area_name[0]) ? $area_name[0] : '';
          $city=isset($area_name[1]) ? $area_name[1] : '';
          $district=isset($area_name[2]) ? $area_name[2] : '';
          $data[$key]["areas_name"]=$province.$city.$district;
        }
        $id_string = Func::getCols($data,'id',',');
        $id_where = '';
        if($id_string){
            $id_where = " and id not in (".$id_string.")";
        }

        $mit = $limit - count($data);
        
        if ($mit <= 0) return $data;
        $cond[] = " is_del = 0   AND state = 1 AND publish_place!=2 AND maxcategory = '{$cid}'".$id_where;
        $cond['columns'] = 'id,title, min_price, thumb, max_price, quantity, goods_unit, areas_name,createtime';
        $cond['limit'] = $mit;
        $cond['order'] = $orderby;
        $rs = self::find($cond)->toArray();
        foreach ($rs as $key => $value) {
          $area_name=explode(",",$value["areas_name"]);
          $province=isset($area_name[0]) ? $area_name[0] : '';
          $city=isset($area_name[1]) ? $area_name[1] : '';
          $district=isset($area_name[2]) ? $area_name[2] : '';
          $rs[$key]["areas_name"]=$province.$city.$district;
        }
        return array_merge($data, $rs);
    }


/**
 * 供应特别推荐
 * @param  integer $uid 用户ID
 * @param  integer $limit 条数 
 */
    public static function getsellspecial($uid=0,$limit=5){
        $users = M\Users::findFirst(" id='{$uid}'");
        $user_attention = M\UserAttention::find(" user_id={$uid}");
        $rest = array();
        $data=array();
        if($users && $user_attention->toArray()){       
            foreach($user_attention->toArray() as $key=>$val){
                $data = self::find(" category='{$val['category_id']}' and state = 1 and is_del = 0 and publish_place != 2 ORDER BY updatetime DESC limit 1")->toArray();
                $rest = array_merge($rest,$data);            
            }
            foreach($rest as $k=>$v){
                if($k>=$limit){
                    unset($rest[$k]);
                }
            }

            if($k<($limit-1)){   
                $limit = $limit-$k-1;
                foreach($user_attention->toArray() as $k=>$v){
                    $sell = self::find(" category!='{$val['category_id']}' and state = 1 and is_del = 0 and publish_place != 2 ORDER BY updatetime DESC limit {$limit}")->toArray();
                    $rest = array_merge($rest,$sell);
                }    
            }
        }


        else{
            $data = self::find(" state = 1 and is_del = 0 and publish_place != 2 ORDER BY updatetime DESC limit {$limit}")->toArray();
            return $data;die;
        }

        $rs = array();
        foreach ($rest as $k => $v) {
            if(!isset($rs[$v['id']])) {
                $rs[$v['id']] = $v;
            }
        }
        return $rs;
    }

    //获取商品图片
    static public function getSellThumb($id){

        if($id) {
            $cond[] = " id ='{$id}'";
            $cond['order'] = 'id desc ';
            $cond['columns'] = 'id, thumb,category';
            $data = self::findFirst($cond);
        }else{
            $data=array();
        }
        if(!$data){
           return false;
        }
        if($data&&$data->thumb){

            return IMG_URL . $data->thumb;
        }else{
           
   
            return  Image::imgmaxsrc($data->category);
        }
    }
    /**
     *  获取热词搜索
     * @return [type] [description]
     */
    static public function getHotlist(){

         $sell=self::find(" 1 ORDER BY count DESC limit 5 ")->toArray();
         return $sell;
    }    
    static public function checkSell($uid=0){
          $sell=Sell::count("uid={$uid} and is_del=0 ");
          if($sell>0){
            return true;
          }
          return false;
    }
}
