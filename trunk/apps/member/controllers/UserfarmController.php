<?php
namespace Mdg\Member\Controllers;
use Mdg\Models\Users as Users;
use Mdg\Models\AreasFull as mAreas;
use Mdg\Models as M;
use Mdg\Models\Sell as Sell;
use Lib\SMS as sms;
use Lib as L;
/**
 * 用户身份验证
 */

class UserfarmController extends ControllerMember
{
    /**
     * 用户农场
     */
    CONST USER_PICTURE_PATH = 'picture_path';
    /* 用户银行卡  */
    CONST USER_BANKCARD_PICTURE = 29;
    /* 用户身份证*/
    CONST USER_IDCARD_PICTURE = 30;
    /* 用户手持身份证 */
    CONST USER_PERSON_PICTURE = 31;
    /**
     * 县域
     */
    CONST USER_TYPE_HC = 2;
    /* 村站 */
    CONST USER_TYPE_VS = 4;
    /* 可信农场 */
    CONST USER_TYPE_IF = 8;
    /* 采购商*/
    CONST USER_TYPE_PE = 16;
    CONST USER_TYPE_MC = 32;
    /**
     * 查询认证进度
     * @param  string $need [description]
     * @return [type]       [description]
     */
    
    public function certificationAction($need = '') 
    {
        $iden = array(
            'if' => self::USER_TYPE_IF,
            'vs' => self::USER_TYPE_VS,
            'hc' => self::USER_TYPE_HC,
            'pe' => self::USER_TYPE_PE,
            'mc' => self::USER_TYPE_MC
        );
        $type = isset($iden[$need]) ? intval($iden[$need]) : 0;
        
        if (!$need || !$type) 
        {
            echo "<script>alert('来源错误');location.href='/member/userfarm/index';</script>";
            exit;
        }
        $uid = $this->getUserID();
        /* 获取认证信息 */
        $cond[] = " user_id = '{$uid}' AND credit_type = '{$type}' AND status = 1  ";
        // print_r($cond);exit;
        $info = M\UserInfo::findFirst($cond);
        
        if (!$info) 
        {
            echo "<script>alert('来源错误');location.href='/member/userfarm/index';</script>";
            exit;
        }
        /* 查询负责区域 */
        $UserArea = M\UserArea::findFirst(array(
            " user_id = '{$uid}' and credit_id = '{$info->credit_id}'"
        ));
        /* 查询用户农场信息 */
        $userFramCrops = M\UserFarmCrops::selectByuserFarm($info->user_id, $info->credit_id);
        $frmaCrops = join('、', array_column($userFramCrops, 'category_name'));
        /* 获取农场信息 */
        $UserFarm = M\UserFarm::selectByUser_id($info->user_id, $info->credit_id);
        if($need=='if') {
            $info->province_name = $UserFarm['province_name'];
            $info->city_name = $UserFarm['city_name'];
            $info->district_name = $UserFarm['district_name'];
            $info->town_name = $UserFarm['town_name'];
        }
        $this->view->UserFarm = $UserFarm;
        $this->view->UserArea = $UserArea;
        $this->view->frmaCrops = $frmaCrops;
        $this->view->info = $info;
        $this->view->title = '个人中心-身份认证-认证详细';
    }
    /**
     * 用户查看申请最新进度
     * @return [type] [description]
     */
    
    public function selectByApplyAction() 
    {
        $uid = $this->getUserID();
        /**/
        $info = M\UserInfo::find(" user_id = '{$uid}' ORDER BY add_time desc ");
        
        if (!$info) 
        {
            echo "<script>alert('该用户未有申请');location.href='/member/userfarm/index';</script>";
            exit;
        }
        /*  查询用户所有申请情况信息 */
        /* 用户注册类型 */
        // $tName = isset(M\Users::$_credit_type[$info->credit_type]) ? M\Users::$_credit_type[$info->credit_type] : '';
        $this->view->data = $info;
        $this->view->her = $her = isset(\Mdg\Models\Users::$_credit_id[$info->credit_type]) ? \Mdg\Models\Users::$_credit_id[$info->credit_type] : '';
        $this->view->tName = $tName;
    }
    
    public function checkCountyAction() 
    {
        $seusername = $this->request->getPost('seusername', 'string', '');
        $seid = $this->request->getPost('seid', 'string', '');
        $userfarm = array(
            'ok' => ''
        );
        $client = L\Func::serviceApi();
        
        if ($seusername) 
        {
            
            if (!$data = $client->county_selectByMobile($seusername)) 
            {
                $userfarm = array(
                    'error' => '该工程师不存在'
                );
                exit(json_encode($userfarm));
            }
        }
        elseif ($seid) 
        {
            
            if (!$data = $client->county_selectByservice_bianhao($seid)) 
            {
                $userfarm = array(
                    'error' => '该工程师不存在'
                );
                exit(json_encode($userfarm));
            }
        }
        else
        {
            $userfarm = array(
                'error' => '参数错误'
            );
            exit(json_encode($userfarm));
        }
        exit(json_encode($userfarm));
    }
    /**
     * 用户身份验证
     * @return [type] [description]
     */
    
    public function indexAction() 
    {
        $uid        = $this->getUserID();
        if (!$uid) {
            $this->flash->error("用户信息不存在!");
            return $this->dispatcher->forward(array(
                "controller"=> "userfarm",
                "action"    => "index",
            ));
        }
        // 获取我的申请
        $userinfo           = array();
        //可信农场申请
        $userHC = false;
        //县域服务中心申请
        $userVS = false;
        //采购经纪人申请
        $userIF = false;
        //采购企业申请
        $userPE = false;
        //蒙商
        $userlwtt=5;
        $userinfo           = M\UserInfo::find("user_id = '{$uid}' and status!=4 ORDER BY add_time desc")->toArray();

        if($userinfo) {
            foreach($userinfo as $k=>&$v) {
                
                // 申请时间
                $v['apply_time']    = date("Y-m-d H:i:s",$v['apply_time']);
                // 申请身份
                if($v['credit_type']== 8) {
                    $v['credit_type']= '可信农场';
                } elseif($v['credit_type']== 2) {
                    $v['credit_type']= '县域服务中心';
                } elseif($v['credit_type']== 16) {
                    if($v['type'] == 0) {
                        $v['credit_type']= '采购经纪人';
                    } elseif($v['type'] == 1) {
                        $v['credit_type']= '采购企业';
                    }
                }elseif($v['credit_type']==32){
                    $v['credit_type']= '产地服务站';
                }
                elseif($v['credit_type']==4){
                    $v['credit_type']= '村级服务站';
                }
                // 用户类型
                if($v['type']== 0) {
                    $v['type']= '个人';
                } elseif($v['type']== 1) {
                    $v['type']= '企业';
                } elseif($v['type']== 2) {
                    $v['type']= '家庭农场';
                } else {
                    $v['type']= '农村合作社';
                }
                // 申请状态
                if($v['status']== 0) {
                    $v['status_name']= '未审核';
                    if ($v['credit_type'] == '可信农场') {
                        $userHC = 1;
                    }elseif ($v['credit_type'] == '县域服务中心') {
                        $userVS = 1;
                    }elseif ($v['credit_type'] == '采购经纪人') {
                        $userIF = 1;
                        $userPE = 1;
                    }elseif ($v['credit_type'] == '采购企业') {
                        $userIF = 1;
                        $userPE = 1;  
                    }elseif($v['credit_type']=='产地服务站'){
                        $userlwtt=1;
                    }
                } elseif($v['status']== 1) {
                    $v['status_name']= '审核通过';
                } elseif($v['status']== 2) {
                    $v['status_name']= '审核未通过';
                } else {
                    $v['status_name']= '取消认证';
                }
            }
        }
         //检测是否完善信息
        $checkinfo=$this->checkLoginInfo();
        //检测是否绑定云农宝
        $checkynp=M\UserYnpInfo::checkuserynp($uid);
        //检测是否还有未删除的供应信息
        $checksell=M\Sell::checkSell($uid);

        $this->view->userHC   = $userHC;
        $this->view->userVS   = $userVS;
        $this->view->userIF   = $userIF;
        $this->view->userPE   = $userPE;
        $this->view->userlwtt   = $userlwtt;
        $this->view->data   = $userinfo;
        $this->view->checkinfo   = $checkinfo;
        $this->view->checkynp   = $checkynp;
        $this->view->checksell   = $checksell;
        $this->view->title  = '个人中心-身份认证';
    }
    /**
     *  删除采购申请
     */
    public function delapplyAction() {
        
        // 接收参数
        $uid        = $this->getUserID();
        $creditId   = isset($_REQUEST['credit_id']) ? intval( $_REQUEST['credit_id'] ) : 0;
        $users      = M\UserInfo::findFirstBycredit_id($creditId);
        
        // 参数校验
        if (!$uid) {
            echo json_encode(array(
                'code'      => 5,
                'result'    => '用户信息不存在'
            ));
            exit;
        }
        if ($users->user_id != $uid) {
            echo json_encode(array(
                'code'      => 0,
                'result'    => '你无权删除此信息！'
            ));
            exit;
        }
        if(!$creditId) {
            echo json_encode(array(
                'code'      => 1,
                'result'    => '参数错误'
            ));
            exit;
        }
        if(!$users) {
            echo json_encode(array(
                'code'      => 2,
                'result'    => '请求错误'
            ));
            exit;
        }
        
        // 处理数据
        $users->status  = 4;
        if(!$users->save()) {
            echo json_encode(array(
                'code'      => 3,
                'result'    => '删除失败'
            ));
            exit;
        }
        echo json_encode(array('code'=>4,'result'=>'删除成功'));
        exit;
    }
    /**
     *  查看详情
     */
    public function detailAction($creditid) {
        
        // 接收参数
        $uid        = $this->getUserID();
        $creditid   = isset($creditid) ? intval( $creditid ) : 0;
        $users      = M\UserInfo::findFirst("credit_id={$creditid} and credit_type!=4 ");
        
        // 参数校验
        if (!$uid) {
            $this->flash->error("用户信息不存在!");
            return $this->dispatcher->forward(array(
                "controller"=> "userfarm",
                "action"    => "index",
            ));
        }
        if (!$creditid) {
            $this->flash->error("参数错误!");
            return $this->dispatcher->forward(array(
                "controller"=> "userfarm",
                "action"    => "index",
            ));
        }
        if(!$users) {
            $this->flash->error("请求错误!");
            return $this->dispatcher->forward(array(
                "controller"=> "userfarm",
                "action"    => "index",
            ));
        }
        if ($users->user_id != $uid) {
            $this->flash->error("你无权查看详情!");
            return $this->dispatcher->forward(array(
                "controller"=> "userfarm",
                "action"    => "index",
            ));
        }       
        // 我的申请
        $flag       = '';
        $func       = '';
        $param      = '';
        $type       = '';

        if($users->credit_type == 8) {
            $users->credit_type= '可信农场';
            $flag   = 'if';
            $func   = 'edituser';

            $param  = 8;
            if($users->type == 0){
                //个人
                $type   = 1;
            } else {
                //企业
                $type   = 2;
            }
        } elseif($users->credit_type == 2) {
            $users->credit_type= '县域服务中心';
            $flag   = 'hc';
            $func   = 'editcounty';
            $param  = 2;
            if($users->type == 0){
                $type   = 1;
            } else {
                $type   = 2;
            }
        } elseif($users->credit_type == 16) {
            if($users->type == 0) {
                $users->credit_type = '采购经纪人';
                $flag   = 'pe';
                $func   = 'purchaser/1/1';
                $param  = 16;
                $type   = 0;
            } elseif($users->type == 1) {
                $users->credit_type = '采购企业';
                $flag   = 'pe';
                $func   = 'purchaser/2/1';
                $param  = 17;
                $type   = 1;
            }
        }elseif($users->credit_type==32){
           $this->response->redirect("lwtt/info/{$creditid}")->sendHeaders();
        }
        // 详细地址
        $users->detailaddress   = $users->province_name.$users->city_name.$users->district_name.$users->town_name.$users->address;
        // 用户类型
        if($users->type== 0) {
            $users->type= '个人';
        } elseif($users->type== 1) {
            $users->type= '企业';
        } elseif($users->type== 2) {
            $users->type= '家庭农场';
        } else {
            $users->type= '农村合作社';
        }
        // 申请日志
        $userLog    =array();

        $userLog    = M\UserLog::find("user_id=$uid and credit_id=$creditid order by add_time asc")->toArray();
       
        // if($userLog) {
        //  foreach($userLog as $k=>&$v) {
        //      $v['operate_time']  = date("Y-m-d H:i:s",$v['operate_time']);
        //      if($v['status']== 0) {
        //          $v['status_name']= '已提交申请';
        //      } elseif($v['status']== 1) {
        //          $v['status_name']= '审核通过';
        //      } elseif($v['status']== 2) {
        //          $v['status_name']= '审核未通过';
        //      } else {
        //          $v['status_name']= '取消审核';
        //      }
        //  }
        // }
        // 联系信息
        $usercontact= M\UserContact::findFirst("user_id = $uid and credit_id=$creditid");
        
        // 基本信息
        $userbasic  = M\UserBank::findFirst("user_id = $uid and credit_id=$creditid");
       
        if($userbasic) {
            $userbasic              = $userbasic->toArray();

            if($userbasic['bank_address']==",,"){
                $userbasic['bank_address']=false;
            }
            if($userbasic['bank_address']==""){
                $userbasic['bank_address']=false;
            }
           
            $userbasic['bankName']  = M\Bank::selectByCode($userbasic['bank_name']);
        }
        //农场信息
        $userfarm   = array();
        $userfarm   = M\UserFarm::findFirst("user_id = $uid and credit_id=$creditid");
        if($userfarm) {
            $userfarm               = $userfarm->toArray();
            $userfarm['address']    = $userfarm['province_name'].' '.$userfarm['city_name'].' '.$userfarm['district_name'].' '.$userfarm['town_name'].' '.$userfarm['village_name'].' '.$userfarm['address'];
        }
        // 采购类别
        $userfarm['cropName']   = M\UserFarmCrops::selectByuserFarm($uid,$creditid);
        $userfarm['cropName']   = join('、', array_column($userfarm['cropName'], 'category_name'));
        
        $pic['farm']        = M\UserFarmPicture::find("user_id = $uid and credit_id=$creditid AND type = '0'")->toArray();
        $pic['contract']        = M\UserFarmPicture::find("user_id = $uid and credit_id=$creditid AND type = '1'")->toArray();
      
        $this->view->users  = $users;
        $this->view->userlog= $userLog;
        $this->view->bank   = $userbasic;
        $this->view->farm   = $userfarm;
        $this->view->cont   = $usercontact;
        $this->view->pic    = $pic;
        $this->view->flag   = $flag;
        $this->view->func   = $func;
        $this->view->param  = $param;
        $this->view->type   = $type;
        $this->view->title = '个人中心-身份认证-详细查看';
        
    }
    
