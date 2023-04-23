<!--头部 start-->
{{ partial('layouts/page_header') }}
<!--头部 end-->

<!--主体 start-->
<div class="center-wrapper pb30">
	<div class="bread-crumbs w1185 mtauto">
		<a href="/">首页</a>
		>
		<a href="/member">个人中心</a>
		> 县域服务中心
	</div>
	<div class="w1185 mtauto clearfix">
		<!-- 左侧 start-->
		{{ partial('layouts/navs_left') }}
		<!-- 左侧 end-->
		<form action="/member/userfarm/countysave" method="post" id='userForm'>

			<!-- 右侧 -->
			<div class="center-right f-fr">

				<div class="form-attest">
					<div class="title">申请表单</div>
					<div class="box">

						<div class="m-title">基本信息</div>
						<div class="message clearfix"> <font>您的身份</font>
							<div class="radio-box buyer-input">
								<label>
									<input type="radio" name='member_type'  value='0' checked > <em>个体户</em>
								</label>
								<label>
									<input type="radio" name='member_type' value='1' > <em>企业</em>
								</label>
							</div>
						</div>
						<!-- 村站 个人 -->
						<div class="buyer-common" style="display:block;" >

							<div class="message clearfix"> <font>姓名</font>
								<div class="input-box">
									<input type="text" name='user_name' value=''  data-rule="required;chinese" />

								</div>
							</div>
							<div class="message clearfix">
								<font>身份证号</font>
								<div class="input-box">
									<input type="text" name='user_credit_no' value='' data-rule="required;ID_card"  />

								</div>
							</div>
							<div class="message clearfix">
								<font>手机号</font>
								<div class="input-box">
									<input type="text" name='user_mobile'  value='' data-rule="required;mobile" />

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
									<select  name='user_town_id' class='selectAreas' data-rule="required;" >
										<option value=''>请选择</option>
									</select>
									<br>
									<input class="f-db" type="text" name='user_address' />
								</div>
							</div>
							<!--<div class="message clearfix">
								<font>申请负责区域</font>
								<div class="select-box">
									<select name='user_area_province_id' class='user_area_selectAreas'>
										<option value=''>请选择</option>
									</select>
									<select name='user_area_city_id' class='user_area_selectAreas'>
										<option value=''>请选择</option>
									</select>
									<select name='user_area_district_id'  class='user_area_selectAreas' data-rule="required;remote[/member/userfarm/checkUserFarmArea]" >
										<option value=''>请选择</option>
									</select>

								</div>
							</div>-->
							<div class="m-title mt20">认证信息</div>
							<div class="message clearfix">
								<font>银行卡开户行</font>
								<div class="select-box lang-select">
									<select name='user_bank_name'  data-rule="required;" >
										<option value=''>请选择</option>
										{% for key , item in  bankList %}
										<option value='{{ item['gate_id']}}'>{{ item['bank_name']}}</option>
										{% endfor %}
									</select>
								</div>
							</div>
							<div class="message clearfix">
								<font>开户行所在地</font>
								<div class="select-box lang-select">
									<select name='user_bank_province_id' class='ent_class_bank_address'>
										<option value=''>请选择</option>
									</select>
									<select name='user_bank_city_id'  class='ent_class_bank_address' >
										<option value=''>请选择</option>
									</select>
									<select name='user_bank_district_id'  class='ent_class_bank_address'  data-rule="required;">
										<option value=''>请选择</option>
									</select>
								</div>
							</div>
							<div class="message clearfix">
								<font>开户名</font>
								<div class="input-box">
									<input type="text" name='user_bank_account'  data-rule="required;chinese" />
								</div>
							</div>
							<div class="message clearfix">
								<font>卡号</font>
								<div class="input-box">
									<input type="text"  name='user_bank_cardno' data-rule="required;mark" />
								</div>
							</div>
							<!--<div class="message clearfix">
								<font>银行卡证明照</font>
								<div class="loadImg-box">
									<div class="imgs" id='user_show_bankcard_picture'>
										</div>

									<div class="file-btn">
										
										<input class="btn2" type="file" name='user_bankcard_picture' id='user_bankcard_picture' />
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
										
										<input class="btn2" type="file"  id='user_person_picture'/>
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
										</div>
									<div class="file-btn">
										
										<input class="btn2" type="file" id='user_idcard_picture' />
										<input id="user_idcard_picture_hide" style="width:1px;opacity:0;filter:alpha(opacity:0);" type="text" value=""  data-rule="required;"  data-target="#user_idcard_picture_tip" />
                                        <i id='user_idcard_picture_tip' style="position:absolute; left:176px; top:-6px;"></i>

										<a target="_blank" href="{{ constant('STATIC_URL')}}/mdg/images/lodeImg_img3.jpg">查看样照</a>
									</div>
								</div>
							</div>
							<div class="message clearfix">
								<font>身份证背面照</font>
								<div class="loadImg-box">
									<div class="imgs" id='user_show_idcard_picture_back'>
										</div>
									<div class="file-btn">
										
										<input class="btn2" type="file" id='user_idcard_picture_back' />
										<input id="user_idcard_picture_back_hide" style="width:1px;opacity:0;filter:alpha(opacity:0);" type="text" value=""  data-rule="required;"  data-target="#user_idcard_picture_back_tip" />
                                        <i id='user_idcard_picture_back_tip' style="position:absolute; left:176px; top:-6px;"></i>

										<a target="_blank" href="{{ constant('STATIC_URL')}}/mdg/images/lodeImg_img3.jpg">查看样照</a>
									</div>
								</div>
							</div>
							<div class="m-title mt20">服务工程师</div>
							<div class="message clearfix">
								<font>工程师账号</font>
								<div class="input-box">
									<input type="text" name='seusername'  data-rule="required;mobile;" />
								</div>
							</div>

						</div>
						<!-- 村站 企业 -->
						<div class="buyer-common"  >

							<div class="message clearfix">
								<font>公司名称</font>
								<div class="input-box">
									<input type="text" name='ent_company_name' data-rule="required;" />

								</div>
							</div>
							<div class="message clearfix">
								<font>注册登记证号</font>
								<div class="input-box">
									<input type="text" name='ent_register_no' data-rule="required;digits;"/>

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
									<select  name='town_id' class='ent_selectAreas' data-rule="required;" >
										<option value=''>请选择</option>
									</select>
									<br>
									<input class="f-db" type="text" name='ent_address' />

								</div>
							</div>
							<div class="message clearfix">
								<font>企业法人</font>
								<div class="input-box">
									<input type="text" name='ent_erprise_legal_person'  data-rule="required;chinese;" />

								</div>
							</div>
							<div class="message clearfix">
								<font>身份证号</font>
								<div class="input-box">
									<input type="text" name='ent_certificate_no'   data-rule="required;ID_card;" />

								</div>
							</div>
						<!--	<div class="message clearfix">
								<font>申请负责区域</font>
								<div class="select-box">
									<select name='ent_area_province_id' class='ent_area_selectAreas'>
										<option value=''>请选择</option>
									</select>
									<select name='ent_area_city_id' class='ent_area_selectAreas'>
										<option value=''>请选择</option>
									</select>
									<select name='ent_area_district_id'  class='ent_area_selectAreas' data-rule="required;remote[/member/userfarm/checkUserFarmArea]" >
										<option value=''>请选择</option>
									</select>

								</div>
							</div>-->
							<div class="m-title mt20">联系人信息</div>
							<div class="message clearfix">
								<font>姓名</font>
								<div class="input-box">
									<input type="text" name='ent_contact_name'  data-rule="required;chinese;" />

								</div>
							</div>
							<div class="message clearfix">
								<font>手机号</font>
								<div class="input-box">
									<input type="text" name='ent_contact_phone'  data-rule="required;mobile;" />

								</div>
							</div>
							<div class="message clearfix">
								<font>传真</font>
								<div class="input-box">
									<input type="text"  name='ent_contact_fax'/> <i>（没有可不填）</i>
								</div>
							</div>
							<div class="m-title mt20">认证信息</div>
							<div class="message clearfix">
								<font>银行卡开户行</font>
								<div class="select-box lang-select">
									<select name='ent_bank_name'  data-rule="required;" >
										<option value=''>请选择</option>
										{% for key , item in  bankList %}
										<option value='{{ item['gate_id']}}'>{{ item['bank_name']}}</option>
										{% endfor %}
									</select>
								</div>
							</div>
							<div class="message clearfix">
								<font>开户行所在地</font>
								<div class="select-box lang-select">
									<select name='ent_bank_province_id' class='class_bank_address'>
										<option value=''>请选择</option>
									</select>
									<select name='ent_bank_city_id'  class='class_bank_address' >
										<option value=''>请选择</option>
									</select>
									<select name='ent_bank_district_id'  class='class_bank_address' data-rule="required;" >
										<option value=''>请选择</option>
									</select>
								</div>
							</div>
							<div class="message clearfix">
								<font>开户公司名称</font>
								<div class="input-box">
									<input type="text" name='ent_bank_account'  data-rule="required;" />
								</div>
							</div>
							<div class="message clearfix">
								<font>卡号</font>
								<div class="input-box">
									<input type="text"  name='ent_bank_cardno' data-rule="required;mark;"/>
								</div>
							</div>
						<!--	<div class="message clearfix">
								<font>银行卡证明照</font>
								<div class="loadImg-box">
									<div class="imgs" id='ent_show_bankcard_picture'>
										</div>
									<div class="file-btn">
										
										<input class="btn2" type="file" name='ent_bankcard_picture' id='ent_bankcard_picture' />

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
										</div>
									<div class="file-btn">
										
										<input class="btn2" type="file" id='ent_identity_picture_lic'/>
										<input id="ent_identity_picture_lic_hide" style="width:1px;opacity:0;filter:alpha(opacity:0);" type="text" value=""  data-rule="required;"  data-target="#ent_identity_picture_lic_tip" />
                                        <i id='ent_identity_picture_lic_tip' style="position:absolute; left:176px; top:-6px;"></i>


										<a target="_blank" href="{{ constant('STATIC_URL')}}/mdg/images/lodeImg_img4.jpg">查看样照</a>
									</div>
								</div>
							</div>
							
                            <div class="message clearfix">
								<font>组织机构代码证</font>
								<div class="loadImg-box">
									<div class="imgs" id='ent_show_organization_code'></div>
									<div class="file-btn">
    									<input class="btn2" type="file" id='ent_organization_code' name="ent_organization_code"/>
										<input id="ent_organization_code_hide" style="width:1px;opacity:0;filter:alpha(opacity:0);" type="text" value="" data-rule="required;"  data-target="#ent_organization_code_tip" />
                                        <i id='ent_organization_code_tip' style="position:absolute; left:176px; top:-6px;"></i>

										<a target="_blank" href="{{ constant('STATIC_URL')}}/mdg/images/lodeImg_img4.jpg">查看样照</a>
									</div>
								</div>
							</div>
                            
                            <div class="message clearfix">
								<font>税务登记证（选填）</font>
								<div class="loadImg-box">
									<div class="imgs" id='ent_show_tax_registration'></div>
									<div class="file-btn">
    									<input class="btn2" type="file" id='ent_tax_registration' name="ent_tax_registration"/>
										<input id="ent_tax_registration_hide" style="width:1px;opacity:0;filter:alpha(opacity:0);" type="text" value="1"  data-target="#ent_tax_registration_tip" />
                                        <i id='ent_tax_registration_tip' style="position:absolute; left:176px; top:-6px;"></i>

										<a target="_blank" href="{{ constant('STATIC_URL')}}/mdg/images/lodeImg_img4.jpg">查看样照</a>
									</div>
								</div>
							</div>
							<div class="message clearfix">
								<font>身份证照</font>
								<div class="loadImg-box">
									<div class="imgs" id='ent_show_idcard_picture'>
										</div>
									<div class="file-btn">
										
										<input class="btn2" type="file" id='ent_idcard_picture' />
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
										</div>
									<div class="file-btn">
										
										<input class="btn2" type="file" id='ent_idcard_picture_back' />
										<input id="ent_idcard_picture_back_hide" style="width:1px;opacity:0;filter:alpha(opacity:0);" type="text" value=""  data-rule="required;"  data-target="#ent_idcard_picture_back_tip" />
                                        <i id='ent_idcard_picture_back_tip' style="position:absolute; left:176px; top:-6px;"></i>

										<a target="_blank" href="{{ constant('STATIC_URL')}}/mdg/images/lodeImg_img3.jpg">查看样照</a>
									</div>
								</div>
							</div>
							<div class="m-title mt20">服务工程师</div>
							<div class="message clearfix">
								<font>工程师编号</font>
								<div class="input-box">
									<input type="text" name='seid' data-rule="required;digits;"  />
								</div>
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

// valid: function(form){
// var me = this;
// 	me.holdSubmit(false);
// }


</script>
<style>
.upload_btn {width:89px; height:25px; border: solid 1px #99be20; color:#99be20; background: #fff; text-align: center; line-height:25px;
  font-family: '微软雅黑';
  cursor: pointer;
  position: relative;}
.edui-default .edui-editor{ margin:10px auto;}
</style>