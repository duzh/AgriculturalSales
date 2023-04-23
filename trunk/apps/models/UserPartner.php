<?php
namespace Mdg\Models;
use Lib as L;
use Lib\Pages as Pages;
use Mdg\Models as M;

class UserPartner extends \Phalcon\Mvc\Model
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
    public $partner_phone;

    /**
     *
     * @var string
     */
    public $partner_name;

    /**
     *
     * @var integer
     */
    public $pay_type;

    /**
     *
     * @var string
     */
    public $bank_name;

    /**
     *
     * @var string
     */
    public $bank_account;

    /**
     *
     * @var string
     */
    public $bank_card;

    /**
     *
     * @var string
     */
    public $bank_address;

    /**
     *
     * @var integer
     */
    public $status;

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
    public $partner_user_id;

    /**
     * 收款方式
     * @var array
     */
    static $_pay_type = array(
        0 => '云农宝',
        1 => '银行支付'
    );
    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'user_partner';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return UserPartner[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return UserPartner
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }
    /**
     * 插入商友数据
     *
     * @param mixed $parameters
     * @return UserPartner
     */
    public static function insertdata($datas)
    {
        $userpartenr = new self();
        if(isset($datas['id'])) $userpartenr->id = $datas['id'];
        if(isset($datas['user_id'])) $userpartenr->user_id = $datas['user_id'];
        if(isset($datas['partner_phone']))$userpartenr->partner_phone = $datas['partner_phone'];
        if(isset($datas['partner_user_id']))$userpartenr->partner_user_id = $datas['partner_user_id'];
        $userpartenr->partner_name = $datas['partner_name'];
        $userpartenr->pay_type = $datas['pay_type'];
        $userpartenr->bank_name = $datas['bank_name'];
        $userpartenr->bank_account = $datas['bank_account'];
        $userpartenr->bank_card = $datas['bank_card'];
        $userpartenr->bank_address = $datas['bank_address'];
        $userpartenr->status = 0;
        if(!isset($datas['id'])) $userpartenr->add_time = time();
        $userpartenr->last_update_time = time();
        return $userpartenr->save();
    }
    public function afterFetch() {
        $this->paytype = isset(self::$_pay_type[$this->pay_type]) ? self::$_pay_type[$this->pay_type] : '';
        $banktext = M\Bank::selectByCode($this->bank_name);
        $this->banktext = $banktext;
    }


    /**
     * 获取订单列表
     * @param  string  $where 条件
     * @param  integer $page  当前页
     * @param  integer $psize 条数
     * @param  string $pageMethod 分页样式
     * @return array ( items=> 数据源, start => 起始页 ， pages => 分页)
     */
    public static function getUserPartnerList($where='', $page=1, $psize =10,$pageMethod='defalut') {
        $page  = $page ? intval($page) : 1 ;
        $cond[] = $where;
        $cond['order'] = ' id DESC ';
        $total = self::count($cond);
        $offst = ( $page  - 1 ) * $psize;

        $cond['limit']  =array($psize , $offst);
        $item = self::find($cond);

        $pages['total_pages'] = ceil($total / $psize);
        $pages['current'] = $page;
        $pages['total'] = $total;
        $pages['method']  = $pageMethod;
        $pages['ajax_func_name'] = "javascript:goto_pages";

        $pages = new L\Pages($pages);
        $data['total_count'] = ceil($total / $psize);
        $data['items'] = $item;
        $data['start'] = $offst;
        $data['pages'] = $pages->show(2);
        return $data;
    }
}
