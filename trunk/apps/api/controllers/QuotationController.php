<?php
namespace Mdg\Api\Controllers;

use Mdg\Models as M;
use Lib as L;
/**
 * 报价相关接口
 */
class QuotationController extends ControllerBase
{
    
    /**
     * 交易-报价
     * <br/>
     * @return  {"errorCode":0,"data":{"purid":"1878","puserid":"481437","title":"\u6d4b\u8bd5","content":"111","quantity":"\u4e0d\u9650","offer":0,"endtime":"2015-03-28 00:00:00","suserid":"481437","username":"15101515776","name":"\u5218\u8273\u59ae","areasname":"\u5317\u4eac\u5e02,\u5e02\u8f96\u533a,\u671d\u9633\u533a,\u5efa\u5916\u8857\u9053\u529e\u4e8b\u5904,\u6c38\u5b89\u91cc\u4e1c\u793e\u533a\u5c45\u59d4\u4f1a","goods_unit":'斤'}}
     * <br/>
     *
     *   {
     *       "errorCode": 0,
     *       "data": {
     *           "purid(采购id)": "1878",
     *           "title(商品名称)": "测试",
     *           "content(采购简介)": "111",
     *           "quantity(采购数量)": "不限",
     *           "offer(报价信息)": 0,
     *           "endtime(报价截止时间)": "2015-03-28 00:00:00",
     *           "username(联系方式)": "15101515776",
     *           "name(采购人)": "刘艳妮",
     *           "areasname(供应地)": "北京市,市辖区,朝阳区,建外街道办事处,永安里东社区居委会",
     *           "goods_unit(单位)" :"斤"（元/ 自己加一下,
     *           "goods_unituid(单位id)":"1";
     *       }
     *   }
     * <br/>
     * <code>
     * post 传值
     * url http://www.5fengshou.com/api/quotation/get <br />
     * int pid 采购id <br />
     * </code>
     */
    
    public function getAction() 
    {   
        /**
         * 获取用户基本信息
         * @var [type]
         */
        $uid      = $this->getUid();
        $username = $this->getMobile();
        $name     = $this->getRealname();

        if(!$uid){
            $this->getMsg(parent::NOT_LOGGED_IN);
        }
        $cond     = array();
        $data     = array();
        $pid      = $this->request->getPOST('pid', 'int', 0 );

        $cond = array(
            " id = '{$pid}' "
        );
        $rs = M\Purchase::findFirst($cond);

        if (!$rs) $this->getMsg(parent::DATA_EMPTY);
        $data=$rs->toArray();
     
        $datas["purid"]=$data["id"];
        $datas["title"]=$data['title'];
        $datas['content'] = M\PurchaseContent::getPurchaseContent($data['id']);
        $datas['quantity'] = $data['quantity'] >0 ? $data['quantity'] . M\Purchase::$_goods_unit[$data['goods_unit']] : '不限';
        $datas['offer'] = M\PurchaseQuotation::countQuo($data['id']);
        $datas['endtime'] = date("Y-m-d H:i:s", $data['endtime']);
        $datas['goods_unit']=M\Purchase::$_goods_unit[$data["goods_unit"]];
        $datas['goods_unituid']=$data["goods_unit"];
        if($uid) {
        //获取用户基本信息
        $datas['username'] =$data["mobile"];
        $datas['name'] = $data["username"];
        $datas['areasname'] = M\UsersExt::getaddress($data["uid"], 1);
        } 
        
        $this->getMsg(parent::SUCCESS, $datas);
    }



    /**
     * 交易报价
     * @return string {"errorCode":0}  11128 不能对自己的商品进行报价   这个要提示用户
     * <code>
     * 
     * post 传值 
     *  @price(报价)
     *  @spec(规格要求) 
     *  @sellname(采购人姓名) 
     *  @sareas(最后选择的地址id)
     *  @sphone(采购人电话) 
     *  @purid(采购的id) 
     *   
     *   <br />
     * url http://www.5fengshou.com/api/quotation/saveoffer <br />
     * </code>
     */
    
