<!-- 头部开始 -->
<div class="login_top">
	<div class="w960">
    	<a href="javascript:;"><img src="{{ constant('STATIC_URL') }}mdg/images/login_logo.png"></a>
        <h5>欢迎登录</h5>
    </div>
</div>
<!-- 头部结束 -->

<!-- 主体内容开始 -->
<div class="forget_box w960 mt20 mb20 pb30">
	<h6>找回密码</h6>
    <ul class="statu_bc">
    	<li class="bc1">验证手机号码</li>
    	<li class="bc2">重新设置新密码</li>
    	<li class="bc3">完成</li>
    </ul>
    <form action="resetpwd" method="post" id="findpwd">
        <ul class="input_box">
        	<li>
            	<span class="label">手机号：</span>
                <em>
                	<input class="mr10" name="mobile" type="text" data-target="#a" />
                    <input type="button" value="获取验证码" id="getvcode">
                </em>
                <p class="yz_tip clear f-ff0" id="vcodemsg">验证码已发送，请查收短信!</p>
            </li>
        	<li>
            	<span class="label">验证码：</span>
                <em>
                	<input name="vcode" class="f-fl mr10" type="text" data-target="#a">
                    <i class="f-fl" id="codemsg"></i>
                </em>
            </li>
        </ul>
    <input class="next_btn mb10" type="submit" value="下一步">
    </form>
</div>
<!-- 主体内容结束 -->

<!-- 底部开始 -->
<div class="footer">
	<div class="f_contact">
    	<div class="left f-fl">
        	<dl>
            	<dt>新手上路</dt>
                <dd><img src="{{ constant('STATIC_URL') }}mdg/images/helpList_h_line.png"></dd>
                <dd><a href="javascript:;">免责条款</a></dd>
                <dd><a href="javascript:;">产品质量保证</a></dd>
                <dd><a href="javascript:;">购物流程</a></dd>
            </dl>
        	<dl>
            	<dt>服务保证</dt>
                <dd><img src="{{ constant('STATIC_URL') }}mdg/images/helpList_h_line.png"></dd>
                <dd><a href="javascript:;">售后服务保证</a></dd>
                <dd><a href="javascript:;">退换货原则</a></dd>
                <dd><a href="javascript:;">售后流程</a></dd>
            </dl>
        	<dl>
            	<dt>联系我们</dt>
                <dd><img src="{{ constant('STATIC_URL') }}mdg/images/helpList_h_line.png"></dd>
                <dd><a href="javascript:;">关于云农场</a></dd>
                <dd><a href="javascript:;">联系我们</a></dd>
            </dl>
        </div>
        <div class="f-fl mg"><img src="{{ constant('STATIC_URL') }}mdg/images/helpList_s_line.png"></div>
        <div class="f-fl"><img src="{{ constant('STATIC_URL') }}mdg/images/footer_contact_img-04.png"></div>
    </div>
</div>
<div class="fIcp">
	<p>Copyright ©2014,版权所有北京天辰云农场有限公司，京ICP备14023165号-2</p>
    <p>北京天辰云农场有限公司 北京市朝阳区东三环中路39号建外SOHO东区9号楼22F</p>
</div>
<!-- 底部结束 -->

<script type="text/javascript" src="{{ constant('STATIC_URL') }}mdg/js/inputFocus.js"></script>
<script>
var limit_time = times = 90;
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
            'vcode' : 'required;remote[checkcode];'
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

    $('#getvcode').click(function(){
        if(_setInterval!=null) return;
        var mo = $('input[name="mobile"]').val();
        $.getJSON('getcode', {mobile:mo},function(data) {
            if(data.ok=="1"){
                $(this).attr('disabled','disabled');
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