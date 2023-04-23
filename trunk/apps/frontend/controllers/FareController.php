<?php

namespace Mdg\Frontend\Controllers;
use Lib\Time as times;
use Lib\Caiji as Caiji;
use Mdg\Models\CarExt as CarExt;
use Mdg\Models\Category as Category;
use Mdg\Models\AreasFull as AreasFull;
use Mdg\Models  as M;



class FareController extends ControllerBase

{

	public function carAction() {

		$car_no = $_POST['car_no'];
		$str = $_POST['add_time'];				//发布日期
		$add_time = times::timestamp($str);							
		$start_dname = $_POST['start_dname'];
		$end_dname = $_POST['end_dname'];	
		$depart_str = $_POST['depart_time'];				//发布日期
		$depart_time = times::timestamp($depart_str);					
		$carry_weight = $_POST['carry_weight'];
		//车辆信息
		$car_manage = explode("&nbsp;",$_POST['car_manage']);      
		$length = $_POST['length'];
		$car_licence = $_POST['car_licence'];
		$contact_man = $_POST['contact_man'];
		$contact_phone = $_POST['contact_phone'];
		$heavy_goods = $_POST['heavy_goods'];
		$light_goods = $_POST['light_goods'];
		$demo = $_POST['demo'];
		$phone_number = $_POST['phone_number'];
		$phone_img = $_POST['phone_img'];
		preg_match('/src="(.+?)"/i', $phone_img, $m);
		$tel_img = isset($m[1]) ? '/fare/'.$m[1] : '';
		$status=1;
		$source=1;

		//箱型
		$box_type = CarExt::$_box_type[$car_manage[0]];	
		//车体
		$body_type = CarExt::$_body_type[$car_manage[1]];	

		
		$sql='INSERT INTO car_info (car_no,start_dname,end_dname,car_licence,contact_man,heavy_goods,light_goods,status,add_time,last_update_time,phone_number,phone_img,source,start_areas,end_areas) values ("%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s")';

		$sql = sprintf($sql, $car_no,$start_dname,$end_dname,$car_licence,$contact_man,$heavy_goods,$light_goods,$status,$add_time,$add_time,$phone_number,$tel_img,$source,$start_dname, $end_dname);
		$this->db->execute($sql);


		$id = $this->db->lastInsertid();
		$sql='INSERT INTO car_ext (car_id,length,carry_weight,demo,box_type,depart_time,add_time,last_update_time) values ("%s","%s","%s","%s","%s","%s","%s","%s")';

		$sql = sprintf($sql, $id,$length,$carry_weight,$demo,$box_type,$depart_time,$add_time,$add_time);
		$this->db->execute($sql);

		
		die('success');

	}

	public function huoAction() {

		$goods_no = $_POST['goods_no'];
		$contact_man = "匿名";
		$goods_name = $_POST['goods_name'];
		$goods_weight = $_POST['goods_weight'];
		$goods_size = $_POST['goods_size'];
		$except_price = $_POST['except_price'];
		$start_dname = $_POST['start_dname'];
		$end_dname = $_POST['end_dname'];
		$settle_type = $_POST['settle_type'];
		$expire_time = strtotime($_POST['expire_time']);     //有效时间

		$str = $_POST['add_time'];				//发布日期
		$add_time = times::timestamp($str);

		$phone_number = $_POST['phone_number'];
		$phone_img = $_POST['phone_img'];
		preg_match('/src="(.+?)"/i', $phone_img, $m);
		$tel_img = isset($m[1]) ? '/fare/'.$m[1] : '';
		$demo = $_POST['demo'];
		$source=1;

		$sql='INSERT INTO cargo_info (goods_no,contact_man,goods_name,goods_weight,goods_size,except_price,start_dname,end_dname,settle_type,expire_time,phone_number,phone_img,add_time,last_update_time,source,start_areas,end_areas) values ("%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s")';
echo $sql;die;
		$sql = sprintf($sql, $goods_no,$contact_man,$goods_name,$goods_weight,$goods_size,$except_price,$start_dname,$end_dname,$settle_type,$expire_time,$phone_number,$tel_img,$add_time,$add_time,$source,$start_dname,$end_dname);
		$this->db->execute($sql);

		$id = $this->db->lastInsertid();
		$sql='INSERT INTO cargo_ext (goods_id,expire_time,demo,settle_type,add_time,last_update_time) values ("%s","%s","%s","%s","%s","%s")';

		$sql = sprintf($sql, $id,$expire_time,$demo,$settle_type,$add_time,$add_time);
		$this->db->execute($sql);

		die('success');
	}


