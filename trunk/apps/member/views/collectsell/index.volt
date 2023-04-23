<!--头部-->
{{ partial('layouts/member_header') }}
<div class="wrapper">
	<div class="w1190 mtauto f-oh">
		<div class="bread-crumbs w1185 mtauto">
	        <span>{{ partial('layouts/ur_here') }}&nbsp;收藏的供应</span>
	    </div>
		<!-- 左侧 -->
		{{ partial('layouts/navs_left') }}
		<!-- 右侧 -->
			<div class="center-right f-fr">

				<div class="collect-sell f-oh pb30">
					<div class="title f-oh">
						<span>收藏的供应</span>
					</div>
					<table cellpadding="0" cellspacing="0" width="867" class="list">
						<tr height="31">
							<th width="222">
								<span class="m-left">商品图片</span>
							</th>
							<th width="210">产品名</th>
							<th width="262">卖家</th>
							<th width="174">
								<font class="m-right">操作</font>
							</th>
						</tr>
						{% if sellList %}
						{% for key,val in sellList['items'] %}
						<tr height="136">
							<td>
								<span class="m-left">
									<img src="{{ constant('IMG_URL') }}{{ val['goods_picture'] }}" height="120" width="120" alt="">
								</span>
							</td>
							<td align="center">{{ val['goods_name'] }}</td>
							<td align="center">{{ val['uname'] }}</td>
							<td>
								<font class="m-right">
									<a href="/sell/info/{{ val['sell_id'] }}">查看详情</a>
									<a href="javascript:;" onclick="sellcansel({{ val['id'] }},{{ val['sell_id'] }})">取消收藏</a>
								</font>
							</td>
						</tr>
						{% endfor %}
						{% endif %}
					</table>
					<!-- 分页 -->
					{% if sellList['total_pages']>1 and sellList['total_pages']!=0 %}
					<div class="esc-page mt30 mb30 f-tac f-fr mr30">
						{{sellList['pages']}}
						 <span>
                        <form action="/member/collectsell/index" method="get">
                            <label>去</label>
                            
                            <input type="text" name="p"  id="p" onkeyup="value=value.replace(/[^\d]/g,'') " onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" value="1"/>
                            <label>页</label>
                        </span>    
                        <input type="hidden" name="total" value="{{sellList['total_pages']}}">
                        <input class="btn" type="submit" value="确定" />
                        </form>
					</div>
					{% endif %}
				</div>
			</div>
		<!--右侧结束-->
	</div>
</div>
<!--底部-->
{{ partial('layouts/footer') }}}
<script type="text/javascript">
function go(){
var p=$("#p").val();
 var count = {{sellList['total']}};
 if(p>count){
    $("#p").val(count);
 }
}
// 取消收藏
function sellcansel(id,pid) {
	$.ajax({
		type:"POST",
		url:"/member/collectsell/collCansel",
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