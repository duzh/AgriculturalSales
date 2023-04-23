<?php
namespace Mdg\Models;
use Mdg\Models\OrdersLog as OrdersLog;
use Mdg\Models\Category as Category;
use Lib\Arrays AS Arrays;
use Mdg\Models as M;
use Lib as L;

use Lib\Pages as Pages;
class Orders extends \Phalcon\Mvc\Model
{
    static $_orders_buy_state = array(
        1 => '交易关闭',
        2 => '待确认',
        3 => '待付款',
        4 => '已付款',
        5 => '已发货',
        6 => '已完成',
    );
    static $_orders_sell_state = array(
        1 => '交易关闭',
        2 => '待确认',
        3 => '待付款',
        4 => '已付款',
        5 => '已发货',
        6 => '已完成',
        /*1 => '交易关闭',
        2 => '待确认',
        3 => '已确认',
        4 => '待发货',
        5 => '待确认收货',
        6 => '已完成',*/
    );
    // static $_orders_flag = array(
    //     'cancel' => 1,
    //     'confirm' => 5,
    //     'cancelconfirm' => 2,
    //     'finish'  => 6,
    //     );
    static $add_type=array(
       0=>'pc网站',
       1=>'pc网站',
       2=> 'pc网站',
       3=> 'app',
       4=> 'app',
       5=> '移动端',
       6=> '移动端',
       7=> 'pc网站',
    );
    static $source=array(
       0=>'供应订单',
       1=>'供应订单',
       2=> '采购订单',
       3=> '供应订单',
       4=> '采购订单',
       5=> '供应订单',
       6=> '采购订单',
       7=> '供应订单',
    );
    /**
     * 取消订单
     * @param  integer $order_id 订单ID
     * @return boolean           true 成功 false 失败
     */
    static function cancel($order_id = 0, $userid = 0, $username = '', $demo = '', $type = 0) 
    {

        $order = self::findFirstByid($order_id);
        
        if ($order->state>3) return false;
        
        //通知维金
        $tpay=new L\TpayInterface();
       
        $order_sn= str_replace("mdg", '',$order->order_sn);
        $order_sn=$order_sn+1;
        $cancel_trade=$tpay->cancel_trade($order_sn,$order->order_sn,"后台订单取消");
        
        if (!OrdersLog::insertLog($order_id, 1, $userid, $username, $type, $demo)) return false;
        $order->state = 1;
        return $order->save();
    }
    /**
     * 确认订单
     * @param  integer $order_id 订单ID
     * @return boolean           true 成功 false 失败
     */
    static function confirm($order_id = 0, $userid = 0, $username = '', $demo = '', $type = 0) 
    {
        $order = self::findFirstByid($order_id);
        
        if (2 != $order->state) return false;
        
        if (!OrdersLog::insertLog($order_id, 3, $userid, $username, $type, $demo)) return false;
        $order->state = 3;
        return $order->save();
    }
    /**
     * 取消确认
     * @param  integer $order_id 订单ID
     * @return boolean           true 成功 false 失败
     */
    static function cancelconfirm($order_id = 0, $userid = 0, $username = '', $demo = '', $type = 0) 
    {
        $order = self::findFirstByid($order_id);
        
        if (3 != $order->state) return false;
        
        if (!OrdersLog::insertLog($order_id, 2, $userid, $username, $type, $demo)) return false;
        $order->state = 2;
        return $order->save();
    }
    /**
     * 订单付款
     */
    static function pay($order_id = 0, $userid = 0, $username = '', $demo = '', $type = 0) 
    {
        $order = self::findFirstByid($order_id);
     
        if (3 != $order->state) return false;
        $tpay=new L\TpayInterface();
        $query_trade = $tpay->query_trade($order->order_sn); #查询订单
        
        if(isset($query_trade->error_code) && $query_trade->error_code == 'ORIGINAL_VOUCHER_INEXISTENCE'){ #不存在创建交易
            throw new \Exception('nofound');
        }
        if(isset($query_trade->trade_list[0]) && $query_trade->trade_list[0]->tradeStatus == 'WAIT_BUYER_PAY'){ #当前状态等于 等待买家付款 使用继续支付接口
           throw new \Exception("nopay");
        }else{
           if (!OrdersLog::insertLog($order_id, 4, $userid, $username, $type, $demo)) return false;
            $order->state = 4;
            $order->pay_time=time();
            $order->updatetime=time();
            return $order->save();
        }
        return false;
       
    }
    /**
     * 订单发货
     */
    static function send($order_id = 0, $userid = 0, $username = '', $demo = '', $type = 0) 
    {
        $order = self::findFirstByid($order_id);
        
        if (4 != $order->state) return false;
        
        if (!OrdersLog::insertLog($order_id, 5, $userid, $username, $type, $demo)) return false;
        $order->state = 5;
        return $order->save();
    }
    /**
     * 完成订单
     * @param  integer $order_id 订单ID
     * @return boolean           true 成功 false 失败
     */
    static function finish($order_id = 0, $userid = 0, $username = '', $demo = '', $type = 0) 
    {
        $order = self::findFirstByid($order_id);
        if(!$order) throw new \Exception("ORDERS_INFO_ERRORS");

        if (5 != $order->state) throw new \Exception("ORDERS_INFO_ERRORS");

        $tpay = new L\TpayInterface();
        $order_sn= str_replace("mdg", '',$order->order_sn).rand();
        #佣金分润计算 by duzh 2015-10-13 16:36:22
        if($order->commission_party)
            $royalty_parameters = mb_strlen(ROYALTY_MEMBERID,'utf8').":".ROYALTY_MEMBERID."^9:MEMBER_ID^3:201^".mb_strlen($order->commission,'utf8').":".$order->commission;
        else
            $royalty_parameters = null;
        #end
        $res = $tpay->create_settle($order_sn,$order->order_sn,$royalty_parameters,$order->puserid);

        if($res->is_success == 'F') throw new \Exception('SDWG:'.$res->error_message);
        /* 订单收货 发放补贴*/
        /* 检测卖方是否为可信农场主 */
        $is_kexin=M\UserInfo::selectBycredit_type($order->suserid, M\UserInfo::USER_TYPE_IF );
        if($is_kexin){
              $SubsidySend=new L\SubsidySend($order->suserid);
              $amount = L\Subsidy::subByOrder($order->total);

              $subsidysend=$SubsidySend->sendByOrder($order->id,$order->order_sn,$order->sname,$order->sphone,$amount);
              if(!$subsidysend){
                  $str=$order->id.','.$order->id.','.$subsidytotal.','.date("Y-m-d",time());
                  file_put_contents(PUBLIC_PATH.'/log/subsidysend.txt',$str."\n", FILE_APPEND);
              }
        }

        if (!OrdersLog::insertLog($order_id, 6, $userid, $username, $type, $demo)) return false;
        $order->state = 6;
        if(!$order->save()) throw new \Exception('ORDER_SAVE_ERRORS');
    }
    /**
     *
     * @var integer
     */
    
