{{ partial('layouts/page_header') }}
<div class="wuliu_body_v2">
	<div class="wuliu_v2 w1190 mtauto">
	 <div class="mianbao_v2">
	    <a href="/">首页</a>
	    <span>&gt;</span>
	    <a href="/wuliu/index/">物流信息</a>
	    <span>&gt;</span>
	    <span>专线信息</span>
	</div>

		<div class="wuliu_left_v2 f-fl">
			<div class="cheyuanTable_v2">
				<table>
					<tr>
						<td colspan="4"><strong class="cheyuan_title_v2"><?php echo Mdg\Models\CargoInfo::GetAreaName($data->start_cname,$data->start_pname)?>到<?php echo Mdg\Models\CargoInfo::GetAreaName($data->end_cname,$data->end_pname)?>的专线信息</strong></td>
					</tr>
					<tr class="tr_bg_v2">
						<td colspan="4"><span class="table_title_v2">专线出发地</span></td>
					</tr>
					<tr>
						<td width="20%"><span class="table_span_right">出发地：</span></td>
						<td width="30%"><span class="table_span_left">{{ data.start_pname}}{{data.start_cname}}{{data.start_dname}}</span></td>
						<td width="20%"><span class="table_span_right">网点名：</span></td>
						<td width="30%"><span class="table_span_left">{{ start['net_name']}}</span></td>
					</tr>
					<tr>
						<td><span class="table_span_right">公司名：</span></td>
						<td><span class="table_span_left">{{start['company_name']}}</span></td>
						<td><span class="table_span_right">联系人：</span></td>
						<td>
							<span class="table_span_left">{{ start['contact_man']}}</span>
						</td>
					</tr>
					<tr>
						<td><span class="table_span_right">固定电话：</span></td>
						<td><span class="table_span_left">{{ start['fix_phone']}}</span></td>
						<td><span class="table_span_right">手机：</span></td>
						<td>
							 {% if start['phone_number'] %}
		                        {% if start['phone_img'] %}

		                                <img src="{{ constant('ITEM_IMG')}}/{{start['phone_img']}}" 
		                                id="phone_img_{{ start['sc_id'] }}" style='display:none' alt="联系人号码">
		                                <span class="table_span_left">
		                                    {{ start['phone_number'] }}
		                                    <a href='javascript:;' onclick="showImg(this,{{start['sc_id']}})">(查看)</a>
		                               </span>         
		                        {% endif %}
		                    {% endif %}
							
						</td>
					</tr>
					<tr>
						<td><span class="table_span_right">QQ：</span></td>
						<td><span class="table_span_left">{{ start['qq']}}</span></td>
						<td><span class="table_span_right">地址：</span></td>
						<td>
							<span class="table_span_left">{{ start['address']}}</span>
						</td>
					</tr>
					<tr class="tr_bg_v2">
						<td colspan="4"><span class="table_title_v2">专线目的地</span></td>
					</tr>
					<tr>
						<td><span class="table_span_right">目的地：</span></td>
						<td><span class="table_span_left">{{ data.end_pname}}{{data.end_cname}}{{data.end_dname}}</span></td>
						<td><span class="table_span_right">网点名：</span></td>
						<td><span class="table_span_left">{{ end['net_name']}}</span></td>
					</tr>
					<tr>
						<td><span class="table_span_right">公司名：</span></td>
						<td><span class="table_span_left">{{end['company_name']}}</span></td>
						<td><span class="table_span_right">联系人：</span></td>
						<td>
							<span class="table_span_left">{{ end['contact_man']}}</span>
						</td>
					</tr>
					<tr>
						<td><span class="table_span_right">固定电话：</span></td>
						<td><span class="table_span_left">{{ end['fix_phone']}}</span></td>
						<td><span class="table_span_right">手机：</span></td>
						<td>
								{% if end['phone_number'] %}
		                                {% if end['phone_img'] %}
		                                <img src="{{ constant('ITEM_IMG')}}/{{end['phone_img']}}" 
		                                id="endphone_img_{{ end['sc_id'] }}" style='display:none;' alt="联系人号码">
		                                <span class="table_span_left">
		                                    {{ end['phone_number'] }}
		                                    <a href='javascript:;' onclick="showendImg(this,{{end['sc_id']}})">(查看)</a>
		                                </span>
		                                 {% else %}
                                            {{ end['phone_number'] }}
		                                 <a href="javascript:getPhoneImg({{end['sc_id']}})">(查看)</a> 
		                                {% endif %}
		                        {% endif %}
						</td>
					</tr>
					<tr>
						<td><span class="table_span_right">QQ：</span></td>
						<td><span class="table_span_left">{{ end['qq']}}</span></td>
						<td><span class="table_span_right">地址：</span></td>
						<td>
							<span class="table_span_left">{{ end['address']}}</span>
						</td>
					</tr>

					<tr class="tr_bg_v2">
						<td colspan="4"><span class="table_title_v2">运行方式</span></td>
					</tr>
					<tr>
						<td><span class="table_span_right">单程/往返：</span></td>
						<td><span class="table_span_left">
							 {% if data.type %}
                            往返
                            {% else %}
                            单程
                            {% endif %}
						   </span>
					   </td>
						<td><span class="table_span_right">重货价：</span></td>
						<td><span class="table_span_left">{% if data.light_price > 0 %}{{ data.light_price}}元/吨{% else %}面议 {% endif %}</span></td>
					</tr>
					<tr>
						<td><span class="table_span_right">轻货价：</span></td>
						<td><span class="table_span_left">{% if data.light_price > 0 %}{{ data.heavy_price}}/吨{% else %}面议 {% endif %}</span></td>
						<td><span class="table_span_right">--</span></td>
						<td><span class="table_span_left">--</span></td>
					</tr>
					<tr>
						<td colspan=""><span class="table_span_right">备注：</span></td>
						<td colspan="3"><span class="table_span_left"><?php echo mb_substr($data->demo,0,50)."...";?></span></td>
					</tr>
				</table>
			</div>
		</div>

		<div class="wuliu_right_v2 f-fr">
			<div class="kuaisu_v2">
                 {{ partial('index/sc_navs') }}
			</div>
		</div>
	</div>
</div>
 
{{ partial('layouts/footer') }}
</body>
</html>
<script type="text/javascript">
function showImg(o, carid) {
    $('#phone_img_'+carid).show();
    $(o).parent().hide();
}
function showendImg(o, carid){
    $('#endphone_img_'+carid).show();
    $(o).parent().hide();
}
</script>
