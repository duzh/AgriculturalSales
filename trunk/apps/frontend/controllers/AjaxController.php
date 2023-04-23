<?php
namespace Mdg\Frontend\Controllers;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Mdg\Models\Category as Category;
use Mdg\Models\Sell as Sell;
use Mdg\Models\Areas as Areas;
use Mdg\Models\AreasFull as AreasFull;
use Lib\Func as Func;
use Mdg\Models AS M;
use Lib as L;

class AjaxController extends ControllerAjax
{
    /** ajax 商品分类 **/
    
    public function getcateAction() 
    {
        $pid = $this->request->get('parent_id', 'int', 0);
        
        if ($pid < '0') 
        {
            die(json_encode(''));
        }
        $cateList = Category::find(array(
            "parent_id = '{$pid}' and is_show=1 ",
            'order' => 'deeps desc'
        ))->toArray();
        
        if (empty($cateList)) 
        {
            $cateList = Category::find("id = '{$pid}' ")->toArray();
        }
        $rs = Func::parseLdData($cateList);
        
        die(json_encode($rs));
    }
    public function getlwttcateAction() 
    {
        $pid = $this->request->get('parent_id', 'int', 0);
        $user_id = $this->getUserID();
        $se_mobile=$this->getUserName();
        if(!$user_id||!$se_mobile){
            die(json_encode(''));
        }
        //已经整合的可信农场
        $user_info=M\UserInfo::getlwttlist($user_id,$se_mobile);
        
        //如果没有已整合的可信农场
        if(!$user_info['maxcategory_id']||!$user_info['cate_id']){
            $credit_id=M\UserInfo::getlwttinfo($user_id);
            if(!$credit_id){
                die(json_encode(''));
            }
            $crops  =M\UserFarmCrops::selectByuserFarm($user_id,$credit_id);
            if(empty($crops)){
                 die(json_encode(''));
            }
            $crops=L\Arrays::getCols($crops,"category_id",',');
            $cateparent_id=M\Category::find("id in ($crops)")->toArray();
            $user_info['maxcategory_id']=L\Arrays::getCols($cateparent_id,"parent_id",',');
            $user_info['cate_id']=$crops;
        }else{
            $credit_id=M\UserInfo::getlwttinfo($user_id);
            if($credit_id){
                $crops  =M\UserFarmCrops::selectByuserFarm($user_id,$credit_id);
                if(!empty($crops)){
                    $crops=L\Arrays::getCols($crops,"category_id",',');
                    $cateparent_id=M\Category::find("id in ($crops)")->toArray();
                    $user_info['maxcategory_id'].=','.L\Arrays::getCols($cateparent_id,"parent_id",',');
                    $user_info['cate_id'].=','.$crops;

                }
            }
        }
        if ($pid < '0') 
        {
            die(json_encode(''));
        }
        $where='1=1';
        if($pid==0){
            $where=" id in({$user_info['maxcategory_id']})";
        }
        if($pid>0){
            $where=" id in({$user_info['cate_id']})";
        }
        
        $cateList = Category::find( "parent_id = '{$pid}' and is_show=1 and {$where} order by deeps desc ")->toArray();
        if (empty($cateList)) 
        {
            $cateList = Category::find("id = '{$pid}' and {$where} ")->toArray();
        }
      
        $rs = Func::parseLdData($cateList);
        
        die(json_encode($rs));
    }

    // 主营产品
    
    public function getfarmcateAction() 
    {
        $uid = $this->getUserID();
        $pid = $this->request->get('parent_id', 'int', 0);
        
        if ($pid < '0') 
        {
            die(json_encode(''));
        }
        $cateList = Category::find(array(
            "parent_id = '{$pid}' and parent_id in (0,1,2,7,8,15,18,21,22,23,899,1294,1377,1378)",
            'order' => 'deeps desc'
        ))->toArray();
         
         if(empty($cateList)) 
         {
            $cateList = Sell::find(" uid = {$uid} AND category = '{$pid}' AND is_del = 0 and state=1")->toArray();
         }
        $rs = Func::parseLdData($cateList);
        
        die(json_encode($rs));
    }
    
