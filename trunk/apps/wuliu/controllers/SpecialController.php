<?php
namespace Mdg\Wuliu\Controllers;
use Lib\Member as Member;
use Phalcon\Mvc\Controller;
use Lib\Auth as Auth;
use Lib\Arrays as Arrays;
use Lib\Pages as Pages;
use Mdg\Models as M;
use Mdg\Models\Category as Category;
use Lib as L;
class  SpecialController extends ControllerBase
{
      
     public function indexAction()
    {

		
        $page = $this->request->get('p', 'int', 1);
        if($page==0){
            $page=1;
        }
        $state = $this->request->get('state', 'int', 0);
        $province = $this->request->get('province', 'int', 0);
        $city = $this->request->get('city', 'int', 0);
        $district = $this->request->get('district', 'int', 0);
        $endprovince = $this->request->get('endprovince', 'int', 0);
        $endcity = $this->request->get('endcity', 'int', 0);
        $enddistrict = $this->request->get('enddistrict', 'int', 0);
        $where[] = " status=1 ";
        $sarea='';
        $endarea='';
        $page_size = 20;
        $startarea=array();
        $endarea=array();
        if ($province) 
        {
            $where[] =   " start_pid={$province} ";
            $startarea[]=M\AreasFull::findFirstByid($province)->name;
        }
        if($city) 
        {
            $where[]= "  start_cid = {$city} ";
            $startarea[]=M\AreasFull::findFirstByid($city)->name;
        }
        if($district) 
        {
            $where[]= "  start_did = {$district} ";
            $startarea[]=M\AreasFull::findFirstByid($district)->name;
        }
        if($endprovince) 
        {
            $where[]= "  end_pid = {$endprovince}  ";
            $endarea[]=M\AreasFull::findFirstByid($endprovince)->name;
        }
        if($endcity) 
        {
            $where[]= "  end_cid = {$endcity}  ";
            $endarea[]=M\AreasFull::findFirstByid($endcity)->name;
        }
        if($enddistrict) 
        {
            $where[]= "  end_did = {$enddistrict}  ";
            $endarea[]=M\AreasFull::findFirstByid($enddistrict)->name;
        }
        if(!EMPTY($startarea)){
            foreach ($startarea as $key => $value) {
               $sarea.="'{$value}',";
            }
            $sarea=rtrim($sarea,',');
        }
        if(!EMPTY($endarea)){
            foreach ($endarea as $key => $value) {
               $earea.="'{$value}',";
            }
            $earea=rtrim($earea,',');
        }
      
        $where = implode(' and ', $where);
       
        $total = M\ScInfo::count($where);
       
        $offst = intval(($page - 1) * $page_size);
        $data = M\ScInfo::find($where . " ORDER BY add_time DESC limit {$offst} , {$page_size} ");
        //print_r($data->toArray());die;
        $sarea = str_replace("'","",$sarea);
        $pages['total_pages'] = ceil($total / $page_size);
        $pages['current'] = $page;
        $pages['total'] = $total;
        $pages = new Pages($pages);
        $pages = $pages->show(1);
        $this->view->current = $page;
        $this->view->data = $data;
        $this->view->pages = $pages;
        $this->view->total = $total;
        $this->view->start_areas=$sarea;
        $this->view->endAreas=$earea;
        $this->view->title = ''.$sarea.'专线信息-丰收汇';
        $this->view->keywords = ''.$sarea.'专线信息，丰收汇';
        $this->view->descript = '丰收汇-可靠农产品交易网，为你提供'.$sarea.'专线物流信息。';        
        $this->view->sorce = '专线物流';

    }
    public function lookAction($id){
    
        if(!$id){
            die("<script>alert('来源错误');location.href='/manage/special/index'</script>");
        }
        $data = M\ScInfo::findFirstBysc_id($id);
        $this->view->data = $data;
        $scext=M\ScExt::find("sc_id={$id}")->toArray();
        $scexts=Arrays::groupBy($scext,'type');
        
        foreach ($scexts as $key => $value) {
             if($key==0){
                $start["net_name"]=$scexts[0][0]["net_name"] ? $scexts[0][0]["net_name"] : '';
                $start["company_name"]=$scexts[0][0]["company_name"] ? $scexts[0][0]["company_name"] : '';
                $start["contact_man"]=$scexts[0][0]["contact_man"] ? $scexts[0][0]["contact_man"] : '';
                $start["fix_phone"]=$scexts[0][0]["fix_phone"] ? $scexts[0][0]["fix_phone"] : '';
                $start["mobile_phone"]=$scexts[0][0]["mobile_phone"] ? $scexts[0][0]["mobile_phone"] : '';
                $start["address"]=$scexts[0][0]["address"] ? $scexts[0][0]["address"] : '';
                $start["phone_number"]=$scexts[0][0]["phone_number"] ? $scexts[0][0]["phone_number"] : '';
                $start["qq"]=$scexts[0][0]["qq"] ? $scexts[0][0]["qq"] : '';
                $start["phone_img"]=$scexts[0][0]["phone_img"] ? $scexts[0][0]["phone_img"] : '';
                $start["sc_id"]=$scexts[0][0]["sc_id"] ? $scexts[0][0]["sc_id"] : '';
             }
             if($key==1){
                $end["net_name"]=$scexts[1][0]["net_name"] ? $scexts[1][0]["net_name"] : '';
                $end["company_name"]=$scexts[1][0]["company_name"] ? $scexts[1][0]["company_name"] : '';
                $end["contact_man"]=$scexts[1][0]["contact_man"] ? $scexts[1][0]["contact_man"] : '';
                $end["fix_phone"]=$scexts[1][0]["fix_phone"] ? $scexts[1][0]["fix_phone"] : '';
                $end["mobile_phone"]=$scexts[1][0]["mobile_phone"] ? $scexts[1][0]["mobile_phone"] : '';
                $end["address"]=$scexts[1][0]["address"] ? $scexts[1][0]["address"] : '';
                $end["phone_number"]=$scexts[1][0]["phone_number"] ? $scexts[1][0]["phone_number"] : '';
                $end["qq"]=$scexts[1][0]["qq"] ? $scexts[1][0]["qq"] : '';
                $end["phone_img"]=$scexts[1][0]["phone_img"] ? $scexts[1][0]["phone_img"] : '';
                $end["sc_id"]=$scexts[1][0]["sc_id"] ? $scexts[1][0]["sc_id"] : '';
             }
        }

        $this->view->start = $start;
        $this->view->end = $end;
        $this->view->data = $data;
        $start_name = M\CargoInfo::GetAreaName($data->start_cname,$data->start_pname);
        $end_name   = M\CargoInfo::GetAreaName($data->start_cname,$data->start_pname);        
        $this->view->title = ''.$start_name.'到'.$end_name.'的专线信息-丰收汇';
        $this->view->keywords = '';
        $this->view->descript = '丰收汇-可靠农产品交易网，为你提供'.$start_name.'到'.$end_name.'的专线信息。';
        $this->view->start_areas = '1';
        $this->view->sorce = '';         
    }
    public function testAction(){
        $data = M\ScInfo::find()->toArray();
         $sql='';
        foreach ($data as $key => $value) {
            if($value['sc_sn']==''){
                $sn= sprintf('%09u',$value['sc_id']);
                $sc_id=$value['sc_id'];
                $sql.="update sc_info set sc_sn='{$sn}' where sc_id={$sc_id};";
            }
            
        }
        echo $sql;
    }
}

