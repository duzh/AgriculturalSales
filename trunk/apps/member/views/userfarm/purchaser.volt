<!--头部 start-->
{{ partial('layouts/member_header') }}
<!--头部 end-->
<link rel="stylesheet" href="{{ constant('STATIC_URL') }}mdg/version2.5/css/verfiy.css">
<!--主体 start-->
<div class="wrapper">
    <div class="w1190 mtauto f-oh">
        <div class="bread-crumbs w1185 mtauto">
            <span>{{ partial('layouts/ur_here') }}{% if ptype == 1%}采购经纪人认证申请{% else %}采购企业认证申请{% endif %}</span>
        </div>
        <!-- 左侧 start-->
        {{ partial('layouts/navs_left') }}
        <!-- 左侧 end-->
        <!-- 右侧 start -->
        <div class="center-right f-fr">
            <div class="roles-apply f-oh">
                <div class="title f-oh">
                    <span>{% if ptype == 1%}采购经纪人认证申请{% else %}采购企业认证申请{% endif %}</span>
                </div>
                <form action="/member/userfarm/purchasersave" method="post" id='userForm'>
                    <div class="m-title">基本信息</div>
                    <!--校验是否是增加和修改-->
                    <input type="hidden" name='flag'  value='{% if credit_id %}1{% else %}0{% endif %}'>
                    <input type="hidden" name='credit_id'  value='{% if credit_id %}{{ credit_id }}{% else %}0{% endif %}'>
                    <input type="hidden" name='member_type'  value='{% if ptype == 1%}0{% else %}1{% endif %}'>
                    <!-- 采购商 个人start -->
                    <div id="buyer_comm" style="display:block;">
                        <div class="message clearfix">
                            <font>
                                <i>*</i>姓名：
                            </font>
                            <div class="inputBox inputBox1 f-pr">
                                <input type="text" class="input1" name='user_name' value='{{ userinfo['name'] }}' data-rule="姓名:required;chinese;" />
                            </div>
                        </div>
                        <div class="message clearfix">
                            <font><i>*</i>身份证号：</font>
                            <div class="inputBox inputBox1 f-pr">
                                <input type="text" class="input1" name='user_certificate_no' value="{{ userinfo['certificate_no'] }}"  data-rule="身份证号:required;ID_card;" />

                            </div>
                        </div>
                        <div class="message clearfix">
                            <font><i>*</i>手机号：</font>
                            <div class="inputBox inputBox1 f-pr">
                                <input type="text" class="input1" name='user_mobile' value='{{ userinfo['phone'] }}' data-rule="手机号:required;mobile;" />
                            </div>
                        </div>
                        <div class="message clearfix">
                            <font><i>*</i>所在区域：</font>
                            <div class="selectBox selectBox1 f-pr">
                                <select name='user_province_id' class='selectAreas select1 mb10'>
                                    <option value=''>请选择</option>
                                </select>
                                <select name='user_city_id' class='selectAreas select1 mb10'>
                                    <option value=''>请选择</option>
                                </select>
                                <select name='user_district_id'  class='selectAreas select1 mb10'>
                                    <option value=''>请选择</option>
                                </select>
                                <select  name='user_town_id' class='selectAreas select1 mb10' data-target="#address-yz-1" data-rule="所在区域:required;">
                                    <option value=''>请选择</option>
                                </select>
                                <div class="f-oh">
                                    <input data-target="#address-yz-2" class="input1" type="text" name='user_address' data-rule="所在区域详细地址:required;" value="{{ userinfo['address'] }}" />
                                </div>
                                <i class="dz-yz1" id="address-yz-1"></i>
                                <i class="dz-yz2" id="address-yz-2"></i>
                            </div>
                        </div>
                        <div class="message clearfix">
                            <font><i>*</i>采购类别：</font>
                            <div class="select-box lang-select clearfix categrey-option f-pr" style="width:520px;">
                                <div class="choose-box f-fl">
                                    <select name='category_name' onchange="selectBycate(this.value, '0' )">
                                        {% for key , item in cateList %}
                                        <option value="{{ item.id }}">{{ item.title}}</option>
                                        {% endfor %}
                                    </select>
                                    <div class="erji-box" id='show_cate_chid_0'></div>
                                </div>
                                <div class="btn-box f-fl">
                                    <a class="btn1" href="javascript:;">添加</a>
                                    <a class="btn2" href="javascript:;">删除</a>
                                </div>
                                <div class="result-box f-fl" id='result-box_0'></div>
                                <input name='category_name_text_0' id="category_name_text_0" style="width:0px;opacity:0;filter:alpha(opacity:0);" type="text" value=""  data-rule="采购类别:required;"  data-target="#category_name_text_0_tip" />
                                <i id='category_name_text_0_tip'></i>

                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="m-title">认证信息</div>
                       <!--  <div class="message clearfix">
                            <font><i>*</i>银行卡开户行：</font>
                            <div class="selectBox selectBox2 f-pr">
                                <select name='user_bank_name' data-rule="银行卡开户行:required;" class="select1">
                                    <option value=''>请选择</option>
                                    {% for key , item in  bankList %}
                                    <option value='{{ item['gate_id']}}' {% if ( item['gate_id'] == userbank['bank_name'] ) %} selected  {% endif %}  >{{ item['bank_name']}}</option>
                                    {% endfor %}
                                </select>
                            </div>
                        </div>
                        <div class="message clearfix">
                            <font><i>*</i>开户行所在地：</font>
                            <div class="selectBox selectBox1 f-pr">
                                <select name='user_bank_province_id' class='class_bank_address select1 mb10'>
                                    <option value=''>请选择</option>
                                </select>
                                <select name='user_bank_city_id'  class='class_bank_address select1 mb10' >
                                    <option value=''>请选择</option>
                                </select>
                                <select name='user_bank_district_id'  class='class_bank_address select1 mb10' data-rule="开户行所在地:required;" >
                                    <option value=''>请选择</option>
                                </select>
                            </div>
                        </div>
                        <div class="message clearfix">
                            <font><i>*</i>开户名：</font>
                            <div class="inputBox inputBox1 f-pr">
                                <input class="input1" type="text" name='user_bank_account' data-rule="开户名:required;chinese;" value="{{userbank['bank_account']}}"  />
                            </div>
                        </div>
                        <div class="message clearfix">
                            <font><i>*</i>卡号：</font>
                            <div class="inputBox inputBox1 f-pr">
                                <input type="text" class="input1" name='user_bank_cardno' data-rule="卡号:required;mark;"  value="{{userbank['bank_cardno']}}" />
                            </div>
                        </div> -->
                        <div class="message clearfix">
                            <font><i>*</i>身份证照：</font>
                            <div class="loadBox f-pr">
                                <div class="loadBtn">
                                    <input class="btn1" type="button" value="+上传图片">
                                    <input class="btn2" type="file" id='user_idcard_picture'  />
                                </div>
                                <a class="link" target="_blank" href="{{ constant('STATIC_URL')}}/mdg/images/lodeImg_img4.jpg">查看样照</a>
                                <div class="tips mt10">图片支持jpg、png、gif格式</div>
                                <input id="user_idcard_picture_hide" type="text" value="" data-rule="身份证照:required;" style="width:0px;opacity:0;filter:alpha(opacity:0);"/>
                            </div>
                        </div>
                        <div class="imgBox f-oh">
                            <div class="imgs f-fl f-pr" id='user_show_idcard_picture'>
                                {% if ( userbank['idcard_picture'] ) %}
                                <img style="width: 120px;height: 120px" src="{{ constant('IMG_URL')}}{{userbank['idcard_picture']}}" />
                                {% endif %}
                            </div>
                        </div>
                        
                        <div class="message clearfix">
                            <font><i>*</i>身份证背面照：</font>
                            <div class="loadBox f-pr">
                                <div class="loadBtn">
                                    <input class="btn1" type="button" value="+上传图片">
                                    <input class="btn2" type="file" id='user_idcard_picture_back' name="user_idcard_picture_back" />
                                </div>
                                <a class="link" target="_blank" href="{{ constant('STATIC_URL')}}/mdg/images/lodeImg_img4.jpg">查看样照</a>
                                <div class="tips mt10">图片支持jpg、png、gif格式</div>
                                <input id="user_idcard_picture_back_hide" type="text" value="" data-rule="身份证背面照:required;" style="width:0px;opacity:0;filter:alpha(opacity:0);"/>
                            </div>
                        </div>
                        <div class="imgBox f-oh">
                            <div class="imgs f-fl f-pr" id='user_show_idcard_picture_back'>
                                {% if ( userbank['idcard_picture_back'] ) %}
                                <img style="width: 120px;height: 120px" src="{{ constant('IMG_URL')}}{{userbank['idcard_picture_back']}}" />
                                {% endif %}
                            </div>
                        </div>  
                        <div class="message clearfix">
                            <font>推荐人：</font>
                            <div class="inputBox inputBox1 f-pr">
                                <input class="input1" type="text" name='seusername'  data-rule="mobile;remote[/member/userfarm/checkEngineer]" value="{{ userinfo['se_mobile']}}"/>
                                <div class="tips mt10">（可以是县域服务中心或责任服务工程师）</div>
                            </div>
                        </div>                      
                    </div>
                    <!-- 采购商 个人end -->
                    <!-- 采购商 企业 start-->
                    <div id="company_comm">
                        <div class="message clearfix">
                            <font><i>*</i>公司名称：</font>
                            <div class="inputBox inputBox1 f-pr">
                                <input type="text" class="input1" name='ent_company_name' data-rule="公司名称:required;gsmc;length_name;length[2~30];" data-rule-gsmc="[/^[a-zA-Z0-9\u4e00-\u9fa5]+$/, '请输入正确的信息']" value="{{ userinfo['company_name']}}" />
                            </div>
                        </div>
                        <div class="message clearfix">
                            <font><i>*</i>注册登记证号：</font>
                            <div class="inputBox inputBox1 f-pr">
                                <input type="text" class="input1" name='ent_register_no' data-rule="注册登记证号:required;wxxx" value="{{ userinfo['register_no']}}" data-rule-wxxx="[/^[0-9a-zA-Z]*$/g, '只能输入字母数字']" />

                            </div>
                        </div>
                        <div class="message clearfix">
                            <font><i>*</i>公司地址：</font>
                            <div class="selectBox selectBox1 f-pr">
                                <select name='province_id' class='ent_selectAreas select1 mb10'>
                                    <option value=''>请选择</option>
                                </select>
                                <select name='city_id' class='ent_selectAreas select1 mb10'>
                                    <option value=''>请选择</option>
                                </select>
                                <select name='district_id'  class='ent_selectAreas select1 mb10'>
                                    <option value=''>请选择</option>
                                </select>
                                <select  name='town_id' class='ent_selectAreas select1 mb10' data-rule="公司地址:required;"  data-target="#address-yzff-1">
                                    <option value=''>请选择</option>
                                </select>
                                <div class="f-oh">
                                    <input class="input1" type="text" data-target="#address-yzff-2" name='ent_address' data-rule="公司详细地址:required;length_name;length[5~50];" value="{{ userinfo['address']}}" />
                                </div>
                                <i class="dz-yz1" id="address-yzff-1"></i>
                                <i class="dz-yz2" id="address-yzff-2"></i>
                            </div>
                        </div>
                        <div class="message clearfix">
                            <font><i>*</i>企业法人：</font>
                            <div class="inputBox inputBox1 f-pr">
                                <input type="text" class="input1" name='ent_erprise_legal_person' data-rule="企业法人:required;fuck;length[2~10];" value="{{ userinfo['enterprise_legal_person']}}"  />
                            </div>
                        </div>
                        <div class="message clearfix">
                            <font><i>*</i>身份证号：</font>
                            <div class="inputBox inputBox1 f-pr">
                                <input type="text" class="input1" name='ent_certificate_no' data-rule="身份证号:required;ID_card;" value="{{ userinfo['certificate_no']}}"  />
                            </div>
                        </div>
                        <div class="message clearfix">
                            <font><i>*</i>采购类别：</font>
                            <div class="select-box lang-select clearfix categrey-option f-pr" style="width:520px;">
                                <div class="choose-box f-fl">
                                    <select name='category_name' onchange="selectBycate(this.value, '1' )">
                                        {% for key , item in cateList %}
                                        <option value="{{ item.id }}">{{ item.title}}</option>
                                        {% endfor %}
                                    </select>
                                    <div class="erji-box" id='show_cate_chid_1'></div>
                                </div>
                                
                                <div class="btn-box f-fl">
                                    <a class="btn1" href="javascript:;">添加</a>
                                    <a class="btn2" href="javascript:;">删除</a>
                                </div>
                                <div class="result-box f-fl" id='result-box_1'></div>

                                <input id="category_name_text_1" name='category_name_text_1' style="width:0px;opacity:0;filter:alpha(opacity:0);" type="text" value=""  data-rule="采购类别:required;"  data-target="#category_name_text_1_tip" />
                                <i id='category_name_text_1_tip'></i>
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="m-title">联系人信息</div>
                        <div class="message clearfix">
                            <font><i>*</i>姓名：</font>
                            <div class="inputBox inputBox1 f-pr">
                                <input type="text" class="input1" name='ent_contact_name' data-rule="姓名:required;chinese;length_name;length[2~10];" value="{{usercontact['name']}}"  />

                            </div>
                        </div>
                        <div class="message clearfix">
                            <font><i>*</i>手机号：</font>
                            <div class="inputBox inputBox1 f-pr">
                                <input type="text" class="input1" name='ent_contact_phone'  data-rule="手机号:required;mobile;" value="{{usercontact['phone']}}" />
                            </div>
                        </div>
                        <div class="message clearfix">
                            <font>传真：</font>
                            <div class="inputBox inputBox1 f-pr">
                                <input type="text" class="input1"  name='ent_contact_fax'  value="{{usercontact['fax']}}"/>
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="m-title">认证信息</div>
                        <!-- <div class="message clearfix">
                            <font><i>*</i>银行卡开户行：</font>
                            <div class="selectBox selectBox2 f-pr">
                                <select name='ent_bank_name' data-rule="银行卡开户行:required;" class="select1">
                                    <option value=''>请选择</option>
                                    {% for key , item in  bankList %}
                                    <option value='{{ item['gate_id']}}' {% if ( item['gate_id'] == userbank['bank_name'] ) %} selected  {% endif %} >{{ item['bank_name']}}</option>
                                    {% endfor %}
                                </select>
                            </div>
                        </div>
                        <div class="message clearfix">
                            <font><i>*</i>开户行所在地：</font>
                            <div class="selectBox selectBox1 f-pr">
                                <select name='ent_bank_province_id' class='ent_class_bank_address select1 mb10'>
                                    <option value=''>请选择</option>
                                </select>
                                <select name='ent_bank_city_id'  class='ent_class_bank_address select1 mb10' >
                                    <option value=''>请选择</option>
                                </select>
                                <select name='ent_bank_district_id'  class='ent_class_bank_address select1 mb10' data-rule="开户行所在地:required;"  >
                                    <option value=''>请选择</option>
                                </select>
                            </div>
                        </div>
                        <div class="message clearfix">
                            <font><i>*</i>开户公司名称：</font>
                            <div class="inputBox inputBox1 f-pr">
                                <input type="text" class="input1" name='ent_bank_account' data-rule="开户公司名称:required;chinese;" value="{{userbank['bank_account']}}" />
                            </div>
                        </div>
                        <div class="message clearfix">
                            <font><i>*</i>卡号：</font>
                            <div class="inputBox inputBox1 f-pr">
                                <input type="text" class="input1" name='ent_bank_cardno' data-rule="卡号:required;mark;" value="{{userbank['bank_cardno']}}" />
                            </div>
                        </div> -->
                        <div class="message clearfix">
                            <font><i>*</i>营业执照：</font>
                            <div class="loadBox f-pr">
                                <div class="loadBtn">
                                    <input class="btn1" type="button" value="+上传图片">
                                    <input class="btn2" type="file" id='ent_identity_picture_lic' name="ent_identity_picture_org"/>
                                </div>
                                <a class="link" target="_blank" href="{{ constant('STATIC_URL')}}/mdg/images/lodeImg_img4.jpg">查看样照</a>
                                <div class="tips mt10">图片支持jpg、png、gif格式</div>
                                <input id="ent_identity_picture_lic_hide" type="text" value="" data-rule="组织机构代码证:required;" style="width:0px;opacity:0;filter:alpha(opacity:0);"/>
                            </div>
                        </div>
                        <div class="imgBox f-oh" id='ent_show_identity_picture_lic'>
                            <div class="imgs f-fl f-pr">
                           {% if ( userbank['identity_picture_lic'] ) %}
                            <img style="width: 120px;height: 120px" src="{{ constant('IMG_URL')}}{{userbank['identity_picture_org']}}" />
                            {% endif %}
                            </div>
                        </div>

                        <div class="message clearfix">
                            <font><i>*</i>组织机构代码证：</font>
                            <div class="loadBox f-pr">
                                <div class="loadBtn">
                                    <input class="btn1" type="button" value="+上传图片">
                                    <input class="btn2" type="file" id='ent_identity_picture_org' name="ent_identity_picture_org"/>
                                </div>
                                <a class="link" target="_blank" href="{{ constant('STATIC_URL')}}/mdg/images/lodeImg_img4.jpg">查看样照</a>
                                <div class="tips mt10">图片支持jpg、png、gif格式</div>
                                <input id="ent_identity_picture_org_hide" type="text" value="" data-rule="组织机构代码证:required;" style="width:0px;opacity:0;filter:alpha(opacity:0);"/>
                            </div>
                        </div>
                        <div class="imgBox f-oh" id='ent_show_identity_picture_org'>
                            <div class="imgs f-fl f-pr">
                            {% if ( userbank['identity_picture_org'] ) %}
                            <img style="width: 120px;height: 120px" src="{{ constant('IMG_URL')}}{{userbank['identity_picture_org']}}" />
                            {% endif %}
                            </div>
                        </div>
                         <div class="message clearfix">
                            <font><i>*</i>税务登记证：</font>
                            <div class="loadBox f-pr">          
                                <div class="loadBtn">
                                    <input class="btn1" type="button" value="+上传图片">
                                    <input class="btn2" type="file" id='ent_identity_picture_tax' name="ent_identity_picture_tax"/>
                                </div>
                                <a class="link" target="_blank" href="{{ constant('STATIC_URL')}}/mdg/images/lodeImg_img4.jpg">查看样照</a>
                                <div class="tips mt10">图片支持jpg、png、gif格式</div>
                                <input id="ent_identity_picture_tax_hide" type="text" value="" data-rule="税务登记证:required;" style="width:0px;opacity:0;filter:alpha(opacity:0);"/>
                            </div>
                        </div>
                        <div class="imgBox f-oh">
                            <div class="imgs f-fl f-pr" id='ent_show_identity_picture_tax'>
                                {% if ( userbank['identity_picture_tax'] ) %}
                                <img style="width: 120px;height: 120px" src="{{ constant('IMG_URL')}}{{userbank['identity_picture_tax']}}" />
                                {% endif %}
                            </div>
                        </div>
                        <div class="message clearfix">
                            <font><i>*</i>身份证照：</font>
                            <div class="loadBox f-pr">
                                <div class="loadBtn">
                                    <input class="btn1" type="button" value="+上传图片">
                                    <input class="btn2" type="file" id='ent_idcard_picture'  />                             
                                </div>
                                <a class="link" target="_blank" href="{{ constant('STATIC_URL')}}/mdg/images/lodeImg_img3.jpg">查看样照</a>
                                <div class="tips mt10">图片支持jpg、png、gif格式</div>
                                <input id="ent_idcard_picture_hide" type="text" value="" data-rule="身份证照:required;" style="width:0px;opacity:0;filter:alpha(opacity:0);"/>
                            </div>
                        </div>
                        <div class="imgBox f-oh">
                            <div class="imgs f-fl f-pr" id='ent_show_idcard_picture'>
                            {% if ( userbank['idcard_picture'] ) %}
                            <img style="width:120px;height: 120px" src="{{ constant('IMG_URL')}}{{userbank['idcard_picture']}}" />
                            {% endif %}
                            </div>
                        </div>
                        <div class="message clearfix">
                            <font><i>*</i>身份证背面照：</font>
                            <div class="loadBox f-pr">
                                <div class="loadBtn">
                                    <input class="btn1" type="button" value="+上传图片">
                                    <input class="btn2" type="file" id='ent_idcard_picture_back' />
                                </div>
                                <a class="link" target="_blank" href="{{ constant('STATIC_URL')}}/mdg/images/lodeImg_img3.jpg">查看样照</a>
                                <div class="tips mt10">图片支持jpg、png、gif格式</div>
                                <input id="ent_idcard_picture_back_hide" type="text" data-rule="身份证背面照:required;" style="width:0px;opacity:0;filter:alpha(opacity:0);"/>
                            </div>
                        </div>
                        <div class="imgBox f-oh">
                            <div class="imgs f-fl f-pr" id='ent_show_idcard_picture_back'>
                                {% if ( userbank['idcard_picture_back'] ) %}
                                <img style="width: 120px;height: 120px" src="{{ constant('IMG_URL')}}{{userbank['idcard_picture_back']}}" />
                                {% endif %}
                            </div>
                        </div>
                        <div class="message clearfix">
                            <font>推荐人：</font>
                            <div class="inputBox inputBox1 f-pr">
                                <input class="input1" type="text" name='ent_seusername'  data-rule="mobile;remote[/member/userfarm/checkEngineer]" value="{{ userinfo['se_mobile']}}"/>
                                <div class="tips mt10">（可以是县域服务中心或责任服务工程师）</div>
                            </div>
                        </div>
                    </div>
                    <!-- 采购商 企业 end-->
                    <input type="hidden" name="isflag" value="{{isflag}}">
                    <input class="apply-btn" type="submit" value="提交申请" />
                </form>
            </div>
        </div>
        <!-- 右侧 end -->
    </div>
