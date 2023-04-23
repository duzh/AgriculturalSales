<?php
/**
 * 丰收汇 我的云农宝管理
 *
 */
namespace Mdg\Member\Controllers;
use Mdg\Models\Users as Users;
use Mdg\Models\AreasFull as mAreas;
use Mdg\Models\YncUsers as YncUsers;
use Lib as L;
use Lib\Member as Member;

class AccountController extends ControllerMember
{
    private $ynpurl = YNP_URL;
    
    private $source = 2; // 丰收汇来源
    private $_status = array(
        1 => '支付成功',
        0 => '等待支付',
        3 => '交易完成',
        99 => '交易关闭',
        2=> '支付失败',
        );
    
    /**
     * 云农宝消费
     * @return array
     */
    
    public function indexAction() 
    {


        //检测当前用户是否绑定云农宝
        $flag = $this->checkIsYnp(1);
        
        //获取当前登录用户id
        $user = $this->session->user;
        $data = array();
        $page_size = 10;
        $p = $this->request->get('p', 'int', 1);
        $p = intval($p)>0 ? intval($p) : 1;
        $username = $user['mobile'];
        $searchType = $this->request->get('searchType', 'int', 1);
        $orderStatus = $this->request->get('orderStatus', 'int', 5);
        $orderNum = L\Validator::replace_specialChar($this->request->get('orderNum', 'string', ''));
        $serialNum = L\Validator::replace_specialChar($this->request->get('serialNum', 'string', ''));
        
        $userInfo = 0;
        /**
         * 检测用户信息
         * @var [type]
         */
        $member = new Member();
        $data = $member->getMember($username);

        if(!$data || !$data['user_name'] || !$data['msn'] || !$data['qq'] ) {
            $userInfo = 1 ;
        }
        $uid = $user['id'];
     
        $ThriftInterface = new L\Ynp($this->ynp);
        
        //获取用户详细余额
        $userData = $ThriftInterface->getAmountByUserPhone(
            (string)$username);

        //查询用户交易记录
        $data = $ThriftInterface->client->searchOpsTransactionByUserPhone(
            (string)$username, 
            $this->source, 
            (int)$searchType , 
            $orderStatus, 
            (string)$orderNum, 
            (string)$serialNum, 
            (int)$p, 
            (int)$page_size);
     
        $offst = intval(($p - 1) * $page_size);
        $total = isset($data->totalRecord) ? intval($data->totalRecord) : 0;
        $pages['total_pages'] = ceil($total / $page_size);
        $pages['current'] = $p;
        $pages['total'] = $total;
        $pages = new L\Pages($pages);
        $pages = $pages->show(2);

        //用户是否绑定云农宝
        $this->view->userInfo = $userInfo;
        $this->view->isYnp = $flag;
        $this->view->total_count = ceil($total / $page_size);
        $this->view->pages = $pages;
        $this->view->p = $p;
        $this->view->start  = $offst;
        $this->view->userData = $userData;
        $this->view->data = $data ;
        $this->view->_status = $this->_status;
        $this->view->title = '个人中心-我的余额-丰收汇-高价值农产品交易服务商';

    }

