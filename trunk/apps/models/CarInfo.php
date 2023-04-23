<?php
namespace Mdg\Models;
use Lib as L;
use Mdg\Models as M;


class CarInfo extends \Phalcon\Mvc\Model
{
    /**
     * 车体 箱型
     * @var array
     */
    private static $_box_type = array(
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

    private static $_body_type = array(
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
     * 运行方式
     * @var array
     */
    private static $_transport_type = array(
        1 =>'始发',
        2 =>'单程',
        3 =>'返程',
        4 =>'短驳',
        5 =>'往返',
        );
    /**
     *
     * @var integer
     */
    
    public $car_id;
    /**
     *
     * @var string
     */
    
    public $car_no;
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
     * @var double
     */
    
    public $light_goods;
    /**
     *
     * @var double
     */
    
    public $heavy_goods;
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
    
    public function initialize() {
        $this->setReadConnectionService('dbRead');
        $this->setWriteConnectionService('dbWrite');
    }
    
    
    public function afterFetch() 
    {
        $this->addtime = $this->add_time ? date("Y-m-d H:i:s", $this->add_time) : 0;
    }
    
    public function beforeCreate()
    {
        $this->status = 1;
        $this->add_time = time();
    }
    public function beforeSave() {
        $this->last_update_time = time();
    }
    /**
     * 获取车源基本信息
     * @param  array   $where     条件
     * @param  integer $p         分页
     * @param  [type]  $orderby   排序
     * @param  integer $page_size 条数
     * @return array
     */
    
    public static function getCarInfoList($where = array() ,  $p = 1, $db=null , $page_size = 20 ) 
    {

        
        $sql = " SELECT count(i.car_id) as total FROM car_info as i ,car_ext as ext WHERE ext.car_id = i.car_id AND  i.status = 1 AND {$where} order by i.car_id ";
        $total = $db->fetchOne($sql,2)['total'];
        $offst = ( $p - 1 ) * $page_size;
        $sql = " SELECT * FROM car_info as i ,car_ext as ext WHERE ext.car_id = i.car_id AND  i.status = 1  AND {$where} order by i.car_id DESC LIMIT {$offst}, {$page_size}";
              
        $data = $db->fetchAll($sql,2);
       
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
     * 获取车源信息
     * @param  integer $cid 车源id
     * @return array
     */
    public static function getCarInfo($cid=0) {
        $cond[] = " car_id = '{$cid}' AND status = 1 ";
        $data = self::findFirst($cond);
        if(!$data) return array();
        $data = $data->toArray();
        $time = new L\Time(time(), $data['add_time']);
        $data['sendtime'] = $time->transform();
        $data['ext'] = M\CarExt::selectByCar_id($data['car_id']);
        if(!$data['ext']) return array();
        return $data;
    }


    /**
     * 车源快速导航
     * @return [type] [description]
     */
    public static function getCarNavsList() {
        $cond['columns'] = "start_pid ,car_id , start_pname, start_cid , start_cname , add_time";
        $cond['group'] = 'start_pid';
        $cond['order'] = 'car_id desc ';
        $cond['limit'] = array( 10, 1);
        $rs = self::find($cond);
        //组装数据
        return $rs;
    }
    /**
     * 获取箱体类型
     * @return [type] [description]
     */
    public  static function getBoxType () {
        return self::$_box_type;
    }
    /**
     * 获取车体类型
     * @return array
     */
    public static function getBodyType() {
        return self::$_body_type;
    }
    /**
     * 运行方式
     * @return [type] [description]
     */
    public static function getTransportType() {
        return self::$_transport_type;
    }
    

   
}
