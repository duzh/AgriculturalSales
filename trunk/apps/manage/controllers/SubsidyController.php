<?php
namespace Mdg\Manage\Controllers;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Mvc\Controller;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Mdg\Models as M;
use Lib\File as File;
use Lib\Func as Func;
use Lib as L;
use Lib\Areas as lAreas;
class SubsidyController extends ControllerMember
{


    public function getsListAction () {
        $cond[] = " status = 0 ";
        $page = $this->request->get('p', 'int', 1);
        $order_sn = $this->request->get('order_sn', 'string', '');
        $status   = $this->request->get('status', 'string', 'all');
        $user_id  = $this->request->get('user_id', 'string', '');
        $mobile   = $this->request->get('mobile', 'string', '');
        $user_name   = $this->request->get('user_name', 'string', '');
        $subsidy_type = $this->request->get('subsidy_type', 'string', 'all');

        if($order_sn) {
            $cond[] = " order_no = '{$order_sn}'";
        }
        if($status != 'all') {
            $status = intval($status);
            $cond[] = " status = '{$status}'";
        }
        if($user_id) {
            $cond[] = " user_id = '{$user_id}'";
        }
        if($mobile) {
            $cond[] = " user_phone = '{$mobile}'";
        }
        if($user_name) {
            $cond[] = " user_name = '{$user_name}'";
        }
        if($subsidy_type!='all'){
            $subsidy_type = intval($subsidy_type);
            $cond[] = " subsidy_type = '{$subsidy_type}'";
        }

        $cond = implode(' AND ', $cond);
        $data = M\Subsidy::getSubsidyList($cond, $page);
        $data['items'] = $data['items']->toArray();

        foreach ($data['items'] as $key => &$value) {
            if ($value['subsidy_type'] == 0) {//交易补贴需要查询支付时间和订单金额
                $res = M\Orders::findFirst("id={$value['order_id']} and order_sn='{$value['order_no']}'");
                if($res){
                    $value['pay_time'] = $res->pay_time;
                    $value['pay_num'] = $res->total;
                }else{
                    $value['pay_time'] = '';
                    $value['pay_num'] = '';
                }
                
            }else{
                $value['pay_time'] = '';
                $value['pay_num'] = '';
            }

        }

        $this->view->user_id = $user_id;
        $this->view->user_name = $user_name;
        $this->view->status = $status;
        $this->view->mobile = $mobile;
        $this->view->order_sn = $order_sn;
        $this->view->_subsidy_type = M\Subsidy::$_subsidy_type;
        $this->view->_status = M\Subsidy::$_status;
        $this->view->data = $data;
        $this->view->subsidy_type = $subsidy_type;

    }
    /**
     * 补贴列表
     * @return [type] [description]
     */
    
    public function indexAction() 
    {
        $cond[] = " 1 ";
        $page = $this->request->get('p', 'int', 1);
		$order_sn = $this->request->get('order_sn', 'string', '');
		$status   = $this->request->get('status', 'string', 'all');
		$user_id  = $this->request->get('user_id', 'string', '');
		$mobile   = $this->request->get('mobile', 'string', '');
        $subsidy_type = $this->request->get('subsidy_type', 'string', 'all');
        
		if($order_sn) {
			$cond[] = " order_no = '{$order_sn}'";
		}
		if($status != 'all') {
			$status = intval($status);
			$cond[] = " status = '{$status}'";
		}
		if($user_id) {
			$cond[] = " user_id = '{$user_id}'";
		}
		if($mobile) {
			$cond[] = " user_phone = '{$mobile}'";
		}
        if($subsidy_type!='all'){
            $subsidy_type = intval($subsidy_type);
            $cond[] = " subsidy_type = '{$subsidy_type}'";
        }

        $cond = implode(' AND ', $cond);
        $data = M\Subsidy::getSubsidyList($cond, $page);
        $data['items'] = $data['items']->toArray();

        foreach ($data['items'] as $key => &$value) {
            if ($value['subsidy_type'] == 0) {//交易补贴需要查询支付时间和订单金额
                $res = M\Orders::find("id={$value['order_id']} and order_sn='{$value['order_no']}'")->toArray();
                $value['pay_time'] = $res['pay_time'];
                $value['pay_num'] = $res['total'];
            }else{
                $value['pay_time'] = '';
                $value['pay_num'] = '';
            }

        }

        $this->view->user_id = $user_id;
        $this->view->user_name = $user_name;
        $this->view->status = $status;
        $this->view->mobile = $mobile;
        $this->view->order_sn = $order_sn;
        $this->view->_subsidy_type = M\Subsidy::$_subsidy_type;
        $this->view->_status = M\Subsidy::$_status;
        $this->view->data = $data;
        $this->view->subsidy_type = $subsidy_type;
        
    }
    /**
     *审核通过
     */
    
