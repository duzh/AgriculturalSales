<?php
namespace Mdg\Api\Controllers;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Mdg\Models\Purchase as Purchase;
use Mdg\Models\PurchaseQuotation as Quotation;
use Mdg\Models\OrdersDelivery as OrdersDelivery;
use Mdg\Models\Orders as Orders;
use Mdg\Models\OrdersLog as OrdersLog;
use Mdg\Models\Sell as Sell;
use Mdg\Models\Users as Users;
use Mdg\Models\Bank as Bank;
use Lib\Func as Func;
use Lib\Pages as Pages;
use Lib\UmpayClass as UmpayClass;
use Lib\Validator as V;
use Lib as L;
use Mdg\Models as M;
class OrderController extends ControllerBase
{
    /**
     * 我买的 @return string  {"errorCode":0,"data":
     * {"order":[{"ordersn":"mdg000000366","createTm":"2015-04-06 19:08:20",
     * "orderPrice":"0.01","goodsName":"\u8611\u83c7","orderStatus":"\u5df2\u5b8c\u6210"}]}}
     * <br />
     *
     *
     *    {
     *       "errorCode": 0,
     *       "data": {
     *           "order": [
     *               {
     *                   "ordersn(订单号)": "mdg000000345",
     *                   "createTm(下单时间)": "2015-03-22 12：45：43",
     *                   "orderPrice(订单金额)": "46.00",
     *                   "id(订单id)": "345",
     *                   "goodsName(商品名称)": "马铃薯",
     *                   "orderStatus(订单状态)": "交易关闭",
     *                   'state(订单状态码)' : "1"
     *               }
     *           ]
     *       }
     *   }
     * <br />
     * <code>
     * <br />
     * post 传值
     * int  p  当前页数
     * int  pagesize 每页显示几条
     * int  state  状态id
     * 1 '交易关闭',
     * 2 '待确认',
     * 3 '待付款',
     * 4 '已付款',
     * 5 '已发货',
     * 6 '已完成'
     * <br/>
     * 搜索条件(0 全部 2 待确认 3 待付款 5 待收货 6 已完成)
     * <br/>
     * url http://www.5fengshou.com/api/order/buy <br />
     * </code>
     */
    
