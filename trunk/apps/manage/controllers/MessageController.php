<?php
namespace Mdg\Manage\Controllers;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Mdg\Models as M;
use Lib\Func as Func;

class MessageController extends ControllerBase
{
    /**
     * 消息列表
     * @return [type] [description]
     */
    public function indexAction() 
    {
        $page_size = '10';
        $where = array(
            '1'
        );
        $info_type = $this->request->get('info_type', 'int', '0');
        $statue = $this->request->get('statue', 'int', '0');
        $st = $this->request->get('st', 'string', '');
        $et = $this->request->get('et', 'string', '');
        $p = $this->request->get('p', 'int', '1');
        
        if ($st && $et) 
        {
            $start_time = strtotime($st . '00:00:00');
            $end_time = strtotime($et . '23:59:59');
            $where[] = " add_time BETWEEN '{$start_time}' AND '{$end_time}' ";
        }
        
        $where[] = "type !=0";
        if (!empty($info_type)) 
        {
            $where[] = " type = {$info_type}";
        }
        
        if (!empty($statue)) 
        {
            $statue = $statue == '2' ? 0 : 1;
            $where[] = "status = $statue";
        }

        $where = implode(' and ', $where);
        $data = M\Message::GetMessageList($p, $where, $page_size);

        $start = !empty($st) ? $st : date("Y-m-d", time());
        $end = !empty($et) ? $et : date("Y-m-d", time());
        $this->view->start = $start;
        $this->view->end = $end;
        $this->view->current = $p;
        $this->view->data = $data;
    }
    /**
     * 新增消息
     */
    
    public function newAction($id = 0) 
    {
        
        if (!$id) 
        {
            if ($this->session->message['message']) 
            {
                unset($this->session->message);
            }
        }
    }
    /**
     * 新增消息保存
     */
    
    public function new_sessionAction($type = 1) 
    {
        
        if ($type == 1) 
        {
            $arr['theme'] = $this->request->getPost('theme', 'string', '#');
            $arr['type'] = '采购推荐';
            $arr['buyer_name'] = $this->request->getPost('buyer_name', 'string', '#');
            $arr['buyer_contact'] = $this->request->getPost('buyer_contact', 'string', '#');
            $arr['buyer_phone'] = $this->request->getPost('buyer_phone', 'string', '#');
            $arr['buyer_goods'] = $this->request->getPost('buyer_goods', 'string', '#');
            $arr['buyer_desc'] = $this->request->getPost('buyer_desc', 'string', '#');
        }
        else
        {
            $arr['themee'] = $this->request->getPost('themee', 'string', '#');
            $arr['type'] = '供应推荐';
            $arr['supplier_name'] = $this->request->getPost('supplier_name', 'string', '#');
            $arr['supplier_contact'] = $this->request->getPost('supplier_contact', 'string', '#');
            $arr['supplier_phone'] = $this->request->getPost('supplier_phone', 'string', '#');
            $arr['supplier_goods'] = $this->request->getPost('supplier_goods', 'string', '#');
            $arr['supplier_desc'] = $this->request->getPost('supplier_desc', 'string', '#');
        }
        
        if (isset($this->session->message['message'])) 
        {
            unset($this->session->message);
        }

        $this->session->message = array('message'=>$arr);
        echo "<script>location.href='/manage/message/new_view'</script>";
        exit;
    }
    /**
     * 新增消息第二步
     */
    
