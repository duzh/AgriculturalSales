<?php
/**
 * 供应商店铺查看
 */
namespace Mdg\Store\Controllers;
use Mdg\Models\Users as Users;
use Mdg\Models\UsersExt as UsersExt;
use Mdg\Models\ShopGrade as ShopGrade;
use Mdg\Models\Shop as Shop;
use Mdg\Models\ShopComments as ShopComments;
use Mdg\Models\ShopCredit as ShopCredit;
use Mdg\Models\Sell as Sell;
use Mdg\Models\AreasFull as AreasFull;
use Mdg\Models\ShopColumns as ShopColumns;
use Mdg\Models\Category as Category;
use Mdg\Models\Image as Image;
use Lib\Func as Func;
use Lib\Pages as Pages;
use Lib\Arrays as Arrays;

class ShoplookController extends ControllerShop
{
    /**
     * 店铺首页
     * [goodslistAction description]
     * @return [type] [description]
     */
    
    public function indexAction() 
    {
        $c = 'shoplook';
        $shop = $this->session->mdgshop;
        $shop_id = $shop['shop_id'];
        $user_id = $shop['user_id'];
        $business_type = $shop['business_type'];

        if ($business_type != 1) 
        {
            echo "<script>alert('此店铺不存在！');location.href='/member'</script>";
            die;
        }
        $shopcredit = ShopCredit::findFirstByshop_id($shop_id);
        $shopgoodslist = Sell::shoplookgoodslist($c, $user_id);
        $this->view->shopcredit = $shopcredit;
        $this->view->shopgoodslist = $shopgoodslist;
        
        $this->view->title = '店铺管理-店铺首页-丰收汇-高价值农产品交易服务商';
        $this->view->keywords = '丰收汇,农业,农产品,农副产品,农产品批发,农产品电商,农产品现货,农产品交易,农产品加工,农产品价格';
        $this->view->descript = '丰收汇是依托中国农业互联网高科技综合服务商云农场的丰富资源和先进技术而建立的中国最专业的网上农产品服务平台，
        为中国农村专业合作社、中小农商企业等提供农产品信息发布、行情资讯、网上商城、金融服务、定向对接、安全模型、订单种植等专业化服务，
        旨在成为全球高价值农产品交易服务商。';

    }
    /**
     * 所有商品
     * [goodslistAction description]
     * @return [type] [description]
     */
    
    public function goodslistAction($id = 0) 
    {
        $p = $this->request->get('p', 'int', 1);
        $shop = $this->session->mdgshop;
        $shop_id = $shop['shop_id'];
        $user_id = $shop['user_id'];
        $business_type = $shop['business_type'];
        
        if ($business_type != 1) 
        {
            echo "<script>alert('此店铺不存在！');location.href='/member'</script>";
            die;
        }
        if($p&&$p>0){
           $p=$p;
        }else{
            $p=1;
        }
        $goodslist = Sell::shoplookgoodslist($c, $user_id, $p, $id);
        $this->view->goodslist = $goodslist;
        $this->view->pages = $pages;
        $this->view->category_id = $id;
        $this->view->user_id = $user_id;
        $this->view->title = '店铺管理-所有商品-丰收汇-高价值农产品交易服务商';
        $this->view->keywords = '丰收汇,农业,农产品,农副产品,农产品批发,农产品电商,农产品现货,农产品交易,农产品加工,农产品价格';
        $this->view->descript = '丰收汇是依托中国农业互联网高科技综合服务商云农场的丰富资源和先进技术而建立的中国最专业的网上农产品服务平台，
        为中国农村专业合作社、中小农商企业等提供农产品信息发布、行情资讯、网上商城、金融服务、定向对接、安全模型、订单种植等专业化服务，
        旨在成为全球高价值农产品交易服务商。';
    }
    /**
     * 店铺介绍
     * [goodslistAction description]
     * @return [type] [description]
     */
    