    public function buyAction() 
    {
        $page = $this->request->getPOST('p', 'int', 1);

        $page_size = $this->request->getPOST('pagesize', 'int', 10);
        $state = $this->request->getPOST('state', 'int', 0);

        $user_id = $this->getUid();
 
        if (!$user_id) 
        {
            $this->getMsg(parent::NOT_LOGGED_IN);
        }
        $params = array(
            "puserid = '{$user_id}' ",
        );
        
        if ($state > 0) 
        {
            $params[] = "state = '{$state}'";
        }
        // $params[] = " sellid > 0 ";
        $params = implode(' and ', $params);
        $total = Orders::count($params);
        $offst = intval(($page - 1) * $page_size);
        $data = Orders::find($params . "  ORDER BY addtime DESC limit {$offst} , {$page_size} ")->toArray();

        if (!$data) 
        {
            $this->getMsg(parent::DATA_EMPTY);
        }
        
        foreach ($data as $key => $value) 
        {
            $order[$key]["ordersn"] = $value["order_sn"];
            $order[$key]["createTm"] = date("Y-m-d H:i:s", $value["addtime"]);
           
            if ($value["state"] == 2) 
            {
              
                $order[$key]["orderPrice"] =Sell::getordertotal($value['sellid'],$value['quantity']);
                $order[$key]["price"] = Sell::getprice($value["sellid"]);
            }
            else
            {
                $order[$key]["orderPrice"] =$value["total"];
                $order[$key]["price"] = $value["price"];
            }
            $order[$key]["id"] = $value["id"];
            $order[$key]["goodsName"] = $value["goods_name"];
            $order[$key]["state"] = $value["state"];
            $order[$key]["orderStatus"] = Orders::$_orders_buy_state[$value["state"]];
        }

        $this->getMsg(parent::SUCCESS, array("order"=>$order));
    }
    /**
     * 我买的-订单详情
     * @return string  {"errorCode":0,"data":{"order":{"description":"","goods_name":"\u8461\u8404","goods_unit":"\u5143\/\u76c6","num":"6000","supplyName":"\u6731\u9e4f","supplyMobile":"13161445972","supplyArea":"\u5317\u4eac\u5e02,\u5e02\u8f96\u533a,\u671d\u9633\u533a,\u5efa\u5916\u8857\u9053\u529e\u4e8b\u5904,\u6c38\u5b89\u91cc\u4e1c\u793e\u533a\u5c45\u59d4\u4f1a","ordersn":"mdg000000352","price":"10.00","total":"60000.00","addtime":"2015-04-07 11:45:38","orderStatus":"\u4ea4\u6613\u5173\u95ed"}}}
     * <br />
     *
     *   {
     *       "errorCode": 0,
     *       "data": {
     *           "order": {
     *               "description(规格描述)": "",
     *               "goods_name(商品名称)": "葡萄",
     *               "goods_unit(单位)": "盆",自己加一下 元/
     *               "num(购买数量)": "6000",
     *                "supplyName(姓名)": "朱鹏",
     *               "supplyMobile(电话)": "13161445972",
     *               "supplyArea(地址)": "北京市,市辖区,朝阳区,建外街道办事处,永安里东社区居委会",
     *               "ordersn(订单号)": "mdg000000352",
     *               "price(单品价格)": "10.00",
     *               "total(订单金额)": "60000.00",
     *               "goods_unitid(商品单位id)":'1',
     *               "addtime(下单时间)": "2015-04-07 11:45:38",
     *               "orderStatus(订单状态)": "交易关闭",
     *               'state(订单状态码)' : "1",
     *           }
     *       }
     *   }
     * <br/>
     * <code>
     * <br />
     * post 传值
     * int  oid  订单id
     * 1 '交易关闭',
     * 2 '待确认',
     * 3 '待付款',
     * 4 '已付款',
     * 5 '已发货',
     * 6 '已完成',
     * 
     * <br />
     * url http://www.5fengshou.com/api/order/buyinfo <br />
     * </code>
     */
    
