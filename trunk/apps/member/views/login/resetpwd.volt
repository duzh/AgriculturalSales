<!-- 头部 -->
<link rel="stylesheet" href="{{ constant('STATIC_URL') }}mdg/version2.5/css/verfiy.css">
{{ partial('layouts/page_header') }}
<script src="{{ constant('STATIC_URL') }}mdg/version2.5/js/form.js"></script>
<div class="wrapper">
	<div class="w1190 mtauto f-oh">

		<div class="registerBox f-fl">
			<div class="title">密码重置</div>
			<form action="changepwd" method="post"  id="registerForm">
				<div class="formBox clearfix">
					<font>密码：</font>
					<div class="inputBox">
						<input name="password" type="password" />
					</div>
				</div>
				<div class="formBox clearfix">
					<font>确认密码：</font>
					<div class="inputBox">
						<input name="repassword" type="password" />
					</div>
				</div>
				<input type="hidden" name="vcode" value="{{vcode}}">
				<input class="carry-btn" type="submit" value="完成" />
			</form>
		</div>
		<div style="padding-left:142px; color:#CC0000;">{{ content() }}</div>
		<div class="registerRight f-fr">
			<div class="yetLogin">
				<font>已经有账号／没有账号，马上注册</font><br />
				<a href="/member/login">立即登录</a><br />
				<a href="/member/register/company">注册</a>
			</div>
			<div class="wx">
				<img src="http://yncstatic.b0.upaiyun.com/mdg/version2.5/images/registerRight-wx.png" alt="">
			</div>
		</div>

	</div>
</div>

<!-- 底部 -->
{{ partial('layouts/footer') }}
