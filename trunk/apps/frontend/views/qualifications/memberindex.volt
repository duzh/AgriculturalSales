<link rel="stylesheet" href="{{ constant('STATIC_URL') }}mdg/version2.5/css/trust-farm.css">
<script src="{{ constant('STATIC_URL') }}mdg/version2.5/js/trust-farm.js"></script>
<!--头部--> 
{{ partial('layouts/orther_herder') }}
<div class="wrapper">
	<div class="w1190 mtauto f-oh">
		 <!--农场头部--> 
		{{ partial('layouts/farm_header') }}
		
		<div class="certify mt30 clearfix">
			{% if credible_farm_picture %}
			{% for k,v in credible_farm_picture %}
			<!-- 列表 -->
			<div {% if k==0 %}class="certify-box active"{% else %} class="certify-box"{% endif %}>
				<div class="time">{{date("Y/m/d",k)}}</div>
				<div class="m-box clearfix">
					<div class="m-border"></div>
					<ul class="gallery f-fl f-oh">
					{% for val in v %}
						<li>
							<a href="{{ constant('ITEM_IMG') }}{{val['picture_path']}}" >
								<img src="{{ constant('ITEM_IMG') }}{{val['picture_path']}}" style="height:185px;width:295px;"/>
							</a>
						</li>
					{% endfor %}
					</ul>
				</div>
			</div>
			{% endfor %}
			{% endif %}
		</div>
		
	</div>
</div>
<!--底部--> 
{{ partial('layouts/orther_footer') }}
	<!-- 图片放大预览 -->
    <link rel="stylesheet" href="{{ constant('STATIC_URL') }}mdg/version2.5/css/zoom.css" media="all">
	<script src="{{ constant('STATIC_URL') }}mdg/version2.5/js/zoom.min.js"></script>
		<style>
		#zoom{ *background:#000; *opacity:0.8; *filter:alpha(opacity:80);}
	</style>