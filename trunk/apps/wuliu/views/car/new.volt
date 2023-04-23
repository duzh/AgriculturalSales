{{ partial('layouts/page_header') }}
<div class="wuliu_body_v2">
	<div class="wuliu_v2 w1190 mtauto">
		<div class="mianbao_v2">
			<a href="/">首页</a>
			<span>&gt;</span>
			<a href="/wuliu/index">物流信息</a>
			<span>&gt;</span>
			<span>发布车源</span>
		</div>

		<div class="fabu_con_v2">
			<div class="fabu_title_v2"><strong>免费发布车源信息</strong></div>
			{{ form("car/create", "method":"post", "autocomplete" : "off" , 'id' : 'createCar') }}
			<div class="fabu_two_title_v2">车主信息</div>
			<div class="fabu_inputCon_v2">
				<div class="fabu_line_v2">
					<div class="fabu_spanBox_v2">
						<span class="input_xing_v2">姓名：</span>
					</div>
					<div class="fabu_inputBox_v2">
						<input type="text" name="contact_man" data-target="#wlName">
						<div class="fabu_tip_v2" id="wlName"></div>
					</div>
				</div>
				<div class="fabu_line_v2">
					<div class="fabu_spanBox_v2">
						<span class="input_xing_v2">电话：</span>
					</div>
					<div class="fabu_inputBox_v2">
						<input type="text" name="contact_phone" data-target="#wlTel" id="contact_phone">
						<div class="fabu_tip_v2" id="wlTel"></div>
					</div>
				</div>
			</div>
			<div class="fabu_two_title_v2">车辆信息</div>
			<div class="fabu_inputCon_v2">
				<div class="fabu_line_v2">
					<div class="fabu_spanBox_v2">
						<span class="input_xing_v2">车牌号：</span>
					</div>
					<div class="fabu_inputBox_v2">
						<input type="text" name="car_no" data-target="#chepai">
						<div class="fabu_tip_v2" id="chepai"></div>
					</div>
				</div>
				<div class="fabu_line_v2">
					<div class="fabu_spanBox_v2">
						<span class="input_xing_v2">厢形：</span>
					</div>
					<div class="fabu_inputBox_v2">
						<select name="truck_type_id" data-target="#cheType">
						    <option value="">请选择</option>
							{% for key , item in _BOX_TYPE %}
							<option value="{{ key }}">{{ item }}</option>
							{% endfor %}
						</select>
						<div class="fabu_tip_v2" id="cheType"></div>
					</div>
				</div>
				<div class="fabu_line_v2">
					<div class="fabu_spanBox_v2">
						<span class="input_xing_v2">车体：</span>
					</div>
					<div class="fabu_inputBox_v2">
						<select name="body_type" id="goods_type" data-target="#cheti">
							<option value="">请选择</option>
							{% for key , item in _BODY_TYPE %}
							<option value="{{ key }}">{{ item }}</option>
							{% endfor %}
						</select>
						<div class="fabu_tip_v2" id="cheti"></div>
					</div>
				</div>
				<div class="fabu_line_v2">
					<div class="fabu_spanBox_v2">
						<span class="input_xing_v2">长度：</span>
					</div>
					<div class="fabu_inputBox_v2">
						<input type="text" name="length" data-target="#mi">
						<div class="fabu_danwei_v2">米</div>
						<div class="fabu_tip_v2" id="mi"></div>
					</div>
				</div>
				<div class="fabu_line_v2">
					<div class="fabu_spanBox_v2">
						<span class="input_xing_v2">载重：</span>
					</div>
					<div class="fabu_inputBox_v2">
						<input type="text" name="carry_weight" data-target="#zaizhong">
						<div class="fabu_danwei_v2">吨</div>
						<div class="fabu_tip_v2" id="zaizhong"></div>
					</div>
				</div>
				<div class="fabu_line_v2">
					<div class="fabu_spanBox_v2">
						<span class="input_xing_v2">车龄：</span>
					</div>
					<div class="fabu_inputBox_v2">
						<input type="text" name="use_time" data-target="#cheling">
						<div class="fabu_danwei_v2">年</div>
						<div class="fabu_tip_v2" id="cheling"></div>
					</div>
				</div>
				<div class="fabu_line_v2">
					<div class="fabu_spanBox_v2">
						<span class="input_xing_v2">发车时间：</span>
					</div>
					<div class="fabu_inputBox_v2">
						<input type="text" name="depart_time" id="depart_time" data-target="#fabutime">
						<div class="fabu_tip_v2" id="fabutime"></div>
					</div>
				</div>
			</div>
			<div class="fabu_two_title_v2">期望流向</div>
			<div class="fabu_inputCon_v2">
				<div class="fabu_line_v2">
					<div class="fabu_spanBox_v2">
						<span class="input_xing_v2">运行方式：</span>
					</div>
					<div class="fabu_inputBox_v2">
						<select name="transport_type" data-target="#yunxing">
							<option value="">请选择</option>
						    {% for key , item in _TRANSPORT_TYPE %}
							<option value="{{ key }}">{{ item }}</option>
							{% endfor %}
						</select>
						<div class="fabu_tip_v2" id="yunxing"></div>
					</div>
				</div>
				<div class="fabu_line_v2">
					<div class="fabu_spanBox_v2">
						<span class="input_xing_v2">是否长期：</span>
					</div>
					<div class="fabu_inputBox_v2">
						<select name="is_longtime" data-target="#changqi">
							<option value="1" selected >是</option>
				             <option value="0">否</option>
						</select>
						<div class="fabu_tip_v2" id="changqi"></div>
					</div>
				</div>
				<div class="fabu_line_v2">
					<div class="fabu_spanBox_v2">
						<span class="input_xing_v2">出发地：</span>
					</div>
					<div class="fabu_inputBox_v2">
						<select name="start_pid"  class="selectAreas" data-target="#chufadi">
							<option value="">请选择</option>
						</select>
						<select name="start_cid" class="selectAreas" >
							<option value="">请选择</option>
						</select>
						<select name="start_did" class="selectAreas">
							<option value="">请选择</option>
						</select>
						<div class="fabu_tip_v2" id="chufadi"></div>
					</div>
				</div>
				<div class="fabu_line_v2">
					<div class="fabu_spanBox_v2">
						<span class="input_xing_v2">目的地：</span>
					</div>
					<div class="fabu_inputBox_v2">
						<select name="end_pid" class="endselectAreas" data-target="#mudidi">
							<option value="">请选择</option>
						</select>
						<select name="end_cid" class="endselectAreas">
							<option value="">请选择</option>
						</select>
						<select name="end_did" class="endselectAreas">
							<option value="">请选择</option>
						</select>
						<div class="fabu_tip_v2" id="mudidi"></div>
					</div>
				</div>
				<div class="fabu_line_v2">
					<div class="fabu_spanBox_v2">
						<span class="">轻货：</span>
					</div>
					<div class="fabu_inputBox_v2">
						<input type="text" name="light_goods">
						<div class="fabu_danwei_v2">元/方</div>
						<div class="fabu_tip_v2" id=""></div>
					</div>
				</div>
				<div class="fabu_line_v2">
					<div class="fabu_spanBox_v2">
						<span class="">重货：</span>
					</div>
					<div class="fabu_inputBox_v2">
						<input type="text" name="heavy_goods">
						<div class="fabu_danwei_v2">元/吨</div>
						<div class="fabu_tip_v2" id=""></div>
					</div>
				</div>
				<div class="fabu_line_v2">
					<div class="fabu_spanBox_v2">
						<span class="">备注：</span>
					</div>
					<div class="fabu_inputBox_v2">
						<textarea name="demo" data-rule="length[~500];"></textarea>
					</div>
				</div>
				<div class="fabu_line_v2">
					<div class="fabu_spanBox_v2">
						<span class="input_xing_v2">验证码：</span>
					</div>
					<div class="fabu_inputBox_v2">
						<input type="text" name="img_yz" id="img_yz"  data-target="#yanzheng">
						<div class="fabu_yanzheng_v2">
							<img src="/member/code/index" height="40" width="135" id='codeimg' onclick="javascript:this.src='/member/code/index?tm='+Math.random();" >
						</div>
						<div class="fabu_tip_v2" id="yanzheng"></div>
					</div>

				</div>
				<div class="fabu_line_v2">
					<div class="fabu_spanBox_v2"></div>
					<div class="fabu_inputBox_v2">
						<input type="submit" class="fabu_submit_v2" value="发布">
					</div>
				</div>
			</div>
			</form>
		</div>
	</div>
