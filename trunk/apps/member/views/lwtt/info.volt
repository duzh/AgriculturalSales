<!--头部 start-->
{{ partial('layouts/member_header') }}
<!--头部 end-->

<!--主体 start-->
<div class="wrapper">
	<div class="w1190 mtauto f-oh">
		<div class="bread-crumbs w1185 mtauto">
            <span>{{ partial('layouts/ur_here') }}盟商申请</span>
        </div>
		<!-- 左侧 start-->
		{{ partial('layouts/navs_left') }}
		<!-- 左侧 end-->
		<!-- 右侧 -->
        <div class="center-right f-fr">
				<!-- add 2015.10.28 盟商 代码开始 -->
				<div class="revision-certification">
					<div class="title f-oh">产地服务站申请</div>
					<!-- 状态1 -->
					{% if users.status == 1 %}
					<div class="wms-certifi-success">
						<strong class="zt1" style="margin-top:56px;">恭喜您，您申请认证已通过审核！</strong>
					</div>
					{% endif %}
					{% if users.status == 2 %}
					<!-- 状态2 -->
					<div class="wms-certifi-success">
						<strong class="zt2">很遗憾，您的申请没有审核通过！</strong>
						<font>如有疑问，请拨打24小时客服电话 400-8811-365.</font>
						<div class="btns">
							<a class="btn1" href="/member/lwtt/edit/{{ users.credit_id }}">重新提交</a>
							<a class="btn1" href="javascript:void(0);" onclick="del({{ users.credit_id }});">删除信息</a>
						</div>
					</div>
					{% endif %}
					{% if users.status == 3 %}
					<!-- 状态3 -->
					<div class="wms-certifi-success">
						<strong class="zt2">您申请的认证已经被管理员取消</strong>
						<font>如有疑问，请拨打24小时客服电话 400-8811-365.</font>
					</div>
					{% endif %}
					{% if users.status == 0 %}
					<!-- 状态4 -->
					<div class="wms-certifi-success">
						<strong class="zt4">
							<span>认证信息已提交，正在待审核中...</span>
						</strong>
						<font class="tips4">我们会在3个工作日内审核完成，请耐心等待。</font>
					</div>
					{% endif %}
					<div class="certifi-message">

						<!-- 申请日志 -->
						{% if userlog %}
						<div class="daily">
							<div class="m-title">申请日志</div>
							<div class="list clearfix">
                               {% for key,val in userlog %}
								<div class="daily-message clearfix">
									<font>{{ date("Y-m-d H:i:s",val['operate_time'])}}</font>
									<div class="box">
									    {% if val['status'] == 2 %}
									    <span>审核失败</span>  
									    {% elseif val['status'] == 3 %}
									    <span>认证取消</span> 
									    {% else %}
									    <span>{{ val['demo'] }}</span>
									    {% endif %}
									    {% if val['status'] == 2 or val['status'] == 3 %}
										<div class="fail-reason">
											<div class="border"></div>
											<p>{{ val['demo']}}</p>
										</div>
										{% endif  %}
									</div>
								</div>
								{% endfor %}
							</div>
						</div>
						{% endif %}
						<!-- 基本信息 -->
						<div class="basic-info">
							<div class="m-title mb20">基本信息</div>
							<div class="message clearfix">
								<font>姓名：</font>
								<div class="info-box">{{ users.name }}</div>
							</div>
							<div class="message clearfix">
								<font>用户类型：</font>
								<div class="info-box">{{ users.type }}</div>
							</div>
							<div class="message clearfix">
								<font>手机号：</font>
								<div class="info-box">{{ users.phone }}</div>
							</div>
							<div class="message clearfix">
								<font>身份证号：</font>
								<div class="info-box">{{ users.certificate_no }}</div>
							</div>
							<div class="message clearfix">
								<font>推荐人姓名：</font>
								<div class="info-box">{{ engineerinfo ?  engineerinfo.engineer_name : '' }}</div>
							</div>
							<div class="message clearfix">
								<font>推荐人手机号：</font>
								<div class="info-box">{{ engineerinfo ? engineerinfo.engineer_phone : '' }}</div>
							</div>
						</div>
						<!-- 认证信息 -->
						<div class="basic-info">
							<div class="m-title mb20">认证信息</div>
							<div class="message clearfix">
								<font>身份证照：</font>
								<div class="info-box">
									<ul class="gallery f-oh">
										<li class="f-fl">
											<a target="_blank" href="{{ constant('ITEM_IMG')}}{{ userbank.idcard_picture}}"><img src="{{ constant('ITEM_IMG')}}{{ userbank.idcard_picture}}" alt=""  width="251px" height="141px"></a>
											
										</li>
										<li class="f-fl">
											<a target="_blank" href="{{ constant('ITEM_IMG')}}{{ userbank.idcard_picture_back }}"><img src="{{ constant('ITEM_IMG')}}{{ userbank.idcard_picture_back }}" alt="" width="251px" height="141px"></a>
											
										</li>
									</ul>
								</div>
							</div>
						</div>
						<!-- 经营类别 -->
						<div class="basic-info">
							<div class="m-title mb20">经营类别:</div>
							<div class="message clearfix">
								<font>选择类别：</font>
								<div class="info-box">
									<ul class="gallery f-oh">
										{% if crops %}
	                                        {% for key , item in crops %}
	                                        <li class="f-fl">{{ item['category_name'] }}</li>
	                                        {% endfor %}
                                        {% endif %}
									</ul>
								</div>
							</div>
						</div>

					</div>
					<div class="wms-center-btns f-tac">
                        <a href="javascript:void(0);" class="btn" onclick="window.history.go(-1);">返回</a>
                    </div>
				</div>
				<!-- add 2015.10.28 盟商 代码结束 -->
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
		url:"/member/lwtt/delapply",
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
