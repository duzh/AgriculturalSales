<?php
/**
 * 个人 供应
 */
namespace Mdg\Member\Controllers;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Mdg\Models\TmpFile as TmpFile;
use Mdg\Models\Sell as Sell;
use Mdg\Models\Users as Users;
use Mdg\Models\UsersExt as Usersext;
use Mdg\Models\AreasFull as mAreas;
use Mdg\Models\Purchase as Purchase;
use Mdg\Models\SellContent as Content;
use Mdg\Models\SellImages as SellImages;
use Mdg\Models\Category as Category;
use Lib\Func as Func;
use Lib\Path as Path;
use Lib\File as File;
use Lib\Pages as Pages;
use Lib\Category as lCategory;
use Lib\Areas as lAreas;
use Mdg\Models as M;
use Lib as L;


class SellController extends ControllerMember
{
    /**
     * 我的供应
     */
    
    public function indexAction() 
    {
        $page = $this->request->get('p', 'int', 1);
        $state = $this->request->get('state', 'int', 0);
        $total = $this->request->get('total', 'int', 0);
        $page = intval($page)>0 ? intval($page) : 1;
        if($total!=0&&$page>$total){
            $page=$total;
        }
        $psize = 5;
        $user_id = $this->getUserID();
        if(!$user_id){
             $this->response->redirect("login/index")->sendHeaders();
        }
        $params = " uid = {$user_id} ";
        
        if ($state) 
        {
            
            switch ($state) 
            {
            case '1':
                $params.= " and state = 0 and is_del=0 ";
                break;

            case '2':
                $params.= " and state = 1 and is_del=0 ";
                break;

            case '3':
                $params.= " and is_del =1 ";
                break;
            case '4':
                $params.= " and state = 2 and is_del=0 ";
                break;
            default:
                break;
            }
        }
        $params.= ' order by updatetime desc ';
        $offst = ($page - 1 ) * $psize ;
        $total = Sell::count($params);
        $data = Sell::find($params." limit $offst,$psize ");
        $pages['total_pages'] = ceil($total / $psize);
        $pages['current'] = $page;
        $pages['total'] = $total;
        $pages = new L\Pages($pages);
        $pages = $pages->show(2);
         
        /* 检测用户是否为可信农场 */    
        $userFarm = M\UserInfo::count(" user_id = '{$user_id}' AND credit_type = '8' AND status ='1'");
        $this->view->userFarm = $userFarm;
        $this->view->total_count = ceil($total / $psize);
        $this->view->data = $data;
        $this->view->pages = $pages;
        $this->view->goods_unit = Purchase::$_goods_unit;
        $this->view->_state = Sell::$type1;
        $this->view->time_type = Sell::$type;
        $this->view->sellstate = $state;
        $this->view->p=$page;
        $this->view->state = Sell::$sellstate;
        $this->view->title = '我的供应-用户中心-丰收汇-高价值农产品交易服务商';
        $this->view->keywords = '丰收汇,农业,农产品,农副产品,农产品批发,农产品电商,农产品现货,农产品交易,农产品加工,农产品价格';
        $this->view->descript = '丰收汇是依托中国农业互联网高科技综合服务商云农场的丰富资源和先进技术而建立的中国最专业的网上农产品服务平台，
        为中国农村专业合作社、中小农商企业等提供农产品信息发布、行情资讯、网上商城、金融服务、定向对接、安全模型、订单种植等专业化服务，
        旨在成为全球高价值农产品交易服务商。';
    }
    /**
     * 我要卖货
     */
    
