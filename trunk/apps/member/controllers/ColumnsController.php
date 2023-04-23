<?php
/**
 * 店铺栏目管理
 */
namespace Mdg\Member\Controllers;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Mdg\Models\Shop as Shop;
use Mdg\Models\ShopColumns as ShopColumns;
use Mdg\Models\ShopCredit as ShopCredit;
use Mdg\Models\ShopCoods as ShopCoods;
use Mdg\Models as M;
use Lib\Func as Func;
use Lib\Path as Path;
use Lib\File as File;
use Lib\Pages as Pages;
use Lib\Category as lCategory;
use Lib\Areas as lAreas;
use Lib as L;

class ColumnsController extends ControllerMember
{

    /**
     * 我的供应
     */
    public function indexAction()
    {    
      

        $page = $this->request->get('p', 'int', 1);
        $page = intval($page)>0 ? intval($page) : 1;
        $user_id = $this->getUserID();
        $shop = $this->checkShopExist();
        $shop_id = $shop['shop_id'];
        
        if(!$shop){
            die("店铺不存在");
        }
        
        $params = "shop_id= '{$shop_id}' AND is_delete = '0' ";
        $orderby= 'order by  id desc ';
        $shopcolumns = ShopColumns::getlist($params,$page,$orderby);
       
        $this->view->http = $_SERVER['SERVER_NAME'];
        $this->view->shop = $shop;
        $this->view->data = $shopcolumns;
        $this->view->title = '店铺管理-栏目列表-丰收汇-高价值农产品交易服务商';
        $this->view->keywords = '丰收汇,农业,农产品,农副产品,农产品批发,农产品电商,农产品现货,农产品交易,农产品加工,农产品价格';
        $this->view->descript = '丰收汇是依托中国农业互联网高科技综合服务商云农场的丰富资源和先进技术而建立的中国最专业的网上农产品服务平台，
        为中国农村专业合作社、中小农商企业等提供农产品信息发布、行情资讯、网上商城、金融服务、定向对接、安全模型、订单种植等专业化服务，
        旨在成为全球高价值农产品交易服务商。';
    }
    /**
     * 栏目内容增加
     * @param integer $cid 栏目id
     */
    public function addAction ($cid=0) {
        
        if(!$cid) $this->response->redirect("columns/index")->sendHeaders();

        $shop = $this->checkShopExist();
        if(!$shop)  $this->response->redirect("columns/index")->sendHeaders();

        $shop_id = $shop['shop_id'];

        $data = ShopColumns::findFirst(" id = '{$cid}' AND shop_id = '{$shop_id}' AND is_delete = 0 ");
        
        // if(!$data) $this->response->redirect("columns/index")->sendHeaders();
        
        $this->view->data = $data;
        $this->view->title = '店铺管理-新增栏目-丰收汇-高价值农产品交易服务商';
        $this->view->keywords = '丰收汇,农业,农产品,农副产品,农产品批发,农产品电商,农产品现货,农产品交易,农产品加工,农产品价格';
        $this->view->descript = '丰收汇是依托中国农业互联网高科技综合服务商云农场的丰富资源和先进技术而建立的中国最专业的网上农产品服务平台，
        为中国农村专业合作社、中小农商企业等提供农产品信息发布、行情资讯、网上商城、金融服务、定向对接、安全模型、订单种植等专业化服务，
        旨在成为全球高价值农产品交易服务商。';
    }

    /**
     * 栏目内容保存
     * @return [type] [description]
     */
    public function savecolcommentAction () {

        if(!$this->request->getPost()) exit(1);
        $shop = $this->checkShopExist();
        $shop_id = $shop['shop_id'];
        $cid = $this->request->getPost('cid', 'int', 0);
        $col_comment = $this->request->getPost('col_comment');
        if(!$cid) exit('cid');
        $data = ShopColumns::findFirst(" id = '{$cid}' AND shop_id = '{$shop_id}'");
        $data->col_comment = $col_comment;
        if(!$data->save()) {

        }

        $this->response->redirect("columns/index")->sendHeaders();

    }
    /**
     * 新建栏目
     * @return [type] [description]
     */
    public function newAction(){
        $data = $this->checkShopExist();
        if(!$data) exit('not exist');

        $shopcolumns = ShopColumns::getAllColumns($data['shop_id']);
        
        $total = 0;
        $total = M\ShopColumns::count( " shop_id = '{$data['shop_id']}' AND col_pid = 0  AND is_delete = 0 ");

        $this->view->total = $total;
        $this->view->shopcolumns = $shopcolumns;
    }
    /**
     * 保存新建 栏目
     * @return [type] [description]
     */
    public function createAction(){
        
        if (!$this->request->isPost()) {
            $this->response->redirect("columns/index")->sendHeaders();
        }

        $data = $this->checkShopExist();
        $count = M\ShopColumns::count( " shop_id = '{$shop_id}' AND is_delete = 0 ");
        if($count > M\ShopColumns::MAXCOUNT ) {
            $this->response->redirect("columns/index")->sendHeaders();
        }

        $columns_name = L\Validator::replace_specialChar($this->request->getPost('columns_name', 'string', ''));
        $columns_pid = $this->request->getPost('columns_pid', 'int', 0);
        $ShopColumns = new M\ShopColumns();
        $ShopColumns->shop_id = $data['shop_id'];
        $ShopColumns->col_pid = $columns_pid;
        $ShopColumns->col_name = $columns_name;
        $ShopColumns->col_link = '';
        $ShopColumns->add_time = time();
        
        $ShopColumns->save();
        $this->response->redirect("columns/index")->sendHeaders();
    }
    /**
     * 编辑栏目
     * @param  integer $cid 栏目id
     * @return [type]       [description]
     */
    public function editAction ($cid=0) {
        $data = $this->checkShopExist();
        if(!$data)  $this->response->redirect("columns/index")->sendHeaders();
        //查询店铺信息
        $total = 0;
        
        $columns = M\ShopColumns::findFirst( " shop_id = '{$data['shop_id']}' AND id = '{$cid}' AND is_delete = 0");
        if(!$columns) $this->response->redirect("columns/index")->sendHeaders();

        $shopcolumns = ShopColumns::getAllColumns($data['shop_id'],  " id != {$cid} AND col_pid = 0 AND is_delete = 0 " );
        $total = M\ShopColumns::count( " shop_id = '{$data['shop_id']}' AND col_pid = 0 AND id != '{$cid}' AND is_delete = 0 ");

        $this->view->total = $total;
        $this->view->shopcolumns = $shopcolumns;
        $this->view->data = $columns;
    }

