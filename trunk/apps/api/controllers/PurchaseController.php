<?php
namespace Mdg\Api\Controllers;
use Lib\Member as Member, Lib\Auth as Auth, Lib\SMS as sms, Lib\Utils as Utils;
use Mdg\Models\Users as Users;
use Mdg\Models\UsersExt as Ext;
use Mdg\Models\YncUsers as yncuser;
use Mdg\Models\AreasFull as mAreas;
use Lib as L;
use Mdg\Models as M;
use Lib\Validator as V;
/**
 *  采购 相关接口 
 */

class PurchaseController extends ControllerBase
{
    /**
     * 采购大厅接口
     * @return string
     * {"errorCode":0,"data":[{"buyerName":"\u674e\u7ecf\u7406","goodsName":"\u7389\u7c73",
     * "purcArea":"\u6e56\u5317\u7701\u6b66\u6c49\u5e02\u9ec4\u9642\u533a\u53cc\u51e4\u5927\u9053327\u53f7",
     * "quotedNum":0,"pid":"123456"}]}
     * <br />
     * <code>
     * <br/>
        *       {
        *           "errorCode": 0,
        *           "data": [
        *               {
        *                    "member": [
        *                        {
        *                        'pid(采购id)':'13'
        *                        "buyerName(采购商)": "李经理",
        *                        "goodsName(采购商品)": "玉米",
        *                        "purcArea(采购地区)": "湖北省武汉市黄陂区双凤大道327号",
        *                        "quotedNum(报价人数)": 0,
        *                        'areas(地址id)':'1'
        *                        }
        *                  
        *               }
        *           ]
        *       }   
     * <br/>
     * post 传值
     * <br />
     * url http://www.5fengshou.com/api/purchase/getList <br />
     * int p   当前页码 <br />
     * int cate 分类id （可选）
     * string keyword 关键字
     * int area 地址id 
     * int page_size   显示条数 <br />
     * </code>
     */
    
