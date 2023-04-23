<?php
namespace Mdg\Models;
use Lib\Pages as Pages;
use Mdg\Models as M;
use Lib as L;

class UserYnpInfo extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $user_id;

    /**
     *
     * @var string
     */
    public $ynp_user_phone;

    /**
     *
     * @var string
     */
    public $ynp_member_id;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var string
     */
    public $add_time;

    /**
     *
     * @var string
     */
    public $last_update_time;

    public $user_name;
    /**
     * 获取列表
     * @param  string  $where 条件
     * @param  integer $page  当前页
     * @param  integer $psize 条数
     * @param  string $pageMethod 分页样式
     * @return array ( items=> 数据源, start => 起始页 ， pages => 分页)
     */
    public static function getUserYnpInfoList($where='', $page=1, $psize =10,$pageMethod='defalut') {

        $cond[] = $where;
        $cond['order'] = ' id DESC ';
        $total = self::count($cond);
        $offst = ( $page  - 1 ) * $psize;
        $cond['limit']  =array($psize , $offst);
        $item = self::find($cond);
//        foreach($item AS $ik =>$iv){
//            $item[$ik]['user_name'] = UsersExt::getusername($iv['user_id']);
//        }
        //$this->user_name = UsersExt::getusername($this->user_id);
        $pages['total_pages'] = ceil($total / $psize);
        $pages['current'] = $page;
        $pages['total'] = $total;
        $pages['method']  = $pageMethod;
        $pages['ajax_func_name'] = "javascript:goto_pages";

        $pages = new Pages($pages);
        $data['items'] = $item;
        $data['start'] = $offst;
        $data['pages'] = $pages->show(7);

        return $data;
    }
    public static function checkuserynp($uid){
         $users=M\Users::getFshUsers($uid);
         if($users&&!$users->is_broker){
            $bindynb = self::findFirst("user_id ={$uid}");
            if(!$bindynb){
                return true;
            }else{
                return false;
            }
         }else{
            return false;
         }
    }
}
