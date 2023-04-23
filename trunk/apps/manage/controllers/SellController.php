<?php
/**
 * 供应信息
 */
namespace Mdg\Manage\Controllers;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Mdg\Models as M;
use Lib\File as File;
use Mdg\Models\Sell as Sell;
use Lib\Func as Func;
use Lib\Areas as lAreas;
use Mdg\Models\Purchase as Purchase;
use Mdg\Models\TmpFile as TmpFile;
use Mdg\Models\SellImages as SellImages;
use Mdg\Models\Users as Users;
use Mdg\Models\UsersExt as UsersExt;
use Mdg\Models\AreasFull as mAreas;
use Lib\Pages as Pages;
use Lib\Category as lCategory;
use Lib\Path as Path;
use Lib\Member as Member;
use Lib\Auth as Auth;
use Lib\SMS as sms;
use Lib\Utils as Utils;

class SellController extends ControllerMember
{
    /**
     * 供应列表
     */
    
    public function indexAction() 
    {
        $plat = $this->request->get('plat', 'int', 0);
        $page = $this->request->get('p', 'int', 1);
        $maxcategory = $this->request->get("maxcategory", 'string', '');
        $category = $this->request->get("category", 'string', '');
        $where = M\Sell::conditions($this->request->get());
        $orderattribute =$this->request->get('orderattribute', 'int',0);
        //echo $where;die;
        if($plat) {
            $where .= " and s.publish_place = '{$plat}'";
        }
        if ($maxcategory) 
        {
            $max_category = M\Category::getChildcate($maxcategory);
            $where.= " and s.category in ($max_category,$maxcategory) ";
        }
        if($orderattribute){
             switch ($orderattribute) {
                 case 1:
                 $where.=" AND u.is_broker=1 ";
                     break;
                 default:
                  $where.=" AND u.is_broker!=1 ";
                     break;
             }
        }
        
        $page_size = 10;

          
        $sql="SELECT count(*) as count from sell as s  left join users as u on s.uid=u.id where {$where} ";
        
        $count=$this->db->fetchOne($sql,2);

        $total = $count["count"];
        $offst = intval(($page - 1) * $page_size);
        $sql="SELECT s.*,u.is_broker from sell as s  left join users as u on s.uid=u.id where {$where} ORDER BY s.createtime DESC limit {$offst} , {$page_size} ";
        $data=$this->db->fetchAll($sql,2);



        // $total = M\Sell::count($where);
        // $offst = intval(($page - 1) * $page_size);

        // $data = Sell::find($where . "  ORDER BY createtime DESC limit {$offst} , {$page_size} ");
        $pages['total_pages'] = ceil($total / $page_size);
        $pages['current'] = $page;
        $pages['total'] = $total;
        $pages = new Pages($pages);
        $pages = $pages->show(3);
        $this->view->sellstate = Sell::$sellstate;
        $this->view->current = $page;
        $this->view->data = $data;
        $this->view->pages = $pages;
        $this->view->is_show = M\ArticleCategory::$_is_show;
        $this->view->ishot = M\Sell::$_ishot;
        $this->view->state = M\Sell::$type1;
        $this->view->state1 = M\Sell::$type2;
        $this->view->username = $this->request->get("username");
        $this->view->stime = $this->request->get("addstime");
        $this->view->etime = $this->request->get("addetime");
        $this->view->is_img = $this->request->get("is_img");
        $this->view->sellname = $this->request->get("sellname");
        $this->view->sellsn = $this->request->get("sellsn");
        $this->view->sellsatues = $this->request->get("state");
        $this->view->is_source = $this->request->get("is_source");
        $this->view->orderattribute=$this->request->get("orderattribute");
        // 发布平台
        $this->view->plat       = M\Users::$_plat;
        $this->view->plat_param = $plat;
        if ($maxcategory && $category == '') 
        {
            $this->view->cat_name = lCategory::ldData($maxcategory);
        }
        else
        {
            $this->view->cat_name = lCategory::ldData($category);
        }
    }
    /**
     * 新增供应
     */
    
    public function newAction() 
    {
        
        if ($this->request->get('id')) 
        {
            echo $this->request->get('id');
            die;
        }
        $userid = $this->request->get('u', 'int', 0);
        $user = Users::findFirstByid($userid);
        $cate = M\Category::find(" parent_id=0 ")->toArray();
        $this->view->cate = $cate;
        $this->view->userid = $userid;
        $this->view->user = $user;
        $this->view->areas_name = $user ? lAreas::ldData($user->areas) : '';
        
        if ($this->request->get("suc")) 
        {
            $arrs = M\Reg::find("type=0")->toArray();
            $this->view->suc = $this->request->get("suc");
            $this->view->error = count($arrs);
        }
        $sid = md5(session_id());
        TmpFile::clearOld($sid);
        // 发布平台
        $this->view->plat= M\Users::$_plat;
        $this->view->sid = $sid;
        $this->tag->setDefault("is_hot", "1");
    }
    /**
     * 编辑供应
     *
     * @param string $id
     */
    