    public function new_viewAction() 
    {
        $user_identity = '';
        $where = array(
            1
        );
        $wherearr = array(
            1
        );
        $page_size = '20';
        $p = $this->request->get('p', 'int', '1');
        $user_type = $this->request->get('user_type', 'int', '');
        $pro = explode(',', $this->request->get('province', 'string', '#'));
        $city = explode(',', $this->request->get('city', 'string', '#'));
        $county = explode(',', $this->request->get('county', 'string', '#'));
        $goods = $this->request->get('goods', 'string', '');
        //采购商
        
        if ($user_type == 0) 
        {
            
            if (isset($province['1'])) 
            {
                $where[] = " areas_name like '%" . $province['1'] . "%'";
            }
            
            if (isset($city['1'])) 
            {
                $where[] = " areas_name like '%" . $city['1'] . "%'";
            }
            
            if (isset($county['1'])) 
            {
                $where[] = " areas_name like '%" . $county['1'] . "%'";
            }
            
            if ($goods) 
            {
                $where[] = "goods like '%" . $goods . "%'";
            }
            $where = implode(' and ', $where);
            $data = M\Message::GetUserList($p, $where, $page_size, 2); //获取采购商列表
            //供应商
            
        }
        else
        {
            $user_identity = $this->request->get('user_identity', 'int', '0');
            //供应商-》普通用户
            
            if ($user_identity == 0) 
            {
                
                if (isset($province['1'])) 
                {
                    $where[] = " areas_name like '%" . $province['1'] . "%'";
                }
                
                if (isset($city['1'])) 
                {
                    $where[] = " areas_name like '%" . $city['1'] . "%'";
                }
                
                if (isset($county['1'])) 
                {
                    $where[] = " areas_name like '%" . $county['1'] . "%'";
                }
                
                if ($goods) 
                {
                    $where[] = "goods like '%" . $goods . "%'";
                }
                $where = implode(' and ', $where);
                $data = M\Message::GetUserList($p, $where, $page_size, 1); //获取普通用户列表
               
                
            }
             //可信农场
/*            else 
            if ($user_identity == 2) 
            {
                $where[] = " is_service = 0 "; //0 = 店铺
                
                if (isset($county['1'])) 
                {
                    $where[] = "county_id = {$county['0']}";
                }
                else
                {
                    
                    if (isset($province['1'])) 
                    {
                        $where[] = "province_id = {$province['0']}";
                    }
                    
                    if (isset($city['1'])) 
                    {
                        $where[] = " city_id = {$city['0']}";
                    }
                }
                
                if ($goods) 
                {
                    $wherearr = " goods_name = '{$goods}'";
                    $cond[] = $wherearr;
                    $cond['columns'] = " distinct shop_id";
                    $shopgoods = M\ShopCoods::find($cond)->toArray();
                    $gid = join(',', array_column($shopgoods, 'shop_id'));
                    
                    if ($shopgoods) 
                    {
                        $where[] = " shop_id in ({$gid})";
                    }
                }
                $where = implode(' and ', $where);
                $data = M\Message::GetShopNoServiceList($p, $where, $page_size, 1);
                //服务站
                
            }*/
            else
            {
                $where[] = " is_service = 1 "; //1 =服务站
                
                if (isset($county['1'])) 
                {
                    $where[] = "county_id = {$county['0']}";
                }
                else
                {
                    
                    if (isset($province['1'])) 
                    {
                        $where[] = "province_id = {$province['0']}";
                    }
                    
                    if (isset($city['1'])) 
                    {
                        $where[] = " city_id = {$city['0']}";
                    }
                }
                
                if ($goods) 
                {
                    $wherearr = " goods_name = '{$goods}'";
                    $cond[] = $wherearr;
                    $cond['columns'] = " distinct shop_id";
                    $shopgoods = M\ShopCoods::find($cond)->toArray();
                    $gid = join(',', array_column($shopgoods, 'shop_id'));
                    
                    if ($shopgoods) 
                    {
                        $where[] = " shop_id in ({$gid})";
                    }
                }
                $where = implode(' and ', $where);
                $data = M\Message::GetShopNoServiceList($p, $where, $page_size, 2);
            }
        }
        $this->view->goods = $goods;
        $this->view->user_type = $user_type;
        
        if (!isset($pro['1'])) 
        {
            $pro['1'] = '';
        }
        
        if (!isset($city['1'])) 
        {
            $city['1'] = '';
        }
        
        if (!isset($county['1'])) 
        {
            $county['1'] = '';
        }
        $this->view->vvv = "'{$pro['1']}','{$city['1']}','{$county['1']}'";
        $this->view->current = $p;
        $this->view->user_identity = $user_identity;
        $this->view->data = $data;
    }
    
