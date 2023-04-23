<?php
namespace Mdg\Models;

class TagCheck extends \Phalcon\Mvc\Model
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
    public $tag_id;

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

 

}
