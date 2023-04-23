<div class="topMenu">
    <div class="w960 f-ff0">
        {% if (session.user) %}
        <span>你好，{% if session.user['name'] %}{{ session.user['name'] }}{% else %}{{ session.user['mobile'] }}{% endif %}&nbsp;&nbsp;欢迎来到云农场农产品网上交易平台！&nbsp;&nbsp;</span>
        {% if messagecount %}<a  class="news_tip num_active " href='/member/messageuser/list'>（{{messagecount}}）</a>{% else %}<a  class="news_tip "  href='/member/messageuser/list' >（0）</a>
        {% endif %}

            <div style="display:inline; position:relative;">
                <a href="/member" id="qipaoBtn">用户中心</a>
                {% if !session.user['name']%}
                <!-- <div class="ws_qp_tips pa">
                        <a class="close_btn" href="javascript:;">关闭</a>
                        <a class="link" href="/member/">完善个人信息将有助于我们为您提供更优质的服务!</a>
                </div> -->
                {% endif %}
            </div>
            <em>|</em><a href="/member/index/logout">退出</a>
        {% else %}
        <span>你好，欢迎来到云农场农产品网上交易平台！&nbsp;请&nbsp;</span><a href="/member">登录</a><em>|</em><a href="/member/register">注册</a>
        {% endif %}

        
    </div>
</div>

<div class="topLogo" style="background:#F2F2F2;">

    <div class="w960">
        <a class="logo f-fl" href="/"><img src="{{ constant('STATIC_URL') }}mdg/images/logo.png" /></a>
        <div class="top_search f-fr">
            <img class="f-fr" src="{{ constant('STATIC_URL') }}mdg/images/a1.png" />
            <span class="f-fr">
      
            <style>
            .t_search_list{ overflow:hidden;}
            .t_search_list li{ float:left;}
            .t_search_list li a{ display:block; height:25px; line-height:25px; padding:0 20px;}
            .t_search_list li.active a{ font-weight:bold; color:#fff; background:#05780A;}
            .top_search span{ margin-top:0;}
            .top_search span .txt{ border:solid 1px #05780A;}
            .t_search_txt .t_search_input{ display:none;}
            .navList .t_nav span a{ width:auto; padding:0 12px;}
            .t_nav span font{ width:108px;}
            .navList .t_nav span font a{ padding:0; width:112px;}
            
            </style>
            <div class="mt10 f-fr">
                <ul class="t_search_list">
                    <li class="active"><a href="javscript:;">商品</a></li>
                    <li><a href="javscript:;">溯源码</a></li>
                </ul>
                <div class="t_search_txt">
                    <form action="/sell/index">
                    <span class="t_search_input" style="display:block;">
                        <input class="btn f-fr" type="submit" value="">
                        <input style="width:380px;" class="txt f-fr" type="text" name="keyword" value="{% if keyword is defined %}{{ keyword }}{% endif %}" placeholder="输入商品名称搜索供应信息" />
                    </span>
                    </form>
                    <form action="/tag/index">
                    <span class="t_search_input" style="display:none;">
                        <input class="btn f-fr" type="submit" value="">
                        <input style="width:380px;" class="txt f-fr" type="text" name="keyword" value="" placeholder="输入20位溯源码" />
                    </span>
                    </form>
                </div>
            </div>
            <script>
            $(function(){
                //头部搜索框切换
                (function(){
                    $('.t_search_list li').on('click', function(){
                        $('.t_search_list li').removeClass('active');
                        $('.t_search_txt .t_search_input').hide();
                        
                        $(this).addClass('active');
                        $('.t_search_txt .t_search_input').eq($(this).index()).show();
                    });
                })();
            });
            </script>
      

            </span>
        </div>
    </div>
</div> 


<div class="topNav">
    <div class="w960 navList">
        <div class="t_nav">
            <span class="f-fl">
                <a {% if (_nav == 'shou') %}class="active"{% endif %} href="/">首页</a>
                
                <a {% if (_nav == 'sell') %}class="active"{% endif %} href="/sell/index/">供应大厅</a>
                <a {% if (_nav == 'purchase') %}class="active"{% endif %} href="/purchase/index/">采购中心</a> 
                <a  {% if (_nav == 'market') %}class="active"{% endif %} href="/market/index/">价格行情</a>
                <a   href="/wuliu/index" target="_blank" >物流信息</a>
                <a  {% if (_nav == 'tag') %}class="active"{% endif %}  href="/tag/index" >可追溯产品</a>
                <a  {% if (_nav == 'product') %}class="hover_list active "{% else %} class="hover_list"{% endif %}  href="/product/fao/">安全种植体系</a>
                <font class="f-db">
                    <a href="/product/fao">预测模型</a>
                    <?php foreach (Mdg\Models\ProductCategory::showarticle() as $key=>$cat) { ?>
                        <a href="/product/category?c=<?php echo $cat['id']; ?>"><?php echo $cat["catname"];?></a>
                      
                    <?php }?>
                    
                </font></span>

            <em class="f-fr"><a class="btn f-fl" href="javascript:newWindows('newpur', '发布采购', '/member/dialog/newpur');">发布采购</a><a class="f-fr" href="/member/sell/new">我要卖货</a>
          
            </em>
        </div>
        <!-- 左侧导航 start -->
        <div class="l_nav">
            <h6><a href="/sell/index"><img src="{{ constant('STATIC_URL')}}mdg/images/l_nav_title.png" /></a></h6>
            <ul>
                <?php foreach (Mdg\Models\Category::showcategroy() as $key=>$cat) { ?>
                
                 <li class="icon1">
                    <em><img src="<?php echo  Mdg\Models\Image::imgsrc($cat['id']) ?>" width="28" height="29" /></em>
                    <span><a style="color:#05780A;" href="/sell/index?c=<?php echo $cat['id']; ?>"><?php echo $cat['title']; ?></a></span>
                    <?php if(!empty($cat['child'])) { ?>
                    <div class="kind_name">
                        <?php $i = 0; ?>
                        <?php foreach ($cat['child'] as $child) { ?>
                            {% if i<=2 %}
                            <?php $i +=1; ?>
                            <a href="/sell/index?c=<?php echo $child['id']; ?>"><?php echo $child['title']; ?></a>
                            {% endif %}
                        <?php } ?> 
                    </div>
                    <?php } ?>
                 </li>
                <?php } ?>
                <li class="more"><a href="/sell/index"><img src="{{ constant('STATIC_URL') }}mdg/images/l_nav_more.png" /></a></li>
            </ul>
        </div>
        <!-- 左侧导航 end -->
    </div>
</div>

<!-- 头部结束 -->
<script type="text/javascript" src="{{ constant('JS_URL') }}lhgdialog/lhgdialog.min.js?skin=igreen"></script>
<script type="text/javascript" src="{{ constant('STATIC_URL') }}/mdg/js/dialog_call.js?skin=igreen"></script>

<div class="addContianer" style="background:#F2F2F2; overflow:hidden;">
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

<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}/mdg/version2.3/css/base_index.css" />
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}/mdg/version2.3/css/index.css" />
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}/mdg/version2.3/css/service-station.css" />
