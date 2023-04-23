<?php
namespace Mdg\Models;
use Mdg\Models as M;
use Lib as L;

class Message extends \Phalcon\Mvc\Model
{
    //用户类型
    static $_user_type = array(
        0 => '采购商',
        1 => '供应商'
    );
    //用户身份
    static $_user_identity = array(
        0 => '普通用户',
        1 => '服务站',
        2 => '可信农场'
    );
    static $_type = array(
        0=>'系统消息',
        1=>'采购推荐',
        2=>'供应推荐');
    static $_is_status = array(
        0=>'未发送',
        1=>'已发送');
    static $is_new = array(
        0=>'未查看',
        1=>'已查看');
    /**
     *
     * @var integer
     * 消息ID
     */
    
    public $msg_id = 0;
    /**
     *
     * @var integer
     * 用户ID
     */
    
    public $user_id = 0;
    /**
     *
     * @var integer
     * 消息内容(主题)
     */
    
    public $comment = 0;
    /**
     *
     * @var integer
     * 订单ID
     */
    
    public $order_id = 0;
    /**
     *
     * @var integer
     * 采购商品名称
     */
    
    public $goods_name = 0;
    /**
     *
     * @var integer
     * 采购商名称
     */
    
    public $buyer_name = 0;
    /**
     *
     * @var integer
     *  联系人
     */
    
    public $contact_man = 0;
    /**
     *
     * @var integer
     * 联系电话
     */
    
    public $contact_phone = 0;
    /**
     *
     * @var integer
     * 采购要求
     */
    
    public $require = 0;
    /**
     *
     * @var integer
     * 报价ID
     */
    
    public $offer_id = 0;
    /**
     *
     * @var integer
     * 类型：0 系统消息 1 采购推荐 2供应推荐
     */
    
    public $type = 0;
    /**
     *
     * @var integer
     * 发送状态：0 未发送 1已发送
     */
    
    public $status = 0;
    /**
     *
     * @var integer
     * 创建时间
     */
    
    public $add_time = 0;
    /**
     *
     * @var integer
     * 最后更新时间
     */
    
