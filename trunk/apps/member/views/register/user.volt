{% include 'layouts/header.volt' %}
<!-- 主体内容开始 -->
<div class="login_box w960 mt20 mb20 pb30">
    <form action="/member/register/save" id="register" method="post">
        <h6>免费注册用户</h6>
        <div class="left f-fl">
            <ul class="pt30">
                <li>
                    <span class="label">手机号：</span> <em><input name="mobile" class="f-fl mr10" type="text" placeholder="手机号码" data-target="#mobile" />
                    <i class="f-fl mt10" id="mobile"></i>
                    </em> 
                </li>
                <li>
                    <span class="label">验证码：</span> 
                    <em>
                        <input name="vcode" class="f-fl mr10" type="text" placeholder="验证码" data-target="#vcode" /> 
                        
                        <input id="getvcode" type="button" value="获取验证码" disabled="disabled" />
                    </em> 
                </li>
                <li id="codemsg">
                    <span class="label">&nbsp;</span> 
                    <em><i class="f-fl mt10" id="vcode"></i></em>
                </li>
                <li>
                    <span class="label">密码：</span>
                    <em>
                        <input name="password" class="mr10 f-fl" type="password" placeholder="密码" data-target="#password" /> 
                        <i class="f-fl" id="password"></i>
                    </em>
                </li>
                <li>
                    <span class="label">重复密码：</span>
                    <em>
                        <input name="repassword" class="mr10 f-fl" placeholder="重复密码" type="password" data-target="#repassword" />
                        <i class="f-fl" id="repassword"></i>
                    </em>
                </li>
            </ul>
            <input type="hidden" name="usertype" value="1" />
            {{ content() }}
            <input class="login_btn" type="submit" value="立即注册" />
        </div>
    </form>
    <div class="right f-fl mt30">
        <p>
            已经有账号?
            <a href="/member/login/index">立即登录!</a>
        </p>
        <img class="ml100" src="{{ constant('STATIC_URL') }}mdg/images/register_wx.png" />
    </div>
</div>
<!-- 主体内容结束 -->

{{ partial('layouts/footer') }}

<script type="text/javascript" src="{{ constant('STATIC_URL') }}mdg/js/inputFocus.js"></script>
<script>
var limit_time = times = 60;
var _setInterval = null;
jQuery(document).ready(function(){
    var loginInput = $('.login_box li input[type=text]');
    inputFb(loginInput);

    $('#codemsg').hide();

    $('#register').validator({
        rules:{
           passwords : [ /^[\@A-Za-z0-9\!\#\$\%\^\&\*\.\~\/\-\_\(\)\+\=\?\<\>]{6,20}$/ , '请输入6-20位密码'],   
        },
        display: function(el){
            return el.getAttribute('placeholder') || '';
        },
        fields: {
            'mobile': 'required; mobile;remote[check]',
            'password': 'required; passwords;',
            'repassword' : 'required;match(password);',
            'vcode' : 'required;remote[checkcode, mobile];'
        }
    });

    $('input[name="mobile"]').on('valid.field', function(e, result, me){
        $('#getvcode').removeAttr('disabled');
    });

    $('input[name="vcode"]').on('invalid.field', function(e, result, me){
        $('#codemsg').show();
    });

    $('input[name="vcode"]').on('valid.field', function(e, result, me){
        $('#codemsg').hide();
    });

    $('#getvcode').click(function(){
        if(_setInterval!=null) return;
        var mo = $('input[name="mobile"]').val();
        $.getJSON('getcode', {mobile:mo},function(data) {
            if(data.ok=="1"){
                $("#getvcode").prop('disabled','disabled');
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
        times = limit_time;
        $('#getvcode').val("获取验证码");
        return;
    }
    var tpl = "秒后重新发送";
    $('#getvcode').val(times+tpl);
}
</script>

</body>