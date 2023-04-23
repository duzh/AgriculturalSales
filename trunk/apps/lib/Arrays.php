<?php
namespace Lib;

class Arrays{
	/**
     * 从数组中删除空白的元素（包括只有空白字符的元素）
     *
     * 用法：
     * @code php
     * $arr = array('', 'test', '   ');
     * Helper_Array::removeEmpty($arr);
     *
     * dump($arr);
     *   // 输出结果中将只有 'test'
     * @endcode
     *
     * @param array $arr 要处理的数组
     * @param boolean $trim 是否对数组元素调用 trim 函数
     */
    static function removeEmpty(& $arr, $trim = true) {
        foreach ($arr as $key => $value) {
            if (is_array($value)) {
                self::removeEmpty($arr[$key]);
            } else {
                $value = trim($value);
                if ($value == '') {
                    unset($arr[$key]);
                }
                elseif ($trim) {
                    $arr[$key] = $value;
                }
            }
        }
    }

    /**
     * 从一个二维数组中返回指定键的所有值
     *
     * 用法：
     * @code php
     * $rows = array(
     *     array('id' => 1, 'value' => '1-1'),
     *     array('id' => 2, 'value' => '2-1'),
     * );
     * $values = Helper_Array::cols($rows, 'value');
     *
     * dump($values);
     *   // 输出结果为
     *   // array(
     *   //   '1-1',
     *   //   '2-1',
     *   // )
     * @endcode
     *
     * @param array $arr 数据源
     * @param string $col 要查询的键
     * @param string 分隔符
     * @return array 包含指定键所有值的数组
     */
    static function getCols($arr, $col,$delimiter='') {
        $ret = array();
        if(is_object($arr)) $arr = (array) $arr;

        foreach ($arr as $row) {
            if(is_object($row)) $row = (array) $row;
            if (isset($row[$col])) {
                $ret[] = $row[$col];
            }
        }
		if($delimiter){
			return implode($delimiter,$ret);
		}
        return $ret;
    }

    /**
     * 将一个二维数组转换为 HashMap，并返回结果
     *
     * 用法1：
     * @code php
     * $rows = array(
     *     array('id' => 1, 'value' => '1-1'),
     *     array('id' => 2, 'value' => '2-1'),
     * );
     * $hashmap = Helper_Array::hashMap($rows, 'id', 'value');
     *
     * dump($hashmap);
     *   // 输出结果为
     *   // array(
     *   //   1 => '1-1',
     *   //   2 => '2-1',
     *   // )
     * @endcode
     *
     * 如果省略 $value_field 参数，则转换结果每一项为包含该项所有数据的数组。
     *
     * 用法2：
     * @code php
     * $rows = array(
     *     array('id' => 1, 'value' => '1-1'),
     *     array('id' => 2, 'value' => '2-1'),
     * );
     * $hashmap = Helper_Array::toHashmap($rows, 'id');
     *
     * dump($hashmap);
     *   // 输出结果为
     *   // array(
     *   //   1 => array('id' => 1, 'value' => '1-1'),
     *   //   2 => array('id' => 2, 'value' => '2-1'),
     *   // )
     * @endcode
     *
     * @param array $arr 数据源
     * @param string $key_field 按照什么键的值进行转换
     * @param string $value_field 对应的键值
     *
     * @return array 转换后的 HashMap 样式数组
     */
    static function toHashmap($arr, $key_field, $value_field = null) {
        $ret = array();
        if ($value_field) {
            foreach ($arr as $row) {
                $ret[$row[$key_field]] = $row[$value_field];
            }
        } else {
            foreach ($arr as $row) {
                $ret[$row[$key_field]] = $row;
            }
        }
        return $ret;
    }

    /**
     * 将一个二维数组按照指定字段的值分组
     *
     * 用法：
     * @code php
     * $rows = array(
     *     array('id' => 1, 'value' => '1-1', 'parent' => 1),
     *     array('id' => 2, 'value' => '2-1', 'parent' => 1),
     *     array('id' => 3, 'value' => '3-1', 'parent' => 1),
     *     array('id' => 4, 'value' => '4-1', 'parent' => 2),
     *     array('id' => 5, 'value' => '5-1', 'parent' => 2),
     *     array('id' => 6, 'value' => '6-1', 'parent' => 3),
     * );
     * $values = Helper_Array::groupBy($rows, 'parent');
     *
     * dump($values);
     *   // 按照 parent 分组的输出结果为
     *   // array(
     *   //   1 => array(
     *   //        array('id' => 1, 'value' => '1-1', 'parent' => 1),
     *   //        array('id' => 2, 'value' => '2-1', 'parent' => 1),
     *   //        array('id' => 3, 'value' => '3-1', 'parent' => 1),
     *   //   ),
     *   //   2 => array(
     *   //        array('id' => 4, 'value' => '4-1', 'parent' => 2),
     *   //        array('id' => 5, 'value' => '5-1', 'parent' => 2),
     *   //   ),
     *   //   3 => array(
     *   //        array('id' => 6, 'value' => '6-1', 'parent' => 3),
     *   //   ),
     *
     *   // )
     * @endcode
     *
     * @param array $arr 数据源
     * @param string $key_field 作为分组依据的键名
     *
     * @return array 分组后的结果
     */
     static function groupBy($arr, $key_field,$pk=false) {
        $ret = array();
        foreach ($arr as $row) {
            $key = $row[$key_field];
            if($pk){
                $k = $row[$pk];
                $ret[$key][$k] = $row;
            }else{
                $ret[$key][] = $row;
            }
            
        }
        return $ret;
    }

