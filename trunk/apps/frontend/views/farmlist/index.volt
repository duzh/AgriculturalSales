<!-- 头部 -->
{{ partial('layouts/page_header') }}


<div class="wrapper">
	<div class="w1190 mtauto f-oh">
		
		<div class="bread-crumbs">
			<a href="/">首页</a>&nbsp;>&nbsp;可信农场  
		</div>
		<!-- 筛选 -->
		{{partial('layouts/navs_cond')}}
		
		<div class="filter-bList f-oh">				
			<!-- 左侧 -->
			<div class="bList-left f-fl">
				<div class="box">
					<div class="erji-filter f-oh">
						<div class="m-page clearfix f-fr">
							{{ newpages }}
						</div>
					</div>
					{% if data %}
					{% for key,item in data %}
					<div class="hall-product-list farm-list">
						<div class="m-box clearfix">
							<dl class="clearfix f-fl">
								<dt class="f-fl">
									<a href="http://{{item['url']}}{{ constant('CUR_DEMAIN') }}/indexfarm/index" class="f-oh">
									{% if item['logo_pic']!='' %}
									<img src="{{ constant('IMG_URL') }}{{item['logo_pic']}}" height="142" width="142">
									{% else %}
									<img src="http://yncstatic.b0.upaiyun.com/mdg/version2.4/images/detial_b_img.jpg" height="142" width="142">
									{% endif %}
									</a>
								</dt>
								<dd class="name f-fr">
									<a href="http://{{item['url']}}{{ constant('CUR_DEMAIN') }}/indexfarm/index">{{item['farmname']}}</a>
								</dd>
								<dd class="message main-crop f-fr">
									<font>种植作物：</font>	
									{{item['goods_name'] ? item['goods_name'] : '' }}
								</dd>
								<dd class="message f-fr">
									<font>农场位置：</font>{{item['farm_address'] ? item['farm_address'] : ''}}
								</dd>
							</dl>
							<div class="operate f-fr">
								<a class="enter-farm" href="http://{{item['url']}}{{ constant('CUR_DEMAIN') }}/indexfarm/index">进入农场</a>
							</div>
						</div>
					</div>
					{% endfor %}
					{% endif %}
				</div>
				<!-- 分页 -->
				{% if total>1   %}
				<form method="get" action="/farmlist/index">
				<div class="esc-page mt30 mb30 f-tac f-fr">
					{{ pages }}
					<span>
						<label>去</label>
						<input type="text" name="p" id="p" onkeyup="value=value.replace(/[^\d]/g,'') " onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" value="1"/>
						<label>页</label>
					</span>
					<input type="hidden" name="total" id="total" value="{{total}}">
					<input class="btn" type="button" value="确定" onclick="goto()"/>
				</div>
				</form>
				{% endif %}
			</div>
			
			
			
			<!-- 右侧 -->
			<div class="bList-right f-fr">

				<div class="apply-farm-link mb20">
					<a href="/member/userfarm/user">
						<img src="http://yncstatic.b0.upaiyun.com/mdg/version2.5/images/apply-farm-img.jpg" alt="">
					</a>
				</div>
				<!-- 推荐农场 -->
				<div class="hot-product tj-farm">
					<div class="title">推荐农场</div>
					{% if result %}
					{% for key,item in result %}
					<div class="list">
						<div class="m-box">
							<dl class="clearfix">
								<dt class="f-fl">
									<a href="http://{{item['url']}}{{ constant('CUR_DEMAIN') }}/indexfarm/index">

									{% if item['logo_pic'] %}
									<img src="{{ constant('IMG_URL') }}{{item['logo_pic']}}" height="92" width="92">
									{% else %}
									<img src="http://yncstatic.b0.upaiyun.com/mdg/version2.4/images/detial_b_img.jpg" height="92" width="92">
									{% endif %}
									</a>
								</dt>
								<dd class="name f-fr">
									<a href="http://{{item['url']}}{{ constant('CUR_DEMAIN') }}/indexfarm/index">{{item['farmname']}}</a>
								</dd>
								<dd class="crops f-fr">
									主要种植作物：<br />
									{{item['farm_address']}}
								</dd>
							</dl>
						</div>
					</div>
					{% endfor %}
					{% endif %}
				</div>
			</div>
		</div>

	</div>
</div>
<!-- 底部 -->
{{ partial('layouts/footer') }}
<script>
function goto(){
	var query = window.location.search;
	var p = parseInt($('#p').val());
	var total=parseInt($("#total").val());
	if(p=='' || isNaN(p) || p>total){
		p=total;
	}
	location.href="/farmlist/mc{{url['mc']}}_a{{url['a']}}_c{{url['a']}}_f{{url['f']}}_p"+p+query;
}
	function pagego(p){
	var query = window.location.search;
	location.href="/farmlist/mc{{url['mc']}}_a{{url['a']}}_c{{url['a']}}_f{{url['f']}}_p"+p+query;
}

</script>
	