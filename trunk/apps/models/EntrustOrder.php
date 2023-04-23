<?php
namespace Mdg\Models;
use Lib as L;
use Mdg\Models as M;
use Mdg\Models\EntrustOrdersLog as EntrustOrdersLog;
use Mdg\Models\EntrustOrderDetail as EntrustOrderDetail;

class EntrustOrder extends \Phalcon\Mvc\Model
{
    /**
     *
     * @var integer
     */
    
    public $order_id;
    /**
     *
     * @var string
     */
    
    public $order_sn;
    /**
     *
     * @var integer
     */
    
    public $order_time;
    /**
     *
     * @var integer
     */
    
    public $order_status;
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
     * @var string
     */
    
    public $goods_unit;
    /**
     *
     * @var string
     */
    
    public $demo;
    /**
     *
     * @var integer
     */
    
    public $pay_time;
    /**
     *
     * @var integer
     */
    
    public $add_time;
    /**
     *
     * @var integer
     */
    
    public $trade_time;
    /**
     *
     * @var integer
     */
    
    public $receipt_time;
    /**
     *
     * @var integer
     */
    
    public $last_update_time;
    /**
     *
     * @var integer
     */
    
    public $pay_type;
    /**
     *
     * @var string
     */
    
    public $ynp_sn;
    /**
     *
     * @var string
     */
    
    public $bank_sn;
    /**
     *
     * @var integer
     */
    
    public $buy_user_id;
    /**
     *
     * @var string
     */
    
    public $buy_user_name;
    /**
     *
     * @var string
     */
    
    public $buy_user_phone;
    /**
     *
     * @var double
     */
    
    public $goods_amount;
    /**
     *
     * @var integer
     */
    
    public $sell_user_count;
    /**
     * 订单状态
     * @var array
     */
    static $_order_status = array(
        1 => '交易关闭',
        3 => '待付款',
        4 => '待收货',
        6 => '已完成'
    );
    /**
     * 支付方式
     * @var array
     */
    static $_pay_type = array(
        2 => '无',
        0 => '云农宝',
        1 => '农业银行',
        6 => '后台支付'
    );
    /* public function initialize()
    
    {
    
        $this->belongsTo('order_id', 'Mdg\Models\EntrustOrderDetail', 'order_parent_id', array(
    
            'alias' => 'ext'
    
        ));
    
    }*/
    /*  订单关闭 */
    CONST ORDER_STATUS_SHUT = 1;
    /* 订单支付 */
    CONST ORDER_STATUS_PAY = 4;
    /* 订单完成*/
    CONST ORDER_STATUS_COMP = 6;
    /*管理员*/
    CONST ADMIN_USER = 1;
    /*会员*/
    CONST ADMIN_NOT_USER = 0;
    /*委托交易*/
    CONST ORDER_TYPE_ENTRUST = 1;
    /*正常交易*/
    CONST ORDER_TYPE_NORMAL = 0;
    /**
     * 更新订单状态
     * @param  string  $order_sn 订单号
     * @param  integer $state    更新状态
     * @param  integer $ismain   是否为主订单
     * @return boolean
     */
    static 
    public function updateState($order_id = '', $state = 0, $ismain = 0) 
    {
        $time = CURTIME;
        
        if (!$order_id || !$state) 
        {
            throw new \Exception('ORDER_STATE_ERRORS');
        }
        
        switch ($state) 
        {
        case self::ORDER_STATUS_SHUT:
            break;

        case self::ORDER_STATUS_PAY:
            break;

        case self::ORDER_STATUS_COMP:
            break;

        default:
            throw new \Exception('ORDER_STATUS_ERRORS');
            break;
        }
        
        if ($ismain) 
        {
            $tablename = 'entrust_order';
            $phql = " UPDATE {$tablename} set order_status='{$state}' last_update_time = '{$time}'  where order_id = '{$order_id}'";
        }
        else
        {
            $tablename = 'entrust_order_detail';
            $phql = " UPDATE {$tablename} set order_status='{$state}' last_update_time = '{$time}'  where order_detail_id = '{$order_id}'";
        }
    }
    
    public function afterFetch() 
    {
        $this->orderstatus = isset(self::$_order_status[$this->order_status]) ? self::$_order_status[$this->order_status] : '';
        $this->category = M\Category::selectBytocateName($this->category_id_one) . '-' . M\Category::selectBytocateName($this->category_id_two);
        $this->order_amount = L\Func::format_money($this->goods_amount - $this->subsidy_total);
        $this->goods_amount = L\Func::format_money($this->goods_amount);
    }
    
    public static function getEntrustOrderList($where = '', $page = 1, $psize = 10) 
    {
        $cond[] = $where;
        $cond['order'] = ' order_id DESC ';
        $total = self::count($cond);
        $offst = ($page - 1) * $psize;
        $cond['limit'] = array(
            $psize,
            $offst
        );
        $item = self::find($cond);
        $pages['total_pages'] = ceil($total / $psize);
        $pages['current'] = $page;
        $pages['total'] = $total;
        $pages = new L\Pages($pages);
        $data['total_count'] = ceil($total / $psize);
        $data['items'] = $item;
        $data['start'] = $offst;
        $data['pages'] = $pages->show(2);
        return $data;
    }
    /**
     * 创建之前 生成订单号
     * @return [type] [description]
     */
    
