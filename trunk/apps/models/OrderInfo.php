<?php
namespace Mdg\Models;
use Lib\Apiconfig AS C;
class OrderInfo extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $order_id = 0;

    /**
     *
     * @var string
     */
    public $order_sn = '';

    /**
     *
     * @var integer
     */
    public $user_id = 0;

    /**
     *
     * @var integer
     */
    public $order_status = 0;

    /**
     *
     * @var integer
     */
    public $shipping_status = 0;

    /**
     *
     * @var integer
     */
    public $pay_status = 0;

    /**
     *
     * @var integer
     */
    public $ding_status = 0;

    /**
     *
     * @var string
     */
    public $consignee = '';

    /**
     *
     * @var integer
     */
    public $country = 0;

    /**
     *
     * @var integer
     */
    public $province = 0;

    /**
     *
     * @var integer
     */
    public $city = 0;

    /**
     *
     * @var integer
     */
    public $district = 0;

    /**
     *
     * @var string
     */
    public $address = '';

    /**
     *
     * @var string
     */
    public $zipcode = '';

    /**
     *
     * @var string
     */
    public $tel = '';

    /**
     *
     * @var string
     */
    public $mobile = '';

    /**
     *
     * @var string
     */
    public $email = '';

    /**
     *
     * @var string
     */
    public $best_time = '';

    /**
     *
     * @var string
     */
    public $sign_building = '';

    /**
     *
     * @var string
     */
    public $postscript = '';

    /**
     *
     * @var integer
     */
    public $shipping_id = 0;

    /**
     *
     * @var string
     */
    public $shipping_name = '';

    /**
     *
     * @var integer
     */
    public $pay_id = 0;

    /**
     *
     * @var string
     */
    public $pay_name = '';

    /**
     *
     * @var string
     */
    public $how_oos = '';

    /**
     *
     * @var string
     */
    public $how_surplus = '';

    /**
     *
     * @var string
     */
    public $pack_name = '';

    /**
     *
     * @var string
     */
    public $card_name = '';

    /**
     *
     * @var string
     */
    public $card_message = '';

    /**
     *
     * @var string
     */
    public $inv_payee = '';

    /**
     *
     * @var string
     */
    public $inv_content = '';

    /**
     *
     * @var double
     */
    public $goods_amount = 0;

    /**
     *
     * @var double
     */
    public $shipping_fee = 0;

    /**
     *
     * @var double
     */
    public $insure_fee = 0;

    /**
     *
     * @var double
     */
    public $pay_fee = 0;

    /**
     *
     * @var double
     */
    public $pack_fee = 0;

    /**
     *
     * @var double
     */
    public $card_fee = 0;

    /**
     *
     * @var double
     */
    public $money_paid = 0;

    /**
     *
     * @var double
     */
    public $surplus = 0;

    /**
     *
     * @var integer
     */
    public $integral = 0;

    /**
     *
     * @var double
     */
    public $integral_money = 0;

    /**
     *
     * @var double
     */
    public $bonus = 0;

    /**
     *
     * @var double
     */
    public $order_amount = 0;

    /**
     *
     * @var integer
     */
    public $from_ad = 0;

    /**
     *
     * @var string
     */
    public $referer = '';

    /**
     *
     * @var integer
     */
    public $add_time = 0;

    /**
     *
     * @var integer
     */
    public $confirm_time = 0;

    /**
     *
     * @var integer
     */
    public $pay_time = 0;

    /**
     *
     * @var integer
     */
    public $shipping_time = 0;

    /**
     *
     * @var integer
     */
    public $pack_id = 0;

    /**
     *
     * @var integer
     */
    public $card_id = 0;

    /**
     *
     * @var integer
     */
    public $bonus_id = 0;

    /**
     *
     * @var string
     */
    public $invoice_no = '';

    /**
     *
     * @var string
     */
    public $extension_code = '';

    /**
     *
     * @var integer
     */
    public $extension_id = 0;

    /**
     *
     * @var string
     */
    public $to_buyer = '';

    /**
     *
     * @var string
     */
    public $pay_note = '';

    /**
     *
     * @var integer
     */
    public $agency_id = 0;

    /**
     *
     * @var string
     */
    public $inv_type = '';

    /**
     *
     * @var double
     */
    public $tax = 0;

    /**
     *
     * @var integer
     */
    public $is_separate = 0;

    /**
     *
     * @var integer
     */
    public $parent_id = 0;

    /**
     *
     * @var double
     */
    public $discount = 0;

    /**
     *
     * @var integer
     */
    public $shopyuding = 0;

    /**
     *
     * @var integer
     */
    public $suppliers_id = 0;

    /**
     *
     * @var string
     */
    public $suppliers_name = '';

    /**
     *
     * @var integer
     */
    public $object_order = 0;

    /**
     *
     * @var integer
     */
    public $shopyuding_status = 0;

    /**
     *
     * @var integer
     */
    public $order_source = 0;

    /**
     *
     * @var integer
     */
    public $act_id = 0;

    /**
     *
     * @var integer
     */
    public $main_id = 0;

    /**
     *
     * @var integer
     */
    public $state = 0;

    /**
     *
     * @var double
     */
    public $total = 0;

    /**
     *
     * @var integer
     */
    public $is_search = 0;

    /**
     *
     * @var integer
     */
    public $is_tehui = 0;

    /**
     *
     * @var integer
     */
    public $u8_status = 0;

    /**
     *
     * @var string
     */
    public $job = '';
    public function initialize(){
        $this->setConnectionService('ync365');
        $this->useDynamicUpdate(true);
    }

}
