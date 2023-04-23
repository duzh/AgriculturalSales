<!--头部 start-->
{{ partial('layouts/page_header') }}
<!--头部 end-->

<!--主体 start-->
<div class="center-wrapper pb30">
	<div class="bread-crumbs w1185 mtauto">
		<a href="/">首页</a>
		>
		<a href="/member">个人中心</a>
		> 身份认证
	</div>
	<div class="w1185 mtauto clearfix">
		<!-- 左侧 start-->
		{{ partial('layouts/navs_left') }}
		<!-- 左侧 end-->

		<!-- 右侧 -->
		<div class="center-right f-fr">

			<div class="certificate-apply">
				

				<div class="title">认证申请</div>

				{% for key ,info in data %}

				{% if info.status == 0 %}
				<!-- 状态1 -->
				<div class="success f-tac">
					<span>您申请的【<?php echo isset(\Mdg\Models\Users::$_credit_type[$info->credit_type]) ? \Mdg\Models\Users::$_credit_type[$info->credit_type] : ''; ?>】已经提交成功！</span>
				</div>
				<div class="tips f-tac">请耐心等待，我们会在24小时内审核完成！如有疑问请拨打客服电话400 8811  365</div>
				{% endif %}
				<!-- 状态2 -->
				{% if info.status == 1 %}
				<div class="success f-tac">
					<span>恭喜您，您申请的【<?php echo isset(\Mdg\Models\Users::$_credit_type[$info->credit_type]) ? \Mdg\Models\Users::$_credit_type[$info->credit_type] : ''; ?>】已经审核通过！</span>
				</div>
				<div class="tips f-tac">
					点击这里
					<a href="/member/userfarm/certification/<?php echo isset(\Mdg\Models\Users::$_credit_id[$info->credit_type]) ? \Mdg\Models\Users::$_credit_id[$info->credit_type] : ''; ?>">【<?php echo isset(\Mdg\Models\Users::$_credit_type[$info->credit_type]) ? \Mdg\Models\Users::$_credit_type[$info->credit_type] : ''; ?>】</a>
					查看证书信息！如有疑问请拨打客服电话400 8811 365
				</div>
				{% endif %}
				<!-- 状态3 -->
				{% if info.status == 2 %}
				<div class="faild f-tac">
					<span>很遗憾，您申请的【<?php echo isset(\Mdg\Models\Users::$_credit_type[$info->credit_type]) ? \Mdg\Models\Users::$_credit_type[$info->credit_type] : ''; ?>】没有审核通过！</span>
				</div>
				<div class="tips f-tac">
					<span>
						很抱歉的通知您，您有部分信息不符合的要求，请完善后再联系管理员！
						<br />
						如有疑问请拨打客服电话400 8811 365
					</span>
				</div>
				{% endif %}
				
			{% endfor %}

			</div>

		</div>
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
// 取消收藏
function sellcansel(id,sid) {
	$.ajax({
		type:"POST",
		url:"/member/collectsell/collCansel",
		data:{id:id},
		dataType:"json",
		success:function(msg){
			if(msg['code'] == 2){
				alert(msg['result']);
				window.location.reload();
			} else {
				alert(msg['result']);
				return;
			}
		}
	});
}
</script>