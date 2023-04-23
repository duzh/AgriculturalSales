<?php
namespace Mdg\Frontend\Controllers;
use Phalcon\Mvc\Controller;
use Mdg\Models as M;
use Mdg\Models\PurchaseQuotation as Quotation;
use Lib\Crypt as crypt;
use Mdg\Models\Users as Users;
use Mdg\Models\Orders as Orders;
use Mdg\Models\UsersExt as UsersExt;
use Mdg\Models\Shop as Shop;
use Mdg\Models\CredibleFarmInfo as CredibleFarmInfo;
use Lib as L;
class ControllerKx extends Controller
{
    
    public function initialize() 
    {

       
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

        $url=$_SERVER['HTTP_HOST'];

        $url=explode('.',$url);
        
        $user_id= $this->session->user ? $this->session->user["id"] : 0;
        $domain  = isset($url[0]) ? htmlentities($url[0]) : '';
        if(!$domain) {
            echo "<script>alert('可信农场不存在');location.href='http://www.5fengshou.com/index'</script>";
            exit;
        }
        $farm = M\CredibleFarmInfo::findFirst(" url = '{$domain}'"); 

        if(!$farm){
            die("该农场不存在");
        }
        // if($farm->status==0){
        //    die("该农场禁止访问");
        // }        
        $this->session->url = $farm->url;
        
        //$farm = M\CredibleFarmInfo::findFirst("url='{$farm->url}' AND status = 1 ");

        $user_info = M\UserInfo::findFirst("user_id={$farm->user_id} and status=1 and credit_type=8 ");
        
        if($user_info){
        $user_farm = M\UserFarm::findFirst("credit_id={$user_info->credit_id} ");
        }else{
        $user_farm='';
        }
        $credible_farm_info = CredibleFarmInfo::findFirstByuser_id($farm->user_id);
        if($credible_farm_info){
            $farmdesc = $credible_farm_info->desc;
        }else{
            $farmdesc='';
        }
        $CollectStore = array();
        if($this->session->user){
            $CollectStore = M\CollectStore::findFirst("store_id='{$farm->id}' and collect_uid='{$this->session->user['id']}'");   
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
                7 => 1,  //粮油
                1 => 0,  //蔬菜
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
        }
       
      

        $user = $this->session->user;
        if(isset($user['id'])){
            
            $messagecount = M\Message::GetMessageUnreadCount($user['id']);
        }else{
            
            $messagecount=0;
        }
        $hotsell=array();
        $hotsell=M\Sell::getHotlist();
       
        $this->usercount = 100;
        $this->shopcount = 100;
        $this->storecount = 100;
        $this->servicecount = 100;
        $this->ordercount = 100;

        $this->view->haeder_cate = $cateData;
        $this->session->farm_user_id = $farm->user_id;
        $nav = strtolower($this->dispatcher->getControllerName());
        $action = strtolower($this->dispatcher->getActionName());
        if(isset($this->session->user["id"])){
            //订单个数
            $ordercount=Orders::getordercount($this->session->user['id']);
            
            //是否有报价
            $quotation=Quotation::count("suserid = ".$this->session->user['id']."");
            $messagecount = M\Message::GetMessageUnreadCount($this->session->user['id']);
        }else{
            $ordercount=0;
            $quotation=0;
            $messagecount=0;
        }
        $hotsell=M\Sell::getHotlist();
        $this->view->hotsell=$hotsell;
        $this->view->userInfo = $this->checkUserInfo();
        $this->view->messagecount = $messagecount ? $messagecount : 0;
        $this->view->quotation = $quotation ? $quotation : 0 ;
        $this->view->ordercount = $ordercount;
        $this->view->farmdesc = $farmdesc;
        $this->view->is_ajax = 0;
        $this->view->is_kx = 1;
        $this->view->farm_header = $user_farm;
        $this->view->farm_logo = $farm;
        $this->view->nav = $nav; 
        $this->view->_nav = 'shou';
        $this->view->action = $action;
        $this->view->collectstore = $CollectStore;
        $this->view->keywords = '农产品价格,农产品价格信息,农产品价格查询,农产品交易网,农产品价格信息网';
        $this->view->descript = '丰收汇全国农产品价格行情信息实时更新,农产品价格走势分析，农产品价格变化指数。';

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
        if(!isset($info->ext->name) || !isset($info->ext->areas_name) ||  !$info->ext->name || !$info->ext->areas_name ) return 1;
        return false;
    }
}
