<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <title>详情</title>
    <script type="text/javascript" src="/mdg/js/user_farm.js?sid=<?php echo $getid;?>&<?php echo rand(1,99);?>"></script>
    <link rel="stylesheet" type="text/css" href="http://yncstatic.b0.upaiyun.com/mdg/manage/css/default.css" />
    <link rel="stylesheet" type="text/css" href="http://yncstatic.b0.upaiyun.com/js//easyui/themes/metro-green/easyui.css" />
    <link rel="stylesheet" type="text/css" href="http://yncstatic.b0.upaiyun.com/js//easyui/themes/icon.css" />
    <script type="text/javascript" src="http://yncstatic.b0.upaiyun.com/js/easyui/jquery.min.js"></script>
    <script type="text/javascript" src="http://yncstatic.b0.upaiyun.com/js/easyui/jquery.easyui.min.js"></script>
    <!--时间插件-->
    <script type="text/javascript" src="http://yncstatic.b0.upaiyun.com/DatePicker/DatePicker/WdatePicker.js"></script>
    <!--下拉列表-->
     <script type="text/javascript" src="http://yncstatic.b0.upaiyun.com/js/jquery/ld-select.js"></script>
     <!--文章分类-->

    <!--valdate验证-->
    <link rel="stylesheet" type="text/css" href="http://yncstatic.b0.upaiyun.com/js/validator/jquery.validator.css" />
    <script type="text/javascript" src="http://yncstatic.b0.upaiyun.com/js/validator/jquery.validator.js"></script>
    <script type="text/javascript" src="http://yncstatic.b0.upaiyun.com/js/validator/local/zh_CN.js"></script>

    <script type="text/javascript" src="/uploadify/jquery.uploadify.min.js" ></script>
    <link rel="stylesheet" type="text/css" href="/uploadify/uploadify.css">

    <script type="text/javascript" src="http://yncstatic.b0.upaiyun.com/mdg/js/inputFocus.js"></script>

            <!-- 添加商品
    // <script type="text/javascript" src="http://static.ync365.com/sc/js/jquery.idTabs.min.js"></script>
    // <script type="text/javascript" src="http://static.ync365.com/sc/js/select-ui.min.js"></script> -->
    <!-- 消息管理 -->
    <!-- <link rel="stylesheet" type="text/css" href="http://yncstatic.b0.upaiyun.com/mdg/manage/css/service.css" /> -->
    <link rel="stylesheet" type="text/css" href="http://yncstatic.b0.upaiyun.com/mdg/manage/css/style.css" />
    <!--物流批量删除 -->
    <script type="text/javascript" src="http://yncstatic.b0.upaiyun.com//mdg/js/form.js"></script>
    
        

</head>
<body>
    
<link rel="stylesheet" type="text/css" href="http://yncstatic.b0.upaiyun.com/mdg/manage/css/style.css" />


<div class="main_right">

    <!--  
    **  代码从这开始
    -->
    <link rel="stylesheet" href="http://yncstatic.b0.upaiyun.com/mdg/manage/css/manage-2.4.css">
    <script src="http://yncstatic.b0.upaiyun.com/mdg/manage/js/manage-2.4.js"></script>
    <div class="bt2">会员详情</div>
     {{ form("corporate/hcupdate", "method":"post", "autocomplete" : "off",'id':'from') }}
    <div class="btn">

        {% if userinfo and userinfo.status==0 %}
        <input type="hidden" id="hc_type" value="审核通过" name="name">
        <input type="button" value="审核通过" class="sub" onclick="submitForm('审核通过');">
        <input type="button"  onclick="ShowDiv('MyDiv1','fade1')" class="sub" value="审核不通过">
        {% endif %}
        {% if userinfo and userinfo.status==1 %}
        <input type="hidden"  id="pename" value="取消认证" name="name">
        <input type="button"  onclick="ShowDiv4('MyDiv1','fade1')" class="sub" value="取消认证">
        {% endif %}
    </div>
    <div id="fade1" class="black_overlay"></div>
    <div id="MyDiv1" class="white_content2">
        <div class="gb">
            确定审核未通过
            <a href="#" onclick="CloseDiv('MyDiv1','fade1')"></a>
        </div>
        <div class="shenh">
            <ul>
                <li>
                    <lable>请输入拒绝理由：</lable>
                    <div>
                        <!--data-rule="拒绝理由:required;" data-target="#rejectinputID"  -->
                        <textarea name="reject" id="" cols="30" rows="10" maxlength="300"></textarea>
                        <!--<span class="msg-box" for="inputID" id='rejectinputID' style=''></span>-->
                    </div>
                </li>
                <li>
                    <lable>&nbsp;</lable>
                    <div>
                        <input type="button" value="审核不通过"  id="saveData" onclick="submitForm('审核不通过');"  class="btn3" />
                        <input name="" type="button" value="取消" class="btn3" onclick="CloseDiv('MyDiv1','fade1')"/>
                    </div>
                </li>
            </ul>
        </div>
    </div>
     <input type="hidden" name='type_credit' value="{% if userinfo %}<?=Mdg\Models\Users::$_credit_id[$userinfo->credit_type];?>{% else %}0{% endif %}">
    <input type="hidden" name='hidden_userinfo_id' value="{% if userinfo %}{{userinfo.credit_id ? userinfo.credit_id : '0'}}{% else %}0{% endif %}">

    {% if userinfo and userinfo.credit_type==2 or userinfo and userinfo.credit_type==4 %}
