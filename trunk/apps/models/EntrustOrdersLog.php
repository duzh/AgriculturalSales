<?php
namespace Mdg\Models;

class EntrustOrdersLog extends \Phalcon\Mvc\Model
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
    public $state;

    /**
     *
     * @var integer
     */
    public $operationid;

    /**
     *
     * @var string
     */
    public $operationname;

    /**
     *
     * @var integer
     */
    public $type;

    /**
     *
     * @var integer
     */
    public $addtime;

    /**
     *
     * @var string
     */
    public $demo;

    /**
     *
     * @var integer
     */
    public $order_id;

    /**
     *
     * @var string
     */
    public $order_sn;

    /**
     *
     * @var integer
     */
    public $order_type;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'entrust_orders_log';
    }

    /**
     * 日志操作人
     */
    CONST OPTYPE_USER = 0 ;
    CONST OPTYPE_ADMIN = 1 ;
    /* 订单日志类型  1 委托订单 0 正常订单*/
    CONST ORDER_TYPE_NORMAL = 0 ;
    CONST ORDER_TYPE_ENTRUST = 1 ;

    /**
     * 保存订单日志
     * @param  array  $log 日志数据   $data
     * order_id = 订单id
     * operationid = 操作人id
     * operationname = 操作人姓名
     * type  操作类型 0 会员 1 管理员 
     * demo 操作备注
     * order_sn 订单号
     * order_id 订单id
     * order_type 订单类型 
     * @return [type]      [description]
     */
    public static function saveOrderLog($data = array()) {
        $log = new self;
        $log->state         = $data['state']?:0;
        $log->operationid   = $data['operationid']?:'';
        $log->operationname =  $data['operationname']?:'';
        $log->type          =  $data['type']?:0;
        $log->demo          =  $data['demo']?:'';
        $log->order_id      =  $data['order_id']?:'';
        $log->order_sn      =  $data['order_sn']?:'';
        $log->order_type    =  $data['order_type']?:'';
        $log->addtime = CURTIME;
        return $log->save();

    }
	 

}