    public function BatchAction($sid = 0) 
    {
        
        if (!$sid) 
        {
            parent::msg('来源错误','/manage/subsidy/index');
            // echo "<script>alert('来源错误');location.href='/manage/subsidy/index'</script>";
            // exit;
        }
        try
        {
            /* 检测数据 */
            $info = M\Subsidy::findFirst($sid);
            
            if (!$info) 
            {
                parent::msg('来源错误','/manage/subsidy/index');
                // echo "<script>alert('来源错误');location.href='/manage/subsidy/index'</script>";
                // exit;
            }
            $SubsidySend = new L\SubsidySend($info->user_id);
            $flag = $SubsidySend->checkSend($info->subsidy_id, 1);
            
            if (!$flag) 
            {
                parent::msg('数据异常','/manage/subsidy/index');
            	// echo "<script>alert('数据异常');location.href='/manage/subsidy/index'</script>";
             //    exit;
            }
        }
        catch(\Exception $e) 
        {
        }
        $this->response->redirect('subsidy/index')->sendHeaders();
    }
    /**
     * 审核不通过
     * @return [type] [description]
     */
    
    public function noBatchAction($sid = 0) 
    {
        
        if (!$sid) 
        {
            parent::msg('来源错误','/manage/subsidy/index');
            // echo "<script>alert('来源错误');location.href='/manage/subsidy/index'</script>";
            // exit;
        }
        try
        {
            /* 检测数据 */
            $info = M\Subsidy::findFirst($sid);
            
            if (!$info) 
            {
                parent::msg('来源错误','/manage/subsidy/index');
            	// echo "<script>alert('来源错误');location.href='/manage/subsidy/index'</script>";
             //    exit;
            }
            
            $SubsidySend = new L\SubsidySend($info->user_id);
            $flag = $SubsidySend->checkSend($info->subsidy_id, 2);
           
            if (!$flag) 
            {
                parent::msg('数据异常','/manage/subsidy/index');
            	// echo "<script>alert('数据异常');location.href='/manage/subsidy/index'</script>";
             //    exit;
            }
        }
        catch(\Exception $e) 
        {
        }
        parent::msg('操作成功','/manage/subsidy/index');
        // echo "<script>alert('操作成功');location.href='/manage/subsidy/index'</script>";
    }