    public function getListAction() 
    {
        $cond = '';
        $p         = $this->request->getPost('p', 'int', 1);
        $page_size = $this->request->getPost('page_size', 'int', 10);
        $cate = $this->request->getPost('cate', 'int', 0);
        $keyword = $this->request->getPOST('keyword', 'string', '');
        $area = $this->request->get('area', 'int', 0);

        

        if(!$page_size){
            $page_size=4;
        }
        if(!$p){
            $p=1;
        }
        $cond[]= ' state = 1 AND is_del = 0 AND  endtime >= '.strtotime(date('Y-m-d'));
        $info = M\Areas::findFirstByarea_id($area);

        if($info) {   
            $cond[] = " areas in ({$info->child}) ";
        }
        if($keyword) {
            $cond[] = " title like '{$keyword}%' ";
        }
        if($cate) {
            // $cids = M\Category::getChild($cate);
            // $this->getMsg(parent::SUCCESS,array('member'=>$_POST));
            // $cids = !empty($cids) ? $cids : array(0);
            // $cond[] = " category in (".implode(',', $cids).") ";
            $cond[] = " maxcategory = '{$cate}'";
        }
        if ($cond) $conds = implode(' AND ', $cond);
        // $this->getMsg(self::SUCCESS , $conds);
        $cond = array();

        $cond[] = $conds;
        $cond['columns'] = " id, title, maxcategory, category, quantity, address, username, goods_unit, mobile, endtime,areas ";
        $offst = ($p - 1) * $page_size;
       $cond['order'] = ' id desc ';
        $cond['limit'] = array(
            $page_size,
            $offst
        );
        // $this->getMsg(self::SUCCESS , $cond);
        $data = M\Purchase::find($cond)->toArray();
        
        if(!$data){
            $this->getMsg(parent::DATA_EMPTY);
        }
        foreach ($data as $key => $val) {
            $datas[$key]['pid']         = $val['id'] ? $val['id'] : '';
            $datas[$key]['buyerName']   = $val['username'] ? $val['username'] : '';
            $datas[$key]['goodsName']   = $val['title'] ? $val['title'] : '';
            $datas[$key]['purcArea']    = $val['address'] ? $val['address'] : '';
            $datas[$key]['areas']       = $val['areas'] ? $val['areas'] : '';
            $datas[$key]['quotedNum']   =  M\PurchaseQuotation::countQuo($val['id']);
            $datas[$key]['maxcategory'] =  $val['maxcategory'];
            $datas[$key]['category']    =  $val['category'];

        }
        $this->getMsg(parent::SUCCESS,array('member'=>$datas));
    }
    /**
     * 确认采购
     * @return string  {"errorCode":0} 10001 参数错误 10000 供应商数据为空  10110 采购量不能大于供应量
     *  不能小于起购量 10112 不能采购自己发布的商品
     * <br />
     * <code>
     * <br />
     * post 传值   
     * sellid 供应商id    1
     * areas  地址id       最后选择的地址的id
     * <br />
     * purname 收货人姓名  张三
     * <br />
     * quantity 采购数量    1.00
     * purphone 收货人手机号  135219962900
     * address  详细地址
     * url http://www.5fengshou.com/api/purchase/savebuy 
     * <br />
     * </code>
     */
    public function  savebuyAction(){
        
        $sellid = $this->request->getPost('sellid', 'int', 0);
        $areas = $this->request->getPost('areas', 'int', 0);
        $purname = $this->request->getPost('purname', 'string', '');
        $quantity = $this->request->getPost('quantity', 'float', 0.00);
        $purphone = $this->request->getPost('purphone', 'string', '');   
        $address = $this->request->getPost('address', 'string', ''); 
        $source = $this->request->getPOST('source', 'int', 3);
        $engineer_name = $this->request->getPOST('engineer_name', 'string', '');
        $engineer_phone = $this->request->getPOST('engineer_phone', 'string', '');
        $engineer_id = $this->request->getPOST('engineer_id', 'int', 0);
        $user_id = $this->getUid();
        if(!$user_id) $this->getMsg(parent::NOT_LOGGED_IN);
           //检测是否完善资料
        $userareas=$this->checkuser($user_id);
        if(!$userareas){
            $this->getMsg(parent::INFO_ERROR);
        }
        //检测各项是否为空
        if(!$sellid || !$areas || !$purname || !$quantity || !$purphone ) {
             $this->getMsg(parent::PARAMS_ERROR);
        }
        if(!L\Validator::validate_is_int($sellid)||!L\Validator::validate_is_int($areas)||!L\Validator::validate_is_float($quantity)||!L\Validator::validate_is_mobile($purphone)){
            $this->getMsg(parent::PARAMS_ERROR);
        }
        //检测传过来的地址
        $addressareas=M\AreasFull::checkarea($areas);
        if(!$addressareas){
             $this->getMsg(parent::PUR_ADDRESS_ERROR);
        }
        //检测采购量不能大于供应量 不能小于起购量
        $sell = M\Sell::findFirstByid($sellid);
        if(!$sell) {
            $this->getMsg(parent::DATA_EMPTY);
        }
        if($sell->quantity>0&&$sell->quantity<$quantity&&$quantity>$sell->min_number&&$sell->min_number!=0){
            $this->getMsg(parent::NOT_MAX_SELL);
        }
        if($user_id == $sell->uid) {
            $this->getMsg(parent::NOT_SELL_SELF);
        }
        $flag = false;
        $this->db->begin();
        try
        {

            $order = new M\Orders();
            $order->sellid = $sellid;
            $order->puserid = $user_id;
            $order->purname = $purname;
            $order->purphone = $purphone;
            $order->suserid = $sell->uid;
            $order->sname = $sell->uname;
            $order->sphone = $sell->mobile;
            $order->areas = $areas;
            $order->areas_name = L\Func::getCols(mAreas::getFamily($areas), 'name', ',');
            $order->address =$order->areas_name.$address;
            $order->goods_name = $sell->title;
            $order->price = $sell->min_price;
            $order->quantity = $quantity;
            $order->goods_unit = $sell->goods_unit;
            $order->total = $order->price * $order->quantity;
            $order->addtime = $order->updatetime = time();
            $order->state = 2;
            $order->source= $source;    
            if(!M\OrdersLog::insertLog($order->id, 2, $user_id, $this->getMobile(), 0, $demo='采购下单')){
                throw new \Exception(parent::NEWBUY_ERROR);
            }   
            if(!$order->save()){
              throw new \Exception(parent::NEWBUY_ERROR);
            }
             #订单号 ， 拼接随机数
            $random = date('Ym',time()). L\Func::random(4,1);
            $order->order_sn = sprintf('mdg%09u', $order->id . $random );
            $order->save();

            //增加服务工程师
            if($engineer_id){
                $engdata = new M\OrderEngineer();
                $engdata->order_id = $order->id;
                $engdata->order_sn = $order->order_sn;
                $engdata->engineer_name = $engineer_name;
                $engdata->engineer_phone = $engineer_phone;
                $engdata->engineer_id = $engineer_id;
                $engdata->add_time = time();
                $engdata->save();
            }

            $flag = $this->db->commit();
        }
        catch(\Exception $e) 
        {
            $flag = false;
            $this->db->rollback();
        }
        
        if($flag){
            $this->getMsg(parent::SUCCESS);
        }else{
            $this->getMsg(parent::NEWBUY_ERROR);
        }
    }
    /**
     * 检测采购数量
     * @return string  {"errorCode":0}  符合   {"errorCode":10110} 不能大于供应量 不能小于起购量
     * <br />
     * <code>
     * <br />
     * post 传值 
     * int sellid 供应商id
     * <br />
     * int quantity 采购数量
     * <br/>
     * url http://www.5fengshou.com/api/purchase/checkquantity <br />
     * </code>
     */
    public function checkquantityAction(){

        $user_id = $this->getUid();
        if(!$user_id) $this->getMsg(parent::NOT_LOGGED_IN);

        $quantity = $this->request->getPost('quantity','int',0);
        $sellid = $this->request->getPost('sellid','int',0);
        if($quantity&&$quantity<0){
            $this->getMsg(parent::PARAMS_ERROR);
        }

        if(!V::validate_is_float($quantity)){
            $this->getMsg(parent::PARAMS_ERROR);
        }
        if(!$sellid){
             $this->getMsg(parent::PARAMS_ERROR);
        }
        $sell=M\Sell::findFirstByid($sellid);
        if(!$sell){
             $this->getMsg(parent::DATA_EMPTY);
        }        
        if($quantity<$sell->min_number&&$sell->min_number!=0){
            $this->getMsg(parent::NOT_MAX_SELL);
        }
        if($quantity>$sell->quantity&&$sell->quantity!=0){
            $this->getMsg(parent::NOT_MAX_SELL);
        }
         $this->getMsg(parent::SUCCESS);
    }
    /**
     * 发布采购信息
     * @return string  {"errorCode":0}    
     * <br />
     * <code>
     * post 传值 
     * <br />
     * string title  采购商品名称
     * float quantity 采购数量
     * int   goods_unit 单位 
     * <br />
     * string endtime 报价截至时间 （正常时间格式）
     * string content 规格要求   
     * <br />
     * url http://www.5fengshou.com/api/purchase/newpur <br />
     * </code>
     */
    public function newpurAction() {
        $user_id = $this->getUid();
        if(!$user_id){
            $this->getMsg(parent::NOT_LOGGED_IN);
        }
        //检测是否完善资料
        $userareas=$this->checkuser($user_id);
        if(!$userareas){
            $this->getMsg(parent::INFO_ERROR);
        }
        $title = $title = $this->request->getPost('title', 'string', '');
        $quantity = $this->request->getPost('quantity', 'float', 0);
        $goods_unit = $this->request->getPost('goods_unit', 'int', 0);
        $endtime = $this->request->getPost("endtime", 'string', '');
        $content = $this->request->getPost("content", 'string', '');
        $source = $this->request->getPost("source", 'int',4);
        
        if(!$title||!$quantity||!$endtime||!$content){
            $this->getMsg(parent::DATA_EMPTY);
        }
    
        //检测各项数据
        if (!V::validate_max_length($title,10)||!V::validate_is_float($quantity)){
            $this->getMsg(parent::PARAMS_ERROR);
        }
        $this->db->begin();
        try
        {   
            $purchase = new M\Purchase();
            $purchase->uid = $userareas->id;
            $purchase->title = $title;
            $purchase->quantity =$quantity;
            $purchase->goods_unit = $goods_unit;
            $purchase->state = 0;
            $purchase->areas = $userareas->areas;
            $purchase->areas_name = $userareas->ext->areas_name;
            $purchase->address = $userareas->ext->address;
            $purchase->username = $userareas->ext->name;
            $purchase->mobile = $userareas->username;
            $purchase->endtime = strtotime($endtime);
            $purchase->source=$source;
            $purchase->createtime = $purchase->updatetime = time();
            if(!$purchase->save()) throw new \Exception(parent::SAVEPUR_ERROR);
            $purchase->pur_sn = sprintf('pur%010u', $purchase->id);
            $purchase->save(); 
            $pContent = new M\PurchaseContent();
            $pContent->purid = $purchase->id;
            $pContent->content = $content;
            if(!$pContent->save()) {
                throw new \Exception(parent::SAVEPURCONTENT_ERROR);
            }
            $this->db->commit();
            $flag =0;
        }
        catch(\Exception $e) 
        {
            $this->db->rollback();
            $flag = $e->getMessage();
        }
        if($flag==0){
            M\Category::numAdd($purchase->category, 'pur_num');
            $this->getMsg(parent::SUCCESS);
        }else{
           $this->getMsg($flag);
        }
    }

