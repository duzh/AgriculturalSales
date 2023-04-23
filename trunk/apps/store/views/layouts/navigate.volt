 <div class="shop_nav w960">
<a {% if shop.business_type==1%} href="/store/shoplook/index"{% else %}href="/store/purchaseshop/index"{% endif %} {% if action == 'index'%} class="active" {% endif %}>店铺首页</a><em>|</em>
<a {% if shop.business_type==1%} href="/store/shoplook/goodslist" {% else %} href="/store/purchaseshop/goodslist"{% endif %} {% if action == 'goodslist'%} class="active" {% endif %}>所有产品</a><em>|</em>

<a {% if shop.business_type==1%} href="/store/shoplook/serviceevaluation" {% else %} href="/store/purchaseshop/serviceevaluation"{% endif %}{% if action == 'serviceevaluation'%} class="active" {% endif %}>服务评价</a>
<!-- <em>|</em> -->
<!-- <a {% if shop.business_type==1%} href="/store/shoplook/shopintroduction"{% else %}href="/store/purchaseshop/shopintroduction"{% endif %}{% if action == 'shopintroduction'%} class="active" {% endif %} >店铺介绍</a> -->
{% for key, item in shopcolumns['items'] %}<em>|</em>
<a {% if shop.business_type==1%} href="/store/shoplook/columns/{{item['id']}}"{% else %}href="/store/purchaseshop/columns/{{item['id']}}"{% endif %}{% if url_id and url_id == item['id']  %}class="active"{% endif %}>{{item['col_name']}}</a>
{% endfor %}
</div>
<!-- 头部 end -->