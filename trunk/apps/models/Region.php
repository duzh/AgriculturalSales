<?php
namespace Mdg\Models;
class Region extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $region_id;

    /**
     *
     * @var integer
     */
    public $parent_id;

    /**
     *
     * @var string
     */
    public $region_name;

    /**
     *
     * @var integer
     */
    public $region_type;

    /**
     *
     * @var integer
     */
    public $agency_id;
    

}
