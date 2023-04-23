<?php
namespace Mdg\Models;

class GoodsAvgprice extends \Phalcon\Mvc\Model
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
    public $sell_id;

    /**
     *
     * @var double
     */
    public $today_avgprice;

    /**
     *
     * @var double
     */
    public $yesterday_avgprice;

    /**
     *
     * @var integer
     */
    public $province_id;

    /**
     *
     * @var string
     */
    public $province_name;

    /**
     *
     * @var integer
     */
    public $category_id;



}
