<!-- 头部 -->
{{ partial('layouts/page_header') }}

<!----中间内容---->
<!--可追溯产品列表-->
<div class="wrapper">
	<div class="w1190 mtauto f-oh">

		<div class="bread-crumbs">
			<a href="../../">首页</a>&nbsp;>&nbsp;可追溯产品
		</div>
		<div class="tracea-list f-oh">
		{% for key , item in data['items'] %}
			<div class="imgBox f-fl">
				<a href="/sell/info/{{ item['id']}}&source=1">
					<div class="imgs">
						{% if  item['thumb'] %}
						<img src="{{ constant('IMG_URL')}}{{ item['thumb']}}" height="210px" width="210px">
						{% else %}
						<img width="210px" height="210px" src="http://static.ync365.com/mdg/images/detial_b_img.jpg" />
						{% endif %}
					</div>
					<div class="imgCon f-pr">
						<div class="name">{{ item['title']}}</div>
						<div class="area">{{ item['uname'] }}</div>
						<div class="price">￥{{ item['min_price'] }}</div>
						<div class="erwx pa">
							{% if item['path'] %}
							<img height="64px" width="64px" src="{{ constant('IMG_URL')}}{{item['path']}}" alt="">
							{% else %}
							<img height="64px" width="64px" src="http://static.ync365.com/mdg/images/detial_b_img.jpg" alt="">
							{% endif %}
						</div>
					</div>
				</a>
			</div>
		{% endfor %}	
		</div>
		<!-- 分页 -->
	
		<div class="esc-page mt30 mb30 f-tac f-fr">
			{% if data["total_count"]>1 %}
			<form action="/tag/index" method="post">
				{{ data['pages']}}
				<span>
					<label>&nbsp;去</label>
					<input type="text" name="p" value="1" id="p"/>
					<label>页</label>
				</span>
				<input class="btn" type="submit" value="确定" onclick="go()"/>
			</form>
			{% endif %}
		</div>
		
		<div class="tracea-guidance">
			<img  src="http://static.ync365.com/mdg/version2.5/images/tracea-guidance-img.png" alt="">
		</div>
		<div class="tracea-process mt30">
			<img src="http://static.ync365.com/mdg/version2.5/images/tracea-process-img.jpg" alt="">
		</div>

	</div>
</div>





<!-- 底部 -->
{{ partial('layouts/footer') }}

<script>	
function go(){
var p=$("#p").val();
 var count = {{data['total_count']}};
 if(p>count){
    $("#p").val(count);
 }
}
</script>