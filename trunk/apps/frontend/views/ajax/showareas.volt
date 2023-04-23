<span class="keywords">
	<font>地区：</font>
	<a class="all {% if (!pid) %}active{% endif %}" href="{{ url }}">全部</a>	
</span>	
<div class="box">
	<div class="area">
		{% for key, parent in parentData %}
		<a href="{{url}}/mc{{arr['mc']}}_a{{ parent.id }}_c{{arr['c']}}_f{{arr['f']}}_p1{% if keyword %}?keyword={{ keyword }}{% endif %}" {% if id==parent.id or pid==parent.id %}class="active"{% endif %} >{{ parent.name }}</a>
		{% endfor %}

	</div>
</div>
{% if childData %}
<div class="box" style="display: block;">
	<div class="area">
		{% for key, child in childData %}
		<a href="{{url}}/mc{{arr['mc']}}_a{{ child.id }}_c{{arr['c']}}_f{{arr['f']}}_p1{% if keyword %}?keyword={{ keyword }}{% endif %}" {% if id==child.id %}class="active"{% endif %} >{{ child.name }}</a>
		{% endfor %}
	</div>
</div>
{% endif %}