    /**
     *  可信农场申请重新提交
     */
    public function edituserAction($creditid) {
        
        $sid    = $this->session->getId();
        $company_addr = '';

        $typeArr = array(32,42);
        foreach($typeArr as $k => $v){
            M\TmpFile::clearall($sid,$v);
        }

        // 接收参数
        $creditid   = isset($creditid) ? intval($creditid) : 0 ;
        $uid        = $this->getUserID();
        $users      = M\UserInfo::findFirstBycredit_id($creditid);

        // 参数校验
        if (!$uid) {
            $this->flash->error("用户信息不存在!");
            return $this->dispatcher->forward(array(
                "controller"=> "userfarm",
                "action"    => "index",
            ));
        }
        if (!$creditid) {
            $this->flash->error("参数错误!");
            return $this->dispatcher->forward(array(
                "controller"=> "userfarm",
                "action"    => "index",
            ));
        }
        if(!$users) {
            $this->flash->error("请求错误!");
            return $this->dispatcher->forward(array(
                "controller"=> "userfarm",
                "action"    => "index",
            ));
        }
        if ($users->user_id != $uid) {
            $this->flash->error("你无权编辑信息!");
            return $this->dispatcher->forward(array(
                "controller"=> "userfarm",
                "action"    => "index",
            ));
        }
        
        // 获取数据
        $user   = M\UserInfo::findFirst("user_id=$uid and credit_id=$creditid");
        $bank   = M\UserBank::findFirst("user_id=$uid and credit_id=$creditid");
        $farm   = M\UserFarm::findFirst("user_id=$uid and credit_id=$creditid");
        $crops  = array_column(M\UserFarmCrops::selectByuserFarm($uid,$creditid),'category_name','id');
        $pic    = M\UserFarmPicture::find("user_id = $uid and credit_id=$creditid")->toArray();
        $cont   = M\UserContact::findFirst("user_id = $uid and credit_id=$creditid");
        
        // 处理农场图片
        $rs = array( 'farm' => array(),  'contract' => array() );
        foreach($pic as $k=>$v) {
            if($v['type'] == 0) {
                $rs['farm'][$v['id']]       = $v['picture_path'];
            } else {
                $rs['contract'][$v['id']]   = $v['picture_path'];
            }
        }
        /* 组装公司地址 */

        $company_addr[] = trim($user->province_name);
        $company_addr[] = trim($user->city_name);
        $company_addr[] = trim($user->district_name);
        $company_addr[] = trim($user->town_name);
        $company_addr = join('","', $company_addr);

        $this->view->company_addr = $company_addr;
        $farm->farm_area        = intval($farm->farm_area);
        $farm->describe         = trim($farm->describe);
        $this->view->farm_pic   = count($rs['farm']);
        $this->view->contract_pic = count($rs['contract']);
        // 传值
        $this->view->bankList   = M\Bank::getBankList();
        $this->view->curAreas   = $farm->village_id ? L\Areas::ldData($farm->village_id) : '';
        if($bank&&$bank->bank_address){
            $bank_area=explode(",",$bank->bank_address);
            $bank_area = "'".implode("', '", $bank_area)."'";
        }else{
            $bank_area='';
        }
        $this->view->bankAreas  =$bank_area;
        $this->view->cateList   = M\Category::getTopCaetList();
        $this->view->user       = $user;
        $this->view->bank       = $bank;
        $this->view->farm       = $farm;
        $this->view->crops      = $crops;
        $this->view->pic        = $rs;
        $this->view->cont       = $cont;
        $this->view->sid        = $sid;
        $this->view->creditid   = $creditid;
                $this->view->title  =' 个人中心-身份认证-编辑信息';
    }
    /**
     * 采购商家
     * @return [type] [description]
     */
    
    public function purchaserAction($ptype ,$isflag=0,$credit_id =0)
    {

        $inArr = array(1,2);
        if (!in_array($ptype,$inArr)) {
             echo "<script>alert('请求错误');location.href=/member/userfarm/index';</script>";
            exit;
        }
        /*  */
      
        if ($this->certcancel(self::USER_TYPE_PE)) 
        {
            
            echo "<script>alert('您的身份已被取消，不能再重复申请');location.href='/member/userfarm/index';</script>";
            die;
        }
 
        /* 检测用户信息是否完整 */
        
        if ($this->checkUserInfo()) 
        {
            echo "<script>alert('您的用户信息不完整,请完善后再申请');location.href='/member/perfect/index';</script>";
            exit;
        }
        /* 检测用户是否申请村站 */
       
        if (!$credit_id && $this->checkapply(self::USER_TYPE_PE))
        {
            echo "<script>alert('您已申请过此身份,不能重复申请');location.href='/member/userfarm/index';</script>";
            exit;
        }

        if($credit_id){
            if(!L\Validator::validate_is_digits($credit_id)){
                $this->flash->error("请求错误!");
                return $this->dispatcher->forward(array(
                    "controller"=> "userfarm",
                    "action"    => "index",
                ));
            }
            $userInfo = M\UserInfo::findFirst("credit_id = {$credit_id}");
            if(!$userInfo){
                $this->flash->error("请求错误!");
                return $this->dispatcher->forward(array(
                    "controller"=> "userfarm",
                    "action"    => "index",
                ));
            }
            $sid = $this->session->getId();
            $typeArr = array(31,33,11,10,34);#需要清除的临时图片类型
            foreach($typeArr as $k => $v){
                M\TmpFile::clearall($sid,$v);
            }
            $userInfo = $userInfo->toArray();
            $userBank = M\UserBank::findFirst("credit_id = {$credit_id}");
            if($userBank) {
                $userBank = $userBank->toArray();
            }
            $userFarmCrpos = M\UserFarmCrops::find("credit_id = {$credit_id}");
            if($userFarmCrpos) {
                $userFarmCrpos = $userFarmCrpos->toArray();
            }
            $UserContact = M\UserContact::findFirst("credit_id ={$credit_id}");
            if($UserContact) {
                $UserContact = $UserContact->toArray();
            }
            #$data = M\UserInfo::find()->toArray();
            #print_r($userInfo);exit;
        }
        $area[] = $userInfo['province_name'];
        $area[] = $userInfo['city_name'];
        $area[] = $userInfo['district_name'];
        $area[] = $userInfo['town_name'];
        $areas = join("','", $area);

        $bankaddress = str_replace(',',"','",$userBank['bank_address']);

        $sid = $this->session->getId();
        /*  清除 */
        M\TmpFile::clearOld($sid);
        $this->view->cateList = $cateList = M\Category::getTopCaetList();
        $this->view->bankList = M\Bank::getBankList();
        $this->view->imgtype = self::USER_PICTURE_PATH;
        $this->view->sid = $sid;
        $this->view->year = date('Y');
        $this->view->ptype = $ptype;
        $this->view->credit_id = $credit_id;
        $this->view->userinfo = $userInfo;
        $this->view->userbank =  $userBank;
        $this->view->selAreas = $selAreas = $areas;
        $this->view->bankaddress = $bankaddress;
        $this->view->userfarmcrpos = $userFarmCrpos;
        $this->view->usercontact = $UserContact;
        $this->view->isflag=$isflag;
#print_r($userbank);exit;
        $this->view->title = '个人中心-采购商身份认证';
    }
    /**
     * 采购商家
     * @return [type] [description]
     */
    