    public function getareasAction() 
    {
        $pid = $this->request->get('parent_id', 'int', 0);
        
        if ($pid < '0') 
        {
            die(json_encode(''));
        }
        $areasList = AreasFull::find(array(
            "pid = '{$pid}'"
        ))->toArray();
        
        if (empty($areasList)) 
        {
            $areasList = AreasFull::find("id = '{$pid}'")->toArray();
        }
        $rs = Func::parseLect($areasList, 'id', 'name');
        die(json_encode($rs));
    }
    
    public function getareasfullAction() 
    {
        $pid = $this->request->get('parent_id', 'int', 0);
        
        if ($pid < '0') 
        {
            die(json_encode(''));
        }
        $areasList = AreasFull::find(array(
            " pid = '{$pid}'"
        ))->toArray();
        
        if (empty($areasList)) 
        {
            $areasList = AreasFull::find("id = '{$pid}'")->toArray();
        }
        $rs = Func::parseLdData($areasList, 'id', 'name');
        die(json_encode($rs));
    }
    
    public function getactiveareasfullAction() 
    {
        $pid = $this->request->get('parent_id', 'int', 1571);
        
        if ($pid < '0') 
        {
            die(json_encode(''));
        }
        $where = " pid = '{$pid}' ";
        
        if ($pid == 1564) 
        {
            $where.= " and id in (1566,1567,1571) ";
        }
        
        if ($pid == 1566) 
        {
            $where.= " and id!=22171 ";
        }
        
        if ($pid == 1567) 
        {
            $where.= " and id!=22183 ";
        }
        $areasList = AreasFull::find($where)->toArray();
        
        if (empty($areasList)) 
        {
            $areasList = AreasFull::find("id = '{$pid}'")->toArray();
        }
        $rs = Func::parseLdData($areasList, 'id', 'name');
        die(json_encode($rs));
    }
    /**
     * 服务站申请 负责区域
     * @return [type] [description]
     */
    
    public function getfullAddressAction() 
    {
        $pid = $this->request->get('parent_id', 'int', 0);
        
        if ($pid < '0') 
        {
            die(json_encode(''));
        }
        $pid = is_array($pid) ? $pid[0] : $pid;
        $areasList = AreasFull::find(array(
            " pid = '{$pid}'"
        ))->toArray();
        
        if (empty($areasList)) 
        {
            $areasList = AreasFull::find("id = '{$pid}'")->toArray();
        }
        $rs = Func::parseLdData($areasList, 'id', 'name');
        die(json_encode($rs));
    }
    /**
     *  负责区域
     * @return [type] [description]
     */
    
    public function getaddressAction() 
    {
        $pid = $this->request->get('parent_id', 'int', 0) [0];
        
        if ($pid < '0') 
        {
            die(json_encode(''));
        }
        $areasList = AreasFull::find(array(
            " pid = '{$pid}'"
        ))->toArray();
        
        if (empty($areasList)) 
        {
            $areasList = AreasFull::find("id = '{$pid}'")->toArray();
        }
        $rs = Func::parseLdData($areasList, 'id', 'name');
        die(json_encode($rs));
    }
    /**
     *  供应大厅分类
     * @return [type] [description]
     */
    
