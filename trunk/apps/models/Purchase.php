<?php

namespace Mdg\Models;

use Mdg\Models\Category as Category;
use Mdg\Models as M;
use Lib\Func as Func;
use Lib\Time as Time;
use Lib\Utils as Utils;
use Lib\Pages as Pages;

class Purchase extends \Phalcon\Mvc\Model
{
    static $_goods_unit = array(
          0=>'不限',
          1=>'公斤',
          2=>'斤',
          3=>'头',
          4=>'克',
          5=>'50克',
          6=>'千克',
          7=>'吨',
          8=>'颗',
          9=>'株',
          10=>'盆',
          11=>'根',
          12=>'支',
          13=>'扎',
          14=>'打',
          15=>'尾',
          16=>'只',
          17=>'个',
          18=>'袋',
          19=>'粒',
          20=>'包',
          21=>'盒',
          22=>'箱',
          23=>'筐',
          24=>'罐',
          25=>'件',
          26=>'平方米',
          27=>'亩',
          28=>'公顷',

    );
    static $goods_unitname = array(
            '不限'=>'0',
            '公斤'=>'1',
            '斤'=>'2',
            '头'=>'3',
            '克'=>'4',
            '50克'=>'5',
            '千克'=>'6',
            '吨'=>'7',
            '颗'=>'8',
            '株'=>'9',
            '盆'=>'10',
            '根'=>'11',
            '支'=>'12',
            '扎'=>'13',
            '打'=>'14',
            '尾'=>'15',
            '只'=>'16',
            '个'=>'17',
            '袋'=>'18',
            '粒'=>'19',
            '包'=>'20',
            '盒'=>'21',
            '箱'=>'22',
            '筐'=>'23',
            '罐'=>'24',
            '件'=>'25',
            '平方米'=>'26',
            '亩'=>'27',
            '公顷'=>'28',
    );
       
    static $_state = array(
        0 => '未审核',
        1 => '通过',
        2 => '审核未通过',
        3 => '已删除',
    );
    static $_state1 = array(
        0 => '审核',
        1 => '取消',
        2 => '',
        
    );
    static $appstate = array(
        0 => '待审核',
        1 => '审核通过',
        2 => '审核失败',
    );
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
     * @var integer
     */
    public $clicknum = 0;

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
    public $state = 0;

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
    public $mobile = ''; 

    /**
     *
     * @var string
     */
    public $address = '';

    /**
     *
     * @var string
     */
    public $username = '';

    /**
     *
     * @var integer
     */
    public $endtime = 0;
    /**
     *
     * @var integer
     */
    public $is_show = 1;
    /**
     *
     * @var integer
     */
    public $is_del = 0;

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
     * @var integer
     */
    public $pur_sn = '';
    
    public $maxcategory=0;

    /**
     *
     * @var integer
     */
    public $province_id = 0;

    
    /**
     *
     * @var integer
     */
    public $city_id = 0;


    
    public function getSource()
    {
        return 'purchase';
    }
    
    public function afterFetch(){
          $time = new Time(time(), $this->updatetime);
          $this->pubtime = $time->transform();
          $this->c_desc = @Utils::c_strcut($this->pcontent->content, 27);
          $this->countQuo = PurchaseQuotation::countQuo($this->id);
          $unit = isset(self::$_goods_unit[$this->goods_unit]) ? self::$_goods_unit[$this->goods_unit] : '';
          $this->conquantity = Func::conversion($this->quantity)  . $unit;
    }

    static  function clickAdd($purid=0) {
        $pur = self::findFirstByid($purid);
        if($pur) {
            $pur->clicknum += 1;
            $pur->save();
        }
    }

    public function initialize()
    {
        $this->belongsTo('id', 'Mdg\Models\PurchaseContent', 'purid', array(
            'alias' => 'pcontent'
        ));
    }

    static function numCount($cond=array()) {
        return self::find($cond)->count();
    }

    /**
     * 根据条件搜索
     * @param  array  $arr  搜索条件
     * @param  string  $type  类型 1为良品库查询 2为次品库查询
     * @return array
     */
    
