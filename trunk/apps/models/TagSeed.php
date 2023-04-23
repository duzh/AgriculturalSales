<?php
namespace Mdg\Models;

class TagSeed extends \Phalcon\Mvc\Model
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
     * @var string
     */
    public $crops;

    /**
     *
     * @var string
     */
    public $breed;

    /**
     *
     * @var string
     */
    public $neatness;

    /**
     *
     * @var string
     */
    public $fineness;

    /**
     *
     * @var string
     */
    public $sprout;

    /**
     *
     * @var string
     */
    public $water;

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

    public static $rainwater = array(
            1=> '干旱',
         2=> '微干',
         3=> '潮湿',
         4=> '湿润',
         5=> '涝灾'
     );



}
