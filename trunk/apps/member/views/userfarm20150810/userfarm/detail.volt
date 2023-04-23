<!--头部 start-->
{{ partial('layouts/page_header') }}
<!--头部 end-->

<!--主体 start-->
<div class="center-wrapper pb30">
	<div class="bread-crumbs w1185 mtauto">
		<a href="/">首页</a> > <a href="/member">个人中心</a> > 身份认证
	</div>
	<div class="w1185 mtauto clearfix">
		<!-- 左侧 start-->
		{{ partial('layouts/navs_left') }}
		<!-- 左侧 end-->
		<!-- 右侧 -->
		<div class="center-right f-fr">
			<div class="revision-certification">
				<div class="title f-oh">认证申请</div>
				<div class="certifi-success">
					{% if users.status == 0 %}
						<strong>您申请的 '{{ users.credit_type }}' 已经提交成功！</strong>
					{% elseif users.status == 1 %}
						<strong>恭喜您，您申请的 {{ users.credit_type }} 已经申请成功！</strong>
						<a {% if flag %} href="/member/userfarm/certification/{{ flag }}" {% endif %}>查看证书</a>
					{% elseif users.status == 2 %}
						<strong>很遗憾，你的申请没有审核通过！</strong>
						<a href="/member/userfarm/{{ func }}/{{ users.credit_id }}">重新提交</a>
						<a href="javascript:void(0);" onclick="del({{ users.credit_id }});">删除信息</a>
					{% elseif users.status == 3 %}
						<strong>您申请的认证已经被取消！</strong>
					{% endif %}
				</div>
				<div class="certifi-message">

					<!-- 申请日志 -->
					{% if userlog %}
					<div class="daily">
						<div class="m-title">申请日志</div>
						<div class="list clearfix">
							{% for key,val in userlog %}
							<div class="daily-message clearfix">
								<font>{{ val['operate_time']}}</font>
								<div class="box">
									<span>{{ val['status_name']}}</span>
									
									<div class="fail-reason">
										<div class="border"></div>
										<p>失败原因：{{ val['demo']}}</p>
									</div>
									
								</div>
							</div>
							{% endfor %}
						</div>
					</div>
					{% endif %}
					<!-- 基本信息 -->
					{% if param == 8 %}
						{% if users %}
						{% if type == 1 %}
							<div class="basic-info">
								<div class="m-title mb20">基本信息</div>
								<div class="message clearfix">
									<font>您的身份：</font>
									<div class="info-box">{{ users.type }}</div>
								</div>
								<div class="message clearfix">
									<font>姓名：</font>
									<div class="info-box">{{ users.name }}</div>
								</div>
								<div class="message clearfix">
									<font>身份证号：</font>
									<div class="info-box">{{ users.certificate_no }}</div>
								</div>
								<div class="message clearfix">
									<font>手机号：</font>
									<div class="info-box">{{ users.phone }}</div>
								</div>
							</div>
						{% else %}
							<div class="basic-info">
								<div class="m-title mb20">基本信息</div>
								<div class="message clearfix">
									<font>您的身份：</font>
									<div class="info-box">{{ users.type }}</div>
								</div>
								<div class="message clearfix">
									<font>公司名称：</font>
									<div class="info-box">{{ users.company_name }}</div>
								</div>
								<div class="message clearfix">
									<font>注册登记证号：</font>
									<div class="info-box">{{ users.register_no }}</div>
								</div>
								<div class="message clearfix">
									<font>企业法人：</font>
									<div class="info-box">{{ users.enterprise_legal_person }}</div>
								</div>
								<div class="message clearfix">
									<font>身份证号：</font>
									<div class="info-box">{{ users.certificate_no }}</div>
								</div>
								<div class="message clearfix">
									<font>公司地址：</font>
									<div class="info-box">{{ users.detailaddress }}</div>
								</div>
							</div>
						{% endif %}
						{% endif %}
					{% elseif param == 2 %}
						{% if type == 1 %}
							<div class="basic-info">
								<div class="m-title mb20">基本信息</div>
								<div class="message clearfix">
									<font>您的身份：</font>
									<div class="info-box">{{ users.type }}</div>
								</div>
								<div class="message clearfix">
									<font>姓名：</font>
									<div class="info-box">{{ users.name }}</div>
								</div>
								<div class="message clearfix">
									<font>身份证号：</font>
									<div class="info-box">{{ users.certificate_no }}</div>
								</div>
								<div class="message clearfix">
									<font>手机：</font>
									<div class="info-box">{{ users.phone }}</div>
								</div>
								<div class="message clearfix">
									<font>所在地区：</font>
									<div class="info-box">{{ users.detailaddress }}</div>
								</div>
							</div>
						{% else %}
							<div class="basic-info">
								<div class="m-title mb20">基本信息</div>
								<div class="message clearfix">
									<font>您的身份：</font>
									<div class="info-box">{{ users.type }}</div>
								</div>
								<div class="message clearfix">
									<font>公司名称：</font>
									<div class="info-box">{{ users.company_name }}</div>
								</div>
								<div class="message clearfix">
									<font>注册登记证号：</font>
									<div class="info-box">{{ users.register_no }}</div>
								</div>
								<div class="message clearfix">
									<font>企业法人：</font>
									<div class="info-box">{{ users.enterprise_legal_person }}</div>
								</div>
								<div class="message clearfix">
									<font>身份证号：</font>
									<div class="info-box">{{ users.certificate_no }}</div>
								</div>
								<div class="message clearfix">
									<font>公司地址：</font>
									<div class="info-box">{{ users.detailaddress }}</div>
								</div>
							</div>
						{% endif %}
					{% elseif param == 16 %}
						{% if type == 0 %}
							<div class="basic-info">
								<div class="m-title mb20">基本信息</div>
								<div class="message clearfix">
									<font>您的身份：</font>
									<div class="info-box">{{ users.type }}</div>
								</div>
								<div class="message clearfix">
									<font>姓名：</font>
									<div class="info-box">{{ users.name }}</div>
								</div>
								<div class="message clearfix">
									<font>身份证号：</font>
									<div class="info-box">{{ users.certificate_no }}</div>
								</div>
								<div class="message clearfix">
									<font>手机：</font>
									<div class="info-box">{{ users.phone }}</div>
								</div>
								<div class="message clearfix">
									<font>公司地址：</font>
									<div class="info-box">{{ users.detailaddress }}</div>
								</div>
								<div class="message clearfix">
									<font>采购类别：</font>
									<div class="info-box">{{ farm['cropName'] }}</div>
								</div>
							</div>
						{% endif %}
					{% else %}
						{% if type == 1 %}
						<div class="basic-info">
							<div class="m-title mb20">基本信息</div>
							<div class="message clearfix">
								<font>您的身份：</font>
								<div class="info-box">{{ users.type }}</div>
							</div>
							<div class="message clearfix">
								<font>公司名称：</font>
								<div class="info-box">{{ users.company_name }}</div>
							</div>
							<div class="message clearfix">
								<font>注册登记证号：</font>
								<div class="info-box">{{ users.register_no }}</div>
							</div>
							<div class="message clearfix">
								<font>企业法人：</font>
								<div class="info-box">{{ users.enterprise_legal_person }}</div>
							</div>
							<div class="message clearfix">
								<font>身份证号：</font>
								<div class="info-box">{{ users.certificate_no }}</div>
							</div>
							<div class="message clearfix">
								<font>公司地址：</font>
								<div class="info-box">{{ users.detailaddress }}</div>
							</div>
							<div class="message clearfix">
								<font>采购类别：</font>
								<div class="info-box">{{ farm['cropName'] }}</div>
							</div>
						</div>
						{% endif %}
					{% endif %}
					<!-- 认证信息 -->
					{% if param == 8 %}
						{% if type != 1 %}
							<!--联系信息-->
							<div class="basic-info">
								<div class="m-title mb20">联系信息</div>
								<div class="message clearfix">
									<font>姓名：</font>
									<div class="info-box">{% if cont %}{{ cont.name }}{% endif %}</div>
								</div>
								<div class="message clearfix">
									<font>手机：</font>
									<div class="info-box">{% if cont %}{{ cont.phone }}{% endif %}</div>
								</div>
								<div class="message clearfix">
									<font>传真：</font>
									<div class="info-box">{% if cont %}{{ cont.fax }}{% endif %}</div>
								</div>
							</div>
						{% endif %}
						<div class="basic-info">
							<div class="m-title mb20">认证信息</div>
							<div class="message clearfix">
								<font>您的身份：</font>
								<div class="info-box">{{ bank['bankName']}}</div>
							</div>
							<div class="message clearfix">
								<font>开户行所在地：</font>
								<div class="info-box">{{ bank['bank_address']}}</div>
							</div>
							<div class="message clearfix">
								<font>开户名：</font>
								<div class="info-box">{{ bank['bank_account']}}</div>
							</div>
							<div class="message clearfix">
								<font>卡号：</font>
								<div class="info-box">{{ bank['bank_cardno']}}</div>
							</div>
							<div class="message clearfix">
								<font>身份证照：</font>
								<div class="info-box">
									<ul class="gallery f-oh">
										<li class="f-fl">
											<a target="_blank" href="{{ constant('ITEM_IMG')}}{{ bank['idcard_picture']}}"><img src="{{ bank['idcard_picture']}}" alt="" width="251px" height="141px"></a>
										</li>
										<li class="f-fl">
											<a target="_blank" href="{{ constant('ITEM_IMG')}}{{ bank['idcard_picture_back']}}"><img src="{{ bank['idcard_picture_back']}}" alt="" width="251px" height="141px"></a>
										</li>
									</ul>
								</div>
							</div>
						</div>
					{% elseif param == 2 %}
						{% if type == 2 %}
							<!--联系信息-->
							<div class="basic-info">
								<div class="m-title mb20">联系信息</div>
								<div class="message clearfix">
									<font>姓名：</font>
									<div class="info-box">{% if cont %}{{ cont.name }}{% endif %}</div>
								</div>
								<div class="message clearfix">
									<font>手机：</font>
									<div class="info-box">{% if cont %}{{ cont.phone }}{% endif %}</div>
								</div>
								<div class="message clearfix">
									<font>传真：</font>
									<div class="info-box">{% if cont %}{{ cont.fax }}{% endif %}</div>
								</div>
							</div>
						{% endif %}
						<div class="basic-info">
							<div class="m-title mb20">认证信息</div>
							<div class="message clearfix">
								<font>您的身份：</font>
								<div class="info-box">{{ bank['bankName']}}</div>
							</div>
							<div class="message clearfix">
								<font>开户行所在地：</font>
								<div class="info-box">{{ bank['bank_address']}}</div>
							</div>
							<div class="message clearfix">
								<font>开户名：</font>
								<div class="info-box">{{ bank['bank_account']}}</div>
							</div>
							<div class="message clearfix">
								<font>卡号：</font>
								<div class="info-box">{{ bank['bank_cardno']}}</div>
							</div>
							<div class="message clearfix">
								<font>身份证照：</font>
								<div class="info-box">
									<ul class="gallery f-oh">
										<li class="f-fl">
											<a target="_blank" href="{{ constant('ITEM_IMG')}}{{ bank['idcard_picture']}}"><img src="{{ bank['idcard_picture']}}" alt="" width="251px" height="141px"></a>
										</li>
										<li class="f-fl">
											<a target="_blank" href="{{ constant('ITEM_IMG')}}{{ bank['idcard_picture_back']}}"><img src="{{ bank['idcard_picture_back']}}" alt="" width="251px" height="141px"></a>
										</li>
									</ul>
								</div>
							</div>
						</div>
					{% elseif param == 16 %}
						<div class="basic-info">
							<div class="m-title mb20">认证信息</div>
							<div class="message clearfix">
								<font>您的身份：</font>
								<div class="info-box">{{ bank['bankName']}}</div>
							</div>
							<div class="message clearfix">
								<font>开户行所在地：</font>
								<div class="info-box">{{ bank['bank_address']}}</div>
							</div>
							<div class="message clearfix">
								<font>开户名：</font>
								<div class="info-box">{{ bank['bank_account']}}</div>
							</div>
							<div class="message clearfix">
								<font>卡号：</font>
								<div class="info-box">{{ bank['bank_cardno']}}</div>
							</div>
							<div class="message clearfix">
								<font>身份证照：</font>
								<div class="info-box">
									<ul class="gallery f-oh">
										<li class="f-fl">
											<a target="_blank" href="{{ constant('ITEM_IMG')}}{{ bank['idcard_picture']}}"><img src="{{ bank['idcard_picture']}}" alt="" width="251px" height="141px"></a>
										</li>
										<li class="f-fl">
											<a target="_blank" href="{{ constant('ITEM_IMG')}}{{ bank['idcard_picture_back']}}"><img src="{{ bank['idcard_picture_back']}}" alt="" width="251px" height="141px"></a>
										</li>
									</ul>
								</div>
							</div>
							<div class="message clearfix">
								<font>推荐人：</font>
								<div class="info-box">{{ users.se_mobile }}</div>
							</div>
						</div>
					{% else %}
						<!--联系信息-->
						<div class="basic-info">
							<div class="m-title mb20">联系信息</div>
							<div class="message clearfix">
								<font>姓名：</font>
								<div class="info-box">{% if cont %}{{ cont.name }}{% endif %}</div>
							</div>
							<div class="message clearfix">
								<font>手机：</font>
								<div class="info-box">{% if cont %}{{ cont.phone }}{% endif %}</div>
							</div>
							<div class="message clearfix">
								<font>传真：</font>
								<div class="info-box">{% if cont %}{{ cont.fax }}{% endif %}</div>
							</div>
						</div>
						<div class="basic-info">
							<div class="m-title mb20">认证信息</div>
							<div class="message clearfix">
								<font>您的身份：</font>
								<div class="info-box">{{ bank['bankName']}}</div>
							</div>
							<div class="message clearfix">
								<font>开户行所在地：</font>
								<div class="info-box">{{ bank['bank_address']}}</div>
							</div>
							<div class="message clearfix">
								<font>开户名：</font>
								<div class="info-box">{{ bank['bank_account']}}</div>
							</div>
							<div class="message clearfix">
								<font>卡号：</font>
								<div class="info-box">{{ bank['bank_cardno']}}</div>
							</div>
							<div class="message clearfix">
								<font>身份证照：</font>
								<div class="info-box">
									<ul class="gallery f-oh">
										<li class="f-fl">
											<a target="_blank" href="{{ constant('ITEM_IMG')}}{{ bank['idcard_picture']}}"><img src="{{ bank['idcard_picture']}}" alt="" width="251px" height="141px"></a>
										</li>
										<li class="f-fl">
											<a target="_blank" href="{{ constant('ITEM_IMG')}}{{ bank['idcard_picture_back']}}"><img src="{{ bank['idcard_picture_back']}}" alt="" width="251px" height="141px"></a>
										</li>
									</ul>
								</div>
							</div>
							<div class="message clearfix">
								<font>工商营业执照：</font>
								<div class="info-box">
									<ul class="gallery f-oh">
										<li class="f-fl">
											<a target="_blank" href="{{ constant('ITEM_IMG')}}{{ bank['identity_picture_lic']}}"><img src="{{ bank['identity_picture_lic']}}" alt="" width="251px" height="141px"></a>
										</li>
									</ul>
								</div>
							</div>
							<div class="message clearfix">
								<font>组织机构代码证：</font>
								<div class="info-box">
									<ul class="gallery f-oh">
										<li class="f-fl">
											<a target="_blank" href="{{ constant('ITEM_IMG')}}{{ bank['identity_picture_org']}}"><img src="{{ bank['identity_picture_org']}}" alt="" width="251px" height="141px"></a>
										</li>
									</ul>
								</div>
							</div>
							<div class="message clearfix">
								<font>税务登记证：</font>
								<div class="info-box">
									<ul class="gallery f-oh">
										<li class="f-fl">
											<a target="_blank" href="{{ constant('ITEM_IMG')}}{{ bank['identity_picture_tax']}}"><img src="{{ bank['identity_picture_tax']}}" alt="" width="251px" height="141px"></a>
										</li>
									</ul>
								</div>
							</div>
							<div class="message clearfix">
								<font>推荐人：</font>
								<div class="info-box">{{ users.se_mobile }}</div>
							</div>
						</div>
					{% endif %}
					
					<!-- 农场信息 -->
					{% if param == 8 %}
						<div class="basic-info">
							<div class="m-title mb20">农场信息</div>
							<div class="message clearfix">
								<font>农场名：</font>
								<div class="info-box">{{ farm['farm_name'] }}</div>
							</div>
							<div class="message clearfix">
								<font>农场地址：</font>
								<div class="info-box">{{ farm['address'] }}</div>
							</div>
							<div class="message clearfix">
								<font>农场面积：</font>
								<div class="info-box">{{ farm['farm_area'] }}亩</div>
							</div>
							<div class="message clearfix">
								<font>种植作物：</font>
								<div class="info-box">{{ farm['cropName'] }}</div>
							</div>
							<div class="message clearfix">
								<font>土地来源：</font>
								<div class="info-box">
									{% if farm['source'] == 0 %} 自有 
									{% elseif farm['source'] == 1 %} 流转
									{% endif %}
								</div>
							</div>
							<div class="message clearfix">
								<font>土地使用年限：</font>
								<div class="info-box">{{ farm['start_year'] }}年{{ farm['start_month'] }}月</div>
							</div>
							<div class="message clearfix">
								<font>农场简介：</font>
								<div class="info-box">{{ farm['describe'] }}</div>
							</div>
							<div class="message clearfix">
								<font>农场图片：</font>
								<div class="info-box">
									<ul class="gallery f-oh">
										{% if pic %}
											{% for key,val in pic['farm'] %}

											<li class="f-fl">
												<a target="_blank" href="{{ constant('ITEM_IMG')}}{{ val['picture_path'] }}"><img src="{{ constant('ITEM_IMG')}}{{ val['picture_path'] }}" width="251px" height="141px" alt=""></a>
											</li>
											{% endfor %}
										{% endif %}
									</ul>
								</div>
							</div>
							<div class="message clearfix">
								<font>耕地合同：</font>
								<div class="info-box">
									<ul class="gallery f-oh">
										{% if pic %}
											{% for key,val in pic['contract'] %}
											<li class="f-fl">
												<a target="_blank" href="{{ constant('ITEM_IMG')}}{{ val['picture_path'] }}"><img src="{{ constant('ITEM_IMG')}}{{ val['picture_path'] }}" width="251px" height="141px" alt=""></a>
											</li>
											{% endfor %}
										{% endif %}
									</ul>
								</div>
							</div>

							<div class="message clearfix">
								<font>推荐人：</font>
								<div class="info-box">{{ users.se_mobile }}</div>
							</div>
						</div>
					{% elseif param == 2 %}
						<div class="basic-info">
							<div class="m-title mb20">服务工程师</div>
							<div class="message clearfix">
								<font>工程师账号：</font>
								<div class="info-box">{% if users.se_id != '' %}{{ users.se_mobile }}{% endif %}</div>
							</div>
						</div>
					{% endif %}
					

				</div>
			</div>
		</div>
		<!-- 右侧 end -->
	</div>
</div>

</div>
</div>
<!--主体 end-->

<!--尾部 start-->
	{{ partial('layouts/footer') }}
<!--尾部 end-->

<script src="{{ constant('STATIC_URL') }}mdg/version2.4/js/personal-center.js"></script>
<script type="text/javascript">
// 删除
function del(credit_id) {
	$.ajax({
		type:"POST",
		url:"/member/Userfarm/delapply",
		data:{credit_id:credit_id},
		dataType:"json",
		success:function(msg){
			if(msg['code'] == 4) {
				alert(msg['result']);
				window.location.href="/member/userfarm/index";
			} else {
				alert(msg['result']);
				return;
			}
		}
	});
}
</script>