       public function showcateAction() 
    {



        $id = $this->request->get('id', 'int', 0);
        // $args = array(
        //     'a',
        //     'mc',
        //     'c',
        //     'keyword',
        //     'u',
        //     'first',
        //     'supply'
        // );
        // print_r($_REQUEST);die;
        $mc = $this->request->get('mc','int',0);
        $first = $this->request->get('f','string','');
        $c = $this->request->get('c','int',0);
        $p = $this->request->get('p','int',0);
        $a = $this->request->get('a','int',0);
        $keyword = $this->request->get('keyword', 'string', '');
        $arr['mc'] = $mc;
        $arr['f'] = $first;
        $arr['c'] = $c;
        $arr['p'] = $p;
        $arr['a'] = $a;

        // $query = trim($this->request->get('query', 'string', '') , '?');
        $controller = $this->request->get('cont', 'string', 'sell');
        $type = $this->request->get('type', 'int', 0);

        $cfirst = '';
        
        if ($type == 3) 
        {
            $ids = $id;
        }
        else
        {
            $ids = '';
        }

        // $first = isset($url['first']) ? $url['first'] : '';

        // $c = isset($url['c']) ? $url['c'] : '';
        // $mc = isset($url['mc']) ? $url['mc'] : '';
        // unset($url['c']);
        // $url = empty($url) ? '' : http_build_query($url) . '&';
        $url = "/{$controller}";
        
        if ($first) 
        {
            $cfirst = " and abbreviation = '{$first}' ";
        }
        else
        {
            $cfirst = ' and 1';
        }
        if($mc) {
            $cfirst .= " AND parent_id = '{$mc}'";
        }
        $parentData = Category::find("is_show = 1 {$cfirst}  and  parent_id!=0  order by deeps desc ");
        $info = Category::findFirstByid($id);
        $pid = $info ? $info->parent_id ? $info->parent_id : $info->id : 0;
        
        if ($type == 3) 
        {
            $info = Category::findFirstByid($pid);
            $pid = $info->parent_id;
            $id = $info->id;
        }
        $childData = $pid ? Category::find("parent_id = '{$pid}' and is_show = 1  {$cfirst} ") : false;
        $threeData = Category::find("parent_id = '{$id}' and is_show = 1 order by deeps desc ");
        $childcount = Category::count("is_show = 1 {$cfirst}");
        
        if ($type == 1) 
        {
            $threeData = array();
        }
   
        $this->view->firstStr = $first;
        $this->view->cateFirst = Category::getCateByFirst($this->db, $pid);
        $this->view->threeData = $threeData ? $threeData : array();;
        $this->view->childcount = $childcount ? $childcount : 0;
        $this->view->parentData = $parentData;
        $this->view->childData = $childData;
        $this->view->url = $url;
        $this->view->pid = $pid;
        $this->view->arr = $arr;
        $this->view->c = $c;
        $this->view->keyword = $keyword;
        $this->view->type = $type;
        $this->view->id = $info ? $info->id : 0;
        $this->view->threeid = $ids;
        $this->view->is_ajax = true;
    }
    
    public function showsupplyAction() 
    {
        $id = $this->request->get('id', 'int', 0);
        $args = array(
            'a',
            'mc',
            'c',
            'keyword',
            'u',
            'first',
            'supply'
        );
        $query = trim($this->request->get('query', 'string', '') , '?');
        $controller = $this->request->get('cont', 'string', 'sell');
        $type = $this->request->get('type', 'int', 0);
        $cfirst = '';
        
        if ($type == 3) 
        {
            $ids = $id;
        }
        else
        {
            $ids = '';
        }
        parse_str($query, $query);
        $url = array();
        
        foreach ($args as $v) 
        {
            
            if (isset($query[$v])) 
            {
                $url[$v] = $query[$v];
            }
        }
        $first = isset($url['first']) ? $url['first'] : '';
        $url = empty($url) ? '' : http_build_query($url) . '&';
        $url = "/{$controller}/index?" . $url;
        $parentData = Category::find('parent_id = 0 and is_show = 1 order by deeps desc ');
        $info = Category::findFirstByid($id);
        $pid = $info ? $info->parent_id ? $info->parent_id : $info->id : 0;
        
        if ($type == 3) 
        {
            $info = Category::findFirstByid($pid);
            $pid = $info->parent_id;
            $id = $info->id;
        }
        
        if ($first) 
        {
            $cfirst = " and abbreviation = '{$first}' ";
        }
        $childData = $pid ? Category::find("parent_id = '{$pid}' and is_show = 1  {$cfirst} ") : false;
        $threeData = Category::find("parent_id = '{$id}' and is_show = 1 order by deeps desc ");
        $childcount = Category::count("parent_id = '{$id}' and is_show = 1");
        
        if ($type == 1) 
        {
            $threeData = array();
        }
        $this->view->firstStr = $first;
        $this->view->cateFirst = Category::getCateByFirst($this->db, $pid);
        $this->view->threeData = $threeData ? $threeData : array();;
        $this->view->childcount = $childcount ? $childcount : 0;
        $this->view->parentData = $parentData;
        $this->view->childData = $childData;
        $this->view->url = $url;
        $this->view->pid = $pid;
        $this->view->type = $type;
        $this->view->id = $info ? $info->id : 0;
        $this->view->threeid = $ids;
        $this->view->is_ajax = true;
    }
    
