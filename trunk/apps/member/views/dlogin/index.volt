<!-- 登录弹框 start -->
<link rel="stylesheet" type="text/css" href="http://yncstatic.b0.upaiyun.com/js/validator/jquery.validator.css" />
<div class="dialog" style="width: 500px;height: 200px;">
	<form action="/member/dlogin/login" method="post" id="dlogin">
    <ul>
    	<li>
    		<span class="label">手机号：</span> <em><input class="f-fl mr10" name="mobile" type="text" placeholder="手机号"  data-target="#mobile"  /><i class="f-fl mt10" id="mobile"></i> </em>
    	</li>
    	<li>
    		<span class="label">密码：</span> <em><input class="mr10 f-fl" name="password" type="password" placeholder="密码" data-target="#password" /> <i class="f-fl" id="password"></i></em> 
    	</li>
    </ul>
    <p class="f-ff0"><div class="errorMessage f-ff0" style="color:#B4272D; padding-left:107px; margin-bottom:4px;">{{ content() }}</div>

    <input type="hidden" name="go_back" value="{{ go_back }}" />
    <input type="hidden" name="islogin" value="{{ islogin }}" />
    <input class="submit_btn" type="submit" value="确认" /><font style="margin-left:10px;">还没有帐号？请
    <a style="color:#05780A; text-decoration:underline;" href="javascript:close();" >注册</a></font>
    </form>
</div>
<!-- 登录弹框 end -->

<script>
$(function() {
	$('#dlogin').validator({
        display: function(el){
            return el.getAttribute('placeholder') || '';
        },
        fields: {
            'mobile': 'required; mobile;'
            ,'password': 'required; password;'
        }
    });

    window.parent.setTitle('登录');
})

function close(){

    parent.window.location='/member/register/company'

}

</script>