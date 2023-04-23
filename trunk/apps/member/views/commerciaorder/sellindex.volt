<!--头部-->
{{ partial('layouts/member_header') }}
<script type="text/javascript" src="{{ constant('STATIC_URL') }}DatePicker/DatePicker/WdatePicker.js"></script>
<div class="wrapper">
	<div class="w1190 mtauto f-oh">
	<div class="bread-crumbs w1185 mtauto">
            <span>{{ partial('layouts/ur_here') }}我的商户订单</span>
        </div>
		<!-- 左侧 -->
		{{ partial('layouts/navs_left') }}
		<!-- 右侧 -->
			<div class="center-right f-fr">

				<div class="my-sellOrder f-oh pb30">
					<div class="title f-oh">
						<span>我的商户订单</span>
					</div>
					<ul class="sell-btn clearfix">
						<li class=""><a href="/member/commerciaorder/index">买入</a></li>
						<li class="border active"><a href="/member/commerciaorder/sellindex">卖出</a></li>
					</ul>
					
					<!-- 卖出 -->
					<div class="tabBox" style="display:block;">
						<form action="/member/commerciaorder/sellindex" method="get">
						<div class="message clearfix">
							<div class="box clearfix">
								<font>卖 家 ID：</font>
								<input class="l-input" type="text" name="puserid" value="<?php if(isset($_GET['puserid'])){?>{{_GET['puserid'] ? _GET['puserid'] : ''}}<?php }?>"/>
							</div>
							<div class="box clearfix">
								<font>手机号：</font>
								<input class="l-input" type="text" name="purphone" value="<?php if(isset($_GET['purphone'])){?>{{_GET['purphone'] ? _GET['purphone'] : ''}}<?php }?>"/>
							</div>
							<div class="box clearfix">
								<font>下单时间：</font>
								<input class="s-input" id="d4311" type="text" value='{{ stime }}' name="stime"  onFocus="WdatePicker({maxDate:'#F{$dp.$D(\'d4312\')||\'2020-10-01\'}'})"/>
								<i>-</i>
								<input class="s-input" id="d4312"  type="text" value='{{ etime }}' name="etime" onFocus="WdatePicker({minDate:'#F{$dp.$D(\'d4311\')}',maxDate:'2020-10-01'})"/>
							</div>
							<!-- <div class="box clearfix">
								<font>卖家身份：</font>
								<select class="l-select" name="credit_type" id="">
									<option value=""<?php if(isset($_GET['credit_type'])){?> {% if _GET['credit_type'] and _GET['credit_type']==''%}selected{% endif%}<?php }?>>请选择</option>
									<option value="1" <?php if(isset($_GET['credit_type'])){?>{% if _GET['credit_type'] and _GET['credit_type']==1 %}selected{% endif%}<?php }?>>普通用户</option>
									<option value="8" <?php if(isset($_GET['credit_type'])){?>{% if _GET['credit_type'] and _GET['credit_type']==8%}selected{% endif%}<?php }?>>可信农场</option>
									<option value="4" <?php if(isset($_GET['credit_type'])){?>{% if _GET['credit_type'] and _GET['credit_type']==4%}selected{% endif%}<?php }?>>村级服务站</option>
									<option value="2" <?php if(isset($_GET['credit_type'])){?>{% if _GET['credit_type'] and _GET['credit_type']==2%}selected{% endif%}<?php }?>>县域服务站</option>
									<option value="16" <?php if(isset($_GET['credit_type'])){?>{% if _GET['credit_type'] and _GET['credit_type']==16%}selected{% endif%}<?php }?>>采购商</option>
								</select>
							</div> -->
						</div>
						<div class="message clearfix">
							<div class="box clearfix">
								<font>订单编号：</font>
								<input class="l-input" type="text" name="order_sn" value="<?php if(isset($_GET['purphone'])){?>{{_GET['order_sn'] ? _GET['order_sn'] : ''}}<?php }?>"/>
							</div>
							<div class="box clearfix">
								<font>订单状态：</font>
								<select name="state" class="l-select">
                                    <option value="0">全部状态</option>
                                    {% for key, val in orders_state %}

                                    <option value="{{ key }}" {% if state == key %} selected="selected" {% endif %} >{{ val }}</option>
                                    {% endfor %}
								</select>
							</div>
							<!-- <div class="box clearfix">
								<font>下单时间：</font>
								<input class="s-input" id="d4311" type="text" value='{{ stime }}' name="stime"  onFocus="WdatePicker({maxDate:'#F{$dp.$D(\'d4312\')||\'2020-10-01\'}'})"/>
								<i>-</i>
								<input class="s-input" id="d4312"  type="text" value='{{ etime }}' name="etime" onFocus="WdatePicker({minDate:'#F{$dp.$D(\'d4311\')}',maxDate:'2020-10-01'})"/>
							</div> -->
						</div>
						<input class="search-btn" type="submit" value="搜  索">
						<table cellpadding="0" cellspacing="0" width="100%" class="list">
							<tr height="31">
								<th width="425">
									<div class="m-left">采购商品信息</div>
								</th>
								<th width="176">
									<span class="f-db f-tal pl20">卖家信息</span>
								</th>
								<th width="164">订单状态</th>
								<th width="164">
									<span class="m-right">操作</span>
								</th>
							</tr>
							{% if data['items']  %}
							{% for k,v in data['items']%}
							<tr height="28">
								<td colspan="4">
									<div class="m-box clearfix">
										<span class="num f-fl">订单号：{{v['order_sn']}}</span>
										<span class="sj f-fr">发布时间：{{date('Y-m-d H:i:s',v['addtime'])}}</span>
									</div>
								</td>
							</tr>
							<tr height="139">
								<td>
									<div class="m-left">
										<dl class="clearfix">
											<dt class="f-fl">
												<?php $img = Mdg\Models\Sell::getSellThumb($v['sellid']); ?>
									<?php if(isset($img) && $img ) { ?>
									<img src="{{ img }}" height="100" width="90">
									<?php }else{ ?>
									<img src="http://static.ync365.com/mdg/images/detial_b_img.jpg" height="120" width="120">
									<?php } ?>
											</dt>
											<dd class="f-fl">
												商品名称：{{ v['goods_name'] }}<br />
												单品价格：<i>{% if v['state'] == 2 %}<?php $orderprice=Mdg\Models\Sell::getprice($v['sellid']); if($orderprice){ echo $orderprice;}else{ echo $v['price'];}?> {% else %}{{ v['price'] }}{% endif %}</i>元／{{ goods_unit[v['goods_unit']] }}<br />
												购买数量：{{ v['quantity'] }}{{ goods_unit[v['goods_unit']] }}
											</dd>
										</dl>
									</div>
								</td>
								<td>
									<span class="m-middle">
										用户ID：{% if v['suserid'] %}
											{{v['suserid']}}
											{% else %}
											暂无
											{% endif %}<br />
										手机号：{% if v['sphone'] %}
											{{v['sphone']}}
											{% else %}
											暂无
											{% endif %}<br />
										商家身份：{{ v['ident']}}
									</span>
								</td>
								<td align="center">{{ orders_state[v['state']] }}</td>
								<td>
									<span class="m-right">
										<a href="/member/ordersbuy/info/{{ v['id'] }}">订单详情</a>
									</span>
								</td>
							</tr>
							{% endfor %}
							{% endif %}
						</table>
						<!-- 分页 -->
						{% if total_count>1 %}
						<div class="esc-page mt30 mb30 f-tac f-fr mr30">
							{{data['pages']}}
								<span>
		                            <label>去</label>
		                            <input type="text" name="p" id="p" onkeyup="value=value.replace(/[^\d]/g,'') " value="1" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" />
		                            <label>页</label>
		                        </span>
		                        <input  type="hidden" name="total" value="{{total_count}}" />	    
		                        <input class="btn" type="submit" value="确定" />		                        
						</div>
						{% endif %}
						</form>
					</div>
				</div>

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
	
</script>