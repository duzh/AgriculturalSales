<?php
/**
 * 用户管理
 */
namespace Mdg\Manage\Controllers;
use Mdg\Models as M;
use Lib\YnbUserInterface;

class YnbbindingController extends ControllerBase
{
    /**
     * 用户管理列表
     */
    
    public function indexAction() 
    {
        $page        = $this->request->get('p' , 'int',  1);
        $psize       = $this->request->get('psize', 'int', 10);
        $uid = $this->request->get('uid','int',0);
        $name = $this->request->get('name','string');
        $moblie = $this->request->get('moblie','string');
        $statime = $this->request->get('stime','string');
        $endtime = $this->request->get('etime','string');
        $con = array();
        if($uid)$con[] = " AND user_id ={$uid}";
        if($name)$con[] =" AND name LIKE '%{$name}%'";
        if($moblie)$con[] = " AND moblie ={$moblie}";
        if($statime)$con[] = " AND statime ={$statime}";
        if($endtime)$con[] =" AND endtime ={$endtime}";

        $data = M\UserYnpInfo::getUserYnpInfoList($con , $page , $psize);
        #print_r($data['items']);exit;
        $this->view->uid = $uid;
        $this->view->name= $name;
        $this->view->moblie = $moblie;
        $this->view->stime = $statime;
        $this->view->etime = $endtime;
        $this->view->data = $d =  $data['items'];
        #print_r($d);exit;
    }

    /**
     *
     * @param $userID
     */
    public function getlogAction($userID){
        $ynbuser = new YnbUserInterface();
        $data = $ynbuser->getUserBinding($userID);
        print_r($data);exit;
    }

}
