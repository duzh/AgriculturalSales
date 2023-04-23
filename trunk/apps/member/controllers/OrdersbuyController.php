<?php
namespace Mdg\Member\Controllers;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Mdg\Models\Purchase as Purchase;
use Mdg\Models\PurchaseQuotation as Quotation;
use Mdg\Models\Orders as Orders;
use Mdg\Models\OrderEngineer as OrderEngineer;
use Mdg\Models\OrdersLog as OrdersLog;
use Mdg\Models\Sell as Sell;
use Mdg\Models\Users as Users;
use Mdg\Models\Bank as Bank;
use Mdg\Models\Message as Message;
use Mdg\Models\MessageUsers as MessageUsers;
use Lib\Func as Func;
use Lib\Pages as Pages;
use Lib\UmpayClass as UmpayClass;
use Lib as L;
use Mdg\Models as M;

class OrdersbuyController extends ControllerMember
{

    public function indexAction() {

        $page = $this->request->get('p', 'int', 1);
        $stime = L\Validator::replace_specialChar($this->request->get('stime', 'string', ''));
        $etime = L\Validator::replace_specialChar($this->request->get('etime', 'string', ''));
        $state = $this->request->get('state', 'int', 0);
        $user_id = $this->getUserID();
        $psize = 10;
        $page = intval($page)>0 ? intval($page) : 1;
        $user_id = intval($user_id) ? intval($user_id) : 11;
        $params = array(
            "puserid = '{$user_id}'"
        );

        if ($stime && $etime)
        {
            $params[].= "addtime >= '" . strtotime($stime . "00:00:00") . "'";
            $params[].= "addtime <= '" . strtotime($etime . "23:59:59") . "'";
        }

        if ($state)
        {
            $params[] = "state = '{$state}'";
        }
        $params = array(
            implode(' and ', $params)
        );
        $offst = ($page - 1 ) * $psize ;
        $params['order'] = ' addtime desc ';
        $total = Orders::count($params);
        //$params['limit'] = array( $offst , $psize );
        $params['limit'] = array($psize,$offst);

        $quotation = Orders::find($params);


        $pages['total_pages'] = ceil($total / $psize);
        $pages['current'] = $page;
        $pages['total'] = $total;
        $pages = new L\Pages($pages);
        $pages = $pages->show(2);
        $data['items'] = $quotation;
        $this->view->total_count = ceil($total / $psize);
        $this->view->data = $data;
        $this->view->total = $total;
        $this->view->pages = $pages;
        $this->view->stime = $stime;
        $this->view->etime = $etime;
        $this->view->state = $state;
        $this->view->p = $page;
        $this->view->user_id = $user_id;
        $this->view->orders_state = Orders::$_orders_buy_state;
        $this->view->goods_unit = Purchase::$_goods_unit;
        $this->view->title = '我采购订单-用户中心-丰收汇-高价值农产品交易服务商';
        $this->view->keywords = '丰收汇,农业,农产品,农副产品,农产品批发,农产品电商,农产品现货,农产品交易,农产品加工,农产品价格';
        $this->view->descript = '丰收汇是依托中国农业互联网高科技综合服务商云农场的丰富资源和先进技术而建立的中国最专业的网上农产品服务平台，
        为中国农村专业合作社、中小农商企业等提供农产品信息发布、行情资讯、网上商城、金融服务、定向对接、安全模型、订单种植等专业化服务，
        旨在成为全球高价值农产品交易服务商。';


    }
    /**
     * 采购订单详细
     * @param  [type] $oid 订单id
     * @return
     */
    public function ordersellinfoAction($oid)
    {
        $order = Orders::findFirstByid($oid);

        $address = '';

        if (!$order)
        {
            echo "<script>alert('该订单不存在');location.href='/member/index'</script>";
            exit;
        }

        $flag = false;
        $user_id = $this->getUserID();

        /*  检测订单查看区域 */
        $server = M\UserArea::selectByArea($user_id);

        if(isset($server['county']) && $server['county']) {
            /* 检测用户是否拥有权限 */
            if(!Users::findFirst("id = '{$order->suserid}' AND district_id = '{$server['county']}'") ) {
                echo "<script>alert('您无权查看订单信息');location.href='/member/index'</script>";
                exit;
            }
            $flag = true;
        }else if(isset($server['village']) && $server['village']){

            if(!Users::findFirst("id = '{$order->suserid}' AND town_id = '{$server['village']}'") ) {
                echo "<script>alert('您无权查看订单信息');location.href='/member/index'</script>";
                exit;
            }
            $flag = true;
        }else{
            echo "<script>alert('订单异常');location.href='/member/index'</script>";
            exit;
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
            $address = $purchase ? $qutaion->saddress : '';
            $spec = $qutaion ? $qutaion->spec : '';
        }
        $message = Message::findFirst("order_id = {$oid} and link_type=3");

        if ($message)
        {
            $messageusers = MessageUsers::findFirstBymsg_id($message->msg_id);
            $messageusers->is_new = 1;
            $messageusers->last_update_time = time();
            $messageusers->save();
        }


        $this->view->flag = $flag;
        $this->view->spec = $spec;
        $this->view->address = $address;
        $this->view->order = $order;
        $this->view->order_state = Orders::$_orders_sell_state;
        $this->view->goods_unit = Purchase::$_goods_unit;

        $this->view->title = '我供应的订单详情-用户中心-丰收汇-高价值农产品交易服务商';
        $this->view->keywords = '丰收汇,农业,农产品,农副产品,农产品批发,农产品电商,农产品现货,农产品交易,农产品加工,农产品价格';
        $this->view->descript = '丰收汇是依托中国农业互联网高科技综合服务商云农场的丰富资源和先进技术而建立的中国最专业的网上农产品服务平台，
        为中国农村专业合作社、中小农商企业等提供农产品信息发布、行情资讯、网上商城、金融服务、定向对接、安全模型、订单种植等专业化服务，
        旨在成为全球高价值农产品交易服务商。';
    }
    /**
     * 订单详细
     * @param  [type] $oid 订单id
     * @return
     */
    public function infoAction($oid,$isorder=0)
    {
        $order = Orders::findFirstByid($oid);
        //自动查询订单

        $payCode = 0;
        $address = '';
        $Engineer_phone='';
        if (!$order)
        {
            echo "<script>alert('该订单不存在');location.href='/member/index'</script>";
            exit;
        }

        $user_id = $this->getUserID();

        if ($order->sellid > 0)
        {
            $sell = Sell::findFirstByid($order->sellid);
            $address = $sell->address;
            $spec = $sell->spec;
        }

        if ($order->purid > 0)
        {
            $purchase = Purchase::findFirstByid($order->purid);
            $qutaion = Quotation::findFirst("purid={$order->purid} and suserid={$order->suserid}");

            $address = $purchase ? $qutaion->saddress : '';
            $spec = $qutaion ? $qutaion->spec : '';
        }
        if($isorder){
            $se_mobile=$this->getUserName();
            $userinfocount=M\UserInfo::find("se_mobile='{$se_mobile}' and status=1 ")->toArray();
            $userinfocount=L\Arrays::getCols($userinfocount,'user_id');
            if(!in_array($order->puserid,$userinfocount)){
                echo "<script>alert('您无权查看该订单');location.href='member/commerciaorder/index'</script>";
                exit;
            }
        }
        if($order->state==2){
            $order->total=M\Sell::getordertotal($order->sellid,$order->quantity);
        }else{
            // $order->total=$order->total+$order->subsidy_total;
        }

        $message = Message::findFirst("order_id = {$oid} and link_type=3");

        if ($message)
        {
            $messageusers = MessageUsers::findFirstBymsg_id($message->msg_id);
            $messageusers->is_new = 1;
            $messageusers->last_update_time = time();
            $messageusers->save();
        }

        $OrderEngineer = OrderEngineer::findFirst("order_id='{$oid}'");
        if ($OrderEngineer)
        {
            $Engineer=$OrderEngineer->toArray();
            $Engineer_phone =$Engineer['engineer_phone'] ;
        }
        if($order->commission_party == 2) #如果佣金支付是采购方进行累加值
            $order->total +=$order->commission;
        $this->view->Engineer_phone = $Engineer_phone;
        $this->view->OrderEngineer = $OrderEngineer;
        $this->view->flag = $flag;
        $this->view->spec = $spec;
        $this->view->address = $address;
        $this->view->order = $order;
        $this->view->isorder=($order->puserid != $user_id )?true:$isorder; #增加可看详情无法操作的情况,
        $this->view->order_state = Orders::$_orders_sell_state;
        $this->view->goods_unit = Purchase::$_goods_unit;
        $this->view->goods_desc = $goods_desc;
        $this->view->title = '我供应的订单详情-用户中心-丰收汇-高价值农产品交易服务商';
        $this->view->keywords = '丰收汇,农业,农产品,农副产品,农产品批发,农产品电商,农产品现货,农产品交易,农产品加工,农产品价格';
        $this->view->descript = '丰收汇是依托中国农业互联网高科技综合服务商云农场的丰富资源和先进技术而建立的中国最专业的网上农产品服务平台，
        为中国农村专业合作社、中小农商企业等提供农产品信息发布、行情资讯、网上商城、金融服务、定向对接、安全模型、订单种植等专业化服务，
        旨在成为全球高价值农产品交易服务商。';
    }
    /**
     * 取消订单
     * @return [type] [description]
     */
    public function cancelAction() {

        $rs = array('state'=>false, 'msg'=>'取消成功！');

        $oid = $this->request->get('oid', 'int', 0);
        $order = Orders::findFirstByid($oid);
        if(!$order) {
            $rs['msg'] = '此订单不存在！';
            die(json_encode($rs));
        }
        if($order->state == 1 ) {
            $rs['msg'] = '订单已取消！';
            die(json_encode($rs));
        }
        $user_id = $this->getUserID();
        if($user_id != $order->puserid  || $order->state > 3) {
            $rs['msg'] = '请正确操作取消订单！';
            die(json_encode($rs));
        }

        $UmpayClass = new UmpayClass();
//        $ThriftInterface = new L\Ynp($this->ynp);

        #接口内容包括订单号、订单金额、订单日期（yyyymmdd）、付款人、收款人（默认天辰云农场）、签名
        //创建token
//        $mobile = $this->session->user['mobile'];
//        $sign = md5(md5($order->order_sn . $UmpayClass->source) . $UmpayClass->getYncMd5Key());
//        $ynp =  $ThriftInterface->noticeOpsTransactionClose($order->order_sn , $UmpayClass->source, $sign);

//        if(!$ynp) {
//           $rs['msg'] = '订单取消失败！';
//           die(json_encode($rs));
//        }

        if($order->addtime > '1443283200'){ #只执行 2015-09-27 0点后的订单执行清结算
            #取消订单 清结算系统 START
            $tpay = new L\TpayInterface();
            $cancel= $tpay->cancel_trade($order->order_sn.rand(),$order->order_sn,'用户取消');
            #END
        }


        OrdersLog::insertLog($oid, 1, $user_id, $this->getUserName(), 0, $demo='采购商取消订单');
        $order->state = 1;
        $order->updatetime=time();
        if(!$order->save()) {
            $rs['msg'] = '取消订单失败，请联系客服！';
            die(json_encode($rs));
        }
        //取消订单
        // $subsidy=M\Subsidy::findFirst("order_id={$order->id}");
        // if(!$subsidy){
        //     $subsidytotal=M\SubsidyPay::findFirst("order_id={$order->id}");
        //     if($subsidytotal){
        //          $SubsidySend=new L\SubsidySend($order->puserid);
        //          $subsidysend=$SubsidySend->sendByCancel($order->id,$order->order_sn,$order->purname,$order->purphone,$subsidytotal->pay_amount);
        //          if(!$subsidysend){
        //               $str=$order->id.','.$order->id.','.$subsidytotal.','.date("Y-m-d",time());
        //               file_put_contents(PUBLIC_PATH.'log/sendBycancel.txt',$str."\n", FILE_APPEND);
        //          }
        //          $order->total=Func::format_money($order->total+$subsidytotal->pay_amount);
        //          $order->save();
        //     }
        // }
        $rs['state'] = true;
        die(json_encode($rs));
    }

