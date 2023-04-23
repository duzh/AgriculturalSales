<div class="kuaisu_title_v2">
<strong>车源快速导航</strong>
<a href="/wuliu/car/index">更多&gt;</a>
</div>
<div class="kuaisu_con_v2">
	{% for key ,item in CarNavs %}
	<a href="/wuliu/car/index?start_pid={{item.start_pid}}">{{ item.start_pname}}</a>
	{% endfor %}
</div>
