<?php

namespace Mdg\Models;

class Article extends \Phalcon\Mvc\Model
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
        return 'article';
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
        $article=ArticleCategory::findFirstByid($id);

        return $article ? $article->catname:"系统分类";
    }


    /**
     * 根据分类获取文章信息
     * @param  integer $cid 分类id
     * @return [type]       [description
     */
    public static function selectBycid($cid=0) {
        $cond[] = " cid = '{$cid}'";
        $cond['order'] = ' id desc ';
        $cond['limit'] = 20;
        $data = self::find($cond)->toArray();
        return $data;
    }
   

}