    /**
     * 云农宝收款记录查询
     * @return [type] [description]
     */
    public function searchopstransactionbyselleruserphoneAction() {

        //检测当前用户是否绑定云农宝
        $flag = $this->checkIsYnp(1);
        //获取当前登录用户id
        $user = $this->session->user;
        $data = array();
        $page_size = 10;
        $p = $this->request->get('p', 'int', 1);
        $p = intval($p)>0 ? intval($p) : 1;
        $username = $user['mobile'];
        $searchType = $this->request->get('searchType', 'int', 5);
        $orderStatus = $this->request->get('orderStatus', 'int', 5);
        $orderNum = L\Validator::replace_specialChar($this->request->get('orderNum', 'string', ''));
        $serialNum = L\Validator::replace_specialChar($this->request->get('serialNum', 'string', ''));
        
        $userInfo = 0;
        /**
         * 检测用户信息
         * @var [type]
         */
        $member = new Member();
        $data = $member->getMember($username);
   
        if(!$data || !$data['user_name'] || !$data['msn'] || !$data['qq'] ) {
            $userInfo = 1 ;
        }
        $uid = $user['id'];

        $ThriftInterface = new L\Ynp($this->ynp);
        //获取用户详细余额
        $userData = $ThriftInterface->getAmountByUserPhone(
            (string)$username);

        //查询用户交易记录
        $data = $ThriftInterface->client->searchOpsTransactionBySellerUserPhone(
            (string)$username, 
            $this->source, 
            (int)$searchType, 
            $orderStatus, 
            (string)$orderNum, 
            (string)$serialNum, 
            (int)$p, 
            (int)$page_size);

        $offst = intval(($p - 1) * $page_size);
        $total = isset($data->totalRecord) ? intval($data->totalRecord) : 0;
        $pages['total_pages'] = ceil($total / $page_size);
        $pages['current'] = $p;
        $pages['total'] = $total;
        $this->view->total_count = ceil($total / $page_size);
        $pages = new L\Pages($pages);
        $pages = $pages->show(2);

        //用户是否绑定云农宝
        $this->view->userInfo = $userInfo;
        $this->view->isYnp = $flag;
        $this->view->pages = $pages;
        $this->view->start  = $offst;
        $this->view->userData = $userData;
        $this->view->data = $data ;
        $this->view->_status = $this->_status;
        $this->view->p = $p;
        
        $this->view->title = '个人中心-我的余额-丰收汇-高价值农产品交易服务商';
        
    }
    /**
     * 充值记录
     * @return [type] [description]
     */
    
    public function searchopsdepositAction() 
    {
        //检测当前用户是否绑定云农宝
        $flag = $this->checkIsYnp(1);
        //获取当前登录用户id
        $user = $this->session->user;
        $uid = $user['id'];
        $data = array();
        $page_size = 10;
        $p = $this->request->get('p', 'int', 1);
        $username = $user['mobile'];
        
        $searchType = $this->request->get('searchType', 'int', 0);
        $userInfo = 0;
        /**
         * 检测用户信息
         * @var [type]
         */
        $data = YncUsers::checkmoblie($username);
        
        
        if(!$data || !$data['user_name'] || !$data['msn'] || !$data['qq'] ) {
            $userInfo = 1 ;
        }

        $ThriftInterface = new L\Ynp($this->ynp);
        //查询详细余额
        $userData = $ThriftInterface->YncInteractionServiceClient('YncInteractionService')->getAmountByUserPhone((string)$username);
        $data = $ThriftInterface->YncInteractionServiceClient('YncInteractionService')->searchOpsDepositByUserPhone(
            (string)$username, 
            $this->source = 0,
            (int)$searchType, 
            $p, 
            $page_size);
         $offst = intval(($p - 1) * $page_size);

        $total = isset($data->totalRecord) ? intval($data->totalRecord) : 0;
        $pages['total_pages'] = ceil($total / $page_size);
        $pages['current'] = $p;
        $pages['total'] = $total;
        $pages = new L\Pages($pages);
        $pages = $pages->show(1);
        

        $this->view->userInfo = $userInfo;  
        $this->view->start  = $offst;        
        $this->view->pages = $pages;
        $this->view->isYnp = $flag;
        $this->view->data = $data;
        $this->view->userData = $userData;
        $this->view->title = '个人中心-我的余额-丰收汇-高价值农产品交易服务商';
    }
    /**
     * 提现记录
     * @return [type] [description]
     */
    
