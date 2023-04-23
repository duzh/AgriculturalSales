<?php
namespace Models;
class YncUsersArea extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $user_id;

    /**
     *
     * @var string
     */
    public $province;

    /**
     *
     * @var string
     */
    public $district;

        /**
     *
     * @var string
     */
    public $townlet;

        /**
     *
     * @var string
     */
    public $village;

        /**
     *
     * @var string
     */
    public $areas;

    
    public function initialize()
    {
        $this->setConnectionService('ync365');
    }
    public function getSource() 
    {
        return 'users_areas';
    }

}