    /**
     * 审核失败重新编辑信息
     * @return string  {"errorCode":0}    
     * <br />
     * <code>
     * post 传值 
     * <br />
     * string title  采购商品名称
     * float quantity 采购数量
     * int   goods_unit 单位 
     * <br />
     * string endtime 报价截至时间 （正常时间格式）
     * string content 规格要求 
     * int pid  采购id 
     * <br />
     * url http://www.5fengshou.com/api/purchase/savepur <br />
     * </code>
     */
    public function savepurAction() {
        $user_id = $this->getUid();

        if(!$user_id){
            $this->getMsg(parent::NOT_LOGGED_IN);
        }
        //检测是否完善资料
        $userareas=$this->checkuser($user_id);
        if(!$userareas){
            $this->getMsg(parent::INFO_ERROR);
        }
        $title = $title = $this->request->getPost('title', 'string', '');
        $quantity = $this->request->getPost('quantity', 'float', 0);
        $goods_unit = $this->request->getPost('goods_unit', 'int', 0);
        $endtime = $this->request->getPost("endtime", 'string', '');
        $content = $this->request->getPost("content", 'string', '');
        $pid = $this->request->getPost("pid", 'int', '');
        
        // if(!$title||!$goods_unit||!$pid||!$endtime||!$content){
        //     $this->getMsg(parent::PARAMS_ERROR);
        // }
    
        //检测各项数据
        if (!V::validate_max_length($title,10)||!V::validate_is_float($quantity)||!V::validate_is_datetime($endtime)){
            $this->getMsg(parent::PARAMS_ERROR);
        }
        $this->db->begin();
        try
        {   

            $purchase = M\Purchase::findFirstByid($pid);
            if(!$purchase){
                 throw new \Exception(parent::DATA_EMPTY);
            }
            if($purchase->state!=2){
                 throw new \Exception(parent::DATA_EMPTY);
            }
            $purchase->uid = $userareas->id;
            $purchase->title = $title;
            $purchase->quantity =$quantity;
            $purchase->goods_unit = $goods_unit;
            $purchase->state = 0;
            $purchase->areas = $userareas->areas;
            $purchase->areas_name = $userareas->ext->areas_name;
            $purchase->address = $userareas->ext->address;
            $purchase->username = $userareas->ext->name;
            $purchase->mobile = $userareas->username;
            $purchase->source = 4;
            $purchase->endtime = strtotime($endtime);
            $purchase->createtime = $purchase->updatetime = time();
            if(!$purchase->save()) throw new \Exception(parent::SAVEPUR_ERROR);
            $purchase->pur_sn = sprintf('pur%010u', $purchase->id);
            $purchase->save(); 
            $pContent = new M\PurchaseContent();
            $pContent->purid = $purchase->id;
            $pContent->content = $content;
            if(!$pContent->save()) {
                throw new \Exception(parent::SAVEPURCONTENT_ERROR);
            }
            $this->db->commit();
            $flag =0;
        }
        catch(\Exception $e) 
        {
            $this->db->rollback();
            $flag = $e->getMessage();
        }
        if($flag==0){
            $this->getMsg(parent::SUCCESS);
        }else{
           $this->getMsg($flag);
        }
    }


