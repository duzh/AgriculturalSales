<!--头部-->
{{ partial('layouts/member_header') }}
<div class="wrapper">
	<div class="w1190 mtauto f-oh">
		<div class="bread-crumbs w1185 mtauto">
	        <span>{{ partial('layouts/ur_here') }}&nbsp;&gt;&nbsp;我评价过的商品</span>
	    </div>
		<!-- 左侧 -->
		{{ partial('layouts/navs_left') }}
		<!-- 右侧 -->
		<div class="center-right f-fr">

			<div class="evaluated-goods f-oh pb30">
				<div class="title f-oh">
					<span>我评价过的商品</span>
				</div>
				<table cellpadding="0" cellspacing="0" width="865" class="list">
					<tr height="31">
						<th width="162">
							<div class="m-left">商品图片</div>
						</th>
						<th width="130">商品名称</th>
						<th width="170">评价内容</th>
						<th width="105">描述打分</th>
						<th width="100">评价时间</th>
						<th width="100">状态</th>
						<th width="100">操作</th>
					</tr>
					{% for key , val in data%}
					<tr height="137" class="imgBox">
						<td>
							<div class="m-left">
								<img src="{{val['thumb']}}" height="120" width="120" alt="">
							</div>
						</td>
						<td>
							<span class="m-middle">{{val['goods_name']}}</span>
						</td>
						<td>
							<span class="m-middle f-tac">{{val['comment']}}</span>
						</td>
						<td align="center">
							<div class="stars f-oh">
								<?php
									switch($val['scores']){
										case "五": $n=5;break;
										case "四": $n=4;break;
										case "三": $n=3;break;
										case "二": $n=2;break;
										case "一": $n=1;break;
									}
								for($i=0;$i<$n;$i++){
									echo '<span class="star active"></span>' ;
								}
								for($j=0;$j<5-$n;$j++){
									echo '<span class="star"></span>';
								}
								?>
								
								
							</div>
						</td>
						<td align="center">{{val['addtime']}}</td>
						<td align="center">{{val['is_status']}}</td>
						<td align="center">
							{% if val["is_check"]!=3 %}<a href="javascript:void(0);" onclick="closeImgup(this, {{val['id']}});">删除</a>{% endif %}
						</td>
					</tr>
					{% endfor %}
				</table>
				<!-- 分页 -->
				{% if total_count>1 %}
				<div class="esc-page mt30 mb30 f-tac f-fr mr30">
					{{pages}}
					
					<span>
						<form action="/member/answer/list" method="get">
						<label>去</label>
						<input type="text" name="p" id="p" onkeyup="value=value.replace(/[^\d]/g,'') " onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" value="1">
						<label>页</label>
					</span>
					<input class="btn" type="submit" value="确定" onclick="go()" />
					</form>
				</div>
				{% endif %}
			</div>

		</div>

	</div>
</div>
<!--底部-->
{{ partial('layouts/footer') }}}
<script type="text/javascript">
function go(){
var p=$("#p").val();
 var count = {{total_count}};
 if(p>count){
    $("#p").val(count);
 }
}
function closeImgup(obj, id) {
      if(confirm('确定要删除吗？')){
	$.getJSON('/member/answer/del', {id : id}, function(data) {
		alert(data.msg);
		if(data.state) {
			// $(obj).parents('.imgBox').slideUp();
			location.reload();
		}
	});
      }
}
</script>