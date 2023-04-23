<?php
namespace Mdg\Models;

class UserFarmPicture extends \Phalcon\Mvc\Model
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
    public $picture_path;

    /**
     *
     * @var integer
     */
    public $add_time;

    /**
     *
     * @var integer
     */
    public $credit_id;
    
    public function afterFetch() {
        $this->pic_img = $this->picture_path ? IMG_URL . $this->picture_path : '';
    }
    /**
     * 农场图片
     */
    CONST FARM_PIC = 0 ;
    /**
     * 农场合同图片
     */
    CONST FARM_CONTRACT_PIC = 1 ;

    static function selectByPic($credit_id=0,  $type=0) {
        $cond[] = " credit_id  = '{$credit_id}' AND type = '{$type}'";
        return self::find($cond);
    }
   

}