    public function findtestAction () {

        $ThriftInterface = new L\Ynp($this->ynp);
        $UmpayClass = new L\UmpayClass();
        #接口内容包括订单号、订单金额、订单日期（yyyymmdd）、付款人、收款人（默认天辰云农场）、签名
        $osn = 'mdg169092015064088';

        $clientip = str_replace('.', '', Func::getIP());
        $payType = intval(0)  == 0 ? L\Ynp::PAYTYPE_YNP : L\Ynp::PAYTYPE_ABC;
        $sign = md5(md5($osn.'32'.$payType ).$UmpayClass->getYncMd5Key());
        $data = $ThriftInterface->noticeOpsTransactionStatus($osn, $sign,$payType);
        var_dump($data);
        exit;
    }
    /**
     * 订单收货
     * @return [type] [description]
     */
    public function finishAction() {
        $rs = array('state'=>false, 'msg'=>'完成订单成功！');
        $oid = $this->request->get('oid', 'int', 0);
        $order = Orders::findFirstByid($oid);
        if(!$order) {
            $rs['msg'] = '此订单不存在！';
            die(json_encode($rs));
        }

        $user_id = $this->getUserID();
        if($user_id != $order->puserid  || $order->state != 5) {
            $rs['msg'] = '请正确操作完成订单！';
            die(json_encode($rs));
        }

        $this->db->begin();
        try {
            $sql = " SELECT id FROM orders where id = '{$oid}' for update ";
            $this->db->fetchOne($sql);

            if($order->pay_time > '1443283200'){ #截至到2015 09 27 0点之后的订单执行
                #确认收货 清结算系统 结算 sta
                $tpay = new L\TpayInterface();
                #佣金分润计算 by duzh 2015-10-13 16:36:22
                if($order->commission_party)
                    $royalty_parameters = mb_strlen(ROYALTY_MEMBERID,'utf8').":".ROYALTY_MEMBERID."^9:MEMBER_ID^3:201^".mb_strlen($order->commission,'utf8').":".$order->commission;
                else
                    $royalty_parameters = null;
                #end
                $res = $tpay->create_settle($order->order_sn.rand(),$order->order_sn,$royalty_parameters,$user_id);
                if($res->is_success == 'F') throw new \Exception('SDWG:'.$res->error_message);
            }
            #end
//            /* 云农宝订单支付 通知云农宝 */
//            if($oid > 1822) {
//                $ThriftInterface = new L\Ynp($this->ynp);
//                $UmpayClass = new L\UmpayClass();
//                #接口内容包括订单号、订单金额、订单日期（yyyymmdd）、付款人、收款人（默认天辰云农场）、签名
//                //创建token
//                $mobile = $this->session->user['mobile'];
//                $clientip = str_replace('.', '', Func::getIP());
//                $payType = intval($order->pay_type)  == 0 ? L\Ynp::PAYTYPE_YNP : L\Ynp::PAYTYPE_ABC;
//                $sign = md5(md5($order->order_sn.'32'.$payType ).$UmpayClass->getYncMd5Key());
//                $data = $ThriftInterface->noticeOpsTransactionStatus($order->order_sn, $sign,$payType);
//
//            }

            /* 修改订单状态 */
            OrdersLog::insertLog($oid, 6, $user_id, $this->getUserName() , 0, $demo = '已完成');
            $order->state = 6;
            $order->updatetime = time();

            if(!$order->save()) throw new Exception('orderstate');

            //发放补贴
            /* 检测卖方是否为可信农场主 */
            $is_kexin=M\UserInfo::selectBycredit_type($order->suserid, M\UserInfo::USER_TYPE_IF );
            if($is_kexin){
                $SubsidySend=new l\SubsidySend($order->suserid);

                $amount= L\Subsidy::subByOrder($order->total);

                $subsidysend=$SubsidySend->sendByOrder($order->id,$order->order_sn,$order->sname,$order->sphone,$amount);
                if(!$subsidysend){
                    $str=$order->id.','.$order->id.','.$subsidytotal.','.date("Y-m-d",time());
                    file_put_contents(PUBLIC_PATH.'/log/subsidysend.txt',$str."\n", FILE_APPEND);
                }
            }
            $rs['state'] = true;
            $this->db->commit();
        } catch (\Exception $e) {
            $rs['state'] = false;
            $str =$e->getMessage();
            file_put_contents(PUBLIC_PATH.'/log/finish.log',$str."\n", FILE_APPEND);
            $rs['msg'] = '完成订单失败，请联系客服！';
            $this->db->rollback();
        }
        if($rs['state']&&$order->activity_id>0){

            $sms=new L\SMS();
            $msgs = "您所订购的丰收汇长沟葡萄已确认被签收，如有疑问请拨打4008811365，欢迎您再次购买！上丰收汇，寻找您信赖的长沟葡萄";
            $mobile=$order->purphone;
            $str=$sms->send($mobile,$msgs);
        }

        die(json_encode($rs));

    }




