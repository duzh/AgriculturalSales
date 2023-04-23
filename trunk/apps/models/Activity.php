<?php

class Activity extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $activity_id;

    /**
     *
     * @var string
     */
    public $activity_name;

    /**
     *
     * @var integer
     */
    public $begin_date;

    /**
     *
     * @var integer
     */
    public $end_date;

    /**
     *
     * @var integer
     */
    public $activity_status;

    /**
     *
     * @var string
     */
    public $activity_desc;

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'activity_id' => 'activity_id', 
            'activity_name' => 'activity_name', 
            'begin_date' => 'begin_date', 
            'end_date' => 'end_date', 
            'activity_status' => 'activity_status', 
            'activity_desc' => 'activity_desc'
        );
    }

}