    public function buyinfoAction() 
    {
        $oid = $this->request->getPOST('oid', 'int', 1);
        $order = Orders::findFirstByid($oid);
        
        if (!$order) 
        {
            $this->getMsg(parent::DATA_EMPTY);
        }
        $user_id = $this->getUid();
        
        if (!$user_id) 
        {
            $this->getMsg(parent::NOT_LOGGED_IN);
        }
        
        if ($user_id != $order->puserid) 
        {
            $this->getMsg(parent::NOT_SELF);
        }
        
        if ($order->sellid > 0) 
        {

            $sell = Sell::findFirstByid($order->sellid);
            $address = $sell->address;
            $spec = $sell->spec;
        }
        
        if ($order->purid > 0) 
        {
            $purchase = Purchase::findFirstByid($order->purid);
            $qutaion = Quotation::findFirstBypurid($order->purid);
            $pcontent= M\PurchaseContent::getPurchaseContent($order->purid);

            $address = $purchase ? $qutaion->saddress : '';
            $spec = $pcontent ? $pcontent  : $qutaion->spec ;
           
        }
        
        if ($order->state == 2) 
        {
            $orderPrice = Sell::getprice($order->sellid);
            $total=Sell::getordertotal($order->sellid,$order->quantity);
        }
        else
        {
            $total=$order->total;
            $orderPrice = $order->price;
        }
        $orders["description"] = $spec ? $spec : '';
        $orders['goods_name'] = $order->goods_name;
        $orders['goods_unit'] = Purchase::$_goods_unit[$order->goods_unit];
        $orders['goods_unitid'] = $order->goods_unit;
        $orders["num"] = $order->quantity;
        $orders["supplyName"] = $order->sname;
        $orders["supplyMobile"] = $order->sphone ? $order->sphone : '4008811365(客服电话)';
        $orders["supplyArea"] = $address;
        $orders["ordersn"] = $order->order_sn;
        $orders["oid"] = $order->id;
        $orders["price"] = $orderPrice;
        $orders["total"] = $total;
        $orders["addtime"] = date("Y-m-d H:i:s", $order->addtime);
        $orders["orderStatus"] = Orders::$_orders_buy_state[$order->state];
        $orders["state"] = $order->state;
        $this->getMsg(parent::SUCCESS, $orders);
       
    }
    /**
     * 我卖的
     * @return string  {"errorCode":0,"data":{"order":[{"ordersn":"mdg000000366","createTm":"1428484182","orderPrice":"0.01","goodsName":"\u8611\u83c7","orderStatus":"\u5df2\u5b8c\u6210"}]}}
     * <br/>
     * {
     *       "errorCode": 0,
     *       "data": {
     *           "order": [
     *               {
     *                   "ordersn(订单号)": "mdg000000345",
     *                   "createTm(下单时间)": "2015-04-06 19:00:22",
     *                   "orderPrice(订单金额)": "46.00",
     *                   "id(订单id)": "345",
     *                   "goodsName(商品名称)": "马铃薯",
     *                   "orderStatus(订单状态)": "交易关闭",
     *                   'state(订单状态码)' : "1"
     *               }
     *           ]
     *       }
     *   }
     * <br/>
     * <code>
     * <br />
     * post 传值
     * int  p  当前页数
     * int  pagesize 每页显示几条
     * int  state  状态id
     * <br/>
     * 订单状态:
     *   1 '交易关闭',
     *   2 '待确认',
     *   3 '已确认',
     *   4 '待发货',
     *   5 '待确认收货',
     *   6 '已完成',
     * <br />
     * 搜索条件(0 全部 2 待确认 3已确认 4 已付款  5待确认收货 6 已完成)
     * <br />
     * url http://www.5fengshou.com/api/order/sell <br />
     * </code>
     */
    
