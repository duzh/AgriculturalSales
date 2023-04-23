<!-- 头部 -->
  <link rel="stylesheet" href="{{ constant('STATIC_URL') }}mdg/version2.5/css/verfiy.css">
{{ partial('layouts/page_header') }}
<script src="{{ constant('STATIC_URL') }}mdg/version2.5/js/form.js"></script>
<div class="wrapper">
	<div class="w1190 mtauto f-oh">

		<div class="loginBox f-fl">
			<div class="title">登录</div>
			<form method="post" action="/member/login/validatelogin" id="loginForm">
				<div class="formInput" style="*z-index:2;">
					<input type="text" name="mobile" />
				</div>
				<div class="formInput" style="*z-index:2;">
					<input type="password" name="password" />
				</div>
				<div class="loginBtn f-oh">
                    <input type="hidden" name="url" value="{{url}}">
                    <div style="padding-left:142px; color:#CC0000;">{{ content() }}</div>
					<input class="btn f-fl" type="submit" value="登  录">
					<a class="f-fr" href="/member/login/findpwd">忘记密码？</a>
				</div>
			</form>
		</div>
		<div class="loginRight f-fr">
			<div class="yetLogin">
				<font>还没有账号，马上注册</font><br />
				<a href="/member/register/company">注册</a>
			</div>
			<div class="explain">
				<p>
					注册后您可以享受：<br />
					1.保存您的个人资料<br />
					2.收藏您关注的商品<br />
					3.享受会员优惠政策<br />
					4.订阅本店商品信息
				</p>
				<p>
					请您放心注册<br />
					云农场不会恶意透漏您的个人信息
				</p>
			</div>
		</div>

	</div>
</div>



<!-- 底部 -->
{{ partial('layouts/footer') }}
    <script type="text/javascript">
    // $(function(){
    //     if(!placeholderSupport()){   // 判断浏览器是否支持 placeholder
    //         $('[placeholder]').focus(function() {
    //             var input = $(this);
    //             if (input.val() == input.attr('placeholder')) {
    //                 input.val('');
    //                 input.removeClass('placeholder');
    //             }
    //         }).blur(function() {
    //             var input = $(this);
    //             if (input.val() == '' || input.val() == input.attr('placeholder')) {
    //                 input.addClass('placeholder');
    //                 input.val(input.attr('placeholder'));
    //             }
    //         }).blur();
    //     };
    // });
    // function placeholderSupport() {
    //     return 'placeholder' in document.createElement('input');
    // };
    </script>
