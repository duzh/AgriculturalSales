{{ partial('layouts/page_header') }}
	<div class="wuliu_body_v2">
		<div class="wuliu_v2 w1190 mtauto">
            <div class="mianbao_v2">
				<a href="/">首页</a>
				<span>&gt;</span>
				<a href="/wuliu/index/">物流信息</a>
				<span>&gt;</span>
				<a href="/wuliu/car/index/">发布货源</a>
			</div>
			<div class="fabu_con_v2">
				<div class="fabu_title_v2"><strong>免费发布货源信息</strong></div>
				<form action="/wuliu/cargo/create" id="formid" method="post">
				<div class="fabu_two_title_v2">货主信息</div>
				<div class="fabu_inputCon_v2">
					<div class="fabu_line_v2">
						<div class="fabu_spanBox_v2">
							<span class="input_xing_v2">姓名：</span>
						</div>
						<div class="fabu_inputBox_v2">
							<input type="text" name="contact_man" id="contact_man" data-target="#wlName">
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
				<div class="fabu_two_title_v2">货物信息</div>
				<div class="fabu_inputCon_v2">
					<div class="fabu_line_v2">
						<div class="fabu_spanBox_v2">
							<span class="input_xing_v2">货物名：</span>
						</div>
						<div class="fabu_inputBox_v2">
							<input type="text" name="goods_name" data-target="#chepai">
							<div class="fabu_tip_v2" id="chepai"></div>
						</div>
					</div>
					<div class="fabu_line_v2">
						<div class="fabu_spanBox_v2">
							<span class="input_xing_v2">货物种类：</span>
						</div>
						<div class="fabu_inputBox_v2">
							<select name="goods_type" id="goods_type" data-target="#cheType">
								<option value="">请选择</option>
							    {% for key , item in _GOODS_TYPE %}
								<option value="{{ key }}">{{ item }}</option>
								{% endfor %}
							</select>
							<div class="fabu_tip_v2" id="cheType"></div>
						</div>
					</div>
					<div class="fabu_line_v2">
						<div class="fabu_spanBox_v2">
							<span class="input_xing_v2">重量：</span>
						</div>
						<div class="fabu_inputBox_v2">
							<input type="text" name="goods_weight" id="goods_weight" data-target="#zhongliang">
							<div class="fabu_danwei_v2">吨</div>
							<div class="fabu_tip_v2" id="zhongliang"></div>
						</div>
					</div>
					<div class="fabu_line_v2">
						<div class="fabu_spanBox_v2">
							<span class="input_xing_v2">体积：</span>
						</div>
						<div class="fabu_inputBox_v2">
							<input type="text" name="goods_size" id="goods_size" data-target="#mi">
							<div class="fabu_danwei_v2">方</div>
							<div class="fabu_tip_v2" id="mi"></div>
						</div>
					</div>
					<div class="fabu_line_v2">
						<div class="fabu_spanBox_v2">
							<span class="input_xing_v2">期望运费：</span>
						</div>
						<div class="fabu_inputBox_v2">
							<input type="text" name="except_price" id="except_price" data-target="#zaizhong">
							<div class="fabu_danwei_v2">元</div>
							<div class="fabu_tip_v2" id="zaizhong"></div>
						</div>
					</div>
				</div>
				<div class="fabu_two_title_v2">期望流向和车辆要求</div>
				<div class="fabu_inputCon_v2">
					<div class="fabu_line_v2">
						<div class="fabu_spanBox_v2">
							<span class="input_xing_v2">厢形：</span>
						</div>
						<div class="fabu_inputBox_v2">
							<select name="box_type" data-target="#xiangxing" id="box_type">
								<option value="">请选择</option>
							    {% for key , item in _BOX_TYPE %}
								<option value="{{ key }}">{{ item }}</option>
								{% endfor %}
							</select>
							<div class="fabu_tip_v2" id="xiangxing"></div>
						</div>
					</div>
					<div class="fabu_line_v2">
						<div class="fabu_spanBox_v2">
							<span class="input_xing_v2">车体：</span>
						</div>
						<div class="fabu_inputBox_v2">
							<select name="body_type" id="body_type" data-target="#cheti">
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
							<span class="input_xing_v2">车体长度：</span>
						</div>
						<div class="fabu_inputBox_v2">
							<input type="text" name="except_length" id="except_length" data-target="#chetichangdu">
							<div class="fabu_tip_v2" id="chetichangdu"></div>
						</div>
					</div>
					<div class="fabu_line_v2">
						<div class="fabu_spanBox_v2">
							<span class="input_xing_v2">有效时间：</span>
						</div>
						<div class="fabu_inputBox_v2">
							<input type="text" name="expire_time" id="expire_time" data-target="#youxiaoshijian">
							<div class="fabu_tip_v2" id="youxiaoshijian"></div>
						</div>
					</div>
					<div class="fabu_line_v2">
						<div class="fabu_spanBox_v2">
							<span class="input_xing_v2">是否长期：</span>
						</div>
						<div class="fabu_inputBox_v2">
							<select name="is_longtime" id="is_longtime" data-target="#changqi">
								<option value="">请选择</option>
								<option value="1">是</option>
								<option value="0">否</option>
							</select>
							<div class="fabu_tip_v2" id="changqi"></div>
						</div>
					</div>
					<div class="fabu_line_v2">
						<div class="fabu_spanBox_v2">
							<span class="input_xing_v2">结算方式：</span>
						</div>
						<div class="fabu_inputBox_v2">
							<input type="text" name="settle_type" id="settle_type" data-target="#jiesuanfangshi">
							<div class="fabu_tip_v2" id="jiesuanfangshi"></div>
						</div>
					</div>
					<div class="fabu_line_v2">
						<div class="fabu_spanBox_v2">
							<span class="input_xing_v2">出发地：</span>
						</div>
						<div class="fabu_inputBox_v2">
							<select name="start_pid" class="wl-select-num1 selectAreas" data-target="#chufadi">
								<option value="">请选择</option>
							</select>
							<select name="start_cid" class="wl-select-num1 selectAreas">
								<option value="">请选择</option>
							</select>
							<select name="start_did" class="wl-select-num1 selectAreas">
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
							<select name="end_pid" class="wl-select-num1 endselectAreas" data-target="#mudidi">
								<option value="">请选择</option>
							</select>
							<select name="end_cid" class="wl-select-num1 endselectAreas">
								<option value="">请选择</option>
							</select>
							<select name="end_did" class="wl-select-num1 endselectAreas">
								<option value="">请选择</option>
							</select>
							<div class="fabu_tip_v2" id="mudidi"></div>
						</div>
					</div>
					<div class="fabu_line_v2">
						<div class="fabu_spanBox_v2">
							<span class="">备注：</span>
						</div>
						<div class="fabu_inputBox_v2">
							<textarea name="demo" data-rule="length[~500];" ></textarea>
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
<!-- 底部 -->
{{ partial('layouts/footer') }}

