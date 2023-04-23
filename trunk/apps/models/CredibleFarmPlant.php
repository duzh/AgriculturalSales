<?php
namespace Mdg\Models;
class CredibleFarmPlant extends \Phalcon\Mvc\Model
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
    public $user_id;
    /**
     *
     * @var string
     */
    public $title;



    /**
     *
     * @var integer
     */
    public $desc;

    /**
     *
     * @var string
     */
    public $add_time;

    /**
     *
     * @var string
     */
    public $last_update_time;

    /**
     *
     * @var string
     */
    public $picture_path;

    /**
     *
     * @var integer
     */
    public $is_delete=0;

    public $picture_time;
}
