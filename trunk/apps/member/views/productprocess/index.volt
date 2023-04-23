<!--头部-->
{{ partial('layouts/member_header') }}
<link rel="stylesheet" href="http://yncstatic.b0.upaiyun.com/mdg/version2.5/css/verfiy.css">
<div class="wrapper">
	<div class="w1190 mtauto f-oh">
		<div class="bread-crumbs w1185 mtauto">
            <span>{{ partial('layouts/ur_here') }}产品种植过程</span>
        </div>
		<!-- 左侧 -->
		{{ partial('layouts/navs_left') }}
		<!-- 右侧 -->
		<div class="center-right f-fr">

			<div class="grow-process f-oh">
				<div class="title f-oh">
					<span>产品种植过程</span>
					<a href="http://{{url}}{{ constant('CUR_DEMAIN') }}/indexfarm/index" target="_Blank">农场预览</a>
				</div>

				<form action="/member/productprocess/save" method="post" >
				<div class="add-newGrow" style="width:100%;">
					<font>新增种植产品：</font>
					<input  class="txt" type="text" placeholder="请输入产品名称" name="goods_name" data-target="#xz-tips" data-rule="产品名称:required;length[~20]" >
					<input class="btn" type="submit" value="新  增"  >
					<i id="xz-tips" class="f-db f-fl f-pr" style="width:30%; margin-top:10px; margin-left:4px;"></i>
				</div>
				</form>
				<table cellpadding="0" cellspacing="0" width="778" class="list">
					<tr height="38">
						<th width="100">序号</th>
						<th width="245">产品名称</th>
						<th width="245">添加时间</th>
						<th width="183">操作</th>
					</tr>
					{% if crediblefarmgoodsplant is defined %}
						<?php $i=($current-1)*15+1 ?>
					{% for k,v in crediblefarmgoodsplant %}
					<tr height="38" class="imgBox">
						<td align="center"><?php echo $i++;?></td>
						<td align="center">{{v['goods_name']}}</td>
						<td align="center">{{date("Y/m/d",v['add_time'])}}</td>
						<td align="center">
							<a style="line-height:20px;" class="f-db" href="/member/productprocess/list/{{v['goods_id']}}/{{v['goods_name']}}">查看过程</a>
							<a style="line-height:20px;" class="f-db" href="javascript:void(0);" onclick="closeImgup(this, {{v['goods_id']}});">删除</a>
						</td>
					</tr>
					{% endfor %}
					{% endif %}
				</table>
				<!-- 分页 -->
				{% if total_count>1 and total_count!=0 %}
				<form action="/member/productprocess/index" method="get">
				<div class="esc-page mt30 mb30 f-tac f-fr mr30">
						{{ pages }}
					<span>
						<label>去</label>
						<input type="text" name="p" id="p" onkeyup="value=value.replace(/[^\d]/g,'') " onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" value="1" />
						<label>页</label>
					</span>
					<input class="btn" type="submit" value="确定" onclick="go()"/>
				</div>
				</form>
				{% endif %}
				
			</div>

		</div>		

	</div>
</div>
<!--底部-->
{{ partial('layouts/footer') }}}
<script>
function go(){
 var p=$("#p").val();
 var count = {{total_count}};
 if(p>count){
 	$("#p").val(count);
 }
}
function closeImgup(obj, id) {
	$.getJSON('/member/productprocess/deleteImgup', {id : id}, function(data) {
		alert(data.msg);
		if(data.state) {
			$(obj).parents('.imgBox').slideUp();
		}
	});

}

</script>
<style>
	.n-right .n-ok .n-msg{ left:0; top:3px;}
</style>