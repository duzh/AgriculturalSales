<?php
namespace Mdg\Models;
use Lib as L;
class ScInfo extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $sc_id;

    /**
     *
     * @var string
     */
    public $contact_man=" ";

    /**
     *
     * @var string
     */
    public $phone=" ";

    /**
     *
     * @var double
     */
    public $light_price;

    /**
     *
     * @var double
     */
    public $heavy_price;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var integer
     */
    public $start_pid=0;

    /**
     *
     * @var string
     */
    public $start_pname=" ";

    /**
     *
     * @var integer
     */
    public $start_cid=0;

    /**
     *
     * @var string
     */
    public $start_cname=" ";

    /**
     *
     * @var integer
     */
    public $start_did=0;

    /**
     *
     * @var string
     */
    public $start_dname=" ";

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
     * @var string
     */
    public $end_pname=" ";

    /**
     *
     * @var integer
     */
    public $end_pid=0;

    /**
     *
     * @var string
     */
    public $end_cname=" ";

    /**
     *
     * @var integer
     */
    public $end_did=0;

    /**
     *
     * @var integer
     */
    public $end_cid=0;

    /**
     *
     * @var string
     */
    public $end_dname=" ";

    /**
     *
     * @var integer
     */
    public $type;

    public static function groupscinfo(){
        $cond['columns'] = "start_pid,start_pname,sc_id ";
        $cond['group'] = 'start_pname';
        $cond['order'] = 'sc_id desc ';
        $rs = self::find($cond);
        //组装数据
        return $rs;            
    }
    public static function getScInfolimit(){
        $cond['columns'] = "start_pid,start_pname,sc_id ";
        $cond['group'] = 'start_pname';
        $cond['order'] = 'sc_id desc ';
        $cond['limit'] = array(8,0);  
        $rs = self::find($cond);
        //组装数据
        return $rs;            
    }


    /**
     * 获取车源基本信息
     * @param  array   $where     条件
     * @param  integer $p         分页
     * @param  [type]  $orderby   排序
     * @param  integer $page_size 条数
     * @return array
     */
    
    public static function getScInfoList($where = array() ,  $p = 1, $db=null , $page_size = 20 ) 
    {

        
        $sql = " SELECT count(i.sc_id) as total FROM sc_info as i ,sc_ext as ext WHERE ext.sc_id = i.sc_id AND  i.status = 1 AND {$where} order by i.sc_id ";
        $total = $db->fetchOne($sql,2)['total'];
        $offst = ( $p - 1 ) * $page_size;
        $sql = " SELECT * FROM sc_info as i ,sc_ext as ext WHERE ext.sc_id = i.sc_id AND  i.status = 1  AND {$where} order by i.sc_id DESC LIMIT {$offst}, {$page_size}";
              
        $data = $db->fetchAll($sql,2);
       
        $pages['total_pages'] = ceil($total / $page_size);
        $pages['current'] = $p;
        $pages['total'] = $total;
        $pages = new L\Pages($pages);
        $datas['total'] = $total;
        $datas['items'] = $data;
        $datas['start'] = $offst;
        $datas['pages'] = $pages->show(1);
        return $datas;
    }


}
