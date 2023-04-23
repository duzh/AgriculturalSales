<?php
namespace Mdg\Models;
use Mdg\Models as M;
class UserBank extends \Phalcon\Mvc\Model
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
    public $user_id;

    /**
     *
     * @var string
     */
    public $bank_name;

    /**
     *
     * @var string
     */
    public $bank_address;

    /**
     *
     * @var string
     */
    public $bank_account;

    /**
     *
     * @var string
     */
    public $bank_cardno;

    /**
     *
     * @var string
     */
    public $bankcard_picture;

    /**
     *
     * @var string
     */
    public $idcard_picture;

    /**
     *
     * @var string
     */
    public $person_picture;

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

    public $identity_card_back;

    static public function getbank_nameinfo($name){
        if($name){
            $bank_nameinfo=M\Bank::findFirstBygate_id($name);
            if($bank_nameinfo){
                return $bank_nameinfo->bank_name;
            }else{
                return '-';
            }
        }else{
            return '-';
        }
    } 
}
