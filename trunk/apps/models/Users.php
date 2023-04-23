<?php
namespace Mdg\Models;
use Lib\Member as Member, Lib\Auth as Auth, Lib\Utils as Utils;
use Lib\Pages as Pages;
use Mdg\Models\UsersExt as Ext;
use Mdg\Models as M;
use Mdg\Models\Sell as Sell;
class Users extends Base
{
    static $_user_type = array(
        1 => '种植户/养殖户（请种植户，养殖户以及农业合作社经营者选此项）',
        2 => '采购商（请采购商选此项）',
    );
    /**
     * 用户类型
     * @var array
     */
    static $_user_type1 = array(
        1 => '供应商',
        2 => '采购商',
    );
    static $_credit_type = array(
        8=>'可信农场',
        4=>'村级服务站',
        2=>'县域服务站',
        16=>'采购商',
        32=>'产地服务站',
    );
    static $_credit_id = array(
        2=>'hc',
        4=>'vs',
        8=>'if',
        16=>'pe'
    );
    // 发布平台
    static $_plat = array(
        '0'=> '全部',
        '1'=> 'pc',
        '2'=> 'app',
        '3'=> '自定义'
    );
    /**  
     *
     * @var integer
     */
    
    public $id;
    /**
     * 用户名
     * @var string
     */
    
    public $username = '';
    /**
     * 密码
     * @var string
     */
    
    public $password = '';
    /**
     * 注册时间
     * @var integer
     */
    
    public $regtime = 0;
    /**
     * 最后登录时间
     * @var integer
     */
    
    public $lastlogintime = 0;
    /**
     * 注册IP
     * @var string
     */
    
    public $regip;
    /**
     * 用户类型
     * @var integer
     */
    
    public $usertype = 1;
    /**
     * 地区
     * @var integer
     */
    
    public $areas = 0;
    /**
     * 登录次数
     * @var integer
     */
    
    public $logincount = 0;
    /**
     * 最后登录IP
     * @var string
     */
    
    public $lastloginip;
    /**
     * 密钥
     * @var string
     */
    
    public $salt = '';
    
    public $member_type = 1;
    
    public $province_id=0;
    public $city_id=0;
    public $district_id=0;
    public $town_id=0;
    public $village_id=0;
    public $credit_id=0;
    public $is_broker=0;


    public function initialize() 
    {
        $this->belongsTo('id', 'Mdg\Models\UsersExt', 'uid', array(
            'alias' => 'ext'
        ));
    }
    
    public function beforeCreate() 
    {
    }
    
    public function beforeSave() 
    {
    }
    