<div class="chaxun vip-list">
        <div class="title">权限信息</div>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr height="30">
                <th width="15%">查看负责区域内用户权限：</th>
                <td width="35%"><input type="checkbox" name="procurementcheck[]" value="1" {% if privilege_taginfo and privilege_taginfo['one']>0 %}checked{% endif %}>用户信息
                    <input type="checkbox" name="procurementcheck[]" value="2" {% if privilege_taginfo and privilege_taginfo['two']>0 %}checked{% endif %}>采购信息
                    <input type="checkbox" name="procurementcheck[]" value="4" {% if privilege_taginfo and privilege_taginfo['three']>0 %}checked{% endif %}>供应信息
                    <input type="checkbox" name="procurementcheck[]" value='8' {% if privilege_taginfo and privilege_taginfo['four']>0 %}checked{% endif %}>订单信息
                </td>
                <th width="15%"></th>
                <td width="35%">
                </td>
            </tr>
           
        </table>
    </div>
    {% endif %}
  </form>
    <div class="chaxun vip-list">
        <div class="title">基本信息</div>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr height="30">
                <th width="15%">身份</th>
                <td width="35%">{% if userinfo %}<?=Mdg\Models\Users::$_credit_type[$userinfo->credit_type]?>{% else %}-{% endif %}</td>
                <th width="15%">{% if userinfo.type==0%}个人名称{% else %}公司名称{% endif %}</th>
                <td width="35%">{% if userinfo.type==0 %}{{userinfo.name}}{% else %}{{userinfo.company_name ? userinfo.company_name : '-'}}{% endif %}</td>
            </tr>
            <tr height="30">
                <th width="15%">用户状态</th>
                <td width="35%">{% if userinfo %}<?=Mdg\Models\Userinfo::$_status[$userinfo->status]?>{% else %}-{% endif %}</td>
                <th width="15%">用户类型</th>
                <td width="35%">{% if userinfo %}<?=Mdg\Models\Userinfo::$_type[$userinfo->type]?>{% else %}-{% endif %}</td>
            </tr>
            <tr height="30">
                <th width="15%">用户ID</th>
                <td width="35%">{% if userinfo %}{{userinfo.user_id ? userinfo.user_id : '-'}}{% endif %}</td>
                <th width="15%">申请时间</th>
                <td width="35%">{% if userinfo %}<?php echo date("Y-m-d H:i:s",$userinfo->add_time);?>{% else %}-{% endif %}</td>
            </tr>
            {% if userinfo.type==0 %}
            <tr height="30">
                <th width="15%">手机号</th>
                <td width="35%">{% if userinfo %}{{userinfo.phone ? userinfo.phone : '-'}}{% else %}-{% endif %}</td>
                <th width="15%">身份证号</th>
                <td width="35%">{% if userinfo %}{{userinfo.certificate_no ? userinfo.certificate_no : '-'}}{% else %}-{% endif %}</td>
            </tr>
            {% else %}
            <tr height="30">
                <th width="15%">企业法人</th>
                <td width="35%">{% if userinfo %}{{userinfo.enterprise_legal_person ? userinfo.enterprise_legal_person : '-'}}{% else %}-{% endif %}</td>
                <th width="15%">身份证号</th>
                <td width="35%">{% if userinfo %}{{userinfo.certificate_no ? userinfo.certificate_no : '-'}}{% else %}-{% endif %}</td>
            </tr>
            <tr height="30">
                <th width="15%">注册登记证号</th>
                <td width="35%">{% if userinfo %}{{userinfo.register_no ? userinfo.register_no : '-'}}{% else %}-{% endif %}</td>
                <th width="15%">公司地址</th>
                <td width="35%">{% if userinfo %}{{userinfo.province_name ? userinfo.province_name : '-'}}{{userinfo.city_name ? userinfo.city_name : '-'}}{{userinfo.district_name ? userinfo.district_name : '-'}}{{userinfo.town_name ? userinfo.town_name : '-'}}{{userinfo.address ? userinfo.address : '-'}}{% else %}-{% endif %}</td>
            </tr>
            {% endif %}
            {% if userinfo and userinfo.credit_type==16%}
            <tr height="30">
                <th width="15%">采购类别</th>
                <td width="35%" >
                    <div id="fuzhila">
                    {% if userfarmcrops %}
                    {% for v in userfarmcrops %}
                    {{v['category_name']}}
                    {% endfor %}
                    {% endif %}
                    </div>
                            <!-- <a class="purches-btn" href="javascript:;">修改</a> -->
                    </td>

                {% else %}
                    <!--{% if userinfo and userinfo.credit_type==2 %}
                    <th width="15%">服务区域</th>
                    <td width="35%">{% if userarea %}{{userarea.province_name ? userarea.province_name : '-'}}{{userarea.city_name ? userarea.city_name : '-'}}{{userarea.district_name ? userarea.district_name : '-'}}{{userarea.village_name ? userarea.village_name : '-'}}
                    {% if userinfo.credit_type==2 %}{{userarea.town_name ? userarea.town_name : '-'}}{% endif %}{% else %}-{% endif %}
                    </td>
                    {% else %}-->
                    <th><td></td></th>
                    <!--{% endif %}-->

                {% endif %}
            </tr>
        {% if userinfo and userinfo.credit_type==2 %}
                    <tr height="30">
                        <th width="15%">上级SE</th>
                        <td width="35%">{% if userinfo %}{{userinfo.se_mobile }}{% endif %}</td>
                        <th width="15%"></th>
                        <td width="35%"></td>
                    </tr>
        {% else %}
             <tr height="30">
                <th width="15%">推荐人</th>
                <td width="35%">{% if userinfo and userinfo.mobile_type != '3' %}{{userinfo.se_mobile ? userinfo.se_mobile : (userinfo.se_id ? userinfo.se_id : '-')}}{% else %}-{% endif %}</td>
                {% if userinfo and userinfo.credit_type == 8 %}
                <th width="15%">产地服务站手机号</th>
                <td width="35%">{% if userinfo.se_mobile %}{{userinfo.se_mobile}}{% else %}-{% endif %}</td>                
                {% endif %}
            </tr>
        {% endif %}
        </table>
    </div>
{% if userinfo and userinfo.type>0 %}
    <div class="chaxun vip-list">
        <div class="title">联系人信息</div>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr height="30">
                <th width="15%">姓名</th>
                <td width="35%">{% if usercontact %}{{usercontact.name ? usercontact.name : '-'}}{% else %}-{% endif %}</td>
                <th width="15%">手机号</th>
                <td width="35%">{% if usercontact %}{{usercontact.phone ? usercontact.phone : '-'}}{% else %}-{% endif %}</td>
            </tr>
            <tr height="30">
                <th width="15%">传真号</th>
                <td width="35%">{% if usercontact %}{{usercontact.fax ? usercontact.fax : '-'}}{% else %}-{% endif %}</td>
                <th width="15%"></th>
                <td width="35%"></td>
            </tr>
        </table>
    </div>
{% endif %}
{% if  userbank and userbank.bank_cardno %}
    <div class="chaxun vip-list">
        <div class="title">银行信息</div>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr height="30">
                <th width="15%">银行卡开户行</th>
                <td width="35%">{% if userbank %}<?=Mdg\Models\UserBank::getbank_nameinfo($userbank->bank_name);?>{% else %}-{% endif %}</td>
                <th width="15%">开户行所在地</th>
                <td width="35%">{% if userbank %}{{userbank.bank_address ? userbank.bank_address : '-'}}{% else %}-{% endif %}</td>
            </tr>
            <tr height="30">
                <th width="15%">开户公司名称</th>
                <td width="35%">{% if userbank %}{{userbank.bank_account ? userbank.bank_account : '-'}}{% else %}-{% endif %}</td>
                <th width="15%">卡号</th>
                <td width="35%">{% if userbank %}{{userbank.bank_cardno ? userbank.bank_cardno : '-'}}{% else %}-{% endif %}</td>
            </tr>
        </table>
    </div>
{% endif %}
{% if userinfo and userinfo.credit_type==8%}

    <div class="chaxun vip-list">
        <div class="title">农场信息</div>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr height="30">
                <th width="15%">农场名</th>
                <td width="35%">{% if userfarm %}{{userfarm.farm_name}}{% else %}-{% endif %}</td>
                <th width="15%">农场地址</th>
                <td width="35%">{% if userfarm %}{{userfarm.province_name ? userfarm.province_name : '-'}}
                    {{userfarm.city_name ? userfarm.city_name : '-'}}
                    {{userfarm.district_name ? userfarm.district_name : '-'}}
                    {{userfarm.town_name ? userfarm.town_name : '-'}}
                    {{userfarm.village_name ? userfarm.village_name : ''}}
                    {{userfarm.address ? userfarm.address : '-'}}
                    {% else %}-{% endif %}</td>
            </tr>
            <tr height="30">
                <th width="15%">农场面积</th>
                <td width="35%">{% if userfarm %}{{userfarm.farm_area ? userfarm.farm_area : '-'}}{% else %}-{% endif %}</td>
                <th width="15%">种植作物</th>
                <td width="35%">{% for v in userfarmcrops%}{{v['category_name']}},{% endfor %}</td>
            </tr>
            <tr height="30">
                <th width="15%">土地来源</th>
                <td width="35%">{% if userfarm %}{% if userfarm.source==0%}自有{% else %}流转{% endif %}{% else %}-{% endif %}</td>
                <th width="15%">土地使用年限</th>
                <td width="35%">{% if userfarm %}{{userfarm.start_year ? userfarm.start_year : '-'}}{% else %}0{% endif %}年{% if userfarm %}{{userfarm.start_month ? userfarm.start_month : '-'}}{% else %}0{% endif %}月&nbsp;&nbsp;——&nbsp;&nbsp;{% if userfarm %}{{userfarm.year ? userfarm.year : '-'}}{% else %}0{% endif %}年{% if userfarm %}{{userfarm.month ? userfarm.month : '-'}}{% else %}0{% endif %}月</td>
            </tr>
            
            <tr height="30">
                <th width="15%">农场照片</th>
                <td width="35%">
                    {% if user_farm_picture %}
                        {% for a in user_farm_picture %}
                        {% if a['picture_path'] %}
                            <img src="http://yncmdg.b0.upaiyun.com/{{a['picture_path']}}" width="100px" height="100px">
                            {% else %}
                            <img src="http://static.ync365.com/mdg/images/detial_b_img.jpg" width="100px" height="100px">
                            {% endif %}
                        {% endfor %}
                    {% else %}<img src="http://static.ync365.com/mdg/images/detial_b_img.jpg" width="100px" height="100px">{% endif %}</td>
                <th width="15%">耕地合同</th>
                <td width="35%">
                    {% if user_farm_picture_contact %}
                        {% for a in user_farm_picture_contact %}
                        {% if a['picture_path'] %}
                            <img src="http://yncmdg.b0.upaiyun.com/{{a['picture_path']}}" width="100px" height="100px">
                            {% else %}
                            <img src="http://static.ync365.com/mdg/images/detial_b_img.jpg" width="100px" height="100px">
                            {% endif %}
                        {% endfor %}
                    {% else %}<img src="http://static.ync365.com/mdg/images/detial_b_img.jpg" width="100px" height="100px">{% endif %}
                    
                </td>
            </tr>
            <tr height="30">
                <th width="15%">农场简介</th>
                <td width="35%">{% if userfarm %}{{userfarm.describe ? userfarm.describe : '-'}}{% else %}-{% endif %}</td>
                <th width="15%"></th>
                <td width="35%"></td>
            </tr>
        </table>
    </div>

