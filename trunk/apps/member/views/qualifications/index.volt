<!--头部-->
{{ partial('layouts/member_header') }}
<div class="wrapper">
	<div class="w1190 mtauto f-oh">
		<div class="bread-crumbs w1185 mtauto">
            <span>{{ partial('layouts/ur_here') }}资质认证</span>
        </div>
		<!-- 左侧 -->
		{{ partial('layouts/navs_left') }}
		<!-- 右侧 -->
			<div class="center-right f-fr">

				<div class="certify f-oh pb30">
					<div class="title f-oh">
						<span>资质认证</span>
						<a href="http://{{url}}{{ constant('CUR_DEMAIN') }}/indexfarm/index" target="_Blank">农场预览</a>
					</div>
					<div class="add-Newcert">
						<input class="btn" type="button"  value="新增" onclick="newWindows('add-developFoot', '新增资质认证', '/member/qualifications/addqua')">
					</div>
					<table cellpadding="0" cellspacing="0" width="870" class="list">
						<tr height="38">
							<th width="254">图片</th>
							<th width="253">资质名称</th>
							<th width="254">认证日期</th>
							<th width="104">操作</th>
						</tr>
					{% if credible_farm_picture %}
						{% for v in credible_farm_picture%}
						<tr height="138" class="imgBox">
							<td align="center">
								<ul class="gallery" style="*padding-top:20px;">
									<li>
										<a href="{{ constant('IMG_URL') }}{{v['picture_path']}}">
											<img src="{{ constant('IMG_URL') }}{{v['picture_path']}}" height="120" width="120" alt="">
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
							<td align="center">{{date('Y/m/d',v['picture_time'])}}</td>
							<td align="center">
								<a href="#" onclick="closeImgup(this, {{v['id']}});">删除</a>
							</td>
						</tr>
						{% endfor %}
					{% endif %}
					</table>
					<!-- 分页 -->
					{% if total_count>1 %}
					<form action="/member/qualifications/index" method="get">
					<div class="esc-page mt30 mb30 f-tac f-fr mr30">
							{{ pages }}
					<span>
	                    <label>去</label>
	                    <input type="text" name='p' id="p" value="1" onkeyup="value=value.replace(/[^\d]/g,'') " onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" value="1">
	                    <label>页</label>
	                </span>
	                <input class="btn" type="submit" value="确定" onclick="go()">

					</div>
					</form>
					{% endif %}
				</div> 

			</div>
		<!--右侧end-->
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
 var count = {{total_count}};
 if(p>count){
 	$("#p").val(count);
 }
}
	function closeImgup(obj, id) {
	    $.getJSON('/member/qualifications/deleteImg', {id : id}, function(data) {
	        alert(data.msg);
	        if(data.state) {
	            $(obj).parents('.imgBox').slideUp();
	        }
	    });
	}
</script>