    /**
     * 新增消息保存
     */
    public function newsaveAction() 
    {
        $users_type = $this->request->get('type');
        $fasong = $this->request->get('fasong', 'string', '');
        $type_id = $this->request->get('type_id');

        $type = $fasong == "发送推荐" ? 1 : 0; //1已发送 0未发送
        $message = new M\Message();
        
        if (isset($this->session->message['message']['theme'])) 
        {
            $message->comment = $this->session->message['message']['theme'];
            $message->order_id = 0;
            $message->goods_name = $this->session->message['message']['buyer_goods'];
            $message->buyer_name = $this->session->message['message']['buyer_name'];
            $message->contact_man = $this->session->message['message']['buyer_contact'];
            $message->offer_id = 0;
            $message->type = 1;
            $message->contact_phone = $this->session->message['message']['buyer_phone'];
            $message->require = $this->session->message['message']['buyer_desc'];
            $message->status = $type;
            $message->add_time = time();
            $message->last_update_time = time();
            $message->link_type = 0;
        }
        else
        {
            $message->comment = $this->session->message['message']['themee'];
            $message->order_id = 0;
            $message->goods_name = $this->session->message['message']['supplier_goods'];
            $message->buyer_name = $this->session->message['message']['supplier_name'];
            $message->contact_man = $this->session->message['message']['supplier_contact'];
            $message->offer_id = 0;
            $message->type = 2;
            $message->contact_phone = $this->session->message['message']['supplier_phone'];
            $message->require = $this->session->message['message']['supplier_desc'];
            $message->status = $type;
            $message->add_time = time();
            $message->last_update_time = time();
            $message->link_type = 0;
        }
        $message->save();

        foreach ($type_id as $key => $value) 
        {
            if($users_type[$key]=='服务站'){
                $users_type = 1;
            }elseif($users_type[$key]=='普通用户'){
                $users_type = 0;
            }else{
                $users_type = 2;
            }
            $messageusers = new M\MessageUsers();
            $messageusers->msg_id = $message->msg_id;
            $messageusers->user_id = $value;
            $messageusers->is_new = 0;
            $messageusers->add_time = time();
            $messageusers->last_update_time = time();
            $messageusers->type = $users_type;
            $messageusers->save();
        }
        Func::adminlog("新增消息：{$message->msg_id}",$this->session->adminuser['id']);
        echo "<script>alert('操作成功');location.href='/manage/message/index'</script>";exit;
    }
    
    /**
     * 查看消息
     * @param  [type] $msg_id [description]
     * @return [type]         [description]
     */
    public function infoAction($msg_id) 
    {

        if (!$msg_id) 
        {
            echo "<script>alert('来源错误');location.href='/manage/message/index'</script>";
            exit;
        }
        $message = M\Message::findFirstBymsg_id($msg_id);
        if (!$message) 
        {
            echo "<script>alert('信息错误，请联系管理员');location.href='/manage/message/index'</script>";
            exit;
        }
        $messageusers = M\MessageUsers::find("msg_id = ".$msg_id)->toArray();

        foreach ($messageusers as $key => $value) {
            $users=M\Users::findFirstByid($value['user_id']);
            $usersext=M\UsersExt::findFirstByuid($value['user_id']);
            $messageusers[$key]['phone'] = $users->username;
            $messageusers[$key]['name'] = $usersext->name;
            $messageusers[$key]['areas'] = str_replace(",", ",", $usersext->areas_name);
            $messageusers[$key]['goods'] = $usersext->goods;
            $messageusers[$key]['info_type'] = $usersext->name;

        }
        $this->view->message = $message;
        $this->view->messageusers = $messageusers;
    }
    /**
     *  审核消息
     * @return [type] [description]
     */
    public function updatestatussaveAction(){
        $msg_id=$this->request->get('msg_id','int','0');
        $message=M\Message::findFirstBymsg_id($msg_id);
        $message->status = 1;
        $message->save();
        Func::adminlog("审核消息：{$message->msg_id}",$this->session->adminuser['id']);
        echo "<script>alert('发送成功');location.href='/manage/message/index'</script>";exit;
    }
}
?>