<?php
namespace Mdg\Models;
class YncOrdersUposp extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $order_id = 1;

    /**
     *
     * @var string
     */
    public $order_sn = '';

    /**
     *
     * @var string
     */
    public $uposp_sn = '';

    /**
     *
     * @var integer
     */
    public $add_time = 1;

    /**
     *
     * @var integer
     */
    public $count = 1;
     
    /**
     *
     * @var string
     */
    public $transation_sn = '';

    public function getSource()
    {
        return "orders_uposp";
    }
    
    public function initialize(){
        $this->setConnectionService('ync365');
        $this->useDynamicUpdate(true);
    }
}