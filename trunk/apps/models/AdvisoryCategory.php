<?php
namespace Mdg\Models;
use Lib\Func as Func;

class AdvisoryCategory extends Base
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
    static $position = array(
        0 => '文章分类',
        1 => '评价标准',
        2 => '产品案例',
    );
    
    public function getSource() 
    {
        return 'advisory_category';
    }
    static function getChild($id) 
    {
        $rs = array();
        $data = self::find("id = '{$id}'")->toArray();
        
        while (!empty($data)) 
        {
            $ids = Func::getCols($data, 'id');
            $rs = array_merge($rs, $ids);
            $data = self::find("pid in (" . implode(', ', $ids) . ")")->toArray();
        }
        sort($rs);
        return $rs;
    }
    
    public function initialize() 
    {
        $this->hasMany("id", "Mdg\Models\Article", "cid", array(
            'alias' => 'art'
        ));
    }
    
    public function getArt($parameters = array(
        'limit' => 3
    )) 
    {
        return $this->getRelated('art', $parameters);
    }
    /**
     * 获得文章
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    static 
    public function getcate($id) 
    {
        $cate = Article::find(" cid=" . $id . " and is_show=1 ");
        return $cate->toArray();
    }
    /**
     * 获取文章分类
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    static 
    public function getcategory($id) 
    {
        
        if ($id == 0) 
        {
            return "系统分类";
        }
        else
        {
            $category = self::findFirstByid($id);
            return $category ? $category->catname : '-';
        }
    }
    /**
     * 返回所有父及分类
     * @return [type] [description]
     */
    static 
    public function showarticle() 
    {
        $category = ProductCategory::find("is_show=1")->toArray();
        return $category;
    }
	/**
	 * 根据推荐获取分类
	 */
	public static function getCatnameArray() {
	
		// 定义数组
		$result = array();
		$where	= " pid=0 AND is_show=1";
		$result[1] = array_column(self::find($where . " ORDER BY id desc")->toArray(),'catname','id');
		$result[2] = array_column(self::find($where . " AND catname!='公告' ORDER BY id desc ")->toArray(),'catname','id');
		$result[3] = $result[2];
		$result[4] = array_column(self::find($where . " AND catname='公告' ORDER BY id desc ")->toArray(),'catname','id');
		$result[5] = $result[1];
		return $result;
	}

    
}