{% endif %}

    <div class="chaxun vip-list">
        <div class="title">照片信息</div>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border-none">
          <!--  <tr>
                <td class="cx_title">银行卡证明照：</td>
                <td class="cx_content">
                    {% if userbank %}
                    <img src="http://yncmdg.b0.upaiyun.com/{{userbank.bankcard_picture ? userbank.bankcard_picture : '/upload/bank/2015/06/14/118001784991.jpg'}}" alt="银行卡正面照" width="150px" height="100px">
                    {% else %}
                    <img src="http://static.ync365.com/mdg/images/detial_b_img.jpg" alt="银行卡正面照" width="150px" height="100px">
                    {% endif %} 
                </td>
            </tr>-->
        
            <tr>
                <td class="cx_title">身份证照：</td>
                <td class="cx_content">
                    {% if userbank %}
                    <img src="http://yncmdg.b0.upaiyun.com/{{userbank.idcard_picture ? userbank.idcard_picture : '/upload/identitycardfront/2015/06/14/647964228109.jpg'}}" alt="银行卡正面照" width="150px" height="100px">
                    {% else %}
                    <img src="http://static.ync365.com/mdg/images/detial_b_img.jpg" alt="银行卡正面照" width="150px" height="100px">
                    {% endif %} 
                </td>
            </tr>
            <tr>
                <td class="cx_title">身份背面证照：</td>
                <td class="cx_content">
                    <div id="ent_show_identity_card_back">
                    {% if userbank %}
                    <img src="http://yncmdg.b0.upaiyun.com/{{userbank.idcard_picture_back ? userbank.idcard_picture_back : '/upload/identitycardfront/2015/06/14/647964228109.jpg'}}" alt="银行卡正面照" width="150px" height="100px">
                    {% else %}
                    <img src="http://static.ync365.com/mdg/images/detial_b_img.jpg" alt="银行卡正面照" width="150px" height="100px">
                    {% endif %} 
                </div>
