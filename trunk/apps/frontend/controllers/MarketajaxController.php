<?php
namespace Mdg\Frontend\Controllers;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Mvc\Controller;

use Phalcon\Paginator\Adapter\Model as Paginator;
use Lib\Member as Member, Lib\Auth as Auth, Lib\SMS as sms, Lib\Utils as Utils;
use Mdg\Models as M;
use Lib as L;
/**
 * 价格行情
 */

class MarketajaxController extends Controller
{
    /**
     * 获取热销产品
     * @return [type] [description]
     */
    
    public function gethotcateAction($cid = 0) 
    {
        $flag = true;
        $count = 24;
        $step = 196;

        while ($flag) {
            $time = date('Ymd',strtotime("-{$step} day"));
            $data = M\MarketAvgprice::selectHome($cid , $pid=0, $time,30, $this->db);
            $count--;
            $step++;
            if($data) $flag=false;
            if(!$count) $flag=false;
        }
        
        foreach ($data as &$val) {
            $val['ppp'] = L\Func::format_money($val['ppp']);
            $val['diff'] = L\Func::format_money($val['today_avgprice'] -  $val['yesterday_avgprice']);
        }
        
        $this->view->pname = M\Category::selectBytocateName($cid);
        $this->view->is_ajax = 1;
        $this->view->data = $data;
    }
    /**
     * 获取top 数据
     * @param  integer $cid 大分类id
     * @return array
     */
    
    public function gettopcateAction($cid = 0) 
    {
        
        $flag = true;
        $count = 24;
        $step = 196;

        while ($flag) {
            $time = date('Ymd',strtotime("-{$step} day"));
            $data = M\MarketAvgprice::selectHome($cid , $pid=0,$time,5, $this->db);
            $count--;
            $step++;
            if($data) $flag=false;
            if(!$count) $flag=false;
        }
  
        foreach ($data as &$val) {
            $val['ppp'] = L\Func::format_money($val['ppp']);
            $val['diff'] = L\Func::format_money($val['today_avgprice'] -  $val['yesterday_avgprice']);
            $val['sort'] = str_replace('-', '', $val['ppp']);

        }
        
        L\Arrays::sortByCol($data , 'sotr', SORT_DESC);
        $this->view->pname = M\Category::selectBytocateName($cid);
        $this->view->is_ajax = 1;
        $this->view->data = $data;

    }
}