    public $id = 0;
    /**
     *
     * @var string
     */
    
    public $order_sn = '';
    /**
     *
     * @var integer
     */
    
    public $purid = 0;
    /**
     *
     * @var integer
     */
    
    public $sellid = 0;
    /**
     *
     * @var integer
     */
    
    public $puserid = 0;
    /**
     *
     * @var string
     */
    
    public $purname = '';
    /**
     *
     * @var string
     */
    
    public $purphone = '';
    /**
     *
     * @var integer
     */
    
    public $suserid = 0;
    /**
     *
     * @var string
     */
    
    public $sname = '';
    /**
     *
     * @var string
     */
    
    public $sphone = '';
    /**
     *
     * @var integer
     */
    
    public $areas = 0;
    /**
     *
     * @var string
     */
    
    public $areas_name = '';
    /**
     *
     * @var string
     */
    
    public $address = '';
    /**
     *
     * @var string
     */
    
    public $goods_name = '';
    /**
     *
     * @var double
     */
    
    public $price = 0.00;
    /**
     *
     * @var double
     */
    
    public $quantity = 0.00;
    /**
     *
     * @var integer
     */
    
    public $goods_unit = 0;
    /**
     *
     * @var double
     */
    
    public $total = 0.00;
    /**
     *
     * @var integer
     */
    
    public $addtime = 0;
    /**
     *
     * @var integer
     */
    
    public $state = 1;
    /**
     *
     * @var integer
     */
    
    public $updatetime = 0;
    /**
     *
     * @var integer
     */
    
    public $is_out = 0;

