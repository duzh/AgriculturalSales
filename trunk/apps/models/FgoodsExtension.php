<?php
namespace Pf\Models;
class FgoodsExtension extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $goods_id;

    /**
     *
     * @var string
     */
    public $goods_keyword;

    /**
     *
     * @var string
     */
    public $goods_unit;

    public $can_order_qty=0;
    /**
     *
     * @var integer
     */
    public $min_order_qty;

    /**
     *
     * @var integer
     */
    public $max_order_qty;

    /**
     *
     * @var integer
     */
    public $once_order_qty;

    /**
     *
     * @var integer
     */
    public $send_time;

    /**
     *
     * @var string
     */
    public $add_time;

    /**
     *
     * @var string
     */
    public $last_update_time;

    public $sale_qty = 0;

    public $id;


}