    public function editAction($id, $page = 1) 
    {
        
        
        $this->session->sessionREFERER = $_SERVER['HTTP_REFERER'];

        
        if (!$this->request->isPost()) 
        {
            $sell = M\Sell::findFirstByid($id);
            
            if (!$sell) 
            {
                parent::msg('供应信息未找到','/manage/sell/index');
                // $this->flash->error("供应信息未找到");
                // return $this->dispatcher->forward(array(
                //     "controller" => "sell",
                //     "action" => "index"
                // ));
            }
            $scontent=array();
            $scontent = M\SellContent::findFirstBysid($id);
            $contents = '';
            if ($scontent&&$scontent->attr != '') 
            {
                
                if ($id < 23028) 
                {
                    $arr = str_replace("u", '\u', $scontent->attr);
                    $code = json_decode($arr, true);
                }
                else
                {
                    $code = json_decode($scontent->attr, true);
                }
                
                foreach ($code as $key => $value) 
                {
                    $contents.= $value['title'] . ":" . $value['val'] . ';';
                }
            }
            else
            {
                $contents = $scontent ? $scontent->content : '';
            }
            
            if ($sell->sell_sn == "") 
            {
                $sell->sell_sn = sprintf('SELL%010u', $sell->id);
                $sell->save();
            }
            // print_R($sell->toArray());
            // exit;
            $sid = md5(session_id());
            TmpFile::clearOld($sid);
            $imgfile = M\SellImages::find("sellid=" . $id)->toArray();
            $this->view->stime = $sell->stime;
            $this->view->imgfile = $imgfile;
            $this->view->etime = $sell->etime;
            $this->view->goods_unit = $sell->goods_unit;
            $this->view->tfile = TmpFile::find("sid='{$sid}'");
            $this->view->sid = $sid;
            $this->view->id = $sell->id;
            $curCate = M\Category::getFamily($sell->category);
            
            $this->view->curCate = $curCate;
            $cat = lCategory::getCatTree($sell->category);

            $curCat = "'" . Func::getCols($cat, 'title', "', '") . "'";
            $this->view->curCat = $curCat;
            $content = M\SellContent::findFirstBysid($id);
            $cate = M\Category::find(" parent_id=0 ")->toArray();
            $this->view->cate = $cate;
            $this->tag->setDefault("id", $sell->id);
            $this->tag->setDefault("title", $sell->title);
            $this->tag->setDefault("max_price", $sell->max_price);
            $this->tag->setDefault("min_price", $sell->min_price);
            $this->tag->setDefault("quantity", $sell->quantity);
            $this->tag->setDefault("areas", $sell->areas);
            $this->tag->setDefault("address", $sell->address);
            $this->tag->setDefault("stime", $sell->stime);
            $this->tag->setDefault("etime", $sell->etime);
            $this->tag->setDefault("breed", $sell->breed);
            $this->tag->setDefault("spec", $sell->spec);
            $this->tag->setDefault("state", $sell->state);
            $this->tag->setDefault("is_hot", $sell->is_hot);
            $this->tag->setDefault("usermoblie", $sell->mobile);
            $this->tag->setDefault("content", $content ? $content->content : '-');
            $this->tag->setDefault("uname", $sell->uname);
            $this->tag->setDefault("createtime", $sell->createtime);
            $this->tag->setDefault("updatetime", $sell->updatetime);
            $this->tag->setDefault("min_number", $sell->min_number);
            $this->view->curAreas = lAreas::ldData($sell->areas);
            $this->view->contents = $contents;
            $this->view->sell = $sell;
            $this->view->goods_unit = Purchase::$_goods_unit;
            $this->view->page = $page;
            $this->view->referer = $_SERVER['HTTP_REFERER'];
            // 发布平台
            $this->view->plat       = M\Users::$_plat;
            $this->view->publish_set= $sell->publish_place;
            $this->view->sell_step_price= M\SellStepPrice::findBysell_id($sell->id)->toArray();
            

        }
    }
    /**
     * 新增供应保存
     */
    
