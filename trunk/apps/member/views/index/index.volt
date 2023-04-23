<!--头部-->
{{ partial('layouts/member_header') }}
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/version2.5/css/jquery.powertip.css" />
<script type="text/javascript" src="{{ constant('STATIC_URL') }}mdg/version2.5/js/jquery.powertip.min.js"></script>
<style>
#powerTip{ padding: 10px; max-width:242px; color:#999; font-family:'宋体'; line-height:24px; background:#fff2d8; white-space:normal; word-wrap:break-word;}
#powerTip.sw-alt:before, #powerTip.se-alt:before, #powerTip.s:before{ border-bottom:10px solid #fff2d8;}
#powerTip.nw-alt:before, #powerTip.ne-alt:before, #powerTip.n:before{ border-top:10px solid #fff2d8;}
</style>
<div class="wrapper">
	<div class="w1190 mtauto f-oh">
		<div class="bread-crumbs w1185 mtauto">
            <span>{{ partial('layouts/ur_here') }}我的信息</span>
        </div>
		<!-- 左侧 -->
		{{ partial('layouts/navs_left') }}
		<!-- 右侧 -->
		<div class="center-right f-fr">
			<div class="purchase-sell clearfix">
				<div class="pur-box f-fl f-oh">
					<div class="title">
						<span>我的采购</span>
					</div>
					<div class="m-box f-oh f-fl">
						<a href="/member/ordersbuy/index?state=3">
							<dl class="clearfix f-fl">
								<dt class="icon1 f-fl"></dt>
								<dd class="zi f-fr">待付款</dd>
								<dd class="num f-fr">{{no_pay }}</dd>
							</dl>
						</a>
					</div>
					<div class="m-box f-oh f-fl">
						<a href="/member/ordersbuy/index?state=5">
							<dl class="clearfix f-fl">
								<dt class="icon2 f-fl"></dt>
								<dd class="zi f-fr">待收货</dd>
								<dd class="num f-fr">{{no_receiving}}</dd>
							</dl>
						</a>
					</div>
					<div class="m-box f-oh f-fl">
						<a href="/member/ordersbuy/index?state=4">
							<dl class="clearfix f-fl">
								<dt class="icon3 f-fl"></dt>
								<dd class="zi f-fr">待发货</dd>
								<dd class="num f-fr">{{no_delivery}}</dd>
							</dl>
						</a>
					</div>
				</div>
				<div class="sell-box f-fl f-oh">
					<div class="title">
						<span>我的销售</span>
					</div>
					<div class="m-box f-oh f-fl">
						<a href="/member/orderssell/index?state=3">
							<dl class="clearfix f-fl">
								<dt class="icon1 f-fl"></dt>
								<dd class="zi f-fr">待付款</dd>
								<dd class="num f-fr">{{no_pay_sell}}</dd>
							</dl>
						</a>
					</div>
					<div class="m-box f-oh f-fl">
						<a href="/member/orderssell/index?state=5">
							<dl class="clearfix f-fl">
								<dt class="icon2 f-fl"></dt>
								<dd class="zi f-fr">待收货</dd>
								<dd class="num f-fr">{{no_receiving_sell}}</dd>
							</dl>
						</a>
					</div>
					<div class="m-box f-oh f-fl">
						<a href="/member/orderssell/index?state=4">
							<dl class="clearfix f-fl">
								<dt class="icon3 f-fl"></dt>
								<dd class="zi f-fr">待发货</dd>
								<dd class="num f-fr">{{no_delivery_sell}}</dd>
							</dl>
						</a>
					</div>
				</div>
			</div>
			{% if users.is_broker == 0 %}
			{% if ynp_user_phone == 0 %}
			<!-- 云农宝状态1 -->
		<div class="my-ynb pb30">
				<div class="title f-oh">
					<span>云农宝</span>
				</div>
				<div class="tips">您现在还没有绑定云农宝帐号！</div>
				<a class="bind-link" href="/member/ynbbinding">立即绑定</a>
			</div>
			
			 {% else %}
			<!-- 云农宝状态2 -->
			<div class="my-ynb pb30 f-oh">
				<div class="title f-oh">
					<span>云农宝</span>
					<a target="_blank" href="/member/ynbbinding/gotoynb">进入我的云农宝&gt;</a>
				</div>
				<div class="left f-fl">
					<div class="name">
						<span>账户名：{{ynp_user_phone}}</span>
						<a href="javascript:checkpage();">更换账号</a>
					</div>
                    <div id="checkPage" style="display: none;">
                        <div class="clearfix pt20 pb20">
                            <font class="f-db">输入当前云农宝账号登录密码：</font>
                            <input style="width:200px; height:38px; line-height:38px; border:solid 1px #ccc; padding:0 4px; display:block; margin-top:10px;" type="password" id="ynbpw">
                        </div>
                        <input  style="width:120px; height:40px; text-align:center; line-height:40px; color:#fff; background:#f9ab14; font-size:14px; border-radius:2px; cursor:pointer; display:block; margin:0 auto; margin-bottom:20px;" class="bind-btn" type="button" id="checkynb" value="确定" />

                        <script>
                            $(document).ready(function(){
                                $('#checkynb').css('background','#888888');
                                $('#checkynb').attr('disabled',true);
                                $('#ynbpw').keyup(function(){
                                    if($(this).val().length>0){
                                        $('#checkynb').css('background','#f9ab14');
                                        $('#checkynb').attr('disabled',false);
                                    }
                                    else{
                                        $('#checkynb').css('background','#888888');
                                        $('#checkynb').attr('disabled',true);
                                    }
                                });
                                $('#checkynb').click(function(){
                                    if(!$('#ynbpw').val()){
                                        alert('请填写密码');
                                    }
                                    else{
                                        $.ajax({
                                            type: "POST",
                                            url: "/member/ynbbinding/checkpass",
                                            data: "pwd="+$('#ynbpw').val()+"",
                                            dataType: "json",
                                            success:function(res){
                                                if(res.status == true){
                                                    //alert('密码正确');
                                                    location.href='/member/ynbbinding/change';
                                                }
                                                else{
                                                    alert(res.msg);
                                                }
                                            }
                                        });
                                    }

                                });
                            });
                            function checkpage(){
                                dialog = $.dialog({
                                    id    : 'check',
                                    title : '更换账号',
                                    min   : false,
                                    max   : false,
                                    lock  : true,
                                    content: $('#checkPage').html()
                                });///member/ynbbinding/existent
                            }
                        </script>
                    </div>
					<div class="ren">实名认证：已认证</div>
				</div>
				<!--<div class="right f-fl">-->
					<!--<div class="price">-->
						<!--<span>账户余额：<i>12398.00</i> 元</span>-->
						<!--<span>可用余额：<i>10000.00</i> 元</span>-->
					<!--</div>-->
					<!--<div class="btns">-->
						<!--<a href="#">充值</a>-->
						<!--<a href="#">转账</a>-->
						<!--<a href="#">提现</a>-->
					<!--</div>-->
				<!--</div>-->
			</div>
		 {% endif %}
		 {% endif %}
			<div class="post-recommon f-oh">
				<div class="post-box f-fl">
					<div class="title f-oh">
						<span>公告</span>
						<a href="/advisory/newslist?cid=8">更多&gt;</a>
					</div>
					{% for key,val in advisory_notice %}
					<div class="m-box f-oh">
						<a href="/advisory/adinfo?id={{val['id']}}">
							<span class="f-fl"><?php echo  \Lib\Func::sub_str($val['title'], 15, true) ?></span>
							<em class="f-fr">{{date('Y-m-d',val['updatetime'])}}</em>
						</a>
					</div>
					{% endfor %}
				</div>
				
				<div class="recommon-box f-fr">
					<div class="title f-oh">
						<span>热门新闻</span>
						<a href="/advisory/newslist?cid=3">更多&gt;</a>
					</div>
					{% for key,val in advisory_hot %}
					<div class="m-box f-oh">
						<a href="/advisory/adinfo?id={{val['id']}}">
							<span class="f-fl"><?php echo  \Lib\Func::sub_str($val['title'], 20, true) ?></span>
							<em class="f-fr">{{date('Y-m-d',val['updatetime'])}}</em>
						</a>
					</div>
					{% endfor %}
				</div>
			</div>

		</div>		

	</div>
