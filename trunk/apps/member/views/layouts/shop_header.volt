<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/css/base.css" />
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/css/topBottom.css" />
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/css/personalCenter.css" />
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/css/registerLogin.css" /> 
<link rel="stylesheet" type="text/css" href="{{ constant('JS_URL') }}validator/jquery.validator.css" />
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/css/line.css" />

<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}validator/jquery.validator-src.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}validator/local/zh_CN.js"></script>
<script type="text/javascript" src="{{ constant('STATIC_URL') }}mdg/js/navSlide.js"></script>
<script type="text/javascript" src="{{ constant('STATIC_URL') }}mdg/js/center_navSlide.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/ld-select.js"></script>
<script type="text/javascript" src="{{ constant('STATIC_URL') }}mdg/js/navList_slider.js"></script>
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/css/topBottom_erji.css" />
<script type="text/javascript" src="{{ constant('STATIC_URL') }}DatePicker/DatePicker/WdatePicker.js"></script>
<!-- <script type="text/javascript" src="{{ constant('JS_URL') }}mdg/js/accordion.js"></script> -->
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/css/fukuan.css" />
<!-- 处理IE6下透明度 -->
<!--[if IE 6]>
    <script type="text/javascript" src="{{ constant('STATIC_URL') }}mdg/js/DD_belatedPNG_0.0.8a-min.js"></script>
    <script type="text/javascript" src="{{ constant('STATIC_URL') }}mdg/js/Fixpng.js"></script>
<![endif]-->
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/css/news_tip.css" />
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/css/shop_manage.css" />
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/css/shop_decoration.css" />
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/css/about_ynbao.css" />

<link rel="stylesheet" type="text/css" href="/mdg/css/base_index.css" />
<link rel="stylesheet" type="text/css" href="/mdg/css/index.css" />
<link rel="stylesheet" type="text/css" href="/mdg/css/service-station.css" />
<script type="text/javascript" src="{{ constant('JS_URL') }}lhgdialog/lhgdialog.min.js?skin=igreen"></script>
<script type="text/javascript" src="{{ constant('STATIC_URL') }}/mdg/js/dialog_call.js?skin=igreen"></script>
<div class="shop_topMenu">
    <div class="w960 f-ff0">
        {% if (session.user) %}
        <span>你好，{% if session.user['name'] %}{{ session.user['name'] }}{% else %}{{ session.user['mobile'] }}{% endif %}&nbsp;&nbsp;欢迎来到云农场农产品网上交易平台！&nbsp;&nbsp;</span><a href="/member">用户中心</a><em>|</em><a href="/member/index/logout">退出</a>
        {% else %}
        <span>你好，欢迎来到云农场农产品网上交易平台！&nbsp;请&nbsp;</span><a href="/member">登录</a><em>|</em><a href="/member/register">注册</a>
        {% endif %}
    </div>
</div>
<div class="shop_topLogo">
    <div class="w960 clearfix">
        <a class="logo f-fl clearfix" href="/sell/index">
            <img src="{{ constant('STATIC_URL') }}mdg/images/shop_logo.png" />
            <font>店铺管理</font>
        </a>
        <div class="top_search f-fr">
            <img class="f-fr" src="{{ constant('STATIC_URL') }}mdg/images/top_hotLine.png" />
        </div>
    </div>
</div>
<div class="wrapper" style="background:#f2f2f2;">