    /**
     * 获取用户详细信息
     * @return [type] [description]
     */
    public function getListByuseridAction() {
    	
        $page = $this->request->get('p', 'int', 1);
		$mobile   = $this->request->get('mobile', 'string', '');
		$user_id  = $this->request->get('user_id', 'int', '');
		$order_sn = $this->request->get('order_sn', 'string', '');
		$status   = $this->request->get('status', 'string', 'all');
		$pay_way  = $this->request->get('pay_way', 'string', 'all');
		$cond[] = " 1 ";

		if($order_sn) {
			$cond[] = " order_no = '{$order_sn}'";
		}
		if($status != 'all') {
			$status = intval($status);
			$cond[] = " status = '{$status}'";
		}
		if($pay_way != 'all') {
			$pay_way = intval($pay_way);
			$cond[] = " pay_way = '{$pay_way}'";
		}
		if($user_id) {
			$cond[] = " user_id = '{$user_id}'";
		}
		if($mobile) {
			$cond[] = " user_phone = '{$mobile}'";
		}


    	$cond = implode( ' AND ' , $cond);

    	$data = M\SubsidyPay::getSubsidyPayList($cond , $page );

    	$this->view->user_id = $user_id;
        $this->view->status = $status;
        $this->view->mobile = $mobile;
        $this->view->order_sn = $order_sn;
        $this->view->pay_way = $pay_way;

    	$this->view->_pay_way = M\SubsidyPay::$_pay_way;
    	$this->view->data = $data;

    }
    public function addAction(){
       
    }
    public function createAction(){

        $usermobile   = $this->request->getPOST('usermobile', 'string', '');
        $subsidy_total  = $this->request->getPOST('subsidy_total', 'float', '');
        $order_sn = $this->request->getPOST('order_sn', 'string', '');
        $content = $this->request->getPOST('content', 'string', '');
        if(!$usermobile||!$subsidy_total||!$content){
            parent::msg('各项不能为空','/manage/subsidy/add');
            // echo "<script>alert('各项不能为空');location.href='/manage/subsidy/add';</script>";die;
        }
        $user =M\Users::findFirstByusername($usermobile); 
        if(!$user){
            parent::msg('用户不存在','/manage/subsidy/add');
           // echo "<script>alert('用户不存在');location.href='/manage/subsidy/add';</script>";die;
        }
        $username=M\UsersExt::getusername($user->id);
        $SubsidySend=new L\SubsidySend();
        $subsidysend=$SubsidySend->sendByAdmin($username,$user->id,$usermobile,$subsidy_total,$order_sn);
        Func::adminlog("新增补贴{$subsidy_total}元",$this->session->adminuser["id"]);
        if(!$subsidysend){
            parent::msg('新增补贴失败','/manage/subsidy/add');
            // echo "<script>alert('新增补贴失败');location.href='/manage/subsidy/add';</script>";die;
        }else{
            parent::msg('新增补贴成功','/manage/subsidy/add');
            // echo "<script>alert('新增补贴成功');location.href='/manage/subsidy/add';</script>";die;
        }
        
    }
    public function checkmobileAction(){
        $usermobile = $this->request->getPOST('usermobile', 'string', '');
        $user =M\Users::findFirstByusername($usermobile);
        
        if(!$user){
           $msg["error"]="用户不存在";
           die(json_encode($msg));
        }
        $isif=M\UserInfo::selectBycredit_type($user->id,M\Userinfo::USER_TYPE_IF);
        if(!$isif){
            $msg["error"]="用户不是可信农场";
            die(json_encode($msg));
        }
        $if=M\UserInfo::findFirst("user_id={$user->id} and credit_type=8 ");
        $userfarm=M\UserFarm::findFirstBycredit_id($if->credit_id);
        if($userfarm&&$userfarm->farm_area<100){
            $msg["error"]="耕地面积不符";
            die(json_encode($msg));
        }
        $msg["ok"]='';
        die(json_encode($msg));
    }
    public function checkorderAction(){
        $order_sn = $this->request->getPOST('order_sn', 'string', '');
        $usermobile = $this->request->getPOST('usermobile', 'string', '');
        $user =M\Users::findFirstByusername($usermobile);
        if(!$user){
           $msg["error"]="用户不存在";
           die(json_encode($msg));
        }

        $order=M\Orders::findFirst("puserid={$user->id} and order_sn='{$order_sn}'");
        if(!$order)
        {
            $msg["error"]="订单号不存在";
           die(json_encode($msg));
        }
        if($order->state!=6) {
            $msg["error"]="该订单尚未完成";
           die(json_encode($msg));
        }
        $order=M\SubsidyPay::findFirst("order_no='{$order_sn}' and user_phone='{$usermobile}'");
        if(!$order){
            $msg["error"]="该订单没有使用补贴";
           die(json_encode($msg));
        }
         $msg["ok"]='';
        die(json_encode($msg));
    }
    public function infoAction($subsidy_id){
        
        if(!$subsidy_id){
            $this->msg("来源错误","/manage/subsidy/index");die;
        }
        $order=array();
        //交易产品所属
        $sell_type='其他';
        //销售量
        $order_sell=0;
        //销售额
        $order_amount=0;
        //种植面积
        $plant_area=0;
        //该产品已补贴金额
        $product_subsidy=0;
        //该产品参考补贴金额
        $consult_subsidy=0;
        //土地使用年限
        $use_period='';
        //土地性质
        $source='';
        //土地面积
        $farm_areas=0;
        //该用户已经使用的补贴金额
        $use_money=0;

        //是否符合审核通过的条件
        $checkCanVerify = false;

        $db=$this->db;
        $subsidy=M\Subsidy::findFirst(" subsidy_id='{$subsidy_id}'");

        $use_money=M\UserSubsidy::getsubsidy_total($subsidy->user_id);
        //检测是否认证可信农场
        $isif=M\UserInfo::selectBycredit_type($subsidy->user_id,M\Userinfo::USER_TYPE_IF);
        
        
        if($subsidy->subsidy_type==0){
           $order =M\Orders::findFirstByorder_sn($subsidy->order_no);

           if($order){
               $sell  =M\Sell::findFirstByid($order->sellid);
               if($sell){
                  $category=M\Category::findFirst(" id={$sell->category}");
                  
                  if($category&&$category->crop_type){
                    $sell_type=M\Category::$_crop_type[$category->crop_type];
                  }
                  if($sell->is_source){
                    $source=1;
                  }
                  $tag=M\Tag::findFirst("sell_id={$sell->id} and status=1 ");
                  if($tag){
                    
                    $plant_area=$tag->plant_area;
                    
                    if($plant_area>0){

                        switch ($category->crop_type) {
                            case '1':
                            $consult_subsidy=$plant_area*1200*0.005;
                                break;
                            case '2':
                            $consult_subsidy=$plant_area*5000*0.005;
                            
                                break;    
                            default:
                            $consult_subsidy=0;    
                                break;
                        }
                    }
                  }
               }

               $sellstate=M\Purchase::$_goods_unit;
               $order_sell=$order->quantity.$sellstate[$order->goods_unit];
               $order_amount=$order->total;
               $sql="select sum(subsidy_amount) as subsidy_amount from subsidy where order_no in (select order_sn from orders where  sellid={$order->sellid}) and subsidy_type=0 and STATUS=1 ";
               
               $product_subsidy=$db->fetchOne($sql,2)["subsidy_amount"];
               
               
           }
           if ($isif && $source && ($subsidy->subsidy_amount + $use_money <= 10000) && ($subsidy->subsidy_amount < $consult_subsidy - $product_subsidy)) {
               $checkCanVerify = true;
           }
        }
       
        //审核补贴
        if($subsidy->subsidy_type==1){
           //$userfarm=M\UserFarm::findFirstByuser_id($subsidy->user_id);
           $if=M\UserInfo::findFirst("user_id={$subsidy->user_id} and credit_type=8 and status=1 ");
           if($if){
               $userfarm=M\UserFarm::findFirstBycredit_id($if->credit_id);
            
               if($userfarm){
                    $use_period=$userfarm->year."年".$userfarm->month."月";
                    $source=$userfarm->source ? "自有" : "流转";
                    $farm_areas=$userfarm->farm_area;
               }
            }
           if ($isif && $source && ($subsidy->subsidy_amount + $use_money <= 10000) ) {
               $checkCanVerify = true;
           }
        }
        if ($subsidy->subsidy_type != 1 && $subsidy->subsidy_type != 0) {
            $checkCanVerify = true;
        }

        //操作日志
        $subsidy_log=$db->fetchAll(" select * from subsidy_operate_log where  subsidy_id={$subsidy_id} order by operate_time desc ");
       
        $this->view->_subsidy_type = M\Subsidy::$_subsidy_type;
        $this->view->checkCanVerify = $checkCanVerify;
        $this->view->_status = M\Subsidy::$_status;
        $this->view->subsidy = $subsidy;
        $this->view->isif = $isif;
        $this->view->source = $source;
        $this->view->use_period=$use_period;
        $this->view->sell_type=$sell_type;
        $this->view->order_sell=$order_sell;
        $this->view->order_amount=$order_amount;
        $this->view->product_subsidy=L\Func::format_money($product_subsidy);
        $this->view->plant_area=$plant_area;
        $this->view->consult_subsidy=L\Func::format_money($consult_subsidy);
        $this->view->subsidy_log=$subsidy_log;
        $this->view->HTTP_REFERER = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        $this->view->farm_areas=$farm_areas;
        $this->view->use_money=$use_money;
    }
    //补贴审核通过
    public function auditorpassAction(){
       
        $subsidy_id = $this->request->getPOST('subsidy_id', 'int', 0);
        $status=$this->request->getPOST('status', 'int', 1);
        $url =$this->request->getPOST('HTTP_REFERER', 'string',"/manage/subsidy/index" );
        $url="/manage/subsidy/index";
       
        if(!$subsidy_id) 
        {
             $this->msg("参数错误",$url);
        }
        $info = M\Subsidy::findFirstBysubsidy_id($subsidy_id);  
        if (!$info) 
        {
             $this->msg("数据不存在",$url);
        }
        $SubsidySend = new L\SubsidySend($info->user_id);
        $flag = $SubsidySend->checkSend($info->subsidy_id,$status);
        if (!$flag) 
        {
            $this->msg("操作失败",$url);
        }
        
        $UserSubsidy = new L\UserSubsidy($info->user_id);
        $UserSubsidy->insertAdminLog($subsidy_id,$this->session->adminuser["name"],"审核补贴通过",1);
        //审核通过 给用户发短信
        $sms=new L\SMS();
        $msgs = "尊敬的用户，您的一笔丰收汇补贴已经到账，请到【用户中心>>我的补贴】中查看";
        $mobile=$info->user_phone;
        $str=$sms->send($mobile,$msgs);
        Func::adminlog("审核补贴{$subsidy_id}成功",$this->session->adminuser["id"]);
        $this->msg("操作成功",$url);
    }
    /**
     *  审核失败
     * @return [type] [description]
     */
    public function fallAction(){
         $subsidy_id = $this->request->getPOST('subsidy_id', 'int', 0);
         $reject=$this->request->getPOST('reject', 'string', '');
         $url="/manage/subsidy/index";
         try{
            $this->db->begin();
            $info = M\Subsidy::findFirstBysubsidy_id($subsidy_id);  
            if(!$info) 
            {
               throw new \Exception('SUBSITY NOTFOUND ERROR');
            }
            $info->status=2;
            if(!$info->save()){
                throw new \Exception('SUBSITY FALL ERROR');
            }
            $UserSubsidy = new L\UserSubsidy($info->user_id);
            $UserSubsidy->insertAdminLog($subsidy_id,$this->session->adminuser["name"],"审核补贴不通过",$reject,2);
            $this->db->commit();
            $flag = true;
        } catch(\Exception $e) {
            $this->db->rollback();
            $flag = false;
        }
        if (!$flag) 
        {
            $this->msg("操作失败",$url);
        }
        Func::adminlog("审核补贴{$subsidy_id}失败",$this->session->adminuser["id"]);
        $this->msg("操作成功",$url);

    }
    /**
     *  修改补贴金额
     * @return [type] [description]
     */
    public function saveamountAction(){
         $subsidy_id = $this->request->getPOST('subsidy_id', 'int', 0);
         $price=$this->request->getPOST('price', 'float', '');
         $content=$this->request->getPOST('content', 'string', '');
         $url ="/manage/subsidy/info/{$subsidy_id}";
         
         try{
            $this->db->begin();
            $info = M\Subsidy::findFirstBysubsidy_id($subsidy_id);  
            if(!$info) 
            {
               throw new \Exception('SUBSITY NOTFOUND ERROR');
            }
            $old=$info->subsidy_amount;
            $info->subsidy_amount=$price;
            if(!$info->save()){
                throw new \Exception('SUBSITY FALL ERROR');
            }
            $UserSubsidy = new L\UserSubsidy($info->user_id);
            $UserSubsidy->insertAdminLog($subsidy_id,$this->session->adminuser["name"],"变价:{$old}元变为{$info->subsidy_amount}元",$content,$info->status);
            $this->db->commit();
            $flag = true;
        } catch(\Exception $e) {
            $this->db->rollback();
            $flag = false;
        }
        if (!$flag) 
        {
            $this->msg("修改失败",$url);
        }
        Func::adminlog("修改补贴金额:把{$subsidy_id}由{$old}元变为{$info->subsidy_amount}元",$this->session->adminuser["id"]);
        $this->msg("修改成功",$url);

    }
}
