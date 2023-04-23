<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />

<div class="main">
	<div class="main_right">
		<div class="bt2">新增价格行情</div>
		{{ form("quotation/create", "method":"post","id":"addpur") }}
		<div class="cx">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" style=" border:none;">

				<tr>
					<td class="cx_title">姓名：</td>
					<td class="cx_content">
						<input type="text" class="txt"   id="contact_man" name='contact_man' value="{{data.contact_name}}" />
					</td>
				</tr>

				<tr>
					<td class="cx_title">电话：</td>
					<td class="cx_content">
						<input type="text" class="txt"   id="contact_phone" name='contact_phone' value="{{data.contact_phone}}" />
					</td>
				</tr>

				<tr>
					<td class="cx_title">产品分类:</td>
					<td class="cx_content">
                        <select name="category" class="selectCate">
                            <option value="">选择分类</option>
                        </select>
					</td>
				</tr>

				<tr>

					<td class="cx_title">产品名称：</td>
					<td class="cx_content">
						<select name="goods_name" id="goods_name" class="selectCate">
							<option value="">请选择</option>
						</select>
					</td>
				</tr>
				<tr>
					<td class="cx_title">地区：</td>
					<td class="cx_content">
						<select name="start_pid" class='selectAreas' id="" data-target="#chufadi">
							<option value="">请选择</option>
						</select>
						<select name="start_cid" class='selectAreas' id="">
							<option value="">请选择</option>
						</select>

						<select name="start_did" class='selectAreas' id="">
							<option value="">请选择</option>
						</select>
						<span id="chufadi"></span>
					</td>
				</tr>
				<tr>

					<td class="cx_title">市场：</td>
					<td class="cx_content">
						<input type="text" name="market" value="{{data.market_name}}"></td>
				</tr>
				<tr>

					<td class="cx_title">价格：</td>
					<td class="cx_content">
						<input type="text" name="price" data-target="#price" value="{{data.price}}">
						<select name="unit" id="">
							<option value="斤">斤</option>
						</select>
						<span id="price"></span>
					</td>
				</tr>
				<tr>
					<td class="cx_title">行情分析：</td>
					<td class="cx_content" ><textarea name="analyze" >{% if data.analyze %}{{ data.analyze }}{% endif %}</textarea></td>
				</tr>

			</table>
		</div>
		<div align="center" style="margin-top:20px;">
			<input type="hidden" id="market_id" value="{data.id}">
			<input type="submit" value="发布" class="sub"/>
		</div>
	</div>
	<!-- main_right 结束  -->
</div>
</form>
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/ld-select.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/jquery-ui.min.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/timepicker/jquery-ui-timepicker-addon.min.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/timepicker/i18n/jquery-ui-timepicker-zh-CN.js"></script>
<link rel="stylesheet" type="text/css" href="{{ constant('JS_URL') }}jquery/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="{{ constant('JS_URL') }}jquery/timepicker/jquery-ui-timepicker-addon.min.css" />
<link rel="stylesheet" type="text/css" href="http://static.ync365.com/mdg/css/uibase.css" />
<link rel="stylesheet" type="text/css" href="{{ constant('JS_URL') }}validator/jquery.validator.css" />
<script type="text/javascript" src="{{ constant('JS_URL') }}validator/jquery.validator.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}validator/local/zh_CN.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}lhgdialog/lhgdialog.min.js?skin=igreen"></script>

<script>
$(".selectCate").ld({ajaxOptions : {"url" : "/ajax/getcate"},
    defaultParentId : 0,
    {% if curCate %}
    texts:[{{ curCate }}],
    {% endif %}
    style : {"width" : 140}
});
$(".selectAreas").ld({ajaxOptions : {"url" : "/ajax/getareasfull"},
    defaultParentId : 0,
    {% if curAreas %}
    texts:[{{ curAreas }}],
    {% endif %}    
    style : {"width" : 140}
});

$("#addpur").validator({
     rules: {
          whd: [/^[a-zA-Z0-9]+$/, '只能输入字母或数字'], 
          par: [/^-?\d+\.?\d{0,2}$/, '请输入正确重量可保留两位小数'],
          data_time:[/^\d{4}\/\d{2,2}\/\d{2,2}\s\d{2,2}:\d{2,2}$/, "请输入正确的日期,例:yyyy-mm-dd"],
     },
     fields:  {
          contact_man:"姓名:chinese",
          contact_phone:"电话:mobile",
         goods_name: "产品名称:required;",
         category : "产品分类:required;",
         start_pid:'地区:required;',
         price	  :'价格:required;',
         market		:'市场:required',
     },
});

</script>
{{ partial('layouts/bottom') }}
</body>
</html>