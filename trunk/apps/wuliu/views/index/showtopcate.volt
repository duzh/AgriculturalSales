<div class="sj-list">
{% for key,val in threecategorys %}
<a href="/sell/index?threec={{val['id']}}">{{val['title']}}</a>
{% endfor %}
</div>