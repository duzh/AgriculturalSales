<?php
namespace Mdg\Models;

class OrderEngineer extends Base
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
    public $order_id;

    /**
     *
     * @var string
     */
    public $order_sn;

    /**
     *
     * @var string
     */
    public $engineer_name;

    /**
     *
     * @var string
     */
    public $engineer_phone;

    /**
     *
     * @var integer
     */
    public $add_time;

    /**
     *
     * @var integer
     */
    public $engineer_id;

        public $type = 0 ;
    
    CONST ORDER_TYPE_ORDERS = 0 ;
    CONST ORDER_TYPE_ORDERS_WT = 1 ;

}
