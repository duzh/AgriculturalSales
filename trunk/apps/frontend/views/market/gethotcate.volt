{% for key , item in data %}
<li>
	<span class="wl-list-zlName">{{ item['goods_name']}}</span> <em class="wl-list-zlMoney wl-{% if item['per'] > 0  %}sheng {% else %}jiang{% endif %}"><?php  echo \Mdg\Models\MarketAvgprice::getCateAvgPrice($item['category_id']); ?>元/斤</em>
</li>
{% endfor %}
<!-- <a href="/market/get?cid={{item['id']}}">
-->