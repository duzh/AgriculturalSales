<?php
namespace Mdg\Models;
class OrdersLog extends \Phalcon\Mvc\Model
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
     * @var string
     */
    public $order_id;

    /**
     * 记录订单log
     * @param  integer $order_id      订单ID
     * @param  integer $state         订单状态
     * @param  integer $operationid   操作者ID
     * @param  string  $operationname 操作者姓名
     * @param  integer $type          操作者类型
     * @param  string  $demo          操作描述
     * @return
     */
    static function insertLog($order_id=0, $state=0, $operationid=0, $operationname='', $type=0, $demo='') {
        $log = new self();
        $log->order_id = $order_id;
        $log->state = $state;
        $log->operationid = $operationid;
        $log->operationname = $operationname;
        $log->type = $type;
        $log->demo = $demo;
        $log->addtime = time();
        return $log->save();
    }

}
