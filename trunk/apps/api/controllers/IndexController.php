<?php
namespace Mdg\Api\Controllers;
use Phalcon\Mvc\Controller;
use Mdg\Models\AreasFull as mAreas;
use Lib as L;
use Mdg\Models as M;
/**
 *   获取地址  获取分类  获取单位  获取上市时间  成交动态展示 首页数据提醒  搜索 资讯列表  资讯详情
 */
class IndexController extends ControllerBase
{
	
    
    public function indexAction(){
        $user_id = $this->getUid();
  
        if(!$user_id){
            $this->getMsg(parent::NOT_LOGGED_IN);
        }
    }
    public function testAction(){

    }

     /**
	 * 获取地址
	 * @return string  {"errorCode":0,"data":{"member":{"id":"地址id","name":"地址名称"}}}
	 * <br />
	 * <code>
	 * post 传值 
	 * int pid 选中地址的id (默认一级) (可选)
	 * url http://www.5fengshou.com/api/index/getarea <br />
	 * </code>
	 */
    public function  getareaAction(){
        $member=array();
    	$pid = $this->request->getPOST('pid', 'int', 0 );
    	$areas= M\AreasFull::find("pid={$pid} and is_show=1 ")->toArray();
        if(!$areas){
          $this->getMsg(parent::DATA_EMPTY);
        }
        foreach ($areas as $key => $value) {
	        $member[$key]["id"]=$value['id'];
	    	$member[$key]["name"]=$value['name'];
        }
        
        $this->getMsg(parent::SUCCESS, array('member'=>$member));
    }
    /**
     * 获取分类
     * @return string  {"errorCode":0,"data":{"member":{"id":"分类id","name":"分类名称"}}}
     * <br />
     * <code>
     * post 传值 
     * int pid 一级分类的id (默认一级) (可选)
     * url http://www.5fengshou.com/api/index/getcate <br />
     * </code>
     */
    public function  getcateAction(){
        $pid = $this->request->getPost('pid', 'int', 0);
        $member=array();
        $areas= M\Newcategory::find("parent_id={$pid} and is_show=1 ")->toArray();
        if(!$areas){
          $this->getMsg(parent::DATA_EMPTY);
        }
        foreach ($areas as $key => $value) {
            $member[$key]["id"]=$value["id"];
            $member[$key]["name"]=$value["title"];
        }
        $this->getMsg(parent::SUCCESS, array('member'=>$member));
    }
    /**
     * 获取新版分类
     * @return string  {"errorCode":0,"data":{"member":{"id":"分类id","name":"分类名称"}}}
     * <br />
     * <code>
     * post 传值 
     * int pid 一级分类的id (默认一级) (可选)
     * url http://www.5fengshou.com/api/index/getnewcate <br />
     * </code>
     */
    public function  getnewcateAction(){
        $pid = $this->request->getPost('pid', 'int', 0);
        $member=array();
        $areas= M\Newcategory::find("parent_id={$pid} and is_show=1 ")->toArray();
        if(!$areas){
          $this->getMsg(parent::DATA_EMPTY);
        }
        foreach ($areas as $key => $value) {
            $member[$key]["id"]=$value["id"];
            $member[$key]["name"]=$value["title"];
        }
        $this->getMsg(parent::SUCCESS, array('member'=>$member));
    }
    /**
     * 获取单位(可缓冲在本地)
     * @return string  {"errorCode":0,"data":{"member":{"key":"单位id","value":"单位名称"}}}
     * <br />
     * <code> 
     * url http://www.5fengshou.com/api/index/getgoods_unit <br />
     * </code>
     */
    public function getgoods_unitAction(){
        $member=M\Purchase::$_goods_unit;
        foreach ($member as $key => $value) {
            $members[$key]["key"]=$key;
            $members[$key]["value"]=$value;
        }
        $this->getMsg(parent::SUCCESS, array('member'=>$members));
    }
     /**
     * 获取上市时间(可缓冲在本地)
     * @return string  {"errorCode":0,"data":{"member":{"key":"时间id","value":"时间名称"}}}
     * <br />
     * <code> 
     * url http://www.5fengshou.com/api/index/gettime <br />
     * </code>
     */
    public function gettimeAction(){
        $member=M\Sell::$type;
         foreach ($member as $key => $value) {
            $members[$key]["key"]=$key;
            $members[$key]["value"]=$value;
        }
        $this->getMsg(parent::SUCCESS, array('member'=>$members));
    }
    /**
	 * 成交动态展示
	 * @return string  {"errorCode":0}  
	 * <br />
     *       {
     *           "errorCode": 0,
     *           "data": {
     *               "member": [
     *                   {
     *                       "pubtime(成交时间)": "1 小时前",
     *                       "purname(姓名)": "吕超",
     *                       "price(采购单价)":"5.00"
     *                       "quantity(采购量)": "50",
     *                       "goods_unit(单位)": "元/吨",
     *                       "goods_name(商品名称)": "小麦"
     *                   }
     *               ]
     *           }
     *       }
     * <br/>
	 * <code>
	 * post 传值 
	 * p   当前页数 (可选) 默认第一页
	 * pagesize 每页显示几条(可选)  默认显示20条  
	 * url http://www.5fengshou.com/api/index/order <br />
	 * </code>
	 */
    public function orderAction(){
    	$page = $this->request->getPost('p', 'int', 1);
        $page_size = $this->request->getPost('pagesize', 'int', 20);
        $offst = intval(($page - 1) * $page_size);
    	$orders = M\Orders::find(" 1 ORDER BY updatetime DESC limit {$offst},{$page_size} ")->toArray();
        if(!$orders){
        	$this->getMsg(parent::DATA_EMPTY);
        }
        $ord=array(); 
        foreach ($orders as $key=>$value) {

            $time = new L\Time(time(), $value['addtime']);
            $ord[$key]['pubtime'] = $time->transform();
            $ord[$key]['price'] =  $value["price"];
            $ord[$key]['purname'] = $value['purname'];
            $ord[$key]['quantity'] = $value['quantity'] > 0 ? $value['quantity'] : '不限';
            $ord[$key]['goods_unit']=M\Purchase::$_goods_unit[$value["goods_unit"]];
            $ord[$key]['goods_name'] = $value['goods_name'];
        }
        $this->getMsg(parent::SUCCESS, array('member'=>$ord));
     
    }
     /**
     * 首页数据提醒
     * @return string   {"errorCode":0,"data":{"sellcount":"0","purchasecount":"0","ordercount":"19","articlecount":0}}
     * <br />
     * <code>
     * url http://www.5fengshou.com/api/index/main <br />
     * </code>
     */
    public function mainAction() 
    {   
        $time=strtotime(date("Y-m-d",strtotime("-1 day")));
      
        $sellcount = M\Sell::count("createtime>='$time'");
        $purchasecount = M\Purchase::count("createtime>='$time'");
        $ordercount = M\Orders::count("state=4");
        $articlecount=M\Advisory::count("is_show=1");
        $ad_img = M\Ad::find('position=2 and is_show=1')->toArray();
        foreach ($ad_img as $key => $value) {
            $img[$key]["imgpath"]=IMG_URL.$value["imgpath"];
        }
        $category=M\Category::find("parent_id=1 limit 12")->toArray();
        foreach ($category as $key => $value) {
          $categorys[$key]["title"]=$value["title"];
        }
        $this->getMsg(parent::SUCCESS, array('sellcount'=>$sellcount,'purchasecount'=>$purchasecount,
        'ordercount'=>$ordercount,'articlecount'=>$articlecount,'img'=>$img,'category'=>$categorys));
    }
    /**
     * 发布  @return string   {"errorCode":0,"data":{"sellcount":"0","purchasecount":"0"}}
     * <br />
     * <code>
     * url http://www.5fengshou.com/api/index/publish <br />
     * </code>
     */
    public function publishAction() 
    {   
         $time=time();
         $sellcount = M\Sell::count();
         $purchasecount = M\Purchase::count();
        $this->getMsg(parent::SUCCESS, array('sellcount'=>$sellcount,'purchasecount'=>$purchasecount));
    }
    /**
     * 普通搜索  高级搜索  @return 
     * <br />
     * 
     * {  
     *    type=1：
     *    "errorCode":0,
     *    数据样例:
     *    "data":{"member":[{"goodsName":"\u5c0f\u9ea6\u3001\u7389\u7c73",
     *          "supplyAmount":"5000000.00",
     *           "minAmount":"6000",
     *           "supplyArea":"\u5c71\u4e1c\u7701",
     *           "goods_unit":"\u5143\/\u516c\u65a4",
     *           "unitPrice":"2.00~2.40",
     *           "id":"138814",
     *           "quotedTm":"5 \u5c0f\u65f6\u524d"}
     *    ]}}
     *    example:
     *       {
     *       "errorCode": 0,
     *       "data": {
     *           "member": [
     *               {
    *                       "goodsName(品名)": "蒜苔",
    *                       "supplyAmount(供应量)": "不限",
    *                       "minAmount(起定量)": "不限",
    *                       "supplyArea(供应地)": "江苏省",
    *                       "goods_unit(单位)": "元/公斤",
    *                       "unitPrice(单价)": "2.00~4.00",
    *                       "id(供应id)": "138769",
    *                       "quotedTm(报价时间)": "2 小时前"
     *               }
     *           ]
     *       }
     *  }
     *  type=2：
     *   {"errorCode":0,
     *   "data":{"member":[{"pid":"1921",
     *   "buyerName":"\u6731\u5fb7\u541b",
     *   "goodsName":"\u5927\u8c46 \u7389\u7c73",
     *   "purcArea":"\u9ed1\u9f99\u6c5f\u7701,\u9ed1\u6cb3\u5e02,\u4e94\u5927\u8fde\u6c60\u5e02,
     *   \u9752\u5c71\u8857\u9053\u529e\u4e8b\u5904,\u798f\u5eb7\u793e\u533a",
     *  "quotedNum":0,
     *  "endtime":"2015-04-08 00:00:00"}]}}
     *    example:
     *       {
     *           "errorCode": 0,
     *           "data": {
     *               "member": [
     *                   {
     *                      'pid(采购id)':'13'
     *                   "buyerName(采购商)": "李经理",
     *                   "goodsName(采购商品)": "玉米",
     *                   "purcArea(采购地区)": "湖北省武汉市黄陂区双凤大道327号",
     *                   "quotedNum(报价人数)": 0
     *                   "endtime(报价截止时间)":'2015-04-08 00:00:00',
     *                    }
     *               ]
     *           }
     *       }
     *  <br />
     *  
     * <code>
     *  <br />
     * int   p 当前页数
     * int  type  类型  1 供应商   2 采购商  默认供应商
     * int  cate  分类id   
     * int  area  地址id
     * pagesize   每页几条       默认10条
     * keyword    关键字  
     *  <br />
     * url http://www.5fengshou.com/api/index/search <br />
     * </code>
     * 
     */
    
