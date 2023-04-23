<?php
/**
 * 采购管理
 * 2015-03-16
 */
namespace Mdg\Manage\Controllers;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Mdg\Models\Purchase as Purchase;
use Mdg\Models\PurchaseContent as PurchaseContent;
use Mdg\Models\Users as Users;
use Mdg\Models\Reg as reg;
use Lib\Areas as lAreas;
use Lib\Category as lCategory;
use Lib\Func as Func;
use Mdg\Models as M;
use Lib\Pages as Pages;

class PurchaseController extends ControllerMember
{
    /**
     * 采购列表
     */
    
    public function indexAction() 
    {

        $page = $this->request->get('p', 'int', 1);
        $state = $this->request->get('state', 'int', 0);
        $category = $this->request->get('category', 'int', 0);
        $pur_sn = $this->request->get('pur_sn', 'string', '');
        $stime = $this->request->get('stime', 'string', '');
        $etime = $this->request->get('etime', 'string', '');
        $title = $this->request->get('title', 'string', '');
        $maxcategory = $this->request->get("maxcategory", 'int', 0);
        $location_home = $this->request->get('location_home', 'string', '');
        $location_category = $this->request->get('location_category', 'string', '');
        $location_hot = $this->request->get('location_hot', 'string', '');
        $where[] = "1=1";
        $page_size = 10;

                
        if ($state == '1') 
        {
            $where[] = "state=0 and is_del=0 ";
        }
        
        if ($state == '2') 
        {
            $where[] = "state=1 and is_del=0 ";
        }
        
        if ($state == '3') 
        {
            $where[] = "state=2 and is_del=0 ";
        }
        
        if ($state == 99) 
        {
            $where[] = " is_del = 1 ";
        }
        
        if ($category) 
        {
            $where[] = "category='{$category}'";
        }
        if($maxcategory) 
        {
            $where[]= "  maxcategory = $maxcategory  ";
        }
        if ($pur_sn) 
        {
            $where[] = "pur_sn = '{$pur_sn}'";
        }
        
        if ($stime) 
        {
            $where[] = "updatetime >= " . strtotime($stime);
        }
        
        if ($etime) 
        {
            $where[] = "updatetime <= " . strtotime($etime);
        }
        
        if ($title) 
        {
            $where[] = "username like '%{$title}%'";
        }

        $recommand_where=array();
        if($location_home){
            $recommand_where[] = " location_home = {$location_home} ";
        }
        if($location_category){
            $recommand_where[] = " location_category = {$location_category}";
        }
        if($location_hot){
            $recommand_where[] = " location_hot = {$location_hot}";
        }

        $recommand_where  = implode( ' AND ', $recommand_where);
        //echo $recommand_where;die;
        if($recommand_where){
                $where[]= M\RecommandPurchase::getpurchase_id($recommand_where);
            }
        $where = implode(' and ', $where);     
       //echo $where;die;
      
        $total = Purchase::count($where);
        $offst = intval(($page - 1) * $page_size);
        $data = Purchase::find($where . " ORDER BY createtime DESC limit {$offst} , {$page_size} ");
        $pages['total_pages'] = ceil($total / $page_size);
        $pages['current'] = $page;
        $pages['total'] = $total;
        $pages = new Pages($pages);
        $pages = $pages->show(3);
        $this->view->current = $page;
        $this->view->data = $data;
        $this->view->pages = $pages;
        $this->view->is_show = M\ArticleCategory::$_is_show;
        $this->view->goods_unit = Purchase::$_goods_unit;
        $this->view->state2 = Purchase::$_state;
        $this->view->state1 = Purchase::$_state1;
        //默认选中的值
        
        $this->view->status = $state;
        $this->view->pur_sn = $pur_sn;
        $this->view->stime = $stime;
        $this->view->etime = $etime;
        $this->view->title = $title;

        $cat[] = lCategory::ldDataName($this->request->get("maxcategory") , 1);
        $cat[] = lCategory::ldDataName($this->request->get("categorys") , 2);
        $this->view->cat_name = join(',', $cat);
    }
    /**
     * 新增采购
     */
    
