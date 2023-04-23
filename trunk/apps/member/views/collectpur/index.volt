<!--头部-->
{{ partial('layouts/member_header') }}
<div class="wrapper">
	<div class="w1190 mtauto f-oh">
		<div class="bread-crumbs w1185 mtauto">
	        <span>{{ partial('layouts/ur_here') }}&nbsp;收藏的采购</span>
	    </div>
		<!-- 左侧 -->
		{{ partial('layouts/navs_left') }}
		<!-- 右侧 -->
			<div class="center-right f-fr">

				<div class="collect-purchase f-oh pb30">
					<div class="title f-oh">
						<span>收藏的采购</span>
					</div>
					<table cellpadding="0" cellspacing="0" width="867" class="list">
						<tr height="31">
							<th width="152">
								<span class="m-left">产品名</span>
							</th>
							<th width="268">采购地区</th>
							<th width="180">采购商</th>
							<th width="148">发布时间</th>
							<th width="118">
								<font class="m-right">操作</font>
							</th>
						</tr>
						{% if purList %}
						{% for key,val in purList['items'] %}
						<tr height="86">
							<td>
								<span class="m-left">{{ val['title'] }}</span>
							</td>
							<td align="center">
								<span class="dz">{{ val['areas_name'] }}</span>
							</td>
							<td align="center"><?php if(isset($val['username'])){ echo $val['username'];}else{echo "";}?></td>
							<td align="center">{{ val['publish_time'] }}</td>
							<td>
								<font class="m-right">
								{% if val['flag'] == 1 %}
									<a href="javascript:newWindows('newquo', '确定报价', '/member/dialog/newquo/{{val['purchase_id']}}');">报价</a>
								{% endif %}
									<a href="javascript:cansel({{ val['id'] }},{{ val['purchase_id'] }});">取消收藏</a>
								</font>
							</td>
						</tr>
						{% endfor %}
					{% endif %}
					</table>
					<!-- 分页 -->
					{% if purList['total_pages'] > 1 %}
					<div class="esc-page mt30 mb30 f-tac f-fr mr30">
						{{purList['pages']}}
						<span>
                        <form action="/member/collectpur/index" method="get">
                            <label>去</label>
                            <input type="text" name="p" id="p" onkeyup="value=value.replace(/[^\d]/g,'') " value="1" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" />
                            <label>页</label>
                            <input type="hidden" value="{{purList['total_pages']}}" name="total" >
                        </span>    
                        <input class="btn" type="submit" value="确定" />
                        </form>
					</div>
					{% endif %}
				</div>
			</div>
    	</div>
<!--底部-->
{{ partial('layouts/footer') }}}
<script type="text/javascript">

function go(){
var p=$("#p").val();
 var count = {{purList['total']}};
 if(p>count){
    $("#p").val(count);
 }
}
// 取消收藏
function cansel(id,pid) {
	$.ajax({
		type:"POST",
		url:"/member/collectpur/collCansel",
		data:{id:id},
		dataType:"json",
		success:function(msg){
		
			if(msg['code'] == 2){
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