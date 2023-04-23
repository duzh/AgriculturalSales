<?php
namespace Mdg\Manage\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Mdg\Models as M;
use Lib as L;
use Lib\Category as lCategory;
use Lib\Areas as lAreas;
class QuotationController extends ControllerBase
{
	/**
	 * 报价列表
	 * @return [type] [description]
	 */
	public function indexAction(){
		$p = $this->request->get('p', 'int', 1 );
        $cond[] = " 1 ";
        $start_areas = array();
        $catname = array();
        $cat =array();
        $start_pid      = $this->request->get('start_pid', 'int', '');
        $start_cid      = $this->request->get('start_cid', 'int', '');
        $start_did      = $this->request->get('start_did', 'int', '');
        $expire_time    = $this->request->get('expire_time', 'string', '');
        $category       = $this->request->get('category', 'int', '');
        $is_long        = $this->request->get('is_long', 'int', '');
        $category_one   = $this->request->get('category_one','int',0);
        $expire_etime	= $this->request->get('expire_etime','string','');
        if($category_one){
        	$cat[] = lCategory::ldDataName($this->request->get("category_one") ,1);
        	$cond[] = " sell_id = '{$category_one}'";
        }
        if($category){
        	$cat[] = lCategory::ldDataName($this->request->get("category") , 2);
        	$cond[] = "category_id = '{$category}'";
        }

		$catname = join(',', $cat);

        if($start_pid) {
            $cond[] = " province_id = '{$start_pid}'";
            $start_areas[] = M\AreasFull::getAreasNametoid($start_pid);
        }
        if($start_cid) {
            $cond[] = " city_id = '{$start_cid}'";
            $start_areas[] = M\AreasFull::getAreasNametoid($start_cid);
        }
        if($start_did) {
            $cond[] = " district_id = '{$start_did}'";
            $start_areas[] = M\AreasFull::getAreasNametoid($start_did);
        }
        if ($expire_time && $expire_etime=='') {
            $cond[] = "publish_time = " .strtotime(($expire_time."00:00:00"));
        }
          if ($expire_time && $expire_etime) 
        {
            $cond[] = "publish_time >= " .strtotime(($expire_time."00:00:00"));
            $cond[] = "publish_time <= " . strtotime(($expire_etime."23:59:59"));
        }

        


        if($is_long !='All' && $is_long!=''){
        	if($is_long==1){
        		$cond[] = " source  != 0";
        	}else{
        		$cond[] = " source  = 0";
        	}
        }

        $this->view->catname = $catname;
        $this->view->start_areas = join("','" ,$start_areas);
        $cond= implode( ' AND ', $cond);
        $data=M\MarketPrice::getMarketPriceList($cond,$p,$this->db);
        $this->view->data = $data;
	}
    /**
     * 新增报价
     * @return [type] [description]
     */
	public function newAction(){

	}
    /**
     *  新增报价保存
     * @return [type] [description]
     */
	public function createAction(){

		$date=date("Ymd",time());

		$contact_name  =$this->request->getPost('contact_man','string','');
		$contact_phone =$this->request->getPost('contact_phone','string','');
		$category_id   =$this->request->getPost('category','string','');
		$sell_id       =$this->request->getPost('goods_name','string','');
		$province_id   =$this->request->getPost('start_pid','string','');
		$city_id       =$this->request->getPost('start_cid','string','');
		$district_id   =$this->request->getPost('start_did','string','');
		$market_name   =$this->request->getPost('market','string','');
		$analyze       =$this->request->getPost('analyze','string','');
		$district_id   =$this->request->getPost('start_did','string','');
		$price         =$this->request->getPost('price','string','');
		$unit		   =$this->request->getPost('unit','string','');
		$category=M\Category::getFamily($sell_id);
 		$province_name=M\AreasFull::getAreasNametoid($province_id);

		//总行情
		$markprice                   = new M\MarketPrice();
		$markprice->contact_name     = $contact_name;
		$markprice->contact_phone    = $contact_phone;
		$markprice->category_id      = $category['1']['id'];
		$markprice->category_name    = $category['1']['title'];
		$markprice->goods_name       = $category['1']['title'];
		$markprice->sell_id 		 = $category['0']['id'];
		$markprice->province_id      = $province_id;
		$markprice->city_id          = $city_id;
		$markprice->district_id      = $district_id;
		$markprice->market_name      = $market_name;
		$markprice->analyze          = $analyze;
		$markprice->publish_time     = strtotime($date);
		$markprice->collect_type     = 0;
		$markprice->price            = $price;
		$markprice->source           = 0;
		$markprice->unit 		 	 = $unit;
		$markprice->firstname 		 = $category['1']['abbreviation'];
		$markprice->add_time         = time();
		$markprice->last_update_time = time();
		if(!$markprice->save()){
			print_R($markprice->getMessages());exit;
		}

		//今日行情
		$marktodayprice              =new M\MarketTodayprice();
		$marketavgprice->id 		      = $markprice->id;
		$marktodayprice->contact_name     = $contact_name;
		$marktodayprice->contact_phone    = $contact_phone;
		$marktodayprice->category_id      = $category['1']['id'];
		$marktodayprice->category_name    = $category['1']['title'];
		$marktodayprice->goods_name       = $category['1']['title'];
		$marktodayprice->sell_id 		  = $category['0']['id'];
		$marktodayprice->province_id      = $province_id;
		$marktodayprice->city_id          = $city_id;
		$marktodayprice->district_id      = $district_id;
		$marktodayprice->market_name      = $market_name;
		$marktodayprice->analyze          = $analyze;
		$marktodayprice->publish_time     = strtotime($date);
		$marktodayprice->collect_type     = 0;
		$marktodayprice->price            = $price;
		$marktodayprice->source           = 0;
		$marktodayprice->unit 			  = $unit;
		$marketavgprice->firstname 	 	  = $category['1']['abbreviation'];
		$marktodayprice->add_time         = time();
		$marktodayprice->last_update_time = time();
		if(!$marktodayprice->save()){
			print_R($marktodayprice->getMessages());exit;
		}

		$date_time = date("Y-m-d",time());
		$date_last_time = strtotime($date_time."00:00:00");

		//每天商品平均报价
		$marketavgprice=M\MarketAvgprice::findFirst(" publish_time = {$date} and province_id = {$province_id} and category_id = {$category['1']['id']}");
		$marketavgprice1=M\MarketAvgprice::findFirst(" publish_time = {$date} and category_id = {$category['1']['id']}");

		//全国平均行情
	if(!$marketavgprice1){

			$marketavgprice1 = new M\MarketAvgprice();
			$marketavgprice1->today_avgprice = $price;
			$marketavgprice1->yesterday_avgprice = 0;
			$marketavgprice1->publish_time = date("Ymd",time());
			$marketavgprice1->province_id = 0;
			$marketavgprice1->province_name ="全国";
			$marketavgprice1->category_id = $category['1']['id'];
			$marketavgprice1->last_update_time = time();
			$marketavgprice1->unit = $unit;
			$marketavgprice1->total = 1;
			$marketavgprice1->amount = $price;
			if(!$marketavgprice1->save()){
				print_R($marketavgprice1->getMessages());exit;
			}
		}else{

			$cond[] = " publish_time between ".$date_last_time." and ".time()."and category_id = {$category['1']['id']}";
			$avg = M\MarketTodayprice::count( $cond);
			$sum = M\MarketTodayprice::sum(array( $cond[0] ,'column'=>'price'));
			$sum=$sum ? $sum : 0;
			$avg=($sum/$avg);
			$marketavgprice1->today_avgprice = $avg;
			$marketavgprice1->yesterday_avgprice = $marketavgprice1->yesterday_avgprice;
			$marketavgprice1->province_id = 0;
			$marketavgprice1->province_name ="全国";
			$marketavgprice1->category_id = $category['1']['id'];
			$marketavgprice1->publish_time = $marketavgprice1->publish_time;
			$marketavgprice1->last_update_time = time();
			$marketavgprice1->unit = $unit;
			$marketavgprice1->total = $marketavgprice1->total+1;
			$marketavgprice1->amount = $marketavgprice1->amount+$price;
			$marketavgprice1->update();
			if(!$marketavgprice1->save()){
				print_R($marketavgprice1->getMessages());exit;
			}
		}

		//根据时间，地区，分类筛选每日评价行情
		if(!$marketavgprice){

			$marketavgprice = new M\MarketAvgprice();
			$marketavgprice->today_avgprice = $price;
			$marketavgprice->yesterday_avgprice = 0;
			$marketavgprice->publish_time = date("Ymd",time());
			$marketavgprice->province_id = $province_id;
			$marketavgprice->province_name =$province_name;
			$marketavgprice->category_id = $category['1']['id'];
			$marketavgprice->last_update_time = time();
			$marketavgprice->unit = $unit;
			$marketavgprice->total = 1;
			$marketavgprice->amount = $price;
			if(!$marketavgprice->save()){
				print_R($marketavgprice->getMessages());exit;
			}
		}else{

			$cond[] = " publish_time between ".$date_last_time." and ".time()." and province_id = {$province_id} and category_id = {$category['1']['id']}";
			$avg = M\MarketTodayprice::count( $cond);
			$sum = M\MarketTodayprice::sum(array( $cond[0] ,'column'=>'price'));
			$sum=$sum ? $sum : 0;
			$avg=($sum/$avg);
			$marketavgprice->today_avgprice = $avg;
			$marketavgprice->yesterday_avgprice = $marketavgprice->yesterday_avgprice;
			$marketavgprice->province_id = $province_id;
			$marketavgprice->province_name =$province_name;
			$marketavgprice->category_id = $category['1']['id'];
			$marketavgprice->publish_time = $marketavgprice->publish_time;
			$marketavgprice->last_update_time = time();
			$marketavgprice->unit = $unit;
			$marketavgprice->total = $marketavgprice->total+1;
			$marketavgprice->amount = $marketavgprice->amount+$price;
			$marketavgprice->update();
			if(!$marketavgprice->save()){
				print_R($marketavgprice->getMessages());exit;
			}
		}

		$this->response->redirect("quotation/index")->sendHeaders();
	}

	// public function updateAction($id = 0){
	// 	$markprice=M\MarketPrice::findFirstByid($id);
	// 	$this->view->data = $markprice;
	// 	$this->view->curCate = lCategory::ldData($markprice->category_id);
	// 	$this->view->curAreas = lAreas::ldData($markprice->district_id ? $markprice->district_id : ($markprice->city_id ? $markprice->city_id : ($markprice->province_id ?$markprice->province_id : 0 )));
	// }

    /**
     * 获取报价信息
     * @param  integer $id [description]
     * @return [type]      [description]
     */
	 public function getAction($id = 0){
	 		$data=M\MarketPrice::findFirstByid($id);
	 		$category=M\Category::getFamily($data->category_id);
	 		$this->view->data = $data;
	 		$this->view->category = $category;

	 }

}
