<?php
namespace Mdg\Manage\Controllers;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Mdg\Models as M;
use Lib as L;
use Lib\Func as Func;

class CorporateController extends ControllerMember
{
    /* Api url 地址*/

    public $ApiUrl = 'http://shopsdev.ync365.com/mdg/api/farm';
    /**
     * HC列表
     * @return [type] [description]
     */

    public function hcAction()
    {
        // $where        = array("member_type&2=2");
        $cond[] = " credit_type = '2'";
        $where = array();
        $credit_type = " credit_type = 2";
        $arr = array();
        $page_size = 10;
        $arrinfo['status'] = 99;
        $arr['user_id'] = $this->request->get('user_id', 'int', 0);
        $arr['ext_name'] = $this->request->get('ext_name', 'string', '');
        $arr['start_pid'] = $this->request->get('start_pid', 'int', 0);
        $arr['start_cid'] = $this->request->get('start_cid', 'int', 0);
        $arr['start_did'] = $this->request->get('start_did', 'int', 0);
        $arr['status'] = $this->request->get('status', 'int', 0);
        $arr['type'] = $this->request->get('type', 'int', 0);
        $arr['expire_time'] = $this->request->get('expire_time', 'string', '');
        $arr['expire_etime'] = $this->request->get('expire_etime', 'string', '');
        $arr['credit_id'] = $this->request->get('credit_id', 'int', 0);
        $arr['username'] = $this->request->get('username', 'string', '');
        $arr['start_pida'] = $this->request->get('start_pida', 'int', 0);
        $arr['start_cida'] = $this->request->get('start_cida', 'int', 0);
        $arr['start_dida'] = $this->request->get('start_dida', 'int', 0);
        $p = $this->request->get('p', 'int', '1');
        $page = $this->request->get('page', 'int', '1');
        $start_areas = '';
        $start_areasa = '';

        if ($arr['start_pid'])
        {
            $start_pname = M\AreasFull::getAreasNametoid($arr['start_pid']);
            $start_areas[] = "'" . $start_pname . "'";
        }

        if ($arr['start_cid'])
        {
            $start_cname = M\AreasFull::getAreasNametoid($arr['start_cid']);
            $start_areas[] = "'" . $start_cname . "'";
        }

        if ($arr['start_did'])
        {
            $start_dname = M\AreasFull::getAreasNametoid($arr['start_did']);
            $start_areas[] = "'" . $start_dname . "'";
        }

        if ($start_areas)
        {
            $start_areas = implode(",", $start_areas);
        }
        else
        {
            $start_areas = '';
        }

        if ($arr['start_pida'])
        {
            $start_pname = M\AreasFull::getAreasNametoid($arr['start_pida']);
            $start_areasa[] = "'" . $start_pname . "'";
        }

        if ($arr['start_cida'])
        {
            $start_cname = M\AreasFull::getAreasNametoid($arr['start_cida']);
            $start_areasa[] = "'" . $start_cname . "'";
        }

        if ($arr['start_dida'])
        {
            $start_dname = M\AreasFull::getAreasNametoid($arr['start_dida']);
            $start_areasa[] = "'" . $start_dname . "'";
        }

        if ($start_areasa)
        {
            $start_areasa = implode(",", $start_areasa);
        }
        else
        {
            $start_areasa = '';
        }
        $where = M\Users::getinfowhere($arr, $where, 2);
        $users = M\Users::getCorporateUsers($where, $p, $page_size, $credit_type);
        $whereinfo = M\Users::getinfowhere($arrinfo, '', 2);
        $usersinfo = M\Users::getCorporateUsers($whereinfo, $page, $page_size, $credit_type);
        $this->view->start_areas = $start_areas ? $start_areas : '';
        $this->view->start_areasa = $start_areasa ? $start_areasa : '';
        $this->view->users = $users;
        L\Arrays::sortByCol($usersinfo, 'add_time', SORT_DESC);
        $this->view->userinfo = $usersinfo;
    }
    /**
     * MS列表
     * @return [type] [description]
     */

    public function msAction()
    {
        $where = array();
        $credit_type = " credit_type = 32";
        $arr = array();
        $page_size = 10;
        $arr['user_id'] = $this->request->get('user_id', 'int', 0);
        $arr['ext_name'] = $this->request->get('ext_name', 'string', '');
        $arr['status'] = $this->request->get('status', 'int', 0);
        $arr['type'] = $this->request->get('type', 'int', 0);
        $arr['expire_time'] = $this->request->get('expire_time', 'string', '');
        $arr['expire_etime'] = $this->request->get('expire_etime', 'string', '');
        $arr['credit_id'] = $this->request->get('credit_id', 'int', 0);
        $arr['username'] = $this->request->get('username', 'string', '');
        $p = $this->request->get('p', 'int', '1');
        $page = $this->request->get('page', 'int', '1');
        $where = M\Users::getinfowhere($arr, $where, 32);     
        $users = M\Users::getCorporateUsers($where, $p, $page_size, $credit_type);
        $this->view->users = $users;

    }
    /**
     * PE列表
     * @return [type] [description]
     */

    public function peAction()
    {
        // $where = array(
        //     "member_type&16=16"
        // );
        $credit_type = " credit_type = 16";
        $where = array();
        $arr = array();
        $start_areas = '';
        $start_areasa = '';
        $page_size = 10;
        $arrinfo['status'] = 99;
        $arr['user_id'] = $this->request->get('user_id', 'int', 0);
        $arr['ext_name'] = $this->request->get('ext_name', 'string', '');
        $arr['start_pid'] = $this->request->get('start_pid', 'int', 0);
        $arr['start_cid'] = $this->request->get('start_cid', 'int', 0);
        $arr['start_did'] = $this->request->get('start_did', 'int', 0);
        $arr['status'] = $this->request->get('status', 'int', 0);
        $arr['type'] = $this->request->get('type', 'int', 0);
        $arr['expire_time'] = $this->request->get('expire_time', 'string', '');
        $arr['expire_etime'] = $this->request->get('expire_etime', 'string', '');
        $arr['credit_id'] = $this->request->get('credit_id', 'int', 0);
        $arr['username'] = $this->request->get('username', 'string', '');
        $arr['category_namea'] = $this->request->get('category_namea', 'int', 0);
        $arr['category_nameb'] = $this->request->get('category_nameb', 'int', 0);

        if ($arr['start_pid'])
        {
            $start_pname = M\AreasFull::getAreasNametoid($arr['start_pid']);
            $start_areas[] = "'" . $start_pname . "'";
        }

        if ($arr['start_cid'])
        {
            $start_cname = M\AreasFull::getAreasNametoid($arr['start_cid']);
            $start_areas[] = "'" . $start_cname . "'";
        }

        if ($arr['start_did'])
        {
            $start_dname = M\AreasFull::getAreasNametoid($arr['start_did']);
            $start_areas[] = "'" . $start_dname . "'";
        }

        if ($start_areas)
        {
            $start_areas = implode(",", $start_areas);
        }
        else
        {
            $start_areas = '';
        }

        if ($arr['category_namea'])
        {
            $categorya = M\Category::selectBytocateName($arr['category_namea']);
            $start_areasa[] = "'" . $categorya . "'";
        }

        if ($arr['category_nameb'])
        {
            $categoryb = M\Category::selectBytocateName($arr['category_nameb']);
            $start_areasa[] = "'" . $categoryb . "'";
        }

        if ($start_areasa)
        {
            $start_areasa = implode(",", $start_areasa);
        }
        else
        {
            $start_areasa = '';
        }
        $where = M\Users::getinfowhere($arr, $where, 16);
        $p = $this->request->get('p', 'int', '1');
        $page = $this->request->get('page', 'int', '1');
        $users = M\Users::getCorporateUsers($where, $p, $page_size, $credit_type);
        $whereinfo = M\Users::getinfowhere($arrinfo, '', 16);
        $usersinfo = M\Users::getCorporateUsers($whereinfo, $page, $page_size, $credit_type);
        $this->view->start_areas = $start_areas ? $start_areas : '';
        $this->view->start_areasa = $start_areasa ? $start_areasa : '';
        $this->view->users = $users;
        $this->view->userinfo = $usersinfo;
    }
    /**
     * IF列表
     * @return [type] [description]
     */

