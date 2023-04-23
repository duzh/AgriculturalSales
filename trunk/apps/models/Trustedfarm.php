<?php
namespace Mdg\Models;
class Trustedfarm extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $address;

    /**
     *
     * @var string
     */
    public $goods;

    public function getSource()
    {
        return "trustedfarm";
    }

}