<!--                      <div class="btn" style="text-align:left; padding:0; margin:0 0 20px;">
                        <input type="file" value="重新上传" class="sub" style="width:121px; height:31px; border:none;" id="ent_identity_card_back">
                     </div> -->
                </td>
            </tr>
            <!--
{% if userinfo and userinfo.type==0 %}
            <tr>
                <td class="cx_title">个人手持身份证照片：</td>
                <td class="cx_content">
                    {% if userbank %}
                    <img src="http://yncmdg.b0.upaiyun.com/{{userbank.person_picture ? userbank.person_picture : '/upload/identitycardfront/2015/06/14/647964228109.jpg'}}" alt="银行卡正面照" width="150px" height="100px">
                    {% else %}
                    <img src="http://static.ync365.com/mdg/images/detial_b_img.jpg" alt="银行卡正面照" width="150px" height="100px">
                    {% endif %} 
                </td>
            </tr>
{% endif %}-->
{% if userinfo and userinfo.type>0 %}
            <tr>
                <td class="cx_title">个体工商营业执照：</td>
                <td class="cx_content">
                    {% if userbank %}
                    <img src="http://yncmdg.b0.upaiyun.com/{{userbank.identity_picture_lic ? userbank.identity_picture_lic : '/upload/identitypicturelicPath/2015/06/14/828668888408.jpg'}}" alt="银行卡正面照" width="150px" height="100px">
                    {% else %}
                    <img src="http://static.ync365.com/mdg/images/detial_b_img.jpg" alt="银行卡正面照" width="150px" height="100px">
                    {% endif %} 
                </td>
            </tr>
             {% if userinfo.credit_type>0  and userinfo.credit_type != 8 %}
            <tr>
                <td class="cx_title">组织机构代码证：</td>
                <td class="cx_content">
                    {% if userbank %}
                    <img src="http://yncmdg.b0.upaiyun.com/{{userbank.identity_picture_org ? userbank.identity_picture_org : ''}}" alt="" width="150px" height="100px">
                    {% else %}
                    <img src="http://static.ync365.com/mdg/images/detial_b_img.jpg" alt="" width="150px" height="100px">
                    {% endif %} 
                </td>
            </tr>
             <tr>
                <td class="cx_title">税务登记证：</td>
                <td class="cx_content">
                    {% if userbank %}
                    <img src="http://yncmdg.b0.upaiyun.com/{{userbank.identity_picture_tax ? userbank.identity_picture_tax : ''}}" alt="" width="150px" height="100px">
                    {% else %}
                    <img src="http://static.ync365.com/mdg/images/detial_b_img.jpg" alt="" width="150px" height="100px">
                    {% endif %} 
                </td>
            </tr>
            {% endif %}    
{% endif %}

        </table>
    </div>