    public function ifAction()
    {

        // $where                  = array("member_type&8=8");
        $credit_type = " credit_type = 8";
        $arr = array();
        $where = '';
        $page_size = 10;
        $arrinfo['status'] = 99;
        $start_areasa = '';
        $start_areas = '';
        $arr['user_id'] = $this->request->get('user_id', 'int', 0);
        $arr['ext_name'] = $this->request->get('ext_name', 'string', '');
        $arr['start_pid'] = $this->request->get('start_pid', 'int', 0);
        $arr['start_cid'] = $this->request->get('start_cid', 'int', 0);
        $arr['start_did'] = $this->request->get('start_did', 'int', 0);
        $arr['start_eid'] = $this->request->get('start_eid', 'int', 0);
        $arr['status'] = $this->request->get('status', 'int', 0);
        $arr['type'] = $this->request->get('type', 'int', 0);
        $arr['expire_time'] = $this->request->get('expire_time', 'string', '');
        $arr['expire_etime'] = $this->request->get('expire_etime', 'string', '');
        $arr['credit_id'] = $this->request->get('credit_id', 'int', 0);
        $arr['username'] = $this->request->get('username', 'string', '');
        $arr['userfarm'] = $this->request->get('userfarm', 'string', '');
        $arr['userareaa'] = $this->request->get('userareaa', 'int', 0);
        $arr['userareab'] = $this->request->get('userareab', 'int', 0);
        $arr['lwtt_phone'] = $this->request->get('lwtt_phone','string','');
        if ($arr['start_pid'])
        {
            $start_pname = M\AreasFull::getAreasNametoid($arr['start_pid']);
            $start_areas[] = "'" . $start_pname . "'";
        }

        if ($arr['start_cid'])
        {
            $start_cname = M\AreasFull::getAreasNametoid($arr['start_cid']);
            $start_areas[] = "'" . $start_cname . "'";
        }

        if ($arr['start_did'])
        {
            $start_dname = M\AreasFull::getAreasNametoid($arr['start_did']);
            $start_areas[] = "'" . $start_dname . "'";
        }

        if ($arr['start_eid'])
        {
            $start_dname = M\AreasFull::getAreasNametoid($arr['start_eid']);
            $start_areas[] = "'" . $start_dname . "'";
        }

        if ($start_areas)
        {
            $start_areas = implode(",", $start_areas);
        }
        else
        {
            $start_areas = '';
        }

        if (!empty($arr['category_namea']))
        {
            $categorya = M\Category::selectBytocateName($arr['category_namea']);
            $start_areasa[] = "'" . $categorya . "'";
        }

        if (!empty($arr['category_nameb']))
        {
            $categoryb = M\Category::selectBytocateName($arr['category_nameb']);
            $start_areasa[] = "'" . $categoryb . "'";
        }

        if ($start_areasa)
        {
            $start_areasa = implode(",", $start_areasa);
        }
        else
        {
            $start_areasa = '';
        }
        $p = $this->request->get('p', 'int', '1');
        $page = $this->request->get('page', 'int', '1');

        $where = M\Users::getinfowhere($arr, $where, 8);

        $users = M\Users::getCorporateUsers($where, $p, $page_size, $credit_type);

        //$users                    = L\Arrays::sortByMultiCols($data, array('shop_price' => SORT_ASC));
        $whereinfo = M\Users::getinfowhere($arrinfo, '', 8);
        $usersinfo = M\Users::getCorporateUsers($whereinfo, $page, $page_size, $credit_type);
        $this->view->start_areas = $start_areas ? $start_areas : '';
        $this->view->start_areasa = $start_areasa ? $start_areasa : '';
        $this->view->users = $users;
        L\Arrays::sortByCol($usersinfo, 'add_time', SORT_DESC);
        $this->view->userinfo = $usersinfo;
    }
    /**
     * VS列表
     * @return [type] [description]
     */

    public function vsAction()
    {
        $where = array(
            0
        );
        $credit_type = " credit_type = 4";
        $arr = array();
        $page_size = 10;
        $arrinfo['status'] = 99;
        $start_areas = '';
        $start_areasa = '';
        $arr['user_id'] = $this->request->get('user_id', 'int', 0);
        $arr['ext_name'] = $this->request->get('ext_name', 'string', '');
        $arr['start_pid'] = $this->request->get('start_pid', 'int', 0);
        $arr['start_cid'] = $this->request->get('start_cid', 'int', 0);
        $arr['start_did'] = $this->request->get('start_did', 'int', 0);
        $arr['status'] = $this->request->get('status', 'int', 0);
        $arr['type'] = $this->request->get('type', 'int', 0);
        $arr['expire_time'] = $this->request->get('expire_time', 'string', '');
        $arr['expire_etime'] = $this->request->get('expire_etime', 'string', '');
        $arr['credit_id'] = $this->request->get('credit_id', 'int', 0);
        $arr['username'] = $this->request->get('username', 'string', '');
        $arr['start_pida'] = $this->request->get('start_pida', 'int', 0);
        $arr['start_cida'] = $this->request->get('start_cida', 'int', 0);
        $arr['start_dida'] = $this->request->get('start_dida', 'int', 0);

        if ($arr['start_pid'])
        {
            $start_pname = M\AreasFull::getAreasNametoid($arr['start_pid']);
            $start_areas[] = "'" . $start_pname . "'";
        }

        if ($arr['start_cid'])
        {
            $start_cname = M\AreasFull::getAreasNametoid($arr['start_cid']);
            $start_areas[] = "'" . $start_cname . "'";
        }

        if ($arr['start_did'])
        {
            $start_dname = M\AreasFull::getAreasNametoid($arr['start_did']);
            $start_areas[] = "'" . $start_dname . "'";
        }

        if ($start_areas)
        {
            $start_areas = implode(",", $start_areas);
        }
        else
        {
            $start_areas = '';
        }

        if ($arr['start_pida'])
        {
            $start_pname = M\AreasFull::getAreasNametoid($arr['start_pida']);
            $start_areasa[] = "'" . $start_pname . "'";
        }

        if ($arr['start_cida'])
        {
            $start_cname = M\AreasFull::getAreasNametoid($arr['start_cida']);
            $start_areasa[] = "'" . $start_cname . "'";
        }

        if ($arr['start_dida'])
        {
            $start_dname = M\AreasFull::getAreasNametoid($arr['start_dida']);
            $start_areasa[] = "'" . $start_dname . "'";
        }

        if ($start_areasa)
        {
            $start_areasa = implode(",", $start_areasa);
        }
        else
        {
            $start_areasa = '';
        }
        $where = M\Users::getinfowhere($arr, $where, 4);
        $p = $this->request->get('p', 'int', '1');
        $page = $this->request->get('page', 'int', '1');
        $users = M\Users::getCorporateUsers($where, $p, $page_size, $credit_type);
        $whereinfo = M\Users::getinfowhere($arrinfo, '', 4);
        $usersinfo = M\Users::getCorporateUsers($whereinfo, $page, $page_size, $credit_type);
        $this->view->start_areas = $start_areas ? $start_areas : '';
        $this->view->start_areasa = $start_areasa ? $start_areasa : '';
        $this->view->users = $users;
        $this->view->userinfo = $usersinfo;
    }
    /**
     * HC企业详情
     * @param  integer $id [description]
     * @return [type]      [description]
     */

    public function hcenterpriseinfoAction($id = 0)
    {

        if (!$id)
        {
            parent::msg('访问方式不正确，请重新访问','/manage/corporate/hc');
        }
        $user = M\Users::findFirst(" id = {$id} and member_type&2=2");

        if (!$user)
        {
            parent::msg('访问方式不正确，请重新访问','/manage/corporate/hc');
        }
        // $userarea = M\UserArea::findFirstByuser_id($id);
        $userinfo = M\UserInfo::findFirst("user_id = {$id} and credit_type = 2");

        if ($userinfo)
        {
            $userbank = M\UserBank::findFirstBycredit_id($userinfo->credit_id);
            $userext = M\UserExt::findFirstBycredit_id($userinfo->credit_id);
            $userbuy = M\UserBuy::findFirstBycredit_id($userinfo->credit_id);
        }

        if ($shop)
        {
            $shopcredit = M\ShopCredit::findFirstByshop_id($shop->shop_id);
        }
        else
        {
            $shopcredit = '';
        }
    }
    /**
     * HC个人详情
     * @param  integer $id [description]
     * @return [type]      [description]
     */

