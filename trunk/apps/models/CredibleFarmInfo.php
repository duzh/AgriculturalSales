<?php
namespace Mdg\Models;
class CredibleFarmInfo extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $farm_id;

    /**
     *
     * @var string
     */
    public $farm_name;

    /**
     *
     * @var integer
     */
    public $user_id;

    /**
     *
     * @var string
     */
    public $user_name;

    /**
     *
     * @var string
     */
    public $desc;

    /**
     *
     * @var string
     */
    public $logo_pic;

    /**
     *
     * @var string
     */
    public $img_pic;

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
    public $status;

    /**
     *
     * @var integer
     */
    public $is_home_page;

    /**
     *
     * @var integer
     */
    public $home_page_order;

    /**
     *
     * @var text
     */
    public $custom_content;
	
	/**
     *
     * @var string
     */
    public $url;
	
	
	/**
	 *	根据域名前缀获取用户id
	 */
	public static function getUserMess() {
		
		// 获取域名前缀
		$arr_url	= explode(".",$_SERVER['HTTP_HOST'])[0];
		$user 		= self::findFirst(" url='$arr_url' ");
		if($user) {
			$user	= $user->toArray()['user_id'];
		} else {
			$user	= "";
		}
		return $user;
	}
}
