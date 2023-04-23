<?php
namespace Mdg\Models;
use Lib\Utils as Utils;
use Lib\Func as Func;
class YncAdmin extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $username;

    /**
     *
     * @var string
     */
    public $password;

    /**
     *
     * @var string
     */
    public $salt;

    /**
     *
     * @var string
     */
    public $lastlogintime;

    /**
     *
     * @var string
     */
    public $lastloginip;

    /**
     *
     * @var integer
     */
    public $logincount;

    /**
     *
     * @var string
     */
    public $createtime;

    /**
     *
     * @var integer
     */
    public $gid;

    /**
     *
     * @var integer
     */
    public $role_id;
    /**
     *
     * @var integer
     */
    public $state;
    /**
     *
     * @var integer
     */
    public $branch;

    static $admin_state = array(
        "0" => '冻结',
        "1" => '激活',
    );
    static $stateflag = array(
        "0" => '激活',
        "1" => '冻结'
    );
    public function getSource()
    {
        return "admin";
    }
    
    public function initialize(){
        $this->setConnectionService('permission');
        $this->useDynamicUpdate(true);
    }

    /**
     * 判断是否有权限路径
     * @param  integer $role_id    [description]
     * @param  string  $controller [description]
     * @param  string  $action     [description]
     * @return [type]              [description]
     */
    static function checkRole($role_id=0, $controller='', $action='') {
       
        $pid=AdminRolePermission::find(" rid=$role_id and gid=8 ")->toarray();
        if(!$pid)  return false;
        $permission = Func::getCols($pid,'pid',',');
        
        $per=Permission::find("id in({$permission}) and gid = 8 ")->toArray(); 
       
        $public =Permission::find(" gid =8  and is_public=1 ")->toArray();

        $arr=array_merge($per,$public);
        
        $permissions=Func::getCols($arr,'id');
        
          
        $cur = Permission::findFirst("controller='{$controller}' and action='{$action}'");
        
        if(!in_array($cur->id,$permissions)){
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
    /**
     *  根据用户id查出对应权限
     * @param  [type] $userid [description]
     * @return [type]         [description]
     */
    static function checkmodel($user){

        //根据组id和角色id获取用户的权限
        $pid=AdminRolePermission::find(" rid={$user['role_id']} and gid=8 ")->toarray();
       
        $permission = Func::getCols($pid,'pid',',');
        if(!$permission) return false;
        $groupid = Func::getCols($pid,'gid',',');
        
        $per=Permission::find("id in({$permission}) and gid =8 ")->toArray(); 
       
        $public =Permission::find(" gid =8  and is_public=1 and pid!=0 ")->toArray();
       
        return array_merge($per,$public);
    }
}
