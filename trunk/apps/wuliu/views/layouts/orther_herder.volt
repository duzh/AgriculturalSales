<!-- 头部 start -->
<div class="headerTop">
    <div class="w960 mtauto clearfix f-pr">
        <div class="welcome f-fl">
            {% if (session.user) %}
            <span>你好，{% if session.user['name'] %}{{ session.user['name'] }}{% else %}{{ session.user['mobile'] }}{% endif %}&nbsp;&nbsp;欢迎来到云农场农产品网上交易平台！&nbsp;&nbsp;&nbsp;&nbsp;</span>
            {% if ordercount %}<a  class="news_tip num_active " href='/member/orderssell/index' >（{{ordercount}}）</a>{% else %}<a  class="news_tip " href='/member/orderssell/index'>（0）</a>{% endif %}
            <a href="/member">用户中心</a><em>|</em><a href="/member/index/logout">退出</a>
            {% else %}
            <span>你好，欢迎来到云农场农产品网上交易平台！&nbsp;&nbsp;&nbsp;&nbsp;请&nbsp;&nbsp;</span>
            <a href="/member">登录</a>
            <em>|</em>
            <a href="/member/register">注册</a>
            {% endif %}
        </div>
        <div class="ours f-fr">
            <a class="save_us" href="javascript:;">收藏我们</a>
            <a class="sure_us" href="javascript:;">关注我们</a>
        </div>
        <div class="sure_weixin pa">
            <img src="/mdg/images/weixin.png" />
        </div>          
    </div>
</div>

<div class="header">

    <div class="menu w960 mtauto clearfix">
        <h1 class="f-fl">
            <a href="/"><img src="/mdg/images/logo.png" /></a>
        </h1>
        <div class="search f-fl">
            <form action="/sell/index/"  method="get" >
                <div class="box clearfix">
                    <input class="txt f-fl" type="text" name="keyword" value="{% if keyword is defined %}{{ keyword }}{% endif %}"
                     placeholder="寻找您最信赖的农产品" />
                    <input class="btn f-fl" type="submit" value="搜索" />
                </div>
            </form>
            <div class="keywords">
                    <?php foreach (Mdg\Models\Sell::getshop() as $key=>$sell ){ ?>
                        <a href="/sell/index?keyword=<?php echo $sell['title']; ?>"><?php echo $sell["title"];?></a>
                        {% if key < 3 %}
                        <em>|</em>
                        {% endif %}
                    <?php }?>
                
            </div>
        </div>
        <div class="hotLine f-fr">
            <img src="{{ constant("VARSION_URL")}}mdg/images/header_hotLine.png" />
        </div>
    </div>
    
</div>

