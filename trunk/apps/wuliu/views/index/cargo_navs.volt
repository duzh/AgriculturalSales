<div class="kuaisu_title_v2">
	<strong>货源快速导航</strong>
	<a href="/wuliu/cargo/index">更多&gt;</a>
</div>
<div class="kuaisu_con_v2">
   {% for key ,item in CarNavs %}
	<a href="/wuliu/cargo/index/{{ item.start_pid}}">{{ item.start_pname}}</a>
	{% endfor %}
</div>