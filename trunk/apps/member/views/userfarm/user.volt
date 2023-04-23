{{ partial('layouts/member_header') }}
<link rel="stylesheet" href="http://yncstatic.b0.upaiyun.com/mdg/version2.5/css/verfiy.css">
<link rel="stylesheet" href="{{ constant('STATIC_URL') }}mdg/version2.5/css/verfiy.css">
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/version2.5/css/jquery.powertip.css" />
<script type="text/javascript" src="{{ constant('STATIC_URL') }}mdg/version2.5/js/jquery.powertip.min.js"></script>
<style>
#powerTip{ padding: 10px; max-width:242px; color:#999; font-family:'宋体'; line-height:24px; background:#fff2d8; white-space:normal; word-wrap:break-word;}
#powerTip.sw-alt:before, #powerTip.se-alt:before, #powerTip.s:before{ border-bottom:10px solid #fff2d8;}
#powerTip.nw-alt:before, #powerTip.ne-alt:before, #powerTip.n:before{ border-top:10px solid #fff2d8;}
</style>
    <div class="wrapper">
        <div class="w1190 mtauto f-oh">
        <div class="bread-crumbs w1185 mtauto">
            <span>{{ partial('layouts/ur_here') }}可信农场申请</span>
        </div>

            <!-- 左侧 -->
             {{ partial('layouts/navs_left') }}
            <!-- 右侧 -->
           <form action="/member/userfarm/usersave" method="post" id='userForm'>
            <div class="center-right f-fr">

                <div class="roles-apply f-oh">
                    <div class="title f-oh">
                        <span>可信农场申请</span>
                    </div>
                    <div class="m-title">基本信息</div>
                   
                    <div class="message clearfix">
                        <font style="line-height:20px;">
                            <i>*</i>产地服务站推荐人<br />手机号：
                        </font>
                        <div class="inputBox inputBox1 f-pr">
                            <div class="ms-personalBox  hb">
                                <input name="lwtt_phone"  class="input1" type="text">
                                <em class="f-db">(此处请填写推荐您来认证的产地服务站的手机号)</em>
                            </div>
                            <div class="ms-personalTips">
                                <a class="south-west-alt" title="产地服务站是能够整合当地产地资源，协助丰收汇开发整合更多的可信农场，并为可靠农产品交易提供物流配送支持的合作伙伴。" href="javascript:;">什么是产地服务站</a>
                                <em>|</em>
                                <a class="south-west-alt" title="丰收汇在全国各地正在开展活动，如果您不知道所在地区的产地服务站的联系信息，请拨打24小时客服电话400-8811-365进行咨询。" href="javascript:;">没有产地服务站推荐人怎么办</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="message clearfix">
                        <font>
                            <i>*</i>您的身份：
                        </font>
                        <div class="radioBox f-oh tabBtn">
                            <label class="f-db f-fl f-oh">
                                <input type="radio" onclick="show1()" value='0' name="member_type" checked>
                                <i>农户</i>
                            </label>
                            <label class="f-db f-fl f-oh">
                                <input type="radio" onclick="show2()"  value='1' name="member_type">
                                <i>农业企业</i>
                            </label>
                            <label class="f-db f-fl f-oh" >
                                <input type="radio" onclick="show2()" value='2' name="member_type">
                                <i>家庭农场</i>
                            </label>
                            <label class="f-db f-fl f-oh">
                                <input type="radio" onclick="show2()" value='3' name="member_type" >
                                <i>农业合作社</i>
                            </label>
                        </div>
                    </div>
                        <div class="tabChange" style="display:block;" id="show1" >
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>姓名：
                                </font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input name="name" class="input1" type="text">
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>身份证号：
                                </font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input name="credit_no" class="input1" type="text">
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>手机号：
                                </font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input name="mobile" class="input1" type="text">
                                </div>
                            </div>
                            <div class="line"></div>
                            <div class="m-title">认证信息</div>
                            <!-- <div class="message clearfix">
                                <font>
                                    <i>*</i>银行卡开户行：
                                </font>
                                <div class="selectBox selectBox2 f-pr">
                                    <select name="user_bank_name" class="select1">
                                        <option value="">请选择</option>
                                        {% for key , item in  bankList %}
                                        <option value='{{ item['gate_id']}}'>{{ item['bank_name']}}</option>
                                        {% endfor %}
                                    </select>
                                </div>
                            </div> -->
                            <!-- <div class="message clearfix">
                                <font>
                                    <i>*</i>开户行所在地：
                                </font>
                                <div class="selectBox selectBox1 f-pr">
                                    <select class="select1 mb10 class_bank_address" name='bank_province_id' >
                                        <option value="">省</option>
                                    </select>
                                    <select class="select1 mb10 class_bank_address"  name='bank_city_id'>
                                        <option value="">市</option>
                                    </select>
                                    <select class="select1 class_bank_address " name='bank_district_id' >
                                        <option value="">区／县</option>
                                    </select>
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>开户名：
                                </font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input class="input1" type="text" name='bank_account' id='bank_account'>
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>卡号：
                                </font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input class="input1" type="text" name='bank_cardno'>
                                </div>
                            </div> -->
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>身份证照：
                                </font>
                                <div class="loadBox f-pr">
                                    <div class="loadBtn">
                                        <input class="btn1" type="button" value="+上传图片" >
                                        <input class="btn2" type="file" id='user_idcard_picture' name='user_idcard_picture' >
                                    </div>
                                    <a class="link" href="{{ constant('STATIC_URL')}}/mdg/images/lodeImg_img3.jpg" target="_blank">查看样照</a>
                                    <input  class="hideTxt" name="photo" id="user_idcard_picture_hide" type="text" value="">
                                    <div class="tips">图片大小不超过2M，支持jpg、png、gif格式</div>
                                </div>
                            </div>
                            <!-- 上传成功后的图片位置 -->
                            <div class="imgBox f-oh" id='user_show_idcard_picture' >
                               <!--  <div class="imgs f-fl f-pr">
                                    <img src="images/hall-product-img.jpg" height="120" width="120" alt="">
                                </div> -->
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>身份证背面照：
                                </font>
                                <div class="loadBox f-pr">
                                    <div class="loadBtn">
                                        <input class="btn1" type="button" value="+上传图片">
                                        <input class="btn2" type="file" name='user_idcard_picture_back' id='user_idcard_picture_back' >
                                    </div>
                                    <a class="link" href="{{ constant('STATIC_URL')}}/mdg/images/lodeImg_img3.jpg" target="_blank">查看样照</a>
                                    <input class="hideTxt" name="back-photo" type="text" value="" id="user_idcard_picture_back_hide" >
                                    <div class="tips">图片大小不超过2M，支持jpg、png、gif格式</div>
                                </div>
                            </div>
                            <!-- 上传成功后的图片位置 -->
                            <div class="imgBox f-oh" id='user_show_idcard_picture_back' >
                                <!-- <div class="imgs f-fl f-pr">
                                    <img src="images/hall-product-img.jpg" height="120" width="120" alt="">
                                </div> -->
                            </div>
                            <div class="line"></div>
                            <div class="m-title">农场信息</div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>农场名：
                                </font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input name='farm_name' id='farm_name' class="input1" type="text">
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>农场地址：
                                </font>
                                <div class="selectBox selectBox1 f-pr">
                                    <select name='user_province_id' class="select1 mb10 selectAreas">
                                        <option value="">省</option>
                                    </select>
                                    <select name='user_city_id'  class="select1 mb10 selectAreas">
                                        <option value="">市</option>
                                    </select>
                                    <select name='user_district_id' class="select1 mb10 selectAreas">
                                        <option value="">区／县</option>
                                    </select>
                                     <select name='user_town_id' class="select1 mb10 selectAreas">
                                        <option value="">镇／乡</option>
                                    </select>
                                    <select name='user_village_id' data-target="#address-yz2-1" class="select1 mb10 selectAreas ">
                                        <option value="">街道</option>
                                    </select>
                                    <div class="f-oh">
                                        <input name='user_address' id='user_address' data-target="#address-yz2-2" class="input1" type="text">
                                    </div>
                                    <i class="dz-yz1" id="address-yz2-1"></i>
                                    <i class="dz-yz2" id="address-yz2-2"></i>
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>农场面积：
                                </font>
                                <div class="inputBox inputBox6 f-pr clearfix">
                                    <input  name='farm_areas' class="input1 f-fl" type="text">
                                    <i class="dw f-fl">亩</i>
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>种植作物：
                                </font>
                                <div class="select-box lang-select clearfix categrey-option f-pr" style="width:520px;">
                                    <div class="choose-box f-fl">
                                        <select name='category_name' onchange="selectBycate(this.value, '0' )" >
                                            {% for key , item in cateList %}
                                            <option value="{{ item.id }}">{{ item.title}}</option>
                                            {% endfor %}
                                        </select>
                                        <div class="erji-box" id='show_cate_chid_0'>
                                        </div>
                                    </div>
                                    <div class="btn-box f-fl">
                                        <a class="btn1" href="javascript:;">添加</a>
                                        <a class="btn2" href="javascript:;">删除</a>
                                    </div>
                                    <div class="result-box f-fl" id='result-box_0' >
                                    </div>
                                    <input id="category_name_text_0" style="width:1px;border:none;opacity:0;filter:alpha(opacity:0);" name='category_name_text_0' class="hideTxt" type="text" value=""  
                                    data-rule="种植作物:required;" />
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>农场照片：
                                </font>
                                <div class="loadBox f-pr">
                                    <div class="loadBtn">
                                        <input class="btn1" type="button" value="+上传图片">
                                        <input class="btn2" type="file" name='user_picture_path' id='user_picture_path' >
                                    </div>
                                   <!--  <a class="link" href="#">查看样照</a> -->
                                    
                                    <input class="hideTxt" name="farm-photo" id='user_picture_path_hide' type="text" value="">
                                    <div class="tips">图片大小不超过2M，支持jpg、png、gif格式</div>
                                </div>
                            </div>
                            <!-- 上传成功后的图片位置 -->
                            <div class="imgBox f-oh" id='user_show_picture_path' >
                               <!--  <div class="imgs f-fl f-pr">
                                    <img src="images/hall-product-img.jpg" height="120" width="120" alt="">
                                </div>
                                <div class="imgs f-fl f-pr">
                                    <img src="images/hall-product-img.jpg" height="120" width="120" alt="">
                                </div> -->
                            </div>
                            <div class="load-tips">
                                （农场主需手持租地合同或租地证明及身份证，在所属土地面前拍照）
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>耕地合同：
                                </font>
                                <div class="loadBox f-pr">
                                    <div class="loadBtn">
                                        <input class="btn1" type="button" value="+上传图片">
                                        <input class="btn2" type="file" name='user_picture_path_contact' id='user_picture_path_contact' >
                                    </div>
                                  <!--   <a class="link" href="#">查看样照</a> -->
                                    <input class="hideTxt" name="gdht" type="text" value="" id='user_picture_path_contact_hide' >
                                    <div class="tips">图片大小不超过2M，支持jpg、png、gif格式</div>
                                </div>
                            </div>
                            <!-- 上传成功后的图片位置 -->
                            <div class="imgBox f-oh" id='user_show_picture_path_contact' >
                               <!--  <div class="imgs f-fl f-pr">
                                    <img src="images/hall-product-img.jpg" height="120" width="120" alt="">
                                </div> -->
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>土地来源：
                                </font>
                                <div class="radioBox f-oh">
                                    <label class="f-db f-fl f-oh">
                                        <input type="radio" name='source' value='0' checked>
                                        <i>自有</i>
                                    </label>
                                    <label class="f-db f-fl f-oh">
                                        <input type="radio" name='source' value='1' >
                                        <i>流转</i>
                                    </label>
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>土地使用年限：
                                </font>
                                <div class="inputBox inputBox2 f-pr clearfix">
                                    <input class="input2 f-fl Wdate "  name="user_stime" data-target="#time-yz" type="text" id="d4331" onfocus="WdatePicker({dateFmt: 'yyyy-MM',maxDate:'#F{$dp.$D(\'d4332\',{M:0,d:0})}'})" readonly="readonly"   >
                                    <i class="heng f-fl">-</i>
                                    <input class="input2 f-fl Wdate "  name="user_etime" data-target="#time-yz" type="text" id="d4332" onfocus="WdatePicker({dateFmt: 'yyyy-MM',minDate:'#F{$dp.$D(\'d4331\',{M:0,d:1});}',maxDate:'2020-4-3'})" readonly="readonly"  >
                                    <i id="time-yz"></i>
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>农场简介：</font>
                                <div class="areaBox f-pr">
                                    <textarea name='user_describe' ></textarea>
                                </div>
                            </div>
                            <!-- <div class="message clearfix">
                                <font>推荐人：</font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input class="input1" type="text"  name='seusername' >
                                    <div class="tips mt10">（可以是县域服务中心或责任服务工程师）</div>
                                </div>
                            </div> -->
                            <div class="message clearfix">
                                <font>&nbsp;</font>
                                <div class="checkBox clearfix">
                                    <label class="clearfix f-fl checked">
                                        <input class="f-fl" style="margin-right:6px; margin-top:14px;" type="checkbox" checked="true" onclick='checkSubmit(this)'  />
                                        <i class="f-db f-fl" style="line-height:40px;">
                                            我已阅读并同意<a href="/member/userfarm/doc">《云农场可信农场协议》</a>
                                        </i>
                                    </label>
                                </div>
                            </div>
                            <input class="apply-btn" type="submit" value="提交申请">
                        </div>
                        <!-- 可信农场 农业企业 -->
                        <div class="tabChange" style="display:none;" id='show2'>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>公司名称：
                                </font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input  name='ent_company_name' class="input1" type="text">
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>注册登记证号：
                                </font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input  name='ent_register_no'   class="input1" type="text">
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>公司地址：
                                </font>
                                <div class="selectBox selectBox1 f-pr">
                                    <select name='province_id' class="select1 mb10 ent_selectAreas ">
                                        <option value="">省</option>
                                    </select>
                                    <select name='city_id' class="select1 mb10 ent_selectAreas">
                                        <option value="">市</option>
                                    </select>
                                    <select name='district_id' class="select1 mb10 ent_selectAreas">
                                        <option value="">区／县</option>
                                    </select>
                                    <select name='town_id'  data-target="#address-yz1-1" class="select1 mb10 ent_selectAreas ">
                                        <option value="">街道</option>
                                    </select>
                                    <div class="f-oh">
                                        <input name='address'  data-target="#address-yz1-2" class="input1" type="text">
                                    </div>
                                    <i class="dz-yz1" id="address-yz1-1"></i>
                                    <i class="dz-yz2" id="address-yz1-2"></i>
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>企业法人：
                                </font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input name='ent_erprise_legal_person' class="input1" type="text">
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>身份证号：
                                </font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input name='ent_certificate_no' class="input1" type="text">
                                </div>
                            </div>
                            <div class="line"></div>
                            <div class="m-title">联系人信息</div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>姓名：
                                </font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input name='ent_contact_name'  class="input1" type="text">
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>手机号：
                                </font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input name='ent_contact_phone' class="input1" type="text">
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>传真：</font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input class="input1" type="text"  name='ent_contact_fax' >
                                </div>
                            </div>
                            <div class="line"></div>
                            <div class="m-title">认证信息</div>
                          <!--   <div class="message clearfix">
                                <font>
                                    <i>*</i>银行卡开户行：
                                </font>
                                <div class="selectBox selectBox2 f-pr">
                                    <select  name='ent_bank_name' class="select1">
                                            <option value=''>请选择</option>
                                            {% for key , item in  bankList %}
                                            <option value='{{ item['gate_id']}}'>{{ item['bank_name']}}</option>
                                            {% endfor %}
                                    </select>
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>开户行所在地：
                                </font>
                                <div class="selectBox selectBox1 f-pr">
                                    <select name='ent_bank_province_id' class="select1 mb10 ent_class_bank_address ">
                                        <option value="">省</option>
                                    </select>
                                    <select name='ent_bank_city_id' class="select1 mb10 ent_class_bank_address ">
                                        <option value="">市</option>
                                    </select>
                                    <select name='ent_bank_district_id' class="select1 ent_class_bank_address ">
                                        <option value="">区／县</option>
                                    </select>
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>开户公司名称：
                                </font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input name='ent_bank_account'  class="input1" type="text">
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>卡号：
                                </font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input  name='ent_bank_cardno' class="input1" type="text">
                                </div>
                            </div> -->
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>营业执照：
                                </font>
                                <div class="loadBox f-pr">
                                    <div class="loadBtn">
                                        <input class="btn1" type="button" value="+上传图片">
                                        <input class="btn2" type="file"  id='ent_identity_picture_lic' >
                                    </div>
                                    <a class="link" href="{{ constant("STATIC_URL")}}/mdg/images/lodeImg_img4.jpg">查看样照</a>
                                    <input class="hideTxt" name="gs-yezz" type="text" id='ent_identity_picture_lic_hide' value="">
                                    <div class="tips">图片大小不超过2M，支持jpg、png、gif格式</div>
                                </div>
                            </div>
                            <!-- 上传成功后的图片位置 -->
                            <div class="imgBox f-oh" id='ent_show_identity_picture_lic' >
                                
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>身份证照：
                                </font>
                                <div class="loadBox f-pr">
                                    <div class="loadBtn">
                                        <input class="btn1" type="button" value="+上传图片">
                                        <input class="btn2" type="file" id='ent_idcard_picture' >
                                    </div>
                                    <a class="link" href="{{ constant("STATIC_URL")}}/mdg/images/lodeImg_img3.jpg">查看样照</a>
                                    <input class="hideTxt" name="gs-photo" id='ent_idcard_picture_hide' type="text" value="">
                                    <div class="tips">图片大小不超过2M，支持jpg、png、gif格式</div>
                                </div>
                            </div>
                            <!-- 上传成功后的图片位置 -->
                            <div class="imgBox f-oh"  id='ent_show_idcard_picture' >
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>身份证背面照：
                                </font>
                                <div class="loadBox f-pr">
                                    <div class="loadBtn">
                                        <input class="btn1" type="button" value="+上传图片">
                                        <input class="btn2" type="file" id='ent_idcard_picture_back' >
                                    </div>
                                    <a class="link" href="{{ constant('STATIC_URL')}}/mdg/images/lodeImg_img3.jpg">查看样照</a>
                                    <input class="hideTxt" name="gs-back-photo" type="text" value="" id='ent_idcard_picture_back_hide' >
                                    <div class="tips">图片大小不超过2M，支持jpg、png、gif格式</div>
                                </div>
                            </div>
                            <!-- 上传成功后的图片位置 -->
                            <div class="imgBox f-oh" id='ent_show_idcard_picture_back' >
                            </div>
                            <div class="line"></div>
                            <div class="m-title">农场信息</div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>农场名：
                                </font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input  name='ent_farm_name' class="input1" type="text">
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>农场地址：
                                </font>
                                <div class="selectBox selectBox1 f-pr">
                                    <select name='ent_province_id' class="select1 mb10 ent_farm_selectAreas ">
                                        <option value="">省</option>
                                    </select>
                                    <select name='ent_city_id' class="select1 mb10 ent_farm_selectAreas ">
                                        <option value="">市</option>
                                    </select>
                                    <select name='ent_district_id' class="select1 mb10 ent_farm_selectAreas ">
                                        <option value="">区／县</option>
                                    </select>
                                    <select name='ent_town_id' class="select1 mb10 ent_farm_selectAreas ">
                                        <option value="">镇／乡</option>
                                    </select>
                                    <select name='ent_village_id' data-target="#address-yz-1" class="select1 mb10 ent_farm_selectAreas ">
                                        <option value="">街道</option>
                                    </select>
                                    <div class="f-oh">
                                        <input name="ent_address" data-target="#address-yz-2" class="input1" type="text">
                                    </div>
                                    <i class="dz-yz1" id="address-yz-1"></i>
                                    <i class="dz-yz2" id="address-yz-2"></i>
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>农场面积：
                                </font>
                                <div class="inputBox inputBox6 f-pr clearfix">
                                    <input  name='ent_farm_area' class="input1 f-fl" type="text">
                                    <i class="dw f-fl">亩</i>
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>种植作物：
                                </font>
                                <div class="select-box lang-select clearfix categrey-option f-pr" style="width:520px;">
                                    <div class="choose-box f-fl">
                                        <select name='category_name' onchange="selectBycate(this.value, '1')" >
                                            {% for key , item in cateList %}
                                            <option value="{{ item.id }}">{{ item.title}}</option>
                                            {% endfor %}
                                        </select>
                                        <div class="erji-box" id='show_cate_chid_1' >
                                        </div>
                                    </div>
                                    <div class="btn-box f-fl">
                                        <a class="btn1" href="javascript:;">添加</a>
                                        <a class="btn2" href="javascript:;">删除</a>
                                    </div>
                                    <div class="result-box f-fl"  id='result-box_1' >
                                    </div>
                                    <input id="category_name_text_1" name='category_name_text_1' style="width:1px;border:none;opacity:0;filter:alpha(opacity:0);" type="text" value=""  data-rule="种植作物:required;" />
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>农场照片：
                                </font>
                                <div class="loadBox f-pr">
                                    <div class="loadBtn">
                                        <input class="btn1" type="button" value="+上传图片">
                                        <input class="btn2" type="file"  id='ent_picture_path' >
                                    </div>
                            
                                    <input class="hideTxt" name="gs-farm-photo" type="text" value="" id="ent_picture_path_hide" >
                                    <div class="tips">图片大小不超过2M，支持jpg、png、gif格式</div>
                                </div>
                            </div>
                            <!-- 上传成功后的图片位置 -->
                            <div class="imgBox f-oh"  id='ent_show_picture_path' >

                            </div>
                            <div class="load-tips">
                                （农场主需手持租地合同或租地证明及身份证，在所属土地面前拍照）
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>耕地合同：
                                </font>
                                <div class="loadBox f-pr">
                                    <div class="loadBtn">
                                        <input class="btn1" type="button" value="+上传图片">
                                        <input class="btn2" type="file" id='ent_picture_path_contact' >
                                    </div>
                                    <input class="hideTxt" name="gs-gdht" type="text" value="" id='ent_picture_path_contact_hide' >
                                    <div class="tips">图片大小不超过2M，支持jpg、png、gif格式</div>
                                </div>
                            </div>
                            <!-- 上传成功后的图片位置 -->
                            <div class="imgBox f-oh" id='ent_show_picture_path_contact' >
                              
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>土地来源：
                                </font>
                                <div class="radioBox f-oh">
                                    <label class="f-db f-fl f-oh">
                                        <input type="radio" name='ent_source'  value='0' checked>
                                        <i>自有</i>
                                    </label>
                                    <label class="f-db f-fl f-oh">
                                        <input type="radio" name='ent_source' value='1' >
                                        <i>流转</i>
                                    </label>
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>土地使用年限：
                                </font>
                                <div class="inputBox inputBox2 f-pr clearfix">
                                    <input readonly="readonly" class="input2 f-fl Wdate " name="ent_stime" id="d43313" onfocus="WdatePicker({dateFmt: 'yyyy-MM',maxDate:'#F{$dp.$D(\'d4334\',{M:0,d:0})}'})" data-target="#time-yz1" type="text">
                                    <i class="heng f-fl">-</i>
                                    <input readonly="readonly" class="input2 f-fl Wdate " name="ent_etime"  id="d4334"  onfocus="WdatePicker({dateFmt: 'yyyy-MM',minDate:'#F{$dp.$D(\'d43313\',{M:0,d:1});}',maxDate:'2020-4-3'})" data-target="#time-yz1" type="text">
                                    <i id="time-yz1"></i>
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>农场简介：</font>
                                <div class="areaBox f-pr">
                                    <textarea name='ent_describe' ></textarea>
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>&nbsp;</font>
                                <div class="checkBox clearfix">
                                    <label class="clearfix f-fl checked">
                                        <input class="f-fl" style="margin-right:6px; margin-top:14px;" type="checkbox" checked="true" onclick='checkSubmit(this)'  />
                                        <i class="f-db f-fl" style="line-height:40px;">
                                            我已阅读并同意<a href="/member/userfarm/doc">《云农场可信农场协议》</a>
                                        </i>
                                    </label>
                                </div>
                            </div>
                            <input class="apply-btn" type="submit" value="提交申请">
                        </div>


                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- 底部 -->
   {{ partial('layouts/footer') }}

    <script>
        $(function(){
            // 切换
            // $('.tabBtn label').click(function(){
            //     $('.tabBtn input').prop('checked', false);
            //     $('.tabChange').hide();

            //     $(this).find('input').prop('checked', true);
            //     $('.tabChange').eq($(this).index()).show();
            // });

            // 验证
            $('#userForm').validator({
                rules: {
                    wxxx : [/^[0-9a-zA-Z]*$/, '只能输入字母数字'],
                    nimei  : [/^([0-9])+(\.([0-8])+)?$/, '请输入数字']
                },
                //timely: 2,
                ignore: ':hidden',
                fields: {
                    'name': '姓名:required;fuck;length[2~10]',
                    'credit_no': '身份证号:required;ID_card',
                    'mobile': '手机号:required;mobile',
                    'lwtt_phone':'产地服务站手机号:required;mobile;remote[/member/userfarm/checklwtt]',
                    'user_village_id': '农场地址:required;',
                    'user_address': '农场地址:required;length[5~50];length_name',
                    'user_bank_name': '银行卡开户行:required;',
                    'bank_district_id': '开户行所在地:required;',
                    'bank_account': '开户名:required;chinese',
                    'bank_cardno': '卡号:required;mark',
                    'photo': '身份证照:required;',
                    'back-photo': '身份证背面照:required;',
                    'farm_name': '农场名:required;length[2~30];length_name',
                    'farm_areas': '农场面积:required;digits;length[~8]',
                    'farm-photo': '农场照片:required;',
                    'gdht': '耕地合同:required;',
                    'user_stime': '土地使用年限:required;',
                    'user_etime': '土地使用年限:required;',
                    //'seusername':  '推荐人:mobile;remote[/member/userfarm/checkEngineer]',
                    //'ent_seusername':  '推荐人:mobile;remote[/member/userfarm/checkEngineer];',
                    'ent_company_name': '公司名称:required;length[2~30];length_name',
                    'ent_register_no': '注册登记证号:required;wxxx',
                    'town_id': '公司地址:required;',
                    'address': '公司地址:required;length[5~50];length_name',
                    'ent_erprise_legal_person': '企业法人:required;fuck;length[2~10]',
                    'ent_contact_name': '姓名:required;fuck;length[2~10]',
                    'ent_certificate_no': '身份证号:required;ID_card',
                    'ent_contact_phone': '手机号:required;mobile',
                    'gs-address1': '农场地址:required;',
                    'gs-address2': '农场地址:required;',
                    'ent_bank_name': '银行卡开户行:required;',
                    'ent_bank_district_id': '开户行所在地:required;',
                    'ent_bank_account': '开户名:required;chinese',
                    'ent_bank_cardno': '卡号:required;mark',
                    'gs-yezz': '营业执照:required;',
                    'gs-photo': '身份证照:required;',
                    'gs-back-photo': '身份证背面照:required;',
                    'ent_farm_name': '农场名:required;chinese',
                    'ent_village_id': '农场地址:required;',
                    'ent_address': '农场地址:required;length_name;length[5~50];',
                    'ent_farm_area': '农场面积:required;digits',
                    'gs-farm-photo': '农场照片:required;',
                    'gs-gdht': '耕地合同:required;',
                    'ent_stime': '土地使用年限:required;',
                    'ent_etime': '土地使用年限:required;',
                    'user_describe':'农场简介:length_name;length[0~500];',
                    'ent_describe':'农场简介:length_name;length[0~500];'
                }
            });

            // 表单分类添加删除
            (function(){
                $('.categrey-option .erji-box a').click(function(){
                    $(this).parents('.categrey-option').find('.result-box em').removeClass('active');

                    $(this).parent().find('a').removeClass('active');
                    $(this).addClass('active');
                });

                $('.categrey-option .result-box em').click(function(){
                    $(this).parents('.categrey-option').find('.erji-box a').removeClass('active');

                    $(this).parent().find('em').removeClass('active');
                    $(this).addClass('active');
                });
                
                //添加
                var add_arr = [];
                $('.categrey-option .btn-box .btn1').click(function(){  
                    $(this).parents('.categrey-option').find('.erji-box a').each(function(){
                        if($(this).hasClass('active')){
                            var txt = $(this).text();
                            add_arr.push(txt);
                        }else{
                            return ;
                        };
                    });
                    if(add_arr.length > 0){
                        var str = "<em>"+ add_arr[0] +"</em>";
                        $(str).appendTo('.categrey-option .result-box');

                        $(this).parents('.categrey-option').find('.result-box em').click(function(){
                            $(this).parents('.categrey-option').find('.erji-box a').removeClass('active');

                            $(this).parent().find('em').removeClass('active');
                            $(this).addClass('active');
                        });

                        add_arr = [];
                    }else{
                        alert("请选择分类！");
                    }
                });

                //删除
                var delete_arr = [];
                $('.categrey-option .btn-box .btn2').click(function(){          
                    $(this).parents('.categrey-option').find('.result-box em').each(function(){
                        if($(this).hasClass('active')){                 
                            var txt = $(this).text();
                            delete_arr.push(txt);
                            $(this).remove();
                        }else{
                            return ;
                        };
                    });

                    if(delete_arr.length > 0){
                        return ;
                    }else{
                        alert("请选择分类！");
                    };
                });         

            })();
        });
    </script>
    <script type="text/javascript">
    function checkSubmit(obj) {
        if(!$(obj).prop('checked')) {
            $('.apply-btn').attr('disabled', true);
        }else{
            $('.apply-btn').removeAttr('disabled');
        }
    }
                
    $(function () {


        $('input[name="member_type"]').click(function () {
            var val = $(this).val();
            if(val == 0 ) {
                $('#member_buyer_0').show();
                $('#member_buyer_1').hide();
                $('#category_name_text_0').val('');
                selectBycate(1, 0);
                $('#userForm').validator('cleanUp');
                

            }else{
                $('#member_buyer_1').show();
                $('#member_buyer_0').hide();
                selectBycate(1, 1);
                $('#category_name_text_1').val('');
                $('#userForm').validator('cleanUp');

            }
        })
    })
    // 删除图片
    function closeImg(obj, id, key ) {
        
        $.getJSON('/upload/deltmpfile', {id : id}, function(data) {
            alert(data.msg);
            if(data.state) {
                $(obj).parents('dl').slideUp();
            }
        });
    }
    //公司地址
    $(".ent_selectAreas").ld({
        ajaxOptions: {
            "url": "/ajax/getareasfull"
        },
        defaultParentId: 0,
        style: {
            "width": 250
        }
    });
    function show1(){
       $("#show1").show();
       $("#show2").hide();
    }
    function show2(){
       $("#show2").show();
       $("#show1").hide();
    }
    $('.south-west-alt').powerTip({
                placement: 's',
                smartPlacement: true
    });
    </script>
    <script type="text/javascript" src="/mdg/js/user_farm.js?sid={{ sid }}&rand=<?php echo rand(1,999);?>"></script>
<script>
 
</script>
<style>.hb .n-right .n-error .n-msg{bottom: -28px;}</style>
</body>
</html>
