<?php
namespace Mdg\Models;
class OrdersDelivery extends \Phalcon\Mvc\Model
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
    public $deliverytype;

    /**
     *
     * @var string
     */
    public $deliveryname;

    /**
     *
     * @var string
     */
    public $drivername;

    /**
     *
     * @var string
     */
    public $driverphone;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $mobile;

    /**
     *
     * @var string
     */
    public $orderid;

    /**
     *
     * @var string
     */
    public $delivery_sn;
    

    static $dev_name = array(
        1 => '快递发货',
        2 => '汽车货运',
        3 => '采购自提'
    );
}
