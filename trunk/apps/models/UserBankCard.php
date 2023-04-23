<?php
namespace Mdg\Models;
use Mdg\Models as M;
use Lib as L;

class UserBankCard extends \Phalcon\Mvc\Model
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
    public $user_id;

    /**
     *
     * @var string
     */
    public $bank_name;

    /**
     *
     * @var string
     */
    public $bank_address;

    /**
     *
     * @var string
     */
    public $bank_account;

    /**
     *
     * @var string
     */
    public $bank_cardno;

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
	 * @var integer,是否默认：0否 1是,default 1
	 */
	public $is_default;
	
	/**
	 * @var integer
	 * 来源： 0手动 1注册 2HC、 4 VS、 8IF 、16PE,default 1
	 */
	public $source_type;
	
	/**
	 * @var integer
	 * 身份认证审核成功id,default 0
	 */
	public $credit_id;	
	
	/**
	 * @var integer
	 * 状态：0 未删除 1删除,default 0
	 */
	public $status;		

    /**
     * 获取用户银行列表
     * @param  string  $where 条件
     * @param  integer $page  当前页
     * @param  integer $psize  条数
     * @return array
     */
	static function getUserBankList($where = ' 1 ', $p=1, $psize=10) {

        $total = self::count( $where );

        $offst = intval(($p - 1) * $psize);
        $cond[] = $where;
        $cond['order'] = " is_default desc, id desc  ";
        $cond['limit'] = array(  $psize, $offst);

        $data = self::find($cond)->toArray();
        $pages['total_pages'] = ceil($total / $psize);
        $pages['current'] = $p;
        $pages['total'] = $total;
        
        $pages = new L\Pages($pages);
        $datas['total_count'] = ceil($total / $psize);
        $datas['total'] = $total;
        $datas['items'] = $data;
        $datas['start'] = $offst;
        $datas['pages'] = $pages->show(7);
        return $datas;

    }
    public static function getUserInfoTypeById($credit_id=0){
        if($credit_id){
            $info=M\UserInfo::findFirstBycredit_id($credit_id);
            if($info){
                return $info->type;
            }else{
                return '';
            }
        }else{
            return '';
        }
    } 
}
