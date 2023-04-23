<?php
namespace Mdg\Models;
use Lib\Arrays as Arrays;
use Lib as Lib;
class YncVillage extends \Phalcon\Mvc\Model
{
    /**
     *
     * @var integer
     */
    
    public $contact_id;
    /**
     *
     * @var string
     */
    
    public $service_bianhao = '0';
    /**
     *
     * @var string
     */
    
    public $contact_name = '0';
    /**
     *
     * @var string
     */
    
    public $pwd = '0';
    /**
     *
     * @var string
     */
    
    public $f_name = '';
    /**
     *
     * @var string
     */
    
    public $phone = '';
    /**
     *
     * @var string
     */
    
    public $sid = 0;
    /**
     *
     * @var string
     */
    
    public $youshi = 0;
    /**
     *
     * @var string
     */
    
    public $work = 0;
    /**
     *
     * @var string
     */
    
    public $jing = 0;
    /**
     *
     * @var string
     */
    
    public $province = 0;
    /**
     *
     * @var string
     */
    
    public $city = 0;
    /**
     *
     * @var string
     */
    
    public $district = 0;
    /**
     *
     * @var string
     */
    
    public $address = 0;
    /**
     *
     * @var string
     */
    
    public $tel = 0;
    /**
     *
     * @var string
     */
    
    public $riqi = 0;
    /**
     *
     * @var string
     */
    
    public $jaddress = 0;
    /**
     *
     * @var string
     */
    
    public $mian = 0;
    /**
     *
     * @var string
     */
    
    public $yongjin = 0;
    /**
     *
     * @var string
     */
    
    public $flag = '0';
    /**
     *
     * @var string
     */
    
    public $engineer_id = 0;
    /**
     *
     * @var string
     */
    
    public $account_mark = 0;
    /**
     *
     * @var string
     */
    
    public $account = 0;
    /**
     *
     * @var string
     */
    
    public $account_name = 0;
    /**
     *
     * @var double
     */
    
    public $comm_money = 0;
    /**
     *
     * @var string
     */
    
    public $bank = 0;
    /**
     *
     * @var string
     */
    
    public $date = 0;
    /**
     *
     * @var string
     */
    
    public $bytescont = 0;
    /**
     *
     * @var string
     */
    
    public $frozencont = 0;
    /**
     *
     * @var string
     */
    
    public $cancont = 0;
    /**
     *
     * @var string
     */
    
    public $deposit = 0;
    /**
     *
     * @var integer
     */
    
    public $Y_check = 0;
    /**
     *
     * @var string
     */
    
    public $zcont = 0;
    /**
     *
     * @var string
     */
    
    public $quhao_flag = 0;
    /**
     *
     * @var integer
     */
    
    public $j_flag = 1;
    /**
     *
     * @var integer
     */
    
    public $calm_id = 0;
    /**
     *
     * @var string
     */
    
    public $zpay_points = 0;
    /**
     *
     * @var string
     */
    
    public $zpay_pointsmi = 0;
    /**
     *
     * @var double
     */
    
    public $comm_sum = 0;
    /**
     *
     * @var double
     */
    
    public $use_money = 0;
    /**
     *
     * @var integer
     */
    
    public $is_sepc = 0;
    /**
     *
     * @var integer
     */
    
    public $user_id = 0;
    
    public $peisongaddress = 0;
    static $_asktype = array();
    
    public function getSource() 
    {
        return 'contact';
    }
    public function initialize(){
        $this->setConnectionService('ync365');
        $this->useDynamicUpdate(true);
    }

    /**
     * 获取村站id
     * @param  integer $cid 村站id
     * @return array
     */
    public static function getVillage() {
        $time=date("Y-m-d",strtotime("-2 day"));
        $sql="select b.province,b.city,b.county,b.town,b.village,a.f_name,a.contact_name,a.address from contact as a  join village_area as b on a.contact_id=b.village_id where  a.date >='$time'  order by contact_id desc limit 1,15 ";
        $db = $GLOBALS['di']['ync365'];
        $rs = $db->query($sql)->FetchAll(); 
        $arr["count"]=self::count();
        $arr["data"]=$rs;
        return $arr;
    }
    /**
     * 获取lc
     */
    public static function getCalm(){
        $time=strtotime(date("Y-m-d",strtotime("-30 day")));
        $db = $GLOBALS['di']['ync365'];
        $sql="SELECT calm_id , f_name, service_bianhao ,address  FROM calm WHERE flag = 1 and j_flag = 1 and  `date` >='$time'  ORDER BY calm_id DESC LIMIT 15";
        $rs = $db->query($sql)->FetchAll(); 
        $phql = " SELECT count(*) as total from calm WHERE flag = 1 and j_flag = 1 ";
        $arr["count"]=$db->query($phql)->fetch()['total']; 
        $arr["data"]=$rs;
        return $arr;
    }
}