<script>
$(function(){
	$('#expire_time').datepicker({
	    timeFormat: "HH:mm:ss",
	    onSelect:function(date){  
			this.focus();  
			this.blur();  
		},
	    minDate: new Date({{year}}, {{month}}, {{day}})
	});
});
$(".selectAreas").ld({ajaxOptions : {"url" : "/ajax/getareasfull"},
    defaultParentId : 0,
   
    style : {"width" : 140}
});
//  目的地
$(".endselectAreas").ld({ajaxOptions : {"url" : "/ajax/getareasfull"},
    defaultParentId : 0,
   
    style : {"width" : 140}
});
$("#formid").validator({
     rules: {
          whd: [/^[a-zA-Z0-9]+$/, '只能输入字母或数字'], 
          par: [/^-?\d+\.?\d{0,2}$/, '请输入正确单位,可保留两位小数'],
          data_time:[/^\d{4}\/\d{2,2}\/\d{2,2}$/, "请输入正确的日期,例:yyyy/mm/dd"]
     },
     fields:  {
         contact_man:"姓名:required;chinese;length[2~5]",
         contact_phone:"电话:required;mobile",
         goods_name: "货物名称:required;",
         goods_type : "货物种类:required;",
         'goods_weight' : {
    		rule : '重量:required; par',
    		msg : {
		    	par : '请输入重量,可保留两位小数'
		    	}
    	 },
    	 'goods_size' : {
    		rule : '体积:required; par',
    		msg : {
		    	par : '请输入体积,可保留两位小数'
		    	}
    	 },
    	 'except_price' : {
    		rule : '期望报价:required; par',
    		msg : {
		    	par : '请输入期望报价,可保留两位小数'
		    	}
    	 },
    	 'except_length' : {
    		rule : '车体长度:required; par',
    		msg : {
		    	par : '请输入车体长度,可保留两位小数'
		    	}
    	 },

         box_type : "厢型:required;",
         body_type : "车体:required;",
         expire_time : "有效时间:required;data_time;",
         is_longtime  :"是否长期:required;",
         start_pid:'出发地:required;',
         end_pid:'目的地:required',
         settle_type:'结算方式:required',
          img_yz:"required;remote[/manage/car/checkimgcode ];"
     }
});


</script>