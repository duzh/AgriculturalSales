<?php
namespace Mdg\Models;
use Lib\Func as Func;

class RecommandPurchase extends \Phalcon\Mvc\Model
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
    public $purchase_id;

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
    public $location_home = 1 ;

    /**
     *
     * @var integer
     */
     public $location_category = 1 ;


    /**
     * 推荐采购推荐   
     * @param  integer $page  当前页
     * @param  integer $psize 条数
     * @param  string  $where 条件
     * @return array
     */
    public static function getRecPurIdList() {
        $_pid = array();
        $cond[] = " status = 1 ";
        $data = self::find($cond)->toArray();
        $_pid = array_column($data, 'purchase_id');
        return $_pid ? join( ',', $_pid) : '';

    }

        /**
     * 查询当前商品的推荐状态
     */
    public static function recommandtype($purchase_id=0){


        $recommand = self::findFirstBypurchase_id($purchase_id);
        
        if($recommand){

            $recommand = $recommand->toArray();
            return $recommand;
        }else{
            $recommand = array();
            $recommand['location_home'] = 0;
            $recommand['location_category'] = 0;
            $recommand['location_hot'] = 0;

        }

        return $recommand;
    }
    /**
     * 根据推荐位查询purchase_id
     * @param  string $recommand_where [description]
     * @return [type]                  [description]
     */
    public static function getpurchase_id($recommand_where=''){
        if($recommand_where){
            $recommandpurchase = self::find($recommand_where)->toArray();

            $purchase_id = Func::getCols($recommandpurchase, 'purchase_id', ",");
            if($purchase_id){
                return "  is_del = 0 and state = 1 and id in ({$purchase_id})";
            }else{
                return " is_del = 0 and state = 1 and id in ('{$purchase_id}')";
            }
            
        }
    }

}
