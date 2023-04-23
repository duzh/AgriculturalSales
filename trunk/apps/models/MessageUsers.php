<?php

namespace Mdg\Models;
class MessageUsers extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id=0;

    /**
     *
     * @var integer
     * 消息ID
     */
    public $msg_id='';

    /**
     *
     * @var integer
     * 用户ID
     */
    public $user_id=0;

    /**
     *
     * @var integer
     * 是否被查看过：0 未查看 1 已查看
     */
    public $is_new=0;

    /**
     *
     * @var integer
     * 创建时间
     */
    public $add_time=0;
    /**
     *
     * @var integer
     * 最后修改时间
     */
    public $last_update_time=0;
}
