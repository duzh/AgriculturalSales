<?php
/**
 * 云农宝绑定
 */
namespace Mdg\Member\Controllers;
use Lib\TpayInterface;
use Lib\Validator;
use Mdg\Models as M;
use Lib\YnbInterface;
use Lib\YnbUserInterface;

class YnbbindingController extends ControllerMember
{
    /**
     * 云农宝绑定
     */

    public function indexAction()
    {
        try{
            $uid = $this->getUserID(); #uid
            $userYnpInfo = M\UserYnpInfo::findFirstByuser_id($uid);
            if(is_object($userYnpInfo)) throw new \Exception('已绑定云农宝');
            $mobile = $this->getUserName(); #手机号
            $ynbInterFace = new YnbInterface();
            $token = $ynbInterFace->getYnbToken($uid);
            if(!is_object($token) || $token->is_success == 'F')throw new \Exception("目前无法绑定");
            $this->session->set('token', urlencode(json_encode($token)));
            $creg = $ynbInterFace->checkregister($mobile,$uid,$token->token);
            if($creg->is_success == 'F')throw new \Exception("check error");
            $this->view->mobile = $mobile;

            if($creg->code =='10023'){  #查询接口 判断不存在云农宝帐号 跳转创建页面
                return $this->dispatcher->forward(array(
                    "controller"=> "ynbbinding",
                    "action"	=> "bind",
                ));
            }
            else{
                return $this->dispatcher->forward(array(
                    "controller"=> "ynbbinding",
                    "action"	=> "existent",
                ));
            }
        }
        catch(\Exception $e)
        {
            echo "<script>alert('{$e->getMessage()}');location.href='/member/index'</script>";exit;
            #print_r($e);exit;
        }
    }

    /**
     * 已存在云农宝 绑定云农宝帐号
     */
    public function bindAction(){
        $mobile = $this->getUserName();
        $this->view->mobile = $mobile;
        $this->view->tmmobile = substr_replace($mobile,'****',3,4);
        $this->view->title = '绑定云农宝-用户中心-丰收汇-高价值农产品交易服务商';
    }