    public function purchasersaveAction() 
    {
        
        if (!$this->request->getPost()) 
        {
            $this->flash->error("数据错误!");
            return $this->dispatcher->forward(array(
                "controller" => "userfarm",
                "action" => "index",
            ));
        }
        $flag       = $this->request->getPost('flag', 'int', 0);
        $credit_id = $this->request->getPost('credit_id', 'int', 0);
        $category_name_text_0 = trim($this->request->getPost('category_name_text_0', 'string', '') , ',');
        $category_name_text_1 = trim($this->request->getPost('category_name_text_1', 'string', '') , ',');
        $member_type = $this->request->getPost('member_type', 'int', 0);
        $user_province_id = $this->request->getPost('user_province_id', 'int', 0);
        $user_city_id = $this->request->getPost('user_city_id', 'int', 0);
        $user_district_id = $this->request->getPost('user_district_id', 'int', 0);
        $user_town_id = $this->request->getPost('user_town_id', 'int', 0);
        $user_area_province_id = $this->request->getPost('user_area_province_id', 'int', 0);
        $user_area_city_id = $this->request->getPost('user_area_city_id', 'int', 0);
        $user_area_district_id = $this->request->getPost('user_area_district_id', 'int', 0);
        $user_area_town_id = $this->request->getPost('user_area_town_id', 'int', 0);
        $user_bank_province_id = $this->request->getPost('user_bank_province_id', 'int', 0);
        $user_bank_city_id = $this->request->getPost('user_bank_city_id', 'int', 0);
        $user_bank_district_id = $this->request->getPost('user_bank_district_id', 'int', 0);
        $province_id = $this->request->getPost('province_id', 'int', 0);
        $city_id = $this->request->getPost('city_id', 'int', 0);
        $district_id = $this->request->getPost('district_id', 'int', 0);
        $town_id = $this->request->getPost('town_id', 'int', 0);
        $ent_area_province_id = $this->request->getPost('ent_area_province_id', 'int', 0);
        $ent_area_city_id = $this->request->getPost('ent_area_city_id', 'int', 0);
        $ent_area_district_id = $this->request->getPost('ent_area_district_id', 'int', 0);
        $ent_area_town_id = $this->request->getPost('ent_area_town_id', 'int', 0);
        $ent_bank_province_id = $this->request->getPost('ent_bank_province_id', 'int', 0);
        $ent_bank_city_id = $this->request->getPost('ent_bank_city_id', 'int', 0);
        $ent_bank_district_id = $this->request->getPost('ent_bank_district_id', 'int', 0);
        $user_name = $this->request->getPost('user_name', 'string', '');
        $user_credit_no = $this->request->getPost('user_credit_no', 'string', '');
        $user_mobile = $this->request->getPost('user_mobile', 'string', '');
        $user_address = $this->request->getPost('user_address', 'string', '');
        $user_bank_name = $this->request->getPost('user_bank_name', 'string', '');
        $user_bank_account = $this->request->getPost('user_bank_account', 'string', '');
        $user_bank_cardno = $this->request->getPost('user_bank_cardno', 'string', '');
        $ent_company_name = $this->request->getPost('ent_company_name', 'string', '');
        $ent_certificate_no = $this->request->getPost('ent_certificate_no', 'string', '');
        $ent_address = $this->request->getPost('ent_address', 'string', '');
        $ent_erprise_legal_person = $this->request->getPost('ent_erprise_legal_person', 'string', '');
        $ent_contact_name = $this->request->getPost('ent_contact_name', 'string', '');
        $ent_contact_phone = $this->request->getPost('ent_contact_phone', 'string', '');
        $ent_contact_fax = $this->request->getPost('ent_contact_fax', 'string', '');
        $ent_bank_name = $this->request->getPost('ent_bank_name', 'string', '');
        $ent_bank_account = $this->request->getPost('ent_bank_account', 'string', '');
        $ent_bank_cardno = $this->request->getPost('ent_bank_cardno', 'string', '');
        $ent_register_no = $this->request->getPost('ent_register_no', 'string', '');
        $user_certificate_no = $this->request->getPost('user_certificate_no', 'string', '');
        $seusername = $this->request->getPost('seusername', 'string', '');
        $ent_seusername = $this->request->getPost('ent_seusername', 'string', '');
        $isedit=$this->request->getPost('isedit', 'string', '');
        $isflag=$this->request->getPost('isflag', 'int', 0);
        
        $category_name_text = !$member_type ? $category_name_text_0 : $category_name_text_1;
        $uid = $this->getUserID();
        
        if (!$uid) 
        {
            $this->flash->error("用户信息不存在!");
            return $this->dispatcher->forward(array(
                "controller" => "userfarm",
                "action" => "index",
            ));
        }
        $sid = $this->session->getId();
        /**/
        $info = M\Users::findFirst(" id ='{$uid}'");
      
        if (!$info) 
        {
            $this->flash->error("用户信息不存在!");
            return $this->dispatcher->forward(array(
                "controller" => "userfarm",
                "action" => "index",
            ));
        }
      
        if($isflag){
            $c_cond=" status=1 ";
        }else{
            $c_cond = $flag != 0 ? " status in (2, 3 )" : "status in (3)";
        }
       
        if(M\UserInfo::findFirst("user_id='{$uid}' and credit_type in (16) and {$c_cond} ")) {
           
            $this->flash->error("您已申请过同类身份!");
            return $this->dispatcher->forward(array(
                "controller" => "userfarm",
                "action" => "index",
            ));
        }
        
        $this->db->begin();
        try
        {

            $UserArea = new M\UserArea();
            /* 用户基本信息 */
            #确定是否是编辑
            if($flag != 0) {
                $UserInfo = M\UserInfo::findFirst("credit_id ={$credit_id}");
            } else {
               $UserInfo = new M\UserInfo();
            }
            $se_mobile = !$member_type ? $seusername : $ent_seusername;
            $UserInfo->user_id = $uid;
            $UserInfo->type = $member_type;
            $UserInfo->apply_time = CURTIME;
            $UserInfo->status = 0;
            $UserInfo->certificate_no = !$member_type ? $user_certificate_no : $ent_certificate_no;
            $UserInfo->phone = !$member_type ? $user_mobile : $UserInfo->phone;
            $UserInfo->province_id = !$member_type ? $user_province_id : $province_id;
            $UserInfo->city_id = !$member_type ? $user_city_id : $city_id;
            $UserInfo->district_id = !$member_type ? $user_district_id : $district_id;
            $UserInfo->town_id = !$member_type ? $user_town_id : $town_id;
            $UserInfo->address = !$member_type ? $user_address : $ent_address;
            $UserInfo->province_name = M\AreasFull::getAreasNametoid($UserInfo->province_id);
            $UserInfo->city_name = M\AreasFull::getAreasNametoid($UserInfo->city_id);
            $UserInfo->district_name = M\AreasFull::getAreasNametoid($UserInfo->district_id);
            $UserInfo->town_name = M\AreasFull::getAreasNametoid($UserInfo->town_id);
            $UserInfo->se_id = 0;
            $UserInfo->se_mobile = $se_mobile;
            $UserInfo->credit_type = self::USER_TYPE_PE;
            $UserInfo->credit_no = $this->getMemberNo($uid, self::USER_TYPE_PE);
            $UserInfo->company_name = $ent_company_name;
            $UserInfo->enterprise_legal_person = $ent_erprise_legal_person;
            $UserInfo->add_time = CURTIME;
            $UserInfo->last_update_time = CURTIME;
            $UserInfo->register_no = $ent_register_no;
            $UserInfo->name = !$member_type ? $user_name : '';//$UserInfo->name;
            //服务工程师
            if($se_mobile){
                $engineerinfo = M\EngineerManager::getEngineerInfo($se_mobile, false);
                if ($engineerinfo)
                {
                    $UserInfo->se_id = $engineerinfo->engineer_id;
                    $UserInfo->se_no = $engineerinfo->engineer_no;
                    $UserInfo->mobile_type = ($se_mobile == $engineerinfo->engineer_phone) ? 0 : 1 ;
                }
            }
            
            $UserInfo->save();
            $credit_id      = $UserInfo->credit_id;
            // $credit_id = 9;
            /* 联系人 */
            #确定是否是编辑
            if($flag != 0){
                $UserContact    = M\UserContact::findFirst("credit_id ={$credit_id}");
            } else {
                $UserContact    = new M\UserContact();
                
            }
            $UserContact->user_id   = $uid;
            $UserContact->name      = $ent_contact_name;
            $UserContact->phone     = $ent_contact_phone;
            $UserContact->fax       = $ent_contact_fax;
            $UserContact->add_time  = CURTIME;
            $UserContact->last_update_time = CURTIME;
            $UserContact->credit_id = $credit_id;
            
            $UserContact->save();
            /* 保存用户银行卡信息 */
            #确定是否是编辑
            if($flag != 0) {
                
                $UserBank = M\UserBank::findFirst("credit_id ={$credit_id}");
            } else {
                $UserBank = new M\UserBank();
            }
            $UserBank->user_id = $uid;
            $bArea[] = M\AreasFull::getAreasNametoid(!$member_type ? $user_bank_province_id : $ent_bank_province_id);
            $bArea[] = M\AreasFull::getAreasNametoid(!$member_type ? $user_bank_city_id : $ent_bank_city_id);
            $bArea[] = M\AreasFull::getAreasNametoid(!$member_type ? $user_bank_district_id : $ent_bank_district_id);
            $UserBank->bank_name = !$member_type ? $user_bank_name : $ent_bank_name;
            $UserBank->bank_address = $bArea ? join(',', $bArea) : '';
            $UserBank->bank_account = !$member_type ? $user_bank_account : $ent_bank_account;
            $UserBank->bank_cardno = !$member_type ? $user_bank_cardno : $ent_bank_cardno;
            // $bankcard_picture = M\TmpFile::findFirst(" sid = '{$sid}' AND type = '29' ");
            // $UserBank->bankcard_picture = $bankcard_picture ? $bankcard_picture->file_path : '';
            $idcard_picture = M\TmpFile::findFirst(" sid = '{$sid}' AND type = '31' ");
            if($idcard_picture) {
                $UserBank->idcard_picture = $idcard_picture ? $idcard_picture->file_path : $UserBank->idcard_picture;
            }
            
            // $person_picture = M\TmpFile::findFirst(" sid = '{$sid}' AND type = '30' ");
            // $UserBank->person_picture = $person_picture ? $person_picture->file_path : '';
            $identity_picture_lic = M\TmpFile::findFirst(" sid = '{$sid}' AND type = '33' ");
            if($identity_picture_lic) {
                $UserBank->identity_picture_lic = $identity_picture_lic ? $identity_picture_lic->file_path : $UserBank->identity_picture_lic;
            }
            
            $identity_picture_org = M\TmpFile::findFirst(" sid = '{$sid}' AND type = '11' ");
            if($identity_picture_org) {
                 $UserBank->identity_picture_org = $identity_picture_org ? $identity_picture_org->file_path : $UserBank->identity_picture_org;
            }
           
            $identity_picture_tax = M\TmpFile::findFirst(" sid = '{$sid}' AND type = '10' ");
            if($identity_picture_tax) {
                $UserBank->identity_picture_tax = $identity_picture_tax ? $identity_picture_tax->file_path : $UserBank->identity_picture_tax;
            }
            
            /* 身份证背面照 */
            $idcard_picture_back = M\TmpFile::findFirst(" sid = '{$sid}' AND type = '34' ");
            if($idcard_picture_back) {
                $UserBank->idcard_picture_back = $idcard_picture_back ? $idcard_picture_back->file_path : $UserBank->idcard_picture_back;
            }
            
            $UserBank->add_time = CURTIME;
            $UserBank->last_update_time = CURTIME;
            $UserBank->credit_id = $credit_id;
            $UserBank->save();
            
            if ($category_name_text) 
            {
                $category_name_text = array_unique(explode(',', $category_name_text));
                #查询是否存在分类属性 存在删除
                $DelUserFarmCrops = M\UserFarmCrops::find("credit_id ={$credit_id}");
                if($DelUserFarmCrops){
                    foreach($DelUserFarmCrops AS $ufckey => $ufcobj){
                        $ufcobj->delete();
                    }
                }
                foreach ($category_name_text as $key => $val) 
                {
                    /* 检测分类是否存在 */
                    $cid = intval($val);
                    
                    if (!$cate = M\Category::findFirst(" id = '{$cid}' AND parent_id > 0 ")) continue;
                    $UserFarmCrops = new M\UserFarmCrops();
                    $UserFarmCrops->user_id = $uid;
                    $UserFarmCrops->category_name = $cate->title;
                    $UserFarmCrops->add_time = CURTIME;
                    $UserFarmCrops->category_id = intval($cate->id);
                    $UserFarmCrops->credit_id = $credit_id;
                    $UserFarmCrops->save();
                }
            }
            //插入日志
            $userlog = new M\UserLog();
            $userlog->user_id = $uid;
            $userlog->credit_id = $credit_id;
            $userlog->operate_user_no =$UserInfo->credit_no;
            $userlog->operate_user_name = $UserInfo->name;
            $userlog->operate_time = CURTIME;
            $userlog->status = 0;
            if($isflag==1){
                $userlog->demo = "重提交申请";
            }else{
                $userlog->demo = "已提交申请";
            }
            $userlog->add_time = time();
            $userlog->save();

            $this->db->commit();
        }
        catch(\Exception $e) 
        {
            $this->db->rollback();            
        }
       
        $this->response->redirect("userfarm/detail/{$credit_id}")->sendHeaders();
        //$this->response->redirect("userfarm/selectByApply")->sendHeaders();
    }
    /**
     * 村站申请
     * @return [type] [description]
     */
    
