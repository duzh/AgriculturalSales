<?php
namespace Lib;

/**
 * 云农宝接口类
 */
class YnbUserInterface
{
    #测试环境地址
    public $test_url = CESHI_MEMBER.'api/ynp/';
    #正式环境地址
    public $formal_url = '';
    #url
    public $url = '';
    #请求方式 1 post,2 get
    public $request_type = 1;


    public function __construct() {
        echo 123;exit;
        #初始化环境url;
        $this->url = $this->test_url;
    }
    private function request($get_type,$params,$data){
        $this->url = $this->url.$get_type;
        $curl = new Curl();
        $data = json_encode($data);
        $data = $params.'='.$data.'&source='.$this->source;
        $result = ($this->request_type == 1)?$curl->post($this->url,$data):$curl->get($this->url.'?'.$data);
        #print_r($this->url.'?'.$data);exit;
        return json_decode($result);
    }
    /**
     * 获取列表
     * @param $uid 系统登录UID
     */
    public function getUserList($uid){
        $get_type= 'userList';
        $params = 'reqtoken';
        $data = array('uid'=>$uid);
        return $this->request($get_type,$params,$data);
    }
    /**
     * 获取详细信息
     * @param $uid 系统登录UID
     */
    public function getUserInfo($uid){
        $get_type= 'userInfo';
        $params = 'reqtoken';
        $data = array('uid'=>$uid);
        return $this->request($get_type,$params,$data);
    }
}


?>