    static public function conditions($arr) 
    {   
        
        $options = array(
            'state' => '',
            'category' => '',
            'title' => '',
            'pur_sn' => '',
            'stime' => '',
            'etime' => '',
        );

        $opt = array_merge($options,$arr);

        $where = '1=1'; 
        if (is_array($arr)) 
        {
           empty($arr['state']) ? : $where    .= " and state= " . $arr['state'] . "";
           empty($arr['category']) ? : $where .= " and category= " . $arr['category'] . "";
           empty($arr['title']) ? : $where    .= " and username like '%" . $arr["title"] . "%'";
           empty($arr['pur_sn']) ? : $where   .= " and pur_sn like '%" . $arr["pur_sn"] . "%'";
           empty(isset($arr['stime'])&&$arr['etime']) ? : 
           $where.= " and createtime between ".strtotime($arr['stime'])." and ".strtotime($arr['etime'])."";

        }
       
        return $where;
    }
    /**
     * 上传文件
     * @param  array   $_files   文件数组
     * @param  string  $path     上传路径
     * @param  boolean $realname 是否需要真实名字
     * @param  array   $ext_arr  允许文件
     * @return array            
     */
    public static  function move_file($_files = array()  , $path = ''   , $realname = false , $ext_arr = array('xls')){

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
    /**
     * 创建文件夹
     * @param  [type] $folder 文件夹路径
     * @return boolean             
     */
    public static function make_dir($folder)
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

                if (!file_exists($base))
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
    public static  function check_files($filename='',$ext_arr = array('xls') )
    {
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (!in_array($ext, $ext_arr)) {
          return false;
        }
        return true;
    }
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
     * 转移商品
     * @param  [type] $id         [description]
     * @param  [type] $categoryid [description]
     * @return [type]             [description]
     */
    public static function  changeCategory($id=0,$dst_id=0){

        $ids = is_array($id) ? $id : array($id);
        $where = 'category in ('. implode(',', $ids) .')';
        $sellList = self::find($where);

        foreach ($sellList as $sell) {
            $sell->category = $dst_id;
            $sell->save();
        }

        $dst_ids = Func::getCols(Category::getFamily($dst_id), 'id');
        $ids = array_merge($ids, $dst_ids);
        foreach ($ids as $v) {
            $ids = array_merge($ids, Func::getCols(Category::getFamily($v), 'id'));
        }
        $ids = array_unique($ids);
        foreach ($ids as $id) {
            Category::numUpdate($id, 'pur');
        }
    }

    /**
     * 店铺采购列表
     * @param  [type] $id         [description]
     * @param  [type] $categoryid [description]
     * @return [type]             [description]
     */
    
    static function getlist($user_id=0,$c,$where ,$p = 1, $page_size = 15){

        $total = self::count($where);
        
        $offst = intval(($p - 1) * $page_size);

        if($c == 'index'){
            $data = self::find($where . " order by createtime desc limit 10 ")->toArray();
        }else{

            $data = self::find($where."  ORDER BY createtime DESC limit {$offst} , {$page_size} ")->toArray();
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
    
    public static function categorygoods($user_id=0,$category=0){

        $time=time();
        $where = "uid='{$user_id}' and endtime>'{$time}' and is_del=0 and state=1 and is_show=1 and category = '{$category}'";
        //$where = ' uid= '.$user_id.' and category='.$category;
        $total = self::count($where);
        return $total;
    }

    /**
     * 检测当前用户是否开
     * @param  boolean $reutnrObj 是否返回对象数据
     * @return 
     */
    public static function checkShopExist($uid) {
        
        $data = Shop::findFirst(" user_id = '{$uid}' and is_service='0' and shop_status=1 ");
        return $data ?  $data->toArray() : array();
    }

    /**
     * 获取采购推荐
     * @param  integer $page  当前页
     * @param  integer $psize 条数
     * @param  string  $where 条件
     * @return array
     */
    public static function getRecPurList($page=1, $psize=10 , $where = '') {
        $data = array();
        
        $total = self::count($where);
        $offst = intval(($page - 1) * $psize);
        $data = self::find($where);

        $pages['total_pages'] = ceil($total / $psize);
        $pages['current'] = $page;
        $pages['total'] = $total;
        $pages = new Pages($pages);
        $datas['total'] = $total;
        $datas['items'] = $data;
        $datas['start'] = $offst;
        $datas['pages'] = $pages->show(3);
        return $datas;

    }
   


    /**
     * 获取采购列表
     * @param  [type] $id         [description]
     * @param  [type] $categoryid [description]
     * @return [type]             [description]
     */
    
    static function getpurchaselist($page = 1, $where=array(), $page_size = 10){

        $recommandpurchase = M\RecommandPurchase::find(" location_category=1 and status=1");

        if($recommandpurchase){
            $purchase_id = Func::getCols($recommandpurchase->toArray(), 'purchase_id', ",");
        }
        //$where['purchase_id'] = " id in ('{$purchase_id}')";
        $where =implode(' and ', $where);
        $offst = intval(($page - 1) * $page_size);
        
        $total = self::count($where . " ORDER BY updatetime");

        $data = self::find($where ." AND id in ('{$purchase_id}') ORDER BY updatetime DESC limit {$offst} , {$page_size}")->toArray();

        /*if($total<10){
            $data.=self::
        }*/
        
        $pages['total_pages'] = ceil($total / $page_size);
        $pages['current'] = $page;
        $pages['total'] = $total;
        $pages = new Pages($pages);
        $datas['total'] = $total;
        $datas['items'] = $data;
        $datas['start'] = $offst;
        $datas['pages'] = $pages->show(3);
        return $datas;

    }


    /**
     * 采购推荐数据
     * @param  string  $need  类型 home 首页 cate 分类 
     * @param  integer $limit 条数 
     * @return array
     */
    public function getRecList($cid=0, $need='home', $limit=10) {
        $sid = array();
        $data = array();
        $where[] = "  p.is_del = 0 AND p.maxcategory = '{$cid}'";
        switch ($need) {
            case 'home':
                $where[] = " rp.location_home = '1' AND status = 1 ";
                $orderby = " createtime desc";
                break;
            case 'cate':
                $where[] = " rp.location_hot = '1' AND status = 1 ";
                $orderby = " clicknum DESC";
                break;
            default:
            return array();
            break;
        }

        $where = implode(' AND ', $where);
        $sql = " SELECT p.id, title, quantity, goods_unit  FROM purchase as p LEFT join recommand_purchase as rp ON rp.purchase_id = p.id where {$where} order by updatetime desc LIMIT {$limit}";
        $data = $this->_dependencyInjector['db']->fetchAll($sql,2);
        $id_string = Func::getCols($data,'id',',');
        $id_where = '';
        if($id_string){
            $id_where = " and id not in (".$id_string.")";
        }

        $mit = $limit - count($data);
        if($mit <= 0 ) return $data;
        
        $cond[] = " is_del = 0   AND state = 1 AND maxcategory = '{$cid}' ".$id_where;
        $cond['columns'] = 'id, title, quantity, goods_unit';
        $cond['limit'] = $mit;
        $cond['order'] = $orderby;
        $rs = self::find($cond)->toArray();
        return array_merge($data, $rs);

    }

/**
 * 采购特别推荐
 * @param  integer $uid 用户ID
 * @param  integer $limit 条数 
 */
    public static function getpurchasespecial($uid=0,$limit=5){

        $users = M\Users::findFirst(" id='{$uid}'");

        if($users){
            $usersext = M\UsersExt::findFirst(" uid = '{$users->id}'");
            $user_goods = explode(',',$usersext->goods);
            $rest = array();
            $data=array();
            foreach($user_goods as $key=>$val){
                $data = self::find(" title='{$val}' and state = 1 and is_del = 0 ORDER BY updatetime DESC limit 1")->toArray();
                $rest = array_merge($rest,$data);
            
            }

            foreach($rest as $k=>$v){
                if($k>=$limit){
                    unset($rest[$k]);
                }
            }

            if($k<($limit-1)){
                $limit = $limit-$k-1;
                foreach($user_goods as $k=>$v){
                    $purchase = self::find(" title!='{$v}' and state = 1 and is_del = 0 ORDER BY updatetime DESC limit {$limit}")->toArray();
                    $rest = array_merge($rest,$purchase);
                
                }    
            }


        }else{

            $data = self::find(" state = 1 and is_del = 0 ORDER BY updatetime DESC limit {$limit}")->toArray();
            return $data;die;
        }


        return $rest;
    }
    public static function gepurchase(){
        $sql="SELECT p.*,c.content,u.company_name from user_info as u  join purchase as p on u.user_id=p.uid JOIN purchase_content as c on p.id=c.purid where u.credit_type=16 and u.STATUS=1 and u.type=1 and p.state=1 and p.is_del=0 ORDER BY p.createtime desc  limit 5 ";
        $arr=$GLOBALS['di']['db']->fetchAll($sql);
        return $arr;
    }
}