    public function hcpersonalinfoAction($id = 0, $type, $type_name, $credit_id = 0)
    {
        $userfarmcrops = array();
        $user = array();
        $userinfo = array();
        $userbank = array();
        $userbuy = array();
        $usercontact = array();
        $userarea = array();
        $privilege_taginfo = '';
        $user_farm_picture = '';
        $userfarm = '';
        $se = '';
        $category_name_id = '';

        if (!$id)
        {
            parent::msg('访问方式不正确，请重新访问',"/manage/corporate/{$type_name}");

        }
        $userinfo = M\UserInfo::findFirst("user_id = {$id} and credit_type = {$type} and credit_id = {$credit_id}");

        if ($userinfo)
        {
            $se_api = L\Func::serviceApi();
            $se = $se_api->county_selectByid($userinfo->se_id);
            $userarea = M\UserArea::findFirstBycredit_id($userinfo->credit_id);
            $userbank = M\UserBank::findFirstBycredit_id($userinfo->credit_id);
            // $userext  = M\UsersExt::findFirstBycredit_id($userinfo->credit_id);
            $privilege_taginfo = M\UserInfo::getprivilege_taginfo($id, $userinfo->credit_id);
            $userbuy = M\UserBuy::findFirstBycredit_id($userinfo->credit_id);
            $usercontact = M\UserContact::findFirstBycredit_id($userinfo->credit_id);
            $userfarm = M\UserFarm::findFirstBycredit_id($userinfo->credit_id);
            $userfarmcrops = M\UserFarmCrops::find("credit_id = {$userinfo->credit_id}")->toArray();
            $user_farm_picture = M\UserFarmPicture::find("credit_id = {$userinfo->credit_id} AND type=0")->toArray();
            $user_farm_picture_contact = M\UserFarmPicture::find("credit_id = {$userinfo->credit_id} AND type=1")->toArray();

            if ($userfarmcrops)
            {
                $category_name = array_Column($userfarmcrops, 'category_id');
                $category_name_id = join(",", $category_name);
            }
            else
            {
                $category_name_id = 0;
            }
        }
        $this->view->user_farm_picture_contact = $user_farm_picture_contact;
        $this->view->privilege_taginfo = $privilege_taginfo;
        $this->view->user_farm_picture = $user_farm_picture;
        $this->view->userfarm = $userfarm;
        $this->view->se = $se ? $se : '';
        $this->view->category_name_id = $category_name_id;
        $this->view->cateList = M\Category::getTopCaetList();
        $this->view->getid = $this->session->getId();
        $this->view->userfarmcrops = $userfarmcrops;
        $this->view->user = $user;
        $this->view->userinfo = $userinfo;
        $this->view->userbank = $userbank;
        // $this->view->userext = $userext;
        $this->view->userbuy = $userbuy;
        $this->view->usercontact = $usercontact;
        $this->view->userarea = $userarea;
    }
    /**
     * 盟商个人详情
     * @param  integer $id [description]
     * @return [type]      [description]
     */

    public function msinfoAction($id = 0, $type, $type_name, $credit_id = 0)
    {
        // var_dump($credit_id);die;
        if (!$id)
        {
            parent::msg('访问方式不正确，请重新访问',"/manage/corporate/{$type_name}");

        }
        $userinfo = M\UserInfo::findFirst("user_id = {$id} and credit_type = {$type} and credit_id = {$credit_id}");
        if($userinfo){
            //工程师信息
            $se_id  = $userinfo->se_id;
            $se_no  = $userinfo->se_no;
            $seinfo = M\EngineerManager::findFirst("engineer_id = {$se_id} and engineer_no = {$se_no}");
            //身份证照片信息
            $idcard_info = M\UserBank::findFirstBycredit_id($userinfo->credit_id);
            //已整合的可信农场
            
            // var_dump($idcard_info->toArray());die;
        }
        $this->view->seinfo      = $seinfo;
        $this->view->idcard_info = $idcard_info;
        $this->view->userinfo    = $userinfo;
        

    }
    /**
     * HC审核
     * @return [type] [description]
     */

