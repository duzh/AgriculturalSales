<?php
namespace Mdg\Models;
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


}
