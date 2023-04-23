<!-- 内容页样式、js（放到公用样式最下面） -->
<link rel="stylesheet" type="text/css" href="/mdg/css/mix-bq-gy.css" />
<link href="{{ constant('JS_URL') }}lightSlider/css/gowuche.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="{{ constant('JS_URL') }}lightSlider/js/jquery.jqzoom.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}lightSlider/js/base.js"></script>
    <link href="{{ constant('STATIC_URL') }}mdg/version2.4/css/MagicZoom.css" rel="stylesheet" type="text/css" media="screen"/>
    <script src="{{ constant('STATIC_URL') }}mdg/version2.4/js/MagicZoom.js" type="text/javascript"></script>
<!-- 头部开始 -->
{{ partial('layouts/orther_herder') }}
<!-- 头部结束 -->

<!-- 主体内容开始 -->
<div class="wrapper f-oh" style="background:#f2f2f2;">
    <div class="ur_here w960">
        <span>
            <a href="/">首页</a>
            &nbsp;>&nbsp;
            <a href="/sell/index">供应大厅</a>
            &nbsp;>&nbsp;
        {% for key, cat in family %}
            <a href="/sell/index?c={{ cat['id'] }}">{{cat['title']}}</a>
            &nbsp;>&nbsp;
        {% endfor %}
        {{sell.title}}
        </span>
    </div>
    <div class="gy_detial">
        <!-- 左侧 start -->
        <div class="left f-fl">
            <!-- 块 start -->
            <!-- 块 start -->
            <div class="canshu">
                <h6 class="mt20 clear">
                    {{ sell.title }}
                    <span>[供应编号：{{ sell.sell_sn }}]</span>
                </h6>
                <div class="dl_box">
                    <!-- 块 start -->
                    <div style="width:313px; float:left; margin-right:6px;">
                        <div id="preview" class="spec-preview">
                            <span class="jqzoom">
                                {% if curImg  %}
                                <img jqimg="{{ curImg }}" src="{{ curImg }}" width="300" height="300" />
                                {% else %}
                                <img jqimg="<?php echo  Mdg\Models\Image::imgmaxsrc($cateid) ?>
                                " src="
                                <?php echo  Mdg\Models\Image::imgmaxsrc($cateid) ?>
                                " width="300" height="300" />
                            {% endif %}
                            </span>
                        </div>
                        <!--缩图开始-->
                        <div class="spec-scroll" style="width:326px; height:60px; margin-left:-13px;">
                            <a class="prev">&lt;</a>
                            <div class="items">
                                <ul>
                                    {% for key, img in imgs %}
                                    <li>
                                        <img bimg="{{ constant('IMG_URL') }}{{ img['path'] }}" src="{{ constant('IMG_URL') }}{{ img['path'] }}" onmousemove="preview(this);"></li>
                                    {% endfor %}
                                </ul>
                            </div>
                            <a class="next">&gt;</a>
                        </div>
                        <!--缩图结束--> </div>
                    <!-- 块 end -->
                    <div class="cs_right f-fl">
                        <p>
                            供应时间： <strong>{{ time_type[sell.stime] }}~{{ time_type[sell.etime] }}</strong>
                        </p>
                        <p>
                            <span>供应地区：</span> <em>{{ sell.areas_name ? sell.areas_name : '' }}</em>
                        </p>
                        <p>
                            产品报价： <strong>{{ sell.min_price }}~{{ sell.max_price }}元/{{ goods_unit[sell.goods_unit] }}</strong> 
                        </p>
                        <p>
                            起购量：
                            <strong>{% if sell.min_number > 0 %}
                        {{ sell.min_number }} {{ goods_unit[sell.goods_unit] }} 
                        {% else %}
                        {{ goods_unit[sell.min_number] }} 
                        {% endif %}</strong> 
                        </p>

                        <p>
                            供应量：
                            <strong>
                                {% if sell.quantity > 0 %}
                        {{ sell.quantity }} {{ goods_unit[sell.goods_unit] }} 
                        {% else %}
                        {{ goods_unit[0] }} 
                        {% endif %}
                                <!-- {{ sell.quantity }} {{ goods_unit[sell.goods_unit] }}  --> </strong>
                        </p>
                        <p>{{date("Y-m-d H:i:s",sell.createtime)}}</p>

                        <!-- <p>
                        供应时间：
                        <strong>{{ time_type[sell.stime] }}~{{ time_type[sell.etime] }}</strong>
                    </p>
                    -->
                    <p class="line">&nbsp;</p>
                    {% if otherSell %}
                    <p>
                        该供应商还供应：
                        {% for key, val in otherSell %}
                        <a href="/sell/info/{{ val['id'] }}">{{ val['title'] }}</a>
                        {% if key+1 != total %}、{% endif %}
                        {% endfor %}
                    </p>
                    {% endif %}
                    <div class="wtcg_btn">
                        <img src="{{ constant('STATIC_URL') }}mdg/images/register_icon2.png">
                        <a href="javascript:newWindows('newbuy', '确认采购信息', '/member/dialog/newbuy/{{ sell.id }}');;">立即委托采购</a>
                    </div>
                </div>
                <!-- 块 start --> </div>
        </div>
        <!-- 块 end -->
        <!-- 块 start -->
        <!-- 供应商详细描述 -->

        {% if istag == 1 %}
        {{ partial('sell/serviceinfo') }}
        {% else %}
        {{ partial('sell/labelinfo') }} 
        {% endif %}
        <!-- 标签信息start  商品检测， 生产信息， 作业信息 -->

        <!-- 标签信息 end  -->

        <div class="message">
            <div class="m_title">平台介绍</div>
            <div style="padding:20px 0;">
                丰收汇是高价值农产品交易服务平台，为农产品种植、加工、销售的企业或个人提供农产品价格信息资讯，产品交易，信誉认证，融资担保等服务。
            </div>
        </div>
        <!-- 块 end --> </div>
    <!-- 左侧 end -->
    <!-- 右侧 start -->
    <div class="right f-fr mt20">

        <!--申请标签后  服务站基本信息-->
        {{ partial('sell/serviceleftinfo') }}
        <!-- 块 end -->
        {% if otherSell %}
        <!-- 块 start -->
        <div class="other_message">

            <h6>
                他还供应{% if sell.uid %}
                <a href="/sell/index?u={{ user.id }}">查看全部</a>
                {% endif %}
            </h6>
            <ul class="other_box">
                {% for key, val in otherSell %}
                <li>
                    <a href="/sell/info/{{ val['id'] }}">{{ val['title'] }}</a>
                    <br>
                    <strong>
                        {{ val['min_price'] }}~{{ val['max_price'] }}元/{{ goods_unit[val['goods_unit']] }}
                    </strong> <font>{{ date('y-m-d', val['updatetime']) }}</font>
                </li>
                {% endfor %}
            </ul>
        </div>
        <!-- 块 end -->
        {% endif %}
        <!-- 块 start -->
        <div class="other_message">
            <h6>同类产品供应</h6>
            <ul class="other_box">
                {% for key , item in sellSame %}
                <li>
                    <a href="/sell/info/{{ item['id'] }}">{{ item.title }}</a>
                    <br />
                    <strong>{{ item.min_price}}~{{ item.max_price}}元/斤</strong> <font>{{ date('Y-m-d', item.createtime) }}</font>
                </li>
                {% endfor %}
            </ul>
        </div>
        <!-- 块 end --> </div>
    <!-- 右侧 end -->
</div>
</div>

<!-- 主体内容结束 -->
<script>

$(function(){
    $('#gy_list_nav li').on('click', function(){
        $('#gy_list_nav li').removeClass('active');
        $('#gy_tabBox .box').hide();

        $(this).addClass('active');
        $('#gy_tabBox .box').eq($(this).index()).show();
    });
    
    /* 鼠标悬停效果 */
    $('.south-west-alt').powerTip({ placement: 'sw-alt' });
    
    //手机号加密、查看
    var val = $('.gys_info .jiami span').text();
   
    var str = $('.gys_info .jiami span').text().substring(0, 3) + '****' + $('.gys_info .jiami span').text().substring(7, 11);
    $('.gys_info .jiami span').text(str);
    
    
});
function jiami(val){
        $('.gys_info .jiami span').text(val);
        $('.gys_info .jiami span').addClass('active'); 
}
</script>
</body>
</html>