    public function sellAction() 
    {
        $page = $this->request->getPOST('p', 'int', 1);
        $page_size = $this->request->getPOST('pagesize', 'int', 10);
        $state = $this->request->getPOST('state', 'int', 0);
        $user_id = $this->getUid();
        
        if (!$user_id) 
        {
            $this->getMsg(parent::NOT_LOGGED_IN);
        }
        $params = array(
            "suserid = '{$user_id}'"
        );
        
        if ($state > 0) 
        {
            $params[] = "state = '{$state}'";
        }
        $params = implode(' and ', $params);
        $total = Orders::count($params);
        $offst = intval(($page - 1) * $page_size);
        $data = Orders::find($params . "  ORDER BY addtime DESC limit {$offst} , {$page_size} ")->toArray();
        
        if (!$data) 
        {
            $this->getMsg(parent::DATA_EMPTY);
        }
        
        foreach ($data as $key => $value) 
        {
            
            $order[$key]["ordersn"] = $value["order_sn"];
            $order[$key]["createTm"] = date("Y-m-d H:i:s", $value["addtime"]);
            
            if ($value["state"] == 2) 
            {
                $order[$key]["orderPrice"] =Sell::getordertotal($value['sellid'],$value['quantity']);
                $order[$key]["price"] = Sell::getprice($value["sellid"]);
            }
            else
            {
                $order[$key]["orderPrice"] = $value["total"];
                $order[$key]["price"] = $value["price"];
            }
            $order[$key]["id"] = $value["id"];
            // $order[$key]["orderPrice"] = $value["total"];
            $order[$key]["goodsName"] = $value["goods_name"];
            $order[$key]["state"] = $value["state"];
            $order[$key]["orderStatus"] = Orders::$_orders_buy_state[$value["state"]];
        }
        $this->getMsg(parent::SUCCESS,  array("order"=>$order));
        
    }
    /**
     * 我卖的-订单详情
     * @return {"errorCode":0,"data":{"order":{"description":"\u9a6c\u94c3\u85af","goods_name":"\u9a6c\u94c3\u85af","goods_unit":"\u5143\/\u516c\u65a4","num":"2","purName":"1352196290","purMobile":"13521962900","purArea":"\u9ed1\u9f99\u6c5f\u7701\u8bb7\u6cb3\u5e02","ordersn":"mdg000000345","price":"23.00","total":"46.00","addtime":"2015-04-06 19:00:22","orderStatus":"\u4ea4\u6613\u5173\u95ed"}}}
     * <br />
     *
     *       {
     *           "errorCode": 0,
     *           "data": {
     *               "order": {
     *                   "description(规格描述)": "马铃薯",
     *                   "goods_name(商品名称)": "马铃薯",
     *                   "goods_unit(单位)": "元/公斤",
     *                   "num(购买数量)": "2",
     *                   "purName(姓名)": "1352196290",
     *                   "purMobile(电话)": "13521962900",
     *                   "purArea(地址)": "黑龙江省讷河市",
     *                   "ordersn(订单号)": "mdg000000345",
     *                   "price(单品价格)": "23.00",
     *                   "total(订单金额)": "46.00",
     *                   "addtime(下单时间)": "2015-04-06 19:00:22",
     *                   "orderStatus(订单状态)": "交易关闭",
     *                   'state(订单状态码)' : "1",
     *               }
     *           }
     *       }
     *
     *
     *
     * <br/>
     * <code>
     * <br />
     * post 传值
     * int  oid  订单id
     * <br/>
     * 订单状态:
     *   1 '交易关闭',
     *   2 '待确认',
     *   3 '已确认',
     *   4 '待发货',
     *   5 '待确认收货',
     *   6 '已完成',
     * <br />
     * url http://www.5fengshou.com/api/order/sellinfo <br />
     * </code>
     */
    
    public function sellinfoAction() 
    {
        $oid = $this->request->getPOST('oid', 'int', 1);
        $order = Orders::findFirstByid($oid);
        
        if (!$order) 
        {
            $this->getMsg(parent::DATA_EMPTY);
        }
        $user_id = $this->getUid();
        
        if (!$user_id) 
        {
            $this->getMsg(parent::NOT_LOGGED_IN);
        }
        
        if ($user_id != $order->suserid) 
        {
            $this->getMsg(parent::NOT_SELF);
        }
        
        if ($order->sellid > 0) 
        {
            $sell = Sell::findFirstByid($order->sellid);
            $address = $sell->address;
            $spec = $sell->spec;
        }
        
        if ($order->purid > 0) 
        {
            $purchase = Purchase::findFirstByid($order->purid);
            $qutaion = Quotation::findFirstBypurid($order->purid);
            $pcontent= M\PurchaseContent::getPurchaseContent($order->purid);
            $address = $purchase ? $qutaion->saddress : '';
            $spec = $pcontent ? $pcontent  : $qutaion->spec ;
        }
        
        if ($order->state == 2) 
        {
            $orderPrice = Sell::getprice($order->sellid);
            $total=Sell::getordertotal($order->sellid,$order->quantity);
        }
        else
        {
            $total=$order->total;
            $orderPrice = $order->price;
        }
        $orders["description"] = $spec ? $spec : '';
        $orders["quantity"] = $orderPrice;
        $orders['goods_name'] = $order->goods_name;
        $orders['goods_unit'] = Purchase::$_goods_unit[$order->goods_unit];
        $orders["num"] = $order->quantity;
        $orders["purName"] = $order->purname;
        $orders["purMobile"] = $order->purphone ? $order->purphone : '4008811365(客服电话)';
        $orders["purArea"] = $order->address;
        $orders["ordersn"] = $order->order_sn;
        $orders["price"] = $orderPrice;
        $orders["oid"] = $order->id;
        $orders["total"] = $total;
        $orders["addtime"] = date("Y-m-d H:i:s", $order->addtime);
        $orders["orderStatus"] = Orders::$_orders_sell_state[$order->state];
        $orders["state"] = $order->state;
        $this->getMsg(parent::SUCCESS, $orders);
       
    }
    /**
     * 我买的-取消订单
     * @return string  {"errorCode":0}
     * <br />
     * <br/>
     * <code>
     * <br />
     * post 传值
     * int  oid  订单id
     * <br />
     * url http://www.5fengshou.com/api/order/buycancel <br />
     * </code>
     */
    
