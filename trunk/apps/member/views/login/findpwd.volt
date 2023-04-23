<!-- 头部 -->
<link rel="stylesheet" href="{{ constant('STATIC_URL') }}mdg/version2.5/css/verfiy.css">
{{ partial('layouts/page_header') }}

<div class="wrapper">
	<div class="w1190 mtauto f-oh">

		<div class="registerBox f-fl">
			<div class="title">密码重置</div>
			<form action="resetpwd" method="post" id="findpwd">
				<div class="formBox clearfix">
					<font>手机号：</font>
					<div class="inputBox">
						<input name="mobile" type="text" />
					</div>
				</div>
				<div class="formBox clearfix">
					<font>验证码：</font>
					<div class="inputYz clearfix">
						<div class="yz-box clearfix f-fl">
							<input class="code f-fl"  name="vcode" type="text" />
						</div>
						<input type="button"  class="codeBtn f-fl hackm-btn" value="获取验证码" id="getvcode" disabled="disabled" >
					</div>
				</div>
				<input class="next-btn" type="submit" value="下一步" />
			</form>
		</div>
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


<script type="text/javascript" src="{{ constant('STATIC_URL') }}mdg/js/inputFocus.js"></script>
<script>
var limit_time = times = 60;
var _setInterval = null;

jQuery(document).ready(function(){
	var forgetInput = $('.forget_box li input[type="text"]');
	inputFb(forgetInput);

    $('#findpwd').validator({
        display: function(el){
            return el.getAttribute('placeholder') || '';
        },
        fields: {
            'mobile': 'required; mobile;',
            'vcode' : 'required;remote[checkcode, mobile];'
        }
    });

    $('input[name="mobile"]').on('invalid.field', function(e, result, me){
        // $('#vcodemsg').addClass('false_tip');
        $('#vcodemsg').text('手机号码错误，请重新输入');
    });
    $('input[name="vcode"]').on('invalid.field', function(e, result, me){
        $('#codemsg').addClass('false_tip');
        $('#codemsg').removeClass('true_tip');
        $('#codemsg').removeClass('mt10');
        $('#codemsg').text('验证码错误');
    });

    $('input[name="mobile"]').on('valid.field', function(e, result, me){
        // $('#vcodemsg').addClass('false_tip');
        // $('#vcodemsg').text('1');
    });
	
	
    $('input[name="vcode"]').on('valid.field', function(e, result, me){
        $('#codemsg').addClass('true_tip');
        $('#codemsg').addClass('mt10');
    });
    $('input[name="mobile"]').on('valid.field', function(e, result, me){
        $('#getvcode').removeAttr('disabled');
        $('#getvcode').removeClass('hackm-btn');
    });
    $('#getvcode').click(function(){
        if(_setInterval!=null) return;
        var mo = $('input[name="mobile"]').val();
        $("#getvcode").prop('disabled','disabled');
        $.getJSON('getcode', {mobile:mo},function(data) {
            if(data.ok=="1"){
                _setInterval = setInterval('clock()',1000);
            }
        });
    });
	
});
function clock(){
    times--;
    if(times==0){
        clearInterval(_setInterval);
        _setInterval = null;
        $('#getvcode').removeAttr('disabled');
        $('#getvcode').removeClass('hackm-btn');
        times = limit_time;
        $('#getvcode').val("获取验证码");
        return;
    }
    var tpl = "秒后重新发送";
    $('#getvcode').val(times+tpl);
}
</script>