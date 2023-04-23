<?php
namespace Mdg\Wuliu\Controllers;
use Phalcon\Mvc\Controller;
use Lib\Crypt as crypt;
use Mdg\Models\Users as Users;
use Mdg\Models\Orders as Orders;
use Mdg\Models\UsersExt as UsersExt;
use Mdg\Models as M;

class ControllerBase extends Controller
{
	public function initialize(){
       header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');   
       
		
        $ordercount=0;
        if(isset($_COOKIE["ync_auth"])|| isset($_COOKIE["village_auth"])){
      
  
             if(isset($_COOKIE["ync_auth"]) && $_COOKIE["ync_auth"]) {
                 $user=crypt::authcode($_COOKIE["ync_auth"],$operation = 'DECODE');
             }
             if(isset($_COOKIE["village_auth"])) {
                 $user=crypt::authcode($_COOKIE["village_auth"],$operation = 'DECODE');
             }

             $users=explode("|",$user);
          
             if(!isset($users[1]) && !empty($users)){
                 $this->session->user=array();
             }else{
                 $mobile=$users[1];
                 $user = Users::checkLogin($mobile,$password='123456');
                 if($user){
                    $userext=UsersExt::getuserext($user->id);
                    $this->session->user = array('mobile'=>$user->username,'id'=>$user->id,'name'=>$userext->name);
                 }
                 //订单个数
                 $ordercount=Orders::getordercount($user->id);
             }
        }

        /* 新版头部内容 */
        /* 头部数据 */
        $cache_key='cate_home.cache';
       
        $rest = $this->cache->get($cache_key,3600);
        
        if($rest === NULL) {
            /* 顶级分类 */
            $pid = array(
                7 => 0,
                1 => 1,
                2 => 2,
                1377 => 3,
                15 => 4,
                22 => 5,
                899 => 6,
            );

            $_pid = join(',', array_keys($pid));
            $data = M\Category::find(array(
                " id in({$_pid})",
                'columns' => 'id, title'
            ))->toArray();
            $i = 0;
            foreach ($data as $val) 
            {

                $parentid = $val['id'];
                $order = array_key_exists($parentid, $pid) ? $pid[$parentid] : 0;

                /* A-E */
                $rest[$order] = $val;
                $rest[$order]['cate'][1] = M\Category::find(array(
                    "parent_id = '{$parentid}' AND abbreviation between 'A' AND 'E'",
                    'colimns' => 'id,title,abbreviation',
                    'limit' => 10
                ))->toArray();
                /* F-J*/
                $rest[$order]['cate'][2] = M\Category::find(array(
                    "parent_id = '{$parentid}' AND abbreviation between 'F' AND 'J'",
                    'colimns' => 'id,title,abbreviation',
                    'limit' => 10
                ))->toArray();
                /* K-O*/
                $rest[$order]['cate'][3] = M\Category::find(array(
                    "parent_id = '{$parentid}' AND abbreviation between 'K' AND 'O'",
                    'colimns' => 'id,title,abbreviation',
                    'limit' => 10
                ))->toArray();
                /* P-T*/
                $rest[$order]['cate'][4] = M\Category::find(array(
                    "parent_id = '{$parentid}' AND abbreviation between 'P' AND 'T'",
                    'colimns' => 'id,title,abbreviation',
                    'limit' => 10
                ))->toArray();
                /* U-Z*/
                $rest[$order]['cate'][5] = M\Category::find(array(
                    "parent_id = '{$parentid}' AND abbreviation between 'U' AND 'Z'",
                    'colimns' => 'id,title,abbreviation',
                    'limit' => 10
                ))->toArray();
            }
            ksort($rest);    
            /* 将数据写入文件 */
            $this->cache->save($cache_key, $rest);
        }else{
        ksort($rest);   
        }

        $user = $this->session->user;
        if(isset($user['id'])){
            
            $messagecount = M\Message::GetMessageUnreadCount($user['id']);
        }else{
            
            $messagecount=0;
        }

        $this->usercount = 100;
        $this->shopcount = 100;
        $this->storecount = 100;
        $this->servicecount = 100;
        $this->ordercount = 100;

        $this->view->haeder_cate = $rest;
        /* 头部数据 */
        $this->view->userInfo = $this->checkUserInfo();
        //检测用户是否完善信息
       
        $this->view->getMyCateList = $this->getMyCateList();
        $this->view->messagecount = $messagecount;

        $this->view->_nav = 'shou';
        $_navs = strtolower($this->dispatcher->getControllerName());
        $hotsell=M\Sell::getHotlist();
        $this->view->hotsell=$hotsell;
        $this->view->is_brokeruser = Users::findFirstByid($user["id"]);;
        $this->view->_navs = $_navs ? $_navs : 'index';
       
        // $this->view->_modules = MODULES ? MODULES : 'wuliu';

        $this->view->title = '';
        $this->view->keywords = '';
        $this->view->descript = '';
        $this->view->ordercount = $ordercount;
    }

 /**
     * 获取用户关注分类
     * @return [type] [description]
     */
    public function getMyCateList() {
        $uid = $this->getUserID();
        if(!$uid) return array();
        /* */
        $sell = M\UserAttention::selectByuid($uid,1);
        $pur = M\UserAttention::selectByuid($uid,2);
        
        return array( $sell , $pur);
    }


    public function getUserID() {
        $user = $this->session->user;
        return isset($user['id']) ? $user['id'] : 0 ;
    }
    

    /**
     * 检测当前用户是否信息完整
     * @return [type] [description]
     */
    public function checkUserInfo() {
        $user = $this->session->user;
        $uid = isset($user['id']) ? intval($user['id']) : 0 ;
        if(!$uid) return 1;
        $info = M\Users::findFirst($uid);
        if(!$info) return 1;
        if(!isset($info->ext->name) || !isset($info->ext->areas_name) || !isset($info->ext->goods) || !$info->ext->name || !$info->ext->areas_name || !$info->ext->goods) return 1;
        return false;
    }



}
