<?php
namespace Mdg\Models;
use Mdg\Models as M;
use Mdg\Models\Sell as Sell;
use Mdg\Models\Purchase as Purchase;
use Mdg\Models\Category as Category;
use Lib\Func as Func;
use Lib\Arrays as Arrays;

class Category extends Base
{
    /**
     *
     * @var integer
     */
    
    public $id;
    /**
     *
     * @var string
     */
    
    public $title = "";
    
    public $parent_id = '0';
    
    public $arr_child = '';
    
    public $arr_parent = '';
    
    public $deeps = '';
    
    public $is_groom = '';
    
    public $keyword = '';
    
    public $depict = '';
    
    public $is_show = '1';
    
    public $sell_num = '0';
    
    public $pur_num = '0';
    
    public $abbreviation = '';
     
    public $crop_type=0;


    static $_crop_type = array( 1 => '大田作物', 2 => '经济作物', 0 => '其他' );
    /**
     * 根据标题查询数据
     * @param  [type] $first  标题
     * @param  [type] $second [description]
     * @return [type]         [description]
     */
    static function parseCategory($first, $second) 
    {
        $first = trim($first);
        $second = trim($second);
        $firstInfo = self::findFirstBytitle($first);
        
        if (!$firstInfo) 
        {
            $firstInfo = new self();
            $firstInfo->title = $first;
            
            if (!$firstInfo->save()) 
            {
                return false;
            }
        }
        $where = "title = '{$second}' and parent_id = '" . $firstInfo->id . "'";
        $secInfo = self::findFirst($where);
        
        if (!$secInfo) 
        {
            $secInfo = new self();
            $secInfo->parent_id = $firstInfo->id;
            $secInfo->title = $second;
            
            if (!$secInfo->save()) 
            {
                return false;
            }
        }
        return $secInfo->id;
    }
    static function numAdd($id = 0, $field = '') 
    {
        $info = self::findFirstByid($id);
        $_field = array(
            'sell_num',
            'pur_num'
        );
        
        if ($info && in_array($field, $_field)) 
        {
            $ids = Func::getCols(self::getFamily($id) , 'id', ',');
            $where = "id in ({$ids})";
            $list = self::find($where);
            
            foreach ($list as $cat) 
            {
                $cat->$field+= 1;
                $cat->save();
            }
        }
    }
    static function numDec($id = 0, $field = '') 
    {
        $info = self::findFirstByid($id);
        $_field = array(
            'sell_num',
            'pur_num'
        );
        
        if ($info && in_array($field, $_field)) 
        {
            $ids = Func::getCols(self::getFamily($id) , 'id', ',');
            $where = "id in ({$ids})";
            $list = self::find($where);
          
            foreach ($list as $cat) 
            {
                if($cat->$field){
                    $cat->$field-= 1;
                    $cat->save();
                }
            }
        }
    }
    /**
     * 根据 $class 更新分类总数     
     * @param  integer $id    分类id
     * @param  string  $class sell 供应 pur 采购
     * @return 
     */
    static function numUpdate($id = 0, $class = '') 
    {
        $info = self::findFirstByid($id);
        $_class = array(
            'sell' => 'Sell',
            'pur' => 'Purchase'
        );
        
        if ($info && isset($_class[$class])) 
        {
            $ids = implode(',', self::getChild($id));
            $where = "category in ({$ids})";
            $field = strtolower($class) . '_num';
            $info->$field = $class == 'sell' ? Sell::numCount($where) : Purchase::numCount($where);
            $info->save();
        }
    }
    /**
     * 获取家庭
     */
    static function getFamily($id = 0) 
    {
        $rs = array();
        $data = self::findFirstByid($id);
        
        if (!$data) return $rs;
        $rs[] = $data->toArray();
        
        while ($data && $data->parent_id) 
        {
            $data = self::findFirstByid($data->parent_id);
            
            if ($data) $rs[] = $data->toArray();
        }
        //        print_r(array_reverse($rs));die;
        return array_reverse($rs);
    }
    
    /**
     * 根据父类获取子级信息
     * @param  integer $pid 父级id
     * @return array
     */
    public function selectByparent_idtoChild($pid=0) {

    }