    public function newAction() 
    {
        $user_id = $this->getUserID();
        $se_mobile=$this->getUserName();
        //已经整合的可信农场
        $user_info=M\UserInfo::getlwttlist($user_id,$se_mobile,0,1);
         // print_r($user_info);die;
        //盟商有申请的话  进行验证
        if($user_info["lwttstate"]){
            
            if(intval($user_info["lwttstate"])==5){
               die("<script>alert('您好,您的盟商认证尚在审核中,目前不能添加');location.href='/member/sell/index'</script>");
            }
            // if($user_info['lwttcount']<1){
            //    die("<script>alert('您好,您当前整合的可信农场未达1家,目前不能添加');location.href='/member/sell/index'</script>"); 
            // }

            // if(!$user_info['cate_id']){
            //    die("<script>alert('您好,您当前整合的可信农场还未成功发布任何供应信息,目前不能添加');location.href='/member/sell/index'</script>"); 
            // }

        }
        #检测是否绑定了云农宝 start
        $userd = M\Users::findFirstByid($user_id);

        if(!$userd->is_broker&&$userd){ #不属于直销经纪人进行验
            $UserYnpInfo = M\UserYnpInfo::findFirst(" user_id={$user_id}");
            if (!$UserYnpInfo)
            {
                die("<script>alert('您好，发布供应信息，必须先绑定云农宝帐号！');location.href='/member/ynbbinding/'</script>");
            }
        }

        #end

        $userext = Usersext::findFirst("uid='{$user_id}'");
        
        $curAreas = '';
        $user=Users::findFirstByid($user_id);
        
        
        $address=mAreas::checkarea($user->areas);
        // var_dump($address);exit;      
        if (!isset($userext) || $userext->name == '' || $userext->areas_name == '') 
        {
           die("<script>alert('请先完善信息');location.href='/member/perfect/index/'</script>");
        }

        if ($user->areas) 
        {
            $curAreas = lAreas::ldData($user->areas);
        }
        // print_r($user_info);die;
        $sid = md5(session_id());
        $goods_unit = Purchase::$_goods_unit;
        $cate = Category::find(" parent_id=0 ")->toArray();
        TmpFile::clearOld($sid);
        $this->view->goods_unit = $goods_unit;
        $this->view->time_type = Sell::$type;
        $this->view->sid = $sid;
        $this->view->curAreas = $curAreas;
        $this->view->cate = $cate;
        $this->view->url=$_SERVER['HTTP_REFERER'];
        $this->view->plat = M\Users::$_plat;
        $this->view->publish_set = $userext;
        $this->view->cur_unit = array_shift($goods_unit);
        $this->view->user_info=$user_info;
        $this->view->title = '我的供应-发布供应-丰收汇-高价值农产品交易服务商';
        $this->view->keywords = '丰收汇,农业,农产品,农副产品,农产品批发,农产品电商,农产品现货,农产品交易,农产品加工,农产品价格';
        $this->view->descript = '丰收汇是依托中国农业互联网高科技综合服务商云农场的丰富资源和先进技术而建立的中国最专业的网上农产品服务平台，
        为中国农村专业合作社、中小农商企业等提供农产品信息发布、行情资讯、网上商城、金融服务、定向对接、安全模型、订单种植等专业化服务，
        旨在成为全球高价值农产品交易服务商。';
        
    }
    /**
     * 修改供应信息
     */
    