    public $pay_time=0;
    /**
     * 支付方式  云农宝支付
     */
    CONST PAY_YNP = 0;
    /**
     * 支付方式 农业银行支付
     */
    CONST PAY_ABC = 1 ;
    /**
     * 根据条件搜索
     * @param  array  $arr  搜索条件
     * @param  string  $type  类型 1为良品库查询 2为次品库查询
     * @return array
     */
    static 
    public function conditions($arr) 
    {
        $options = array(
            'addtime' => '',
            'order_sn' => '',
            'state' => '',
            'stime' => '',
            'etime' => '',
            'purname' => '',
            'sellname' => ''
        );
        $opt = array_merge($options, $arr);

        $where = ' 1=1 ';
        
        if (is_array($arr)) 
        {

            if (isset($arr['stime']) && !empty($arr['stime']) && isset($arr['etime']) && !empty($arr['etime'])) 
            {
                $where.= " and o.addtime between " . strtotime($arr['stime']) . " and " . strtotime($arr['etime']) . "";
            }
            if (isset($arr['paystime']) && !empty($arr['paystime']) && isset($arr['payetime']) && !empty($arr['payetime'])) 
            {
                $where.= " and o.pay_time!=0 and o.pay_time between " . strtotime($arr['paystime']."00:00:00") . " and " . strtotime($arr['payetime']."23:59:59") . "";
            }
             if(!empty($arr['sellname'])){

                 $user_id=self::getuid($arr['sellname']);
                 if($user_id){
                   $where.= " and o.suserid in ($user_id)";
                 }else{
                   $where.= " and o.suserid in (0)";
                 }
                 
            }
            if(!empty($arr['purname'])){
                 $user_id=self::getuid($arr['purname']);

                 if($user_id){
                   $where.= " and o.puserid in ($user_id)";
                 }else{
                   $where.= " and o.puserid in (0)";
                 }
             }
            empty($arr['state']) ? : $where.= " and o.state =" . intval($arr["state"]);
            empty($arr['order_sn']) ? : $where.= " and o.order_sn like '" . htmlentities($arr["order_sn"]) . "%' ";
        }
      
        return $where;
    }
    static public  function getuid($name){
       $userid='';
       $userid=UsersExt::find(" name like '%{$name}%' ")->toArray();
       if(!empty($userid)){
          return Arrays::getCols($userid,'uid',',');
       }else{
           return false;
       }
    }
    static public  function getUserid($name){
           $userid='';
           $userid=UsersExt::find(" name like '%{$name}%' ");
           if($userid){
              $user=$userid->toArray();
              if(empty($user)){
                 return false;
              }else{
                 return Arrays::getCols($user,'user_id',',');
              }
           }else{
               return false;
           }

    }
    /**
     * 条件搜索
     * @param  [type] $cate_id [description]
     * @return [type]          [description]
     */
    static public function purcate($cate_id=0, $pid=0) 
    {
        
        $goods_id = array();
    
        $pur = Purchase::findBycategory($cate_id)->toArray();
        $sell = Sell::findBycategory($cate_id)->toArray();
        foreach ($pur as $key => $value) 
        {
            $goods_id[] = $value["id"];
        }
        foreach ($sell as $key => $value) 
        {
            $goods_id[] = $value["id"];
        }
        
        if (!empty($goods_id)) 
        {
            return self::getgoods($goods_id);
        }
    }
    /**
     * 条件搜索
     * @param  [type] $cate_id [description]
     * @return [type]          [description]
     */
    static public function purmaxcate($cate_id=0, $pid=0) 
    {
        
        $goods_id = array();
    
        $pur = Purchase::findBymaxcategory($cate_id)->toArray();
        $sell = Sell::findBymaxcategory($cate_id)->toArray();
        foreach ($pur as $key => $value) 
        {
            $goods_id[] = $value["id"];
        }
        foreach ($sell as $key => $value) 
        {
            $goods_id[] = $value["id"];
        }
        
        if (!empty($goods_id)) 
        {
            return self::getgoods($goods_id);
        }
    }
    /**
     *
     * @param  [type] $goods_id [description]
     * @return [type]           [description]
     */
    static public function getgoods($goods_id) 
    {
        $where = "";
        $goods_id = implode(",", $goods_id);
        $where.= " and purid in(" . $goods_id . ") or sellid in (" . $goods_id . ")";
        return $where;
    }
    static 
    public function getordercount($sellid = 0) 
    {
        //待确认的订单  我卖的
        $sellcount = self::count(" suserid={$sellid} and state=2 ");
        return $sellcount ? $sellcount : '';
    }