    public function villageAction() 
    {
        /*  */
        
        if ($this->certcancel(self::USER_TYPE_VS)) 
        {
            echo "<script>alert('您的身份已被取消，不能再重复申请');location.href='/member/userfarm/index';</script>";
            exit;
        }
        /* 检测用户信息是否完整 */
        
        if ($this->checkUserInfo()) 
        {
            echo "<script>alert('您的用户信息不完整,请完善后再申请');location.href='/member/perfect/index';</script>";
            exit;
        }
        /* 检测用户是否申请村站 */
        
        if ($this->checkapplyFor(self::USER_TYPE_VS)) 
        {
            echo "<script>alert('您已申请过此身份,不能重复申请');location.href='/member/userfarm/index';</script>";
            exit;
        }
        
        if ($this->checkapplyFor(self::USER_TYPE_HC)) 
        {
            echo "<script>alert('您已申请过县域服务中心,不能申请此身份');location.href='/member/userfarm/index';</script>";
            exit;
        }
        $sid = $this->session->getId();
        /*  清除 */
        M\TmpFile::clearOld($sid);
        $this->view->cateList = $cateList = M\Category::getTopCaetList();
        $this->view->bankList = M\Bank::getBankList();
        $this->view->imgtype = self::USER_PICTURE_PATH;
        $this->view->sid = $sid;
        $this->view->year = date('Y');
        $this->view->title = '个人中心-村级服务站申请';
    }
    /**
     * 村站申请
     * @return [type] [description]
     */
    
    public function villagesaveAction() 
    {
        
        if (!$this->request->getPost()) 
        {
            $this->flash->error("数据错误!");
            return $this->dispatcher->forward(array(
                "controller" => "userfarm",
                "action" => "index",
            ));
        }
        $member_type = $this->request->getPost('member_type', 'int', 0);
        $user_province_id = $this->request->getPost('user_province_id', 'int', 0);
        $user_city_id = $this->request->getPost('user_city_id', 'int', 0);
        $user_district_id = $this->request->getPost('user_district_id', 'int', 0);
        $user_town_id = $this->request->getPost('user_town_id', 'int', 0);
        $user_area_province_id = $this->request->getPost('user_area_province_id', 'int', 0);
        $user_area_city_id = $this->request->getPost('user_area_city_id', 'int', 0);
        $user_area_district_id = $this->request->getPost('user_area_district_id', 'int', 0);
        $user_area_town_id = $this->request->getPost('user_area_town_id', 'int', 0);
        $user_bank_province_id = $this->request->getPost('user_bank_province_id', 'int', 0);
        $user_bank_city_id = $this->request->getPost('user_bank_city_id', 'int', 0);
        $user_bank_district_id = $this->request->getPost('user_bank_district_id', 'int', 0);
        $province_id = $this->request->getPost('province_id', 'int', 0);
        $city_id = $this->request->getPost('city_id', 'int', 0);
        $district_id = $this->request->getPost('district_id', 'int', 0);
        $town_id = $this->request->getPost('town_id', 'int', 0);
        $ent_area_province_id = $this->request->getPost('ent_area_province_id', 'int', 0);
        $ent_area_city_id = $this->request->getPost('ent_area_city_id', 'int', 0);
        $ent_area_district_id = $this->request->getPost('ent_area_district_id', 'int', 0);
        $ent_area_town_id = $this->request->getPost('ent_area_town_id', 'int', 0);
        $ent_bank_province_id = $this->request->getPost('ent_bank_province_id', 'int', 0);
        $ent_bank_city_id = $this->request->getPost('ent_bank_city_id', 'int', 0);
        $ent_bank_district_id = $this->request->getPost('ent_bank_district_id', 'int', 0);
        $user_name = $this->request->getPost('user_name', 'string', '');
        $user_credit_no = $this->request->getPost('user_credit_no', 'string', '');
        $user_mobile = $this->request->getPost('user_mobile', 'string', '');
        $user_address = $this->request->getPost('user_address', 'string', '');
        $user_bank_name = $this->request->getPost('user_bank_name', 'string', '');
        $user_bank_account = $this->request->getPost('user_bank_account', 'string', '');
        $user_bank_cardno = $this->request->getPost('user_bank_cardno', 'string', '');
        $ent_company_name = $this->request->getPost('ent_company_name', 'string', '');
        $ent_certificate_no = $this->request->getPost('ent_certificate_no', 'string', '');
        $ent_address = $this->request->getPost('ent_address', 'string', '');
        $ent_erprise_legal_person = $this->request->getPost('ent_erprise_legal_person', 'string', '');
        $ent_contact_name = $this->request->getPost('ent_contact_name', 'string', '');
        $ent_contact_phone = $this->request->getPost('ent_contact_phone', 'string', '');
        $ent_contact_fax = $this->request->getPost('ent_contact_fax', 'string', '');
        $ent_bank_name = $this->request->getPost('ent_bank_name', 'string', '');
        $ent_bank_account = $this->request->getPost('ent_bank_account', 'string', '');
        $ent_bank_cardno = $this->request->getPost('ent_bank_cardno', 'string', '');
        $ent_register_no = $this->request->getPost('ent_register_no', 'string', '');
        $uid = $this->getUserID();
        
        if (!$uid) 
        {
            $this->flash->error("用户不存在!");
            return $this->dispatcher->forward(array(
                "controller" => "userfarm",
                "action" => "index",
            ));
        }
        $sid = $this->session->getId();
        /**/
        $info = M\Users::findFirst(" id ='{$uid}'");
        
        if (!$info) 
        {
            $this->flash->error("用户不村子啊!");
            return $this->dispatcher->forward(array(
                "controller" => "userfarm",
                "action" => "index",
            ));
        }
        $this->db->begin();
        try
        {
            $UserInfo = new M\UserInfo();
            $UserContact = new M\UserContact();
            $UserBank = new M\UserBank();
            $UserArea = new M\UserArea();
            /* 用户基本信息 */
            $UserInfo->user_id = $uid;
            $UserInfo->type = $member_type;
            $UserInfo->apply_time = CURTIME;
            $UserInfo->status = 0;
            $UserInfo->certificate_no = !$member_type ? $user_credit_no : $ent_certificate_no;
            $UserInfo->phone = !$member_type ? $user_mobile : '';
            $UserInfo->province_id = !$member_type ? $user_province_id : $province_id;
            $UserInfo->city_id = !$member_type ? $user_city_id : $city_id;
            $UserInfo->district_id = !$member_type ? $user_district_id : $district_id;
            $UserInfo->town_id = !$member_type ? $user_town_id : $town_id;
            $UserInfo->address = !$member_type ? $user_address : $ent_address;
            $UserInfo->province_name = M\AreasFull::getAreasNametoid($UserInfo->province_id);
            $UserInfo->city_name = M\AreasFull::getAreasNametoid($UserInfo->city_id);
            $UserInfo->district_name = M\AreasFull::getAreasNametoid($UserInfo->district_id);
            $UserInfo->town_name = M\AreasFull::getAreasNametoid($UserInfo->town_id);
            $UserInfo->se_id = 0;
            $UserInfo->credit_type = self::USER_TYPE_VS;
            $UserInfo->credit_no = $this->getMemberNo($uid, self::USER_TYPE_VS);
            $UserInfo->company_name = $ent_company_name;
            $UserInfo->enterprise_legal_person = $ent_erprise_legal_person;
            $UserInfo->add_time = CURTIME;
            $UserInfo->last_update_time = CURTIME;
            $UserInfo->register_no = $ent_register_no;
            $UserInfo->name = !$member_type ? $user_name : '';
            //服务工程师
            if($UserInfo->se_mobile){
                $engineerinfo = M\EngineerManager::getEngineerInfo($UserInfo->se_mobile, false);
                if ($engineerinfo)
                {
                    $UserInfo->se_id = $engineerinfo->engineer_id;
                    $UserInfo->se_no = $engineerinfo->engineer_no;
                    $UserInfo->mobile_type = ($se_mobile == $engineerinfo->engineer_phone) ? 0 : 1 ;
                }
            }
            $UserInfo->save();
            $credit_id = $UserInfo->credit_id;
            // $credit_id                      = 9;
            /* 联系人 */
            $UserContact->user_id = $uid;
            $UserContact->name = $ent_contact_name;
            $UserContact->phone = $ent_contact_phone;
            $UserContact->fax = $ent_contact_fax;
            $UserContact->add_time = CURTIME;
            $UserContact->last_update_time = CURTIME;
            $UserContact->credit_id = $credit_id;
            $UserContact->save();
            /* 保存用户银行卡信息 */
            $UserBank->user_id = $uid;
            $bArea[] = M\AreasFull::getAreasNametoid(!$member_type ? $user_bank_province_id : $ent_bank_province_id);
            $bArea[] = M\AreasFull::getAreasNametoid(!$member_type ? $user_bank_city_id : $ent_bank_city_id);
            $bArea[] = M\AreasFull::getAreasNametoid(!$member_type ? $user_bank_district_id : $ent_bank_district_id);
            $UserBank->bank_name = !$member_type ? $user_bank_name : $ent_bank_name;
            $UserBank->bank_address = $bArea ? join(',', $bArea) : '';
            $UserBank->bank_account = !$member_type ? $user_bank_account : $ent_bank_account;
            $UserBank->bank_cardno = !$member_type ? $user_bank_cardno : $ent_bank_cardno;
            $bankcard_picture = M\TmpFile::findFirst(" sid = '{$sid}' AND type = '29' ");
            $UserBank->bankcard_picture = $bankcard_picture ? $bankcard_picture->file_path : '';
            $idcard_picture = M\TmpFile::findFirst(" sid = '{$sid}' AND type = '31' ");
            $UserBank->idcard_picture = $idcard_picture ? $idcard_picture->file_path : '';
            $person_picture = M\TmpFile::findFirst(" sid = '{$sid}' AND type = '30' ");
            $UserBank->person_picture = $person_picture ? $person_picture->file_path : '';
            $identity_picture_lic = M\TmpFile::findFirst(" sid = '{$sid}' AND type = '33' ");
            $UserBank->identity_picture_lic = $identity_picture_lic ? $identity_picture_lic->file_path : '';
            /* 身份证背面照 */
            $idcard_picture_back = M\TmpFile::findFirst(" sid = '{$sid}' AND type = '34' ");
            $UserBank->idcard_picture_back = $idcard_picture_back ? $idcard_picture_back->file_path : '';
            $UserBank->add_time = CURTIME;
            $UserBank->last_update_time = CURTIME;
            $UserBank->credit_id = $credit_id;
            $UserBank->save();
            /* 保存服务区域 */
            $UserArea->user_id = $uid;
            $UserArea->province_id = !$member_type ? $user_area_province_id : $ent_area_province_id;
            $UserArea->city_id = !$member_type ? $user_area_city_id : $ent_area_city_id;
            $UserArea->district_id = !$member_type ? $user_area_district_id : $ent_area_district_id;
            $UserArea->town_id = !$member_type ? $user_area_town_id : 0;
            $UserArea->town_name = M\AreasFull::getAreasNametoid($UserArea->town_id);
            $UserArea->district_name = M\AreasFull::getAreasNametoid($UserArea->district_id);
            $UserArea->city_name = M\AreasFull::getAreasNametoid($UserArea->city_id);
            $UserArea->province_name = M\AreasFull::getAreasNametoid($UserArea->province_id);
            $UserArea->village_name = '';
            $UserArea->village_id = 0;
            $UserArea->add_time = CURTIME;
            $UserArea->last_update_time = CURTIME;
            $UserArea->credit_id = $credit_id;
            $UserArea->save();
            /* 农场图片 */
            $UserFarmPicture = new M\UserFarmPicture();
            $UserFarmPicture->user_id = $uid;
            $picture_path = M\TmpFile::findFirst(" sid = '{$sid}' AND type = '32' ");
            $UserFarmPicture->picture_path = $picture_path ? $picture_path->file_path : '';
            $UserFarmPicture->add_time = CURTIME;
            $UserFarmPicture->credit_id = $credit_id;
            $UserFarmPicture->save();
            $this->db->commit();
        }
        catch(\Exception $e) 
        {
            $this->db->rollback();
            // print_R($e->getMessage());
            
        }
        $this->response->redirect("userfarm/selectByApply")->sendHeaders();
    }
    /**
     * 县站
     * @return [type] [description]
     */
    
