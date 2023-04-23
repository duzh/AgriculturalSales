<!--头部-->
{{ partial('layouts/member_header') }}
<div class="wrapper">
	<div class="w1190 mtauto f-oh">
		<div class="bread-crumbs w1185 mtauto">
	        <span>{{ partial('layouts/ur_here') }}标签管理</span>
	    </div>
		<!-- 左侧 -->
		{{ partial('layouts/navs_left') }}
		<!-- 右侧 -->
		<div class="center-right f-fr">

			<div class="tag-manage f-oh">
				<div class="title f-oh">
					<span>标签管理</span>
				</div>
				<table cellpadding="0" cellspacing="0" width="875" class="list">
					<tr height="38">
						<th width="16%">
							<span>商品名称</span>
						</th>
						<th width="15%">所属分类</th>
						<th width="17%">产地</th>
						<th width="12%">保质期（天）</th>
						<th width="14%">创建时间</th>
						<th width="14%">状态</th>
						<th width="12%">操作</th>
					</tr>
					{% for key,val in data['items'] %}
					<tr height="78">
						<td>
							<span><?php echo isset($val->goods_name) ? Lib\Func::sub_str($val->goods_name,8) : ''; ?></span>
						</td>
						<td align="center">
						<?php
								echo Mdg\Models\Category::selectBytocateName($val->
										category_one).">>";
								echo Mdg\Models\Category::selectBytocateName($val->category_two).">>";
								echo Mdg\Models\Category::selectBytocateName($val->category_three);
								?>
						</td>
						<td align="center">
							<em>
								<?php 
									echo Mdg\Models\AreasFull::getAreasNametoid($val->
											province);
									echo Mdg\Models\AreasFull::getAreasNametoid($val->city);
									echo Mdg\Models\AreasFull::getAreasNametoid($val->district);
									echo $val->address;?>
							</em>
						</td>
						<td align="center">{{ val.expiration_date }}</td>
						<td align="center">
							<em>
								{{ date('Y-m-d' , val.add_time )}}<br /> 
								{{ date('H:i:s' , val.add_time )}}
							</em>
						</td>
						<td align="center">{{ _STATUS[val.status]}}</td>
						<td align="center">
								{% if val.status == 2 %}
									<a href="/member/tag/edit/{{val.tag_id}}">修改</a>
								{% endif %}
								{% if val.status == 1 or val.status == 3 %}
									<a href="/member/tag/get?itd={{ val.tag_id}}">查看</a>
								{% endif %}
						</td>
					</tr>
					 {% endfor %}
				</table>
				<!-- 分页 -->
				{% if data['total_count']> 1  %}
				<form action="index" medthod="get">
				<div class="esc-page mt30 mb30 f-tac f-fr mr30">
					{{data['pages']}}
				
					<span>
						<label>去</label>
						<input type="text" name="p" id="p" onkeyup="value=value.replace(/[^\d]/g,'') " onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" value="1"  />
						<label>页</label>
					</span>
					<input class="btn" type="submit" value="确定" onclick="go()" />
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
 var count = {{data['total_count']}};
 if(p>count){
    $("#p").val(count);
 }
}	

</script>
