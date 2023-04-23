<?php
namespace Mdg\Models;

class PurchaseCheck extends \Phalcon\Mvc\Model
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
    public $purchase_id;

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

    public function beforeSave() {
        $this->last_update_time = time();
    }

    /**
     * 获取 采购单失败原因
     * @param  integer $sid 采购单id
     * @return string
     */
    public  static function getPurchaseFailReason ($pid = 0){
        
        $conds = " purchase_id = '{$pid}' ORDER BY id desc ";
        $data = self::findFirst($conds);
        return $data ? $data->fail_reason : "";

    }


}
