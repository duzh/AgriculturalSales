<!--头部-->
{{ partial('layouts/member_header') }}
<div class="wrapper">
	<div class="w1190 mtauto f-oh">
		<div class="bread-crumbs w1185 mtauto">
	        <span><a href="/">首页</a>&nbsp;&gt;&nbsp;<a href="/member">个人中心 </a>&nbsp;&gt;&nbsp;我的补贴</span>
	    </div>
		<!-- 左侧 -->
		{{ partial('layouts/navs_left') }}
		<!-- 右侧 -->
		<div class="center-right f-fr">

			<div class="my-subsy f-oh pb30">
				<div class="sudsy-yue">
					<div class="m-title">我的补贴</div>
					<div class="price">
						可用补贴金额：<i><?php echo isset($info->subsidy_left_subsidy) ? $info->subsidy_left_subsidy : 0;?></i>元
					</div>
				</div>
				<div class="sudsy-detial f-oh">
					<div class="m-title">补贴明细</div>
					<table cellpadding="0" cellspacing="0" width="100%" class="list">
						<tr height="41">
							<th width="252">
								<span class="m-left">日期</span>
							</th>
							<th width="262">
							<form action="/member/subsidy/index" id='selectSumForm'>
								<select name="state"  onchange="selectSum(this.value)">									
									<option value="0" {% if state == 0 %} selected {% endif %}>收入/支出</option>
									<option value="1" {% if state == 1 %} selected {% endif %} >收入</option>
									<option value="2" {% if state == 2 %} selected {% endif %}>支出</option>
								</select>
							</form>
							</th>
							<th width="415">
								<span class="m-right">详细说明</span>
							</th>
						</tr>
						{% for key , item in data['items']%}
						<tr height="43">
							<td>
								<span class="m-left">{{ date('Y-m-d H:i:s', item.add_time)}}</span>
							</td>
							<td align="center">
								<i>{{ item.amount}}</i>元
							</td>
							<td>
								<span class="m-right">{{  item.demo}}</span>
							</td>
						</tr>
						{% endfor %}
					</table>
					<!-- 分页 -->
                    {% if data['total_count']>1 %}
    					<div class="esc-page mt30 mb30 f-tac f-fr mr30">
    						{{ data['pages'] }}
                            <span>
                            <form action="/member/subsidy/index" method="get">
                                <label>去</label>
                                <input type="text" name="p" id="p" onkeyup="value=value.replace(/[^\d]/g,'') " value="1" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" />
                                <label>页</label>
                            </span>    
                            <input class="btn" type="submit" value="确定" onclick="go()"/>
                            </form>
    					</div>
                    {% endif %}
				</div>
			</div>

		</div>		

	</div>
</div>
<!--底部-->
{{ partial('layouts/footer') }}}

<script type="text/javascript">
  function go(){
var p=$("#p").val();
 var count = {{data['total_count']}};
 if(p>count){
    $("#p").val(count);
 }
}  
    function selectSum(val) {
        $('#selectSumForm').submit();
    }
</script>