    public function countyAction() 
    {
        /*  */
        
        if ($this->certcancel(self::USER_TYPE_HC)) 
        {
            echo "<script>alert('您的身份已被取消，不能再重复申请');location.href='/member/userfarm/index';</script>";
            exit;
        }
        /* 检测用户信息是否完整 */
        
        if ($this->checkUserInfo()) 
        {
            echo "<script>alert('您的用户信息不完整,请完善后再申请');location.href='/member/perfect/index';</script>";
            exit;
        }
        /* 检测用户是否申请村站 */
        
        if ($this->checkapply(self::USER_TYPE_HC)) 
        {
            echo "<script>alert('您已申请过此身份,不能重复申请');location.href='/member/userfarm/index';</script>";
            exit;
        }
        
        if ($this->checkapplyFor(self::USER_TYPE_VS, 1) > 0 ) 
        {
            echo "<script>alert('您已申请过村级服务中心,不能申请此身份');location.href='/member/userfarm/index';</script>";
            exit;
        }
        $sid = $this->session->getId();
        $result = M\EngineerManager::getEngineerInfo($this->getUserName(), true);
        if($result){
            echo "<script>alert('您已经是工程师,不能申请此身份');location.href='/member/userfarm/index';</script>";
            exit;
        }

        /*  清除 */
        M\TmpFile::clearOld($sid);
        $this->view->cateList = $cateList = M\Category::getTopCaetList();
        $this->view->bankList = M\Bank::getBankList();
        $this->view->imgtype = self::USER_PICTURE_PATH;
        $this->view->sid = $sid;
        $this->view->year = date('Y');
        $this->view->title = '个人中心-县域服务中心申请';
    }
    /**
     * 县级保存
     * @return [type] [description]
     */
    
    public function countysaveAction() 
    {
        
        if (!$this->request->getPost()) 
        {
            $this->flash->error("数据错误!");
            return $this->dispatcher->forward(array(
                "controller" => "userfarm",
                "action" => "index",
            ));
        }
        
        $member_type = $this->request->getPost('member_type', 'int', 0);
        $user_province_id = $this->request->getPost('user_province_id', 'int', 0);
        $user_city_id = $this->request->getPost('user_city_id', 'int', 0);
        $user_district_id = $this->request->getPost('user_district_id', 'int', 0);
        $user_town_id = $this->request->getPost('user_town_id', 'int', 0);
        $user_area_province_id = $this->request->getPost('user_area_province_id', 'int', 0);
        $user_area_city_id = $this->request->getPost('user_area_city_id', 'int', 0);
        $user_area_district_id = $this->request->getPost('user_area_district_id', 'int', 0);
        $user_area_town_id = $this->request->getPost('user_area_town_id', 'int', 0);
        $user_bank_province_id = $this->request->getPost('user_bank_province_id', 'int', 0);
        $user_bank_city_id = $this->request->getPost('user_bank_city_id', 'int', 0);
        $user_bank_district_id = $this->request->getPost('user_bank_district_id', 'int', 0);
        $province_id = $this->request->getPost('province_id', 'int', 0);
        $city_id = $this->request->getPost('city_id', 'int', 0);
        $district_id = $this->request->getPost('district_id', 'int', 0);
        $town_id = $this->request->getPost('town_id', 'int', 0);
        $ent_area_province_id = $this->request->getPost('ent_area_province_id', 'int', 0);
        $ent_area_city_id = $this->request->getPost('ent_area_city_id', 'int', 0);
        $ent_area_district_id = $this->request->getPost('ent_area_district_id', 'int', 0);
        $ent_area_town_id = $this->request->getPost('ent_area_town_id', 'int', 0);
        $ent_bank_province_id = $this->request->getPost('ent_bank_province_id', 'int', 0);
        $ent_bank_city_id = $this->request->getPost('ent_bank_city_id', 'int', 0);
        $ent_bank_district_id = $this->request->getPost('ent_bank_district_id', 'int', 0);
        $user_name = $this->request->getPost('user_name', 'string', '');
        $user_credit_no = $this->request->getPost('user_credit_no', 'string', '');
        $user_mobile = $this->request->getPost('user_mobile', 'string', '');
        $user_address = $this->request->getPost('user_address', 'string', '');
        $user_bank_name = $this->request->getPost('user_bank_name', 'string', '');
        $user_bank_account = $this->request->getPost('user_bank_account', 'string', '');
        $user_bank_cardno = $this->request->getPost('user_bank_cardno', 'string', '');
        $ent_company_name = $this->request->getPost('ent_company_name', 'string', '');
        $ent_certificate_no = $this->request->getPost('ent_certificate_no', 'string', '');
        $ent_address = $this->request->getPost('ent_address', 'string', '');
        $ent_erprise_legal_person = $this->request->getPost('ent_erprise_legal_person', 'string', '');
        $ent_contact_name = $this->request->getPost('ent_contact_name', 'string', '');
        $ent_contact_phone = $this->request->getPost('ent_contact_phone', 'string', '');
        $ent_contact_fax = $this->request->getPost('ent_contact_fax', 'string', '');
        $ent_bank_name = $this->request->getPost('ent_bank_name', 'string', '');
        $ent_bank_account = $this->request->getPost('ent_bank_account', 'string', '');
        $ent_bank_cardno = $this->request->getPost('ent_bank_cardno', 'string', '');
        $uid = $this->getUserID();
        $seusername = $this->request->getPost('senameid', 'string', '');
        $ent_register_no = $this->request->getPost('ent_register_no', 'string', '');
        $seid = $this->request->getPost('seid', 'int', 0);
        $isedit = $this->request->getPost('isedit', 'int', 0);
       
        $county = array();
        
        $credit_id = $this->request->getPost('info_id', 'int', 0);
        
        if (!$uid) 
        {
            $this->flash->error("用户信息不存在!");
            return $this->dispatcher->forward(array(
                "controller" => "userfarm",
                "action" => "index",
            ));
        }
        $sid = $this->session->getId();
        /**/
        $info = M\Users::findFirst(" id ='{$uid}'");
        
        if (!$info) 
        {
            $this->flash->error("用户信息不存在!");
            return $this->dispatcher->forward(array(
                "controller" => "userfarm",
                "action" => "index",
            ));
        }

        $result = M\EngineerManager::getEngineerInfo($this->getUserName(), true);
        if($result){
            echo "<script>alert('您已经是工程师,不能申请此身份');location.href='/member/userfarm/index';</script>";
            exit;
        }

        $this->db->begin();
        try
        {
         
        /*
            $client = L\Func::serviceApi();
            if (!$member_type) 
            {
                $county = $client->county_selectByMobile($seusername);
            }
            else
            {
                $county = $client->county_selectByservice_bianhao($seid);
            }
        */

            // $data = json_decode($county);
            
            
            $UserContact= M\UserContact::findFirst(" user_id ='{$uid}' and credit_id='{$credit_id}'");
            if (!$UserContact){
                $UserContact = new M\UserContact();
            }
            $UserBank   = M\UserBank::findFirst(" user_id ='{$uid}' and credit_id='{$credit_id}'");
            if (!$UserBank){
                $UserBank = new M\UserBank();
            }
            $UserInfo   = M\UserInfo::findFirst(" user_id ='{$uid}' and credit_id='{$credit_id}'");
            if (!$UserInfo){
                $UserInfo = new M\UserInfo();
            }
            $UserArea   = M\UserArea::findFirst(" user_id ='{$uid}' and credit_id='{$credit_id}'");
             if (!$UserArea){
                $UserArea = new M\UserArea();
            }
            
            /* 用户基本信息 */
            $se_mobile = !$member_type ? $seusername : $seid;
            $UserInfo->user_id = $uid;
            $UserInfo->type = $member_type;
            $UserInfo->apply_time = CURTIME;
            $UserInfo->status = 0;
            $UserInfo->certificate_no = !$member_type ? $user_credit_no : $ent_certificate_no;
            $UserInfo->phone = !$member_type ? $user_mobile : '';
            $UserInfo->province_id = !$member_type ? $user_province_id : $province_id;
            $UserInfo->city_id = !$member_type ? $user_city_id : $city_id;
            $UserInfo->district_id = !$member_type ? $user_district_id : $district_id;
            $UserInfo->town_id = !$member_type ? $user_town_id : $town_id;
            $UserInfo->address = !$member_type ? $user_address : $ent_address;
            $UserInfo->province_name = M\AreasFull::getAreasNametoid($UserInfo->province_id);
            $UserInfo->city_name = M\AreasFull::getAreasNametoid($UserInfo->city_id);
            $UserInfo->district_name = M\AreasFull::getAreasNametoid($UserInfo->district_id);
            $UserInfo->town_name = M\AreasFull::getAreasNametoid($UserInfo->town_id);
            $UserInfo->se_id = isset($county['county_id']) ? intval($county['county_id']) : 0;
            $UserInfo->se_mobile = $se_mobile;
            $UserInfo->credit_type = self::USER_TYPE_HC;
            $UserInfo->credit_no = $this->getMemberNo($uid, self::USER_TYPE_HC);
            $UserInfo->company_name = $ent_company_name;
            $UserInfo->enterprise_legal_person = $ent_erprise_legal_person;
            $UserInfo->add_time = CURTIME;
            $UserInfo->last_update_time = CURTIME;
            $UserInfo->register_no = $ent_register_no;
            $UserInfo->name = !$member_type ? $user_name : '';
            //服务工程师
            if($se_mobile){
                $engineerinfo = M\EngineerManager::getEngineerInfo($se_mobile, true);
                if ($engineerinfo)
                {
                    $UserInfo->se_id = $engineerinfo->engineer_id;
                    $UserInfo->se_no = $engineerinfo->engineer_no;
                }
            }
            $UserInfo->save();
            $credit_id = $UserInfo->credit_id;
            /* 联系人 */
            $UserContact->user_id = $uid;
            $UserContact->name = $ent_contact_name;
            $UserContact->phone = $ent_contact_phone;
            $UserContact->fax = $ent_contact_fax;
            $UserContact->add_time = CURTIME;
            $UserContact->last_update_time = CURTIME;
            $UserContact->credit_id = $credit_id;
            $UserContact->save();
            /* 保存用户银行卡信息 */
            $UserBank->user_id = $uid;
            $bArea[] = M\AreasFull::getAreasNametoid(!$member_type ? $user_bank_province_id : $ent_bank_province_id);
            $bArea[] = M\AreasFull::getAreasNametoid(!$member_type ? $user_bank_city_id : $ent_bank_city_id);
            $bArea[] = M\AreasFull::getAreasNametoid(!$member_type ? $user_bank_district_id : $ent_bank_district_id);
            $UserBank->bank_name = !$member_type ? $user_bank_name : $ent_bank_name;
            $UserBank->bank_address = $bArea ? join(',', $bArea) : '';
            $UserBank->bank_account = !$member_type ? $user_bank_account : $ent_bank_account;
            $UserBank->bank_cardno = !$member_type ? $user_bank_cardno : $ent_bank_cardno;
            // $bankcard_picture = M\TmpFile::findFirst(" sid = '{$sid}' AND type = '29' ");
            // $UserBank->bankcard_picture = $bankcard_picture ? $bankcard_picture->file_path : '';
            $idcard_picture = M\TmpFile::findFirst(" sid = '{$sid}' AND type = '31' ");
            if($idcard_picture) {
                
                $UserBank->idcard_picture = $idcard_picture ? $idcard_picture->file_path : '';
            }
            // $person_picture = M\TmpFile::findFirst(" sid = '{$sid}' AND type = '30' ");
            //$UserBank->person_picture = $person_picture ? $person_picture->file_path : '';
            $identity_picture_lic = M\TmpFile::findFirst(" sid = '{$sid}' AND type = '33' ");
            if($identity_picture_lic) {
                $UserBank->identity_picture_lic = $identity_picture_lic ? $identity_picture_lic->file_path : '';
            }
            
            $identity_picture_org = M\TmpFile::findFirst(" sid = '{$sid}' AND type = '11' ");
            if($identity_picture_org) {
                $UserBank->identity_picture_org = $identity_picture_org ? $identity_picture_org->file_path : '';
            }
            
            $identity_picture_tax = M\TmpFile::findFirst(" sid = '{$sid}' AND type = '10' ");
            if($identity_picture_tax) {
                $UserBank->identity_picture_tax = $identity_picture_tax ? $identity_picture_tax->file_path : '';
            }
            
            /* 身份证背面照 */
            $idcard_picture_back = M\TmpFile::findFirst(" sid = '{$sid}' AND type = '34' ");
            if($idcard_picture_back) {
                $UserBank->idcard_picture_back = $idcard_picture_back ? $idcard_picture_back->file_path : '';
            }
            
            $UserBank->add_time = CURTIME;
            $UserBank->last_update_time = CURTIME;
            $UserBank->credit_id = $credit_id;
            $UserBank->save();
            /* 保存服务区域 */
            $UserArea->user_id = $uid;
            $UserArea->province_id = !$member_type ? $user_area_province_id : $ent_area_province_id;
            $UserArea->city_id = !$member_type ? $user_area_city_id : $ent_area_city_id;
            $UserArea->district_id = !$member_type ? $user_area_district_id : $ent_area_district_id;
            $UserArea->town_id = !$member_type ? $user_area_town_id : $ent_area_town_id;
            $UserArea->town_name = M\AreasFull::getAreasNametoid($UserArea->town_id);
            $UserArea->district_name = M\AreasFull::getAreasNametoid($UserArea->district_id);
            $UserArea->city_name = M\AreasFull::getAreasNametoid($UserArea->city_id);
            $UserArea->province_name = M\AreasFull::getAreasNametoid($UserArea->province_id);
            $UserArea->village_name = '';
            $UserArea->village_id = 0;
            $UserArea->add_time = CURTIME;
            $UserArea->last_update_time = CURTIME;
            $UserArea->credit_id = $credit_id;
            $UserArea->save();

             //插入日志
            $userlog = new M\UserLog();
            $userlog->user_id = $uid;
            $userlog->credit_id = $credit_id;
            $userlog->operate_user_no =$UserInfo->credit_no;
            $userlog->operate_user_name = $UserInfo->name;
            $userlog->operate_time = CURTIME;
            $userlog->status = 0;
            if($isedit==1){
                $userlog->demo = "重提交申请";
            }else{
                $userlog->demo = "已提交申请";
            }
            $userlog->add_time = time();
            $userlog->save();

            $this->db->commit();
        }
        catch(\Exception $e) 
        {
            $this->db->rollback();
            // print_R($e->getMessage());
            
        }
        $this->response->redirect("userfarm/detail/{$credit_id}")->sendHeaders();
        //$this->response->redirect("userfarm/selectByApply")->sendHeaders();
    }
    /**
     * 用户申请
     * @return [type] [description]
     */
    