    public function saveOfferAction() 
    {


        $purid = $this->request->getPost('purid', 'int', 0);
        $price = $this->request->getPost('price', 'float', 0.00);
        $spec = $this->request->getPost('spec', 'string', '');
        $sellname = $this->request->getPost('sellname', 'string', '');
        $sareas = $this->request->getPost('sareas', 'int', 0);
        $saddress = $this->request->getPost('saddress', 'string', '');
        $sphone = $this->request->getPost('sphone', 'string', '');
   
        if(!$price|| !$sellname || !$sareas || !$sphone) {
           $this->getMsg(parent::DATA_EMPTY);
        }
        $user_id = $this->getUid();
        $purchase = M\Purchase::findFirstByid($purid);
        
        if(!$purchase) {
            $this->getMsg(parent::DATA_EMPTY);
        }
        if($user_id == $purchase->uid) {
            $this->getMsg(parent::NOT_SELF_QUOTAION);     
        }
        $this->db->begin();
        try
        {
            $quotation = M\PurchaseQuotation::findFirst("purid='{$purid}' and suserid='{$user_id}' ");

            if(!$quotation) {
            $quotation = new M\PurchaseQuotation();
            }else{
             $this->getMsg(parent::PURORDER_ERROR);
            }
            $quotation->purid = $purid;
            $quotation->price = $price;
            $quotation->spec = $spec;
            $quotation->puserid = $purchase->uid;
            $quotation->purname = $purchase->username;
            $quotation->suserid = $user_id;
            $quotation->sellname = $sellname;
            $quotation->sareas = $sareas;
            $quotation->saddress = L\Func::getCols(M\AreasFull::getFamily($sareas), 'name', ',');
            $quotation->sphone = $sphone;

            $quotation->addtime = time();
            if(!$quotation->save()) throw new \Exception(parent::QUOTAION_ERROR);
            $this->db->commit();
            $flag = parent::SUCCESS;
        }
        catch(\Exception $e) 
        {
            $this->db->rollback();
            $flag = $e->getMessage();
        }
        $this->getMsg($flag);
    }
    /**
     * 查看报价-用户中心
     *   @return string  
     * <br />
     *       {
     *           "errorCode": 0,
     *           "data": {
     *               
     *               "purchase": {
     *                   "pur_sn采购编号": "pur0000001886",
     *                   "title商品名称": "123",
     *                   "quantity采购数量": "123.00",
     *                   "areas_name采购地区": "北京市",
     *                   "goods_unit": "公斤",(自己加元/),
     *                   "endtime报价截止时间": "2015-03-29 00:00:00",
     *                   "createtime发布时间": "2015-03-26 20:18:13",
     *                   "purchase_id采购id": "1886",
     *                   "goods_unitid单位id":'1',
     *                    "areas地址id":'1'
     *               },
     *               "quotation": [
     *                   {
     *                       "price报价": "20.00",
     *                       "saddress供应地": "河北省,石家庄市,桥东区,中山东路街道办事处,栗康街南居委会",
     *                       "sphone手机号": "13241754050",
     *                       "sellname姓名": "若冰",
     *                       "spec规格描述": "11",
     *                       "quotation_id报价id": "62",
     *                       "statecode":'状态码（1 已采购  0 未采购）',
     *                        "state":'已采购',
     *                   }
     *               ]
     *           }
     *       }
     * <br/>
     * <code>
     *  url http://www.5fengshou.com/api/quotation/lookquo <br />
     *  <br/>
     *  @int pid 采购id
     *  @int  p   当前页数 默认第一页
     *  @int  pagesize 当前条数 默认4条 最新的报价信息
     *  <br/>
     * </code>
     */
    public function lookquoAction()
    {
        $purid = $this->request->getPOST('pid', 'int', 0);
        $purchase = M\Purchase::findFirstByid($purid);
        $user_id = $this->getUid();
        $page = $this->request->getPost('p', 'int', 1);
        $page_size = $this->request->getPost('pagesize', 'int', 4);
       
        if (!$purchase || $purchase->uid != $user_id) {
              $this->getMsg(parent::DATA_EMPTY);
        }

        $where = array(" purid='{$purid}'");
        $where = implode(' and ', $where);
 
        $total = M\PurchaseQuotation::count($where);
       
        $offst = intval(($page - 1) * $page_size);
       
        $data = M\PurchaseQuotation::find($where."  ORDER BY addtime DESC limit {$offst} , {$page_size} ")->toArray();
        
        if(!$data){
           $this->getMsg(parent::DATA_EMPTY);
        }
        //采购的信息
        $purchase=$purchase->toArray();
        $purchases["pur_sn"]=$purchase['pur_sn'] ? $purchase['pur_sn'] : '';
        $purchases["title"]=$purchase['title'] ? $purchase['title'] : '';
        $purchases["quantity"]=$purchase['quantity'] > 0 ? $purchase['quantity'] : '不限';
        $purchases["areas_name"]=$purchase['areas_name'] ? L\Utils::getC($purchase['areas_name']) : '';
        $purchases['goods_unit']=M\Purchase::$_goods_unit[$purchase["goods_unit"]];
        $purchases['goods_unitid']=$purchase["goods_unit"];
        $purchases['areas']=$purchase["areas"];
        $purchases['endtime']=date("Y-m-d H:i:s",$purchase['endtime']);
        $purchases["createtime"]=date("Y-m-d H:i:s",$purchase['createtime']);
        $purchases["purchase_id"]=$purchase['id'];
       
        //报价的信息
        foreach ($data as $key => $value) {
            $quotation[$key]["price"]=$value['price'] ? $value['price'] : '';
            $quotation[$key]["saddress"]=$value['saddress'] ? $value['saddress'] : '';
            $quotation[$key]["sphone"]=$value['sphone']  ? $value['sphone'] : '';
            $quotation[$key]["sellname"]=$value['sellname'] ? $value['sellname'] : '';
            $quotation[$key]["spec"]=$value['spec'] ? $value['spec'] : '';
            $quotation[$key]["quotation_id"]=$value['id'];
            $quotation[$key]["statecode"]=$value['state'];
            $quotation[$key]["state"]=$value['state']==1 ? "已采购" : '确认采购';
        }

        $this->getMsg(parent::SUCCESS, array('purchase'=>$purchases,'quotation'=>$quotation));
    }
    /**
     * 确认采购-用户中心
     * @return string  {"errorCode":0}
     * <code>
     * url http://www.5fengshou.com/api/quotation/purchaseorder <br />
     *  <br />
     *  int quoid 报价id
     *  <br />
     * </code>
     */
    public function  purchaseorderAction(){
        $quoid = $this->request->getPOST('quoid', 'int', 0);
        $source = $this->request->getPOST('source', 'int', 4);
        $user_id = $this->getUid();
        //$user_id=470424;
        
        if(!$user_id){
            $this->getMsg(parent::NOT_LOGGED_IN);
        }
        $quotation = M\PurchaseQuotation::findFirstByid($quoid);
        
        if(!$quotation){
            $this->getMsg(parent::DATA_EMPTY);
        }
        if($quotation->state==1){
            $this->getMsg(parent::PURORDER_ERROR);
        }
        $purchase = M\Purchase::findFirstByid($quotation->purid);
        if(!$purchase){
            $this->getMsg(parent::DATA_EMPTY);
        }
      
        if ($user_id!=$quotation->puserid) {
              $this->getMsg(parent::QUOTATIONORDER_ERROR);
        }
        $this->db->begin();
        try
        {
            $order = new M\Orders();
            $order->purid = $quotation->purid;
            $order->puserid = $user_id;
            $order->purname = $quotation->purchase->username;
            $order->purphone = $quotation->purchase->mobile;
            $order->suserid = $quotation->suserid;
            $order->sname = $quotation->sellname;
            $order->sphone = $quotation->sphone;
            $order->areas = $purchase->areas;
            $order->areas_name = $purchase->address;
            $order->address = $purchase->address;
            $order->goods_name = $quotation->purchase->title;
            $order->price = $quotation->price;
            $order->quantity = $purchase->quantity;
            $order->goods_unit = $quotation->purchase->goods_unit;
            $order->total = $quotation->price * $purchase->quantity;
            $order->addtime = $order->updatetime = time();
            $order->state = 3;
            $order->source = $source;
            if(!$order->save()){
                throw new \Exception(parent::QUOTATIONORDER_ERROR);
            } 

            #订单号 ， 拼接随机数
            $random = date('Ym',time()). L\Func::random(4,1);
            $order->order_sn = sprintf('mdg%09u', $order->id . $random );
            
            // $order->order_sn = sprintf('mdg%09u', $order->id);
            if(!$order->save()){
                throw new \Exception(parent::QUOTATIONORDER_ERROR);
            }
            if(!M\OrdersLog::insertLog($order->id, 3, $user_id, $this->getMobile(), 0, $demo='供应商确认订单')){
                throw new \Exception(parent::QUOTATIONORDER_ERROR);
            }   
            $this->db->commit();
            $flag = parent::SUCCESS;
        }
        catch(\Exception $e) 
        {
            $this->db->rollback();
            $flag = $e->getMessage();
        }
        if($flag==0){
            $quotation->state = 1;
            $quotation->save();
        }
        $this->getMsg($flag);
    }  


