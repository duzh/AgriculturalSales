<?php
namespace Mdg\Store\Controllers;
use Mdg\Models\Users as Users;
use Mdg\Models\Shop as Shop;

class IndexController extends ControllerShop
{
    
    public function indexAction() 
    {
      
        //$user_id = $this->getUserID();
        //$shop = Shop::getList($user_id);
        $shop = $this->session->mdgshop;
        if ($shop['business_type'] == 1) 
        {
            $this->response->redirect("shoplook/index");
        }
        elseif ($shop['business_type'] == 2) 
        {
            $this->response->redirect("purchaseshop/index");
        }
        else
        {
            echo "<script>alert('此店铺类型错误！');location.href='/member'</script>";
            die;
        }
    }
}
