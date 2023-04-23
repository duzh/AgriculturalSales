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
    <div class="bt2"><?php if (isset($userinfo) && $userinfo){echo Mdg\Models\Users::$_credit_id[$userinfo->credit_type];}?>编辑</div>
{{ form("corporate/hcsave", "method":"post", "autocomplete" : "off","id":'myarticle') }}
	{% if userinfo and userinfo.credit_type==2 or userinfo.credit_type==4 %}
 <div class="chaxun vip-list">
        <div class="title">权限信息</div>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr height="30">
                <th width="15%">查看负责区域内用户权限：</th>
                <td width="35%"><input type="checkbox" name="usercheck" value="1" {% if privilege_taginfo and privilege_taginfo['one']>0 %}checked{% endif %}>用户信息
                	<input type="checkbox" name="procurementcheck" value="2" {% if privilege_taginfo and privilege_taginfo['two']>0 %}checked{% endif %}>采购信息
                	<input type="checkbox" name="supplycheck" value="4" {% if privilege_taginfo and privilege_taginfo['three']>0 %}checked{% endif %}>供应信息
                	<input type="checkbox" name="ordercheck" value='8' {% if privilege_taginfo and privilege_taginfo['four']>0 %}checked{% endif %}>订单信息
                </td>
                <th width="15%"></th>
                <td width="35%">
                </td>
            </tr>
           
        </table>
    </div>
  {% endif %}
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
                <td width="35%">
                    <input type="text" name="infophone" value="{% if userinfo %}{{userinfo.phone ? userinfo.phone : '-'}}{% else %}-{% endif %}" />
                </td>
            </tr>
            <tr height="30">
                <th width="15%">企业法人</th>
                <td width="35%">{% if userinfo %}{{userinfo.enterprise_legal_person ? userinfo.enterprise_legal_person : '-'}}{% else %}-{% endif %}</td>
                <th width="15%">身份证号</th>
                <td width="35%">{% if userinfo %}{{userinfo.certificate_no ? userinfo.certificate_no : '-'}}{% else %}-{% endif %}</td>
            </tr>

 			<tr height="30">
                <th width="15%">{% if userinfo.type==1 %}公司地址{% else %}所在地址{% endif %}</th>
                <td width="35%">
                	<select name="start_pida" class='selectAreasa' id="">
                        <option value="">请选择</option>
                      </select>
                      <select name="start_cida" class='selectAreasa' id="">
                        <option value="">请选择</option>
                      </select>
                      <select name="start_dida" class='selectAreasa' id="">
                        <option value="">请选择</option>
                      </select>
                      <select name="start_eida" class='selectAreasa' id="">
                        <option value="">请选择</option>
                      </select>
                  <input type="text" name="infoaddress" value="{% if userinfo %}{{userinfo.address ? userinfo.address : '-'}}{% else %}-{% endif %}" />
                </td>
				<th width="15%">服务区域</th>
	               <td width="35%">
                      <select name="userarea_province" class='userarea' id="">
                        <option value="">请选择</option>
                      </select>
                      <select name="userarea_city" class='userarea' id="">
                        <option value="">请选择</option>
                      </select>

                      <select name="userarea_district" class='userarea' id="">
                        <option value="">请选择</option>
                      </select>
                      <select name="userarea_town" class='userarea' id="">
                        <option value="">请选择</option>
                      </select>
	                </td>
            </tr>
        </table>
    </div>
{% if userinfo and userinfo.type==1%}
    <div class="chaxun vip-list">
        <div class="title">联系人信息</div>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr height="30">
                <th width="15%">姓名</th>
                <td width="35%">
                    <input type="text" name="contactname" value="{% if usercontact %}{{usercontact.name ? usercontact.name : '-'}}{% else %}-{% endif %}" />
                </td>
                <th width="15%">手机号</th>
                <td width="35%">
                    <input type="text" name="contactphone" value="{% if usercontact %}{{usercontact.phone ? usercontact.phone : '-'}}{% else %}-{% endif %}" />
                </td>
            </tr>
            <tr height="30">
                <th width="15%">传真号</th>
                <td width="35%">
                    <input type="text" name="contactfax" value="{% if usercontact %}{{usercontact.fax ? usercontact.fax : '-'}}{% else %}-{% endif %}" />
                </td>
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
                <td width="35%">
                	<select name="bank_name" id="">
                		{% for v in bank %}
                		<option value="{{v['gate_id']}}" 
                		{% if userbank %}{% if userbank.bank_name==v['gate_id']%}selected{% endif %}{% endif %}>{{v['bank_name']}}</option>
                		{% endfor %}
                	</select>
                    
                </td>
                <th width="15%">开户行所在地</th>
                <td width="35%">
                	<select name="start_pid" class='selectAreas' id="">
                        <option value="">请选择</option>
                      </select>
                      <select name="start_cid" class='selectAreas' id="">
                        <option value="">请选择</option>
                      </select>
                      <select name="start_did" class='selectAreas' id="">
                        <option value="">请选择</option>
                      </select>
                </td>
            </tr>
            <tr height="30">
                <th width="15%">开户公司名称</th>
                <td width="35%">
                    <input type="text" name="bank_account" value="{% if userbank %}{{userbank.bank_account ? userbank.bank_account : '-'}}{% else %}-{% endif %}" />
                <th width="15%">卡号</th>
                <td width="35%">
                    <input type="text" name="bank_cardno" value="{% if userbank %}{{userbank.bank_cardno ? userbank.bank_cardno : '-'}}{% else %}-{% endif %}" />
                </td>
            </tr>
        </table>
    </div>
    <div class="chaxun vip-list">
        <div class="title">照片信息</div>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border-none">
            <tr>
                <td class="cx_title">银行卡证明照：</td>
                <td class="cx_content" >
                	<div id ="ent_show_bankcard_picture">
                    {% if userbank %}
                	<img src="http://yncmdg.b0.upaiyun.com/{{userbank.bankcard_picture ? userbank.bankcard_picture : '/upload/bank/2015/06/14/118001784991.jpg'}}" alt="银行卡正面照" width="150px" height="100px" id="ent_show_bankcard_picture">
                	{% else %}
                    <img src="http://yncmdg.b0.upaiyun.com//upload/bank/2015/06/14/118001784991.jpg  " alt="银行卡正面照" width="150px" height="100px" >
                    {% endif %} 
                </div>
                     <div class="btn" style="text-align:left; padding:0; margin:0 0 20px;">
                        <input type="file" value="重新上传" class="sub" style="width:121px; height:31px; border:none;" id="ent_bankcard_picture">
                     </div>
                </td>
            </tr>
            <tr>
                <td class="cx_title">身份证照：</td>
                <td class="cx_content">
                	<div id="ent_show_identity_picture_lic">
					{% if userbank %}
                	<img src="http://yncmdg.b0.upaiyun.com/{{userbank.idcard_picture ? userbank.idcard_picture : '/upload/identitycardfront/2015/06/14/647964228109.jpg'}}" alt="银行卡正面照" width="150px" height="100px">
                	{% else %}
                    <img src="http://yncmdg.b0.upaiyun.com//upload/identitycardfront/2015/06/14/647964228109.jpg " alt="银行卡正面照" width="150px" height="100px">
                    {% endif %} 
                </div>
