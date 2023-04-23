<?php
namespace Lib;

class Pages
{
    
    public $plus = 5; //分页偏移量
    
    public $total_pages; //总页数
    
    public $current; //当前页数
    
    public $method = 'defalut'; //处理情况 Ajax分页 Html分页(静态化时) 普通get方式
    
    public $parameter = ''; //$_GET参数
    
    public $page_name; //分页参数的名称
    
    public $ajax_func_name; //ajax调用
    
    public $url; //分页url地址
    
    /**
     * 构造函数
     * @param unknown_type $data
     */
    
    public function __construct($data = array()) 
    {
        $data = (array)$data;
        $this->parameter = !empty($data['parameter']) ? $data['parameter'] : '';
        $this->total_pages = $data['total_pages'];
        $this->page_name = !empty($data['page_name']) ? $data['page_name'] : 'p';
        $this->ajax_func_name = !empty($data['ajax_func_name']) ? $data['ajax_func_name'] : '';
        $this->method = !empty($data['method']) ? $data['method'] : '';
        $this->type = !empty($data['type']) ? $data['type'] : '';
        $this->ajax_type = !empty($data['ajax_type']) ? $data['ajax_type'] : '';
        $this->total = !empty($data['total']) ? $data['total'] : '';
        /* 当前页面 */
        
        if (!empty($data['current'])) 
        {
            $this->current = intval($data['current']);
        }
        else
        {
            $this->current = !empty($_GET[$this->page_name]) ? intval($_GET[$this->page_name]) : 1;
        }
        $this->current = $this->current <= 0 ? 1 : $this->current;
        
        if (!empty($this->total_pages) && $this->current > $this->total_pages) 
        {
            $this->current = $this->total_pages;
        }
    }
    /**
     * 得到当前连接
     * @param $page
     * @param $text
     * @return string
     */
    
    protected function _get_link($page, $text, $class = '') 
    {
        $style = $class ? ' class="' . $class . '" ' : '';
        
        switch ($this->method) 
        {
        case 'ajax':
            $parameter = '';
            
            if ($this->parameter) 
            {
                $parameter = ',' . $this->parameter;
            }
            $ajax_type = 1;
            
            if ($this->ajax_type) 
            {
                $ajax_type = 2;
            }
            
            if ($this->type == 1) 
            {
                return '<a ' . $style . ' onclick="' . $this->ajax_func_name . '(\'' . $page . '\'' . $parameter . ',0,' . $ajax_type . ')" href="javascript:void(0)">' . $text . '</a>' . "\n";
            }
            elseif ($this->type == 2) 
            {
                return '<a ' . $style . ' onclick="' . $this->ajax_func_name . '(\'' . $page . '\'' . $parameter . ',1,' . $ajax_type . ')" href="javascript:void(0)">' . $text . '</a>' . "\n";
            }
            else
            {
                return '<a ' . $style . ' onclick="' . $this->ajax_func_name . '(\'' . $page . '\'' . $parameter . ')" href="javascript:void(0)">' . $text . '</a>' . "\n";
            }
            break;

        case 'html':
            $url = str_replace('?', $page, $this->parameter);
            return '<a ' . $style . ' href="' . $url . '">' . $text . '</a>' . "\n";
            break;

        default:
            return '<a ' . $style . ' href="' . $this->_get_url($page) . '">' . $text . '</a>' . "\n";
            break;
        }
    }
    /**
     * 设置当前页面链接
     */
    
    protected function _set_url() 
    {
        $url = $_SERVER['REQUEST_URI'] . (strpos($_SERVER['REQUEST_URI'], '?') ? '' : "?") . $this->parameter;
        $parse = parse_url($url);
        
        if (isset($parse['query'])) 
        {
            parse_str($parse['query'], $params);
            unset($params[$this->page_name]);
            $url = $parse['path'] . '?' . http_build_query($params);
        }
        
        if (!empty($params)) 
        {
            $url.= '&';
        }
        $this->url = $url;
    }
    /**
     * 得到$page的url
     * @param $page 页面
     * @return string
     */
    