  public function searchAction() 
    {   
        $page = $this->request->getPOST('p', 'int', 1);
        $type = $this->request->getPOST('type', 'int', 1);
        $cate = $this->request->getPOST('cate', 'int', 0);
        $area = $this->request->getPOST('area', 'int', 0);
        $page_size = $this->request->getPOST('pagesize', 'int', 40);
        $keyword = $this->request->getPOST('keyword', 'string', '');
        
        //基本条件
        $where = array('state = 1 and is_del = 0');

        //地区搜索
        $info = M\Areas::findFirstByarea_id($area);
        
        if($cate) {
            $where[] = " maxcategory = '{$cate}'";
            // $cids = M\Category::getChild($cate);
            // $cids = !empty($cids) ? $cids : array(0);
            // $where[] = " category in (".implode(',', $cids).") ";
        }
        if($info) {   
            $where[] = " areas in ({$info->child}) ";
        }
        if($keyword) {
            $where[] = " title like '%{$keyword}%' ";
        }

        $where = implode(' and ', $where);
        $search =array();
        $offst = intval(($page - 1) * $page_size);

        if($type==1){
                $total = M\Sell::count($where);
                $data = M\Sell::find($where."  ORDER BY updatetime DESC limit {$offst} , {$page_size} ")->toArray();
                if(!$data){
                   $this->getMsg(parent::DATA_EMPTY);
                }
                foreach ($data as $key => $value) {
                    $search[$key]["goodsName"]=$value['title'] ? $value['title'] : '';
                    $search[$key]["supplyAmount"]=$value['quantity'] > 0 ? $value['quantity'] : '不限';
                    $search[$key]["minAmount"]=$value['min_number'] > 0 ? $value['min_number'] : '不限';
                    $search[$key]["supplyArea"]=$value['areas_name'] ? L\Utils::getC($value['areas_name']) : '';
                    $search[$key]['goods_unit']=M\Purchase::$_goods_unit[$value["goods_unit"]];
                    $search[$key]["unitPrice"]=$value['min_price'].'~'.$value['max_price'];
                    $search[$key]["id"]=$value['id'];
                    $time = new L\Time(time() , $value['updatetime']);
                    $search[$key]['quotedTm'] = $time->transform();
                }
        }
        if($type==2){
                $where.= ' and endtime >= '.strtotime(date('Y-m-d'));
                $total = M\Purchase::count($where);     
                $data = M\Purchase::find($where."  ORDER BY updatetime DESC limit {$offst} , {$page_size} ")->toArray();
                if(!$data){
                   $this->getMsg(parent::DATA_EMPTY);
                }
                foreach ($data as $key => $val) {
                    $search[$key]['pid'] = $val['id'] ? $val['id'] : '';
                    $search[$key]['buyerName'] = $val['username'] ? $val['username'] : '';
                    $search[$key]['goodsName'] = $val['title'] ? $val['title'] : '';
                    $search[$key]['purcArea'] = $val['address'] ? $val['address'] : '';
                    $search[$key]['quotedNum'] =  M\PurchaseQuotation::countQuo($val['id']);
                    $search[$key]['endtime'] =  date("Y-m-d H:i:s",$val['endtime']);
                }
        }
      
        if(!$search) {
           $this->getMsg(parent::DATA_EMPTY);
        }
        $this->getMsg(parent::SUCCESS,array('member'=>$search));
    }
     /**
     * 获取地址 分类 最新版本号  @return string  
     *  {"errorCode":{"cate":"1","area":"1"}}
     * <br />
     * <code>
     * url http://www.5fengshou.com/api/index/version <br />
     * </code>
     */
    public function  versionAction(){
          $version=M\Ifversion::getversion();
          $this->getMsg($version);
    }
     /**
     *  反馈建议  @return string  
     *  {"errorCode":{"cate":"1","area":"1"}}
     * <br />
     * <code>
     * 1 界面意见 2 您的新需求 3 操作意见 4 流程问题 5 其他
     * url http://www.5fengshou.com/api/index/feedback <br />
     * </code>
     */
    public function feedbackAction(){
        $type = $this->request->get('type', 'int', 1);
        $content = $this->request->get('content', 'string','');

        if(!$type||!$content){
            $this->getMsg(parent::PARAMS_ERROR);
        }
        $sql="insert into feedback(type,content) values ($type,'$content')";
     
        $db =$GLOBALS['di']['db'];
        if(!$db->execute($sql)){
             $this->getMsg(parent::Feedback_ERROR);
        } 
        $this->getMsg(parent::SUCCESS);
    }
     /**
     *  心跳更新操作  @return string  
     *  {"errorCode":0}
     * <br />
     * <code>
     * post 请求   
     * url http://www.5fengshou.com/api/index/token <br />
     * </code>
     */
    public function tokenAction(){
        $user_id = $this->getUid();
        if(!$user_id) 
        {
            $this->getMsg(parent::NOT_LOGGED_IN);
        }
        $this->getMsg(parent::SUCCESS);
    }