</div>

{{ partial('layouts/footer') }}
<script type="text/javascript">
function codefun(){
    var tm=Math.random();
    $("#codeimg").attr("src","/member/code/index?tm="+tm);
}
$(".selectAreas").ld({ajaxOptions : {"url" : "/ajax/getareasfull"},
    defaultParentId : 0,
    style : {"width" : 247}
});

//  目的地
$(".endselectAreas").ld({ajaxOptions : {"url" : "/ajax/getareasfull"},
    defaultParentId : 0,
    style : {"width" : 247}
});
$(function (){

$("#createCar").validator({
     rules: {
          whd: [/^[a-zA-Z0-9]+$/, '只能输入字母或数字'], 
          par: [/^-?\d+\.?\d{0,2}$/, '请输入正确单位,可保留两位小数'],
          parq: [/^-?\d+\.?\d{0,2}$/, '请输入正确轻货价格,可保留两位小数'],
          parz: [/^-?\d+\.?\d{0,2}$/, '请输入正确重货价格,可保留两位小数'],
          cheNum : [ /^[\u4E00-\u9FA5][\da-zA-Z]{6}$/ , '请输入正确的车牌号,例:京BXXXXX'],
          data_time:[/^\d{4}\/\d{2,2}\/\d{2,2}$/, "请输入正确的日期,例:yyyy/mm/dd"]
     },
     fields:  {
         contact_man:"姓名:required;chinese;length[2~5]",
         contact_phone:"手机号:required;mobile",
         car_no: "车牌号:required;cheNum",
         truck_type_id : "箱型:required;checked;",
         body_type  : "车体:required;checked;",
         'length' : {
         	rule : "长度:required;par",
         	msg : {
         		par : '请输入车辆长度,可保留两位小数'
         	}
         },
         'carry_weight' : {
         	rule : "载重:required;par",
         	msg : {
         		par : '请输入车辆载重,可保留两位小数'
         	}
         },
         'use_time' : {
         	rule : "车龄:required;par",
         	msg : {
         		par : '请输入车辆车龄,可保留两位小数'
         	}
         },
         depart_time : "发车时间:required;data_time",
         transport_type : "运行方式:required;checked;",
         is_longtime : "是否长期:required;checked;",
         start_pid  :"出发地:required;",
         end_pid:'目的地:required;',
         light_goods:'轻货:parq;',
         heavy_goods:'重货:parz;',
         img_yz:"验证码:required;remote[/wuliu/car/checkimgcode ];"
     }
});
});

$(function(){
$('#depart_time').datepicker({
    timeFormat: "HH:mm:ss",
    onSelect:function(date){  
		this.focus();  
		this.blur();  
	},
    minDate: new Date({{year}}, {{month}}, {{day}})
});
});
</script>
</body>
</html>