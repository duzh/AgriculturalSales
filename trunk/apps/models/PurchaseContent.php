<?php
namespace Mdg\Models;
class PurchaseContent extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $purid;

    /**
     *
     * @var string
     */
    public $content;


    public function getSource()
    {
        return 'purchase_content';
    }
    static function getcategory($id){
        $a=Category::findFirstByid($id);
        return $a ?$a->title:'-';
    }
    /**
     * 获取采购详细信息
     * @param  [type] $pid 采购id
     * @return string
     */
    public static function getPurchaseContent($pid=0) {
        $data = self::findFirst(" purid = '{$pid}'");
        return $data ? $data->content : '';
    }
    

}
