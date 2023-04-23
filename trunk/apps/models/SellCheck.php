<?php
namespace Mdg\Models;

class SellCheck extends \Phalcon\Mvc\Model
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
    public $sell_id;

    /**
     *
     * @var string
     */
    public $fail_reason;

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
    public $fail_id;

    public function beforeSave(){
        $this->last_update_time = time();
    }
    /**
     * 获取 供应单失败原因
     * @param  integer $sid 采购单id
     * @return string
     */
    public  static function getfailReason ($sid = 0){
        
        $conds = " sell_id = '{$sid}' ORDER BY id desc ";
        $data = self::findFirst($conds);
        return $data ? $data->fail_reason : "";

    }

}