</div>
<div class="vip-layer"></div>
<div class="vip-box">
    <a class="close-btn" href="javascript:;"></a>
    <div class="form-attest">
        <div class="message clearfix">
            <font>采购类别</font>
            <div class="select-box lang-select clearfix categrey-option">
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
                <div class="result-box f-fl" id='result-box_0'>
                        {% if userfarmcrops %}
                        {% for v in userfarmcrops %}
                        <em data-id='{{v['category_id']}}'>{{v['category_name']}}</em>
                        {% endfor %}
                        {% endif %}
                </div>
            </div>
        </div>
    </div>
    <div class="btn" style="margin-top:30px;">
         <input type="hidden" name='category_name_text_0' id='category_name_text_0' value="{% if category_name_id %}{{category_name_id}}{% endif %}">
        <input type="button" value="确定" class="sub" id="button_id">            
    </div>
</div>
<!-- main_right 结束  -->
<div id="fade1" class="black_overlay"></div>
<div id="MyDiv1" class="white_content2">
    <form action="/manage/tag/tagunauditun"   method="post"  >
        <div class="gb">
            确定审核未通过
            <a href="#" onclick="CloseDiv('MyDiv1','fade1')"></a>
        </div>
        <div class="shenh">
            <ul>
                <li>
                    <lable>请输入取消理由：</lable>
                    <div>
                        <input type="hidden" id="zjuser_id" value="">
                        <input type="hidden" id="zjcredit_id" value="">
                        <input name="reject" type="text"  value=''  data-rule="拒绝理由:required;" data-target="#rejectinputID"   />
                        <span class="msg-box" for="inputID" id='rejectinputID' style=''></span>
                    </div>
                </li>
                <li>
                    <lable>&nbsp;</lable>
                    <div>
                        <input type="button" value="确定" class="btn3" id="saveData" onclick="setreject();"/>
                        <input type="hidden" name='id' value='{{ data.tag_id}}'> <!-- # 隐藏ID -->
                        <input name="" type="button" value="取消" class="btn3" onclick="CloseDiv('MyDiv1','fade1')"/>
                    </div>
                </li>
            </ul>
        </div>
    </form>
