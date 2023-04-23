<?php
namespace Mdg\Models;
use Lib\Pages as Pages;
use Mdg\Models as M;
class Tag extends \Phalcon\Mvc\Model
{

    /**
     * 未审核
     */
    CONST NOTAUDIT     = 0;
    /**
     * 审核通过
     */
    CONST APPROVED     = 1;
    /**
     * 审核失败
     */
    CONST AUDITFAILURE = 2 ;

    /**
     * 作业类型
     * @var array
     */
    static $_operate_type = array(
          1 => '整地',
          2 => '播种',
          3 => '施肥',
          4 => '灌溉',
          5 => '施药',
          6 => '收获',
        );
     /**
     * 作业类型
     * @var array
     */
    static $rainwater_type = array(
          0 => '暂无',
          1 => '干旱',
          2 => '微干',
          3 => '潮湿',
          4 => '湿润',
          5 => '涝灾',
    );
    /**
     *
     * @var integer
     */
    public $tag_id;

    /**
     *
     * @var integer
     */
    public $category_one;

    /**
     *
     * @var integer
     */
    public $category_two;

    /**
     *
     * @var string
     */
    public $goods_name;

    /**
     *
     * @var integer
     */
    public $province;

    /**
     *
     * @var integer
     */
    public $city;

    /**
     *
     * @var integer
     */
    public $district;

    /**
     *
     * @var string
     */
    public $address;

    /**
     *
     * @var string
     */
    public $productor;

    /**
     *
     * @var string
     */
    public $product_date;

    /**
     *
     * @var integer
     */
    public $expiration_date;

    /**
     *
     * @var integer
     */
    public $is_gene;

    /**
     *
     * @var double
     */
    public $min_price;

    /**
     *
     * @var double
     */
    public $max_price;

    /**
     *
     * @var integer
     */
    public $sell_id;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var string
     */
    public $full_address;

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

    /**
     *
     * @var string
     */
    public $path;
    /**
     * 标签状态
     * @var array
     */
    public static $_STATUS = array(
        0=>'未审核', 
        1=>'已审核通过', 
        2=>'审核未通过', 
        3=>'已过期',
        );
    private $db = null;
  
    public function afterFetch() {
        $this->addtime = $this->add_time ? date("Y-m-d H:i:s",  $this->add_time) : 0;
    }

    public function beforeSave() {
        $this->last_update_time = time();
    }
    public function beforeCreate() {
        $this->status = 0;
        $this->add_time = time();
    }

    public function initialize($db=null)
    {
        
       // 标签图片
       $this->hasOne('tag_id', 'Mdg\Models\TagPicture', 'tag_id', array(
            'foreignKey' => true,
            'alias' => 'TagPicture'
        ));

        #生产信息
        $this->hasOne('tag_id', 'Mdg\Models\TagProduct', 'tag_id', array(
            'foreignKey' => true,
            'alias' => 'TagProduct'
        ));
        #质量评估信息表
        $this->hasOne('tag_id', 'Mdg\Models\TagQuality', 'tag_id', array(
            'foreignKey' => true,
            'alias' => 'TagQuality'
        ));

    }

    public static function checkSellBind($sid=0) {
        $data = self::findFirst(" sell_id = '{$sid}'");
        //查询图片
        return $data ? 0 : 1;
    }
    /**
     * 查询 供应商品是否拥有标签
     * @param  integer $sid 商品id
     * @return array
     */
    public static function selectBySellid ($sid=0) {
        $data = array();

        $data = self::findFirst(" sell_id = '{$sid}' AND status = 1 ");
        //查询图片
        if(!$data) return array();

        $data = $data->toArray();
        $data['imgList'] = M\TagPicture::getTagProductionImgList($data['tag_id']);

        return $data;
    }
    /**
     * 获取标签列表
     * @param  array   $where     di组合条件
     * @param  integer $p         [description]
     * @param  [type]  $orderby   [description]
     * @param  integer $page_size [description]
     * @return [type]             [description]
     */
    public static function getTagList($where = array(), $p =1 ,$orderby='', $page_size = 10) {
        
        $total = self::count( $where );
        $offst = intval(($p - 1) * $page_size);
        $cond[] = $where;
        $cond['order'] = " tag_id desc ";
        $cond['limit'] = array(  $page_size, $offst);

        $data = self::find($cond);
  
        $pages['total_pages'] = ceil($total / $page_size);
        $pages['current'] = $p;
        $pages['total'] = $total;
        
        $pages = new Pages($pages);
        $datas['total_count'] = ceil($total / $page_size);
        $datas['total'] = $total;
        $datas['items'] = $data;
        $datas['start'] = $offst;
        $datas['pages'] = $pages->show(2);
        return $datas;
    }
    /**
     * 检测商品是否可以申请标签
     * @param  integer $sid 商品id
     * @return boolean
     */
    public static function checkSell ($sid=0){
        $data = self::findFirst(" sell_id = '{$sid}'");
        return $data ? $data : array();
    }
    
    /**
     * 查询标签信息
     * @param  integer $tid  标签id
     * @param  string  $cond 条件数据
     * @return array
     */
    public static function getTagInfo($tid=0 ,$conditions = array()) {

        $cond[] = " tag_id = '{$tid}'";
        $data = self::findFirst($cond);

        return $data;
    }
    
    /**
     * 检测标签是否可以审核
     * @param  integer $tid    标签id
     * @param  integer $status 状态
     * @return [type]          [description]
     */
    private $table = " tag";
    public static function checkTagStatus ($tid=0, $status=0, $db=null) {
        $sql = " SELECT status, goods_name,sell_id FROM %s where tag_id = '%s' FOR update ";
  
        $phql = sprintf($sql, ' tag ', $tid);
        $data = $db->fetchOne($phql, 2);
        if(!$data) throw new \Exception('tag not exists');
        if(intval($data['status']) != intval($status) ) throw new \Exception("status error");
        return $data;
    } 
    /**
     * 修改标签状态
     * @param  integer $tid    标签id
     * @param  integer $status 状态值
     * @return boolean
     */
    public static function updateTagStatus ($tid=0,$status=0,$db=null) {
        $sql = "UPDATE tag SET status = '{$status}' WHERE tag_id = '{$tid}'";
        $db->execute($sql);
        if (!$db->affectedRows()) throw new \Exception('errors'); //状态修改失败
    }
    /**
     * 获取已经申请成功的商品id
     * @return string'
     */
    public static function getSellid($where= '') {
        $_sid = array();

        $cond[] = " status = 1  AND {$where}";

        $cond['columns'] = ' sell_id ';
        $data = self::find($cond)->toArray();

        if(!$data) return 0;

        $sid = array_column($data, 'sell_id');
        return join(',', $sid);

    }


}