    public function beforeCreate() 
    {
    }
    /**
     * 生成主订单
     * @param  array  $post 数据源
     * @return [type]       [description]
     */
    
    public static function MainOrderCreate($user = array() , $goods = array() , $sell = array()) 
    {
        $time = CURTIME;
        /* 计算总额*/
        
        if (!$goods['goods_amount'] || !$user['user_id'] || !$goods['goods_name'] || !$goods['category_id_one'] || !$goods['goods_price'] || !$goods['goodsnumber']) 
        {
            throw new \Exception('DATA_ERRORS');
        }
        $order = new self;
        $order->goods_name = $goods['goods_name'] ? : '';
        $order->category_id_one = $goods['category_id_one'] ? : '';
        $order->category_id_two = $goods['category_id_two'] ? : '';
        $order->goods_price = $goods['goods_price'] ? : '';
        $order->goods_unit = $goods['goods_unit'] ? : '';
        $order->demo = $goods['demo'] ? : '';
        $order->pay_type = 2;
        $order->ynp_sn = '';
        $order->bank_sn = '';
        $order->buy_user_id = $user['user_id'];
        $order->buy_user_name = $user['uname'];
        $order->buy_user_phone = $user['username'];
        $order->goods_amount = $goods['goods_amount'];
        $order->sell_user_count = count($sell);
        $order->add_time = $time;
        $order->last_update_time = $time;
        $order->receipt_time = 0;
        $order->pay_time = 0;
        $order->trade_time = 0;
        $order->order_time = $time;
        $order->order_status = 3;
        $order->subsidy_total = 0;
        $order->paymenturl = '';
        $order->goods_number = $goods['goodsnumber'];
        
        if (!$order->create()) 
        {
            // print_R($order->getMessages());exit;
            throw new \Exception("ORDER_CREATE_ERRORS");
        }
        $order->order_sn = L\Order::getOrderSn($order->order_id);
        
        if (!$order->save()) 
        {
            throw new \Exception("ORDER_CREATE_ERRORS");
        }
        $log = array(
            'order_id' => $order->order_id,
            'order_sn' => $order->order_sn,
            'operationid' => $user['user_id'],
            'state' => 3,
            'operationname' => $user['uname'],
            'type' => M\EntrustOrdersLog::OPTYPE_USER,
            'demo' => $user['uname'] . '完成下单',
            'order_type' => M\EntrustOrdersLog::ORDER_TYPE_ENTRUST
        );
        
        if (!M\EntrustOrdersLog::saveOrderLog($log)) 
        {
            throw new \Exception('LOG_ERRORS');
        }
        return array(
            $order->order_id,
            $order->order_sn
        );
    }
    /**
     * 获取订单信息
     * @param  string $order_sn 主订单号
     * @return array    0 => 主订单信息 1 => 子订单信息
     */
    
    public function checkWtPay($order_sn = '') 
    {
        $db = $this->getDI() ['db'];
        $sql = "SELECT * FROM entrust_order WHERE order_sn = '{$order_sn}' for update  ";
        
        if (!$data = $db->fetchOne($sql, 2)) 
        {
            throw new \Exception("ORDER_STATUS_ERRORS");
        }
        $data['mainOrder'] = 1;
        $sql = " SELECT * FROM entrust_order_detail WHERE order_parent_id = '{$data['order_id']}'";
        $order_detail = $db->fetchAll($sql, 2);
        // $order_detail[] = $data;
        return array(
            $data,
            $order_detail
        );
        // $data = $this->_dependencyInjector['modelsManager']->executeQuery($phql);
        
    }
    /*update父订单以及相关订单状态*/
    
