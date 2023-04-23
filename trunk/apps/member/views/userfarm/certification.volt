<!--头部 start-->
{{ partial('layouts/member_header') }}
<!--头部 end-->
<script src="http://yncstatic.b0.upaiyun.com/mdg/version2.4/js/personal-center.js"></script>
<!--主体 start-->
<div class="wrapper pb30">
	<div class="w1190 mtauto clearfix">
		<div class="bread-crumbs w1185 mtauto">
            <span>{{ partial('layouts/ur_here') }}{% if info.type == 0 %} 个人认证详情{% else %} 企业认证详情{% endif %}</span>
        </div>
		<!-- 左侧 start-->
		{{ partial('layouts/navs_left') }}
		<!-- 左侧 end-->

			<!-- 右侧 -->
			<div class="center-right f-fr">

				<div class="enterprise-company">

					<div class="title">{% if info.type == 0 %} 个人认证详情{% else %} 企业认证详情{% endif %}</div>
					{% if info.type == 0 %}
					<!-- 个人采购商证书 -->
					<div class="certificate">
						<div class="box">

							<div class="m-title f-tac mt20">
								<img src="{{ constant('STATIC_URL')}}/mdg/version2.4/images/certificate-title.png">
							</div>
							<div class="message clearfix">
								<font>姓名：</font>
								<div class="msg-box">{{ info.name}}</div>
							</div>
							<div class="message clearfix">
								<font>身份：</font>
								<div class="msg-box">
									<?php echo isset(Mdg\Models\Users::$_credit_type[$info->credit_type]) ? Mdg\Models\Users::$_credit_type[$info->credit_type] : ''; ?>
								</div>
							</div>
							<div class="message clearfix">
								<font>类型：</font>
								<div class="msg-box">
									<?php echo isset(Mdg\Models\UserInfo::$_type[$info->type]) ? Mdg\Models\UserInfo::$_type[$info->type] : ''; ?>
								</div>
							</div>
							<div class="message clearfix">
								<font>银行信息：</font>
								<div class="msg-box">已认证</div>
							</div>
                             <div class="message clearfix">
								<font>所在地区：</font>
								<div class="msg-box">{{ info.province_name }}{{ info.city_name }}{{ info.district_name	 }}{{ info.town_name }}</div>
							</div>
							{% if info.type == 0 and info.credit_type != 16 and info.credit_type != 8  %}
							<!--  <div class="message clearfix">
								<font>负责区域：</font>
								<div class="msg-box">
									{{ UserArea.province_name }}{{ UserArea.city_name }}{{ UserArea.district_name	 }}{{ UserArea.town_name }}
								</div>
							</div>  -->
							{% endif %}

							{% if info.credit_type == 8 or info.credit_type == 16 %}
							<div class="message clearfix">
								<font>{% if info.credit_type == 16 %}采购作物 {% else %} 种植作物{% endif %}：</font>
								<div class="msg-box">{{ frmaCrops}}</div>
							</div>
								{% if UserFarm %}
								<div class="message clearfix">
									<font>农场名：</font>
									<div class="msg-box"><?php echo isset($UserFarm['farm_name'] ) ? $UserFarm['farm_name'] : '';?></div>
								</div>
								{% endif %}
							{% endif %}

							<div class="message clearfix">
								<font>身份编号：</font>
								<div class="msg-box">{{ info.credit_no }}</div>
							</div>
							<div class="img">
								<img src="{{ constant('STATIC_URL')}}/mdg/version2.4/images/certificate-img1.png">
							</div>

						</div>
					</div>

					{% else %}

					<!-- 个人县区服务中心证书 -->
					<div class="certificate">
						<div class="box">

							<div class="m-title f-tac mt20">
								<img src="{{ constant('STATIC_URL')}}/mdg/version2.4/images/certificate-title.png">
							</div>
							<div class="message clearfix">
								<font>公司名称：</font>
								<div class="msg-box">{{ info.company_name }}</div>
							</div>
							<div class="message clearfix">
								<font>身份：</font>
								<div class="msg-box">
									<?php echo isset(Mdg\Models\Users::$_credit_type[$info->credit_type]) ? Mdg\Models\Users::$_credit_type[$info->credit_type] : ''; ?>
								</div>
							</div>
							<div class="message clearfix">
								<font>类型：</font>
								<div class="msg-box">
									<?php echo isset(Mdg\Models\UserInfo::$_type[$info->type]) ? Mdg\Models\UserInfo::$_type[$info->type] : ''; ?>
								</div>
							</div>

							<div class="message clearfix">
								<font>银行信息：</font>
								<div class="msg-box">已认证</div>
							</div>
							<div class="message clearfix">
								<font>营业执照号：</font>
								<div class="msg-box">{{ info.register_no }}</div>
							</div>
							<div class="message clearfix">
								<font>公司地址：</font>
								<div class="msg-box">{{ info.province_name }}{{ info.city_name }}{{ info.district_name	 }}{{ info.town_name }}</div>
							</div>

							{% if info.credit_type == 8 or info.credit_type == 16 %}
							<div class="message clearfix">
								<font>采购作物：</font>
								<div class="msg-box">{{ frmaCrops}}</div>
							</div>
							{% else %}
							{% endif %}

							<div class="message clearfix">
								<font>身份编号：</font>
								<div class="msg-box">{{ info.credit_no }}</div>
							</div>
							<div class="img">
								<img src="{{ constant('STATIC_URL')}}/mdg/version2.4/images/certificate-img1.png">
							</div>

						</div>
					</div>
					{% endif %}
					
					

				</div>

			</div>



		</div>
	</div>



<!--尾部 start-->
{{ partial('layouts/footer') }}
<!--尾部 end-->
<script type="text/javascript" src="/mdg/js/user_farm.js?sid={{ sid }}&rand=<?php echo rand(1,999);?>"></script>

<style>
.upload_btn {width:89px; height:25px; border: solid 1px #99be20; color:#99be20; background: #fff; text-align: center; line-height:25px;
  font-family: '微软雅黑';
  cursor: pointer;
  position: relative;}
.edui-default .edui-editor{ margin:10px auto;}
</style>
