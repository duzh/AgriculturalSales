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

				<div class="author-attest">

					<div class="title f-oh">
					 	<span class="f-fl">身份认证</span>
						<a class="f-fr" href="/member/userfarm/selectByApply">查看我的申请</a>
					</div>
					<div class="box f-oh">

						<a class="f-db f-fl" href="/member/userfarm/user">
							<div class="contianer">
								<h6>可信农场</h6>
								<p>有较大种植规模的种植户或合作社,丰收汇授予可信农场认证,丰收汇为其提供农产品安全种植技术及丰收汇标签,通过丰收汇平台及县域加盟商对接采优质采购商.</p>
								<div class="btn">我要申请</div>
							</div>
						</a>

						<a class="f-db f-fl" href="/member/userfarm/village">
							<div class="contianer">
								<h6>村级服务站</h6>
								<p>帮助农户发布实时真实的农产品信息;</p>
								<div class="btn">我要申请</div>
							</div>
						</a>

						<a class="f-db f-fl" href="/member/userfarm/county">
							<div class="contianer">
								<h6>县域服务中心</h6>
								<p>丰收汇线下服务实体。发布农产品价格信息，直接销售农产品或为采购数提供看货、代购服务；</p>
								<div class="btn">我要申请</div>
							</div>
						</a>

						<a class="f-db f-fl" href="/member/userfarm/purchaser">
							<div class="contianer">
								<h6>采购商</h6>
								<p>经过丰收汇认证的采购企业；</p>
								<div class="btn">我要申请</div>
							</div>
						</a>

					</div>
					<div class="process f-tac">
						<h6>申请流程</h6>
						<img src="{{ constant('STATIC_URL')}}/mdg/version2.4/images/author-attest-img.png">
					</div>
					<div class="m-title">常见问题</div>
					<div class="ask">Q1、丰收汇身份认证需要付费吗？</div>
					<div class="answer">
						丰收汇的身份认证是完全免费的，不会向认证申请人收取任何费用。县级运营服务中心申请则需要交纳保证金以及品牌使用费。
					</div>
					<div class="ask">Q2、丰收汇身份认证需要多久时间？</div>
					<div class="answer">
						会员成功提交丰收汇认证资料后，需要经过审核。一般会在工作日24小时内完成审核，请耐心等待。
					</div>
					<div class="ask">Q3、如何知道是否通过认证审核？</div>
					<div class="answer">
						是否审核通过，网站都会发送通知消息到您提交认证的会员账号上，建议您提交资料后及时关注私信内容。<br />
						也可以重新进入认证申请页面查看资料审核状态，若未通过审核，可按页面提示调整后重新提交。<br />
						（例如：所提交的身份证件不清晰，需重新提交清晰可见的证件照片）
					</div>
					<div class="ask">Q4、个人可以申请丰收汇认证吗？</div>
					<div class="answer">
						个人有丰收汇经营需要的，可以凭有效的身份证件申请丰收汇身份认证，不需要上传营业执照，但需要本人手持身份证件合影拍照上传，并保证真人头像可见和证件信息清楚。如果有网店，请提供网址，方便更快通过审核。
					</div>
					<div class="ask">Q5、丰收汇身份认证需要填写什么资料？</div>
					<div class="answer">
						提交认证的申请页面凡是带有 * 号的选项均为必填。<br />
						要保证填写信息与提供的证件资料信息相符，并保证证件图片文字清楚可辩，否则无法通过审核。
					</div>
					<div class="ask">Q6、丰收汇身份认证的上传证件有什么要求？</div>
					<div class="answer">
						企业申请丰收汇认证，营业执照需加盖申请公司的公章，还需要提供企业联系人的身份证件。<br />
						个人申请丰收汇认证，需要手持身份证件合影拍照上传，并保证真人头像和证件信息可见。
					</div>

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