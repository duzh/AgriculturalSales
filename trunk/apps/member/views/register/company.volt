{{ partial('layouts/page_header') }}
<link rel="stylesheet" href="{{ constant('STATIC_URL') }}mdg/version2.5/css/verfiy.css">
<link rel="stylesheet" href="/js/artDialog/css/ui-dialog.css">
<script src="/js/artDialog/dist/dialog-min.js"></script>
<!-- 主体内容开始 -->
<div class="wrapper">
        <div class="w1190 mtauto f-oh">
            <div class="registerBox f-fl">
            <div class="title">免费注册用户</div>
            <form action="/member/register/save" id="register" method="post">                
             
                
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
                                    <input class="code f-fl" name="vcode" type="text" placeholder="验证码" />
                                </div>
                                <input type="button" class="codeBtn f-fl" disabled="disabled"  id="getvcode"  value="发送验证码">
                            </div>
                        </div>

             
                        <div class="formBox clearfix">
                            <font>密码：</font>
                            <div class="inputBox">
                                <input name="password" type="password" placeholder="密码" />
                            </div>
                        </div>
       
                        <div class="formBox clearfix">
                            <font>确认密码：</font>
                            <div class="inputBox">
                                <input name="repassword" type="password" placeholder="重复密码" />
                            </div>
                        </div>
                     
                        <input type="hidden" name="usertype" value="1" />
                        
                        <input class="register-btn"  type="submit" value="注册" />
                        {{ content() }}
               
            </form>
            </div>
            <div class="registerRight f-fr">
                <div class="yetLogin">
                    <font>已经有账号</font><br />
                    <a href="/member/login">立即登录</a>
                </div>
                <div class="wx">
                    <img src="http://yncstatic.b0.upaiyun.com/mdg/version2.5/images/registerRight-wx.png" alt="">
                </div>
            </div>
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

    

    $('#register').validator({
        rules:{
           passwords : [ /^[\@A-Za-z0-9\!\#\$\%\^\&\*\.\~\/\-\_\(\)\+\=\?\<\>]{6,20}$/ , '请输入6-20位密码']
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
    $('#getvcode').click(function(){
        showcode();
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


var dialogs = null
function showcode(){
    if(dialogs != null){
        dialogs.show();
        return;
    }
    var dialogs = dialog({
        title: '获取短信验证码',
        width: 260,
        height:50,
        content: '<div style="line-height:35px"><input class="f-fl" style="border:solid 1px rgb(153, 153, 153);height:35px;" value="" id="img_code" placeholder="请输入验证码" /><img src="/member/vcode/getvcode" onclick="this.src = this.src+\'?\'+Math.random();" style="float:right;"></div>',
        okValue: '确定',
        ok: function () {
            if($('#img_code').val()=='') return false;
            if(_setInterval!=null) return false;
            var mo = $('input[name="mobile"]').val();
            var code = $('#img_code').val();
            $("#getvcode").attr('disabled','disabled');
            $.getJSON('getcode', {mobile:mo,code:code},function(data) {
                if(data.ok=="1"){
                    $('#img_code').val('');
                    _setInterval = setInterval('clock()',1000);
                    dialogs.close().remove();
                }else{
                    dialogs.title('验证码输入错误');
                    return false;
                }
            });
            return false;
        },
        cancelValue: '取消',
        cancel: function () {}
    });
    dialogs.showModal();
}
</script>

</body>