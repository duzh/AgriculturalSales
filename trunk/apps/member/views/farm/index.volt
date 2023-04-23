<!--头部-->
{{ partial('layouts/member_header') }}
<div class="wrapper">
	<div class="w1190 mtauto f-oh">
		<div class="bread-crumbs w1185 mtauto">
            <span>{{ partial('layouts/ur_here') }}农场介绍</span>
        </div>
		<!-- 左侧 -->
		{{ partial('layouts/navs_left') }}
		<!-- 右侧 -->
		<div class="center-right f-fr">

			<div class="farm-intro f-oh pb30">
				<div class="title f-oh">
					<span>农场介绍</span>
					<a href="http://{{url}}{{ constant('CUR_DEMAIN') }}/indexfarm/index" target="blank">农场预览</a>
				</div>
				<div class="add-newPhoto">
					<font>图文介绍：</font>
					<input class="btn" type="button"  value="新  增" id="addNew">
				</div>
				<table cellpadding="0" cellspacing="0" width="806" class="list">
					<tr height="38">
						<th width="231">图片</th>
						<th width="231">标题</th>
						<th width="231">内容</th>
						<th width="95">操作</th>
					</tr>
					{% if credible_farm_picture%}
                    {% for v in credible_farm_picture %}
					<tr height="138" class="imgBox">
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
							<span>{{v['desc']}}</span>
						</td>
						<td align="center">
							<a class="f-db" href="#" onclick="closeImgup(this, {{v['id']}});">删除</a>
						</td>
					</tr>
					{% endfor %}
                    {% endif %}
				</table>
				<div class="line"></div>
				<!--
				<form action="/member/farm/upsave" method="post" id="customForm">
					<div class="message clearfix">
						<font>自定义内容：</font>
						<div class="areaBox f-pr" id="textArea">
							<?php echo $this->tag->textArea(array("custom_content","data-rule"=>"自定义内容:length[1~300]","name"=>"News")) ?>
							<div class="tips">
								您还可以输入<em class="numbers" style="color:red;"></em>个字
							</div>
							<input type="hidden" id="srNum" name="srNum" value="" />
						</div>
					</div>
					
					<input class="confirm-btn" type="submit" value="提交">
				</form>
				-->
			</div>

		</div>


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
<script type="text/javascript">
var ue = UE.getEditor('container');
$('#customForm').on('submit', function(){
		if(UE.getEditor('container').hasContents()){
			$('#shopdesc').val('1');
		}else{
			$('#shopdesc').val('');
		};
});
function closeImgup(obj, id) {
	$.getJSON('/member/farm/deleteImg', {id : id}, function(data) {
		alert(data.msg);
		if(data.state) {
			$(obj).parents('.imgBox').slideUp();
			parent.location.reload()
		}
	});
}
$(function(){
	$("#addNew").click(function(){
		var max=$(".imgBox").size();
		if(max>=3){
			alert("最多只能添加3个！");
		}else{
			newWindows('add-description', '新增图文介绍','/member/farm/add_description');
		}

	});
	//验证
	$('#customForm').validator({
		fields: {
			'News': '自定义内容:required;'
		}
	});
	//字数统计
	// var text=$("#textArea textarea").val();
	// var counter=text.length;
	// if(counter==0){
	// 	$("#textArea em").text(300);
	// 	$('#srNum').val(300-counter);
	// }else{
	// 	$("#textArea em").text(100-counter);
	// 	$('#srNum').val(300-counter);
	// }
	// $(document).keyup(function() {
 //        var text=$("#textArea textarea").val();
 //        var counter=text.length;
	// 	if(counter > 300){
	// 		$('#srNum').val('');
	// 		return ;
	// 	}else{
	// 		$("#textArea em").text(300-counter);
	// 		$('#srNum').val(300-counter);
	// 	};
 //    });
});

</script>
