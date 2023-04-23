<?php
namespace Mdg\Models;
use Lib\Arrays as Arrays;
class ShopCoods extends \Phalcon\Mvc\Model
{


    /**
     *
     * @var integer
     */
    public $goods_id;
     /**
     *
     * @var integer
     */    
    public $shop_id;
     /**
     *
     * @var string
     */ 
    public $goods_name;
    /**
     *
     * @var string
     */   
    public $shop_name; 
    /**
     *
     * @var integer
     */  
    public $user_id; 
    /**
     *
     * @var string
     */ 
    public $user_name;
    /**
     *
     * @var integer
     */    
    public $goods_status;
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
    
    public function getSource()
    {
        return 'shop_goods';
    }

    /**
     * 获取店铺主营商品
     */
    
    public static function getgoodsname($shop_id=0){
       
        $data = self::find(" shop_id='{$shop_id}'")->toArray();
        if(!$data){
            return "暂无";
        }
        $goods_name = Arrays::getCols($data,'goods_name',',');
        
        return $goods_name ?  $goods_name : '暂无';

    }

}
