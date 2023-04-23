<?php
namespace Mdg\Models;

class ShopCheck extends \Phalcon\Mvc\Model
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
    public $shop_id;

    /**
     *
     * @var integer
     */
    public $last_failure;

    /**
     *
     * @var string
     */
    public $failure_desc;

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

    public function beforeSave() {
        $this->last_update_time = time();
    }

}
