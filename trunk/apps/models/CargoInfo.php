<?php
namespace Mdg\Models;
use Lib as L;
use Mdg\Models\Codes as Codes;
class CargoInfo extends \Phalcon\Mvc\Model
{
    /**
     * 车体 箱型  车辆类型
     * @var array
     */
    public static $_box_type = array(
            0 => '普通',
            1 => '平板',
            2 => '低栏', 
            3 => '中栏',
            4 => '高栏',
            5 => '篷车',
            6 => '厢式',
            7 => '伸缩板', 
            8 => '标准集装箱', 
            9 => '超宽车', 
            10 => '其它', 
            11 => '罐式车',
        );
    /**
     * 车体类型
     * @var array
     */

    public static $_body_type = array(
        1 => '前四后四',
        2 => '前四后八', 
        3 => '单车', 
        4 => '后双桥', 
        5 => '半挂', 
        6 => '全挂', 
        7 => '四桥', 
        8 => '五桥', 
        9 => '六桥', 
        10 => '拖挂', 
        11 => '多桥', 
        12 => '三桥', 
        13 => '其他', 
        14 => '单桥', 
        15 => '平板车',
        16 => '加长挂', 
        17 => '后八轮',
        18 => '货车', 
        19 => '冷藏车'
        );

    /**
     * 车源 货物种类
     * @var array
     */
    public static $_goods_type = array(
            0 => '普通',
            1 => '轻货',
            2 => '重货',
        );

    /**
     *
     * @var integer
     */
    public $goods_id;

    /**
     *
     * @var string
     */
    public $goods_no;

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
    public $goods_type;

    /**
     *
     * @var string
     */
    public $goods_name;

    /**
     *
     * @var double
     */
    public $goods_weight;

    /**
     *
     * @var double
     */
    public $goods_size;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var integer
     */
    public $start_pid;

    /**
     *
     * @var string
     */
    public $start_pname;

    /**
     *
     * @var integer
     */
    public $start_cid;

    /**
     *
     * @var string
     */
    public $start_cname;

    /**
     *
     * @var integer
     */
    public $start_did;

    /**
     *
     * @var string
     */
    public $start_dname;

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
     *
     * @var string
     */
    public $end_pname;

    /**
     *
     * @var integer
     */
    public $end_pid;

    /**
     *
     * @var string
     */
    public $end_cname;

    /**
     *
     * @var integer
     */
    public $end_did;

    /**
     *
     * @var integer
     */
    public $end_cid;

    /**
     *
     * @var string
     */
    public $end_dname;

    /**
     *
     * @var double
     */
    public $except_length;

    /**
     *
     * @var double
     */
    public $except_price;

    /**
     *
     * @var integer
     */
    public $box_type;

    /**
     *
     * @var integer
     */
    public $body_type;

    /**
     *
     * @var integer
     */
    public $expire_time;

    /**
     *
     * @var string
     */
    public $demo;

    /**
     *
     * @var string
     */
    public $settle_type;

    /**
     * 获取货物种类
     * @return [type] [description]
     */
    public static function getGoodsType () {
        return self::$_goods_type;
    }

    /**
     * 获取货源基本信息
     * @param  array   $where     条件
     * @param  integer $p         分页
     * @param  [type]  $orderby   排序
     * @param  integer $page_size 条数
     * @return array
     */
    
    public static function getCarInfoList($where = array() ,  $p = 1, $db=null , $page_size = 20 ) 
    {

        $total=self::count($where. "  AND status = 1");
        $offst = ( $p - 1 ) * $page_size;
        $data = self::find($where. "  AND status = 1 order by goods_id desc limit {$offst} , {$page_size} ")->toArray();
        $pages['total_pages'] = ceil($total / $page_size);
        $pages['current'] = $p;
        $pages['total'] = $total;
        $pages = new L\Pages($pages);
        $datas['total'] = $total;
        $datas['items'] = $data;
        $datas['start'] = $offst;
        $datas['pages'] = $pages->show(12);
        return $datas;
    }

     /**
     * 车源快速导航
     * @return [type] [description]
     */
    public static function getCarNavsList() {
        $cond['columns'] = "start_pid ,goods_id , start_pname, start_cid , start_cname , add_time";
        $cond['group'] = 'start_pid';
        $cond['order'] = 'goods_id desc ';
        $rs = self::find($cond);
        //组装数据
        return $rs;
    }

    /**
     * 获取货源信息
     * @param  integer $cid 车源id
     * @return array
     */
    public static function getCargoInfo($cid=0) {
        $cond[] = " goods_id = '{$cid}' AND status = 1 ";
        $data = self::findFirst($cond);
        if(!$data) return array();
        $data = $data->toArray();
        $time = new L\Time(time(), $data['add_time']);
        $data['sendtime'] = $time->transform();
        return $data;
    }

    /**
     * 总条数
     * @return [type] [description]
     */
    public static function getCargoCount(){
        $count=self::count("status = 1");
        return $count;
    }

    /**
     * 货源快速导航
     * @return [type] [description]
     */
    public static function getCargoInfolimit() {
        $cond['columns'] = "start_pid ,goods_id , start_pname, start_cid , start_cname , add_time";
        $cond['group'] = 'start_pid';
        $cond['order'] = 'goods_id desc ';
        $cond['limit'] = array( 8,0);
        $rs = self::find($cond);
        //组装数据
        return $rs;
    }

    public static function GetMoblie($moblie= 0){
        return Codes::getmoblie($moblie);
    }

    /**
     * 检测地区显示最新
     * @param string $cname [description]
     * @param string $pname [description]
     */
    public static function GetAreaName($cname='',$pname = ''){
        if($cname && $cname!='市辖区' && $cname!='县'){
            return $cname;
        }else if($pname){
            return $pname;
        }else{
            return '暂无';
        }
    }
}
