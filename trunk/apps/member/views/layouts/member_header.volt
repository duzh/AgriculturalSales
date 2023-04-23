

<div class="header-top">
	<div class="w1190 mtauto clearfix">
		<!--判断是否登录-->
		{% if !session.user %}
			<div class="welcome f-fl">
				<font>您好，欢迎来到云农场网上交易平台！</font>
				<span class="userMsg">
					<a href="http://www{{ constant('CUR_DEMAIN') }}/member/login">登录</a> |
					<a href="http://www{{ constant('CUR_DEMAIN') }}/member/register/company">注册</a>
				</span>
			</div>
		
		{% elseif session.user['mobile'] %}
			<div class="welcome f-fl">
				<font>您好，{{ session.user['name'] }}欢迎来到云农场网上交易平台！</font>
				<span class="userMsg">
					<a href="http://www{{ constant('CUR_DEMAIN') }}/member/index">用户中心</a> |
					<a href="http://www{{ constant('CUR_DEMAIN') }}/member/index/logout">退出</a>
				</span>
			</div>
		{% endif %}
		<!--判断是否登录-->   
		<div class="menu f-fr">
			<font>
				<a href="http://www{{ constant('CUR_DEMAIN') }}/member/ordersbuy/index">我的订单</a>
			</font>
			<font>
				<a href="javascript:;" rel="sidebar" id="addcollect" >收藏我们</a>
			</font>
			<font class="has-con">
				<a class="drop-down" href="javascript:;">关注我们</a>
				<span class="wx">
					<img src="{{ constant('STATIC_URL') }}mdg/version2.5/images/header-wx.jpg" alt="">
				</span>
			</font>
			<font class="has-con">
				<a class="drop-down" href="javascript:;">客户服务</a>
				<span class="list">
					<a href="http://www{{ constant('CUR_DEMAIN') }}/article/index?p=15">联系客服</a>
					<a href="http://www{{ constant('CUR_DEMAIN') }}/article/index?p=12">帮助中心</a>
				</span>
			</font>
		</div>

	</div>
</div>
<div class="header-logo">
	<div class="w1190 mtauto clearfix">

		<div class="logo f-fl">
			<a href="/index"><img src="{{ constant('STATIC_URL') }}mdg/version2.5/images/logo.png" alt=""></a>
		</div>
		<!--搜索-->
		<form action="http://www{{ constant('CUR_DEMAIN') }}/index/search" >
		<div class="searchBox f-fl">
			<div class="sou-box">
				<div class="has-con">
					<font class="drop-down">
						<?php 
						$con = '供应';
						if(isset($_GET['search_header_type']) && $_GET['search_header_type'] || $_nav) {
							$t = isset($_GET['search_header_type']) ? $_GET['search_header_type'] : $_nav;
							switch ($t) {
								case  'pur':
								case 'purchase':
									$con = '采购';
									$tvalue = 'pur';
									break;
								case  'shop':
									$con = '店铺';
									$tvalue = 'shop';
									break;
								case  'tag':
									$con = '溯源码';
									$tvalue = 'tag';
									break;
								default:
									$con = '供应';
									$tvalue = 'sell';
									break;
							}
						}
						echo $con;
						?>
					</font>
					<span class="list">
						<font id="sell">供应</font>
						<font id="pur">采购</font>
						<font id="tag">溯源码</font>
					</span>
					<input type="hidden" name='search_header_type'  class='header_search'  value='{{ tvalue }}'>
				</div>
				<div class="inputDiv">
					<input type="text" placeholder="请输入关键词，例如：白菜、西瓜、椴木香菇" name="keywords" value="<?php if(isset($_GET['keyword'])&&$_GET['keyword']!=''){ echo $_GET['keyword'] ;}?>" />
				</div>
				<div class="inputBtn">
					<input type="submit" value="搜索" />
				</div>
			</div>
			<div class="hotwords">
				{% for val in  hotsell %}
				<?php $keyword=urlencode($val['title']);?>
				<a href="http://www{{ constant('CUR_DEMAIN') }}/sell/index?search_header_type=sell&keyword={{keyword}}">{{val['title']}}
				</a><em>|</em> 
				{% endfor %}
			</div>
		</div>
		</form>
		<div class="hotline f-fr">
			<img src="{{ constant('STATIC_URL') }}mdg/version2.5/images/header-line.png" alt="">
		</div>

	</div>
</div>
<div class="header-tab">
	<ul class="w1190 mtauto clearfix">
		<li class="active">
			<a href="/member/index/index">个人中心</a>
		</li>
	</ul>
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
