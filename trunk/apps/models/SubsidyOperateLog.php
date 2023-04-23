<?php
namespace Mdg\Models;
class SubsidyOperateLog extends \Phalcon\Mvc\Model
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
    public $subsidy_id;

    /**
     *
     * @var string
     */
    public $sunsidy_no;

    /**
     *
     * @var string
     */
    public $operate_user_no;

    /**
     *
     * @var string
     */
    public $operate_user_name;

    /**
     *
     * @var integer
     */
    public $operate_time;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var string
     */
    public $operate_content;

    /**
     *
     * @var string
     */
    public $demo;

    /**
     *
     * @var integer
     */
    public $add_time;

}