    public function payorderAction($oid){

        if(!isset($oid)){
            echo '此订单不存在！';exit;
        }
        $order = Orders::findFirstByid($oid);
        if(!$order) {
            echo '此订单不存在！';exit;
        }
        $user_id = $this->getUserID();
        if($user_id != $order->puserid  || $order->state != 3) {
            echo '请正确操作未支付订单！';exit;
        }

        // $this->ynppaymentAction($order->id);
        // exit;
        /* 查询订单是否使用补贴 */
        // $subpay = M\SubsidyPay::findFirst(" pay_way  = '0' AND order_id = '{$order->id}'");

        // $usersubsidy=M\UserSubsidy::getusermoney($user_id,$order->total);

        // $isSubsidyPay=M\SubsidyPay::checkSubsidy($order->id);
        // $is_kexin =M\UserInfo::selectBycredit_type($user_id,M\UserInfo::USER_TYPE_IF);

        $Bank = new Bank();
        $this->view->subpay = $subpay;
        $this->view->bank = $Bank->getPaymentList();
        $this->view->bankList = Bank::getBankList();

        $this->view->order = $order;
        $this->view->usersubsidy = $usersubsidy;
        $this->view->isSubsidyPay = $isSubsidyPay;
        $this->view->is_kexin = $is_kexin;
        $this->view->order_amount=Func::format_money($order->total);

        $this->view->title = '我采购的订单详情-用户中心-丰收汇-高价值农产品交易服务商';
        $this->view->keywords = '丰收汇,农业,农产品,农副产品,农产品批发,农产品电商,农产品现货,农产品交易,农产品加工,农产品价格';
        $this->view->descript = '丰收汇是依托中国农业互联网高科技综合服务商云农场的丰富资源和先进技术而建立的中国最专业的网上农产品服务平台，
        为中国农村专业合作社、中小农商企业等提供农产品信息发布、行情资讯、网上商城、金融服务、定向对接、安全模型、订单种植等专业化服务，
        旨在成为全球高价值农产品交易服务商。';
    }
    public function subsidyAction(){

        $userid=$this->session->user["id"];
        $username=$this->session->user["mobile"];
        $user_money = $this->request->getPost('user_money', 'float',0.00);
        $order_id = $this->request->getPost('order_id', 'int',0);
        $order_sn = L\Validator::replace_specialChar($this->request->getPost('order_sn', 'string',''));

        if(!$userid){
            $rs = array('state' => 2,"msg"=>"登录超时");
            echo json_encode($rs);die;
        }
        if(!$userid||!$username||!$user_money||!$order_id||!$order_sn){
            $rs = array('state' => 5,"msg"=>"参数错误");
            echo json_encode($rs);die;
        }
        $isSubsidyPay=M\SubsidyPay::checkSubsidy($order_id);
        if($isSubsidyPay){
            $rs = array('state' => 4,'msg'=>'订单已经使用过补贴');
            echo json_encode($rs);die;
        }
        $order = Orders::findFirstByid($order_id);
        $usersubsidy=M\UserSubsidy::getusermoney($userid,$order->total);
        if($user_money<0 || $user_money>$usersubsidy["ordersubsidy"]){
            $rs = array('state' => 4,'msg'=>'补贴金额错误');
            echo json_encode($rs);die;
        }
        $name=M\UsersExt::getusername($userid);
        $SubsidyPay=new L\SubsidyPay($userid);
        $insert=$SubsidyPay->subsidyUse($order_id,$order_sn,0,$name,$username,$user_money);
        if($insert){
            $order = Orders::findFirstByid($order_id);
            $order->subsidy_total=$user_money;
            $order->total=$order->total-$user_money;
            if($order->save()) {

                $UmpayClass = new UmpayClass();
                $ThriftInterface = new L\Ynp($this->ynp);
                $source = L\Ynp::MDG_SOURCE;
                $sign = md5(md5($order->order_sn . $order->total . $source).$UmpayClass->getYncMd5Key());
                $ynpdata = $ThriftInterface->noticeOpsTransactionAmount($order->order_sn , $order->total , $sign);
            }

            $rs = array('state' => 1,"total"=>Func::format_money($order->total));
            echo json_encode($rs);die;
        }else{
            $rs = array('state' => 3,"msg"=>'使用失败');
            echo json_encode($rs);die;
        }
    }
    public function  payorderproAction(){

        $gate_id =L\Validator::replace_specialChar($this->request->get('gate_id', 'string', ''));
        switch ($gate_id) {
            case '1':
                $gate_id="YNP";
                break;
            case '2':
                $gate_id="ABC";
                break;
        }
        $order_id =$this->request->get('order_id', 'int', '0');
        $order = Orders::findFirstByid($order_id);

        if(!$order) {
            echo '此订单不存在！';exit;
        }

        if($order->total==0){
            die("订单金额不对");
        }
        $user_id = $this->getUserID();
        if($user_id != $order->puserid  || $order->state != 3) {
            echo '请正确操作未支付订单！';exit;
        }


        if($gate_id == 'ABC') {
            $this->payordernongAction($order->id);
            exit;
        }elseif($gate_id == 'YNP') {
            $this->ynppaymentAction($order->id);
            exit;
        }
    }