<!--                      <div class="btn" style="text-align:left; padding:0; margin:0 0 20px;">
                        <input type="file" value="重新上传" class="sub" style="width:121px; height:31px; border:none;" id="ent_identity_picture_lic">
                     </div> -->
                </td>
            </tr>
  <tr>
                <td class="cx_title">身份背面证照：</td>
                <td class="cx_content">
                    <div id="ent_show_identity_card_back">
                    {% if userbank %}
                    <img src="http://yncmdg.b0.upaiyun.com/{{userbank.idcard_picture_back ? userbank.idcard_picture_back : '/upload/identitycardfront/2015/06/14/647964228109.jpg'}}" alt="银行卡正面照" width="150px" height="100px">
                    {% else %}
                    <img src="http://yncmdg.b0.upaiyun.com//upload/identitycardfront/2015/06/14/647964228109.jpg " alt="银行卡正面照" width="150px" height="100px">
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
                	<div id="ent_show_idcard_picture">
					{% if userbank %}
                	<img src="http://yncmdg.b0.upaiyun.com/{{userbank.person_picture ? userbank.person_picture : '/upload/identitycardfront/2015/06/14/647964228109.jpg'}}" alt="银行卡正面照" width="150px" height="100px">
                	{% else %}
                    <img src="http://yncmdg.b0.upaiyun.com//upload/identitycardfront/2015/06/14/647964228109.jpg " alt="银行卡正面照" width="150px" height="100px">
                    {% endif %} 
                    </div>
