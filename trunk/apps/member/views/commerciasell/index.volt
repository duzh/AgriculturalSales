<!--头部-->
{{ partial('layouts/member_header') }}
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/ld-select.js"></script>
<script type="text/javascript" src="{{ constant('STATIC_URL') }}DatePicker/DatePicker/WdatePicker.js"></script>
<div class="wrapper">
	<div class="w1190 mtauto f-oh">
		<div class="bread-crumbs w1185 mtauto">
            <span>{{ partial('layouts/ur_here') }}商户的供应</span>
        </div>
		<!-- 左侧 -->
		{{ partial('layouts/navs_left') }}
		<!-- 右侧 -->
			<div class="center-right f-fr">

				<div class="sell-purchase f-oh pb30">
					<div class="title f-oh">
						<span>商户的供应</span>
					</div>
					<form action="/member/commerciasell/index/" method="get">
					<div class="message clearfix">
						<div class="box clearfix">
							<font>商 家 ID：</font>
							<input class="l-input" type="text" name="userid" value="<?php if(isset($_GET["userid"])){ echo $_GET["userid"]; }?>">
						</div>
						<div class="box clearfix">
							<font>手机号：</font>
							<input class="l-input" type="text" name="mobile" value="<?php if(isset($_GET["mobile"])){ echo $_GET["mobile"]; }?>">
						</div>
						<div class="box clearfix">
							<font>供应编号：</font>
							<input class="l-input" type="text" name="suppliers_sn" value="<?php if(isset($_GET["suppliers_sn"])){ echo $_GET["suppliers_sn"]; }?>">
						</div>
					</div>
					<div class="message clearfix">
						<div class="box clearfix">
							<font>供应状态：</font>
							<select class="l-select">
								{% for key,val in state %}
									<option value="{{key}}" {% if sellstate == key %} selected="selected" {% endif %} >{{val}}</option>
								{% endfor %}
							</select>
						</div>
						<div class="box clearfix">
							<font>下单时间：</font>
							<input class="s-input" id="d4311" type="text" name="stime" onfocus="WdatePicker({maxDate:'#F{$dp.$D(\'d4312\')||\'2020-10-01\'}'})" value="{% if stime %}{{stime}}{% endif %}">
							<i>-</i>
							<input class="s-input" id="d4312" type="text" name="etime" onfocus="WdatePicker({minDate:'#F{$dp.$D(\'d4311\')}',maxDate:'2020-10-01'})" value="{% if etime %}{{etime}}{% endif %}">
						</div>
						<div class="box clearfix">
							<font>供应价格：</font>
							<input class="s-input" type="text" name="sell_price_start" value="<?php if(isset($_GET["sell_price_start"])){ echo $_GET["sell_price_start"]; }?>">
							<i>-</i>
							<input class="s-input" type="text" name="sell_price_end" value="<?php if(isset($_GET["sell_price_end"])){ echo $_GET["sell_price_end"]; }?>">
						</div>
					</div>
					<div class="message clearfix">
						<div class="box clearfix">
							<font>供应区域：</font>
							<select class="s-select mr2" name="province">
								<option value="0">省</option>
							</select>
							<select class="s-select mr2" name="city" >
                                <option value="0">市</option>
							</select>
							<select class="s-select" name="district">
                                <option value="0" >县/区</option>
							</select>
						</div>
						<div class="box clearfix">
							<font>供应量：</font>
							<input class="s-input" type="text"  name="quantity_start" onkeyup="value=value.replace(/[^\d]/g,'') " onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))"  value="<?php if(isset($_GET["quantity_start"])){ echo $_GET["quantity_start"]; }?>">
							<i>-</i>
							<input class="s-input" type="text" name="quantity_end"  onkeyup="value=value.replace(/[^\d]/g,'') " onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))"  value="<?php if(isset($_GET["quantity_end"])){ echo $_GET["quantity_end"]; }?>">
						</div>
					</div>
					<input class="search-btn" type="submit" value="搜  索">
					<table cellpadding="0" cellspacing="0" width="100%" class="list">
						<tr height="41">
							<th width="221">
								<span class="m-left">商家信息</span>
							</th>
							<th width="172">商品信息</th>
							<th width="147">供货价格</th>
							<th width="130">供应数量</th>
							<th width="110">
								<select onchange="setcheck()"  id="selectcate" >
									 {% for key,val in state %}
                                    <option value="{{key}}" {% if sellstate == key %} selected="selected" {% endif %}>{{val}}</option>
                                    {% endfor %}
								</select>
							</th>
							<th width="148">
								<font class="m-right">操作</font>
							</th>
						</tr>
						 {% for key, sell in data %}
						<tr height="28">
							<td colspan="6">
								<div class="m-box clearfix">
									<span class="bh f-fl">
										供应编号：{{ sell['sell_sn'] }}&nbsp;&nbsp;&nbsp;&nbsp;供应时间：{{ time_type[sell['stime']] }}~{{ time_type[sell['etime']] }}
									</span>
									<em class="sj f-fr">发布时间：{{ date('Y-m-d H:i:s', sell['createtime']) }}</em>
								</div>
							</td>
						</tr>
						<?php $userinfo=\Mdg\Models\UserInfo::getuserinfo($sell['user_id']); ?>
						<tr height="99">
							<td>
								<span class="m-left">
									用户ID：{{ sell["user_id"] }}<br />
									手机号：{{ sell['username'] }}<br />
									商家身份：{{ sell['type'] }}
								</span>
							</td>
							<td>
								<span class="m-middle">供应商品：{{ sell['title'] }}</span>
								<span class="m-middle clearfix">
									<font class="dq">供应地区：</font>
									<font class="dz">{{ sell['address'] }}</font>
								</span>
							</td>
							<td align="center">
								<i>{{ sell['min_price'] }}~{{ sell['max_price'] }}</i>  元/{{ goods_unit[sell['goods_unit']] }}
							</td>
							<td align="center">
								{% if sell['quantity'] > 0  %}
									{{ sell['quantity'] }}
								 	{{ goods_unit[sell['goods_unit']] }}
								{% else %}
									不限 
								{% endif %}
							</td>
							<td align="center">
								{% if sell['is_del']==0  %}
	                                {% if (sell['state']== 1 ) %}
	                                    已发布
	                                {% endif %}
	                                {% if (sell['state']== 2 )%}
	                                    审核失败 
	                                {% endif %}
	                                {% if (sell['state']== 0 ) %}
	                                    待审核
	                                {% endif %}
                                {% else %}
                                    已删除
                                {% endif %}
							</td>
							<td>
							<?php if($sell['state'] == 1  && $sell['is_del']== 0  ) { ?>
								<font class="m-right">
									<a href="/sell/info/{{sell['id']}}">查看详情</a>
								</font>
							<?php } ?>
							</td>
						</tr>
						{% endfor %}
					</table>
					<!-- 分页 -->
					{% if total_count>1 %}
					<div class="esc-page mt30 mb30 f-tac f-fr mr30">
						{{ pages }}
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
			<!--右侧end-->
	</div>
</div>
<!--底部-->
{{ partial('layouts/footer') }}}
<script>
function go(){
var p=$("#p").val();
 var count = {{total_count}};
 if(p>count){
    $("#p").val(count);
 }
}	
function btnHover(obj){
    obj.hover(function(){
        $(this).addClass('hover');
    }, function(){
        $(this).removeClass('hover');
    });
};

function setcheck(){
    var state=$("#selectcate").val();
    location.href='/member/commerciasell/index?state='+state;
}
jQuery(document).ready(function(){
    var searchBtn = $('.top_search .btn');
    btnHover(searchBtn);
});
$(function(){
	$(".s-select").ld({ajaxOptions:{"url":"/ajax/getareasfull"},
    defaultParentId : 0,
    {% if (curAreas) %}
    texts : [{{ curAreas }}],
    {% endif %}
    style : {"width" : 140}
});
});

</script>