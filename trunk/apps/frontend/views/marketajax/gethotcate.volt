{% for key , item in data %}
<li><span class="bigLi_spanName_v2">{{ item['title']}}</span><span class="bigLi_{% if item['ppp'] > 0  %}sheng{% else %}jiang{% endif %}_v2"><?php  echo $item['today_avgprice']; ?>元/斤</span></li>
{% endfor %}