    /**
     * 我的采购接口-个人中心 
     * @return string  {"errorCode":0,"data":{"member":[{"pur_sn":"pur0000001886","title":"123","quantity":"123.00",
     * "areas_name":"\u5317\u4eac\u5e02","goods_unit":"\u5143\/\u516c\u65a4",
     * "endtime":"2015-03-29 00:00:00","createtime":"2015-03-26 20:18:13",
     * "spec":"12","id":"1886","offer":2,"state":"\u672a\u5ba1\u6838"}]}}
     * <br/>
     *   {
     *           "errorCode": 0,
     *           "data": {
     *               "token": "nq1qnmqagh9kp1cv12s6fq1m61",
     *               "member": [
     *                   {
     *                       "pur_sn(采购编号)": "pur0000001886",
     *                       "title(商品名称)": "123",
     *                       "quantity(采购数量)": "123.00",
     *                       <br />
     *                       "areas_name(采购地区)": "北京市",
     *                       "goods_unit(单位)": "元/公斤",
     *                       "endtime(报价截止时间)": "2015-03-29 00:00:00",
     *                       "createtime(发布时间)": "2015-03-26 20:18:13",
     *                       <br />
     *                       "spec(规格要求)": "12",
     *                       "id(采购id)": "1886",
     *                       "offer(报价数量)": 2,
     *                        "state(状态)": "未审核",
     *                        'failReason审核失败原因':'不真实',
     *                        'statecode采购状态数据库的':'1',
     *                        'is_del是否删除(已取消的时候用)':1,
     *                         'areas'=>'地址id',
     *                         'goods_unitid'=>'单位id'
     *                        <br />
     *                   }
     *               ]
      *          }
     *       } 
     *
     * <br/>
     * 
     * <code>
     * <br />
     * post 传值 
     * int p 当前页数 默认第一页
     * int pagesize 每页显示几条  默认4条
     * int state  状态   0  未审核   1 已审核   2 审核失败 (数据库本来的状态)
     * int is_del  1 已取消 0 未删除 
     * <br/>
     * (搜索条件 0 全部 1 待审核 2 已审核 3 已取消)
     * <br/>
     * url http://www.5fengshou.com/api/purchase/memberpur <br />
     * </code>
     */
    public function memberpurAction()
    {
        $user_id = $this->getUid();
        if(!$user_id){
            $this->getMsg(parent::NOT_LOGGED_IN);
        }
        $page = $this->request->getPost('p', 'int', 1);
        $page_size = $this->request->getPost('pagesize', 'int', 4);
        $state=$this->request->getPost('state', 'int', '0');
        
        $where[] = " uid = {$user_id} ";
        if ($state>0) 
        {
             
            switch ($state) 
            {
            case '1':
                $where[]= "  state = 0 and is_del=0 ";
                break;

            case '2':
                $where[]= "  state != 0 and is_del=0 ";
                break;

            case '3':
                $where[]= "  is_del =1 ";
                break;
            default:
                break;
            }
        }

        $where = implode(' and ', $where);
     
        $total = M\Purchase::count($where);
       
        $offst = intval(($page - 1) * $page_size);

        $data = M\Purchase::find($where."  ORDER BY updatetime DESC limit {$offst} , {$page_size} ")->toArray();
       
        if(!$data){
           $this->getMsg(parent::DATA_EMPTY);
        }

        foreach ($data as $key => $value) {
            $purchase[$key]["pur_sn"]=$value['pur_sn'] ? $value['pur_sn'] : '';
            $purchase[$key]["title"]=$value['title'] ? $value['title'] : '';
            $purchase[$key]["quantity"]=$value['quantity'] > 0 ? intval($value['quantity']) : '不限';
            $purchase[$key]["areas_name"]=$value['areas_name'] ? L\Utils::getC($value['areas_name']) : '';
            $purchase[$key]["areas"]=$value['areas'] ? $value['areas'] : '';
            $purchase[$key]['goods_unit']=M\Purchase::$_goods_unit[$value["goods_unit"]];
            $purchase[$key]['goods_unitid']=$value["goods_unit"];
            $purchase[$key]['endtime']=date("Y-m-d ",$value['endtime']);
            $purchase[$key]["createtime"]=date("Y-m-d H:i:s",$value['createtime']);
            $spec=M\PurchaseContent::getPurchaseContent($value['id']);
            $purchase[$key]["spec"]=$spec ? L\Utils::c_strcut($spec, 20) : '';
            $purchase[$key]["id"]=$value['id'];
            $purchase[$key]['offer'] = M\PurchaseQuotation::countQuo($value['id']);
            $purchase[$key]['statecode']=$value['state'];
            $purchase[$key]['is_del']=$value['is_del'];
            if($value['is_del']==1){
                 $purchase[$key]['state'] ="已取消";
            }else{
                 $purchase[$key]['state'] =M\Purchase::$appstate[$value['state']];
                 if($value["state"]==2){
                  $purchase[$key]['failReason']=M\PurchaseCheck::getPurchaseFailReason($value['id']);
                 }
            }
        }
        $this->getMsg(parent::SUCCESS, array('member'=>$purchase));
    }
    /**
     * 我的采购接口-个人中心-取消采购
     * @return string  {"errorCode":0}
     * <br/>
     * <code>
     * <br />
     * post 传值 
     * int purid 采购商id
     * <br/>
     * url http://www.5fengshou.com/api/purchase/remove <br />
     * </code>
     */
    public function removeAction() {
        $user_id = $this->getUid();
        if(!$user_id){
            $this->getMsg(parent::NOT_LOGGED_IN);
        }
        $id = $this->request->getPost('purid', 'int', 0);
        $purchase = M\Purchase::findFirstByid($id);
        if (!$purchase || $purchase->uid != $user_id) {
            $this->getMsg(parent::DATA_EMPTY);
        }
        $purchase->is_del = 1;
        if(!$purchase->save()){
            $this->getMsg(parent::PURREMOVE_ERROR);
        }else{
            $this->getMsg(parent::SUCCESS);
        }
    }