    /**
     *  资讯列表  @return string  {"errorCode":0}
     * <br />
     * <code>
     *        {
     *           "errorCode": 0,
     *           "data": {
     *               "member": [
     *                   {
     *                       "title(资讯标题)": "测试1",
     *                       "id(资讯id)": "2",
     *                       "description(资讯描述)": "111111111111111111111",
     *                       "addtime(添加时间)": "10:09",
     *                       "catename(分类名称)": "系统分类",
     *                       'img(图片路径)',
     *                       'href'=>'http://mdgdev.ync365.com/api/index/advisoryinfo?id=30',
     *                   }
     *               ]
     *           }
     *       }
     * url http://www.5fengshou.com/api/index/advisory <br />
     * </code>
     */
 public function advisoryAction(){
        
        $page = $this->request->getPOST('p', 'int', 1);
        $page_size = $this->request->getPOST('pagesize', 'int', 20);
        $offst = intval(($page - 1) * $page_size);
    
        $where=' is_show=1 ';
        $total = M\Advisory::count($where);

        $advisory=M\Advisory::find($where." order by addtime desc  limit {$offst},{$page_size} ")->toArray();
        if(!$advisory){
            $this->getMsg(parent::DATA_EMPTY);
        }
        $img='';
        $advisorys=array(); 
        foreach ($advisory as $key => $value) {
            $advisorys[$key]["catename"]=M\Advisory::returncategory($value["cid"]);
            $advisorys[$key]["addtime"]=date("H:i",$value["addtime"]);
            $advisorys[$key]["title"]=$value["title"];
            $advisorys[$key]["id"]=$value["id"];
            $advisorys[$key]["description"]=$value["description"];
            $advisorys[$key]["href"]="http://www.5fengshou.com/api/index/advisoryinfo?id=".$value["id"];
            if(isset($value["content"])&&$value["content"]!=''){
                 $pattern="/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg]))[\'|\"].*?[\/]?>/"; 
                 preg_match_all($pattern,$value["content"],$match); 
                 if(isset($match[1][0])){
                    $img=$match[1][0];
                    if($img){
                        $advisorys[$key]["img"]=$img;
                    }else{
                        $advisorys[$key]["img"]='';
                    }
                    
                 }else{
                     $advisorys[$key]["img"]='';
                 }
            }

        }
        $this->getMsg(parent::SUCCESS,array('member'=>$advisorys));
    }

