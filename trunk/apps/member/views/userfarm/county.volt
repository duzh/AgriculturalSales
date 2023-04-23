<!--头部-->
{{ partial('layouts/member_header') }}
<link rel="stylesheet" href="{{ constant('STATIC_URL') }}mdg/version2.5/css/verfiy.css">
<div class="wrapper">
    <div class="w1190 mtauto f-oh">
        <!-- 左侧 -->
        {{ partial('layouts/navs_left') }}
        <!-- 右侧 -->
            <div class="center-right f-fr">
                <form action="/member/userfarm/countysave" method="post" id='rolesForm'>
                <div class="roles-apply f-oh">
                    <div class="title f-oh">
                        <span>县域服务中心申请</span>
                    </div>
                    <div class="m-title">基本信息</div>
                    <div class="message clearfix">
                        <font>
                            <i>*</i>您的身份：
                        </font>
                        <div class="radioBox f-oh tabBtn">
                            <label class="f-db f-fl f-oh">
                                <input type="radio" name='member_type'  value='0' checked>
                                <i>个体户</i>
                            </label>
                            <label class="f-db f-fl f-oh">
                                <input type="radio" name='member_type' value='1'>
                                <i>企业</i>
                            </label>
                        </div>
                    </div>
                        <div class="tabChange" style="display:block;">
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>姓名：
                                </font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input name="user_name" class="input1" type="text">
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>身份证号：
                                </font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input name="user_credit_no" class="input1" type="text">
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>手机号：
                                </font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input name="user_mobile" class="input1" type="text">
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>所在区域：
                                </font>
                                <div class="selectBox selectBox1 f-pr">
                                    <select class="select1 mb10 selectAreas" name='user_province_id'>
                                        <option value="">省</option>
                                    </select>
                                    <select class="select1 mb10 selectAreas" name='user_city_id'>
                                        <option value="">市</option>
                                    </select>
                                    <select class="select1 mb10 selectAreas" name='user_district_id'>
                                        <option value="">区／县</option>
                                    </select>
                                    <select name='user_town_id' data-target="#address-yz-1" class="select1 mb10 selectAreas">
                                        <option value="">街道</option>
                                    </select>
                                    <div class="f-oh">
                                        <input name="user_address"  data-target="#address-yz-2" class="input1" type="text">
                                    </div>
                                    <i class="dz-yz1" id="address-yz-1"></i>
                                    <i class="dz-yz2" id="address-yz-2"></i>
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
                                    <select class="select1 mb10 selectAreasBank" name='user_bank_province_id'>
                                        <option value="">省</option>
                                    </select>
                                    <select class="select1 mb10 selectAreasBank" name='user_bank_city_id'>
                                        <option value="">市</option>
                                    </select>
                                    <select name='user_bank_district_id' class="select1 selectAreasBank" data-rule="开户行所在地:required;">
                                        <option value="">区／县</option>
                                    </select>
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>开户名：
                                </font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input name='user_bank_account' class="input1" type="text">
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>卡号：
                                </font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input name="user_bank_cardno" class="input1" type="text">
                                </div>
                            </div> -->
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>身份证照：
                                </font>
                                <div class="loadBox f-pr">
                                    <div class="loadBtn">
                                        <input class="btn1" type="button" value="+上传图片">
                                        <input class="btn2" type="file" id='user_idcard_picture'>
                                    </div>
                                    <a class="link" href="{{ constant('STATIC_URL')}}/mdg/images/lodeImg_img3.jpg" target="_blank">查看样照</a>
                                    <input class="hideTxt" name="photo" id="user_idcard_picture_hide" type="text" value=""  data-target="#user_idcard_picture_tip">
                                    <i id='user_idcard_picture_tip' style="left:176px; top:-6px;"></i>
                                    <div class="tips">图片大小不超过2M，支持jpg、png、gif格式</div>
                                </div>
                            </div>
                            <!-- 上传成功后的图片位置 -->
                            <div class="imgBox f-oh">
                                <div class="imgs f-fl f-pr" id='user_show_idcard_picture'>
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>身份证背面照：
                                </font>
                                <div class="loadBox f-pr">
                                    <div class="loadBtn">
                                        <input class="btn1" type="button" value="+上传图片">
                                        <input class="btn2" type="file" id='user_idcard_picture_back'>
                                    </div>
                                    <a class="link" href="{{ constant('STATIC_URL')}}/mdg/images/lodeImg_img3.jpg" target="_blank">查看样照</a>
                                    <input class="hideTxt" name="back-photo" id="user_idcard_picture_back_hide" style="width:1px;opacity:0;filter:alpha(opacity:0);" type="text" value=""   data-target="#user_idcard_picture_back_tip">
                                    <i id='user_idcard_picture_back_tip' style="left:176px; top:-6px;"></i>
                                    <div class="tips">图片大小不超过2M，支持jpg、png、gif格式</div>
                                </div>
                            </div>
                            <!-- 上传成功后的图片位置 -->
                            <div class="imgBox f-oh">
                                <div class="imgs f-fl f-pr" id='user_show_idcard_picture_back'>
                                    
                                </div>
                            </div>
                            <div class="line"></div>
                            <div class="m-title">服务工程师</div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>工程师账号：
                                </font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input name='senameid' class="input1" type="text">
                                </div>
                            </div>
                        </div>
                        <div class="tabChange"  style="display:none;">
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>公司名称：
                                </font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input name="ent_company_name" class="input1" type="text">
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>注册登记证号：
                                </font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input name="ent_register_no" class="input1" type="text">
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>公司地址：
                                </font>
                                <div class="selectBox selectBox1 f-pr">
                                    <select class="select1 mb10 selectAreasCompany" name='province_id'>
                                        <option value="">省</option>
                                    </select>
                                    <select class="select1 mb10 selectAreasCompany" name='city_id'>
                                        <option value="">市</option>
                                    </select>
                                    <select class="select1 mb10 selectAreasCompany" name='district_id'>
                                        <option value="">区／县</option>
                                    </select>
                                    <select name='town_id' data-target="#address-yz1-1" class="select1 mb10 selectAreasCompany">
                                        <option value="">街道</option>
                                    </select>                                    
                                    <div class="f-oh">
                                        <input name="ent_address" data-target="#address-yz1-2" class="input1" type="text">
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
                                    <input name="ent_erprise_legal_person" class="input1" type="text">
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>身份证号：
                                </font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input name="ent_certificate_no" class="input1" type="text">
                                </div>
                            </div>
                            <div class="line"></div>
                            <div class="m-title">联系人信息</div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>姓名：
                                </font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input name="ent_contact_name" class="input1" type="text">
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>手机号：
                                </font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input name="ent_contact_phone" class="input1" type="text">
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>传真：</font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input class="input1" name="ent_contact_fax" type="text">
                                </div>
                            </div>
                            <div class="line"></div>
                            <div class="m-title">认证信息</div>
                           <!--  <div class="message clearfix">
                                <font>
                                    <i>*</i>银行卡开户行：
                                </font>
                                <div class="selectBox selectBox2 f-pr">
                                    <select name='ent_bank_name' class="select1">
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
                                    <select class="select1 mb10 selectAreasCompanyBank" name='ent_bank_province_id'>
                                        <option value="">省</option>
                                    </select>
                                    <select class="select1 mb10 selectAreasCompanyBank" name='ent_bank_city_id'>
                                        <option value="">市</option>
                                    </select>
                                    <select name="gs-bank-address" class="select1 selectAreasCompanyBank" name='ent_bank_district_id' data-rule="开户行所在地:required;">
                                        <option value="">区／县</option>
                                    </select>
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>开户公司名称：
                                </font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input name="ent_bank_account" class="input1" type="text">
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>卡号：
                                </font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input name="ent_bank_cardno" class="input1" type="text">
                                </div>
                            </div> -->
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>营业执照：
                                </font>
                                <div class="loadBox f-pr">
                                    <div class="loadBtn">
                                        <input class="btn1" type="button" value="+上传图片">
                                        <input class="btn2" type="file" id='ent_identity_picture_lic'>
                                    </div>
                                    <a class="link" href="{{ constant('STATIC_URL')}}/mdg/images/lodeImg_img4.jpg">查看样照</a>
                                    <input class="hideTxt" id="ent_identity_picture_lic_hide" name="gs-yezz" type="text" value="" data-target="#ent_identity_picture_lic_tip" />
                                        <i id='ent_identity_picture_lic_tip' style="left:176px; top:-6px;"></i>
                                    <div class="tips">图片大小不超过2M，支持jpg、png、gif格式</div>
                                </div>
                            </div>
                            <!-- 上传成功后的图片位置 -->
                            <div class="imgBox f-oh">
                                <div class="imgs f-fl f-pr" id='ent_show_identity_picture_lic'>
                                    
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>组织机构代码证：
                                </font>
                                <div class="loadBox f-pr">
                                    <div class="loadBtn">
                                        <input class="btn1" type="button" value="+上传图片">
                                        <input class="btn2" type="file" id='ent_organization_code' name="ent_organization_code">
                                    </div>
                                    <a class="link" href="{{ constant('STATIC_URL')}}/mdg/images/lodeImg_img4.jpg">查看样照</a>
                                    <input class="hideTxt" name="gs-jh-photo" id="ent_organization_code_hide"  type="text" value="" data-target="#ent_organization_code_tip" />
                                        <i id='ent_organization_code_tip' style="left:176px; top:-6px;"></i>
                                    <div class="tips">图片大小不超过2M，支持jpg、png、gif格式</div>
                                </div>
                            </div>
                            <!-- 上传成功后的图片位置 -->
                            <div class="imgBox f-oh">
                                <div class="imgs f-fl f-pr" id='ent_show_organization_code'>
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>税务登记证：
                                </font>
                                <div class="loadBox f-pr">
                                    <div class="loadBtn">
                                        <input class="btn1" type="button" value="+上传图片">
                                        <input class="btn2" type="file" id='ent_tax_registration' name="ent_tax_registration">
                                    </div>
                                    <a class="link" href="{{ constant('STATIC_URL')}}/mdg/images/lodeImg_img4.jpg">查看样照</a>
                                    <input class="hideTxt" name="gs-dj-photo" id="ent_tax_registration_hide" style="width:1px;opacity:0;filter:alpha(opacity:0);" type="text" value="" data-rule="required;"  data-target="#ent_tax_registration_tip" />
                                        <i id='ent_tax_registration_tip' style="left:176px; top:-6px;"></i>
                                    <div class="tips">图片大小不超过2M，支持jpg、png、gif格式</div>
                                </div>
                            </div>
                            <!-- 上传成功后的图片位置 -->
                            <div class="imgBox f-oh" >
                                <div class="imgs f-fl f-pr" id='ent_show_tax_registration'>
                                    
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>身份证照：
                                </font>
                                <div class="loadBox f-pr">
                                    <div class="loadBtn">
                                        <input class="btn1" type="button" value="+上传图片">
                                        <input class="btn2" type="file" id='ent_idcard_picture'>
                                    </div>
                                    <a class="link" href="{{ constant('STATIC_URL')}}/mdg/images/lodeImg_img3.jpg">查看样照</a>
                                    <input class="hideTxt" name="gs-photo" type="text" id="ent_idcard_picture_hide" value=""  data-target="#ent_idcard_picture_tip">
                                    <i id='ent_idcard_picture_tip' style="left:176px; top:-6px;"></i>
                                    <div class="tips">图片大小不超过2M，支持jpg、png、gif格式</div>
                                </div>
                            </div>
                            <!-- 上传成功后的图片位置 -->
                            <div class="imgBox f-oh">
                                <div class="imgs f-fl f-pr" id='ent_show_idcard_picture'>
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>身份证背面照：
                                </font>
                                <div class="loadBox f-pr">
                                    <div class="loadBtn">
                                        <input class="btn1" type="button" value="+上传图片">
                                        <input class="btn2" type="file" id='ent_idcard_picture_back'>
                                    </div>
                                    <a class="link" href="{{ constant('STATIC_URL')}}/mdg/images/lodeImg_img3.jpg">查看样照</a>
                                    <input class="hideTxt" id="ent_idcard_picture_back_hide" name="gs-back-photo" type="text" value="" data-target="#ent_idcard_picture_back_tip" />
                                        <i id='ent_idcard_picture_back_tip' style="left:176px; top:-6px;"></i>
                                    <div class="tips">图片大小不超过2M，支持jpg、png、gif格式</div>
                                </div>
                            </div>
                            <!-- 上传成功后的图片位置 -->
                            <div class="imgBox f-oh">
                                <div class="imgs f-fl f-pr" id='ent_show_idcard_picture_back'>
                                </div>
                            </div>
                            <div class="line"></div>
                            <div class="m-title">服务工程师</div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>工程师账号：
                                </font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input name='seid' class="input1" type="text">
                                </div>
                            </div>
                        </div>
                        <input class="apply-btn" type="submit" value="提交申请">
                    </form>
                </div>

            </div>
        <!-- 右侧end-->
    </div>