    /**
     * 绑定其他云农宝账号
     */
    public function existentAction(){
        $this->view->title = '绑定其他云农宝-用户中心-丰收汇-高价值农产品交易服务商';
    }
    /**
     * 变更其他云农宝
     */
    public function changeAction(){
        try{
            $flag = $this->session->get('ckflag');
            if($flag != '1')throw  new \Exception('验证失败,请重新验证');
            $uid = $this->getUserID(); #uid
            $token = json_decode(urldecode($this->session->get('tokin')));
            $this->session->set('token', urlencode(json_encode($token)));
            $UserYnpInfo = M\UserYnpInfo::findFirst(" user_id={$uid}");
            $mobile = $UserYnpInfo->ynp_user_phone; #手机号
            $this->view->moblie = $mobile;
            $this->view->tmmoblie = substr_replace($mobile,'****',3,4);
            $this->view->title = '修改绑定云农宝-用户中心-丰收汇-高价值农产品交易服务商';
            #$this->session->set('ckflag', 2); #修改session为不可修改
        }catch (\Exception $e){
            echo "<script>alert('{$e->getMessage()}');location.href='/member/index'</script>";exit;
        }
    }
    /**
     * 获取短信
     */
    public function getcodeAction(){
        try{
            $mobile = $this->request->getpost('mobile','string',$this->getUserName());
            $opstype = $this->request->getpost('st','int');
            $ynbInterFace = new YnbInterface();
            $uid = $this->getUserID();
            $token = ($opstype == 1)?$ynbInterFace->getYnbToken($uid):$ynbInterFace->getModifyYnbToken($uid);
            #print_r($token);exit;
            if(!is_object($token))throw new \Exception("Tokin Error");
            $redis = new \Lib\PhpRedis('ynbbind_');
            $redis_exp = 120;
            $redis->set('token', urlencode(json_encode($token)),$redis_exp);
            $sms = $ynbInterFace->getSmsInform($uid,$mobile,$opstype,$token->token);
            if($sms->code == '10009')
                $redis->set('smscodes', urlencode(json_encode($sms)),$redis_exp);
            #print_r($token);print_r($sms);exit;
            echo ($sms)?true:false;exit;
        }
        catch(\Exception $e)
        {
            print_r($e->getMessage());
            return false;
        }

    }
    /**
     * 绑定保存数据方法
     */
    public function savedataAction(){
        try{
            $data['user_id'] = $this->getUserID();
            $data['ynp_user_phone'] = $this->request->getpost('mobile');
            $data['password'] = $this->request->getpost('password');
            $vcode = $this->request->getpost('vcode','string','');
            $redis = new \Lib\PhpRedis('ynbbind_');
            $redis_exp = 10800;
            $token = json_decode(urldecode($redis->get('token')));
            $redis_sms = $redis->get('smscodes');
            if(!$redis_sms)throw new \Exception("短信验证码已超时,请重新获取!");
            $smscodes = json_decode(urldecode($redis_sms));
            if($vcode != $smscodes->smscode){
                throw new \Exception("验证码已失效,请重新下发");
            }
            $users = M\Users::getFshUsers($data['user_id']);
            $num = $redis->get($data['ynp_user_phone']);
//            if($num == 5){
//                throw new \Exception("密码锁定，请3小时后再试");
//            }
            $ynbInterFace = new YnbInterface();
            $ynbRes= $ynbInterFace->bindTogetherYnb($data['user_id'],$this->getUname(),$data['ynp_user_phone'],$data['password'],$token->token,$vcode,($users->usertype==1)?1:2);
//print_r($ynbRes);exit;
            if($ynbRes->code == 10003){ #接口返回成功后执行
                $redis->set($data['user_id'], 0, 0);
                #以下参数接口获取
//                $data['user_id'] = $data['user_id'];
//                $data['ynp_user_phone'] = $data['ynp_user_phone'];
                $this->db->begin();
                try{
                    $UserYnpInfo = M\UserYnpInfo::findFirst(" user_id={$data['user_id']}");
                    if(!$UserYnpInfo){
                        $UserYnpInfo = new M\UserYnpInfo();
                        $UserYnpInfo->add_time = time();
                    }
                    $UserYnpInfo->last_update_time = time();
                    $UserYnpInfo->status = 1;
                    $UserYnpInfo->user_id= $data['user_id'];
                    $UserYnpInfo->ynp_user_phone = $data['ynp_user_phone'];
                    $UserYnpInfo->ynp_member_id = $ynbRes->memberId;
                    $UserYnpInfo->save();
                    $this->db->commit();
                    #echo "<script>alert('绑定成功!');location.href='/member/index'</script>";exit;
                    $returns = array('type'=>1,'msg'=>'绑定成功');
                    echo json_encode($returns);exit;
                }catch (\Exception $e){
                    $this->db->rollback();
                    throw new \Exception('绑定失败');
                }
            }
//            elseif($ynbRes->code == 10004){
//                throw new \Exception($ynbInterFace->getCode($ynbRes->code));
//            }
            else{
                $num = $redis->get($data['ynp_user_phone']);
                if($num){
                    if($num<5){
                        $redis->set($data['ynp_user_phone'], $num+1, $redis_exp);
                        $seeNum = 5-$num;
                        throw new \Exception("密码错误,剩余{$seeNum}次输入机会!");
                    }
                    else{
                        throw new \Exception("密码锁定，请3小时后再试");
                    }
                }
                else{
                    $redis->set($data['ynp_user_phone'], 1, $redis_exp);
                }
                throw new \Exception('用户名或密码错误!');
            }
        }
        catch(\Exception $e)
        {
            $returns = array('type'=>2,'msg'=>$e->getMessage());
            echo json_encode($returns);exit;
            return false;
        }
    }
    /**
     * 更改绑定数据方法
     */
    public function changedataAction(){
        try{
            $data['user_id'] = $this->getUserID();
            $userYnpInfo = M\UserYnpInfo::findFirstByuser_id($data['user_id']);
            $data['user_phone'] = $userYnpInfo->ynp_user_phone;
            $data['ynp_user_phone'] = $this->request->getpost('mobile','string');
            $data['password'] = $this->request->getpost('password','string');
            $vode = $this->request->getpost('vcode','string','');
            $redis = new \Lib\PhpRedis('ynbbind_');
            $redis_exp = 10800;#三个小时内不允许绑定
            $token = json_decode(urldecode($redis->get('token')));
            $redis_sms = $redis->get('smscodes');
            if(!$redis_sms)throw new \Exception("短信验证码已超时,请重新获取!");
            $smscodes = json_decode(urldecode($redis_sms));
            if($vode != $smscodes->smscode){
                throw new \Exception("验证码已失效,请重新下发");
            }
            $num = $redis->get($data['ynp_user_phone']);
            if($num == 5){
                throw new \Exception("密码锁定，请3小时后再试");
            }
            $users = M\Users::getFshUsers($data['user_id']);
            $ynbInterFace = new YnbInterface();
            $ynbRes= $ynbInterFace->modifyYnb($data['user_id'],$data['user_phone'],$data['ynp_user_phone'],$data['password'],$userYnpInfo->ynp_member_id,$token->token,$smscodes->smscode,($users->usertype==1)?1:2);

            if($ynbRes->code == 10019){ #接口返回成功后执行
                $this->db->begin();
                try{
                    #以下参数接口获取
                    $userynpinfo = M\UserYnpInfo::findFirst("user_id={$data['user_id']}");
                    $userynpinfo->ynp_user_phone = $data['ynp_user_phone'];
                    if($ynbRes->memberId)$userynpinfo->ynp_member_id = $ynbRes->memberId;
                    $userynpinfo->last_update_time = time();

                    if($userynpinfo->save()){
                        $this->db->commit();
                        #echo "<script>alert('修改绑定成功!');location.href='/member/index'</script>";exit;
                        $returns = array('type'=>1,'msg'=>'修改绑定成功');
                        echo json_encode($returns);exit;
                    }
                    else{
                        throw new \Exception('change error');
                    }
                } catch (\Exception $e) {
                    $this->db->rollback();
                    throw new \Exception('绑定失败');
                }
            }
            elseif($ynbRes->code == 10004){
                throw new \Exception($ynbInterFace->getCode($ynbRes->code));
            }
            else{
                $num = $redis->get($data['ynp_user_phone']);
                if($num){
                    if($num<5){
                        $redis->set($data['ynp_user_phone'], $num+1, $redis_exp);
                        $seeNum = 5-$num;
                        throw new \Exception("密码错误,剩余{$seeNum}次输入机会!");
                    }
                    else{
                        throw new \Exception("密码锁定，请3小时后再试");
                    }
                }
                else{
                    $redis->set($data['ynp_user_phone'], 1, $redis_exp);
                }
                throw new \Exception('用户名或密码错误!');
            }
        }
        catch(\Exception $e)
        {
            $returns = array('type'=>2,'msg'=>$e->getMessage());
            echo json_encode($returns);exit;
            return false;
        }
    }