    /**
     * 获取子分类
     * @param  integer $cid 分类id
     * @return arrat
     */
    static function getChild($cid = 0) 
    {
        $rs = array();
        $data = self::find("id = '{$cid}'")->toArray();
        
        while (!empty($data)) 
        {
            $ids = Func::getCols($data, 'id');
            $rs = array_merge($rs, $ids);
            $data = self::find("parent_id in (" . implode(', ', $ids) . ")")->toArray();
        }
        sort($rs);
        return $rs;
    }
    /**
     * 改变状态
     * @param  [type] $id    分类id
     * @param  string $field 字段
     * @param  string $state 状态
     * @return
     */
    static function changeState($id,$field,$deep,$state) 
    {
        $child =Self::getChild($id);
     
        $parent = Func::getCols(Self::getFamily($id) , 'id');
       
        $ids = array_unique(array_merge($child, $parent));
      
        if (empty($ids)) return;
        $where = ' id in (' . implode(',', $ids) . ')';
        $data = self::find($where);
      
        foreach ($data as $key=>$val) 
        {   
            $val->$field = $state;
            $val->save();
        }
    }
    /**
     * 获取自己分类
     * @param  integer $id 父级分类
     * @return array
     */
    static function getChildcate($id = 0) 
    {
        $rs = array();
        $data = self::find("parent_id = '{$id}'")->toArray();
        
        if (!empty($data)) 
        {
            $ids = Func::getCols($data, 'id');
            $rs = array_merge($rs, $ids);
        }
        $rs1 = implode(",", $rs);
        return $rs1;
    }
    /**
     * 更改状态
     */
    static function upstate($pid = '', $type, $e) 
    {
        $rs = array();
        $data = self::find(" id in ({$pid})")->toArray();
        
        foreach ($data as $key => $value) 
        {
            $state = self::findFirstByid($value["id"]);
            $state->$type = $e;
            $state->save();
        }
    }
    /**
     * @param array $arr 数据源
     * @param string $key_node_id 节点ID字段名
     * @param string $key_parent_id 节点父ID字段名
     * @param string $key_children 保存子节点的字段名
     * @param boolean $refs 是否在返回结果中包含节点引用
     *
     * return array 树形结构的数组
     */
    static function toTree($arr, $key_node_id, $key_parent_id = 'parent_id', $key_children = 'children', &$refs = null) 
    {
        $refs = array();
        //print_r($arr);die;
        
        foreach ($arr as $offset => $row) 
        {
            $arr[$offset][$key_children] = array();
            $refs[$row[$key_node_id]] = & $arr[$offset];
        }
        $tree = array();
        
        foreach ($arr as $offset => $row) 
        {
            $parent_id = $row[$key_parent_id];
            
            if ($parent_id) 
            {
                
                if (!isset($refs[$parent_id])) 
                {
                    $tree[$row[$key_node_id]] = & $arr[$offset];
                    continue;
                }
                $parent = & $refs[$parent_id];
                $parent[$key_children][$row[$key_node_id]] = & $arr[$offset];
            }
            else
            {
                $tree[$row[$key_node_id]] = & $arr[$offset];
            }
        }
        return $tree;
    }
    /**
     * 将树形数组展开为平面的数组
     *
     * 这个方法是 tree() 方法的逆向操作。
     *
     * @param array $tree 树形数组
     * @param string $key_children 包含子节点的键名
     *
     * @return array 展开后的数组
     */
    static function treeToArray($tree, $without = 0, $key_children = 'children', $deep = 1) 
    {
        $ret = array();
        
        if (isset($tree[$key_children]) && is_array($tree[$key_children])) 
        {
            $children = $tree[$key_children];
            unset($tree[$key_children]);
            $tree['deep'] = $deep;
            $ret[] = $tree;
            
            foreach ($children as $key => $node) 
            {
                
                if ($key != $without) 
                {
                    $ret = array_merge($ret, self::treeToArray($node, $without, $key_children, $deep + 1));
                }
            }
        }
        else
        {
            unset($tree[$key_children]);
            $ret[] = $tree;
        }
        return $ret;
    }
    /**
     * 返回商品分类
     * @param  [type] $cateid [description]
     * @return [type]         [description]
     */
    static 
    public function tocategroy($cateid) 
    {
        $cate = Category::findFirstByid($cateid);
        return $cate;
    }
    /**
     * 获取分类
     * @return [type] [description]
     */
    static 
    public function showcategroy() 
    {
        $category = Category::find("  is_groom =1 order by deeps desc ")->toArray();
        $cat_list = Category::totree($category, 'id', 'parent_id', 'child');
        return $cat_list;
    }
    /**
     * 获取以及分类名称
     */
    static function getparent($id = 0) 
    {
        $data = self::findFirstByid($id);
        if(!$data) return '';
        $data = $data->toArray();
        $parent = self::findFirstByid($data['parent_id']);
        return $parent ? $parent->title  :  '';
    }
    static function shopcategory($goods = array()) 
    {
        $categroy = Arrays::getCols($goods, 'category');
        
        foreach ($categroy as $key => $value) 
        {
            
            if ($value == 0) 
            {
                unset($categroy[$key]);
            }
        }
        
        if (!$categroy) 
        {
            return false;
        }
        $categroys = implode(",", $categroy);
        $purchasecate = Category::find(" id in ($categroys)  ")->toArray();
        $getcols = Arrays::getCols($purchasecate, 'parent_id');
        $unique = array_unique($getcols);
        $imp = implode(",", $unique);
        $purchasecates = Category::find(" id in ($imp)  ")->toArray();
        $purchasecatehory = array_merge($purchasecate, $purchasecates);
        $purchasecatehorys = Arrays::toTree($purchasecatehory, 'id', 'parent_id', 'children');
        return $purchasecatehorys;
    }
    /**
     * 获取子级
     * @param  integer $id    父级id
     * @param  [type]  $limit 条数
     * @return array
     */
    static function threecate($id = 0,$limit=10) 
    { 
        $threecategory = self::find(" parent_id = $id order by deeps desc  LIMIT {$limit} ")->toArray();
        return $threecategory ? $threecategory : array();
    }
    static public function gettopcate(){
        $maxcategory=M\Category::find(" is_groom=1 and parent_id= 0 order by deeps desc ")->toArray();
        $maxcategoryid=Arrays::getCols($maxcategory,'id',',');
        $twocategory=M\Category::find(" is_groom=1 and  parent_id in ($maxcategoryid) order by deeps desc ")->toArray();  
        $grouptwocategory=Arrays::groupBy($twocategory,'parent_id');
        foreach ($grouptwocategory as $key => $value) {
              if($key=='899'){
                $cate['899']=$grouptwocategory[$key][0]["id"];
              }
              if($key=='7'){
                $cate['7']=$grouptwocategory[$key][0]["id"];
              }
              if($key=='1'){
                $cate['1']=$grouptwocategory[$key][0]["id"];
              }
              if($key=='2'){
                $cate['2']=$grouptwocategory[$key][0]["id"];
              }
              if($key=='1377'){
                $cate['1377']=$grouptwocategory[$key][0]["id"];
              }
        }
        $arr["twocategory"]=$twocategory;
        $arr["cate"]=$cate;
        return $arr;
    }
    /**
     * 根据分类id 获取分类名称
     * @param  integer $cid 分类id
     * @return string
     */
    public static function selectBytocateName($cid=0){
        $data = self::findFirst( " id = '{$cid}'");
        return $data ? $data->title : '';
    }