     /**
     * 报价详情
     *   @return string  
     * 
     *  url http://www.5fengshou.com/api/quotation/lookDetail <br />
     *  <br/>
     *  @int pid 采购id
     *  @int  p   当前页数 默认第一页
     *  @int  pagesize 当前条数 默认4条 最新的报价信息
     *  <br/>
     * </code>
     */
    public function lookDetailAction()
    {
        $purid = $this->request->getPOST('pid', 'int', 0);
        $purchase = M\Purchase::findFirstByid($purid);
        $user_id = $this->getUid();

        if (!$purchase || $purchase->uid != $user_id) {
              $this->getMsg(parent::DATA_EMPTY);
        }
        
        //采购的信息
        $purchase=$purchase->toArray();
        $purchases["pur_sn"]=$purchase['pur_sn'] ? $purchase['pur_sn'] : '';
        $purchases["title"]=$purchase['title'] ? $purchase['title'] : '';
        $purchases["quantity"]=$purchase['quantity'] > 0 ? $purchase['quantity'] : '不限';
        $purchases["areas_name"]=$purchase['areas_name'] ? L\Utils::getC($purchase['areas_name']) : '';
        $purchases['goods_unit']=M\Purchase::$_goods_unit[$purchase["goods_unit"]];
        $purchases['goods_unitid']=$purchase["goods_unit"];
        $purchases['areas']=$purchase["areas"];
        $purchases['endtime']=date("Y-m-d H:i:s",$purchase['endtime']);
        $purchases["createtime"]=date("Y-m-d H:i:s",$purchase['createtime']);
        $spec=M\PurchaseContent::findFirst("purid = ".$purid);
        $purchases["spec"]=$spec->content ? $spec->content : '';
        //$purchases["spec"] = $purchase['spec'] ? $purchase['spec'] : '';
        $purchases["purchase_id"]=$purchase['id'];
        $check=M\PurchaseCheck::findFirst("purchase_id = '{$purid}' order by add_time desc");
        $purchases["fail_reason"]=$check->fail_reason ? $check->fail_reason : '';
       

        $this->getMsg(parent::SUCCESS, array('purchase'=>$purchases));
    }