</div>
<!--主体 end-->
<!--尾部 start-->
{{ partial('layouts/footer') }}
<!--尾部 end-->
<script type="text/javascript" src="/mdg/js/user_farm.js?sid={{ sid }}&rand=<?php echo rand(1,999);?>"></script>


<script type="text/javascript">
    $('#userForm').validator({
        ignore: ':hidden'
});
</script>

<script type="text/javascript">

$(function () {
        $('input[name="member_type"]').click(function () {
                var val = $(this).val();
                if(val == 0 ) {
                    $('#member_buyer_0').show();
                    $('#member_buyer_1').hide();
                }else{
                    $('#member_buyer_1').show();
                    $('#member_buyer_0').hide();
                    selectBycate(1, 1);
            }
        })
    {% if ptype == 1 %} //个人
        $('#buyer_comm').show();
        $('#company_comm').hide();
    {% if credit_id %}
        /* 公司地址 */
        $(".selectAreas").ld({
            ajaxOptions: {
                "url": "/ajax/getareasfull"
            },
            defaultParentId: 0,
        {% if ( selAreas ) %}
        texts : ['{{selAreas}}'],
        {% endif %}
            style: {
                "width": 102
            }
        });
        // 地区联动
        $(".class_bank_address").ld({
            ajaxOptions: {
                "url": "/ajax/getareasfull"
            },
            defaultParentId: 0,
        {% if ( bankaddress ) %}
        texts : ['{{bankaddress}}'],
        {% endif %}
        style: {
            "width": 102
        }
        });
        {% if(userfarmcrpos) %}
        var html = '';
        var ids = '';
        {% for key,val in userfarmcrpos %}
            html += '<em data-id="{{val['category_id']}}">{{val['category_name']}}</em>';
            ids += '{{val['category_id']}},';
        {% endfor %}
        $('#result-box_0').html(html);
        $('#category_name_text_0').val(ids);
        {% endif %}
        {% if(userbank['idcard_picture']) %}
            $('#user_idcard_picture_hide').val('{{userbank['idcard_picture']}}');
        {% endif %}
        {% if(userbank['idcard_picture_back']) %}
        $('#user_idcard_picture_back_hide').val('{{userbank['idcard_picture_back']}}');
        {% endif %}
    {% endif %}
    {% else %} //公司
        $('#buyer_comm').hide();
        $('#company_comm').show();
        selectBycate(1, 1);
    {% if credit_id %}
        /* 公司地址 */
        $(".ent_selectAreas").ld({
            ajaxOptions: {
                "url": "/ajax/getareasfull"
            },
            defaultParentId: 0,
        {% if ( selAreas ) %}
        texts : ['{{selAreas}}'],
                    {% endif %}
        style: {
            "width": 102
        }
        });
        {% if(userfarmcrpos) %}
        var html = '';
        var ids = '';
        {% for key,val in userfarmcrpos %}
        html += '<em data-id="{{val['category_id']}}">{{val['category_name']}}</em>';
        ids += '{{val['category_id']}},';
        {% endfor %}
        $('#result-box_1').html(html);
        $('#category_name_text_1').val(ids);
        {% endif %}
        // 地区联动
        $(".ent_class_bank_address").ld({
            ajaxOptions: {
                "url": "/ajax/getareasfull"
            },
            defaultParentId: 0,
        {% if ( bankaddress ) %}
        texts : ['{{bankaddress}}'],
        {% endif %}
        style: {
            "width": 102
        }
        });    
        {% if(userbank['idcard_picture']) %}
        $('#ent_idcard_picture_hide').val('{{userbank['idcard_picture']}}');
        {% endif %}
        {% if(userbank['idcard_picture_back']) %}
        $('#ent_idcard_picture_back_hide').val('{{userbank['idcard_picture_back']}}');
        {% endif %}
        {% if(userbank['identity_picture_tax']) %}
        $('#ent_identity_picture_tax_hide').val('{{userbank['identity_picture_tax']}}');
        {% endif %}
        {% if(userbank['identity_picture_org']) %}
        $('#ent_identity_picture_org_hide').val('{{userbank['identity_picture_org']}}');
        {% endif %}
        {% if(userbank['identity_picture_lic']) %}
        $('#ent_identity_picture_lic_hide').val('{{userbank['identity_picture_lic']}}');
        {% endif %}
    {% endif %}
    {% endif %}

})
/* 我要申请-公司地址 */
$(".ent_selectAreas").ld({
    ajaxOptions: {
        "url": "/ajax/getareasfull"
    },
    defaultParentId: 0,
    style: {
        "width": 250
    }
});   
</script>
