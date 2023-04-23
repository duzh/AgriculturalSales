<!-- 头部 -->
<div class="header-top">
	<div class="w1190 mtauto clearfix">
	<!--未登录-->
		{% if !session.user %}
		<div class="welcome f-fl">
			<font>您好，欢迎来到云农场网上交易平台！</font>
			<span class="userMsg">
				<a href="/member/login">登录</a> |
				<a href="/member/register/company">注册</a>
			</span>
		</div>
		{% elseif session.user['mobile'] %}
		<div class="welcome f-fl">
			<font>您好，{{ session.user['name'] }} 欢迎来到云农场网上交易平台！</font>

			<span class="userMsg">
				<a href="/member/messageuser/list"></a>
			</span>

			<label class="user">
				<a href="/member/index" style="color:#7d7d7d;">用户中心</a>
			</label>
			<!--  检测用户是否完善信息 --> 
			{% if userInfo %}
			<label class="list">
				<a class="close" href="javascript:;">x</a>
				<a class="ws" href="/member/">完善个人信息</a>
			</label>
			{% endif %}&nbsp;&nbsp;|&nbsp;
            <a class="out" href="/member/index/logout" style="color:#7d7d7d;">退出</a>
		</div>
		{% endif %} 
		 
		<div class="menu f-fr">
			<font>
				<a href="/member/ordersbuy/index">我的订单</a>
			</font>
			<font>
				<a id="addcollect" href="javascript:;" rel="sidebar">收藏我们</a>
			</font>
			<font class="has-con">
				<a class="drop-down" href="javascript:;">关注我们</a>
				<span class="wx">
					<img src="{{ constant('STATIC_URL')}}/mdg/version2.5/images/header-wx.jpg" alt="">
				</span>
			</font>
			<font class="has-con">
				<a class="drop-down" href="#">客户服务</a>
				<span class="list">
					<a href="http://www.5fengshou.com/article/index?p=15">联系客服</a>
					<a href="/article/index?p=22">帮助中心</a>
				</span>
			</font>
		</div>

	</div>
</div>
<form action="/advisory/newslist" name="form1" id="form1"  onsubmit="return checkSearchForm()">
	<div class="header-logo">
		<div class="w1190 mtauto clearfix">

			<div class="logo hzx-logo f-fl" style="margin-right:90px;">
				<a href="http://www{{ constant('CUR_DEMAIN') }}"><img src="{{ constant('STATIC_URL')}}/mdg/version2.5/images/hzx-logo.png" alt=""></a>
			</div>
			<div class="searchBox hzx-searchBox f-fl">
				<div class="sou-box">
					<div class="inputDiv">
						<input type="text" placeholder="搜索资讯文章"  name="keys" {% if keys!='' %} value="{{ keys }}" {% endif %} id="keys" />
					</div>
					<div class="inputBtn">
						<input type="submit" value="搜索" />
					</div>
				</div>
			</div>
			<div class="hotline f-fr">
				<img src="{{ constant('STATIC_URL')}}/mdg/version2.5/images/header-line.png" alt="">
			</div>

		</div>
	</div>
</form>
<div class="hzx-header-nav">
	<div class="w1190 mtauto clearfix">
		<div class="nav-list">
			<a href="/advisory/adindex" <?php if (!isset($catId) || is_array($catId)) { ?> class="active" <?php } ?>>资讯首页</a>             
			<a href="/advisory/newslist?cid=3" {% if catId == 3 %} class="active" {% endif %}>新闻</a>                
			<a href="/advisory/newslist?cid=7" {% if catId == 7 %} class="active" {% endif %}>动态</a>                
			<a href="/advisory/newslist?cid=6" {% if catId == 6 %} class="active" {% endif %}>活动</a>                
			<a href="/advisory/newslist?cid=8" {% if catId == 8 %} class="active" {% endif %}>公告</a>
		</div>
	</div>
</div>
    <script type="text/javascript">
    $(function(){
        if(!placeholderSupport()){   // 判断浏览器是否支持 placeholder
            $('[placeholder]').focus(function() {
                var input = $(this);
                if (input.val() == input.attr('placeholder')) {
                    input.val('');
                    input.removeClass('placeholder');
                }
            }).blur(function() {
                var input = $(this);
                if (input.val() == '' || input.val() == input.attr('placeholder')) {
                    input.addClass('placeholder');
                    input.val(input.attr('placeholder'));
                }
            }).blur();
        };
    });
    function placeholderSupport() {
        return 'placeholder' in document.createElement('input');
    };
    </script>

<script>
function checkSearchForm()
{	
	if(document.getElementById('keys').value && document.getElementById('keys').value!='搜索咨询文章')
	{
		return true;
	}
	else
	{
		alert("请输入资讯文章关键字");
		return false;
	}
}
</script>