    public $last_update_time = 0;
    /**
     * 消息列表带分页
     * @param  [type] $p     [description]
     * @param  [type] $where [description]
     * @return [type]        [description]
     */
    static 
    public function GetMessageList($p = '1', $where = '', $page_size = '20') 
    {
        $total = self::count($where);
        $offst = intval(($p - 1) * $page_size);
        $data = self::find($where . "  ORDER BY add_time DESC limit {$offst} , {$page_size} ")->toArray();
        foreach ($data as $key => $value) {
              $message=M\Message::findFirstBymsg_id($value['msg_id']);
              $data[$key]['comment'] = $message->comment;
              $data[$key]['info_type'] = self::$_type[$message->type];
              $data[$key]['status'] = $message->status;
        }
      
        $pages['total_pages'] = ceil($total / $page_size);
        $pages['current'] = $p;
        $pages['total'] = $total;
        $pages = new L\Pages($pages);
        $datas['total'] = $total;
        $datas['items'] = $data;
        $datas['start'] = $offst;
        $datas['pages'] = $pages->show(1);
        return $datas;
    }
    static 
    public function GetPurchaseList($p = '1', $where = '', $page_size = '20') 
    {
        $total = M\Purchase::count($where);
        $offst = intval(($p - 1) * $page_size);
        $data = M\Purchase::find($where . "ORDER BY createtime DESC limit {$offst} , {$page_size}")->toArray();
        $pages['total_pages'] = ceil($total / $page_size);
        $pages['current'] = $p;
        $pages['total'] = $total;
        $pages = new L\Pages($pages);
        $datas['total'] = $total;
        $datas['items'] = $data;
        $datas['start'] = $offst;
        $datas['pages'] = $pages->show(1);
        return $datas;
    }
    /**
     * 用户列表
     * @param string $p         [description]
     * @param string $where     [description]
     * @param string $page_size [description]
     * @param string $type      [1为普通用户  2为采购商]
     */
    static 
    public function GetUserList($p = '1', $where = '', $page_size = '20', $type = '1') 
    {
        $cond[] = $where;
        $cond['columns'] = 'uid';
        $usersExt = M\UsersExt::find($cond)->toArray();
        $where = ' usertype = ' . $type;
        
        if (empty($usersExt)) 
        {
            $where.= ' and id = 0';
        }
        else
        {
            $id = join(',', array_column($usersExt, 'uid'));
            $where.= " and id in ({$id})";
        }
        $total = M\Users::count($where);
        $offst = intval(($p - 1) * $page_size);
        $data = M\Users::find($where . " ORDER BY id DESC limit {$offst} , {$page_size}")->toArray();
        if ($data) 
        {
            
            foreach ($data as $key => $value) 
            {
                $users = M\UsersExt::findFirst(' uid = ' . $value['id']);
                
                if (!$users) 
                {
                    unset($data[$key]);
                }
                else
                {
                    $data[$key]['check_id'] = $value['id'];
                    $data[$key]['name'] = $users->name;
                    $data[$key]['phone'] = $value['username'];
                    $data[$key]['goods'] = $users->goods;
                    $data[$key]['area'] = str_replace(",", ",", $users->areas_name);
                    $data[$key]['type'] = $type == 1 ? '普通用户' : '可信农场';
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
        return $datas;
    }
    /**
     * 服务站列表
     * @param string $p         [description]
     * @param string $where     [description]
     * @param string $page_size [description]
     */
    static 
    public function GetShopNoServiceList($p = '1', $where = '', $page_size = '10',$type) 
    {
        $total = M\Shop::count($where);
        $offst = intval(($p - 1) * $page_size);
        $data = M\Shop::find($where . " ORDER BY shop_id DESC limit {$offst} , {$page_size}")->toArray();
        
        if ($data) 
        {
            
            foreach ($data as $key => $value) 
            {  
                $cond = array();
                $cond[] = "shop_id = {$value['shop_id']}";
                $cond['columns'] = " goods_name";

                $shop = M\ShopCoods::find($cond)->toArray();

                if (!$shop) 
                {
                    unset($data[$key]);
                }
                else
                {
                    
                    if (empty($value['village_id'])) 
                    {
                        $area = '无';
                    }
                    else
                    {
                        $area = M\AreasFull::getFamily($value['village_id']);
                        
                        if ($area) 
                        {
                            $area = $area[0]['name'] . $area[1]['name'] . $area[2]['name'];
                        }
                        else
                        {
                            $area = '无';
                        }
                    }
                    $data[$key]['check_id'] = $value['user_id'];
                    $shop = join(',', array_column($shop, 'goods_name'));
                    $data[$key]['name'] = $value['contact_man'];
                    $data[$key]['phone'] = $value['contact_phone'];
                    $data[$key]['goods'] = $shop;
                    $data[$key]['area'] = $area;
                    $data[$key]['type'] = $type==1 ? '可信农场' : '服务站';
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
        return $datas;
    }

    /**
     *  保存消息
     * @param  integer $uid         用户id
     * @param  integer $offer_id    对应id
     * @param  integer $type    类型
     * @param  string  $goods_name  商品名称
     * @param  string  $buyer_name  采购商名称
     * @param  string  $contact_phone 联系人手机号
     * @param  string  $contact_man 联系人
     * @param  string  $require     要求
     * @param  integer $oid    订单id
     * 
     * @return [type]               [description]
     */
    public static function saveMessages($info) {
            
            $message  = new self();
            $message->order_id         = $info['oid'];
            $message->goods_name       = $info['goods_name'];
            $message->buyer_name       = $info['buyer_name'];
            $message->contact_phone    = $info['contact_phone'];
            $message->contact_man      = $info['contact_man'];
            $message->require          = $info['require'];
            $message->offer_id         = $info['offer_id'];
            $message->type             = 0;
            $message->status           = 1;
            $message->add_time         = time();
            $message->last_update_time = time();
            $message->comment = $info['comment'];
            $message->link_type = 2;
            if(!$message->save()) {
                    var_dump($message->getMessages());
            }
            $messageinspect=new M\MessageInspect();
            $messageinspect->msg_id = $message->msg_id;
            $messageinspect->add_time = time();
            $messageinspect->last_update_time = time();
            $messageinspect->buy_qty = $info['buy_qty'];
            $messageinspect->address = $info['address'];
            $messageinspect->spec = $info['spec'];
            $messageinspect->save();

            $messageusers=new M\MessageUsers();
            $messageusers->msg_id = $message->msg_id;
            $messageusers->user_id = $info['uid'];
            $messageusers->is_new = 0;
            $messageusers->add_time = time();
            $messageusers->last_update_time = time();
            $messageusers->type = 0;
            if($messageusers->save()){
                var_dump($message->getMessages());
            }
            return $message->msg_id;

    }


    /**
     * 后台推荐内容发送
     * @param  string $source    来源
     * @param  [type] $uname     对应人
     * @param  [type] $goodsname 商品名称
     * @param  [type] $mobile    手机号
     * @return string
     */
    public static function getAfterComment ($source='', $uname, $mobile, $goodsname) {
        
        $sourceaArr = array('seen','purchase','sell');
        if(!in_array($source, $sourceaArr)) return '';

        switch ($source) {
            case 'seen':
                $tpl = "申请实地看货，联系人电话：%s，联系人姓名：%s";
                $tpl = sprintf($tpl, $mobile, $uname);
                break;
            case 'purchase': 
                $tpl = "供应信息推荐：%s推出了新的供应商品，点击查看详情";
                $tpl = sprintf($tpl, $uname);

                break;
            case 'sell':
                $tpl = "采购信息推荐：%s需要采购%s商品，点击查看详情";
                $tpl = sprintf($tpl, $uname, $goodsname);
                break;
        }
        return $tpl;
    }

    /**
     * 获取前台发送内容
     * @param  string $source    来源 orders 订单 purchase 采购， sell 供应， ordersconfirm       
     * @param  [type] $uname     源始人 
     * @param  [type] $mobile    手机号    
     * @param  [type] $goodsname 商品名称
     * @return [type]            [description]
     */
    public static function getFrontComment($source='', $uname='', $mobile='', $goodsname='') {

        $sourceaArr = array('orders','purchase','sell','ordersconfirm');
        if(!in_array($source, $sourceaArr)) return '';

        switch ($source) {
            case 'sellconfirm':
                $tpl = "您提交的采购，供应商确定了，点击查看。";
                $tpl = sprintf($tpl, $goodsname);
                break;
            case 'purchase': 
                $tpl = "您发布的%s 采购，有人报价啦，点击查看";
                $tpl = sprintf($tpl, $goodsname);
                break;
            case 'sell':
                $tpl = "您发布的%s 商品，有人采购啦，点击查看";
                $tpl = sprintf($tpl,$goodsname);
                break;
            case 'ordersconfirm':
                $tpl = "您提交的采购，供应商确定了，点击查看。";
                $tpl = sprintf($tpl,$goodsname);
                break;
        }
        return $tpl;
    }

    /**
     * 采购消息插入
     * @param [type] $info [description]
     */
    public static function GetInsertMessage($info){
        $message=new M\Message();
        $message->comment = $info['comment'];
        $message->order_id = $info['order_id'];
        $message->type = 0;
        $message->status = 1;
        $message->add_time = time();
        $message->last_update_time = time();
        $message->link_type = 1;
        $message->save();
        $messageusers=new M\MessageUsers();
        $messageusers->msg_id = $message->msg_id;
        $messageusers->user_id = $info['user_id'];
        $messageusers->is_new = 0;
        $messageusers->add_time = time();
        $messageusers->last_update_time = time();
        $messageusers->type = 0;
        $messageusers->save();
    }
    /**
     * 报价消息插入
     * @param [type] $info [description]
     */
    public static function GetInsertOffer($info){
        $message=new M\Message();
        $message->comment = $info['comment'];
        $message->order_id = 0;
        $message->type = 0;
        $message->status = 1;
        $message->offer_id = $info['offer_id'];
        $message->add_time = time();
        $message->last_update_time = time();
        $message->link_type = 0;
        $message->save();
        $messageusers=new M\MessageUsers();
        $messageusers->msg_id = $message->msg_id;
        $messageusers->user_id = $info['user_id'];
        $messageusers->is_new = 0;
        $messageusers->add_time = time();
        $messageusers->last_update_time = time();
        $messageusers->type = 0;
        $messageusers->save();
    }

    public static function GetInsertPurchase($info){
        $message=new M\Message();
        $message->comment = $info['comment'];
        $message->order_id = $info['order_id'];
        $message->type = 0;
        $message->status = 1;
        $message->offer_id = 0;
        $message->add_time = time();
        $message->last_update_time = time();
        $message->link_type = 3;
        $message->save();
        $messageusers=new M\MessageUsers();
        $messageusers->msg_id = $message->msg_id;
        $messageusers->user_id = $info['user_id'];
        $messageusers->is_new = 0;
        $messageusers->add_time = time();
        $messageusers->last_update_time = time();
        $messageusers->type = 0;
        $messageusers->save();
    }

    public static function GetInsertPurchaseOrder($info){

        $message=new M\Message();
        $message->comment = $info['comment'];
        $message->order_id = $info['order_id'];
        $message->type = 0;
        $message->status = 1;
        $message->offer_id = 0;
        $message->add_time = time();
        $message->last_update_time = time();
        $message->link_type = 4;
        $message->save();
        $messageusers=new M\MessageUsers();
        $messageusers->msg_id = $message->msg_id;
        $messageusers->user_id = $info['user_id'];
        $messageusers->is_new = 0;
        $messageusers->add_time = time();
        $messageusers->last_update_time = time();
        $messageusers->type = 0;
        $messageusers->save();
    }

    public static function GetMessageUnreadCount($user_id=0){
        $total = M\MessageUsers::find("is_new = 0 and user_id = {$user_id}")->toArray();
       
        foreach ($total as $key => $value) {
            $message=M\Message::findFirstBymsg_id($value['msg_id']);
            if(!empty($message->status) && $message->status==0){
                    unset($total[$key]);
                }
        }

        $total=count($total);
        return $total;
    }

}
