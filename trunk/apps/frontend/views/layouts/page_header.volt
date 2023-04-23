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
                <font{% if ynbtype['type'] == 2 %} class="has-con "{% endif %}>
                    <a{% if ynbtype['type'] == 2 %} class="drop-down" style="padding-right:23px;" {% else %} target="_blank" href="{{ ynbtype['url'] }}"{% endif %} >{{ ynbtype['text'] }}</a>
                    {% if ynbtype['type'] == 2 %}
                    <span class="list">
                        <a href="{{ ynbtype['url2'] }}">绑定云农宝</a>
                    </span>
                    {% endif %}

                </font>
                <font>
                    <a href="http://www{{ constant('CUR_DEMAIN') }}/member/ordersbuy/index">我的订单</a>
                </font>
                <font>
                    <a href="javascript:;" id="addcollect">收藏我们</a>
                </font>
                
                <font class="has-con ">
                    <a class="drop-down" href="javascript:;">关注我们</a>
                    <span class="wx">
                        <img src="{{ constant('STATIC_URL') }}mdg/version2.5/images/header-wx.jpg" alt="">
                    </span>
                </font>
                <font class="has-con">
                    <a class="drop-down" href="javascript:;">客户服务</a>
                    <span class="list">
                        <a href="http://www{{ constant('CUR_DEMAIN') }}/article/index?p=15">联系客服</a>
                        <a href="http://www{{ constant('CUR_DEMAIN') }}/article/index?p=22">帮助中心</a>
                    </span>
                </font>
            </div>

        </div>
    </div>
    <div class="header-logo">
        <div class="w1190 mtauto clearfix af-header">

            <div class="logo f-fl">
            <a href="http://www{{ constant('CUR_DEMAIN') }}"><img src="{{ constant('STATIC_URL') }}mdg/version2.5/images/logo.png" alt=""></a>
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
                        <input type="text" placeholder="请输入关键词，例如：白菜、西瓜、椴木香菇" name="keywords" value="<?php if(isset($_GET['keyword'])&&$_GET['keyword']!=''){ echo $_GET['keyword'];}?>" />
                    </div>
                    <div class="inputBtn">
                        <input type="submit" value="搜索" />
                    </div>
                </div>
                <div class="hotwords">
                    {% for val in  hotsell %}
                    <?php $hearderkeyword=urlencode($val['title']);?>
                    <a href="http://www{{ constant('CUR_DEMAIN') }}/sell/index?keyword={{hearderkeyword}}">{{val['title']}}
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
    <div class="header-nav" style="*z-index:9;">
        <div class="w1190 mtauto f-pr clearfix" style="*z-index:10;">
            <!-- 
            **  li的类名为has-erji时，表示在对应页面有二级导航
            -->
            <ul class="horizontal clearfix">
                <li {% if _nav == 'index'%}class="active"{% endif %}>
                    <a href="http://www{{ constant('CUR_DEMAIN') }}">首页</a>
                </li>
                <li {% if _nav == 'sell'%}class="active"{% endif %}>
                    <a href="http://www{{ constant('CUR_DEMAIN') }}/sell/index/">供应大厅</a>
                    <div class="border"></div>
                </li>
                <li {% if _nav == 'purchase'%}class="active"{% endif %}>
                    <a href="http://www{{ constant('CUR_DEMAIN') }}/purchase/index/">采购中心</a>
                    <div class="border"></div>
                </li>
                <li {% if _nav == 'tag'%}class='active'{% endif %}>
                    <a href="http://www{{ constant('CUR_DEMAIN') }}/tag/index/">可信农产品</a>
                </li>
                <li {% if _nav == 'market'%}class='active'{% endif %}>
                    <a href="http://www{{ constant('CUR_DEMAIN') }}/market/index/">价格行情</a>
                </li>
                <li {% if _nav == 'wuliu'%}class='active'{% endif %}>
                    <a href="http://www{{ constant('CUR_DEMAIN') }}/wuliu/index/">物流信息</a>
                </li>
                <li {% if _nav == 'advisory'%}class='active'{% endif %}>
                    <a href="http://www{{ constant('CUR_DEMAIN') }}/advisory/adindex">资讯</a>
                </li>
            </ul>
            <!-- 
            *** 此按钮首页不应显示，其他页均应显示
            -->
             {% if _nav != 'index' %}
            <div class="navBtn f-oh">
                {% if is_brokeruser and is_brokeruser.is_broker==1 %}
                {% else %}
                <a class="gy-btn" onclick="member_new_sell()" >发布供应</a>
                 
                <a class="cg-btn" href="javascript:newWindows('newpur', '发布采购', '/member/dialog/newpur');">发布采购</a>
                 {% endif %}
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
                                <a href="/sell/mc{{v['parent_id']}}_a0_c{{v['id']}}_f{{v['abbreviation']}}_p1">{{ v['title']}}</a>
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
                <?php $hearderkeyword=urlencode($val['title']);?>
                <a href="/sell/index?search_header_type=sell&keyword={{hearderkeyword}}">{{val['title']}}
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
                        <input type="text" name="keywords" placeholder="请输入关键词，例如：白菜、西瓜、椴木香菇" value="<?php if(isset($_GET['keyword'])&&$_GET['keyword']!=''){ echo $_GET['keyword'] ;}?>" />
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
 
 <?php if(isset($_SESSION["user"]["mobile"])&&isset($_SESSION["user"]["id"]))
 { $lwttinfos=Mdg\Models\UserInfo::getlwttlist($_SESSION["user"]["id"],$_SESSION["user"]["mobile"]); } else{$lwttinfos=false;} ?>
{% if lwttinfos and lwttinfos['lwttstate'] %}
<div class="frontend_fsh_tongzhiCon" style="display: none;">
     <div class="wms-alert wms-alert2">
      <a class="close-btn" href="javascript:;" onclick="memberwarnPageClose();" ></a>
      <div class="title">提示</div>
      <font class="f-db">禁止发布整合供应信息：</font>
        {% if lwttinfos['lwttstate']==5  %}<div class="tips f-oh"><span class="f-fl">-您的盟商认证正在审核中,不可发布整合供应信息</span> </div>{% else %}{% if lwttinfos['lwttcount']<1  %}<div class="tips f-oh"><span class="f-fl">-您当前整合的可信农场未达1家,不可发布整合供应信息</div>{% else %}{% if !lwttinfos['cate_id'] %}<div class="tips f-oh "><span class="f-fl">-您当前整合的可信农场还未成功发布任何供应信息，不可发布整合供应信息</span></div>{% endif %}{% endif %}{% endif %}
      <div class="btns">
        <input type="button" value="知道了" onclick="memberwarnPageClose();">
      </div>
  </div>
  <div class="frontend_fsh_tongzhiBg"></div>
</div>
<style>
    .frontend_fsh_tongzhiCon{ z-index:99;}
    .frontend_fsh_tongzhiCon, .frontend_fsh_tongzhiBg{ position:fixed; width:100%; height:100%; left:0; top:0; right:0; bottom:0;}
    .frontend_fsh_tongzhiBg{ background:#000; opacity: 0.4; filter: alpha(opacity=40); z-index:99;}
</style>
{% endif %}
<script>
    function   member_new_sell(){
      {% if !lwttinfos or !lwttinfos['lwttstate'] %}
           location.href="/member/sell/new";
      {% else %}
          {% if lwttinfos['lwttstate']==5 %}
          $('.frontend_fsh_tongzhiCon').show();
          {% else %}
          location.href="/member/sell/new";
          {% endif %}
      {% endif %}
    }
    function memberwarnPageClose(){
         $('.frontend_fsh_tongzhiCon').hide();
    }
</script>
