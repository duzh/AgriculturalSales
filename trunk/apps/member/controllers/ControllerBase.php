<?php
namespace Mdg\Member\Controllers;
use Phalcon\Mvc\Controller;
use Mdg\Models\UsersNav as Nav;
use Mdg\Models\Article as Article;
use Mdg\Models\ArticleCategory as ArticleCategory;
use Lib\Func as Func;
use Lib\Crypt as crypt;
use Mdg\Models\Users as Users;
use Mdg\Models\UsersExt as UsersExt;
use Mdg\Models\Orders as Orders;
use Mdg\Models\Shop as Shop;
use Mdg\Models as M;
use Lib as L;


class ControllerBase extends Controller
{
    
    public function initialize() 
    {   
        
        $navList = Nav::find(array(
            'is_show=1',
            'order' => 'sortrank desc'
        ))->toArray();


        $navList = Func::toTree($navList, 'id', 'pid');
        $helper = ArticleCategory::find('pid = 1');
        $this->view->ordercount = '';
        $this->view->quotation = '';
        $this->view->navList = $navList;
        $this->view->title = '';
        $this->view->helper = $helper;
        $this->view->title = '';
        $this->view->keywords = '';
        $this->view->descript = '';
        $this->view->messagecount ='';
        
    header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
       
       


        /*if(!isset($_GET['mobile'])){
            $Wap = new L\Wap();
            if($Wap->is_mobile_request()) {
                echo  "<script>location.href='http://m.5fengshou.com/'</script>"; die;
            }
        } */       
        // $url=$_SERVER['HTTP_HOST'];
        // $url=explode('.',$url);
        // $url=$url[0];
        // if(!$url){
        //      echo  "<script>location.href='http://www.5fengshou.com/'</script>"; die;
        // }
        // if($url!='www'){
        //     $shop = Shop::findFirst("shop_link='{$url}' AND shop_status = 1 ");
        //     if($shop)
        //     {   $url="http://".$url.".5fengshou.com/store/";
        //         $url="http://www.5fengshou.com/";
        //         echo  "<script>location.href='$url'</script>"; die;
        //     }else{
        //         echo  "<script>location.href='http://www.5fengshou.com/'</script>"; die;
        //     }
        // }
        
        $cateData = array();
        $ordercount = 0;
        
        if (!empty($_COOKIE["ync_auth"]) && $_COOKIE["ync_auth"] != '') 
        {
            $user = crypt::authcode($_COOKIE["ync_auth"], $operation = 'DECODE');
            $users = explode("|", $user);
            $mobile = isset($users[1]) ? $users[1] : 0;
            
            if ($mobile) 
            {
                $user = Users::checkLogin($mobile);
                $userext = UsersExt::getuserext($user->id);
                $this->session->user = array(
                    'mobile' => $user->username,
                    'id' => $user->id,
                    'name' => $userext->name
                );
                $ordercount = Orders::getordercount($user->id);
            }
            else
            {
                $_COOKIE["ync_auth"] = array();
                $this->session->user = array();
            }
        }
        else
        {
            $_COOKIE["ync_auth"] = array();
            $this->session->user = array();
        }
        
        /* 头部数据 */	
        $cache_key='cate_home.cache';

        $cateData = $this->cache->get($cache_key);
        
        if($cateData === NULL) {
        
            $data = M\Category::find(array(
                " is_groom = 1 ",
                'columns' => 'id, title,abbreviation,parent_id',
                'order' => 'id ASC '
            ))->toArray();
            
            $sort = array(
                7 => 0,  //粮油
                1 => 1,  //蔬菜
                2 => 2,  //水果
                1377 => 3, //苗木
                15 => 4,  //干果
                22 => 5,  //中药材
                899 => 6, //其他
            );

            $pid = array_unique(array_column($data, 'parent_id'));
            $data = L\Arrays::toTree($data, 'id', 'parent_id');
            
            $k = 0;

            foreach ($data as $row) {
                
                if(isset($sort[$row['id']])) {
                    $k = $sort[$row['id']];
                }else{
                    continue;
                }

                $cateData[$k]['id'] = $row['id'];
                $cateData[$k]['title'] = $row['title'];
                $cateData[$k]['parent_id'] = $row['parent_id'];
                $cateData[$k]['cate'] = array();
                /* 检测是否存在 */
                foreach ($row['children'] as $key => $val) {
                    switch ($val['abbreviation']) {
                        case 'A': case 'B': case 'C': case 'D': case 'E':
                            if(isset($cateData[$k]['cate'][1]) && count($cateData[$k]['cate'][1]) > 10 ) break;
                            $cateData[$k]['cate'][1][$key]  = $val;
                            
                            break;
                        case 'F': case 'G': case 'H': case 'I': case 'J':
                            if(isset($cateData[$k]['cate'][2]) && count($cateData[$k]['cate'][2]) > 10 ) break;
                            $cateData[$k]['cate'][2][$key]  = $val;
                            break;
                        case 'K': case 'L': case 'M': case 'N': case 'O':
                            if(isset($cateData[$k]['cate'][3]) && count($cateData[$k]['cate'][3]) > 10 ) break;
                            $cateData[$k]['cate'][3][$key]  = $val;

                            break; 
                        case 'P': case 'Q': case 'R': case 'S': case 'T':
                            if(isset($cateData[$k]['cate'][4]) && count($cateData[$k]['cate'][4]) > 10 ) break;
                           $cateData[$k]['cate'][4][$key]  = $val;

                            break;
                        case 'U': case 'V': case 'W': case 'X': case 'Y':case 'Z':
                            if(isset($cateData[$k]['cate'][5]) && count($cateData[$k]['cate'][5]) > 10 ) break;
                            $cateData[$k]['cate'][5][$key]  = $val;
                            break;
                    }
                }
                
                if($cateData[$k]['cate']) {
                    ksort($cateData[$k]['cate']);
                }
                if(!in_array($cateData[$k]['id'] , $pid)) unset($cateData[$k]);
            }
           
            $this->cache->save($cache_key, $cateData);
        }else{
             ksort($cateData);   
        }
       
      

        $user = $this->session->user;
        if(isset($user['id'])){
            
            $messagecount = M\Message::GetMessageUnreadCount($user['id']);
        }else{
            
            $messagecount=0;
        }
        $hotsell=M\Sell::getHotlist();
       
        $this->usercount = 100;
        $this->shopcount = 100;
        $this->storecount = 100;
        $this->servicecount = 100;
        $this->ordercount = 100;

        $this->view->haeder_cate = $cateData;
		// $arr				= explode('/',__DIR__);
		// $action				= $arr['1'];
		// $this->view->action	= $action;
		
		
		
        /* 头部数据 */
        $this->view->userInfo = $this->checkUserInfo();
        //检测用户是否完善信息
        $this->view->_nav = $_nav = strtolower($this->dispatcher->getControllerName());
        $this->view->_action = $action = strtolower($this->dispatcher->getActionName());

        $this->view->getMyCateList = $getMyCateList = $this->getMyCateList();
        $this->view->get = $_GET;
        $this->view->is_ajax = 0;
        $this->view->is_kx = 0;
        $this->view->messagecount = $messagecount;
        $this->view->title = '';
        $this->view->hotsell=$hotsell;
        $this->view->keywords = '丰收汇,农业,农产品,农副产品,农产品批发,农产品电商,农产品现货,农产品交易,农产品加工,农产品价格';
        $this->view->descript = '丰收汇是依托中国农业互联网高科技综合服务商云农场的丰富资源和先进技术而建立的中国最专业的网上农产品服务平台，
        为中国农村专业合作社、中小农商企业等提供农产品信息发布、行情资讯、网上商城、金融服务、定向对接、安全模型、订单种植等专业化服务，
        旨在成为全球高价值农产品交易服务商。';
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

    public function getUserName() {
        $user = $this->session->user;
        return $user['mobile'];
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
        if(!isset($info->ext->name) || !isset($info->ext->areas_name)  || !$info->ext->name || !$info->ext->areas_name ) return 1;
        return false;
    }
    

}