    public function ModyOrderState($order_sn = '', $demo = '', $type = '') 
    {
        $time = CURTIME;
        $pay_time = '';
        $pay_type = '';
        
        switch ($type) 
        {
        case 'closeorder':
            $state = self::ORDER_STATUS_SHUT;
            break;

        case 'payorder':
            $state = self::ORDER_STATUS_PAY;
            $pay_type = ", pay_type = 6 "; /*后台支付*/
            $pay_time = ", pay_time ='{$time}'";
            break;

        case 'delivery':
            $state = self::ORDER_STATUS_COMP;
            break;

        default:
            break;
        }
        //$time             = CURTIME ;
        $operationid = $this->_dependencyInjector['session']->adminuser['id'];
        $operationname = $this->_dependencyInjector['session']->adminuser['name'];
        $type = self::ADMIN_USER;
        $order_type = self::ORDER_TYPE_ENTRUST;
        $db = $this->getDI() ['db'];
        /*锁表*/
        $sql = " SELECT * FROM entrust_order WHERE order_sn = '{$order_sn}' for update ";
        $data = $db->fetchOne($sql, 2);
        /* 更新主订单状态*/
        $entrustordersql = " UPDATE entrust_order set order_status = '{$state}', last_update_time  = '{$time}'" . $pay_type . $pay_time . " where order_sn = '{$order_sn}' ";
        $db->execute($entrustordersql);
        
        if (!$db->affectedRows()) 
        {
            throw new \Exception(" entrust_order status update failure");
        }
        /* 更新子订单状态*/
        $entrustorderdetail = " UPDATE entrust_order_detail set order_status = '{$state}', last_update_time  = '{$time}'" . $pay_time . " where order_parent_sn = '{$order_sn}' ";
        $db->execute($entrustorderdetail);
        
        if (!$db->affectedRows()) 
        {
            throw new \Exception(" this entrustorderdetail record is not found");
        }
        /* 查找该父订单下所有子订单编号与id*/
        $sql = " SELECT order_detail_id,order_detail_sn FROM entrust_order_detail WHERE order_parent_sn = '{$order_sn}' ";
        $logarray = $db->fetchall($sql);
        /*添加父订单与子订单操作log记录*/
        $logsql = "INSERT INTO entrust_orders_log( state, operationid, operationname, type, demo, order_id ,   order_sn, order_type, addtime ) VALUES ";
        
        if ($logarray) 
        {
            
            foreach ($logarray as $value) 
            {
                $logsql = $logsql . "('{$state}','{$operationid}','{$operationname}','{$type}','{$demo}','{$value["order_detail_id"]}','{$value["order_detail_sn"]}','{$order_type}','{$time}'),";
            }
        }
        $logsql = $logsql . "('{$state}','{$operationid}','{$operationname}','{$type}','{$demo}','{$data['order_id']}','{$order_sn}','{$order_type}','{$time}')";
        
        if ($db->execute($logsql) == false) 
        {
            throw new \Exception("insert entrust_orders_log record is failure ");
        }
        return true;
    }
    /**
     * 更新子订单状态
     * @param integer $order_id      订单id
     * @param string  $demo          备注
     * @param integer $operationid   订单状态
     * @param string  $operationname 操作人姓名
     * @param integer  $operationtype 操作人类型
     */
    
    public function ModyChildState($order_id = 0, $demo = '', $operationid = 0, $operationname = '', $operationtype = 0) 
    {
        $time = CURTIME;
        $operationid = $operationid ? $operationid : $this->_dependencyInjector['session']->adminuser['id'];
        $operationname = $operationname ? $operationname : $this->_dependencyInjector['session']->adminuser['name'];
        $type = self::ADMIN_USER;
        $state = self::ORDER_STATUS_COMP;
        $order_type = self::ORDER_TYPE_ENTRUST;
        $mainlog = '';
        $db = $this->getDI() ['db'];
        /*锁表*/
        $sql = " SELECT * FROM entrust_order_detail WHERE order_detail_id = '{$order_id}' for update ";
        $data = $db->fetchOne($sql, 2);
        /* 更新子订单状态*/
        $entrustorderdetail = " UPDATE entrust_order_detail set order_status = '{$state}', last_update_time  = '{$time}' where order_detail_id = '{$order_id}' ";
        $db->execute($entrustorderdetail);
        
        if (!$db->affectedRows()) 
        {
            throw new \Exception(" entrust_order_detail status update failure");
        }
        /* 查找主订单下所有子订单最小状态*/
        $sql = "select MIN( order_status ) as min from entrust_order_detail where order_parent_id = '{$data['order_parent_id']}'";
        $minstatus = $db->fetchOne($sql, 2);
        $min = self::ORDER_STATUS_COMP; //订单状态 6 完成交易
        
        if ($minstatus) 
        {
            
            if ($minstatus['min'] == $min) 
            {
                /* 更新主订单状态为交易完成*/
                $entrustsql = "select * from entrust_order where order_id = '{$data['order_parent_id']}'";
                $maindata = $db->fetchOne($entrustsql, 2);
                
                if (!$maindata) 
                {
                    throw new \Exception('this order id not found');
                }
                $updatemainorder = " UPDATE entrust_order set order_status = '{$state}', last_update_time  = '{$time}' where order_id = '{$data['order_parent_id']}' ";
                $db->execute($updatemainorder);
                
                if (!$db->affectedRows()) 
                {
                    throw new \Exception(" entrust_order_detail status update failure");
                }
                $mainlog = ",('{$state}','{$operationid}','{$operationname}','{$type}','{$demo}','{$maindata['order_id']}','{$maindata['order_sn']}','{$order_type}','{$time}')";
            }
        }
        /*添加父订单与子订单操作log记录*/
        $logsql = "INSERT INTO entrust_orders_log( state, operationid, operationname, type, demo, order_id ,   order_sn, order_type, addtime ) VALUES ";
        $logsql = $logsql . "('{$state}','{$operationid}','{$operationname}','{$type}','{$demo}','{$data['order_detail_id']}','{$data['order_detail_sn']}','{$order_type}','{$time}')" . $mainlog;
        
        if ($db->execute($logsql) == false) 
        {
            throw new \Exception("insert entrust_orders_log record is failure ");
        }
        return true;
    }
}