    protected function _get_url($page) 
    {
        
        if ($this->url === NULL) 
        {
            $this->_set_url();
        }
        //  $lable = strpos('&', $this->url) === FALSE ? '' : '&';
        return $this->url . $this->page_name . '=' . $page;
    }
    /**
     * 得到第一页
     * @return string
     */
    
    public function first_page($name = '首页',$class='first') 
    {
        
        if ($this->current > 5) 
        {
            return $this->_get_link('1', $name,$class);
        }
        return '';
    }
    /**
     * 最后一页
     * @param $name
     * @return string
     */
    
    public function last_page($name = '尾页',$class='last') 
    {
        
        if ($this->current < $this->total_pages - 5) 
        {
            return $this->_get_link($this->total_pages, $name,$class);
        }
        return '';
    }
    /**
     * 上一页
     * @return string
     */
    
    public function up_page($name='上一页',$class='prev') 
    {
        if ($this->current > 1)
        {
            return $this->_get_link($this->current - 1, $name, $class);
        }
        else
        {
            return '';
            // return $this->_get_link( 1, $name, 'prev');
            
        }
    }
    
    public function up_pagenew($name = '上一页') 
    {
        
        if ($this->current>= 1) 
        {
            return $this->_get_link($this->current - 1, $name, 'prev-page');
        }
        else
        {
            return '';
        }
    }
    
    public function up_pages($name = '上一页') 
    {
        
        if ($this->current != 1) 
        {
            return $this->_get_link($this->current - 1, $name, 'select-page-prev');
        }
        else
        {
            return '';
        }
    }
    /**
     * 下一页
     * @return string
     */
    
    public function down_page($name = '下一页',$class='next') 
    {
        
        if ($this->current < $this->total_pages) 
        {
            return $this->_get_link($this->current + 1, $name,$class);
        }
        return '';
    }
    
    public function down_pagenew($name = '下一页') 
    {
        
        if ($this->current < $this->total_pages) 
        {
            return $this->_get_link($this->current + 1, $name, 'next-page');
        }
        return '';
    }
    
    public function down_pages($name = '下一页') 
    {
        
        if ($this->current < $this->total_pages) 
        {
            return $this->_get_link($this->current + 1, $name, 'select-page-next');
        }
        return '';
    }
    /**
     * 分页样式输出
     * @param $param
     * @return string
     */
    
    public function show($param = 1) 
    {
        $className = 'show_' . $param;
        $classNames = get_class_methods($this);
        
        if (in_array($className, $classNames)) 
        {
            return $this->$className();
        }
        return '';
    }
    
    protected function show_1() 
    {
        $plus = $this->plus;
        
        if ($plus + $this->current > $this->total_pages) 
        {
            $begin = $this->total_pages - $plus * 2;
        }
        else
        {
            $begin = $this->current - $plus;
        }
        $begin = ($begin >= 1) ? $begin : 1;
        $return = '';
        //$return.= $this->first_page("上一页","first_page");
        $return.= $this->up_page("上一页","prev-page");
      
        
        for ($i = $begin; $i <= $begin + $plus * 2; $i++) 
        {
            
            if ($i > $this->total_pages) 
            {
                break;
            }
            
            if ($i == $this->current) 
            {
                $return.= "<a class='active'>$i</a>";
            }
            else
            {
                $return.= $this->_get_link($i, $i) . "";
            }
        }
        $return.= $this->down_page("下一页",'next-page');
        //$return.= $this->last_page("尾页",'next-page');
        $return.= "共" . $this->total_pages . "页";
        return $return;
    }
    
    protected function show_2() 
    {
        $plus = $this->plus;
        
        if ($this->total_pages != 1) 
        {
            $return = '';

            $return.= $this->up_page('<',"prev-page");
           
            
            for ($i = 1; $i <= $this->total_pages; $i++) 
            {
                
                if ($i == $this->current) 
                {
                    $return.= "<a class='active'>$i</a>\n";
                }
                else
                {
                    
                    if ($this->current - $i >= $plus && $i != 1) 
                    {

                        $return.= "<font>...</font>\n";
                        $i = $this->current - $plus;
                    }
                    else
                    {
                        
                        if ($i >= $this->current + $plus && $i != $this->total_pages) 
                        {
                            $return.= "<font>...</font>\n";
                            $i = $this->total_pages;
                        }
                        $return.= $this->_get_link($i, $i) . "\n";
                    }
                }
            }

            $return.= $this->down_page('>','next-page');
            return $return;
        }
    }
    
