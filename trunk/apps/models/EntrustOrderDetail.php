<?php
namespace Mdg\Models;
use Lib as L;
use Mdg\Models as M;

class EntrustOrderDetail extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $order_detail_id;

    /**
     *
     * @var string
     */
    public $order_detail_sn;

    /**
     *
     * @var integer
     */
    public $order_parent_id;

    /**
     *
     * @var string
     */
    public $order_parent_sn;

    /**
     *
     * @var integer
     */
    public $order_status;

    /**
     *
     * @var integer
     */
    public $sell_user_id;

    /**
     *
     * @var string
     */
    public $sell_user_name;

    /**
     *
     * @var string
     */
    public $sell_user_phone;

    /**
     *
     * @var string
     */
    public $user_bank_name;

    /**
     *
     * @var string
     */
    public $user_bank_card;

    /**
     *
     * @var string
     */
    public $user_bank_account;

    /**
     *
     * @var string
     */
    public $user_bank_address;

    /**
     *
     * @var string
     */
    public $goods_name;

    /**
     *
     * @var integer
     */
    public $category_id_one;

    /**
     *
     * @var integer
     */
    public $category_id_two;

    /**
     *
     * @var double
     */
    public $goods_price;

    /**
     *
     * @var integer
     */
    public $goods_number;

    /**
     *
     * @var double
     */
    public $goods_amount;

    /**
     *
     * @var string
     */
    public $goods_unit;

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
    public $pay_time;

    /**
     *
     * @var integer
     */
    public $order_time;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
	 public function initialize() 
    {
        $this->belongsTo('order_parent_id', 'Mdg\Models\EntrustOrder', 'order_id', array(
            'alias' => 'ext'
        ));
    }
    public function getSource()
    {
        return 'entrust_order_detail';
    }

    public function afterFetch() {
        $this->orderstatus = isset(M\EntrustOrder::$_order_status[$this->order_status]) ? M\EntrustOrder::$_order_status[$this->order_status] : '';
        $this->category = M\Category::selectBytocateName($this->category_id_one) . '-' .M\Category::selectBytocateName($this->category_id_two);
        $this->order_amount = L\Func::format_money($this->goods_amount + $this->subsidy_amount) ;
        $this->bankname = M\Bank::selectByCode($this->user_bank_name);
        $order = M\EntrustOrder::findFirst(" order_id = '{$this->order_parent_id}'");
        $this->buy_name = $order ? $order->buy_user_name : '';
        $this->buy_phone = $order ? $order->buy_user_phone : ''; 
    }

    /**
     * 生成子订单信息
     * @param array $order  主订单信息 array( order_id , order_sn )
     * @param array $user  卖家信息
     * @param array $goods 商品信息
     * @param array $post  
     */
	
    public static function DetailOrderCreate(array $MOrder , array $sell, array $goods) {
        $time = CURTIME;
        $orderAmount = 0;
    
        foreach ($sell as $key => $val) {
            $log = array();
         
            $order = new self;
            $order->order_parent_id   = $MOrder['order_id'] ?:0;
            $order->order_parent_sn   = $MOrder['order_sn'] ?:0; 
            $order->order_status      = 3 ;
            $order->sell_user_id      = $val['partner_user_id'] ?:0;
            $order->sell_user_name    = $val['uname'] ?:0; 
            $order->sell_user_phone   = $val['username'] ?:0; 
            $order->user_bank_name    = htmlentities($val['bank_code']);
            $order->user_bank_card    = htmlentities($val['user_bank_card']);
            $order->user_bank_account = htmlentities($val['bank_account']);
            $order->user_bank_address = htmlentities($val['bank_address']);
            $order->goods_name        = $goods['goods_name'];
            $order->category_id_one   = $goods['category_id_one'] ;
            $order->category_id_two   = $goods['category_id_two'] ;
            $order->goods_price       = $goods['goods_price'];
            $order->goods_number      = $val['goods_number'] ; 
            $order->goods_amount      = $order->goods_price  * $order->goods_number ;
            $order->goods_unit        = $goods['goods_unit'];
            $order->add_time          = $time;
            $order->last_update_time  = $time;
            $order->pay_time          = 0;
            $order->order_time        = $time;
            $order->subsidy_amount    = 0 ;

            $orderAmount += $order->goods_price  * $order->goods_number;
            /* 检测是否需要添加联系人为商友 */
            if(isset($val['partner'])&& $val['partner']) {
                /* 创建联系人为商友用户 */
                if($info = M\UserPartner::findFirst(" user_id = '{$val['user_id']}' AND partner_user_id = '{$val['partner_user_id']}'")){
                    $insert['id'] = $info->id;
                }
                $insert['user_id']       = isset($val['user_id']) ? intval($val['user_id']) : 0 ;
                $insert['partner_phone'] = isset($val['username']) ? htmlentities($val['username']) : 0 ;
                $insert['partner_name']  = isset($val['uname']) ? htmlentities($val['uname']) : 0 ;
                $insert['pay_type']      = isset($val['pay_type']) ? intval($val['pay_type']) : 1 ;
                $insert['bank_name']     = isset($val['bank_code']) ? htmlentities($val['bank_code']) : 0 ;
                $insert['bank_account']  = isset($val['bank_account']) ? htmlentities($val['bank_account']) : 0 ;
                $insert['bank_card']     = isset($val['user_bank_card']) ? htmlentities($val['user_bank_card']) : 0 ;
                $insert['bank_address']  = isset($val['bank_address']) ? htmlentities($val['bank_address']) : 0 ;
                $insert['partner_user_id']  = isset($val['partner_user_id']) ? intval($val['partner_user_id']) : 0 ;
                if(!M\UserPartner::insertdata($insert)) throw new \Exception("USER_PARTNER_ERRORS");
                
            }
            
            if(!$order->create()) {
                
                throw new \Exception('DETAILORDERCREATE_ERRORS');
            }
            $order->order_detail_sn = L\Order::getOrderSn($order->order_detail_id);
            if(!$order->save()) {
                throw new \Exception('DETAILORDERCREATE_ERRORS');
            }
            $log = array(
                'order_id' => $order->order_detail_id, 
                'order_sn' => $order->order_detail_sn,
                'operationid' => $val['user_id'],
                'operationname' => $val['uname'],
                'state' => 3,
                'type' => M\EntrustOrdersLog::OPTYPE_USER,
                'demo' => $val['uname'] . '完成下单' ,
                'order_type' => M\EntrustOrdersLog::ORDER_TYPE_ENTRUST
                );
            if(!M\EntrustOrdersLog::saveOrderLog($log)) {
                throw new \Exception('LOG_ERRORS');
            }

        }
        return $orderAmount;
    }

    /**
     * 获取订单产品名称
     * @param  integer $orderid 订单id
     * @param  integer $ismain 是否为主订单
     * @return string
     */
    public static function selectBygoods_name ($orderid=0,$ismain=1) {
        $cond[] = $ismain ? " order_detail_id  ='{$orderid}' " :  " order_parent_id  ='{$orderid}' ";
        if(!$data = self::find($cond)->toArray()) {
            return '';
        }
        $gname = array_column($data , 'goods_name');
        return join( ',', $gname);
    }


    /**
     * 获取订单列表
     * @param  string  $where 条件
     * @param  integer $page  当前页
     * @param  integer $psize 条数
     * @return array ( items=> 数据源, start => 起始页 ， pages => 分页)
     */
    public static function getEntrustOrderDetailList($where='', $page=1, $psize =10) {
        
        $cond[] = $where;
        $cond['order'] = ' order_detail_id DESC ';
        $total = self::count($cond);
        $offst = ( $page  - 1 ) * $psize;
        $cond['limit']  =array($psize , $offst);
        $item = self::find($cond);

        $pages['total_pages'] = ceil($total / $psize);
        $pages['current'] = $page;
        $pages['total'] = $total;
        $pages = new L\Pages($pages);
        $data['items'] = $item;
        $data['start'] = $offst;
        $data['total_pages'] = ceil($total / $psize);
        $data['pages'] = $pages->show(2);
        
        return $data;
    }



}