    /**
     *   丰收汇云农宝对接
     */
        
    CONST STATE_ERROR  = 110001; # 订单状态错误
    CONST SIGN_ERROR   = 110002; # 签名串错误
    CONST UN_PAY_STATE = 3 ;#待付款状态
    CONST PAY_STATE    = 4 ;#待付款状态
    
    CONST ORDER_CANCEL = 1 ;#订单取消


    /**
     * 检测订单是否可付款
     * @param  integer $oid 订单id
     * @return boolean
     */
    public  function checkPay($oid=0,$db=null, $issn=0) {
        $where = " id  = '{$oid}' ";
        if($issn) $where = " order_sn  = '{$oid}'";

        $sql = " SELECT * FROM orders where {$where} AND state = '3'  for update ";

        $data = $db->fetchOne($sql,1);
        if(!$data || $data['state'] != self::UN_PAY_STATE) throw new \Exception(self::STATE_ERROR);
        return $data;
    }
    /**
     * 云农宝支付订单 更新订单状态
     * @param  integer $oid     订单id
     * @param  integer $state   更新状态
     * @param  string  $ynp_sn  云农宝i流水号
     * @param  string  $bank_sn 银行流水号
     * @param  [type]  $db      db
     * @return boolean
     */
    public function updateState($osn=0, $state=0, $ynp_sn='', $bank_sn= '', $paytype=0,$db=null) {
        $time = CURTIME;

        $sql = " UPDATE orders set state ='%s', bank_sn ='{$bank_sn}', ynp_sn = '{$ynp_sn}', pay_type='{$paytype}', pay_time = '{$time}', updatetime ='{$time}' where order_sn = '%s'";
        $phql = sprintf($sql, $state, $osn);
        $db->execute($phql);
        if (!$db->affectedRows()) throw new \Exception(self::STATE_ERROR); //状态修改失败
        
    }
     /**
     * 检测订单是否已经付款
     * @param  integer $oid 订单id
     * @return boolean
     */
    public  function checkabcPay($oid=0,$db=null, $issn=0) {
        $where = " id  = '{$oid}' ";
        if($issn) $where = " order_sn  = '{$oid}'";

        $sql = " SELECT * FROM orders where {$where} AND state = '3'  for update ";

        $data = $db->fetchOne($sql,1);

        if($data&&$data['state'] == self::PAY_STATE)  throw new \Exception(0);
        if(!$data || $data['state'] != self::UN_PAY_STATE) throw new \Exception(self::STATE_ERROR);
        return $data;
    }


    /* 获取成交订单 */
    public static function getOrdersTotal() {
        $cond[] = " state > 3 " ;
        return self::count($cond);
    }

