<?php
namespace Mdg\Manage\Controllers;
use Lib\Member as Member;
use Phalcon\Mvc\Controller;
use Lib\Auth as Auth;
use Lib\Arrays as Arrays;
use Mdg\Models as M;
use Mdg\Models\AreasFull as mAreas;
use Lib as L;
use Lib\Func as Func;
use Lib\Pages as Pages;
use Lib\Areas as lAreas;
use Mdg\Models\Codes as Codes;
class  SpecialController extends Controller
{
    
    /** 操作成功，CODE: 0 */
    const SUCCESS = 0;
    /** 添加失败，CODE: 11000 */
    const  SPECIAl_ERROR= 11000;
    /**
     *  物流列表
     * @return [type] [description]
     */
    public function indexAction()
    {


        $page = $this->request->get('p', 'int', 1);
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
        
        $pages['total_pages'] = ceil($total / $page_size);
        $pages['current'] = $page;
        $pages['total'] = $total;
        $pages = new Pages($pages);
        $pages = $pages->show(1);
        $this->view->current = $page;
        $this->view->data = $data;
        $this->view->pages = $pages;
        $this->view->startAreas=$sarea;
        $this->view->endAreas=$earea;
        $this->view->title = '丰收汇物流 ';

    }
    /**
     * 新增物流
     * @return [type] [description]
     */
    public function newAction(){

    }
    /**
     * 新增物流保存
     * @return [type] [description]
     */
    public function createAction(){

        $company_name    = $this->request->getPOST('company_name', 'string', '');
        $net_name        = $this->request->getPOST('net_name', 'string', '');
        $contact_man     = $this->request->getPOST('contact_man', 'string', '');
        $fix_phone       = $this->request->getPOST('fix_phone', 'string', '');
        $mobile_phone    = $this->request->getPOST('mobile_phone', 'string', '');
        $address         = $this->request->getPOST('address', 'string', '');
        $qq              = $this->request->getPOST('qq', 'string', '');
        
        $light_price     = $this->request->getPOST('light_price', 'float',0.00);
        $heavy_price     = $this->request->getPOST('heavy_price', 'float',0.00);
        //出发地
        $start_pname     = $this->request->getPOST('start_pname', 'int', 0);
        $start_cname     = $this->request->getPost('start_cname','int',0);
        $start_areas     = $this->request->getPost('start_areas','int',0);


        $endaddress      = $this->request->getPOST('endaddress', 'string', '');
        $endcontact_man  = $this->request->getPOST('endcontact_man', 'string', '');
        $endmobile_phone = $this->request->getPOST('endmobile_phone', 'string', '');
        $endfix_phone    = $this->request->getPOST('endfix_phone', 'string', '');
        $endnet_name     = $this->request->getPOST('endnet_name', 'string', '');
        $endcompany_name = $this->request->getPOST('endcompany_name', 'string', '');
        $endqq           = $this->request->getPOST('endqq', 'string', '');
        //目的地
        $end_areas       = $this->request->getPOST('endAreas', 'int', 0);
        $end_pname       = $this->request->getPost('end_pname','int',0);
        $end_cname       = $this->request->getPost('end_pname','int',0);
        $type            = $this->request->getPOST('type', 'int',0);
        $content         = $this->request->getPost('content','string','');

		if(!$company_name||!$net_name||!$contact_man||!$fix_phone||!$mobile_phone){
              die("<script>alert('各项不能为空');location.href='/manage/special/new'</script>");
		}
		
		$this->db->begin();
        try
        {

        	$scinfo= new M\ScInfo();
			$scinfo->contact_man      = $contact_man;
			$scinfo->phone            = $mobile_phone;
			$scinfo->light_price      = $light_price;
			$scinfo->heavy_price      = $heavy_price;
			$scinfo->status           = 1;
			$scinfo->start_pid        = $start_pname;
			$scinfo->start_pname      = M\AreasFull::getAreasNametoid($scinfo->start_pid);
			$scinfo->start_cid        = $start_cname;
			$scinfo->start_cname      = M\AreasFull::getAreasNametoid($scinfo->start_cid);
			$scinfo->start_did        = $start_areas;
			$scinfo->start_dname      = M\AreasFull::getAreasNametoid($scinfo->start_did);
			$scinfo->add_time         = time();
			$scinfo->last_update_time = time();
            $scinfo->end_pid          = $end_pname;
			$scinfo->end_pname        = M\AreasFull::getAreasNametoid($scinfo->end_pid);
            $scinfo->end_cid          = $end_cname;
			$scinfo->end_cname        = M\AreasFull::getAreasNametoid($scinfo->end_cid);
            $scinfo->end_did          = $end_areas;
			$scinfo->end_dname        = M\AreasFull::getAreasNametoid($scinfo->end_did);

			$scinfo->type=$type;
			$scinfo->phone_number = substr_replace($mobile_phone,'****',3,4);
            $scinfo->phone_img=Codes::getmoblie($mobile_phone);
            $scinfo->demo             = $content;
			if(!$scinfo->save()){
                throw new \Exception(self::SPECIAl_ERROR);
			}
            $scinfo->sc_sn = sprintf('%09u', $scinfo->sc_id);
            $scinfo->save();
            $scext= new M\ScExt();
			$scext->sc_id=$scinfo->sc_id;
			$scext->net_name=$net_name;
			$scext->company_name=$company_name;
			$scext->contact_man=$contact_man;
			$scext->fix_phone=$fix_phone;
			$scext->mobile_phone=$mobile_phone;
			$scext->type=0;
			$scext->address=$address;
			$scext->add_time=time();
			$scext->last_update_time=time();
			$scext->status=1;
            $scext->qq=$qq;
            $scext->phone_number = substr_replace($mobile_phone,'****',3,4);
            $scext->phone_img=$scinfo->phone_img;
			if(!$scext->save()){
               throw new \Exception(self::SPECIAl_ERROR);
			}
           
			$scextend = new M\ScExt();
			$scextend->sc_id=$scinfo->sc_id;
			$scextend->net_name=$endnet_name;
			$scextend->company_name=$endcompany_name;
			$scextend->contact_man=$endcontact_man;
			$scextend->fix_phone=$endfix_phone;
			$scextend->mobile_phone=$endmobile_phone;
			$scextend->type=1;
			$scextend->address=$endaddress;
			$scextend->add_time=time();
			$scextend->last_update_time=time();
			$scextend->status=1;
            $scextend->qq=$endqq;
            $scextend->phone_number = substr_replace($endmobile_phone,'****',3,4);
            $scextend->phone_img=Codes::getmoblie($endmobile_phone);
			if(!$scextend->save()){
               throw new \Exception(self::SPECIAl_ERROR);   
			}
            $this->db->commit();
            $flag =0;

        }
        catch(\Exception $e) 
        {
            $this->db->rollback();
            $flag = $e->getMessage();
        }

        if($flag==0){
             die("<script>alert('添加成功');location.href='/manage/special/index'</script>");
        }else{
             die("<script>alert('添加失败');location.href='/manage/special/new'</script>");
        }
    }
    /**
     * 编辑物流
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function editAction($id){
    	if(!$id){
    		die("<script>alert('来源错误');location.href='/manage/special/index'</script>");
    	}
    	$data = M\ScInfo::findFirstBysc_id($id);
        if($data&&$data->status==0){
            die("<script>alert('信息已删除');location.href='/manage/special/index'</script>");
        }
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
                $start["phone_img"]=$scexts[0][0]["phone_img"] ? "<img src='".IMG_URL.$scexts[0][0]["phone_img"]."'>" : '';
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
                $end["phone_img"]=$scexts[1][0]["phone_img"] ? "<img src='".IMG_URL.$scexts[1][0]["phone_img"]."'>" : '';
                $end["sc_id"]=$scexts[1][0]["sc_id"] ? $scexts[1][0]["sc_id"] : '';
             }
        }
        $start_areas = "'{$data->start_pname}','{$data->start_cname}','{$data->start_dname}'";  
        $end_areas = "'{$data->end_pname}','{$data->end_cname}','{$data->end_dname}'"; 
        $this->view->startAreas = $start_areas;
        $this->view->endAreas =$end_areas;
        $this->view->start = $start;
        $this->view->end = $end;
        $this->view->data = $data;
    }
    /**
     * 编辑物流保存
     * @return [type] [description]
     */
    public function saveAction(){
        
        //$_SERVER['HTTP_REFERER'];
        $company_name    =$this->request->getPOST('company_name', 'string', '');
        $net_name        =$this->request->getPOST('net_name', 'string', '');
        $contact_man     =$this->request->getPOST('contact_man', 'string', '');
        $fix_phone       =$this->request->getPOST('fix_phone', 'string', '');
        $mobile_phone    =$this->request->getPOST('mobile_phone', 'string', '');
        $startaddress    =$this->request->getPOST('startaddress', 'string', '');
        
        $light_price     =$this->request->getPOST('light_price', 'float',0.00);
        $heavy_price     =$this->request->getPOST('heavy_price', 'float',0.00);

        //出发地
        $startprovince     =$this->request->getPOST('startprovince', 'int', 0);
        $startcity     =$this->request->getPost('startcity','int',0);
        $startdistrict     =$this->request->getPost('startdistrict','int',0);


        $endaddress      =$this->request->getPOST('endaddress', 'string', '');
        $endcontact_man  =$this->request->getPOST('endcontact_man', 'string', '');
        $endmobile_phone =$this->request->getPOST('endmobile_phone', 'string', '');
        $endfix_phone    =$this->request->getPOST('endfix_phone', 'string', '');
        $endnet_name     =$this->request->getPOST('endnet_name', 'string', '');
        $endcompany_name =$this->request->getPOST('endcompany_name', 'string', '');
        $content         = $this->request->getPost('content','string','');
        $qq              = $this->request->getPOST('qq', 'string', '');
        $endqq           = $this->request->getPOST('endqq', 'string', '');

        //目的地
        $endprovince       =$this->request->getPOST('endprovince', 'int', 0);
        $endcity     =$this->request->getPost('endcity','int',0);
        $enddistrict     =$this->request->getPost('enddistrict','int',0);


        $type            =$this->request->getPOST('type', 'int',0);
        
        $sc_id           =$this->request->getPOST('sc_id', 'int',0);
        
       
        if(!$company_name||!$net_name||!$contact_man||!$fix_phone||!$mobile_phone){
              die("<script>alert('各项不能为空');location.href='/manage/special/new'</script>");
        }
       
        $this->db->begin();
        try
        {

            $scinfo= M\ScInfo::findFirstBysc_id($sc_id);
            $scinfo->contact_man      = $contact_man;
            $scinfo->phone            = $mobile_phone;
            $scinfo->light_price      = $light_price;
            $scinfo->heavy_price      = $heavy_price;
            $scinfo->status           = 1;

            $scinfo->start_pid        = $startprovince;
            $scinfo->start_pname      = M\AreasFull::getAreasNametoid($scinfo->start_pid);
            $scinfo->start_cid        = $startcity;
            $scinfo->start_cname      = M\AreasFull::getAreasNametoid($scinfo->start_cid);
            $scinfo->start_did        = $startdistrict;
            $scinfo->start_dname      = M\AreasFull::getAreasNametoid($scinfo->start_did);

            $scinfo->end_pid          = $endprovince;
            $scinfo->end_pname        = M\AreasFull::getAreasNametoid($scinfo->end_pid);
            $scinfo->end_cid          = $endcity;
            $scinfo->end_cname        = M\AreasFull::getAreasNametoid($scinfo->end_cid);
            $scinfo->end_did          = $enddistrict;
            $scinfo->end_dname        = M\AreasFull::getAreasNametoid($scinfo->end_did);
            
            $scinfo->add_time         = time();
            $scinfo->last_update_time = time();
            $scinfo->type=$type;
            $scinfo->phone_number = substr_replace($mobile_phone,'****',3,4);
            $scinfo->phone_img=Codes::getmoblie($mobile_phone);
            $scinfo->demo             =$content;
            if(!$scinfo->save()){
                throw new \Exception(self::SPECIAl_ERROR);
            }
            $scext= M\ScExt::findFirst("sc_id={$sc_id} and type=0 ");
            $scext->sc_id=$scinfo->sc_id;
            $scext->net_name=$net_name;
            $scext->company_name=$company_name;
            $scext->contact_man=$contact_man;
            $scext->fix_phone=$fix_phone;
            $scext->mobile_phone=$mobile_phone;
            $scext->address=$startaddress;
            $scext->add_time=time();
            $scext->last_update_time=time();
            $scext->status=1;
            $scext->qq=$qq;
            $scext->phone_number = substr_replace($mobile_phone,'****',3,4);
            $scext->phone_img=$scinfo->phone_img;

            if(!$scext->save()){
               throw new \Exception(self::SPECIAl_ERROR);
            }

            $scextend = M\ScExt::findFirst("sc_id={$sc_id} and type=1 ");
            $scextend->sc_id=$scinfo->sc_id;
            $scextend->net_name=$endnet_name;
            $scextend->company_name=$endcompany_name;
            $scextend->contact_man=$endcontact_man;
            $scextend->fix_phone=$endfix_phone;
            $scextend->mobile_phone=$endmobile_phone;
            $scextend->address=$endaddress;
            $scextend->add_time=time();
            $scextend->last_update_time=time();
            $scextend->status=1;
            $scextend->qq=$endqq;
            $scextend->phone_number = substr_replace($endmobile_phone,'****',3,4);
            $scextend->phone_img=Codes::getmoblie($endmobile_phone);
            if(!$scextend->save()){
               throw new \Exception(self::SPECIAl_ERROR);   
            }

            $this->db->commit();
            $flag =0;

        }
        catch(\Exception $e) 
        {
            $this->db->rollback();
            $flag = $e->getMessage();
        }
       
        if($flag==0){
             die("<script>alert('修改成功');location.href='/manage/special/index'</script>");
        }else{
             die("<script>alert('修改失败');location.href='/manage/special/edit/{$sc_id}'</script>");
        }
    }
    /**
     * 查看物流
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
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
                $start["mobile_phone"]=$scexts[0][0]["mobile_phone"] ? $scexts[0][0]["mobile_phone"] : "<img src='".IMG_URL.$scexts[0][0]["phone_img"]."'>";
                $start["address"]=$scexts[0][0]["address"] ? $scexts[0][0]["address"] : '';
                $start["phone_number"]=$scexts[0][0]["phone_number"] ? $scexts[0][0]["phone_number"] : '';
                $start["qq"]=$scexts[0][0]["qq"] ? $scexts[0][0]["qq"] : '';
                $start["sc_id"]=$scexts[0][0]["sc_id"] ? $scexts[0][0]["sc_id"] : '';
             }
             if($key==1){
                $end["net_name"]=$scexts[1][0]["net_name"] ? $scexts[1][0]["net_name"] : '';
                $end["company_name"]=$scexts[1][0]["company_name"] ? $scexts[1][0]["company_name"] : '';
                $end["contact_man"]=$scexts[1][0]["contact_man"] ? $scexts[1][0]["contact_man"] : '';
                $end["fix_phone"]=$scexts[1][0]["fix_phone"] ? $scexts[1][0]["fix_phone"] : '';
                $end["mobile_phone"]=$scexts[1][0]["mobile_phone"] ? $scexts[1][0]["mobile_phone"] : "<img src='".IMG_URL.$scexts[1][0]["phone_img"]."'>";
                $end["address"]=$scexts[1][0]["address"] ? $scexts[1][0]["address"] : '';
                $end["phone_number"]=$scexts[1][0]["phone_number"] ? $scexts[1][0]["phone_number"] : '';
                $end["qq"]=$scexts[1][0]["qq"] ? $scexts[1][0]["qq"] : '';
                $end["sc_id"]=$scexts[1][0]["sc_id"] ? $scexts[1][0]["sc_id"] : '';
             }
        }

        $this->view->start = $start;
        $this->view->end = $end;
        $this->view->data = $data;
    }
    /**
     * 删除物流
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function deleteAction($id){
       
        if(!$id){
            die("<script>alert('来源错误');location.href='/manage/special/index'</script>");
        }
        $data = M\ScInfo::findFirst(" sc_id={$id} and status=1 ");
        if($data){
            $data->status=0;
            $data->save();
        }
        $scextend = M\ScExt::findFirst("sc_id={$id} and type=1 ");
        if($scextend){
            $scextend->status=0;
            $scextend->save();
        }
           
        $scextends = M\ScExt::findFirst("sc_id={$id} and type=0 ");
        if($scextends){
            $scextends->status=0;
            $scextends->save();
        }
        die("<script>alert('删除成功');location.href='/manage/special/index'</script>");
    }

    /**
     * 批量删除
     * @return [type] [description]
     */
    public function removeAllAction () {
        if(!$this->request->getPost()) {
            return $this->dispatcher->forward(array(
                    "controller" => "special",
                    "action" => "index"
                ));
        }
        $id = $this->request->getPost('remove');

        if(!$id) {
            // $this->response->redirect("special/index")->sendHeaders();
        }
        $_id = join(',', $id);

        $sql = " UPDATE sc_info SET status = 0 where sc_id in ({$_id}) ";
        
        $this->db->execute($sql);
        $this->response->redirect("special/index")->sendHeaders();
    }
}

