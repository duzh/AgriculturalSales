<?php

namespace Mdg\Frontend\Controllers;
use Lib\Member as Member;
use Lib\Auth as Auth;
use Lib\Arrays as Arrays;
use Mdg\Models as M;
use Mdg\Models\Category as Category;
use Lib as L;
class MapController extends ControllerBase
{


    public function mapAction(){
            //phpinfo();
            $page=$this->request->get('p','int',1);
            $pagesize=$this->request->get('pagesize','int',10);
            $type=$this->request->get('type','int',1);
            $ipaddress=$this->request->get('address','string','');
            $addressid=$this->request->get('addressid','int',0);
            $size=$this->request->get('size','string','');
            $db = $GLOBALS['di']['db'];

            $offst = intval(($page - 1) * $pagesize);
            $calm=array();
            $str='';
            $addressstr='';
            if($type==0){
               $where=" 1=1 ";
            }
            if($type==1){
               $where=" type=1 ";
            }
            if($type==2){
               $where=" type=2  ";
            }
            if($type==3){
               $where=" type=3 ";
            }
            if($addressid){
               $addressstr=L\Areas::ldData($addressid);
            }
            if($ipaddress==''||$ipaddress=='请选择'){
                $ip=L\Func::GetIpLookup();
                $ipaddress=$ip["province"];
                $size=12;
                $where.=" and  address like '%{$ipaddress}%' ";
            }else{
                $where.=" and  address like '%{$ipaddress}%' ";
            }
            
            $typestr[1]="县级服务站";
            $typestr[2]="县级服务站";
            $typestr[3]="可信农场";
            $sql="SELECT count(*) as count  FROM map where  $where  ORDER BY id ";
            $count=$db->query($sql)->FetchArray();
            $total = $count["count"];
    
            $sql="SELECT f_name,lat,lng,address,type,mobile  FROM map where  $where  ORDER BY id limit {$offst} , {$pagesize} ";

            $calm = $db->FetchAll($sql,2);

            if(!empty($calm)){
                foreach ($calm as $key => $value) {
                  if($value["f_name"]!=''&&$value["address"]!=''){  
                      $lat=$value["lat"];
                      $lng=$value["lng"];
                      if($value["type"]==1){
                        $url="http://yncstatic.b0.upaiyun.com/mdg/version2.4/img/map1.png";
                      }else{
                        $url="http://yncstatic.b0.upaiyun.com/mdg/version2.4/img/map2.png";
                      }
                      $f_name=$value["f_name"];
                      $address=$value["address"];
                      $typestrs=$typestr[$value["type"]];
                      $content='';
                      $content='<div class="map-sign-box"><div class="title m-title"><font class="f-fl">'.$typestrs.'</font></div><div class="box"><div class="message clearfix mt20"><font class="f-db f-fl">联系人：</font><div class="content">'.$f_name.'</div></div><div class="message clearfix"><font class="f-db f-fl">地址：</font><div class="content s_content">'.$address.'</div></div></div><div class="fw-area"><div class="message clearfix"><font class="f-db f-fl">服务范围：</font><div class="s_content"><span>'.$address.'</span></div></div></div></div>';
                  
                      $str.='['."$lng,$lat,'{$content}','{$url}'".'],';
                  }
                  
                  $calm[$key]["type"]=$typestr[$value["type"]];
                  $calm[$key]["url"]=$url;
                  $calm[$key]["content"]=base64_encode($content);
                }
            }
            
            //echo $str;die;
            $this->view->addressid=$addressid;
            $this->view->max=$total;
            $this->view->total=ceil($total/$pagesize);
            $this->view->page=$page+1;
            $this->view->address=$ipaddress;
            $this->view->type=$type;
            $this->view->size=$size;
            $this->view->calm=$calm;
            $this->view->str=$str; 
            $this->view->addressstr=$addressstr; 

    }
    public function showmapAction(){

        $page=$this->request->get('p','int',1);
        $type=$this->request->get('type','int',1);
        //$page=$page;
        $ipaddress=$this->request->get('address','string','');
        
        if($type==0){
           $where=" 1=1 ";
        }
        if($type==1){
           $where=" type=1 ";
        }
        if($type==2){
           $where=" type=2  ";
        }
        if($type==3){
           $where=" type=3 ";
        }
        if($ipaddress){
            $where.=" and  address like '%{$ipaddress}%' ";
        }else{
            $ip=L\Func::GetIpLookup();
            $ipaddress=$ip["province"];
            $size=12;
            $where.=" and  address like '%{$ipaddress}%' ";
        }
        $typestr[1]="县级服务站";
        $typestr[2]="县级服务站";
        $typestr[3]="可信农场"; 
        $pagesize=10;
        $calm=array();
        $db = $GLOBALS['di']['db'];
        $offst = intval(($page - 1) * $pagesize);
        
        $sql="SELECT count(*) as count  FROM map where  $where  ORDER BY id ";
        $count=$db->query($sql)->FetchArray();
        $total = $count["count"];
        $total_page=ceil($total/$pagesize);
        $sql="SELECT f_name,lat,lng,address,type  FROM map where  $where  ORDER BY id limit {$offst} , {$pagesize} ";
        $calm = $db->query($sql)->FetchAll();
        foreach ($calm as $key => $value) {
           $f_name=$value["f_name"];
           $address=$value["address"];
           $calms[$key]["lng"]=$value["lng"];
           $calms[$key]["lat"]=$value["lat"];
           if($value["type"]==1){
             $calms[$key]['url']="http://yncstatic.b0.upaiyun.com/mdg/version2.4/img/map1.png";
           }else{
             $calms[$key]['url']="http://yncstatic.b0.upaiyun.com/mdg/version2.4/img/map2.png";
           }
           $f_name=$value["f_name"];
           $address=$value["address"];
           $typestrs=$typestr[$value["type"]];
           $content='';
           $content='<div class="map-sign-box"><div class="title m-title"><font class="f-fl">'.$typestrs.'</font></div><div class="box"><div class="message clearfix mt20"><font class="f-db f-fl">联系人：</font><div class="content">'.$f_name.'</div></div><div class="message clearfix"><font class="f-db f-fl">地址：</font><div class="content s_content">'.$address.'</div></div></div><div class="fw-area"><div class="message clearfix"><font class="f-db f-fl">服务范围：</font><div class="s_content"><span>'.$address.'</span></div></div></div></div>';
           $calms[$key]["address"]=base64_encode($content);
        }
        $arr["str"]= $calms;
        if($page<$total_page){
            $arr["pages"]=$page+1;
        }
        echo json_encode($arr);die;
    }
    public function savemapAction(){
        $lat = $this->request->get('lat');
        $lng = $this->request->get('lng');
        $f_name = $this->request->get('f_name');
        $address = $this->request->get('address');
        $mobile = $this->request->get('mobile');
        $contact_sn = $this->request->get('contact_sn');
        if($lat&&$lng&&$f_name&&$address&&$mobile&&$contact_sn){
            $db = $GLOBALS['di']['db'];
            $select="seelct * from map where type=1 and pid='{contact_sn}'";
            if($select){
              $update="update map set lat='{$lat}',lng='{$lng}',address='{$address}' where pid='{contact_sn}' ";
            }else{
              $update="insert into map (lat,lng,f_name,address,type,pid,mobile ) values  ('$lat','$lng','$f_name','$address',1,$contact_sn,'$mobile');";
            }
            $arr=$db->execute($update);
            if($arr){
               return true;
            }else{
               return false;
            }
        }else{
          return false;
        }
      
    }
    public static function maptestAction(){
      
        $sql=" select * from user_info where credit_type=2 and `status`=1 ";
        $calm=$GLOBALS['di']['db']->fetchAll($sql,2);
        $sql='';
        foreach ($calm as $key => $value) {
           
            
            $areas=$value["province_name"].$value["city_name"].$value["district_name"].$value["town_name"];
            
            $a1=self::addresstestAction($areas);
            $aa=json_decode($a1);
            $lat=$aa->result->location->lat;
            $lng=$aa->result->location->lng;
           
             
             $username=M\Users::findFirstByid($value["user_id"]);
             $userext=M\UsersExt::findFirstByuid($value["user_id"]);
             $f_name=$userext->name;
             $address=$areas;
             $pid=$value["user_id"];
             $mobile=$username->username;
             // print_r($userext);die;
            // $address=$area[0];
            // $pid=$area[1];
          
             $sql.="insert into map (lat,lng,f_name,address,type,pid,mobile) values  ('$lat','$lng','$f_name','$address',1,$pid,'$mobile');";
             
            //   # code...
            // self::exec($sql1);
            // echo $key."</br>";
            
           
        }
        echo $sql;die;
      //  echo $sql1;die;
        //print_r($arr);die;
    }
    public static function addresstestAction($areas) 
    {   $data=array();
        $url="http://api.map.baidu.com/geocoder/v2/?ak=ocQy04ecp2pLe6SlT4cpyiu7&output=json&address=".$areas;
        $Curl = new  L\Curl();

        $data = $Curl->get($url,$data);
        return $data;
    }
}

