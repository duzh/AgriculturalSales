<?php
namespace Mdg\Models;

class MarketTodayprice extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $contact_name;

    /**
     *
     * @var string
     */
    public $contact_phone;

    /**
     *
     * @var integer
     */
    public $category_id;

    /**
     *
     * @var string
     */
    public $category_name;

    /**
     *
     * @var string
     */
    public $goods_name;

    /**
     *
     * @var integer
     */
    public $province_id;

    /**
     *
     * @var integer
     */
    public $city_id;

    /**
     *
     * @var integer
     */
    public $district_id;

    /**
     *
     * @var string
     */
    public $market_name;

    /**
     *
     * @var string
     */
    public $analyze;

    /**
     *
     * @var integer
     */
    public $publish_time;

    /**
     *
     * @var integer
     */
    public $collect_type;

    /**
     *
     * @var double
     */
    public $price;

    /**
     *
     * @var integer
     */
    public $source;

    /**
     *
     * @var integer
     */
    public $sell_id;

 

}