    protected function show_3() 
    {
        $plus = $this->plus;
        
        if ($this->total_pages != 1) 
        {
            $return = '';
            $return.= $this->up_page();
            $return.= "<b>\n";
            
            for ($i = 1; $i <= $this->total_pages; $i++) 
            {
                
                if ($i == $this->current) 
                {
                    $return.= "<a class='active'>$i</a>\n";
                }
                else
                {
                    
                    if ($this->current - $i >= $plus && $i != 1) 
                    {
                        $return.= "<i>...</i>\n";
                        $i = $this->current - $plus;
                    }
                    else
                    {
                        
                        if ($i >= $this->current + $plus && $i != $this->total_pages) 
                        {
                            $return.= "<i>...</i>\n";
                            $i = $this->total_pages;
                        }
                        $return.= $this->_get_link($i, $i) . "\n";
                    }
                }
            }
            $return.= "</b>\n";
            $return.= $this->down_page();
            return $return;
        }
    }
    
    protected function show_4() 
    {
        $plus = $this->plus;
        
        if ($plus + $this->current > $this->total_pages) 
        {
            $begin = $this->total_pages - $plus * 2;
        }
        else
        {
            $begin = $this->current - $plus;
        }
        $begin = ($begin >= 1) ? $begin : 1;
        $return = '';
        $return.= $this->up_pagenew();
        $return.= '<b>';
        
        for ($i = $begin; $i <= $begin + $plus * 2; $i++) 
        {
            
            if ($i > $this->total_pages) 
            {
                break;
            }
            
            if ($i == $this->current) 
            {
                $return.= "<a class='active'>$i</a>";
            }
            else
            {
                $return.= $this->_get_link($i, $i) . "";
            }
        }
        $return.= '</b>';
        $return.= $this->down_pagenew();
        $return.= "<span>共" . $this->total_pages . "页</span>";
        return $return;
    }
    
    protected function show_5() 
    {
        $plus = $this->plus;
        
        if ($plus + $this->current > $this->total_pages) 
        {
            $begin = $this->total_pages - $plus * 2;
        }
        else
        {
            $begin = $this->current - $plus;
        }
        $begin = ($begin >= 1) ? $begin : 1;
        $return = '<font>';
        $return.= $this->current . '/' . $this->total_pages;
        $return.= '</font>';
        $return.= $this->up_pages();
        $return.= $this->down_pages();
        $return.= "<span>共" . $this->total_pages . "页</span>";
        return $return;
    }
    
    protected function show_6() 
    {
        $plus = $this->plus;
        
        if ($plus + $this->current > $this->total_pages) 
        {
            $begin = $this->total_pages - $plus * 2;
        }
        else
        {
            $begin = $this->current - $plus;
        }
        $begin = ($begin >= 1) ? $begin : 1;
        $return = '';
        $return.= $this->up_pagenew();
        $return.= '<b>';
        
        for ($i = $begin; $i <= $begin + $plus * 2; $i++) 
        {
            
            if ($i > $this->total_pages) 
            {
                break;
            }
            
            if ($i == $this->current) 
            {
                $return.= "<a class='active'>$i</a>";
            }
            else
            {
                $return.= $this->_get_link($i, $i) . "";
            }
        }
        $return.= '</b>';
        $return.= $this->down_pagenew();
        return $return;
    }
    
