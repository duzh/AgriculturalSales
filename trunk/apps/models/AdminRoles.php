<?php

namespace Mdg\Models;

class AdminRoles extends \Phalcon\Mvc\Model
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
    public $parent_id=0;

    /**
     *
     * @var string
     */
    public $rolename='';

    /**
     *
     * @var string
     */
    public $description='';
    public function getSource()
    {
        return 'admin_roles';
    }
    /**
     * 控制器名称
     * @var [type]
     */
    static $controllername = 
        array(
            'index'=>'首页',
            'ad'=>'广告位管理', 
            'adminroles'=>'角色管理',
            'article'=>'文章管理',
            'category'=>'分类管理',
            'member'=>'初始化验证',
            'login'=>'登录',
            'orders'=>'订单管理',   
            'priv'=>'权限设置管理',
            'purchase'=>'采购管理', 
            'sell'=>'供应管理',
            'users'=>'用户管理',
            'admin'=>'权限管理',
            'adminroles'=>'角色管理',
            'articlecategory'=>'文章分类管理',
            'product'=>'产品管理',
            'productcategory'=>'产品分类管理',

    );
    /**
     * action name
     * @var array
     */
    static $actionername = array(
            'index' => '首页',
            'new' => '新建',
            'create' => '新建执行',
            'edit' => '修改',
            'save' => '修改执行',
            'search' => '搜索',
            'delete' => '删除',
            'look' => '查看',
            'chanage' => '转移商品',
            'check' => '选择商品',
            'chanagepro' => '选择商品执行方法',
            'initialize' => '初始化',
            'logout' => '退出',
            'validatelogin' => '验证登录',
            'changepwd' => '验证密码',
            'updatestate' => '修改状态',
            'getuser' => '获取用户',
            'download' => '下载模板',
            'ajax' => 'ajax操作',
            'showuser' => '显示用户',
            'showcategory' => '显示分类',
            'getuserid' => '获取用户id',
            'getusername' => '获取用户名称',
            'info' => '订单详情',
            'upstate'=>"修改状态",
            'getroleid'=>'111',
            'aaa'=>'1222',
            'review'=>'批量审核',
            'reload'=>'刷新',
    );
    public static  function rolename($id){
        $role=self::findFirstByid($id);
        return $role ? $role->rolename:"-";
    }
    static $publicrole=array(
            0=>'check',
            1=>'chanagepro',
            2=>'toggle',
            3=>'info',
            4=>'updatestate',
            5=>'upstate',
            6=>'showuser',
            7=>'upexcal',
            8=>'excal',
            9=>'downexcal',
            10=>'download',
            11=>'delimg',
            12=>'showuser',
            13=>'upexcal',
            14=>'excal',
            15=>'downexcal',
            16=>'download',
            17=>'create',
            18=>'save',
            19=>'index',
            20=>'checkarticle',
            21=>'editprice',
            22=>'saveprice',
            23=>'setdev',
            24=>'savedev',
            25=>'ajax',
            26=>'shenhe',
    );


}