    public function editAction() 
    {
        $sellid = $this->request->get('sellid', 'int', 0);
        $user_id = $this->getUserID();
        $se_mobile=$this->getUserName();
        $sell = Sell::findFirst("id='{$sellid}' and is_del=0");
        $user_info=M\UserInfo::getlwttlist($user_id,$se_mobile);
        if (!$sell) 
        {
            $this->flash->error("此供应信息不存在！");
            return $this->dispatcher->forward(array(
                "controller" => "sell",
                "action" => "index"
            ));
        }
        
        if ($this->getUserID() != $sell->uid) 
        {
            $this->flash->error("你无权修改此供应信息！");
            return $this->dispatcher->forward(array(
                "controller" => "sell",
                "action" => "index"
            ));
        }
        $cate = Category::find(" parent_id=0 ")->toArray();
        $sid = md5(session_id());
        // 删除旧session图片
        TmpFile::clearOld($sid);
        $this->view->quantity = $sell->quantity;
        $simages=SellImages::find("sellid='{$sellid}'");
        if(empty($simages->toArray())){
            $is_img=0;
        }else{
            $is_img=SellImages::count("sellid='{$sellid}'");
        }
        $this->view->simages= $simages;
        $this->view->is_img = $is_img;
        $this->view->goods_unit = Purchase::$_goods_unit;
        $this->view->time_type = Sell::$type;
        $this->view->sid = $sid;
        $this->view->sell = $sell;
        $curCate = Category::getFamily($sell->category);
        $this->view->curCate = $curCate;
        if(isset($curCate['0']['title']) && isset($curCate['1']['title'])){
            $texttitle = "'".$curCate['0']['title']."'".','."'".$curCate["1"]["title"]."'";
        }else{
            $texttitle = '';
        }
        // 判断用户是自定义还是全部
        $userext = Usersext::findFirstByuid($user_id);
        if($userext && $userext->publish_set == 3){
            $this->view->plat = M\Users::$_plat;
        } else {
            $this->view->plat = '';
        }
        $SellStepPrice=M\SellStepPrice::find("sell_id={$sellid}");
        $SellStepcount=M\SellStepPrice::count("sell_id={$sellid}");
        $this->view->textCate = $texttitle;
        $this->view->SellStepcount = $SellStepcount;
        $this->view->userlwtt = M\UserLwtt::getuserlwtt($sellid);
        $user_info['lwtt'] = M\UserInfo::sellFarm($user_id, $sell->category);
        $this->view->user_info=$user_info;
        $this->view->curAreas = lAreas::ldData($sell->areas);
        $this->view->cate = $cate;
        $this->view->sellstepprice=$SellStepPrice;
        $this->view->url=$_SERVER['HTTP_REFERER'];
        $this->view->title = '修改供应信息-用户中心-丰收汇-高价值农产品交易服务商';
        $this->view->keywords = '丰收汇,农业,农产品,农副产品,农产品批发,农产品电商,农产品现货,农产品交易,农产品加工,农产品价格';
        $this->view->descript = '丰收汇是依托中国农业互联网高科技综合服务商云农场的丰富资源和先进技术而建立的中国最专业的网上农产品服务平台，
        为中国农村专业合作社、中小农商企业等提供农产品信息发布、行情资讯、网上商城、金融服务、定向对接、安全模型、订单种植等专业化服务，
        旨在成为全球高价值农产品交易服务商。';
    }
    /**
     * 发布
     */
    