    public function createAction() 
    {
        if (!$this->request->isPost()) 
        {
            return $this->dispatcher->forward(array(
                "controller" => "sell",
                "action" => "new"
            ));
        }
        $user_id = $this->request->getPost('user', 'int', 0);
        $users = Users::findFirstByid($user_id);
        
        if (!$users) 
        {
            parent::msg('请选择会员！','/manage/sell/new');
            // $this->flash->error('请选择会员！');
            // return $this->dispatcher->forward(array(
            //     "controller" => "sell",
            //     "action" => "new"
            // ));
        }
        $maxcategory = $this->request->getPost("maxcategory", 'int', 0);
        $category = $this->request->getPost("category", 'int', 0);
        $autocomplete= $this->request->getPost("autocomplete", 'string', '');
        $province_id=$this->request->getPost("province", 'int',0);
        $city_id=$this->request->getPost("city", 'int',0);
        if ($category == '-1') 
        {
            $qita = M\Category::findFirst(" parent_id={$maxcategory} and title like '%其他%'");
            
            if ($qita) 
            {
                $category = $qita->id;
            }
            else
            {

                $newcategory = new Category();
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
        $areas = $this->request->getPost("cun", 'int', 0);
        $areas_name = Func::getCols(mAreas::getFamily($areas) , 'name', ',');
        $full_name=str_replace(',', '',$areas_name);
        $cur_time = time();
        $sell = new M\Sell();
        $sell->title = $this->request->getPost("title", 'string', '');
        $sell->category = intval($category);
        $sell->maxcategory=intval($maxcategory);
        $sell->min_price = $this->request->getPost("min_price", 'float', 0.00);
        $sell->max_price = $this->request->getPost("max_price", 'float', 0.00);
        $sell->quantity = $this->request->getPost("quantity", 'float', 0.00);
        $sell->price_type = $this->request->getPost("price_type",'int',0);
//        if($sell->price_type == 1)
//            $sell->goods_unit = $this->request->getPost("goods_unit2", 'int', 0);
//        else
//            $sell->goods_unit = $this->request->getPost("goods_unit", 'int', 0);
        $sell->goods_unit = $this->request->getPost("goods_unit2", 'int', 0);
        $sell->stime = $this->request->getPost("stime", 'string', 0);
        $sell->etime = $this->request->getPost("etime", 'string', 0);
        $sell->areas = $areas;
        $sell->areas_name = $areas_name;
        $sell->address = $areas_name;
        $sell->breed = $this->request->getPost("breed", 'string', '');
        $sell->spec = $this->request->getPost("spec", 'string', '');
        $sell->is_hot = $this->request->getPost("is_hot", 'int', 0);
        $sell->uid = $user_id;
        $sell->state = 0;
        $sell->uname = $users->ext->name;
        $sell->mobile = $users->username;
        $sell->min_number = $this->request->getPost("min_number", 'string', '');
        $sell->createtime = $sell->updatetime = $cur_time;
        $sell->full_address=$full_name;
        $sell->publish_place= $this->request->getPost("plat", 'int', 0);
        $sell->province_id=$province_id;
        $sell->city_id=$city_id;
        $content = $this->request->getPost('content');
        
        if (!$sell->save()) 
        {
            parent::msg('发布出售失败！','/manage/sell/new');
            // $this->flash->error('发布出售失败！');
            // return $this->dispatcher->forward(array(
            //     "controller" => "sell",
            //     "action" => "new"
            // ));
        }
        #价格类型判断

        if($sell->price_type == 1){ #阶梯价格保存
            $step_quantity = $this->request->getPost('step_quantity');
            $step_price = $this->request->getPost('step_price');
            foreach($step_quantity AS $stepKey => $stepVal){
                $sell_step_price = new M\SellStepPrice();
                $sell_step_price->sell_id = $sell->id;
                $sell_step_price->price = $step_price[$stepKey];
                $sell_step_price->quantity = $step_quantity[$stepKey];
                $sell_step_price->save();
            }
        }
        $sell->sell_sn = sprintf('SELL%010u', $sell->id);
        $sell->save();
        $sid = md5(session_id());
        //M\SellImages::copyImages($sell->id, $sid);
        M\TmpFile::copyImages($sell->id, $sid);
        M\Category::numAdd($sell->category, 'sell_num');
        $scontent = new M\SellContent();
        $scontent->sid = $sell->id;
        $scontent->content = $content;
        $scontent->save();
        Func::adminlog("新增供应{$sell->title}",$this->session->adminuser["id"]);
        $this->response->redirect('sell/index')->sendHeaders();
    }
    /**
     * 编辑供应保存
     *
     */
    
    public function saveAction() 
    {   
       
        
        
        $cur_time = time();
        $id = $this->request->getPost('id', 'int', 0);
        $sell = M\Sell::findFirstByid($id);
     
        $referer = $this->request->getPost('referer');
       

        $maxcategory = $this->request->getPost("maxcategory", 'int', 0);
        $category = $this->request->getPost("category", 'int', 0);
        
        if ($category == '-1') 
        {   

            $qita = M\Category::findFirst(" parent_id={$maxcategory} and title like '%其他%'");
            
            if ($qita) 
            {
                $category = $qita->id;
            }
            else
            {
              
                $newcategory = new Category();
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
        
        if (!$sell) 
        {

            $this->flash->error('此供应信息不存在！');
            $this->showMessage($referer);
        }
        $plat  = $this->request->getPost('plat', 'int', 0);
        $pages = $this->request->getPost('pages', 'int', 1);
        $areas = $this->request->getPost("cun", 'int', 0);
        $province_id=$this->request->getPost("province", 'int',0);
        $city_id=$this->request->getPost("city", 'int',0);
        $areas_name = Func::getCols(mAreas::getFamily($areas) , 'name', ',');
        $full_name=str_replace(',', '',$areas_name);
        $sell->title = $this->request->getPost("title", 'string', '');
        $sell->category = intval($category);
        $sell->maxcategory=intval($maxcategory);
        $sell->min_price = $this->request->getPost("min_price", 'float', 0.00);
        $sell->max_price = $this->request->getPost("max_price", 'float', 0.00);
        $sell->quantity = $this->request->getPost("quantity", 'float', 0.00);
        $sell->price_type = $this->request->getPost("price_type",'int',0);
//        if($sell->price_type == 1)
//            $sell->goods_unit = $this->request->getPost("goods_unit2", 'int', 0);
//        else
//            $sell->goods_unit = $this->request->getPost("goods_unit", 'int', 0);
        $sell->goods_unit = $this->request->getPost("goods_unit2", 'int', 0);
        $sell->stime = $this->request->getPost("stime", 'int', 0);
        $sell->etime = $this->request->getPost("etime", 'int', 0);
        $sell->areas = $areas;
        $sell->areas_name = $areas_name;
        $sell->address = $areas_name;
        $sell->breed = $this->request->getPost("breed", 'string', '');
        $sell->spec = $this->request->getPost("spec", 'string', '');
        $sell->is_hot = $this->request->getPost("is_hot", 'int', 0);
        $sell->uname = $this->request->getPost("uname", 'string', '');
        $sell->mobile = $this->request->getPost("usermoblie", 'string', '');
        $sell->min_number = $this->request->getPost("min_number", 'string', '');
        $sell->updatetime = $cur_time;
        $sell->province_id=$province_id;
        $sell->city_id=$city_id;
        $sell->full_address=$full_name;
        // 发布平台
        $sell->publish_place=$plat;
        $content = $this->request->getPost('content');
        
        if (!$sell->save()) 
        {
            $this->flash->error('修改供应信息失败！');
            $this->showMessage($referer);
        }
        if($sell->price_type == 1){ #阶梯价格保存
            $SellStepPrice = M\SellStepPrice::findBysell_id($sell->id);
            if($SellStepPrice){
                foreach($SellStepPrice AS $ssp){
                    $ssp->delete();
                }
            }
            $step_quantity = $this->request->getPost('step_quantity');
            $step_price = $this->request->getPost('step_price');
            foreach($step_quantity AS $stepKey => $stepVal){
                $sell_step_price = new M\SellStepPrice();
                $sell_step_price->sell_id = $sell->id;
                $sell_step_price->price = $step_price[$stepKey];
                $sell_step_price->quantity = $step_quantity[$stepKey];
                $sell_step_price->save();
            }
        }
        // 处理图片
        $sid = md5(session_id());
        //M\SellImages::copyImages($sell->id, $sid);
        M\TmpFile::copyImages($sell->id, $sid);
        M\Category::numUpdate($sell->category, 'sell');
        $scontent = M\SellContent::findFirstBysid($sell->id);
        
        if (!$scontent) 
        {
            $scontent = new M\SellContent();
            $scontent->sid = $id;
            $scontent->content = $content;
            $scontent->save();
        }
        $scontent->content = $content;
        $scontent->save();
       
        Func::adminlog("供应商品修改：{$sell->title}",$this->session->adminuser["id"]);
        
        $this->showMessage($referer);
        
        
    }
    /**
     * 供应删除
     * @return [type] [description]
     */
    
    public function deleteAction() 
    {
        $id = $this->request->get('id', 'int', 0);
        
        if (!$id) 
        {
            $rs["state"] = false;
            $rs["msg"] = "操作失败";
            die(json_encode($rs));
        }
        $sell = M\Sell::findFirstByid($id);
        
        if (!$sell) 
        {
            $rs["state"] = false;
            $rs["msg"] = "信息不存在";
            die(json_encode($rs));
        }
        $sell->is_del = !$sell->is_del;
        
        if (!$sell->save()) 
        {
            $rs["state"] = false;
            $rs["msg"] = "操作失败";
            die(json_encode($rs));
        }
        M\Category::numDec($sell->category, 'sell_num');
        $rs["state"] = true;
        Func::adminlog("供应商品删除：{$sell->title}",$this->session->adminuser['id']);
        $rs["msg"] = "删除成功";
        die(json_encode($rs));
    }
    /**
     * 删除供应图片
     * @return [type] [description]
     */
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
    /**
     * 查询供应商
     * @param  [type] $username [description]
     * @return [type]           [description]
     */
    public function showuserAction($username) 
    {
        
        if (!empty($username)) 
        {
            $this->view->ajax = 1;
            $user = M\Users::find(" username like '%" . $username . "%' ");
            $this->view->user = $user->toArray();
        }
        else
        {
            die("请选择查询信息");
        }
    }
    /**
     * 审核操作
     * @return [type] [description]
     */
    
    public function upstateAction() 
    {
        $referer = $this->session->referer = $_SERVER['HTTP_REFERER'];
        $id = $this->request->get("id", 'int', 0);
       

        if (!$id) 
        {
            $rs["state"] = false;
            $rs["msg"] = "操作失败";
            die(json_encode($rs));
        }
        $sell = M\Sell::findFirstByid($id);
        
        if (!$sell) 
        {
            $rs["state"] = true;
            $rs["msg"] = "信息不存在";
            die(json_encode($rs));
        }
        $sell->state = !$sell->state;
        
        if ($sell->state == 1) 
        {
            $statestr = '取消';
            $nostate = "已审核";
        }
        else
        {
            $statestr = '审核';
            $nostate = "待审核";
        }
        
        if ($sell->save()) 
        {
            $rs["nostate"] = $nostate;
            $rs["statestr"] = $statestr;
            $rs["state"] = true;
            Func::adminlog("供应商品取消审核：{$sell->title}",$this->session->adminuser['id']);
            $rs["msg"] = "操作成功";
            die(json_encode($rs));
        }
        exit;
        // $this->showMessage($referer);
    }
    /**
     * 导入表格
     * @return [type] [description]
     */
    public function upexcalAction() 
    {
        
        if (!Purchase::check_files($_FILES["myexcal"]['name'])) 
        {
            echo "<script>alert('表格格式不对');'</script>";
        }
        $categoryPath = 'excel/' . date('Ymd') . '/';
        $rs = Purchase::move_file($_FILES["myexcal"], $categoryPath);
        $excal_path = $rs['path'];
        
        if ($excal_path) 
        {
            $this->excalAction($excal_path);
        }
    }
    /**
     * 导入表格执行
     * @param  string $excal [description]
     * @return [type]        [description]
     */
    public function excalAction($excal = '') 
    {
        $excal = $excal;
        $excalpath = strtolower(trim(substr(strrchr($excal, '.') , 1)));
        $xlsPath = $excal;
        
        if ($excalpath == 'xlsx') 
        {
            $type = 'Excel2007';
        }
        else
        {
            $type = 'Excel5';
        }
        include_once 'excel/Classes/PHPExcel.php';
        include_once 'excel/Classes/PHPExcel/IOFactory.php';
        $xlsReader = \PHPExcel_IOFactory::createReader($type);
        $xlsReader->setReadDataOnly(true);
        $xlsReader->setLoadSheetsOnly(true);
        $Sheets = $xlsReader->load($xlsPath);
        //开始读取
        $eet = $Sheets->getSheet(0)->toArray();
        
        $zong = count($eet);
        
        if ($eet[1][0] != "供应信息收集表") 
        {
            unlink($excel);
            parent::msg('请上传指定的表格','/manage/purchase/new');
        }
        //  //删除临时表
        $reg = M\Reg::find("type=0");
        $reg->delete();
        
        foreach ($eet as $key => $value) 
        {
            
            if ($key < 300) 
            {
                
                if ($key > 4 && $value[0] != "") 
                {
                    M\Reg::checksell($value);
                }
                else
                {
                    $error[] = $value;
                }
            }
        }
        unlink($excal);
        parent::msg('导入完成','/manage/sell/new?suc=' . $zong );
        // echo "<script>alert('导入完成');location.href='/manage/sell/new?suc=" . $zong . "'</script>";
    }
    /**
     * 下载表格
     * @return [type] [description]
     */
    public function downexcalAction() 
    {
        $arrs = M\Reg::find("type=0")->toArray();
        
        if (!empty($arrs)) 
        {
            include_once 'excel/Classes/PHPExcel.php';
            include_once 'excel/Classes/PHPExcel/IOFactory.php';
            // 创建一个处理对象实例
            $objExcel = new \PHPExcel();
            $objWriter = new \PHPExcel_Writer_Excel5($objExcel); // 用于其他版本格式
            $objExcel->setActiveSheetIndex(0);
            $objActSheet = $objExcel->getActiveSheet();
            //设置当前活动sheet的名称
            $objActSheet->setTitle('exce导出');
             
            foreach ($arrs as $key => $val) 
            {
                $array[$key]['title'] = empty($val['title']) ? '-' : $val['title'];
                $array[$key]['max_category'] = empty($val['max_category']) ? '-' : $val['max_category'];
                $array[$key]['min_category'] = empty($val['min_category']) ? '-' : $val['min_category'];
                $array[$key]['min_price'] = empty($val['min_price']) ? '-' : $val['min_price'];
                $array[$key]['max_price'] = empty($val['max_price']) ? '-' : $val['max_price'];
                $array[$key]['quantity'] = empty($val['quantity']) ? '-' : $val['quantity'];
                $array[$key]['goods_unit'] = empty($val['goods_unit']) ? '-' : $val['goods_unit'];
                $array[$key]['stime'] = empty($val['stime']) ? '-' : $val['stime'];
                $array[$key]['etime'] = empty($val['etime']) ? '-' : $val['etime'];
                $array[$key]['province'] = empty($val['province']) ? '-' : $val['province'];
                $array[$key]['city'] = empty($val['city']) ? '-' : $val['city'];
                $array[$key]['town'] = empty($val['town']) ? '-' : $val['town'];
                $array[$key]['breed'] = empty($val['breed']) ? '-' : $val['breed'];
                $array[$key]['spec'] = empty($val['spec']) ? '-' : $val['spec'];
                $array[$key]['address'] = empty($val['address']) ? '-' : $val['address'];
                $array[$key]['sellmoblie'] = empty($val['sellmoblie']) ? '-' : $val['sellmoblie'];
                $array[$key]['sellname'] = empty($val['sellname']) ? '-' : $val['sellname'];
                $array[$key]['content'] = empty($val['content']) ? '-' : $val['content'];
            }
            $objExcel->getActiveSheet()->setCellValue('A1', "商品名称"); //设置列的值
            $objExcel->getActiveSheet()->setCellValue('B1', "一级分类"); //设置列的值
            $objExcel->getActiveSheet()->setCellValue('C1', "二级分类"); //设置列的值
            $objExcel->getActiveSheet()->setCellValue('D1', "最大价格"); //设置列的值
            $objExcel->getActiveSheet()->setCellValue('E1', "最小价格"); //设置列的值
            $objExcel->getActiveSheet()->setCellValue('F1', "供应量"); //设置列的值
            $objExcel->getActiveSheet()->setCellValue('G1', "供应单位"); //设置列的值
            $objExcel->getActiveSheet()->setCellValue('H1', "上市时间"); //设置列的值
            $objExcel->getActiveSheet()->setCellValue('I1', "下市时间"); //设置列的值
            $objExcel->getActiveSheet()->setCellValue('J1', "品种"); //设置列的值
            $objExcel->getActiveSheet()->setCellValue('K1', "规则"); //设置列的值
            $objExcel->getActiveSheet()->setCellValue('L1', "描述"); //设置列的值
            $objExcel->getActiveSheet()->setCellValue('M1', "供应商姓名"); //设置列的值
            $objExcel->getActiveSheet()->setCellValue('N1', "联系电话"); //设置列的值
            $objExcel->getActiveSheet()->setCellValue('O1', "省"); //设置列的值
            $objExcel->getActiveSheet()->setCellValue('P1', "市"); //设置列的值
            $objExcel->getActiveSheet()->setCellValue('Q1', "县"); //设置列的值
            $objExcel->getActiveSheet()->setCellValue('R1', "详细地址"); //设置列的值
            $objExcel->getActiveSheet()->getColumnDimension("A")->setWidth(15);
            $objExcel->getActiveSheet()->getColumnDimension("B")->setWidth(15);
            $objExcel->getActiveSheet()->getColumnDimension("C")->setWidth(15);
            $objExcel->getActiveSheet()->getColumnDimension("D")->setWidth(15);
            $objExcel->getActiveSheet()->getColumnDimension("E")->setWidth(15);
            $objExcel->getActiveSheet()->getColumnDimension("F")->setWidth(15);
            $objExcel->getActiveSheet()->getColumnDimension("G")->setWidth(15);
            $objExcel->getActiveSheet()->getColumnDimension("H")->setWidth(15);
            $objExcel->getActiveSheet()->getColumnDimension("I")->setWidth(15);
            $objExcel->getActiveSheet()->getColumnDimension("J")->setWidth(15);
            $objExcel->getActiveSheet()->getColumnDimension("K")->setWidth(15);
            $objExcel->getActiveSheet()->getColumnDimension("L")->setWidth(15);
            $objExcel->getActiveSheet()->getColumnDimension("M")->setWidth(15);
            $objExcel->getActiveSheet()->getColumnDimension("N")->setWidth(15);
            $objExcel->getActiveSheet()->getColumnDimension("O")->setWidth(15);
            $objExcel->getActiveSheet()->getColumnDimension("P")->setWidth(15);
            $objExcel->getActiveSheet()->getColumnDimension("Q")->setWidth(15);
            $objExcel->getActiveSheet()->getColumnDimension("R")->setWidth(15);
            $i = 2;
            
            foreach ($array as $key => $val) 
            {
                $objExcel->getActiveSheet()->setCellValue("A{$i}", $val['title']);
                $objExcel->getActiveSheet()->setCellValue("B{$i}", $val['max_category']);
                $objExcel->getActiveSheet()->setCellValue("C{$i}", $val['min_category']);
                $objExcel->getActiveSheet()->setCellValue("D{$i}", $val['max_price']);
                $objExcel->getActiveSheet()->setCellValue("E{$i}", $val['min_price']);
                $objExcel->getActiveSheet()->setCellValue("F{$i}", $val['quantity']);
                $objExcel->getActiveSheet()->setCellValue("G{$i}", $val['goods_unit']);
                $objExcel->getActiveSheet()->setCellValue("H{$i}", $val['stime']);
                $objExcel->getActiveSheet()->setCellValue("I{$i}", $val['etime']);
                $objExcel->getActiveSheet()->setCellValue("J{$i}", $val['breed']);
                $objExcel->getActiveSheet()->setCellValue("K{$i}", $val['spec']);
                $objExcel->getActiveSheet()->setCellValue("L{$i}", $val['content']);
                $objExcel->getActiveSheet()->setCellValue("M{$i}", $val['sellname']);
                $objExcel->getActiveSheet()->setCellValue("N{$i}", $val['sellmoblie']); //设置列的值
                $objExcel->getActiveSheet()->setCellValue("O{$i}", $val['province']); //设置列的值
                $objExcel->getActiveSheet()->setCellValue("P{$i}", $val['city']); //设置列的值
                $objExcel->getActiveSheet()->setCellValue("Q{$i}", $val['town']); //设置列的值
                $objExcel->getActiveSheet()->setCellValue("R{$i}", $val['address']); //设置列的值
                $i++;
            }
            // //合并单元格
            // $objActSheet->mergeCells('B1:C22');
            // //分离单元格
            // $objActSheet->unmergeCells('B1:C22');
            $outputFileName = "output.xls";
            header("Content-Type:application/octet-stream;charset=utf-8");
            header('Content-Disposition: attachment; filename=' . $outputFileName);
            $objWriter->save('php://output');
            exit;
        }
        else
        {
            echo "<script>alert('没有信息');location.href='/manage/sell/new'</script>";
        }
    }
    
    public function downloadAction() 
    {
        $myfile = "excel/pur.xls";
        $len = filesize($myfile);
        ob_end_clean();
        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: public');
        header('Content-Description: File Transfer');
        header('Content-type: application/octet-stream');
        header('Content-Disposition: attachment; filename="卖的贵供应信息表.xls"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . $len);
        readfile($myfile);
    }
    //批量审核操作
    
    public function reviewAction() 
    {
        $sellid = $this->request->get("id", 'string', '');
        $type = $this->request->get("type", 'int', 0);
        $stime = $this->request->get("stime", 'string', '');
        $etime = $this->request->get("etime", 'string', '');
        
        if (!$sellid) 
        {
            $rs["state"] = false;
            $rs["msg"] = "操作失败";
            die(json_encode($rs));
        }
        $sell_id = rtrim($sellid, ",");
        
        if ($sell_id && $type == 1) 
        {
            $sql = "update sell set state=1 where id in ($sell_id) and state=0 and is_del=0  ";
        }
        
        if ($sell_id && $type == 2 && $stime && $etime) 
        {
            $sellid = explode(',', $sell_id);
            $timecount = count($sellid);
            
            for ($i = 0; $i < $timecount; $i++) 
            {
                $time[] = $this->randomDate($stime, $etime);
            }
            $sql = '';
            
            foreach ($sellid as $key => $value) 
            {
                $times = $time[$key];
                $sql.= "update sell set updatetime='{$times}' ,createtime='{$times}' where id=$value and state=1 and is_del=0" . ';';
            }
        }
        
        if ($sell_id && $type == 3) 
        {
            $sql = "update sell set state=0 where id in ($sell_id) and state=1 and is_del=0  ";
        }
        
        if ($sell_id && $type == 4) 
        {
            $sql = "update sell set is_del=1 where id in ($sell_id) and is_del=0  ";
        }
        
        if (!$GLOBALS['di']['db']->execute($sql)) 
        {
            $rs["state"] = false;
            $rs["msg"] = "操作失败";
            die(json_encode($rs));
        }
        $rs["state"] = true;
        $rs["msg"] = "操作成功";
        die(json_encode($rs));
    }
    /**
     *   生成某个范围内的随机时间
     * @param <type> $begintime  起始时间 格式为 Y-m-d H:i:s
     * @param <type> $endtime    结束时间 格式为 Y-m-d H:i:s
     */
    function randomDate($begintime, $endtime) 
    {
        $begin = strtotime($begintime);
        $end = $endtime == "" ? mktime() : strtotime($endtime);
        $timestamp = rand($begin, $end);
        return $timestamp;
    }
    //刷新操作
    
    public function reloadAction() 
    {
        $id = $this->request->get("id", 'int', 0);
        
        if (!$id) 
        {
            $rs["state"] = false;
            $rs["msg"] = "操作失败";
            die(json_encode($rs));
        }
        $sell = M\Sell::findFirstByid($id);
        
        if (!$sell) 
        {
            $rs["state"] = true;
            $rs["msg"] = "信息不存在";
            die(json_encode($rs));
        }
        $sell->updatetime = time();
        $sell->createtime = time();
        
        if ($sell->save()) 
        {
            $rs["state"] = true;
            $rs["msg"] = "操作成功";
            die(json_encode($rs));
        }
    }

    /**
     * 推荐
     * @param  [type]  $id   供应Id
     * @param  integer $page [description]
     * @return [type]        [description]
     */
    public function recommandAction(){

        $id = $this->request->get("id", 'int', 140596);
        $type = $this->request->get("type", 'int', 2);
        
        $recommand_sell = M\RecommandSell::findFirstBysell_id($id);
        if($recommand_sell){
            $recommand_sell->last_update_time = time();
            if($type == 1){
                $recommand_sell->location_home = $recommand_sell->location_home==1 ? 0 : 1;
            }elseif($type==2){
                $sell=M\Sell::findFirstByid($id);
                if($sell){
                    $sell->is_hot=$recommand_sell->location_category==1 ? 0 : 1;
                    $sell->save();
                }
                $recommand_sell->location_category = $recommand_sell->location_category==1 ? 0 : 1;
            }else{
                $recommand_sell->location_hot = $recommand_sell->location_hot==1 ? 0 : 1;
            }
            $recommand_sell->last_update_time = time();
        }else{
            
            $recommand_sell = new M\RecommandSell();
            $recommand_sell->sell_id = $id;
            $recommand_sell->add_time = time();
            $recommand_sell->last_update_time = time();
            if($type == 1){
                $recommand_sell->location_home=1;
                $recommand_sell->location_category=0;
                $recommand_sell->location_hot=0;
            }elseif($type==2){
                //如果是供应推荐  
                $sell=M\Sell::findFirstByid($id);
                if($sell){
                    $sell->is_hot=1;
                    $sell->save();
                }
                $recommand_sell->location_category=1;
                $recommand_sell->location_home=0;
                $recommand_sell->location_hot=0;
            }else{
                $recommand_sell->location_hot=1;
                $recommand_sell->location_category=0;
                $recommand_sell->location_home=0;
            }
        }

        if($recommand_sell->save()){
            $msg = '操作成功';
        }else{
            $msg = '操作失败';
        }
        die(json_encode($msg));
    }
    

    /**
     * 审核
     * @param  [type]  $id   供应Id
     * @param  integer $page [description]
     * @return [type]        [description]
     */
    
    public function shenheAction($id, $page = 1) 
    {
        $this->session->referer = $_SERVER['HTTP_REFERER'];
        
        if (!$this->request->isPost()) 
        {
            $sell = M\Sell::findFirstByid($id);
            
            if (!$sell) 
            {
                parnet::msg('供应信息未找到','/manage/sell/index');
                // $this->flash->error("供应信息未找到");
                // return $this->dispatcher->forward(array(
                //     "controller" => "sell",
                //     "action" => "index"
                // ));
            }
            $scontent = M\SellContent::findFirstBysid($id);
            $contents = '';
     
            if ($scontent&&$scontent->attr != '') 
            {
                
                if ($id < 23028) 
                {
                    $arr = str_replace("u", '\u', $scontent->attr);
                    $code = json_decode($arr, true);
                }
                else
                {
                    $code = json_decode($scontent->attr, true);
                }
                
                foreach ($code as $key => $value) 
                {
                    $contents.= $value['title'] . ":" . $value['val'] . ';';
                }
            }
            
            if ($sell->sell_sn == "") 
            {
                $sell->sell_sn = sprintf('SELL%010u', $sell->id);
                $sell->save();
            }
            #判断区间价格显示
            if($sell->price_type == 1){
                $sell_step_price = M\SellStepPrice::findBysell_id($sell->id)->toArray();
                $this->view->sell_step_price = $sell_step_price;
            }
            $sid = md5(session_id());
            TmpFile::clearOld($sid);
            $imgfile = M\SellImages::find("sellid=" . $id)->toArray();
            $this->view->stime = $sell->stime;
            $this->view->imgfile = $imgfile;
            $this->view->etime = $sell->etime;
            $this->view->goods_unit = $sell->goods_unit;
            $this->view->tfile = TmpFile::find("sid='{$sid}'");
            $this->view->sid = $sid;
            $this->view->id = $sell->id;
            $cat = lCategory::getCatTree($sell->category);
            
            $curCat = "'" . Func::getCols($cat, 'title', "', '") . "'";
            $this->view->curCat = $curCat;
            $content = M\SellContent::findFirstBysid($sell->id);
            $this->tag->setDefault("id", $sell->id);
            $this->tag->setDefault("title", $sell->title);
            $this->tag->setDefault("max_price", $sell->max_price);
            $this->tag->setDefault("min_price", $sell->min_price);
            $this->tag->setDefault("quantity", $sell->quantity);
            $this->tag->setDefault("areas", $sell->areas);
            $this->tag->setDefault("address", $sell->address);
            $this->tag->setDefault("stime", $sell->stime);
            $this->tag->setDefault("etime", $sell->etime);
            $this->tag->setDefault("breed", $sell->breed);
            $this->tag->setDefault("spec", $sell->spec);
            $this->tag->setDefault("state", $sell->state);
            $this->tag->setDefault("is_hot", $sell->is_hot);
            $this->tag->setDefault("usermoblie", $sell->mobile);
            $this->tag->setDefault("content", $content ? $content->content : '-');
            $this->tag->setDefault("uname", $sell->uname);
            $this->tag->setDefault("createtime", $sell->createtime);
            $this->tag->setDefault("updatetime", $sell->updatetime);
            $this->view->curAreas = lAreas::ldData($sell->areas);
            $this->view->contents = $content;
            $this->view->sell = $sell;
            $this->view->pages = $page;
            $this->view->goods_unit = Purchase::$_goods_unit;
            $this->view->referer = $_SERVER['HTTP_REFERER'];
            // 发布平台
            $this->view->plat = M\Users::$_plat;
        }
    }
    /**
     * 供应信息审核
     * @return [type] [description]
     */
    
    public function auditorpassAction() 
    {   

        $sid = $this->request->getPost("id", 'int', 0);
        $pages = $this->request->getPost('pages', 'int', 1);
        $referer = $this->request->getPost('referer', 'string', '');
        
        if (!$sid) 
        {
            $this->response->redirect('sell/index')->sendHeaders();
        }
        $sell = M\Sell::findFirstByid($sid);
        
        if (!$sell || $sell->state != 0) 
        {
            $this->response->redirect('sell/index')->sendHeaders();
        }
        $sell->state = !$sell->state;
        
        if (!$sell->save()) 
        {
        }
        Func::adminlog("供应商品审核通过：{$sell->title}",$this->session->adminuser['id']);        
        $this->showMessage($referer);die;
    }
    /**
     * 供应审核未通过
     * @return [type] [description]
     */
    
    public function fallAction() 
    {
        $sid = $this->request->getPost('id', 'int', 0);
        $reject = $this->request->getPost('reject', 'string', '');
        $pages = $this->request->getPost('pages', 'int', 1);
        $referer = $this->request->getPost('referer', 'string', '');
        
        if (!$sid) 
        {
            $this->response->redirect('sell/index')->sendHeaders();
        }
        $sell = M\Sell::findFirstByid($sid);
        
        if (!$sell || $sell->state != 0) 
        {
            $this->response->redirect('sell/index')->sendHeaders();
        }
        $sell->state = 2;
        
        if (!$sell->save()) 
        {
        }
        $SellCheck = new M\SellCheck();
        $SellCheck->sell_id = $sid;
        $SellCheck->fail_reason = $reject;
        $SellCheck->add_time = time();
        $SellCheck->save();
        Func::adminlog("供应商品审核未通过：{$sell->title}",$this->session->adminuser['id']);
        
        $this->showMessage($referer);
        $this->response->redirect('sell/index?p=' . $pages)->sendHeaders();
    }
    /**
     * 获取供应分类
     * @return [type] [description]
     */
    public function getcateAction(){
        
     
        $pid = $this->request->get('pid','int',0);
        $autocomplete = $this->request->get('autocomplete','string','');
        if(!$pid){
             $rs = array('state'=>true);
             die(json_encode($rs));
        }

        $cate=M\Category::find("parent_id={$pid} and title like '%{$autocomplete}%' ")->toArray();
         
        if(empty($cate)){
            $data[] = array('label'=>"其他",'value'=>"其他",'id'=>'-1');
        }
        foreach ($cate as $key => $value) {
           if($value["title"]!=''){
              $data[] = array('label'=>$value['title'],'value'=>$value['title'],'id'=>$value['id']);
           }
        }
        die(json_encode($data));

    }

}
