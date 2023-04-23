<?php
namespace Mdg\Member\Controllers;
use Mdg\Models\Users as Users;
use Mdg\Models\AreasFull as mAreas;
use Mdg\Models as M;
use Lib\PhpRedis as PhpRedis;
use Lib\YnbInterface;
class IndexController extends ControllerMember
{
    
    public function indexAction() 
    {
       
        $user_id = $this->getUserID();
        $users = Users::findFirstByid($user_id);
        $purchasecatecount=M\UserAttention::count("user_id='{$user_id}'");
        $address = mAreas::checkarea($users->areas);
        if (!$users->areas || !$users->ext->name ) 
        {
            $this->response->redirect("perfect/index")->sendHeaders();
        }
        $this->view->users = $users;
        $this->view->user_type = Users::$_user_type;
        $user_id = $this->getUserID();
        $user_id = intval($user_id) ? intval($user_id) : 11;
        $page = $this->request->get('p', 'int', 1);
        $page = intval($page)>0 ? intval($page) : 1;
        $page_size = 5;
        $offst = intval(($page - 1) * $page_size);
        $advisory_notice = M\Advisory::getcatList('',$page,$offst,$page_size,$catid=8,$order=" updatetime desc");
        $advisory_hot = M\Advisory::getcatList('',$page,$offst,$page_size,$catid='3',$order=" updatetime desc");

        //代付款订单数(买入)
        $no_pay = M\Orders::count("  puserid = {$user_id} and state = '3'");
        //代发货订单数量(买入)
        $no_delivery = M\Orders::count("  puserid = {$user_id} and state = '4'");
        //待确认收货订单数量(买入)
        $no_receiving = M\Orders::count("  puserid = {$user_id} and state = '5'");

        //代付款订单数(卖出)
        $no_pay_sell = M\Orders::count("  suserid = {$user_id} and state = '3'");
        //代发货订单数量(卖出)
        $no_delivery_sell = M\Orders::count("  suserid = {$user_id} and state = '4'");
        //待确认收货订单数量(卖出)
        $no_receiving_sell = M\Orders::count("  suserid = {$user_id} and state = '5'");

        #获取云农宝绑定情况数据 start
        #增加直营经纪人绕过绑定提示
        if(!$users->is_broker){
            $bindynb = M\UserYnpInfo::findFirst("user_id ={$user_id}");
            try{
                $ynbInterFace = new YnbInterface();
                $token = $ynbInterFace->getYnbLoginToken($user_id,$bindynb->ynp_user_phone,$bindynb->ynp_member_id);
                if(!$token)throw new \Exception('token 获取失败');
                if($bindynb->ynp_user_phone != $token->yid){ #如果返回的手机号不等于丰收汇绑定的手机号更新
                    $bindynb->ynp_user_phone = $token->yid;
                    if(!$bindynb->save())
                        throw new \Exception('操作失败');
                }
            }
            catch(\Exception $e){

            }
            $this->view->ynp_user_phone = isset($bindynb->ynp_user_phone)?substr_replace($bindynb->ynp_user_phone,'****',3,4):0;
        }
        else{
            $bindynb = 1;
        }
        
        $this->view->noWarn = ($bindynb)?1:0;
        
        $lwtt=M\UserInfo::getlwttinfo($user_id);
        if($lwtt){
            $redis = new PhpRedis('lwttcount_');
            $lwttcount = "lwttcount{$lwtt}_".$user_id;
            $lwttcount = $redis->get($lwttcount);
            $redisstate = new PhpRedis('lwttstate_');
            $lwttstate = "lwttstate{$lwtt}_".$user_id;
            $lwttstate = $redisstate->get($lwttstate);
        }else{
            $lwttcount=0;
            $lwttstate=0;
        }
        // $lwttcount=0;
        // $lwttstate=0;
        $this->view->lwtt=$lwtt; 
        $this->view->lwttcount=$lwttcount; 
        $this->view->lwttstate=$lwttstate; 
        $this->view->advisory_notice = $advisory_notice['items'];
        $this->view->advisory_hot = $advisory_hot['reList'];
        $this->view->no_pay = $no_pay;
        $this->view->no_delivery = $no_delivery;
        $this->view->no_receiving = $no_receiving;
        $this->view->no_pay_sell = $no_pay_sell;
        $this->view->no_delivery_sell = $no_delivery_sell;
        $this->view->no_receiving_sell = intval($no_receiving_sell);
        $this->view->title = '个人信息-用户中心-丰收汇-高价值农产品交易服务商';
        $this->view->keywords = '丰收汇,农业,农产品,农副产品,农产品批发,农产品电商,农产品现货,农产品交易,农产品加工,农产品价格';
        $this->view->descript = '丰收汇是依托中国农业互联网高科技综合服务商云农场的丰富资源和先进技术而建立的中国最专业的网上农产品服务平台，
        为中国农村专业合作社、中小农商企业等提供农产品信息发布、行情资讯、网上商城、金融服务、定向对接、安全模型、订单种植等专业化服务，
        旨在成为全球高价值农产品交易服务商。';
    }
    
    public function logoutAction() 
    {
        setcookie("village_auth", '', time() - 3600, '/', ".ync365.com");
        setcookie("ync_auth", '', time() - 3600, '/', ".ync365.com");
        setcookie("ync_auth", '', time() + 3600, '/', ".5fengshou.com");
        setcookie("village_auth", '', time() + 3600, '/', ".5fengshou.com");
       setcookie("ync_auth", '', time() + 3600, '/', ".abc.com");
       setcookie("ync_auth", '', time() + 3600, '/', ".ynb.com");
       setcookie("ync_auth", '', time() + 3600, '/', ".5fengshoudev.com");
        session_destroy();
        $this->session->user = array();
        echo "<script src='http://vs.ync365.com/login/emptycookie?key=ync_auth'></script>";
        echo "<script src='http://vs.ync365.com/login/emptycookie?key=village_auth'></script>";
        echo "<script>location.href='/member/login/index/'</script>";exit;
    }
    /**
     * 关闭公告方法
     */
    public function closewarnAction(){
        $type1=$this->request->get('type1','int',1);
        $type2=$this->request->get('type2','int',1);
        $credit_id=$this->request->get('credit_id','int',0);
        $user_id = $this->getUserID();
        if($type1||$type2){
            try{
                if($type1){
                   $redis = new \Lib\PhpRedis('lwttstate_');
                   $a=$redis->set("lwttstate{$credit_id}_".$user_id,1,1080000);
                }
                if($type2)
                {
                   $redis = new \Lib\PhpRedis('lwttcount_');
                   $redis->set("lwttcount{$credit_id}_".$user_id,1,1080000);
                }
            }
            catch(\Exception $e){
                echo 0;
            }
        }
        echo 1;die;
    }
}
