<!--头部 start-->
{{ partial('layouts/member_header') }}
<!--头部 end-->

<!--主体 start-->
<div class="wrapper">
	<div class="w1190 mtauto f-oh">
		<div class="bread-crumbs w1185 mtauto">
            <span>{{ partial('layouts/ur_here') }}身份认证</span>
        </div>
		<!-- 左侧 start-->
		{{ partial('layouts/navs_left') }}
		<!-- 左侧 end-->
		<!-- 右侧 -->
		<div class="center-right f-fr">
			<div class="revision-author-attest">
				<div class="title f-oh">身份认证</div>
				<div class="box f-oh">	
					<div class="ma-box f-fl">					
						<div class="contianer">
							<h6>
								<font class="f-db">可信农场</font>
								<span class="f-db icon1">可信农场</span>
							</h6>
							<p>有较大种植规模的种植户或合作社,丰收汇授予可信农场认证,丰收汇为其提供农产品安全种植技术及丰收汇标签,通过丰收汇平台及县域加产地服务站对接优质采购商.</p>

							{% if user_lwtt==0 or user_lwtt==1   %}
							<!--如果产地服务站申请了  那么为空-->

							{% else %}
							   <!--如果产地服务站未申请  那么user_if审核通过的时侯 显示认证的图标-->
                                {% if user_if == 1 %}
                                <div class="image">
									<img src="{{ constant('STATIC_URL') }}mdg/version2.5/images/auther-certify-image.png" alt="">
								</div>
								{% else %}
								   <!--如果可信农场未通过 按钮为禁用  -->
								    {% if user_if== 0 %}
								        <a class="yet-btn"  href="#">我要申请</a>
								    {% else %}
								        <a class="btn" href="/member/userfarm/user">我要申请</a>
								    {% endif %}
                                {% endif %}
							{% endif %}
						   
						</div>
                    </div>
                        <!--如果是产地服务站,进行产地服务站的东西,否则进行县级服务中心东西-->
                        
							<!--产地服务站-->
						<div class="ma-box f-fl">
							<div class="contianer">
							{% if (user_lwtt!=5 and user_lwtt!=4) or user_hc!=1 %}
								<h6>
									<font class="f-db">产地服务站</font>
									<span class="f-db icon5">产地服务站</span>
								</h6>
								<p>能够整合当地产地资源，协助丰收汇开发整合更多的可信农场，并为可靠农产品交易提供物流配送支持的合作伙伴</p>
                                
                                {% if user_if==0 or user_if==1 or user_if==2 or user_if==3 or (user_pe and user_pe.status==0) or   (user_pe and user_pe.status==1) or (user_pe and user_pe.status==2) or (user_pe and user_pe.status==3) %}

									<!--如果可信农场已申请 按钮为禁用状态 -->
									<a class="yet-btn"  href="#">我要申请</a>
								{% else %}
								
								   <!--如果可信农场未申请  那么user_lwtt审核通过的时侯 显示认证的图标 -->
	                                {% if user_lwtt == 1 %}
			                        <label>
										{% if lwttinfos['lwttstate'] %}<a href="/member/conformity/index">已整合的可信农场{{lwttinfos['lwttcount'] ? lwttinfos['lwttcount'] : 0 }}</a>{% endif %}
									</label>
	                                <div class="image">
										<img src="{{ constant('STATIC_URL') }}mdg/version2.5/images/auther-certify-image.png" alt="">
									  
									</div>

									{% else %}
									   <!--如果商盟未通过 按钮为禁用  -->
									    {% if user_lwtt== 0 %}
									        <a class="yet-btn"  href="#">我要申请</a>
									    {% elseif user_lwtt ==3 or user_lwtt ==2 %}
									       <a class="btn" href="/member/lwtt/new" >我要申请</a>
									    {% else %}
									        <a class="btn" href="#" onclick="bindbwtt()" >我要申请</a>
									    {% endif %}
	                                {% endif %}
								{% endif %}
							{% endif %}
					        {% if user_hc==1 %}
							<h6>
								<font class="f-db">县域服务中心</font>
								<span class="f-db icon2">县域服务中心</span>
							</h6>
							<p>能够整合当地产地资源，协助丰收汇开发整合更多的可信农场，并为可靠农产品交易提供物流配送支持的合作伙伴</p>
							{% if user_hc == 1 %}
								<div class="image">
									<img src="{{ constant('STATIC_URL') }}mdg/version2.5/images/auther-certify-image.png" alt="">
								</div>
							{% elseif user_hc == 0 %}
								<a class="yet-btn" href="#">我要申请</a>
							{% else %}
								<a class="btn" href="/member/userfarm/county">我要申请</a>
							{% endif %}
							{% endif %}
							</div>
						</div>
 					<div class="ma-box f-fl">
						<div class="contianer">
							<h6>
								<font class="f-db">采购企业</font>
								<span class="f-db icon3">采购企业</span>
							</h6>
							<p>经过丰收汇认证的采购企业</p>
                            {% if user_lwtt==4 or user_lwtt==5  or user_lwtt==2 or user_lwtt==3 %}
	                            {% if user_pe and user_pe.type==0 and user_pe.status==1 %}
	                               <a class="yet-btn"  href="#">我要申请</a>
	                            {% else %}
	                                {% if user_pe %}<!--如果采购企业已经申请 -->
	                                    <!--采购企业审核通过  显示认证图标 -->
		                                {% if user_pe.status == 1 %}
				                                <div class="image">
													<img src="{{ constant('STATIC_URL') }}mdg/version2.5/images/auther-certify-image.png" alt="">
												</div>
										{% else %}
											   <!--如果商盟未通过 按钮为禁用  -->
											    {% if user_pe.status== 0  %}
											        <a class="yet-btn"  href="#">我要申请</a>
											    {% else %}
											        <a class="btn" href="/member/userfarm/purchaser/2">我要申请</a> 
											    {% endif %}
		                                {% endif %}									   
									{% else %}
									   <a class="btn" href="/member/userfarm/purchaser/2">我要申请</a> 
									{% endif %}
							    {% endif %}
						    {% endif %}
						</div>	
					</div>
					<div class="ma-box ma-box2 f-fl">					
						<div class="contianer no-border">
							<h6>
								<font class="f-db">采购经纪人</font>
								<span class="f-db icon4">采购经纪人</span>
							</h6>
							<p>经过丰收汇认证的采购个人</p>
                            {% if user_lwtt==4 or user_lwtt==5  or user_lwtt==2 or user_lwtt==3 %}
	                            {% if user_pe and user_pe.type==1 and user_pe.status==1 %}
	                               <a class="yet-btn"  href="#">我要申请</a>
	                            {% else %}
	                                {% if user_pe %}<!--如果采购企业已经申请 -->
		                                    <!--采购企业审核通过  显示认证图标 -->
			                                {% if user_pe.status == 1 %}
					                                <div class="image">
														<img src="{{ constant('STATIC_URL') }}mdg/version2.5/images/auther-certify-image.png" alt="">
													</div>
											{% else %}
												   <!--如果商盟未通过 按钮为禁用  -->
												    {% if user_pe.status== 0 %}
												        <a class="yet-btn"  href="#">我要申请</a>
												    {% else %}
												        <a class="btn" href="/member/userfarm/purchaser/1">我要申请</a> 
												    {% endif %}
			                                {% endif %}
										
										   
									{% else %}
									      <a class="btn" href="/member/userfarm/purchaser/1">我要申请</a> 
									{% endif %}
							    {% endif %}
							{% endif %}
						</div>
					</div>
				</div>
				<div class="my-apply">
					<div class="my-title">我的申请</div>
					<table cellpadding="0" cellspacing="0" width="100%">
						<tr height="30">
							<th width="28"></th>
							<th align="left" width="140">我的申请</th>
							<th width="190">用户类型</th>
							<th width="198">申请时间</th>
							<th width="198">申请状态</th>
							<th width="172">操作</th>
						</tr>
						<tr height="14"></tr>
						{% if data %}
							{% for key,val in data %}
							<tr height="25">
								<td></td>
								<td>{{ val['credit_type'] }}</td>
								<td align="center">{{ val['type'] }}</td>
								<td align="center">{{ val['apply_time'] }}</td>
								<td align="center">{{ val['status_name'] }}</td>
								<td align="center">
									<a href="/member/userfarm/detail/{{ val['credit_id'] }}">查看详情</a>
									{% if val['status'] == 2 or val['status'] == 3 %}
									<a href="javascript:void(0)" onclick="del({{ val['credit_id'] }});">删除</a>
									{% endif %}
								</td>
							</tr>
							{% endfor %}
						{% else %}
							<tr>
								<td></td>
								<td colspan="5">您还没有申请任何身份，请尽快申请！</td>
							</tr>
						{% endif %}
						<tr height="14"></tr>
					</table>
				</div>
				<div class="apply-process f-oh">
					<div class="s-title">申请流程</div>
					<div class="process f-tac">
						<img src="{{ constant('STATIC_URL') }}mdg/version2.4/images/add-apply-process-img.png" alt="">
					</div>
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

