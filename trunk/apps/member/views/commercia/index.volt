<!--头部-->
{{ partial('layouts/member_header') }}
<div class="wrapper">
	<div class="w1190 mtauto f-oh">
		<div class="bread-crumbs w1185 mtauto">
            <span>{{ partial('layouts/ur_here') }}我的商户</span>
        </div>
		<!-- 左侧 -->
		{{ partial('layouts/navs_left') }}
		<!-- 右侧 -->
		<div class="center-right f-fr">

			<div class="sell-purchase f-oh pb30">
				<div class="title f-oh">
					<span>我的商户</span>
				</div>
				<form action="/member/commercia/index" method="get">
				<div class="message clearfix">
					<div class="box clearfix">
						<font>用 户 ID：</font>
						<input class="l-input" type="text" name="userid" value="<?php if(isset($_GET['userid'])){?>{{ _GET['userid']}}<?php }?>">
					</div>
					<div class="box clearfix">
						<font>手机号：</font>
						<input class="l-input" type="text" name="mobile" value="<?php if(isset($_GET['mobile'])){?>{{ _GET['mobile']}}<?php }?>">
					</div>
					<div class="box clearfix">
						<font>姓名：</font>
						<input class="l-input" type="text" name="username" value="<?php if(isset($_GET['username'])){?>{{ _GET['username']}}<?php }?>">
					</div>
				</div>
				<div class="message clearfix">
					<!-- <div class="box clearfix">
						<font>服务区域：</font>
						<select class="s-select mr2" name="province">
							<option value="0">省</option>
						</select>
						<select class="s-select mr2" name="city">
							<option value="0">市</option>
						</select>
						<select class="s-select" name="district">
							<option value="0">县/区</option>
						</select>
					</div> -->
					<div class="box clearfix">
						<font>注册时间：</font>
						<input class="s-input" type="text" id="d4311" value="{% if stime %}{{stime}}{% endif %}" name="stime" onfocus="WdatePicker({maxDate:'#F{$dp.$D(\'d4312\')||\'2020-10-01\'}'})">
						<i>-</i>
						<input class="s-input" type="text" id="d4312" value="{% if etime %}{{etime}}{% endif %}" name="etime" onfocus="WdatePicker({minDate:'#F{$dp.$D(\'d4311\')}',maxDate:'2020-10-01'})">
					</div>
				</div>
				<input class="search-btn" type="submit" value="搜  索">
				<table cellpadding="0" cellspacing="0" width="100%" class="list">
					<tr height="41">
						<th width="138">
							<span class="m-left">用户ID</span>
						</th>
						<th width="162">手机号</th>
						<th width="238">注册时间</th>
						<th width="148">用户身份</th>
						<th width="148">姓名</th>
						<th width="96">
							<font class="m-right">操作</font>
						</th>
					</tr>
					{% for key,val in userinfo["items"] %}
					<tr height="41">
						<td>
							<span class="m-left">{{val["id"]}}</span>
						</td>
						<td align="center">{{val["username"]}}</td>
						<td align="center">{{date("Y-m-d",val["regtime"])}} {{date("H:i:s",val["regtime"])}}</td>
						<td align="center">{{val["type"]}}</td>
						<td align="center">{{val["name"]}}</td>
						<td>
							<font class="m-right">
								<a href="/member/perfect/info?user_id=<?php echo base64_encode($val['id']); ?>">详情</a>
							</font>
						</td>
					</tr>
					{% endfor %}
				</table>
				<!-- 分页 -->
				{% if userinfo["total_count"]>1 %}
				<div class="esc-page mt30 mb30 f-tac f-fr mr30">
					{{userinfo["pages"]}}
			        <span>
                        <label>去</label>
                        <input type="text" name="p" id="p" onkeyup="value=value.replace(/[^\d]/g,'') " value="1" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" />
                        <label>页</label>
                    </span>    
                    <input class="btn" type="submit" value="确定" onclick="go()"/>
                    
				</div>
				{% endif %}
				</form>
			</div>

		</div>

	</div>
</div>
<!--底部-->
<script type="text/javascript" src="{{ constant('STATIC_URL') }}DatePicker/DatePicker/WdatePicker.js"></script>
{{ partial('layouts/footer') }}}
<script type="text/javascript">
function go(){
var p=$("#p").val();
 var count = {{userinfo['total_count']}};
 if(p>count){
    $("#p").val(count);
 }
}
$(".s-select").ld({ajaxOptions:{"url":"/ajax/getareasfull"},
    defaultParentId : 0,
    {% if (curAreas) %}
    texts : [{{ curAreas }}],
    {% endif %}
    style : {"width" : 140}
});
</script>