<div class="navgation">
    <div class="w960 mtauto clearfix f-pr">
        <ul class="horiz-nav clearfix">

            <li {% if (_nav == 'shou') %}class="active" {% endif %} >
                <a href="/">首页</a>
            </li>
            <li {% if (_nav == 'sell') %}class="active" {% endif %}>
                <a href="/sell/index/">供应大厅</a>
            </li>
            <li {% if (_nav == 'purchase') %}class="active" {% endif %}>
                <a href="/purchase/index/">采购中心</a>
            </li>
            <li>
                <a href="/advisory/index/">价格行情</a>
            </li>
            <li>
                <a href="/product/fao/">安全种植体系</a>
            </li>
        </ul>
        <div class="vertical-nav pa" id="nav">
            <h5>
                <span><img src="/mdg/images/vertical-nav-title.png" /></span>
            </h5>
            <div class="vertical-nav-list" style="display:none;">
            
                <div class="fore">
                    <div class="fore1 clearfix">
                        <span>
                            <a href="/sell/index?onec=7" class="foreTitle">粮油</a>
                        </span>
                        <em class="f-fr">&gt;</em>
                    </div>
                    <?php $arr= Mdg\Models\Category::gettopcate(); $twocate=$arr["twocategory"];$cate=$arr["cate"];?>
                    <div class="foreBox" style="top:0;">
                        <div class="erji">                                                           
                            {% for  key,val in  twocate %}
                                {% if val["parent_id"]== 7 %}
                                <a  href="javascript:;"    onclick="showtopcate1({{val['id']}})" {% if cate["7"] == val['id'] %} class="active" {% endif %} >
                                    {{val['title']}}
                                </a>
                                {% endif %}
                            {% endfor %}  
                        </div>
                        <div class="sanji" id="showtop1">
                      
                        </div>
                    </div>
                </div>
                <div class="fore">
                    <div class="fore2 clearfix">
                        <span>
                            <a href="/sell/index?onec=1" class="foreTitle">蔬菜</a>
                        </span>
                        <em class="f-fr">&gt;</em>
                    </div>
                    <div class="foreBox" style="top:0;">
                        <div class="erji">
                            {% for  key,val in  twocate %}
                                {% if val["parent_id"]== 1 %}
                                <a  href="javascript:;"    onclick="showtopcate2({{val['id']}})" {% if cate["1"] == val['id'] %} class="active" {% endif %} >
                                    {{val['title']}}
                                </a>
                                {% endif %}
                            {% endfor %}  
                        </div>
                        <div class="sanji" id="showtop2">
                          
                        </div>
                    </div>
                </div>
                <div class="fore">
                    <div class="fore3 clearfix">
                        <span>
                            <a href="/sell/index?onec=2" class="foreTitle">水果</a>
                        </span>
                        <em class="f-fr">&gt;</em>
                    </div>
                    <div class="foreBox" style="top:0;">
                        <div class="erji">
                            {% for  key,val in  twocate %}
                                {% if val["parent_id"]== 2 %}
                                <a  href="javascript:;"    onclick="showtopcate3({{val['id']}})" {% if cate["2"] == val['id'] %} class="active" {% endif %} >
                                    {{val['title']}}
                                </a>
                                {% endif %}
                            {% endfor %}  
                           
                        </div>
                        <div class="sanji" id="showtop3">
                           
                        </div>
                    </div>
                </div>
                <div class="fore">
                    <div class="fore4 clearfix">
                        <span>
                            <a href="/sell/index?onec=1377" class="foreTitle">园艺</a>
                        </span>
                        <em class="f-fr">&gt;</em>
                    </div>
                    <div class="foreBox" style="bottom:0;">
                        <div class="erji">
                           {% for  key,val in  twocate %}
                                {% if val["parent_id"]== 1377 %}
                                <a  href="javascript:;" onclick="showtopcate4({{val['id']}})" {% if cate["1377"] == val['id'] %} class="active" {% endif %} >
                                    {{val['title']}}
                                </a>
                                {% endif %}
                            {% endfor %}  
                        </div>
                        <div class="sanji" id="showtop4" >
                           
                        </div>
                    </div>
                </div>
                <div class="fore">
                    <div class="fore5 clearfix">
                        <span>
                            <a href="/sell/index?onec=899" class="foreTitle">其他</a>
                        </span>
                        <em class="f-fr">&gt;</em>
                    </div>
                    <div class="foreBox" style="bottom:0;">
                        <div class="erji">
                             {% for  key,val in  twocate %}
                                {% if val["parent_id"]== 899 %}
                                <a  href="javascript:;"    onclick="showtopcate5({{val['id']}})" {% if cate["899"] == val['id'] %} class="active" {% endif %} >
                                    {{val['title']}}
                                </a>
                                {% endif %}
                            {% endfor %}  
                        </div>
                        <div class="sanji">
                            <div class="sj-list" id="showtop5">
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
                
            </div>
            
        </div>
        
    </div>  
</div>
{{ partial('layouts/topcate') }}

<script>
	$('#nav .vertical-nav-list').hide();
	$('#nav').hover(function(){
		$(this).find('h5 span').addClass('show');
		$(this).find('.vertical-nav-list').slideDown();
	}, function(){
		$(this).find('h5 span').removeClass('show');
		$(this).find('.vertical-nav-list').slideUp();
	});
	$('.fore').hover(function(){
		$(this).addClass('active');
	}, function(){
		$(this).removeClass('active');
	});
	$('.foreBox .erji a').bind('click', function(){
		$(this).parent().find('a').removeClass('active');
		$(this).addClass('active');
	});
</script>
<div style="background:#f2f2f2;">