</div>
<script type="text/javascript">

        $('#button_id').click(function() {
            var a=$("#result-box_0").html();
            $("#fuzhila").html(a);
            $('.vip-layer').hide();
            $('.vip-box').hide();
        });

//弹出隐藏层
function ShowDiv(show_div,bg_div){

    document.getElementById(show_div).style.display='block';
    document.getElementById(bg_div).style.display='block' ;
    var bgdiv = document.getElementById(bg_div);
    bgdiv.style.width = document.body.scrollWidth;
    $("#"+bg_div).height($(document).height());
};
//弹出隐藏层
function ShowDiv4(show_div,bg_div){
   
    $("#pename").val("取消认证");
    document.getElementById(show_div).style.display='block';
    document.getElementById(bg_div).style.display='block' ;
    var bgdiv = document.getElementById(bg_div);
    bgdiv.style.width = document.body.scrollWidth;
    $("#"+bg_div).height($(document).height());
};
//关闭弹出层
function CloseDiv(show_div,bg_div)
{
    document.getElementById(show_div).style.display='none';
    document.getElementById(bg_div).style.display='none';
};
function submitForm(text){
    $('#saveData').attr('disabled',true);
    var len = $("textarea[name='reject']").val().length;
    if(len>=300){
        alert('理由过长,请重新输入');
        $('#saveData').attr('disabled',false);
        return false;
    }
    if(text == '审核通过'){
        $("#hc_type").val('审核通过');
        $('form').submit();
    }
    else{
        $("#hc_type").val('审核不通过');
        $('form').submit();
    }
}

