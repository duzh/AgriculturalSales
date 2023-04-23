<?php
namespace 	Mdg\Models;
use 		Lib as L;
use 		Mdg\Models as M;
class CredibleFarmGoods extends \Phalcon\Mvc\Model
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
     * @var integer
     */
    public $user_id;

    /**
     *
     * @var integer
     */
    public $fram_id;

    /**
     *
     * @var integer
     */
    public $category_one;

    /**
     *
     * @var integer
     */
    public $category_two;

    /**
     *
     * @var string
     */
    public $goods_name;

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
     * @var string
     */
    public $picture_path;

    /**
     *
     * @var integer
     */
    public $is_recommend;
	
	/**
	 *	获取可信农场主营产品
	 *	@param integer page
	 *	@param integer psize
	 *	@param integer offst
	 *	@param integer userId
	 */
	public static function getCfarmList( $page, $psize, $offst, $userId ) {
		
		// 定义数组
		$farmgoods	= $formList	= array();
		
		// 获取总数
		$total		= self::count(" user_id = $userId AND is_recommend = 1 ");
		
		// 获取列表
		$farmgoods	= self::find(" user_id = $userId AND is_recommend = 1 ORDER BY id desc limit $offst, $psize ")->toArray();
		if( $farmgoods ){
			// 处理图片
			foreach($farmgoods as $key=>$val) {
				if($val['picture_path']) {
					$farmgoods[$key]['picture_path']	= IMG_URL . $val['picture_path'];
				} elseif($val['category_two'] && $image	= M\Image::imgsrc($val['category_two'])) {
					$farmgoods[$key]['picture_path']	= M\Image::imgsrc($val['category_two']);
				}else{
					$farmgoods[$key]['picture_path'] = 'http://static.ync365.com/mdg/images/detial_b_img.jpg';
				}
			}
		} 
		
		// 分页
		$pages['total_pages']	= ceil($total / $psize);
        $pages['current']		= $page;
        $pages['total'] 		= $total;
        $pages	= new L\Pages($pages);
        $formList['start']      = $offst;
        $formList['total'] 		= $total;
        $formList['items'] 		= $farmgoods;
        $formList['pages'] 		= $pages->show(2);
		if($formList){
			return $formList;
		} else {
			return array();
		}
	}

}
