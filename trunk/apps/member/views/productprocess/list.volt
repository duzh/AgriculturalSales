{{ partial('layouts/page_header') }}
<div class="center-wrapper pb30">
		<div class="bread-crumbs w1185 mtauto">
		   {{ partial('layouts/ur_here') }}种植过程列表
		</div>

		<div class="w1185 mtauto clearfix">
			<!-- 左侧 -->
			{{ partial('layouts/navs_left') }}			<!-- 右侧 -->
			<div class="center-right f-fr">

				<div class="plant-process-list clearfix">
					<div class="title">{{goods_name}}种植过程列表</div>
					<div class="box">
						<input class="f-db add-btn" type="button" value="新增过程" onclick="newWindows('add-developFoot', '新增种植过程', '/member/productprocess/addlist/{{goods_id}}/{{goods_name}}')"/>
						<table cellpadding="0" cellspacing="0" width="736">
							<tr height="30">
								<th width="15%">时间</th>
								<th width="25%">图片</th>
								<th width="20%">标题</th>
								<th width="30%">内容</th>
								<th width="10%">操作</th>
							</tr>
							{% if crediblefarmplant %}
							{% for v in crediblefarmplant %}
							<tr height="120" class="imgBox">
								<td align="center">{{date("Y/m/d",v['picture_time'])}}</td>
								<td align="center">
									<ul class="gallery" style="*padding-top:20px;">
										<li>
											<a href="{{ constant('IMG_URL') }}{{v['picture_path']}}">
												<img src="{{ constant('IMG_URL') }}{{v['picture_path']}}" height="86" width="166" />
											</a>
										</li>
										<li style="height:1px;">
											<a href="">
												<img style="opacity:0; filter:alpha(opacity:0);" src="" alt="">
											</a>
										</li>
									</ul>
									
								</td>
								<td align="center">{{v['title']}}</td>
								<td align="center">
									<p>{{v['desc']}}</p>
								</td>
								<td align="center">
									<a class="f-db" href="#" onclick="closeImgup(this, {{v['id']}});">删除</a>
								</td>
							</tr>
							{% endfor %}
							{% endif %}
						</table>
					</div>
					{% if total_count>1 and total_count!=0 %}
					<form action="/member/productprocess/list/{{goods_id}}/{{goods_name}}" method="get">
						<div class="esc-page mt30 mb30 f-tac f-fr mr30">
								{{ pages }}
							<span>
								<label>去</label>
								<input type="text" name="p" id="p" onkeyup="value=value.replace(/[^\d]/g,'') " onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" value="1" />
								<label>页</label>
							</span>
							<input class="btn" type="hidden" name="total" value="{{total_count}}" />
							<input class="btn" type="submit" value="确定" />
						</div>
					</form>	
					{% endif %}
				</div>
               
			</div>

		</div>
	</div>
<!-- 图片放大预览 -->
<link rel="stylesheet" href="http://yncstatic.b0.upaiyun.com/mdg/version2.5/css/zoom.css" media="all">
<script src="http://yncstatic.b0.upaiyun.com/mdg/version2.5/js/zoom.js"></script>
<style>
	#zoom{ *background:#000; *opacity:1; *filter:alpha(opacity:100);}
	#zoom .previous, #zoom .next, #zoom #previous, #zoom #next{display:none; opacity:0; filter:alpha(opacity:0);}
</style>
		<script>
			function closeImgup(obj, id) {
		    $.getJSON('/member/productprocess/deleteImg', {id : id}, function(data) {
		        alert(data.msg);
		        if(data.state) {
		        	 location.reload();
		            // $(obj).parents('.imgBox').slideUp();
		        }
		    });
		}</script>