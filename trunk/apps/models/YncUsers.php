<?php
namespace Mdg\Models;

class YncUsers extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $user_id;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var string
     */
    public $user_name;

    /**
     *
     * @var string
     */
    public $password;

    /**
     *
     * @var string
     */
    public $question;

    /**
     *
     * @var string
     */
    public $answer;

    /**
     *
     * @var integer
     */
    public $sex;

    /**
     *
     * @var string
     */
    public $birthday;

    /**
     *
     * @var double
     */
    public $user_money;

    /**
     *
     * @var double
     */
    public $frozen_money;

    /**
     *
     * @var integer
     */
    public $pay_points;

    /**
     *
     * @var integer
     */
    public $rank_points;

    /**
     *
     * @var integer
     */
    public $address_id;

    /**
     *
     * @var integer
     */
    public $reg_time;

    /**
     *
     * @var integer
     */
    public $last_login;

    /**
     *
     * @var string
     */
    public $last_time;

    /**
     *
     * @var string
     */
    public $last_ip;

    /**
     *
     * @var integer
     */
    public $visit_count;

    /**
     *
     * @var integer
     */
    public $user_rank;

    /**
     *
     * @var integer
     */
    public $is_special;

    /**
     *
     * @var string
     */
    public $salt;

    /**
     *
     * @var integer
     */
    public $parent_id;

    /**
     *
     * @var integer
     */
    public $flag;

    /**
     *
     * @var string
     */
    public $alias;

    /**
     *
     * @var string
     */
    public $msn;

    /**
     *
     * @var string
     */
    public $qq;

    /**
     *
     * @var string
     */
    public $office_phone;

    /**
     *
     * @var string
     */
    public $home_phone;

    /**
     *
     * @var string
     */
    public $mobile_phone;

    /**
     *
     * @var integer
     */
    public $is_validated;

    /**
     *
     * @var double
     */
    public $credit_line;

    /**
     *
     * @var string
     */
    public $passwd_question;

    /**
     *
     * @var string
     */
    public $passwd_answer;

    /**
     *
     * @var integer
     */
    public $service_id;

    /**
     *
     * @var string
     */
    public $grade;

    /**
     *
     * @var string
     */
    public $bank;

    /**
     *
     * @var string
     */
    public $account_mark;

    /**
     *
     * @var string
     */
    public $account;

    /**
     *
     * @var string
     */
    public $account_name;

    /**
     *
     * @var integer
     */
    public $contact_id;

    /**
     *
     * @var string
     */
    public $card;

    /**
     *
     * @var string
     */
    public $one_card;

    /**
     *
     * @var string
     */
    public $huiyuan;

    /**
     *
     * @var string
     */
    public $zhibu;

    /**
     *
     * @var string
     */
    public $address_name;

    /**
     *
     * @var integer
     */
    public $paypwd;

    /**
     *
     * @var string
     */
    public $zhonglei;

    /**
     *
     * @var string
     */
    public $mianji;

    /**
     *
     * @var integer
     */
    public $active_flag;

    /**
     *
     * @var string
     */
    public $active_bh;

    /**
     *
     * @var integer
     */
    public $statue;

    /**
     *
     * @var string
     */
    public $province;

    /**
     *
     * @var string
     */
    public $city;

    /**
     *
     * @var string
     */
    public $district;

    /**
     *
     * @var string
     */
    public $zhen;

    /**
     *
     * @var string
     */
    public $cun;

    /**
     *
     * @var string
     */
    public $jaddress;

    /**
     *
     * @var integer
     */
    public $sign_time;

    /**
     *
     * @var integer
     */
    public $sign_count;

    /**
     *
     * @var integer
     */
    public $pay;

    /**
     *
     * @var integer
     */
    public $reg_type;

    /**
     *
     * @var integer
     */
    public $import;

    /**
     *
     * @var string
     */
    public $col1;

    /**
     *
     * @var integer
     */
    public $level;

    /**
     *
     * @var integer
     */
    public $is_ava;

    public function getSource()
    {
        return "users";
    }
    
    public function initialize(){
        $this->setConnectionService('ync365');
        $this->useDynamicUpdate(true);
    }
    
    public static function register($mobile,$password){
   
        if(self::checkmoblie($mobile)){
            return false;
        }
        $user = new self();
        $user->user_name= $mobile;
        $user->password = md5($password);
        $user->reg_time =time();
        $user->reg_type =10;
        if($user->save()){
            return $user->toArray();
        }

    }
    /**
     * 检测手机号
     * @param  string  $sn        手机号
     * @param  integer $isprimary 是否主键查询
     * @return array
     */
    public static  function  checkmoblie($sn='', $isprimary=0){
        if($isprimary) {
            $user= self::findFirstByuser_id($sn);      
        }else{
            $user= self::findFirstByuser_name($sn);      
        }
      return $user ? $user->toArray() : false;
    }

        /**
     * 根据手机号获取云农场用户信息
     * @param  string $mobile 手机号
     * @return array
     */
    public static function getYncUsers($mobile='') {
        
        $data = self::findFirst(" user_name = '{$mobile}'")->toArray();
        return $data;
    }
    
}
