<?php
/**
 * @package     Mdg
 * @subpackage  Member
 * @author      zhanggp <2901233237@qq.com>
 * @copyright   2014 YNC365
 * @version     @@PACKAGE_VERSION@@
 */
namespace Mdg\Api\Controllers;
use Mdg\Models as M;
use Lib as L;

class MsgController extends ControllerBase
{
    /**
	 * 获取信息列表
	 * @return string  
	 *  {
	 *    "errorCode": 0,
	 *    "data": [
	 *        {
	 *            "add_time(发布时间)": "2 天前",
	 *            "msg_id(信息id)": "信息id",
	 *            "desc(信息数据)": "<p>您发布的西红柿商品，有人采购啦</p>",
	 *            "name(信息类别)": "系统消息"
	 *        }
	 *    ]
	 * }
	 * <br />
	 * <code>
	 * post 传值 
	 * url http://mdgdev.ync365.com/api/msg/list <br/>
	 * </code>
	 */
    public function listAction() 
    {

        $user_id = parent::getUid();

        $p = $this->request->get('p', 'int', 1);
        $page_size = $this->request->get('psize', 'int', 15);
        $total = M\MessageUsers::count(' is_new = 0 and user_id = ' . $user_id);
        
        $offst = intval(($p - 1) * $page_size);
        $data = M\MessageUsers::find(' is_new = 0 and user_id = ' . $user_id . " ORDER BY add_time DESC limit {$offst} , {$page_size} ")->toArray();
        if(!$data) {
           $this->getMsg(parent::DATA_EMPTY);
        }
        foreach ($data as $key => $value) 
        {
            $message = M\Message::findFirstBymsg_id($value['msg_id']);
            $time = new L\Time(time() , $value['add_time']);
            $datas[$key]['add_time'] = $time->transform();
            $datas[$key]["msg_id"]=$value["msg_id"];     
            if (!$message) 
            {
                unset($data[$key]);
            }
            else
            {
                
                if (!empty($message->order_id)) 
                {
                    $order = M\Orders::findFirstByid($message->order_id);
                }
                
                if (!empty($message->status) && $message->status == 0) 
                {
                    unset($data[$key]);
                }
                else
                {
                    
                    if ($message->type == 1) 
                    {
                        $datas[$key]['desc'] = "供应信息推荐：{$message->buyer_name}推出了新的供应";
                        $datas[$key]['name'] = "采购推荐";
                    }
                    elseif ($message->type == 2) 
                    {
                        $datas[$key]['desc'] = "采购信息推荐：{$message->buyer_name}需要采购<font>{$message->goods_name}</font>商品";
                        $datas[$key]['name'] = "供应推荐";
                    }
                    else
                    {
                        
                        if ($message->link_type == 2) 
                        {
                            $datas[$key]['desc'] = "" . $message->comment . "";
                            $datas[$key]['name'] = "系统消息";
                        }
                        elseif ($message->link_type == 1) 
                        {
                            
                            if ($order->state == 1) 
                            {
                                $datas[$key]['desc'] = "" . $message->comment . "";
                                $datas[$key]['name'] = "系统消息";
                            }
                            else
                            {
                                $datas[$key]['desc'] = "" . $message->comment . "";
                                $datas[$key]['name'] = "系统消息";
                            }
                            $datas[$key]['name'] = "系统消息";
                        }
                        elseif ($message->link_type == 0) 
                        {
                            $datas[$key]['desc'] = "" . $message->comment . "";
                            $datas[$key]['name'] = "系统消息";
                        }
                        else 
                        if ($message->link_type == 3) 
                        {
                            
                            if ($order->state == 1) 
                            {
                                $datas[$key]['desc'] = "" . $message->comment . "";
                                $datas[$key]['name'] = "系统消息";
                            }
                            else
                            {
                                $datas[$key]['desc'] = "" . $message->comment . "";
                                $datas[$key]['name'] = "系统消息";
                            }
                            $datas[$key]['name'] = "系统消息";
                        }
                        else
                        {
                            
                            if ($order->state == 1) 
                            {
                                $datas[$key]['desc'] = "" . $message->comment . "";
                                $datas[$key]['name'] = "系统消息";
                            }
                            else
                            {
                                $datas[$key]['desc'] = "" . $message->comment . "";
                                $datas[$key]['name'] = "系统消息";
                            }
                        }
                    }
                }
            }
        }

        $this->getMsg(parent::SUCCESS,$datas);
       
    }
    
    public function infoAction($msg_id = '') 
    {
        
        if (!$msg_id) 
        {
            echo "<script>alert('信息错误，请联系管理员');location.href='/member/messageuser/list'</script>";
            exit;
        }
        $message = M\Message::findFirstBymsg_id($msg_id);
        
        if (!$message) 
        {
            echo "<script>alert('信息错误，请联系管理员');location.href='/member/messageuser/list'</script>";
            exit;
        }
        $messageusers = M\MessageUsers::findFirstBymsg_id($msg_id);
        $messageinspect = M\MessageInspect::findFirstBymsg_id($msg_id);
        $messageusers->is_new = 1;
        $messageusers->save();
        $this->view->messageinspect = $messageinspect;
        $this->view->message = $message;
    }
    