    public function searchopswithdrawAction() 
    {
        //检测当前用户是否绑定云农宝
        $flag = $this->checkIsYnp(1);
        //获取当前登录用户id
        
        $uid = $user['id'];
        $data = array();
        $page_size = 10;
        $p = $this->request->get('p', 'int', 1);
        $searchType = $this->request->get('searchType', 'int', 0);
        $user = $this->session->user;
        $username = $user['mobile'];
        $userInfo = 0;
        /**
         * 检测用户信息
         * @var [type]
         */
        $data = YncUsers::checkmoblie($username);
        
        
        if(!$data || !$data['user_name'] || !$data['msn'] || !$data['qq'] ) {
            $userInfo = 1 ;
        }

        $ThriftInterface = new L\Ynp($this->ynp);
        //查询详细余额
        $userData = $ThriftInterface->YncInteractionServiceClient('YncInteractionService')->getAmountByUserPhone((string)$username);
        $data = $ThriftInterface->YncInteractionServiceClient('YncInteractionService')->searchOpsWithdrawByUserPhone((string)
            $username, 
            $this->source = 0, 
            $searchType, 
            $p, 
            $page_size);
        
        $offst = intval(($p - 1) * $page_size);
        $total = isset($data->totalRecord) ? intval($data->totalRecord) : 0;
        $pages['total_pages'] = ceil($total / $page_size);
        $pages['current'] = $p;
        $pages['total'] = $total;
        $pages = new L\Pages($pages);
        $pages = $pages->show(1);

        $this->view->userInfo = $userInfo;
        $this->view->start  = $offst;
        $this->view->pages = $pages;
        $this->view->isYnp = $flag;
        $this->view->data = $data;
        $this->view->userData = $userData;
        $this->view->title = '个人中心-我的余额-丰收汇-高价值农产品交易服务商';
    }
    
    /**
     * 充值
     * @return [type] [description]
     */
    public function rechargeAction() 
    {
        $UmpayClass = new L\UmpayClass();
        $ThriftInterface = new L\Ynp($this->ynp);
        #接口内容包括订单号、订单金额、订单日期（yyyymmdd）、付款人、收款人（默认天辰云农场）、签名
        //创建token
        $mobile = $this->session->user['mobile'];
        $clientip = str_replace('.', '', L\Func::getIP());
        $sign = md5(md5($clientip.$mobile).$UmpayClass->getYncMd5Key());

        $token = $ThriftInterface->createBindToken($clientip, $mobile, $sign);
        
        $url = "{$this->ynpurl}/shopAutoLogin.htm?token={$token}&clientip={$clientip}&where=2";
        header("location: {$url}");
    }
    /**
     * 用户登录云农宝
     * @return [type] [description]
     */
    public function loginYnpAction () {
        $UmpayClass = new L\UmpayClass();
        $ThriftInterface = new L\Ynp($this->ynp);
        
        #接口内容包括订单号、订单金额、订单日期（yyyymmdd）、付款人、收款人（默认天辰云农场）、签名
        //创建token
        $mobile = $this->session->user['mobile'];
        $clientip = str_replace('.', '', L\Func::getIP());
        $sign = md5(md5($clientip.$mobile).$UmpayClass->getYncMd5Key());

        $token = $ThriftInterface->createBindToken($clientip, $mobile, $sign );
        $url = "{$this->ynpurl}/shopAutoLogin.htm?token={$token}&clientip={$clientip}&where=1";
        header("location: {$url}");

    }

    /**
     * 用户提现
     * @return [type] [description]
     */
    public function putAction() 
    {
        $UmpayClass = new L\UmpayClass();
        $ThriftInterface = new L\Ynp($this->ynp);
        
        #接口内容包括订单号、订单金额、订单日期（yyyymmdd）、付款人、收款人（默认天辰云农场）、签名
        //创建token
        $mobile = $this->session->user['mobile'];
        $clientip = str_replace('.', '', L\Func::getIP());
        $sign = md5(md5($clientip.$mobile).$UmpayClass->getYncMd5Key());

        $token = $ThriftInterface->createBindToken($clientip, $mobile, $sign );
        $url = "{$this->ynpurl}/shopAutoLogin.htm?token={$token}&clientip={$clientip}&where=3";
        header("location: {$url}");

    }
}