    /**
     * 云农宝支付
     * @param  integer $oid 订单id
     * @return array
     */
    public function ynppaymentAction($oid=0) {

        //检测用户是否绑定云农宝
//        $flag = $this->checkIsYnp(1);
//
//        if($flag) {
//            echo "<script>location.href='/member/bind/index?isbox=1'</script>";exit;
//        }
        $user_id = $this->getUserID();
        $suserynp_info = M\UserYnpInfo::findFirstByuser_id($user_id);
        if (!is_object($suserynp_info) && !isset($suserynp_info->ynp_member_id))
        {
            echo "<script>alert('您没有绑定云农宝!');location.href='/member/ynbbinding'</script>";
            exit;
        }
        if (!isset($oid) || !$oid)
        {

            echo "<script>alert('请正确操作订单');location.href='/member/orderby'</script>";
            exit;
        }

        $order = Orders::findFirstByid($oid);
        if (!$order)
        {
            echo "<script>alert('请正确操作订单');location.href='/member/orderby'</script>";
            exit;
        }



        if ($user_id != $order->puserid || $order->state != 3)
        {
            echo "<script>alert('请正确操作订单');location.href='/member/orderby'</script>";
            exit;
        }
//        //云农宝原来的开始
//        $UmpayClass = new UmpayClass();
//        $ThriftInterface = new L\Ynp($this->ynp);
//
//        #接口内容包括订单号、订单金额、订单日期（yyyymmdd）、付款人、收款人（默认天辰云农场）、签名
//        //创建token
//        $mobile = $this->session->user['mobile'];
//        $clientip = str_replace('.', '', Func::getIP());
//        $sign = md5(md5($clientip.$mobile).$UmpayClass->getYncMd5Key());
//
//        $token = $ThriftInterface->createBindToken($clientip, $mobile, $sign );
//
//        if(!isset($token) || !$token ){
//            echo "<script>alert('来源错误');location.href='/member/'</script>";exit;
//        }
//
//        //检测买家
//        $info = $ThriftInterface->checkPhoneExist($mobile);
//        if($info != '01') {
//            //绑定用户信息
//            $member = new L\Member();
//            $info = $member->getMember($mobile);
//
//            $ynpinfo = $ThriftInterface->userDataSync(
//                $info['user_id'],
//                $info['user_name'],
//                $info['email'],
//                $info['password'],
//                $info['reg_time'],
//                $info['msn'],
//                '','','',$info['qq'],0
//                );
//        }
//
//        //获取卖家信息
//        $suserid = $order->suserid;
//        if($suserid){
//           $user_name=M\Users::getUsersName($suserid);
//        }
//
//        $sellmobile=$user_name ? $user_name : false;
//        $member = new L\Member();
//        $sellinfo =$member->getMember($sellmobile);
//        if(!$sellinfo||!$sellmobile) {
//             echo "<script>alert('卖家信息有误');location.href='/member/'</script>";exit;
//        }
//
//        $info = $ThriftInterface->checkPhoneExist($sellmobile);
//        if($info != '01') {
//            //绑定用户信息
//
//            $ynpinfo = $ThriftInterface->userDataSync(
//                $sellinfo['user_id'],
//                $sellinfo['user_name'],
//                $sellinfo['email']= '',
//                $sellinfo['password'],
//                $sellinfo['reg_time'],
//                $sellinfo['msn'],
//                '','','',$sellinfo['qq'],0
//            );
//        }
//
//        $data = array(
//            'orderNum' => $order->order_sn,
//            'orderAmount'=> $order->total,
//            'orderDate' => date("Ymd", $order->addtime),
//            'orderName'=> $order->goods_name,
//            'payer'=> $mobile,
//            'receipt' => $sellinfo['user_name'],
//            'order_id' => $order->id,
//            'source' => $UmpayClass->source,
//            'clientip' => $clientip,
//            'token' => $token,
//        );
//        $url  =$UmpayClass->createData($data);

        //云农宝现在的结束
        #收单网关系统支付 start
        #直营经纪人 开始
        $users  = M\Users::findFirstByid($order->suserid);
        if($users&&$users->is_broker){
            $suser_ynp_member_id = BROKER_MEMBERID;
        }
        else{
            #获取卖家用户清结算MEMBER_ID
            $suserynp_info = M\UserYnpInfo::findFirstByuser_id($order->suserid);
            if (!is_object($suserynp_info) && !isset($suserynp_info->ynp_member_id))
            {
                echo "<script>alert('卖家没有绑定云农宝,或云农宝绑定错误!');location.href='/member/ordersbuy'</script>";
                exit;
            }
            $suser_ynp_member_id = $suserynp_info->ynp_member_id;
        }
        #直营经纪人 结束
        #获取买家用户清结算MEMBER_ID
        $puserynp_info = M\UserYnpInfo::findFirstByuser_id($order->puserid); #$puserynp_info->ynp_member_id
        if (!is_object($puserynp_info) && !isset($puserynp_info->ynp_member_id))
        {
            echo "<script>alert('买家没有绑定云农宝,或云农宝绑定错误!');location.href='/member/ordersbuy'</script>";
            exit;
        }
        $tpay = new L\TpayInterface(1);
        #回调地址
        $tpay->return_url = TPAY_RETURN.'Callback/tpay/1/'.$order->order_sn.'/'; #同步
        $tpay->sync_url = TPAY_RETURN.'Callback/tpay/2/';#异步
        $query_trade = $tpay->query_trade($order->order_sn); #查询订单
        // print_r($query_trade);exit;
        #增加佣金支付计算支付额 By duzh 2015-10-13 15:55:08
        switch($order->commission_party){
            case 1: #供应方支付
                $pay_total = $order->total;
                break;
            case 2: #采购方支付
                $pay_total = $order->total+$order->commission;
                break;
            default:
                $pay_total = $order->total;
                break;
        }
        #佣金支付结束
        #查询订单是否已经存在 不存在 创建
        if(isset($query_trade->error_code) && $query_trade->error_code == 'ORIGINAL_VOUCHER_INEXISTENCE'){ #不存在创建交易
            #首次创建交易 清结算
            $trade_info = array(
                $order->order_sn,#订单号
                $order->goods_name, #商品名称
                $order->price, #商品单价
                1, #商品数量floor($order->quantity)
                $pay_total, #商品总价
                $pay_total, #担保金额
                #'uid001',#测试卖家id
                //$order->suserid, #卖家标识id
                $suser_ynp_member_id, #卖家标识id
                //'MEMBER_ID', #卖家标识类型 MEMBER_ID or UID
                'MEMBER_ID',
                $order->order_sn, #业务号
                $order->goods_name,#商品描述
                '',#商品展示URL
                '',#使用订金金额
                //            '',#订金下订的商户网站唯一订单号
                //            '',#商户订单提交时间
                $tpay->sync_url,#服务器异步通知页面路径
                '',#支付过期时间
                ''
            );
            $urlsObj = $tpay->create_ensure_trade($order->order_sn, $trade_info, $this->getUserID(),$puserynp_info->ynp_member_id,'MEMBER_ID', Func::getAddressIp(), null, null, 'N', 'Y');
            #print_r($urlsObj);die;
            if ($urlsObj->is_success == 'F' && $urlsObj->error_code != 'DUPLICATE_REQUEST_NO') {
                echo "<script>alert('{$urlsObj->error_message}');location.href='/member/'</script>";
                exit;
            }
            else if($urlsObj->is_success == 'F' && $urlsObj->error_code == 'DUPLICATE_REQUEST_NO'){
                if($order->paymenturl){
                    $url = $order->paymenturl;
                }
                else{
                    echo "<script>alert('错误 请重新下单');location.href='/member/'</script>";
                }
            }
            elseif($urlsObj->is_success == 'T'){
                #保存支付链接
                $order->paymenturl = $urlsObj->cashier_url;
                $order->save();
                $url = $urlsObj->cashier_url;
            }
        }
        else if(isset($query_trade->trade_list[0]) && $query_trade->trade_list[0]->tradeStatus == 'WAIT_BUYER_PAY'){ #当前状态等于 等待买家付款 使用继续支付接口
            $tpay->re_type = 2; #使用返回连接方式
            $url = $tpay->create_pay($order->order_sn.rand(),$order->order_sn,$this->getUserID(),null,Func::getAddressIp(),$puserynp_info->ynp_member_id,'MEMBER_ID', 'Y');
            #print_r($url);exit;
        }
        else if(isset($query_trade->trade_list[0]) && $query_trade->trade_list[0]->tradeStatus == 'PAY_FINISHED'){ 

            $orders = new Orders();
            $orders->pay($oid, 0, '系统', '去付款未回调主动查询修改', 0);
            die("<script>alert('此订单已支付');location.href='/member/ordersbuy?p=1'</script>");
        }
        else{
            #print_r($query_trade);exit;
            echo "<script>alert('已支付过或支付失败!');location.href='/member/ordersbuy/index'</script>";exit;
        }
        echo "<script>location.href='{$url}'</script>";exit;

    }

    /**
     * 农行支付
     * @param  [type] $oid [description]
     * @return [type]      [description]
     */
    public function payordernongAction($oid)
    {


        if (!isset($oid))
        {
            echo '此订单不存在！';
            exit;
        }

        $order = Orders::findFirstByid($oid);
        if (!$order)
        {
            echo '此订单不存在！';
            exit;
        }

        $user_id = $this->getUserID();

        if ($user_id != $order->puserid || $order->state != 3)
        {
            echo '请正确操作未支付订单！';
            exit;
        }
        // var_dump($order->paymenturl);die;
        if($order->paymenturl) {
            echo "<script language='javascript'>";
            echo "window.location.href='{$order->paymenturl}'";
            echo "</script>";
            exit;
        }

        $sn = $order->order_sn;

        $liborder = new L\Order();
        $data = $order->toArray();
        $data['source'] = L\Order::ORDER_TYPE_ENTRUST;
        $data['order_amount'] = $data['total'];
        $data['goods_id'] = $data['sellid'];
        $posturl = $liborder->postAbcPayment($data);
        exit;
    }


}