    public function hcupdateAction()
    {

        $usercheck = $this->request->get('usercheck', 'int', 0);
        $procurementcheck = $this->request->getPost('procurementcheck', 'int', 0);
        $supplycheck = $this->request->get('supplycheck', 'int', 0);
        $ordercheck = $this->request->get('ordercheck', 'int', 0);
        $strinfo = 0;
        $UserFarm='';
        if (!empty($procurementcheck))
        {
            $strinfo = array_sum($procurementcheck);
        }
        $name = $this->request->getPost('name', 'string', '#');
        $hidden_userinfo_id = $this->request->getPost('hidden_userinfo_id', 'int', 0);
        $type_credit = $this->request->getPost('type_credit', 'string', '');
        $type_id_arr = 0;
        $userinfo = M\UserInfo::findFirst("credit_id = {$hidden_userinfo_id}");
        // $user = M\Users::findFirstByid($userinfo->user_id);

        if ($name == '#' || $name == '' || !$hidden_userinfo_id || !$userinfo)
        {
            parent::msg('修改失败','/manage/corporate/hc');
        }

        if ($type_credit == 'hc')
        {
            $type_id_arr = 2;
        }

        if ($type_credit == 'vs')
        {
            $type_id_arr = 4;
        }

        if ($type_credit == 'if')
        {
            $type_id_arr = 8;
        }

        if ($type_credit == 'pe')
        {
            $type_id_arr = 16;
        }
        $userext = M\Users::findFirst(" id = '{$userinfo->user_id}'");

        if (!$userext)
        {
            parent::msg('该用户不存在',"/manage/corporate/{$type_credit}");

        }
        
        if ($name == '审核通过')
        {
            $this->db->begin();
            //保存银行卡信息
            $userbank = M\UserBank::findFirstBycredit_id($userinfo->credit_id);
            $count = M\UserBankCard::count("user_id={$userinfo->user_id} and status=0 and is_default=1");
            //是否存在自增相同卡号
            $userbankcard = M\UserBankCard::findFirst("user_id={$userinfo->user_id} AND bank_cardno = '{$userbank->bank_cardno}' ");
            if($userbankcard) {
                $userbankcard->delete();
                $userbankcard = new M\UserBankCard();
            }else{
                $userbankcard = new M\UserBankCard();
            }

            $userbankcard->is_default = ($userbankcard && $userbankcard->is_default == 1) ? 1 : ($count > 0 ? 0 : 1);
            $userbankcard->source_type = $userinfo->credit_type;
            $userbankcard->user_id = $userinfo->user_id;
            $userbankcard->bank_name = $userbank->bank_name;
            $userbankcard->bank_address = $userbank->bank_address;
            $userbankcard->bank_account = $userbank->bank_account;
            $userbankcard->bank_cardno = $userbank->bank_cardno;
            $userbankcard->add_time = CURTIME;
            $userbankcard->last_update_time = CURTIME;
            $userbankcard->credit_id = $userinfo->credit_id;
            $userbankcard->status = 0;
            $userbankcard->save();
            if(!$count = M\UserBankCard::count("user_id={$userinfo->user_id}  AND  is_default=1")) {
                $userbankcard->is_default = 1 ;
            }
            $userbankcard->save();

            if ($type_credit == 'if')
            {
                try
                {

                    /* 获取用户农场信息 */
                    $UserFarm = M\UserFarm::findFirst(" user_id = '{$userinfo->user_id}' AND credit_id = '{$userinfo->credit_id}'");

                    if (!$UserFarm)
                    {
                        throw new Exception("农场信息错误", 1);
                    }
                    /* 检测用户是否已发放补贴 */
                    if(!M\Subsidy::findFirst(" user_id = '{$userinfo->user_id}' AND subsidy_type = '1'")){

                        /* 发放补贴 */
                        $SubsidySend = new L\SubsidySend($userinfo->user_id);
                        /* 获取用户附加信息 */
                        if (!$userext)
                        {
                            throw new Exception("用户信息错误", 1);
                        }
                        $flag = $SubsidySend->sendByFarm($userext->ext->name, $userext->username, L\Subsidy::subByFarm($UserFarm->farm_area));

                        if (!$flag)
                        {
                            throw new Exception("补贴发放失败", 1);
                        }
                        #可信农场审核通过后发送短信 限定9月15日 23：59：59之前 start
                        $endtime= strtotime('2015-09-15 23:59:59');
                        $smspro = array(1486,1659); #山东 河南两省
                        if($UserFarm){

                            if($userinfo->last_update_time <$endtime && in_array($UserFarm->province_id,$smspro)){

                                $phone=M\Users::getUserMobile($userinfo->user_id);

                                $sms = new L\SMS();
                                $content = '【您的专属奖励】丰收汇限量发放免费“高温险”名额，恭喜您获得10元保单，将有机会获赔50元赔偿额。请登录丰收汇网站查看详情。活动资讯：400-8811-365';
                                // $send=$sms->send($phone,$content);
                                //通知第三方平台
                                $city=str_replace("市",'',$UserFarm->city_name);

                                $gift=L\Gift::gift($userinfo->user_id,$userext->username,$userext->ext->name,$userinfo->last_update_time,$city);

                                //记录已经发放的专属奖励
                                $time=date("Y-m-d H:i:s",time());
                                $str="发送手机号:{$phone},可信农场id{$userinfo->credit_id},时间:{$time}";
                                file_put_contents(PUBLIC_PATH.'/log/kx.log',$str."\n", FILE_APPEND);
                            }
                        }
                        #end
                    }
                    $userinfo->privilege_tag = $strinfo;
                    $userinfo->status = 1;
                    $userinfo->save();
                    $userext->credit_id = $userinfo->credit_id;
                    $userext->member_type = intval($userext->member_type+$type_id_arr);

                    $userext->save();
                    $crediblefarminfo = M\CredibleFarmInfo::findFirstByuser_id($userinfo->user_id);

                    if (!$crediblefarminfo)
                    {
                        $crediblefarminfo = new M\CredibleFarmInfo();
                        $crediblefarminfo->user_id = $userinfo->user_id;
                        $crediblefarminfo->status = 0;
                        $crediblefarminfo->save();
                        $crediblefarminfo = M\CredibleFarmInfo::findFirstByuser_id($userinfo->user_id);
                        $crediblefarminfo->url = 100000 + $crediblefarminfo->id;
                        $crediblefarminfo->save();
                    }
                    //增加审核通过日志 --by duzh  start
                    $userlog = M\UserLog::findFirst("user_id={$userinfo->user_id} AND credit_id = {$userinfo->credit_id} AND status={$userinfo->status}");
                    $time=date("Y-m-d H:i:s",CURTIME);
                    if (!$userlog) $userlog = new M\UserLog();
                    $userlog->user_id = $userinfo->user_id;
                    $userlog->credit_id = $userinfo->credit_id;
                    $userlog->operate_user_no = $userinfo->credit_no;
                    $userlog->operate_user_name = $userinfo->name;
                    // $userlog->operate_time = $userinfo->apply_time;
                    $userlog->operate_time = CURTIME;
                    $userlog->status = $userinfo->status;
                    $userlog->demo = "申请审核通过";
                    $userlog->add_time = CURTIME;
                    $userlog->save();
                    //end
                    $this->db->commit();

                    //如果该可信农场有审核  并且审核通过5个  发短信
                    if($userinfo->se_id&&$userinfo->mobile_type==2){
                           $userinfocount=M\UserInfo::count(" credit_type=8 and  se_id ='{$userinfo->se_id}' and mobile_type=2 and  status=1 ");
                           
                           if($userinfocount==1){
                                 $lwtt_phone=M\UserInfo::findFirst("credit_id={$userinfo->se_id} and status=1 ");
                                 if($lwtt_phone){
                                     $sms = new L\SMS();
                                     $msgs = "恭喜，您整合的可信农场已达到1家，您可以添加产地服务商的产品供应信息";
                                     $str = $sms->send($userinfo->se_mobile,$msgs);
                                 }
                                 $user_id  = M\Users::selectByusername($userinfo->se_mobile);
                                 if($user_id){
                                     $redis = new \Lib\PhpRedis('lwttcount_');
                                     $a=$redis->set("lwttcount{$userinfo->se_id}_".$user_id,0,1080000);
                                 }

                           }
                    }
                }
                catch(\Exception $e)
                {
                    $this->db->rollback();
                    $msg=$e->getMessage();
                    
                    parent::msg('审核失败',"/manage/corporate/{$type_credit}");
                }

                
            }
            else
            {

                $userinfo->privilege_tag = $strinfo;
                $userinfo->status = 1;
                $userinfo->save();
                $userext->member_type = $userext->member_type + $type_id_arr;
                $userext->save();
                //增加审核通过日志 --by duzh  start
                $userlog = M\UserLog::findFirst("user_id={$userinfo->user_id} AND credit_id = {$userinfo->credit_id} AND status={$userinfo->status}");
                $time=date("Y-m-d H:i:s",time());
                if (!$userlog) $userlog = new M\UserLog();
                $userlog->user_id = $userinfo->user_id;
                $userlog->credit_id = $userinfo->credit_id;
                $userlog->operate_user_no = $userinfo->credit_no;
                $userlog->operate_user_name = $userinfo->name;
                $userlog->operate_time = CURTIME;
                $userlog->status = $userinfo->status;
                $userlog->demo = "申请审核通过";
                $userlog->add_time = time();
                $userlog->save();
                //end
                $this->db->commit();
            }
        }

        if ($name == '审核不通过')
        {
            $userinfo->privilege_tag = $strinfo;
            $userinfo->status = 2;
            $userinfo->save();
            //拒绝审核日志增加 --duzh start
            $reject = $this->request->getPost('reject', 'string');
            $time = CURTIME;
            $userlog = M\UserLog::findFirst("user_id={$userinfo->user_id} AND credit_id = {$userinfo->credit_id} AND status={$userinfo->status} AND add_time = '{$time}'");

            if (!$userlog) $userlog = new M\UserLog();
            $userlog->user_id = $userinfo->user_id;
            $userlog->credit_id = $userinfo->credit_id;
            $userlog->operate_user_no = $userinfo->credit_no;
            $userlog->operate_user_name = $userinfo->name;
            $userlog->operate_time = CURTIME;
            $userlog->status = $userinfo->status;
            $userlog->demo = "申请审核未通过:".$reject;
            $userlog->add_time = time();
            $userlog->save();
            //end
            /* IF 审核失败通知 */

            if ($type_credit == 'if')
            {
                /* 通知用户更新数据 */
                $curl = new L\Curl();
                $json['data'] = json_encode(array(
                    'user_id' => $userinfo->user_id,
                    'status' => 7
                ));
                $data = $curl->Post($this->ApiUrl, $json);
            }
        }

        if ($name == '取消认证')
        {
            $this->db->begin();
            //用户取消认证，用户银行卡信息删除
            $userbankcard = M\UserBankCard::findFirst("user_id={$userinfo->user_id} AND credit_id={$userinfo->credit_id}");


            if ($userbankcard)
            {
                $userbankcard->delete();
            }

            $crediblefarminfo = M\CredibleFarmInfo::findFirstByuser_id($userinfo->user_id);
            if($crediblefarminfo){
                $crediblefarminfo->status = 0;
                $crediblefarminfo->save();
            }
            $userext->member_type = $userext->member_type - $type_id_arr;
            $userext->save();
            $userinfo->privilege_tag = $strinfo;
            $userinfo->status = 3;

            $userinfo->save();
            //取消认证日志增加 --duzh start
            $reject = $this->request->get('reject', 'string');
            if(mb_strlen($reject,'utf8')>=300){
                parent::msg('内容超长',"/manage/corporate/{$type_credit}");
            }
            $time = CURTIME;
            $userlog = M\UserLog::findFirst("user_id={$userinfo->user_id} AND credit_id = {$userinfo->credit_id} AND status={$userinfo->status} AND add_time = '{$time}'");

            if (!$userlog) $userlog = new M\UserLog();
            $userlog->user_id = $userinfo->user_id;
            $userlog->credit_id = $userinfo->credit_id;
            $userlog->operate_user_no = $userinfo->credit_no;
            $userlog->operate_user_name = $userinfo->name;
            $userlog->operate_time = CURTIME;
            $userlog->status = $userinfo->status;
            $userlog->demo = "取消认证:".$reject;
            $userlog->add_time = time();
            $userlog->save();
            //end
            $this->db->commit();
        }
        
        Func::adminlog("审核{$type_credit}{$name}{$userext->username}",$this->session->adminuser['id']);
        parent::msg($name .'成功',"/manage/corporate/{$type_credit}");
    }

    /**
     *
     * @return [type] [description]
     */