    public function createAction() 
    {
        
        
        if (!$this->request->isPost()) 
        {
            return $this->dispatcher->forward(array(
                "controller" => "sell",
                "action" => "index"
            ));
        }
        $plat = $this->request->getPost("plat", 'int', 0);
        $maxcategory = intval($this->request->getPost("maxcategory", 'int', 0));
        $category = intval($this->request->getPost("category", 'int', 22));
        $quantity=$this->request->getPost("quantity", 'float',0);
        $min_number=$this->request->getPost("min_number", 'float',0);
        $province_id=$this->request->getPost("province_id", 'int',0);
        $city_id=$this->request->getPost("city_id", 'int',0);
        $fromfarm=$this->request->getPost("fromfarm", 'int',0);
        $step_quantity=$this->request->getPost("step_quantity", 'float',0);
        $step_price=$this->request->getPost("step_price", 'float',0);
        $price_type=$this->request->getPost("price_type",'int',0);
        $ladder_goods_unit=$this->request->getPost("ladder_goods_unit",'int',0);
        if($quantity!=0 && $quantity<$min_number){
            echo "<script>alert('供应量不能低于起购量');location.href='/member/sell/new/'</script>";die;
        }
        $user_id = $this->getUserID();
        if(!$user_id){
            echo "<script>alert('登陆超时');location.href='/member/login/index/'</script>";die;
        }
        $users = Users::findFirstByid($user_id);
        $cur_time = time();
        $areas = $this->request->getPost("areas", 'int', 0);
        $areas_name = Func::getCols(mAreas::getFamily($areas) , 'name', ',');
        $full_name=str_replace(',', '',$areas_name);
        $sell = new Sell();
        $sell->title = L\Validator::replace_specialChar($this->request->getPost("title", 'string', ''));
        $sell->category = intval($category);
        $sell->maxcategory=intval($maxcategory);
        $sell->min_price = $this->request->getPost("min_price", 'float', 0.00);
        $sell->max_price = $this->request->getPost("max_price", 'float', 0.00);
        $sell->quantity = $this->request->getPost("quantity", 'float',0);
//        if($price_type==1){
//        $sell->goods_unit = $this->request->getPost("goods_unit", 'int', 0);
//        }else{
//        $sell->goods_unit = $this->request->getPost("ladder_goods_unit", 'int', 0);
//        }
        $sell->goods_unit = $this->request->getPost("ladder_goods_unit", 'int', 0);
        $sell->stime = $this->request->getPost("stime", 'int', 0);
        $sell->etime = $this->request->getPost("etime", 'int', 0);
        $sell->areas = $areas;
        $sell->areas_name = $areas_name;
        $sell->address = $areas_name;
        $sell->breed = L\Validator::replace_specialChar($this->request->getPost("breed", 'string', ''));
        $sell->spec = L\Validator::replace_specialChar($this->request->getPost("spec", 'string', ''));
        $sell->state = 0;
        $sell->uid = $user_id;
        $sell->uname = $users->ext->name;
        $sell->mobile = $users->username;
        $sell->createtime = $sell->updatetime = $cur_time;
        $sell->full_address=$full_name;
        $sell->min_number =$this->request->getPost("min_number", 'float', 0);
        $sell->province_id=$province_id;
        $sell->city_id=$city_id;
        $sell->price_type=$price_type;
        if($plat) {
            $sell->publish_place = $plat;
        }
        $content = $this->request->getPost('content');
       
        if (!$sell->save()) 
        {
             echo "<script>alert('发布出售失败！');location.href='/member/sell/new/'</script>";die;
        }
        $sell->sell_sn = sprintf('SELL%010u', $sell->id);
        $sell->save();
        // 处理图片
        $sid = L\Validator::replace_specialChar($this->request->getPost("sid", 'string', ''));
        
        TmpFile::copyImages($sell->id, $sid);
        $scontent = new Content();
        $scontent->sid = $sell->id;
        $scontent->content = $content;
        $scontent->save();
        Category::numAdd($sell->category, 'sell_num');
        //供应信息可信农场
       
        if($fromfarm){
            foreach ($fromfarm as $key => $value) {
                M\UserLwtt::adduserlwtt($sell->id,$value);
            }
        }
        if($price_type == 1&&$step_price&&$step_quantity){ #阶梯价格保存
            #print_r($step_price);exit;
            foreach($step_quantity AS $stepKey => $stepVal){
                $sell_step_price = new M\SellStepPrice();
                $sell_step_price->sell_id = $sell->id;
                $sell_step_price->price = $step_price[$stepKey];
                $sell_step_price->quantity = $step_quantity[$stepKey];
                $sell_step_price->save();
            }
        }
        $this->response->redirect("sell/index")->sendHeaders();
    }
    /**
     * 保存修改
     */
    
