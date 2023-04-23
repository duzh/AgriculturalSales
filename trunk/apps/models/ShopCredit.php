<?php
namespace Mdg\Models;

class ShopCredit extends \Phalcon\Mvc\Model
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
    public $shop_id;

    /**
     *
     * @var string
     */
    public $identity_no;

    /**
     *
     * @var string
     */
    public $bank_name;

    /**
     *
     * @var string
     */
    public $account_name;

    /**
     *
     * @var string
     */
    public $card_no;

    /**
     *
     * @var string
     */
    public $bank_card_picture;

    /**
     *
     * @var string
     */
    public $identity_card_front;

    /**
     *
     * @var string
     */
    public $identity_card_back;

    /**
     *
     * @var string
     */
    public $identity_picture_lic;

    /**
     *
     * @var string
     */
    public $tax_registration;

    /**
     *
     * @var string
     */
    public $organization_code;

    /**
     *
     * @var string
     */
    public $shop_desc;

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

    

}
