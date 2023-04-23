<?php
namespace Mdg\Models;


class CargoExt extends \Phalcon\Mvc\Model
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
    public $goods_id;

    /**
     *
     * @var integer
     */
    public $box_type;

    /**
     *
     * @var integer
     */
    public $body_type;

    /**
     *
     * @var integer
     */
    public $expire_time;

    /**
     *
     * @var integer
     */
    public $demo;

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
    public $settle_type;


   public function initialize() {
        $this->setReadConnectionService('dbRead');
         $this->setWriteConnectionService('dbWrite');
    }
    
    

}