    public function buycancelAction() 
    {
        $oid = $this->request->getPOST('oid', 'int', 0);
        $this->db->begin();
        try
        {
            $order = Orders::findFirstByid($oid);
           
            if (!$order) throw new \Exception(parent::DATA_EMPTY);
            $user_id = $this->getUid();
            
            if (!$user_id) throw new \Exception(parent::NOT_LOGGED_IN);
            
            if ($user_id != $order->puserid || $order->state != 2 && $order->state != 3 ) 
            {
                throw new \Exception(parent::ORDER_ERROR);
            }
            if (!OrdersLog::insertLog($oid, 1, $user_id, $this->getMobile() , 0, $demo = '采购商取消订单')) 
            {
                throw new \Exception(parent::ORDER_CANCEL_ERROR);
            }
            $order->state = 1;
            
            if (!$order->save()) 
            {
                throw new \Exception(parent::ORDER_CANCEL_ERROR);
            }
            $this->db->commit();
            $flag = parent::SUCCESS;
        }
        catch(\Exception $e) 
        {
            $this->db->rollback();
            $flag = $e->getMessage();
        }
        $this->getMsg($flag);
    }
    /**
     * 我卖的-取消订单
     * @return string  {"errorCode":0}
     * <br />
     * <br/>
     * <code>
     * <br />
     * post 传值
     * int  oid  订单id
     * <br />
     * url http://www.5fengshou.com/api/order/sellcancel <br />
     * </code>
     */
    
    public function sellcancelAction() 
    {
        $oid = $this->request->getPOST('oid', 'int', 0);
        $this->db->begin();
        try
        {
            $order = Orders::findFirstByid($oid);
            
            if (!$order) throw new \Exception(parent::DATA_EMPTY);
            $user_id = $this->getUid();
            
            if (!$user_id) throw new \Exception(parent::NOT_LOGGED_IN);
            
            if ($user_id != $order->suserid || $order->state != 2) 
            {
                throw new \Exception(parent::ORDER_ERROR);
            }
            
            if (!OrdersLog::insertLog($oid, 1, $user_id, $this->getMobile() , 0, $demo = '供应商取消交易')) 
            {
                throw new \Exception(parent::ORDER_CANCEL_ERROR);
            }
            $order->state = 1;
            
            if (!$order->save()) 
            {
                throw new \Exception(parent::ORDER_CANCEL_ERROR);
            }
            $this->db->commit();
            $flag = parent::SUCCESS;
        }
        catch(\Exception $e) 
        {
            $this->db->rollback();
            $flag = $e->getMessage();
        }
        $this->getMsg($flag);
    }
    /**
     * 我卖的-设置价格-第一步
     * @return {"errorCode":0,"data":{"order":{"id":"345","sname":"\u5218\u767e\u6d9b","sphone":"13614613225","address":"\u5317\u4eac\u5e02,\u5e02\u8f96\u533a,\u4e1c\u57ce\u533a,\u4e1c\u534e\u95e8\u8857\u9053\u529e\u4e8b\u5904","quantity":"2","goods_unit":"\u5143\/\u516c\u65a4"}}}
     * <br />
     * {
     *        "errorCode": 0,
     *        "data": {
     *            "order": {
     *                "id(订单id)": "345",
     *                "sname(姓名)": "刘百涛",
     *                "sphone(电话)": "13614613225",
     *                "address(收货人地址)": "北京市,市辖区,东城区,东华门街道办事处",
     *                "quantity(购买数量)": "2",
     *                "goods_unit(单位)": "元/公斤"
     *            }
     *        }
     *    }
     * <br/>
     * <code>
     * <br />
     * post 传值
     * int  oid  订单id
     * <br />
     * url http://www.5fengshou.com/api/order/editprice <br />
     * </code>
     */
    