    /**
     * 获取分类首字母
     * @return array
     */
    public static function getCateByFirst($db=null, $pid=0) {
      //所有分类
      $data = array( 'A' => 'A', 'B' => 'B','C' => 'C' , 'D' => 'D', 'E' => 'E', 'F' => 'F', 'G' => 'G', 'H' => 'H', 'I' => 'I', 'J' => 'J', 'K' => 'K',
        'L' => 'L', 'M' => 'M', 'N' => 'N', 'O' => 'O', 'P' => 'P', 'Q' => 'Q', 'R' =>'R', 'S' =>'S' , 'T' => 'T', 'U' => 'U', 'V' => 'V', 'W' => 'W', 'X' => 'X', 'Y' => 'Y', 'Z' => 'Z');
      return $data;
      $where = ' 1 ';
      if($pid) {
        $where = " parent_id  = '{$pid}'";
      }
      $sql = " SELECT abbreviation from category where {$where} group by abbreviation ";
      $data = $db->fetchAll($sql,2);
      return $data;
     
    } 
    /**
     * 检测底下是否有数据
     * @param  integer $cid 分类id
     * @return boolean
     */
    public static function  checkChid($cid=0) {
        $cond[] = " parent_id = '{cid}' AND is_show = 1 ";
        return  self::count($cond);
    }
    /**
     * 获取所有顶级分类
     * @return [type] [description]
     */
    public static function getTopCaetList() {
      $cond[] = " parent_id = '0' and is_show=1 ";
      return self::find($cond);
    }

    /**
     * 根据分类id 获取分类名称
     * @param  integer $cid 分类id
     * @return string
     */
    public static function selectBytocateabbrev($cid=0){
        $data = self::findFirst( " id = '{$cid}'");
        return $data ? $data->abbreviation : '';
    }
    /**
     * 根据分类id 获取分类名称
     * @param  integer $cid 分类id
     * @return string
     */
    public static function selectBytocateparent_id($cid=0){
        $data = self::findFirst( " id = '{$cid}'");
        return $data ? $data->parent_id : 0;
    }
    /**
     * 根据分类名称 获取分类id
     * @param  string $title 分类名称
     * @return integer
     */
    public static function selectBytitle($title){
        $data = self::findFirst( " title = '{$title}'");
        return $data ? $data->id: 0;
    }    
}