    public function newAction() 
    {
        $userid = $this->request->get('u', 'int', 0);
        
        if ($this->request->get("suc")) 
        {
            $arrs = M\Reg::find("type=1")->toArray();
            $this->view->suc = $this->request->get("suc");
            $this->view->error = count($arrs);
        }
        $users = Users::findFirstByid($userid);
        $where = array();
        $this->view->goods_unit = Purchase::$_goods_unit;
        $this->view->users = $users;
        $this->view->areas_name = $users ? lAreas::ldData($users->areas) : '';
    }
    /**
     * 编辑采购
     *
     * @param string $id
     */
    
    public function editAction($id, $pages = 1) 
    {
        $this->session->sessionREFERER = $_SERVER['HTTP_REFERER'];
        
        if (!$this->request->isPost()) 
        {
            $purchase = Purchase::findFirstByid($id);
            
            if (!$purchase) 
            {
                parent::msg('此采购信息不存在！','/manage/purchase/index');
                // $this->flash->error("此采购信息不存在！");
                // return $this->dispatcher->forward(array(
                //     "controller" => "purchase",
                //     "action" => "index"
                // ));
            }
            $this->view->pages = $pages;
            $this->view->purchase = $purchase;
            $this->view->goods_unit = Purchase::$_goods_unit;
            $this->view->curCate = lCategory::ldData($purchase->category);
            $this->view->curAreas = lAreas::ldData($purchase->areas);
        }
    }
    /**
     * 新增采购保存
     */
    
    public function createAction() 
    {
        
             
        if (!$this->request->isPost()) 
        {
            return $this->dispatcher->forward(array(
                "controller" => "purchase",
                "action" => "index"
            ));
        }
        $userid = $this->request->get('user', 'int', 0);
        $users = M\Users::findFirstByid($userid);
        $areas=M\AreasFull::getFamily($users->areas);
        if (!$users) 
        {
            parent::msg('请先绑定用户！','/manage/purchase/new');
            // echo '<script>alert("请先绑定用户！");window.location.href="/manage/purchase/new";</script>';
            // exit;
        }
        $endtime=$this->request->getPost("endtime", 'string', '');
        $purchase = new Purchase();
        $purchase->uid = $userid;
        $purchase->title = $this->request->getPost("title", 'string', '');
        $purchase->maxcategory=$this->request->getPost("maxcategory", 'int', 0);
        $purchase->category = $this->request->getPost("category", 'int', 0);
        $purchase->quantity = $this->request->getPost("quantity", 'float', 0.00);
        $purchase->goods_unit = $this->request->getPost("goods_unit", 'int', 0);
        $purchase->areas = $this->request->getPost("village", 'int', 0);
        $purchase->areas_name = Func::getCols($areas , 'name', ',');
        $purchase->address = $purchase->areas_name; //$this->request->getPost("address", 'string', '');
        $purchase->username = $this->request->getPost("username", 'string', '');
        $purchase->mobile = $this->request->getPost("mobile", 'string', '');;
        $purchase->endtime = $endtime ? strtotime($endtime) : '';
        $purchase->province_id=$this->request->getPost("province", 'int', 0);
        $purchase->city_id=$this->request->getPost("city", 'int', 0);
        $purchase->createtime = $purchase->updatetime = time();
        $purchase->state = 1;
        
        if (!$purchase->save()) 
        {
            parent::msg('添加采购失败！','/manage/purchase/new');
            // $this->flash->error('添加采购失败！');
            // return $this->dispatcher->forward(array(
            //     "controller" => "purchase",
            //     "action" => "new"
            // ));
        }
        $purchase->pur_sn = sprintf('pur%010u', $purchase->id);
        $purchase->save();
        $purchaseContent = new PurchaseContent();
        $purchaseContent->purid = $purchase->id;
        $purchaseContent->content = $this->request->getPost("content", 'string', '');
        
        if (!$purchaseContent->save()) 
        {
            $purchase = Purchase::findFirstByid($purchase->id)->delete();
            parent::msg('添加采购失败！','/manage/purchase/new');
            // $this->flash->error('添加采购失败！');
            // return $this->dispatcher->forward(array(
            //     "controller" => "purchase",
            //     "action" => "new"
            // ));
        }
        M\Category::numAdd($purchase->category, 'pur_num');

        Func::adminlog("添加采购商品：{$purchase->title}",$this->session->adminuser['id']);
        parent::msg('添加成功','/manage/purchase/index');
        // $this->flash->success("添加成功");
        // $this->response->redirect('purchase/index')->sendHeaders();
    }
    /**
     * 编辑采购保存
     *
     */
    
