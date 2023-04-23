<?php

namespace Mdg\Models;
class MessageInspect extends Base
{

    /**
     *
     * @var integer
     */
    public $id=0;

    /**
     *
     * @var integer
     * 查看供应商消息ID
     */
    public $msg_id='';

    /**
     *
     * @var integer
     * 创建时间
     */
    public $add_time='';

    /**
     *
     * @var integer
     * 最后更新时间
     */
    public $last_update_time='';

    /**
     *
     * @var integer
     * 预计采购数量
     */
    public $buy_qty=0;
    /**
     *
     * @var integer
     * 查看商品地址
     */
    public $address='';
    /**
     * 采购规格
     * @var string
     */
    public $spec = '';
}
