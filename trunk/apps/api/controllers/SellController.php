<?php
namespace Mdg\Api\Controllers;

use Lib\Member as Member,
    Lib\Auth as Auth,
    Lib\SMS as sms,
    Lib\Utils as Utils;
use Mdg\Models\Users as Users;
use Mdg\Models\UsersExt as Ext;
use Mdg\Models\YncUsers as yncuser;
use Mdg\Models\AreasFull as mAreas;
use Lib as L;
use Mdg\Models as M;
use Lib\Validator as V;
/**
 *   供应 相关接口  
 */
class SellController extends ControllerBase
{    
     /**
     * 最新供应接口 
     * @return string  {"errorCode":0,"data":{"member":[{"goodsName":"\u6843",
     * "supplyAmount":"5000.00","minAmount":"100",
     * "supplyArea":"\u5c71\u4e1c\u7701",
     * "goods_unit":"\u5143\/\u516c\u65a4",
     * "unitPrice":"5.00~6.00",
     * "id":"138789",
     * "quotedTm":"5 \u5c0f\u65f6\u524d"}]}}
     * <br/>
     *       {
     *           "errorCode": 0,
     *           "data": {
     *               "member": [
     *                   {
     *                       "goodsName(品名)": "蒜苔",
     *                       "supplyAmount(供应量)": "不限",
     *                       "minAmount(起定量)": "不限",
     *                       "supplyArea(供应地)": "江苏省",
     *                       "goods_unit(单位)": "公斤",自己加 元/
     *                       "goods_unitid(单位id)":"123",
     *                       "areas地址id"=>'1',
     *                       "unitPrice(单价)": "2.00~4.00",
     *                       "id(供应id)": "138769",
     *                       "quotedTm(报价时间)": "2 小时前"
     *                   },
     * 
     *               ]
     *           }
     *       }
     *
     * <br/>
     * <code>
     * post 传值 
     * <br/>
     * int p 当前页数 默认第一页
     * pagesize 每页显示几条  默认4条
     * keyword  关键字搜索(有搜索条件时可选)
     * cate 分类的id (可选)
     * area 地址的id (可选)
     * <br/>
     * url http://www.5fengshou.com/api/sell/selllist <br />
     * </code>
     */
    public function selllistAction()
    {
       
        $page = $this->request->getPost('p', 'int', 1);
        $cate = $this->request->getPost('cate', 'int',0);
        $area = $this->request->getPost('area', 'int', 1659);
        $sellid = $this->request->getPost('sellid', 'int', 0);
        $page_size = $this->request->getPost('pagesize', 'int', 40);
        $keyword = $this->request->getPost('keyword', 'string', '');
        if(!$page_size){
            $page_size=4;
        }
        if(!$page){
            $page=1;
        }

        //基本条件
        $where = array( 'state = 1 and is_del = 0' );
        //地区搜索
        $info = M\Areas::findFirstByarea_id($area);
  
        if($cate) {
            // $cids = M\Category::getChild($cate);
            // $cids = !empty($cids) ? $cids : array(0);
            // $where[] = " category in (".implode(',', $cids).") ";
            $where[] = " maxcategory = '{$cate}'";
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

        if($sellid) {
            $where[] = " uid = '{$sellid}'";
        }
        $where = implode(' and ', $where);
        $total = M\Sell::count($where);
        $offst = intval(($page - 1) * $page_size);

        $data = M\Sell::find( $where . "  ORDER BY createtime DESC limit {$offst} , {$page_size} ")->toArray();
        
        if(!$data){
           $this->getMsg(parent::DATA_EMPTY);
        }
        foreach ($data as $key => $value) {
            $sell[$key]["goodsName"]=$value['title'] ? $value['title'] : '';
            $sell[$key]["supplyAmount"]=$value['quantity'] > 0 ? $value['quantity'] : '不限';
            $sell[$key]["minAmount"]=$value['min_number'] > 0 ? $value['min_number'] : '不限';
            $sell[$key]["supplyArea"]=$value['areas_name'] ? L\Utils::getC($value['areas_name']) : '';
            $sell[$key]['goods_unit']=M\Purchase::$_goods_unit[$value["goods_unit"]];
            $sell[$key]['goods_unitid']=$value["goods_unit"];
            $sell[$key]['areas']=$value["areas"];
            $sell[$key]["unitPrice"]=$value['min_price'].'~'.$value['max_price'];
            $sell[$key]["id"]=$value['id'];
            $time = new L\Time(time() , $value['createtime']);
            $sell[$key]['quotedTm'] = $time->transform();
        }
        
        $this->getMsg(parent::SUCCESS,array('member'=>$sell));
    }
    /**
     * 供应详情接口 
     * @return 
     * string  
     * {"errorCode":0,"data":{"member":{"sell_sn":"SELL0000000027","id":"27","goodsName":"\u8110\u6a59",
     * "goodsType":"\u6682\u65e0",
     * "goodsSpec":"\u8110\u6a59",
     * "supplyTm":"11\u6708\u4e0a\u65ec~3\u6708\u4e0a\u65ec",
     * "supplyArea":"\u6e56\u5357\u7701",
     * "supplyAddress":"\u6e56\u5357 \u5e38\u5fb7 \u6fa7\u53bf \u590d\u5174\u5382\u9547",
     * <br />
     * "quotedPrice":"0.00~2.40",
     * "goods_unit":"\u5143\/\u516c\u65a4",
     * "createtime":"1425610196",
     * "supplyName":"\u738b\u6d77",
     * "supplyMobile":"13762690609"},
     * "goodsImg":[]}}
    * <br />
    *        {
    *       "errorCode": 0,
    *      "data": {
    *          "member": {
    *             "sell_sn(供应编号)": "SELL0000000027",
    *             "id(供应id)": "27",
    *            "goodsName(产品品名)": "脐橙",
    *           "goodsType(产品品种)": "暂无",
    *          "goodsSpec(产品规格)": "脐橙",
    *           "supplyTm(供应时间)": "11月上旬~3月上旬",
    *           "supplyArea(供应地区)": "湖南省",
    *          "supplyAddress(供应商地区)": "湖南 常德 澧县 复兴厂镇",
    *         "quotedPrice(产品报价)": "0.00~2.40",
    *        "goods_unit(单位)": "公斤",自己加 元/
    *        "goods_unitid(单位id)":"新加",
    *        "areas(地址id)":"1",
    *       "createtime(发布时间)": "2015-04-09 09:40:26",
    *      "supplyName(供应商姓名)": "王海",
    *     "supplyMobile(供应商电话)": "13762690609"
    *     "goodsImg(产品图片 有可能多张)": []
    *            },
    *           
    *           
    *      }
    *   }
    * <br />
     * <code>
     * <br/>
     * post 传值 
     * int sellid  供应商id
     * <br/>
     * url http://www.5fengshou.com/api/sell/sellinfo <br />
     * </code>
     */
    public function sellinfoAction()
    {
        
        if (!$this->request->isPost()) {
            $this->getMsg(parent::PARAMS_ERROR);
        }
        $curImgs=array();
        $id = $this->request->getPost('sellid', 'int', 0);
        
        $sell= M\Sell::findFirstByid($id);
        
        if (!$sell || !$sell->state) {
           $this->getMsg(parent::DATA_EMPTY);
        }
        $member=array();
        M\Sell::clickAdd($id);
        $imgs = M\SellImages::find("sellid='{$id}'")->toArray();
        
        if($sell->thumb){
            $curImgs[0] =IMG_URL.$sell->thumb;
            $curImgs[1] =IMG_URL.$sell->thumb;
        }else{
            if($imgs){
                $curImgs=$imgs;
                foreach ($curImg as $key => $value) {
                    $curImgs[$key]=IMG_URL.$value["path"];
                }
            }else{
                $curImgs[0]=M\Image::imgmaxsrc($sell->category);
            }
        }
        $member["sell_sn"]=$sell->sell_sn;
        $member["id"]=$sell->id;
        $member["goodsName"]=$sell->title;
        $member["goodsType"]=$sell->breed ? $sell->breed : '暂无';
        $member["goodsSpec"] =$sell->spec ? $sell->spec : '暂无';
        $member["supplyTm"]=M\Sell::$type[$sell->stime].'~'.M\Sell::$type[$sell->etime];
        $member["supplyArea"]=$sell->areas_name;
        $member["supplyAddress"]=$sell->address;
        $member["quotedPrice"]=$sell->min_price.'~'.$sell->max_price;
        $member["goods_unit"]=M\Purchase::$_goods_unit[$sell->goods_unit];
        $member['goods_unitid']=$sell->goods_unit;
        $member['areas']=$sell->areas;
        $member["createtime"]=date("Y-m-d H:i:s",$sell->createtime);
        $member["supplyName"]=$sell->uname;
        $member["supplyMobile"]=$sell->mobile ? $sell->mobile:"4008811365";
        $member["minAmount"]=$sell->min_number ? $sell->min_number : '';
        $member["goodsImg"]=$curImgs;
        $member["minnumber"]=$sell->min_number;
        $this->getMsg(parent::SUCCESS, array('member'=>$member));
    }

     /**
     * 发布供应
     * @return string  {"errorCode":0}
     * <br />
     * <code>
     * <br/>
     * post 传值 
     * float quantity  供应量
     * int  maxcategory  大分类的id
     * int  mincategory  小分类的id
     * int  areas       地区id
     * <br/>
     * string title     商品的名称
     * float  min_price  最小价格
     * float  max_price  最大价格
     * int    goods_unit  单位id
     * int stime  供应时间id
     * int etime  供应时间id
     * string address 详细地址
     * <br/>
     * int min_number 起购量
     * string content 详细描述
     * string breed   品种
     * string spec    规格
     * string category 最后一级分类的id 
     * <br/>
     * url http://www.5fengshou.com/api/sell/create <br />
     * </code>
     */
    public function createAction() 
    {
        
        if (!$this->request->isPost()) 
        {
           $this->getMsg(parent::DATA_EMPTY);
        }
        $str = '';
        foreach ($_POST as $key => $val) {
            $str .= "{$key} => {$val}";
        }
        file_put_contents(PUBLIC_PATH.'/sell.txt',$str."\n", FILE_APPEND);
        $maxcategory = $this->request->getPost("maxcategory", 'int', 0);
        $mincategory    = $this->request->getPost("mincategory", 'int', 0);
        $category = $this->request->getPost("category", 'int', 0);
        $areas       = $this->request->getPost("areas", 'int', 0);
        $title       = $this->request->getPost("title", 'string', '');
        $min_price   = $this->request->getPost("min_price", 'float', 0.00);
        $max_price   = $this->request->getPost("max_price", 'float', 0.00);
        $quantity    = $this->request->getPost("quantity", 'int', 0);
        $goods_unit  = $this->request->getPost("goods_unit", 'int', 1);
        $stime       = $this->request->getPost("stime", 'int', 0);
        $etime       = $this->request->getPost("etime", 'int', 0);
        $min_number  = $this->request->getPost("min_number", 'float', 0.00);
        $address     = $this->request->getPost("address", 'string', 0);
        $content     = $this->request->getPost('content', 'string', '');
        $breed       = $this->request->getPost("breed", 'string', '');
        $spec        = $this->request->getPost("spec", 'string', '');
        $source      = $this->request->getPost("source", 'int',4); 
       
        if(!$maxcategory||!$max_price||!$min_price||!$areas||!$title||!$stime||!$etime){
            $this->getMsg(parent::PARAMS_ERROR);
        }
     
        // 检测各项数据
        if (!V::validate_max_length($title,10)||V::validate_is_digits($title)||!V::validate_is_float($min_price)
            ||!V::validate_is_float($max_price)){
            $this->getMsg(parent::PARAMS_ERROR);
        }

        if($min_price>$max_price) {
             $this->getMsg(parent::MAX_PRICE_ERROR);
        }
       
        if($min_number!=0&&$quantity!=0){

            if($min_number>$quantity){
                $this->getMsg(parent::NOT_MAX_SELL); 
            }
        }

        $user_id = $this->getUid();
        if(!$user_id){
            $this->getMsg(parent::NOT_LOGGED_IN);
        }
        //检测是否完善资料
        $userareas=$this->checkuser($user_id);
        if(!$userareas){
            $this->getMsg(parent::INFO_ERROR);
        }
        $areas_name = L\Func::getCols(mAreas::getFamily($areas) , 'name', ',');
       
        $users = M\Users::findFirstByid($user_id);

        $cur_time = time();

        $this->db->begin();
        try
        {
       
                $sell = new M\Sell();
                $sell->title = $title;
                $sell->category = intval($mincategory);
                $sell->twocategory=intval($category);
                $sell->maxcategory=intval($maxcategory);
                $sell->min_price = $min_price;
                $sell->max_price = $max_price;
                $sell->quantity = $quantity;
                $sell->goods_unit = $goods_unit;
                $sell->stime = $stime;
                $sell->etime = $etime;
                $sell->areas = $areas;
                $sell->areas_name = $areas_name;
                $sell->address = $areas_name.$address;
                $sell->breed = $breed;
                $sell->spec = $spec;
                $sell->state = 0;
                $sell->uid = $user_id;
                $sell->uname = $users->ext->name;
                $sell->mobile = $users->username;
                $sell->source = $source;
                $sell->createtime = $sell->updatetime = $cur_time;
                $sell->min_number =$min_number;
           
                if (!$sell->save()) throw new \Exception(parent::NEWSELL_ERROR);

                $sell->sell_sn = sprintf('SELL%010u', $sell->id);
                $sell->save();
                $scontent = new M\SellContent();
                $scontent->sid = $sell->id;
                $scontent->content = $content;
                if(!$scontent->save()) throw new \Exception(parent::SAVESELLCONTENT_ERROR);

                $this->db->commit();
                $flag = parent::SUCCESS;
        }
        catch(\Exception $e) 
        {
            $this->db->rollback();
            $flag = $e->getMessage();
        }
        if($flag==0){
              $sid=$this->session->getID();
              if($sid){
                  M\TmpFile::copyImages($sell->id, $sid);
              }
              M\Category::numAdd($sell->category, 'sell_num');
        }
        $this->getMsg($flag);
        
    }
    public function newcreateAction() 
    {
        
        if (!$this->request->isPost()) 
        {
           $this->getMsg(parent::DATA_EMPTY);
        }
        $str = '';
        foreach ($_POST as $key => $val) {
            $str .= "{$key} => {$val}";
        }
        file_put_contents(PUBLIC_PATH.'/sell.txt',$str."\n", FILE_APPEND);
        $maxcategory = $this->request->getPost("maxcategory", 'int', 0);
        $mincategory    = $this->request->getPost("mincategory", 'int', 0);
        $category = $this->request->getPost("category", 'int', 0);
        $areas       = $this->request->getPost("areas", 'int', 0);
        $title       = $this->request->getPost("title", 'string', '');
        $min_price   = $this->request->getPost("min_price", 'float', 0.00);
        $max_price   = $this->request->getPost("max_price", 'float', 0.00);
        $quantity    = $this->request->getPost("quantity", 'int', 0);
        $goods_unit  = $this->request->getPost("goods_unit", 'int', 1);
        $stime       = $this->request->getPost("stime", 'int', 0);
        $etime       = $this->request->getPost("etime", 'int', 0);
        $min_number  = $this->request->getPost("min_number", 'float', 0.00);
        $address     = $this->request->getPost("address", 'string', 0);
        $content     = $this->request->getPost('content', 'string', '');
        $breed       = $this->request->getPost("breed", 'string', '');
        $spec        = $this->request->getPost("spec", 'string', '');
        
      
        if(!$maxcategory||!$max_price||!$min_price||!$areas||!$title||!$stime||!$etime){
            $this->getMsg(parent::PARAMS_ERROR);
        }
      
         //检测各项数据
        if (!V::validate_max_length($title,10)||V::validate_is_digits($title)||!V::validate_is_float($quantity)||!V::validate_is_float($min_price)
            ||!V::validate_is_float($max_price)){
            $this->getMsg(parent::PARAMS_ERROR);
        }
     
        if($min_price>$max_price) {
             $this->getMsg(parent::MAX_PRICE_ERROR);
        }
       
        if($min_number!=0&&$quantity!=0){

            if($min_number>$quantity){
                $this->getMsg(parent::NOT_MAX_SELL); 
            }
        }

        $user_id = $this->getUid();
        if(!$user_id){
            $this->getMsg(parent::NOT_LOGGED_IN);
        }
        //检测是否完善资料
        $userareas=$this->checkuser($user_id);
        if(!$userareas){
            $this->getMsg(parent::INFO_ERROR);
        }
        $areas_name = L\Func::getCols(mAreas::getFamily($areas) , 'name', ',');
       
        $users = M\Users::findFirstByid($user_id);

        $cur_time = time();

        $this->db->begin();
        try
        {
       
                $sell = new M\Sell();
                $sell->title = $title;
                $sell->category = intval($category);
                $sell->maxcategory=intval($maxcategory);
                $sell->min_price = $min_price;
                $sell->max_price = $max_price;
                $sell->quantity = $quantity;
                $sell->goods_unit = $goods_unit;
                $sell->stime = $stime;
                $sell->etime = $etime;
                $sell->areas = $areas;
                $sell->areas_name = $areas_name;
                $sell->address = $areas_name.$address;
                $sell->breed = $breed;
                $sell->spec = $spec;
                $sell->state = 0;
                $sell->uid = $user_id;
                $sell->uname = $users->ext->name;
                $sell->mobile = $users->username;
                $sell->source = 4;
                $sell->createtime = $sell->updatetime = $cur_time;
                $sell->min_number =$min_number;
           
                if (!$sell->save()) throw new \Exception(parent::NEWSELL_ERROR);

                $sell->sell_sn = sprintf('SELL%010u', $sell->id);
                $sell->save();
                $scontent = new M\SellContent();
                $scontent->sid = $sell->id;
                $scontent->content = $content;
                if(!$scontent->save()) throw new \Exception(parent::SAVESELLCONTENT_ERROR);

                $this->db->commit();
                $flag = parent::SUCCESS;
        }
        catch(\Exception $e) 
        {
            $this->db->rollback();
            $flag = $e->getMessage();
        }
        if($flag==0){
              $sid=$this->session->getID();
              if($sid){
                  M\TmpFile::copyImages($sell->id, $sid);
              }
              M\Category::numAdd($sell->category, 'sell_num');
        }
        $this->getMsg($flag);
        
    }
     /**
     * 发布供应-编辑-个人中心审核失败后 可以重新编辑 -第一步
     * @return string  {"errorCode":0,"data":{"member":{"title":"\u767d\u83dc","category":"\u852c\u83dc_\u767d\u83dc",
     * "maxcategoryid":"1","mincategoryid":"19","quantity":"0.00","min_number"=>'0',"min_price":"2.00",
     * "max_price":"2.20",
     * "address":"\u5317\u4eac\u5e02,\u5e02\u8f96\u533a,\u4e1c\u57ce\u533a,
     * \u4e1c\u534e\u95e8\u8857\u9053\u529e\u4e8b\u5904,\u591a\u798f\u5df7\u793e\u533a\u5c45\u59d4\u4f1a",
     * "stime":"11","etime":"123","spec":"","breed":"","content":"11","failReason":'321313'}}}
     * <br/>
     * <br/>
     *
     *   {
     *       "errorCode": 0,
     *       "data": {
     *           "member": {
     *               "title(产品名称)": "土豆土豆土豆11",
     *               "category(分类显示的汉字)": "蔬菜_菜瓜",
     *               
      *              "maxcategoryid(大分类id)": "1",
     *               "mincategoryid(小分类id)": "48",
     *               "quantity(供应量)": "455.00",
     *               "min_number(起定量)": "455.00",
     *               "min_price(最小价格)": "100.00",
     *               "max_price(最大价格)": "200.00",
     *               "address(供货地)": "北京市,县,密云县,果园街道办事处,新北路社区居委会",
     *              "stime(上市时间)": "11",
     *               "etime(上市时间)": "123",
     *               "spec(规格)": "",
     *               "breed(品种)": "",
     *               "content(详细描述)": "的撒大大合适的",
     *               'failReason(审核失败原因)':'dsadad',
     *               'goods_unit(单位)':'公斤' 自己加 元/,
     *               'goods_unitid(单位id)':'1',
     *               'areas(地址id)':'2'
     *               'categoryid(二级分类id)':''
    *            }
    *            "goodsImg(产品图片 有可能多张)": []
    *        }
    *    }
     * 
     * <br/>
     * <code>
     * <br/>
     * post 传值 
     * int sellid  供应商id
     * url http://www.5fengshou.com/api/sell/edit <br />
     * 
     * </code>
     */
    public function editAction() 
    {
        $user_id = $this->getUid();
        
        if(!$user_id){
            $this->getMsg(parent::NOT_LOGGED_IN);
        }
        //检测是否完善资料
        $userareas=$this->checkuser($user_id);
         $curImgs=array();
        if(!$userareas){
            $this->getMsg(parent::INFO_ERROR);
        }
        $sellid = $this->request->getPOST('sellid', 'int', 0);

        if(!$sellid){
           $this->getMsg(parent::PARAMS_ERROR); 
        }
       
        $sell = M\Sell::findFirst("id='{$sellid}' and is_del=0 and state=2 ");
        
        if (!$sell) 
        {
            $this->getMsg(parent::DATA_EMPTY); 
        }
        if ($user_id!= $sell->uid) 
        {
            $this->getMsg(parent::NOT_SVAESELL_ERROR); 
        }

        $imgs = M\SellImages::find("sellid='{$sellid}'")->toArray();
        if($sell->thumb){
            $curImgs[0] =IMG_URL.$sell->thumb;
        }else{
            $curImg=$imgs;
            foreach ($curImg as $key => $value) {
                $curImgs[$key]["path"]=IMG_URL.$value["path"];
            }
        }
        // $sid = md5(session_id());
        // 删除旧session图片
        //TmpFile::clearOld($sid);
        $curCate = M\Category::getFamily($sell->category);

        $member["title"] = $sell->title;
        $cate1=isset($curCate[0]["title"]) ? $curCate[0]["title"] : "";
        $cate2=isset($curCate[1]["title"]) ? $curCate[1]["title"] : "";
        $cate3=isset($curCate[2]["title"]) ? $curCate[2]["title"] : "";
        if($cate3){
        $member["category"] =$cate1.'_'.$cate2.'_'.$cate3;
        }else{
        $member["category"] =$cate1.'_'.$cate2;
        }
        $member["maxcategoryid"] = $sell->maxcategory;
        $member["mincategoryid"] = $sell->category;
        $member["quantity"] = $sell->quantity>0 ? intval($sell->quantity) : '不限' ;
        $member["min_number"] = $sell->min_number>0 ? $sell->min_number : '不限';
        $member["min_price"] = $sell->min_price;
        $member["max_price"] = $sell->max_price;
        $member["address"] = $sell->address;
        $member["stime"] = $sell->stime;
        $member["etime"] = $sell->etime;
        $member["spec"] = $sell->spec;
        $member["breed"] = $sell->breed;
        $member["content"] =$sell->scontent ? $sell->scontent->content : '';  
        $member["failReason"] =M\SellCheck::getfailReason($sell->id);
        $member["goods_unit"]=M\Purchase::$_goods_unit[$sell->goods_unit];
        $member['goods_unitid']=$sell->goods_unit;
        $member["areas"] = $sell->areas;
        $member["categoryid"] = isset($sell->twocategory) ? $sell->twocategory : 0 ;
        $this->getMsg(parent::SUCCESS, array('member'=>$member,'goodsImg'=>$curImgs));
    }
         /**
     * 发布供应-编辑-个人中心审核失败后 可以重新编辑 -第一步
     * @return string  {"errorCode":0,"data":{"member":{"title":"\u767d\u83dc","category":"\u852c\u83dc_\u767d\u83dc",
     * "maxcategoryid":"1","mincategoryid":"19","quantity":"0.00","min_number"=>'0',"min_price":"2.00",
     * "max_price":"2.20",
     * "address":"\u5317\u4eac\u5e02,\u5e02\u8f96\u533a,\u4e1c\u57ce\u533a,
     * \u4e1c\u534e\u95e8\u8857\u9053\u529e\u4e8b\u5904,\u591a\u798f\u5df7\u793e\u533a\u5c45\u59d4\u4f1a",
     * "stime":"11","etime":"123","spec":"","breed":"","content":"11","failReason":'321313'}}}
     * <br/>
     * <br/>
     *
     *   {
     *       "errorCode": 0,
     *       "data": {
     *           "member": {
     *               "title(产品名称)": "土豆土豆土豆11",
     *               "categoryname(二级分类的名字)": "蔬菜",
      *              "maxcategoryid(大分类id)": "1",
      *              "maxcategoryname(大分类的名称)":""
      *              'categoryid(二级分类id)':''
     *               "quantity(供应量)": "455.00",
     *               "min_number(起定量)": "455.00",
     *               "min_price(最小价格)": "100.00",
     *               "max_price(最大价格)": "200.00",
     *               "address(供货地)": "北京市,县,密云县,果园街道办事处,新北路社区居委会",
     *              "stime(上市时间)": "11",
     *               "etime(上市时间)": "123",
     *               "spec(规格)": "",
     *               "breed(品种)": "",
     *               "content(详细描述)": "的撒大大合适的",
     *               'failReason(审核失败原因)':'dsadad',
     *               'goods_unit(单位)':'公斤' 自己加 元/,
     *               'goods_unitid(单位id)':'1',
     *               'areas(地址id)':'2'
     *               
    *            }
    *            "goodsImg(产品图片 有可能多张)": []
    *        }
    *    }
     * 
     * <br/>
     * <code>
     * <br/>
     * post 传值 
     * int sellid  供应商id
     * url http://www.5fengshou.com/api/sell/newedit <br />
     * 
     * </code>
     */
    public function neweditAction() 
    {
        $user_id = $this->getUid();
        if(!$user_id){
            $this->getMsg(parent::NOT_LOGGED_IN);
        }
        //检测是否完善资料
        $userareas=$this->checkuser($user_id);
         $curImgs=array();
        if(!$userareas){
            $this->getMsg(parent::INFO_ERROR);
        }
        $sellid = $this->request->getPOST('sellid', 'int', 0);
      
        if(!$sellid){
           $this->getMsg(parent::PARAMS_ERROR); 
        }
       
        $sell = M\Sell::findFirst("id='{$sellid}' and is_del=0 and state=2 ");
       
        if (!$sell) 
        {
            $this->getMsg(parent::DATA_EMPTY); 
        }
        if ($user_id!= $sell->uid) 
        {
            $this->getMsg(parent::NOT_SVAESELL_ERROR); 
        }
        $imgs = M\SellImages::find("sellid='{$sellid}'")->toArray();
        if($sell->thumb){
            $curImgs[0] =IMG_URL.$sell->thumb;
        }else{
            $curImg=$imgs;
            foreach ($curImg as $key => $value) {
                $curImgs[$key]=IMG_URL.$value["path"];
            }
        }
        // $sid = md5(session_id());
        // 删除旧session图片
        //TmpFile::clearOld($sid);
      
        $curCate = M\Category::getFamily($sell->category);

        $member["title"] = $sell->title;
        $cate1=isset($curCate[0]["title"]) ? $curCate[0]["title"] : "";
        $cate2=isset($curCate[1]["title"]) ? $curCate[1]["title"] : "";
        $cate3=isset($curCate[2]["title"]) ? $curCate[2]["title"] : "";
        
        $member["categoryname"] =$cate2;
        $member["maxcategoryname"] =$cate1;
        $member["maxcategoryid"] = $sell->maxcategory;
        $member["categoryid"] =  $sell->category;
        $member["quantity"] = $sell->quantity>0 ? intval($sell->quantity) : '不限' ;
        $member["min_number"] = $sell->min_number>0 ? $sell->min_number : '不限';
        $member["min_price"] = $sell->min_price;
        $member["max_price"] = $sell->max_price;
        $member["address"] = $sell->address;
        $member["areas_address"] = $sell->areas_name;
        $member["stime"] = $sell->stime;
        $member["etime"] = $sell->etime;
        $member["spec"] = $sell->spec;
        $member["breed"] = $sell->breed;
        $member["content"] =$sell->scontent ? $sell->scontent->content : '';  
        $member["failReason"] =M\SellCheck::getfailReason($sell->id);
        $member["goods_unit"]=M\Purchase::$_goods_unit[$sell->goods_unit];
        $member['goods_unitid']=$sell->goods_unit;
        $member["areas"] = $sell->areas;
        $this->getMsg(parent::SUCCESS, array('member'=>$member,'goodsImg'=>$curImgs));
    }
    /**
     * 编辑供应
     * @return string  {"errorCode":0}
     * <br />
     * <code>
     * <br/>
     * post 传值 
     * int sellid  供应商id
     * int  maxcategory  大分类的id
     * int  mincategory  小分类的id
     * int  areas       地区id
     * <br/>
     * string title     商品的名称
     * float  min_price  最小价格
     * float  max_price  最大价格
     * int    goods_unit  单位id
     * <br/>
     * int stime  开始时间id
     * int etime  结束时间id
     * int min_number 起购量
     * <br/>
     * string content 详细描述
     * string breed   品种
     * string spec    规格
     * <br />
     * url http://www.5fengshou.com/api/sell/save 
     * <br />
     * </code>
     */
    public function saveAction() 
    {
        
        if (!$this->request->isPost()) 
        {
           $this->getMsg(parent::DATA_EMPTY);
        }
        $maxcategory = $this->request->getPost("maxcategory", 'int', 0);
        $mincategory    = $this->request->getPost("mincategory", 'int', 0);
        $category = $this->request->getPost("category", 'int', 0);

        $areas       = $this->request->getPost("areas", 'int', 0);
        $title       = $this->request->getPost("title", 'string', '');
        $min_price   = $this->request->getPost("min_price", 'float', 0.00);
        $max_price   = $this->request->getPost("max_price", 'float', 0.00);
        $quantity    = $this->request->getPost("quantity", 'float', 0.00);
        $goods_unit  = $this->request->getPost("goods_unit", 'int', 1);
        $stime       = $this->request->getPost("stime", 'int', 0);
        $etime       = $this->request->getPost("etime", 'int', 0);
        $min_number  = $this->request->getPost("min_number", 'int', 0);
         
        $content     = $this->request->getPost('content', 'string', '');
        $breed       = $this->request->getPost("breed", 'string', '');
        $spec        = $this->request->getPost("spec", 'string', '');
        $address        = $this->request->getPost("address", 'string', '');
        $sellid = $this->request->getPost('sellid', 'int', 0);
        
        $user_id = $this->getUid();
        if(!$user_id){
            $this->getMsg(parent::NOT_LOGGED_IN);
        }
        if(!$maxcategory||!$max_price||!$min_price||!$areas||!$title||!$goods_unit||!$stime||!$etime){
            $this->getMsg(parent::DATA_EMPTY);
        }

        if($min_price>$max_price) {
             $this->getMsg(parent::MAX_PRICE_ERROR);
        }
        if($min_number!=0&&$quantity!=0){

            if($min_number>$quantity){
                $this->getMsg(parent::NOT_MAX_SELL); 
            }
        }
        //检测是否完善资料
        $userareas=$this->checkuser($user_id);
        if(!$userareas){
            $this->getMsg(parent::INFO_ERROR);
        }
        if(!$sellid){
           $this->getMsg(parent::PARAMS_ERROR); 
        }

        $sell = M\Sell::findFirst("id='{$sellid}' and is_del=0");

        if (!$sell) 
        {
            $this->getMsg(parent::DATA_EMPTY); 
        }
        if ($user_id!= $sell->uid) 
        {
            $this->getMsg(parent::NOT_SVAESELL_ERROR); 
        }

         //检测各项数据
        if (!V::validate_max_length($title,10)||!V::validate_is_float($quantity)||!V::validate_is_float($min_price)
            ||!V::validate_is_float($max_price)){
            $this->getMsg(parent::PARAMS_ERROR);
        }
        
        $user_id = $this->getUid();
        if(!$user_id){
            $this->getMsg(parent::NOT_LOGGED_IN);
        }
        
        //如果小分类没有  默认为其他分类
        if($category == 0) 
        {
            $qita = M\Category::findFirst(" parent_id={$maxcategory} and title like '%其他%'");

            if ($qita) 
            {
                $category = $qita->id;
            }
            else
            {
                $newcategory = new M\Category();
                $newcategory->title = "其他";
                $newcategory->parent_id = $maxcategory;
                $newcategory->keyword = "其他";
                $newcategory->depict = "其他分类";
                $newcategory->deeps = 0;
                $newcategory->is_groom  = 0;
                $newcategory->is_show = 1;
                $newcategory->save();
                $category = $newcategory->id;
            }
        }
        else
        {
            $category = $category;
        }

        $areas_name = L\Func::getCols(mAreas::getFamily($areas) , 'name', ',');
       
        $users = M\Users::findFirstByid($user_id);

        $cur_time = time();

        $this->db->begin();
        try
        {
            
                $sell = M\Sell::findFirst("id='{$sellid}'");
                $sell->title = $title;
                $sell->category = intval($mincategory);
                $sell->twocategory=intval($category);
                $sell->maxcategory=intval($maxcategory);
                $sell->min_price = $min_price;
                $sell->max_price = $max_price;
                $sell->quantity = $quantity;
                $sell->goods_unit = $goods_unit;
                $sell->stime = $stime;
                $sell->etime = $etime;
                $sell->areas = $areas;
                $sell->areas_name = $areas_name;
                $sell->address = $areas_name.$address;
                $sell->breed = $breed;
                $sell->spec = $spec;
                $sell->state = 0;
                $sell->uid = $user_id;
                $sell->uname = $users->ext->name;
                $sell->mobile = $users->username;
                $sell->source = 4;
                $sell->createtime = $sell->updatetime = $cur_time;
                $sell->min_number =$min_number;
                if (!$sell->save()) throw new \Exception(parent::SAVESELL_ERROR);

                $scontent = new M\SellContent();
                $scontent->sid = $sell->id;
                $scontent->content = $content;
                if(!$scontent->save()) throw new \Exception(parent::SAVESELLCONTENT_ERROR);

                $this->db->commit();
                $flag = parent::SUCCESS;
        }
        catch(\Exception $e) 
        {
            $this->db->rollback();
            $flag = $e->getMessage();
        }
        if($flag==0){
          $sid=$this->session->getID();
          if($sid){
              M\TmpFile::copyImages($sell->id, $sid);
          }
        }
        $this->getMsg($flag);
    }
    /**
     * 编辑供应
     * @return string  {"errorCode":0}
     * <br />
     * <code>
     * <br/>
     * post 传值 
     * int sellid  供应商id
     * int  maxcategory  大分类的id
     * int  mincategory  小分类的id
     * int  areas       地区id
     * <br/>
     * string title     商品的名称
     * float  min_price  最小价格
     * float  max_price  最大价格
     * int    goods_unit  单位id
     * <br/>
     * int stime  开始时间id
     * int etime  结束时间id
     * int min_number 起购量
     * <br/>
     * string content 详细描述
     * string breed   品种
     * string spec    规格
     * <br />
     * url http://www.5fengshou.com/api/sell/save 
     * <br />
     * </code>
     */
    public function newsaveAction() 
    {
        
        if (!$this->request->isPost()) 
        {
           $this->getMsg(parent::DATA_EMPTY);
        }
        $maxcategory = $this->request->getPost("maxcategory", 'int', 0);
        //$mincategory    = $this->request->getPost("mincategory", 'int', 0);
        $category = $this->request->getPost("category", 'int', 0);

        $areas       = $this->request->getPost("areas", 'int', 0);
        $title       = $this->request->getPost("title", 'string', '');
        $min_price   = $this->request->getPost("min_price", 'float', 0.00);
        $max_price   = $this->request->getPost("max_price", 'float', 0.00);
        $quantity    = $this->request->getPost("quantity", 'float', 0.00);
        $goods_unit  = $this->request->getPost("goods_unit", 'int', 1);
        $stime       = $this->request->getPost("stime", 'int', 0);
        $etime       = $this->request->getPost("etime", 'int', 0);
        $min_number  = $this->request->getPost("min_number", 'int', 0);
         
        $content     = $this->request->getPost('content', 'string', '');
        $breed       = $this->request->getPost("breed", 'string', '');
        $spec        = $this->request->getPost("spec", 'string', '');
        $address        = $this->request->getPost("address", 'string', '');
        $sellid = $this->request->getPost('sellid', 'int', 0);
        
        $user_id = $this->getUid();
        if(!$user_id){
            $this->getMsg(parent::NOT_LOGGED_IN);
        }
        if(!$maxcategory||!$max_price||!$min_price||!$areas||!$title||!$goods_unit||!$stime||!$etime){
            $this->getMsg(parent::DATA_EMPTY);
        }

        if($min_price>$max_price) {
             $this->getMsg(parent::MAX_PRICE_ERROR);
        }
        if($min_number!=0&&$quantity!=0){

            if($min_number>$quantity){
                $this->getMsg(parent::NOT_MAX_SELL); 
            }
        }
        //检测是否完善资料
        $userareas=$this->checkuser($user_id);
        if(!$userareas){
            $this->getMsg(parent::INFO_ERROR);
        }
        if(!$sellid){
           $this->getMsg(parent::PARAMS_ERROR); 
        }

        $sell = M\Sell::findFirst("id='{$sellid}' and is_del=0");

        if (!$sell) 
        {
            $this->getMsg(parent::DATA_EMPTY); 
        }
        if ($user_id!= $sell->uid) 
        {
            $this->getMsg(parent::NOT_SVAESELL_ERROR); 
        }

         //检测各项数据
        if (!V::validate_max_length($title,10)||!V::validate_is_float($quantity)||!V::validate_is_float($min_price)
            ||!V::validate_is_float($max_price)){
            $this->getMsg(parent::PARAMS_ERROR);
        }
        
        $user_id = $this->getUid();
        if(!$user_id){
            $this->getMsg(parent::NOT_LOGGED_IN);
        }
        
        //如果小分类没有  默认为其他分类
        if($category == 0) 
        {
            $qita = M\Category::findFirst(" parent_id={$maxcategory} and title like '%其他%'");

            if ($qita) 
            {
                $category = $qita->id;
            }
            else
            {
                $newcategory = new M\Category();
                $newcategory->title = "其他";
                $newcategory->parent_id = $maxcategory;
                $newcategory->keyword = "其他";
                $newcategory->depict = "其他分类";
                $newcategory->deeps = 0;
                $newcategory->is_groom  = 0;
                $newcategory->is_show = 1;
                $newcategory->save();
                $category = $newcategory->id;
            }
        }
        else
        {
            $category = $category;
        }

        $areas_name = L\Func::getCols(mAreas::getFamily($areas) , 'name', ',');
       
        $users = M\Users::findFirstByid($user_id);

        $cur_time = time();

        $this->db->begin();
        try
        {
            
                $sell = M\Sell::findFirst("id='{$sellid}'");
                $sell->title = $title;
                $sell->category = $category;
                // $sell->twocategory=$category;
                $sell->maxcategory=$maxcategory;
                $sell->min_price = $min_price;
                $sell->max_price = $max_price;
                $sell->quantity = $quantity;
                $sell->goods_unit = $goods_unit;
                $sell->stime = $stime;
                $sell->etime = $etime;
                $sell->areas = $areas;
                $sell->areas_name = $areas_name;
                $sell->address = $areas_name.$address;
                $sell->breed = $breed;
                $sell->spec = $spec;
                $sell->state = 0;
                $sell->uid = $user_id;
                $sell->uname = $users->ext->name;
                $sell->mobile = $users->username;
                $sell->source = 4;
                $sell->createtime = $sell->updatetime = $cur_time;
                $sell->min_number =$min_number;
                if (!$sell->save()) throw new \Exception(parent::SAVESELL_ERROR);

                $scontent = new M\SellContent();
                $scontent->sid = $sell->id;
                $scontent->content = $content;
                if(!$scontent->save()) throw new \Exception(parent::SAVESELLCONTENT_ERROR);

                $this->db->commit();
                $flag = parent::SUCCESS;
        }
        catch(\Exception $e) 
        {
            $this->db->rollback();
            $flag = $e->getMessage();
        }
        if($flag==0){
          $sid=$this->session->getID();
          if($sid){
              M\TmpFile::copyImages($sell->id, $sid);
          }
        }
        $this->getMsg($flag);
    }
    /**
     * 我的供应接口-个人中心 
     * @return string  {"errorCode":0,"data":{"member":[{"sell_sn":"SELL0000138783",
     * "sell_name":"\u571f\u8c46\u571f\u8c46\u571f\u8c4611","quantity":"455.00",
     * "min_number":"\u4e0d\u9650","areas_name":"\u5317\u4eac\u5e02",
     * "goods_unit":"\u5143\/\u516c\u65a4","etime":"12\u6708\u4e0b\u65ec",
     * "stime":"1\u6708\u4e0a\u65ec","price":"100.00~200.00",
     * "createtime":"2015-04-07 20:17:20","id":"138783",
     * "state":"\u7b49\u5f85\u5ba1\u6838"}]}}
     * <br/>
     * <br/>
     *   {
     *       "errorCode": 0,
     *       "data": {
     *           "token": "nq1qnmqagh9kp1cv12s6fq1m61",
     *           "member": [
     *               {
     *                   "sell_sn(供应编号)": "SELL0000138783",
     *                   "sell_name(供应商品)": "土豆土豆土豆11",
     *                   "quantity(供应数量)": "455.00",
     *                   "min_number(起定量)": "不限",
     *                   "areas_name(供应编号)": "北京市",
     *                   
     *                   "goods_unit(单位)": "公斤",
     *                   "etime(供应时间)": "12月下旬",
     *                  "stime(供应时间)": "1月上旬",
     *                   "price(供货价格)": "100.00~200.00",
     *                   "createtime(发布时间)": "2015-04-07 20:17:20",
     *                   "id(供应编号)": "138783",
     *                  "state(供应状态)": "等待审核",
     *                  'statecode(供应状态码)':'1',
     *                  'is_del(是否删除)' ：'0',
     *                  'goods_unitid(单位id)' :'1',
     *                   'areas(地址id)':'',
     *               }
     *           ]
     *       }
     *   }
     * <br/>
     * 
     * <code>
     * <br />
     * post 传值 
     * int p 当前页数 默认第一页
     * int pagesize 每页显示几条  默认4条
     * int state  状态   0  未审核   1 已审核   2  审核失败   （数据库本来的状态）
     * int is_del 1 已取消 0 未删除 
     * <br/>
     * (搜索条件 0 全部 1 待审核 2 已审核  3 已取消)
     * <br/>
     * url http://www.5fengshou.com/api/sell/membersell 
     * <br />
     * </code>
     */
    public function membersellAction()
    {
        $user_id = $this->getUid();
       
        if(!$user_id){
            $this->getMsg(parent::NOT_LOGGED_IN);
        }
        //检测是否完善资料
        $userareas=$this->checkuser($user_id);
        if(!$userareas){
            $this->getMsg(parent::INFO_ERROR);
        }
        $page = $this->request->getPost('p', 'int', 1);
        $page_size = $this->request->getPost('pagesize', 'int', 4);
        $state=$this->request->getPost('state', 'int', '0');
        if(!$page_size){
            $page_size=4;
        }
        if(!$page){
            $page=1;
        }
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
   
        $total = M\Sell::count($where);
       
        $offst = intval(($page - 1) * $page_size);

        $data = M\Sell::find($where."  ORDER BY updatetime DESC limit {$offst} , {$page_size} ")->toArray();
       
        if(!$data){
           $this->getMsg(parent::DATA_EMPTY);
        }
   
        foreach ($data as $key => $value) {
            $sell[$key]["sell_sn"]=$value['sell_sn'] ? $value['sell_sn'] : '';
            $sell[$key]["sell_name"]=$value['title'] ? $value['title'] : '';
            $sell[$key]["quantity"]=$value['quantity'] > 0 ? intval($value['quantity']) : '不限';
            $sell[$key]["min_number"]=$value['min_number'] > 0 ? $value['min_number'] : '不限';
            $sell[$key]["areas_name"]=$value['areas_name'] ? L\Utils::getC($value['areas_name']) : '';
            $sell[$key]['goods_unit']=M\Purchase::$_goods_unit[$value["goods_unit"]];
            $sell[$key]['etime']=$value['etime'] ? M\Sell::$type[$value['etime']] : '';
            $sell[$key]['stime']=$value['stime'] ? M\Sell::$type[$value['stime']] : '';
            $sell[$key]["price"]=$value['min_price'].'~'.$value['max_price'];
            $sell[$key]["createtime"]=date("Y-m-d H:i:s",$value['createtime']);
            $sell[$key]["id"]=$value['id'];
            $sell[$key]["statecode"]=$value['state'];
            $sell[$key]["is_del"]=$value['is_del'];
            if($value['is_del']==1){
                 $sell[$key]['state'] ="已取消";
            }else{
                 $sell[$key]['state'] =M\Sell::$appstate[$value['state']];
            }
            $sell[$key]['goods_unitid']=$value["goods_unit"];
            $sell[$key]['areas']=$value["areas"];
           
        }
        $this->getMsg(parent::SUCCESS, array('member'=>$sell));
    }


    /**
     * 我的供应接口-个人中心-取消发布
     * @return string  {"errorCode":0}
     * <br/>
     * <code>
     * <br />
     * post 传值 
     * int purid 供应id
     * <br/>
     * url http://www.5fengshou.com/api/sell/remove <br />
     * </code>
     */
    public function removeAction() {
        $user_id = $this->getUid();
        if(!$user_id){
            $this->getMsg(parent::NOT_LOGGED_IN);
        }
        $id = $this->request->getPost('purid', 'int', 0);
        $purchase = M\Sell::findFirstByid($id);
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
     * 我的供应接口-个人中心-重新发布
     * @return string  {"errorCode":0}
     * <br/>
     * <code>
     * <br />
     * post 传值 
     * int purid 供应id
     * <br/>
     * url http://www.5fengshou.com/api/sell/anew <br />
     * </code>
     */
    public function anewAction() {
        $user_id = $this->getUid();
        if(!$user_id){
            $this->getMsg(parent::NOT_LOGGED_IN);
        }
        $id = $this->request->getPost('purid', 'int', 0);
        $purchase = M\Sell::findFirstByid($id);
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
     * 我的供应接口-个人中心 
     * @return string  {"errorCode":0,"data":{"member":[{"sell_sn":"SELL0000138783",
     * "sell_name":"\u571f\u8c46\u571f\u8c46\u571f\u8c4611","quantity":"455.00",
     * "min_number":"\u4e0d\u9650","areas_name":"\u5317\u4eac\u5e02",
     * "goods_unit":"\u5143\/\u516c\u65a4","etime":"12\u6708\u4e0b\u65ec",
     * "stime":"1\u6708\u4e0a\u65ec","price":"100.00~200.00",
     * "createtime":"2015-04-07 20:17:20","id":"138783",
     * "state":"\u7b49\u5f85\u5ba1\u6838"}]}}
     * 
     * <code>
     * <br />
     * post 传值 
     * int p 当前页数 默认第一页
     * int pagesize 每页显示几条  默认4条
     * int state  状态   0  未审核   1 已审核   2  审核失败   （数据库本来的状态）
     * int is_del 1 已取消 0 未删除 
     * <br/>
     * (搜索条件 0 全部 1 待审核 2 已审核  3 已取消 4 审核失败)
     * <br/>
     * url http://www.5fengshou.com/api/sell/wxMembersell 
     * <br />
     * </code>
     */
    public function wxMembersellAction()
    {
        $user_id = $this->getUid();
       
        if(!$user_id){
            $this->getMsg(parent::NOT_LOGGED_IN);
        }
        //检测是否完善资料
        $userareas=$this->checkuser($user_id);
        if(!$userareas){
            $this->getMsg(parent::INFO_ERROR);
        }
        $page = $this->request->getPost('p', 'int', 1);
        $page_size = $this->request->getPost('pagesize', 'int', 4);
        $state=$this->request->getPost('state', 'int', '0');
        if(!$page_size){
            $page_size=4;
        }
        if(!$page){
            $page=1;
        }
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
   
        $total = M\Sell::count($where);
       
        $offst = intval(($page - 1) * $page_size);

        $data = M\Sell::find($where."  ORDER BY updatetime DESC limit {$offst} , {$page_size} ")->toArray();
       
        if(!$data){
           $this->getMsg(parent::DATA_EMPTY);
        }
   
        foreach ($data as $key => $value) {
            $sell[$key]["sell_sn"]=$value['sell_sn'] ? $value['sell_sn'] : '';
            $sell[$key]["sell_name"]=$value['title'] ? $value['title'] : '';
            $sell[$key]["quantity"]=$value['quantity'] > 0 ? $value['quantity'] : '不限';
            $sell[$key]["min_number"]=$value['min_number'] > 0 ? $value['min_number'] : '不限';
            $sell[$key]["areas_name"]=$value['areas_name'] ? L\Utils::getC($value['areas_name']) : '';
            $sell[$key]['goods_unit']=M\Purchase::$_goods_unit[$value["goods_unit"]];
            $sell[$key]['etime']=$value['etime'] ? M\Sell::$type[$value['etime']] : '';
            $sell[$key]['stime']=$value['stime'] ? M\Sell::$type[$value['stime']] : '';
            $sell[$key]["price"]=$value['min_price'].'~'.$value['max_price'];
            $sell[$key]["createtime"]=date("Y-m-d H:i:s",$value['createtime']);
            $sell[$key]["id"]=$value['id'];
            $sell[$key]["statecode"]=$value['state'];
            $sell[$key]["is_del"]=$value['is_del'];
            if($value['is_del']==1){
                 $sell[$key]['state'] ="已取消";
            }else{
                 $sell[$key]['state'] =M\Sell::$appstate[$value['state']];
            }
            $sell[$key]['goods_unitid']=$value["goods_unit"];
            $sell[$key]['areas']=$value["areas"];
           
        }
        $this->getMsg(parent::SUCCESS, array('member'=>$sell));
    }
}
?>