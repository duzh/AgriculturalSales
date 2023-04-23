<?php
namespace Mdg\Models;
class AdminPermission extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $permission_id;

    /**
     *
     * @var integer
     */
    public $display;

    /**
     *
     * @var string
     */
    public $namespace;

    /**
     *
     * @var string
     */
    public $controller;

    /**
     *
     * @var string
     */
    public $action;

    /**
     *
     * @var string
     */
    public $aliasname;

    
}
