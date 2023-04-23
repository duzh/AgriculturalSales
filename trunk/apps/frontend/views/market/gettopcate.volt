<div class="wl-top10-title"> <strong>{{ pname }}价格浮动TOP10</strong>
</div>
<ul>
	{% for key, item in data %}
	<!-- <a href="/market/get?cid={{item['id']}}">
	-->
	<li> <em class="wl-list-liEm">{{ key + 1 }}</em>
		<div class="wl-list-name">{{ item['goods_name']}}</div>
		<div class="wl-list-money">
			<span class="wl-{% if item['per'] >
				0  %}sheng {% else %}jiang{% endif %}">
				{{ item['todayprice']}}元/斤
			</span>
		</div>
		<span class="wl-list-lilv">
			￥
			<?php  echo $item['difference']; ?>({{ item['per']}}%)</span>
	</li>
</a>
{% endfor %}
</ul>