    public function saveAction() 
    {
        
        if (!$this->request->isPost()) 
        {
            return $this->dispatcher->forward(array(
                "controller" => "purchase",
                "action" => "index"
            ));
        }
        $id = $this->request->getPost("purid");
        $pages = $this->request->getPost("pages");
        $purchase = Purchase::findFirstByid($id);
        
        if (!$purchase) 
        {
            parent::msg('此采购信息不存在！','/manage/purchase/index');
            // $this->flash->error("此采购信息不存在！");
            // return $this->dispatcher->forward(array(
            //     "controller" => "purchase",
            //     "action" => "index"
            // ));
        }
        $endtime=$this->request->getPost("endtime", 'string', '');
        $purchase->title = $this->request->getPost("title", 'string', '');
        $purchase->category = $this->request->getPost("category", 'int', 0);
        $purchase->maxcategory=$this->request->getPost("maxcategory", 'int', 0);
        $purchase->quantity = $this->request->getPost("quantity", 'float', 0.00);
        $purchase->goods_unit = $this->request->getPost("goods_unit", 'int', 0);
        $purchase->areas = $this->request->getPost("village", 'int', 0);
        $purchase->areas_name = Func::getCols(M\AreasFull::getFamily($purchase->areas) , 'name', ',');
        $purchase->address = $purchase->areas_name; //$this->request->getPost("address", 'string', '');
        $purchase->username = $this->request->getPost('username', 'string', '');
        $purchase->mobile = $this->request->getPost('mobile', 'string', '');;
        $purchase->province_id=$this->request->getPost("province", 'int', 0);
        $purchase->city_id=$this->request->getPost("city", 'int', 0);
        $purchase->endtime = $endtime ? strtotime($endtime) : '';
        $purchase->updatetime = time();
        
        if (!$purchase->save()) 
        {
            parent::msg('修改采购信息失败！',"/manage/purchase/edit/{$id}");
            // $this->flash->error('修改采购信息失败！');
            // return $this->dispatcher->forward(array(
            //     "controller" => "purchase",
            //     "action" => "edit",
            //     "params" => array(
            //         $purchase->id
            //     )
            // ));
        }
        $purchaseContent = M\PurchaseContent::findFirstBypurid($id);
        
        if (!$purchaseContent) 
        {
            $purcontent = new M\purchaseContent();
            $purcontent->purid = $purchase->id;
            $purcontent->content = $this->request->getPost("content", 'string', '');
            
            if (!$purcontent->save()) 
            {
                parent::msg('修改采购信息失败！',"/manage/purchase/edit/{$id}");
                // $this->flash->error('修改采购信息失败！');
                // $this->response->redirect('/purchase/edit/' . $purchase->id . '')->sendHeaders();
            }
        }
        else
        {
            $purchaseContent->content = $this->request->getPost("content", 'string', '');
            
            if (!$purchaseContent->save()) 
            {
                $purchase = Purchase::findFirstByid($purchase->id)->delete();
                parent::msg('修改采购信息失败！',"/manage/purchase/edit/{$id}");
                // $this->flash->error('修改采购信息失败！');
                // return $this->dispatcher->forward(array(
                //     "controller" => "purchase",
                //     "action" => "edit",
                //     "params" => array(
                //         $purchase->id
                //     )
                // ));
            }
        }
        M\Category::numUpdate($purchase->category, 'pur');

        Func::adminlog("修改采购商品：{$purchase->title}",$this->session->adminuser['id']);
        $re = $this->session->sessionREFERER;
        $this->showMessage($re);
        parent::msg('修改成功','/purchase/index?p=' . $pages);
        // $this->flash->success("修改成功");
        // $this->response->redirect('/purchase/index?p=' . $pages . '')->sendHeaders();
    }
    /**
     *  删除采购
     */
    
