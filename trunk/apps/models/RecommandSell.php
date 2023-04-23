<?php
namespace Mdg\Models;
use Lib\Func as Func;

class RecommandSell extends \Phalcon\Mvc\Model
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
    public $sell_id;

    /**
     *
     * @var string
     */
    public $add_time;

    /**
     *
     * @var string
     */
    public $status=1;

    /**
     *
     * @var string
     */
    public $last_update_time;


    /**
     *
     * @var string
     */
    public $location_home;

    /**
     *
     * @var integer
     */
    public $location_category;

    /**
     *
     * @var integer
     */
    public $location_hot=0;

    /**
     * 查询当前商品的推荐状态
     */
    public static function recommandtype($sell_id=0){


        $recommand = self::findFirstBysell_id($sell_id);

        if($recommand){
            $recommand = $recommand->toArray();
        }else{
            $recommand = array();
            $recommand['location_home'] = 0;
            $recommand['location_category'] = 0;
            $recommand['location_hot'] = 0;

        }

        return $recommand;
    }
    /**
     * 根据推荐位查询sell_id
     * @param  string $recommand_where [description]
     * @return [type]                  [description]
     */
    public static function getsell_id($recommand_where=''){
        
        if($recommand_where){
            $recommandsell = self::find($recommand_where)->toArray();

            $sell_id = Func::getCols($recommandsell, 'sell_id', ",");
            if($sell_id){
                return " AND s.state = 1 AND s.id in ({$sell_id})";
            }else{
                return " AND s.state = 1 AND s.id in ('{$sell_id}')";
            }

            
        }
        
    }
    
    /**
     * 获取供应id 
     * @return array
     */
    public static   function selectBytoArray($cond= array()) {
        $cond['columns'] = ' sell_id';
        $data = self::find($cond)->toArray();
        return $data;
    }

    

}

