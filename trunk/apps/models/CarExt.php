<?php
namespace Mdg\Models;
/**
 * 车辆详细 扩展信息
 */
class CarExt extends \Phalcon\Mvc\Model
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
    public $car_id;

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
     * @var double
     */
    public $length;

    /**
     *
     * @var double
     */
    public $carry_weight;

    /**
     *
     * @var integer
     */
    public $use_time;

    /**
     *
     * @var integer
     */
    public $depart_time;

    /**
     *
     * @var integer
     */
    public $is_longtime;

    /**
     *
     * @var integer
     */
    public $transport_type;

    /**
     *
     * @var integer
     */
    public $demo;

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

     public function initialize() {
        $this->setReadConnectionService('dbRead');
        $this->setWriteConnectionService('dbWrite');
      }

    
    /**
     * 获取车辆扩展信息
     * @param  integer $cid 车辆id
     * @return array
     */
    
    public function beforeSave() {
        $this->last_update_time = time();
    }
    /**
     * 获取车源扩展信息
     * @param  integer $cid 车源id
     * @return array
     */
    public static function selectByCar_id ($cid=0) {
        $cond[] = "car_id = '{$cid}'";
        $data = self::findFirst($cond);
        if(!$data) return array();

        $data = $data->toArray();
        $data['departtime'] = date('Y-m-d', $data['depart_time']);
        return $data;
    }

    /**
     * 获取车辆信息-箱型
     * @return [type] [description]
     */
    
    public static $_box_type = array( 
        '平板'=>1,
        '低栏'=>2,
        '中栏'=>3,
        '高栏'=>4,
        '篷车'=>5,
        '厢式'=>6,
        '伸缩板'=>7,
        '标准集装箱'=>8,
        '超宽车'=>9,
        '其它'=>10,
        '罐式车'=>11,
        '普通'=>0,
        ); 

    /**
     * 获取车辆信息-车体
     * @return [type] [description]
     */
    
    public static $_body_type = array( 
        '前四后四'=>1,
        '前四后八'=>2,
        '单车'=>3,
        '后双桥'=>4,
        '半挂'=>5,
        '全挂'=>6,
        '四桥'=>7,
        '五桥'=>8,
        '六桥'=>9,
        '拖挂'=>10,
        '多桥'=>11,
        '三桥'=>12,
        '其他'=>13,
        '单桥'=>14,
        '平板车'=>15,
        '加长挂'=>16,
        '后八轮'=>17,
        '货车'=>18,
        '冷藏车'=>19,
        );

}