    /**
     * 保存编辑信息
     * @return [type] [description]
     */
    public function saveAction () {

        $columns_name = L\Validator::replace_specialChar($this->request->getPost('columns_name', 'string', ''));
        $columns_pid = $this->request->getPost('columns_pid', 'int', 0);

        $cid = $this->request->getPost('cid', 'int', 0);

        if(!$cid) $this->response->redirect("columns/index")->sendHeaders();
        $shop = $this->checkShopExist();

        if(!$shop) {$this->response->redirect("columns/index")->sendHeaders();}

        $shop_id = $shop['shop_id'];
        $data = M\ShopColumns::findFirst( " shop_id = '{$shop_id}' AND id = '{$cid}' AND is_delete = 0");
        if(!$data) $this->response->redirect("columns/index")->sendHeaders();
        $data->col_name = $columns_name;
        $data->col_pid = $columns_pid;
      
        $data->save();

        $this->response->redirect("columns/index")->sendHeaders();
    }
    /**
     * 删除栏目
     * @param  integer $cid 栏目id
     * @return [type]       [description]
     */
    public function removeAction ($cid=0) {
        
        if(!$cid) $this->response->redirect("columns/index")->sendHeaders();
        $data = $this->checkShopExist();

        $columnsLevel = M\ShopColumns::find( " shop_id = '{$data['shop_id']}' AND col_pid = '{$cid}' AND is_delete = 0 ")->toArray();

        if($columnsLevel) {
            echo "<script>alert('有下级栏目不能删除');location.href='/member/columns/index'</script>";
           exit;
        }

        $columns = M\ShopColumns::findFirst( " shop_id = '{$data['shop_id']}' AND id = '{$cid}' AND is_delete = 0 ");

        if(!$columns) $this->response->redirect("columns/index")->sendHeaders();

        $columns->is_delete = !$columns->is_delete;
        if(!$columns->save()){

        }
        $this->response->redirect("columns/index")->sendHeaders();
    }

    /**
     * 检测栏目名字是否重复 以及上限个数
     * @return [type] [description]
     */
    public function checkcolumnsnameAction () {

        $columns_name = trim($this->request->getPost('columns_name', 'string', ''));
        $t = L\Validator::replace_specialChar($this->request->get('type', 'string', ''));
        $cid = $this->request->get('cid', 'int', '0');
        $columns_pid = $this->request->get('columns_pid', 'int', 0);
        $where = '';
     
        if($cid) {
             $where = " AND id != '{$cid}' ";
        }
        if($columns_name=='店铺首页'||$columns_name=='所有产品'||$columns_name=='服务评价'){
             $msg = array('error' => '栏目名字重复');
             exit(json_encode($msg));
        }
        $count = 0 ;
        $shop = $this->checkShopExist(); #检测当前登录用户店铺
        $shop_id = $shop['shop_id'];
        
        $data = M\ShopColumns::count(" col_name = '{$columns_name}' AND shop_id = '{$shop_id}' {$where} AND is_delete = 0  ");
        if(!$t) {

            //$count = M\ShopColumns::count( " shop_id = '{$shop_id}' AND shop_id = '{$shop_id}' AND col_pid = 0 AND is_delete = 0 ");
        }

        $msg = array('ok' => '');
        if($data){
            $msg = array('error' => '栏目名字重复');
        }

        if($count >= M\ShopColumns::MAXCOUNT ) {
            $msg = array('error' => '栏目超限');
        }
        
        exit(json_encode($msg));
        

    }



}   