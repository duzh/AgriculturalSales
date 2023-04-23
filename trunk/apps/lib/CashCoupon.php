<?php
namespace Lib;
class CashCoupon
{
    /** @var array 商品对应返现比例 */
    static $_config = array(
        7282 => 880,
        7283 => 880,
        7284 => 780,
        7285 => 780,
        7286 => 880,
        7287 => 660,
        7288 => 780,
        7281 => 780,
        6662 => 118,
        7225 => 218,
        6903 => 198,
        6430 => 118,
        6161 => 880
    );
    /*	166 => 100,
    
    185 => 200,
    
    6695 => 500
    
    );*/
    /**
     * 根据商品列表获取此单所返总现金券
     * @param  array  $goodsList 商品列表
     * array(
     * 		array(
     * 			'goods_id' => 1,
     * 			'goods_number' => 5
     * 		)
     * )
     * @return double
     */
    static function getCashAmount($goodsList = array() , $user_name) 
    {
        /*var_dump($goodsList);die;*/
        $amount = 0;
        
        if ((strtotime('2015-08-08') > time()) || (strtotime('2015-08-19') <= time())) 
        {
           return 0;
        }
        $sqlmess = "SELECT contact_id FROM contact where contact_name='{$user_name}'";
        $check = DB::getDB('ync365')->fetchOne($sqlmess, 2);
       
        if ($check) 
        {
            
            foreach ($goodsList as $g) 
            {
                
                if (isset(self::$_config[$g['goods_id']]) && $g['goods_number'] > 0) 
                {
                    $amount+= self::$_config[$g['goods_id']] * $g['goods_number'];
                }
            }
        }

        return $amount > 0 ? sprintf('%.2f', $amount) : 0;
    }
    /**
     * 根据订单商品获取最多可使用多少现金券
     * @param  array  $goodsList 商品列表
     * array(
     * 		array(
     * 			'goods_id' => 1,
     * 			'goods_number' => 5,
     * 			'goods_price' => 0.01
     * 		)
     * )
     * @return double
     */
    static function canUse($goodsList = array()) 
    {
        
        if ((strtotime('2015-09-01') <= time()) || (strtotime('2015-08-19') > time())) 
        {
            return 0;
        }
        $amount = 0;
        
        foreach ($goodsList as $g) 
        {
            
            if (isset(self::$_config[$g['goods_id']]) && $g['goods_number'] > 0) 
            {
                $amount+= $g['goods_price'] * $g['goods_number'];
            }
        }
        return $amount > 0 ? sprintf('%.2f', $amount) : 0;
    }
    /**
     * 检测商品是否属于88活动
     * @param  array  $goodsList 商品列表
     * array(
     * 		array(
     * 			'goods_id' => 1
     * 		)
     * )
     * @return double
     */
    static function checkGoods($goodsList = array()) 
    {
        $con = array_keys(self::$_config);
        
        foreach ($goodsList as $k => $v) 
        {
            
            if (in_array($v['goods_id'], $con)) 
            {
                return true;
                break;
            }
        }
    }
    /**
     * 发放现金券
     * @param  array  $goodsList 商品列表
     * array(
     * 		array(
     * 			'goods_id' => 1,
     * 			'goods_number' => 5,
     * 			'goods_price' => 0.01
     * 		)
     * )
     * @return double
     */
    static function sendCoupon($order = array()) 
    {
        $order_id = $order['order_id'];
        $user_id = $order['user_id'];
        $order_sn = $order['order_sn'];
        $sql = "SELECT * from users_assets where user_id = '{$user_id}' for update";
        $a = DB::getDB('ync365')->fetchOne($sql);

        //用户信息
        $sqlmess = "SELECT user_name FROM users where user_id='" . $user_id . "'";
        $usermess = DB::getDB('ync365')->fetchOne($sqlmess, 2);
        $goodssql = "select goods_id,goods_number,goods_price from order_goods where order_id in (select order_id from order_info where order_id = {$order_id} or main_id = {$order_id})";
        $goodsList = DB::getDB('ync365')->fetchAll($goodssql, 2);

        $cash_coupon = self::getCashAmount($goodsList, $usermess['user_name']);
        
        if ($cash_coupon > 0) 
        {
            $insql = "INSERT INTO cash_coupon_log (amount,type,order_id,order_sn,demo,add_time,user_id) VALUES('$cash_coupon', 0,'" . $order_id . "', '$order_sn', '下单返还现金券', '" . time() . "','" . $user_id . "')";
            $OrderLog = DB::getDB('ync365')->execute($insql);
            
            if (!$OrderLog) 
            {
                throw new \Exception('此订单已发放');
            }
            $sqluser = "SELECT * FROM users_assets where user_id='" . $user_id . "'";
            $users = DB::getDB('ync365')->fetchOne($sqluser, 2);
            
            if (!$users) 
            {
                $sql = "INSERT INTO users_assets (user_id,cash_coupon) VALUES('" . $user_id . "',$cash_coupon)";
                $OrderLog = DB::getDB('ync365')->execute($sql);
            }
            else
            {
                $sql = "UPDATE users_assets set cash_coupon = cash_coupon+" . $cash_coupon . "  where user_id  = '" . $user_id . "' and cash_coupon ='{$a['cash_coupon']}'";
                $a = DB::getDB('ync365')->execute($sql);
            }
        }
    }
}