<!--                      <div class="btn" style="text-align:left; padding:0; margin:0 0 20px;">
                        <input type="file" value="重新上传" class="sub" style="width:121px; height:31px; border:none;" id="ent_idcard_picture">
                     </div> -->
                </td>
            </tr>

            {% endif %}
            {% if userinfo and userinfo.type>0 %}
            <tr>
                <td class="cx_title">个体工商营业执照：</td>
                <td class="cx_content">
                	<div id="ent_show_idcard_picture">
					{% if userbank %}
                	<img src="http://yncmdg.b0.upaiyun.com/{{userbank.identity_picture_lic ? userbank.identity_picture_lic : '/upload/identitypicturelicPath/2015/06/14/828668888408.jpg'}}" alt="银行卡正面照" width="150px" height="100px">
                	{% else %}
                    <img src="http://yncmdg.b0.upaiyun.com//upload/identitypicturelicPath/2015/06/14/828668888408.jpg " alt="银行卡正面照" width="150px" height="100px">
                    {% endif %} 
                    </div>
<!--                      <div class="btn" style="text-align:left; padding:0; margin:0 0 20px;">
                        <input type="file" value="重新上传" class="sub" style="width:121px; height:31px; border:none;" id="ent_idcard_picture">
                     </div> -->
                </td>
            </tr>
            {% endif %}





        </table>
    </div>

	<div class="btn">
		<input type="hidden" name='category_name_text_0' id='category_name_text_0' value="{% if category_name_id %}{{category_name_id}}{% endif %}">
		<input type="hidden" name="info_id" value="{% if userinfo %}{{userinfo.credit_id}}{% endif %}">
		<input type="hidden" name="user_id" value="{% if userinfo %}{{userinfo.user_id}}{% endif %}">
		<input type="hidden" name="info_type" value="{% if userinfo %}<?=Mdg\Models\Users::$_credit_id[$userinfo->credit_type];?>{% else %}hc{% endif %}">
        <input type="hidden" value="{{userinfo.credit_id}}" name='user_credit_id'>
        
        <input type="submit" value="保存" class="sub">              
    </div>
</form>
</div>

<!-- 采购修改 弹层 -->
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
$(function(){
$(".selectAreas").ld({ajaxOptions : {"url" : "/ajax/getareasfull"},
    defaultParentId : 0,
    {% if areainfo %}
    texts:[{{ areainfo}}],
    {% endif %}
    style : {"width" : 140}
});
$(".selectAreasa").ld({ajaxOptions : {"url" : "/ajax/getareasfull"},
    defaultParentId : 0,
    <?php if(isset($infoarea)){?>
    texts:[{{ infoarea}}],
    <?php }?>
    style : {"width" : 140}
});
$(".userarea").ld({ajaxOptions : {"url" : "/ajax/getareasfull"},
    defaultParentId : 0,
    {% if userareainfo %}
    texts:[{{ userareainfo}}],
    {% endif %}
    style : {"width" : 140}
});
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
bankImg($('#ent_bankcard_picture'), 29, $('#ent_show_bankcard_picture'), 'html');
bankImg($('#ent_identity_picture_lic'), 33, $('#ent_show_identity_picture_lic'), 'html');
bankImg($('#ent_idcard_picture'), 31, $('#ent_show_idcard_picture'), 'html');
bankImg($('#ent_identity_card_back'), 34, $('#ent_show_identity_card_back'), 'html');

$("#myarticle").validator({
     fields:  {
         userarea_town:"required;remote[/manage/corporate/checkAreaVillage, user_credit_id]",
     },
    
});
</script>
<div class="footer"> Copyright © 2013-2014 ync365.com All rights reserved. </div>
</body>
</html>

</body>
</html>

