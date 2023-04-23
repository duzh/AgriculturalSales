{% for sell in category %}
<a href="/sell/index?category={{sell.id}}"  >{{sell.title}}
	<img src="http://static.ync365.com/mdg/images/choose_checked_img.png"/></a>
{% endfor %}

