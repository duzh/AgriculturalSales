<?php
namespace Mdg\Models;

class UsersNav extends \Phalcon\Mvc\Model
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
    public $pid;

    /**
     *
     * @var string
     */
    public $title;

    /**
     *
     * @var string
     */
    public $models;

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

}
