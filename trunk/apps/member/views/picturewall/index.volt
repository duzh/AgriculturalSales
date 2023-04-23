<!--头部-->
{{ partial('layouts/member_header') }}
<script type="text/javascript" src="/uploadify/jquery.uploadify.min.js?var=3893111" ></script>
<link rel="stylesheet" type="text/css" href="/uploadify/uploadify.css?var=6107652">
<div class="wrapper">
	<div class="w1190 mtauto f-oh">
		<div class="bread-crumbs w1185 mtauto">
            <span>{{ partial('layouts/ur_here') }}图片墙</span>
        </div>
		<!-- 左侧 -->
		{{ partial('layouts/navs_left') }}
		
		<!-- 右侧 -->
			<div class="center-right f-fr">

				<div class="my-pictureWall f-oh">
					<div class="title f-oh">
						<span>图片墙</span>
						<a href="http://{{url}}{{ constant('CUR_DEMAIN') }}/indexfarm/index" target="_Blank">农场预览</a>
					</div>
					<div class="add-newWall">
						<input class="btn" type="button" onclick="newWindows('picturewall', '图片墙－上传图片', '/member/picturewall/upfile')" value="新增">
					</div>
					<table cellpadding="0" cellspacing="0" width="870" class="list">
						<tr height="38">
							<th width="254">图片</th>
							<th width="253">标题</th>
							<th width="254">内容</th>
							<th width="104">操作</th>
						</tr>
						{% for key,val in credible_farm_picture["items"] %}
						<tr height="138" class="imgBox">
							<td align="center">
								<ul class="gallery" style="*padding-top:20px;">
									<li>
										<a href="{{ constant('IMG_URL') }}{{val['picture_path']}}">
											<img src="{{ constant('IMG_URL') }}{{val['picture_path']}}" height="120" width="120" alt="">
										</a>
									</li>
									<li style="height:1px;">
										<a href="">
											<img style="opacity:0; filter:alpha(opacity:0);" src="" alt="">
										</a>
									</li>
								</ul>
							</td>
							<td align="center">{{val['title']}}</td>
							<td align="center">
								<span>{{val['desc']}}</span>
							</td>
							<td align="center">
								<a href="javascript:void(0);" onclick="closeImgup(this,{{val['id']}});">删除</a>
							</td>
						</tr>
						{% endfor  %}
					</table>
					<!-- 分页 -->
				
					
					<form action="/member/picturewall/index" method="get">
					<div class="esc-page mt30 mb30 f-tac f-fr mr30">

						   {% if credible_farm_picture['total_count']>1 %}
								{{credible_farm_picture['pages']}}
								<span>
				                    <label>去</label>
				                    <input type="text" name='p' id="p" onkeyup="value=value.replace(/[^\d]/g,'') " onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" value="1">
				                    <label>页</label>
				                </span>
				                <input type="hidden" name="total" value="{{credible_farm_picture['total_count']}}">
		                        <input class="btn" type="submit" value="确定" onclick="go()">
						{% endif %}
					</div>
					</form>
                    
					</div>
					
			</div>

		</div>
	</div>
	<!-- 右侧结束-->
	</div>
</div>
<!--底部-->
{{ partial('layouts/footer') }}}
<!-- 图片放大预览 -->
<link rel="stylesheet" href="http://yncstatic.b0.upaiyun.com/mdg/version2.5/css/zoom.css" media="all">
<script src="http://yncstatic.b0.upaiyun.com/mdg/version2.5/js/zoom.js"></script>
<style>
	#zoom{ *background:#000; *opacity:1; *filter:alpha(opacity:100);}
	#zoom .previous, #zoom .next, #zoom #previous, #zoom #next{display:none; opacity:0; filter:alpha(opacity:0);}
</style>
<script>
function go(){
	 var p=$("#p").val();
 var count = {{credible_farm_picture['total_count']}};
 if(p>count){
 	$("#p").val(count);
 }
}
function closeImgup(obj, id) {
	$.getJSON('/member/farm/deleteImg', {id : id}, function(data) {
		alert(data.msg);
		if(data.state) {
			$(obj).parents('.imgBox').slideUp();
		}
	});
}
</script>