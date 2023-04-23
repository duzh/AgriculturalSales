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

            <!-- 左侧 -->
             {{ partial('layouts/navs_left') }}
            <!-- 右侧 -->
           <form action="/member/userfarm/usersave" method="post" id='userForm'>
             <input type="hidden" name="cid" id="cid" value="{{ creditid }}" >
            <input type="hidden" name="tp" id="tp" value="{{ user.type }}" >
            <input type="hidden" name="isedit" value="1" >
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
                            <div class="ms-personalBox">
                                <input name="lwtt_phone"  class="input1" value="{% if user.se_mobile %}{{user.se_mobile}}{% endif %}" 
                                type="text">
                                <em class="f-db">(此处请填写推荐您来认证的产地服务站的手机号)</em>
                            </div>
                            <div class="ms-personalTips">
                                <a class="south-west-alt" title="产地服务站是能够整合当地产地资源，协助丰收汇开发整合更多的可信农场，并为可靠农产品交易提供物流配送支持的合作伙伴。" href="javascript:;">什么是产地服务站</a>
                                <em>|</em>
                                <a class="south-west-alt" title="丰收汇在全国各地正在开展活动，如果您不知道所在地区的盟商的联系信息，请拨打24小时客服电话400-8811-365进行咨询。" href="javascript:;">没有产地服务站推荐人怎么办</a>
                            </div>
                        </div>
                    </div>
                    <div class="message clearfix">
                        <font>
                            <i>*</i>您的身份：
                        </font>
                        <div class="radioBox f-oh tabBtn">
                            <label class="f-db f-fl f-oh">
                                <input type="radio"  value='0' {% if user.type == 0 %}checked{% endif %} name="member_type" checked>
                                <i>农户</i>
                            </label>
                            <label class="f-db f-fl f-oh">
                                <input type="radio"  value='1' {% if user.type == 1 %}checked{% endif %} name="member_type">
                                <i>农业企业</i>
                            </label>
                            <label class="f-db f-fl f-oh" >
                                <input type="radio"  value='2' {% if user.type == 2 %}checked{% endif %}  name="member_type">
                                <i>家庭农场</i>
                            </label>
                            <label class="f-db f-fl f-oh">
                                <input type="radio"  value='3' {% if user.type == 3 %}checked{% endif %} name="member_type" >
                                <i>农业合作社</i>
                            </label>
                        </div>
                    </div>
                        <div class="tabChange" {% if user.type == 0 %} style="display:block;" {% else %} style="display:none;" {% endif %}  id="member_buyer_0" >
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>姓名：
                                </font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input name="name" class="input1" type="text" value='{% if user and user.name!='' %}{{ user.name }}{% endif %}' >
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>身份证号：
                                </font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input name="credit_no" class="input1" type="text" value='{% if user and user.certificate_no!='' %}{{ user.certificate_no }}{% endif %}' >
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>手机号：
                                </font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input name="mobile" class="input1" type="text" value='{% if user and user.phone!='' %}{{ user.phone }}{% endif %}' >
                                </div>
                            </div>
                            <div class="line"></div>
                            <div class="m-title">认证信息</div>
                           <!--  <div class="message clearfix">
                                <font>
                                    <i>*</i>银行卡开户行：
                                </font>
                                <div class="selectBox selectBox2 f-pr">
                                    <select name="user_bank_name" class="select1">
                                        <option value="">请选择</option>
                                        {% for key , item in  bankList %}
                                        <option value='{{ item['gate_id']}}' {% if bank and bank.bank_name!='' and bank.bank_name ==  item['gate_id'] %} selected {% endif %} >{{ item['bank_name']}}</option>
                                        {% endfor %}
                                    </select>
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>开户行所在地：
                                </font>
                                <div class="selectBox selectBox1 f-pr">
                                    <select class="select1 mb10 class_bank_address_bak" name='bank_province_id' >
                                        <option value="">省</option>
                                    </select>
                                    <select class="select1 mb10 class_bank_address_bak"  name='bank_city_id'>
                                        <option value="">市</option>
                                    </select>
                                    <select class="select1 class_bank_address_bak " name='bank_district_id' >
                                        <option value="">区／县</option>
                                    </select>
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>开户名：
                                </font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input class="input1" type="text" name='bank_account' id='bank_account' value="{% if bank and bank.bank_account!='' %}{{ bank.bank_account }}{% endif %}" >
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>卡号：
                                </font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input class="input1" type="text" name='bank_cardno' value="{% if bank and bank.bank_cardno!='' %}{{ bank.bank_cardno }}{% endif %}" >
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
                                    <a class="link" href="{{ constant('STATIC_URL')}}/mdg/images/lodeImg_img3.jpg">查看样照</a>
                                    <input class="hideTxt" name="photo" id="user_idcard_picture_hide" type="text" {% if bank and bank.idcard_picture %} value="1" {% else %} value="" {% endif %} >
                                    <div class="tips">图片大小不超过2M，支持jpg、png、gif格式</div>
                                </div>
                            </div>
                            <!-- 上传成功后的图片位置 -->
                            <div class="imgBox f-oh" id='user_show_idcard_picture' >
                                {% if bank and bank.idcard_picture %}
                                <div class="imgs f-fl f-pr">
                                    <img src="{{ constant('IMG_URL')}}{{ bank.idcard_picture }}" height="120" width="120" alt="">
                                </div>
                                {% endif %}
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
                                    <a class="link" href="{{ constant('STATIC_URL')}}/mdg/images/lodeImg_img3.jpg">查看样照</a>
                                    <input class="hideTxt" name="back-photo" type="text" {% if bank and bank.idcard_picture_back %} 
                                     value="1" {% else %} value="" {% endif %}  id="user_idcard_picture_back_hide" >
                                    <div class="tips">图片大小不超过2M，支持jpg、png、gif格式</div>
                                </div>
                            </div>
                            <!-- 上传成功后的图片位置 -->
                            <div class="imgBox f-oh" id='user_show_idcard_picture_back' >
                                {% if bank and bank.idcard_picture_back %}
                                <div class="imgs f-fl f-pr">
                                    <img src="{{ constant('IMG_URL')}}{{ bank.idcard_picture_back }}" height="120" width="120" alt="">
                                </div>
                                {% endif %}
                            </div>
                            <div class="line"></div>
                            <div class="m-title">农场信息</div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>农场名：
                                </font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input name='farm_name' id='farm_name' class="input1" value="{% if farm and farm.farm_name!='' %}{{ farm.farm_name }}{% endif %}"  type="text">
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>农场地址：
                                </font>
                                <div class="selectBox selectBox1 f-pr">
                                    <select name='user_province_id' class="select1 mb10 selectAreas_bak">
                                        <option value="">省</option>
                                    </select>
                                    <select name='user_city_id'  class="select1 mb10 selectAreas_bak">
                                        <option value="">市</option>
                                    </select>
                                    <select name='user_district_id' class="select1 mb10 selectAreas_bak">
                                        <option value="">区／县</option>
                                    </select>
                                     <select name='user_town_id' class="select1 mb10 selectAreas_bak">
                                        <option value="">镇／乡</option>
                                    </select>
                                    <select name='user_village_id' data-target="#address-yz2" class="select1 mb10 selectAreas_bak ">
                                        <option value="">街道</option>
                                    </select>
                                    <div class="f-oh">
                                        <input name='user_address' id='user_address' data-target="#address-yz2" class="input1" type="text"
                                        value="{% if farm and farm.address %}{{ farm.address }}{% endif %}" >
                                    </div>
                                    <i id="address-yz2"></i>
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>农场面积：
                                </font>
                                <div class="inputBox inputBox1 f-pr clearfix">
                                    <input  name='farm_areas' class="input1 f-fl" type="text"  value="{% if farm and farm.farm_area!='' %}{{ farm.farm_area }}{% endif %}" >
                                    <i class="dw f-fl">亩</i>
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>种植作物：
                                </font>
                                <div class="select-box lang-select clearfix categrey-option f-pr" style="width:518px;">
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
                                        {% if crops %}
                                        {% for key , item in crops %}
                                        <em data-id="{{ key }}">{{ item }}</em>
                                        {% endfor %}
                                        {% endif %}
                                    </div>
                                    <input id="category_name_text_0"  name='category_name_text_0' class="hideTxt" type="hidden" 
                                    {% if crops %} value="1" {% else %} value="" {% endif %} data-rule="种植作物:required;" />
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>农场照片：
                                </font>
                                <div class="loadBox f-pr">
                                    <div class="loadBtn">
                                        <input class="btn1" type="button" value="+上传图片" >
                                        <input class="btn2" type="file" name='user_picture_path' id='user_picture_path' >
                                    </div>
                                   <!--  <a class="link" href="#">查看样照</a> -->
                                    
                                    <input class="hideTxt" name="farm-photo" id='user_picture_path_hide' type="text" 
                                    {% if pic and pic['farm'] %} value="1" {% else %} value="" {% endif %} >
                                    <div class="tips">图片大小不超过2M，支持jpg、png、gif格式</div>
                                </div>
                            </div>
                            <!-- 上传成功后的图片位置 -->
                            <div class="imgBox f-oh" id='user_show_picture_path' >
                                {% if pic and pic['farm'] %}
                                    {% for key , item in pic['farm']  %}
                                        <div class='imgs f-fl f-pr' id='dl_user_picture_path_{{ key }}' >
                                        <a class='close-btn' href='javascript:;' 
                                        onclick="closeImgs(this,{{ key }},'user_picture_path');"></a>
                                        <img src='{{ constant('IMG_URL')}}{{ item }}' height='145' width='120'>
                                        </div>
                                    {% endfor %}
                                {% endif %}
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
                                    <input class="hideTxt" name="gdht" type="text" {% if pic and pic['contract'] %} value="1" {% else %} value="" {% endif %} id='user_picture_path_contact_hide' >
                                    <div class="tips">图片大小不超过2M，支持jpg、png、gif格式</div>
                                </div>
                            </div>
                            <!-- 上传成功后的图片位置 -->
                            <div class="imgBox f-oh" id='user_show_picture_path_contact' >
                                {% if pic and pic['contract']  %}
                                    {% for key , item in pic['contract']  %}
                                        <div class='imgs f-fl f-pr' id='dl_user_picture_path_contact_{{ key }}' >
                                        <a class='close-btn' href='javascript:;' 
                                        onclick="closeImgs(this,{{ key }},'user_picture_path_contact');"></a>
                                        <img src='{{ constant('IMG_URL')}}{{ item }}' height='145' width='120'>
                                        </div>
                                    {% endfor %}
                                {% endif %}
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>土地来源：
                                </font>
                                <div class="radioBox f-oh">
                                    <label class="f-db f-fl f-oh">
                                        <input type="radio" name='source' value='0' {% if farm and farm.source == 0 %}checked {% endif %} checked>
                                        <i>自有</i>
                                    </label>
                                    <label class="f-db f-fl f-oh">
                                        <input type="radio" name='source' value='1' {% if farm and farm.source == 1 %}checked {% endif %} >
                                        <i>流转</i>
                                    </label>
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>土地使用年限：
                                </font>
                                <div class="inputBox inputBox2 f-pr clearfix">
                                    <input class="input2 f-fl Wdate "  name="user_stime" data-target="#time-yz" type="text" id="d4331" onfocus="WdatePicker({dateFmt: 'yyyy-MM',maxDate:'#F{$dp.$D(\'d4332\',{M:0,d:0})}'})" readonly="readonly"  value="{% if farm and farm.start_year!='' and farm.start_month!='' %}{{ farm.start_year }}-{{ farm.start_month }}{% endif %}" >
                                    <i class="heng f-fl">-</i>
                                    <input class="input2 f-fl Wdate "  name="user_etime" data-target="#time-yz" type="text" id="d4332" onfocus="WdatePicker({dateFmt: 'yyyy-MM',minDate:'#F{$dp.$D(\'d4331\',{M:0,d:1});}',maxDate:'2020-4-3'})" readonly="readonly" value="{% if farm and farm.year!='' and farm.month!='' %}{{ farm.year }}-{{ farm.month }}{% endif %}" >
                                    <i id="time-yz"></i>
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>农场简介：</font>
                                <div class="areaBox f-pr">
                                    <textarea name='user_describe' >{% if farm and farm.describe!='' %}{{ farm.describe }}{% endif %}</textarea>
                                </div>
                            </div>
                            <!-- <div class="message clearfix">
                                <font>推荐人：</font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input class="input1" type="text"  name='seusername' value="{% if user and user.se_mobile!='' %}{{ user.se_mobile }}{% endif %}" >
                                    <div class="tips mt10">（可以是县域服务中心或责任服务工程师）</div>
                                </div>
                            </div> -->
                            <div class="message clearfix">
                                <font>&nbsp;</font>
                                <div class="checkBox clearfix">
                                    <label class="clearfix f-fl checked">
                                        

                                        <i>
                                            <input type="checkbox" checked="true" onclick='checkSubmit(this)'  />我已阅读并同意<a href="/member/userfarm/doc">《云农场可信农场协议》</a>
                                        </i>
                                    </label>
                                </div>
                            </div>
                            <input class="apply-btn" type="submit" value="提交申请">
                        </div>
                        <!-- 可信农场 农业企业 -->
                        <div class="tabChange" {% if user.type != 0 %} style="display:block;" {% else %} style="display:none;"  {% endif %} id='member_buyer_1' >
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>公司名称：
                                </font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input  name='ent_company_name' class="input1" type="text" value="{% if user and user.company_name!='' %}{{ user.company_name }}{% endif %}">
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>注册登记证号：
                                </font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input  name='ent_register_no' value="{% if user and user.register_no!='' %}{{ user.register_no }}{% endif %}"   class="input1" type="text">
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
                                    <select name='town_id'  data-target="#address-yz1" class="select1 mb10 ent_selectAreas ">
                                        <option value="">街道</option>
                                    </select>
                                    <div class="f-oh">
                                        <input name='address' value="{{ user.address }}"  data-target="#address-yz" class="input1" type="text">
                                    </div>
                                    <i id="address-yz1"></i>
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>企业法人：
                                </font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input name='ent_erprise_legal_person' class="input1" type="text" value="{% if user and user.enterprise_legal_person!='' %}{{ user.enterprise_legal_person }}{% endif %}" >
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>身份证号：
                                </font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input name='ent_certificate_no' class="input1" type="text" value="{% if user and user.certificate_no!='' %}{{ user.certificate_no }}{% endif %}" >
                                </div>
                            </div>
                            <div class="line"></div>
                            <div class="m-title">联系人信息</div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>姓名：
                                </font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input  name='ent_contact_name'  class="input1" type="text" value="{% if cont and cont.name!='' %}{{ cont.name }}{% endif %}" >
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>手机号：
                                </font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input name='ent_contact_phone' class="input1" type="text" value="{% if cont and cont.phone!='' %}{{ cont.phone }}{% endif %}" >
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>传真：</font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input class="input1" type="text"  name='ent_contact_fax' value="{% if cont and cont.fax %}{{ cont.fax }}{% endif %}"  >
                                </div>
                            </div>
                            <div class="line"></div>
                            <div class="m-title">认证信息</div>
                            <!-- <div class="message clearfix">
                                <font>
                                    <i>*</i>银行卡开户行：
                                </font>
                                <div class="selectBox selectBox2 f-pr">
                                    <select  name='ent_bank_name' class="select1">
                                            <option value=''>请选择</option>
                                            {% for key , item in  bankList %}
                                            <option value='{{ item['gate_id']}}' {% if bank and bank.bank_name!='' and bank.bank_name ==  item['gate_id'] %} selected {% endif %} >{{ item['bank_name']}}</option>
                                            {% endfor %}
                                    </select>
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>开户行所在地：
                                </font>
                                <div class="selectBox selectBox1 f-pr">
                                    <select name='ent_bank_province_id' class="select1 mb10 ent_class_bank_address_bak ">
                                        <option value="">省</option>
                                    </select>
                                    <select name='ent_bank_city_id' class="select1 mb10 ent_class_bank_address_bak ">
                                        <option value="">市</option>
                                    </select>
                                    <select name='ent_bank_district_id' class="select1 ent_class_bank_address_bak ">
                                        <option value="">区／县</option>
                                    </select>
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>开户公司名称：
                                </font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input name='ent_bank_account'  class="input1" type="text" value="{% if bank and bank.bank_account!='' %}{{ bank.bank_account }}{% endif  %}" >
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>卡号：
                                </font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input  name='ent_bank_cardno' class="input1" type="text" value="{% if bank and bank.bank_cardno!='' %}{{ bank.bank_cardno }}{% endif %}" >
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
                                    <input class="hideTxt" name="gs-yezz" type="text" id='ent_identity_picture_lic_hide' {% if bank and bank.identity_picture_lic %} value="1" {% else %} value="" {% endif %} >
                                    <div class="tips">图片大小不超过2M，支持jpg、png、gif格式</div>
                                </div>
                            </div>
                            <!-- 上传成功后的图片位置 -->
                            <div class="imgBox f-oh" id='ent_show_identity_picture_lic' >
                                {% if bank and bank.identity_picture_lic %}
                                <div class="imgs f-fl f-pr">
                                    <img src="{{ constant('IMG_URL')}}{{ bank.identity_picture_lic }}" height="120" width="120" alt="">
                                </div>
                                {% endif %}
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
                                    <input class="hideTxt" name="gs-photo" id='ent_idcard_picture_hide' type="text" 
                                    {% if bank and bank.idcard_picture %} value="1" {% else %} value="" {% endif %} >
                                    <div class="tips">图片大小不超过2M，支持jpg、png、gif格式</div>
                                </div>
                            </div>
                            <!-- 上传成功后的图片位置 -->
                            <div class="imgBox f-oh"  id='ent_show_idcard_picture' >
                                 {% if bank and bank.idcard_picture %}
                                <div class="imgs f-fl f-pr">
                                    <img src="{{ constant('IMG_URL')}}{{ bank.idcard_picture }}" height="120" width="120" alt="">
                                </div>
                                {% endif %}
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
                                    <input class="hideTxt" name="gs-back-photo" type="text" {% if bank and bank.idcard_picture_back %} value="1" {% else %} value="" {% endif %} id='ent_idcard_picture_back_hide' >
                                    <div class="tips">图片大小不超过2M，支持jpg、png、gif格式</div>
                                </div>
                            </div>
                            <!-- 上传成功后的图片位置 -->
                            <div class="imgBox f-oh" id='ent_show_idcard_picture_back' >
                                {% if bank and bank.idcard_picture_back %}
                                <div class="imgs f-fl f-pr">
                                    <img src="{{ constant('IMG_URL')}}{{ bank.idcard_picture_back }}" height="120" width="120" alt="">
                                </div>
                                {% endif %}
                            </div>
                            <div class="line"></div>
                            <div class="m-title">农场信息</div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>农场名：
                                </font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input  name='ent_farm_name' class="input1" type="text" value="{% if farm and farm.farm_name!='' %}{{ farm.farm_name }}{% endif %}" >
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>农场地址：
                                </font>
                                <div class="selectBox selectBox1 f-pr">
                                    <select name='ent_province_id' class="select1 mb10  ent_farm_selectAreas_bak ">
                                        <option value="">省</option>
                                    </select>
                                    <select name='ent_city_id' class="select1 mb10  ent_farm_selectAreas_bak ">
                                        <option value="">市</option>
                                    </select>
                                    <select name='ent_district_id' class="select1 mb10  ent_farm_selectAreas_bak ">
                                        <option value="">区／县</option>
                                    </select>
                                    <select name='ent_town_id' class="select1 mb10  ent_farm_selectAreas_bak ">
                                        <option value="">镇／乡</option>
                                    </select>
                                    <select name='ent_village_id' name="gs-farm-address1" data-target="#address-yz" class="select1 mb10  ent_farm_selectAreas_bak ">
                                        <option value="">街道</option>
                                    </select>
                                    <div class="f-oh">
                                        <input name="ent_address" data-target="#address-yz" class="input1" type="text" value="{% if farm and farm.address %}{{ farm.address }}{% endif %}" >
                                    </div>
                                    <i id="address-yz"></i>
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>农场面积：
                                </font>
                                <div class="inputBox inputBox1 f-pr clearfix">
                                    <input  name='ent_farm_area' class="input1 f-fl" type="text" value="{% if farm and farm.farm_area!='' %}{{ farm.farm_area }}{% endif %}" >
                                    <i class="dw f-fl">亩</i>
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>种植作物：
                                </font>
                                <div class="select-box lang-select clearfix categrey-option f-pr" style="width:518px;">
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
                                         {% if crops %}
                                            {% for key , item in crops %}
                                                <em data-id="{{ key }}">{{ item }}</em>
                                            {% endfor %}
                                        {% endif %}
                                    </div>
                                    <input id="category_name_text_1" name='category_name_text_1' style="width:1px;opacity:0;filter:alpha(opacity:0);" type="hidden" {% if crops %} value="1" {% else %} value="" {% endif %}   data-rule="种植作物:required;" />
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
                            
                                    <input class="hideTxt" name="gs-farm-photo" type="text" {% if pic and pic['farm'] %} value="1" {% else %} value="" {% endif %}  id="ent_picture_path_hide" >
                                    <div class="tips">图片大小不超过2M，支持jpg、png、gif格式</div>
                                </div>
                            </div>
                            <!-- 上传成功后的图片位置 -->
                            <div class="imgBox f-oh"  id='ent_show_picture_path' >
                                     {% if pic and pic['farm'] %}
                                        {% for key , item in pic['farm'] %}
                                            <div class='imgs f-fl f-pr' id='dl_ent_picture_path_{{ key }}' >
                                            <a class='close-btn' href='javascript:;' 
                                            onclick="closeImgs(this,{{ key }},'ent_picture_path');"></a>
                                            <img src='{{ constant('IMG_URL')}}{{ item }}' height='145' width='120'>
                                            </div>
                                        {% endfor %}
                                    {% endif %}
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
                                    <input class="hideTxt" name="gs-gdht" type="text" {% if pic and pic['contract'] %} value="1" {% else %} value="" {% endif %} id='ent_picture_path_contact_hide' >
                                    <div class="tips">图片大小不超过2M，支持jpg、png、gif格式</div>
                                </div>
                            </div>
                            <!-- 上传成功后的图片位置 -->
                            <div class="imgBox f-oh" id='ent_show_picture_path_contact' >
                                {% if pic and pic['contract'] %}
                                    {% for key , item in pic['contract'] %}
                                        <div class='imgs f-fl f-pr' id='dl_ent_picture_path_contact_{{ key }}' >
                                        <a class='close-btn' href='javascript:;' 
                                        onclick="closeImgs(this,{{ key }},'ent_picture_path_contact');"></a>
                                        <img src='{{ constant('IMG_URL')}}{{ item }}' height='145' width='120'>
                                        </div>
                                    {% endfor %}
                                {% endif %}
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>土地来源：
                                </font>
                                <div class="radioBox f-oh">
                                    <label class="f-db f-fl f-oh">
                                        <input type="radio" name='ent_source'  value='0' {% if farm and  farm.source == 0 %} checked {% endif %} >
                                        <i>自有</i>
                                    </label>
                                    <label class="f-db f-fl f-oh">
                                        <input type="radio" name='ent_source' value='1' {% if farm and farm.source == 1 %} checked {% endif %}  >
                                        <i>流转</i>
                                    </label>
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>土地使用年限：
                                </font>
                                <div class="inputBox inputBox2 f-pr clearfix">
                                    <input readonly="readonly" class="input2 f-fl Wdate " name="ent_stime" id="d43313" onfocus="WdatePicker({dateFmt: 'yyyy-MM',maxDate:'#F{$dp.$D(\'d4334\',{M:0,d:0})}'})" data-target="#time-yz" type="text"
                                    value="{% if farm and farm.start_year!='' and farm.start_month!='' %}{{ farm.start_year }}-{{ farm.start_month }}{% endif %}" >
                                    <i class="heng f-fl">-</i>
                                    <input readonly="readonly" class="input2 f-fl Wdate " name="ent_etime"  id="d4334"  onfocus="WdatePicker({dateFmt: 'yyyy-MM',minDate:'#F{$dp.$D(\'d43313\',{M:0,d:1});}',maxDate:'2020-4-3'})" data-target="#time-yz" type="text" value="{% if farm and farm.year!='' and farm.month!='' %}{{ farm.year }}-{{ farm.month }}{% endif %}" >
                                    <i id="time-yz"></i>
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>农场简介：</font>
                                <div class="areaBox f-pr">
                                    <textarea name='ent_describe' >{% if farm and farm.describe!='' %}{{ farm.describe }}{% endif %}</textarea>
                                </div>
                            </div>