    public static function getOrderInfo($user_id = 0,$p = 1,$page_size = 10,$type ='buy',$arr = array(),$db=null){
        if(!$user_id) return false;
        $where = array(1);
        $whereinfo = array(1);
        $identity  = array();

        $areas = M\UserArea::selectByArea($user_id);

        if(isset($areas['county']) && $areas['county']) {
            $where[] = "  u.district_id = '{$areas['county']}'";
        }elseif(isset($areas['village']) && $areas['village']) {
            $where[] = "  u.town_id = '{$areas['village']}'";
        }else{
            return array('items' => array(), 'pages' => '' );
        }

        if(isset($arr['sellnotorderuid']) && $arr['sellnotorderuid']) {
            $where[]= "  o.puserid != '{$arr['sellnotorderuid']}'";
            $where[] = " u.id = o.puserid";

        }
        if(isset($arr['buyordernotuid']) && $arr['buyordernotuid']) {
            $where[]= "  o.suserid != '{$arr['buyordernotuid']}'";  
            $where[] = " u.id = o.suserid";
        }

        if(isset($arr['puserid']) && $arr['puserid']){
            $where[] = " o.puserid = {$arr['puserid']}";
        }
        if(isset($arr['suserid'])&& $arr['suserid']){
            $where[] = "  o.suserid = {$arr['suserid']}";
        }
        if(isset($arr['purphone']) && $arr['purphone']){
            $where[] = " o.purphone = {$arr['purphone']}";
        }
        if(isset($arr['sphone']) && $arr['sphone']){
            $where[] = "  o.sphone = {$arr['sphone']}";
        }
        if(isset($arr['order_sn']) && $arr['order_sn']){
            $where[] = " o.order_sn = '{$arr['order_sn']}'";
        }
        if(isset($arr['credit_type']) && $arr['credit_type']){
            if(isset($arr['credit_type']) && $arr['credit_type'] == 1 ) {
                $where[] = " u.member_type = 1 ";
            }else{
                $where[] = " u.member_type&{$arr['credit_type']}={$arr['credit_type']}";
            }
            
            // $where[] = " u.member_type&{$arr['credit_type']}={$arr['credit_type']}";
        }
        if(isset($arr['state']) && $arr['state']){
            $where[] = "o.state = {$arr['state']}";
        }
        if (isset($arr['stime']) && $arr['stime'] && isset($arr['etime']) && $arr['etime']) 
        {
            $where[].= "o.addtime >= '" . strtotime($arr['stime'] . "00:00:00") . "'";
            $where[].= "o.addtime <= '" . strtotime($arr['etime'] . "23:59:59") . "'";
        }
        
        $where=implode(" AND ",$where);
     
        $sql = " SELECT count(o.id) as total FROM orders AS o , users AS u WHERE {$where} ";
        $total = $db->fetchOne($sql,2)['total'];
        $offst = intval(($p - 1) * $page_size);

        $sql = " SELECT  o.* , u.`member_type` ,u.`username`, u.id as user_id FROM orders AS o , users AS u WHERE {$where} ORDER BY addtime DESC limit {$offst} , {$page_size}";

        $data = $db->fetchAll($sql,2);
        
        foreach ($data as &$val) {
            /* 获取用户审核身份呢 */
           $val['ident'] = M\UserInfo::selectIdentity($val['user_id']);

        }
        // $whereinfo = implode(" AND ",$whereinfo);
        
        // $userinfo=M\UserInfo::findFirst(" user_id = {$user_id} and credit_type in (2,4) and status=1  ");



        // if($userinfo){
        //     $userinfo = $userinfo->toArray();
        //     $userarea = M\UserArea::findFirst("credit_id = {$userinfo['credit_id']}");
        //     if($userarea){
        //         $users=M\Users::find($whereinfo." AND district_id = {$userarea->district_id}")->toArray();
        //         $id = array_Column($users, 'id');
        //         $id = join(',',$id);

        //         if(!$id) $id = 0.01;

        //         if($type=='buy'){

        //             $data=M\Orders::find($where." AND puserid in ($id) ORDER BY addtime DESC limit {$offst} , {$page_size}")->toArray();
                  
        //             foreach($data as $k=>$v){

        //                 $users = M\Users::findFirstByid($v['puserid']);
        //                 if($users) $users = $users->toArray();
        //                 else $users = '';

        //                 $data[$k]['userinfo'] = $users;
        //                 $data[$k]['usertype'] = M\UserInfo::getmember_typeinfo($v['puserid']);
        //             }
                    
        //             $total = M\Orders::count("{$where} AND puserid in ($id)");
        //         }else{
        //             $data=M\Orders::find($where." AND suserid in ($id) ORDER BY addtime DESC limit {$offst} , {$page_size}")->toArray();
        //             foreach($data as $k=>$v){

        //                 $users = M\Users::findFirstByid($v['suserid']);
        //                 if($users) $users = $users->toArray();
        //                 else $users = '';

        //                 $data[$k]['userinfo'] = $users;
        //                 $data[$k]['usertype'] = M\UserInfo::getmember_typeinfo($v['suserid']);
        //             }

        //             $total = M\Orders::count(" {$where} AND suserid in ($id)");

        //         }
               
        //     }else{
        //         $data = array();
        //         $total = 0;
        //     }
        // }

      
        $page['total_pages'] = ceil($total / $page_size);
        $page['current'] = $p;
        $page['total'] = $total;
        $page = new Pages($page);
        $datas['total'] = $total;
        $datas['items'] = $data;
        $datas['start'] = $offst;
        $datas['pages'] = $page->show(2);
        return $datas;
    } 

}
