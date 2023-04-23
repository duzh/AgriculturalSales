<!--头部-->
{{ partial('layouts/member_header') }}
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/ld-select.js"></script>

<div class="wrapper">
	<div class="w1190 mtauto f-oh">
		<div class="bread-crumbs w1185 mtauto">
            <span>{{ partial('layouts/ur_here') }}商户的采购</span>
        </div>
		<!-- 左侧 -->
		{{ partial('layouts/navs_left') }}
		<!-- 右侧 -->
			<div class="center-right f-fr">

				<div class="sell-purchase f-oh pb30">
				<form action="/member/commerciapurchase/index/" method="get">
					<div class="title f-oh">
						<span>商户的采购</span>
					</div>
					<div class="message clearfix">
						<div class="box clearfix">
							<font>商 家 ID：</font>
							<input class="l-input" type="text" name="userid" value="<?php if(isset($_GET['userid'])){echo $_GET['userid'];}?>">
						</div>
						<div class="box clearfix">
							<font>手机号：</font>
							<input class="l-input" type="text" name="mobile" value="<?php if(isset($_GET['mobile'])){echo $_GET['mobile'];}?>">
						</div>
						<div class="box clearfix">
							<font>采购编号：</font>
							<input class="l-input" type="text" name="purchase_sn" value="<?php if(isset($_GET['purchase_sn'])){echo $_GET['purchase_sn'];}?>">
						</div>
					</div>
					<div class="message clearfix">
						<div class="box clearfix">
							<font>采购状态：</font>
							<select name="state" class="l-select">
                            <option value="all">请选择</option>
                            {% for key, item in _state %}
                            <option value="{{ key}}" <?php if(isset($_GET['state']) && $_GET['state'] == "$key" ){ echo 'selected';}?>>{{ item}}
                            </option>
                            {% endfor %}
						</div>
						<div class="box clearfix">
							<font>发布时间：</font>
							<input class="s-input" id="d4311" type="text" name="fabu_stime" onfocus="WdatePicker({maxDate:'#F{$dp.$D(\'d4312\')||\'2020-10-01\'}'})" value="<?php if(isset($_GET["fabu_stime"])){ echo $_GET["fabu_stime"]; }?>">
							<i>-</i>
							<input class="s-input" id="d4312" type="text" name="fabu_etime" onfocus="WdatePicker({minDate:'#F{$dp.$D(\'d4311\')}',maxDate:'2020-10-01'})" value="<?php if(isset($_GET["fabu_etime"])){ echo $_GET["fabu_etime"]; }?>">
						</div>
						<div class="box clearfix">
							<font>截止日期：</font>
							<input class="s-input" id="d4313" type="text" name="baojia_stime" onfocus="WdatePicker({maxDate:'#F{$dp.$D(\'d4312\')||\'2020-10-01\'}'})" value="<?php if(isset($_GET["baojia_stime"])){ echo $_GET["baojia_stime"]; }?>">
							<i>-</i>
							<input class="s-input" id="d4314" type="text" name="baojia_etime" onfocus="WdatePicker({minDate:'#F{$dp.$D(\'d4311\')}',maxDate:'2020-10-01'})" value="<?php if(isset($_GET["baojia_etime"])){ echo $_GET["baojia_etime"]; }?>">
						</div>
					</div>
					<div class="message clearfix">
						<div class="box clearfix">
							<font>采购区域：</font>
							<select class="s-select mr2" name="province">
                                <option value="0">请选择</option>
							</select>
							<select class="s-select mr2" name="city" >
                                <option value="0">请选择</option>
							</select>
							<select class="s-select" name="district">
                                <option value="0" >请选择</option>
							</select>
						</div>
						<div class="box clearfix">
							<font>采购量：</font>
							<input class="s-input" type="text" name="min_quantity" value="<?php if(isset($_GET['min_quantity'])){echo $_GET['min_quantity'];}?>">
							<i>-</i>
							<input class="s-input" type="text" name="max_quantity" value="<?php if(isset($_GET['max_quantity'])){echo $_GET['max_quantity'];}?>">
						</div>
					</div>
					<input class="search-btn" type="submit" value="搜  索">
				
					<table cellpadding="0" cellspacing="0" width="100%" class="list">
						<tr height="41">
							<th width="221">
								<span class="m-left">商家信息</span>
							</th>
							<th width="172">商品信息</th>
							<th width="147">采购数量</th>
							<th width="130">报价截止时间</th>
							<th width="110">
								<select name="state" id="" onchange='onSelect(this.value)'>
                                    <option value="all">全部状态</option>
                                    {% for key, item in _state %}
                                    <option value="{{ key}}" <?php if(isset($_GET['state']) && $_GET['state'] == "$key" ){ echo 'selected';}?>>{{ item}}</option>
                                    {% endfor %}
                                </select>
							</th>
							<th width="148">
								<font class="m-right">操作</font>
							</th>
						</tr>
						{% for key, val in data %}
						<tr height="28">
							<td colspan="6">
								<div class="m-box clearfix">
									<span class="bh f-fl">采购编号：{{ val['pur_sn'] }}</span>
									<em class="sj f-fr">发布时间：{{ date('Y-m-d H:i:s', val['createtime']) }}</em>
								</div>
							</td>
						</tr>
						<tr height="99">
						<?php $userinfo=\Mdg\Models\UserInfo::getuserinfo($val['uid']); ?>
							<td>
								<span class="m-left">
									用户ID：{{ val["user_id"] }}<br />
									手机号：{{ val['user_name'] }}<br />
									商家身份：{{ val['type'] }}
								</span>
							</td>
							<td>
								<span class="m-middle">采购商品：{{ val['title'] }}</span>
								<span class="m-middle clearfix">
									<font class="dq">采购地区：</font>
									<font class="dz">{{ val['address'] }}
                                        {% if val['state'] == 2 %}
                                            <p>审核未通过原因:<?php echo Mdg\Models\PurchaseCheck::getPurchaseFailReason($val['id']);?></p>
                                        {% endif %}</font>
								</span>
							</td>
							<td align="center">
								{% if val['quantity'] > 0 %}
                                {{ val['quantity'] }}
                                {% else %}
                                    不限/
                                {% endif %}
                                {{ goods_unit[val['goods_unit']] }}</td>
							<td align="center">
								<span class="rq">
									{{ date('Y-m-d', val['endtime']) }}<br /> 
									{{ date('H:i:s', val['endtime']) }}
								</span>
							</td>
							<td align="center">
								{% if val['is_del'] == 1 %}
                                    已删除
                                {% else %}
                                {{ _state[val['state']]}}
                                {% endif %}
                            </td>
							<td>
								<font class="m-right">
								{% if val['countquo'] %}
                                {{ val['countquo'] }}个供应商报价
                                {% else %}
                                暂无供应商报价
                                {% endif %}
                                </font>
							</td>
						</tr>
						{% endfor %}
					</table>
					<!-- 分页 -->
					{% if total_count>1 %}
					<div class="esc-page mt30 mb30 f-tac f-fr mr30">
						
						{{pages}}
				        <span>
                            <label>去</label>
                            <input type="text" name="p" id="p" onkeyup="value=value.replace(/[^\d]/g,'') " value="1" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" />
                            <label>页</label>
                        </span>    
                        <input class="btn" type="submit" value="确定" onclick="go()"/>
                       
                        {% endif %}
					</div>
					</form>
				</div>
			<!-- 右侧 end -->
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
function onSelect(Svalue ) {
    location.href='/member/commerciapurchase/index?state='+Svalue;
}

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
