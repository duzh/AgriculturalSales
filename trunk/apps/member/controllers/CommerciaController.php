<?php
/**
 * 商户栏目管理
 */
namespace Mdg\Member\Controllers;
use Phalcon\Mvc\Model\Criteria;
use Mdg\Models as M;
use Lib\Func as Func;
use Lib\Areas as lAreas;
use Lib\Arrays as Arrays;
class CommerciaController extends ControllerMember
{

    /**
     * 我的供应
     */
    public function indexAction()
    {    
        
 
        $page = $this->request->get('p', 'int', 1);
        $page = intval($page)>0 ? intval($page) : 1;
        $userid = $this->request->get('userid', 'int', 0);
        $mobile = $this->request->get('mobile', 'string','');
        $username = $this->request->get('username', 'string', '');
        $province = $this->request->get('province', 'string', '');
        $city = $this->request->get('city', 'string', '');
        $district = $this->request->get('district', 'string', '');
        $stime = $this->request->get('stime', 'string', '');
        $etime = $this->request->get('etime', 'string', '');
        $state = $this->request->get('state', 'int', 0);
        $user_id=$this->getUserID();
        $se_mobile=$this->getUserName();
        
        $where=" ui.se_mobile = '{$se_mobile}' and ui.credit_type!=2 and ui.status=1 and ui.user_id!=$user_id and u.id!='' ";
        
        if($state) 
        {
            switch ($state) {
                case '1':
                $where.= " and ui.status=0 ";
                break;
                case '2':
                $where.= " and ui.status=1 ";
                break;
                case '3':
                $where.= " and ui.status=2 ";
                break;
            }
        }
        $curAreas='';
        $areawhere='';
        if($userid){
             $where.=" and u.id ={$userid} ";    
        }
        if($mobile){
             $where.=" and u.username ={$mobile} ";    
        }
        if($username){
            $where.=" and uext.name like '%{$username}%' ";    
        }
        if($province){
            $curAreas=lAreas::ldData($province);
            $areawhere=" and u.province_id in ({$province}) ";
        }
        if($city){
            $curAreas=lAreas::ldData($city);
            $areawhere=" and u.city_id in ({$city}) ";
        }
        if($district){
            $curAreas=lAreas::ldData($district);
            $areawhere=" and u.district_id in ({$district}) ";
        }
        $where.=$areawhere;
    
        if($stime&&$etime){
             $stimes=strtotime($stime."00:00:00");
             $etimes=strtotime($etime . "23:59:59");
             $where.=" and  u.regtime  BETWEEN {$stimes} and {$etimes} ";
        }
        
        $userinfo=M\UserInfo::getlist($where,$page,10);
       
        $this->view->http = $_SERVER['SERVER_NAME'];
        $this->view->state = M\UserInfo::$_status;
        $this->view->commstate=$state;
        $this->view->stime=$stime;
        $this->view->etime=$etime;
        $this->view->p=$page;
        $this->view->userinfo =$userinfo;
        $this->view->curAreas =$curAreas;
        $this->view->title = '我发展的商户-商户列表';
        $this->view->keywords = '丰收汇,农业,农产品,农副产品,农产品批发,农产品电商,农产品现货,农产品交易,农产品加工,农产品价格';
        $this->view->descript = '丰收汇是依托中国农业互联网高科技综合服务商云农场的丰富资源和先进技术而建立的中国最专业的网上农产品服务平台，
        为中国农村专业合作社、中小农商企业等提供农产品信息发布、行情资讯、网上商城、金融服务、定向对接、安全模型、订单种植等专业化服务，
        旨在成为全球高价值农产品交易服务商。';
    }
}   