    public function userAction() 
    {//echo self::USER_TYPE_IF;exit;
        /*  */
        //echo $this->session->user['id'];die;
        
        if ($this->certcancel(self::USER_TYPE_IF)) 
        {
            echo "<script>alert('您的身份已被取消，不能再重复申请');location.href='/member/userfarm/index';</script>";
            exit;
        }
        /* 检测用户信息是否完整 */
        
        if ($this->checkUserInfo()) 
        {
            echo "<script>alert('您的用户信息不完整,请完善后再申请');location.href='/member/perfect/index';</script>";
            exit;
        }
        // /* 检测用户是否申请村站 */
        
        if ($this->checkapply(self::USER_TYPE_IF)) 
        {
            echo "<script>alert('您已申请过此身份,不能重复申请');location.href='/member/userfarm/index';</script>";
            exit;
        }
        $sid = $this->session->getId();
        /*  清除 */
        M\TmpFile::clearOld($sid);
        $this->view->cateList = $cateList = M\Category::getTopCaetList();
        $this->view->bankList = M\Bank::getBankList();
        $this->view->imgtype = self::USER_PICTURE_PATH;
        $this->view->sid = $sid;
        $this->view->year = date('Y');
        $this->view->maxyear = 100;
        $this->view->title = '个人中心-可信农场申请';
    }
    /**
     * 用户申请保存
     * @return [type] [description]
     */
    
