{{ content() }}
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />

<div class="main">
<!-- main_right 开始  -->
    <div class="main_right">
        <div class="bt2">文章推荐</div>
        <div class="chaxun">
		{{ form("advisory/recom", "method":"post",'id':'recom') }}
            <table width="100%" border="0" cellspacing="0" cellpadding="0"  id="childLast">
                <tr>
                    <td class="cx_title">推荐到：</td>
                    <td class="cx_content">
                        {% for index, state in is_recom %}
							{% if index == child %}
								<input type="radio" id="is_recom" name="is_recom" checked="checked" value="{{ index }}" onclick="recom({{ index }});">&nbsp;{{ state }}
								{% else %}
								<input type="radio" id="is_recom" name="is_recom" value="{{ index }}" onclick="recom({{ index }});">&nbsp;{{ state }}
							 {% endif %}
                        {% endfor %}
						
                    </td>
                </tr>
				<tr>
                    <td class="cx_title">主分类：</td>
                    <td class="cx_content">
                       <select name="pid" id="pid">
							{% for key, value in recoms[1] %}
                            <option value="{{ key }}" {% if pid == key %} selected="selected" {% endif %}>{{ value }}</option>
							{% endfor %}
						</select>
                    </td>
                </tr>
				<!--<tr>
                    <td class="cx_title">子分类：</td>
                    <td class="cx_content">
                       <select name="childid" id="childid">
						{% for key, value in childCat %}
							<option value="{{ value['id'] }}" {% if value['id'] == key %} selected="selected" {% endif %}>{{ value['catname'] }}</option>
						{% endfor %}
					   </select>
                    </td>
                </tr>-->
            </table>
			<div class="btn">{{ submit_button("查询","class":'sub') }}</div>
		</form>
			<div id="tb">
				<!--<form id="recom" method="post" action="/manage/advisory/recom" onsubmit="fun();">-->
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr align="left">
						<select name="source_select1" id="source_select1" size="20" style="width:50%" multiple="true">
							{% if cats %}
								{% for article in cats %}
									<option id="article_{{ article['id'] }}" value="{{ article['id'] }}">{{ article['title'] }}</option>
								{% endfor %}
							{% endif %}
						</select>
					</tr>
				</table>
				<div class="btn"><input type="button" class="sub" value="推荐" onclick="fun();"></div>
				<!--</form>-->
			</div>
			<div class="neirong" id="tb">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr align="center">

						<td width='6%'  class="bj">编号</td>
						<td width='10%' class="bj">推荐位</td>
						<td width='20%' class="bj">文章标题</td>
						<td width='10%' class="bj">文章分类</td>
						<td width='14%' class="bj">推荐时间</td>
						<td width='8%' class="bj">操作</td>
					</tr>
					{% if recomList %}
					{% for key,val in recomList['items'] %}
					<tr align="center">
						<td>{{ val['id'] }}</td>
						<td>
						{% if val['is_hot'] == 1 %}
							相关推荐
						{% elseif val['is_hot'] == 2 %}
							副轮播
						{% elseif val['is_hot'] == 3 %}
							带图标题
						{% elseif val['is_hot'] == 4 %}
							公告版
						{% else %}
							主轮播
						{% endif %}
						</td>
						<td>{{ val['title'] }}</td>
						<td>{{ val['catname'] }}</td>
						<td>{{ date('Y-m-d H:i:s',val['recomtime']) }}</td>
						<td><a href='javascript:void(0);' onclick="delRecom({{ val['id'] }});">取消推荐</a>
					</tr>
					{% endfor %}
					{% endif %}
				</table>
			</div>
			{{ form("advisory/recom", "method":"get") }}
				<div class="fenye">
					<div class="fenye1">
						<span>{{ recomList['pages'] }}</span> <em>跳转到第
							<input type="text" class='input' name='p' <?php if(isset($_GET['p'])&&$_GET['p']!=''){ echo "value='".$_GET['p']."'" ;}else{ echo "value='1'"; } ?>/>页</em>
						<input class="sure_btn"  type='submit' value='确定'></div>
				</div>
			</form>
			<!-- 搜索 -->
			{{ form("advisory/recom", "method":"get") }}
				<div class="neirong" id="tb">
						<select name="keywords" id="keywords">
						{% for index, state in is_recom %}
							<option id="{{ index }}" value="{{ index }}">{{ state }}</option>
						{% endfor %}
						</select>
						<input class="sure_btn"  type='submit' value='确定'></div>
				</div>
			</form>
        </div>     
	</div>
<!-- main_right 结束  -->
</div>
<div class="footer">Copyright © 2013-2014 ync365.com All rights reserved.</div>
<script type="text/javascript">
//根据推荐位获取分类	
function recom(id){
	var cat_recom = '{{ cat_recom }}';
	var obj = JSON.parse(cat_recom);
	var htmls="";
	$.each(obj,function(i,item){  
		if(id == i){
			$.each(item,function(j,items){
				htmls+='<option value='+j+'>'+items+'</option>';
			});
		}
	});
	$("#pid").empty().append( htmls );
}
//根据主分类获取子类[二期会用](onclick="addSel({{ key }})";onclick="addSel('+j+')")
/*function addSel(id) {
	var htmls="";
	$.ajax({
		type:"POST",
		url:"/manage/advisory/getCats",
		data:{id:id},
		dataType:"json",
		success:function(msg){
			if($.isEmptyObject(msg)){
				$("#childid").empty();
				alert('无子分类');
				return;
			} else {
				$.each(msg,function(i,row){ 
					htmls+='<option value='+row.id+'>'+row.catname+'</option>';
				});
				$("#childid").empty().append( htmls );
			}
			
		}
	});
}*/
function fun(){
	var source_select1 	= $("#source_select1").val();
	var pid 			= $("#pid").val();
	var is_recom		= $('input:radio:checked').val();
	
	if(source_select1 == null) {
		alert('请选择推荐文章');
		return;
	}
	$.ajax({
		type:"POST",
		url:"/manage/advisory/getArticle",
		data:{source_select1:source_select1,pid:pid,is_recom:is_recom},
		dataType:"json",
		success:function(msg){
			if(msg == 'success'){
				alert('推荐成功');
				window.location.reload();
				return;
			} else {
				alert('推荐失败');
				return;
			}
		}
	});
}
function delRecom(id){
	$.ajax({
		type:"POST",
		url:"/manage/advisory/delRecom",
		data:{id:id},
		dataType:"json",
		success:function(msg){
			if(msg == 'success'){
				alert('取消成功');
				window.location.reload();
				return;
			} else {
				alert('取消失败');
				return;
			}
		}
	});
}
</script>
</body>
</html>