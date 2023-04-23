<?php
namespace Mdg\Frontend\Controllers;
use Mdg\Models as M;
use Lib as L;
/**
 * 追溯管理
 */

class TagController extends ControllerBase
{
    /**
     * 追溯大厅
     * @return [type] [description]
     */
    
    public function indexAction() 
    {
        $cond[] = " 1 ";
        $tagcon = ' 1 ';
        $page_size = 15;
        $p = $this->request->get('p', 'int', 1);
        $p = $p > 0 ? $p : 1;
        $keyword = $this->request->get('keyword', 'string', '');
        
        if ($keyword) 
        {
            $tagcon.= "  AND goods_name LIKE '{$keyword}%' or source_no='{$keyword}'";
        }
        $sid = M\Tag::getSellid($tagcon);
        $cond[] = " id in ({$sid}) AND is_del = '0' AND  state = '1' and publish_place!=2 ";
        $cond = implode(' AND ', $cond);

        $data = M\Sell::getSellList($cond, $p, $page_size, 1);
        
        foreach ($data['items'] as $key => $val) 
        {
            $sql = "select path from tag where sell_id ='{$val['id']}'";
            $path = $this->db->fetchOne($sql, 2);
            $data['items'][$key]['path'] = $path ? $path['path'] : '';
        }
        $this->view->p = $p;
        $this->view->data = $data;
        $this->view->title = '可信农产品-丰收汇';
        $this->view->keywords = '可信农产品，丰收汇';
        $this->view->descript = '丰收汇-可靠农产品交易网，农产品可追溯，安全交易有保障。';  
    }
    /**
     * 标签详细查看
     * @return [type] [description]
     */
    
    public function getAction() 
    {
        $tid = $this->request->get('tid', 'int', 0);
        $data = M\Tag::getTagInfo($tid);
        
        if (!$data) 
        {
            echo "<script>alert('来源错误');location.href='/index.php'</script>";
            exit;
        }
        $areas[] = M\AreasFull::getAreasNametoid($data->province);
        $areas[] = M\AreasFull::getAreasNametoid($data->city);
        $areas[] = M\AreasFull::getAreasNametoid($data->district);
        $areas = join(',', $areas) . $data->address;
        //查询质量
        //质量评估
        $tagquality = M\TagQuality::getTagQuality($data->tag_id, 1);
        //查询分类
        $categroy[] = M\Category::selectBytocateName($data->category_one);
        $categroy[] = M\Category::selectBytocateName($data->category_two);
        $categroy[] = M\Category::selectBytocateName($data->category_three);
        $cate = join('>>', $categroy);
        $is_gene = isset($tagquality['is_gene']) && $tagquality['is_gene'] ? '是' : '否';
        $inspecttime = isset($tagquality['inspect_time']) ? date("Y-m-d", $tagquality['inspect_time']) : 0;
        print ("<div style='padding:20px; line-height:28px; font-size:20px;'>
              <p>品名：{$data->goods_name}</p> 
              <p>溯源码：{$data->source_no}</p> 
              <p>蔬菜种类：{$cate}</p>
              <p>产地：{$areas}</p>
              <p>生产商：{$data->productor}</p>
              <p>加工地：{$data->process_place}</p>
              <p>加工商：{$data->process_merchant}</p>
              <p>生产日期：{$data->product_date}</p>
              <p>保质期：{$data->expiration_date}</p>
              <p>质量等级： " . (isset($tagquality['quality_level']) ? $tagquality['quality_level'] : '') . "</p>
              <p>农残含量：" . (isset($tagquality['pesticide_residue']) ? $tagquality['pesticide_residue'] : '') . "</p>
              <p>重金属含量：" . (isset($tagquality['heavy_metal']) ? $tagquality['heavy_metal'] : '') . "</p>
              <p>转基因：{$is_gene}</p>
              
              <a href='/sell/info/{$data->sell_id}&source=1'>查看更多</a>
              </div> 
          ");
        /*
              <p>检验员：{$tagquality['inspector']}</p>
              <p>检验时间：{$inspecttime}</p>
              <p>权威机构安全鉴定：{$tagquality['certifying_agency']}</p>
        
        */
        exit;
    }
}
