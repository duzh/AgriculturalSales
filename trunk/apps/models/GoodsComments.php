<?php
namespace Mdg\Models;

class GoodsComments extends \Phalcon\Mvc\Model
{
	static $_is_check = array(
		0 => '待审核',
		1 => '审核通过',
		2 => '审核未通过',
		3 => '已删除'
	    );
	static $_decribe_score = array(
		0 => '零',
		1 => '一',
		2 => '二',
		3 => '三',
		4 => '四',
		5 => '五',
	    );
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
	* @var string
	*/
	public $user_name;

	/**
	*
	* @var integer
	*/
	public $user_id;

	/**
	*
	* @var string
	*/
	public $comment;

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
	public $is_check;

	/**
	*
	* @var string
	*/
	public $goods_name;

	/**
	*
	* @var integer
	*/
	public $decribe_score;

	/**
	*
	* @var integer
	*/
	public $anonym_flag;

	/**
	*
	* @var integer
	*/
	public $category_id;
    /**
     *
     * @var integer
     */
    public $order_id;


	/**
	* 获取评论列表
	* @param  obj $db  
	* @param  string $where  查询条件
	* @param  int $p		 当前页  
	* @param  int $page_size	 查询数量  
	* @return  array() 
	*/
	public static function getList($db, $where, $p, $page_size =2){
		$start = ($p-1) * $page_size;
		$arr = self::find($where . " ORDER BY add_time DESC LIMIT $start, $page_size")->toArray();
		$count = self::find($where . " ORDER BY add_time DESC")->count();
		return array('total'=>$count,'total_page'=>ceil($count/$page_size),'page_size'=>$page_size,'data'=>$arr);
	}


	public static function goodscomments($db, $where, $p, $page_size=2){
		$start = ($p-1) * $page_size;
		$arr = self::find($where . " ORDER BY add_time DESC LIMIT $start, $page_size")->toArray();
		$count = self::find($where . " ORDER BY add_time DESC")->count();
		return array('total'=>$count,'total_page'=>ceil($count/$page_size),'page_size'=>$page_size,'data'=>$arr);
	}

	/**
	 * 查询商品是否有评论
	 */
	public static function WhetherComments($goods_id=0,$user_id=0){
		$data = self::find(" sell_id={$goods_id} and user_id={$user_id} or purchase_id={$goods_id} and user_id={$user_id} limit 1");

	//return $data->toArray();die;
		if($data->toArray()){
			return true;
		}else{
			return false;
		}
	}
    /**
     * 查询订单是否有评论
     */
    public static function WOrderComments($order_id=0,$user_id=0){

        $data = self::find(" order_id={$order_id} and user_id={$user_id} limit 1");
      
        //return $data->toArray();die;
        if($data->toArray()){
            return true;
        }else{
            return false;
        }
    }
}
?>