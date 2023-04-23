<?php
namespace Mdg\Models;
use Lib\Member as Member, Lib\Auth as Auth, Lib\Utils as Utils;
use Lib\Pages as Pages;
use Mdg\Models\UsersExt as Ext;
use Mdg\Models as M;

class UserLog extends \Phalcon\Mvc\Model
{
    /**  
     *
     * @var integer
     */
    
    public $log_id;
    /**
     * 用户id
     * @var integer
     */
    
    public $user_id;
    /**
     *
     * @var integer
     */
    
    public $credit_id;
    /**
     *
     * @var string
     */
    
    public $operate_user_no;
    /**
     *
     * @var string
     */
    
    public $operate_user_name;
    /**
     *
     * @var string
     */
    
    public $operate_time;
    /**
     * 状态
     * @var integer
     */
    
    public $status;
    /**
     *
     * @var string
     */
    
    public $demo = 0;
    /**
     *
     * @var integer
     */
    
    public $add_time;

    
//    public function initialize()
//    {
//    }
}
