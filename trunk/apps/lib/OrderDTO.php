<?php
namespace Lib;

class OrderDTO {

	static function BooleanDTO($flag=false) {
		$dto = new \ORDER\BooleanDTO();
		$dto->value = $flag;
		return $dto;
	}

	static function OrderInfoDTO($data=array()) {
		$dto = new \ORDER\OrderInfoDTO();
		$dto->orderMainInfo = self::OrderMainInfoDTO($data['info']);
		$dto->orderAddressInfo = self::OrderAddressInfoDTO($data['address']);
		$dto->orderGoodsInfo = self::OrderGoodsInfoDTO($data['goods']);
		$dto->orderPreferentialInfo = self::OrderPreferentialInfoDTO($data['prefer']);
		//$dto->orderPreferentialInfo = '';
		return $dto;
	}

	static function OrderMainInfoDTO($info=array()) {
		$dto = new \ORDER\OrderMainInfoDTO();
		$dto->id = (int) (isset($info['id']) ? intval($info['id']) : 0);
		$dto->orderSn = (string) (isset($info['orderSn']) ? $info['orderSn'] : '');
		$dto->orderType = (int) (isset($info['orderType']) ? intval($info['orderType']) : 0);
		$dto->orderDevice = (int) (isset($info['orderDevice']) ? intval($info['orderDevice']) : 0);
		$dto->orderStatus = (int) (isset($info['orderStatus']) ? intval($info['orderStatus']) : 0);
		$dto->buyerUserid = (string) (isset($info['buyerUserid']) ? $info['buyerUserid'] :'');
		//$dto->buyerUserid = (int) (isset($info['buyerUserid']) ? intval($info['buyerUserid']) : 0);
		$dto->buyerName = (string) (isset($info['buyerName']) ? $info['buyerName'] : '');
		$dto->buyerPhone = (string) (isset($info['buyerPhone']) ? $info['buyerPhone'] : '');
		$dto->jobNumber = (string) (isset($info['jobNumber']) ? $info['jobNumber'] : '');
		$dto->jobUsername = (string) (isset($info['jobUsername']) ? $info['jobUsername'] : '');
		$dto->payId = (int) (isset($info['payId']) ? intval($info['payId']) : 0);
		$dto->payWay = (string) (isset($info['payWay']) ? $info['payWay'] : '');
		$dto->payUrl = (string) (isset($info['payUrl']) ? $info['payUrl'] : '');
		$dto->payComment = (string) (isset($info['payComment']) ? $info['payComment'] : '');
		$dto->orderAmount = (double) (isset($info['orderAmount']) ? floatval($info['orderAmount']) : 0);
		$dto->shippingFee = (double) (isset($info['shippingFee']) ? floatval($info['shippingFee']) : 0);
		$dto->payAmount = (double) (isset($info['payAmount']) ? floatval($info['payAmount']) : 0);
		$dto->reservationShippingTime = (int) (isset($info['reservationShippingTime']) ? intval($info['reservationShippingTime']) : 0);		
		$dto->createTime = (int) (isset($info['createTime']) ? intval($info['createTime']) : 0);
		$dto->cancelTime = (int) (isset($info['cancelTime']) ? intval($info['cancelTime']) : 0);
		$dto->payTime = (int) (isset($info['payTime']) ? intval($info['payTime']) : 0);
		$dto->comment = (string) (isset($info['comment']) ? $info['comment'] : '');
		
		return $dto;

	}

	static function OrderAddressInfoDTO($address=array()) {
		$dto = new \ORDER\OrderAddressInfoDTO();
		$dto->id = (int) (isset($address['id']) ? intval($address['id']) : 0);
		$dto->orderSn = (string) (isset($address['orderSn']) ? $address['orderSn'] : '');
		$dto->provinceName = (string) (isset($address['provinceName']) ? $address['provinceName'] : '');
		$dto->provinceId = (int) (isset($address['provinceId']) ? intval($address['provinceId']) : 0);
		$dto->cityId = (int) (isset($address['cityId']) ? intval($address['cityId']) : 0);
		$dto->cityName = (string) (isset($address['cityName']) ? $address['cityName'] : '');
		$dto->districtId = (int) (isset($address['districtId']) ? intval($address['districtId']) : 0);
		$dto->districtName = (string) (isset($address['districtName']) ? $address['districtName'] : '');
		$dto->townId = (int) (isset($address['townId']) ? intval($address['townId']) : 0);
		$dto->townName = (string) (isset($address['townName']) ? $address['townName'] : '');
		$dto->villageId = (int) (isset($address['villageId']) ? intval($address['villageId']) : 0);
		$dto->villageName = (string) (isset($address['villageName']) ? $address['villageName'] : '');
		$dto->address = (string) (isset($address['address']) ? $address['address'] : '');
		return $dto;
	}

