<body>
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
    	<li class="bc1 visited">验证手机号码</li>
    	<li class="bc2 active">重新设置新密码</li>
    	<li class="bc3">完成</li>
    </ul>
    <form action="changepwd" method="post" id="resetpwd">
    <ul class="input_box">
    	<li>
        	<span class="label">设置新密码：</span>
            <em>
            	<input name="password" class="f-fl mr10" type="password" data-target="#password" />
                <i class="f-fl mt10" id="password"></i>
            </em>
        </li>
    	<li>
        	<span class="label">重复新密码：</span>
            <em>
            	<input name="repassword" class="f-fl mr10" type="password" data-target="#repassword" />
                <i class="f-fl" id="repassword"></i>
            </em>
        </li>
    </ul>
    <input type="hidden" name="vcode" value="{{vcode}}">
    <input class="next_btn next_btn2 mb10" type="submit" value="下一步">
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
jQuery(document).ready(function(){
	var forgetInput = $('.forget_box li input[type="password"]');
	inputFb(forgetInput);

    $('#resetpwd').validator({
        display: function(el){
            return el.getAttribute('placeholder') || '';
        },
        fields: {
            'password': 'required; password;',
            'repassword' : 'required;match(password);'
        }
    });
});
</script>


</body>