    public function encodePwd($pwd, $salt) 
    {
        $this->salt = $salt;
        $this->password = md5(md5($pwd) . $salt);
    }
    static function validateLogin($mobile = '', $pwd = '') 
    {
        $member = new member();
        $user = $member->validateLogin($mobile, $pwd);
        $users = self::findFirstByusername($mobile);
        if($users&&$user&&$user["user_id"]!=$users->id){
            $users->delete();
        }
        if ($user['user_id']) 
        {   
            return self::syncUser($user, $pwd);
        }
        return $user;
    }
    static function checkLogin($mobile = '', $pwd = '123456') 
    {
        $user = self::findFirstByusername($mobile);
        
        if (!$user) 
        {
            return self::syncvillage($mobile, $pwd);
        }
        return $user;
    }
    static 
    public function changePWD($mobile, $new) 
    {
        $member = new Member();
        $result = $member->changePWD($mobile, $new, '', false);
        
        if ($result) 
        {
            $user = self::findFirstByusername($mobile);
            
            if ($user) 
            {
                $user->encodePwd($new, $user->salt);
                $user->save();
            }
            else
            {
                $salt = Auth::random(6, 1);
                $tmp = $member->getMember($mobile);
                $user = new self();
                $user->id = $tmp['user_id'];
                $user->username = $mobile;
                $user->usertype = 0;
                $user->regtime = time();
                $user->lastlogintime = time();
                $user->encodePwd($new, $salt);

                $user->save();
            }
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * 同步用户
     * @param  [type] $user [description]
     * @param  string $pwd  [description]
     * @return [type]       [description]
     */
    static function syncUser($user, $pwd = '') 
    {
        

        $member = new Member();
        $users = self::findFirstByusername($user['user_name']);
        if ($users && $users->id != $user['user_id']) 
        {
            $ext = Ext::findFirstByuid($user['user_id']);
            
            if ($ext) 
            {
                $ext->delete();
            }
            $users->delete();
        }
        elseif (!$users) 
        {
            $salt = Auth::random(6, 1);
            $tmp = $member->getMember($user['user_name']);
            $users = new self();
            $users->id = $user['user_id'];
            $users->username = $user['user_name'];
            $users->usertype = 1;
            $users->regtime = time();
            $users->regip = Utils::getIP();
            $users->lastlogintime = time();
            $users->member_type = 1 ;
            $users->encodePwd($pwd, $salt);
            $users->save();
            $ext = new Ext();
            $ext->uid = $user['user_id'];
            $ext->save();
        }
        $users->lastlogintime = time();
        $users->logincount+= 1;
        $users->lastloginip = Utils::getIP();
        $users->save();
        return $users;
    }
    static function syncvillage($user, $pwd = '') 
    {
        $member = new Member();
        $salt = Auth::random(6, 1);
        $tmp = $member->getMember($user);
        $users = new self();
        $users->id = $tmp["user_id"];
        $users->username = $tmp["user_name"];
        $users->usertype = 1;
        $users->regtime = time();
        $users->regip = Utils::getIP();
        $users->lastlogintime = time();
        $users->password = md5($tmp["password"] . $salt);
        $users->logincount+= 1;
        $users->lastloginip = Utils::getIP();
        $users->member_type = 1 ;
        $users->salt = $salt;
        $users->save();
        $ext = new Ext();
        $ext->uid = $tmp['user_id'];
        $ext->name = $tmp['msn'];
        $ext->save();
        return $users;
    }
    /**
     * 根据条件搜索
     * @param  array  $arr  搜索条件
     * @param  string  $type  类型 1为良品库查询 2为次品库查询
     * @return array
     */
    static 
    public function conditions($arr) 
    {
        $options = array(
            'regtime' => '',
            'areas' => '',
            'usertype' => '',
            'stime' => '',
            'etime' => '',
            'username' => '',
            'province' => '',
            'city' => ''
        );
        $opt = array_merge($options, $arr);
        $where = '1=1';
        
        if (is_array($arr)) 
        {
            empty(isset($arr['stime']) && $arr['etime']) ? : $where.= " and regtime between " . strtotime($arr['stime']) . " and " . strtotime($arr['etime']) . "";
            empty($arr['usertype']) ? : $where.= " and usertype =" . $arr["usertype"];
            empty($arr['username']) ? : $where.= " and username like '%" . $arr["username"] . "%' ";
        }
        return $where;
    }
    /**
     * 根据用户Id 获取用户手机号
     * @param  integer $uid 用户id
     * @return string
     */
    
    public static function getUsersName($uid = 0) 
    {
        $data = self::findFirst(" id ='{$uid}'");
        
        if (!$data) 
        {
            return '';
        }
        return $data->username;
    }
    /**
     * 根据id 获取丰收汇用户信息
     * @param  integer $uid 用户id
     * @return array
     */
    
    public static function getFshUsers($uid = 0) 
    {
        $data = self::findFirst(" id ='{$uid}'");
        return $data ? $data : array();
    }
    
    public static function getCorporateUsers($where = ' 1=1', $p = 1, $page_size = 10,$credit_type = 1) 
    {   

        $total = M\UserInfo::count($where);
        $offst = intval(($p - 1) * $page_size);

        $data = M\UserInfo::find($where . "  ORDER BY add_time DESC limit {$offst} , {$page_size} ")->toArray();
        if($data){
            foreach ($data as $key => $value) 
            {
                $data[$key]['area'] = array();
                $data[$key]['farm'] = array();
                $data[$key]['userfarm'] = array();
                $data[$key]['credit_farm'] = array();
                $userfarm=M\UserFarm::findFirstBycredit_id($value['credit_id']);
                if($userfarm) $data[$key]['userfarm'] = $userfarm->toArray();
                $userarea = M\UserArea::findFirstBycredit_id($value['credit_id']);
                if($userarea) $data[$key]['area'] = $userarea->toArray();
                $userfarm=M\UserFarmCrops::find("credit_id = {$value['credit_id']}");
                if($userfarm) $data[$key]['farm'] = $userfarm->toArray();
                if($value['credit_type']== 32){
                    $credit_farm = M\UserInfo::count("credit_type = '8' and mobile_type = '2' and se_id = {$value['credit_id']} and status = '1'");
                    if($credit_farm) $data[$key]['credit_farm'] = $credit_farm;
                }    
                $data[$key]['info'] = $data;
            }
        }else{
            $data = array();
        }
        
        $pages['total_pages'] = ceil($total / $page_size);
        $pages['current'] = $p;
        $pages['total'] = $total;
        $pages = new Pages($pages);
        $datas['total'] = $total;
        $datas['items'] = $data;
        $datas['start'] = $offst;
        $datas['pages'] = $pages->show(3);
        return $datas;
    }
    
    public static function getinfowhere($arr = array(),$where = array(),$credit_type = 0) 
    {       
            $where = array();
            $where[] = " credit_type={$credit_type}";
            $userwhere = array();
            //用户HC,IF,PE,IS状态
            if(isset($arr['member_type']) && $arr['member_type']){
                $where[] = " member_type&".$arr['member_type']."=".$arr['member_type']."";
                
            }

            //用户ID
            if (isset($arr['user_id']) && $arr['user_id']) 
            {
                $where[] = " user_id = {$arr['user_id']}";
            }
            //公司名称
            if (isset($arr['ext_name']) && $arr['ext_name'])
            {
                $where[] = " company_name = '{$arr['ext_name']}' or  credit_type&".$credit_type."=".$credit_type." and name = '{$arr['ext_name']}'";
            }
            if (isset($arr['start_pid']) && $arr['start_pid'])
            {
                $where[] = " province_id = {$arr['start_pid']} ";
                $start_areas[] = M\AreasFull::getAreasNametoid($arr['start_pid']);
            }
            
            if (isset($arr['start_cid']) && $arr['start_cid'])
            {
                $where[] = " city_id = {$arr['start_cid']} ";
                $start_areas[] = M\AreasFull::getAreasNametoid($arr['start_cid']);
            }
            
            if (isset($arr['start_did']) && $arr['start_did'])
            {
                $where[] = " district_id = {$arr['start_did']}";
                $start_areas[] = M\AreasFull::getAreasNametoid($arr['start_did']);
            }
            if(isset($arr['start_eid']) && $arr['start_eid']){
                $where[] = "town_id = {$arr['start_eid']}";
            }
            //用户状态
            if (isset($arr['status']) && $arr['status'])
            {
                   
                if (isset($arr['status'])  && $arr['status']== '99')
                {

                    $where[] = " status = 0";
               
                }
                else 
                {
                  
                    $where[] = " status = {$arr['status']}";
                }
            }
            //用户类型
            if (isset($arr['type']) && $arr['type'])
            {
               
                if (isset($arr['type']) && $arr['type']== '99')
                {

                    $where[] = " type = 0";
                }
                else
                {
                    $where[] = " type = 1";
                }
            }

            //开始时间
            if (isset($arr['expire_time']) && isset($arr['expire_time'])&&$arr['expire_time']!='')
            {

                $expire_time = strtotime($arr['expire_time'].'0:00:00');
                $where[] = " add_time >={$expire_time}";
            }
            //结束时间
            if (isset($arr['expire_etime']) && $arr['expire_etime']&&$arr['expire_etime']!='')
            {
                $expire_etime = strtotime($arr['expire_etime'].'23:59:59');
                $where[] = " add_time <={$expire_etime}";
            }
            //身份ID
            if (isset($arr['credit_id']) && $arr['credit_id'])
            {
                $where[] = " credit_id = {$arr['credit_id']}";
            }
            //用户名
            if (isset($arr['username']) && $arr['username'])
            {
                $userwhere[] = " username = {$arr['username']}";
            }
            
            if (isset($arr['start_pida']) && $arr['start_pida'])
            {
                $areawhere[] = " province_id = {$arr['start_pida']}";
                $start_areasa[] = M\AreasFull::getAreasNametoid($arr['start_pida']);
            }
            
            if (isset($arr['start_cida']) && $arr['start_cida'])
            {
                $areawhere[] = " city_id = {$arr['start_cida']}";
                $start_areasa[] = M\AreasFull::getAreasNametoid($arr['start_cida']);
            }
            
            if (isset($arr['start_dida']) && $arr['start_dida'])
            {
                $areawhere[] = " district_id = {$arr['start_dida']}";
                $start_areasa[] = M\AreasFull::getAreasNametoid($arr['start_dida']);
            }
            
            if (isset($arr['start_areas']) && $arr['start_areas']) 
            {
                $start_areas = join("','", $arr['start_areas']);
            }
            
            if (isset($arr['start_areasa']) && $arr['start_areasa']) 
            {
                $start_areasa = join("','", $arr['start_areasa']);
            }

            if(isset($arr['userareaa']) && $arr['userareaa'])
            {
                $userfarm[] = "farm_area >= {$arr['userareaa']}";
            }

            if(isset($arr['userareab']) && $arr['userareab'])
            {
                $userfarm[] = "farm_area <= {$arr['userareab']}";
            }
            //农场分类
            if(isset($arr['category_nameb']) && $arr['category_nameb'])
            {
                $user_farm_crops[] = " category_id = {$arr['category_nameb']}";
            }
            if(isset($arr['userfarm']) && $arr['userfarm']){
                $userfarm[] = " farm_name = '{$arr['userfarm']}'";
            }
            if($userwhere){
                $users=M\Users::find($userwhere)->toArray();
                if($users){
                    $users_id = array_Column($users, 'id');
                    $user_id=join(",",$users_id);
                    if($user_id){
                        $where[] = " user_id in ({$user_id})";
                    }else{
                        $where[] = " user_id = 0";
                    }
                }
            }

            //盟商手机号
            if(isset($arr['lwtt_phone'])&&$arr['lwtt_phone']){
                $user = M\Users::findFirstByusername($arr['lwtt_phone']);
                if($user){
                    $se_id=M\UserInfo::getlwttinfo($user->id);
                }else{
                    $se_id=1;
                }
                $where[] = " se_id = {$se_id}and mobile_type = '2' ";
            }
            // echo $where;die;
            if(isset($userfarm) && $userfarm)
            {
                $userfarm=implode(" AND ",$userfarm);
                $userfarm = M\UserFarm::find($userfarm)->toArray();
 
                if($userfarm){
                    $credit_id = array_Column($userfarm, 'credit_id');
                    $user_user_id = array_Column($userfarm, 'user_id');

                    $useruser_id=join(",",$user_user_id);
                    $credit_id = join(",", $credit_id);

                    $where[] = " credit_id in ({$credit_id}) and user_id in ({$useruser_id})";
                }else{
                    $where[] = "credit_id = 0 and user_id = 0";
                }
            }

            if(isset($user_farm_crops) && $user_farm_crops){
                $user_farm_crops = implode(" AND ",$user_farm_crops);
                $userfarmcrops=M\UserFarmCrops::find($user_farm_crops)->toArray();
                if($userfarmcrops){
                    $credit_id = array_Column($userfarmcrops, 'credit_id');
                    $user_user_id = array_Column($userfarmcrops, 'user_id');
                    $useruser_id=join(",",$user_user_id);
                    $credit_id = join(",", $credit_id);
                    $where[] = " credit_id in ({$credit_id}) and user_id in ({$useruser_id})";
                }else{
                    $where[] = "credit_id = 0 and user_id = 0";
                }
            }

            if (isset($areawhere)) 
            {
                $areawhere = implode(" AND ", $areawhere);
                $userarea = M\UserArea::find($areawhere)->toArray();
                
                if ($userarea) 
                {
                    $areauser_id = array_Column($userarea, 'user_id');
                    $areauser_id = join(",", $areauser_id);
                    $where[] = " user_id in({$areauser_id})";
                }
                else
                {
                    $where[] = " user_id = 0";
                }
            }
            if(isset($where)) return implode(' AND ', $where);
            else return "1=1";
            
    }

    
    /**
     * 获取用户申请类型
     * @param  integer $uid 用户id
     * @param  integer $de  type值
     * @return 
     */
    public static function getMemberType($uid=0 ,$de = 0) {
        $info = self::findFirst(array(" id = '{$uid}'"));
        return  $info->member_type + $de;
    }

    public static function getUserMobile($user_id=0){
        $users=self::findFirst("id = {$user_id}");
        if($users){
            return $users->username;
        }else{
            return '';
        }
    }

    public static function selectByusername ($username='') {
        $data = self::findFirstByusername ($username);
        
        return $data ? $data->id : 0;
    }
    /**
     * 根据手机号查询联系方式
     * @param  string $mobile 手机号
     * @return string
     */
    
    public static function selectMobile($mobile = '') 
    {
        $cond[] = " username = '{$mobile}' ";
        $cond['columns'] = " id , username";
        $data = self::find($cond)->toArray();
        $_userid = array_column($data, 'id');
        return $_userid ? join(',', $_userid) : '';
    }
     /**
     * 根据姓名查询联系方式
     * @param  string $mobile 手机号
     * @return string
     */
    
    public static function selectbyname($name = '') 
    {
        $cond[] = " name = '{$name}' ";
        $cond['columns'] = " uid ";
        $data = UsersExt::find($cond)->toArray();
        $_userid = array_column($data, 'uid');
        return $_userid ? join(',', $_userid) : '';
    }
    /**
    * 生成日志
    */
    public  static function log($reject,$userinfo,$text){
            $time = CURTIME;
            $userlog = M\UserLog::findFirst("user_id={$userinfo->user_id} AND credit_id = {$userinfo->credit_id} AND status={$userinfo->status} AND add_time = '{$time}'");
            if (!$userlog) $userlog = new M\UserLog();      
            $userlog->user_id = $userinfo->user_id;
            $userlog->credit_id = $userinfo->credit_id;
            $userlog->operate_user_no = $userinfo->credit_no;
            $userlog->operate_user_name = $userinfo->name;
            $userlog->operate_time = CURTIME;
            $userlog->status = $userinfo->status;
            $userlog->demo = $text.$reject;
            $userlog->add_time = time();
            if(!$userlog->save()){
                return false;
            };
            
            return true;  


    }
  

}
