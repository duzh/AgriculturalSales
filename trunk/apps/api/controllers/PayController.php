<?php
namespace Mdg\Api\Controllers;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Mdg\Models\Purchase as Purchase;
use Mdg\Models\PurchaseQuotation as Quotation;
use Mdg\Models\Orders as Orders;
use Mdg\Models\OrdersLog as OrdersLog;
use Mdg\Models\Sell as Sell;
use Mdg\Models\Users as Users;
use Mdg\Models\Bank as Bank;
use Lib\Func as Func;
use Lib as L;
use Lib\Pages as Pages;
use Lib\UmpayClass as UmpayClass;

class PayController extends ControllerBase
{
    /**
     * 获取农行支付信息
     * @return string 
     * <code><pre>
     * http://www.5fengshou.com/api/abcpay/getpayurl
     * post
     * order_sn   string   订单编号
     * return
     * {"status":0,"data":{"payUrl":"https:\/\/mobile.abchina.com\/mpay\/KCodePaymentInitBAct.do?TOKEN=14334767089405784224"},"msg":""}
     * </pre></code>
     */
    public function getpayurlAction() 
    {
        
        $postdata = $this->request->getPost('data');
        
         if($postdata){
             $postdata=json_decode($postdata,2);
             if(!empty($postdata)){
                foreach ($postdata as $key => $value) {
                    $_POST[$key]=$value;
                }
             }
         }
        $order_sn = $this->request->getPost('order_sn', 'string', 'mdg152982015095443');
        
        $order = Orders::findFirstByorder_sn($order_sn);
        
        if (!$order) 
        {
           $this->getJson(parent::DATA_EMPTY);
        }

        if ($order->state != 3) 
        {
           $this->getJson(parent::ORDER_STATE_ERROR);
        }

        if($order->paymenturl){
             $datas["payUrl"]=$order->app_paymenturl;
             $this->getJson(parent::SUCCESS,$datas);
        }
        $sn = $order->order_sn;
        $_POST=array(
            'PayTypeID' => 'ImmediatePay',
            'OrderDate' => date('Y/m/d',$order->addtime),
            'OrderTime' => date("H:i:s",$order->addtime),
            'orderTimeoutDate' => '20241019104901',
            'OrderNo' => $sn,
            'CurrencyCode' => '156',
            'PaymentRequestAmount' => $order->total,
            //'PaymentRequestAmount' =>0.01,
            'Fee' => '',
            'OrderDesc' => '丰收汇',
            'OrderURL' => 'http://www.5fengshou.com/msg/merchantqueryorder?ON='.$sn.'&DetailQuery=1',
            'ReceiverAddress' => '1',
            'InstallmentMark' => '0',
            'InstallmentCode' => '',
            'InstallmentNum' => '',
            'CommodityType' => '0101',
            'BuyIP' => '',
            'ExpiredDate' => '30',
            'PaymentType' => 'A',
            'PaymentLinkType' => '2',
            'UnionPayLinkType' => '0',  
            'ReceiveAccount' => '',
            'ReceiveAccName' => '',
            'NotifyType' => '1',
            'ResultNotifyURL' => 'http://www.5fengshou.com/api/pay/callback',
            'MerchantRemarks' => 'Hi',
            'IsBreakAccount' => '0',
            'SplitAccTemplate' => '',
        );
        $order=$order->toArray();
        $order["source"]=1;
        $is_moblie=1;
        require_once (__DIR__  . ' /../../lib/abc/demo/MerchantPayment.php');
        
        if(isset($PaymentURL)&&$PaymentURL!=''){ 
             $datas["payUrl"]=$PaymentURL;
             $this->getJson(parent::SUCCESS,$datas);
        }else{
             $this->getJson(parent::SIGN_ERROR);
        }
    }
    public function callbackAction(){

        require_once (__DIR__ . '/../../lib/abc/ebusclient/Result.php');
        //1、取得MSG参数，并利用此参数值生成验证结果对象
        $tResult = new \Result();
        $tResponse = $tResult->init($_POST['MSG']);
        
        if ($tResponse->isSuccess()) {

            $success = false;
            $data['orderNum'] = $tResponse->getValue("OrderNo");
            //file_put_contents( __DIR__ .'/log/log.txt', $data['orderNum'] , FILE_APPEND );
            $this->db->begin();
            try
            {
                
                if (!$data) throw new \Exception(1);
                $order_sn = $data['orderNum'] ? $data['orderNum'] : 0;
               
                $order = new Orders();
                //检测订单状态
                $orders = $order->checkabcPay($order_sn, $this->db, 1);
                
                $userid=0;
                if($orders["sellid"]){
                  $userid=$orders["sellid"];
                }else{
                  $userid=$orders["purid"];  
                }

                //插入订单日志
                //订单日志 测试
                $sql = " INSERT INTO orders_log (state, operationid, operationname, type, addtime, demo,order_id) values('%s','%s','%s','%s','%s','%s','%s')";
                $phql = sprintf($sql, Orders::PAY_STATE, $userid, $orders['purname'], 0, time() , '支付订单', $orders['id']);
                $this->db->execute($phql);
                
                if (!$this->db->affectedRows()) throw new \Exception(Orders::STATE_ERROR); //状态修改失败
                //更新订单状态
                $order->updateState($order_sn, Orders::PAY_STATE, '', '', 2, $this->db);
                // /* 通知云农宝状态 */
                // $ThriftInterface = new L\ThriftInterface();
                // $UmpayClass = new L\UmpayClass();
                // $status = 1 ;
                // $sign = md5( md5($order_sn . $status )  .$UmpayClass->getYncMd5Key() );
                // $ThriftInterface->noticeRechargeOpsTransactionStatus($order_sn,  $status ,$sign);
                /* 通知云农宝 end */
                $success = $this->db->commit();
                if($success){
                      $flag = parent::SUCCESS;
                }
            }
            catch(\Exception $e) 
            {
                $flag = $e->getMessage();
                $this->db->rollback();
            }
            if($flag==0){
                echo "<input type='hidden' value='0' name='ync_abcpay_resultcode' >";die;
            }else{
                echo "<input type='hidden' value='1' name='ync_abcpay_resultcode' >";die;
            }

        }
    }
    public function mdgcallbackAction(){
        $content = '';
        foreach ($_POST as $key => $val) {
            $content .= " {$key} => {$val},";    
        }
        file_put_contents('aaa.txt',$content, FILE_APPEND);
        require_once (__DIR__ . '/../../lib/abc/ebusclient/Result.php');
        //1、取得MSG参数，并利用此参数值生成验证结果对象
        $tResult = new \Result();
        $tResponse = $tResult->init($_POST['MSG']);
        if ($tResponse->isSuccess()) {
                $order_sn= $tResponse->getValue("OrderNo");
                //$order_sn="mdg18832015098300";
                $Curl = new  L\Curl();
                $data = array('order_sn'=>$order_sn);
                $url="http://mdgdev.ync365.com:81/api/pay/callback";
                $data = $Curl->POST($url,$data);
                if($data==0){
                    echo "<input type='hidden' value='0' name='ync_abcpay_resultcode' >";die;
                }else{
                    echo "<input type='hidden' value='1' name='ync_abcpay_resultcode' >";die;
                }

        }
        
    }
    
}