    public function shopintroductionAction() 
    {
        $p = $this->request->get('p', 'int', 1);
        $shop = $this->session->mdgshop;
        $shop_id = $shop['shop_id'];
        $user_id = $shop['user_id'];
        $business_type = $shop['business_type'];
        
        if ($business_type != 1) 
        {
            echo "<script>alert('此店铺不存在！');location.href='/member'</script>";
            die;
        }
        $shopcredit = ShopCredit::findFirstByshop_id($shop_id);
        $this->view->category_id = $id;
        $this->view->user_id = $user_id;
        $this->view->shopcredit = $shopcredit;
        $this->view->title = '店铺管理-店铺介绍-丰收汇-高价值农产品交易服务商';
        $this->view->keywords = '丰收汇,农业,农产品,农副产品,农产品批发,农产品电商,农产品现货,农产品交易,农产品加工,农产品价格';
        $this->view->descript = '丰收汇是依托中国农业互联网高科技综合服务商云农场的丰富资源和先进技术而建立的中国最专业的网上农产品服务平台，
        为中国农村专业合作社、中小农商企业等提供农产品信息发布、行情资讯、网上商城、金融服务、定向对接、安全模型、订单种植等专业化服务，
        旨在成为全球高价值农产品交易服务商。';
    }
    /**
     * 店铺栏目
     * [goodslistAction description]
     * @return [type] [description]
     */
    
    public function columnsAction($id = 0) 
    {

        $cid = $this->request->Get('cid','int',0);
        $shop = $this->session->mdgshop;
        $shop_id = $shop['shop_id'];
        $user_id = $shop['user_id'];
        $business_type = $shop['business_type'];
        
        if ($business_type != 1) 
        {
            echo "<script>alert('此店铺不存在！');location.href='/member'</script>";
            die;
        }



        if($cid){
            $columns = ShopColumns::find(" shop_id=$shop_id AND id=$cid");
        }else{
            $columns = ShopColumns::find("shop_id=$shop_id AND id=$id");
        }

        if (!$columns->toArray() || !is_numeric($id)) 
        {
            echo "<script>alert('数据错误！');location.href='/store/shoplook'</script>";
            exit;
        }
        
        $cate = Category::toTree(ShopColumns::find(" shop_id=$shop_id AND is_delete = 0 and col_pid=$id or id =$id  ")->toArray() , 'id', 'col_pid', 'children');
        
        if($cate[$id]["children"]){
            $is_parent = $cate[$id]["col_name"];
        }
        $this->view->columns_id = $cid;
        $this->view->is_parent = $is_parent;
        $this->view->id = $id;
        $this->view->cate = $cate;
        $this->view->columns = $columns;
 
        $this->view->title = '店铺管理-店铺栏目-丰收汇-高价值农产品交易服务商';
        $this->view->keywords = '丰收汇,农业,农产品,农副产品,农产品批发,农产品电商,农产品现货,农产品交易,农产品加工,农产品价格';
        $this->view->descript = '丰收汇是依托中国农业互联网高科技综合服务商云农场的丰富资源和先进技术而建立的中国最专业的网上农产品服务平台，
        为中国农村专业合作社、中小农商企业等提供农产品信息发布、行情资讯、网上商城、金融服务、定向对接、安全模型、订单种植等专业化服务，
        旨在成为全球高价值农产品交易服务商。';
    }
    /**
     * 服务评价
     * [goodslistAction description]
     * @return [type] [description]
     */
    
    public function serviceevaluationAction() 
    {
        $p = $this->request->get('p', 'int', 1);
        $shop = $this->session->mdgshop;
        $shop_id = $shop['shop_id'];
        $user_id = $shop['user_id'];
        $business_type = $shop['business_type'];
        
        if ($business_type != 1) 
        {
            echo "<script>alert('此店铺不存在！');location.href='/member'</script>";
            die;
        }
        $this->view->category_id = $id;
        $this->view->user_id = $user_id;
        $this->view->title = '店铺管理-服务评价-丰收汇-高价值农产品交易服务商';
        $this->view->keywords = '丰收汇,农业,农产品,农副产品,农产品批发,农产品电商,农产品现货,农产品交易,农产品加工,农产品价格';
        $this->view->descript = '丰收汇是依托中国农业互联网高科技综合服务商云农场的丰富资源和先进技术而建立的中国最专业的网上农产品服务平台，
        为中国农村专业合作社、中小农商企业等提供农产品信息发布、行情资讯、网上商城、金融服务、定向对接、安全模型、订单种植等专业化服务，
        旨在成为全球高价值农产品交易服务商。';
        
    }
}
