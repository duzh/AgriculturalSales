<?php

namespace Mdg\Manage\Controllers;
use Mdg\Models\AdminNav as nav;
use Mdg\Models\Admin as Admin;
use Lib\Func as func;
use Lib\Arrays as Arrays;
use Mdg\Models as M;
class IndexController extends ControllerMember
{
    /**
     * 后台管理首页
     * @return [type] [description]
     */
     public function indexAction()
    {
        $adminuser = $this->session->adminuser;
       
        $navs=$adminuser["permission"];
        $navs=Arrays::toTree($navs,'id','pid','items');
        $row=array();
        foreach ($navs as $key => $value) {

             if(!empty($value["items"])){
                 $row[$key]["id"]=$value["controller"];
                 
                 $row[$key]['menu'][0]["text"]=$value['desc'];
                 $res=array();
                 foreach ($value["items"] as $k => $v) { 
                    if($v["deep"]==2&&$v['is_public']==0){
                         $res[$k]["id"]=$v['id']; 
                         $res[$k]["text"]=$v['desc'];   
                         $res[$k]["href"]='/manage/'.$v['controller']."/".$v['action'];  
                    }
                 }
                 $res=array_values($res);
                 $menu[$key]["text"]=$value['desc'];
                 $menu[$key]["items"]=$res;
                 $menu[$key]["collapsed"]=true;


                 $row[$key]["menu"][0]["items"]=$res;
             }
            
      
             if($value["deep"]==1||$value["deep"]==2){
                     $nav[$key]["title"]=$value["desc"];
                     $url='/manage/'.$value["controller"].'/'.$value["action"];
                     $nav[$key]["url"]  =$url;
                     if($value["pid"]==0){
                      $nav[$key]["icon"]="icon-sys";
                     }else{
                      $nav[$key]["icon"]="icon-nav";
                     }
                     $nav[$key]["params"]=$value["params"];
                     $nav[$key]["controller"]=$value["controller"];
                     $nav[$key]["action"]=$value["action"];
                     $nav[$key]["deeps"]=$value["deep"];
                     $nav[$key]["is_show"]=1;
                     $nav[$key]["id"]=$value["id"];
                     $nav[$key]["pid"]=$value["pid"];
             }
        }
        $row=array_values($row);
        $menu=array_values($menu);
        $arrs["id"]="menu";
        
        $arrs["menu"]=$menu;

        $row=json_encode($arrs);
        //var_dump($row);die;
        // print_r($row);die;
       // print_r($row);die;
        // foreach ($nav as $k => $value) {
        //         if(!$value['pid']) continue;    
        //         $cur=Admin::checkRole($this->getRoleID(), $value['controller'], $value['action']);
        //         // if($cur=="yes"){
        //         //    unset($nav[$k]);
        //         // }
        // }
        $nav=Arrays::sortByCol($nav,'params');
        $nav = func::totree($nav,'id','pid','menus');
        
        foreach ($nav as $key => $val) {
           $nav[$key]["menus"]=Arrays::sortByCol($nav[$key]["menus"],'params');
        }
      
        $this->view->menu = json_encode(array('menus'=>$nav));
        $this->view->title = '';
        $this->view->row=$row;
    }

    public function infoAction() {
        $this->view->username = $this->getUsername();
    }

}