</div>

<!--弹窗start-->
<div class="fsh_tongzhiCon" style="display: none;">
    <div class="fsh_tongzhiBg"></div>
    <div class="fsh_tongzhi">
        <div class="fsh_touzhiText">
            <h2 class="tongzhi_title">通知</h2>
            <div class="tongzhi_text">
                <div class="tongzhi_text_name">
                    注册/绑定云农宝
                </div>
                <div class="tongzhi_p tongzhi_p1">尊敬的会员您好,为了您的资金安全,请您绑定云农宝帐号.</div>
                <div class="tongzhi_p tongzhi_p2">云农宝将更好的保障您的资金按期,满足您资金管理及提现的需求.</div>
                <!--<div class="tongzhi_p tongzhi_p3">注：对于9月27日零点之前的订单，还按之前支付流程进行。丰收汇团队-->
                    <!--2015年9月14日</div>-->
            </div>
            <div class="tongzhi_close">
                <input type="button" value="关闭" class="tongzhi_closeBtn" onclick="warnPageClose();" />
            </div>
        </div>
    </div>
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
<script>
    $(document).ready(function(){
         {% if noWarn != 1 %}$('.fsh_tongzhiCon').show();{% endif %}
    });
    function warnPageClose(){
         $('.fsh_tongzhiCon').hide();
    }