    public function hceditAction($id = 0, $typeid = 0, $type = 0, $credit_id = 0)
    {
        $getid = $this->session->getId();

        if (!$id)
        {
            parent::msg('访问方式不正确，请重新访问',"/manage/corporate/{$type}");
        }
        $userinfo = M\UserInfo::findFirst("user_id = {$id} and credit_type = {$typeid} and credit_id = {$credit_id}");
        $privilege_taginfo = M\UserInfo::getprivilege_taginfo($id, $userinfo->credit_id);
        $bank = M\Bank::find()->toArray();
        $se_api = L\Func::serviceApi();
        $se = $se_api->county_selectByid($userinfo->se_id);
        if ($userinfo)
        {
            $userarea = M\UserArea::findFirstBycredit_id($userinfo->credit_id);

            if ($userarea)
            {
                $userareainfo = "'" . $userarea->province_name . "','" . $userarea->city_name . "','" . $userarea->district_name . "','" . $userarea->town_name . "'";
            }
            $userbank = M\UserBank::findFirstBycredit_id($userinfo->credit_id);
            // $userext  = M\UsersExt::findFirstBycredit_id($userinfo->credit_id);

            if ($userinfo->province_name)
            {
                $infoarea = "'" . $userinfo->province_name . "','" . $userinfo->city_name . "','" . $userinfo->district_name . "','" . $userinfo->town_name . "'";
                $this->view->infoarea = $infoarea;
            }

            if ($userbank && !empty($userbank->bank_address))
            {
                $area = explode(",", $userbank->bank_address);
                $areainfo = "'" . $area['0'] . "','" . $area['1'] . "','" . $area['2'] . "'";
                $this->view->areainfo = $areainfo;
            }
            $userbuy = M\UserBuy::findFirstBycredit_id($userinfo->credit_id);
            $usercontact = M\UserContact::findFirstBycredit_id($userinfo->credit_id);
            $userfarmcrops = M\UserFarmCrops::find("credit_id = {$userinfo->credit_id}")->toArray();
            $userfarm = M\UserFarm::findFirstBycredit_id($userinfo->credit_id);
            $user_farm_picture = M\UserFarmPicture::find("credit_id = {$userinfo->credit_id}")->toArray();

            if ($userfarmcrops)
            {
                $category_name = array_Column($userfarmcrops, 'category_id');
                $category_name_id = join(",", $category_name);
            }
            else
            {
                $category_name_id = 0;
            }
        }
        $this->view->se = $se;
        $this->view->user_farm_picture = $user_farm_picture;
        $this->view->userfarm = $userfarm;
        $this->view->privilege_taginfo = $privilege_taginfo;
        $this->view->category_name_id = $category_name_id;
        $this->view->cateList = M\Category::getTopCaetList();
        $this->view->userareainfo = $userareainfo ? $userareainfo : '';
        $this->view->userfarmcrops = $userfarmcrops;
        $this->view->user = $user;
        $this->view->userinfo = $userinfo;
        $this->view->userbank = $userbank;
        // $this->view->userext = $userext;
        $this->view->userbuy = $userbuy;
        $this->view->usercontact = $usercontact;
        $this->view->userarea = $userarea;
        $this->view->getid = $getid;
        $this->view->bank = $bank;
    }
   /**
     *盟商信息修改
     * @return [type] [description]
     */

    public function mseditAction($id = 0, $type, $type_name, $credit_id = 0)
    {
      // var_dump($credit_id);die;
        if (!$id)
        {
            parent::msg('访问方式不正确，请重新访问',"/manage/corporate/{$type_name}");

        }
        $userinfo = M\UserInfo::findFirst("user_id = {$id} and credit_type = {$type} and credit_id = {$credit_id}");
        if($userinfo){
            //工程师信息
            $se_id  = $userinfo->se_id;
            $se_no  = $userinfo->se_no;
            $seinfo = M\EngineerManager::findFirst("engineer_id = {$se_id} and engineer_no = {$se_no}");
            //身份证照片信息
            $idcard_info = M\UserBank::findFirstBycredit_id($userinfo->credit_id);
            // var_dump($idcard_info->toArray());die;
        }
        $this->view->seinfo      = $seinfo;
        $this->view->idcard_info = $idcard_info;
        $this->view->userinfo    = $userinfo;

    }
    /**
      *盟商信息保存
      *@return boolean
      */
    public function mssaveAction()
    {
        $user_id   = $this->request->getPost('user_id','int',0);
        $info_id   = $this->request->getPost('info_id', 'int', 0);
        $info_type = $this->request->getPost('info_type', 'string', '');
        $phone     = $this->request->getPost('phone','string','');
        if(!$info_id)
        {
            parent::msg('数据不完整保存失败',"/manage/corporate/{$info_type}");
        }
        $info = M\UserInfo::findFirst("credit_id = {$info_id}");
        if($info){
            $info->phone = $phone;
        }
        if($info->save()){
            parent::msg("保存成功！","manage/corporate/{$info_type}");
        }else{
            parent::msg("保存失败！","manage/corporate/{$info_type}");
        }
        
    }



