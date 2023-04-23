<link rel="stylesheet" href="{{ constant('STATIC_URL') }}mdg/version2.5/css/trust-farm.css">
<script src="{{ constant('STATIC_URL') }}mdg/version2.5/js/trust-farm.js"></script>
{{ partial('layouts/orther_herder') }}
<div class="wrapper">
	<div class="w1190 mtauto f-oh">
		 <!--农场头部--> 
		{{ partial('layouts/farm_header') }}
		
		
		<div class="tf-productList f-oh">
			{% if data %}
			{% for key,item in data %}
			<div class="imgBox f-fl">
				<a href="/sell/info/{{item['id']}}">
					<div class="imgs">
						{% if item['thumb'] %}
						<img src="{{ constant('IMG_URL') }}{{item['thumb']}}" height="210" width="220">
						{% else %}
						<img src="http://yncstatic.b0.upaiyun.com//mdg/version2.4/images/detial_b_img.jpg" height="210" width="220">
						{% endif %}
					</div>
					<div class="name">{{item['title']}}</div>
					<div class="intro">
						<font>价   格：</font><i>{{item['min_price']}} ~ {{item['max_price']}}</i> 元/{{item['goods_unit']}}
					</div>
					<div class="intro">
						<font>供应量：</font>{% if item['quantity']>0 %}{{item['quantity']}}{{item['goods_unit']}}{% else %}不限{% endif %}
					</div>
					<div class="intro">
						<font>起购量：</font>{% if item['min_number']>0 %}{{item['min_number']}}{{item['goods_unit']}}{% else %}不限{% endif %}
					</div>
				</a>
			</div>
			{% endfor %}
			{% endif %}
		</div>
		<!-- 分页 -->
		<form method="get" action="/crediblefarm/goodslist">
		<div class="esc-page mt30 mb30 f-tac f-fr">
			{{ pages }}
			<span>
				<label>去</label>
				<input type="text"  name="p"  onkeyup="value=value.replace(/[^\d]/g,'') " onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" //>
				<label>页</label>
			</span>
			<input class="btn" type="submit" value="确定" />
		</div>
		</form>
	</div>
</div>
{{ partial('layouts/orther_footer') }}