</div>
<!--底部-->
{{ partial('layouts/footer') }}}
<script type="text/javascript" src="/mdg/js/user_farm.js?sid={{ sid }}&rand=<?php echo rand(1,999);?>"></script>
<script type="text/javascript">
        $(function(){
            // 切换
            $('.tabBtn label').click(function(){
                $('.tabBtn input').prop('checked', false);
                $('.tabChange').hide();

                $(this).find('input').prop('checked', true);
                $('.tabChange').eq($(this).index()).show();
            });

            // 验证
            $('#rolesForm').validator({
                ignore: ':hidden',
                fields: {
                    'user_name': '姓名:required;fuck;length[2~10]',
                    'user_credit_no': '身份证号:required;ID_card',
                    'user_mobile': '手机号:required;mobile',
                    'user_address': '所在区域详细地址:required;length_name;length[3~50];',
                    'address2': '所在区域:required;',
                    'user_bank_name': '银行卡开户行:required;',
                    'user_bank_district_id': '开户行所在地:required;',
                    'user_bank_account': '开户名:required;',
                    'user_bank_cardno': '卡号:required;mark',
                    'photo': '身份证照:required;',
                    'back-photo': '身份证背面照:required;',
                    'senameid': '工程师账号:required;remote[/member/userfarm/checkEngineer]',
                    'ent_company_name': '公司名称:required;length_name;length[2~30];',
                    'ent_register_no': '注册登记证号:required;',
                    'ent_address': '公司详细地址:required;length_name;length[5~50];',
                    'ent_erprise_legal_person': '企业法人:required;fuck;length[2~10];',
                    'ent_certificate_no': '身份证号:required;ID_card',
                    'ent_contact_name': '姓名:required;fuck;length[2~10];',
                    'ent_contact_phone': '手机号:required;mobile',
                    'ent_bank_name': '银行卡开户行:required;',
                    'ent_bank_district_id': '开户行所在地:required;',
                    'ent_bank_account': '开户公司名称:required;',
                    'ent_bank_cardno': '卡号:required;',
                    'gs-yezz': '营业执照:required;',
                    'town_id':'公司地址:required;',
                    'user_town_id':'所在区域:required;',
                    'gs-jh-photo': '组织机构代码证:required;',
                    'gs-dj-photo': '税务登记证:required;',
                    'gs-photo': '身份证照:required;',
                    'gs-back-photo': '身份证背面照:required;',
                    'seid': '工程师账号:required;remote[/member/userfarm/checkEngineer]'
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
//个人地址
$(".selectAreas").ld({
    ajaxOptions: {
        "url": "/ajax/getareasfull"
    },
    defaultParentId: 0,
    style: {
        "width": 250
    }
});
//银行地址
$(".selectAreasBank").ld({
    ajaxOptions: {
        "url": "/ajax/getareasfull"
    },
    defaultParentId: 0,
    style: {
        "width": 250
    }
});
//银行地址
$(".selectAreasCompanyBank").ld({
    ajaxOptions: {
        "url": "/ajax/getareasfull"
    },
    defaultParentId: 0,
    style: {
        "width": 250
    }
});
//公司地址
$(".selectAreasCompany").ld({
    ajaxOptions: {
        "url": "/ajax/getareasfull"
    },
    defaultParentId: 0,
    style: {
        "width": 250
    }
});

</script>
