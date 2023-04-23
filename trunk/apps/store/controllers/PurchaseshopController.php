<?php
/**
 * 采购商店铺查看
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

use Mdg\Models\Purchase as Purchase;







class PurchaseshopController extends ControllerShop

{


/**
 * 店铺首页
 * [goodslistAction description]
 * @return [type] [description]
 */
    public function indexAction()

    {


        $c='index';

        $time=time();

        $shop = $this->session->mdgshop;

        $shop_id = $shop['shop_id'];

        $user_id = $shop['user_id'];

        $business_type=$shop['business_type'];

        if($business_type!=2){
            echo "<script>alert('此店铺不存在！');location.href='/member'</script>";die;
        }

        $time=time();

        $where = "uid='{$user_id}' and endtime>'{$time}' and is_del=0 and state=1 and is_show=1";

        $shopcredit = ShopCredit::findFirstByshop_id($shop_id);     

        $purchaselist = Purchase::getlist($user_id,$c,$where);
        //$purchaselist = Purchase::find(" uid='{$user_id}' and endtime>{$time} order by createtime desc limit 10")->toArray();

        $this->view->shopcredit = $shopcredit;
        $this->view->purchaselist = $purchaselist;
        $this->view->title = '店铺查看';

    }



/**
 * 所有商品
 * [goodslistAction description]
 * @return [type] [description]
 */
    public function goodslistAction($id=0){


        $p = $this->request->get('p', 'int', 1);
        
        if($p&&$p>0){
           $p=$p;
        }else{
            $p=1;
        }
        $shop = $this->session->mdgshop;

        $shop_id = $shop['shop_id'];

        $user_id = $shop['user_id'];

        $business_type=$shop['business_type'];

        if($business_type!=2){
            echo "<script>alert('此店铺不存在！');location.href='/member'</script>";die;
        }

        $time=time();

        if($id){
            $where = "uid='{$user_id}' and endtime>'{$time}' and is_del=0 and state=1 and is_show=1 and category='{$id}'";
        }else{
            $where = "uid='{$user_id}' and endtime>'{$time}' and is_del=0 and state=1 and is_show=1";
        }

        $purchaselist = Purchase::getlist($user_id,$c,$where,$p);
       
        $this->view->purchaselist = $purchaselist;
        $this->view->pages      = $pages;
        $this->view->category_id    = $id;
        $this->view->user_id      = $user_id;
        $this->view->title = '所有商品';

    }

/**
 * 店铺介绍
 * [goodslistAction description]
 * @return [type] [description]
 */
    public function shopintroductionAction(){


        $p = $this->request->get('p', 'int', 1);

        $shop = $this->session->mdgshop;

        $shop_id = $shop['shop_id'];

        $user_id = $shop['user_id'];

        $business_type=$shop['business_type'];

        if($business_type!=2){
            echo "<script>alert('此店铺不存在！');location.href='/member'</script>";die;
        }

        $shopcredit = ShopCredit::findFirstByshop_id($shop_id);

        $this->view->category_id    = $id;

        $this->view->user_id      = $user_id;

        $this->view->shopcredit   = $shopcredit;

        $this->view->title  = '店铺介绍';

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
        
        if ($business_type != 2) 
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
    
        $cate = Category::toTree(ShopColumns::find(" shop_id=$shop_id AND is_delete = 0 and col_pid=$id or id =$id ")->toArray() , 'id', 'col_pid', 'children');
        
        if($cate[$id]["children"]){
            $is_parent = $cate[$id]["col_name"];
        }
        
        $this->view->columns_id = $cid;
        $this->view->is_parent = $is_parent;
        $this->view->id = $id;
        $this->view->cate = $cate;
        $this->view->columns = $columns;
        $this->view->title = '店铺栏目';
    }
/**
 * 服务评价
 * [goodslistAction description]
 * @return [type] [description]
 */    

    public function serviceevaluationAction(){

        $p = $this->request->get('p', 'int', 1);

        $shop = $this->session->mdgshop;

        $shop_id = $shop['shop_id'];

        $user_id = $shop['user_id'];

        $business_type=$shop['business_type'];
        
        if($business_type!=2){
            echo "<script>alert('此店铺不存在！');location.href='/member'</script>";die;
        }

        $this->view->category_id    = $id;

        $this->view->user_id      = $user_id;

        $this->view->title  = '服务评价';
    }
}