    public function deleteAction($id) 
    {
        $purchase = Purchase::findFirstByid($id);
        
        if (!$purchase) 
        {
            parent::msg('此采购信息不存在！','/manage/purchase/index');
            // $this->flash->error("此采购信息不存在！");
            // return $this->dispatcher->forward(array(
            //     "controller" => "purchase",
            //     "action" => "index"
            // ));
        }
        $purchase->is_del = 1;
        
        if (!$purchase->save()) 
        {
            parent::msg('采购信息删除失败！','/manage/purchase/search');
            // $this->flash->error("采购信息删除失败！");
            // return $this->dispatcher->forward(array(
            //     "controller" => "purchase",
            //     "action" => "search"
            // ));
        }
        M\Category::numDec($purchase->category, 'pur_num');

        
        Func::adminlog("采购商品删除{$purchase->title}",$this->session->adminuser['id']);
        parent::msg('采购信息删除成功！','/manage/purchase/index');
        // $this->flash->success("采购信息删除成功！");
        // return $this->dispatcher->forward(array(
        //     "controller" => "purchase",
        //     "action" => "index"
        // ));
    }
    /**
     * 供应取消审核
     * @param  [type] $id 供应id
     * @return 
     */
    public function upstateAction($id=0) 
    {
        $Purchase = M\Purchase::findFirstByid($id);
        $Purchase->state = !$Purchase->state;
        
        if ($Purchase->save()) 
        {
            Func::adminlog("采购商品取消审核：{$Purchase->title}",$this->session->adminuser['id']);
        }
        //跳转
        $referer = $_SERVER['HTTP_REFERER'];
        $this->showMessage($referer);
    }
    /**
     * 查询采购商的信息
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
     * 导入采购信息
     * @return [type] [description]
     */
    public function upexcalAction() 
    {
        
        if ($_FILES["myexcal"]["error"] == "0") 
        {
            
            if (!Purchase::check_files($_FILES["myexcal"]['name'])) 
            {
                parent::msg('表格格式不对','/manage/purchase/new');
                // echo "<script>alert('表格格式不对');location.href='/manage/purchase/new'</script>";
                // die;
            }
            $categoryPath = 'excel/' . date('Ymd') . '/';
            $rs = Purchase::move_file($_FILES["myexcal"], $categoryPath);
            $excal_path = $rs['path'];
            
            if ($excal_path) 
            {
                $this->excalAction($excal_path);
            }
        }
        else
        {
            parent::msg('请选择上传文件','/manage/purchase/new');
            // echo "<script>alert('请选择上传文件');location.href='/manage/purchase/new'</script>";
            // die;
        }
    }
    /**
     * 导入采购信息执行
     * @param  string $excel [description]
     * @return [type]        [description]
     */
    public function excalAction($excel = '') 
    {
        $excel = $excel;
        include_once 'excel/Classes/PHPExcel.php';
        include_once 'excel/Classes/PHPExcel/IOFactory.php';
        $excalpath = strtolower(trim(substr(strrchr($excel, '.') , 1)));
        $xlsPath = $excel;
        
        if ($excalpath == 'xlsx') 
        {
            $type = 'Excel2007';
        }
        else
        {
            $type = 'Excel5';
        }
        $xlsReader = \PHPExcel_IOFactory::createReader($type);
        $xlsReader->setReadDataOnly(true);
        $xlsReader->setLoadSheetsOnly(true);
        $Sheets = $xlsReader->load($xlsPath);
        //开始读取
        $eet = $Sheets->getSheet(0)->toArray();
        
        if ($eet[1][0] != "采购信息收集表") 
        {
            unlink($excel);
            parent::msg('请上传指定的表格','/manage/purchase/new');
            // echo "<script>alert('请上传指定的表格');location.href='/manage/purchase/new'</script>";
            // die;
        }
        //删除临时表
        $reg = M\Reg::find("type=1");
        $reg->delete();
        
        foreach ($eet as $key => $value) 
        {
            
            if ($key < 100) 
            {
                
                if ($key > 4 && $value != '') 
                {
                    $arr = M\Reg::checkpur($value);
                }
                else
                {
                    $error[] = $value;
                }
            }
        }
        $zong = count($eet) - count($error);
        unlink($excel);
        parent::msg('导入完毕','/manage/purchase/new?suc='. $zong);
        // echo "<script>alert('导入完毕');location.href='/manage/purchase/new?suc=" . $zong . "'</script>";
    }
    /**
     * 供应信息审核
     * @return [type] [description]
     */
    
