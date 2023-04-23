<?php
namespace Mdg\Store\Controllers;
use Phalcon\Mvc\Controller;
use Mdg\Models\UsersNav as Nav;
use Mdg\Models\Article as Article;
use Mdg\Models\ArticleCategory as ArticleCategory;
use Mdg\Models\Category as Category;
use Lib\Func as Func;
use Lib\Crypt as crypt;
use Mdg\Models\Users as Users;
use Mdg\Models\UsersExt as UsersExt;
//
use Mdg\Models\Shop as Shop;
use Mdg\Models\AreasFull as AreasFull;
use Mdg\Models\Image as Image;
use Mdg\Models\ShopGrade as ShopGrade;
use Mdg\Models\ShopColumns as ShopColumns;
use Mdg\Models\ShopComments as ShopComments;
use Mdg\Models\Sell as Sell;
use Mdg\Models\Purchase as Purchase;
use Lib\Arrays as Arrays;

class ControllerShop extends Controller
{
    
    public function initialize() 
    {
        
        if(isset($_COOKIE["ync_auth"])){
             $user=crypt::authcode($_COOKIE["ync_auth"],$operation = 'DECODE');
             $users=explode("|",$user);
   
             $mobile=$users[1];
             $user = Users::checkLogin($mobile,$password='123456');
            
             if($user){
                $userext=UsersExt::getuserext($user->id);
                $this->session->user = array('mobile'=>$user->username,'id'=>$user->id,'name'=>$userext->name);
             }
        }
        $url=$_SERVER['HTTP_HOST'];

        $url=explode('.',$url);
        //$url = isset($_GET["shop_link"]) ? $_GET["shop_link"] : $this->session->url;
       
        $this->session->url = $url[0];
        $shop = Shop::findFirst("shop_link='{$this->session->url}' AND shop_status = 1 ");
        
        if (!$shop) 
        {
            echo  "<script>location.href='http://www.5fengshou.com/'</script>"; die;
        }
        //print_r($shop);die;
        $this->session->mdgshop = $shop->toArray();
        //得到店铺的信息
        $shop_id = $shop->shop_id;
        $user_id = $shop->user_id;
        $areas = AreasFull::getFamily($shop->village_id);
        //宣传图
        $image = Image::publicize($shop_id);
        
        //店铺 评价
        $shopcomments = ShopComments::getShopComments($shop_id, 1);
        //获取店铺 评分星数
        list($data , $score) = ShopComments::getShopService($shop_id);

        $shopgrade = ShopGrade::findFirstByshop_id($shop_id);
        
        //店铺栏目
        $condition[] = ' shop_id = ' . $shop_id . ' and is_delete=0 and col_pid=0';
        $orderby = ' order by last_update_time desc';
        $where = implode(' AND ', $condition);
        $shopcolumns = ShopColumns::getList($where, 1, $orderby);
        
        $cate = array();
        $purchasecatehorys = array();
        
        if ($shop->business_type == 2) 
        {
            //采购商产品分类
            $time = time();
            $pwhere = "uid='{$user_id}' and endtime>'{$time}' and is_del=0 and state=1 and is_show=1";
            $purchasegoods = Purchase::getlist($user_id, $c = 'index', $pwhere);
            
            if ($purchasegoods["items"]) 
            {
                $purchasecatehorys = Category::shopcategory($purchasegoods["items"]);
            }
        }

        if ($shop->business_type == 1) 
        {
            //供应商产品分类
            $goodscategroy = Sell::lookgoodslist($user_id);
            
            if ($goodscategroy["items"]) 
            {
                $cate = Category::shopcategory($goodscategroy["items"]);
            }
        }

        $url_id=explode("/",$_GET["_url"]);
        $this->view->cate = $cate;
        $this->view->purchasecate = $purchasecatehorys;
        $this->view->title = '';
        $this->view->keywords = '';
        $this->view->descript = '';
        $this->view->shop = $shop;
        $this->view->areas = $areas;
        $this->view->image = $image;
        $this->view->shopgrade = $data;
        $this->view->score = $score;
        $this->view->shopcolumns = $shopcolumns;
        $this->view->action = $this->dispatcher->getActionName();
        $this->view->url_id = isset($url_id[4]) ? $url_id[4] : '';
        $this->view->shopcomments = $shopcomments;
    }
    
    public function getUserID() 
    {
        $user = $this->session->user;
        return $user['id'];
    }
    
    public function getUserName() 
    {
        $user = $this->session->user;
        return $user['mobile'];
    }
}