     public function showareasAction() 
    {


        $id = $this->request->get('id', 'int', 0);
        // $args = array(
        //     'a',
        //     'mc',
        //     'c',
        //     'keyword',
        //     'u',
        //     'supply'
        // );
        // $query = trim($this->request->get('query', 'string', '') , '?');
        $controller = $this->request->get('cont', 'string', 'sell');
        $keyword = $this->request->get('keyword', 'string', '');
        // parse_str($query, $query);
        // $url = array();
        
        // foreach ($args as $v) 
        // {
            
        //     if (isset($query[$v])) 
        //     {
        //         $url[$v] = $query[$v];
        //     }
        // }
        // unset($url['a']);
        // $url = empty($url) ? '' : http_build_query($url) . '&';
        $url = "/{$controller}";
      
        $mc = $this->request->get('mc','int',0);
        $first = $this->request->get('f','string','');
        $c = $this->request->get('c','int',0);
        $p = $this->request->get('p','int',1);
        $a = $this->request->get('a','int',0);

        $arr['mc'] = $mc;
        $arr['f'] = $first;
        $arr['c'] = $c;
        $arr['p'] = $p;
        $arr['a'] = $a;

        $parentData = AreasFull::find('pid = 0 and is_show = 1');
        $info = AreasFull::findFirstByid($id);
        $pid = $info ? $info->pid ? $info->pid : $info->id : 0;
        $childData = $pid ? AreasFull::find("pid = '{$pid}' and is_show = 1") : false;
        $this->view->parentData = $parentData;
        $this->view->childData = $childData;
        $this->view->url = $url;
        $this->view->arr = $arr;
        $this->view->pid = $pid;
        $this->view->keyword = $keyword;
        $this->view->area_id = $id;
        $this->view->id = $info ? $info->id : 0;
        $this->view->is_ajax = true;
    }
    /**
     *
     * 首页中间数据加载
     * @return [type] [description]
     */
    