    public function auditorpassAction($id, $pages = 1) 
    {
        $this->session->sessionREFERER = $_SERVER['HTTP_REFERER'];
        
        if (!$id) 
        {
            $this->response->redirect('purchase/index')->sendHeaders();
        }
        $purchase = M\Purchase::findFirstByid($id);
        
        if (!$purchase || $purchase->state != 0) 
        {
            parent::msg('操作失败','/manage/purchase/index');
            //die("<script>alert('操作失败');location.href='/manage/purchase/index'</script>");
        }
        $this->view->pages = $pages;
        $this->view->purchase = $purchase;
        $this->view->goods_unit = Purchase::$_goods_unit;
        $this->view->curCate = lCategory::ldData($purchase->category);
        $this->view->curAreas = lAreas::ldData($purchase->areas);
    }
    /**
     * 采购审核通过
     * @return [type] [description]
     */
    
    public function auditorpassproAction() 
    {
        $sid = $this->request->getPost("id", 'int', 0);
        $pages = $this->request->getPost("pages", 'int', 1);
        
        if (!$sid) 
        {
            $this->response->redirect('purchase/index')->sendHeaders();
        }
        $Purchase = M\Purchase::findFirstByid($sid);
        
        if (!$Purchase || $Purchase->state != 0) 
        {
            $this->response->redirect('purchase/index')->sendHeaders();
        }
        $Purchase->state = !$Purchase->state;
        
        if (!$Purchase->save()) 
        {
        }
        Func::adminlog("采购商品审核通过：{$Purchase->title}",$this->session->adminuser['id']);
        $re = $this->session->sessionREFERER;

        $this->showMessage($re);
        $this->response->redirect('purchase/index?p=' . $pages)->sendHeaders();
    }
    /**
     * 供应审核未通过
     * @return [type] [description]
     */
    
