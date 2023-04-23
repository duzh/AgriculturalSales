<?php

namespace Mdg\Models;

use Lib\Time as times;

class PurchaseQuotation extends \Phalcon\Mvc\Model
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
    public $purid;

    /**
     *
     * @var double
     */
    public $price;

    /**
     *
     * @var string
     */
    public $spec;

    /**
     *
     * @var integer
     */
    public $puserid;

    /**
     *
     * @var string
     */
    public $purname;

    /**
     *
     * @var integer
     */
    public $suserid;

    /**
     *
     * @var string
     */
    public $sellname;

    /**
     *
     * @var integer
     */
    public $sareas;

    /**
     *
     * @var string
     */
    public $saddress;

    /**
     *
     * @var string
     */
    public $sphone;

    /**
     *
     * @var integer
     */
    public $addtime;

    public $state=0;

    public $is_out=0;

    public function getSource()
    {
        return 'purchase_quotation';
    }

    /**
     * 获取报价数量
     * @param  integer $purid 求购单号
     * @return int
     */
    static function countQuo($purid=0) {
        return self::find("purid='{$purid}'")->count();
    }

    public function initialize()
    {
        $this->belongsTo('purid', 'Mdg\Models\Purchase', 'id', array(
            'alias' => 'purchase'
        ));
    }
     /**
     * 根据条件搜索
     * @param  array  $arr  搜索条件
     * @param  string  $type  类型 1为良品库查询 2为次品库查询
     * @return array
     */
    
    static public function conditions($arr) 
    {   
        
        $options = array(
            'sellname' => '',
            'category' => '',
            'area_id' => '',
        );

        $opt = array_merge($options,$arr);

        $where = '1=1'; 
        if (is_array($arr)) 
        {
           empty($arr['category']) ? : $where.= " and category= " . $arr['category'] . "";
           empty($arr['area_id']) ? : $where.= " and areas =".$arr["area_id"] ;
           empty($arr['sellname']) ? : $where.= " and title like '%" . $arr["sellname"] . "%'";
        }
       
        return $where;
    }
    static public function humandate($timestamp) {
        
        $a = new times(time(),$timestamp);
        return $a->transform();
    }
    static public function spec($id){
        $spec=PurchaseContent::findFirstBypurid($id);
        return $spec ? $spec->content:"-";
    }
    static public function oredercount($id){
        $order=Orders::findBypurid($id);
      
        return $order ?count($order->toArray()):"0";
    }
}