      /**
     *  资讯详情  @return string  
     * <br />
     * <code>
     *       {
     *           "errorCode": 0,
     *           "data": {
     *               "member": {
     *                   "catename(资讯分类)": "顶级分类",
     *                   "title(资讯标题)": "1",
     *                   "content(资讯内容)": "<p>这是咨询内容</p>"
     *               }
     *           }
     *       }
     * url http://www.5fengshou.com/api/index/advisoryinfo <br />
     * </code>
     */
    public function advisoryinfoAction(){
      
        $id = $this->request->get('id', 'int', 0);
    
        if(!$id){
            $this->getMsg(parent::PARAMS_ERROR);
        }
        $advisory=M\Advisory::findFirst("id=$id and is_show=1 ")->toArray();
     
        if(!$advisory){
           $advisory=array();
        }

        $this->view->advisory=$advisory;
    }
     /**
     * 用户注册协议
     * @return   {"errorCode":0}   
     * <br />
     * <code>
     * <br />
     * post 传值 
     * url http://mdg.ync365.com/api/register/agreement <br />
     * string mobile 要检测的手机号 <br />
     * </code>
     */
    public function agreementAction(){
         $article=M\Article::findFirst(" type=1 and is_show=1 ")->toArray();
         $member["content"]=$article["content"];
         $member["title"]=$article["title"];
         $this->view->member=$member;
    }
    public function abountAction(){
        
    }
    public function getcountAction(){ 
        $type = $this->request->getPOST('type', 'int', 0);
        $sql="update app_count set count=count+1 where type={$type} ";
        $db = $GLOBALS['di']['db'];
        if($db->execute($sql)){
            $this->getMsg(parent::SUCCESS);
        }
    }
     /**应用推荐 @return   
      <br />
                    
     *        {
     *           "errorCode": 0,
     *           "data": {
     *               "member": [
     *                   {
     *                       "id": "1",
     *                       "type": "1",
     *                       "count": "17",
     *                       "title": "乡间货的",
     *                       "desc": "让乡间没用难运的货",
     *                       "img_path": "http://yncmdg.b0.upaiyun.com//upload/app/xiangjianhuodi.png",
     *                       "url": "http://www.365huodi.com/download/xjhd_hz.apk",
     *                       "uploadcont": "0",
     *                       "content": "让乡间没用难运的货",
     *                       "version": "1.0",
     *                       "detailed_img": [
     *                           "http://yncmdg.b0.upaiyun.com//upload/app/xiangjianhuodi/xiangjianhuodi1.png",
     *                           "http://yncmdg.b0.upaiyun.com//upload/app/xiangjianhuodi/xiangjianhuodi2.png"
     *                       ],
     *                       "app_size": "5.7"
     *                   }
     *               ]
     *           }
     *       }

     * <br />
     * <code>
     * <br />
     * post 传值 
     * url http://www.5fengshou.com/api/index/gettype <br />
     * string mobile 要检测的手机号 <br />
     * </code>
     */
    public function  gettypeAction(){
        $sql="select * from  app_count where id in (1,4,5) ";
        $db = $GLOBALS['di']['db'];

        $rs=$db->fetchAll($sql,2);
        $img=M\Image::find(" type in (100,101,102,103,104) and  state=1 ")->toArray();
        foreach ($img as $key => $value) {
            $arr[$value["gid"]][]=IMG_URL.$value['agreement_image'];
        }
        foreach ($rs as $key => $value) {
            $rs[$key]["img_path"]=IMG_URL.$value["img_path"];
            $rs[$key]["detailed_img"]=$arr[$value["id"]];
        }
        $this->getMsg(parent::SUCCESS,array('member'=>$rs));
    }
    public function downloadAction(){
        $author=isset($_GET["author"]) ? $_GET["author"] : "" ;
        $db = $GLOBALS['di']['db'];
        
        $res = self::isWeixin();
        if(!$res){
            if(isset($author)&&$author!=''){
                $insert="insert into app_author(type,author) values (5,'$author') ";
                $db->execute($insert);
            }
            $sql="update app_count set uploadcont=uploadcont+1 where type=5 ";
            $db->execute($sql);
            echo "<script>location.href='http://sheguanjia.com/apk/shegj.apk'</script>";
            exit;
        }
       
       
    }
    public function isWeixin(){ 
        return strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'micromessenger') ? true : false ; 
    }

    
    public function newsearchAction() 
    {   
        $page = $this->request->getPOST('p', 'int', 1);
        $type = $this->request->getPOST('type', 'int', 1);
        $cate = $this->request->getPOST('cate', 'int', 0);
        $area = $this->request->getPOST('area', 'int', 0);
        $page_size = $this->request->getPOST('pagesize', 'int', 40);
        $keyword = $this->request->getPOST('keyword', 'string', '');
        
        //基本条件
        $where = array('state = 1 and is_del = 0');

        //地区搜索
        $info = M\Areas::findFirstByarea_id($area);
        
        if($cate) {
            $where[] = " category = '{$cate}'";
            // $cids = M\Category::getChild($cate);
            // $cids = !empty($cids) ? $cids : array(0);
            // $where[] = " category in (".implode(',', $cids).") ";
        }
        if($info) {   
            $data = M\AreasFull::getFamily($area);
            $areas = array_column($data, 'name');
            $areas = join('', $areas);
            $where[] = " full_address like '{$areas}%'";
            //$where[] = " areas in ({$info->child}) ";
        }
        if($keyword) {
            $where[] = " title like '%{$keyword}%' ";
        }

        $where = implode(' and ', $where);
        $search =array();
        $offst = intval(($page - 1) * $page_size);

        if($type==1){
                $total = M\Sell::count($where);
                $data = M\Sell::find($where."  ORDER BY updatetime DESC limit {$offst} , {$page_size} ")->toArray();
                if(!$data){
                   $this->getMsg(parent::DATA_EMPTY);
                }
                foreach ($data as $key => $value) {
                    $search[$key]["goodsName"]=$value['title'] ? $value['title'] : '';
                    $search[$key]["supplyAmount"]=$value['quantity'] > 0 ? $value['quantity'] : '不限';
                    $search[$key]["minAmount"]=$value['min_number'] > 0 ? $value['min_number'] : '不限';
                    $search[$key]["supplyArea"]=$value['areas_name'] ? L\Utils::getC($value['areas_name']) : '';
                    $search[$key]['goods_unit']=M\Purchase::$_goods_unit[$value["goods_unit"]];
                    $search[$key]["unitPrice"]=$value['min_price'].'~'.$value['max_price'];
                    $search[$key]["id"]=$value['id'];
                    $time = new L\Time(time() , $value['updatetime']);
                    $search[$key]['quotedTm'] = $time->transform();
                }
        }
        if($type==2){
                $where.= ' and endtime >= '.strtotime(date('Y-m-d'));
                $total = M\Purchase::count($where);     
                $data = M\Purchase::find($where."  ORDER BY updatetime DESC limit {$offst} , {$page_size} ")->toArray();
                if(!$data){
                   $this->getMsg(parent::DATA_EMPTY);
                }
                foreach ($data as $key => $val) {
                    $search[$key]['pid'] = $val['id'] ? $val['id'] : '';
                    $search[$key]['buyerName'] = $val['username'] ? $val['username'] : '';
                    $search[$key]['goodsName'] = $val['title'] ? $val['title'] : '';
                    $search[$key]['purcArea'] = $val['address'] ? $val['address'] : '';
                    $search[$key]['quotedNum'] =  M\PurchaseQuotation::countQuo($val['id']);
                    $search[$key]['endtime'] =  date("Y-m-d H:i:s",$val['endtime']);
                }
        }
      
        if(!$search) {
           $this->getMsg(parent::DATA_EMPTY);
        }
        $this->getMsg(parent::SUCCESS,array('member'=>$search));
    }


    public function  wxgetcateAction(){
        $pid = $this->request->getPost('pid', 'int', 0);
        $member=array();
        $areas= M\Category::find("parent_id={$pid} and is_show=1 ")->toArray();
        if(!$areas){
          $this->getMsg(parent::DATA_EMPTY);
        }
        foreach ($areas as $key => $value) {
            $member[$key]["id"]=$value["id"];
            $member[$key]["name"]=$value["title"];
        }
        $this->getMsg(parent::SUCCESS, array('member'=>$member));
    }

}

