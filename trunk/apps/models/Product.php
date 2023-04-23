<?php

namespace Mdg\Models;

class Product extends \Phalcon\Mvc\Model
{
     /**
     * 获取文章类型
     */
    static $_is_show = array(
        0 => '否',
        1 => '是',
    );
    /**
     *
     * @var integer
     */
    public $id=0;

    /**
     *
     * @var integer
     */
    public $cid=0;

    /**
     *
     * @var string
     */
    public $title='';

    /**
     *
     * @var string
     */
    public $keywords='';

    /**
     *
     * @var string
     */
    public $tags='';

    /**
     *
     * @var string
     */
    public $description='';

    /**
     *
     * @var string
     */
    public $content='';

    /**
     *
     * @var integer
     */
    public $addtime=0;

    /**
     *
     * @var integer
     */
    public $updatetime=0;

    /**
     *
     * @var integer
     */
    public $is_show=1;

    /**
     *
     * @var integer
     */
    public $count=0;
    
    public function getSource()
    {
        return 'product';
    }
    /**
     * 点击量
     * @param  integer $aid [description]
     * @return [type]       [description]
     */
    static function clickAdd($aid=0) {
        $article = self::findFirstByid($aid);
        if($article) {
            $article->count += 1;
            $article->save();
        }
    }
    /**
     * 返回文章分类名称
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    static public function returncategory($id){
        
        $article=ProductCategory::findFirstByid($id);
        return $article ? $article->catname:"系统分类";
    }
     /**
     * 计算农药残留量
     * @param  int $c 农药原始浓度（%）
     * @param  int $n 农药稀释倍数
     * @param  int $x 喷药后天数
     * @return int
     */
    static public function jisuan($c=5, $n=400, $x=6) {
        $Kp = 0.1038; // 农药调整系数
        $Ke = -0.2161; // 环境调整系数
        $y=pow(10, 4) * $Kp * ($c / $n ) * exp( $Ke * $x );
        return round($y,3);
    }
    static public function  productcategory(){
        $arr=self::find(" is_show=1");
        return $arr;
    }

}

