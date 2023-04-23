
 <div class="shop_topMenu">
    <div class="w960 f-ff0">
        {% if (session.user) %}
        <span>你好，{% if session.user['name'] %}{{ session.user['name'] }}{% else %}{{ session.user['mobile'] }}{% endif %}&nbsp;&nbsp;欢迎来到云农场农产品网上交易平台！&nbsp;&nbsp;</span>
        <a href="http://www.5fengshou.com/member">用户中心</a><em>|</em><a href="http://www.5fengshou.com/member/index/logout">退出</a>
        {% else %}
        <span>你好，欢迎来到云农场农产品网上交易平台！&nbsp;请&nbsp;</span><a href="http://www.5fengshou.com/member">登录</a><em>|</em>
        <a href="http://www.5fengshou.com/member/register">注册</a>
        {% endif %}
    </div>
</div>
<div class="shop_topLogo" style="background:#F2F2F2;">
    <div class="w960 clearfix">
        <a class="logo f-fl clearfix" href="http://www.5fengshou.com/">
            <img src="{{ constant('STATIC_URL') }}mdg/images/shop_logo.png" /></a>
            <a class="logo f-fl clearfix" href="/store"><font><?php echo $shop->shop_name; ?></font></a>
        
        <div class="top_search f-fr">
            <img class="f-fr" src="{{ constant('STATIC_URL') }}mdg/images/top_hotLine.png" />
        </div>
    </div>
</div> 

<div class="shop_banner w960">
        <!-- <a href="#"><img src="{{ constant('STATIC_URL') }}mdg/images/shop_banner.jpg" /></a> -->

        <a href=""><img src="{{image}}" width="960px" height="270px"/></a>

    </div>
<!-- 头部 end -->

<div class="addContianer" style="background:#F2F2F2; overflow:hidden;">