    public function fallAction() 
    {
        $sid = $this->request->getPost('id', 'int', 0);
        $reject = $this->request->getPost('reject', 'string', '');
        $pages = $this->request->getPost("pages", 'int', 1);
        
        if (!$sid) 
        {
            $this->response->redirect('purchase/index')->sendHeaders();
        }
        $purchase = M\Purchase::findFirstByid($sid);
        
        if (!$purchase || $purchase->state != 0) 
        {
            $this->response->redirect('purchase/index')->sendHeaders();
        }
        $purchase->state = 2;
        
        if (!$purchase->save()) 
        {
        }
        $PurchaseCheck = new M\PurchaseCheck();
        $PurchaseCheck->purchase_id = $sid;
        $PurchaseCheck->fail_reason = $reject;
        $PurchaseCheck->add_time = time();
        $PurchaseCheck->fail_id = 0;
        $PurchaseCheck->save();
        Func::adminlog("采购商品审核未通过：{$purchase->title}",$this->session->adminuser['id']);
        $this->response->redirect('purchase/index?p=' . $pages)->sendHeaders();
    }
    // public function downexcalAction(){
    //         $arrs=M\Reg::find("type=1")->toArray();
    //         include_once 'excel/Classes/PHPExcel.php';
    //         include_once 'excel/Classes/PHPExcel/IOFactory.php';
    //             // 创建一个处理对象实例
    //         $objExcel = new \PHPExcel();
    //         $objWriter = new \PHPExcel_Writer_Excel5($objExcel);    // 用于其他版本格式
    //         $objExcel->setActiveSheetIndex(0);
    //         $objActSheet = $objExcel->getActiveSheet();
    //         //设置当前活动sheet的名称
    //         $objActSheet->setTitle('exce导出');
    //         foreach($arrs as $key=>$val){
    //             $array[$key]['title']        = empty($val['title']) ? '-' : $val['title'];
    //             $array[$key]['max_category'] = empty($val['max_category']) ?'-' :$val['max_category'];
    //             $array[$key]['min_category'] = empty($val['min_category']) ? '-' :$val['min_category'];
    //             $array[$key]['quantity']     = empty($val['quantity']) ? '-' :$val['quantity'];
    //             $array[$key]['goods_unit']   = empty($val['goods_unit']) ? '-' :$val['goods_unit'];
    //             $array[$key]['province']     = empty($val['province']) ? '-' :$val['province'];
    //             $array[$key]['city']         = empty($val['city']) ? '-' :$val['city'];
    //             $array[$key]['town']         = empty($val['town']) ? '-' :$val['town'];
    //             $array[$key]['spec']         = empty($val['spec']) ? '-' :$val['spec'];
    //             $array[$key]['address']      = empty($val['address']) ? '-' :$val['address'];
    //             $array[$key]['endtime']      = empty($val['endtime']) ? '-' : $val['endtime'];
    //             $array[$key]['purname']      = empty($val['purname']) ? '-' : $val['purname'];
    //             $array[$key]['purmoblie']    = empty($val['purmoblie']) ? '-' : $val['purmoblie'];
    //        }
    //         $objExcel->getActiveSheet()->setCellValue('A1', "商品名称");//设置列的值
    //         $objExcel->getActiveSheet()->setCellValue('B1', "一级分类");//设置列的值
    //         $objExcel->getActiveSheet()->setCellValue('C1', "二级分类");//设置列的值
    //         $objExcel->getActiveSheet()->setCellValue('D1', "采购数量");//设置列的值
    //         $objExcel->getActiveSheet()->setCellValue('E1', "采购单位");//设置列的值
    //         $objExcel->getActiveSheet()->setCellValue('F1', "规则要求");//设置列的值
    //         $objExcel->getActiveSheet()->setCellValue('G1', "采购截止时间");//设置列的值
    //         $objExcel->getActiveSheet()->setCellValue('H1', "采购人性名");//设置列的值
    //         $objExcel->getActiveSheet()->setCellValue('I1', "联系电话");//设置列的值
    //         $objExcel->getActiveSheet()->setCellValue('J1', "省");//设置列的值
    //         $objExcel->getActiveSheet()->setCellValue('K1', "市");//设置列的值
    //         $objExcel->getActiveSheet()->setCellValue('L1', "区/县");//设置列的值
    //         $objExcel->getActiveSheet()->setCellValue('M1', "详细地址");//设置列的值
    //         $objExcel->getActiveSheet()->getColumnDimension("A")->setWidth(15);
    //         $objExcel->getActiveSheet()->getColumnDimension("B")->setWidth(15);
    //         $objExcel->getActiveSheet()->getColumnDimension("C")->setWidth(15);
    //         $objExcel->getActiveSheet()->getColumnDimension("D")->setWidth(15);
    //         $objExcel->getActiveSheet()->getColumnDimension("E")->setWidth(15);
    //         $objExcel->getActiveSheet()->getColumnDimension("F")->setWidth(15);
    //         $objExcel->getActiveSheet()->getColumnDimension("G")->setWidth(15);
    //         $objExcel->getActiveSheet()->getColumnDimension("H")->setWidth(15);
    //         $objExcel->getActiveSheet()->getColumnDimension("I")->setWidth(15);
    //         $objExcel->getActiveSheet()->getColumnDimension("J")->setWidth(15);
    //         $objExcel->getActiveSheet()->getColumnDimension("K")->setWidth(15);
    //         $objExcel->getActiveSheet()->getColumnDimension("L")->setWidth(15);
    //         $objExcel->getActiveSheet()->getColumnDimension("M")->setWidth(15);
    //         $i = 2;
    //         foreach($array as $key=>$val){
    //             $objExcel->getActiveSheet()->setCellValue("A{$i}", $val['title']);
    //             $objExcel->getActiveSheet()->setCellValue("B{$i}", $val['max_category']);
    //             $objExcel->getActiveSheet()->setCellValue("C{$i}", $val['min_category']);
    //             $objExcel->getActiveSheet()->setCellValue("D{$i}", $val['quantity']);
    //             $objExcel->getActiveSheet()->setCellValue("E{$i}", $val['goods_unit']);
    //             $objExcel->getActiveSheet()->setCellValue("F{$i}", $val['spec']);
    //             $objExcel->getActiveSheet()->setCellValue("G{$i}", $val['endtime']);
    //             $objExcel->getActiveSheet()->setCellValue("H{$i}", $val['purname']);
    //             $objExcel->getActiveSheet()->setCellValue("I{$i}", $val['purmoblie']);
    //             $objExcel->getActiveSheet()->setCellValue("J{$i}", $val['province']);
    //             $objExcel->getActiveSheet()->setCellValue("K{$i}", $val['city']);
    //             $objExcel->getActiveSheet()->setCellValue("L{$i}", $val['town']);
    //             $objExcel->getActiveSheet()->setCellValue("M{$i}", $val['address']);
    //             $i++;
    //         }
    //         $outputFileName = "output.xls";
    //         header("Content-Type:application/octet-stream;charset=utf-8");
    //         header('Content-Disposition: attachment; filename=' . $outputFileName);
    //         $objWriter->save('php://output');
    //         exit;
    // }
    /**
     * 下载采购信息
     * @return [type] [description]
     */
    public function downexcalAction() 
    {
        include_once 'excel/Classes/PHPExcel.php';
        include_once 'excel/Classes/PHPExcel/IOFactory.php';
        $arrs = M\Reg::find("type=1")->toArray();
        
        foreach ($arrs as $key => $val) 
        {
            $array[$key]['title'] = empty($val['title']) ? '-' : $val['title'];
            $array[$key]['max_category'] = empty($val['max_category']) ? '-' : $val['max_category'];
            $array[$key]['min_category'] = empty($val['min_category']) ? '-' : $val['min_category'];
            $array[$key]['quantity'] = empty($val['quantity']) ? '-' : $val['quantity'];
            $array[$key]['goods_unit'] = empty($val['goods_unit']) ? '-' : $val['goods_unit'];
            $array[$key]['province'] = empty($val['province']) ? '-' : $val['province'];
            $array[$key]['city'] = empty($val['city']) ? '-' : $val['city'];
            $array[$key]['town'] = empty($val['town']) ? '-' : $val['town'];
            $array[$key]['spec'] = empty($val['spec']) ? '-' : $val['spec'];
            $array[$key]['address'] = empty($val['address']) ? '-' : $val['address'];
            $array[$key]['endtime'] = empty($val['endtime']) ? '-' : $val['endtime'];
            $array[$key]['purname'] = empty($val['purname']) ? '-' : $val['purname'];
            $array[$key]['purmoblie'] = empty($val['purmoblie']) ? '-' : $val['purmoblie'];
        }
        //创建一个读Excel模版的对象
        $objReader = \PHPExcel_IOFactory::createReader('Excel5');
        $objPHPExcel = $objReader->load("excel/sell.xls");
        // //获取当前活动的表
        $objPHPExcel->setActiveSheetIndex(0);
        $baseRow = 5; //数据从N-1行开始往下输出 这里是避免头信息被覆盖
        
        foreach ($arrs as $r => $dataRow) 
        {
            $row = $baseRow + $r;
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $row, $dataRow['title']); //学员编号
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $row, $dataRow['max_category']); //学员编号
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $row, $dataRow['min_category']); //学员编号
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $row, $dataRow['quantity']); //学员编号
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $row, $dataRow['goods_unit']); //学员编号
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $row, $dataRow['spec']); //学员编号
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $row, $dataRow['endtime']); //学员编号
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $row, $dataRow['purname']); //学员编号
            $objPHPExcel->getActiveSheet()->setCellValue('I' . $row, $dataRow['purmoblie']); //学员编号
            $objPHPExcel->getActiveSheet()->setCellValue('J' . $row, $dataRow['province']); //学员编号
            $objPHPExcel->getActiveSheet()->setCellValue('K' . $row, $dataRow['city']); //学员编号
            $objPHPExcel->getActiveSheet()->setCellValue('L' . $row, $dataRow['town']); //学员编号
            $objPHPExcel->getActiveSheet()->setCellValue('M' . $row, $dataRow['address']); //真实姓名
            
        }
        $filename = time();
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="上传失败信息表.xls"'); //"'.$filename.'.xls"
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); //在内存中准备一个excel2003文件
        $objWriter->save('php://output');
        die;
    }
    
    public function downloadAction() 
    {
        $myfile = "excel/sell.xls";
        $len = filesize($myfile);
        ob_end_clean();
        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: public');
        header('Content-Description: File Transfer');
        header('Content-type: application/octet-stream');
        header('Content-Disposition: attachment; filename="卖的贵采购信息表.xls"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . $len);
        readfile($myfile);
    }

        /**
     * 推荐
     * @param  [type]  $id   供应Id
     * @param  integer $page [description]
     * @return [type]        [description]
     */
    public function recommandAction(){
        $id = $this->request->get("id", 'int', 0);
        $type = $this->request->get("type", 'int', 0);
        
        $recommand_purchase = M\RecommandPurchase::findFirstBypurchase_id($id);
//echo $recommand_sell->location_category;die;
        if($recommand_purchase){
            $recommand_purchase->last_update_time = time();
            if($type == 1){
                $recommand_purchase->location_home = $recommand_purchase->location_home==1 ? 0 : 1;
            }elseif($type==2){
                $recommand_purchase->location_category = $recommand_purchase->location_category==1 ? 0 : 1;
            }
            $recommand_purchase->last_update_time = time();
        }else{

            $recommand_purchase = new M\RecommandPurchase();
            $recommand_purchase->purchase_id = $id;
            $recommand_purchase->add_time = time();
            $recommand_purchase->last_update_time = time();
            if($type == 1){
                $recommand_purchase->location_home=1;
                $recommand_purchase->location_category=0;
            }elseif($type==2){
                $recommand_purchase->location_category=1;
                $recommand_purchase->location_home=0;
            }
        }

        if($recommand_purchase->save()){
            $msg = '操作成功';
        }else{
            $msg = '操作失败';
        }
        die(json_encode($msg));
    }
}
