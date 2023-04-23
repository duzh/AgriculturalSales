    <div class="header-top">
        <div class="w1185 mtauto clearfix">
            {% if !session.user %}
            <!-- 未登录 -->
            <div class="welcome f-fl">
                <font>您好，欢迎来到云农场网上交易平台！</font>
                <span>
                    请
                    <a href="/member/login">登录</a>&nbsp;&nbsp;|&nbsp;
                    <a href="/member/register">注册</a>
                </span>
            </div>
            {% elseif session.user['mobile'] %}

             <div class="welcome-login f-fl">
                <font>您好，{{ session.user['name'] }}   欢迎来到云农场网上交易平台！</font>
                <a class="news" href="/member/messageuser/list">({{ messagecount }})</a>
                <span>
                    <label class="user">
                        <a href="/member/index">用户中心</a>
                    </label>

                    <!--  检测用户是否完善信息 --> 
                    {% if userInfo %}
                    <label class="list">
                        <a class="close" href="javascript:;">x</a>
                        <a class="ws" href="/member/">完善个人信息</a>
                    </label>
                    {% endif %}

                </span>&nbsp;&nbsp;|&nbsp;
                <a class="out" href="/member/index/logout">退出</a>
            </div>
            {% endif %}

            <div class="menu f-fr">
                <font>
                    <a href="/member/ordersbuy/index">我的订单</a>
                </font>
                <font>
                    <a id="addcollect" href="javascript:;" rel="sidebar">收藏我们</a>
                </font>
                <font class="has-list">
                    <a class="attr" href="javascript:;">关注我们</a>
                    <span class="wx">
                        <img src="{{ constant('STATIC_URL')}}/mdg/version2.4/images/header-wx.jpg">
                    </span>
                </font>
                <font class="has-list">
                    <a class="attr" href="javascript:;">客户服务</a>
                    <span class="list">
                        <!-- <a href="#">在线客服</a> -->
                        <a href="http://www.5fengshou.com/article/index?p=15">联系客服</a>
                        <a href="/article/index?p=22">帮助中心</a>
                    </span>
                </font>
            </div>

        </div>
    </div>
    <!-- 搜索 -->
    <div class="header-search">
        <div class="w1185 mtauto clearfix">
            <a class="logo f-fl" href='/index'>
                <img src="{{ constant('STATIC_URL')}}/mdg/version2.4/images/logo.png">
            </a>
            <form action="/index/search" >
            <div class="search-box f-fl">
                <div class="left f-fl clearfix">

                    <div class="selectBox f-fl">
                        <span>
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
                        </span>
                        <div class="list">
                            <!-- <a href="javascript:;" id='all'><font>全部</font></a> -->
                            <a href="javascript:;" id='sell'><font>供应</font></a>
                            <a href="javascript:;" id='pur'><font>采购</font></a>
                            <!-- <a href="javascript:;" id='shop'><font>店铺</font></a> -->
                            <a href="javascript:;" id='tag'><font>溯源码</font></a>
                        </div>
                    </div>
                    <div class="textBox f-fl">
                        <input type="text" name='keywords' value="<?php if(isset($_GET['keyword'])&&$_GET['keyword']!=''){ echo $_GET['keyword'] ;}?>" placeholder="商品名/编号/用户" style="line-height:40px; width: 326px; height:40px; margin-left: 36px; border :0 none; padding: 0 14px;" />
                    </div>
                </div>
                <input type="hidden" name='search_header_type'  id='header_search'  value='{{ tvalue }}'>
                <input class="btn f-fl" type="submit" value="搜索" />
            </div>
            </form>
            <img class="f-fr hotLine" src="{{ constant('STATIC_URL')}}/mdg/version2.4/images/header-hotLine.jpg">
        </div>
    </div>
    
    <!-- 导航 -->
    <div class="header-navigation" style="position:relative; z-index:1000;">
        <div class="w1185 mtauto clearfix f-pr">

            <ul class="across-list clearfix">
                
                <li {% if _nav == 'index'%}class='active'{% endif %}>
                    <a href="/index" > 首页</a>
                </li>
                <li class="has-list {% if _nav == 'sell'%}active{% endif %}" >
                    <a href="/sell/index/" <?php echo $_nav=='sell'&&$_action!='info' ? "class='hover'" : ''; ?>>供应大厅</a>
                    <div class="small-list commonW1 samll-list-minwidth"  <?php echo $_nav=='sell'&&$_action!='info' ? "style='display:block'" : ''; ?> >
                        {% if haeder_cate %}
                            {% for key ,val in haeder_cate %}
                            <font><a  href="/sell/index?mc={{ val['id']}}" class=<?php echo (isset($get['mc'])&&$get['mc']==$val['id']) ?'active' : ''; ?>>{{ val['title']}}</a></font>
                            {% endfor %}
                        {% endif %}
                        
                    </div>
                </li>
                <li class="has-list {% if _nav == 'purchase'%}active{% endif %} ">
                    <a href="/purchase/index/" <?php echo ($_nav=='purchase') ? "class='hover'" : ''; ?> >采购中心</a>
                    <div class="small-list commonW2 small-list-caigou" <?php echo ($_nav=='purchase') ? "style='display:block'" : ''; ?> >
                        {% if haeder_cate %}

                            {% for key ,val in haeder_cate %}
                            <font><a  href="/purchase/index?mc={{ val['id']}}" class=<?php echo (isset($get['mc'])&&$get['mc']==$val['id']) ?'active' : ''; ?> >{{ val['title']}}</a></font>
                            {% endfor %}
                        {% endif %}
                        
                    </div>
                </li>
                <li {% if _nav == 'market'%}class='active'{% endif %} >
                    <a href="/market/index/">价格行情</a>
                </li>
               <!--  <li {% if _nav == 'product'%}class='active'{% endif %} >
                    <a href="/product/fao/">安全种植体系</a>
                </li> -->

                <li {% if _nav == 'tag'%}class='active'{% endif %} >
                    <a href="/tag/index/">可追溯产品</a>
                </li>
                <li class="has-list">
                    <a href="/wuliu" >物流信息</a>
                    <div class="small-list commonW2 small-list-wuliu">
                        <font>
                            <a href="/wuliu/car/" >车源信息</a>
                        </font>
                        <font>
                            <a href="/wuliu/car/">货源信息</a>
                        </font>
                        <font>
                            <a href="/wuliu/car/">专线信息</a>
                        </font>                       
                    </div>
                </li>
            </ul>

            {% if _nav != 'index' %}
            <div class="nav-btn">
                <a class="btn1" href="/member/sell/new">发布产品</a>
                <a class="btn2" href="javascript:newWindows('newpur', '发布采购', '/member/dialog/newpur');">发布采购</a>
            </div>
            {% endif %}
            
            <!-- 
            **  class为navAllsort时是未登录的导航类  此时“我的关注”隐藏 
            **  class为navAllsort和navAllsort-login时是已登录的导航类  此时“我的关注”显示 
            -->
            <div class="vertical-navigation">
                <h6>全部农产品分类</h6>
                <!--  -->
                <div class="navAllsort <?php echo isset($getMyCateList[1])||isset($getMyCateList[0]) ? 'navAllsort-login' : ''; ?>" {% if _nav == 'index'%}style="display:block;" {% endif %} >
                    <div class="fore showFore">

                        <div class="fore8 clearfix">
                            <span>
                                <a href="#">我的关注</a>
                            </span>
                            <font></font>
                        </div>
                        
                        <div class="foreBox">
                            <div class="fb-list">
                                <br>
                                供应：
                                <?php if(isset($getMyCateList[1])) { ?>
                                {% for key , item  in getMyCateList[1] %}

                                <a href="/sell/index?first={{ item['abbreviation']}}&mc={{ item['parent_id']}}&c={{ item['category_id']}}">{{ item['category_name']}}</a>
                                {% endfor %}
                                <?php } ?>
                            </div>
                            <br>
                            <div class="fb-list">
                                采购：
                                <?php if(isset($getMyCateList[0])) { ?>
                                {% for key , item  in getMyCateList[0] %}
                                <a href="/purchase/index?first={{ v['abbreviation']}}&mc={{ item['parent_id']}}&c={{ item['category_id']}}">{{ item['category_name']}}</a>
                                {% endfor %}
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <!-- 头部导航 -->
                    {% if haeder_cate %}
                    {% for key , item in haeder_cate %}
                    <div class="fore">
                        <div class="fore{{key + 1 }} clearfix">
                            <span>
                                <a href="#">{{ item['title'] ? item['title']  :''}}</a>
                            </span>
                            <font></font>
                        </div>
                                
                        <div class="foreBox">
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

    <style>
    .across-list li{ display: inline;}
    .samll-list-minwidth, .small-list-caigou{ min-width:500px;}
    .across-list li.has-list .small-list a{ font-size: 12px;}
    .small-list-wuliu{ min-width:280px;}
    .header-search .selectBox{ z-index:1001;}
    </style>
    
