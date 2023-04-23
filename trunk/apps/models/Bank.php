<?php
namespace Mdg\Models;
class Bank extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $bank_name;

    /**
     *
     * @var string
     */
    public $gate_id;

    /**
     *
     * @var string
     */
    public $bank_img;

    /**
     *
     * @var string
     */
    public $bank_image;

    private $paymentType = array(
            'YNP' => 'images/bank/ynp.png',
            'ABC' => 'images/bank/abc.jpg',
        );
    public function getPaymentList() {
        return $this->paymentType;
    }

    public static function getBankList() {
        $data = self::find()->toArray();
       foreach ($data as $key => $val) {
           // if(trim($val['gate_id']) == 'ABC') {
           //  unset($data[$key]);
           // }
       }
        return $data;
    }
    /**
     * 获取银行名称
     * @param  string $cid 编号code
     * @return [type]      [description]
     */
    public static function selectByCode($code='') {
        $cond[] = " gate_id = '{$code}'";
        $data = self::findFirst($cond);
        return $data ? $data->bank_name : '';
    }

}