    public function listinfoAction() 
    {
        $user_id = $this->session->user['id'];
        $page_size = '15';
        $p = $this->request->get('p', 'int', '1');
        $total = M\MessageUsers::count('is_new = 1 and user_id = ' . $user_id);
        $offst = intval(($p - 1) * $page_size);
        $data = M\MessageUsers::find('is_new = 1 and user_id = ' . $user_id . " ORDER BY add_time DESC limit {$offst} , {$page_size} ")->toArray();
        
        foreach ($data as $key => $value) 
        {
            $message = M\Message::findFirstBymsg_id($value['msg_id']);
            
            if (!$message) 
            {
                unset($data[$key]);
            }
            else
            {
                
                if (!empty($message->order_id)) 
                {
                    $order = M\Orders::findFirstByid($message->order_id);
                }
                
                if ($message->status == 0) 
                {
                    unset($data[$key]);
                }
                else
                {
                    
                    if ($message->type == 1) 
                    {
                        $data[$key]['desc'] = "<p>供应信息推荐：{$message->buyer_name}推出了新的供应商品，点击<a href='/member/messageuser/info/{$message->msg_id}'>查看详情</a>";
                        $data[$key]['name'] = "采购推荐";
                    }
                    elseif ($message->type == 2) 
                    {
                        $data[$key]['desc'] = "<p>采购信息推荐：{$message->buyer_name}需要采购<font>{$message->goods_name}</font>商品，<a href='/member/messageuser/info/{$message->msg_id}'>查看详情</a></p>";
                        $data[$key]['name'] = "供应推荐";
                    }
                    else
                    {
                        
                        if (isset($order->state) && $order->state != 1) 
                        {
                            $dess = "点击查看";
                        }
                        else
                        {
                            $dess = '';
                        }
                        
                        if ($message->link_type == 2) 
                        {
                            $data[$key]['desc'] = "<p>" . $message->comment . ", <a href='/member/messageuser/info/{$message->msg_id}'>点击查看</a></p>";
                            $data[$key]['name'] = "系统消息";
                        }
                        elseif ($message->link_type == 1) 
                        {
                            
                            if ($order->state == 1) 
                            {
                                $data[$key]['desc'] = "<p>" . $message->comment . ", <a href='/member/orderssell/index'>$dess</a></p>";
                                $data[$key]['name'] = "系统消息";
                            }
                            else
                            {
                                $data[$key]['desc'] = "<p>" . $message->comment . ", <a href='/member/orderssell/info/{$message->order_id}'>$dess</a></p>";
                                $data[$key]['name'] = "系统消息";
                            }
                            $data[$key]['name'] = "系统消息";
                        }
                        elseif ($message->link_type == 0) 
                        {
                            $data[$key]['desc'] = "<p>" . $message->comment . ", <a href='/member/purchase/quolist?purid={$message->offer_id}'>点击查看</a></p>";
                            $data[$key]['name'] = "系统消息";
                        }
                        else 
                        if ($message->link_type == 3) 
                        {
                            
                            if ($order->state == 1) 
                            {
                                $data[$key]['desc'] = "<p>" . $message->comment . ", <a href='/member/ordersbuy/index'>$dess</a></p>";
                                $data[$key]['name'] = "系统消息";
                            }
                            else
                            {
                                $data[$key]['desc'] = "<p>" . $message->comment . ", <a href='/member/ordersbuy/info/{$message->order_id}'>$dess</a></p>";
                                $data[$key]['name'] = "系统消息";
                            }
                            $data[$key]['name'] = "系统消息";
                        }
                        else
                        {
                            
                            if ($order->state == 1) 
                            {
                                $data[$key]['desc'] = "<p>" . $message->comment . ", <a href='/member/orderssell/index'>$dess</a></p>";
                                $data[$key]['name'] = "系统消息";
                            }
                            else
                            {
                                $data[$key]['desc'] = "<p>" . $message->comment . ", <a href='/member/orderssell/info/{$message->order_id}'>$dess</a></p>";
                                $data[$key]['name'] = "系统消息";
                            }
                        }
                    }
                }
            }
        }
        $pages['total_pages'] = ceil($total / $page_size);
        $pages['current'] = $p;
        $pages['total'] = $total;
        $pages = new L\Pages($pages);
        $datas['total'] = $total;
        $datas['items'] = $data;
        $datas['start'] = $offst;
        $datas['pages'] = $pages->show(1);
        $this->view->current = $p;
        $this->view->data = $datas;
    }
}


