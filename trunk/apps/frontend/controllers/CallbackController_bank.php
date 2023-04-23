<?php
/**
 * 订单支付成功回调
 */
namespace Mdg\Frontend\Controllers;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Lib\Member as Member, Lib\Auth as Auth, Lib\SMS as sms, Lib\Utils as Utils;
use Mdg\Models\Users as Users;
use Mdg\Models\UsersExt as Ext;
use Mdg\Models as M;
use Lib as L;

class CallbackController extends ControllerBase
{
    /**
     * 订单支付成功回调
     * @return [type] [description]
     */
    
    public function indexAction() 
    {
        $data = $_GET;
        $success = false;


        $this->db->begin();
        try
        {
            
            if (!$data) throw new \Exception(1);
            $UmpayClass = new L\UmpayClass();
            
            if (!$UmpayClass->callback($data)) throw new \Exception(M\Orders::SIGN_ERROR);
            $order_sn = $data['orderNum'] ? $data['orderNum'] : 0;
            $order = new M\Orders();
            //检测订单状态
            $orders = $order->checkPay($order_sn, $this->db, 1);
            //插入订单日志
      
            //订单日志 测试
            $sql = " INSERT INTO orders_log (state, operationid, operationname, type, addtime, demo,order_id) values('%s','%s','%s','%s','%s','%s','%s')";
            $phql = sprintf($sql, M\Orders::PAY_STATE, $orders['purid'], $orders['purname'] ,0,time(),'支付订单', $orders['id'] );
            $this->db->execute($phql);
            if (!$this->db->affectedRows()) throw new \Exception(M\Orders::STATE_ERROR); //状态修改失败

            // if (!M\OrdersLog::insertLog($orders['id'], M\Orders::PAY_STATE, $orders['purid'], $orders['purname'], 0, '支付订单')) 
            // {
            //     throw new \Exception(M\Orders::PAY_STATE);
            // }
            //更新订单状态
            
            $order->updateState($orders['id'], M\Orders::PAY_STATE, $this->db);
            $flag = 1 ;
            $success = 1 ;
            $this->db->commit();
        }
        catch(\Exception $e) 
        {   
            $success = 0;
            $this->db->rollback();
            $flag = '失败';
        }

        unset($data['_url']);
        
        $content = $data['orderNum'].'|'.date('Y-m-d H:i:s', time())."|".M\Orders::PAY_STATE. " |{$flag}" ;
        file_put_contents(PUBLIC_PATH.'/log/call_pay.txt', $content);
        $sign = md5(md5($success.$data['orderNum']).$UmpayClass->getYncMd5Key());

        $data = join(":", array( intval($success),$data['orderNum'], $sign));
        
        exit($data);
    }
}
