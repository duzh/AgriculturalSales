<?php

namespace Mdg\Models;

use Lib\Func as Func;

class ProductCategory extends \Phalcon\Mvc\Model
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
    public $pid;

    /**
     *
     * @var string
     */
    public $catname;

    /**
     *
     * @var integer
     */
    public $sortrank;

    /**
     *
     * @var integer
     */
    public $is_show;

    /**
     *
     * @var string
     */
    public $keywords;

    /**
     *
     * @var string
     */
    public $description;

    /**
     *
     * @var integer
     */
    public $addtime;

    static $_is_show = array(
            0 => '否',
            1 => '是',
        );
    static $position= array(
            0=>'文章分类',
            1=>'评价标准',
            2=>'产品案例',
        );
    public function getSource()
    {
        return 'product_category';
    }

    static function getChild($id) {
        $rs = array();
        $data = self::find("id = '{$id}'")->toArray();

        while (!empty($data)) {
            $ids = Func::getCols($data, 'id');
            $rs = array_merge($rs, $ids);
            $data = self::find("pid in (".implode(', ', $ids).")")->toArray();
        }
        sort($rs);
        return $rs;
    }

    public function initialize()
    {
        $this->hasMany("id", "Mdg\Models\Article", "cid", array('alias' => 'art'));
    }

    public function getArt($parameters=array('limit' => 3))
    {
        return $this->getRelated('art', $parameters);
    }
    /**
     * 获得文章
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    static public function getcate($id){
        $cate=Article::find(" cid=".$id." and is_show=1 ");
        return $cate->toArray();
    }
    /**
     * 获取文章分类
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    static public function getcategory($id){
      
        if($id==0){
            return "系统分类";
        }else{
             $category=self::findFirstByid($id);
             return $category ? $category->catname : '-';
        }  
    }
    /**
     * 返回所有父及分类
     * @return [type] [description]
     */
    static public function showarticle(){
        $category=ProductCategory::find("is_show=1")->toArray();
        return $category;
    }
   
}
