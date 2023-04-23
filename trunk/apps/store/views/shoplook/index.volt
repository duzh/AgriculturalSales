{{ partial('layouts/page_header') }}

<div class="contianer pb30">
    
{{ partial('layouts/navigate') }}
    <div class="shop_symm w960 clearfix">
        <div class="symm_left f-fl">
            {{ partial('layouts/shoper_left') }}
            {{ partial('layouts/comments_left') }}
        </div>
        <!-- 右侧 start -->
        <div class="symm_right f-fr">
            <div class="shop_intro">
                <h6>店铺介绍</h6>
                <div class="information f-oh">
                    <div class="f-oh">
                        <p class="f-ff0">{{shopcredit.shop_desc}}</p>
                    </div>
                </div>
            </div>
            <div class="shop_goods">
                <h6>供应产品</h6>
                <div class="list clearfix">
                    {% for key, item in shopgoodslist['items'] %}
                    <dl>
                         <dt><a href="/store/goods/index/{{ item['id']}}"><img src="{% if item['thumb'] %}{{ constant('IMG_URL') }}{{item['thumb']}}{% else %}
                            http://static.ync365.com/mdg/images/detial_b_img.jpg {% endif %}" width="120" height="120" /></a></dt>
                        <dd>
                            <div class="name"><a href="/store/goods/index/{{ item['id']}}">{{item['title']}}</a></div>
                            <div class="price">{{item['min_price']}}~{{item['max_price']}}/斤</div>
                            <div class="address">{{item['areas_name']}}</div>
                        </dd>
                    </dl>
                    {% endfor %}
                </div>
            </div>
        </div>
        <!-- 右侧 end -->
    </div>
</div>

<!-- 底部开始 -->
{{ partial('layouts/footer') }}
<!-- 底部结束 -->
</body>
</html>
