<div class="kuaisu_title_v2">
	<strong>专线快速导航</strong>
	<a href="/wuliu/special/index/">更多&gt;</a>
</div>
<div class="kuaisu_con_v2">
    <?php $address=Mdg\Models\ScInfo::groupscinfo();?>
	{% for val in address %}
	<a href="/wuliu/special/index?province={{val.start_pid }}">{{val.start_pname}}</a>
	{% endfor %}
</div>