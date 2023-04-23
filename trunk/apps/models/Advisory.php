<?php
namespace Mdg\Models;
use Lib\Pages as Pages;
use Mdg\Models\AdvisoryCategory as AdvisoryCategory;

class Advisory extends \Phalcon\Mvc\Model
{
    /**
     * 获取文章类型
     */
    static $_is_show = array(
        0 => '否',
        1 => '是',
    );
	static $_is_recom = array(
        1 => '相关推荐',
        2 => '副轮播',
        3 => '带图标题',
        4 => '公告版',
        5 => '主轮播',
    );
	static $_cat_recom = array(
        1 => array(
			3=> '新闻',
			7=> '动态',
			6=> '活动',
			8=> '公告'
		),
        2 =>  array(
			3=> '新闻',
			7=> '动态',
			6=> '活动'
		),
        3 =>  array(
			3=> '新闻',
			7=> '动态',
			6=> '活动'
		),
        4 =>  array(
			8=> '公告'
		),
        5 =>  array(
			3=> '新闻',
			7=> '动态',
			6=> '活动',
			8=> '公告'
		)
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
    
    /**
     *
     * @var string
     */
    public $thumb='';

    /**
     *
     * @var integer
     */
    public $is_hot=0;
    
    static $_order = array(
        'default' => '发布时间',
        'count'   => '阅读量',
    );
    
    public function getSource()
    {
        return 'advisory';
    }
	
    /**
     * 从临时表复制信息
     * @param  int $id  咨询ID
     * @param  string $sid  session_id md5值
     */
    static function insertImages($id, $sid='',$path='') {
		
        $sell	= Advisory::findFirstByid($id);
        if($sell){
            $sell->thumb = $path;
            $sell->save();    
        }
    }
	
    /**
     * 点击量
     * @param  integer $aid [description]
     * @return [type]       [description]
     */
    static function clickAdd($aid=0) {
        $article = self::findFirstByid($aid);
        if($article) {
            $article->count += 100;
            $article->save();
        }
    }
	//
    static function advisoryClickAdd($aid=0) {
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
        
        $article=AdvisoryCategory::findFirstByid($id);
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

    /**
     * 获取分类今天 资讯
     * @param  integer $cid 分类id
     * @return array
     */
    public static function getdayList ($cid=0, $limit=10) {
        if($cid) {
            $cond[] = " cid ='{$cid}'";
        }
        $cond['order'] = 'id desc ';
        $cond['limit'] = $limit;
        $cond['columns'] = 'title, id, content,description,addtime';
        $data = self::find($cond);
        return $data;
    }
    
    /**
     * 获取汇资讯首页
     * @param  integer $catId 分类id
     * @return array
     */
    public static function getNewsList( $catId ) {
        
        // 定义数组
        $result = array();
        // 判断此分类是否有数据
        if( !self::findFirstBycid($catId) ) {
            return array(
				'reList'	=>array(),
				'newsList'	=>array(),
				'hotList'	=>array(),
				'carousel'	=>array(),
				'notice'	=>array(),
				'newNotice'	=>array()
			);
            break;
        }
		
		// 获取所有分类ID
		$ids	= AdvisoryCategory::getChild($catId);
		$cond	= "is_show=1 AND cid in(".implode(',', $ids).")";

		if ( $catId == 8 ) {
          
			// 公告
			// 获取主轮播
			$result['carousel']	= self::find("is_show=1 AND is_hot=5 ORDER BY recomtime desc limit 4")->toArray();
			// 获取公告板
			$result['notice']	= self::find("is_show=1 AND is_hot=4 ORDER BY recomtime desc limit 1")->toArray();
			// 最新公告
			$result['newNotice']= self::find("$cond  ORDER BY recomtime desc limit 7")->toArray();#AND is_hot=0
		} else {
			// 活动相册显示12个
			if ( $catId == 6 ) {
				$num	= 12;
			} else {
				$num	= 10;
			}
			$limit = 3;
			if ($catId == 7){
				$limit = 2;
			}
			// 获取副轮播
			$result['reList']	= self::find("$cond AND is_hot=2 ORDER BY recomtime desc limit {$limit}")->toArray();
			// 获取新闻
			$result['newsList'] = self::find("$cond AND is_hot=0 ORDER BY recomtime desc limit 4")->toArray();
			// 获取热门
			$result['hotList']  = self::find("$cond ORDER BY count desc limit $num")->toArray();
			// 带图标题	
			$result['imgTitle'] = self::find("$cond AND is_hot=3 ORDER BY recomtime desc limit 2")->toArray();		
			//echo "<pre>";
			//var_dump("$cond AND is_hot=3 ORDER BY recomtime desc limit 2");exit;
		}
        return $result;
    }
    
    /**
     * 获取汇资讯列表页
     * @param  integer $page
     * @param  integer $offst
     * @param  integer $pageSize
     * @param  integer $order
     * @param  integer $catId   分类id
     * @return array
     */
    public static function getcatList($keywords , $page, $offst, $pageSize, $catId, $order ) {

       
        // 定义数组
        $catList    = $recomList = array();
 
        $where='';
		if($keywords) {
			$where = " title LIKE '{$keywords}%' AND ";
		}
        if($catId==''){
            $where .= " is_show=1";
        }else{
            $where .= "cid = '$catId' AND is_show=1";
        }
      
        $total      = self::count(" $where ");//AND is_hot=0 
        if (!$order){
			$order = ' addtime DESC';
		}
        // echo $total;die;
        // 列表
        $catsList   = self::find("$where ORDER BY  $order LIMIT $offst,$pageSize")->toArray();//AND is_hot=0
		//$catsList   = self::find("$where ORDER BY $order LIMIT $offst,$pageSize")->toArray();
        // 推荐
        $recomList	= self::find("$where AND is_hot=1 ORDER BY recomtime DESC limit 5")->toArray();//LIMIT $offst,$pageSize
		
        // 分页
        $pages['total_pages']   = ceil($total / $pageSize);
        $pages['current']       = $page;
        $pages['total']         = $total;
        $pages  = new Pages($pages);
        $catList['total_pages']	= ceil($total / $pageSize);
        $catList['total']   = $total;
        $catList['items']   = $catsList;
        $catList['pages']   = $pages->show(4);
        $catList['reList']  = $recomList;
		$catList['newpages']= $pages->show(8);
        $catList['newpages']= str_replace(array('下一页', '上一页'), '', $catList['newpages']);
        if($catList){
            return $catList;
        } else {
            return array();
        }
    }
    
    /**
     * 获取汇资讯列表页-排序
     * @param  integer $selected
     * @param  integer $order
     * @return array
     */
    public static function getOrder( $selected='default', $order = 'desc' ) {
        
        $tpl    = '';
        $params = $_GET;
        $get    = array_diff_key($params,self::$_order);
        unset($get['_url']);
        $query  = http_build_query($get);

        foreach (self::$_order as $key => $value) {
            $class = "";
            if($selected == $key){
                $orders = $order == "asc" ? 'desc' : 'asc';
            }else{
                $orders = 'desc';
            }
		if ($key == 'default'){
			$class  = ' class = "fb-time"';
		}else{
			$class  = ' class = "read-num"';
		}

            $tpl .= sprintf('<a %s href="/advisory/newslist?%s&%s=%s">%s</a>',$class,$query,$key,$orders,$value);
        }
        return $tpl;
    }
	
	/**
     * 获取汇资讯详情页
     * @param  integer $id
     * @return array
     */
    public static function getAdvisoryInfo( $id ) {
        
		// 定义数组
		$infoMess	= $recomInfo = $hotInfo= array();
        // 获取详情
		$infoMess   = self::findFirst("id = $id AND is_show=1")->toArray();
		if( !$infoMess ){
			return array(
				'recomList'	=> $recomInfo,
				'infoMess' 	=> $infoMess,
				'hotMess'	=> $hotInfo
			);
		}
		// 获取推荐
		$recomInfo	= self::find("cid = '{$infoMess['cid']}' AND is_show=1 AND is_hot=1 ORDER BY count DESC limit 5")->toArray();
		// 获取热门
		$hotInfo	= self::find("cid = '{$infoMess['cid']}' AND is_show=1 ORDER BY count DESC limit 5")->toArray();
		return array(
			'recomList'	=> $recomInfo,
			'infoMess' 	=> $infoMess,
			'hotMess'	=> $hotInfo,
		);
    }
	
	/**
     * 获取推荐数据
     * @param  integer $page
     * @param  integer $offst
     * @param  integer $pageSize
     * @return array
     */
    public static function getRecomList( $db, $page, $offst, $pageSize ,$where ) {
		
		// 判断条件
		if(isset($where) && $where) {
			$cond = " $where ";
		} else {
			$cond = " is_hot!=0 ";
		}
		// 定义数组
        $recomList  = array();
        $total      = $db->fetchOne( "SELECT count(a.id) as total FROM advisory as a left join advisory_category as ac ON a.cid = ac.id WHERE $cond", 2 )['total'];
        // 推荐
        $list		= $db->fetchAll("SELECT ac.catname,a.title,a.id,a.is_hot,a.recomtime FROM advisory as a left join advisory_category as ac ON a.cid = ac.id WHERE $cond ORDER BY recomtime desc LIMIT $offst,$pageSize",2);
        // 分页
        $pages['total_pages']   = ceil($total / $pageSize);
        $pages['current']       = $page;
        $pages['total']         = $total;
        $pages  = new Pages($pages);
        $recomList['total_pages']	= ceil($total / $pageSize);
        $recomList['total']   		= $total;
        $recomList['items']   		= $list;
        $recomList['pages']   		= $pages->show(1);
		
        return $recomList;
	}
}