    public function hcsaveAction()
    {
        $user_id = $this->request->getPost('user_id', 'int', 0);
        $info_id = $this->request->getPost('info_id', 'int', 0);
        $infophone = $this->request->getPost('infophone', 'string', '');
        $start_pid = $this->request->getPost('start_pid', 'int', 0);
        $start_cid = $this->request->getPost('start_cid', 'int', 0);
        $start_did = $this->request->getPost('start_did', 'int', 0);
        $start_pida = $this->request->getPost('start_pida', 'int', 0);
        $start_cida = $this->request->getPost('start_cida', 'int', 0);
        $start_dida = $this->request->getPost('start_dida', 'int', 0);
        $start_eida = $this->request->getPost('start_eida', 'int', 0);
        $infoaddress = $this->request->getPost('infoaddress', 'string', '');
        $contactphone = $this->request->getPost('contactphone', 'string', '');
        $contactfax = $this->request->getPost('contactfax', 'string', '');
        $bank_name = $this->request->getPost('bank_name', 'string', '');
        $bank_cardno = $this->request->getPost('bank_cardno', 'string', '');
        $info_type = $this->request->getPost('info_type', 'string', '');
        $userarea_province = $this->request->getPost('userarea_province', 'int', 0);
        $userarea_city = $this->request->getPost('userarea_city', 'int', 0);
        $userarea_district = $this->request->getPost('userarea_district', 'int', 0);
        $userarea_town = $this->request->getPost('userarea_town', 'int', 0);
        $usercheck = $this->request->getPost('usercheck', 'int', 0);
        $procurementcheck = $this->request->getPost('procurementcheck', 'int', 0);
        $supplycheck = $this->request->getPost('supplycheck', 'int', 0);
        $ordercheck = $this->request->getPost('ordercheck', 'int', 0);
        $bank_account = $this->request->getPost('bank_account', 'string', '');
        $contactname = $this->request->getPost('contactname', 'string', '');
        $farm_name = $this->request->getPost('farm_name', 'string', '');
        $userfarm_pid = $this->request->getPost('userfarm_pid', 'int', 0);
        $userfarm_cid = $this->request->getPost('userfarm_cid', 'int', 0);
        $userfarm_did = $this->request->getPost('userfarm_did', 'int', 0);
        $userfarm_tid = $this->request->getPost('userfarm_tid', 'int', 0);
        $userfarm_vid = $this->request->getPost('userfarm_vid', 'int', 0);
        $farm_area = $this->request->getPost('farm_area', 'string', '');
        $category_name = $this->request->getPost('category_name', 'string', '');
        $source = $this->request->getPost('source', 'int', 0);
        $start_year = $this->request->getPost('start_year', 'int', 0);
        $start_month = $this->request->getPost('start_month', 'int', 0);
        $year = $this->request->getPost('year', 'string', '');
        $month = $this->request->getPost('month', 'string', '');
        $user_describe = $this->request->getPost('user_describe', 'string', '');
        $se_mobile=$this->request->getPost('se_mobile', 'string', '');

        if (!$info_id)
        {
            parent::msg('数据不完整保存失败',"/manage/corporate/{$info_type}");
        }
        $info = M\UserInfo::findFirst("credit_id = {$info_id}");

        if ($info->credit_type == 2 )  //县域服务工程师
        {
            if (!$se_mobile || !L\Validator::validate_is_mobile($se_mobile))
            {
                parent::msg('服务工程师手机号不正确',"/manage/corporate/{$info_type}");
            }

            $result = M\EngineerManager::getEngineerInfo($se_mobile, true);
            if (!$result)
            {
                parent::msg('服务工程师不存在',"/manage/corporate/{$info_type}");
            }
            $info->se_id = $result->engineer_id;
            $info->se_no = $result->engineer_no;
        }


        $userarea = M\UserArea::findFirstBycredit_id($info_id);
        $sid = $this->session->getId();

        if ($userarea)
        {
            $userarea->province_id = $userarea_province;
            $userarea->city_id = $userarea_city;
            $userarea->district_id = $userarea_district;
            $userarea->town_id = $userarea_town;
            $userarea->province_name = M\AreasFull::getAreasNametoid($userarea_province);
            $userarea->city_name = M\AreasFull::getAreasNametoid($userarea_city);
            $userarea->district_name = M\AreasFull::getAreasNametoid($userarea_district);
            $userarea->town_name = M\AreasFull::getAreasNametoid($userarea_town);
            $userarea->last_update_time = time();
        }
        else
        {
            $userarea = new M\UserArea();
            $userarea->user_id = $user_id;
            $userarea->province_id = $userarea_province;
            $userarea->city_id = $userarea_city;
            $userarea->district_id = $userarea_district;
            $userarea->town_id = $userarea_town;
            $userarea->province_name = M\AreasFull::getAreasNametoid($userarea_province);
            $userarea->city_name = M\AreasFull::getAreasNametoid($userarea_city);
            $userarea->district_name = M\AreasFull::getAreasNametoid($userarea_district);
            $userarea->town_name = M\AreasFull::getAreasNametoid($userarea_town);
            $userarea->add_time = time();
            $userarea->last_update_time = time();
            $userarea->credit_id = $info_id;
        }
        $userarea->save();
        $category_name_text_0 = $this->request->getPost('category_name_text_0', 'string', '');
        $getid = $this->session->getId();
        $ent_bankcard_picture = M\TmpFile::findFirst("sid = '{$getid}' and type = 29");
        $ent_identity_picture_lic = M\TmpFile::findFirst("sid = '{$getid}' and type = 33");
        $ent_idcard_picture = M\TmpFile::findFirst("sid = '{$getid}' and type = 31");
        $ent_identity_card_back = M\TmpFile::findFirst("sid = '{$getid}' and type=34");

        if ($category_name_text_0)
        {
            $category_name_text_0 = rtrim($category_name_text_0, ",");
            $category_name_text_0 = explode(",", $category_name_text_0);
            $userfarmcrops = M\UserFarmCrops::find("credit_id = {$info_id}");

            if ($userfarmcrops)
            {
                $userfarmcrops->delete();
            }

            foreach ($category_name_text_0 as $key => $value)
            {
                $userfarmcrops = new M\UserFarmCrops();
                $userfarmcrops->user_id = $user_id;
                // $a=M\Category::selectBytocateName($value);
                // print_r($a);die;
                $userfarmcrops->category_name = M\Category::selectBytocateName($value);
                $userfarmcrops->add_time = time();
                $userfarmcrops->category_id = $value;
                $userfarmcrops->credit_id = $info_id;
                $userfarmcrops->save();
            }
        }


        $userext = M\Users::findFirst(" id = '{$info->user_id}'");
        $info->phone = $infophone;
        $info->province_id = $start_pida;
        $info->province_name = M\AreasFull::getAreasNametoid($start_pida);
        $info->city_id = $start_cida;
        $info->city_name = M\AreasFull::getAreasNametoid($start_cida);
        $info->district_id = $start_dida;
        $info->district_name = M\AreasFull::getAreasNametoid($start_dida);
        $info->town_id = $start_eida;
        $info->town_name = M\AreasFull::getAreasNametoid($start_eida);
        $info->address = $infoaddress;
        $info->privilege_tag = $usercheck + $procurementcheck + $supplycheck + $ordercheck;
        $info->se_mobile=$se_mobile;
        //服务工程师
        if($se_mobile){
            $tag = ($info->credit_type == 2) ? true : false;
            $engineerinfo = M\EngineerManager::getEngineerInfo($se_mobile, $tag);
            if ($engineerinfo)
            {
                $info->se_id = $engineerinfo->engineer_id;
                $info->se_no = $engineerinfo->engineer_no;
            }
        }
        $info->save();
        $bank = M\UserBank::findFirst("credit_id = {$info_id}");
        $bank->bank_name = $bank_name;
        $start_pid = M\AreasFull::getAreasNametoid($start_pid);
        $start_cid = M\AreasFull::getAreasNametoid($start_cid);
        $start_did = M\AreasFull::getAreasNametoid($start_did);
        $bank->bank_address = $start_pid . ',' . $start_cid . ',' . $start_did;
        $bank->bank_cardno = $bank_cardno;
        $bank->bank_account = $bank_account;

        if ($ent_bankcard_picture)
        {
            $bank->bankcard_picture = $ent_bankcard_picture->file_path;
        }

        if ($ent_identity_picture_lic)
        {
            $bank->idcard_picture = $ent_identity_picture_lic->file_path;
        }

        if ($ent_identity_card_back)
        {
            $bank->idcard_picture_back = $ent_identity_card_back->file_path;
        }

        if ($ent_idcard_picture)
        {

            if ($info->type == 1)
            {
                $bank->identity_picture = $ent_idcard_picture->file_path;
            }
            else
            {
                $bank->person_picture = $ent_idcard_picture->file_path;
            }
        }
        $bank->save();

        if ($ent_bankcard_picture) $ent_bankcard_picture->delete();

        if ($ent_identity_picture_lic) $ent_identity_picture_lic->delete();

        if ($ent_idcard_picture) $ent_idcard_picture->delete();

        if ($ent_identity_card_back) $ent_identity_card_back->delete();
        $contact = M\UserContact::findFirst("credit_id = {$info_id}");

        if ($contact)
        {
            $contact->phone = $contactphone;
            $contact->fax = $contactfax;
            $contact->name = $contactname;
            $contact->save();
        }

        if ($farm_name)
        {
            $userfarm = M\UserFarm::findFirstBycredit_id($info_id);
            $userfarm->farm_name = $farm_name;
            $userfarm->province_id = $userfarm_pid;
            $userfarm->city_id = $userfarm_cid;
            $userfarm->district_id = $userfarm_did;
            $userfarm->town_id = $userfarm_tid;
            $userfarm->village_id = $userfarm_vid;
            $userfarm->province_name = M\AreasFull::getAreasNametoid($userfarm_pid);
            $userfarm->city_name = M\AreasFull::getAreasNametoid($userfarm_cid);
            $userfarm->district_name = M\AreasFull::getAreasNametoid($userfarm_did);
            $userfarm->town_name = M\AreasFull::getAreasNametoid($userfarm_tid);
            $userfarm->village_name = M\AreasFull::getAreasNametoid($userfarm_vid);
            $userfarm->village_id = $userfarm_vid;
            $userfarm->farm_area = $farm_area;
            $userfarm->source = $source;
            $userfarm->start_year = $start_year;
            $userfarm->start_month = $start_month;
            $userfarm->year = $year;
            $userfarm->month = $month;
            $userfarm->describe = $user_describe;
            $userfarm->last_update_time = time();
            $userfarm->save();

            $userfarmpictures = M\UserFarmPicture::findFirstBycredit_id($info_id);
            $picture_path_arr = M\TmpFile::find(" sid = '{$sid}' AND type = '32' ");
            if($picture_path_arr){
                foreach ($picture_path_arr as $picture_path)
                {
                    // $UserFarmPicture = M\UserFarmPicture::findFirst(" user_id ='{$uid}' and credit_id='{$credit_id}' and type=0 ");
                    // if(!$UserFarmPicture) {
                    $UserFarmPicture = new M\UserFarmPicture();
                    //}
                    $UserFarmPicture->user_id = $info->user_id;
                    $UserFarmPicture->picture_path = $picture_path ? $picture_path->file_path : '';
                    $UserFarmPicture->add_time = CURTIME;
                    $UserFarmPicture->credit_id = $info_id;
                    $UserFarmPicture->type = 0 ;
                    $UserFarmPicture->save();
                }
                $picture_path_arr->delete();
            }

            /* 上传农场合同照片 */
            $picture_path_arr_contact = M\TmpFile::find(array(" sid = '{$sid}' AND type = '42' ", 'limit' => 5, 'order' => 'addtime desc ' ));
            if($picture_path_arr_contact){
                // print_R($picture_path_arr_contact->toArray());
                foreach ($picture_path_arr_contact as $picture_path)
                {
                    // $UserFarmPicture = M\UserFarmPicture::findFirst(" user_id ='{$uid}' and credit_id='{$credit_id}' and type=1 ");
                    // if(!$UserFarmPicture) {
                    $UserFarmPicture = new M\UserFarmPicture();
                    //}
                    $UserFarmPicture->user_id = $info->user_id;
                    $UserFarmPicture->picture_path = $picture_path ? $picture_path->file_path : '';
                    $UserFarmPicture->add_time = CURTIME;
                    $UserFarmPicture->credit_id = $info_id;
                    $UserFarmPicture->type = 1 ;
                    $UserFarmPicture->save();

                }
                $picture_path_arr_contact->delete();
            }
        }

        $name = !empty($userext->username) ? $userext->username : $info->user_id;
        Func::adminlog("修改{$info_type}{$name}",$this->session->adminuser['id']);
        parent::msg('修改成功','/manage/corporate/'.$info_type);
        // echo "<script>alert('修改成功');location.href='/manage/corporate/{$info_type}'</script>";
        // exit;
    }

