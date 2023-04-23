<script type="text/javascript" src="/mdg/js/user_farm.js?sid=<?php echo $getid;?>&<?php echo rand(1,99);?>"></script>
    <link rel="stylesheet" type="text/css" href="{{ constant('UPY_URL') }}mdg/manage/css/default.css" />
    <script type="text/javascript" src="{{ constant('UPY_URL') }}/mdg/js/form.js"></script>
</head>
<body>
<link rel="stylesheet" type="text/css" href="{{ constant('UPY_URL') }}mdg/manage/css/style.css" />
<div class="main_right">

    <!--  
    **  代码从这开始
    -->
    <link rel="stylesheet" href="{{ constant('UPY_URL') }}mdg/manage/css/manage-2.4.css">
    <script src="{{ constant('UPY_URL') }}mdg/manage/js/manage-2.4.js"></script>
    <div class="bt2">会员详情</div>
     {{ form("corporate/hcupdate", "method":"get", "autocomplete" : "off") }}
    <div class="btn">

        {% if userinfo and userinfo.status==0 %}
        <input type="submit" value="审核通过" class="sub" name="name">
        <input type="submit" value="审核不通过" class="sub" name="name">
        {% endif %}
        {% if userinfo and userinfo.status==1 %}
        <input type="submit" value="取消认证" class="sub" name="name">              
        {% endif %}
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
            <tr height="30">
                <th width="15%">注册登记证号</th>
                <td width="35%">{% if userinfo %}{{userinfo.register_no ? userinfo.register_no : '-'}}{% else %}-{% endif %}</td>
                <th width="15%">手机号</th>
                <td width="35%">{% if userinfo %}{{userinfo.phone ? userinfo.phone : '-'}}{% else %}-{% endif %}</td>
            </tr>
            <tr height="30">
                <th width="15%">企业法人</th>
                <td width="35%">{% if userinfo %}{{userinfo.enterprise_legal_person ? userinfo.enterprise_legal_person : '-'}}{% else %}-{% endif %}</td>
                <th width="15%">身份证号</th>
                <td width="35%">{% if userinfo %}{{userinfo.certificate_no ? userinfo.certificate_no : '-'}}{% else %}-{% endif %}</td>
            </tr>
            <tr height="30">
                <th width="15%">公司地址</th>
                <td width="35%">{% if userinfo %}{{userinfo.province_name ? userinfo.province_name : '-'}}{{userinfo.city_name ? userinfo.city_name : '-'}}{{userinfo.district_name ? userinfo.district_name : '-'}}{{userinfo.town_name ? userinfo.town_name : '-'}}{{userinfo.address ? userinfo.address : '-'}}{% else %}-{% endif %}</td>
                {% if userinfo and userinfo.credit_type==16%}
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
                    {% if userinfo and userinfo.credit_type==2 %}
                    <th width="15%">服务区域</th>
                    <td width="35%">{% if userarea %}{{userarea.province_name ? userarea.province_name : '-'}}{{userarea.city_name ? userarea.city_name : '-'}}{{userarea.district_name ? userarea.district_name : '-'}}{{userarea.village_name ? userarea.village_name : '-'}}
                    {% if userinfo.credit_type==2 %}{{userarea.town_name ? userarea.town_name : '-'}}{% endif %}{% else %}-{% endif %}
                    </td>
                    {% else %}
                    <th><td></td></th>
                    {% endif %}

                {% endif %}
            </tr>
        {% if userinfo and userinfo.credit_type==2 %}
                    <tr height="30">
                        <th width="15%">上级SE</th>
                        <td width="35%">{% if se %}{{se['f_name']}}{% endif %}</td>
                        <th width="15%"></th>
                        <td width="35%"></td>
                    </tr>
        {% endif %}
        </table>
    </div>
{% if userinfo and userinfo.type==1%}
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
                    {{userfarm.town_name ? userfarm.town_name : '-'}}{% else %}-{% endif %}</td>
            </tr>
            <tr height="30">
                <th width="15%">农场面积</th>
                <td width="35%">{% if userfarm %}{{userfarm.farm_area ? userfarm.farm_area : '-'}}亩{% else %}-{% endif %}</td>
                <th width="15%">种植作物</th>
                <td width="35%">{% for v in userfarmcrops%}{{v['category_name']}},{% endfor %}</td>
            </tr>
            <tr height="30">
                <th width="15%">土地来源</th>
                <td width="35%">{% if userfarm %}{% if userfarm.source==0%}自有{% else %}流转{% endif %}{% else %}-{% endif %}</td>
                <th width="15%">土地使用年限</th>
                <td width="35%">
                    {% if userfarm %}{{userfarm.year ? userfarm.year : '-'}}年{% else %}
                0{% endif %}

                {% if userfarm %}{{userfarm.month ? userfarm.month : '-'}}月{% else %}0{% endif %}
                </td>
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
                <th width="15%"></th>
                <td width="35%"></td>
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
            <tr>
                <td class="cx_title">银行卡证明照：</td>
                <td class="cx_content">
                    {% if userbank %}
                    <img src="http://yncmdg.b0.upaiyun.com/{{userbank.bankcard_picture ? userbank.bankcard_picture : '/upload/bank/2015/06/14/118001784991.jpg'}}" alt="银行卡正面照" width="150px" height="100px">
                    {% else %}
                    <img src="http://static.ync365.com/mdg/images/detial_b_img.jpg" alt="银行卡正面照" width="150px" height="100px">
                    {% endif %} 
                </td>
            </tr>
        
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
{% endif %}
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

<script type="text/javascript">

        $('#button_id').click(function() {
            var a=$("#result-box_0").html();
            $("#fuzhila").html(a);
            $('.vip-layer').hide();
            $('.vip-box').hide();
        });



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
<link rel="stylesheet" href="http://js.static.ync365.com/zTree/css/zTreeStyle/zTreeStyle.css" type="text/css">
<script type="text/javascript" src="http://js.static.ync365.com/zTree/js/jquery.ztree.core-3.5.min.js"></script>
<script type="text/javascript" src="http://js.static.ync365.com/zTree/js/jquery.ztree.excheck-3.5.min.js"></script>

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
$("#myarticle").validator({
     fields:  {
         title:"required;",
         keywords:"required;",
         description:"required;",
         tags:"required;"
     },
    
});
</script>
<div class="footer"> Copyright © 2013-2014 ync365.com All rights reserved. </div>
</body>
</html>

</body>
</html>

