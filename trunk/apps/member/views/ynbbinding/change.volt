{{ partial('layouts/page_header') }}
<link rel="stylesheet" type="text/css" href="/css/jquery.validator.css" />
<div class="center-wrapper pb30">
    <div class="w1185 mtauto clearfix f-oh">
        <!-- 云农宝对接 -->
        <div class="ynb-dock">
            <div class="ynb-title">绑定云农宝</div>
            <div class="box">

                <div class="tips1">
                    您已绑定云农宝账户（<label class="encrypt-phone">{{tmmoblie}}</label>）。
                </div>
                <div class="tips2">确认要更改云农宝账户？</div>
                <form action="/member/ynbbinding/changedata" id="dockForm3" method="post">
                    <div class="message clearfix">
                        <font>云农宝账号：</font>
                        <div class="inputBox">
                            <input name="mobile" id="mobile" type="text" value="" />
                        </div>
                    </div>
                    <div class="m-tip">注册云农宝的手机号</div>
                    <div class="message clearfix" style="margin-top:40px;">
                        <font>手机验证码：</font>
                        <div class="codeBox clearfix">
                            <input class="code f-fl" name="vcode" data-target="#yz-code" type="text" />
                            <input class="codeBtn f-fl" type="button" id="getCode" onclick="settime(this)" value="获取验证码" />
                            <i id="yz-code"></i>
                        </div>
                    </div>
                    <div class="message clearfix">
                        <font>云农宝登录密码：</font>
                        <div class="inputBox">
                            <input name="password" type="password" />
                        </div>
                    </div>
                    <input style="display:inline;" class="bind-btn" type="submit" value="立即绑定" />
                    如果您还没有云农宝帐号，请先注册。 <a target="_blank" class="back-link" href="/member/ynbbinding/gotoregister">立即注册</a>
                    <a class="back-link" href="/member/ynbbinding">返回>></a>
                </form>

            </div>
        </div>

    </div>
</div>
<script>
    $(document).ready(function(){
        $('input[name="vcode"]').attr('disabled',true);
        $('#getCode').click(function(){
            $.ajax({
                type: "POST",
                url: "/member/ynbbinding/getcode",
                data: "mobile="+$('#mobile').val()+"&st=2",
                dataType: "text",
                success:function(res){
                    if(res){

                    }
                }
            });
        });
        // 验证22
        $('#dockForm3').validator({
            rules:{
                passwords : [ /^[\@A-Za-z0-9\!\#\$\%\^\&\*\.\~\/\-\_\(\)\+\=\?\<\>]{6,20}$/ , '请输入6-20位密码']
            },
            fields: {
                'mobile': 'required;mobile;remote[/member/ynbbinding/checkmbile, mobile];',
                'password': 'required;',
                'vcode' : 'required;remote[/member/ynbbinding/checkcode, mobile];'
            },
            valid:function(form){
                $('input[type="submit"]').attr('disabled',true);
                $.ajax({
                    type: "POST",
                    url: "/member/ynbbinding/changedata",
                    data: "mobile="+form.mobile.value+"&password="+form.password.value+"&vcode="+form.vcode.value,
                    dataType: "json",
                    success:function(res){
                        alert(res.msg);
                        if(res.type == 1){
                            window.location = "/member/index";
                        }
                        else{
                            $('input[type="submit"]').attr('disabled',false);
                        }
                    }
                });
                return false;
            }
        });
    });
    var countdown=100;
    function settime(val) {
        $('input[name="vcode"]').attr('disabled',false);
        if (countdown == 0) {
            val.removeAttribute("disabled");
            val.value="获取验证码";
            countdown = 120;
            return false;
        } else {
            val.setAttribute("disabled", true);
            val.value="重新发送(" + countdown + ")";
            countdown--;
        }
        setTimeout(function() {
            settime(val)
        },1000)
    }
</script>
<!-- 底部 -->
{{ partial('layouts/footer') }}