     /*public function saveconquoAction() {

        $quoid = $this->request->getPost('quoid', 'int', 0);
        $user_id = $this->getUid();
        $quotation = M\PurchaseQuotation::findFirstByid($quoid);
       
        if($user_id != $quotation->puserid) {
            $this->flash->error("不能采购此报价！");
            return $this->dispatcher->forward(array(
                "controller" => "msg",
                "action" => "showmsg",
            )); 
        }
        
        $purphone = $this->request->getPost('purphone', 'string', '');
        $quantity = $this->request->getPost('quantity', 'float', 0.00);
        $purname = $this->request->getPost('purname', 'string', '');
        $areas = $this->request->getPost('areas', 'int', 0);
        //echo $purphone;die;
        if(!$purphone || !$quantity || !$purname || !$areas) {
            $this->flash->error("信息不完整，请完善信息后提交！");
            return $this->dispatcher->forward(array(
                "controller" => "msg",
                "action" => "showmsg",
            )); 
        }

        $order = new M\Orders();
        $order->purid = $quotation->purid;
        $order->puserid = $user_id;
        $order->purname = $purname;
        $order->purphone = $purphone;
        $order->suserid = $quotation->suserid;
        $order->sname = $quotation->sellname;
        $order->sphone = $quotation->sphone;
        $order->areas = $areas;
        $order->address = L\Func::getCols(M\AreasFull::getFamily($areas), 'name', ',');
        $order->goods_name = $quotation->purchase->title;
        $order->price = $quotation->price;
        $order->quantity = $quantity;
        $order->goods_unit = $quotation->purchase->goods_unit;
        $order->total = $order->price * $order->quantity;
        $order->addtime = $order->updatetime = time();
        $order->state = 2;
        $order->areas_name = L\Func::getCols(M\AreasFull::getFamily($areas), 'name', ',');
        if(!$order->save()) {

            foreach($order->getMessages() as $msg){
                print_r($msg);
            }
   
        }

        $quotation->state = 1;
        $quotation->save();

        $order->order_sn = sprintf('mdg%09u', $order->id);
        $order->save();
        
    }
*/