	static function OrderGoodsInfoDTO($goods=array()) {
		$rs = array();
		foreach($goods as $g) {
			$dto = new \ORDER\OrderGoodsInfoDTO();
			$dto->id = (int) (isset($g['id']) ? intval($g['id']) : 0);
			$dto->orderSn = (string) (isset($g['orderSn']) ? $g['orderSn'] : '');
			$dto->itemId = (int) (isset($g['itemId']) ? intval($g['itemId']) : 0);
			$dto->goodsId = (string) (isset($g['goodsId']) ? $g['goodsId'] : '');
			$dto->goodsCode=(string) (isset($g['goodsCode']) ? $g['goodsCode'] : '');
			$dto->goodsName = (string) (isset($g['goodsName']) ? $g['goodsName'] : '');
			$dto->goodsType = (int) (isset($g['goodsType']) ? intval($g['goodsType']) : 0);
			$dto->goodsAttr = (string) (isset($g['goodsAttr']) ? $g['goodsAttr'] : '');
			$dto->goodsUnit = (string) (isset($g['goodsUnit']) ? $g['goodsUnit'] : '');
			$dto->goodsNumber = (int) (isset($g['goodsNumber']) ? intval($g['goodsNumber']) : 0);
			$dto->marketPrice = (double) (isset($g['marketPrice']) ? floatval($g['marketPrice']) : 0);
			$dto->salePrice = (double) (isset($g['salePrice']) ? floatval($g['salePrice']) : 0);
			$dto->supplierId = (string) (isset($g['supplierId']) ? $g['supplierId'] : '');
			$dto->supplierName = (string) (isset($g['supplierName']) ? $g['supplierName'] : '');
			$dto->supplierPhone = (string) (isset($g['supplierPhone']) ? $g['supplierPhone'] : '');
			$dto->createTime = (int) (isset($g['createTime']) ? intval($g['createTime']) : 0);
			$dto->isCancelled = (int) (isset($g['isCancelled']) ? intval($g['isCancelled']) : 0);
			$dto->isHaigou = (int) (isset($g['isHaigou']) ? intval($g['isHaigou']) : 0);
			$dto->goodsFreight = (double) (isset($g['goodsFreight']) ? floatval($g['goodsFreight']) : 0);
			$dto->promotionId = (int) (isset($g['promotionId']) ? intval($g['promotionId']) : 0);
			$dto->promotionType = (int) (isset($g['promotionType']) ? intval($g['promotionType']) : 0);		
			$dto->promotionName = (string) (isset($g['promotionName']) ? $g['promotionName'] : '');
			$dto->goodsRemark = (string) (isset($g['goodsRemark']) ? $g['goodsRemark'] : '');
			$rs[] = $dto;
		}
		
		return $rs;
	}

	static function OrderPreferentialInfoDTO($prefer=array()) {

		$rs = array();
		foreach ($prefer as $p) {
			$dto = new \ORDER\OrderPreferentialInfoDTO();
			$dto->id = (int) (isset($p['id']) ? intval($p['id']) : 0);
			$dto->orderSn = (string) (isset($p['orderSn']) ? $p['orderSn'] : '');
			$dto->preferId = (int) (isset($p['preferId']) ? intval($p['preferId']) : 0);
			$dto->preferCode=(string) (isset($p['preferCode']) ? $p['preferCode'] : '');
			$dto->preferName = (string) (isset($p['preferName']) ? $p['preferName'] : '');
			$dto->preferAmount = (double) (isset($p['preferAmount']) ? floatval($p['preferAmount']) : 0);
			$dto->supplierId = (string) (isset($p['supplierId']) ? $p['supplierId'] : '');
			$dto->supplierName = (string) (isset($p['supplierName']) ? $p['supplierName'] : '');
			$rs[] = $dto;
			
		}
	
		return $rs;
	}	

	static function StringDTO($str=''){
		$dto = new \ORDER\StringDTO();
		$dto->value =(string) (isset($str) ? $str : '');//$str;
		
		return $dto;
	}
	static function IntDTO($int=''){
		$dto = new \ORDER\IntDTO();
		$dto->value = (int) (isset($int) ? intval($int) : 0);
		
		return $dto;
	}
	static function OrderOperatorDTO ($data=array()){
		 $dto = new \ORDER\OrderOperatorDTO ();
		 $dto->userId=(string) (isset($data['userId']) ? $data['userId'] : '');
		 $dto->userName=(string) (isset($data['userName']) ? $data['userName'] : '');
		 return $dto;
	}
	static function ShippingDTO($data){
		 $dto = new \ORDER\ShippingDTO();
		 $dto->shippingType=(string) (isset($data['shippingType']) ? $data['shippingType'] : '');
		 $dto->consignee=(string) (isset($data['consignee']) ? $data['consignee'] : '');
		 $dto->phone=(string) (isset($data['phone']) ? $data['phone'] : '');
		 $dto->buyerComment = (string) (isset($data['buyerComment']) ? $data['buyerComment'] : '');
		 $dto->deliveryId=(int) (isset($data['deliveryId']) ? intval($data['deliveryId']) : 0 );
		 $dto->deliveryWay=(string) (isset($data['deliveryWay']) ? $data['deliveryWay'] : '');
		 return $dto;
	}
}