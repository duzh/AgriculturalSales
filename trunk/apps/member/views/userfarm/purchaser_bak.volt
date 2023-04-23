<!--头部 start-->
{{ partial('layouts/page_header') }}
<!--头部 end-->
<link rel="stylesheet" type="text/css" href="http://yncstatic.b0.upaiyun.com/js/validator/jquery.validator.css" />
<!--主体 start-->
<div class="center-wrapper pb30">
	<div class="bread-crumbs w1185 mtauto">
		<a href="/">首页</a>
		>
		<a href="/member">个人中心</a>
		> 采购商
	</div>
	<div class="w1185 mtauto clearfix">
		<!-- 左侧 start-->
		{{ partial('layouts/navs_left') }}
		<!-- 左侧 end-->

		<form action="/member/userfarm/purchasersave" method="post" id='userForm'>
			<!-- 右侧 -->
			<div class="center-right f-fr">

				<div class="form-attest">
					<div class="title">申请表单</div>
					<div class="box">

						<div class="m-title">基本信息</div>
						<!--<div class="message clearfix"> <font>您的身份</font>-->
							<!--<div class="radio-box buyer-input">-->
								<!--<label>-->
									<!--<input type="radio" name='member_type'  value='0' checked > <em>采购经纪人</em>-->
								<!--</label>-->
								<!--<label>-->
									<!--<input type="radio" name='member_type' value='1' > <em>采购企业</em>-->
								<!--</label>-->
							<!--</div>-->
						<!--</div>-->
						<!--校验是否是增加和修改-->
						<input type="hidden" name='flag'  value='{% if credit_id %}1{% else %}0{% endif %}'>
                        <input type="hidden" name='credit_id'  value='{% if credit_id %}{{ credit_id }}{% else %}0{% endif %}'>
                        <input type="hidden" name='member_type'  value='{% if ptype == 1%}0{% else %}1{% endif %}'>
						<!-- 采购商 个人 -->
						<div class="buyer-common" id="buyer_comm" style="display:block;">

							<div class="message clearfix"> <font>姓名</font>
								<div class="input-box">
									<input type="text" name='user_name' value='{{ userinfo['name'] }}' data-rule="required;chinese;" />

								</div>
							</div>
							<div class="message clearfix">
								<font>身份证号</font>
								<div class="input-box">
									<input type="text" name='user_certificate_no' value="{{ userinfo['certificate_no'] }}"  data-rule="required;ID_card;" />

								</div>
							</div>
							<div class="message clearfix">
								<font>手机号</font>
								<div class="input-box">
									<input type="text" name='user_mobile' value='{{ userinfo['phone'] }}' data-rule="required;mobile;" />
								</div>
							</div>
							<div class="message clearfix">
								<font>所在区域</font>
								<div class="select-box">
									<select name='user_province_id' class='selectAreas'>
										<option value=''>请选择</option>
									</select>
									<select name='user_city_id' class='selectAreas'>
										<option value=''>请选择</option>
									</select>
									<select name='user_district_id'  class='selectAreas'>
										<option value=''>请选择</option>
									</select>
									<select  name='user_town_id' class='selectAreas' data-rule="required;">
										<option value=''>请选择</option>
									</select>
									 <br>
									<input class="f-db" type="text" name='user_address' value="{{ userinfo['address'] }}" />
								</div>
							</div>
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
									<div class="result-box f-fl" id='result-box_0'></div>
									

									<input name='category_name_text_0' id="category_name_text_0" style="width:1px;opacity:0;filter:alpha(opacity:0);" type="text" value=""  data-rule="required;"  data-target="#category_name_text_0_tip" />
									<i id='category_name_text_0_tip'></i>

								</div>
							</div>
							<div class="m-title mt20">认证信息</div>
							<div class="message clearfix">
								<font>银行卡开户行</font>
								<div class="select-box lang-select">
									<select name='user_bank_name' data-rule="required;" >
										<option value=''>请选择</option>
										{% for key , item in  bankList %}
										<option value='{{ item['gate_id']}}' {% if ( item['gate_id'] == userbank['bank_name'] ) %} selected  {% endif %}  >{{ item['bank_name']}}</option>
										{% endfor %}
									</select>
								</div>
							</div>
							<div class="message clearfix">
								<font>开户行所在地</font>
								<div class="select-box lang-select">
									<select name='user_bank_province_id' class='class_bank_address'>
										<option value=''>请选择</option>
									</select>
									<select name='user_bank_city_id'  class='class_bank_address' >
										<option value=''>请选择</option>
									</select>
									<select name='user_bank_district_id'  class='class_bank_address' data-rule="required;" >
										<option value=''>请选择</option>
									</select>
								</div>
							</div>
							<div class="message clearfix">
								<font>开户名</font>
								<div class="input-box">
									<input type="text" name='user_bank_account' data-rule="required;chinese;" value="{{userbank['bank_account']}}"  />
								</div>
							</div>
							<div class="message clearfix">
								<font>卡号</font>
								<div class="input-box">
									<input type="text"  name='user_bank_cardno' data-rule="required;mark;"  value="{{userbank['bank_cardno']}}" />
								</div>
							</div>
							<!--<div class="message clearfix">
								<font>银行卡证明照</font>
								<div class="loadImg-box">
									<div class="imgs" id='user_show_bankcard_picture'>
										</div>
									<div class="file-btn">
										
										<input class="btn2" type="file"  id='user_bankcard_picture'  />
										<input id="user_bankcard_picture_hide" style="width:1px;opacity:0;filter:alpha(opacity:0);" type="text" value=""  data-rule="required;"  data-target="#user_bankcard_picture_tip" />
                                        <i id='user_bankcard_picture_tip' style="position:absolute; left:176px; top:-6px;"></i>

										<a target="_blank" href="{{ constant('STATIC_URL')}}/mdg/images/lodeImg_img1.jpg">查看样照</a>
									</div>
								</div>
							</div>
							<div class="message clearfix">
								<font>个人手持身份证照片</font>
								<div class="loadImg-box">
									<div class="imgs" id='user_show_person_picture'>
										</div>
									<div class="file-btn">
										
										<input class="btn2" type="file" id='user_person_picture' />
										<input id="user_person_picture_hide" style="width:1px;opacity:0;filter:alpha(opacity:0);" type="text" value=""  data-rule="required;"  data-target="#user_person_picture_tip" />
                                        <i id='user_person_picture_tip' style="position:absolute; left:176px; top:-6px;"></i>

										<a target="_blank" href="{{ constant('STATIC_URL')}}/mdg/images/lodeImg_img2.jpg">查看样照</a>
									</div>
								</div>
							</div>-->
							<div class="message clearfix">
								<font>身份证照</font>
								<div class="loadImg-box">
									<div class="imgs" id='user_show_idcard_picture'>
                                        {% if ( userbank['idcard_picture'] ) %}
                                        <img style="width: 119px;height: 144px" src="{{ constant('IMG_URL')}}{{userbank['idcard_picture']}}" />
                                        {% endif %}
										</div>
									<div class="file-btn">
										
										<input class="btn2" type="file" id='user_idcard_picture'  />
										<input id="user_idcard_picture_hide" style="width:1px;opacity:0;filter:alpha(opacity:0);" type="text" value=""  data-rule="required;"  data-target="#user_idcard_picture_tip" />
                                        <i id='user_idcard_picture_tip' style="position:absolute; left:176px; top:-6px;"></i>

										<a target="_blank" href="{{ constant('STATIC_URL')}}/mdg/images/lodeImg_img4.jpg">查看样照</a>
									</div>
								</div>
							</div>
							<div class="message clearfix">
								<font>身份证背面照</font>
								<div class="loadImg-box">
									<div class="imgs" id='user_show_idcard_picture_back'>
                                        {% if ( userbank['idcard_picture_back'] ) %}
                                        <img style="width: 119px;height: 144px" src="{{ constant('IMG_URL')}}{{userbank['idcard_picture_back']}}" />
                                        {% endif %}
										</div>
									<div class="file-btn">
										
										<input class="btn2" type="file" id='user_idcard_picture_back' name="user_idcard_picture_back" />
										<input id="user_idcard_picture_back_hide" style="width:1px;opacity:0;filter:alpha(opacity:0);" type="text" value=""  data-rule="required;"  data-target="#user_idcard_picture_back_tip" />
                                        <i id='user_idcard_picture_back_tip' style="position:absolute; left:176px; top:-6px;"></i>

										<a target="_blank" href="{{ constant('STATIC_URL')}}/mdg/images/lodeImg_img4.jpg">查看样照</a>
									</div>
								</div>
							</div>
                            
                           
                            <div class="message clearfix" style="margin-top: 20px;" >
                                <font>推荐人</font>
                                <div class="input-box">
                                    <input type="text" name='seusername'  data-rule="mobile" value="{{ userinfo['se_mobile']}}"/>
                                    
                                </div>
                                <div style="margin-top: 5px;">（选填）（可以是县域服务中心或责任服务工程师）</div>
                            </div>

						</div>

						<!-- 采购商 企业 -->
						<div class="buyer-common" id="company_comm">

							<div class="message clearfix">
								<font>公司名称</font>
								<div class="input-box">
									<input type="text" name='ent_company_name' data-rule="required;" value="{{ userinfo['company_name']}}" />
								</div>
							</div>
							<div class="message clearfix">
								<font>注册登记证号</font>
								<div class="input-box">
									<input type="text" name='ent_register_no' data-rule="required;wxxx" value="{{ userinfo['register_no']}}"  data-rule="required;wxxx"  data-rule-wxxx="[/^[0-9a-zA-Z]*$/g, '只能输入字母数字']" />

								</div>
							</div>
							<div class="message clearfix">
								<font>公司地址</font>
								<div class="select-box">
									<select name='province_id' class='ent_selectAreas'>
										<option value=''>请选择</option>
									</select>
									<select name='city_id' class='ent_selectAreas'>
										<option value=''>请选择</option>
									</select>
									<select name='district_id'  class='ent_selectAreas'>
										<option value=''>请选择</option>
									</select>
									<select  name='town_id' class='ent_selectAreas' data-rule="required;">
										<option value=''>请选择</option>
									</select>
									<br>
									<input class="f-db" type="text" name='ent_address' value="{{ userinfo['address']}}" />
								</div>
							</div>
							<div class="message clearfix">
								<font>企业法人</font>
								<div class="input-box">
									<input type="text" name='ent_erprise_legal_person' data-rule="required;" value="{{ userinfo['enterprise_legal_person']}}"  />
								</div>
							</div>
							<div class="message clearfix">
								<font>身份证号</font>
								<div class="input-box">
									<input type="text" name='ent_certificate_no' data-rule="required;ID_card;" value="{{ userinfo['certificate_no']}}"  />
								</div>
							</div>
							<div class="message clearfix">
								<font>采购类别</font>
								<div class="select-box lang-select clearfix categrey-option">
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

									<input id="category_name_text_1" name='category_name_text_1' style="width:1px;opacity:0;filter:alpha(opacity:0);" type="text" value=""  data-rule="required;"  data-target="#category_name_text_1_tip" />
									<i id='category_name_text_1_tip'></i>
								</div>
							</div>
							<div class="m-title mt20">联系人信息</div>
							<div class="message clearfix">
								<font>姓名</font>
								<div class="input-box">
									<input type="text" name='ent_contact_name' data-rule="required;chinese;" value="{{usercontact['name']}}"  />

								</div>
							</div>
							<div class="message clearfix">
								<font>手机号</font>
								<div class="input-box">
									<input type="text" name='ent_contact_phone'  data-rule="required;mobile;" value="{{usercontact['phone']}}" />
								</div>
							</div>
							<div class="message clearfix">
								<font>传真</font>
								<div class="input-box">
									<input type="text"  name='ent_contact_fax'  value="{{usercontact['fax']}}"/> <i>（没有可不填）</i>
								</div>
							</div>
							<div class="m-title mt20">认证信息</div>
							<div class="message clearfix">
								<font>银行卡开户行</font>
								<div class="select-box lang-select">
									<select name='ent_bank_name' data-rule="required ;" >
										<option value=''>请选择</option>
										{% for key , item in  bankList %}
										<option value='{{ item['gate_id']}}' {% if ( item['gate_id'] == userbank['bank_name'] ) %} selected  {% endif %} >{{ item['bank_name']}}</option>
										{% endfor %}
									</select>
								</div>
							</div>
							<div class="message clearfix">
								<font>开户行所在地</font>
								<div class="select-box lang-select">
									<select name='ent_bank_province_id' class='ent_class_bank_address'>
										<option value=''>请选择</option>
									</select>
									<select name='ent_bank_city_id'  class='ent_class_bank_address' >
										<option value=''>请选择</option>
									</select>
									<select name='ent_bank_district_id'  class='ent_class_bank_address' data-rule="required;"  >
										<option value=''>请选择</option>
									</select>
								</div>
							</div>
							<div class="message clearfix">
								<font>开户公司名称</font>
								<div class="input-box">
									<input type="text" name='ent_bank_account' data-rule="required;chinese;" value="{{userbank['bank_account']}}" />

								</div>
							</div>
							<div class="message clearfix">
								<font>卡号</font>
								<div class="input-box">
									<input type="text"  name='ent_bank_cardno' data-rule="required;mark;" value="{{userbank['bank_cardno']}}" />

								</div>
							</div>
							<!--<div class="message clearfix">
								<font>银行卡证明照</font>
								<div class="loadImg-box">
									<div class="imgs" id='ent_show_bankcard_picture'>
										</div>
									<div class="file-btn">
									
										<input class="btn2" type="file" id='ent_bankcard_picture' />
										<input id="ent_bankcard_picture_hide" style="width:1px;opacity:0;filter:alpha(opacity:0);" type="text" value=""  data-rule="required;"  data-target="#ent_bankcard_picture_tip" />
                                        <i id='ent_bankcard_picture_tip' style="position:absolute; left:176px; top:-6px;"></i>

										<a target="_blank" href="{{ constant('STATIC_URL')}}/mdg/images/lodeImg_img1.jpg">查看样照</a>
									</div>
								</div>
							</div>-->
							<div class="message clearfix">
								<font>个体工商营业执照</font>
								<div class="loadImg-box">
									<div class="imgs" id='ent_show_identity_picture_lic'>
                                        {% if ( userbank['identity_picture_lic'] ) %}
                                        <img style="width: 119px;height: 144px" src="{{ constant('IMG_URL')}}{{userbank['identity_picture_lic']}}" />
                                        {% endif %}
										</div>
									<div class="file-btn">
									
										<input class="btn2" type="file" id='ent_identity_picture_lic' />
										<input id="ent_identity_picture_lic_hide" style="width:1px;opacity:0;filter:alpha(opacity:0);" type="text" value=""  data-rule="required;"  data-target="#ent_identity_picture_lic_tip" />
                                        <i id='ent_identity_picture_lic_tip' style="position:absolute; left:176px; top:-6px;"></i>

										<a target="_blank" href="{{ constant('STATIC_URL')}}/mdg/images/lodeImg_img4.jpg">查看样照</a>
									</div>
								</div>
							</div>
                            
                            <div class="message clearfix">
								<font>组织机构代码证</font>
								<div class="loadImg-box">
									<div class="imgs" id='ent_show_identity_picture_org'>
                                        {% if ( userbank['identity_picture_org'] ) %}
                                        <img style="width: 119px;height: 144px" src="{{ constant('IMG_URL')}}{{userbank['identity_picture_org']}}" />
                                        {% endif %}
                                    </div>
									<div class="file-btn">
    									<input class="btn2" type="file" id='ent_identity_picture_org' name="ent_identity_picture_org"/>
										<input id="ent_identity_picture_org_hide" style="width:1px;opacity:0;filter:alpha(opacity:0);" type="text" value="" data-rule="required;"  data-target="#ent_identity_picture_org_tip" />
                                        <i id='ent_identity_picture_org_tip' style="position:absolute; left:176px; top:-6px;"></i>

										<a target="_blank" href="{{ constant('STATIC_URL')}}/mdg/images/lodeImg_img4.jpg">查看样照</a>
									</div>
								</div>
							</div>
                            
                            <div class="message clearfix">
								<font>税务登记证（选填）</font>
								<div class="loadImg-box">
									<div class="imgs" id='ent_show_identity_picture_tax'>
                                        {% if ( userbank['identity_picture_tax'] ) %}
                                        <img style="width: 119px;height: 144px" src="{{ constant('IMG_URL')}}{{userbank['identity_picture_tax']}}" />
                                        {% endif %}
                                    </div>
									<div class="file-btn">
    									<input class="btn2" type="file" id='ent_identity_picture_tax' name="ent_identity_picture_tax"/>
										<input id="ent_identity_picture_tax_hide" style="width:1px;opacity:0;filter:alpha(opacity:0);" type="text" value=""  data-target="#ent_identity_picture_tax_tip" />
                                        <i id='ent_identity_picture_tax_tip' style="position:absolute; left:176px; top:-6px;"></i>

										<a target="_blank" href="{{ constant('STATIC_URL')}}/mdg/images/lodeImg_img4.jpg">查看样照</a>
									</div>
								</div>
							</div>
                            
							<div class="message clearfix">
								<font>身份证照</font>
								<div class="loadImg-box">
									<div class="imgs" id='ent_show_idcard_picture'>
                                        {% if ( userbank['idcard_picture'] ) %}
                                        <img style="width: 119px;height: 144px" src="{{ constant('IMG_URL')}}{{userbank['idcard_picture']}}" />
                                        {% endif %}
										</div>
									<div class="file-btn">
										
										<input class="btn2" type="file" id='ent_idcard_picture'  />
										<input id="ent_idcard_picture_hide" style="width:1px;opacity:0;filter:alpha(opacity:0);" type="text" value=""  data-rule="required;"  data-target="#ent_idcard_picture_tip" />
                                        <i id='ent_idcard_picture_tip' style="position:absolute; left:176px; top:-6px;"></i>

										<a target="_blank" href="{{ constant('STATIC_URL')}}/mdg/images/lodeImg_img3.jpg">查看样照</a>
									</div>
								</div>
							</div>
							<div class="message clearfix">
								<font>身份证背面照</font>
								<div class="loadImg-box">
									<div class="imgs" id='ent_show_idcard_picture_back'>
                                        {% if ( userbank['idcard_picture_back'] ) %}
                                        <img style="width: 119px;height: 144px" src="{{ constant('IMG_URL')}}{{userbank['idcard_picture_back']}}" />
                                        {% endif %}
										</div>
									<div class="file-btn">
										
										<input class="btn2" type="file" id='ent_idcard_picture_back'  />
										<input id="ent_idcard_picture_back_hide" style="width:1px;opacity:0;filter:alpha(opacity:0);" type="text" value=""  data-rule="required;"  data-target="#ent_idcard_picture_back_tip" />
                                        <i id='ent_idcard_picture_back_tip' style="position:absolute; left:176px; top:-6px;"></i>

										<a target="_blank" href="{{ constant('STATIC_URL')}}/mdg/images/lodeImg_img3.jpg">查看样照</a>
									</div>
								</div>
							</div>
                            
                            
                            <div class="message clearfix">
                                <font>推荐人</font>
                                <div class="input-box">
                                    <input type="text" name='ent_seusername'  data-rule="mobile" value="{{ userinfo['se_mobile']}}"/>
                                   
                                </div>
                                <div style="margin-top: 5px;">（选填）（可以是县域服务中心或责任服务工程师）</div>
                            </div>
                            
						</div>

					</div>
					<input class="buyer-btn" type="submit" value="提交申请" />
				</div>

			</div>
		</form>
	</div>
</div>


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
		"width": 102
	}
});   
</script>

<style>
.upload_btn {width:89px; height:25px; border: solid 1px #99be20; color:#99be20; background: #fff; text-align: center; line-height:25px;
  font-family: '微软雅黑';
  cursor: pointer;
  position: relative;}
.edui-default .edui-editor{ margin:10px auto;}
</style>