</script>
{% if noWarn==1 %}
<?php if(isset($_SESSION["user"]["mobile"])&&isset($_SESSION["user"]["id"]))
 { $lwttinfos=Mdg\Models\UserInfo::getlwttlist($_SESSION["user"]["id"],$_SESSION["user"]["mobile"]);  } else{$lwttinfos=false;} ?>
<!--弹窗start-->
{% if lwttinfos and lwttinfos['lwttstate'] %}
<div class="usermember_fsh_tongzhiCon" style="display: none;">
    <div class="usermember_fsh_tongzhiBg"></div>
    <div class="wms-alert wms-alert1" >
		<a class="close-btn" href="javascript:;" onclick="usermemberwarnPageClose();" ></a>
		<div class="title">提示</div>
			<h5>恭喜，产地服务站认证已经通过！</h5>
			<div class="m-tips">
				1.您现在可以整合可信农场了；
				<br/>
				2.您现在可以发布供应信息。
			</div>
			<div class="mouse-tips">
				<a class="south-west-alt" title="1.产地服务站与已注册成为丰收汇会员的农场进行沟通；<br />2.该农场进行身份认证中的可信农场认证时，填写产地服务站用于注册丰收汇的手机账户；<br />3.平台审核通过后，该农场即成为产地服务站整合的可信农场。" href="javascript:;">如何整合可信农场</a>
				<em>|</em>
				<a class="south-west-alt" title="1.在丰收汇中登录您用于产地服务站认证的会员账户；<br />2.在身份认证页面，点击产地服务站认证区域上的“已整合的可信农场”即可查看。" href="javascript:;">如何查看已整合的可信农场</a>
			</div>
			<div class="btns">
				<input type="button" value="关  闭" onclick="usermemberwarnPageClose();" >
			</div>        
	</div>
</div>
<style>
    .usermember_fsh_tongzhiCon{ z-index:99;}
    .usermember_fsh_tongzhiCon, .usermember_fsh_tongzhiBg{ position:fixed; width:100%; height:100%; left:0; top:0; right:0; bottom:0;}
    .usermember_fsh_tongzhiBg{ background:#000; opacity: 0.4; filter: alpha(opacity=40); z-index:99;}
</style>
{% endif %}
<script>

{% if lwttinfos['lwttstate']==1   and  !lwttstate %}
      $('.usermember_fsh_tongzhiCon').show();
{% elseif lwttinfos['lwttcount']>=1 and !lwttcount %}
      // $('.usermember_fsh_tongzhiCon').show();
{% endif %}
 function usermemberwarnPageClose(){
		{% if lwttinfos['lwttstate']==1 %}var type1=1;{% else %}var type1=0;{% endif %}
		{% if lwttinfos['lwttcount']>=1 %}var type2=1;{% else %}var type2=0;{% endif %}
		var id={{lwtt}};
		$.get('/member/index/closewarn', {type1:type1,type2:type2,credit_id:id}, function(data) {
		});
      	$('.usermember_fsh_tongzhiCon').hide();
}
$('.south-west-alt').powerTip({
    placement: 's',
    smartPlacement: true
});
</script>

{% endif %}
<!--弹窗end-->
<!--底部-->
{{ partial('layouts/footer') }}