	public function zhuanxianAction() {


		$start_dname = $_POST['start_dname'];
		$start_net_name = $_POST['start_net_name'];
		$start_company_name = $_POST['start_company_name'];
		$start_contact_man = $_POST['start_contact_man'];
		$start_fix_phone = $_POST['start_fix_phone'];
		$start_phone_number = $_POST['start_phone_number'];
		$start_phone_img = $_POST['start_phone_img'];
		preg_match('/src="(.+?)"/i', $start_phone_img, $m);
		$start_tel_img = isset($m[1]) ? '/fare/'.$m[1] : '';
		$start_qq = $_POST['start_qq'];
		$start_address = $_POST['start_address'];
		$heavy_price = $_POST['heavy_price'];
		$light_price = $_POST['light_price'];
		$type = $_POST['type']=='单程'? 0 : 1 ;
		$demo = $_POST['demo'];

		$add_time = time()-rand(0,1296000);	
		$source = 1;

		$end_dname = $_POST['end_dname'];
		$end_net_name = $_POST['end_net_name'];
		$end_company_name = $_POST['end_company_name'];
		$end_contact_man = $_POST['end_contact_man'];
		$end_fix_phone = $_POST['end_fix_phone'];
		$end_phone_number = $_POST['end_phone_number'];
		$end_phone_img = $_POST['end_phone_img'];
		preg_match('/src="(.+?)"/i', $end_phone_img, $m);
		$end_tel_img = isset($m[1]) ? '/fare/'.$m[1] : '';
		$end_qq = $_POST['end_qq'];
		$end_address = $_POST['end_address'];

		$sql='INSERT INTO sc_info (contact_man,light_price,heavy_price,start_dname,end_dname,type,demo,phone_number,phone_img,source,add_time,last_update_time,start_areas,end_areas) values ("%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s")';

		$sql = sprintf($sql, $start_contact_man,$light_price,$heavy_price,$start_dname,$end_dname,$type,$demo,$start_phone_number,$start_tel_img,$source,$add_time,$add_time,$start_dname,$end_dname);
		$this->db->execute($sql);

		$id = $this->db->lastInsertid();
		$sql='INSERT INTO sc_ext (sc_id,net_name,company_name,contact_man,fix_phone,type,address,status,phone_number,phone_img,qq,add_time,last_update_time) values ("%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s")';

		$sql = sprintf($sql, $id,$start_net_name,$start_company_name,$start_contact_man,$start_fix_phone,0,$start_address,1,$start_phone_number,$start_tel_img,$start_qq,$add_time,$add_time);
		$this->db->execute($sql);


		$sql='INSERT INTO sc_ext (sc_id,net_name,company_name,contact_man,fix_phone,type,address,status,phone_number,phone_img,qq,add_time,last_update_time) values ("%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s")';

		$sql = sprintf($sql, $id,$end_net_name,$end_company_name,$end_contact_man,$end_fix_phone,1,$end_address,1,$end_phone_number,$end_tel_img,$end_qq,$add_time,$add_time);
		$this->db->execute($sql);

		die('success');


	}

