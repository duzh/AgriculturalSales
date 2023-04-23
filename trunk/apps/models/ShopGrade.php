<?php
namespace Mdg\Models;
class ShopGrade extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $shop_id;

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $service;

    /**
     *
     * @var integer
     */
    public $accompany;

    /**
     *
     * @var integer
     */
    public $supply;

    /**
     *
     * @var integer
     */
    public $description;

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

    public $comments_count;


    public function beforeSave() {
        $this->last_update_time=  time();
    } 

}