    public function indexcenterAction() 
    {
        /* 供应产品  蔬菜 */
        $sell = new M\Sell();
        $purchase = new M\Purchase();
        /* 蔬菜 */
        $vegcid = 1;
        $veg['sell'] = $sell->getRecoList($vegcid, 'home', 10);
        $veg['pur'] = $purchase->getRecList($vegcid, 'home', 10);
        $veg['cate'] = M\Category::threecate($vegcid, 14);
        
        foreach ($veg['sell'] as $key => $val) 
        {
            $veg['sell'][$key]['quantity'] = L\Func::conversion($val['quantity']);
            $veg['pur'][$key]['quantity'] = L\Func::conversion($veg['pur'][$key]['quantity']);
        }
        /* 水果 */
        $fruitid = 2;
        $fruit['sell'] = $sell->getRecoList($fruitid, 'home', 10);
        $fruit['pur'] = $purchase->getRecList($fruitid, 'home', 10);
        $fruit['cate'] = M\Category::threecate($fruitid, 14);
        
        foreach ($fruit['sell'] as $key => $val) 
        {
            $fruit['sell'][$key]['quantity'] = L\Func::conversion($val['quantity']);
            $fruit['pur'][$key]['quantity'] = L\Func::conversion($fruit['pur'][$key]['quantity']);
        }
        /* 粮油 */
        $grainid = 7;
        $grain['sell'] = $sell->getRecoList($grainid, 'home', 10);
        $grain['pur'] = $purchase->getRecList($grainid, 'home', 10);
        $grain['cate'] = M\Category::threecate($grainid, 14);
        
        foreach ($grain['sell'] as $key => $val) 
        {
            $grain['sell'][$key]['quantity'] = L\Func::conversion($val['quantity']);
            $grain['pur'][$key]['quantity'] = L\Func::conversion($grain['pur'][$key]['quantity']);
        }
        $this->view->vegcid = $vegcid;
        $this->view->grainid = $grainid;
        $this->view->fruitid = $fruitid;
        $this->view->goods_unit = M\Purchase::$_goods_unit;
        $this->view->is_ajax = 1;
        $this->view->veg = $veg;
        $this->view->fruit = $fruit;
        $this->view->grain = $grain;
        // $this->view->is_ajax = 1 ;
        //
        
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
    
    public function checkengphoneAction() 
    {
        $engphone = $this->request->getPost('engphone', 'string', '');
        
        if ($engphone) 
        {
            $client = L\Func::serviceApi();
            $data = $client->county_selectByEngineerMobile($engphone);
            if (!$data) 
            {
                echo ('工程师不存在');
                exit;
            }
            exit;
        }
    }
    /* 检测申请农场身份证 */
    
    public function checkIdCardAction() 
    {
        $idcard = $this->request->getPost('idcard', 'string', '130728199201051111');
        $cond[] = " certificate_no = '{$idcard}'";
        $msg = array(
            'ok' => ''
        );
        
        if (!M\UserInfo::count($cond)) 
        {
            exit(json_encode($msg));
        }
        $msg = array(
            'error' => '该身份证已有人使用'
        );
        $msg = json_encode($msg);
        exit($msg);
    }
    /**
     * 获取用户商友信息
     * @return [type] [description]
     */
    
    public function getUserPartnerAction() 
    {
        $this->view->is_ajax = 1;
        $user = $this->session->user;
        $uid = isset($user['id']) ? intval($user['id']) : 0;
        $page = $this->request->get('p', 'int', 1);
        $paytype = $this->request->getPost('paytype', 'int', 0);
        $mobile = $this->request->getPost('mobile', 'string', '');
        $cond[] = " user_id = '{$uid}' AND status = '0' ";
        
        if ($mobile) 
        {
            $cond[] = " partner_phone = '{$mobile}' ";
        }
        
        if (intval($paytype)) 
        {
            $cond[] = "pay_type = '{$paytype}'";
        }
        $cond = implode(' AND ', $cond);
        // var_dump($cond);
        /* 获取所有商友数据 */
        $UserPartner = M\UserPartner::getUserPartnerList($cond, $page, $psize = 5, 'ajax');

        $this->view->add_mobile = $mobile;
        $this->view->add_paytype = $paytype;
        $this->view->UserPartner = $UserPartner;
        $_pay_type = M\EntrustOrder::$_pay_type;
        $_pay_type[2] = '请选择';
        unset($_pay_type[0]);
        unset($_pay_type[6]);
        $this->view->_pay_type = $_pay_type;
    }
    /**
     * 新建卖家信息
     */
    
    public function addSellerAction() 
    {
        $this->view->is_ajax = 0;
        $bankList = M\Bank::getBankList();
        $this->view->bankList = $bankList;
        $this->view->title = '新建委托交易订单';
    }
    /**
     * 修改卖家信息
     * @return [type] [description]
     */
    
    public function editSellerAction() 
    {
        $mobile = $this->request->getPost('mobile', 'string', '18610983024');
        
        if (!$mobile) 
        {
            exit('信息错误');
        }
        $bankList = M\Bank::getBankList();
        $this->view->bankList = $bankList;
        $this->view->is_ajax = 1;
        $this->view->mobile = $mobile;
    }
    /**
     * 检测手机号是否存在
     * @return [type] [description]
     */
    
    public function checkUsernameAction() 
    {
        $username = $this->request->getPost('add_partner_phone', 'string', '');
        $this->view->is_ajax = 1;
        
        if (!$info = M\Users::findFirstByusername($username)) 
        {
            /* 注册用户信息 */
            $member = new L\Member();
            $password = '123456';
            $synuser = $member->register($username, $password);
            $synuser['user_id'] = $synuser['id'];
            /* 同步丰收汇用户信息 */
            
            if ($synuser) 
            {
                M\Users::syncUser($synuser, $password);
            }
        }
        $msg = array(
            'ok' => 1
        );
        exit(json_encode($msg));
    }
    /**
     * 检测是否为可信农场 已经获取详细信息
     * @return [type] [description]
     */
    
    public function getUserInfoAction() 
    {
        $mobile = $this->request->getPost('add_partner_phone', 'string', '');
        $send = $this->request->getPost('send', 'int', 0);
        $msg = array(
            'ok' => '',
            'state' => 0
        );
        /**/
        
        if (!$info = M\Users::findFirstByusername($mobile)) 
        {
            if($send) {
                /* 注册用户信息 */
                $member = new L\Member();
                $synuser = $member->getMember($mobile);
                $password = '123456';

                if (!$synuser) 
                {
                    $synuser = $member->register($mobile, $password);
                    $synuser['user_id'] = $synuser['id'];
                    //发送短信内容
                    $sms = new L\SMS();
                    $content = '欢迎来到丰收汇，自动注册密码为' . $password . '(如非本人操作请忽略！)';
                    $sms->send($mobile, $content);
                }
                //同步丰收汇用户信息
                
                if ($synuser) 
                {
                    $synuser['id'] = $synuser['user_id'];
                    M\Users::syncUser($synuser, $password);
                }
            }
        }

        $data['uname'] = isset($info->ext->name) ? $info->ext->name : '';
        $msg = array(
            'state' => 0,
            'ok' => '',
            'uname' => $data['uname']
        );
        exit(json_encode($msg));
    }
    /**
     * 检测商友是否存在
     * @return [type] [json]
     */
    
    public function checkUserPartnerAction($type=0) 
    {
        
        if($type){
            $mobile = $this->request->getPost('partner_phone', 'string', '');
        }else{
           $mobile = $this->request->getPost('add_partner_phone', 'string', ''); 
        }
        if(!$mobile){
            $msg = array(
                'error' => '参数错误'
            );
            exit(json_encode($msg));
        }
        $send = $this->request->getPost('send', 'int', 0);
        $user_id = $this->getUserID();
        
        $msg = array(
            'ok' => '',
            'state' => 0
        );
        /**/
        
        if (!$info = M\Users::findFirstByusername($mobile)) 
        {
            if($send) {
                /* 注册用户信息 */
                $member = new L\Member();
                $synuser = $member->getMember($mobile);
                $password = '123456';

                if (!$synuser) 
                {
                    $synuser = $member->register($mobile, $password);
                    $synuser['user_id'] = $synuser['id'];
                    //发送短信内容
                    $sms = new L\SMS();
                    $content = '欢迎来到丰收汇，自动注册密码为' . $password . '(如非本人操作请忽略！)';
                    $sms->send($mobile, $content);
                }
                //同步丰收汇用户信息
                
                if ($synuser) 
                {
                    $synuser['id'] = $synuser['user_id'];
                    M\Users::syncUser($synuser, $password);
                }
            }
        }
        if($info&&$info->id==$user_id){
             $msg = array(
                'error' => '商友不能为自己'
            );
            exit(json_encode($msg));
        }
        $reslut = M\UserPartner::findFirst('user_id ='.$user_id . ' and partner_phone="' . $mobile . '" and status=0 and partner_user_id !=""');
        if($reslut){
            if ($type) {
                $msg = array(
                'error' => '商友已存在'
                );
            }else{
                $msg = array(
                'error' => '商友已存在,请在"选择我的商友"中直接选择'
                );
            }
            
            exit(json_encode($msg));
        }
        if ($reslut) {
           $reslut = $reslut->toArray();
        }else{
            $reslut = array('id' =>0 );
        }

        $data['uname'] = isset($info->ext->name) ? $info->ext->name : '';
         $msg = array(
                'state' => 0,
                'ok' => '',
                'uname' => $data['uname']
         );
         exit(json_encode($msg));
        
    }

    /**
     * 检测认证银行卡
     * @return [type] [description]
     */
    public function selectbankNoAction () {
        $cardno = trim($this->request->getPost('cardno', 'string', ''));
        $user_id = $this->getUserID();

        // $user_id = isset($user['user_id']) ? $user['user_id'] : 0 ;
        $msg = array('error' => '信息错误', 'state' => 4);
        if(!$user_id || !$cardno) {
            exit(json_encode($msg));
        }

        if($count = M\UserBankCard::count(" user_id = '{$user_id}' AND bank_cardno = '{$cardno}' AND source_type > 1 ")) {
            $msg = array('state' => 2);
            exit(json_encode($msg));
        }

       
        if($data = M\UserBankCard::findFirst(" user_id = '{$user_id}' AND bank_cardno = '{$cardno}' AND source_type < '2' ")) {
            $msg = array('state' => 1);
            exit(json_encode($msg));
        }

        $msg = array('state' => 0);
        exit(json_encode($msg));
    }
    public function getcatenameAction(){

        $pid = $this->request->get('pid','int',1);

        $cate=Category::find("parent_id={$pid} ")->toArray();

        if(!$cate){
            die(json_encode(array()));
        }
        $str='';
        foreach ($cate as $key => $value) {
            $id=$value["id"];
            $str.="<a href='javascript:;' id='{$id}'>{$value['title']}</a>";
        }
        $str=base64_encode($str);
        die(json_encode($str));
    }
}