    public function getAction($id = 0, $type = 0, $info_type = 0, $credit_id = 0)
    {
        $userinfo = M\UserInfo::findFirst("user_id = {$id} and credit_type = {$type} and credit_id = {$credit_id}");
        $userext = M\Users::findFirst(" id = '{$userinfo->user_id}'");

        if (!$userinfo)
        {
            parent::msg('数据不完整请求失败','/manage/corporate/'.$info_type);
            // echo "<script>alert('数据不完整请求失败');location.href='/manage/corporate/{$info_type}'</script>";
            // exit;
        }
        else
        {
            //用户取消认证，用户银行卡信息删除
            $userbankcard = M\UserBankCard::findFirst("user_id={$userinfo->user_id} AND credit_id={$userinfo->credit_id}");
            if ($userbankcard)
            {
                $userbankcard->delete();
            }

            if($userinfo->credit_type == 8 ) {
                $crediblefarminfo = M\CredibleFarmInfo::findFirstByuser_id($userinfo->user_id);
                if (!$crediblefarminfo)
                {
                    parent::msg('认证取消失败','/manage/corporate/'.$info_type);
                    // echo "<script>alert('认证取消失败');location.href='/manage/corporate/{$info_type}'</script>";
                    // exit;
                }
                $crediblefarminfo->status = 0;
                $crediblefarminfo->save();

            }

            $userinfo->status = 3;
            $userinfo->save();
            //取消认证日志增加 --duzh start
            $reject = $this->request->get('reject', 'string');
            if(mb_strlen($reject,'utf8')>=300){
                parent::msg('内容超长','/manage/corporate/'.$info_type);
                // echo "<script>alert('内容超长');location.href='/manage/corporate/{$info_type}'</script>";
                // exit;
            }
            $time = CURTIME;
            $userlog = M\UserLog::findFirst("user_id={$userinfo->user_id} AND credit_id = {$userinfo->credit_id} AND status={$userinfo->status} AND add_time = '{$time}'");

            if (!$userlog) $userlog = new M\UserLog();
            $userlog->user_id = $userinfo->user_id;
            $userlog->credit_id = $userinfo->credit_id;
            $userlog->operate_user_no = $userinfo->credit_no;
            $userlog->operate_user_name = $userinfo->name;
            $userlog->operate_time = CURTIME;
            $userlog->status = $userinfo->status;
            $userlog->demo = $reject;
            $userlog->add_time = time();
            $userlog->save();
            //end
            Func::adminlog("认证取消{$type}{$userext->username}",$this->session->adminuser['id']);
            //parent::msg('认证取消成功','/manage/corporate/{$info_type}');
            echo "<script>alert('认证取消成功');location.href='/manage/corporate/{$info_type}'</script>";
            exit;
        }
    }

    public function ifeditAction($id = 0, $typeid = 0, $type = 0, $credit_id = 0)
    {
        $sid = $this->session->getId();
        $getid = $this->session->getId();

        if (!$id)
        {
            parent::msg('访问方式不正确，请重新访问',"/manage/corporate/{$info_type}");
            // echo "<script>alert('访问方式不正确，请重新访问');location.href='/manage/corporate/{$type}'</script>";
            // exit;
        }
        $userinfo = M\UserInfo::findFirst("user_id = {$id} and credit_type = {$typeid} and credit_id = {$credit_id}");
        $privilege_taginfo = M\UserInfo::getprivilege_taginfo($id, $userinfo->credit_id);
        $bank = M\Bank::find()->toArray();
        $se_api = L\Func::serviceApi();
        $se = $se_api->county_selectByid($userinfo->se_id);

        if ($userinfo)
        {
            $userarea = M\UserArea::findFirstBycredit_id($userinfo->credit_id);

            if ($userarea)
            {
                $userareainfo = "'" . $userarea->province_name . "','" . $userarea->city_name . "','" . $userarea->district_name . "','" . $userarea->town_name . "'";
            }
            $userbank = M\UserBank::findFirstBycredit_id($userinfo->credit_id);
            // $userext  = M\UsersExt::findFirstBycredit_id($userinfo->credit_id);

            if ($userinfo->province_name)
            {
                $infoarea = "'" . $userinfo->province_name . "','" . $userinfo->city_name . "','" . $userinfo->district_name . "','" . $userinfo->town_name . "'";
                $this->view->infoarea = $infoarea;
            }

            if ($userbank && !empty($userbank->bank_address))
            {
                $area = explode(",", $userbank->bank_address);
                $areainfo = "'" . $area['0'] . "','" . $area['1'] . "','" . $area['2'] . "'";
                $this->view->areainfo = $areainfo;
            }
            $userbuy = M\UserBuy::findFirstBycredit_id($userinfo->credit_id);
            $usercontact = M\UserContact::findFirstBycredit_id($userinfo->credit_id);
            $userfarmcrops = M\UserFarmCrops::find("credit_id = {$userinfo->credit_id}")->toArray();
            $userfarm = M\UserFarm::findFirstBycredit_id($userinfo->credit_id);
            $user_farm_picture = M\UserFarmPicture::find("credit_id = {$userinfo->credit_id} AND type=0")->toArray();
            $user_farm_picture_contact = M\UserFarmPicture::find("credit_id = {$userinfo->credit_id} AND type=1")->toArray();
            if ($userfarmcrops)
            {
                $category_name = array_Column($userfarmcrops, 'category_id');
                $category_name_id = join(",", $category_name);
            }
            else
            {
                $category_name_id = 0;
            }
        }

        if ($userfarm)
        {
            $userfarm_area = "'" . $userfarm->province_name . "','" . $userfarm->city_name . "','" . $userfarm->district_name . "','" . $userfarm->town_name . "','" . $userfarm->village_name . "'";
        }
        else
        {
            $userfarm_area = '';
        }
        // var_dump($userfarm);die;
        $this->view->year = date('Y',strtotime("-30 year"));
        $this->view->maxyear = 200;
        $this->view->userfarm_area = $userfarm_area;
        $this->view->se = $se;
        $this->view->user_farm_picture = $user_farm_picture;
        $this->view->userfarm = $userfarm;
        $this->view->privilege_taginfo = $privilege_taginfo;
        $this->view->user_farm_picture_contact=$user_farm_picture_contact;
        $this->view->category_name_id = $category_name_id;
        $this->view->cateList = M\Category::getTopCaetList();
        $this->view->userareainfo = $userareainfo ? $userareainfo : '';
        $this->view->userfarmcrops = $userfarmcrops;
        $this->view->user = $user;
        $this->view->userinfo = $userinfo;
        $this->view->userbank = $userbank;
        // $this->view->userext = $userext;
        $this->view->userbuy = $userbuy;
        $this->view->sid = $sid;
        $this->view->usercontact = $usercontact;
        $this->view->userarea = $userarea;
        $this->view->getid = $getid;
        $this->view->bank = $bank;
    }