<!--                             <div class="message clearfix">
                                <font>推荐人：</font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input class="input1" type="text" name='ent_seusername' data-rule="mobile" 
                                    {% if user and user.se_mobile!='' %}{{ user.se_mobile }}{% endif %} >
                                    <div class="tips mt10">（可以是县域服务中心或责任服务工程师）</div>
                                </div>
                            </div> -->
                            <div class="message clearfix">
                                <font>&nbsp;</font>
                                <div class="checkBox clearfix">
                                    <label class="clearfix f-fl checked">
                                        <i>
                                            <input type="checkbox" checked="true" onclick='checkSubmit(this)'  />我已阅读并同意<a href="/member/userfarm/doc">《云农场可信农场协议》</a>
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
                    wxxx:[/^[0-9a-zA-Z]*$/, '只能输入字母数字'],
                    nimei  : [/^([0-9])+(\.([0-8])+)?$/, '请输入数字'],
                },
                //timely: 2,
                ignore: ':hidden',
                fields: {
                 'name': '姓名:required;fuck;length[2~10]',
                    'credit_no': '身份证号:required;ID_card',
                    'mobile': '手机号:required;mobile',
                    'lwtt_phone':'盟商推荐人手机号:required;mobile;remote[/member/userfarm/checklwtt]',
                    'user_village_id': '农场地址:required;',
                    'user_address': '农场地址:required;length_name;length[5~50];',
                    'user_bank_name': '银行卡开户行:required;',
                    'bank_district_id': '开户行所在地:required;',
                    'bank_account': '开户名:required;chinese',
                    'bank_cardno': '卡号:required;mark',
                    'photo': '身份证照:required;',
                    'back-photo': '身份证背面照:required;',
                    'farm_name': '农场名:required;length_name;length[2~30];',
                    'farm_areas': '农场面积:required;digits;length[~8]',
                    'farm-photo': '农场照片:required;',
                    'gdht': '耕地合同:required;',
                    'user_stime': '土地使用年限:required;',
                    'user_etime': '土地使用年限:required;',
                    //'seusername':  '推荐人:mobile;remote[/member/userfarm/checkEngineer]',
                    //'ent_seusername':  '推荐人:mobile;remote[/member/userfarm/checkEngineer]',
                    'ent_company_name': '公司名称:required;length_name;length[2~30];',
                    'ent_register_no': '注册登记证号:required;wxxx',
                    'town_id': '公司地址:required;',
                    'address': '公司地址:required;length_name;length[5~50];',
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
                    'ent_address': '农场地址:required;length_name;length[5~50]',
                    'ent_farm_area': '农场面积:required;digits',
                    'gs-farm-photo': '农场照片:required;',
                    'gs-gdht': '耕地合同:required;',
                    'ent_stime': '土地使用年限:required;',
                    'ent_etime': '土地使用年限:required;',
                    'user_describe':'农场简介:length_name;length[0~500]',
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
    <script >
var sid = '';
var upload_total = {};
{% if farm_pic %}
upload_total['user_picture_path'] = {{farm_pic}};
upload_total['ent_picture_path'] = {{farm_pic}};
{% endif %}
{% if contract_pic  %}
upload_total['user_picture_path_contact'] = {{contract_pic}};
upload_total['ent_picture_path_contact'] = {{contract_pic}};
{% endif %}
$(function() {
    /* 可信农场 */
    /*  银行卡 */
    bankImg($('#user_bankcard_picture'), 29, $('#user_show_bankcard_picture'), 'html', $('#user_bankcard_picture_hide'));
    /* 用户手持身份证 */
    bankImg($('#user_person_picture'), 30, $('#user_show_person_picture'), 'html', $('#user_person_picture_hide'));
    /* 用户身份证 */
    bankImg($('#user_idcard_picture'), 31, $('#user_show_idcard_picture'), 'html', $('#user_idcard_picture_hide'));
    /*  用户农场信息 */
    bankImg($('#user_picture_path'), 32, $('#user_show_picture_path'), 'append', $('#user_picture_path_hide'));
    /*  用户农场信息 */
    bankImg($('#user_picture_path_contact'), 42, $('#user_show_picture_path_contact'), 'append', $('#user_picture_path_contact_hide'));
    /* 身份证背面照 */
    bankImg($('#user_idcard_picture_back'), 34, $('#user_show_idcard_picture_back'), 'html', $('#user_idcard_picture_back_hide'));

    /* 农产信息 */
    /* 银行卡信息 */
    bankImg($('#ent_bankcard_picture'), 29, $('#ent_show_bankcard_picture'), 'html', $('#ent_bankcard_picture_hide'));
    /* 农产 个人营业 ent_identity_picture_lic */
    bankImg($('#ent_identity_picture_lic'), 33, $('#ent_show_identity_picture_lic'), 'html', $('#ent_identity_picture_lic_hide'));
    /* 农产 个人身份证 */
    bankImg($('#ent_idcard_picture'), 31, $('#ent_show_idcard_picture'), 'html', $('#ent_idcard_picture_hide'));
    /* 农产农场信息  */
    bankImg($('#ent_picture_path'), 32, $('#ent_show_picture_path'), 'append', $('#ent_picture_path_hide'));
    /* 用户农场合同  */
    bankImg($('#ent_picture_path_contact'), 42, $('#ent_show_picture_path_contact'), 'append', $('#ent_picture_path_contact_hide'));
    /*  身份证背面  */
    bankImg($('#ent_idcard_picture_back'), 34, $('#ent_show_idcard_picture_back'), 'html', $('#ent_idcard_picture_back_hide'));

    /*  组织机构代码证*/
    bankImg($('#ent_identity_picture_org'), 11, $('#ent_show_identity_picture_org'), 'html', $('#ent_identity_picture_org_hide'));
    /* 税务登记证  */
    bankImg($('#ent_identity_picture_tax'), 10, $('#ent_show_identity_picture_tax'), 'html', $('#ent_identity_picture_tax_hide'));


    /*  组织机构代码 */
    bankImg($('#ent_organization_code'), 11, $('#ent_show_organization_code'), 'html', $('#ent_organization_code_hide'));
    /*  税务登记证（选填） */
    bankImg($('#ent_tax_registration'), 10, $('#ent_show_tax_registration'), 'html', $('#ent_tax_registration_hide'));


    /* 可信农场 end*/
    selectBycate(1, 0);


})



/*  获取所有选中分类结果 */
function getAllCateResult(type) {
    /* 获取result */
    var txt = '';
    $('#result-box_' + type).find('em').each(function() {
        txt += $(this).attr('data-id') + ',';
    });

    $('#category_name_text_' + type).val(txt);
    if (txt) {
        $('#category_name_text_' + type).next('i').html('<span class="msg-wrap n-ok" role="alert"><span class="n-icon"></span><span class="n-msg"></span></span>');
    } else {
        $('#category_name_text_' + type).next('i').html('<span class="msg-wrap n-error" role="alert"><span class="n-icon"></span><span class="n-msg">不能为空</span></span>');
    }


}

/* 上传图片实例化 */
function bankImg(id, type, show_img, limit, tip_id) {
    key = id.attr('id');
    if (typeof upload_total[key] == 'undefined') upload_total[key] = 0;
    // count=0;
    // if(type==32&&{{farm_pic}}){
    //    count=upload_total[key];
    //    if(count<5){
    //     count=0;
    //    }
    // }
    // if(type==42&&{{contract_pic}}){
    //    count=upload_total[key];
    //    if(count<5){
    //     count=0;
    //    }
    // }
    //银行正面照
    id.uploadify({
        'width': '89',
        'height': '25',
        'swf': '/uploadify/uploadify.swf',
        'uploader': '/upload/tmpfile',
        'fileSizeLimit': '2MB',
        'fileTypeExts': '*.jpg;*.png;*.jpeg;*.bmp;*.png',
        'progressData': 'percentage',
        'formData': {
            'sid': '{{sid}}',
            'type': type,
            'member': 1,
            'count':upload_total[key]+1,
            'key' : key
        },
        'buttonClass': 'upload_btn',
        'buttonText': '上传图片',
        'multi': false,
        onDialogOpen: function() {
            $('.gy_step').eq(1).addClass("active").siblings().removeClass("active");
        },
        onUploadSuccess: function(file, data, response) {
            
            data = $.parseJSON(data);
            alert(data.msg);
            if (data.status) {
                key = id.attr('id');
                if(limit=='html'){
                    upload_total[key]=1;
                }else{
                     upload_total[key]++;
                }
                if (limit == 'append') {
                    show_img.append(data.html);
                } else if (limit == 'html') {
                    show_img.html(data.html);
                }     
                tip_id.val(1);
                if (type == 32 || type == 42) {
                 tip_id.val(data.total);
                }
                tip_id.next('i').html('<span class="msg-wrap n-ok" role="alert"><span class="n-icon"></span><span class="n-msg"></span></span>');
                if ( (upload_total[key] >= 5 && key == 'user_picture_path_contact') || (upload_total[key] >= 5 && key == 'ent_picture_path_contact')  
                    || (upload_total[key] >= 5 && key == 'user_picture_path') ||  (upload_total[key] >= 5 && key == 'ent_picture_path') 
                    ) {
                     id.uploadify('disable', true); 
                }

            }
        }
    });
}



// 删除图片
function closeImg(obj, id, mkey) {
    

    $.getJSON('/upload/deltmpfile', {
        id: id
    }, function(data) {
        alert(data.msg);
        if (data.state) {
            $('#dl_' + id).slideUp();
            upload_total[''+mkey+'']--;
            $('#'+mkey).uploadify('disable', false);
           
            if(upload_total[''+mkey+''] == 0){
                $('#'+mkey+'_hide').val('');
            }
            // if (    (upload_total[''+mkey+''] == 0 && mkey == 'user_picture_path_contact') || 
            //         (upload_total[''+mkey+''] == 0 && mkey == 'ent_picture_path_contact') ||
            //         (upload_total[''+mkey+''] == 0 && mkey == 'user_picture_path') ||
            //         (upload_total[''+mkey+''] == 0 && mkey == 'ent_picture_path') 
            //     ) {
            //                         // alert(mkey + " 00000") ; 
            //         $('#'+mkey+'_hide').val('');
            //     // $('#'+mkey+'_hide_tip').html('<span class="msg-wrap n-error" role="alert"><span class="n-icon"></span><span class="n-msg">图片不能为空</span></span>');
            // }
        

            
        }
    });
}

//添加
var add_arr = [];
var add_cid = [];

function selectBycate(cid, type) {

    $.get('/ajax/getcate', {
        'parent_id': cid
    }, function(json) {
        var str = '';
        data = eval(json);
        for (var o in data) {
            str += '<a href="javascript:;" data-id="' + data[o].region_id + '">' + data[o].region_name + '</a>';
        }

        $(".categrey-option .erji-box a").unbind("click");
        $(".categrey-option .btn-box .btn1").unbind("click");
        $(".categrey-option .btn-box .btn2").unbind("click");
        $(".categrey-option .result-box em").unbind("click");
        $('#show_cate_chid_' + type).html(str);

        $('.categrey-option .erji-box a').click(function() {
            $(this).parents('.categrey-option').find('.result-box em').removeClass('active');
            $(this).parent().find('a').removeClass('active');
            $(this).addClass('active');
        });

        $('.categrey-option .result-box em').click(function() {
            $(this).parents('.categrey-option').find('.erji-box a').removeClass('active');
            $(this).parent().find('em').removeClass('active');
            $(this).addClass('active');
        });

        //添加
        var add_arr = [];
        var add_cid = [];
        var flag = 0;

        $('.categrey-option .btn-box .btn1').click(function() {
            $(this).parents('.categrey-option').find('.erji-box a').each(function() {
                if ($(this).hasClass('active')) {
                    var txt = $(this).text();
                    var cid = $(this).attr('data-id');
                    add_arr.push(txt);
                    add_cid.push(cid);

                } else {
                    return;
                };
            });


            if (add_arr.length > 0) {
                /* 检测是否有重复数据 */
                flag = 0;
                $('#result-box_' + type).find('em').each(function(index, el) {
                    if ($.trim($(el).text()) == $.trim(add_arr[0])) {
                        flag = 1;
                    }
                });

                if (flag) {
                    alert('分类重复');
                    add_arr = [];
                    add_cid = [];
                    return false;
                }

                var str = "<em data-id='" + add_cid[0] + "'>" + add_arr[0] + "</em>";
                $(str).appendTo('#result-box_' + type);
                getAllCateResult(type);
                $(this).parents('.categrey-option').find('.result-box em').click(function() {
                    $(this).parents('.categrey-option').find('.erji-box a').removeClass('active');

                    $(this).parent().find('em').removeClass('active');
                    $(this).addClass('active');
                });

                add_arr = [];
                add_cid = [];
                flag = 0;

            } else {
                alert("请选择分类！");
            }
        });


        //删除
        var delete_arr = [];
        $('.categrey-option .btn-box .btn2').click(function() {
            $(this).parents('.categrey-option').find('.result-box em').each(function() {
                if ($(this).hasClass('active')) {
                    var txt = $(this).text();
                    delete_arr.push(txt);
                    $(this).remove();
                } else {
                    return;
                };
            });

            if (delete_arr.length > 0) {
                getAllCateResult(type);
                return;
            } else {
                alert("请选择分类！");
            };
        });

    });
}



// 地区联动
$(".selectAreas").ld({
    ajaxOptions: {
        "url": "/ajax/getareasfull"
    },
    defaultParentId: 0,

    style: {
        "width": 250
    }
});
// 地区联动
$(".class_bank_address").ld({
    ajaxOptions: {
        "url": "/ajax/getareasfull"
    },
    defaultParentId: 0,
    style: {
        "width": 250
    }
});
/* 公司地址 */
$(".ent_selectAreas").ld({
    ajaxOptions: {
        "url": "/ajax/getareasfull"
    },
    defaultParentId: 0,

    style: {
        "width": 250
    }
});
/* 申请负责区域 */
$(".user_area_selectAreas").ld({
    ajaxOptions: {
        "url": "/ajax/getareasfull"
    },
    defaultParentId: 0,

    style: {
        "width": 250
    }
});
/* 企业负责区域 */
$(".ent_area_selectAreas").ld({
    ajaxOptions: {
        "url": "/ajax/getareasfull"
    },
    defaultParentId: 0,

    style: {
        "width": 250
    }
});


/**/
$(".ent_class_bank_address").ld({
    ajaxOptions: {
        "url": "/ajax/getareasfull"
    },
    defaultParentId: 0,
    style: {
        "width": 250
    }
});
/**/
$(".ent_farm_selectAreas").ld({
    ajaxOptions: {
        "url": "/ajax/getareasfull"
    },
    defaultParentId: 0,
    style: {
        "width": 250
    }
});

/**/
$(".area_select").ld({
    ajaxOptions: {
        "url": "/ajax/getareasfull"
    },
    defaultParentId: 0,
    style: {
        "width": 250
    }
});



var jsFileName = "user_farm.js";
var rName = new RegExp(jsFileName + "(\\?(.*))?$")
var jss = document.getElementsByTagName('script');
for (var i = 0; i < jss.length; i++) {
    var j = jss[i];
    if (j.src && j.src.match(rName)) {
        var oo = j.src.match(rName)[2];
        if (oo && (t = oo.match(/([^&=]+)=([^=&]+)/g))) {
            for (var l = 0; l < t.length; l++) {
                r = t[l];
                var tt = r.match(/([^&=]+)=([^=&]+)/);
                if (tt && tt[1] == 'sid') {
                    sid = tt[2];
                }
            }
        }
    }
}
    </script>
    <script type="text/javascript">
    function checkSubmit(obj) {
        if(!$(obj).prop('checked')) {
            $('.apply-btn').attr('disabled', true);
        }else{
            $('.apply-btn').removeAttr('disabled');
        }
    }
                
    /*$(function () {
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
    })*/
    // 删除图片
    function closeImgs(obj, id, mkey ) {
        $.getJSON('/member/userfarm/deltmpfile', {id : id}, function(data) {
            alert(data.msg);
            if(data.state) {
                 //$(obj).parents('dl').slideUp();
                 
                  $('#dl_'+mkey+'_'+id).slideUp();
                    upload_total[''+mkey+'']--;
                    $('#'+mkey).uploadify('disable', false);
                    

                    if (    (upload_total[''+mkey+''] == 0 && mkey == 'user_picture_path_contact') || 
                            (upload_total[''+mkey+''] == 0 && mkey == 'ent_picture_path_contact') ||
                            (upload_total[''+mkey+''] == 0 && mkey == 'user_picture_path') ||
                            (upload_total[''+mkey+''] == 0 && mkey == 'ent_picture_path') 
                        ) {
                                            // alert(mkey + " 00000") ; 
                            $('#'+mkey+'_hide').val('');
                        // $('#'+mkey+'_hide_tip').html('<span class="msg-wrap n-error" role="alert"><span class="n-icon"></span><span class="n-msg">图片不能为空</span></span>');
                    }
            }
        });
    }
    function checkSubmit(obj) {
        if(!$(obj).prop('checked')) {
            $('.classSubmit').attr('disabled', true);
        }else{
            $('.classSubmit').removeAttr('disabled');
        }
    }
            
    $(function () {
        //农作物显示下拉
        if($("#tp") == 0){
            selectBycate(1, 0);
        } else {
            selectBycate(1, 1);
        }

        $('input[name="member_type"]').click(function () {
            var val = $(this).val();

            if(val == 0 ) {
                $('#member_buyer_0').show();
                $('#member_buyer_1').hide();
                if($('#category_name_text_0').val() != 1) {
                    $('#category_name_text_0').val('');
                }
                selectBycate(1, 0);
                $('#userForm').validator('cleanUp');
                

            }else{
                $('#member_buyer_1').show();
                $('#member_buyer_0').hide();
                selectBycate(1, 1);
                if($('#category_name_text_1').val() != 1) {
                    $('#category_name_text_1').val('');
                }
                $('#userForm').validator('cleanUp');

            }
        });
    });
    // 农场地址
    $(".selectAreas_bak").ld({ajaxOptions : {"url" : "/ajax/getareasfull"},
        defaultParentId : 0,
        {% if ( curAreas ) %}
        texts : [{{curAreas}}],
        {% endif %}
        style : {"width" : 250}
       
    });
    $(".ent_farm_selectAreas_bak").ld({ajaxOptions : {"url" : "/ajax/getareasfull"},
        defaultParentId : 0,
        {% if ( curAreas ) %}
        texts : [{{curAreas}}],
        {% endif %}
        style : {"width" : 250}
       
    });
    //公司地址
    $(".ent_selectAreas").ld({ajaxOptions : {"url" : "/ajax/getareasfull"},
        defaultParentId : 0,
        {% if ( company_addr ) %}
        texts : ["{{company_addr}}"],
        {% endif %}
        style : {"width" : 250}
       
    });
    //开户行所在地
    $(".ent_class_bank_address_bak").ld({ajaxOptions : {"url" : "/ajax/getareasfull"},
        defaultParentId : 0,
        {% if ( bankAreas ) %}
        texts : [{{bankAreas}}],
        {% endif %}
        style : {"width" : 250}
       
    });
    //开户行所在地-农户
    $(".class_bank_address_bak").ld({ajaxOptions : {"url" : "/ajax/getareasfull"},
        defaultParentId : 0,
        {% if ( bankAreas ) %}
        texts : [{{bankAreas}}],
        {% endif %}
        style : {"width" : 250}
       
    });
    $('.south-west-alt').powerTip({
                placement: 's',
                smartPlacement: true
    });
    </script>
    
</body>
</html>
