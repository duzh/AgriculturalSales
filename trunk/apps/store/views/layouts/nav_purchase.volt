 <div class="shop_nav w960">
        <a href="/store/purchaseshop/index" {% if action == 'index'%} class="active" {% endif %}>店铺首页</a><em>|</em>
        <a href="/store/purchaseshop/goodslist" {% if action == 'goodslist'%} class="active" {% endif %}>所有产品</a><em>|</em>
        <a href="/store/purchaseshop/serviceevaluation"{% if action == 'serviceevaluation'%} class="active" {% endif %}>服务评价</a><!-- <em>|</em> -->
        <!-- <a href="/store/purchaseshop/shopintroduction" {% if action == 'shopintroduction'%} class="active" {% endif %}>店铺介绍</a> -->
        {% for key, item in shopcolumns['items'] %}<em>|</em>
        <a href="/store/purchaseshop/columns/{{item['id']}}" {% if url_id !='' and url_id == item['id']  %}class="active"{% endif %} >{{item['col_name']}}</a>
        {% endfor %}
</div>