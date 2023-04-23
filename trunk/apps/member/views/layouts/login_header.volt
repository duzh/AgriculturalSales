   
    <div class="header-top">
        <div class="w1190 mtauto clearfix">
            <!--判断是否登录-->
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
                    <font>您好，{{ session.user['name'] }}欢迎来到云农场网上交易平台！</font>
                    <span class="userMsg">
                        <a href="/member/index">用户中心</a> |
                        <a href="/member/index/logout">退出</a>
                    </span>
                </div>
            {% endif %}
            <!--判断是否登录-->   
            <div class="menu f-fr">
                <font>
                    <a href="{{ $ynbType['URL'] }}">{{ $ynbType['TEXT'] }}</a>
                </font>
                <font>
                    <a href="/member/ordersbuy/index">我的订单</a>
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
                        <a href="http://www.5fengshou.com/article/index?p=15">联系客服</a>
                        <a href="/article/index?p=22">帮助中心</a>
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
            <form action="/index/search" >
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
                    <a href="http://www.5fengshou.com/sell/index?search_header_type=sell&keyword={{val['title']}}">{{val['title']}}
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
    <div class="header-nav">
        <div class="w1190 mtauto f-pr clearfix" style="*z-index:6;">
            <!-- 
            **  li的类名为has-erji时，表示在对应页面有二级导航
            -->
            <ul class="horizontal clearfix">
                <li {% if _nav == 'index'%}class="active"{% endif %}>
                    <a href="/index">首页</a>
                </li>
                <li {% if _nav == 'sell'%}class="active"{% endif %}>
                    <a href="/sell/index/">供应大厅</a>
                    <div class="border"></div>
                </li>
                <li {% if _nav == 'purchase'%}class="active"{% endif %}>
                    <a href="/purchase/index/">采购中心</a>
                    <div class="border"></div>
                </li>
                <li {% if _nav == 'tag'%}class='active'{% endif %}>
                    <a href="/tag/index/">可信农产品</a>
                </li>
                <li {% if _nav == 'market'%}class='active'{% endif %}>
                    <a href="/market/index/">价格行情</a>
                </li>
                <li>
                    <a href="/wuliu/index/">物流信息</a>
                </li>
                <li>
                    <a href="/advisory/adindex">资讯</a>
                </li>
            </ul>
            <!-- 
            *** 此按钮首页不应显示，其他页均应显示
            -->
		     {% if _nav != 'index' %}
            <div class="navBtn f-oh">
                <a class="gy-btn" href="/member/sell/new">发布供应</a>
                <a class="cg-btn" href="javascript:newWindows('newpur', '发布采购', '/member/dialog/newpur');">发布采购</a>
            </div>
			 {% endif %}
            <div class="vertical-navgation">
                <div class="cate">
                    <span>农产品分类</span>
                </div>
				
                <div class="vert-box clearfix" {% if _nav == "index" and _action == "index" %}style="display:block;"{% else %}style="display:none;"{% endif %}>
                    <!-- 分类 -->

                {% if haeder_cate %}
				<!--
				<div class="cateBox">
					<div class="arrow-bc">
						<span class="core core1">
							<font>我的关注</font>
						</span>
						<font></font>
					</div>

					<div class="core-box">
						<div class="fb-list1">
							<a href="#">大米</a>
						</div>
					</div>
				</div>
				-->
				
                {% for key , item in haeder_cate %}
                    <div class="cateBox cateBox{{key + 1 }}">
                        <div class="arrow-bc">
                        <span class="core core{{key + 1 }}">
                                <font>{{ item['title'] ? item['title']  :''}}</font>
                        </span>
                        </div>
                        <div class="core-box">
                             <?php if(isset($item['cate'])  && $item['cate']) { ?>
                             {% for row , val in item['cate'] %}
                            <div class="fb-list{{ row }}">
                                
                                 {% for v in val %}
                                <a href="/sell/index?first={{ v['abbreviation']}}&mc={{ v['parent_id']}}&c={{ v['id']}}">{{ v['title']}}</a>
                                {% endfor %}
                            </div>
                           {% endfor %}
                            <?php } ?>
                        </div>
                    </div>
                {% endfor %}
                {% endif %}
                </div>
            </div>

        </div>
    </div>
    <!-- 吸顶导航 -->
    {% if _nav == "index" and _action == "index" %}
    <div class="fixed-nav">
        <div class="w1190 mtauto clearfix">

            <div class="logo f-fl">
                <a href="#"><img src="{{ constant('STATIC_URL') }}mdg/version2.5/images/fixed-nav-logo.png" alt=""></a>
            </div>
            <div class="hotwords f-fl">
                {% for val in  hotsell %}
                <a href="http://www.5fengshou.com/sell/index?search_header_type=sell&keyword={{val['title']}}">{{val['title']}}
                </a><em>|</em> 
                {% endfor %}
            </div>
            <form action="/index/search" >
            <div class="searchBox f-fr">
                <div class="sou-box">
                    <div class="has-con">
                        <font class="drop-down">供应</font>
                        <span class="list">
                            <font id="sell">供应</font>
                            <font id="pur">采购</font>
                            <font id="tag">溯源码</font>
                        </span>
                        <input type="hidden" name='search_header_type'  class='header_search'  value='{{ tvalue }}'>
                    </div>
                    <div class="inputDiv">
                        <input type="text" placeholder="请输入关键词，例如：白菜、西瓜、椴木香菇" value="<?php if(isset($_GET['keyword'])&&$_GET['keyword']!=''){ echo $_GET['keyword'] ;}?>" />
                    </div>
                    <div class="inputBtn">
                        <input type="submit" value="搜索" />
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
    {% endif %}
   