    public function editpriceAction() 
    {
        $user_id = $this->getUid();
        $oid = $this->request->getPOST('oid', 'int', 0);
        $order = Orders::findFirstByid($oid);
        
        if (!$order) 
        {
            $this->getMsg(parent::DATA_EMPTY);
        }
        
        if ($user_id != $order->suserid || $order->state != 2) 
        {
            $this->getMsg(parent::ORDER_ERROR);
        }
        $orders["id"] = $order->id;
        $orders["sname"] = $order->purname;
        $orders["sphone"] = $order->purphone;
        $orders["address"] = $order->address;
        $orders["quantity"] = $order->quantity;
        $orders['goods_unit'] =Purchase::$_goods_unit[$order->goods_unit];
        $this->getMsg(parent::SUCCESS, $orders);
    }
    /**
     * 我卖的-设置价格-提交
     * @return {"errorCode":0}
     * <br />
     * <code>
     * <br />
     * post 传值
     * int  oid  订单id
     * float price 价格
     * <br />
     * url http://www.5fengshou.com/api/order/saveprice <br />
     * </code>
     */
    
    public function savepriceAction() 
    {
        $oid = $this->request->getPost('oid', 'int', 0);
        $price = $this->request->getPost('price', 'float', 0.00);
        
        if (!$price || !$oid) 
        {
            $this->getMsg(parent::PARAMS_ERROR);
        }
        $this->db->begin();
        try
        {
            $order = Orders::findFirstByid($oid);
            
            if (!$order) 
            {
                throw new \Exception(parent::DATA_EMPTY);
            }
            $user_id = $this->getUid();
            
            if (!$user_id) throw new \Exception(parent::NOT_LOGGED_IN);
            
            if ($user_id != $order->suserid || $order->state != 2) 
            {
                throw new \Exception(parent::ORDER_ERROR);
            }
            
            if (!OrdersLog::insertLog($oid, 3, $user_id, $this->getMobile() , 0, $demo = '供应商修改价格确认订单')) 
            {
                throw new \Exception(parent::ORDER_SAVEPRICE_ERROR);
            }
            $order->state = 3;
            $order->price = $price;
            $order->total = $order->price * $order->quantity;
            
            if (!$order->save()) 
            {
                throw new \Exception(parent::ORDER_SAVEPRICE_ERROR);
            }
            $this->db->commit();
            $flag = parent::SUCCESS;
        }
        catch(\Exception $e) 
        {
            $this->db->rollback();
            $flag = $e->getMessage();
        }
        $this->getMsg($flag);
    }
    /**
     * 我买的-确认收货
     * @return string  {"errorCode":0}
     * <br />
     * <br/>
     * <code>
     * <br />
     * post 传值
     * int  oid  订单id
     * <br />
     * url http://www.5fengshou.com/api/order/finish <br />
     * </code>
     */
    
