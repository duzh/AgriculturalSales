<?php

namespace Mdg\Models;

class WebsiteLink extends \Phalcon\Mvc\Model
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
    public $website_name;

    /**
     *
     * @var string
     */
    public $website_link;

    /**
     *
     * @var string
     */
    public $logo;

    /**
     *
     * @var integer
     */
    public $order_item;

    /**
     *
     * @var integer
     */
    public $location;

    /**
     *
     * @var integer
     */
    public $status;

	/**
     *
     * @var string
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
	/**
     *
     * @var integer
     */
	 public $islogo;
	 
	/*连接使用状态*/
	static $Linkstatus = array(
        1 => '禁用',
        2 => '激活',
		3 => '全部',
    );
	/*连接显示位置*/
	static $Linklocation = array(
        1 => '首页',
        2 => '全站',
		3 => '全部',
    );
	/*是否有logo*/
	static $Linklogo = array(
        1 => '无',
        2 => '有',
		3 => '不限',
    );

	static public function showlinks($location){     

		$links=WebsiteLink::find("status=2 and location in ({$location})  ORDER BY order_item DESC,id DESC LIMIT 35")->toArray();
		
        return $links;
    }


}
