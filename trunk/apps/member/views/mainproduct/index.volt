<!--头部 start-->
{{ partial('layouts/member_header') }}
<!--头部 end-->
<link rel="stylesheet" href="{{ constant('STATIC_URL') }}mdg/version2.4/css/trusted-farm/trusted-farm.css">
<!--主体 start-->
<div class="wrapper">
	<div class="w1190 mtauto f-oh">
		<div class="bread-crumbs w1185 mtauto">
            <span>{{ partial('layouts/ur_here') }}主营产品推荐</span>
        </div>
		<!-- 左侧 start-->
		{{ partial('layouts/navs_left') }}
		<!-- 左侧 end-->
		
		<!-- 右侧 start-->
		<!-- 右侧 -->
			<div class="center-right f-fr">

				<div class="main-recommon f-oh pb30">
					<div class="title f-oh">
						<span>主营产品推荐</span>
						<a href="http://{{url}}{{ constant('CUR_DEMAIN') }}/indexfarm/index" target="_Blank">农场预览</a>
					</div>
					<div class="message f-oh">
						<select name="maxcategory" id="maxcategory" class="selectCateForJs f-fl" data-target="#msg">
							<option value="">选择分类</option>
						</select>
						<select name="category"  id="selectcate" class="selectCateForJs f-fl" data-target="#msg">
							<option value="">选择分类</option>
						</select>
						<select name="product"  id="product" class="selectCateForJs f-fl" data-target="#msg">
							<option value="">选择产品</option>
						</select>
						<span class="f-fl" style="display:inline-block; height:40px; line-height:40px; margin-left:6px;">
							<a href="javascript:;" onclick="farmrecom();">推荐</a>  最多可推荐6个商品到农场首页
						</span>
					</div>
					<!-- <div class="m-tips">
						<a href="javascript:;" onclick="farmrecom();">推荐</a>  最多可推荐6个商品到农场首页
					</div> -->
					<table cellpadding="0" cellspacing="0" width="870" class="list">
						<tr height="38">
							<th width="360">图片</th>
							<th width="360">产品名</th>
							<th width="148">操作</th>
						</tr>
						{% if farmgoods %}
						{% for key,val in farmgoods['items'] %}
						<tr height="138">
							<td align="center">
								<ul class="gallery" style="*padding-top:20px;">
									<li>
										<a href="{{ val['picture_path'] }}">
											<img src="{{ val['picture_path'] }}" height="120" width="120" alt="">
										</a>
									</li>
									<li style="height:1px;">
										<a href="">
											<img style="opacity:0; filter:alpha(opacity:0);" src="" alt="">
										</a>
									</li>
								</ul>								
							</td>
							<td align="center">
								<span class="m-middle">{{ val['goods_name'] }}</span>
							</td>
							<td align="center">
								<a href="javascript:;" onclick="isrecom({{ val['id'] }})">取消推荐</a>
							</td>
						</tr>
						{% endfor %}
						{% endif %}
					</table>
					<!-- 分页 -->
                    {% if farmgoods['pages'] and farmgoods['items'] %}
                    <div class="esc-page mt30 mb30 f-tac f-fr mr30">
                        {{ farmgoods['pages'] }}
                          <span>
                        <form action="/member/mainproduct/index" method="get">
                            <label>去</label>
                            <input type="text" name="p" id="p" onkeyup="value=value.replace(/[^\d]/g,'') " value="1" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" />
                            <label>页</label>
                        </span>    
                        <input class="btn" type="submit" value="确定" onclick="go()"/>
                        </form>
                    </div>
                    {% endif  %}
				</div>

			</div>
		<!-- 右侧 end-->
	</div>
</div>
<input type="hidden" name="userId" id="userId" value="{{ userId }}">
<!--主体 end-->

<!--尾部 start-->
{{ partial('layouts/footer') }}
<!--尾部 end-->
<!-- 图片放大预览 -->
<link rel="stylesheet" href="http://yncstatic.b0.upaiyun.com/mdg/version2.5/css/zoom.css" media="all">
<script src="http://yncstatic.b0.upaiyun.com/mdg/version2.5/js/zoom.js"></script>
<style>
	#zoom{ *background:#000; *opacity:1; *filter:alpha(opacity:100);}
	#zoom .previous, #zoom .next, #zoom #previous, #zoom #next{display:none; opacity:0; filter:alpha(opacity:0);}
</style>
<script type="text/javascript">
function go(){
 var p=$("#p").val();
 var count = {{farmgoods['total']}};
 if(p>count){
 	$("#p").val(count);
 }
}
// 获取分类
jQuery(document).ready(function(){
	var uid=$("#userId").val();
	$(".selectCateForJs").ld({ajaxOptions : {"url" : "/ajax/getfarmcate?uid="+uid},
		 defaultParentId : 0
	});
});
// 取消推荐
function isrecom(id) {
	$.ajax({
		type:"POST",
		url:"/member/mainproduct/recommendCansel",
		data:{id:id},
		dataType:"json",
		success:function(msg){
			if(msg['code'] == 0) {
				newWindows('login', '登录', "/member/dlogin/index?ref=/mainproduct/index&islogin=1");
				
			} else if(msg['code'] == 3) {
				alert(msg['result']);
				window.location.reload();
				
			} else if( msg['code'] == 2 ){
				alert(msg['result']);
				window.location.reload();
			} else {
				alert(msg['result']);
				return;
			}
		}
	});
}
// 推荐
function farmrecom() {
	var cidone 	= $("#maxcategory").val();
	var cidtwo 	= $("#category").val();
	var sell_id = $("#product").val();
	$.ajax({
		type:"POST",
		url:"/member/mainproduct/recommfram",
		data:{sell_id:sell_id,cidone:cidone,cidtwo:cidtwo},
		dataType:"json",
		success:function(msg){
			if(msg['code'] == 0) {
				newWindows('login', '登录', "/member/dlogin/index?ref=/mainproduct/index&islogin=1");
			} else if(msg['code'] == 3) {
				alert(msg['result']);
				window.location.reload();
			} else {
				alert(msg['result']);
				return;
			}
		}
	});
	
}
</script>