    public function finishAction() 
    {
        $oid = $this->request->getPOST('oid', 'int', 0);
        $this->db->begin();
        try
        {
            $order = Orders::findFirstByid($oid);
            
            if (!$order) 
            {
                throw new \Exception(parent::DATA_EMPTY);
            }
            $user_id = $this->getUid();
            
            if (!$user_id) throw new \Exception(parent::NOT_LOGGED_IN);
            
            if ($user_id != $order->puserid || $order->state != 5) 
            {
                throw new \Exception(parent::ORDER_ERROR);
            }
            
            if (!OrdersLog::insertLog($oid, 6, $user_id, $this->getMobile() , 0, $demo = '确认收货')) 
            {
                throw new \Exception(parent::ORDER_FINISH_ERROR);
            }
            $order->state = 6;
            
            if (!$order->save()) 
            {
                throw new \Exception(parent::ORDER_FINISH_ERROR);
            }
            $this->db->commit();
            $flag = parent::SUCCESS;
        }
        catch(\Exception $e) 
        {
            $this->db->rollback();
            $flag = $e->getMessage();
        }
        $this->getMsg($flag);
    }
    /**
     * 我卖的-发货
     * @return string  {"errorCode":0}
     * <br />
     * <br/>
     * <code>
     * <br />
     * post 传值
     * int  oid  订单id
     * string  fahuo  发货方式  1 快递发货  2 汽车货运  3 采购自提
     * string  wuliu_sn 物流号
     * string  wuliu_gongsi 物流公司
     * string  driver_name  司机姓名
     * string  driver_phone 司机电话
     * <br/>
     * string  name       提货人姓名
     * string  mobile     提货人电话
     * <br />
     * url http://www.5fengshou.com/api/order/send <br />
     * </code>
     */
    
    public function sendAction() 
    {
        $oid = $this->request->getPOST('oid', 'int', 0);
        $type = $this->request->getPOST('type', 'int', 0);
        $fuck1 = $this->request->getPOST('fuck1', 'string', '');
        $fuck2 = $this->request->getPOST('fuck2', 'string', '');
        if (!$type || !$fuck1 || !$fuck2 ) 
        {
            $this->getMsg(parent::PARAMS_ERROR);
        }
        
        //检测各项数据
        if ($type == 2 && !V::validate_is_mobile($fuck2)) 
        {
            $this->getMsg(parent::PARAMS_ERROR);
        }
        
        if ($type == 3 && !V::validate_is_mobile($fuck2)) 
        {
            $this->getMsg(parent::PARAMS_ERROR);
        }

        $this->db->begin();
        try
        {
            $order = Orders::findFirstByid($oid);
            
            if (!$order) 
            {
                throw new \Exception(parent::ORDER_ERROR);
            }
            $user_id = $this->getUid();
            
            if (!$user_id) throw new \Exception(parent::NOT_LOGGED_IN);
            
            if ($user_id != $order->suserid || $order->state != 4) 
            {
                throw new \Exception(parent::ORDER_ERROR);
            }
            
            if (!OrdersLog::insertLog($oid, 5, $user_id, $this->getMobile() , 0, $demo = '订单发货')) 
            {
                throw new \Exception(parent::ORDER_SEND_ERROR);
            }
            $OrdersDelivery = new OrdersDelivery();
            $OrdersDelivery->deliverytype = $type;
            
            if ($type == 1) 
            {
                $OrdersDelivery->deliveryname = $fuck1;
            }
            else
            {
                $OrdersDelivery->deliveryname = OrdersDelivery::$dev_name[$type];
            }
            if($type==2){
            $OrdersDelivery->orderid = $oid;
            $OrdersDelivery->drivername = $fuck1;
            $OrdersDelivery->driverphone = $fuck2;
            }
            if($type==3){
            $OrdersDelivery->orderid = $oid;
            $OrdersDelivery->name = $fuck1;
            $OrdersDelivery->mobile = $fuck2;
            }
            if($type==1){
            $OrdersDelivery->orderid = $oid;
            $OrdersDelivery->delivery_sn = $fuck2;    
            }

            if (!$OrdersDelivery->save()) 
            {
                throw new \Exception(parent::ORDER_SEND_ERROR);
            }
            $order->state = 5;
            
            if (!$order->save()) 
            {
                throw new \Exception(parent::ORDER_SEND_ERROR);
            }
            $this->db->commit();
            $flag = parent::SUCCESS;
        }
        catch(\Exception $e) 
        {
            $this->db->rollback();
            $flag = $e->getMessage();
        }
        $this->getMsg($flag);
    }
}