    public function saveconquoAction(){

        $quoid = $this->request->getPOST('quoid', 'int', 0);
        $source = $this->request->getPOST('source', 'int', 4);
        $user_id = $this->getUid();
        //$user_id=470424;
        $purphone = $this->request->getPost('purphone', 'string', '');
        $quantity = $this->request->getPost('quantity', 'float', 0.00);
        $purname = $this->request->getPost('purname', 'string', '');
        $areas = $this->request->getPost('areas', 'int', 0);
        $engineer_name = $this->request->getPOST('engineer_name', 'string', '');
        $engineer_phone = $this->request->getPOST('engineer_phone', 'string', '');
        $engineer_id = $this->request->getPOST('engineer_id', 'int', 0);
        
        
        if(!$user_id){
            $this->getMsg(parent::NOT_LOGGED_IN);
        }
        $quotation = M\PurchaseQuotation::findFirstByid($quoid);
        
        if(!$quotation){
            $this->getMsg(parent::DATA_EMPTY);
        }
        if($quotation->state==1){
            $this->getMsg(parent::PURORDER_ERROR);
        }
        $purchase = M\Purchase::findFirstByid($quotation->purid);
        if(!$purchase){
            $this->getMsg(parent::DATA_EMPTY);
        }
      
        if ($user_id!=$quotation->puserid) {
              $this->getMsg(parent::QUOTATIONORDER_ERROR);
        }
        $this->db->begin();

        try
        {
            $order = new M\Orders();
            $order->purid = $quotation->purid;
            $order->puserid = $user_id;
            $order->purname = $purname;
            $order->purphone = $purphone;
            $order->suserid = $quotation->suserid;
            $order->sname = $quotation->sellname;
            $order->sphone = $quotation->sphone;
            $order->areas = $areas;
            $order->areas_name = L\Func::getCols(M\AreasFull::getFamily($areas), 'name', ',');
            $order->address = L\Func::getCols(M\AreasFull::getFamily($areas), 'name', ',');
            $order->goods_name = $quotation->purchase->title;
            $order->price = $quotation->price;
            $order->quantity = $quantity;
            $order->goods_unit = $quotation->purchase->goods_unit;
            $order->total = $order->price * $order->quantity;
            $order->addtime = $order->updatetime = time();
            $order->state = 3;
            $order->source = 5;
            if(!$order->save()){
                throw new \Exception(parent::QUOTATIONORDER_ERROR);
            } 

            #订单号 ， 拼接随机数
            $random = date('Ym',time()). L\Func::random(4,1);
            $order->order_sn = sprintf('mdg%09u', $order->id . $random );
            
            // $order->order_sn = sprintf('mdg%09u', $order->id);
            if(!$order->save()){
                throw new \Exception(parent::QUOTATIONORDER_ERROR);
            }
            if(!M\OrdersLog::insertLog($order->id, 3, $user_id, $this->getMobile(), 0, $demo='供应商确认订单')){
                throw new \Exception(parent::QUOTATIONORDER_ERROR);
            }

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


            $this->db->commit();
            $flag = parent::SUCCESS;
        }
        catch(\Exception $e) 
        {
            $this->db->rollback();
            $flag = $e->getMessage();
        }
        if($flag==0){
            $quotation->state = 1;
            $quotation->save();
        }
        $this->getMsg($flag);

    }
}
?>