    public function saveAction() 
    {
        
        if (!$this->request->isPost()) 
        {
            echo "<script>location.href='/member/sell/index/'</script>";die;
        }
        $sellid = $this->request->get('sellid', 'int', 0);
        $sell = Sell::findFirstByid($sellid);
        
        if (!$sell) 
        {
            echo "<script>alert('此供应信息不存在！');location.href='/member/sell/index/'</script>";die;
        }
        $user_id = $this->getUserID();
        if(!$user_id){
            echo "<script>alert('登陆超时');location.href='/member/login/index/'</script>";die;
        }
        if ($user_id != $sell->uid) 
        {
            echo "<script>alert('你无权修改此供应信息！');location.href='/member/sell/index/'</script>";die; 
        }

        $maxcategory = $this->request->getPost("maxcategory", 'int', 0);
        $category = $this->request->getPost("category", 'int', 22);
        $plat = $this->request->getPost("plat", 'int', 0);
        $province_id=$this->request->getPost("province_id", 'int',0);
        $city_id=$this->request->getPost("city_id", 'int',0);
        $fromfarm=$this->request->getPost("fromfarm", 'int',0);

        $step_quantity=$this->request->getPost("step_quantity", 'float',0);
        $step_price=$this->request->getPost("step_price", 'float',0);
        $price_type=$this->request->getPost("price_type",'int',0);
        $ladder_goods_unit=$this->request->getPost("ladder_goods_unit",'int',0);
        $users = Users::findFirstByid($user_id);
        $areas = $this->request->getPost("areas", 'int', 0);
        $areas_name = Func::getCols(mAreas::getFamily($areas) , 'name', ',');
        $full_name=str_replace(',', '',$areas_name);
        $sid = L\Validator::replace_specialChar($this->request->getPost("sid", 'string', ''));
        $sell->title = L\Validator::replace_specialChar($this->request->getPost("title", 'string', ''));
        $sell->category = $category;
        $sell->maxcategory=$maxcategory;
        $sell->min_price = $this->request->getPost("min_price", 'float', 0.00);
        $sell->max_price = $this->request->getPost("max_price", 'float', 0.00);
        $sell->quantity = $this->request->getPost("quantity", 'float', 0.00);
//        if($price_type==1){
//        $sell->goods_unit = $this->request->getPost("goods_unit", 'int', 0);
//        }else{
//        $sell->goods_unit = $this->request->getPost("ladder_goods_unit", 'int', 0);
//        }
        $sell->goods_unit = $this->request->getPost("ladder_goods_unit", 'int', 0);
        $sell->stime = $this->request->getPost("stime", 'int', 0);
        $sell->etime = $this->request->getPost("etime", 'int', 0);
        $sell->areas = $areas;
        $sell->areas_name = $areas_name;
        $sell->address = $areas_name;
        $sell->breed = L\Validator::replace_specialChar($this->request->getPost("breed", 'string', ''));
        $sell->spec = L\Validator::replace_specialChar($this->request->getPost("spec", 'string', ''));
        $sell->state = 0;
        $sell->uid = $user_id;
        $sell->mobile = $users->username;
        $sell->uname = $users->ext->name;
        $sell->updatetime = time();
        $sell->min_number =$this->request->getPost("min_number", 'float', 0);
        $sell->full_address=$full_name;
        $sell->province_id=$province_id;
        $sell->city_id=$city_id;
        $sell->price_type=$price_type;
        if($plat) {
            $sell->publish_place = $plat;
        }
        $content = $this->request->getPost('content');
       
      
        if (!$sell->save()) 
        {
            echo "<script>alert('保存失败！');location.href='/member/sell/new/'</script>";die;
        }
        TmpFile::copyImages($sell->id, $sid);
        $scontent = new Content();
        $scontent->sid = $sell->id;
        $scontent->content = $content;
        $scontent->save();
        if($fromfarm){
            $UserLwtt=M\UserLwtt::find("sell_id={$sell->id}");
            if($UserLwtt){
                $UserLwtt->delete();
            }
            foreach ($fromfarm as $key => $value) {
               
                M\UserLwtt::adduserlwtt($sell->id,$value);
            }
        }
        $sellprice=M\SellStepPrice::find("sell_id={$sell->id}");
        if($sellprice){
            $sellprice->delete();
        }
        if($price_type == 1&&$step_price&&$step_quantity){ #阶梯价格保存
            foreach($step_quantity AS $stepKey => $stepVal){
                $sell_step_price = new M\SellStepPrice();
                $sell_step_price->sell_id = $sell->id;
                $sell_step_price->price = $step_price[$stepKey];
                $sell_step_price->quantity = $step_quantity[$stepKey];
                $sell_step_price->save();
            }
        }
        $this->response->redirect("sell/index")->sendHeaders();
    }
    /**
     * 删除供应信息
     */
    