    /**
     * 我的采购接口-个人中心-重新采购
     * @return string  {"errorCode":0}
     * <br/>
     * <code>
     * <br />
     * post 传值 
     * int purid 采购商id
     * <br/>
     * url http://www.5fengshou.com/api/purchase/anew <br />
     * </code>
     */
    public function anewAction() {
        $user_id = $this->getUid();
        if(!$user_id){
            $this->getMsg(parent::NOT_LOGGED_IN);
        }
        $id = $this->request->getPost('purid', 'int', 0);
        $purchase = M\Purchase::findFirstByid($id);
        if (!$purchase || $purchase->uid != $user_id) {
            $this->getMsg(parent::DATA_EMPTY);
        }
        $purchase->state = 1;
        $purchase->is_del = 0;
        if(!$purchase->save()){
            $this->getMsg(parent::PURREMOVE_ERROR);
        }else{
            $this->getMsg(parent::SUCCESS);
        }
    }
    


     /**
     * 我的采购接口-个人中心 
     * @return string  {"errorCode":0,"data":{"member":[{"pur_sn":"pur0000001886","title":"123","quantity":"123.00",
     * "areas_name":"\u5317\u4eac\u5e02","goods_unit":"\u5143\/\u516c\u65a4",
     * "endtime":"2015-03-29 00:00:00","createtime":"2015-03-26 20:18:13",
     * "spec":"12","id":"1886","offer":2,"state":"\u672a\u5ba1\u6838"}]}}
     * 
     * <code>
     * <br />
     * post 传值 
     * int p 当前页数 默认第一页
     * int pagesize 每页显示几条  默认4条
     * int state  状态   0  未审核   1 已审核   2 审核失败 (数据库本来的状态)
     * int is_del  1 已取消 0 未删除 
     * <br/>
     * (搜索条件 0 全部 1 待审核 2 已审核 3 已取消)
     * <br/>
     * url http://www.5fengshou.com/api/purchase/wxMemberpur <br />
     * </code>
     */
    public function wxMemberpurAction()
    {
        $user_id = $this->getUid();
        if(!$user_id){
            $this->getMsg(parent::NOT_LOGGED_IN);
        }
        $page = $this->request->getPost('p', 'int', 1);
        $page_size = $this->request->getPost('pagesize', 'int', 4);
        $state=$this->request->getPost('state', 'int', '0');
        
        $where[] = " uid = {$user_id} ";
        if ($state>0) 
        {
             
            switch ($state) 
            {
            case '1':
                $where[]= "  state = 0 and is_del=0 ";
                break;

            case '2':
                $where[]= "  state = 1 and is_del=0 ";
                break;

            case '3':
                $where[]= "  is_del =1 ";
                break;

            case '4':
                $where[]= "  state = 2 and is_del=0 ";
                break;

            default:
                break;
            }
        }

        $where = implode(' and ', $where);
     
        $total = M\Purchase::count($where);
       
        $offst = intval(($page - 1) * $page_size);

        $data = M\Purchase::find($where."  ORDER BY updatetime DESC limit {$offst} , {$page_size} ")->toArray();
       
        if(!$data){
           $this->getMsg(parent::DATA_EMPTY);
        }

        foreach ($data as $key => $value) {
            $purchase[$key]["pur_sn"]=$value['pur_sn'] ? $value['pur_sn'] : '';
            $purchase[$key]["title"]=$value['title'] ? $value['title'] : '';
            $purchase[$key]["quantity"]=$value['quantity'] > 0 ? $value['quantity'] : '不限';
            $purchase[$key]["areas_name"]=$value['areas_name'] ? L\Utils::getC($value['areas_name']) : '';
            $purchase[$key]["areas"]=$value['areas'] ? $value['areas'] : '';
            $purchase[$key]['goods_unit']=M\Purchase::$_goods_unit[$value["goods_unit"]];
            $purchase[$key]['goods_unitid']=$value["goods_unit"];
            $purchase[$key]['endtime']=date("Y-m-d H:i:s",$value['endtime']);
            $purchase[$key]["createtime"]=date("Y-m-d H:i:s",$value['createtime']);
            $spec=M\PurchaseContent::getPurchaseContent($value['id']);
            $purchase[$key]["spec"]=$spec ? L\Utils::c_strcut($spec, 20) : '';
            $purchase[$key]["id"]=$value['id'];
            $purchase[$key]['offer'] = M\PurchaseQuotation::countQuo($value['id']);
            $purchase[$key]['statecode']=$value['state'];
            $purchase[$key]['is_del']=$value['is_del'];
            if($value['is_del']==1){
                 $purchase[$key]['state'] ="已取消";
            }else{
                 $purchase[$key]['state'] =M\Purchase::$appstate[$value['state']];
                 if($value["state"]==2){
                  $purchase[$key]['failReason']=M\PurchaseCheck::getPurchaseFailReason($value['id']);
                 }
            }
        }
        $this->getMsg(parent::SUCCESS, array('member'=>$purchase));
    }
    
}
?>