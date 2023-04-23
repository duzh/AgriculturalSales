{% for Dkey, Ditem in data %}
{% if Ditem['isChid'] %}
<div class="zzs-topList clearfix" id="Dtime_{{ Ditem['id']}}" >

	<div class="zzs-topList-title clearfix">
		<div class="zzs-topListT-l">
			<span>{{ Dkey + 1  }}F</span> <strong>{{ Ditem['title']}}</strong>
		</div>
		<a href="/market/catelist/{{ Ditem['id']}}" class="zzs-topListT-more">更多&gt;&gt;</a>
	</div>
	<div class="zzs-top10">
		<div class="zzs-top10-title clearfix"> <strong>{{ Ditem['title']}}价格浮动</strong>
			<span>TOP10</span>
		</div>
		<div class="zzs-top10-list" id='top_{{ Ditem['id']}}' >

			
		</div>
	</div>
	<div class="zzs-rexiao">
		<strong class="zzs-rexiao-title">热销</strong>
		<ul class="zzs-rexiao-list clearfix" id='hot_{{Ditem['id']}}'>
			
		</ul>
	</div>
</div>

</div>

<script type="text/javascript">
	// 动态加载 热销产品
	$(function (){
		// $('#top_'+{{ Ditem['id'] }}).load("/market/gettopcate/{{ Ditem['id']}}");
		// $('#hot_'+{{ Ditem['id'] }}).load("/market/gethotcate/{{ Ditem['id']}}");
	})
</script>
{% endif %}
{% endfor %}