    public function peeditAction($id = 0, $typeid = 0, $type = 0, $credit_id = 0)
    {
        $getid = $this->session->getId();

        if (!$id)
        {
            parent::msg('访问方式不正确，请重新访问','/manage/corporate/'.$type);
            // echo "<script>alert('访问方式不正确，请重新访问');location.href='/manage/corporate/{$type}'</script>";
            // exit;
        }
        $userinfo = M\UserInfo::findFirst("user_id = {$id} and credit_type = {$typeid} and credit_id = {$credit_id}");
        $privilege_taginfo = M\UserInfo::getprivilege_taginfo($id, $userinfo->credit_id);
        $bank = M\Bank::find()->toArray();
        $se_api = L\Func::serviceApi();
        $se = $se_api->county_selectByid($userinfo->se_id);

        if ($userinfo)
        {
            $userarea = M\UserArea::findFirstBycredit_id($userinfo->credit_id);

            if ($userarea)
            {
                $userareainfo = "'" . $userarea->province_name . "','" . $userarea->city_name . "','" . $userarea->district_name . "','" . $userarea->town_name . "'";
            }
            $userbank = M\UserBank::findFirstBycredit_id($userinfo->credit_id);
            // $userext  = M\UsersExt::findFirstBycredit_id($userinfo->credit_id);

            if ($userinfo->province_name)
            {
                $infoarea = "'" . $userinfo->province_name . "','" . $userinfo->city_name . "','" . $userinfo->district_name . "','" . $userinfo->town_name . "'";
                $this->view->infoarea = $userinfo->town_id ? L\Areas::ldData($userinfo->town_id) : '';
                //$this->view->infoarea = $infoarea;

            }

            if ($userbank && !empty($userbank->bank_address))
            {
                $area = explode(",", $userbank->bank_address);
                $areainfo = "'" . $area['0'] . "','" . $area['1'] . "','" . $area['2'] . "'";
                $this->view->areainfo = $areainfo;
            }
            $userbuy = M\UserBuy::findFirstBycredit_id($userinfo->credit_id);
            $usercontact = M\UserContact::findFirstBycredit_id($userinfo->credit_id);
            $userfarmcrops = M\UserFarmCrops::find("credit_id = {$userinfo->credit_id}")->toArray();
            $userfarm = M\UserFarm::findFirstBycredit_id($userinfo->credit_id);
            $user_farm_picture = M\UserFarmPicture::find("credit_id = {$userinfo->credit_id}")->toArray();

            if ($userfarmcrops)
            {
                $category_name = array_Column($userfarmcrops, 'category_id');
                $category_name_id = join(",", $category_name);
            }
            else
            {
                $category_name_id = 0;
            }
        }

        if ($userfarm)
        {
            $userfarm_area = "'" . $userfarm->province_name . "','" . $userfarm->city_name . "','" . $userfarm->district_name . "','" . $userfarm->town_name . "','" . $userfarm->village_name . "'";
        }
        else
        {
            $userfarm_area = '';
        }
        $this->view->year = date('Y',strtotime("-30 year"));
        $this->view->maxyear = 100;
        $this->view->userfarm_area = $userfarm_area;
        $this->view->se = $se;
        $this->view->user_farm_picture = $user_farm_picture;
        $this->view->userfarm = $userfarm;
        $this->view->privilege_taginfo = $privilege_taginfo;
        $this->view->category_name_id = $category_name_id;
        $this->view->cateList = M\Category::getTopCaetList();
        $this->view->userareainfo = $userareainfo ? $userareainfo : '';
        $this->view->userfarmcrops = $userfarmcrops;
        $this->view->user = $user;
        $this->view->userinfo = $userinfo;
        $this->view->userbank = $userbank;
        // $this->view->userext = $userext;
        $this->view->userbuy = $userbuy;
        $this->view->usercontact = $usercontact;
        $this->view->userarea = $userarea;
        $this->view->getid = $getid;
        $this->view->bank = $bank;
    }

    public function vseditAction($id = 0, $typeid = 0, $type = 0, $credit_id = 0)
    {
        $getid = $this->session->getId();

        if (!$id)
        {
            parent::msg('访问方式不正确，请重新访问','/manage/corporate/'.$type);
            // echo "<script>alert('访问方式不正确，请重新访问');location.href='/manage/corporate/{$type}'</script>";
            // exit;
        }
        $userinfo = M\UserInfo::findFirst("user_id = {$id} and credit_type = {$typeid} and credit_id = {$credit_id}");
        $privilege_taginfo = M\UserInfo::getprivilege_taginfo($id, $userinfo->credit_id);
        $bank = M\Bank::find()->toArray();
        $se_api = L\Func::serviceApi();
        $se = $se_api->county_selectByid($userinfo->se_id);

        if ($userinfo)
        {
            $userarea = M\UserArea::findFirstBycredit_id($userinfo->credit_id);

            if ($userarea)
            {
                $userareainfo = "'" . $userarea->province_name . "','" . $userarea->city_name . "','" . $userarea->district_name . "','" . $userarea->town_name . "'";
            }
            $userbank = M\UserBank::findFirstBycredit_id($userinfo->credit_id);
            // $userext  = M\UsersExt::findFirstBycredit_id($userinfo->credit_id);

            if ($userinfo->province_name)
            {
                $infoarea = "'" . $userinfo->province_name . "','" . $userinfo->city_name . "','" . $userinfo->district_name . "','" . $userinfo->town_name . "'";
                $this->view->infoarea = $infoarea;
            }

            if ($userbank && !empty($userbank->bank_address))
            {
                $area = explode(",", $userbank->bank_address);
                $areainfo = "'" . $area['0'] . "','" . $area['1'] . "','" . $area['2'] . "'";
                $this->view->areainfo = $areainfo;
            }
            $userbuy = M\UserBuy::findFirstBycredit_id($userinfo->credit_id);
            $usercontact = M\UserContact::findFirstBycredit_id($userinfo->credit_id);
            $userfarmcrops = M\UserFarmCrops::find("credit_id = {$userinfo->credit_id}")->toArray();
            $userfarm = M\UserFarm::findFirstBycredit_id($userinfo->credit_id);
            $user_farm_picture = M\UserFarmPicture::find("credit_id = {$userinfo->credit_id}")->toArray();

            if ($userfarmcrops)
            {
                $category_name = array_Column($userfarmcrops, 'category_id');
                $category_name_id = join(",", $category_name);
            }
            else
            {
                $category_name_id = 0;
            }
        }

        if ($userfarm)
        {
            $userfarm_area = "'" . $userfarm->province_name . "','" . $userfarm->city_name . "','" . $userfarm->district_name . "','" . $userfarm->town_name . "','" . $userfarm->village_name . "'";
        }
        else
        {
            $userfarm_area = '';
        }
        $this->view->year = date('Y',strtotime("-30 year"));
        $this->view->maxyear = 100;
        $this->view->userfarm_area = $userfarm_area;
        $this->view->se = $se;
        $this->view->user_farm_picture = $user_farm_picture;
        $this->view->userfarm = $userfarm;
        $this->view->privilege_taginfo = $privilege_taginfo;
        $this->view->category_name_id = $category_name_id;
        $this->view->cateList = M\Category::getTopCaetList();
        $this->view->userareainfo = $userareainfo ? $userareainfo : '';
        $this->view->userfarmcrops = $userfarmcrops;
        $this->view->user = $user;
        $this->view->userinfo = $userinfo;
        $this->view->userbank = $userbank;
        // $this->view->userext = $userext;
        $this->view->userbuy = $userbuy;
        $this->view->usercontact = $usercontact;
        $this->view->userarea = $userarea;
        $this->view->getid = $getid;
        $this->view->bank = $bank;
    }
    /**
     * 验证村站服务区域
     * @return [type] [description]
     */

    public function checkAreaVillageAction()
    {
        $userarea_town = $this->request->getPost('userarea_town', 'int', 0);
        $tid = 0;

        if ($userarea_town) $tid = $userarea_town;
        $msg = array(
            'ok' => ''
        );

        if (!$tid)
        {
            $msg = array(
                'error' => '参数错误'
            );
            exit(json_encode($msg));
        }
        $where = "  ua.town_id  = '{$tid}' ";
        $credit_type = 4;
        $where.= "  AND 1 AND u.credit_type = '{$credit_type}'  AND u.status IN (0,1) ";
        $sql = " SELECT ua.credit_id  FROM `user_info` AS u , `user_area` AS ua WHERE ua.`credit_id` =  u.`credit_id` AND  {$where}  ";
        $area = $this->db->fetchAll($sql, 2);

        if (!$area)
        {
            exit(json_encode($msg));
        }
        $msg = array(
            'error' => '地区已有人负责'
        );
        exit(json_encode($msg));
    }
    /**
     * 检测用户服务区域
     * @param  integer $aid 区域id
     * @return [type]       [description]
     */

    public function checkUserFarmAreaAction()
    {
        $userarea_district = $this->request->getPost('userarea_district', 'int', 0);
        $tid = 0;

        if ($userarea_district) $tid = $userarea_district;
        $msg = array(
            'ok' => ''
        );

        if (!$tid)
        {
            $msg = array(
                'error' => '参数错误'
            );
            exit(json_encode($msg));
        }
        $credit_type = 2;
        $where = " ua.district_id = '{$tid}' ";
        $where.= "  AND 1 AND u.credit_type = '{$credit_type}'  AND u.status IN (0,1) ";
        $sql = " SELECT ua.credit_id  FROM `user_info` AS u , `user_area` AS ua WHERE ua.`credit_id` =  u.`credit_id` AND  {$where}  ";
        $area = $this->db->fetchAll($sql, 2);

        if (!$area)
        {
            exit(json_encode($msg));
        }
        $msg = array(
            'error' => '地区已有人负责'
        );
        exit(json_encode($msg));
    }
    /**
     *  删除可信农场图片
     * @return [type] [description]
     */
    public function delimgAction(){
        $id = $this->request->get('id', 'int', 640);
        $type = $this->request->get('type', 'int', 0);
        $rs = array('state'=>false, 'msg'=>'删除失败！');
        $user_farm_picture = M\UserFarmPicture::findFirst(" id ={$id} ");
        if($user_farm_picture){
            if($user_farm_picture->delete()){
                $rs = array('state'=>true, 'msg'=>'删除成功！');
            }
        }
        exit(json_encode($rs));
    }

}
