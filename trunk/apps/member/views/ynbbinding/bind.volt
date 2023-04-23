{{ partial('layouts/page_header') }}
<link rel="stylesheet" type="text/css" href="/css/jquery.validator.css" />
<div class="center-wrapper pb30">
    <div class="w1185 mtauto clearfix f-oh">
        <!-- 云农宝对接 -->
        <div class="ynb-dock">
            <div class="ynb-title">绑定云农宝</div>
            <div class="box">

                <div class="tips1">
                    系统检测到您的账户手机号（<label class="encrypt-phone">{{tmmobile}}</label>）已经注册云农宝账户。
                </div>
                <div class="tips2">是否以此手机号创建云农宝账户？</div>
                <form action="/member/ynbbinding/savedata" id="dockForm2" method="post">
                    <div class="message clearfix">
                        <font>手机验证码：</font>
                        <div class="codeBox clearfix">
                            <input id="mobile" name="mobile" type="hidden" value="{{ mobile }}" />
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
                    <input class="bind-btn" type="submit" value="立即绑定" />
                    <a class="bind-others" href="/member/ynbbinding/existent">绑定其他云农宝账户>></a>
                </form>

            </div>
        </div>

    </div>
</div>
<div id="checkPage" style="display: none;">
    <div>
        输入当前云农宝账号登录密码:
        <input type="password" id="ynbpw">
    </div>
    <input class="bind-btn" type="button" id="checkynb" value="确定" />
    <script>
        $(document).ready(function(){
//            $('#checkynb').click(function(){
//                if(!$('#ynbpw').val()){
//                    alert('请填写密码');
//                }
//                else{
//                    $.ajax({
//                        type: "POST",
//                        url: "/member/ynbbinding/checkpass",
//                        data: "pwd="+$('#ynbpw').val()+"",
//                        dataType: "text",
//                        success:function(res){
//                            if(res == true){
//                                alert('密码正确');
//                                location.href='/member/ynbbinding/existent';
//                            }
//                            else{
//                                alert('密码错误');
//                            }
//                        }
//                    });
//                }
//
//            });
        });
    </script>
</div>
<script>
    $(document).ready(function(){
        $('input[name="vcode"]').attr('disabled',true);
        $('#getCode').click(function(){
            $.ajax({
                type: "POST",
                url: "/member/ynbbinding/getcode",
                data: "moblie="+$('#moblie').val()+"&st=1",
                dataType: "text",
                success:function(res){
                    if(res){

                    }
                }
            });
        });

        // 验证
        $('#dockForm2').validator({
            rules:{
                passwords : [ /^[\@A-Za-z0-9\!\#\$\%\^\&\*\.\~\/\-\_\(\)\+\=\?\<\>]{6,20}$/ , '请输入6-20位密码']
            },
            fields: {
                'password': 'required;',
               // 'repassword' : 'required;match(password);',
                'vcode' : 'required;remote[/member/ynbbinding/checkcode, mobile];'
            },
            valid:function(form){
                $('input[type="submit"]').attr('disabled',true);
                $.ajax({
                    type: "POST",
                    url: "/member/ynbbinding/savedata",
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
    function checkpage(){
        dialog = $.dialog({
            id    : 'check',
            title : '修改云农宝',
            min   : false,
            max   : false,
            lock  : true,
            content: $('#checkPage').html()
        });///member/ynbbinding/existent
    }
</script>
<!-- 底部 -->
{{ partial('layouts/footer') }}