	public function hangqingAction(){
		
		$goods_name = mb_substr($_POST['goods_name'], 0, -2, 'utf8');
		$market_name = $_POST['market_name'];
		$publish_time = strtotime($_POST['publish_time']);
		$price = $_POST['price'];
		list(,$unit) = explode('/', $price);
		$price = floatval($price);
		if(!$price || $price<=0) {
			die('success');
		}

		if(!$goods_name){
			die('success');
		}else{

			$privince_info = mb_substr($_POST['market_name'], 0, 2, 'utf8');

			$privince = AreasFull::findFirst(" name like '{$privince_info}%' and level=1");

			if($privince && $privince_info){

				$province_id = $privince->id;

			}else{
				$province_id = 0;
			}


			$category = Category::findFirstBytitle($goods_name);
			if($category){

				$sql='INSERT INTO market_price (category_id,category_name,goods_name,market_name,publish_time,price,collect_type,source,province_id, unit, firstname,sell_id) values ("%s","%s","%s","%s","%s","%s","%s","%s","%s", "%s", "%s","%s")';

				$sql = sprintf($sql, $category->id,$category->title,$goods_name,$market_name,$publish_time,$price,1,1,$province_id, $unit, $category->abbreviation,$category->parent_id);
				$this->db->execute($sql);

			}else{

				$sql='INSERT INTO market_price (category_id,goods_name,market_name,publish_time,price,collect_type,source,province_id, unit) values ("%s","%s","%s","%s","%s","%s","%s","%s", "%s")';

				$sql = sprintf($sql, 0,$goods_name,$market_name,$publish_time,$price,1,1,$province_id, $unit);
				$this->db->execute($sql);

			}


			die('success');
		}
	}

/**
 * 价格行情--采集近N天的数据
 * @return [type] [description]
 */
	public function daysAction(){

		$goods_name = mb_substr($_POST['goods_name'], 0, -2, 'utf8');
		$market_name = $_POST['market_name'];
		$publish_time = strtotime($_POST['publish_time']);
		$price = $_POST['price'];
		list(,$unit) = explode('/', $price);
		$price = floatval($price);

		$date_time = strtotime(date('Y-m-d',time()));
		$add_time = time();

		if(!$price || $price<=0) {
			die('success');
		}

		if($publish_time!=$date_time){
			die('success');
		}

		if(!$goods_name){

			die('success');

		}else{

			$privince_info = mb_substr($_POST['market_name'], 0, 2, 'utf8');

			$privince = AreasFull::findFirst(" name like '{$privince_info}%' and level=1");

			if($privince && $privince_info)
				{

				$province_id = $privince->id;

				}else{
				$province_id = 0;
				}

			

				$category = Category::findFirstBytitle($goods_name);
				if($category){

					$sql='INSERT INTO market_price (category_id,category_name,goods_name,market_name,publish_time,add_time,price,collect_type,source,province_id, unit, firstname) values ("%s","%s","%s","%s","%s","%s","%s","%s","%s", "%s", "%s", "%s")';

					$sql = sprintf($sql, $category->id,$category->title,$goods_name,$market_name,$publish_time,$add_time,$price,1,1,$province_id, $unit, $category->abbreviation);
					$this->db->execute($sql);

					$id = $this->db->lastInsertid();
					$sql='INSERT INTO market_todayprice (id,category_id,category_name,goods_name,market_name,publish_time,add_time,price,collect_type,source,province_id, unit, firstname) values ("%s","%s","%s","%s","%s","%s","%s","%s","%s", "%s", "%s", "%s", "%s")';

					$sql = sprintf($sql, $id,$category->id,$category->title,$goods_name,$market_name,$publish_time,$add_time,$price,1,1,$province_id, $unit, $category->abbreviation);
					$this->db->execute($sql);

					$sql = " delete from market_todayprice where publish_time!=".$date_time;
					$this->db->execute($sql);

					die('success');

				}else{

					$sql='INSERT INTO market_price (category_id,goods_name,market_name,publish_time,add_time,price,collect_type,source,province_id, unit) values ("%s","%s","%s","%s","%s","%s","%s","%s", "%s", "%s")';

					$sql = sprintf($sql, 0,$goods_name,$market_name,$publish_time,$add_time,$price,1,1,$province_id, $unit);
					$this->db->execute($sql);

					die('success');

				}			

		}
	}