    public function usersaveAction() 
    {
        
        if (!$this->request->getPost()) 
        {
            $this->flash->error("来源错误!");
            return $this->dispatcher->forward(array(
                "controller" => "userfarm",
                "action" => "index",
            ));
        }
      
        $uid        = $this->getUserID();
        $credit_id  = $this->request->getPost('cid', 'int', 0);
        $start_year = $this->request->getPost('start_year', 'int', 0);
        $start_month = $this->request->getPost('start_month', 'int', 0);
        $year = $this->request->getPost('year', 'int', 0);
        $month = $this->request->getPost('month', 'int', 0);
        $member_type = $this->request->getPost('member_type', 'int', 0);
        $bank_province_id = $this->request->getPost('bank_province_id', 'int', 0);
        $bank_city_id = $this->request->getPost('bank_city_id', 'int', 0);
        $bank_district_id = $this->request->getPost('bank_district_id', 'int', 0);
        $user_province_id = $this->request->getPost('user_province_id', 'int', 0);
        $user_city_id = $this->request->getPost('user_city_id', 'int', 0);
        $user_district_id = $this->request->getPost('user_district_id', 'int', 0);
        $user_town_id = $this->request->getPost('user_town_id', 'int', 0);
        $user_village_id = $this->request->getPost('user_village_id', 'int', 0);
        $ent_province_id = $this->request->getPost('ent_province_id', 'int', 0);
        $ent_city_id = $this->request->getPost('ent_city_id', 'int', 0);
        $ent_district_id = $this->request->getPost('ent_district_id', 'int', 0);
        $ent_town_id = $this->request->getPost('ent_town_id', 'int', 0);
        $ent_bank_city_id = $this->request->getPost('ent_bank_city_id', 'int', 0);
        $ent_bank_district_id = $this->request->getPost('ent_bank_district_id', 'int', 0);
        $ent_village_id = $this->request->getPost('ent_village_id', 'int', 0);
        $source = $this->request->getPost('source', 'int', 0);
        $ent_bank_province_id = $this->request->getPost('ent_bank_province_id', 'int', 0);
        $ent_source = $this->request->getPost('ent_source', 'int', 0);
        $name = $this->request->getPost('name', 'string', '');
        $credit_no = $this->request->getPost('credit_no', 'string', '');
        $mobile = $this->request->getPost('mobile', 'string', '');
        $user_bank_name = $this->request->getPost('user_bank_name', 'string', '');
        $bank_account = $this->request->getPost('bank_account', 'string', '');
        $bank_cardno = $this->request->getPost('bank_cardno', 'string', '');
        $farm_name = $this->request->getPost('farm_name', 'string', '');
        $user_address = $this->request->getPost('user_address', 'string', '');
        $farm_areas = $this->request->getPost('farm_areas', 'string', '');
        $category_name = $this->request->getPost('category_name', 'string', '');
        $category_name_text_0 = trim($this->request->getPost('category_name_text_0', 'string', '') , ',');
        $category_name_text_1 = trim($this->request->getPost('category_name_text_1', 'string', '') , ',');
        $user_describe = $this->request->getPost('user_describe', 'string', '');
        $ent_company_name = $this->request->getPost('ent_company_name', 'string', '');
        $ent_certificate_no = $this->request->getPost('ent_certificate_no', 'string', '');
        $ent_address = $this->request->getPost('ent_address', 'string', '');
        $ent_erprise_legal_person = $this->request->getPost('ent_erprise_legal_person', 'string', '');
        $ent_contact_name = $this->request->getPost('ent_contact_name', 'string', '');
        $ent_contact_phone = $this->request->getPost('ent_contact_phone', 'string', '');
        $ent_contact_fax = $this->request->getPost('ent_contact_fax', 'string', '');
        $ent_bank_name = $this->request->getPost('ent_bank_name', 'string', '');
        $ent_bank_account = $this->request->getPost('ent_bank_account', 'string', '');
        $ent_bank_cardno = $this->request->getPost('ent_bank_cardno', 'string', '');
        $ent_farm_name = $this->request->getPost('ent_farm_name', 'string', '');
        $ent_farm_area = $this->request->getPost('ent_farm_area', 'string', '');
        $ent_month = $this->request->getPost('ent_month', 'string', '');
        $ent_describe = $this->request->getPost('ent_describe', 'string', '');
        $ent_start_year = $this->request->getPost('ent_start_year', 'int', 0);
        $ent_start_month = $this->request->getPost('ent_start_month', 'int', 0);
        $seusername = $this->request->getPost('seusername', 'string', '');
        $ent_seusername = $this->request->getPost('ent_seusername', 'string', '');
        /* 公司基本信息 */
        $province_id = $this->request->getPost('province_id', 'int', 0);
        $city_id = $this->request->getPost('city_id', 'int', 0);
        $district_id = $this->request->getPost('district_id', 'int', 0);
        $town_id = $this->request->getPost('town_id', 'int', 0);
        $address = $this->request->getPost('address', 'string', '');
        $ent_register_no = $this->request->getPost('ent_register_no', 'string', '');
        $ent_year = $this->request->getPost('ent_year', 'string', '');
        
        $user_stime = $this->request->getPost('user_stime', 'string', '');
        $user_etime = $this->request->getPost('user_etime', 'string', '');
        $ent_stime = $this->request->getPost('ent_stime', 'string', '');
        $ent_etime = $this->request->getPost('ent_etime', 'string', '');
        $lwtt_phone = $this->request->getPost('lwtt_phone', 'string', '');
        $isedit=$this->request->getPost('isedit', 'int', 0);

        $category_name_text = !$member_type ? $category_name_text_0 : $category_name_text_1;
        $sid = $this->session->getId();
        
        /**/
        $info = M\Users::findFirst(" id ='{$uid}'");
        /* 检测用户是否申请农场 */
        //print_R($_POST);
        // exit;
        // if ($this->checkapplyFor(self::USER_TYPE_IF, 0)) 
        // {
        //     echo "<script>alert('该用户已申请可信农场');location.href='/member/userfarm/index';</script>";
        //     exit;
        // }
        if (!$uid) 
        {
            $this->flash->error("用户信息不存在!");
            return $this->dispatcher->forward(array(
                "controller" => "userfarm",
                "action" => "index",
            ));
        }
        if (!$info) 
        {
            $this->flash->error("用户不存在!");
            return $this->dispatcher->forward(array(
                "controller" => "userfarm",
                "action" => "index",
            ));
        }
        $this->db->begin();
        try
        {
            $UserFarm   = M\UserFarm::findFirst(" user_id ='{$uid}' and credit_id='{$credit_id}'");
            
            if(!$UserFarm){
                $UserFarm = new M\UserFarm();
            }
            $UserInfo   = M\UserInfo::findFirst(" user_id ='{$uid}' and credit_id='{$credit_id}'");
            
            if(!$UserInfo){
                $UserInfo = new M\UserInfo();
            }
            $UserContact    = M\UserContact::findFirst(" user_id ='{$uid}' and credit_id='{$credit_id}'");
            
            if(!$UserContact){
                $UserContact = new M\UserContact();
            }
            $UserBank   = M\UserBank::findFirst(" user_id ='{$uid}' and credit_id='{$credit_id}'");
            
            if(!$UserBank){
                $UserBank = new M\UserBank();
            }
            /* 用户基本信息 */
            $se_mobile = !$member_type ? $seusername : $ent_seusername;
            $UserInfo->user_id = $uid;
            $UserInfo->type = $member_type;
            $UserInfo->apply_time = CURTIME;
            $UserInfo->status = 0;
            $UserInfo->certificate_no = !$member_type ? $credit_no : $ent_certificate_no;
            $UserInfo->phone = !$member_type ? $mobile : '';
            $UserInfo->province_id = $member_type ? $province_id : 0;
            $UserInfo->city_id = $member_type ? $city_id : 0;
            $UserInfo->district_id = $member_type ? $district_id : 0;
            $UserInfo->town_id = $member_type ? $town_id : 0;
            $UserInfo->address = $address;
            $UserInfo->province_name = M\AreasFull::getAreasNametoid($UserInfo->province_id);
            $UserInfo->city_name = M\AreasFull::getAreasNametoid($UserInfo->city_id);
            $UserInfo->district_name = M\AreasFull::getAreasNametoid($UserInfo->district_id);
            $UserInfo->town_name = M\AreasFull::getAreasNametoid($UserInfo->town_id);
            $UserInfo->credit_type = self::USER_TYPE_IF;
            $UserInfo->credit_no = $this->getMemberNo($uid, self::USER_TYPE_IF);
            $UserInfo->company_name = $ent_company_name;
            $UserInfo->enterprise_legal_person = $ent_erprise_legal_person;
            $UserInfo->add_time = CURTIME;
            $UserInfo->last_update_time = CURTIME;
            $UserInfo->register_no = $ent_register_no;
            $UserInfo->name = $name;
            //服务工程师
            if(!$UserInfo->save()){
               throw new \Exception("添加用户信息失败");
            }
            $credit_id = $UserInfo->credit_id;
            if($lwtt_phone){
                $engineerinfo = M\Users::findFirstByusername($lwtt_phone);
                if ($engineerinfo)
                {  
                   $se_id=M\UserInfo::getlwttinfo($engineerinfo->id);
                   $UserInfo->se_id=$se_id;
                   $UserInfo->se_mobile=$lwtt_phone;
                   $UserInfo->mobile_type=2;
                   if(!$UserInfo->save()) {
                       throw new \Exception("添加关系表失败");
                   }
                }
            }
            // $credit_id = 3;
            /* 联系人 */
            $UserContact->user_id = $uid;
            $UserContact->name = $ent_contact_name;
            $UserContact->phone = $ent_contact_phone;
            $UserContact->fax = $ent_contact_fax;
            $UserContact->add_time = CURTIME;
            $UserContact->last_update_time = CURTIME;
            $UserContact->credit_id = $credit_id;
            if(!$UserContact->save()){
                throw new \Exception("添加联系人失败");
            }
            /* 农场信息 */
            $UserFarm->user_id = $uid;
            $UserFarm->farm_name = !$member_type ? $farm_name : $ent_farm_name;
            $UserFarm->province_id = !$member_type ? $user_province_id : $ent_province_id;
            $UserFarm->city_id = !$member_type ? $user_city_id : $ent_city_id;
            $UserFarm->district_id = !$member_type ? $user_district_id : $ent_district_id;
            $UserFarm->town_id = !$member_type ? $user_town_id : $ent_town_id;
            $UserFarm->village_id = !$member_type ? $user_village_id : $ent_village_id;
            $UserFarm->province_name = M\AreasFull::getAreasNametoid($UserFarm->province_id);
            $UserFarm->city_name = M\AreasFull::getAreasNametoid($UserFarm->city_id);
            $UserFarm->district_name = M\AreasFull::getAreasNametoid($UserFarm->district_id);
            $UserFarm->town_name = M\AreasFull::getAreasNametoid($UserFarm->town_id);
            $UserFarm->village_name = M\AreasFull::getAreasNametoid($UserFarm->village_id);
            $UserFarm->address = !$member_type ? $user_address : $ent_address;
            $UserFarm->farm_area = !$member_type ? $farm_areas : $ent_farm_area;
            $UserFarm->source = !$member_type ? $source : $ent_source;
            
            $UserFarm->describe = !$member_type ? $user_describe : $ent_describe;
            $UserFarm->add_time = CURTIME;
            $UserFarm->last_update_time = CURTIME;
            $UserFarm->credit_id = $credit_id;
            $stime = !$member_type ? $user_stime : $ent_stime;
            $etime = !$member_type ? $user_etime : $ent_etime;
            list($start_year, $start_month ) = explode('-', $stime);
            list($ent_year, $ent_month ) = explode('-', $etime);

            $UserFarm->year = $ent_year;
            $UserFarm->month = $ent_month;
            $UserFarm->start_year = $start_year;
            $UserFarm->start_month = $start_month;
            if(!$UserFarm->save()){
                throw new \Exception("添加农场信息失败");
            }
            /* 保存用户银行卡信息 */
            $UserBank->user_id = $uid;
            $bArea[] = M\AreasFull::getAreasNametoid(!$member_type ? $bank_province_id : $ent_bank_province_id);
            $bArea[] = M\AreasFull::getAreasNametoid(!$member_type ? $bank_city_id : $ent_bank_city_id);
            $bArea[] = M\AreasFull::getAreasNametoid(!$member_type ? $bank_district_id : $ent_bank_district_id);
            $UserBank->bank_name = !$member_type ? $user_bank_name : $ent_bank_name;
            $UserBank->bank_address = $bArea ? join(',', $bArea) : '';
            $UserBank->bank_account = !$member_type ? $bank_account : $ent_bank_account;
            $UserBank->bank_cardno = !$member_type ? $bank_cardno : $ent_bank_cardno;
            $bankcard_picture = M\TmpFile::findFirst(" sid = '{$sid}' AND type = '29' ");
            if($bankcard_picture){
                $UserBank->bankcard_picture = $bankcard_picture ? $bankcard_picture->file_path : '';
            }
            
            $idcard_picture = M\TmpFile::findFirst(" sid = '{$sid}' AND type = '31' ");
            if($idcard_picture) {
                $UserBank->idcard_picture = $idcard_picture ? $idcard_picture->file_path : '';
            }
            $person_picture = M\TmpFile::findFirst(" sid = '{$sid}' AND type = '30' ");
            if($person_picture){
                $UserBank->person_picture = $person_picture ? $person_picture->file_path : '';
            }
            /* 营业执照 */
            $identity_picture_lic = M\TmpFile::findFirst(" sid = '{$sid}' AND type = '33' ");
            if($identity_picture_lic){
                $UserBank->identity_picture_lic = $identity_picture_lic ? $identity_picture_lic->file_path : '';
            }
            /* 身份证背面照 */
            $idcard_picture_back = M\TmpFile::findFirst(" sid = '{$sid}' AND type = '34' ");
            if($idcard_picture_back) {
                $UserBank->idcard_picture_back = $idcard_picture_back ? $idcard_picture_back->file_path : '';
            }
            $UserBank->add_time = CURTIME;
            $UserBank->last_update_time = CURTIME;
            $UserBank->credit_id = $credit_id;
            if(!$UserBank->save()){
                throw new \Exception("添加认证信息失败");
            }
            /* 保存农作物信息 */
            if ($category_name_text && $category_name_text!=1) 
            {
                $category_name_text = array_unique(explode(',', $category_name_text));
                $DelUserFarmCrops = M\UserFarmCrops::find("credit_id ={$credit_id}");
                if($DelUserFarmCrops){
                    foreach($DelUserFarmCrops AS $ufckey => $ufcobj){
                        $ufcobj->delete();
                    }
                }
                foreach ($category_name_text as $key => $val) 
                {
                    /* 检测分类是否存在 */
                    $cid = intval($val);
                    
                    if (!$cate = M\Category::findFirst(" id = '{$cid}' AND parent_id > 0 ")) continue;
                    // $UserFarmCrops   = M\UserFarmCrops::findFirst(" user_id ='{$uid}' and credit_id='{$credit_id}'");
                    // if(!$UserFarmCrops) {
                        $UserFarmCrops = new M\UserFarmCrops();
                    //}
                    $UserFarmCrops->user_id = $uid;
                    $UserFarmCrops->category_name = $cate->title;
                    $UserFarmCrops->add_time = CURTIME;
                    $UserFarmCrops->category_id = intval($cate->id);
                    $UserFarmCrops->credit_id = $credit_id;
                    $UserFarmCrops->save();
                }
            }
            /* 农场图片 */
            
            // $picture_path = M\TmpFile::findFirst(" sid = '{$sid}' AND type = '32' ");
            /* 上传农场照片 */
            $picture_path_arr = M\TmpFile::find(" sid = '{$sid}' AND type = '32' ");
            if($picture_path_arr){
                foreach ($picture_path_arr as $picture_path) 
                {
                    // $UserFarmPicture = M\UserFarmPicture::findFirst(" user_id ='{$uid}' and credit_id='{$credit_id}' and type=0 ");
                    // if(!$UserFarmPicture) {
                        $UserFarmPicture = new M\UserFarmPicture();
                    //}
                    $UserFarmPicture->user_id = $uid;
                    $UserFarmPicture->picture_path = $picture_path ? $picture_path->file_path : '';
                    $UserFarmPicture->add_time = CURTIME;
                    $UserFarmPicture->credit_id = $credit_id;
                    $UserFarmPicture->type = 0 ;
                    $UserFarmPicture->save();
                }
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
                    $UserFarmPicture->user_id = $uid;
                    $UserFarmPicture->picture_path = $picture_path ? $picture_path->file_path : '';
                    $UserFarmPicture->add_time = CURTIME;
                    $UserFarmPicture->credit_id = $credit_id;
                    $UserFarmPicture->type = 1 ;
                    $UserFarmPicture->save();

                }
            }

            //插入日志
            $userlog = new M\UserLog();
            $userlog->user_id = $uid;
            $userlog->credit_id = $credit_id;
            $userlog->operate_user_no =$UserInfo->credit_no;
            $userlog->operate_user_name = $UserInfo->name;
            $userlog->operate_time = CURTIME;
            $userlog->status = 0;
            if($isedit==1){
                $userlog->demo = "重提交申请";
            }else{
                $userlog->demo = "已提交申请";
            }
            $userlog->add_time = time();
            $userlog->save();
            if(!$userlog->save()){
                throw new \Exception("添加日志失败");
            }
            $this->db->commit();

        }
        catch(\Exception $e) 
        {
            $this->db->rollback();
            // print_R($e->getMessages());
        }
        M\TmpFile::clearOld($sid);
       $this->response->redirect("userfarm/detail/{$credit_id}")->sendHeaders();
    }
    /**
     * 验证村站服务区域
     * @return [type] [description]
     */
    
    public function checkAreaVillageAction() 
    {
        $user_area_town_id = $this->request->getPost('user_area_town_id', 'int', 0);
        $ent_area_town_id = $this->request->getPost('ent_area_town_id', 'int', 0);
        $tid = 0;
        
        if ($user_area_town_id) $tid = $user_area_town_id;
        
        if ($ent_area_town_id) $tid = $ent_area_town_id;
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
        $credit_type = self::USER_TYPE_VS;
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
    
    public function checkUserFarmAreaAction($aid = 0) 
    {
        $uid = $this->getUserID();
        $user_area_district_id = $this->request->getPost('user_area_district_id', 'int', 0);
        $ent_area_district_id = $this->request->getPost('ent_area_district_id', 'int', 0);
        $tid = 0;
        
        if ($user_area_district_id) $tid = $user_area_district_id;
        
        if ($ent_area_district_id) $tid = $ent_area_district_id;
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
        $credit_type = self::USER_TYPE_HC;
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
    
    private function getMemberNo($uid = 0, $type = 0) 
    {
        $no = '';
        
        switch ($type) 
        {
        case self::USER_TYPE_HC:
            $no = 'HC' . $uid . L\Func::random(3, 1);
            break;

        case self::USER_TYPE_VS:
            $no = 'VS' . $uid . L\Func::random(3, 1);
            break;

        case self::USER_TYPE_IF:
            $no = 'IF' . $uid . L\Func::random(3, 1);
            break;

        case self::USER_TYPE_PE:
            $no = 'PE' . $uid . L\Func::random(3, 1);
            break;
        }
        return $no;
    }
    /**
     * 检测用户申请类型
     * @param  [type] $type 类型
     * @return boolean
     */
    
    private function checkapplyFor($type = 0, $membertype = 0) 
    {
        $uid = $this->getUserID();
        $where = " 1 ";
        
        if (!$membertype) 
        {
            $where.= "   AND status in(0,1,3) ";
        }
        $info = M\UserInfo::count(" {$where} AND (user_id = '{$uid}' AND credit_type = '{$type}') ");
        // var_dump(" {$where} AND (user_id = '{$uid}' AND credit_type = '{$type}'  ) ");
        // exit;
        return $membertype ? ($info - 1) : $info;
    }
    /**
     * 检测用户是否已申请
     * @param  [type] $type 类型
     * @return boolean
     */
    
    private function checkapply($type = 0) 
    {
        $uid    = $this->getUserID();
        $info   = M\UserInfo::count(" status!=4 AND (user_id = '{$uid}' AND credit_type = '{$type}') ");
        if($info) {
            return $info;
        } else {
            return 0;
        }
    }
    /**
     * 检测用户是否有身份证正在申请中
     * @param  [type] $type [description]
     * @return [type]       [description]
     */
    
    private function checkapplication($type = 0) 
    {
        $uid = $this->getUserID();
        $info = M\UserInfo::count(" (user_id = '{$uid}'  AND status =  '0' ) ");
        return $info;
    }
    /**
     * 检测身份是否被取消
     * @param  integer $type 类型
     * @return [type]        [description]
     */
    
    private function certcancel($type = 0) 
    {
        $uid = $this->getUserID();
        $info = M\UserInfo::count(" (user_id = '{$uid}'  AND credit_type = '{$type}' AND status =  '3' ) ");
        return $info;
    }
    
    
      /**
     * 修改用户申请
     * 
     */
    public function editcountyAction($credit_id) 
    {
        if (!$credit_id) 
        {
            $this->flash->error("用户信息不存在!");
            return $this->dispatcher->forward(array(
                "controller" => "userfarm",
                "action" => "index",
            ));
        }
        $sid = $this->session->getId();
        $uid = $this->getUserID();
        //$credit_id = 93;
        //$uid = 540745;
        $userinfo = M\UserInfo::findFirst("user_id = {$uid} and credit_id = {$credit_id}");
        if (!$userinfo) {
            $this->flash->error("用户信息不存在!");
            return $this->dispatcher->forward(array(
                "controller" => "userfarm",
                "action" => "index",
            ));
        }
               
        $userfarm = M\UserFarm::findFirst("user_id = {$uid} and credit_id = {$credit_id}");
        $userfarmpicture = M\UserFarmPicture::find("user_id = {$uid} and credit_id = {$credit_id}");
        $userbank = M\UserBank::findFirst("user_id = {$uid} and credit_id = {$credit_id}");
        $usercontact = M\UserContact::findFirst("user_id = {$uid} and credit_id = {$credit_id}");
        //var_dump(L\Areas::ldData($userinfo->town_id));exit;
        if ($userinfo->province_name) {
            //$infoarea = "'" . $userinfo->province_name . "','" . $userinfo->city_name . "','" . $userinfo->district_name . "','" . $userinfo->town_name . "'";
            $this->view->curAreas   = $userinfo->town_id ? L\Areas::ldData($userinfo->town_id,4) : '';
        }

        if ($userbank && !empty($userbank->bank_address)) {
            $area = explode(",", $userbank->bank_address);
            $areainfo = "'" . $area['0'] . "','" . $area['1'] . "','" . $area['2'] . "'";
            $this->view->areainfo =$areainfo;
        }
        $this->view->userinfo = $userinfo;
        $this->view->userfarm = $userfarm;
        $this->view->userfarmpicture = $userfarmpicture;
        $this->view->userbank = $userbank;
        $this->view->usercontact = $usercontact;
        $this->view->credit_id = $credit_id;
        /*  清除 */
        M\TmpFile::clearOld($sid);
        $this->view->cateList = $cateList = M\Category::getTopCaetList();
        $this->view->bankList = M\Bank::getBankList();
        $this->view->imgtype = self::USER_PICTURE_PATH;
        $this->view->sid = $sid;
        $this->view->year = date('Y');
        $this->view->maxyear = 100;
        $this->view->title = '个人中心-县域服务中心申请';
    }   




    /**
     * 下载doc模版
     * @return [type] [description]
     */
    public function docAction () {
        $filename = PUBLIC_PATH . '/mdg/云农场 IF协议.doc'; 
        //文件的类型 
        header('Content-type: application/doc'); 
        //下载显示的名字 
        header('Content-Disposition: attachment; filename="云农场 IF协议.doc"'); 
        readfile("$filename"); 
        exit(); 
    }
    public function deltmpfileAction(){
        $rs = array(
            'state' => true,
            'msg' => '删除成功！'
        );
        $id = $this->request->get('id', 'int', 0);
        $tmpFile = M\UserFarmPicture::findFirstByid($id);
        
        if ($tmpFile) 
        {
            @unlink($tmpFile->file_path);
            $tmpFile->delete();
        }
        die(json_encode($rs));
    }
    
    /**
     * 检查服务工程师
     */
    public function checkEngineerAction() 
    {
        $seusername = $this->request->getPost('seusername', 'string', '');
        $ent_seusername = $this->request->getPost('ent_seusername', 'string', '');
        
        $senameid = $this->request->getPost('senameid', 'string', '');
        $seid = $this->request->getPost('seid', 'string', '');
        
        $userfarm = array(
            'ok' => ''
        );
        
        $tag = false;
        $phone = $seusername ? $seusername : $ent_seusername;
        if ($senameid || $seid)
        {
            $tag = true;
            $phone = $senameid ? $senameid : $seid;
        }
        
        if (!$phone || !L\Validator::validate_is_mobile($phone))
        {
            $userfarm = array(
                'error' => '手机号格式不正确'
            );
            exit(json_encode($userfarm));
        }
            
        $result = M\EngineerManager::getEngineerInfo($phone, $tag);
            
        if (!$result) {
            $userfarm = array(
                'error' => '该手机号当前还未成为工程师，禁止使用'
            );
        }
        
        exit(json_encode($userfarm));
    }

    /**
     * 检查服务工程师
     */
    public function checklwttAction() 
    {
        $lwtt_phone = $this->request->getPost('lwtt_phone', 'string', '');
        $userfarm = array(
            'ok' => ''
        );        
        if (!$lwtt_phone || !L\Validator::validate_is_mobile($lwtt_phone))
        {
            $userfarm = array(
                'error' => '手机号格式不正确'
            );
            exit(json_encode($userfarm));
        }
        $user=M\Users::findFirstByusername($lwtt_phone);
        if(!$user){
            $userfarm = array(
                'error' => '该手机号当前还未开通产地服务站账号，禁止使用'
            );
            exit(json_encode($userfarm));
        }
        $userinfo=M\UserInfo::findFirst("user_id={$user->id} and status=1 and credit_type=8 ");
        if($userinfo){
            $userfarm = array(
                'error' => '该手机号当前还未开通产地服务站账号，禁止使用'
            );
            exit(json_encode($userfarm));
        }   
        $userinfo=M\UserInfo::findFirst("user_id={$user->id} and status=1 and credit_type=32 ");
        if(!$userinfo){
            $userfarm = array(
                'error' => '该手机号当前还未开通产地服务站账号，禁止使用'
            );
            exit(json_encode($userfarm));
        }   
        exit(json_encode($userfarm));
    }


}
?>