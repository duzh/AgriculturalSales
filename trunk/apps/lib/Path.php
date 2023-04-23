<?php

namespace Lib;


class Path {
	static function tmpPath() {

		$path = '/upload/tmp/';
		$path .= self::famatPath();
		$path .= '/';
		return $path;
	}
    //供应发布商品的图片
	static function imagePath() {
		$path = '/upload/images/';
		$path .= self::famatPath();
		$path .= '/';
		return $path;
	}

	static function famatPath() {
		return date('Y/m/d');
	}
	//分类图片
	static function categroyPath(){
		$path = '/upload/CategoryFile/';
		$path .= self::famatPath();
		$path .= '/';
		return $path;
	}
	//分类小图
	static function categroyminPath(){
		$path = '/upload/Categorymin/';
		$path .= self::famatPath();
		$path .= '/';
		return $path;
	}
	//后台广告位
	static function adPath(){
		$path = '/upload/ad/';
		$path .= self::famatPath();
		$path .= '/';
		return $path;
	}

	//银行卡照片
	static function bankPath(){
		$path = '/upload/bank/';
		$path .= self::famatPath();
		$path .= '/';
		return $path;
	}
	
	//身份证正面照
	static function identity_card_frontPath(){
		$path = '/upload/identitycardfront/';
		$path .= self::famatPath();
		$path .= '/';
		return $path;
	}
	//身份证背面照
	static function identity_card_backPath(){
		$path = '/upload/identitycardback/';
		$path .= self::famatPath();
		$path .= '/';
		return $path;
	}
	//持身份证正面头部照
	static function identity_picture_licPath(){
		$path = '/upload/identitypicturelicPath/';
		$path .= self::famatPath();
		$path .= '/';
		return $path;
	}
	//企业税务登记证照
	static function tax_registrationPath(){
		$path = '/upload/taxregistration/';
		$path .= self::famatPath();
		$path .= '/';
		return $path;
	}
    //组织机构代码证
    static function organization_codePath(){
		$path = '/upload/organizationcode/';
		$path .= self::famatPath();
		$path .= '/';
		return $path;
	}
	
	// 店铺宣传图
	static function mapPath() {
		$path = '/upload/map/';
		$path .= self::famatPath();
		$path .= '/';
		return $path;
	}
	//手机端分类图片
    static function mobliePath(){
        $path = '/upload/moblie/';
		$path .= self::famatPath();
		$path .= '/';
		return $path;
    }
    static function personal_logoPath() {
    	$path = '/upload/personal_logo/';
		$path .= self::famatPath();
		$path .= '/';
		return $path;
    }
    /**
     * 服务站实地
     * @return [type] [description]
     */
    static function servicePic() {
    	$path = '/upload/servicepic/';
		$path .= self::famatPath();
		$path .= '/';
		return $path;
    }

    /**
     * 店铺推荐图pain
     * @return [type] [description]
     */
    static function shoprec() {
    	$path = '/upload/shoprec/';
		$path .= self::famatPath();
		$path .= '/';
		return $path;
    }
    /**
     * 标签安全鉴定文件
     * @return [type] [description]
     */
    static function tagcertifying_file() {
    	
    	$path = '/upload/tag/certifying_file/';
		$path .= self::famatPath();
		$path .= '/';
		return $path;
    }
    /**
     * 产地照片
     * @return [type] [description]
     */
    static function originPath () {
    	$path = '/upload/tag/origin/';
		$path .= self::famatPath();
		$path .= '/';
		return $path;
    }

    /**
     * 种植作业
     * @return [type] [description]
     */
    static function plantPath () {
    	$path = '/upload/tag/plant/';
		$path .= self::famatPath();
		$path .= '/';
		return $path;
    }

    /**
     * 个人中心头像
     * @return [type] [description]
     */
    static function  HeadPath () {
    	$path = '/upload/member/head/';
		$path .= self::famatPath();
		$path .= '/';
		return $path;
    }
	
	/**
     * 咨询图片
     * @return [type] [description]
     */
    static function  AdvisoryPath () {
    	$path = '/upload/advisory/';
		$path .= self::famatPath();
		$path .= '/';
		return $path;
    }


    /**
     * 咨询图片
     * @return [type] [description]
     */
    static function  bankcardPath () {
    	$path = '/upload/bankcard/';
		$path .= self::famatPath();
		$path .= '/';
		return $path;
    }

    /**
     * 个人手持身份证照
     * @return [type] [description]
     */
    static function  memberbankcardPath () {
    	$path = '/upload/memberbankcard/';
		$path .= self::famatPath();
		$path .= '/';
		return $path;
    }

    /**
     * 身份证
     * @return [type] [description]
     */
    static function  membercardPath () {
    	$path = '/upload/membercard/';
		$path .= self::famatPath();
		$path .= '/';
		return $path;
    }

    /**
     * 身份证背面
     * @return [type] [description]
     */
    static function  membercardbackPath () {
    	$path = '/upload/membercard/';
		$path .= self::famatPath();
		$path .= '/';
		return $path;
    }

    /**
     * 用户农场信息
     * @return [type] [description]
     */
    static function  memberpicturePath () {
    	$path = '/upload/picture/';
		$path .= self::famatPath();
		$path .= '/';
		return $path;
    }

    /**
     * 用户农场信息- 个人营业执照
     * @return [type] [description]
     */
    static function  memberidentityPath () {
    	$path = '/upload/identity/';
		$path .= self::famatPath();
		$path .= '/';
		return $path;
    }
     /**
     * 用户农场信息- 个人营业执照
     * @return [type] [description]
     */
    static function  picturnwallPath () {
    	$path = '/upload/picturnwall/';
		$path .= self::famatPath();
		$path .= '/';
		return $path;
    }
    /**
     * 个人中心 税务证或者组织机构代码证
     **/
	static function identity_picture_orgtaxPath(){
		$path = '/upload/identityorgniztax/';
		$path .= self::famatPath();
		$path .= '/';
		return $path;
	}

    /**
     * 个人中心 农场合同
     **/

	static function memberpicturecontactPath(){
		$path = '/upload/identityorgniztax/';
		$path .= self::famatPath();
		$path .= '/';
		return $path;
	}

    /**
     * 5fengshou 友情连接
     **/
	static function friendlinkpath(){
		$path = '/upload/5fengshou/websitelink/';
		$path .= self::famatPath();
		$path .= '/';
		return $path;
	}
	static function crediblefarminfopath(){
		$path = '/upload/crediblefarminfo/';
		$path .= self::famatPath();
		$path .= '/';
		return $path;
	}
    

}