    protected function show_7() 
    {
        $plus = $this->plus;
        
        if ($plus + $this->current > $this->total_pages) 
        {
            $begin = $this->total_pages - $plus * 2;
        }
        else
        {
            $begin = $this->current - $plus;
        }
        $begin = ($begin >= 1) ? $begin : 1;
        $return = '';
        $return.= $this->up_pagenew();
       
        
        for ($i = $begin; $i <= $begin + $plus * 2; $i++) 
        {
            
            if ($i > $this->total_pages) 
            {
                break;
            }
            
            if ($i == $this->current) 
            {
                $return.= "<a class='active'>$i</a>";
            }
            else
            {
                $return.= $this->_get_link($i, $i) . "";
            }
        }
        $return.= $this->down_pagenew();
        return $return;
    }
    protected function show_8() 
    {
        $plus = $this->plus;
        
        if ($plus + $this->current > $this->total_pages) 
        {
            $begin = $this->total_pages - $plus * 2;
        }
        else
        {
            $begin = $this->current - $plus;
        }
        $begin = ($begin >= 1) ? $begin : 1;
        $return = '<font>';
        $return.= $this->current . '/' . $this->total_pages;
        $return.= '</font>';
        $return.= $this->up_page();
        $return.= $this->down_page();
        return $return;
    }
	protected function show_9() 
    {
        $plus = $this->plus;
        
        if ($this->total_pages != 1) 
        {
            $return = '';
            $return.= $this->up_pagenew();
            $return.= "<b>\n";
            
            for ($i = 1; $i <= $this->total_pages; $i++) 
            {
                
                if ($i == $this->current) 
                {
                    $return.= "<a class='active'>$i</a>\n";
                }
                else
                {
                    
                    if ($this->current - $i >= $plus && $i != 1) 
                    {
                        $return.= "<i>...</i>\n";
                        $i = $this->current - $plus;
                    }
                    else
                    {
                        
                        if ($i >= $this->current + $plus && $i != $this->total_pages) 
                        {
                            $return.= "<i>...</i>\n";
                            $i = $this->total_pages;
                        }
                        $return.= $this->_get_link($i, $i) . "\n";
                    }
                }
            }
            $return.= "</b>\n";
            $return.= $this->down_pagenew();
            return $return;
        }
    }	

    protected function show_10() 
    {
        $plus = $this->plus;
        
        if ($plus + $this->current > $this->total_pages) 
        {
            $begin = $this->total_pages - $plus * 2;
        }
        else
        {
            $begin = $this->current - $plus;
        }

        $begin = ($begin >= 1) ? $begin : 1;
        $return = '';
        $return.= $this->up_pagenew();
        $return.= '<b>';

        for ($i = $begin; $i <= $begin + $plus * 2; $i++) 
        {
            
            if ($i > $this->total_pages) 
            {
                break;
            }
            
            if ($i == $this->current) 
            {
                $return.= "<a class='active'>$i</a>";
            }
            else
            {
                $this->ajax_func_name = "pagego";
                $this->method = 'ajax';
                $return.= $this->_get_link($i, $i) . "";
            }
        }
        $return.= '</b>';
        $return.= $this->down_pagenew();
        $return.= "<span>共" . $this->total_pages . "页</span>";

        return $return;
    }
    protected function show_11() 
    {
        $this->ajax_func_name = "pagego";
        $this->method = 'ajax';
        $plus = $this->plus;
        
        if ($plus + $this->current > $this->total_pages) 
        {
            $begin = $this->total_pages - $plus * 2;
        }
        else
        {
            $begin = $this->current - $plus;
        }
        $begin = ($begin >= 1) ? $begin : 1;
        $return = '<font>';
        $return.= $this->current . '/' . $this->total_pages;
        $return.= '</font>';
        $return.= $this->up_page();
        $return.= $this->down_page();
        return $return;
    }

    protected function show_12() 
    {
        $plus = $this->plus;
        
        if ($this->total_pages != 1) 
        {
            $return = '';

            $return.= $this->up_page('<',"prev-page");
           
            
            for ($i = 1; $i <= $this->total_pages; $i++) 
            {
                
                if ($i == $this->current) 
                {
                    $return.= "<a class='active'>$i</a>\n";
                }
                else
                {
                    
                    if ($this->current - $i >= $plus && $i != 1) 
                    {

                        $return.= "<font>...</font>\n";
                        $i = $this->current - $plus;
                    }
                    else
                    {
                        
                        if ($i >= $this->current + $plus && $i != $this->total_pages) 
                        {
                            $return.= "<font>...</font>\n";
                            $i = $this->total_pages;
                        }
                        $return.= $this->_get_link($i, $i) . "\n";
                    }
                }
            }

            $return.= $this->down_page('>','next-page');
            $return.= "<span>共" . $this->total_pages . "页</span>";
            return $return;
        }
    }
}
