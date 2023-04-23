<?php
namespace Mdg\Member\Controllers;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Lib\Member as Member, Lib\Auth as Auth, Lib\SMS as sms, Lib\Utils as Utils;
use Mdg\Models\Users as Users;
use Mdg\Models\UsersExt as Ext;
use Lib as L;
// use Mdg\Models\YncUsers as YncUsers;


class BindController extends ControllerMember
{
    
    public function indexAction() 
    {
        $this->view->title = '用户中心-绑定账号-高价值农产品交易服务商';
        $this->view->keywords = '丰收汇,农业,农产品,农副产品,农产品批发,农产品电商,农产品现货,农产品交易,农产品加工,农产品价格';
        $this->view->descript = '丰收汇是依托中国农业互联网高科技综合服务商云农场的丰富资源和先进技术而建立的中国最专业的网上农产品服务平台，
        为中国农村专业合作社、中小农商企业等提供农产品信息发布、行情资讯、网上商城、金融服务、定向对接、安全模型、订单种植等专业化服务，
        旨在成为全球高价值农产品交易服务商。';

        $ThriftInterface = new L\Ynp($this->ynp);
        if(!$this->checkIsYnp(1)) {

            echo "<script>alert('来源错误');javascript:history.go(-1);</script>";exit;
        }
        //检测该手机号是否注册云农宝
        // $data  = $this->checkIsYnp();

        $data = $this->session->user;
        $this->view->data = $data;
    }
    /**
     * 保存数据
     * @return [type] [description]
     */
    public function saveAction() 
    {

        //查询当前登录用户的信息
        
    

        // $villagecode = $this->session->villagecode;
        // $vcode = $this->session->vcode;
        // $mobile = L\Validator::replace_specialChar($this->request->getPost('mobile', 'string', ''));
        // $msg_yz = L\Validator::replace_specialChar($this->request->getPost('msg_yz', 'string', ''));
        // $img_yz = L\Validator::replace_specialChar($this->request->getPost('img_yz', 'string', ''));
        // //同步用户
        // // if((string)strtolower($img_yz) != (string)strtolower($villagecode) ) {
        // //     echo "<script>alert('图形验证码错误');history.back(-1);</script>";exit;
        // // }
        // // if((string)strtolower($msg_yz) != (string)strtolower($vcode) ){
        // //     echo "<script>alert('验证码错误');history.back(-1);</script>";exit;
        // // }

        // //查询云农场农户信息
        // $users = YncUsers::getYncUsers($mobile);
        // if(!$users) {
        //     echo "<script>alert('此用户不存在');history.back(-1);</script>";exit;
        // }

        // $data = array(
        //     'userId' => $users['user_id'],
        //     'userPhone' => $users['user_name'], 
        //     'userEmail' => $users['email'],
        //     'userLoginPassword' => $users['password'],
        //     'realName' => $users['msn'],
        //     'regDate' => $users['reg_time'] + 28800,
        //     'userPayPassword' => '',
        //     'userSafeQuestion' => '',
        //     'userSafeAnswer' => '',
        //     'idcardNum' => '',
        //     'amount' => 0
        //     );
        // extract($data);
        
        // $data = $data = $this->ynp->userDataSync(
        //     $userId,$userPhone,$userEmail,$userLoginPassword,$realName,
        //     $regDate,$userPayPassword,$userSafeQuestion,$userSafeAnswer,
        //     $idcardNum,$amount);
        // $sms = array( 1=> '绑定成功', 2=> '用户已绑定', 3 => '手机号已存在', 4 => '邮箱已绑定');
        // $message  = isset($sms[$data->value]) ? $sms[$data->value] : '绑定异常';
        // echo "<script>alert('{$message}');location.href='/member/'</script>";exit;
        

    }
    /**
     * 检测手机号
     * @return [type] [description]
     */
    public function checkAction() 
    {
        $mobile = $this->request->getPost('mobile');
        $msg = array();
        $member = new Member();
        
        if ($mobile) 
        {
            $member = $member->checkMember($mobile);
            
            if (!$member) 
            {
                $msg['ok'] = '';
            }
            else
            {
                $msg['error'] = '手机号码不存在!';
            }
        }
        else
        {
            $msg['error'] = '手机号码错误!';
        }
        echo json_encode($msg);
        exit;
    }
    /**
     * 发送验证码
     * @return [type] [description]
     */
    public function getcodeAction() 
    {
        $mobile = L\Validator::replace_specialChar($this->request->get('mobile', 'string', ''));
        $msg = array();
        
        if ($mobile && $this->validatemobile($mobile)) 
        {
            $code = auth::random(6, 1);
            $sms = new sms();
            $str = "您正在进行绑定云农宝操作-丰收汇，您的短信验证码为： %s 如非本人操作，请直接忽略";
            $str = $sms->send($mobile, $code, $str);
            
            if ($str == 0) 
            {
                $this->session->vcode = $code;
                $this->session->mobile = $mobile;
                $msg['ok'] = '1';
            }
        }
        else
        {
            $msg['ok'] = '0';
            $msg['error'] = '手机号码错误';
        }
        echo json_encode($msg);
        exit;
    }
    /**
     * 检测验证码
     * @return [type] [description]
     */
    public function checkcodeAction() 
    {
        $msg = array(
            'code' => $this->session->vcode
        );
        $vcode = L\Validator::replace_specialChar($this->request->get('msg_yz', 'string', ''));
        
        if ($this->session->vcode == $vcode) 
        {
            $msg['ok'] = 1;
        }
        else
        {
            $msg['error'] = '验证码错误';
        }
        die(json_encode($msg));
    }
    
    public function validateMobile($mobile) 
    {
        $regex = '/^1[3-9]\d{9}$/';
        return preg_match($regex, $mobile);
    }
    

    
}