	public function avgpriceAction(){

		set_time_limit(0);

	$date_time = strtotime(date('Y-m-d',time()));	

	$psql = "SELECT province_id as pro from market_todayprice where publish_time = '{$date_time}' group by province_id";
	$proList = $this->db->query($psql)->fetchAll();

		foreach ($proList as $pro) {
			$csql = "SELECT category_id as cat from market_todayprice where publish_time = '{$date_time}' and province_id = '{$pro['pro']}' group by category_id";
			$catList = $this->db->query($csql)->fetchAll();
			foreach ($catList as $cat) {
				$rsql = "SELECT count(*) as total, sum(price) as amt, unit from market_todayprice where publish_time = '{$date_time}' and province_id = '{$pro['pro']}' and category_id = '{$cat['cat']}'";
				$total = $this->db->query($rsql)->fetch();
				$before = strtotime('-1 day');
				$tmp=date('Ymd',$before);
				$ysql = "SELECT * from market_avgprice where publish_time = '{$tmp}' and province_id = '{$pro['pro']}' and category_id = '{$cat['cat']}'";
				$yes = $this->db->query($ysql)->fetch();
				$yesprice = $yes ? $yes['today_avgprice'] : 0;

				$unit = $total['unit'] ? $total['unit'] : '';
				$curAreas = isset($areas[$pro['pro']]) ? $areas[$pro['pro']] : '';
				$price_total = $total['total'] ? $total['total'] : 0;
				$price_amt = $total['amt'] ? sprintf("%.2f", $total['amt']) : 0;
				$todayprice = $total['total'] ? sprintf("%.2f",($total['amt']/$total['total'])) : 0;
				$format_time = date('Ymd', $date_time);
				$selsql = "SELECT * FROM market_avgprice where category_id='{$cat['cat']}' and publish_time = '{$format_time}' and province_id = '{$pro['pro']}'";
				$rs = $this->db->query($selsql)->fetch();
				if($rs) {
					$isql = "UPDATE market_avgprice set today_avgprice = '{$todayprice}',yesterday_avgprice='{$yesprice}' where category_id='{$cat['cat']}' and publish_time = '{$format_time}' and province_id = '{$pro['pro']}'";

				} else {
					$isql = "INSERT INTO market_avgprice (`today_avgprice`, `yesterday_avgprice`, `province_id`, `province_name`, `category_id`, `publish_time`, `unit`, `total`, `amount`) VALUES ('{$todayprice}', '{$yesprice}', '{$pro['pro']}', '{$curAreas}', '{$cat['cat']}', '{$format_time}', '{$unit}', '{$price_total}', '{$price_amt}')";
				}
				$this->db->execute($isql);
				//$pdo->exec($isql);
			}
		}

	//全国平均价格
		$categorysql = "SELECT category_id as cate from market_todayprice where publish_time = '{$date_time}' group by category_id";
		$categoryList = $this->db->query($categorysql)->fetchAll();
		foreach ($categoryList as $cate) {
			$countsql = "SELECT count(*) as count, sum(price) as amt, unit from market_todayprice where publish_time = '{$date_time}' and category_id = '{$cate['cate']}'";
			$count = $this->db->query($countsql)->fetch();

			//昨日全国平均价
			$before = strtotime('-1 day');
			$tmpdate=date('Ymd',$before);
			$allysql = "SELECT * from market_avgprice where publish_time = '{$tmpdate}' and province_id = '0' and province_name = '全国' and category_id = '{$cate['cate']}'";
			$allyes = $this->db->query($allysql)->fetch();
			$allyesprice = $allyes ? $allyes['today_avgprice'] : 0;

			$alltodayprice = $count['count'] ? sprintf("%.2f",($count['amt']/$count['count'])) : 0;
			$allunit = $count['unit'] ? $count['unit'] : '';
			$allprice_count = $count['count'] ? $count['count'] : 0;
			$allprice_amt = $count['amt'] ? sprintf("%.2f", $count['amt']) : 0;
			

			$allselsql = "SELECT * FROM market_avgprice where province_id='0' and province_name = '全国' and category_id = '{$cate['cate']}'";
			$allrs = $this->db->query($allselsql)->fetch();	

			$allformat_time = date('Ymd', $date_time);

			if($allrs) {
					$allisql = "UPDATE market_avgprice set today_avgprice = '{$alltodayprice}',yesterday_avgprice='{$allyesprice}' where category_id = '{$cate['cate']}' and publish_time = '{$allformat_time}' and province_id = '0' and province_id = '0'";

				} else {
					$allisql = "INSERT INTO market_avgprice (`today_avgprice`, `yesterday_avgprice`, `province_id`, `province_name`, `category_id`, `publish_time`, `unit`, `total`, `amount`) VALUES ('{$alltodayprice}', '{$allyesprice}', '0', '全国', '{$cate['cate']}', '{$allformat_time}', '{$allunit}', '{$allprice_count}', '{$allprice_amt}')";
				}
				//echo $allisql;die;
				$this->db->execute($allisql);

		}

		echo "<script>alert('操作成功');location.href='http://mdgdev.ync365.com/'</script>";
	}


}
