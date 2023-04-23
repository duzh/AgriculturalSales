{{ partial('layouts/page_header') }}
<link href="{{ constant('JS_URL') }}lightSlider/css/gowuche.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="{{ constant('JS_URL') }}lightSlider/js/jquery.jqzoom.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}lightSlider/js/base.js"></script>

<div class="contianer pb30">

    <div class="shop_nav w960">
        <a href="/store/shoplook" class="active">店铺首页</a> <em>|</em>
        <a href="/store/shoplook/goodslist">所有产品</a> <em>|</em>
        <a href="/store/shoplook/serviceevaluation">服务评价</a>
       <!--  <a href="/store/shoplook/shopintroduction">店铺介绍</a> -->
        {% for key, item in shopcolumns['items'] %}
        <em>|</em>
        <a href="/store/shoplook/columns/{{item['id']}}" >{{item['col_name']}}</a>
        {% endfor %}
    </div>
    <div class="shop_symm w960 clearfix">
        <div class="symm_left f-fl">
            {{ partial('layouts/shoper_left') }}
            {{ partial('layouts/comments_left') }}
        </div>
        <div class="shop_symm w960 clearfix">

            <!-- 右侧 start -->
            <div class="symm_right f-fr">
                <div class="shop_detial">
                    <h6>
                        {{ sell.title }}
                        <span>[供应编号：{{ sell.sell_sn }}]</span>
                    </h6>

                    <div class="dl_box">
                        <!-- 块 start -->

                        <div class="dl_box">
                <!-- 块 start -->
                <div style="width:313px; float:left; margin-right:6px;">
                    <div id="preview" class="spec-preview">
                        <span class="jqzoom">
                            {% if curImg  %}
                            <img jqimg="{{ curImg }}" src="{{ curImg }}" width="300" height="300" />
                            {% else %}
                            <img jqimg="<?php echo  Mdg\Models\Image::imgmaxsrc($cateid) ?>" src="<?php echo  Mdg\Models\Image::imgmaxsrc($cateid) ?>" width="300" height="300" />
                            {% endif %}
                        </span>
                    </div>
                    <!--缩图开始-->
                    <div class="spec-scroll" style="width:326px; height:60px; margin-left:-13px;">
                        <a class="prev">&lt;</a>
                        <div class="items">
                            <ul>
                                {% for key, img in imgs %}
                                <li><img bimg="{{ constant('IMG_URL') }}{{ img['path'] }}" src="{{ constant('IMG_URL') }}{{ img['path'] }}" onmousemove="preview(this);"></li>
                                {% endfor %}
                            </ul>
                        </div>
                        <a class="next">&gt;</a>
                    </div>
                    <!--缩图结束-->
            </div>
                        <!-- 块 end -->
                        <!-- 块 end -->
                        <div class="cs_right f-fl">
                            <p>
                                供应时间： <strong>{{ time_type[sell.stime] }}~{{ time_type[sell.etime] }}</strong>
                            </p>
                            <p>
                                <span>供应地区：</span>
                                <em>{{ sell.areas_name}}</em>
                            </p>
                            <p>
                                产品报价： <strong>{{ sell.min_price }}~{{ sell.max_price }}元/{{ goods_unit[sell.goods_unit] }}</strong>
                            </p>
                            <p>{{date("Y-m-d H:i:s",sell.createtime)}}</p>
                            <p class="line">&nbsp;</p>
                            <p>
                                该供应商还供应：
                                {% for key, val in otherSell %}
                                <a href="/sell/info/{{ val['id'] }}">{{ val['title'] }}</a>
                                {% if key+1 != total %}、{% endif %}
                                {% endfor %}

                            </p>
                            <div class="wtcg_btn">
                                <img src="{{ constant('STATIC_URL') }}mdg/images/register_icon2.png">
                                <a href="javascript:newWindows('newbuy', '确认采购信息', '/member/dialog/newbuy/{{ sell.id }}');;">立即委托采购</a>

                            <!--     <img src="images/register_icon2.png">
                                <a href="javascript:;">立即委托采购</a> -->
                            </div>
                        </div>
                        <!-- 块 start --> </div>
                </div>
                <!-- 块 end -->
                <!-- 块 start -->
                <div class="message">
                    <div class="m_title">详细描述</div>
                    <table class="m_table">
                <tbody>
                    <tr height="24">
                        <td width="223">供应编号：{{ sell.sell_sn }}</td>
                        <td width="224">产品品名：{{ sell.title }}</td>
                        <td>产品品种：{{ sell.breed }}</td>
                    </tr>
                    <tr height="24">
                        <td>供应时间：{{ time_type[sell.stime] }}~{{ time_type[sell.etime] }}</td>
                        {% if sell.spec %}
                        <td colspan="2">产品规格：{{ sell.spec }}</td>
                        {% endif %}
                    </tr>
                </tbody>
            </table>
                    <p class="line">&nbsp;</p>

                    {% if sell.scontent  %}
                        {{ sell.scontent.content }}
                    {% else %}
                        {{contents}}
                    {% endif %}
                </div>
            </div>
            <!-- 右侧 end --> </div>

    </div>
</div>

<!-- 底部开始 -->
{{ partial('layouts/footer') }}
<!-- 底部结束 -->
<link rel="stylesheet"  href="{{ constant('JS_URL') }}lightSlider/css/lightSlider.css"/>
<script src="{{ constant('JS_URL') }}lightSlider/js/jquery.lightSlider.js"></script> 
<script>
$(document).ready(function() {
    var tel = $("#tel").text().substring(0,3)+"****"+$("#tel").text().substring(7,11);
    //alert(tel);
    $("#tel").text(tel);

    $('#lightSlider').lightSlider({
        gallery:true,
        item:1,
        thumbItem:6,
        slideMargin: 0,
        auto:true,
        onSliderLoad: function() {
            $('#lightSlider').removeClass('cS-hidden');
        }     
    });
});
</script>
</body>
</html>