    /**
     * 登录操作
     */
    public function gotoynbAction(){
        try{
            #登录
            $uid = $this->getUserID();
            $userynbinfo = M\UserYnpInfo::findFirstByuser_id($uid);
            if(!is_object($userynbinfo)){
                echo "<script>alert('请先绑定云农宝!');location.href='/member/ynbbinding'</script>";exit;
            }
            $moblie = $userynbinfo->ynp_user_phone;
            $ynbInterFace = new YnbInterface();
            $token = $ynbInterFace->getYnbLoginToken($uid,$moblie,$userynbinfo->ynp_member_id);
            if(!$token)throw new \Exception('token 获取失败');
            if($userynbinfo->ynp_user_phone != $token->yid){ #如果返回的手机号不等于丰收汇绑定的手机号更新
                $userynbinfo->ynp_user_phone = $token->yid;
                if(!$userynbinfo->save())
                    throw new \Exception('登录失败');
            }
            $ynbInterFace = new YnbInterface(2);#print_r($uid);echo $moblie; print_r($token);exit;
            $urlparams = $ynbInterFace->loginYnbWallet($uid,$moblie,$userynbinfo->ynp_member_id,$token->token);
            if(!$urlparams)throw new \Exception('无法登录');
            $html ="<script type='text/javascript' src='http://yncstatic.b0.upaiyun.com/js/jquery/jquery-1.11.1.min.js'></script><form action='{$urlparams->url}' method='post' id='hidFrom'>";
            $params = explode('&',$urlparams->data);
            foreach($params AS $pk => $pv){
                $vv = explode('=',$pv);
                $dat = urldecode($vv[1]);
                $html .="<input id='{$vv[0]}' name='{$vv[0]}' type='hidden' value='{$dat}' />";
            }
            $html .="</form><script>$(document).ready(function(){ $('#hidFrom').submit();});</script>";
            echo $html;exit;
        }
        catch(\Exception $e)
        {
            echo "<script>alert('{$e->getMessage()}');location.href='/member/index'</script>";exit;
            return false;
        }
    }

