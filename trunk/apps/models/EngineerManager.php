<?php
namespace Mdg\Models;
use Mdg\Models as M;
use Lib\Validator as Validator;

class EngineerManager extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $engineer_id;

    /**
     *
     * @var string
     */
    public $engineer_no;

    /**
     *
     * @var string
     */
    public $engineer_name;

    /**
     *
     * @var string
     */
    public $engineer_phone;

    /**
     *
     * @var integer
     */
    public $level;

    /**
     *
     * @var string
     */
    public $logon_password;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var integer
     */
    public $add_time;

    /**
     *
     * @var integer
     */
    public $last_update_time;

    /**
     *
     * @var integer
     */
    public $last_logon_time;

   
	/**
     * 查询工程师信息
     * @para $phone 
     * @para $ishc 是否是hc会员
     */
    public static function getEngineerInfo($phone, $ishc=false)
    {
        $result = null;
		if (!$phone || !Validator::validate_is_mobile($phone)) return array();

		if (!$ishc) {
			
			$user = M\Users::findFirst("username={$phone}");
            if($user) 
            {
                $hcinfo = M\UserInfo::findFirst("user_id={$user->id} and credit_type=2 and status=1");

                if($hcinfo && $hcinfo->se_mobile)
                {
                    $result = self::findFirst("engineer_phone={$hcinfo->se_mobile} and status=0  and level=0");
                }
            }
		}

        if (!$result)  
        {
            $result = self::findFirst("engineer_phone={$phone} and status=0 and level=0");
        }
		return $result;
		
    }  

}
