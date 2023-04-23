<?php
namespace Mdg\Models;

use Mdg\Models\AdminRolesPermission as ARP,
    Mdg\Models\AdminPermission      as APermission,
    Lib\Member as Member,
    Lib\Auth as Auth,
    Lib\Utils as Utils,
    Lib\Func as Func;
class Admin extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id=0;

    /**
     *
     * @var string
     */
    public $username='';

    /**
     *
     * @var string
     */
    public $password='';

    /**
     *
     * @var string
     */
    public $salt='';

    /**
     *
     * @var string
     */
    public $lastlogintime=0;

    /**
     *
     * @var string
     */
    public $lastloginip='';

    /**
     *
     * @var integer
     */
    public $logincount=0;

    /**
     *
     * @var string
     */
    public $createtime=0;

    /**
     *
     * @var integer
     */
    public $role_id=0; 
    /**
     * 判断是否有权限路径
     * @param  integer $role_id    [description]
     * @param  string  $controller [description]
     * @param  string  $action     [description]
     * @return [type]              [description]
     */
    static function checkRole($role_id=0, $controller='', $action='') {
        
        $actList = Func::getObjCols(ARP::find("role_id='{$role_id}'"), 'permission_id');

        $cur = APermission::findFirst("controller='{$controller}' and action='{$action}'");
        
        if(!in_array($cur->permission_id, $actList)){
            return "yes";
        }else{
            return "no";
        }
       
    }

    /**
     * 验证登陆
     * @param  string $mobile [description]
     * @param  string $pwd    [description]
     * @return [type]         [description]
     */
    static function validateLogin($mobile='', $pwd='') {
          $user=self::findFirst("username='{$mobile}'");
          
          if($user && $user->password==self::encodePwd($pwd,$user->salt)){
            self::syncUser($user->id);
            return $user; 
          }else{
            return false;
          }

    }
    /**
     * 修改登陆用户的信息
     * @param  [type] $userid [description]
     * @return [type]         [description]
     */
    static function syncUser($userid) {
            $users=self::findFirstByid($userid);
            $users->lastlogintime = date("Y-m-d H:i:s",time());
            $users->logincount += 1;
            $users->lastloginip = Utils::getIP();
            $users->save();  
    }
    /**
     * 密码加密
     * @param  [type] $pwd  [description]
     * @param  [type] $salt [description]
     * @return [type]       [description]
     */
    static function encodePwd($pwd,$salt){
       return md5(md5($pwd).$salt);
    }
}