    /**
     * 跳转云农宝注册
     */
    public function gotoregisterAction(){
        $ynb = new YnbInterface();
        $url = $ynb->registInfo();
        echo "<script>location.href='{$url}'</script>";exit;
    }
    /**
     * 验证短信码
     */
    public function checkcodeAction(){

        $vcode = \Lib\Validator::replace_specialChar($this->request->getPost('vcode','string',''));
        if(!$vcode) {
            exit(json_encode(array('error' => '验证码不合法')));
        }
        try{
            $redis = new \Lib\PhpRedis('ynbbind_');
        }catch(\RedisException $e) {
            die(json_encode(array('error'=>'网络异常')));
        }
        $redis_sms = $redis->get('smscodes');
        $smscodes = json_decode(urldecode($redis_sms));
        if(!$smscodes)die(json_encode(array('error'=>'未获取或已失效,请重新获取短信!')));
        if($smscodes->smscode == $vcode){
            $msg['ok'] = 1;
        }else{
            $msg['error']='验证码错误';
        }
        die(json_encode($msg));
    }
    public function checkpassAction(){
        try {
            $uid = $this->getUserID();
            #$mobile = $this->getUserName();
            $redis = new \Lib\PhpRedis('ynbbind_');
            $redis_exp = 10800;#三个小时内不允许绑定
            $pwd = $this->request->getpost('pwd', 'string');
            $UserYnpInfo = M\UserYnpInfo::findFirst(" user_id={$uid}");
            $mobile = $UserYnpInfo->ynp_user_phone; #手机号
            $num = $redis->get($uid);
            if($num==5){
                echo json_encode(array('status'=>false,'msg'=>'密码锁定，请3小时后再试'));exit;
            }
            $ynb = new YnbInterface();
            $token = $ynb->getModifyYnbToken($uid);
            $check = $ynb->checkYidPwd($mobile,$UserYnpInfo->ynp_member_id,$pwd,$uid,$token->token);
//            print_r($check);exit;
            if($check->code == '10025'){
                $this->session->set('ckflag', 1);
                $status =  true;
                $msg = '';
            }
            else{
                $redis->set($uid, $num+1, $redis_exp);
                $status = false;
                $seeNum = 5-$num;
                $msg = "密码错误,剩余{$seeNum}次输入机会!";
            }

            echo json_encode(array('status'=>$status,'msg'=>$msg));
            exit;
        }catch (\Exception $e){
            return 'error';
        }

    }
    /**
     * 验证手机号
     */
    public function checkmbileAction(){

        $mobile = \Lib\Validator::replace_specialChar($this->request->getPost('mobile','string',''));
        if(!$mobile) {
            exit(json_encode(array('error' => '手机号码错误')));
        }
        $checkMbile = M\UserYnpInfo::findFirstByynp_user_phone($mobile);
        if($checkMbile){
            exit(json_encode(array('error' => '此号码已绑定其他帐号.')));
        }
//        if($mobile == $this->getUserName()){
//            exit(json_encode(array('error' => '')));
//        }
        $uid = $this->getUserID();
        try{
            $ynbInterFace = new YnbInterface();
            $token = $ynbInterFace->getYnbToken($uid);

            if(!is_object($token)) throw new \Exception("接口访问错误");
            $creg = $ynbInterFace->checkregister($mobile,$uid,$token->token);

            if(!is_object($creg)) throw new \Exception("接口访问错误");

        }catch(\RedisException $e) {
            die(json_encode(array('error'=>'网络异常')));
        }
        catch(\Exception $e) {
            die(json_encode(array('error'=>$e->getMessage())));
        }
        if($creg->code =='10023'){
            $msg['ok'] = 1;
        }else{
            $msg['error']='此手机号未注册云农宝账号，请更换手机号';
        }
        die(json_encode($msg));
    }
}
