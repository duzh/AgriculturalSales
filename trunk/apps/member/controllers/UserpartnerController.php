<?php
namespace Mdg\Member\Controllers;
use Lib\Member as Member;
use Lib\Validator;
use Mdg\Models\Users as Users;
use Mdg\Models\AreasFull as mAreas;
use Mdg\Models as M;
use Lib as L;
/**
 * 商友控制器
 */

class UserpartnerController extends ControllerMember
{
    /**
     * 我的商友
     * @return [type] [description]
     */

    public function indexAction()
    {
        $page        = $this->request->get('p' , 'int',  1);
        $page        = intval($page)>0 ? intval($page) : 1;
        $psize       = $this->request->get('psize', 'int', 10);
        $moblie      = trim($this->request->get('moblie'));
        $s_pay_type    = $this->request->get('s_pay_type','string','all');

        $con[] = " status=0 AND user_id =".$this->getUserID();
        if($moblie) {
            $con[] = " partner_phone LIKE '%{$moblie}%'";
        }
        if(isset($s_pay_type) && $s_pay_type != 'all') {
            $con[] = " pay_type = '{$s_pay_type}' ";
        }
        $con = implode( ' AND ', $con);

        $data = M\UserPartner::getUserPartnerList($con , $page , $psize);

        $this->view->data = $data;
        $this->view->moblie = $moblie;
        $this->view->s_pay_type = $s_pay_type;
        $this->view->_banks  = $_banks = M\Bank::getBankList();
        $this->view->p                   = $page;
        $this->view->_pay_type = M\UserPartner::$_pay_type;
        $this->view->title = '个人中心-委托交易-我的商友';
    }
    /**
     * 增加商友
     * @return [type] [description]
     */

    public function editAction()
    {
        $this->request->get('');
        $UserParrner = M\UserPartner();
        $this->view->title = '个人中心-委托交易-我的商友';
    }
    /**
    * 根据手机号验证商友是否存在
    * @return [type] [json]
    */
    public function checkPartnerAction(){
        $moblie = $this->request->get('moblie');
        $user_id = $this->getUserID();
        $reslut = M\UserPartner::findFirst('user_id ='.$user_id . ' and partner_phone="' . $moblie . '" and status=0 and partner_user_id !=""');
        if ($reslut) {
           $reslut = $reslut->toArray();
        }else{
            $reslut = array('id' =>0 );
        }
        die(json_encode($reslut));
    }
    /**
     * 验证用户是否存在
     * @return [type] [description]
     */

    public function checkuserAction()
    {
        $moblie = $this->request->get('moblie');
        $member = new Member();
        $member->checkMember($moblie);
        #$userarea = $member->checkMember($moblie);
        $userarea = $member->checkMember($moblie);
        $res = ($userarea)?1:2;
        echo $res;exit;
    }
    /**
     * 新增更新数据
     * @return [type] [description]
     */

    public function savedatasAction()
    {
        $savedata['user_id'] = $this->getUserID();
        #$id = $this->request->getPost('partner_id');
        $username = $this->request->getPost('partner_phone');
        $thisUserName = $this->getUserName();
        if($username == $thisUserName) {
            echo '不能添加自己';exit;
        }
        // 校验手机号
        if(!$username || !Validator::validate_is_mobile($username)) {
            echo '手机号码格式有误';exit;
        }
        $savedata['partner_name'] = $this->request->getPost('partner_name');
        if(!$savedata['partner_name']){
            echo '卖家名称不能为空';exit;
        }
        $savedata['pay_type'] = $this->request->getPost('pay_type');
        $savedata['bank_name'] = $this->request->getPost('bank_name');
        $savedata['bank_account'] = $this->request->getPost('bank_account');
        if(!$savedata['bank_account']){
            echo '开户名不能为空';exit;
        }
        $savedata['bank_card'] = $this->request->getPost('bank_card');
        if(!$savedata['bank_card']){
            echo '银行卡号不能为空';exit;
        }
        $savedata['bank_address'] = $this->request->getPost('bank_address');
        if(!$savedata['bank_address']){
            echo '银行所在地不能为空';exit;
        }
        $user_id = Users::selectByusername($username);

        $userPartner = M\UserPartner::findFirst("partner_phone ='{$username}' and partner_user_id='{$user_id}' ");
       
        if($userPartner){
            $savedata['id'] = $userPartner->id;
            $savedata['partner_phone'] = $userPartner->partner_phone;
            $savedata['partner_user_id'] = $userPartner->partner_user_id;
        }
        else{
            $member = new Member();
            $savedata['partner_phone'] = $username;
            $memberdata = $member->getMember($username);
            if($memberdata){
                $savedata['partner_user_id'] = $memberdata['user_id'];
            }
            else{
                $password = '123456';
                $synuser = $member->register($username,$password);
                $savedata['partner_user_id'] = $synuser['user_id'];
            }

        }
       
        $res = M\UserPartner::insertdata($savedata);
        echo $res;exit;
    }
    /**
     * 修改事获取数据
     * @return [type] [description]
     */

    public function getdataAction()
    {
        $userpartner_id = $this->request->get('id');
        $userarea = M\UserPartner::findFirst('id ='.$userpartner_id)->toArray();
        echo json_encode($userarea);exit;
    }
    /**
     * 删除数据
     * @return [type] [description]
     */

    public function delAction()
    {
        $userpartner_id = $this->request->get('id');
        $userarea = M\UserPartner::findFirst('id ='.$userpartner_id);
        if($userarea){
            $userarea->status= 1;
            $userarea->save();
            echo 1;
        }else{
            echo 2;
        }
        exit;
    }
}
?>