<?php

namespace Mdg\Frontend\Controllers;
use Mdg\Models as M;
use Lib\Pages as Pages;
use Lib\Arrays as Arrays;
class FarmlistController extends ControllerBase
{
    /**
     * 可信农场列表
     * @return [type]           [description]
     */
    public function indexAction() {
    
        $page    = $this->dispatcher->getParam('p','int',1);
        $page = intval($page) > 0 ? intval($page) : 1;
        $cate    = $this->dispatcher->getParam('c', 'int', 0);
        $area    = $this->dispatcher->getParam('a', 'int', 0);
        $maxcate = $this->dispatcher->getParam('mc', 'int', 0);
        $f       = $this->dispatcher->getParam('f', 'string', '');
        $url['mc'] = $maxcate;
        $url['f'] = $f;
        $url['c'] = $cate;
        $url['p'] = $page;
        $url['a'] = $area;
        $data=array();
        $result=array();
        $info   = M\AreasFull::findFirstByid($area);
        $where =" ui.credit_type=8 and ui.status=1 and cfg.logo_pic!='' and cfg.status=1 ";

        if($cate) {
             $categroy=M\UserFarmCrops::find(" category_id ={$cate} ")->toArray();
             $credit_id=Arrays::getCols($categroy,'credit_id',',');
             $where.=" and ui.credit_id in ($credit_id) ";
        }
       
        if($info) {  
            if($info->level==1){
                 $where.= " and uf.province_id=$area";
            }else{
                 $where.= " and uf.city_id=$area";
            }
        }
        
        $page_size = 10;
        $offst = intval(($page - 1) * $page_size);
        $sql=" select  count(*) as count from user_info as ui left join user_farm as uf on ui.credit_id=uf.credit_id left join credible_farm_info as cfg on cfg.user_id=ui.user_id where {$where} ";
       
        $count=$this->db->fetchOne($sql,2);
        
        $total=$count["count"] ? $count["count"] : 0;

        $fetchsql="select  * from user_info as ui left join user_farm as uf on ui.credit_id=uf.credit_id left join credible_farm_info as cfg on cfg.user_id=ui.user_id  where  {$where} ORDER BY ui.add_time desc limit {$offst} , {$page_size} ";
     
        $data=$this->db->fetchAll($fetchsql,2);
        
        //主营产品
        foreach($data as $k=>$v){
         
                $farmGodosIds   = M\UserFarmCrops::selectByuserFarm($v["user_id"],$v["credit_id"]);
                if(!empty($farmGodosIds)){
                    $data[$k]['goods_name']=Arrays::getCols($farmGodosIds,'category_name',' ');
                }else{
                    $data[$k]['goods_name']='-';
                }
                $data[$k]['farm_address'] =  $v['province_name'].$v['city_name'].$v['district_name'].$v['town_name'].$v["village_name"];
                $user_farm = M\UserFarm::findFirst("credit_id={$v["credit_id"]} ");
                if($user_farm){
                    $data[$k]["farmname"]=$user_farm->farm_name;
                }else{
                    $data[$k]['farmname']='';
                }
        }
        $pages['total_pages'] = ceil($total / $page_size);
        $pages['current'] = $page;
        $pages['total'] = $total;
        $pages = new Pages($pages);
        $newpages = $pages->show(11);
        $newpages = str_replace(array('下一页', '上一页'), '', $newpages);     
        
        $pages = $pages->show(10);
        


        $fetchsqlall="select  * from user_info as ui left join user_farm as uf on ui.credit_id=uf.credit_id left join credible_farm_info as cfg on cfg.user_id=ui.user_id where ui.credit_type=8 and ui.status=1 and cfg.logo_pic!='' and cfg.status=1  ";
     
        $resultall=$this->db->fetchAll($fetchsqlall,2);
   
        shuffle ($resultall); 
        $result = array_slice($resultall,0,5); 
        foreach($result as $k=>$v){
         
            $farmGodosIds   = M\UserFarmCrops::selectByuserFarm($v["user_id"],$v["credit_id"]);
            if(!empty($farmGodosIds)){
                $result[$k]['goods_name']=Arrays::getCols($farmGodosIds,'category_name',' ');
            }else{
                $result[$k]['goods_name']='-';
            }
            $result[$k]['farm_address'] =  $v['province_name'].$v['city_name'].$v['district_name'].$v['town_name'].$v["village_name"];
            $user_farm = M\UserFarm::findFirst("credit_id={$v["credit_id"]} ");
            if($user_farm){
                $result[$k]["farmname"]=$user_farm->farm_name;
            }else{
                $result[$k]['farmname']='';
            }
        }
        $this->view->total = ceil($total/$page_size);
        $this->view->result = $result;
        $this->view->cate = $cate;
        $this->view->area = $area;
        $this->view->data = $data;
        $this->view->pages = $pages;
        $this->view->url=$url;
        $this->view->p=$page;
        $this->view->newpages = $newpages;
        $this->view->title = '可信农场列表';
        $this->view->controller = 'farmlist';
        $this->view->navs_title = '可信农场';
    }

    /**
     * 获取农场主地址
     * @param integer $id [description]
     */
    public function GetFarmAddress($id=0){
        $cond[] = "id={$id}";
        $cond['columns'] = "credit_id";
        $users = M\Users::findFirst($cond);
        if($users&&$users->credit_id){
            $user_farm = M\UserFarm::findFirst(" user_id={$id} and credit_id={$users->credit_id}");
            if($user_farm->province_name){
                return $user_farm->province_name.$user_farm->city_name.$user_farm->district_name.$user_farm->town_name.$user_farm->village_name;
            }else{
                return '-';
            }
        }else{
            return '-';
        }
    }

}