<!--弹窗start-->
<div class="fsh_tongzhiCon" style="display: none;">
	<div class="wms-alert wms-alert1">
		<a class="close-btn" href="javascript:;" onclick="warnPageClose();"  ></a>
		<div class="title">提示</div>
		<font class="f-db">产地服务站认证前请先完成以下操作：</font>
		{% if checkinfo  %}
		<div class="tips f-oh">
			<span class="f-fl">- 完善账号信息</span>
			<a class="fr" href="/member/perfect/index">前去完善></a>
		</div>
		{% endif %}
		{% if checkynp  %}
		<div class="tips f-oh">
			<span class="f-fl">- 绑定云农宝</span>
			<a class="fr" href="/member/ynbbinding">前去绑定></a>
		</div>
		{% endif %}
		{% if checksell %}
		<div class="tips f-oh">
			<span class="f-fl">- 删除所有已发布的供应信息<br />（且产地服务站认证未通过时禁止发布供应信息）</span>
			<a class="fr" href="/member/sell/index">前去删除></a>
		</div>
		{% endif %}
		<div class="btns">
			<input type="button" value="确  认" onclick="warnPageClose();"  >
		</div>
	</div>
    <div class="fsh_tongzhiBg"></div>
</div>
<style>
    .fsh_tongzhiCon{ z-index:99;}
    .fsh_tongzhiCon, .fsh_tongzhiBg{ position:fixed; width:100%; height:100%; left:0; top:0; right:0; bottom:0;}
    .fsh_tongzhiBg{ background:#000; opacity: 0.4; filter: alpha(opacity=40); z-index:99;}
    .fsh_tongzhi{ width:550px; height:265px; border:1px solid #ccc; background:#fff; position:fixed; left:50%; top:50%; margin-left:-260px; margin-top:-210px; z-index:100;}
    .tongzhi_title{ font-size:24px; padding-top:10px; color:#000; text-align: center; height:50px; line-height: 50px;}
    .tongzhi_text{ margin-top:5px; font-size:14px; line-height: 26px;}
    .tongzhi_text_name{ width:430px; margin:0 auto;}
    .tongzhi_p{ width:360px; margin:0 auto; text-indent: 2em;}
    .tongzhi_p1{ margin-top:10px;}
    .tongzhi_p3{ margin-top:10px;}
    .tongzhi_link{ text-align: right; margin-top:10px;}
    .tongzhi_close{ text-align: center; margin-top:10px;}
    .tongzhi_closeBtn{ width:125px; height:32px; line-height: 32px; background:#f9ab14; color:#fff; font-size: 14px;}
    .tongzhi_close label{ margin-left:10px;}
    .tongzhi_close label input{ margin-right:5px; line-height: 30px;}
</style>
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
				window.location.reload();
			} else {
				alert(msg['result']);
				return;
			}
		}
	});
}
function   bindbwtt(){
    {% if checkinfo or checkynp or checksell %}
	$('.fsh_tongzhiCon').show();
	{% else %}
	location.href="/member/lwtt/new";
	{% endif %}
}
function warnPageClose(){
     $('.fsh_tongzhiCon').hide();
}
</script>
