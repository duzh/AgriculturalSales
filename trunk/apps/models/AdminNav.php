<?php
namespace Mdg\Models;
class AdminNav extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id=0;

    /**
     *
     * @var string
     */
    public $title='';

    /**
     *
     * @var string
     */
    public $url='';

    /**
     *
     * @var string
     */
    public $icon='';

    /**
     *
     * @var integer
     */
    public $pid=0;

    /**
     *
     * @var integer
     */
    public $is_show=0;

}