    /**
     * 将一个平面的二维数组按照指定的字段转换为树状结构
     *
     * 用法：
     * @code php
     * $rows = array(
     *     array('id' => 1, 'value' => '1-1', 'parent' => 0),
     *     array('id' => 2, 'value' => '2-1', 'parent' => 0),
     *     array('id' => 3, 'value' => '3-1', 'parent' => 0),
     *
     *     array('id' => 7, 'value' => '2-1-1', 'parent' => 2),
     *     array('id' => 8, 'value' => '2-1-2', 'parent' => 2),
     *     array('id' => 9, 'value' => '3-1-1', 'parent' => 3),
     *     array('id' => 10, 'value' => '3-1-1-1', 'parent' => 9),
     * );
     *
     * $tree = Helper_Array::tree($rows, 'id', 'parent', 'nodes');
     *
     * dump($tree);
     *   // 输出结果为：
     *   // array(
     *   //   array('id' => 1, ..., 'nodes' => array()),
     *   //   array('id' => 2, ..., 'nodes' => array(
     *   //        array(..., 'parent' => 2, 'nodes' => array()),
     *   //        array(..., 'parent' => 2, 'nodes' => array()),
     *   //   ),
     *   //   array('id' => 3, ..., 'nodes' => array(
     *   //        array('id' => 9, ..., 'parent' => 3, 'nodes' => array(
     *   //             array(..., , 'parent' => 9, 'nodes' => array(),
     *   //        ),
     *   //   ),
     *   // )
     * @endcode
     *
     * 如果要获得任意节点为根的子树，可以使用 $refs 参数：
     * @code php
     * $refs = null;
     * $tree = Helper_Array::toTree($rows, 'id', 'parent', 'nodes', $refs);
     *
     * // 输出 id 为 3 的节点及其所有子节点
     * $id = 3;
     * dump($refs[$id]);
     * @endcode
     *
     * @param array $arr 数据源
     * @param string $key_node_id 节点ID字段名
     * @param string $key_parent_id 节点父ID字段名
     * @param string $key_children 保存子节点的字段名
     * @param boolean $refs 是否在返回结果中包含节点引用
     *
     * return array 树形结构的数组
     */
    static function toTree($arr, $key_node_id, $key_parent_id = 'parent_id',
                           $key_children = 'children', & $refs = null) {
        $refs = array();
        foreach ($arr as $offset => $row) {
            
            $arr[$offset][$key_children] = array();
            $refs[$row[$key_node_id]] =& $arr[$offset];
        }
        
        $tree = array();
        foreach ($arr as $offset => $row) {
            $parent_id = $row[$key_parent_id];
            if ($parent_id) {
                if (!isset($refs[$parent_id])) {
                    $tree[$row[$key_node_id]] =& $arr[$offset];
                    continue;
                }
                $parent =& $refs[$parent_id];
                $parent[$key_children][$row[$key_node_id]] =& $arr[$offset];
            } else {
                $tree[$row[$key_node_id]] =& $arr[$offset];
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
    static function treeToArray($tree, $without=0,$key_children = 'children',$deep=1) {
        $ret = array();
        if (isset($tree[$key_children]) && is_array($tree[$key_children])) {
            $children = $tree[$key_children];
            unset($tree[$key_children]);
            $tree['deep'] = $deep;
            $ret[] = $tree;
            foreach ($children as $key => $node) {
                if ($key != $without) {
                    $ret = array_merge($ret, self::treeToArray($node, $without,$key_children,$deep+1));
                }
            }

        } else {
            unset($tree[$key_children]);
            $ret[] = $tree;
        }

        return $ret;
    }

    /**
     * 根据指定的键对数组排序
     *
     * 用法：
     * @code php
     * $rows = array(
     *     array('id' => 1, 'value' => '1-1', 'parent' => 1),
     *     array('id' => 2, 'value' => '2-1', 'parent' => 1),
     *     array('id' => 3, 'value' => '3-1', 'parent' => 1),
     *     array('id' => 4, 'value' => '4-1', 'parent' => 2),
     *     array('id' => 5, 'value' => '5-1', 'parent' => 2),
     *     array('id' => 6, 'value' => '6-1', 'parent' => 3),
     * );
     *
     * $rows = Helper_Array::sortByCol($rows, 'id', SORT_DESC);
     * dump($rows);
     * // 输出结果为：
     * // array(
     * //   array('id' => 6, 'value' => '6-1', 'parent' => 3),
     * //   array('id' => 5, 'value' => '5-1', 'parent' => 2),
     * //   array('id' => 4, 'value' => '4-1', 'parent' => 2),
     * //   array('id' => 3, 'value' => '3-1', 'parent' => 1),
     * //   array('id' => 2, 'value' => '2-1', 'parent' => 1),
     * //   array('id' => 1, 'value' => '1-1', 'parent' => 1),
     * // )
     * @endcode
     *
     * @param array $array 要排序的数组
     * @param string $keyname 排序的键
     * @param int $dir 排序方向
     *
     * @return array 排序后的数组
     */
    static function sortByCol($array, $keyname, $dir = SORT_ASC) {
        return self::sortByMultiCols($array, array($keyname => $dir));
    }

    /**
     * 将一个二维数组按照多个列进行排序，类似 SQL 语句中的 ORDER BY
     *
     * 用法：
     * @code php
     * $rows = Helper_Array::sortByMultiCols($rows, array(
     *     'parent' => SORT_ASC,
     *     'name' => SORT_DESC,
     * ));
     * @endcode
     *
     * @param array $rowset 要排序的数组
     * @param array $args 排序的键
     *
     * @return array 排序后的数组
     */
    static function sortByMultiCols($rowset, $args) {
        $sortArray = array();
        $sortRule = '';
        foreach ($args as $sortField => $sortDir) {
            foreach ($rowset as $offset => $row) {
                $sortArray[$sortField][$offset] = $row[$sortField];
            }
            $sortRule .= '$sortArray[\'' . $sortField . '\'], ' . $sortDir . ', ';
        }
        if (empty($sortArray) || empty($sortRule)) {
            return $rowset;
        }
        eval('array_multisort(' . $sortRule . '$rowset);');
        return $rowset;
    }
    /*
    //根据条件查询其子孙
    static function getFamilyTree($tree,$key_node_id,$key_parent_id,$id=0,$deep=0) {
        $tmplist = array();
        foreach($tree as $v) {
            if ($v[$key_parent_id] == $id) {
                $v['deep'] = $deep;
                $tmplist[] = $v;
                $tmplist = array_merge($tmplist,self::getFamilyTree($tree,$key_node_id,$key_parent_id,$v[$key_node_id],$deep+1));
            }
        }
        return $tmplist;
    }
    */
    /**
     * 对字符串或数组进行格式化，返回格式化后的数组
     *
     * $input 参数如果是字符串，则首先以“,”为分隔符，将字符串转换为一个数组。
     * 接下来对数组中每一个项目使用 trim() 方法去掉首尾的空白字符。最后过滤掉空字符串项目。
     *
     * 该方法的主要用途是将诸如：“item1, item2, item3” 这样的字符串转换为数组。
     *
     * @code php
     * $input = 'item1, item2, item3';
     * $output = Helper_Array::normalize($input);
     * // $output 现在是一个数组，结果如下：
     * // $output = array(
     * //   'item1',
     * //   'item2',
     * //   'item3',
     * // );
     *
     * $input = 'item1|item2|item3';
     * // 指定使用什么字符作为分割符
     * $output = Helper_Array::normalize($input, '|');
     * @endcode
     *
     * @param array|string $input 要格式化的字符串或数组
     * @param string $delimiter 按照什么字符进行分割
     *
     * @return array 格式化结果
     */
    static function normalize($input, $delimiter = ',')
    {
        if (!is_array($input))
        {
            $input = explode($delimiter, $input);
        }
        $input = array_map('trim', $input);
        return array_filter($input, 'strlen');
    }
    /**
     * [setAllay description]
     * @param [type] $arr [description]
     * @param [type] $str [description]
     */
    static function setAllay($array,$string){
     
        $arr1 = explode(',', $string);

        foreach ($array as $k=>$v)
        {
            foreach($v as $key=>$val){
                if($key==$string){
                $new[$k]['agreement_sn'] = $v['agreement_sn'];
                $new[$k]['supplier_name'] = $v['supplier_name'];
                $new[$k]['supplier_id'] = $v['supplier_id'];
               
            }
        }

           
        }
        return $new;
      
    }
    /**
     * [SUM description]
     * @param [type] $oArray [description]
     */
    static  function SUM($oArray){
        return array_sum($oArray);
    }

    static  function explode($arr,$str){
        
        if(count($arr)>1){
            foreach($arr as $k=>$v){
                $supplier_id[]=$v[$str];
            }
          $arr=implode(",",$supplier_id); 
        }else{
         return $arr[0][$str];
        }
        
        return $arr;
             
    }

    static function soreKey($data = array()) {
        return ksort($data);
    }
   

}