    public function deleteAction() 
    {
        $id = $this->request->get('sellid', 'int', 0);
        $sell = Sell::findFirstByid($id);
        
        if (!$sell) 
        {
            $this->flash->error("此供应信息不存在！");
            return $this->dispatcher->forward(array(
                "controller" => "sell",
                "action" => "index"
            ));
        }
        
        if ($sell->uid != $this->getUserID()) 
        {
            $this->flash->error("你无权删除此信息！");
            return $this->dispatcher->forward(array(
                "controller" => "sell",
                "action" => "index"
            ));
        }
        $sell->is_del = 1;
        
        if (!$sell->save()) 
        {
            echo '<script>alert("取消发布失败，请联系客服！")</script>';
            
            foreach ($sell->getMessages() as $message) 
            {
                $this->flash->error('取消发布失败，请联系客服！');
            }
            return $this->dispatcher->forward(array(
                "controller" => "sell",
                "action" => "index"
            ));
        }
        Category::numDec($sell->category, 'sell_num');
        echo '<script>alert("取消发布成功！")</script>';
        return $this->dispatcher->forward(array(
            "controller" => "sell",
            "action" => "index"
        ));
    }
    
    public function delimgAction() 
    {
        $rs = array(
            'state' => false,
            'msg' => '删除图片成功！'
        );
        $id = $this->request->get('id', 'int', 0);
        $img = SellImages::findFirstByid($id);
        
        if (!$img) 
        {
            $rs['msg'] = '图片不存在！';
            die(json_encode($rs));
        }
        
        if ($this->getUserID() != $img->sell->uid) 
        {
            $rs['msg'] = '你无权删除此图片！';
            die(json_encode($rs));
        }
        $sellid = $img->sellid;
        @unlink(PUBLIC_PATH . $img->path);
        $img->delete();
        $data = SellImages::findFirstBysellid($sellid);
        
        if (!$data) 
        {
            $sell = Sell::findFirstByid($sellid);
            
            if ($sell) 
            {
                $sell->thumb = '';
                $sell->save();
            }
        }
        $rs['state'] = true;
        die(json_encode($rs));
    }
    public function checkimgAction(){
        $content=$this->request->getPost('content');
        if($content==''){
            $msg['error'] = '描述不能为空';
             die(json_encode($msg));
        }
        if(preg_match("/<img.*>/",$content)){
           $msg["ok"]='';
        }else{
           $msg['error'] = '描述必须包括文字和图片描述';
        } 
        die(json_encode($msg));
    }
    public function checkfarmAction(){
          $user_id = $this->getUserID();
          $se_mobile=$this->getUserName();
          $maxcate = $this->request->get('maxcate', 'int',1);
          $cate = $this->request->get('cate', 'int',20);

          $user_info=M\UserInfo::getlwttlist($user_id,$se_mobile,0,1);
          if($maxcate&&$cate&&$user_info["lwtt"]){
            $user_id=$user_info['user_id'];
            $credit_id=$user_info['credit_id'];
            $sell=M\Sell::findFirst(" uid in ({$user_id}) and state=1 and is_del=0 and maxcategory={$maxcate} and category={$cate} ");
            if($sell){
               $userinfo=M\UserInfo::find("user_id={$sell->uid} and credit_type=8  and credit_id in($credit_id) ")->toArray();
            }else{
                $userinfo=array();
            }
            if($userinfo){
                foreach ($user_info["lwtt"] as $k => $v) {
                    foreach ($userinfo as $key => $value) {
                         if($value['credit_id']==$v["credit_id"]){
                            $arr[]=$v;
                         }
                     }
                }
            }else{
                $arr=array();
            }
          }else{
            $arr=array();
          }
          $this->view->arr=$arr;
          $this->view->isajax=1;
    }
    public function checkstepAction(){
        $step_price=$this->request->getPost('step_price', 'int',1);
        $step_quantity=$this->request->getPost('step_quantity', 'int',1);
       print_r($_POST);die;
    }
    
}