function setreject(){
    $('#saveData').attr('disabled',true);
    if($('#zjuser_id').val() && $('#zjcredit_id').val()){
        window.location='/manage/corporate/get/'+$('#zjuser_id').val()+'/16/pe/'+$('#zjcredit_id').val()+'?reject='+$("input[name='reject']").val();
    }
    else{
        alert('参数错误');
    }
}
//弹出隐藏层
function ShowDiv3(show_div,bg_div,user_id,credit_id){
    document.getElementById(show_div).style.display='block';
    document.getElementById(bg_div).style.display='block' ;
    var bgdiv = document.getElementById(bg_div);
    bgdiv.style.width = document.body.scrollWidth;
    $("#"+bg_div).height($(document).height());
    $('#zjuser_id').val(user_id);
    $('#zjcredit_id').val(credit_id);
};
//关闭弹出层
function CloseDiv4(show_div,bg_div)
{
    document.getElementById(show_div).style.display='none';
    document.getElementById(bg_div).style.display='none';
    $('#zjuser_id').val('');
    $('#zjcredit_id').val('');
};


function tab(o, s, cb, ev){ //tab切换类
var $ = function(o){return document.getElementById(o)};
var css = o.split((s||'_'));
if(css.length!=4)return;
this.event = ev || 'onclick';
o = $(o);
if(o){
this.ITEM = [];
o.id = css[0];
var item = o.getElementsByTagName(css[1]);
var j=1;
for(var i=0;i<item.length;i++){
if(item[i].className.indexOf(css[2])>=0 || item[i].className.indexOf(css[3])>=0){
if(item[i].className == css[2])o['cur'] = item[i];
item[i].callBack = cb||function(){};
item[i]['css'] = css;
item[i]['link'] = o;
this.ITEM[j] = item[i];
item[i]['Index'] = j++;
item[i][this.event] = this.ACTIVE;
}
}
return o;
}
}
tab.prototype = {
ACTIVE:function(){
var $ = function(o){return document.getElementById(o)};
this['link']['cur'].className = this['css'][3];
this.className = this['css'][2];
try{
$(this['link']['id']+'_'+this['link']['cur']['Index']).style.display = 'none';
$(this['link']['id']+'_'+this['Index']).style.display = 'block';
}catch(e){}
this.callBack.call(this);
this['link']['cur'] = this;
}
}
</script>
<script type="text/javascript">
window.onload = function(){
new tab('test2_li_now_');
}
</script>
<SCRIPT type="text/javascript">
<!--
    var treeObj;
    var setting = {
        check: {
            enable: true,
            chkStyle: "radio",
            radioType: "all"
        },
        data: {
            simpleData: {
                enable: true
            }
        },
        callback: {
                onCheck: zTreeOnCheck,
                onClick: zTreeOnClick
            }
    };
    function zTreeOnCheck(event, treeId, treeNode) {
        $('#cid').val(treeNode.id);
    };

    function zTreeOnClick(event, treeId, treeNode) {
        treeObj.checkNode(treeNode, true, true);
        $('#cid').val(treeNode.id);
    };

    function setCheck() {
        $.getJSON('/manage/articlecategory/ajax', function(data) {
            treeObj = $.fn.zTree.init($("#treeDemo"), setting, data);
            treeObj.expandAll(true);
            treeObj.checkNode()
        });

    }
    $(document).ready(function(){
        setCheck();
    });
//-->
</SCRIPT>
<script type="text/javascript">
    var ue = UE.getEditor('editor');
</script>
<link rel="stylesheet" type="text/css" href="http://yncstatic.b0.upaiyun.com/js/validator/jquery.validator.css" />
<script type="text/javascript" src="http://yncstatic.b0.upaiyun.com/js/validator/jquery.validator.js"></script>
<script type="text/javascript" src="http://yncstatic.b0.upaiyun.com/js/validator/local/zh_CN.js"></script>
<script type="text/javascript">
$("#from").validator({
     fields:  {
         reject:"length[1~300]",
     }
    
});
</script>
<div class="footer"> Copyright © 2013-2014 ync365.com All rights reserved. </div>
</body>
</html>

</body>
</html>

