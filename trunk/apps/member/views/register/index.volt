{{ partial('layouts/header') }}
<!-- 主体内容开始 -->
<div class="login_box w960 mt20 mb20 pb30">
	<h6>免费注册用户</h6>
	<div class="left r_left f-fl">
		<dl>
			<dt>
				<a href="/member/register/user"><img src="{{ constant('STATIC_URL') }}mdg/images/register_icon1.png"></a></dt>
			<dd>
				<p>我有农产品要出售</p>
			</dd>
			<dd>
				<a class="btn1" href="/member/register/user">去注册</a>
			</dd>
		</dl>
		<dl>
			<dt>
				<a href="/member/register/company"><img src="{{ constant('STATIC_URL') }}mdg/images/register_icon2.png"></a></dt>
			<dd>
				<p>我要采购农产品</p>
			</dd>
			<dd>
				<a class="btn2" href="/member/register/company">去注册</a>
			</dd>
		</dl>
	</div>
	<div class="right f-fl mt30">
		<p>
			已经有账号?
			<a href="/member">立即登录!</a>
		</p>
		<img class="ml100" src="{{ constant('STATIC_URL') }}mdg/images/register_wx.png"></div>
</div>
<!-- 主体内容结束 -->

{{ partial('layouts/footer') }}