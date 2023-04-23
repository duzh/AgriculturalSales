{% for key, item in data %}
<li {% if key < 3 %}class="li_first_three"{% endif %}>
    <em>{{ key + 1 }}</em>
    <span class="zl_Lispan1_v2">{{ item['title']}}</span>
    <span class="zl_danwei_v2">{{ item['today_avgprice']}}元/公斤</span>
    <span class="zl_{% if item['ppp'] > 0 %}sheng{% else %}jiang{% endif %}_v2">
		<?php  echo $item['diff']; ?>({{ item['ppp']}}%)
	</span>
</li>
{% endfor %}